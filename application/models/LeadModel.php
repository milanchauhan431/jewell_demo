<?php
class LeadModel extends MasterModel{
    private $appointmentTable = "crm_appointments";
    private $partyMaster = "party_master";
    private $lead_managment='lead_managment';
    private $transMain = "trans_main";
    private $transChild = "trans_child";

    public function getDTRows($data){
		$data['tableName'] = $this->lead_managment;
        $data['select'] ="lead_managment.*,party_master.party_name,employee_master.emp_name,party_master.party_phone";
        $data['join']['party_master'] = "party_master.id = lead_managment.party_id AND party_master.is_delete = 0";
        $data['leftJoin']['employee_master'] = "employee_master.id = lead_managment.sales_executive";

        $data['where']['lead_managment.lead_status'] = $data['lead_status'];
		
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "lead_managment.lead_date";
        $data['searchCol'][] = "lead_managment.lead_no";
        $data['searchCol'][] = "lead_managment.lead_from";
        $data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "party_master.party_phone";
        $data['searchCol'][] = "employee_master.emp_name";
        if($data['lead_status'] == 0):
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
        elseif($data['lead_status'] == 3):
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
        elseif($data['lead_status'] == 4):
            $data['searchCol'][] = "lead_managment.reason";
        endif;
        
        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
	}

    public function getNextLeadNo(){
        $data['tableName'] = $this->lead_managment;
        $data['select'] = "MAX(lead_no) as lead_no";
        $maxNo = $this->row($data)->lead_no;
		$nextNo = (!empty($maxNo))?($maxNo + 1):1;
		return $nextNo;
    }
    
    public function saveLead($data){
        try{
            $this->db->trans_begin();
            $leadNo = $this->getNextLeadNo();
            $leadData = [
                'id'=>$data['id'],
                'party_id'=>$data['party_id'],
                'lead_date'=>$data['lead_date'],
                'sales_executive'=>$data['sales_executive'],
                'mode'=>$data['mode'],
                'lead_no'=>$leadNo,
                'lead_status'=>$data['status'],
                'lead_from'=>$data['lead_from'],
                'next_fup_date'=>$data['next_fup_date']
            ];
            $result = $this->store($this->lead_managment,$leadData);
            
            if(empty($data['id'])):
                $data['lead_id'] = $result['id'];
                $data['entry_type'] = 1;
                $data['appointment_date'] = $data['lead_date'];
                $data['next_fup_date'] = $data['next_fup_date'];
                unset($data['lead_date'],$data['lead_from']);
                $this->saveFollowup($data);
            endif;

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function saveApproachStatus($data){
        try{
            $this->db->trans_begin();

            $entry_type = $data['entry_type'];

            if($entry_type == 4):
                $dataRow = $this->salesQuotation->getSalesQuotation(['id'=>$data['id'],'itemList'=>1]);
                $mainData = [
                    'id' => $data['id'],
                    'is_approve' => $this->loginId,
                    'approve_date' => date("Y-m-d"),
                    'close_reason' => $data['reason'],
                    'trans_status' => ($data['lead_status'] == 4)?2:0,
                ];
                $result = $this->store($this->transMain,$mainData,'Sales Quotation');

                $this->edit($this->transChild,['trans_main_id'=>$data['id']],['confirm_status'=>(($data['lead_status'] == 3)?2:3),'confirm_by'=>$this->loginId,'trans_status'=>($data['lead_status'] == 4)?2:0]);

                if($data['lead_status'] == 3):
                    $this->edit($this->partyMaster,['id'=>$dataRow->party_id],['party_type'=>1]);
                endif;

                if(!empty($dataRow->vou_acc_id)):
                    $leadData = [
                        'id' => $dataRow->vou_acc_id,
                        'reason' => $data['reason'],
                        'lead_status' => $data['lead_status'],
                    ];
                    $this->store($this->lead_managment,$leadData,'Status');
                endif;

                $result['message'] = "Sales Quotation ".(($data['lead_status'] == 3)?"Approved":"Closed")." successfully.";
            else:
                if($data['lead_status'] == 3):
                    $leadData = $this->getLead($data['id']);
                    $this->edit($this->partyMaster,['id'=>$leadData->party_id],['party_type'=>1]);
                endif;

                unset($data['entry_type']);
                $result = $this->store($this->lead_managment,$data,'Status');
                $result['message'] = "Lead ".(($data['lead_status'] == 3)?"won":"lost")." successfully.";
            endif;

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function saveFollowup($data){ 
        try{
            $this->db->trans_begin();

            $result = $this->store($this->appointmentTable,$data,'Followup');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function setAppointment($data){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->appointmentTable,$data,'Appointment');
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }			
    }

    public function deleteApproachTans($id){
        try{
            $this->db->trans_begin();
            
            $result = $this->trash($this->appointmentTable,['id'=>$id]);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getLead($id){
        $data['select'] = "lead_managment.*,party_master.party_name,employee_master.emp_name as sales_executive_name";
        $data['leftJoin']['party_master'] = "party_master.id = lead_managment.party_id";
        $data['leftJoin']['employee_master'] = "employee_master.id = lead_managment.sales_executive";
        $data['where']['lead_managment.id'] = $id;
        $data['tableName'] = $this->lead_managment;
        return $this->row($data);
    }    

    public function getFollowupData($postData){
        $data['tableName'] = $this->appointmentTable;
        $data['where']['crm_appointments.entry_type'] = $postData['entry_type'];
        $data['where']['crm_appointments.lead_id'] = $postData['lead_id'];
        $data['limit'] = 1;
        $data['order_by']['crm_appointments.appointment_date'] = "DESC";
        $data['order_by']['crm_appointments.id'] = "DESC";
        return $this->row($data);
    }

    public function getAppointments($postData){
        $data['tableName'] = $this->appointmentTable;
        $data['select']='crm_appointments.*,employee_master.emp_name as executive_name,lead_managment.lead_no,party_master.party_name';
        $data['leftJoin']['employee_master'] = 'employee_master.id = crm_appointments.sales_executive';
        $data['leftJoin']['lead_managment'] = "lead_managment.id = crm_appointments.lead_id";
        $data['leftJoin']['party_master'] = "party_master.id = crm_appointments.party_id";
        $data['where']['lead_id'] = $postData['lead_id'];
        $data['where']['entry_type'] = $postData['entry_type'];
        if(isset($postData['status'])){$data['where']['crm_appointments.status'] = $postData['status'];}
        return $this->rows($data);
    }

    public function getAppointmentDetail($id){
        $data['tableName'] = $this->appointmentTable;
        $data['select']='crm_appointments.*,employee_master.emp_name as executive_name,lead_managment.lead_no,lead_managment.lead_status,party_master.party_name';
        $data['leftJoin']['employee_master'] = 'employee_master.id = crm_appointments.sales_executive';
        $data['leftJoin']['lead_managment'] = "lead_managment.id = crm_appointments.lead_id";
        $data['leftJoin']['party_master'] = "party_master.id = lead_managment.party_id";
        $data['where']['crm_appointments.id'] = $id;
        return $this->row($data);
    }
}
?>