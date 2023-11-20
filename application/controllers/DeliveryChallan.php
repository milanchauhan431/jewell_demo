<?php
class DeliveryChallan extends MY_Controller{
    private $indexPage = "delivery_challan/index";
    private $form = "delivery_challan/form";    

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Delivery Challan";
		$this->data['headData']->controller = "deliveryChallan";        
        $this->data['headData']->pageUrl = "deliveryChallan";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'deliveryChallan']);
	}

    public function index(){
        $this->data['tableHeader'] = getSalesDtHeader("deliveryChallan");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();$data['status'] = $status;
        $data['entry_type'] = $this->data['entryData']->id;
        $result = $this->deliveryChallan->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getDeliveryChallanData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addDeliveryChallan(){
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
        $this->data['salesAccounts'] = $this->party->getPartyList(['system_code'=>$this->salesTypeCodes]);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->data['salesExecutives'] = $this->employee->getEmployeeList();
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
                $serialNo = $row['masterData']['t_col_1'];
                if($row['stock_eff'] == 1):
                    $postData = ['location_id' => $row['masterData']['i_col_1'],'batch_no' => $row['masterData']['t_col_1'],'item_id' => $row['item_id'],'stock_required'=>1,'single_row'=>1];
                    
                    $stockData = $this->itemStock->getItemStockBatchWise($postData);  
                    
                    $stockQty = (!empty($stockData->qty))?$stockData->qty:0;
                    if(!empty($row['id'])):
                        $oldItem = $this->deliveryChallan->getDeliveryChallanItem(['id'=>$row['id']]);
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
            $this->printJson($this->deliveryChallan->save($data));
        endif;
    }

    public function edit($id){
        $this->data['dataRow'] = $dataRow = $this->deliveryChallan->getDeliveryChallan(['id'=>$id,'itemList'=>1]);
        $this->data['gstinList'] = $this->party->getPartyGSTDetail(['party_id' => $dataRow->party_id]);
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => 1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
        $this->data['salesAccounts'] = $this->party->getPartyList(['system_code'=>$this->salesTypeCodes]);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->data['salesExecutives'] = $this->employee->getEmployeeList();
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->deliveryChallan->delete($id));
        endif;
    }

    public function printChallan($id,$pdf_type=''){
        $this->data['dataRow'] = $dataRow = $this->salesOrder->getSalesOrder(['id'=>$id,'itemList'=>1]);
        $this->data['partyData'] = $this->party->getParty(['id'=>$dataRow->party_id]);
        $this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();
        
        $logo = base_url('assets/images/logo.png');
        $this->data['letter_head'] =  base_url('assets/images/letterhead-top.png');
        
        $pdfData = $this->load->view('delivery_challan/print', $this->data, true);        
        
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

    public function getPartyChallan(){
        $data = $this->input->post();
        $this->data['orderItems'] = $this->deliveryChallan->getPendingChallanItems($data);
        $this->load->view('delivery_challan/create_invoice',$this->data);
    }
}
?>