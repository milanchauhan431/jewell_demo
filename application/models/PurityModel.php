
<?php
class PurityModel extends MasterModel{
    private $purity = "purity_master";
	
    public function getDTRows($data){
        $data['tableName'] = $this->purity;

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "purity";
        $data['searchCol'][] = "remark";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
		return $this->pagingRows($data);
    }

    public function getPurity($data){
        if(!empty($data['id'])):
            $queryData['where']['id'] = $data['id'];
        endif;
        if(!empty($data['purity'])):
            $queryData['where']['purity'] = $data['purity'];
        endif;
        $queryData['tableName'] = $this->purity;
        return $this->row($queryData);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            if($this->checkDuplicate($data) > 0):
                $errorMessage['purity'] = "Purity is duplicate.";
                return ['status'=>0,'message'=>$errorMessage];
            endif;

            $result = $this->store($this->purity,$data,'Purity');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
				$masterDetails['purity'] = $data['purity'];
				$masterDetails['gold_rate'] = $data['gold_rate'];
				$masterDetails['silver_rate'] = $data['silver_rate'];
				$masterDetails['platinum_rate'] = $data['platinum_rate'];
				$masterDetails['palladium_rate'] = $data['palladium_rate'];
				$masterDetails['date'] = date("Y-m-d");  
				$masterDetails['id'] = "";
				$this->masterModel->store("rates",$masterDetails);
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function checkDuplicate($data){
        $queryData['tableName'] = $this->purity;

        if(!empty($data['purity']))
            $queryData['where']['purity'] = $data['purity'];
        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];

        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $checkData['columnName'] = ["purity_id"];
            $checkData['value'] = $id;
            $checkUsed = $this->checkUsage($checkData);

            if($checkUsed == true):
                return ['status'=>0,'message'=>'The purity is currently in use. you cannot delete it.'];
            endif;

            $result = $this->trash($this->purity,['id'=>$id],'Purity');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getPurityList($data=array()){
        $queryData = array();
        $queryData['tableName'] = $this->purity;
        $result = $this->rows($queryData);
        return $result;
    }
}
?>