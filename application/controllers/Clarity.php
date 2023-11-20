<?php
class Clarity extends MY_Controller{
    private $indexPage = "clarity/index";
    private $formPage = "clarity/form";
   
    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Clarity";
		$this->data['headData']->controller = "clarity";
		$this->data['headData']->pageUrl = "clarity";
	}
	
	public function index(){
        $this->data['tableHeader'] = getMasterDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->clarity->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getClarityData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addClarity(){
        $this->load->view($this->formPage,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if (empty($data['clarity']))
            $errorMessage['clarity'] = "Clarity is required.";

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $this->printJson($this->clarity->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->clarity->getClarity($data);
        $this->load->view($this->formPage,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->clarity->delete($id));
        endif;
    } 

    public function getClarityList(){
        $data = $this->input->post();
        $clarityList = $this->clarity->getClarityList($data);
        $clarityOptions = getClarityListOptions($clarityList);
        $this->printJson(['status'=>1,'data'=>['clarityList'=>$clarityList,"clarityOptions"=>$clarityOptions]]);
    }   
}
?>