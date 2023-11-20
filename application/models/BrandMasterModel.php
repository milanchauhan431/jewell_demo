<?php
class BrandMasterModel extends MasterModel{
    private $brandMaster = "brand_master";

    public function getDTRows($data){
        $data['tableName'] = $this->brandMaster;

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "brand_name";
        $data['searchCol'][] = "remark";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
		return $this->pagingRows($data);
    }

    public function getBrand($data){
        $queryData['where']['id'] = $data['id'];
        $queryData['tableName'] = $this->brandMaster;
        return $this->row($queryData);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            if($this->checkDuplicate($data) > 0):
                return ['status'=>0,'message'=>['brand_name'=>"Brand Name is duplicate."]];
            endif;

            $result = $this->store($this->brandMaster,$data,'Brand');

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
        $queryData['tableName'] = $this->brandMaster;

        if(!empty($data['brand_name']))
            $queryData['where']['brand_name'] = $data['brand_name'];
        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];

        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $checkData['columnName'] = ["brand_id"];
            $checkData['value'] = $id;
            $checkUsed = $this->checkUsage($checkData);

            if($checkUsed == true):
                return ['status'=>0,'message'=>'The brand is currently in use. you cannot delete it.'];
            endif;

            $result = $this->trash($this->brandMaster,['id'=>$id],'Terms');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getBrandList($data=array()){
        $queryData['tableName'] = $this->brandMaster;
        return $this->rows($queryData);
    }
}
?>