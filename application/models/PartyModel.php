<?php
class PartyModel extends MasterModel{
    private $partyMaster = "party_master";
    private $groupMaster = "group_master";
    private $countries = "countries";
	private $states = "states";
	private $cities = "cities";
    private $transDetails = "trans_details";

    public function getPartyCode($category=1){
        $queryData['tableName'] = $this->partyMaster;
        $queryData['select'] = "ifnull((MAX(CAST(REGEXP_SUBSTR(party_code,'[0-9]+') AS UNSIGNED)) + 1),1) as code";
        $queryData['where']['party_category'] = $category;
        $result = $this->row($queryData)->code;
        return $result;
    }

    public function getDTRows($data){
        $data['tableName'] = $this->partyMaster;
        //$data['select'] = "";

        if($data['party_category'] != 4):
            $data['where']['party_master.party_category'] = $data['party_category'];
        endif;

        $data['searchCol'][] = "";
		$data['searchCol'][] = "";
        if($data['party_category'] == 1):
            $data['where']['party_master.party_type'] = 1;

            $data['searchCol'][] = "party_master.party_name";
			$data['searchCol'][] = "party_master.contact_person";
			$data['searchCol'][] = "party_master.party_mobile";
			$data['searchCol'][] = "party_master.party_code";
			$data['searchCol'][] = "party_master.currency";
        elseif($data['party_category'] == 2):
            $data['searchCol'][] = "party_master.party_name";
			$data['searchCol'][] = "party_master.contact_person";
			$data['searchCol'][] = "party_master.party_mobile";
			$data['searchCol'][] = "party_master.party_code";
        elseif($data['party_category'] == 3):
            $data['searchCol'][] = "party_master.party_name";
			$data['searchCol'][] = "party_master.contact_person";
			$data['searchCol'][] = "party_master.party_mobile";
			$data['searchCol'][] = "party_master.party_address";
        elseif($data['party_category'] == 4):
            $data['select'] = "party_master.*,(CASE WHEN tl.op_balance > 0 THEN CONCAT(ABS(tl.op_balance), ' Cr.') WHEN tl.op_balance < 0 THEN CONCAT(ABS(tl.op_balance), ' Dr.') ELSE 0 END) as op_balance,(CASE WHEN tl.cl_balance > 0 THEN CONCAT(ABS(tl.cl_balance), ' Cr.') WHEN tl.cl_balance < 0 THEN CONCAT(ABS(tl.cl_balance), ' Dr.') ELSE 0 END) as cl_balance";
            $data['leftJoin']["(SELECT tl.vou_acc_id , (am.opening_balance + SUM( CASE WHEN tl.trans_date < '".$this->startYearDate."' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as op_balance, (am.opening_balance  + SUM( CASE WHEN tl.trans_date <= '".$this->endYearDate."' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as cl_balance FROM party_master as am LEFT JOIN trans_ledger as tl ON am.id = tl.vou_acc_id WHERE am.is_delete = 0 AND tl.is_delete = 0 GROUP BY am.id) as tl"] = 'tl.vou_acc_id = party_master.id';

            $data['searchCol'][] = "party_master.party_name";
            $data['searchCol'][] = "party_master.group_name";
            $data['searchCol'][] = "tl.op_balance";
            $data['searchCol'][] = "tl.cl_balance";
        endif;

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function getPartyList($data=array()){
        $queryData = array();
        $queryData['tableName']  = $this->partyMaster;
        
        if(!empty($data['party_category'])):
            $queryData['where_in']['party_category'] = $data['party_category'];
        endif;

        if(!empty($data['group_code'])):
            $queryData['where_in']['group_code'] = $data['group_code'];
        endif;

        if(!empty($data['system_code'])):
            $queryData['where_in']['system_code'] = $data['system_code'];
            $queryData['order_by_field']['system_code'] = $data['system_code'];
        else:
            $queryData['order_by']['party_name'] = "ASC";
        endif;

        if(!empty($data['party_type'])):
            $queryData['where_in']['party_type'] = $data['party_type'];
        else:
            $queryData['where']['party_type'] = 1;
        endif;

        return $this->rows($queryData);
    }

    public function getParty($data){
        $queryData = array();
        $queryData['tableName']  = $this->partyMaster;
        $queryData['select'] = "party_master.*,b_countries.name as country_name,b_states.name as state_name,b_states.gst_statecode as state_code,b_cities.name as city_name,d_countries.name as delivery_country_name,d_states.name as delivery_state_name,d_states.gst_statecode as delivery_state_code,d_cities.name as delivery_city_name";

        $queryData['leftJoin']['countries as b_countries'] = "party_master.country_id = b_countries.id";
        $queryData['leftJoin']['states as b_states'] = "party_master.state_id = b_states.id";
        $queryData['leftJoin']['cities as b_cities'] = "party_master.city_id = b_cities.id";

        $queryData['leftJoin']['countries as d_countries'] = "party_master.delivery_country_id = d_countries.id";
        $queryData['leftJoin']['states as d_states'] = "party_master.delivery_state_id = d_states.id";
        $queryData['leftJoin']['cities as d_cities'] = "party_master.delivery_city_id = d_cities.id";


        if(!empty($data['id'])):
            $queryData['where']['party_master.id'] = $data['id'];
        endif;

        if(!empty($data['party_category'])):
            $queryData['where_in']['party_master.party_category'] = $data['party_category'];
        endif;

        if(!empty($data['system_code'])):
            $queryData['where']['party_master.system_code'] = $data['system_code'];
        endif;
        return $this->row($queryData);
    }

    public function getCurrencyList(){
		$queryData['tableName'] = 'currency';
		return $this->rows($queryData);
	}

    public function getCountries(){
		$queryData['tableName'] = $this->countries;
		$queryData['order_by']['name'] = "ASC";
		return $this->rows($queryData);
	}

    public function getCountry($data){
		$queryData['tableName'] = $this->countries;
		$queryData['where']['id'] = $data['id'];
		return $this->row($queryData);
	}

    public function getStates($data=array()){
        $queryData['tableName'] = $this->states;
		$queryData['where']['country_id'] = $data['country_id'];
		$queryData['order_by']['name'] = "ASC";
		return $this->rows($queryData);
    }

    public function getState($data){
        $queryData['tableName'] = $this->states;
		$queryData['where']['id'] = $data['id'];
		return $this->row($queryData);
    }

    public function getCities($data=array()){
        $queryData['tableName'] = $this->cities;
		$queryData['where']['state_id'] = $data['state_id'];
		$queryData['order_by']['name'] = "ASC";
		return $this->rows($queryData);
    }

    public function getCity($data){
        $queryData['tableName'] = $this->cities;
		$queryData['where']['id'] = $data['id'];
		return $this->row($queryData);
    }

    public function save($data){
		try {
			$this->db->trans_begin();
			if ($this->checkDuplicate($data) > 0) :
				$errorMessage['party_name'] = "Company name is duplicate.";
				return ['status' => 0, 'message' => $errorMessage];
            endif;

            if($data['party_category'] != 4):
                $groupCode = ($data['party_category'] == 1) ? "SD" : "SC";
				$groupData = $this->getGroupOnGroupCode($groupCode, true);
				$data['group_id'] = $groupData->id;
				$data['group_name'] = $groupData->name;
				$data['group_code'] = $groupData->group_code;
            else:
                $groupData = $this->getGroup($data['group_id']);
                $data['group_name'] = $groupData->name;
				$data['group_code'] = $groupData->group_code;
            endif;
			
            $result = $this->store($this->partyMaster, $data, 'Party');

            if($data['party_category'] != 4):
                $data['party_id'] = $result['id'];
                $this->saveGstDetail($data);	
            endif;			
			
			if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
	}

    public function checkDuplicate($data){
        $queryData['tableName'] = $this->partyMaster;
        $queryData['where']['party_name'] = $data['party_name'];
		$queryData['where']['party_category'] = $data['party_category'];
        
        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];
        
        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function delete($id){
		try {
			$this->db->trans_begin();

            $checkData['columnName'] = ['party_id','acc_id','opp_acc_id','vou_acc_id'];
            $checkData['value'] = $id;
            $checkUsed = $this->checkUsage($checkData);

            if($checkUsed == true):
                return ['status'=>0,'message'=>'The Party is currently in use. you cannot delete it.'];
            endif;

			$result = $this->trash($this->partyMaster, ['id' => $id], 'Party');

			if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
	}

    public function getPartyGSTDetail($data){
        $queryData = array();
        $queryData['tableName'] = $this->transDetails;
        $queryData['select'] = "id, main_ref_id as party_id, t_col_1 as gstin, t_col_2 as party_address, t_col_3 as party_pincode, t_col_4 as delivery_address, t_col_5 as delivery_pincode";
        $queryData['where']['main_ref_id'] = $data['party_id'];
        $queryData['where']['table_name'] = $this->partyMaster;
        $queryData['where']['description'] = "PARTY GST DETAIL";
        return $this->rows($queryData);
    }

    public function saveGstDetail($data){
        try {
			$this->db->trans_begin();

            $queryData['tableName'] = $this->transDetails;
            $queryData['where']['main_ref_id'] = $data['party_id'];
            $queryData['where']['table_name'] = $this->partyMaster;
            $queryData['where']['description'] = "PARTY GST DETAIL";
            $queryData['where']['t_col_1'] = $data['gstin'];
            $gstData = $this->row($queryData);

            $postData = [
                'id' => (!empty($gstData))?$gstData->id:"",
                'main_ref_id' =>  $data['party_id'],
                'table_name' => $this->partyMaster,
                'description' => "PARTY GST DETAIL",
                't_col_1' => $data['gstin'],
                't_col_2' => $data['party_address'],
			    't_col_3' => $data['party_pincode'],
                't_col_4' => $data['delivery_address'],
                't_col_5' => $data['delivery_pincode']
            ];

            $result = $this->store($this->transDetails,$postData);

            if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
    }

    public function deleteGstDetail($id){
		try {
			$this->db->trans_begin();

			$result = $this->trash($this->transDetails, ['id' => $id], 'Party GST Detail');

			if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
	}

    public function getPartyContactDetail($data){
        $queryData = array();
        $queryData['tableName'] = $this->transDetails;
        $queryData['select'] = "id, main_ref_id as party_id, t_col_1 as contact_person, t_col_2 as mobile_no, t_col_3 as contact_email";
        $queryData['where']['main_ref_id'] = $data['party_id'];
        $queryData['where']['table_name'] = $this->partyMaster;
        $queryData['where']['description'] = "PARTY CONTACT DETAIL";
        return $this->rows($queryData);
    }

    public function saveContactDetail($data){
        try {
			$this->db->trans_begin();

            $postData = [
                'id' => "",
                'main_ref_id' => $data['party_id'],
                'table_name' => $this->partyMaster,
                'description' => "PARTY CONTACT DETAIL",
                't_col_1' => $data['person'],
                't_col_2' => $data['mobile'],
			    't_col_3' => $data['email']
            ];

            $result = $this->store($this->transDetails,$postData,'Contact Detail');

            if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
    }

    public function deleteContactDetail($id){
		try {
			$this->db->trans_begin();

			$result = $this->trash($this->transDetails, ['id' => $id], 'Contact Detail');

			if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
	}

    public function getGroupOnGroupCode($groupCode,$defualtGroup = false){
        $queryData = array();
        $queryData['tableName'] = $this->groupMaster;
        $queryData['where']['group_code'] = $groupCode;
        if($defualtGroup == true)
            $queryData['where']['is_default'] = 1;
        $groupData = $this->row($queryData);
        return $groupData;
    }

    public function getGroupListOnGroupCode($groupCode){
        $queryData = array();
        $queryData['tableName'] = $this->groupMaster;
        $queryData['customWhere'][] = $groupCode;
        $groupData = $this->rows($queryData);
        return $groupData;
    }

    public function getGroup($id){
        $queryData = array();
        $queryData['tableName'] = $this->groupMaster;
        $queryData['where']['id'] = $id;
        $groupData = $this->row($queryData);
        return $groupData;
    }

    public function getGroupList(){
        $queryData = array();
        $queryData['tableName'] = $this->groupMaster;
        $groupData = $this->rows($queryData);
        return $groupData;
    }
}
?>