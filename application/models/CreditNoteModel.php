<?php
class CreditNoteModel extends MasterModel{
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
        $data['searchCol'][] = "trans_main.order_type";
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

            if(!empty($data['id'])):   
                $vouData = $this->getCreditNote(['id'=>$data['id'],'itemList'=>1]);

                foreach($vouData->itemList as $row):
                    if($row->stock_eff == 1 && !empty($row->ref_id)):
                        $setData = array();
                        $setData['tableName'] = $this->transChild;
                        $setData['where']['id'] = $row->ref_id;
                        $setData['set']['dispatch_qty'] = 'dispatch_qty, - '.$row->qty;
                        $setData['update']['trans_status'] = "(CASE WHEN dispatch_qty >= qty THEN 1 ELSE 0 END)";
                        $this->setValue($setData);
                    endif;

                    $this->trash($this->transChild,['id'=>$row->id]);
                endforeach;

                $this->trash($this->transExpense,['trans_main_id'=>$data['id']]);
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transMain,'description'=>"CN TERMS"]);
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transMain,'description'=>"CN MASTER DETAILS"]);
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transChild,'description'=>"CN SERIAL DETAILS"]);
                $this->remove($this->stockTrans,['main_ref_id'=>$data['id'],'entry_type'=>$data['entry_type']]);

                if(!empty($vouData->ref_id)):
                    $setData = array();
                    $setData['tableName'] = $this->transMain;
                    $setData['where']['id'] = $vouData->ref_id;
                    $setData['set']['rop_amount'] = 'rop_amount, - '.$vouData->net_amount;
                    $this->setValue($setData);
                endif;
            endif;
            
            if($data['memo_type'] == "CASH"):
				$cashAccData = $this->party->getParty(['system_code'=>"CASHACC"]);
				$data['opp_acc_id'] = $cashAccData->id;
			else:
				$data['opp_acc_id'] = $data['party_id'];
			endif;
            $data['ledger_eff'] = 1;
            $data['gstin'] = (!empty($data['gstin']))?$data['gstin']:"URP";
            $data['disc_amount'] = array_sum(array_column($data['itemData'],'disc_amount'));;
            $data['total_amount'] = $data['taxable_amount'] + $data['disc_amount'];
            $data['gst_amount'] = $data['igst_amount'] + $data['cgst_amount'] + $data['sgst_amount'];

            $accType = ($data['order_type'] == "Increase Purchase")?"PURACC":"SALESACC";
            $spAcc = $this->party->getParty(['system_code'=>$accType]);
            $data['vou_acc_id'] = (!empty($spAcc))?$spAcc->id:0;

            $masterDetails = (!empty($data['masterDetails']))?$data['masterDetails']:array();
            $itemData = $data['itemData'];

            $transExp = getExpArrayMap(((!empty($data['expenseData']))?$data['expenseData']:array()));
			$expAmount = $transExp['exp_amount'];
            $termsData = (!empty($data['termsData']))?$data['termsData']:array();

            unset($transExp['exp_amount'],$data['itemData'],$data['expenseData'],$data['termsData'],$data['masterDetails']);		

            $result = $this->store($this->transMain,$data,'Credit Note');

            if(!empty($masterDetails)):
                $masterDetails['id'] = "";
                $masterDetails['main_ref_id'] = $result['id'];
                $masterDetails['table_name'] = $this->transMain;
                $masterDetails['description'] = "CN MASTER DETAILS";
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
                    $row['description'] = "CN TERMS";
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
                $serialData['description'] = "CN SERIAL DETAILS";
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
                        'p_or_m' => 1,
                        'qty' => $row['qty'],
                        'standard_qty' => $serialData['d_col_1'],
                        'sales_price' => $row['taxable_amount'],
                        'purity' => $serialData['d_col_2'],
                        'gross_weight' => $row['gross_weight'],
                        'net_weight' => $row['net_weight'],
                        'gold_weight' => $row['gold_weight'],
                        'stock_type' => "RETURN"
                    ];

                    $this->store($this->stockTrans,$stockData);

                    if(!empty($row['ref_id'])):
                        $setData = array();
                        $setData['tableName'] = $this->transChild;
                        $setData['where']['id'] = $row['ref_id'];
                        $setData['set']['dispatch_qty'] = 'dispatch_qty, + '.$row['qty'];
                        $setData['update']['trans_status'] = "(CASE WHEN dispatch_qty >= qty THEN 1 ELSE 0 END)";
                        $this->setValue($setData);
                    endif;
                endif;
            endforeach;

            if(!empty($data['ref_id'])):
                $setData = array();
                $setData['tableName'] = $this->transMain;
                $setData['where']['id'] = $data['ref_id'];
                $setData['set']['rop_amount'] = 'rop_amount, + '.$data['net_amount'];
                $this->setValue($setData);
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

    public function getCreditNote($data){
        $queryData = array();
        $queryData['tableName'] = $this->transMain;
        $queryData['select'] = "trans_main.*";
        $queryData['where']['trans_main.id'] = $data['id'];
        $result = $this->row($queryData);

        if($data['itemList'] == 1):
            $result->itemList = $this->getCreditNoteItems($data);
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
        $queryData['where']['description'] = "CN TERMS";
        $result->termsConditions = $this->rows($queryData);

        return $result;
    }

    public function getCreditNoteItems($data){
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

        $queryData['leftJoin']['trans_details'] = "trans_child.trans_main_id = trans_details.main_ref_id AND trans_details.child_ref_id = trans_child.id AND trans_details.description = 'CN SERIAL DETAILS' AND trans_details.table_name = '".$this->transChild."'";

        $queryData['where']['trans_child.trans_main_id'] = $data['id'];
        $result = $this->rows($queryData);
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

            $vouData = $this->getCreditNote(['id'=>$id,'itemList'=>1]);
            if(!empty($vouData->ref_id)):
                $setData = array();
                $setData['tableName'] = $this->transMain;
                $setData['where']['id'] = $vouData->ref_id;
                $setData['set']['rop_amount'] = 'rop_amount, - '.$vouData->net_amount;
                $this->setValue($setData);
            endif;

            foreach($vouData->itemList as $row):
                if($row->stock_eff == 1 && !empty($row->ref_id)):
                    $setData = array();
                    $setData['tableName'] = $this->transChild;
                    $setData['where']['id'] = $row->ref_id;
                    $setData['set']['dispatch_qty'] = 'dispatch_qty, - '.$row->qty;
                    $setData['update']['trans_status'] = "(CASE WHEN dispatch_qty >= qty THEN 1 ELSE 0 END)";
                    $this->setValue($setData);
                endif;

                $this->trash($this->transChild,['id'=>$row->id]);
            endforeach;

            $this->transMainModel->deleteLedgerTrans($id);

            $this->trash($this->transExpense,['trans_main_id'=>$id]);
            
            $this->remove($this->transDetails,['main_ref_id'=>$id,'table_name'=>$this->transMain,'description'=>"CN TERMS"]);
            $this->remove($this->transDetails,['main_ref_id'=>$id,'table_name'=>$this->transMain,'description'=>"CN MASTER DETAILS"]);
            $this->remove($this->stockTrans,['main_ref_id'=>$id,'entry_type'=>$this->data['entryData']->id]);
            $this->remove($this->transDetails,['main_ref_id'=>$id,'table_name'=>$this->transChild,'description'=>"CN SERIAL DETAILS"]);
            $result = $this->trash($this->transMain,['id'=>$id],'Credit Note');

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