<?php
class DebitNote extends MY_Controller{
    private $indexPage = "debit_note/index";
    private $form = "debit_note/form";    

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Debit Note";
		$this->data['headData']->controller = "debitNote";        
        $this->data['headData']->pageUrl = "debitNote";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'debitNote']);
	}

    public function index(){
        $this->data['tableHeader'] = getAccountingDtHeader("debitNote");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();$data['status'] = $status;
        $data['entry_type'] = $this->data['entryData']->id;
        $result = $this->debitNote->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getDebitNoteData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addDebitNote(){
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>"1,2,3"]);
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
        $this->data['purchaseAccounts'] = $this->party->getPartyList(['system_code'=>$this->purchaseTypeCodes]);
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(1);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(1);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Purchase']);
		$this->data['ledgerList'] = $this->party->getPartyList(['group_code'=>["'DT'","'ED'","'EI'","'ID'","'II'"]]);
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Party Name is required.";
        if(empty($data['sp_acc_id']))
            $errorMessage['sp_acc_id'] = "GST Type is required.";
        if(empty($data['itemData'])):
            $errorMessage['itemData'] = "Item Details is required.";
        else:
            foreach($data['itemData'] as $key => $row):
                if($row['stock_eff'] == 1):
                    $serialNo = $row['masterData']['t_col_1'];
                    if(empty($serialNo)):
                        $errorMessage['barcode'.$key] = "Barcode No. is required.";
                    else:
                        $postData = ['location_id' => $row['masterData']['i_col_1'],'batch_no' => $row['masterData']['t_col_1'],'item_id' => $row['item_id'],'stock_required'=>1,'single_row'=>1];
                    
                        $stockData = $this->itemStock->getItemStockBatchWise($postData);  
                        
                        $stockQty = (!empty($stockData->qty))?$stockData->qty:0;
                        if(!empty($row['id'])):
                            $oldItem = $this->debitNote->getDebitNoteItem(['id'=>$row['id']]);
                            $stockQty = $stockQty + $oldItem->qty;
                        endif;
                        
                        if(!isset($bQty[$serialNo])):
                            $bQty[$serialNo] = $row['qty'] ;
                        else:
                            $bQty[$serialNo] += $row['qty'];
                        endif;
    
                        if(empty($stockQty)):
                            $errorMessage['qty'.$key] = "Stock not available.";
                        else:
                            if($bQty[$serialNo] > $stockQty):
                                $errorMessage['qty'.$key] = "Stock not available.";
                            endif;
                        endif;
                    endif;
                endif;
            endforeach;
        endif;
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['vou_name_l'] = $this->data['entryData']->vou_name_long;
            $data['vou_name_s'] = $this->data['entryData']->vou_name_short;
            $this->printJson($this->debitNote->save($data));
        endif;
    }

    public function edit($id){
        $this->data['dataRow'] = $dataRow = $this->debitNote->getDebitNote(['id'=>$id,'itemList'=>1]);
        $this->data['gstinList'] = $this->party->getPartyGSTDetail(['party_id' => $dataRow->party_id]);
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => "1,2,3"]);
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
        $this->data['purchaseAccounts'] = $this->party->getPartyList(['system_code'=>($dataRow->order_type == "Increase Sales")?$this->salesTypeCodes:$this->purchaseTypeCodes]);
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(1);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(1);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Purchase']);
        $this->data['ledgerList'] = $this->party->getPartyList(['group_code'=>["'DT'","'ED'","'EI'","'ID'","'II'"]]);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->debitNote->delete($id));
        endif;
    }

    public function getDebitNoteTypes(){
        $data = $this->input->post();
        $accounts = $this->party->getPartyList(['system_code'=>($data['order_type'] == "Increase Sales")?$this->salesTypeCodes:$this->purchaseTypeCodes]);
        $type = ($data['order_type'] == "Increase Sales")?"SALES":"PURCHASE";
        $accountOptions = getSpAccListOption($accounts);
        $this->printJson(['status'=>1,'accountOptions'=>$accountOptions,'inv_type'=>$type]);
    }

    public function getAccountSummaryHtml(){
        $data = $this->input->post();
        $type = ($data['order_type'] == "Increase Sales")?2:1;
        $this->data['taxList'] = $this->taxMaster->getActiveTaxList($type);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList($type);
        $this->data['ledgerList'] = $this->party->getPartyList(['group_code'=>["'DT'","'ED'","'EI'","'ID'","'II'"]]);
        $this->data['dataRow'] = array();
        $this->load->view('includes/tax_summary',$this->data);
    }

    public function printDebitNote(){
        $postData = $this->input->post();
        
        $printTypes = array();
        if(!empty($postData['original'])):
            $printTypes[] = "ORIGINAL";
        endif;

        if(!empty($postData['duplicate'])):
            $printTypes[] = "DUPLICATE";
        endif;

        if(!empty($postData['triplicate'])):
            $printTypes[] = "TRIPLICATE";
        endif;

        if(!empty($postData['extra_copy'])):
            for($i=1;$i<=$postData['extra_copy'];$i++):
                $printTypes[] = "EXTRA COPY";
            endfor;
        endif;
        $postData['header_footer'] = (!empty($postData['header_footer']))?1:0;
        $this->data['header_footer'] = $postData['header_footer'];

        $inv_id = (!empty($id))?$id:$postData['id'];

		$this->data['invData'] = $invData = $this->debitNote->getDebitNote(['id'=>$inv_id,'itemList'=>1]);
		$this->data['partyData'] = $this->party->getParty(['id'=>$invData->party_id]);
        $type = ($invData->order_type == "Increase Sales")?2:1;
        $this->data['taxList'] = $this->taxMaster->getActiveTaxList($type);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList($type);
		$this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();
		$response="";
		$logo=base_url('assets/images/logo.png');
		$this->data['letter_head']=base_url('assets/images/letterhead-top.png');
				
        $pdfData = "";
        $countPT = count($printTypes); $i=0;
        foreach($printTypes as $printType):
            ++$i;
            $this->data['printType'] = $printType;
            $this->data['maxLinePP'] = (!empty($postData['max_lines']))?$postData['max_lines']:(($postData['header_footer'] == 1)?18:10);
		    $pdfData .= $this->load->view('debit_note/print',$this->data,true);
            if($i != $countPT): $pdfData .= "<pagebreak>"; endif;
        endforeach;
            
		$mpdf = new \Mpdf\Mpdf();
		$pdfFileName = str_replace(["/","-"," "],"_",$invData->trans_number).'.pdf';
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css?v='.time()));
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->SetWatermarkImage($logo,0.03,array(120,45));
		$mpdf->showWatermarkImage = true;
		$mpdf->SetProtection(array('print'));
		
		$mpdf->AddPage('P','','','','',8,8,(($postData['header_footer'] == 1)?5:20),(($postData['header_footer'] == 1)?5:40),5,5,'','','','','','','','','','A4-P');
		$mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName,'I');
    }
}
?>