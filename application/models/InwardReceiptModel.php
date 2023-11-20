<?php
class InwardReceiptModel extends MasterModel{
    private $inwardReceipt = "inward_receipt";
    private $stockTransaction = "stock_transaction";

    public function getDTRows($data){
        $data['tableName'] = $this->inwardReceipt;
        $data['select'] = "inward_receipt.id,inward_receipt.trans_number,inward_receipt.trans_date,inward_receipt.party_id,party_master.party_name,inward_receipt.item_id,item_master.item_code,item_master.item_name,inward_receipt.design_no,inward_receipt.qty,inward_receipt.gross_weight,inward_receipt.net_weight,inward_receipt.purchase_price,inward_receipt.sales_price,inward_receipt.remark,inward_receipt.approved_by";

        $data['leftJoin']['party_master'] = "inward_receipt.party_id = party_master.id";
        $data['leftJoin']['item_master'] = "inward_receipt.item_id = item_master.id";

        if($data['status'] == 0):
            $data['where']['inward_receipt.approved_by'] = 0;
            $data['where']['inward_receipt.trans_date <='] = $this->endYearDate;
        elseif($data['status'] == 1):
            $data['where']['inward_receipt.approved_by >'] = 0;
            $data['where']['inward_receipt.trans_date >='] = $this->startYearDate;
            $data['where']['inward_receipt.trans_date <='] = $this->endYearDate;
        endif;

        $data['order_by']['inward_receipt.trans_date'] = "DESC";
        $data['order_by']['inward_receipt.id'] = "DESC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "inward_receipt.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(inward_receipt.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "inward_receipt.design_no";
        $data['searchCol'][] = "inward_receipt.qty";
        $data['searchCol'][] = "inward_receipt.gross_weight";
        $data['searchCol'][] = "inward_receipt.net_weight";
        $data['searchCol'][] = "inward_receipt.purchase_price";
        $data['searchCol'][] = "inward_receipt.sales_price";
        $data['searchCol'][] = "inward_receipt.remark";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function getInwardReceipt($data=array()){
        $queryData = array();
        $queryData['tableName'] = $this->inwardReceipt;
        $queryData['where']['id'] = $data['id'];
        return $this->row($queryData);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            if(empty($data['id'])):
                $data['trans_no'] = $this->transMainModel->getNextTransNo(['table_name'=>"inward_receipt"]);
                $data['trans_number'] = $data['trans_prefix'].$data['trans_no'];
            endif;

            $result = $this->store($this->inwardReceipt,$data,'Inward Receipt');

            if(!empty($data['approved_by'])):
                $colorName = "";
                if(!empty($data['color_id'])):
                    $colorData = $this->color->getColor(['id'=>$data['color_id']]);
                    $colorName = $colorData->color;
                endif;

                $stockTransData = [
                    'id' => '',
                    'entry_type' => $data['entry_type'],
                    'ref_date' => $data['trans_date'],
                    'ref_no' => $data['trans_number'],
                    'main_ref_id' => $result['id'],
                    'location_id' => $data['location_id'],
                    'batch_no' => '',
                    'party_id' => $data['party_id'],
                    'item_id' => $data['item_id'],
                    'p_or_m' => 1,
                    'purity' => $data['purity'],
                    'stock_category' => $data['inward_type'],
                    'standard_qty' => $data['standard_qty'],
                    'gross_weight' => $data['gross_weight'],
                    'gold_weight' => $data['gold_weight'],
                    'net_weight' => $data['net_weight'],
                    'color' => $colorName,
                    'diamond_pcs' => $data['diamond_pcs'],
                    'diamond_carat' => $data['diamond_carat'],
                    /* 'purchase_price' => $data['purchase_price'],
                    'sales_price' => $data['sales_price'], */
                    'stock_type' => "NEW",
                ];

                for($i=1;$i<=$data['qty'];$i++):
                    $random_numbers = mt_rand(1000000000,9999999999);
                    $stockTransData['unique_id'] = $random_numbers;
                    $stockTransData['batch_no'] = $random_numbers;
                    $stockTransData['qty'] = 1;

                    $this->store($this->stockTransaction,$stockTransData);
                endfor;
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
     
	public function reversalApproval($data){
        try{ 
            $this->db->trans_begin();

            $barcodeDetails = $this->getItemSerialNo($data);

            $serialNo = [];
            foreach($barcodeDetails as $row):
                $postData = ['batch_no' => $row->unique_id,'item_id' => $row->item_id,'stock_required'=>1,'single_row'=>1];
                $stockData = $this->itemStock->getItemStockBatchWise($postData);

                if(empty($stockData)):
                    $serialNo[] = $row->unique_id;
                endif;
            endforeach;

            if(!empty($serialNo)):
                return ['status' => 0, 'message' => "Some serial no. item sold. you can not revers this approval. Serial No. : ".implode(", ",$serialNo)];
            endif;

			$data['approved_by']=0;
			$data['approved_at']=NULL;
			$result = $this->store($this->inwardReceipt,$data);	
            
			$this->remove($this->stockTransaction,['entry_type'=>$data['entry_type'],'main_ref_id'=>$data['id']]);

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

            $result = $this->trash($this->inwardReceipt,['id'=>$id],'Inward Receipt');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getItemSerialNo($data){
        $queryData = array();
        $queryData['tableName'] = $this->stockTransaction;
        $queryData['select'] = "stock_transaction.*,item_master.item_code,item_master.item_name,party_master.party_code,party_master.party_name,inward_receipt.diamond_pcs,inward_receipt.diamond_carat,color_master.color,clarity_master.clarity";
        $queryData['leftJoin']['party_master'] = "party_master.id = stock_transaction.party_id";
        $queryData['leftJoin']['item_master'] = "item_master.id = stock_transaction.item_id";
        $queryData['leftJoin']['inward_receipt'] = "inward_receipt.id = stock_transaction.main_ref_id";
        $queryData['leftJoin']['color_master'] = "color_master.id = inward_receipt.color_id";
        $queryData['leftJoin']['clarity_master'] = "clarity_master.id = inward_receipt.clarity_id";

        if(!empty($data['id']))
            $queryData['where']['stock_transaction.main_ref_id'] = $data['id'];
        if(!empty($data['trans_id']))
            $queryData['where_in']['stock_transaction.id'] = $data['trans_id'];

        $queryData['where']['stock_transaction.p_or_m'] = 1;
        $queryData['where']['stock_transaction.entry_type'] = $this->data['entryData']->id;

        $result = $this->rows($queryData);
        return $result;
    }

    public function getPendingInwardItems($data){
        $queryData = array();
        $queryData['tableName'] = $this->inwardReceipt;
        $queryData['select'] = "inward_receipt.*,(inward_receipt.qty - inward_receipt.inv_qty) as pending_qty,inward_receipt.entry_type as main_entry_type,item_master.item_code,item_master.item_name,item_master.item_type,item_master.hsn_code,item_master.gst_per,unit_master.id as unit_id,unit_master.unit_name,'0' as stock_eff,color_master.color";
        $queryData['leftJoin']['item_master'] = "item_master.id = inward_receipt.item_id";
        $queryData['leftJoin']['unit_master'] = "item_master.unit_id = unit_master.id";
        $queryData['leftJoin']['color_master'] = "inward_receipt.color_id = color_master.id";
        $queryData['where']['inward_receipt.party_id'] = $data['party_id'];
        $queryData['where']['inward_receipt.entry_type'] = $this->data['entryData']->id;
        $queryData['where']['(inward_receipt.qty - inward_receipt.inv_qty) >'] = 0;
        return $this->rows($queryData);
    }
}
?>