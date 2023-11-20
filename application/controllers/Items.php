<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Items extends MY_Controller{
    private $indexPage = "item_master/index";
    private $form = "item_master/form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Products";
		$this->data['headData']->controller = "items";        
	}

    public function index(){
        $item_type = 1;
        $this->data['headData']->pageUrl = "items";
        $this->data['item_type'] = $item_type;
        $headerName = str_replace(" ","_",strtolower($this->itemTypes[$item_type]));
        $this->data['tableHeader'] = getMasterDtHeader($headerName);
        $this->load->view($this->indexPage,$this->data);
    }

    public function list($item_type = 0){
        $this->data['headData']->pageUrl = "items/list/".$item_type;
        $this->data['item_type'] = $item_type;
        $headerName = str_replace(" ","_",strtolower($this->itemTypes[$item_type]));
        $this->data['tableHeader'] = getMasterDtHeader($headerName);
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($item_type = 0){
        $data = $this->input->post();$data['item_type'] = $item_type;
        $result = $this->item->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $row->item_type_text = $this->itemTypes[$row->item_type];
            $sendData[] = getProductData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addItem(){
        $data = $this->input->post();
        $this->data['item_type'] = $data['item_type'];
        $this->data['unitData'] = $this->item->itemUnits();
        $this->data['categoryList'] = $this->itemCategory->getCategoryList(['category_type'=>$data['item_type'],'final_category'=>1]);
        $this->data['hsnData'] = $this->hsnModel->getHSNList();
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        
        /* if(empty($data['item_code']))
            $errorMessage['item_code'] = "Item Code is required."; */

        if(empty($data['item_name']))
            $errorMessage['item_name'] = "Item Name is required.";

        if(empty($data['unit_id']))
            $errorMessage['unit_id'] = "Unit is required.";

        if(empty($data['category_id']))
            $errorMessage['category_id'] = "Category is required.";
            
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $fname = Array();
            if(!empty($data['item_code'])){$fname[] = $data['item_code'];}
            if(!empty($data['item_name'])){$fname[] = $data['item_name'];}
            $data['full_name'] = (!empty($fname)) ? implode(' - ',$fname) : '';			
			
			/* if(!empty($data['hsn_code'])):
			    $hsnData = $this->hsnModel->getHSNDetail(['hsn'=>$data['hsn_code']]);
				$data['gst_per'] = $hsnData->gst_per;
			endif; */

            $this->printJson($this->item->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $itemDetail = $this->item->getItem($data);
        $this->data['unitData'] = $this->item->itemUnits();
        $this->data['categoryList'] = $this->itemCategory->getCategoryList(['category_type'=>$itemDetail->item_type,'final_category'=>1]);
        $this->data['hsnData'] = $this->hsnModel->getHSNList();
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->item->delete($id));
        endif;
    }

    public function getItemList(){
        $data = $this->input->post();
        $itemList = $this->item->getItemList($data);
        $itemOptions =getItemListOption($itemList);
        $this->printJson(['status'=>1,'data'=>['itemList'=>$itemList,"itemOptions"=>$itemOptions]]);
    }

    public function getItemDetails(){
        $data = $this->input->post();
        $itemDetail = $this->item->getItem($data);
        $this->printJson(['status'=>1,'data'=>['itemDetail'=>$itemDetail]]);
    }
}
?>