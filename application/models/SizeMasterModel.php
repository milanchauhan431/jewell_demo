<?php
class SizeMasterModel extends MasterModel{
    private $sizeMaster = "size_master";

    public function getDTRows($data){
        $data['tableName'] = $this->sizeMaster;

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "shape";
        $data['searchCol'][] = "size";
        $data['searchCol'][] = "size_mm";
        $data['searchCol'][] = "remark";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
		return $this->pagingRows($data);
    }

    public function getSize($data){
        $queryData['where']['id'] = $data['id'];
        $queryData['tableName'] = $this->sizeMaster;
        return $this->row($queryData);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            if($this->checkDuplicate($data) > 0):
                return ['status'=>0,'message'=>['size'=>"Size is duplicate."]];
            endif;

            $result = $this->store($this->sizeMaster,$data,'Size');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function checkDuplicate($data){
        $queryData['tableName'] = $this->sizeMaster;

        if(!empty($data['shape']))
            $queryData['where']['shape'] = $data['shape'];
        if(!empty($data['size']))
            $queryData['where']['size'] = $data['size'];

        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];

        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $checkData['columnName'] = ["size_id"];
            $checkData['value'] = $id;
            $checkUsed = $this->checkUsage($checkData);

            if($checkUsed == true):
                return ['status'=>0,'message'=>'The size is currently in use. you cannot delete it.'];
            endif;

            $result = $this->trash($this->sizeMaster,['id'=>$id],'Terms');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getSizeList($data=array()){
        $queryData['tableName'] = $this->sizeMaster;
        return $this->rows($queryData);
    }
}
?>