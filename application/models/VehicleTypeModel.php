<?php
class VehicleTypeModel extends MasterModel{
    private $vehicleType = "vehicle_types";

    public function getDTRows($data){
        $data['tableName'] = $this->vehicleType;

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "vehicle_type";
        $data['serachCol'][] = "remark";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }
    
    public function getVehicleTypeList($data=array()){
        $queryData['tableName'] = $this->vehicleType;
        $result = $this->rows($queryData);
        return $result;
    }

    public function getVehicleType($data){
        $data['tableName'] = $this->vehicleType;
        $data['where']['id'] = $data['id'];
        return $this->row($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->vehicleType,$data,'Vehicle Type');

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

            $checkData['columnName'] = ["vehicle_type"];
            $checkData['value'] = $id;
            $checkUsed = $this->checkUsage($checkData);

            if($checkUsed == true):
                return ['status'=>0,'message'=>'The Vehicle Type is currently in use. you cannot delete it.'];
            endif;

            $result = $this->trash($this->vehicleType,['id'=>$id],'Vehicle Type');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }
	
}