<?php
class Estimate extends MY_Controller{
    private $indexPage = "estimate/index";
    private $form = "estimate/form";    

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Estimate";
		$this->data['headData']->controller = "estimate";        
        $this->data['headData']->pageUrl = "estimate";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'estimate']);
	}

    public function index(){
        $this->data['tableHeader'] = getSalesDtHeader("estimate");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();$data['status'] = $status;
        $data['entry_type'] = $this->data['entryData']->id;
        $result = $this->estimate->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getEstimateData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addEstimate(){
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
		$this->data['taxList'] = array();//$this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
		$this->data['ledgerList'] = $this->party->getPartyList(["'DT'","'ED'","'EI'","'ID'","'II'"]);
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Party Name is required.";
        if(empty($data['itemData'])):
            $errorMessage['itemData'] = "Item Details is required.";
        else:
            $bQty = array();
            foreach($data['itemData'] as $key => $row):
                if(!empty(floatVal($row['qty'])) && !empty($row['size'])):
                    if(is_int(($row['qty'] / $row['packing_qty'])) == false):
                        $errorMessage['qty'.$key] = "Invalid qty against packing standard.";
                    endif;
                endif;

                if($row['stock_eff'] == 1):
                    $postData = ['location_id' => $this->RTD_STORE->id,'batch_no' => "GB",'item_id' => $row['item_id'],'stock_required'=>1,'single_row'=>1];
                    
                    $stockData = $this->itemStock->getItemStockBatchWise($postData);  
                    
                    $stockQty = (!empty($stockData->qty))?$stockData->qty:0;
                    if(!empty($row['id'])):
                        $oldItem = $this->estimate->getEstimateItem(['id'=>$row['id']]);
                        $stockQty = $stockQty + $oldItem->qty;
                    endif;
                    
                    if(!isset($bQty[$row['item_id']])):
                        $bQty[$row['item_id']] = $row['qty'] ;
                    else:
                        $bQty[$row['item_id']] += $row['qty'];
                    endif;

                    if(empty($stockQty)):
                        $errorMessage['qty'.$key] = "Stock not available.";
                    else:
                        if($bQty[$row['item_id']] > $stockQty):
                            $errorMessage['qty'.$key] = "Stock not available.";
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
            $this->printJson($this->estimate->save($data));
        endif;
    }

    public function edit($id){
        $this->data['dataRow'] = $dataRow = $this->estimate->getEstimate(['id'=>$id,'itemList'=>1]);
        $this->data['gstinList'] = $this->party->getPartyGSTDetail(['party_id' => $dataRow->party_id]);
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => 1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
		$this->data['taxList'] = array();//$this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->data['ledgerList'] = $this->party->getPartyList(["'DT'","'ED'","'EI'","'ID'","'II'"]);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->estimate->delete($id));
        endif;
    }

    public function printInvoice($id="",$type=""){
        $postData = $this->input->post();
        //print_r($postData);exit;
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

        $inv_id = (!empty($id))?$id:$postData['id'];

		$this->data['invData'] = $invData = $this->estimate->getEstimate(['id'=>$inv_id,'itemList'=>1]);
		$this->data['partyData'] = $this->party->getParty(['id'=>$invData->party_id]);
        $this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
		$this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();
		$response="";
		$logo=base_url('assets/images/logo.png');
		$this->data['letter_head']=base_url('assets/images/letterhead-top.png');
				
        $pdfData = "";
        $countPT = count($printTypes); $i=0;
        foreach($printTypes as $printType):
            ++$i;
            $this->data['printType'] = $printType;
            $this->data['maxLinePP'] = (!empty($postData['max_lines']))?$postData['max_lines']:14;
		    $pdfData .= $this->load->view('sales_invoice/print',$this->data,true);
            if($i != $countPT): $pdfData .= "<pagebreak>"; endif;
        endforeach;

        //print_r($pdfData);exit;
		
		$htmlHeader = '<img src="'.$this->data['letter_head'].'" class="img">';

		$htmlFooter = '<table>
            <tr>
                <th colspan="2" style="vertical-align:bottom;text-align:right;font-size:1rem;padding:5px 2px;">
                    For, ' . $companyData->company_name . '<br>
                </th>
            </tr>
            <tr>
                <td colspan="2" height="35"></td>
            </tr>
            <tr>
                <td colspan="2" style="vertical-align:bottom;text-align:right;font-size:1rem;padding:5px 2px;"><b>Authorised Signature</b></td>
            </tr>
        </table>        
        <table class="table top-table" style="margin-top:10px; border-top:1px solid #545454;">
            <tr>
                <td style="width:25%;font-size:12px;">This is computer generated invoice.</td>
                <!--<td style="width:50%;text-align:left;">Inv No. & Date : '.$invData->trans_number.' ['.formatDate($invData->trans_date).']</td>
                <td style="width:50%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>-->
            </tr>
        </table>';
        
            
		$mpdf = new \Mpdf\Mpdf();
		$pdfFileName = str_replace(["/","-"," "],"_",$invData->trans_number).'.pdf';
		/* $stylesheet = file_get_contents(base_url('assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css'));
        $stylesheet = file_get_contents(base_url('assets/css/style.css?v=' . time())); */
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css?v='.time()));
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->SetWatermarkImage($logo,0.03,array(120,45));
		$mpdf->showWatermarkImage = true;
		$mpdf->SetProtection(array('print'));
		
		$mpdf->SetHTMLHeader($htmlHeader);
		$mpdf->SetHTMLFooter($htmlFooter);
		$mpdf->AddPage('P','','','','',10,5,38,30,5,5,'','','','','','','','','','A4-P');
		$mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName,'I');
	}
}
?>