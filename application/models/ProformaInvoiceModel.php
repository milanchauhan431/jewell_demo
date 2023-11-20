<?php
class ProformaInvoiceModel extends MasterModel{
    private $transMain = "trans_main";
    private $transChild = "trans_child";
    private $transExpense = "trans_expense";
    private $transDetails = "trans_details";
    private $stockTrans = "stock_transaction";

    public function getDTRows($data){
        $data['tableName'] = $this->transMain;
        $data['select'] = "trans_main.*";

        $data['where']['trans_main.entry_type'] = $data['entry_type'];

        if($data['status'] == 0):
            $data['where']['trans_main.trans_status'] = 0;
        elseif($data['status'] == 1):
            $data['where']['trans_main.trans_status'] = 1;
        endif;

        $data['where']['trans_main.trans_date >='] = $this->startYearDate;
        $data['where']['trans_main.trans_date <='] = $this->endYearDate;

        $data['order_by']['trans_main.trans_date'] = "DESC";
        $data['order_by']['trans_main.id'] = "DESC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "trans_main.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "trans_main.party_name";
        $data['searchCol'][] = "trans_main.taxable_amount";
        $data['searchCol'][] = "trans_main.gst_amount";
        $data['searchCol'][] = "trans_main.net_amount";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();
 
            if(empty($data['id'])): 
                $data['trans_no'] = $this->transMainModel->nextTransNo($data['entry_type']);
                $data['trans_number'] = $data['trans_prefix'].$data['trans_no'];
            endif; 

            if($this->checkDuplicate($data) > 0):
                $errorMessage['trans_number'] = "PI. No. is duplicate.";
                return ['status'=>0,'message'=>$errorMessage];
            endif;

            if(!empty($data['id'])):
                $dataRow = $this->getProformaInvoice(['id'=>$data['id'],'itemList'=>1]);
                foreach($dataRow->itemList as $row):
                    if(!empty($row->ref_id)):
                        $setData = array();
                        $setData['tableName'] = $this->transChild;
                        $setData['where']['id'] = $row->ref_id;
                        $setData['set']['dispatch_qty'] = 'dispatch_qty, - '.$row->qty;
                        $setData['update']['trans_status'] = "(CASE WHEN dispatch_qty >= qty THEN 1 ELSE 0 END)";
                        $this->setValue($setData);
                    endif;

                    $this->trash($this->transChild,['id'=>$row->id]);
                endforeach;

                if(!empty($dataRow->ref_id)):
                    $oldRefIds = explode(",",$dataRow->ref_id);
                    foreach($oldRefIds as $main_id):
                        $setData = array();
                        $setData['tableName'] = $this->transMain;
                        $setData['where']['id'] = $main_id;
                        $setData['update']['trans_status'] = "(SELECT IF( COUNT(id) = SUM(IF(trans_status <> 0, 1, 0)) ,1 , 0 ) as trans_status FROM trans_child WHERE trans_main_id = ".$main_id." AND is_delete = 0)";
                        $this->setValue($setData);
                    endforeach;
                endif;
                
                $this->trash($this->transExpense,['trans_main_id'=>$data['id']]);
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transMain,'description'=>"PI TERMS"]);
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transMain,'description'=>"PI MASTER DETAILS"]);
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transChild,'description'=>"PI SERIAL DETAILS"]);
            endif;
            
            $data['ledger_eff'] = 0;
            $data['gstin'] = (!empty($data['gstin']))?$data['gstin']:"URP";
            $data['disc_amount'] = array_sum(array_column($data['itemData'],'disc_amount'));;
            $data['total_amount'] = $data['taxable_amount'] + $data['disc_amount'];
            $data['gst_amount'] = $data['igst_amount'] + $data['cgst_amount'] + $data['sgst_amount'];

            $accType = getSystemCode($data['vou_name_s'],false);
            if(!empty($accType)):
				$spAcc = $this->party->getParty(['system_code'=>$accType]);
                $data['vou_acc_id'] = (!empty($spAcc))?$spAcc->id:0;
            else:
                $data['vou_acc_id'] = 0;
            endif;

            $masterDetails = (!empty($data['masterDetails']))?$data['masterDetails']:array();
            $itemData = $data['itemData'];

            $transExp = getExpArrayMap(((!empty($data['expenseData']))?$data['expenseData']:array()));
			$expAmount = $transExp['exp_amount'];
            $termsData = (!empty($data['termsData']))?$data['termsData']:array();

            unset($transExp['exp_amount'],$data['itemData'],$data['expenseData'],$data['termsData'],$data['masterDetails']);		

            $result = $this->store($this->transMain,$data,'Proforma Invoice');

            if(!empty($masterDetails)):
                $masterDetails['id'] = "";
                $masterDetails['main_ref_id'] = $result['id'];
                $masterDetails['table_name'] = $this->transMain;
                $masterDetails['description'] = "PI MASTER DETAILS";
                $this->store($this->transDetails,$masterDetails);
            endif;

            $expenseData = array();
            if($expAmount <> 0):				
				$expenseData = $transExp;
                $expenseData['id'] = "";
				$expenseData['trans_main_id'] = $result['id'];
                $this->store($this->transExpense,$expenseData);
			endif;

            if(!empty($termsData)):
                foreach($termsData as $row):
                    $row['id'] = "";
                    $row['table_name'] = $this->transMain;
                    $row['description'] = "PI TERMS";
                    $row['main_ref_id'] = $result['id'];
                    $this->store($this->transDetails,$row);
                endforeach;
            endif;

            $i=1;
            foreach($itemData as $row):
                $row['entry_type'] = $data['entry_type'];
                $row['trans_main_id'] = $result['id'];
                $row['gst_amount'] = $row['igst_amount'];
                $row['is_delete'] = 0;
                $serialData = $row['masterData'];unset($row['masterData']);
                $itemTrans = $this->store($this->transChild,$row);

                $serialData['id'] = "";
                $serialData['table_name'] = $this->transChild;
                $serialData['description'] = "PI SERIAL DETAILS";
                $serialData['main_ref_id'] = $result['id'];
                $serialData['child_ref_id'] = $itemTrans['id'];
                $this->store($this->transDetails,$serialData);

                if(!empty($data['is_approve'])):
                    $this->edit($this->stockTrans,['id'=>$serialData['i_col_2']],['stock_type'=>'FREEZE']);
                endif;
                 
                if(!empty($row['ref_id'])):
                    $setData = array();
                    $setData['tableName'] = $this->transChild;
                    $setData['where']['id'] = $row['ref_id'];
                    $setData['set']['dispatch_qty'] = 'dispatch_qty, + '.$row['qty'];
                    $setData['update']['trans_status'] = "(CASE WHEN dispatch_qty >= qty THEN 1 ELSE 0 END)";
                    $this->setValue($setData);
                endif;
            endforeach;

            if(!empty($data['ref_id'])):
                $refIds = explode(",",$data['ref_id']);
                foreach($refIds as $main_id):
                    $setData = array();
                    $setData['tableName'] = $this->transMain;
                    $setData['where']['id'] = $main_id;
                    $setData['update']['trans_status'] = "(SELECT IF( COUNT(id) = SUM(IF(trans_status <> 0, 1, 0)) ,1 , 0 ) as trans_status FROM trans_child WHERE trans_main_id = ".$main_id." AND is_delete = 0)";
                    $this->setValue($setData);
                endforeach;
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
     
    public function checkDuplicate($data){
        $queryData['tableName'] = $this->transMain;
        $queryData['where']['entry_type'] = $data['entry_type'];
        $queryData['where']['trans_number'] = $data['trans_number'];

        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];

        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function getProformaInvoice($data){
        $queryData = array();
        $queryData['tableName'] = $this->transMain;
        $queryData['select'] = "trans_main.*,trans_details.i_col_1 as bill_per,trans_details.t_col_1 as contact_person,trans_details.t_col_2 as contact_no,trans_details.t_col_3 as ship_address";
        $queryData['leftJoin']['trans_details'] = "trans_main.id = trans_details.main_ref_id AND trans_details.description = 'PI MASTER DETAILS' AND trans_details.table_name = '".$this->transMain."'";
        $queryData['where']['trans_main.id'] = $data['id'];
        $result = $this->row($queryData);

        if($data['itemList'] == 1):
            $result->itemList = $this->getProformaInvoiceItems($data);
        endif;

        $queryData = array();
        $queryData['tableName'] = $this->transExpense;
        $queryData['where']['trans_main_id'] = $data['id'];
        $result->expenseData = $this->row($queryData);

        $queryData = array();
        $queryData['tableName'] = $this->transDetails;
        $queryData['select'] = "i_col_1 as term_id,t_col_1 as term_title,t_col_2 as condition";
        $queryData['where']['main_ref_id'] = $data['id'];
        $queryData['where']['table_name'] = $this->transMain;
        $queryData['where']['description'] = "SI TERMS";
        $result->termsConditions = $this->rows($queryData);

        return $result;
    }

    public function getProformaInvoiceItems($data){
        $queryData = array();
        $queryData['tableName'] = $this->transChild;
        $queryData['select'] = "trans_child.*,";
        $queryData['select'] .= "trans_details.i_col_1 as location_id,";
        $queryData['select'] .= "trans_details.i_col_2 as stock_trans_id,";
        $queryData['select'] .= "trans_details.d_col_1 as standard_qty,";
        $queryData['select'] .= "trans_details.d_col_2 as purity,";
        $queryData['select'] .= "trans_details.d_col_3 as diamond_pcs,";
        $queryData['select'] .= "trans_details.t_col_1 as unique_id,";
        $queryData['select'] .= "trans_details.t_col_2 as stock_category,";
        $queryData['select'] .= "trans_details.t_col_3 as color,";
        $queryData['select'] .= "trans_details.t_col_4 as diamond_carat";

        $queryData['leftJoin']['trans_details'] = "trans_child.trans_main_id = trans_details.main_ref_id AND trans_details.child_ref_id = trans_child.id AND trans_details.description = 'PI SERIAL DETAILS' AND trans_details.table_name = '".$this->transChild."'";

        $queryData['where']['trans_child.trans_main_id'] = $data['id'];
        $result = $this->rows($queryData);
        return $result;
    }

    public function getProformaInvoiceItem($data){
        $queryData = array();
        $queryData['tableName'] = $this->transChild;
        $queryData['select'] = "trans_child.*,";
        $queryData['select'] .= "trans_details.i_col_1 as location_id,";
        $queryData['select'] .= "trans_details.i_col_2 as stock_trans_id,";
        $queryData['select'] .= "trans_details.d_col_1 as standard_qty,";
        $queryData['select'] .= "trans_details.d_col_2 as purity,";
        $queryData['select'] .= "trans_details.d_col_3 as diamond_pcs,";
        $queryData['select'] .= "trans_details.t_col_1 as unique_id,";
        $queryData['select'] .= "trans_details.t_col_2 as stock_category,";
        $queryData['select'] .= "trans_details.t_col_3 as color,";
        $queryData['select'] .= "trans_details.t_col_4 as diamond_carat";

        $queryData['leftJoin']['trans_details'] = "trans_child.trans_main_id = trans_details.main_ref_id AND trans_details.child_ref_id = trans_child.id AND trans_details.description = 'PI SERIAL DETAILS' AND trans_details.table_name = '".$this->transChild."'";

        $queryData['where']['trans_child.id'] = $data['id'];
        $result = $this->row($queryData);
        return $result;
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $postData["table_name"] = $this->transMain;
            $postData['where'] = [['column_name'=>'from_entry_type','column_value'=>$this->data['entryData']->id]];
            $postData['find'] = [['column_name'=>'ref_id','column_value'=>$id]];
            $checkRef = $this->checkEntryReference($postData);
            if($checkRef['status'] == 0):
                return $checkRef;
            endif;

            $dataRow = $this->getProformaInvoice(['id'=>$id,'itemList'=>1]);
             
            foreach($dataRow->itemList as $row):
                if(!empty($row->ref_id)):
                    $setData = array();
                    $setData['tableName'] = $this->transChild;
                    $setData['where']['id'] = $row->ref_id;
                    $setData['set']['dispatch_qty'] = 'dispatch_qty, - '.$row->qty;
                    $setData['update']['trans_status'] = "(CASE WHEN dispatch_qty >= qty THEN 1 ELSE 0 END)";
                    $this->setValue($setData);
                endif;

                $this->trash($this->transChild,['id'=>$row->id]);
            endforeach;

            if(!empty($dataRow->ref_id)):
                $oldRefIds = explode(",",$dataRow->ref_id);
                foreach($oldRefIds as $main_id):
                    $setData = array();
                    $setData['tableName'] = $this->transMain;
                    $setData['where']['id'] = $main_id;
                    $setData['update']['trans_status'] = "(SELECT IF( COUNT(id) = SUM(IF(trans_status <> 0, 1, 0)) ,1 , 0 ) as trans_status FROM trans_child WHERE trans_main_id = ".$main_id." AND is_delete = 0)";
                    $this->setValue($setData);
                endforeach;
            endif;

            $this->transMainModel->deleteLedgerTrans($id);

            $this->trash($this->transExpense,['trans_main_id'=>$id]);
            
            $this->remove($this->transDetails,['main_ref_id'=>$id,'table_name'=>$this->transMain,'description'=>"PI TERMS"]);
            $this->remove($this->transDetails,['main_ref_id'=>$id,'table_name'=>$this->transMain,'description'=>"PI MASTER DETAILS"]);
            $this->remove($this->transDetails,['main_ref_id'=>$id,'table_name'=>$this->transChild,'description'=>"PI SERIAL DETAILS"]);
			$result = $this->trash($this->transMain,['id'=>$id],'Proforma Invoice');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function saveReversalApproval($data){
        try{
            $this->db->trans_begin();

            $dataRow = $this->getProformaInvoice(['id'=>$data['id'],'itemList'=>1]);
            foreach($dataRow->itemList as $row):
                $this->edit($this->stockTrans,['id'=>$row->stock_trans_id],['stock_type'=>'FRESH']);
            endforeach;

            $data['is_approve'] = 0;
            $data['approve_date'] = null;
            $result = $this->store($this->transMain,$data);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getPendingProformaItems($data){
        $queryData = array();
        $queryData['tableName'] = $this->transChild;
        $queryData['select'] = "trans_child.*,(trans_child.qty - trans_child.dispatch_qty) as pending_qty,trans_main.entry_type as main_entry_type,trans_main.trans_number,trans_main.trans_date,trans_main.doc_no,";
        $queryData['select'] .= "trans_details.i_col_1 as location_id,";
        $queryData['select'] .= "trans_details.i_col_2 as stock_trans_id,";
        $queryData['select'] .= "trans_details.d_col_1 as standard_qty,";
        $queryData['select'] .= "trans_details.d_col_2 as purity,";
        $queryData['select'] .= "trans_details.d_col_3 as diamond_pcs,";
        $queryData['select'] .= "trans_details.t_col_1 as unique_id,";
        $queryData['select'] .= "trans_details.t_col_2 as stock_category,";
        $queryData['select'] .= "trans_details.t_col_3 as color,";
        $queryData['select'] .= "trans_details.t_col_4 as diamond_carat";

        $queryData['leftJoin']['trans_main'] = "trans_child.trans_main_id = trans_main.id";
        //$queryData['leftJoin']['item_master'] = "trans_child.item_id = item_master.id";
        $queryData['leftJoin']['trans_details'] = "trans_child.trans_main_id = trans_details.main_ref_id AND trans_details.child_ref_id = trans_child.id AND trans_details.description = 'PI SERIAL DETAILS' AND trans_details.table_name = '".$this->transChild."'";

        $queryData['where']['trans_main.party_id'] = $data['party_id'];
        $queryData['where']['trans_child.entry_type'] = $this->data['entryData']->id;
        $queryData['where']['(trans_child.qty - trans_child.dispatch_qty) >'] = 0;
        return $this->rows($queryData);
    }
}
?>