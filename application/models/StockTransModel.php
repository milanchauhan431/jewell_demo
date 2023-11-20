<?php
class StockTransModel extends MasterModel{
    private $stockTrans = "stock_transaction";

    public function getDTRows($data){
        $data['tableName'] = $this->stockTrans;
        $data['select'] = "stock_transaction.*,item_master.item_code,item_master.item_name";

        $data['leftJoin']['item_master'] = "item_master.id = stock_transaction.item_id";

        $data['where']['stock_transaction.p_or_m'] = 1;
        $data['where']['stock_transaction.ref_date >='] = $this->startYearDate;
        $data['where']['stock_transaction.ref_date <='] = $this->endYearDate;
        
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['serachCol'][] = "DATE_FORMAT(stock_transaction.ref_date,'%d-%m-%Y')";
        $data['searchCol'][] = "item_master.item_code";
        $data['serachCol'][] = "item_master.item_name";
        $data['serachCol'][] = "stock_transaction.qty";
        $data['serachCol'][] = "stock_transaction.size";
        $data['serachCol'][] = "stock_transaction.remark";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }


    public function save($data){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->stockTrans,$data,'Stock');
        
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $transData = $this->getStockTrans(['id'=>$id]);
            $itemStock = $this->getItemCurrentStock(['item_id'=>$transData->item_id]);
            if($transData->qty > $itemStock->qty):
                return ['status'=>0,'message'=>'Item Stock Used. You cant delete this record.'];
            endif;

            $result = $this->trash($this->stockTrans,['id'=>$id],'Stock');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getStockTrans($data){
        $queryData['tableName'] = $this->stockTrans;
        $queryData['select'] = "stock_transaction.*,SUM(stock_transaction.qty * stock_transaction.p_or_m) as qty,item_master.item_code,item_master.item_name,item_master.hsn_code,item_master.gst_per,item_master.unit_id,unit_master.unit_name";

        $queryData['leftJoin']['item_master'] = "item_master.id = stock_transaction.item_id";
        $queryData['leftJoin']['unit_master'] = "item_master.unit_id = unit_master.id";

        if(!empty($data['id']))
            $queryData['where']['stock_transaction.id'] = $data['id'];
        if(!empty($data['unique_id']))
            $queryData['where']['stock_transaction.unique_id'] = $data['unique_id'];

        if(!empty($data['stock_required'])):
            $queryData['where']['stock_transaction.stock_type'] = "FRESH";
            $queryData['having'][] = 'SUM(stock_transaction.qty * stock_transaction.p_or_m) > 0';
        endif;
        
        return  $this->row($queryData);        
    }

    // Get Single Item Stock From Stock Transaction
    public function getItemCurrentStock($data){
        $queryData['tableName'] = $this->stockTrans;
        $queryData['select'] = "SUM(qty * p_or_m) as qty";
        $queryData['where']['item_id'] = $data['item_id'];
        return $this->row($queryData);
    }

    /* Created At : 09-12-2022 [Milan Chauhan] */
    public function getItemStockBatchWise($data){
        $queryData['tableName'] = $this->stockTrans;
        $queryData['select'] = "stock_transaction.item_id, item_master.item_code, item_master.item_name, SUM(stock_transaction.qty * stock_transaction.p_or_m) as qty, stock_transaction.unique_id, stock_transaction.batch_no,  stock_transaction.location_id, lm.location, lm.store_name";
        
        $queryData['leftJoin']['location_master as lm'] = "lm.id=stock_transaction.location_id";
        $queryData['leftJoin']['item_master'] = "stock_transaction.item_id = item_master.id";

        if(!empty($data['item_id'])): 
            $queryData['where']['stock_transaction.item_id'] = $data['item_id'];           
        endif;

        if(!empty($data['location_id'])):
            $queryData['where']['stock_transaction.location_id'] = $data['location_id'];
        endif;

        if(!empty($data['batch_no'])):
            $queryData['where']['stock_transaction.batch_no'] = $data['batch_no'];
        endif;
        
        if(!empty($data['p_or_m'])):
            $queryData['where']['stock_transaction.p_or_m'] = $data['p_or_m'];
        endif;

        if(!empty($data['entry_type'])):
            $queryData['where']['stock_transaction.entry_type'] = $data['entry_type'];
        endif;

        if(!empty($data['main_ref_id'])):
            $queryData['where']['stock_transaction.main_ref_id'] = $data['main_ref_id'];
        endif;

        if(!empty($data['child_ref_id'])):
            $queryData['where']['stock_transaction.child_ref_id'] = $data['child_ref_id'];
        endif;

        if(!empty($data['ref_no'])):
            $queryData['where']['stock_transaction.ref_no'] = $data['ref_no'];
        endif;

        if(!empty($data['customWhere'])):
            $queryData['customWhere'][] = $data['customWhere'];
        endif;
        
        if(!empty($data['stock_required'])):
            $queryData['having'][] = 'SUM(stock_transaction.qty * stock_transaction.p_or_m) > 0';
        endif;

        //$queryData['where']['lm.final_location'] = 0;
        $queryData['group_by'][] = "stock_transaction.unique_id";
        $queryData['order_by']['lm.location'] = "ASC";

        if(isset($data['single_row']) && $data['single_row'] == 1):
            $stockData = $this->row($queryData);
        else:
            $stockData = $this->rows($queryData);
        endif;
        return $stockData;
    }

}
?>