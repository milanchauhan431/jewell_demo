<?php
class SalesInvoice extends MY_Controller{
    private $indexPage = "sales_invoice/index";
    private $form = "sales_invoice/form";    

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Sales Invoice";
		$this->data['headData']->controller = "salesInvoice";        
        $this->data['headData']->pageUrl = "salesInvoice";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'salesInvoice']);
	}

    public function index(){
        $this->data['tableHeader'] = getAccountingDtHeader("salesInvoice");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();$data['status'] = $status;
        $data['entry_type'] = $this->data['entryData']->id;
        $result = $this->salesInvoice->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getSalesInvoiceData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addInvoice(){
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
        $this->data['salesAccounts'] = $this->party->getPartyList(['system_code'=>$this->salesTypeCodes]);
        $this->data['salesExecutives'] = $this->employee->getEmployeeList();
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
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
            $bQty = array();
            foreach($data['itemData'] as $key => $row):
                $serialNo = $row['masterData']['t_col_1'];
                if($row['stock_eff'] == 1):
                    $postData = ['location_id' => $row['masterData']['i_col_1'],'batch_no' => $row['masterData']['t_col_1'],'item_id' => $row['item_id'],'stock_required'=>1,'single_row'=>1];
                    
                    $stockData = $this->itemStock->getItemStockBatchWise($postData);  
                    
                    $stockQty = (!empty($stockData->qty))?$stockData->qty:0;
                    if(!empty($row['id'])):
                        $oldItem = $this->salesInvoice->getSalesInvoiceItem(['id'=>$row['id']]);
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
            endforeach;
        endif;
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['vou_name_l'] = $this->data['entryData']->vou_name_long;
            $data['vou_name_s'] = $this->data['entryData']->vou_name_short;

			$attachments = array();
            if(!empty($data['id']) && !empty($data['attachment'])):
                $attachments = $data['attachment']; unset($data['attachment']);
            endif;

            if(!empty($_FILES['attachments']['name'][0])):
                $this->load->library('upload');
                $this->load->library('image_lib'); 

                $errorMessage['attachment_error'] = "";
                
                foreach($_FILES['attachments']['name'] as $key => $fileName):
                    $_FILES['userfile']['name']     = $fileName;
                    $_FILES['userfile']['type']     = $_FILES['attachments']['type'][$key];
                    $_FILES['userfile']['tmp_name'] = $_FILES['attachments']['tmp_name'][$key];
                    $_FILES['userfile']['error']    = $_FILES['attachments']['error'][$key];
                    $_FILES['userfile']['size']     = $_FILES['attachments']['size'][$key];

                    $imagePath = realpath(APPPATH . '../assets/uploads/sales_document/');

                    $fileName = preg_replace('/[^A-Za-z0-9]+/', '_', strtolower($fileName));
                    $config = ['file_name' => time()."_IR_".$fileName,'allowed_types' => 'jpg|jpeg|png|gif|JPG|JPEG|PNG','max_size' => 10240,'overwrite' => FALSE, 'upload_path' => $imagePath];                

                    $this->upload->initialize($config);
                    if(!$this->upload->do_upload()):
                        $errorMessage['attachment_error'] .= $fileName." => ". $this->upload->display_errors();
                    else:
                        $uploadData = $this->upload->data();
                        $attachments[] = $uploadData['file_name'];

                        $imgConfig['image_library'] = 'gd2';
                        $imgConfig['source_image'] = $uploadData['full_path'];
                        $imgConfig['maintain_ratio'] = TRUE;
                        $imgConfig['width'] = 640;
                        $imgConfig['height'] = 480;
                        $imgConfig['quality'] = "50%";

                        $this->image_lib->clear();
                        $this->image_lib->initialize($imgConfig);                   

                        if(!$this->image_lib->resize()):
                            $errorMessage['attachment_error'] .= $fileName." => ". $this->image_lib->display_errors();
                        endif;
                    endif;
                endforeach;

                if(!empty($errorMessage['attachment_error'])):
                    foreach($attachments as $file):
                        if(file_exists($imagePath.'/'.$file)): unlink($imagePath.'/'.$file); endif;
                    endforeach;
                    $this->printJson(['status'=>0,'message'=>$errorMessage]);
                endif;                
            endif;

            if(!empty($attachments)):
                $data['attachments'] = implode(",",$attachments);
            else:
                $data['attachments'] = "";
            endif;
            
            $this->printJson($this->salesInvoice->save($data));
        endif;
    }

    public function edit($id){
        $this->data['dataRow'] = $dataRow = $this->salesInvoice->getSalesInvoice(['id'=>$id,'itemList'=>1]);
        $this->data['gstinList'] = $this->party->getPartyGSTDetail(['party_id' => $dataRow->party_id]);
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => 1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
        $this->data['salesAccounts'] = $this->party->getPartyList(['system_code'=>$this->salesTypeCodes]);
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->data['ledgerList'] = $this->party->getPartyList(['group_code'=>["'DT'","'ED'","'EI'","'ID'","'II'"]]);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesInvoice->delete($id));
        endif;
    }

    public function printInvoice($id="",$type=""){
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

		$this->data['invData'] = $invData = $this->salesInvoice->getSalesInvoice(['id'=>$inv_id,'itemList'=>1]);
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
            $this->data['maxLinePP'] = (!empty($postData['max_lines']))?$postData['max_lines']:(($postData['header_footer'] == 1)?18:10);
		    $pdfData .= $this->load->view('sales_invoice/print',$this->data,true);
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
		/* $mpdf->SetHTMLHeader($htmlHeader);
		$mpdf->SetHTMLFooter($htmlFooter); */
		$mpdf->AddPage('P','','','','',8,8,(($postData['header_footer'] == 1)?5:20),(($postData['header_footer'] == 1)?5:40),5,5,'','','','','','','','','','A4-P');
		$mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName,'I');
	}

    public function getPartyInvoiceItems(){
        $data = $this->input->post();
        $this->data['orderItems'] = $this->salesInvoice->getPendingInvoiceItems($data);
        $this->load->view('credit_note/create_creditnote',$this->data);
    }
}
?>