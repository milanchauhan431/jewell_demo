<?php
class SalesInvoiceModel extends MasterModel{
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
            $data['where']['trans_main.trans_status !='] = 3;
        elseif($data['status'] == 1):
            $data['where']['trans_main.trans_status'] = 3;
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
            endif;
            $data['trans_number'] = $data['trans_prefix'].$data['trans_no'];

            if($this->checkDuplicate($data) > 0):
                $errorMessage['trans_number'] = "Inv. No. is duplicate.";
                return ['status'=>0,'message'=>$errorMessage];
            endif;

            if(!empty($data['id'])):
                $dataRow = $this->getSalesInvoice(['id'=>$data['id'],'itemList'=>1]);
                $data['rop_amount'] = ($dataRow->memo_type == $data['memo_type'])?$dataRow->rop_amount:0;
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
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transMain,'description'=>"SI TERMS"]);
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transMain,'description'=>"SI MASTER DETAILS"]);
                $this->remove($this->stockTrans,['main_ref_id'=>$data['id'],'entry_type'=>$data['entry_type']]);
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transChild,'description'=>"SI SERIAL DETAILS"]);
            endif;
            
            if($data['memo_type'] == "CASH"):
				$cashAccData = $this->party->getParty(['system_code'=>"CASHACC"]);
				$data['opp_acc_id'] = $cashAccData->id;
                $data['rop_amount'] = $data['net_amount'];
			else:
				$data['opp_acc_id'] = $data['party_id'];
			endif;
            $data['ledger_eff'] = 1;
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

            $result = $this->store($this->transMain,$data,'Sales Invoice');

            if(!empty($masterDetails)):
                $masterDetails['id'] = "";
                $masterDetails['main_ref_id'] = $result['id'];
                $masterDetails['table_name'] = $this->transMain;
                $masterDetails['description'] = "SI MASTER DETAILS";
                $this->store($this->transDetails,$masterDetails);
            endif;

            $expenseData = array();
            if($expAmount <> 0):				
				$expenseData = $transExp;
			endif;

            if(!empty($termsData)):
                foreach($termsData as $row):
                    $row['id'] = "";
                    $row['table_name'] = $this->transMain;
                    $row['description'] = "SI TERMS";
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
                $serialData['description'] = "SI SERIAL DETAILS";
                $serialData['main_ref_id'] = $result['id'];
                $serialData['child_ref_id'] = $itemTrans['id'];
                $this->store($this->transDetails,$serialData);

                if($row['stock_eff'] == 1):
                    $stockData = [
                        'id' => "",
                        'entry_type' => $data['entry_type'],
                        'unique_id' => $serialData['t_col_1'],
                        'ref_date' => $data['trans_date'],
                        'ref_no' => $data['trans_number'],
                        'main_ref_id' => $result['id'],
                        'child_ref_id' => $itemTrans['id'],
                        'location_id' => $serialData['i_col_1'],
                        'batch_no' => $serialData['t_col_1'],
                        'party_id' => $data['party_id'],
                        'item_id' => $row['item_id'],
                        'stock_category' => $serialData['t_col_2'],
                        'p_or_m' => -1,
                        'qty' => $row['qty'],
                        'standard_qty' => $serialData['d_col_1'],
                        'sales_price' => $row['taxable_amount'],
                        'purity' => $serialData['d_col_2'],
                        'gross_weight' => $row['gross_weight'],
                        'net_weight' => $row['net_weight'],
                        'gold_weight' => $row['gold_weight'],
                    ];

                    $this->store($this->stockTrans,$stockData);
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
            
            $data['id'] = $result['id'];
            $this->transMainModel->ledgerEffects($data,$expenseData);

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

    public function getSalesInvoice($data){
        $queryData = array();
        $queryData['tableName'] = $this->transMain;
        $queryData['select'] = "trans_main.*,trans_details.i_col_1 as bill_per,trans_details.t_col_1 as contact_person,trans_details.t_col_2 as contact_no,trans_details.t_col_3 as ship_address,trans_details.t_col_4 as pan_no,trans_details.t_col_5 as aadhar_no";
        $queryData['leftJoin']['trans_details'] = "trans_main.id = trans_details.main_ref_id AND trans_details.description = 'SI MASTER DETAILS' AND trans_details.table_name = '".$this->transMain."'";
        $queryData['where']['trans_main.id'] = $data['id'];
        $result = $this->row($queryData);

        if($data['itemList'] == 1):
            $result->itemList = $this->getSalesInvoiceItems($data);
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

    public function getSalesInvoiceItems($data){
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

        $queryData['leftJoin']['trans_details'] = "trans_child.trans_main_id = trans_details.main_ref_id AND trans_details.child_ref_id = trans_child.id AND trans_details.description = 'SI SERIAL DETAILS' AND trans_details.table_name = '".$this->transChild."'";

        $queryData['where']['trans_child.trans_main_id'] = $data['id'];
        $result = $this->rows($queryData);
        return $result;
    }

    public function getSalesInvoiceItem($data){
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

        $queryData['leftJoin']['trans_details'] = "trans_child.trans_main_id = trans_details.main_ref_id AND trans_details.child_ref_id = trans_child.id AND trans_details.description = 'SI SERIAL DETAILS' AND trans_details.table_name = '".$this->transChild."'";

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

            $dataRow = $this->getSalesInvoice(['id'=>$id,'itemList'=>1]);

            if($dataRow->rop_amount > 0 && $dataRow->memo_type == "DEBIT"):
                return ['status'=>2,'message'=>"Payment Received against this invoice. You can not delete it."];
            endif;

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
            
            $this->remove($this->transDetails,['main_ref_id'=>$id,'table_name'=>$this->transMain,'description'=>"SI TERMS"]);
            $this->remove($this->transDetails,['main_ref_id'=>$id,'table_name'=>$this->transMain,'description'=>"SI MASTER DETAILS"]);

            $this->remove($this->stockTrans,['main_ref_id'=>$dataRow->id,'entry_type'=>$dataRow->entry_type]);
            $this->remove($this->transDetails,['main_ref_id'=>$dataRow->id,'table_name'=>$this->transChild,'description'=>"SI SERIAL DETAILS"]);

            $result = $this->trash($this->transMain,['id'=>$id],'Sales Invoice');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getPendingInvoiceItems($data){
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
        $queryData['leftJoin']['trans_details'] = "trans_child.trans_main_id = trans_details.main_ref_id AND trans_details.child_ref_id = trans_child.id AND trans_details.description = 'SI SERIAL DETAILS' AND trans_details.table_name = '".$this->transChild."'";
        $queryData['where']['trans_main.id'] = $data['id'];
        $queryData['where']['trans_child.entry_type'] = $this->data['entryData']->id;
        $queryData['where']['(trans_child.qty - trans_child.dispatch_qty) >'] = 0;
        return $this->rows($queryData);
    }
}
?>