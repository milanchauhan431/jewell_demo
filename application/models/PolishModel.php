
<?php
class PolishModel extends MasterModel{
    private $polish = "polish_master";
	
    public function getDTRows($data){
        $data['tableName'] = $this->polish;

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "polish";
        $data['searchCol'][] = "remark";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
		return $this->pagingRows($data);
    }

    public function getPolish($data){
        $queryData['where']['id'] = $data['id'];
        $queryData['tableName'] = $this->polish;
        return $this->row($queryData);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->polish,$data,'Polish');

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

            $checkData['columnName'] = ["polish_id"];
            $checkData['value'] = $id;
            $checkUsed = $this->checkUsage($checkData);

            if($checkUsed == true):
                return ['status'=>0,'message'=>'The polish is currently in use. you cannot delete it.'];
            endif;

            $result = $this->trash($this->polish,['id'=>$id],'Polish');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getPolishList($data=array()){
        $queryData = array();
        $queryData['tableName'] = $this->polish;
        $result = $this->rows($queryData);
        return $result;
    }
}
?>