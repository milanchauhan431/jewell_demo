<?php
class DiamondQuality extends MY_Controller{
    private $indexPage = "diamond_quality/index";
    private $formPage = "diamond_quality/form";
   
    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Diamond Quality";
		$this->data['headData']->controller = "diamondQuality";
		$this->data['headData']->pageUrl = "diamondQuality";
	}
	
	public function index(){
        $this->data['tableHeader'] = getMasterDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->diamondQuality->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getDiamondQualityData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addDiamondQuality(){
        $this->load->view($this->formPage,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if (empty($data['quality']))
            $errorMessage['quality'] = "Diamond Quality is required.";

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $this->printJson($this->diamondQuality->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->diamondQuality->getPurity($data);
        $this->load->view($this->formPage,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->diamondQuality->delete($id));
        endif;
    }    

    public function getList(){
        $data = $this->input->post();
        $qualityList = $this->diamondQuality->getList($data);
        $qualityOptions = getdiamondQualityOptions($qualityList);
        $this->printJson(['status'=>1,'data'=>['qualityList'=>$qualityList,"qualityOptions"=>$qualityOptions]]);
    }
}
?>