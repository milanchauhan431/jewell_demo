<?php
class MaterialIssue extends MY_Controller{
    private $indexPage = "material_issue/index";
    private $issueForm = "material_issue/form";
    private $acceptForm = "material_issue/accept_form";
    private $returnForm = "material_issue/return_form";
    private $salesReturnForm = "material_issue/sales_return_form";
    
	public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Material Issue";
		$this->data['headData']->controller = "materialIssue";
        $this->data['headData']->pageUrl = "materialIssue";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'materialIssue']);
	}
	 
	public function index(){
        $this->data['tableHeader'] = getStoreDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage,$this->data);
    }
	
    public function getDTRows($status = 0){
        $data = $this->input->post();$data['trans_status'] = $status;
        $result = $this->materialIssue->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getMaterialIssueData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addIssue(){
        $this->data['locationList'] = $this->storeLocation->getStoreLocationList(['final_location'=>1]);
        $this->data['employeeList'] = $this->employee->getEmployeeList();
        $this->load->view($this->issueForm,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['vou_acc_id']))
            $errorMessage['vou_acc_id'] = "Collected By is required.";
        if(empty($data['sales_executive']))
            $errorMessage['sales_executive'] = "Sales Executive is required.";
        if(empty($data['feasibility_by']))
            $errorMessage['feasibility_by'] = "To Location is required.";
        if(empty($data['itemData']))
            $errorMessage['item_error'] = "Please add at liest one item.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['trans_prefix'] =  $this->data['entryData']->trans_prefix;
            $data['entry_type'] = $this->data['entryData']->id;
            $this->printJson($this->materialIssue->save($data));
        endif;
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->materialIssue->delete($id));
        endif;
    }

    public function loadIssueData(){
		$result = $this->materialIssue->getIssueList();

		$tbody = '';
		foreach($result as $row):
            $accpetParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-xl', 'form_id' : 'acceptMaterialForm','fnedit':'acceptMaterial','controller':'materialIssue','fnsave':'saveAcceptMaterial', 'title' : 'Accept Material','res_function':'resAcceptMatrial','js_store_fn':'confirmStore', 'confirm_msg':'Are you sure want to accept this material ?'}";
            $acceptButton = '<a class="btn btn-success btn-edit" href="javascript:void(0)" datatip="Accept" flow="down" onclick="edit('.$accpetParam.');"><i class="ti-check"></i></a>';

            $action = getActionButton($acceptButton);
			$tbody .= '<tr>
				<td>'.$action.'</td>
				<td>'.$row->trans_number.'</td>
				<td>'.formatDate($row->trans_date).'</td>
				<td>'.$row->collected_by_name.'</td>
				<td>'.$row->total_issue.'</td>
			</tr>';
		endforeach;

		$this->printJson(['status'=>1,'tbodyData'=>$tbody]);
	}

    public function acceptMaterial(){
        $data = $this->input->post();
        $this->data['id'] = $data['id'];
        $this->data['issueItemList'] = $this->materialIssue->getIssueItems($data);
        $this->load->view($this->acceptForm,$this->data);
    }

    public function saveAcceptMaterial(){
        $data = $this->input->post();
        $this->printJson($this->materialIssue->saveAcceptMaterial($data));
    }

    public function returnMaterial(){
        $data = $this->input->post();
        $this->data['id'] = $data['id'];
        $this->data['issueItemList'] = $this->materialIssue->getIssueItemListForReturn($data);
        $this->load->view($this->returnForm,$this->data);
    }

    public function saveReturnMaterial(){
        $data = $this->input->post();
        $this->printJson($this->materialIssue->saveReturnMaterial($data));
    }

    public function salesReturn(){
        $this->data['locationList'] = $this->storeLocation->getStoreLocationList(['final_location'=>1]);
        $this->data['returnItemList'] = $this->materialIssue->getSalesReturnItems();
        $this->load->view($this->salesReturnForm,$this->data);
    }

    public function saveSalesReturnMaterial(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['location_id']))
            $errorMessage['location_id'] = "Store Location is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->materialIssue->saveSalesReturnMaterial($data));
        endif;
    }
}
?>