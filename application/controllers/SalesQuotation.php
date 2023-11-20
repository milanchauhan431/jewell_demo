<?php
class SalesQuotation extends MY_Controller{
    private $indexPage = "sales_quotation/index";
    private $form = "sales_quotation/form";
    private $revHistory = "sales_quotation/revision_history";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Sales Quotation";
		$this->data['headData']->controller = "salesQuotation";        
        $this->data['headData']->pageUrl = "salesQuotation";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'salesQuotation']);
	}

    public function index(){
        $this->data['tableHeader'] = getSalesDtHeader("salesQuotation");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();$data['status'] = $status;
        $data['entry_type'] = $this->data['entryData']->id;
        $result = $this->salesQuotation->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getSalesQuotationData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function create($jsonData){
        $postData = (Array) decodeURL($jsonData);
        $dataRow = $this->salesEnquiry->getSalesEnquiry(['id'=>$postData['enq_id'],'itemList'=>1]);
        $this->data['gstinList'] = $this->party->getPartyGSTDetail(['party_id' => $dataRow->party_id]);
        
        $dataRow->from_entry_type = $dataRow->entry_type;
        $dataRow->ref_id = $dataRow->id;
        $dataRow->entry_type = "";
        $dataRow->id = "";
        $dataRow->trans_prefix = "";
        $dataRow->trans_no = "";
        $dataRow->trans_number = "";

        $itemList = array();
        foreach($dataRow->itemList as $row):
            $row->from_entry_type = $row->entry_type;
            $row->ref_id = $row->id;
            $row->entry_type = "";
            $row->id = "";
            $row->taxable_amount = $row->amount = round(($row->qty * $row->price),2);
            if(!empty($row->disc_per) && !empty($row->amount)):
                $row->disc_amount = round((($row->disc_per * $row->amount) / 100),2);
                $row->taxable_amount = $row->taxable_amount - $row->disc_amount;
            endif;

            $row->net_amount = $row->taxable_amount;
            if(!empty($row->taxable_amount) && !empty($row->gst_per)):
                $row->gst_amount = round((($row->gst_per * $row->taxable_amount) / 100),2);

                $row->igst_per = $row->gst_per;
                $row->igst_amount = $row->gst_amount;

                $row->cgst_per = round(($row->gst_per / 2),2);
                $row->cgst_amount = round(($row->gst_amount / 2),2);
                $row->sgst_per = round(($row->gst_per / 2),2);
                $row->sgst_amount = round(($row->gst_amount / 2),2);

                $row->net_amount = $row->taxable_amount + $row->gst_amount;
            endif;

            $itemList[] = $row;
        endforeach;
        $dataRow->itemList = $itemList;
        
        $this->data['dataRow'] = $dataRow;
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => 1,'party_type'=>"0,1"]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1,'active_item'=>"1,2"]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->data['salesExecutives'] = $this->employee->getEmployeeList();
        $this->load->view($this->form,$this->data);
    }

    public function addQuotation(){
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => 1,'party_type'=>"0,1"]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1,'active_item'=>"1,2"]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->data['salesExecutives'] = $this->employee->getEmployeeList();
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Party Name is required.";
        if(empty($data['itemData']))
            $errorMessage['itemData'] = "Item Details is required.";
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if(empty($data['id'])):
                $data['trans_no'] = $this->transMainModel->nextTransNo($data['entry_type']);
                $data['trans_number'] = $data['trans_prefix'].$data['trans_no'];
            endif;
            
            if (!empty($data['is_rev'])) :
                $data['doc_date'] = date('Y-m-d');
            else :
                $data['doc_date'] = formatDate($data['trans_date'], 'Y-m-d');
            endif;

            $data['vou_name_l'] = $this->data['entryData']->vou_name_long;
            $data['vou_name_s'] = $this->data['entryData']->vou_name_short;
            $this->printJson($this->salesQuotation->save($data));
        endif;
    }

    public function edit($id){
        $this->data['dataRow'] = $dataRow = $this->salesQuotation->getSalesQuotation(['id'=>$id,'itemList'=>1]);
        $this->data['gstinList'] = $this->party->getPartyGSTDetail(['party_id' => $dataRow->party_id]);
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => 1,'party_type'=>"0,1"]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1,'active_item'=>"1,2"]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->data['salesExecutives'] = $this->employee->getEmployeeList();
        $this->load->view($this->form,$this->data);
    }

    public function reviseQuotation($id){
        $this->data['is_rev'] = 1;
        $this->data['dataRow'] = $dataRow = $this->salesQuotation->getSalesQuotation(['id'=>$id,'itemList'=>1]);
        $this->data['gstinList'] = $this->party->getPartyGSTDetail(['party_id' => $dataRow->party_id]);
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => 1,'party_type'=>"0,1"]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1,'active_item'=>"1,2"]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->data['salesExecutives'] = $this->employee->getEmployeeList();
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesQuotation->delete($id));
        endif;
    }

    public function revisionHistory(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->salesQuotation->getQuotationRevisionList($data);
        $this->load->view($this->revHistory,$this->data);
    }

    public function printPdfQuotation($id,$pdf_type=''){
        $this->data['dataRow'] = $dataRow = $this->salesQuotation->getSalesQuotation(['id'=>$id,'itemList'=>1]);
        $this->data['partyData'] = $this->party->getParty(['id'=>$dataRow->party_id]);
        $this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();
        
        $logo = base_url('assets/images/logo.png');
        $this->data['letter_head'] =  base_url('assets/images/letterhead-top.png');
        
        $pdfData = $this->load->view('sales_quotation/print', $this->data, true);
        
        
        $htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
            <tr>
                <td style="width:25%;">Qtn. No. & Date : '.$dataRow->trans_number . ' [' . formatDate($dataRow->trans_date) . ']</td>
                <td style="width:25%;"></td>
                <td style="width:25%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
            </tr>
        </table>';
        
		$mpdf = new \Mpdf\Mpdf();
		$filePath = realpath(APPPATH . '../assets/uploads/sales_quotation/');
        $pdfFileName = $filePath.'/' . str_replace(["/","-"],"_",$dataRow->trans_number) . '.pdf';
        
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css?v='.time()));
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetWatermarkImage($logo, 0.03, array(120, 120));
        $mpdf->showWatermarkImage = true;
        $mpdf->SetHTMLFooter($htmlFooter);
		$mpdf->AddPage('P','','','','',7,5,5,5,3,3,'','','','','','','','','','A4-P');
        $mpdf->WriteHTML($pdfData);
		
		ob_clean();
		$mpdf->Output($pdfFileName, 'I');
		
    }

    public function printQuotation($id,$pdf_type=''){
        $this->data['dataRow'] = $dataRow = $this->salesQuotation->getSalesQuotation(['id'=>$id,'itemList'=>1]);
        $this->data['partyData'] = $this->party->getParty(['id'=>$dataRow->party_id]);
        $this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();
        
        $logo = base_url('assets/images/logo.png');
      
		$pdfData = $this->load->view('sales_quotation/print_pos',$this->data,true);
		$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8' ]);
		$filePath = realpath(APPPATH . '../assets/uploads/sales_quotation/');
        $pdfFileName =  str_replace(["/","-"],"_",$dataRow->trans_number) . '.pdf';
        $mpdf->SetDisplayMode('fullpage');
       	$mpdf->AddPage();
        $mpdf->WriteHTML($pdfData); 
		$y = $mpdf->y;

		//echo $y; exit;
		
		$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8' ]);
		$filePath = realpath(APPPATH . '../assets/uploads/sales_quotation/'); 
        
        
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->AddPage('P','','','','',1,0,0,0,1,1,'','','','','','','','','',[56, $y+10]);
		//$mpdf->AddPage('P', '', '', '', '', 1, 1, 1, 1, 1, 1);
        $mpdf->WriteHTML($pdfData); 
		
		
		
		
		ob_clean(); 
		$mpdf->Output($pdfFileName, 'D');
    }
    
    public function getPartyQuotation(){
        $data = $this->input->post();
        $this->data['orderItems'] = $this->salesQuotation->getPendingQuotationItems($data);
        $this->load->view('sales_order/create_order',$this->data);
    }
}
?>