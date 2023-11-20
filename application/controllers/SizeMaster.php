<?php
class SizeMaster extends MY_Controller{
    private $indexPage = "size_master/index";
    private $sizeForm = "size_master/form";
    
	public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Size Master";
		$this->data['headData']->controller = "sizeMaster";
        $this->data['headData']->pageUrl = "sizeMaster";
	}
	
    public function index(){
        $this->data['tableHeader'] = getMasterDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->sizeMaster->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getSizeData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addSize(){
        $this->load->view($this->sizeForm, $this->data);
    }

    public function save(){
        $data = $this->input->post();
		$errorMessage = array();		
        if(empty($data['size']))
			$errorMessage['size'] = "Size is required.";
        if(empty($data['shape']))
			$errorMessage['shape'] = "Shape is required.";
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->sizeMaster->save($data));
        endif;
    }

    public function edit(){     
        $data = $this->input->post();
        $this->data['dataRow'] = $this->sizeMaster->getSize($data);
        $this->load->view($this->sizeForm, $this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->sizeMaster->delete($id));
        endif;
    }
}
?>