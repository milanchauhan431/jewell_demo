<?php
class Polish extends MY_Controller{
    private $indexPage = "polish/index";
    private $formPage = "polish/form";
   
    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Polish";
		$this->data['headData']->controller = "polish";
		$this->data['headData']->pageUrl = "polish";
	}
	
	public function index(){
        $this->data['tableHeader'] = getMasterDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->polish->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getPolishData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addPolish(){
        $this->load->view($this->formPage,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if (empty($data['polish']))
            $errorMessage['polish'] = "Polish is required.";

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $this->printJson($this->polish->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->polish->getPolish($data);
        $this->load->view($this->formPage,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->polish->delete($id));
        endif;
    }    

    public function getPolishList(){
        $data = $this->input->post();
        $polishList = $this->polish->getPolishList($data);
        $polishOptions = getPolishListOptions($polishList);
        $this->printJson(['status'=>1,'data'=>['polishList'=>$polishList,"polishOptions"=>$polishOptions]]);
    }
}
?>