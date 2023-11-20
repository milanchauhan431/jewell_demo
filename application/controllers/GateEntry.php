<?php
class GateEntry extends MY_Controller{
    private $indexPage = "gate_entry/index";
    private $form = "gate_entry/form";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Gate Entry Register";
		$this->data['headData']->controller = "gateEntry";  
        $this->data['headData']->pageUrl = "gateEntry";      
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'gateEntry']);
    }

    public function index(){        
        $this->data['tableHeader'] = getStoreDtHeader("gateEntry");
		$this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post(); $data['status'] = $status;
        $result = $this->gateEntry->getDTRows($data);
        $sendData = array();$i=($data['start'] + 1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;    
            $row->status = $status;
            $sendData[] = getGateEntryData($row);
        endforeach;        
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addGateEntry(){
        $this->data['transportList'] = $this->transport->getTransportList();
        $this->data['vehicleTypeList'] = $this->vehicleType->getVehicleTypeList();
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>[1,2,3]]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>[2,3]]);
        $this->data['trans_no'] = $this->transMainModel->getMirNextNo();
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;//n2y(getFyDate("Y"));
        $this->data['trans_number'] = $this->data['trans_prefix'].sprintf("%04d",$this->data['trans_no']);
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        /* if(empty($data['driver_name']))
            $errorMessage['driver_name'] = "Driver Name is required.";

        if(empty($data['driver_contact']))
            $errorMessage['driver_contact'] = "Driver Contact No. is required."; */

        if(empty($data['vehicle_type']))
            $errorMessage['vehicle_type'] = "vehicle Type is required.";

        if(empty($data['lr']))
            $errorMessage['lr'] = "LR No. is required.";

        if(empty($data['inv_no']) && empty($data['doc_no']))
            $errorMessage['inv_no'] = "Invoice No OR Challan No is required";

        if(!empty($data['inv_no']) && empty($data['inv_date']))
            $errorMessage['inv_date'] = "Invoice Date is required";

        if(!empty($data['doc_no']) && empty($data['doc_date']))
            $errorMessage['doc_date'] = "Challan Date is required";

        if(!empty($data['vehicle_no']) && strlen(trim($data['vehicle_no'])) < 7 || !empty($data['vehicle_no']) && strlen(trim($data['vehicle_no'])) > 10)
            $errorMessage['vehicle_no'] = "Invalid Vehicle No.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if(empty($data['id'])):
                $data['trans_prefix'] = $this->data['entryData']->trans_prefix;//n2y(getFyDate("Y"));;
                $data['trans_no'] = $this->transMainModel->getMirNextNo();
                $data['trans_number'] = $data['trans_prefix'].sprintf("%04d",$data['trans_no']);
            endif;

            /* $data['driver_name'] = ucwords($data['driver_name']); */
            $data['vehicle_no'] = (!empty($data['vehicle_no']))?strtoupper($data['vehicle_no']):"";
            $data['inv_date'] = (!empty($data['inv_date']))?$data['inv_date']:null;
            $data['doc_date'] = (!empty($data['doc_date']))?$data['doc_date']:null;
            $data['entry_type'] = $this->data['entryData']->id;
            $this->printJson($this->gateEntry->save($data));
        endif;
    }

    public function edit(){
        $id = $this->input->post('id');
        $this->data['transportList'] = $this->transport->getTransportList();
        $this->data['vehicleTypeList'] = $this->vehicleType->getVehicleTypeList();
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>[1,2,3]]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>[2,3]]);
        $this->data['dataRow'] = $this->gateEntry->getGateEntry($id);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->gateEntry->delete($id));
        endif;
    }
}
?>