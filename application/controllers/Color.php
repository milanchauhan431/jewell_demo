<?php
class Color extends MY_Controller{
    private $indexPage = "color/index";
    private $formPage = "color/form";
   
    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Color";
		$this->data['headData']->controller = "color";
		$this->data['headData']->pageUrl = "color";
	}
	
	public function index(){
        $this->data['tableHeader'] = getMasterDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->color->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getColorData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addcolor(){
        $this->load->view($this->formPage,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if (empty($data['color']))
            $errorMessage['color'] = "Color is required.";

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $this->printJson($this->color->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->color->getColor($data);
        $this->load->view($this->formPage,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->color->delete($id));
        endif;
    }    

    public function getColorList(){
        $data = $this->input->post();
        $colorList = $this->color->getColorList($data);
        $colorOptions = getColorListOptions($colorList);
        $this->printJson(['status'=>1,'data'=>['colorList'=>$colorList,"colorOptions"=>$colorOptions]]);
    }
}
?>