<?php
class FamilyGroup extends MY_Controller
{
    private $indexPage = "family_group/index";
    private $familyGroupForm = "family_group/form";
    private $parameterForm = "family_group/param_form";
    private $typeArr = Array(["key"=>2,"val"=>'Chemical'],["key"=>3,"val"=>"Mechanical"],["key"=>4,"val"=>"Heat Treatment Process"],["key"=>5,"val"=>"Microstructure"]);
    private $type = ["0"=>"", "1"=>"Family Group", "2"=>"Chemical", "3"=>"Mechanical", "4"=>"Heat Treatment Process", "5"=>"Microstructure"];

	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "family Group";
		$this->data['headData']->controller = "familyGroup";
		$this->data['headData']->pageUrl = "familyGroup";
	}
	
	public function index(){
        $this->data['tableHeader'] = getPurchaseDtHeader($this->data['headData']->controller);
        $this->data['type'] = 1;
        $this->data['pageTitle'] = 'Family Group';
        $this->load->view($this->indexPage,$this->data);
    }

    public function indexQcParam(){
        $this->data['tableHeader'] = getPurchaseDtHeader('mqcParam');
        $this->data['type'] = 2;
        $this->data['pageTitle'] = 'Material Quality Parameters';
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($type = 1){
        $data = $this->input->post(); $data['type'] = $type;

        $result = $this->familyGroup->getDTRows($data);
        $sendData = array();$i=1;
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $row->type_name = $this->type[$row->type];
            $sendData[] = getfamilyGroupData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addfamilyGroup(){
        $this->load->view($this->familyGroupForm,$this->data);
    }

    public function addParameter(){
        $this->data['typeArr'] = $this->typeArr;
        $this->load->view($this->parameterForm,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if(empty($data['family_name'])):
            if($data['type'] == 1):
                $errorMessage['family_name'] = "Family Name is required.";
            else:
                $errorMessage['family_name'] = "Parameters is required.";    
            endif;
        endif;
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['created_by'] = $this->session->userdata('loginId');
            $this->printJson($this->familyGroup->save($data));
        endif;
    }

    public function edit(){
        $this->data['dataRow'] = $dataRow = $this->familyGroup->getFamilyGroup($this->input->post('id'));
        if($dataRow->type == 1){
            $this->load->view($this->familyGroupForm,$this->data);
        }else{
            $this->data['typeArr'] = $this->typeArr;
            $this->load->view($this->parameterForm,$this->data);
        }
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->familyGroup->delete($id));
        endif;
    }
    
}
?>