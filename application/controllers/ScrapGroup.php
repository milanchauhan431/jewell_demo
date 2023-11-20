<?php
class ScrapGroup extends MY_Controller{
    private $indexPage = "scrap_group/index";
    private $formPage = "scrap_group/form";
   
    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Scrap Group";
		$this->data['headData']->controller = "scrapGroup";
		$this->data['headData']->pageUrl = "scrapGroup";
	}
	
	public function index(){
        $this->data['tableHeader'] = getConfigDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->item->getScrapGroupDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getScrapGroupData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addScrapGroup(){
        $this->data['item_type'] = 10;
        $this->data['unitData'] = $this->item->itemUnits();
        $this->data['categoryList'] = $this->itemCategory->getCategoryList(['category_type'=>10,'final_category'=>1]);
        $this->load->view($this->formPage,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if (empty($data['item_name']))
            $errorMessage['item_name'] = "Scrap Group Name is required.";

		if (empty($data['category_id']))
            $errorMessage['category_id'] = "Category Name is required.";

        if (empty($data['unit_id']))
            $errorMessage['unit_id'] = "Unit Name is required.";

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $this->printJson($this->item->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->item->getItem($data);
        $this->data['unitData'] = $this->item->itemUnits();
        $this->data['categoryList'] = $this->itemCategory->getCategoryList(['category_type'=>10,'final_category'=>1]);
        $this->load->view($this->formPage,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->item->delete($id));
        endif;
    }    
}
?>