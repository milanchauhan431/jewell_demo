<?php
class StoreReportModel extends MasterModel{
    private $itemMaster = "item_master";
    private $stockTrans = "stock_transaction";

    public function getStockRegisterData($data){
        $queryData = array();
        $queryData['tableName'] = $this->itemMaster;
        $queryData['select'] = "item_master.id,item_master.item_code,item_master.item_name,ifnull(st.stock_qty,0) as stock_qty,ifnull(st.gross_weight,0) as gross_weight,ifnull(st.net_weight,0) as net_weight";

        $queryData['leftJoin']['(SELECT SUM(qty * p_or_m) as stock_qty,SUM(gross_weight * p_or_m) as gross_weight,SUM(net_weight * p_or_m) as net_weight,item_id FROM stock_transaction WHERE is_delete = 0 GROUP BY item_id) as st'] = "item_master.id = st.item_id";

        $queryData['where']['item_master.item_type'] = $data['item_type'];
        if(!empty($data['stock_type'])):
            if($data['stock_type'] == 1):
                $queryData['where']['ifnull(st.stock_qty,0) > '] = "ifnull(st.stock_qty,0) > 0";
            else:
                $queryData['where']['ifnull(st.stock_qty,0) <= '] = "0";
            endif;
        endif;

        $result = $this->rows($queryData);
        return $result;
    }

    public function getLocationWiseStockData($data){
        $queryData = array();
        $queryData['tableName'] = $this->stockTrans;
        $queryData['select'] = "stock_transaction.item_id,stock_transaction.location_id,location_master.location,stock_transaction.item_id,SUM(stock_transaction.qty * stock_transaction.p_or_m) as stock_qty,SUM(stock_transaction.gross_weight * stock_transaction.p_or_m) as gross_weight,SUM(stock_transaction.net_weight * stock_transaction.p_or_m) as net_weight";
        $queryData['leftJoin']['location_master'] = "stock_transaction.location_id = location_master.id";
        $queryData['where']['stock_transaction.item_id'] = $data['item_id'];
        $queryData['having'][] = "SUM(stock_transaction.qty * stock_transaction.p_or_m) > 0";
        $queryData['group_by'][] = "stock_transaction.location_id";
        $result = $this->rows($queryData);
        return $result;
    }

    public function getSerialNumberWiseStockData($data){
        $queryData = array();
        $queryData['tableName'] = $this->stockTrans;
        $queryData['select'] = "stock_transaction.item_id,stock_transaction.location_id,location_master.location,stock_transaction.unique_id,stock_transaction.item_id,SUM(stock_transaction.qty * stock_transaction.p_or_m) as stock_qty,SUM(stock_transaction.gross_weight * stock_transaction.p_or_m) as gross_weight,SUM(stock_transaction.net_weight * stock_transaction.p_or_m) as net_weight";
        $queryData['leftJoin']['location_master'] = "stock_transaction.location_id = location_master.id";
        $queryData['where']['stock_transaction.item_id'] = $data['item_id'];
        $queryData['where']['stock_transaction.location_id'] = $data['location_id'];
        $queryData['having'][] = "SUM(stock_transaction.qty * stock_transaction.p_or_m) > 0";
        $queryData['group_by'][] = "stock_transaction.unique_id";
        $result = $this->rows($queryData);
        return $result;
    }

    public function getItemHistoryData($data){
        $queryData = array();
        $queryData['tableName'] = $this->itemMaster;
        $queryData['select'] = "item_master.id,item_master.item_code,item_master.item_name,ifnull(st.op_stock_qty,0) as op_stock_qty,ifnull(st.op_gross_weight,0) as op_gross_weight,ifnull(st.op_net_weight,0) as op_net_weight,ifnull(st.in_stock_qty,0) as in_stock_qty,ifnull(st.in_gross_weight,0) as in_gross_weight,ifnull(st.in_net_weight,0) as in_net_weight,ifnull(st.out_stock_qty,0) as out_stock_qty,ifnull(st.out_gross_weight,0) as out_gross_weight,ifnull(st.out_net_weight,0) as out_net_weight,ifnull(st.cl_stock_qty,0) as cl_stock_qty,ifnull(st.cl_gross_weight,0) as cl_gross_weight,ifnull(st.cl_net_weight,0) as cl_net_weight";

        $queryData['leftJoin']['(SELECT 
        item_id,
        SUM((CASE WHEN ref_date < "'.$data['from_date'].'" THEN (qty * p_or_m) ELSE 0 END)) as op_stock_qty,
        SUM((CASE WHEN ref_date < "'.$data['from_date'].'" THEN (gross_weight * p_or_m) ELSE 0 END)) as op_gross_weight,
        SUM((CASE WHEN ref_date < "'.$data['from_date'].'" THEN (net_weight * p_or_m) ELSE 0 END)) as op_net_weight,
        
        SUM((CASE WHEN ref_date >= "'.$data['from_date'].'" AND ref_date <= "'.$data['to_date'].'" AND p_or_m = 1 THEN qty ELSE 0 END)) as in_stock_qty,
        SUM((CASE WHEN ref_date >= "'.$data['from_date'].'" AND ref_date <= "'.$data['to_date'].'" AND p_or_m = 1 THEN gross_weight ELSE 0 END)) as in_gross_weight,
        SUM((CASE WHEN ref_date >= "'.$data['from_date'].'" AND ref_date <= "'.$data['to_date'].'" AND p_or_m = 1 THEN net_weight ELSE 0 END)) as in_net_weight,
        
        SUM((CASE WHEN ref_date >= "'.$data['from_date'].'" AND ref_date <= "'.$data['to_date'].'" AND p_or_m = -1 THEN qty ELSE 0 END)) as out_stock_qty,
        SUM((CASE WHEN ref_date >= "'.$data['from_date'].'" AND ref_date <= "'.$data['to_date'].'" AND p_or_m = -1 THEN gross_weight ELSE 0 END)) as out_gross_weight,
        SUM((CASE WHEN ref_date >= "'.$data['from_date'].'" AND ref_date <= "'.$data['to_date'].'" AND p_or_m = -1 THEN net_weight ELSE 0 END)) as out_net_weight,
        
        SUM((CASE WHEN ref_date <= "'.$data['to_date'].'" THEN (qty * p_or_m) ELSE 0 END)) as cl_stock_qty,
        SUM((CASE WHEN ref_date <= "'.$data['to_date'].'" THEN (gross_weight * p_or_m) ELSE 0 END)) as cl_gross_weight,
        SUM((CASE WHEN ref_date <= "'.$data['to_date'].'" THEN (net_weight * p_or_m) ELSE 0 END)) as cl_net_weight 
        FROM stock_transaction WHERE is_delete = 0 GROUP BY item_id) as st'] = "item_master.id = st.item_id";

        if(!empty($data['item_id'])):
            $queryData['where']['item_master.id'] = $data['item_id'];
            $result = $this->row($queryData);
        else:
            $result = $this->rows($queryData);
        endif;
        return $result;
    }

    public function getItemHistoryDetailData($data){
        $queryData = array();
        $queryData['tableName'] = "stock_transaction";
        $queryData['select'] = "stock_transaction.*,(CASE WHEN stock_transaction.p_or_m = 1 THEN stock_transaction.qty ELSE 0 END) as in_qty,(CASE WHEN stock_transaction.p_or_m = 1 THEN stock_transaction.gross_weight ELSE 0 END) as in_gross_weight,(CASE WHEN stock_transaction.p_or_m = 1 THEN stock_transaction.net_weight ELSE 0 END) as in_net_weight,(CASE WHEN stock_transaction.p_or_m = -1 THEN stock_transaction.qty ELSE 0 END) as out_qty,(CASE WHEN stock_transaction.p_or_m = -1 THEN stock_transaction.gross_weight ELSE 0 END) as out_gross_weight,(CASE WHEN stock_transaction.p_or_m = -1 THEN stock_transaction.net_weight ELSE 0 END) as out_net_weight,party_master.party_name,sub_menu_master.vou_name_long";

        $queryData['leftJoin']['party_master'] = "party_master.id = stock_transaction.party_id";
        $queryData['leftJoin']['sub_menu_master'] = "sub_menu_master.id = stock_transaction.entry_type";
        
        $queryData['where']['stock_transaction.item_id'] = $data['item_id'];
        $queryData['where']['stock_transaction.ref_date >='] = $data['from_date'];
        $queryData['where']['stock_transaction.ref_date <='] = $data['to_date'];

        $result = $this->rows($queryData);
        return $result;
    }
}
?>