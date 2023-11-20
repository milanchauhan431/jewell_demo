<?php 
class PaymentVoucherModel extends MasterModel{
	private $transMain = "trans_main";
    private $transDetails = "trans_details";
	private $transLedger = "trans_ledger";

	public function getDtRows($data){
		$data['tableName'] = $this->transMain;
		$data['select'] = "trans_main.*,opp_acc.party_name as opp_acc_name,vou_acc.party_name as vou_acc_name";

        $data['leftJoin']['party_master as opp_acc'] = "opp_acc.id = trans_main.opp_acc_id";
        $data['leftJoin']['party_master as vou_acc'] = "vou_acc.id = trans_main.vou_acc_id";

        if(!empty($data['vou_name_s'])):
            $data['where']['vou_name_s'] = $data['vou_name_s'];
        endif;
		
        $data['where']['trans_main.trans_date >='] = $this->startYearDate;
        $data['where']['trans_main.trans_date <='] = $this->endYearDate;

        $data['order_by']['trans_main.id'] = "DESC";
        $data['order_by']['trans_main.trans_no'] = "DESC";
		
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "trans_main.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "opp_acc.party_name";
        $data['searchCol'][] = "vou_acc.party_name";
        $data['searchCol'][] = "trans_main.net_amount";
        $data['searchCol'][] = "trans_main.doc_no";
        $data['searchCol'][] = "trans_main.doc_date";
        $data['searchCol'][] = "trans_main.remark";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
	}

    /* public function getPartyInvoiceList($data){
        $queryData['tableName'] = $this->transMain;
        $queryData['select'] = "id,trans_number,(net_amount - rop_amount) as due_amount";
        $queryData['where']['party_id'] = $data['party_id'];
        $queryData['where']['vou_name_s'] = $data['vou_name_s'];
        if(empty($data['ref_id'])):
            $queryData['where']['(net_amount - rop_amount) >'] = 0;
        else:
            $queryData['cusomWhere'][] = "((net_amount - rop_amount) > 0 OR id = ".$data['ref_id'].")";
        endif;
        return $this->rows($queryData);
    } */

    public function getPartyInvoiceList($data){
        $queryData['tableName'] = $this->transMain;
        $queryData['select'] = "trans_main.id,trans_main.trans_number,(trans_main.net_amount - trans_main.rop_amount) as due_amount";

        if(!empty($data['ref_id'])):
            $queryData['select'] = "trans_main.id,trans_main.trans_number,(trans_main.net_amount - (trans_main.rop_amount - ifnull(blr.amount,0))) as due_amount";

            $queryData['leftJoin']["(SELECT i_col_1 as inv_id, SUM(d_col_1) as amount FROM trans_details WHERE table_name = 'trans_main' AND description = 'PAYMENT BILL WISE DETAILS' AND is_delete = 0 GROUP BY i_col_1) as blr"] = "trans_main.id = blr.inv_id";
        endif;

        if(!empty($data['party_id'])):
            $queryData['where']['trans_main.party_id'] = $data['party_id'];
        endif;

        if(!empty($data['vou_name_s'])):
            $queryData['where_in']['trans_main.vou_name_s'] = $data['vou_name_s'];
        endif;

        if(empty($data['ref_id'])):
            $queryData['where']['(trans_main.net_amount - trans_main.rop_amount) >'] = 0;
        elseif(!empty($data['ref_id'])):
            $queryData['cusomWhere'][] = "((trans_main.net_amount - trans_main.rop_amount) > 0 OR trans_main.id IN (".$data['ref_id']."))";
        endif;

        if(!empty($data['only_ref_id'])):
            $queryData['where_in']['trans_main.id'] = $data['only_ref_id'];
        endif;

        return $this->rows($queryData);
    }

    public function getPaymentRefDetails($data){
        $queryData = array();
        $queryData['tableName'] = $this->transDetails;
        $queryData['select'] = "id,main_ref_id,i_col_1 as inv_id,d_col_1 as amount";
        $queryData['where']['table_name'] = $this->transMain;
        $queryData['where']['description'] = "PAYMENT BILL WISE DETAILS";
        $queryData['where']['main_ref_id'] = $data['id'];
        return $this->rows($queryData);
    }

	public function save($data){
		try{
			$this->db->trans_begin();

            if(empty($data['id'])):
                if($data['vou_name_s'] == "BCRct"):
                    $data['trans_prefix'] = $this->data['entryData']->trans_prefix;
                else:
                    $data['trans_prefix'] = str_replace("R","P",$this->data['entryData']->trans_prefix);
                endif;
                
                $data['trans_no'] = $this->transMainModel->nextTransNo($this->data['entryData']->id,0,$data['vou_name_s']);
                $data['trans_number']	= $data['trans_prefix'].$data['trans_no'];
            endif;

            if(!empty($data['id'])):
                $vouData = $this->getVoucher($data['id']);
                if(!empty($vouData->ref_id)):
                    $refList = $this->getPaymentRefDetails(['id'=>$data['id']]);
                    foreach($refList as $row):
                        $setData = array();
                        $setData['tableName'] = $this->transMain;
                        $setData['where']['id'] = $row->inv_id;
                        $setData['set']['rop_amount'] = 'rop_amount, - '.$row->amount;
                        $this->setValue($setData);

                        $this->remove($this->transDetails,['id'=>$row->id]);
                    endforeach;

                    /* $setData = array();
                    $setData['tableName'] = $this->transMain;
                    $setData['where']['id'] = $vouData->ref_id;
                    $setData['set']['rop_amount'] = 'rop_amount, - '.$vouData->net_amount;
                    $this->setValue($setData); */
                endif;
            endif;

			$data['doc_date'] = (!empty($data['doc_date']))?$data['doc_date']:null;

			$result = $this->store($this->transMain,$data,'Voucher');
			$data['id'] = $result['id'];	

            if(!empty($data['ref_id'])):
                $invoiceList = $this->getPartyInvoiceList(['only_ref_id'=>$data['ref_id']]);
                
                $totalAmount = $data['net_amount'];
                foreach($invoiceList as $row):
                    $dueAmt = ($totalAmount > $row->due_amount)?$row->due_amount:$totalAmount;

                    $setData = array();
                    $setData['tableName'] = $this->transMain;
                    $setData['where']['id'] = $row->id;
                    $setData['set']['rop_amount'] = 'rop_amount, + '.$dueAmt;
                    $this->setValue($setData);

                    $totalAmount -= $dueAmt;

                    $refDetails = [
                        'id' => '',
                        'table_name' => $this->transMain,
                        'description' => 'PAYMENT BILL WISE DETAILS',
                        'main_ref_id' => $result['id'],
                        'i_col_1' => $row->id, // invoice id
                        'd_col_1' => $dueAmt // received/paid amount against invoice
                    ];
                    $this->store($this->transDetails,$refDetails);

                    if($totalAmount <= 0): break; endif;
                endforeach;

                /* $setData = array();
                $setData['tableName'] = $this->transMain;
                $setData['where']['id'] = $data['ref_id'];
                $setData['set']['rop_amount'] = 'rop_amount, + '.$data['net_amount'];
                $this->setValue($setData); */
            endif;

			$this->transMainModel->ledgerEffects($data);

			if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
		}catch(\Throwable $e){
            $this->db->trans_rollback();
			return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
	}

	public function getVoucher($id){
        $data['tableName'] = $this->transMain;
		$data['select'] = "trans_main.*,party_master.party_name, reference.trans_no as inv_no, reference.trans_prefix as inv_prefix, reference.trans_date as inv_date";
		$data['leftJoin']['party_master'] = "party_master.id = trans_main.party_id";
		$data['leftJoin']['trans_main as reference'] = "reference.id = trans_main.ref_id";
        $data['where']['trans_main.id'] = $id;
        return $this->row($data);
    }
    
    public function getVoucherDetails($id){
        $queryData = array();
        $queryData['tableName'] = $this->transMain;
		$queryData['select'] = "trans_main.*,party_master.party_name,employee_master.emp_name as created_name";
		$queryData['leftJoin']['party_master'] = "party_master.id = trans_main.party_id";
		$queryData['leftJoin']['employee_master'] = "employee_master.id = trans_main.created_by";
        $queryData['where']['trans_main.id'] = $id;
        $result =  $this->row($queryData);

        /* $queryData = array();
        $queryData['tableName'] = $this->transDetails;
        $queryData['select'] = "trans_details.id,trans_details.d_col_1 as adjust_amount,trans_main.trans_number,trans_main.trans_date,trans_main.net_amount,trans_main.rop_amount";
        $queryData['leftJoin']['trans_main'] = "trans_details.i_col_1 = trans_main.id";
        $queryData['where']['trans_details.table_name'] = $this->transMain;
        $queryData['where']['trans_details.description'] = "PAYMENT BILL WISE DETAILS";
        $queryData['where']['trans_details.main_ref_id'] = $id;
        $result->invoiceRef = $this->rows($queryData); */

        $queryData = array();
        $queryData['tableName'] = $this->transDetails;
        $queryData['select'] = "trans_details.i_col_1,trans_main.trans_number,trans_main.trans_date,trans_main.net_amount,trans_main.vou_name_s";
        $queryData['leftJoin']['trans_main'] = "trans_details.i_col_1 = trans_main.id";
        $queryData['where']['trans_details.table_name'] = $this->transMain;
        $queryData['where']['trans_details.description'] = "PAYMENT BILL WISE DETAILS";
        $queryData['where']['trans_details.main_ref_id'] = $id;
        $queryData['order_by']['trans_details.i_col_1'] = "ASC";
        $queryData['order_by']['trans_main.trans_date'] = "ASC";
        $invoiceRef = $this->rows($queryData);

        $payments = array();
        foreach($invoiceRef as $row):
            $payments[$row->i_col_1] = [
                'id' => $row->i_col_1,
                'trans_number' => $row->trans_number,
                'trans_date' => $row->trans_date,
                'net_amount' => $row->net_amount,
                'vou_name_s' => $row->vou_name_s
            ];

            $queryData = array();
            $queryData['tableName'] = $this->transDetails;
            $queryData['select'] = "trans_main.id,trans_main.vou_name_s,trans_main.trans_number,trans_main.trans_date,trans_main.net_amount";
            $queryData['leftJoin']['trans_main'] = "trans_details.main_ref_id = trans_main.id";
            $queryData['where']['trans_details.table_name'] = $this->transMain;
            $queryData['where']['trans_details.description'] = "PAYMENT BILL WISE DETAILS";
            $queryData['where']['trans_details.i_col_1'] = $row->i_col_1;
            $queryData['where']['trans_details.main_ref_id <= '] = $id;
            $queryData['order_by']['trans_main.id'] = "ASC";
            $queryData['order_by']['trans_main.trans_date'] = "ASC";
            $paymentRef = $this->rows($queryData);

            foreach($paymentRef as $row):
                $payments[$row->id] = [
                    'id' => $row->id,
                    'trans_number' => $row->trans_number,
                    'trans_date' => $row->trans_date,
                    'net_amount' => $row->net_amount,
                    'vou_name_s' => $row->vou_name_s
                ];
            endforeach;
        endforeach;

        array_multisort(array_column($payments, 'id'), SORT_ASC, $payments);
        //array_multisort(array_column($payments, 'trans_number'), SORT_ASC, $payments);

        $result->invoiceRef = (!empty($payments))?(object)$payments:$payments;
        //print_r($result->invoiceRef);exit;

        return $result;
    }

	public function delete($id){
		try{
			$this->db->trans_begin();

            $vouData = $this->getVoucher($id);
            if(!empty($vouData->ref_id)):
                $refList = $this->getPaymentRefDetails(['id'=>$id]);
                foreach($refList as $row):
                    $setData = array();
                    $setData['tableName'] = $this->transMain;
                    $setData['where']['id'] = $row->inv_id;
                    $setData['set']['rop_amount'] = 'rop_amount, - '.$row->amount;
                    $this->setValue($setData);

                    $this->remove($this->transDetails,['id'=>$row->id]);
                endforeach;

                /* $setData = array();
                $setData['tableName'] = $this->transMain;
                $setData['where']['id'] = $vouData->ref_id;
                $setData['set']['rop_amount'] = 'rop_amount, - '.$vouData->net_amount;
                $this->setValue($setData); */
            endif;
			
			$result= $this->trash($this->transMain,['id'=>$id],'Voucher');
			$this->transMainModel->deleteLedgerTrans($id);

			if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
		}catch(\Throwable $e){
            $this->db->trans_rollback();
			return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
	}

    public function getBankTransactions($data){
        $queryData = array();
        $queryData['tableName'] = $this->transMain;
        $queryData['select'] = "trans_main.id,trans_main.trans_number,trans_main.trans_date,opp_acc.party_name as opp_acc_name,vou_acc.party_name as vou_acc_name,trans_main.doc_no,trans_main.doc_date,trans_main.payment_mode,trans_main.net_amount,trans_main.recon_date";

        $queryData['leftJoin']['party_master as opp_acc'] = "opp_acc.id = trans_main.opp_acc_id";
        $queryData['leftJoin']['party_master as vou_acc'] = "vou_acc.id = trans_main.vou_acc_id";
        $queryData['where']['trans_main.payment_mode !='] = "CASH";
        $queryData['where']['trans_main.trans_date >='] = $data['from_date'];
        $queryData['where']['trans_main.trans_date <='] = $data['to_date'];
        $queryData['where_in']['trans_main.entry_type'] = $this->data['entryData']->id;
        
        if(!empty($data['acc_id'])):
            $queryData['where']['trans_main.vou_acc_id'] = $data['acc_id'];
        endif;

        if($data['status'] != -1):
            if($data['status'] == 0):
                $queryData['customWhere'][] = 'trans_main.recon_date IS NULL'; 
            else:
                $queryData['customWhere'][] = 'trans_main.recon_date IS NOT NULL'; 
            endif;
        endif;

        $queryData['order_by']['trans_main.trans_date'] = "ASC";

        $result = $this->rows($queryData);
        return $result;
    }

    public function saveBankReconciliation($data){
        try{
            $this->db->trans_begin();

            foreach($data['item_data'] as $row):
                if(!empty($row['recon_date'])):
                    $this->edit($this->transMain,['id'=>$row['id']],['recon_date'=>$row['recon_date']]);
                    $this->edit($this->transLedger,['trans_main_id'=>$row['id']],['recon_date'=>$row['recon_date']]);
                endif;
            endforeach;         
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return ['status'=>1,'message'=>"Bank Reconciliation saved successfully."];
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
}
?>