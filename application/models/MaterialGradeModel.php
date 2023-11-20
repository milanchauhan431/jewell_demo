<?php
class MaterialGradeModel extends MasterModel{
    private $materialMaster = "material_master";
	
	public function getDTRows($data){
        $data['tableName'] = $this->materialMaster;
        $data['select'] = "material_master.*,item_master.item_name as group_name,";
        $data['leftJoin']['item_master'] = "item_master.id = material_master.scrap_group";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "material_master.material_grade";
        $data['searchCol'][] = "material_master.standard";
        $data['searchCol'][] = "item_master.item_name"; 
        $data['searchCol'][] = "material_master.color_code";
        
		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
		return $this->pagingRows($data);
    }

    public function getMaterialGrades($data=array()){
        $queryData['tableName'] = $this->materialMaster;
        if(!empty($data['ids'])):
            $queryData['where_in']['id'] = $data['ids'];
        endif;
        return $this->rows($queryData);
    }

    public function getMaterial($data){
        $queryData['where']['id'] = $data['id'];
        $queryData['tableName'] = $this->materialMaster;
        return $this->row($queryData);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            $data['material_grade'] = trim($data['material_grade']);
            if($this->checkDuplicate($data) > 0):
                $errorMessage['material_grade'] = "Material Grade is duplicate.";
                return ['status'=>0,'message'=>$errorMessage];
            endif;
            
            $result = $this->store($this->materialMaster,$data,'Material Grade');            

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
        $queryData['tableName'] = $this->materialMaster;
        $queryData['where']['material_grade'] = $data['material_grade'];
        
        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];
        
        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $checkData['columnName'] = [];
            $checkData['value'] = $id;
            $checkUsed = $this->checkUsage($checkData);

            if($checkUsed == true):
                return ['status'=>0,'message'=>'The Material Grade is currently in use. you cannot delete it.'];
            endif;

            $result = $this->trash($this->materialMaster,['id'=>$id],'Material Grade');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getStandards(){
        $data['tableName'] = $this->materialMaster;
        $data['select'] = "DISTINCT(standard)";
        return $this->rows($data);
    }

    public function getColorCodes(){
        $data['tableName'] = $this->materialMaster;
        $data['select'] = "DISTINCT(color_code)";
        return $this->rows($data);
    }
}
?>