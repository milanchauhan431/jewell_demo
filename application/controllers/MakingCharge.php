<?php
class MakingCharge extends MY_Controller{

	 public function __construct()	{
		parent::__construct();
		$this->data['headData']->pageTitle = "Update Charges";
		$this->data['headData']->controller = "makingCharge";
	}
	
	public function index(){
		$this->data['partyList'] = $this->party->getPartyList(['party_category'=>"2"]);
        $this->load->view('making_charge',$this->data);
    }
	 
    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
		
		if(empty($data['itemData']))
			$errorMessage['item_error'] = "Item details is required.";

		if(!empty($errorMessage)):
			$this->printJson(['status'=>0,'message'=>$errorMessage]);
		else:
			$this->printJson($this->makingCharge->save($data));
		endif;
	}

	public function getApprovedInwardList(){
		$data = $this->input->post();
		$result = $this->makingCharge->getApprovedInwardList($data);

		$tbody = '';

		foreach($result as $row):
			$tbody .= '<tr>
				<td style="width:5%;">'.$row->unique_id.'</td>
				<td>
					'.$row->item_name.'
					<input type="hidden" name="itemData['.$row->id.'][id]" value="'.$row->id.'">
				</td>
				<td>'.$row->ref_no.'</td>
				<td>'.$row->qty.'</td>
				<td>'.$row->gross_weight.'</td>
				<td>'.$row->net_weight.'</td>
				<td>'.$row->unit_name.'</td> 
				<td>
					<input type="text" class="form-control-sm floatOnly" name="itemData['.$row->id.'][price]" value="'.$row->price.'">
				</td>
				<td>
					<input type="text" class="form-control-sm floatOnly" name="itemData['.$row->id.'][making_per]" value="'.$row->making_per.'">
				</td>
				<td>
					<input type="text" class="form-control-sm floatOnly" name="itemData['.$row->id.'][making_disc_per]" value="'.$row->making_disc_per.'">
				</td>
				<td>
					<input type="text" class="form-control-sm floatOnly" name="itemData['.$row->id.'][otc_amount]" value="'.$row->otc_amount.'">
				</td>
				<td>
					<input type="text" class="form-control-sm floatOnly" name="itemData['.$row->id.'][vrc_amount]" value="'.$row->vrc_amount.'">
				</td>
				<td>
					<input type="text" class="form-control-sm floatOnly" name="itemData['.$row->id.'][diamond_amount]" value="'.$row->diamond_amount.'">
				</td>  
			</tr>';
		endforeach;

		$tbody = (!empty($tbody))?$tbody:'<tr><td colspan="13" class="text-center">No data available in table</td></tr>';

		$this->printJson(['status'=>1,'tbodyData'=>$tbody]);
	}
}
