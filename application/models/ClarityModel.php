
<?php
class ClarityModel extends MasterModel{
    private $clarity = "clarity_master";
	
    public function getDTRows($data){
        $data['tableName'] = $this->clarity;

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "clarity";
        $data['searchCol'][] = "remark";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
		return $this->pagingRows($data);
    }

    public function getClarity($data){
        $queryData['where']['id'] = $data['id'];
        $queryData['tableName'] = $this->clarity;
        return $this->row($queryData);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->clarity,$data,'Clarity');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $checkData['columnName'] = ["clarity_id"];
            $checkData['value'] = $id;
            $checkUsed = $this->checkUsage($checkData);

            if($checkUsed == true):
                return ['status'=>0,'message'=>'The clarity is currently in use. you cannot delete it.'];
            endif;

            $result = $this->trash($this->clarity,['id'=>$id],'Clarity');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getClarityList($data=array()){
        $queryData = array();
        $queryData['tableName'] = $this->clarity;
        $result = $this->rows($queryData);
        return $result;
    }
}
?>