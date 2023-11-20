<?php
class MakingChargeModel extends MasterModel{
    private $stockTrans = "stock_transaction";

    public function getApprovedInwardList($data){
        $queryData['tableName'] = $this->stockTrans;  
		$queryData['select'] = "stock_transaction.*,item_master.item_code,item_master.item_name,item_master.hsn_code,item_master.gst_per,item_master.unit_id,unit_master.unit_name";

		$queryData['leftJoin']['item_master'] = "item_master.id = stock_transaction.item_id";
        $queryData['leftJoin']['unit_master'] = "item_master.unit_id = unit_master.id";
        
        if(!empty($data['ref_date'])):
		    $queryData['where']['stock_transaction.ref_date'] = $data['ref_date']; 
        else:
            $queryData['where']['stock_transaction.stock_type'] = "NEW"; 
        endif;

        if(!empty($data['party_id'])):
		    $queryData['where']['stock_transaction.party_id'] = $data['party_id']; 
        endif;
        
	    $result = $this->masterModel->rows($queryData); 
        return $result;
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            foreach($data['itemData'] as $row):
                $row['stock_type'] = "FRESH";
                $result = $this->store($this->stockTrans,$row);
            endforeach;

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return ['status'=>1,'message'=>'Charges saved successfully.'];
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }
}
?>