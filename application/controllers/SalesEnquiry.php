<?php
class SalesEnquiry extends MY_Controller{
    private $indexPage = "sales_enquiry/index";
    private $form = "sales_enquiry/form";    

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Sales Enquiry";
		$this->data['headData']->controller = "salesEnquiry";        
        $this->data['headData']->pageUrl = "salesEnquiry";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'salesEnquiry']);
	}

    public function index(){
        $this->data['tableHeader'] = getSalesDtHeader("salesEnquiry");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();$data['status'] = $status;
        $data['entry_type'] = $this->data['entryData']->id;
        $result = $this->salesEnquiry->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getSalesEnquiryData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function create($jsonData){
        $postData = (Array) decodeURL($jsonData);
        $this->data['party_id'] = $postData['party_id'];
        $this->data['vou_acc_id'] = $postData['lead_id'];
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>1,'party_type'=>"0,1"]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1,'active_item'=>"1,2"]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->load->view($this->form,$this->data);
    }

    public function addEnquiry(){
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>1,'party_type'=>"0,1"]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1,'active_item'=>"1,2"]);
        $this->data['unitList'] = $this->item->itemUnits();
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
            $data['vou_name_l'] = $this->data['entryData']->vou_name_long;
            $data['vou_name_s'] = $this->data['entryData']->vou_name_short;
            $this->printJson($this->salesEnquiry->save($data));
        endif;
    }

    public function edit($id){
        $this->data['dataRow'] = $dataRow = $this->salesEnquiry->getSalesEnquiry(['id'=>$id,'itemList'=>1]);
        $this->data['gstinList'] = $this->party->getPartyGSTDetail(['party_id' => $dataRow->party_id]);
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => 1,'party_type'=>"0,1"]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1,'active_item'=>"1,2"]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesEnquiry->delete($id));
        endif;
    }
}
?>