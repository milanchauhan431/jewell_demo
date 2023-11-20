<?php
class SalesOrders extends MY_Controller{
    private $indexPage = "sales_order/index";
    private $form = "sales_order/form";    

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Sales Order";
		$this->data['headData']->controller = "salesOrders";        
        $this->data['headData']->pageUrl = "salesOrders";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'salesOrders']);
	}

    public function index(){
        $this->data['tableHeader'] = getSalesDtHeader("salesOrders");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();$data['status'] = $status;
        $data['entry_type'] = $this->data['entryData']->id;
        $result = $this->salesOrder->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getSalesOrderData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addOrder(){
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Party Name is required.";
        /* if(empty($data['order_type']))
            $errorMessage['order_type'] = "Production Type is required."; */
        if(empty($data['itemData']))
            $errorMessage['itemData'] = "Item Details is required.";
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            /* $this->load->library('upload');
            $filePath = realpath(APPPATH . '../assets/uploads/sales_order');

            $itemData = array();
            foreach($data['itemData'] as $key => $row):
                if(isset($_FILES['itemData']['name'][$key]['attachment']) && !empty($_FILES['itemData']['name'][$key]['attachment'])):
                    $_FILES['userfile']['name']     = $_FILES['itemData']['name'][$key]['attachment'];
                    $_FILES['userfile']['type']     = $_FILES['itemData']['type'][$key]['attachment'];
                    $_FILES['userfile']['tmp_name'] = $_FILES['itemData']['tmp_name'][$key]['attachment'];
                    $_FILES['userfile']['error']    = $_FILES['itemData']['error'][$key]['attachment'];
                    $_FILES['userfile']['size']     = $_FILES['itemData']['size'][$key]['attachment'];

                    $config = ['file_name' => time()."_soi_".$_FILES['userfile']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$filePath];

                    $this->upload->initialize($config);
                    if(!$this->upload->do_upload()):
                        $errorMessage['ba_file_'.$key] = $this->upload->display_errors();
                        $this->printJson(["status"=>0,"message"=>$errorMessage]);
                    else:
                        $uploadData = $this->upload->data();
                        $row['attachment'] = $uploadData['file_name'];
                    endif;
                endif;

                if(!empty($row['id']) && $row['attachment_status'] == 2):
                    $soItem = $this->salesOrder->getSalesOrderItem(['id'=>$row['id']]);
                    if(!empty($soItem->attachment)):
                        if(file_exists($filePath."/".$soItem->attachment)):
                            unlink($filePath."/".$soItem->attachment);
                        endif;
                    endif;

                    $row['attachment'] = "";
                endif;

                unset($row['attachment_status']);

                $itemData[] = $row;
            endforeach;
            $data['itemData'] = $itemData; */

            $data['vou_name_l'] = $this->data['entryData']->vou_name_long;
            $data['vou_name_s'] = $this->data['entryData']->vou_name_short;
            $this->printJson($this->salesOrder->save($data));
        endif;
    }

    public function edit($id){
        $this->data['dataRow'] = $dataRow = $this->salesOrder->getSalesOrder(['id'=>$id,'itemList'=>1]);
        $this->data['gstinList'] = $this->party->getPartyGSTDetail(['party_id' => $dataRow->party_id]);
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => 1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesOrder->delete($id));
        endif;
    }

    public function printOrder($id,$pdf_type=''){
        $this->data['dataRow'] = $dataRow = $this->salesOrder->getSalesOrder(['id'=>$id,'itemList'=>1]);
        $this->data['partyData'] = $this->party->getParty(['id'=>$dataRow->party_id]);
        $this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();
        
        $logo = base_url('assets/images/logo.png');
        $this->data['letter_head'] =  base_url('assets/images/letterhead-top.png');
        
        $pdfData = $this->load->view('sales_order/print', $this->data, true);        
        
        $htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
            <tr>
                <td style="width:25%;">SO. No. & Date : '.$dataRow->trans_number . ' [' . formatDate($dataRow->trans_date) . ']</td>
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
		$mpdf->AddPage('P','','','','',10,5,5,15,5,5,'','','','','','','','','','A4-P');
        $mpdf->WriteHTML($pdfData);
		
		ob_clean();
		$mpdf->Output($pdfFileName, 'I');
		
    }

    public function getPartyOrders(){
        $data = $this->input->post();
        $this->data['orderItems'] = $this->salesOrder->getPendingOrderItems($data);
        $this->load->view('sales_invoice/create_invoice',$this->data);
    }
}
?>