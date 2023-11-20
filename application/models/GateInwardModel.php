<?php
class GateInwardModel extends masterModel{
    private $mir = "mir";
    private $mirTrans = "mir_transaction";
    private $transMain = "trans_main";
    private $transChild = "trans_child";
    private $stockTrans = "stock_transaction";


    public function getDTRows($data){
        if($data['trans_type'] == 1):
            $data['tableName'] = $this->mir;

            $data['select'] = "mir.id,mir.trans_number,DATE_FORMAT(mir.trans_date,'%d-%m-%Y') as trans_date,mir.qty as no_of_items,party_master.party_name,mir.inv_no,ifnull(DATE_FORMAT(mir.inv_date,'%d-%m-%Y'),'') as inv_date,mir.doc_no,ifnull(DATE_FORMAT(mir.doc_date,'%d-%m-%Y'),'') as doc_date,mir.qty_kg,mir.inward_qty,mir.trans_status,mir.trans_type";

            $data['where']['mir.trans_status'] = $data['trans_status'];
            $data['where']['mir.entry_type'] = $this->data['entryData']->id;
        else:
            $data['tableName'] = $this->mirTrans;

            $data['select'] = "mir.id,mir.trans_number,DATE_FORMAT(mir.trans_date,'%d-%m-%Y') as trans_date,mir.qty as no_of_items,party_master.party_name,item_master.item_name,mir.inv_no,ifnull(DATE_FORMAT(mir.inv_date,'%d-%m-%Y'),'') as inv_date,mir.doc_no,ifnull(DATE_FORMAT(mir.doc_date,'%d-%m-%Y'),'') as doc_date,trans_main.trans_number as po_number,mir.qty_kg,mir.inward_qty,mir_transaction.trans_status,mir.trans_type,mir_transaction.qty,mir_transaction.id as mir_trans_id";

            $data['leftJoin']['mir'] = "mir.id = mir_transaction.mir_id";
            $data['leftJoin']['item_master'] = "item_master.id = mir_transaction.item_id";
            $data['leftJoin']['trans_main'] = "trans_main.id = mir_transaction.po_id";

            $data['where']['mir_transaction.trans_status'] = $data['trans_status'];
            $data['where']['mir_transaction.entry_type'] = $this->data['entryData']->id;
        endif;

        $data['leftJoin']['party_master'] = "party_master.id = mir.party_id";

        
        $data['where']['mir.trans_type'] = $data['trans_type'];
            
        $data['order_by']['mir.id'] = "DESC";

        if($data['trans_type'] == 1):
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "mir.trans_number";
            $data['searchCol'][] = "DATE_FORMAT(mir.trans_date,'%d-%m-%Y')";
            $data['searchCol'][] = "party_master.party_name";
            $data['searchCol'][] = "mir.inv_no";
            $data['searchCol'][] = "ifnull(DATE_FORMAT(mir.inv_date,'%d-%m-%Y'),'')";
            $data['searchCol'][] = "mir.doc_no";
            $data['searchCol'][] = "ifnull(DATE_FORMAT(mir.doc_date,'%d-%m-%Y'),'')";
        else:
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "mir.trans_number";
            $data['searchCol'][] = "DATE_FORMAT(mir.trans_date,'%d-%m-%Y')";
            $data['searchCol'][] = "party_master.party_name";
            $data['searchCol'][] = "item_master.item_name";
            $data['searchCol'][] = "mir_transaction.qty";
            $data['searchCol'][] = "trans_main.trans_number";
        endif;

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
		if (isset($data['order'])) {
			$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];
		}

		return $this->pagingRows($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            if(!empty($data['id'])):
                $gateInwardData = $this->getGateInward($data['id']);

                if(!empty($gateInwardData->ref_id)):
                    $this->store($this->mir,['id'=>$gateInwardData->ref_id,'trans_status'=>0]);
                endif;

                foreach($gateInwardData->itemData as $row):
                    if(!empty($row->po_trans_id)):
                        $setData = array();
                        $setData['tableName'] = $this->transChild;
                        $setData['where']['id'] = $row->po_trans_id;
                        $setData['set']['dispatch_qty'] = 'dispatch_qty, - '.$row->qty;
                        $setData['update']['trans_status'] = 0;
                        $this->setValue($setData);

                        $setData = array();
                        $setData['tableName'] = $this->transMain;
                        $setData['where']['id'] = $row->po_id;
                        $setData['update']['trans_status'] = 0;
                        $this->setValue($setData);
                    endif;

                    $this->trash($this->mirTrans,['id',$row->id]);
                endforeach;
            else:
                $data['trans_no'] = $this->transMainModel->getMirNextNo(2);
                $data['trans_prefix'] = $this->data['entryData']->trans_prefix;//.n2y(getFyDate("Y"));
                $data['trans_number'] = $data['trans_prefix'].sprintf("%04d",$data['trans_no']);
            endif;

            $itemData = $data['batchData'];unset($data['batchData']);

            $data['trans_type'] = 2;$data['entry_type'] = $this->data['entryData']->id;
            $result = $this->store($this->mir,$data,'Gate Inward');

            foreach($itemData as $row):         
                $itemData = $this->item->getItem($row['item_id']);

                $row['mir_id'] = $result['id'];
                $row['location_id'] = $this->RTD_STORE->id;
                $row['entry_type'] = $this->data['entryData']->id;
                $row['type'] = 1;
                $row['is_delete'] = 0;

                if($row['item_stock_type'] == 1):
                    $nextBatchNo = $this->gateReceipt->getNextBatchOrSerialNo(['trans_id'=>$row['id'],'item_id'=>$row['item_id'],'heat_no'=>$row['heat_no']]);

                    $row['batch_no'] = $nextBatchNo['batch_no'];                    
                    $row['serial_no'] = $nextBatchNo['serial_no'];
                elseif($row['item_stock_type'] == 2):
                    $row['batch_no'] = $itemData->item_code.sprintf(n2y(date('Y'))."%03d",$data['trans_no']);
                else:
                    $row['batch_no'] = "GB";
                    $row['serial_no'] = 0;
                endif;

                $this->store($this->mirTrans,$row);

                if(!empty($row['po_trans_id'])):
                    $setData = array();
                    $setData['tableName'] = $this->transChild;
                    $setData['where']['id'] = $row['po_trans_id'];
                    $setData['set']['dispatch_qty'] = 'dispatch_qty, + '.$row['qty'];
                    $setData['update']['trans_status'] = "(CASE WHEN dispatch_qty >= qty THEN 1 ELSE 0 END)";
                    $this->setValue($setData);

                    $setData = array();
                    $setData['tableName'] = $this->transMain;
                    $setData['where']['id'] = $row['po_id'];
                    $setData['update']['trans_status'] = "(SELECT IF( COUNT(id) = SUM(IF(trans_status = 1, 1, 0)) ,1 , 0 ) as trans_status FROM trans_child WHERE trans_main_id = ".$row['po_id']." AND is_delete = 0)";
                    $this->setValue($setData);
                endif;
                
            endforeach;

            //Update GI Status
            if(!empty($data['ref_id'])):
                $this->store($this->mir,['id'=>$data['ref_id'],'trans_status'=>1]);
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

    public function getNextBatchOrSerialNo($data){
		$result = array(); $code = "";

        $itemData = $this->item->getItem($data['item_id']);
        $code = (!empty($itemData->stock_type) && $itemData->stock_type == 2)?$itemData->item_code:"";
        
        $itemTypes = [5,6,7];
        
		if(!empty($data['trans_id'])):
            $queryData = array();
			$queryData['select'] = "serial_no,heat_no";
			$queryData['tableName'] = $this->mirTrans;
            $queryData['where']['type'] = 1;
			$queryData['where']['id'] = $data['trans_id'];
			$result = $this->row($queryData);

			if(!empty($result->serial_no) && $data['heat_no'] == $result->heat_no):
                if(in_array($itemData->item_type,$itemTypes)):
			        $code .= sprintf("-%03d",$result->serial_no);
			    else:
			        $code .= sprintf(n2y(date('Y'))."%03d",$result->serial_no);    
			    endif;
				return ['status'=>1,'batch_no'=>$code,'serial_no'=>$result->serial_no];
			endif;			
		endif;
		
		if(!empty($itemData->stock_type) && $itemData->stock_type == 1):
            $queryData = array();
            $queryData['select'] = "serial_no,heat_no";
			$queryData['tableName'] = $this->mirTrans;
			$queryData['where']['item_id'] = $data['item_id'];
            $queryData['where']['type'] = 1;
			$queryData['where']['heat_no'] = $data['heat_no'];
			$result = $this->row($queryData);
			
			if(!empty($result->serial_no)):
                if(in_array($itemData->item_type,$itemTypes)):
			        $code .= sprintf("-%03d",$result->serial_no);
			    else:
			        $code .= sprintf(n2y(date('Y'))."%03d",$result->serial_no);    
			    endif;
				return ['status'=>1,'batch_no'=>$code,'serial_no'=>$result->serial_no];
			endif;
		endif;

		$queryData = array();
		$queryData['select'] = "ifnull(MAX(serial_no) + 1,1) as serial_no";
		$queryData['tableName'] = $this->mirTrans;
        $queryData['where']['type'] = 1;
		$queryData['where']['item_id'] = $data['item_id'];
		$queryData['where']['is_delete'] = 0;
		$queryData['where']['YEAR(created_at)'] = date("Y");
		$serial_no = $this->specificRow($queryData)->serial_no;
		
		if(in_array($itemData->item_type,$itemTypes)):
	        $code .= sprintf("-%03d",$serial_no);
	    else:
	        $code .= sprintf(n2y(date('Y'))."%03d",$serial_no);    
	    endif;
		return ['status'=>1,'batch_no'=>$code,'serial_no'=>$serial_no];
	}

    public function getGateInward($id){
        $queryData['tableName'] = $this->mir;
        $queryData['select'] = "mir.*,party_master.party_name";
        $queryData['leftJoin']['party_master'] = "mir.party_id = party_master.id";
        $queryData['where']['mir.id'] = $id;
        $result = $this->row($queryData);

        $result->itemData = $this->getGateInwardItems($id);
        return $result;
    }
    
    public function getGateInwardItems($id){
        $queryData['tableName'] = $this->mirTrans;
        $queryData['select'] = "mir_transaction.*,item_master.item_code,item_master.item_name,location_master.location as location_name,trans_main.trans_number as po_number";
        $queryData['leftJoin']['item_master'] = "item_master.id = mir_transaction.item_id";
        $queryData['leftJoin']['location_master'] = "location_master.id = mir_transaction.location_id";
        $queryData['leftJoin']['trans_main'] = "trans_main.id = mir_transaction.po_id";
        $queryData['where']['mir_transaction.mir_id'] = $id;
        return $this->rows($queryData);
    }

    public function getInwardItem($data){
        $queryData['tableName'] = $this->mirTrans;
        $queryData['select'] = "mir_transaction.*,item_master.item_code,item_master.item_name,location_master.location as location_name,trans_main.trans_number as po_no";
        $queryData['leftJoin']['item_master'] = "item_master.id = mir_transaction.item_id";
        $queryData['leftJoin']['location_master'] = "location_master.id = mir_transaction.location_id";
        $queryData['leftJoin']['trans_main'] = "trans_main.id = mir_transaction.po_id";
        $queryData['where']['mir_transaction.id'] = $data['id'];
        return $this->row($queryData);
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $gateInwardData = $this->getGateInward($id);

            if(!empty($gateInwardData->ref_id)):
                $this->store($this->mir,['id'=>$gateInwardData->ref_id,'trans_status'=>0]);
            endif;

            foreach($gateInwardData->itemData as $row):
                if(!empty($row->po_trans_id)):
                    $setData = array();
                    $setData['tableName'] = $this->transChild;
                    $setData['where']['id'] = $row->po_trans_id;
                    $setData['set']['dispatch_qty'] = 'dispatch_qty, - '.$row->qty;
                    $setData['update']['trans_status'] = 0;
                    $this->setValue($setData);

                    $setData = array();
                    $setData['tableName'] = $this->transMain;
                    $setData['where']['id'] = $row->po_id;
                    $setData['update']['trans_status'] = 0;
                    $this->setValue($setData);
                endif;

                $this->trash($this->mirTrans,['id'=>$row->id]);
            endforeach;

            $result = $this->trash($this->mir,['id'=>$id],'Gate Inward');        

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function saveInspectedMaterial($data){
        try{
            $this->db->trans_begin();

            foreach($data['itemData'] as $key => $row):
                $mirData = $this->getGateInward($row['mir_id']);
                $mirItem = $this->getInwardItem(['id'=>$row['id']]);

                $this->remove($this->stockTrans,['entry_type'=>26,'main_ref_id' => $mirData->id,'child_ref_id' => $mirItem->id]);

                $row['ok_qty'] = (!empty($row['ok_qty']))?$row['ok_qty']:0;
                $row['reject_qty'] = (!empty($row['reject_qty']))?$row['reject_qty']:0;
                $row['short_qty'] = (!empty($row['short_qty']))?$row['short_qty']:0;
                
                $totalQty = 0;
                $totalQty = ($row['ok_qty'] + $row['reject_qty'] + $row['short_qty']);
                if($mirItem->qty < $totalQty):
                    $this->db->trans_rollback(); break;
                    return ['status'=>0,'message'=>['ok_qty_'.$key => "Invalid Qty."]];
                endif;

                $row['trans_status'] = ($totalQty >= $mirItem->qty)?1:0;

                $this->store($this->mirTrans,$row);

                if(!empty($row['ok_qty'])):
                    $stockData = [
                        'id' => "",
                        'entry_type' => $this->data['entryData']->id,
                        'unique_id' => 0,//$this->transMainModel->getStockUniqueId(['location_id' => $mirItem->location_id,'batch_no' => $mirItem->batch_no,'item_id' => $mirItem->item_id]),
                        'ref_date' => $mirData->trans_date,
                        'ref_no' => $mirData->trans_number,
                        'main_ref_id' => $mirData->id,
                        'child_ref_id' => $mirItem->id,
                        'location_id' => $mirItem->location_id,
                        'batch_no' => $mirItem->batch_no,
                        'party_id' => $mirData->party_id,
                        'item_id' => $mirItem->item_id,
                        'p_or_m' => 1,
                        'qty' => $row['ok_qty'],
                        'price' => $mirItem->price
                    ];

                    $this->store($this->stockTrans,$stockData);
                endif;
            endforeach;

            $result = ['status'=>1,'message'=>"Material Inspected successfully."];

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getPendingInwardItems($data){
        $queryData = array();
        $queryData['tableName'] = $this->mirTrans;
        $queryData['select'] = "mir_transaction.*,(mir_transaction.qty - mir_transaction.inv_qty) as pending_qty,mir.entry_type as main_entry_type,mir.trans_number,mir.trans_date,mir.inv_no,mir.inv_date,mir.doc_no,mir.doc_date,item_master.item_code,item_master.item_name,item_master.item_type,item_master.hsn_code,item_master.gst_per,unit_master.id as unit_id,unit_master.unit_name,'0' as stock_eff";
        $queryData['leftJoin']['mir'] = "mir_transaction.mir_id = mir.id";
        $queryData['leftJoin']['item_master'] = "item_master.id = mir_transaction.item_id";
        $queryData['leftJoin']['unit_master'] = "item_master.unit_id = unit_master.id";
        $queryData['where']['mir.party_id'] = $data['party_id'];
        $queryData['where']['mir_transaction.entry_type'] = $this->data['entryData']->id;
        $queryData['where']['(mir_transaction.qty - mir_transaction.inv_qty) >'] = 0;
        return $this->rows($queryData);
    }
}
?>