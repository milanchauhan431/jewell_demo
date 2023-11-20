<?php
class Purity extends MY_Controller{
    private $indexPage = "purity/index";
    private $formPage = "purity/form";
   
    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Purity";
		$this->data['headData']->controller = "purity";
		$this->data['headData']->pageUrl = "purity";
	}
	
	public function index(){
        $this->data['tableHeader'] = getMasterDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->purity->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getPurityData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addPurity(){
        $this->load->view($this->formPage,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if (empty($data['purity']))
            $errorMessage['purity'] = "Purity is required.";

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $this->printJson($this->purity->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->purity->getPurity($data);
        $this->load->view($this->formPage,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->purity->delete($id));
        endif;
    }    

    public function getPurityList(){
        $data = $this->input->post();
        $purityList = $this->purity->getPurityList($data);
        $purityOptions = getPurityListOptions($purityList);
        $this->printJson(['status'=>1,'data'=>['purityList'=>$purityList,"purityOptions"=>$purityOptions]]);
    }
}
?>