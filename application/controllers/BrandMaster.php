<?php
class BrandMaster extends MY_Controller{
    private $indexPage = "brand_master/index";
    private $brandForm = "brand_master/form";
    
	public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Brand Master";
		$this->data['headData']->controller = "brandMaster";
        $this->data['headData']->pageUrl = "brandMaster";
	}
	
    public function index(){
        $this->data['tableHeader'] = getMasterDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->brandMaster->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getBrandData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addBrand(){
        $this->load->view($this->brandForm, $this->data);
    }

    public function save(){
        $data = $this->input->post();
		$errorMessage = array();		
        if(empty($data['brand_name']))
			$errorMessage['brand_name'] = "Brand Name is required.";
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->brandMaster->save($data));
        endif;
    }

    public function edit(){     
        $data = $this->input->post();
        $this->data['dataRow'] = $this->brandMaster->getBrand($data);
        $this->load->view($this->brandForm, $this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->brandMaster->delete($id));
        endif;
    }
}
?>