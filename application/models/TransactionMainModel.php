<?php
class TransactionMainModel extends MasterModel{
    private $transMain = "trans_main";
    private $transChild = "trans_child";
	private $mir = "mir";

	public function getEntryType($data){
		$queryData['tableName'] = "sub_menu_master";
		$queryData['where']['sub_controller_name'] = $data['controller'];
		$result = $this->row($queryData);

		$nextNo = $this->nextTransNo($result->id,1,$result->vou_name_short);
		$nextNo = (!empty($nextNo))?($nextNo + 1):$result->auto_start_no;

		$result->trans_no = $nextNo;
		$result->trans_prefix = $result->vou_prefix.getFyDate("Y").'/';

		return $result;
	}

    public function nextTransNo($entry_type,$last_no = 0,$vouNameS = ""){
        $data['select'] = "MAX(trans_no) as trans_no";
        $data['where']['entry_type'] = $entry_type;
		if(!empty($vouNameS)):
			$data['where']['vou_name_s'] = $vouNameS;
		endif;
		$data['where']['trans_main.trans_date >='] = $this->startYearDate;
		$data['where']['trans_main.trans_date <='] = $this->endYearDate;
        $data['tableName'] = $this->transMain;
		$trans_no = $this->specificRow($data)->trans_no;
		$trans_no = (empty($last_no))?($trans_no + 1):$trans_no;
		return $trans_no;
    }

	public function getNextTransNo($data=array()){
		$no_column = (!empty($data['no_column']))?$data['no_column']:"trans_no";
		$date_column = (!empty($data['date_column']))?$data['date_column']:"trans_date";
		$queryData = array();
		$queryData['tableName'] = $data['table_name'];
        $queryData['select'] = "ifnull(MAX(".$no_column." + 1),1) as next_no";
        $queryData['where'][$date_column.' >='] = $this->startYearDate;
        $queryData['where'][$date_column.' <='] = $this->endYearDate;
        return $this->row($queryData)->next_no;
	}

	public function getMirNextNo($type = 1){
        $queryData['tableName'] = $this->mir;
        $queryData['select'] = "ifnull(MAX(trans_no + 1),1) as next_no";
        $queryData['where']['trans_type'] = $type;
        $queryData['where']['trans_date >='] = $this->startYearDate;
        $queryData['where']['trans_date <='] = $this->endYearDate;
        return $this->row($queryData)->next_no;
    }

	public function getStockUniqueId($data){
		$queryData = array();
		$queryData['tableName'] = "stock_transaction";
		$queryData['select'] = "unique_id";
		$queryData['where']['item_id'] = $data['item_id'];
		$queryData['where']['location_id'] = $data['location_id'];
		$queryData['where']['batch_no'] = $data['batch_no'];
		$result = $this->row($queryData);

		if(!empty($result->unique_id)):
			return $result->unique_id;
		endif;

		$queryData = array();
		$queryData['tableName'] = "stock_transaction";
		$queryData['select'] = "ifnull((MAX(unique_id) + 1),1) as unique_id";
		return $this->row($queryData)->unique_id;
	}
	
    /* public function getTransPrefix($entry_type){
		$prefix = 'TRANS/';
        switch($entry_type)
		{
			case 1: $prefix = 'SE/';break;
			case 2: $prefix = 'SQ/';break;
			case 3: $prefix = 'SQR/';break;
			case 4: $prefix = 'SO/';break;
			case 5: $prefix = 'SCH/';break;
			case 6: $prefix = 'GT/';break;
			case 7: $prefix = 'JW/';break;
			case 8: $prefix = 'EXP/';break;
			case 9: $prefix = 'PINV/';break;			
			case 13: $prefix = 'CRN/';break;
			case 14: $prefix = 'DRN/';break;
			case 15: $prefix = 'RV/';break;
			case 16: $prefix = 'PV/';break;
			case 17: $prefix = 'JV/';break;
			case 18: $prefix = 'GEXP/';break;
			case 19: $prefix = 'GINC/';break;
			case 20: $prefix = 'JINV/';break;
			case 21: $prefix = 'AS/';break;
			case 22: $prefix = 'EL/';break;
			case 26: $prefix = 'SER/';break;

			case 20: $prefix = 'SO/';break;
			case 21: $prefix = 'AE/';break;

			default : $prefix = 'TRANS/';break;
		}
		return $prefix.getFyDate("Y").'/';
    } */

	public function ledgerEffects($transMainData,$expenseData = array()){
		try{
			$this->deleteLedgerTrans($transMainData['id']);
			$this->deleteExpenseTrans($transMainData['id']);

			/* $transMainData['vou_name_s'] = getVoucherNameShort($data['entry_type']);
			$transMainData['vou_name_l'] = getVoucherNameLong($data['entry_type']); */
			
			$partyData = $this->party->getParty(['id'=>$transMainData['opp_acc_id']]);
			if(!empty($partyData)):
				$transLedgerData['currency'] = (!empty($partyData->currency))?$partyData->currency:"INR";
				$transLedgerData['inrrate'] = (!empty($partyData->inrrate) && $partyData->inrrate > 0)?$partyData->inrrate:1;
			endif;

			if(in_array($transMainData['vou_name_s'],["BCRct","BCPmt","AdSal","EmpLoan"])):
				$cord = getCrDrEff($transMainData['vou_name_s']);
				//Save Party Account Detail
				$transLedgerData = ['id'=>"",'entry_type'=>$transMainData['entry_type'],'trans_main_id'=>$transMainData['id'],'trans_date'=>$transMainData['trans_date'],'trans_number'=>$transMainData['trans_number'],'doc_date'=>$transMainData['doc_date'],'doc_no'=>$transMainData['doc_no'],'vou_acc_id'=>$transMainData['opp_acc_id'],'opp_acc_id'=>$transMainData['vou_acc_id'],'amount'=>$transMainData['net_amount'],'c_or_d'=>$cord['opp_type'],'remark'=>$transMainData['remark'],'trans_mode'=>$transMainData['payment_mode'],'vou_name_l'=>$transMainData['vou_name_l'],'vou_name_s'=>$transMainData['vou_name_s']];
				$this->storeTransLedger($transLedgerData);

				//Save BankCash Account Detail
				$transLedgerData['vou_acc_id'] = $transMainData['vou_acc_id'];
				$transLedgerData['opp_acc_id'] = $transMainData['opp_acc_id'];
				$transLedgerData['c_or_d'] = $cord['vou_type'];
				$this->storeTransLedger($transLedgerData);
			endif;

			if(in_array($transMainData['vou_name_s'],["Purc","Sale","C.N.","D.N.","GExp","GInc"])):
				if($transMainData['ledger_eff'] == 1):
					if(!empty($expenseData)):
						$expenseData['id'] = "";
						$expenseData['trans_main_id'] = $transMainData['id'];
						$this->store('trans_expense',$expenseData);
					endif;

					$cord = getCrDrEff($transMainData['vou_name_s']);
					//Save Party Account Detail
					$transLedgerData = ['id'=>'','entry_type'=>$transMainData['entry_type'],'trans_main_id'=>$transMainData['id'],'trans_date'=>$transMainData['trans_date'],'trans_number'=>$transMainData['trans_number'],'doc_date'=>$transMainData['doc_date'],'doc_no'=>$transMainData['doc_no'],'vou_acc_id'=>$transMainData['opp_acc_id'],'opp_acc_id'=>$transMainData['vou_acc_id'],'amount'=>$transMainData['net_amount'],'c_or_d'=>$cord['vou_type'],'remark'=>$transMainData['remark'],'vou_name_l'=>$transMainData['vou_name_l'],'vou_name_s'=>$transMainData['vou_name_s']];
					$this->storeTransLedger($transLedgerData);

					//Save Sale/Purc Account Detail
					if(!in_array($transMainData['vou_name_s'],["GExp","GInc"])):
						if(!isset($transMainData['sp_acc_id'])):
							//$accType = getSystemCode($transMainData['entry_type'],true,$transMainData['gst_type']);
							$accType = getSPAccCode($transMainData['vou_name_s'],$transMainData['gst_type'],$transMainData['sales_type']);
							if(!empty($accType)):
								$spAcc = $this->party->getParty(['system_code'=>$accType]);
								$transMainData['sp_acc_id'] = (!empty($spAcc))?$spAcc->id:0;
								$this->edit('trans_main',['id'=>$transMainData['id']],['sp_acc_id'=>$transMainData['sp_acc_id']]);
							else:
								$transMainData['sp_acc_id'] = 0;
							endif;
						endif;
						$transLedgerData['vou_acc_id'] = $transMainData['sp_acc_id'];
						$transLedgerData['opp_acc_id'] = $transMainData['opp_acc_id'];
						$transLedgerData['amount'] = $transMainData['taxable_amount'];
						$transLedgerData['c_or_d'] = $cord['opp_type'];
						$this->storeTransLedger($transLedgerData);
					else:
						$gstExpenseTrans = $this->gstExpense->getGstExpenseItems(['id'=>$transMainData['id']]);
						foreach($gstExpenseTrans as $row):
							$transLedgerData['vou_acc_id'] = $row->item_id;
							$transLedgerData['opp_acc_id'] = $transMainData['opp_acc_id'];
							$transLedgerData['amount'] = $row->taxable_amount;
							$transLedgerData['c_or_d'] = $cord['opp_type'];
							$this->storeTransLedger($transLedgerData);
						endforeach;
					endif;

					//Save Tax Account Detail
					if($transMainData['gst_type'] == 2):
						if($transMainData['igst_amount'] <> 0):
							$transLedgerData['vou_acc_id'] = $transMainData['igst_acc_id'];
							$transLedgerData['opp_acc_id'] = $transMainData['opp_acc_id'];
							$transLedgerData['amount'] = $transMainData['igst_amount'];
							$transLedgerData['c_or_d'] = $cord['opp_type'];
							$this->storeTransLedger($transLedgerData);
						endif;
					else:
						if($transMainData['cgst_amount'] <> 0 && $transMainData['sgst_amount'] <> 0):
							$transLedgerData['vou_acc_id'] = $transMainData['cgst_acc_id'];
							$transLedgerData['opp_acc_id'] = $transMainData['opp_acc_id'];
							$transLedgerData['amount'] = $transMainData['cgst_amount'];
							$transLedgerData['c_or_d'] = $cord['opp_type'];
							$this->storeTransLedger($transLedgerData);

							$transLedgerData['vou_acc_id'] = $transMainData['sgst_acc_id'];
							$transLedgerData['opp_acc_id'] = $transMainData['opp_acc_id'];
							$transLedgerData['amount'] = $transMainData['sgst_amount'];
							$transLedgerData['c_or_d'] = $cord['opp_type'];
							$this->storeTransLedger($transLedgerData);
						endif;
					endif;

					if((isset($transMainData['cess_amount'])) && $transMainData['cess_amount'] <> 0):
						$transLedgerData['vou_acc_id'] = $transMainData['cess_acc_id'];
						$transLedgerData['opp_acc_id'] = $transMainData['opp_acc_id'];
						$transLedgerData['amount'] = $transMainData['cess_amount'];
						$transLedgerData['c_or_d'] = $cord['opp_type'];
						$this->storeTransLedger($transLedgerData);
					endif;

					if((isset($transMainData['cess_qty_amount'])) && $transMainData['cess_qty_amount'] <> 0):
						$transLedgerData['vou_acc_id'] = $transMainData['cess_qty_acc_id'];
						$transLedgerData['opp_acc_id'] = $transMainData['opp_acc_id'];
						$transLedgerData['amount'] = $transMainData['cess_qty_amount'];
						$transLedgerData['c_or_d'] = $cord['opp_type'];
						$this->storeTransLedger($transLedgerData);
					endif;

					if((isset($transMainData['tcs_amount'])) && $transMainData['tcs_amount'] <> 0):
						$transLedgerData['vou_acc_id'] = $transMainData['tcs_acc_id'];
						$transLedgerData['opp_acc_id'] = $transMainData['opp_acc_id'];
						$transLedgerData['amount'] = $transMainData['tcs_amount'];
						$transLedgerData['c_or_d'] = $cord['opp_type'];
						$this->storeTransLedger($transLedgerData);
					endif;

					if((isset($transMainData['tds_amount'])) && $transMainData['tds_amount'] <> 0):
						$transLedgerData['vou_acc_id'] = $transMainData['tds_acc_id'];
						$transLedgerData['opp_acc_id'] = $transMainData['opp_acc_id'];
						$transLedgerData['amount'] = $transMainData['tds_amount'];
						$transLedgerData['c_or_d'] = $cord['opp_type'];
						$this->storeTransLedger($transLedgerData);
					endif;

					//Save Expense Account Detail
					$expType = (in_array($transMainData['vou_name_s'],["Purc","D.N.","GExp"]))?1:2;
					$expenseMaster = $this->expenseMaster->getActiveExpenseList($expType);
					foreach($expenseMaster as $row):
						if(isset($expenseData[$row->map_code."_acc_id"]) && isset($expenseData[$row->map_code.'_amount'])):
							if($expenseData[$row->map_code.'_amount'] <> 0 && $row->map_code != "roff"): 
								$transLedgerData['vou_acc_id'] = $expenseData[$row->map_code."_acc_id"];
								$transLedgerData['opp_acc_id'] = $transMainData['opp_acc_id'];
								$transLedgerData['amount'] = $expenseData[$row->map_code.'_amount'];
								$transLedgerData['c_or_d'] = (in_array($transMainData['vou_name_s'],["Purc","C.N.","GExp"]))?(($row->add_or_deduct == 1)?"DR":"CR"):(($row->add_or_deduct == 1)?"CR":"DR");
								$this->storeTransLedger($transLedgerData);
							endif;
						endif;
					endforeach;

					//Save Round off Account Detail 
					if($transMainData['round_off_amount'] <> 0): 
						$transLedgerData['vou_acc_id'] = $transMainData["round_off_acc_id"];
						$transLedgerData['opp_acc_id'] = $transMainData['opp_acc_id'];
						$transLedgerData['amount'] = abs($transMainData['round_off_amount']);
						$transLedgerData['c_or_d'] = (in_array($transMainData['vou_name_s'],["Purc","C.N.","GExp"]))?(($transMainData['round_off_amount'] > 0)?"DR":"CR"):(($transMainData['round_off_amount'] > 0)?"CR":"DR");
						$this->storeTransLedger($transLedgerData);
					endif;
				endif;
			endif;

			return true;
		}catch(\Throwable $e){
			return false;
        }		
	}

	public function storeTransLedger($data){
		try{
			$data['p_or_m'] = ($data['c_or_d'] == "DR")?-1:1;
			/* $data['vou_name_s'] = getVoucherNameShort($data['entry_type']);
			$data['vou_name_l'] = getVoucherNameLong($data['entry_type']); */
			$this->store("trans_ledger",$data);
			$this->updateAccountBalance($data['vou_acc_id'],( $data['p_or_m'] * $data['amount'] ));
			return true;
		}catch(\Throwable $e){
			return false;
        }			
	}

	public function updateAccountBalance($acc_id,$amount){
		try{
			$setData = Array();
			$setData['tableName'] = "party_master";
			$setData['where']['id'] = $acc_id;
			$setData['set']['cl_balance'] = 'cl_balance, + '.$amount;
			$this->setValue($setData);
			return true;
		}catch(\Throwable $e){
			return false;
        }
	}

	public function deleteLedgerTrans($trans_main_id){
		try{
			$queryData = array();
			$queryData['tableName'] = "trans_ledger";
			$queryData['where']['trans_main_id'] = $trans_main_id;
			$transLedgerData = $this->rows($queryData);

			if(!empty($transLedgerData)):
				foreach($transLedgerData as $row):
					$amount = $row->amount * $row->p_or_m * -1;
					$this->updateAccountBalance($row->vou_acc_id,$amount);				
				endforeach;
				$this->remove("trans_ledger",['trans_main_id'=>$trans_main_id]);
				$this->deleteExpenseTrans($trans_main_id);
			endif;
			return true;
		}catch(\Throwable $e){
			return false;
        }
	}

	public function deleteExpenseTrans($trans_main_id){
		try{
			$this->trash('trans_expense',['trans_main_id'=>$trans_main_id]);
			return true;
		}catch(\Throwable $e){
			return false;
		}
	}

	public function getPartyInvoiceList($data){
        $queryData  = array();
        $queryData['tableName'] = $this->transMain;
        $queryData['select'] = "trans_main.id,trans_main.entry_type,trans_main.trans_number,DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y') as trans_date";
        $queryData['where']['party_id'] = $data['party_id'];
        $queryData['where_in']['vou_name_s'] = (in_array($data['order_type'],["Increase Sales","Decrease Sales","Sales Return"]))?"'Sale','GInc'":"'Purc','GExp'";
        $queryData['like']['trans_main.trans_number'] = $data['doc_no'];
        $result = $this->rows($queryData);
        return $result;
    }
}
	
?>