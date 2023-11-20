<?php
class MaterialGrade extends MY_Controller{
    private $indexPage = "material_grade/index";
    private $materialForm = "material_grade/form";
    private $inspection_param = "material_grade/inspection_param";
    
	public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Material Grade";
		$this->data['headData']->controller = "materialGrade";
        $this->data['headData']->pageUrl = "materialGrade";
	}
	 
	public function index(){
        $this->data['tableHeader'] = getConfigDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage,$this->data);
    }
	
    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->materialGrade->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getMaterialData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addMaterialGrade(){
        $this->data['scrapData'] = $this->item->getItemList(['item_type'=>10]);
        $this->data['colorList'] = $this->materialGrade->getColorCodes();;
        $this->data['standard'] = $this->materialGrade->getStandards();
        $this->load->view($this->materialForm,$this->data);
    }

    public function save(){
        $data = $this->input->post();
		$errorMessage = array();		
        if(empty($data['material_grade']))
			$errorMessage['material_grade'] = "Material Grade is required.";
        if(empty($data['scrap_group']))
			$errorMessage['scrap_group'] = "Scrap Group is required.";
        if(empty($data['standard']))
            $errorMessage['standardName'] = "Standard is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->materialGrade->save($data));
        endif;
    }

    public function edit(){     
        $data = $this->input->post();
        $this->data['dataRow'] = $this->materialGrade->getMaterial($data);
        $this->data['scrapData'] = $this->item->getItemList(['item_type'=>10]);
        $this->data['colorList'] = $this->materialGrade->getColorCodes();;
        $this->data['standard'] = $this->materialGrade->getStandards();
        $this->load->view($this->materialForm,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->materialGrade->delete($id));
        endif;
    }
}
?>