<?php
class Fine extends MY_Controller{
    private $indexPage = "fine/index";
    private $formPage = "fine/form";
   
    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Fine";
		$this->data['headData']->controller = "fine";
		$this->data['headData']->pageUrl = "fine";
	}
	
	public function index(){
        $this->data['tableHeader'] = getMasterDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->fine->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getFineData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addFine(){
        $this->load->view($this->formPage,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if (empty($data['fine']))
            $errorMessage['fine'] = "Fine is required.";

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $this->printJson($this->fine->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->fine->getFine($data);
        $this->load->view($this->formPage,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->fine->delete($id));
        endif;
    }  
    
    public function getFineList(){
        $data = $this->input->post();
        $fineList = $this->fine->getFineList($data);
        $fineOptions = getFineListOptions($fineList);
        $this->printJson(['status'=>1,'data'=>['fineList'=>$fineList,"fineOptions"=>$fineOptions]]);
    }
}
?>