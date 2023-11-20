
<?php
class diamondQualityModel extends MasterModel{
    private $quality = "diamond_quality_master";
	
    public function getDTRows($data){
        $data['tableName'] = $this->quality;

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "quality";
        $data['searchCol'][] = "remark";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
		return $this->pagingRows($data);
    }

    public function getPurity($data){
        $queryData['where']['id'] = $data['id'];
        $queryData['tableName'] = $this->quality;
        return $this->row($queryData);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->quality,$data,'Quality');

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

            $checkData['columnName'] = ["diamond_quality_id"];
            $checkData['value'] = $id;
            $checkUsed = $this->checkUsage($checkData);

            if($checkUsed == true):
                return ['status'=>0,'message'=>'The quality is currently in use. you cannot delete it.'];
            endif;

            $result = $this->trash($this->quality,['id'=>$id],'Qurity');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getList($data=array()){
        $queryData = array();
        $queryData['tableName'] = $this->quality;
        $result = $this->rows($queryData);
        return $result;
    }
}
?>