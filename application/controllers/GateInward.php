<?php
class GateInward extends MY_Controller{
    private $indexPage = "gate_inward/index";
    private $form = "gate_inward/form";
    private $inspectionFrom = "gate_inward/material_inspection";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Gate Inward Register";
		$this->data['headData']->controller = "gateInward";
        $this->data['headData']->pageUrl = "gateInward";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'gateInward']);
    }

    public function index(){
        $this->data['tableHeader'] = getStoreDtHeader("pendingGE");
		$this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($type = 1,$status = 0){
        $data = $this->input->post();
        $data['trans_type'] = $type;
        $data['trans_status'] = $status;

        $result = $this->gateInward->getDTRows($data);
        $sendData = array();$i=($data['start']+1);

        foreach($result['data'] as $row):
            $row->sr_no = $i++;        
            $row->controller = $this->data['headData']->controller;
            $sendData[] = getGateInwardData($row);
        endforeach;

        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function createGI(){
        $data = $this->input->post();
        $gateEntryData = $this->gateEntry->getGateEntry($data['id']);
        $this->data['gateEntryData'] = $gateEntryData;
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>[1,2,3]]);
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['locationList'] = $this->storeLocation->getStoreLocationList(['store_type'=>'0,15','final_location'=>1]);
        $this->data['materialGradeList'] = $this->materialGrade->getMaterialGrades();
        $this->data['trans_no'] = $this->transMainModel->getMirNextNo(2);
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;//.n2y(getFyDate("Y"));
        $this->data['trans_number'] = $this->data['trans_prefix'].sprintf("%04d",$this->data['trans_no']);
        $this->load->view($this->form,$this->data);
    }

    public function addGateInward(){
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>[1,2,3]]);
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['locationList'] = $this->storeLocation->getStoreLocationList(['store_type'=>'0,15','final_location'=>1]);
        $this->data['materialGradeList'] = $this->materialGrade->getMaterialGrades();
        $this->data['trans_no'] = $this->transMainModel->getMirNextNo(2);
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;//.n2y(getFyDate("Y"));
        $this->data['trans_number'] = $this->data['trans_prefix'].sprintf("%04d",$this->data['trans_no']);
        $this->load->view($this->form,$this->data);
    }

    /* public function getPoNumberListOnItemId(){
        $data = $this->input->post();
        $poList = $this->purchaseOrder->getItemWisePoList($data);

        $options = '<option value="">Select Purchase Order</option>';
        foreach($poList as $row):
            $options .= '<option value="'.$row->po_trans_id.'" data-po_id="'.$row->po_id.'" data-po_no="'.$row->trans_number.'" data-price="'.$row->price.'" data-disc_per="'.$row->disc_per.'">'.$row->trans_number.' [ Pending Qty : '.$row->pending_qty.' ]</option>';
        endforeach;

        $this->printJson(['status'=>1,'poOptions'=>$options]);
    } */

    public function getPoNumberList(){
        $data = $this->input->post();
        $poList = $this->purchaseOrder->getPartyWisePoList($data);

        $options = '<option value="">Select Purchase Order</option>';
        foreach($poList as $row):
            $options .= '<option value="'.$row->po_id.'" data-po_no="'.$row->trans_number.'" >'.$row->trans_number.'</option>';
        endforeach;

        $this->printJson(['status'=>1,'poOptions'=>$options]);
    }

    public function getItemList(){
        $data = $this->input->post();

        $options = '<option value="">Select Item Name</option>';
        if(empty($data['po_id'])):
            $options .= getItemListOption($this->item->getItemList());
        else:
            $itemList = $this->purchaseOrder->getPendingPoItems($data);

            foreach($itemList as $row):
                $options .= '<option value="'.$row->item_id.'" data-po_trans_id="'.$row->po_trans_id.'" data-price="'.$row->price.'" data-disc_per="'.$row->disc_per.'">[ '.$row->item_code.' ] '.$row->item_name.' [ Pending Qty : '.$row->pending_qty.' ]</option>';
            endforeach;
        endif;

        $this->printJson(['status'=>1,'itemOptions'=>$options]);
    }

    public function save(){
        $data = $this->input->post(); 
        $errorMessage = array();
        
        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Party Name is required.";
        if(empty($data['batchData']))
            $errorMessage['batch_details'] = "Item Details is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->gateInward->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $gateInward = $this->gateInward->getGateInward($data['id']);
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>[1,2,3]]);
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['locationList'] = $this->storeLocation->getStoreLocationList(['store_type'=>'0,15','final_location'=>1]);
        $this->data['gateInwardData'] = $gateInward;
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->gateInward->delete($id));
        endif;
    }
    
    public function ir_print($id){
        $irData = $this->gateInward->getGateInward($id);  
        $countData = count($irData->itemData);
		$itemList="";$i=1;
		
        $logo=base_url('assets/images/logo.png');

        foreach($irData->itemData as $row):
			$itemList .='<style>.top-table-border th,.top-table-border td{font-size:12px;}</style>
            <table class="table">
                <tr>
                    <td><img src="'.$logo.'" style="max-height:40px;"></td>
                    <td class="org_title text-right" style="font-size:18px;">IIR Tag</td>
                </tr>
            </table>
            <table class="table top-table-border">
                <tr> 
                    <th>GI No</th>
                    <td>'.$irData->trans_number.'</td>
                    <th>GI Date</th>
                    <td>'.date("d-m-Y h:i:s a", strtotime($irData->trans_date)).'</td>
                </tr>
                <tr> 
                    <th>Part Name</th>
                    <td colspan="3">'.$row->item_name.'</td>
                </tr>
                <tr> 
                    <th>Supplier</th>
                    <td colspan="3">'.$irData->party_name.'</td>
                </tr>
                <tr> 
                    <th> Batch No</th>
                    <td>'.$row->batch_no.'  </td>
                    <th>Batch Qty</th>
                    <td>'.$row->qty.' </td>
                </tr>
                <tr> 
                    <th>Heat No </th>
                    <td>'.$row->heat_no.' </td>
                    <th>Pallate No.</th>
                    <td>'.$i.'/'.$countData.'</td>
                </tr>
                <tr> 
                    <th>Printed At</th>
                    <td colspan="3">'.date("d-m-Y h:i:s a").'</td>
                </tr>
            </table>'; $i++;
        endforeach;
		
        $pdfData = '<div style="width:100mm;height:25mm;">'.$itemList.'</div>';
        		
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [100, 58]]);
		$pdfFileName='IR_PRINT.pdf';
		$stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->SetProtection(array('print'));
		$mpdf->AddPage('P','','','','',2,2,2,2,2,2);
		$mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName,'I');
    }

    public function materialInspection(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->gateInward->getGateInwardItems($data['id']);
        $this->load->view($this->inspectionFrom,$this->data);
    }

    public function saveInspectedMaterial(){
        $data = $this->input->post();
        $this->printJson($this->gateInward->saveInspectedMaterial($data));
    }

    public function getPartyInwards(){
        $data = $this->input->post();
        $this->data['orderItems'] = $this->gateInward->getPendingInwardItems($data);
        $this->load->view('purchase_invoice/create_invoice',$this->data);
    }
}
?>