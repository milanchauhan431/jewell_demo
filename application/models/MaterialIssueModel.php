<?php
class MaterialIssueModel extends MasterModel{
    private $transMain = "trans_main";
    private $transChild = "trans_child";
    private $stockTrans = "stock_transaction";

    public function getDTRows($data){
        $data['tableName'] = $this->transMain;
        $data['select'] = "trans_main.id,trans_main.trans_number,trans_main.trans_date,trans_main.vou_acc_id as collected_by,cb.emp_name as collected_by_name,trans_main.sales_executive,se.emp_name as sales_executive_name, trans_main.is_approve, trans_main.trans_status, tc.total_issue, tc.total_return, tc.total_sold";

        $data['leftJoin']['employee_master as cb'] = "cb.id = trans_main.vou_acc_id";
        $data['leftJoin']['employee_master as se'] = "se.id = trans_main.sales_executive";
        $data['leftJoin']['(SELECT trans_main_id, COUNT(id) as total_issue, SUM(IF(trans_status = 1,1,0)) as total_return, SUM(IF(trans_status = 2,1,0)) as total_sold FROM trans_child WHERE is_delete = 0 AND entry_type = '.$this->data['entryData']->id.' GROUP BY trans_main_id) as tc'] = "tc.trans_main_id = trans_main.id";

        $data['where']['trans_main.entry_type'] = $this->data['entryData']->id;

        if($data['trans_status'] == 0):
            $data['where']['trans_main.trans_status'] = $data['trans_status'];
            $data['where']['trans_main.trans_date <='] = $this->endYearDate;
        elseif($data['trans_status'] == 1):
            $data['where']['trans_main.trans_status'] = $data['trans_status'];
            $data['where']['trans_main.trans_date >='] = $this->startYearDate;
            $data['where']['trans_main.trans_date <='] = $this->endYearDate;
        endif;

        $data['order_by']['trans_main.id'] = "DESC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "trans_main.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(trans_number.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "cb.emp_name";
        $data['searchCol'][] = "se.emp_name";
        $data['searchCol'][] = "tc.total_issue";
        $data['searchCol'][] = "tc.total_return";
        $data['searchCol'][] = "tc.total_sold";
        $data['searchCol'][] = "(tc.total_issue-tc.total_return-tc.total_sold)";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
		return $this->pagingRows($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();
            
            $data['id'] = "";
            $data['trans_no'] = $this->transMainModel->nextTransNo($data['entry_type']);
            $data['trans_number'] = $data['trans_prefix'].$data['trans_no'];
            $data['trans_date'] = getFyDate();
            $toLocation = $data['feasibility_by'];
            $itemData = $data['itemData'];
            unset($data['itemData'],$data['feasibility_by']);

            $result = $this->store($this->transMain,$data);

            foreach($itemData as $row):
                $row['entry_type'] = $data['entry_type'];
                $row['trans_main_id'] = $result['id'];
                $row['feasibility_by'] = $toLocation;
                $row['is_delete'] = 0;
                $this->store($this->transChild,$row);

                $this->edit($this->stockTrans,['id'=>$row['ref_id']],['location_id'=>$toLocation]);
            endforeach;
            

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getIssueItems($data){
        $queryData = array();
        $queryData['tableName'] = $this->transChild;
        $queryData['select'] = "trans_child.*";
        $queryData['where']['trans_child.trans_main_id'] = $data['id'];
        $result = $this->rows($queryData);
        return $result;
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $queryData = array();
            $queryData['tableName'] = $this->transMain;
            $queryData['where'] = $id;
            $issueData = $this->row($queryData);

            if($issueData->is_approve > 0):
                return ['status'=>0,'message'=>'Material Accepted BY Sales Executive. You can not reverse it.'];
            endif;

            $transList = $this->getIssueItems(['id'=>$id]);

            foreach($transList as $row):
                $this->edit($this->stockTrans,['id'=>$row->ref_id],['location_id'=>$row->initiate_by]);
                $this->trash($this->transChild,['id'=>$row->id]);
            endforeach;

            $result = $this->trash($this->transMain,['id'=>$id]);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getIssueList(){
        $queryData = array();
        $queryData['tableName'] = $this->transMain;
        $queryData['select'] = "trans_main.id,trans_main.trans_number,trans_main.trans_date,trans_main.vou_acc_id as collected_by,cb.emp_name as collected_by_name, tc.total_issue";

        $queryData['leftJoin']['employee_master as cb'] = "cb.id = trans_main.vou_acc_id";
        $queryData['leftJoin']['(SELECT trans_main_id, SUM(IF(trans_status = 0,1,0)) as total_issue FROM trans_child WHERE is_delete = 0 AND entry_type = '.$this->data['entryData']->id.' GROUP BY trans_main_id) as tc'] = "tc.trans_main_id = trans_main.id";

        $queryData['where']['trans_main.entry_type'] = $this->data['entryData']->id;
        $queryData['where']['trans_main.sales_executive'] = $this->loginId;
        $queryData['where']['trans_main.is_approve'] = 0;

        $result = $this->rows($queryData);
        return $result;
    }

    public function saveAcceptMaterial($data){
        try{
            $this->db->trans_begin();

            $postData = [
                'id' => $data['id'],
                'is_approve' => $this->loginId,
                'ship_bill_date' => date("Y-m-d H:i:s")
            ];
            $result = $this->store($this->transMain,$postData);
            $result['message'] = "Material Accepted successfully.";

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getIssueItemListForReturn($data){
        $itemList = $this->getIssueItems($data);
        

        $itemData = array();
        foreach($itemList as $row):
            $stock = $this->itemStock->getStockTrans(['unique_id'=>$row->item_desc]);
            $row->sold_status = (!empty($stock) && $stock->qty <= 0)?"SOLD":"";
            $itemData[] = $row;
        endforeach;
        return $itemData;
    }

    public function saveReturnMaterial($data){
        try{
            $this->db->trans_begin();
            
            foreach($data['itemData'] as $row):
                $stData = array();
                $stData = $row['stData']; unset($row['stData']);
                
                if(!isset($row['trans_status'])):
                    $row['trans_status'] = ($row['item_remark'] == "SOLD")?2:0;
                endif;

                $this->store($this->transChild,$row);

                if($row['trans_status'] == 1):
                    $this->store($this->stockTrans,$stData);
                endif;
            endforeach;

            $setData = array();
            $setData['tableName'] = $this->transMain;
            $setData['where']['id'] = $data['id'];
            $setData['update']['trans_status'] = "(SELECT IF( COUNT(id) = SUM(IF(trans_status <> 0, 1, 0)) ,1 , 0 ) as trans_status FROM trans_child WHERE trans_main_id = ".$data['id']." AND is_delete = 0)";
            $this->setValue($setData);

            $result['status'] = 1;
            $result['message'] = "Material Returned successfully.";

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getSalesReturnItems(){
        $queryData = array();
        $queryData['tableName'] = $this->stockTrans;
        $queryData['select'] = "stock_transaction.*,item_master.item_code,item_master.item_name";
        $queryData['leftJoin']['item_master'] = "stock_transaction.item_id = item_master.id";
        $queryData['where']['stock_transaction.stock_type'] = "RETURN";
        $result = $this->rows($queryData);
        return $result;
    }

    public function saveSalesReturnMaterial($data){
        try{
            $this->db->trans_begin();
            
            foreach($data['itemData'] as $row):
                if(isset($row['trans_status']) && $row['trans_status'] == 1):
                    unset($row['trans_status']);
                    $row['location_id'] = $data['location_id'];
                    $row['stock_type'] = "FRESH";
                    $this->store($this->stockTrans,$row);
                endif;
            endforeach;

            $result['status'] = 1;
            $result['message'] = "Material Returned successfully.";

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }
}
?>