<?php
class Parties extends MY_Controller{
    private $index = "party/index";
    private $form = "party/form";
    private $ledgerForm = "party/ledger_form";
    private $gstFrom = "party/gst_form";
    private $contactFrom = "party/contact_form";

    public function __construct(){
        parent::__construct();
		$this->data['headData']->pageTitle = "Party Master";
		$this->data['headData']->controller = "parties";        
    }

    public function list($type="customer"){
        $this->data['headData']->pageUrl = "parties/list/".$type;
        $this->data['type'] = $type;
        
        if($type == "vendor") $static_type = "karigar"; else $static_type = $type;
        $this->data['party_category'] = array_search(ucwords($static_type),$this->partyCategory);
        $this->data['tableHeader'] = getMasterDtHeader($type);
        $this->load->view($this->index,$this->data);
    }

    public function getDTRows($party_category){
        $data=$this->input->post();$data['party_category'] = $party_category;
        $result = $this->party->getDTRows($data);
        $sendData = array();
        $i = ($data['start']+1);
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            $row->table_status = $party_category;
            $row->party_category_name = $this->partyCategory[$row->party_category];
            $sendData[] = getPartyData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addParty(){
        $data = $this->input->post();
        $this->data['party_category'] = $data['party_category'];
        $this->data['party_type'] = (isset($data['party_type']))?$data['party_type']:1;
        if($data['party_category'] != 4):            
            $this->data['currencyData'] = $this->party->getCurrencyList();
            $this->data['countryData'] = $this->party->getCountries();
            $this->data['party_code'] = $this->getPartyCode($data['party_category']);
            $this->data['salesExecutives'] = $this->employee->getEmployeeList();
            $this->load->view($this->form, $this->data);
        else:
            $this->data['groupList'] = $this->party->getGroupList();
            $this->data['hsnList'] = $this->hsnModel->getHSNList();
            $this->load->view($this->ledgerForm,$this->data);
        endif;
    }

    /* Auto Generate Party Code */
    public function getPartyCode($party_category=""){
        $partyCategory = (!empty($party_category))?$party_category:$this->input->post('party_category');
        $code = $this->party->getPartyCode($partyCategory);
        $prefix = "AE";
        if($partyCategory == 1):
            $prefix = "C";
        elseif($partyCategory == 2):
            $prefix = "S";
        elseif($partyCategory == 3):
            $prefix = "V";
        endif;

        $party_code = $prefix.sprintf("%03d",$code);

        if(!empty($party_category)):
            return $party_code;
        else:
            $this->printJson(['status'=>1,'party_code'=>$party_code]);
        endif;
    }

    public function getStatesOptions($postData=array()){
        $country_id = (!empty($postData['country_id']))?$postData['country_id']:$this->input->post('country_id');

        $result = $this->party->getStates(['country_id'=>$country_id]);

        $html = '<option value="">Select State</option>';
        foreach ($result as $row) :
            $selected = (!empty($postData['state_id']) && $row->id == $postData['state_id']) ? "selected" : "";
            $html .= '<option value="' . $row->id . '" ' . $selected . '>' . $row->name . '</option>';
        endforeach;

        if(!empty($postData)):
            return $html;
        else:
            $this->printJson(['status'=>1,'result'=>$html]);
        endif;
    }

    public function getCitiesOptions($postData=array()){
        $state_id = (!empty($postData['state_id']))?$postData['state_id']:$this->input->post('state_id');

        $result = $this->party->getCities(['state_id'=>$state_id]);
        
        $html = '<option value="">Select City</option>';
        foreach ($result as $row) :
            $selected = (!empty($postData['city_id']) && $row->id == $postData['city_id']) ? "selected" : "";
            $html .= '<option value="' . $row->id . '" ' . $selected . '>' . $row->name . '</option>';
        endforeach;

        if(!empty($postData)):
            return $html;
        else:
            $this->printJson(['status'=>1,'result'=>$html]);
        endif;
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if (empty($data['party_name']))
            $errorMessage['party_name'] = "Company name is required.";

        if (empty($data['party_category']))
            $errorMessage['party_category'] = "Party Category is required.";

        /* if (empty($data['contact_person']))
            $errorMessage['contact_person'] = "Contact Person is required.";

        if (empty($data['party_mobile']))
            $errorMessage['party_mobile'] = "Contact No. is required."; */

        if($data['party_category'] != 4):
       
            if (empty($data['supplied_types']))
                $errorMessage['supplied_types'] = 'Supplied Types are required.';

            if (empty($data['gstin']) && in_array($data['registration_type'],[1,2]))
                $errorMessage['gstin'] = 'Gstin is required.';

            if (empty($data['country_id']))
                $errorMessage['country_id'] = 'Country is required.';

            if (empty($data['state_id']))
                $errorMessage['state_id'] = 'State is required.';

            if (empty($data['city_id']))
                $errorMessage['city_id'] = 'City is required.';

            if (empty($data['party_address']))
                $errorMessage['party_address'] = "Address is required.";

            if (empty($data['party_pincode']))
                $errorMessage['party_pincode'] = "Pincode is required.";
                
        endif;

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $attachments = array();
            if (!empty($data['id']) && !empty($data['attachment'])) :
                $attachments = $data['attachment'];
                unset($data['attachment']);
            endif;

            if (!empty($_FILES['attachments']['name'][0])) :
                $this->load->library('upload');
                $this->load->library('image_lib');

                $errorMessage['attachment_error'] = "";

                foreach ($_FILES['attachments']['name'] as $key => $fileName) :
                    $_FILES['userfile']['name']     = $fileName;
                    $_FILES['userfile']['type']     = $_FILES['attachments']['type'][$key];
                    $_FILES['userfile']['tmp_name'] = $_FILES['attachments']['tmp_name'][$key];
                    $_FILES['userfile']['error']    = $_FILES['attachments']['error'][$key];
                    $_FILES['userfile']['size']     = $_FILES['attachments']['size'][$key];

                    $imagePath = realpath(APPPATH . '../assets/uploads/parties/');

                    $fileName = preg_replace('/[^A-Za-z0-9]+/', '_', strtolower($fileName));
                    $config = ['file_name' => time() . "_IR_" . $fileName, 'allowed_types' => 'jpg|jpeg|png|gif|JPG|JPEG|PNG', 'max_size' => 10240, 'overwrite' => FALSE, 'upload_path' => $imagePath];

                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()) :
                        $errorMessage['attachment_error'] .= $fileName . " => " . $this->upload->display_errors();
                    else :
                        $uploadData = $this->upload->data();
                        $attachments[] = $uploadData['file_name'];

                        $imgConfig['image_library'] = 'gd2';
                        $imgConfig['source_image'] = $uploadData['full_path'];
                        $imgConfig['maintain_ratio'] = TRUE;
                        $imgConfig['width'] = 640;
                        $imgConfig['height'] = 480;
                        $imgConfig['quality'] = "50%";

                        $this->image_lib->clear();
                        $this->image_lib->initialize($imgConfig);

                        if (!$this->image_lib->resize()) :
                            $errorMessage['attachment_error'] .= $fileName . " => " . $this->image_lib->display_errors();
                        endif;
                    endif;
                endforeach;

                if (!empty($errorMessage['attachment_error'])) :
                    foreach ($attachments as $file) :
                        if (file_exists($imagePath . '/' . $file)) : unlink($imagePath . '/' . $file);
                        endif;
                    endforeach;
                    $this->printJson(['status' => 0, 'message' => $errorMessage]);
                endif;
            endif;

            if (!empty($attachments)) :
                $data['attachments'] = implode(",", $attachments);
            else :
                $data['attachments'] = "";
            endif;

            $data['party_name'] = ucwords($data['party_name']);
            $data['gstin'] = (!empty($data['gstin']))?strtoupper($data['gstin']):"";
            $this->printJson($this->party->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $result = $this->party->getParty($data);
        $this->data['dataRow'] = $result;
        if($result->party_category != 4):
            $this->data['currencyData'] = $this->party->getCurrencyList();
            $this->data['countryData'] = $this->party->getCountries();
            $this->data['salesExecutives'] = $this->employee->getEmployeeList();
            $this->load->view($this->form, $this->data);
        else:
            $this->data['groupList'] = $this->party->getGroupList();
            $this->data['hsnList'] = $this->hsnModel->getHSNList();
            $this->load->view($this->ledgerForm,$this->data);
        endif;
    }

    public function delete(){
        $id = $this->input->post('id');
        if (empty($id)) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $this->printJson($this->party->delete($id));
        endif;
    }

    public function gstDetail(){
        $data = $this->input->post();
        $this->data['party_id'] = $data['id'];
        $this->load->view($this->gstFrom,$this->data);
    }

    public function getPartyGSTDetailHtml(){
        $data = $this->input->post();
        $result = $this->party->getPartyGSTDetail($data);

        $tbodyData = "";$i = 1;        
        if (!empty($result)) :
            foreach ($result as $row) :
                $deleteParam = "{'postData':{'id' : ".$row->id.",'party_id':".$row->party_id."},'message' : 'GST Detail','fndelete':'deleteGstDetail','res_function':'resTrashPartyGstDetail'}";
                $tbodyData .= '<tr>
                    <td>' .  $i++ . '</td>
                    <td>' . $row->gstin . '</td>
                    <td>' . $row->party_address . '</td>
                    <td>' . $row->party_pincode . '</td>
                    <td>' . $row->delivery_address . '</td>
                    <td>' . $row->delivery_pincode . '</td>
                    <td class="text-center">
                        <button type="button" onclick="trash('.$deleteParam.');" class="btn btn-outline-danger waves-effect waves-light btn-delete permission-remove"><i class="ti-trash"></i></button>
                    </td>
                </tr> ';
            endforeach;
        else :
            $tbodyData .= '<tr><td colspan="7" style="text-align:center;">No data available in table</td></tr>';
        endif;
        $this->printJson(['status'=>1,"tbodyData"=>$tbodyData]);
    }

    public function saveGstDetail(){
        $data = $this->input->post();
        $errorMessage = array();

        if (empty($data['gstin']))
            $errorMessage['gstin'] = "GST is required.";
		if (empty($data['party_address']))
            $errorMessage['party_address'] = "Party Address is required.";
        if (empty($data['party_pincode']))
            $errorMessage['party_pincode'] = "Party Pincode is required.";
        if (empty($data['delivery_address']))
            $errorMessage['delivery_address'] = "Delivery Address is required.";
        if (empty($data['delivery_pincode']))
            $errorMessage['delivery_pincode'] = "Delivery Pincode is required.";

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $this->printJson($this->party->saveGstDetail($data));
        endif;
    }

    public function deleteGstDetail(){
        $id = $this->input->post('id');
        if (empty($id)) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $this->printJson($this->party->deleteGstDetail($id));
        endif;
    }

    public function contactDetail(){
        $data = $this->input->post();
        $this->data['party_id'] = $data['id'];
        $this->load->view($this->contactFrom,$this->data);
    }

    public function getPartyContactDetailHtml(){
        $data = $this->input->post();
        $result = $this->party->getPartyContactDetail($data);

        $tbodyData = "";$i = 1;        
        if (!empty($result)) :
            foreach ($result as $row) :
                $deleteParam = "{'postData':{'id' : ".$row->id.",'party_id':".$row->party_id."},'message' : 'Contact Detail','fndelete':'deleteContactDetail','res_function':'resTrashPartyContactDetail'}";
                $tbodyData .= '<tr>
                    <td>' .  $i++ . '</td>
                    <td>' . $row->contact_person . '</td>
                    <td>' . $row->mobile_no . '</td>
                    <td>' . $row->contact_email . '</td>
                    <td class="text-center">
                        <button type="button" onclick="trash('.$deleteParam.');" class="btn btn-outline-danger waves-effect waves-light btn-delete permission-remove"><i class="ti-trash"></i></button>
                    </td>
                </tr> ';
            endforeach;
        else :
            $tbodyData .= '<tr><td colspan="5" style="text-align:center;">No data available in table</td></tr>';
        endif;
        $this->printJson(['status'=>1,"tbodyData"=>$tbodyData]);
    }

    public function saveContactDetail(){
        $data = $this->input->post();
		$errorMessage = array();

		if(empty($data['person']))
			$errorMessage['person'] = "Contact Person is required.";
        if(empty($data['mobile']))
			$errorMessage['mobile'] = "Contact Mobile is required.";
        if(empty($data['email']))
			$errorMessage['email'] = "Contact Email is required.";
		
		if(!empty($errorMessage)):
			$this->printJson(['status'=>0,'message'=>$errorMessage]);
		else:
            $this->printJson($this->party->saveContactDetail($data));
		endif;
    }

    public function deleteContactDetail(){
        $id = $this->input->post('id');
        if (empty($id)) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $this->printJson($this->party->deleteContactDetail($id));
        endif;
    }

    public function getPartyList(){
        $data = $this->input->post();
        $partyList = $this->party->getPartyList($data);
        $partyOptions = getPartyListOption($partyList);
        $this->printJson(['status'=>1,'data'=>['partyList'=>$partyList,'partyOptions'=>$partyOptions]]);
    }

    public function getPartyDetails(){
        $data = $this->input->post();
        $partyDetail = $this->party->getParty($data);
        $gstDetails = $this->party->getPartyGSTDetail(['party_id'=>$data['id']]);
        $this->printJson(['status'=>1,'data'=>['partyDetail'=>$partyDetail,'gstDetails'=>$gstDetails]]);
    }
}
?>