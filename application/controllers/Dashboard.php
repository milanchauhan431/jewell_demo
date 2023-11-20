<?php
class Dashboard extends MY_Controller{

	private $hbd_msg = '';
	public function __construct()	{
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Dashboard";
		$this->data['headData']->controller = "dashboard";
	}
	
	public function index(){
	    
		$this->data['lastSyncedAt'] = "";
		//$this->data['lastSyncedAt'] = $this->biometric->getDeviceData()[0]->last_sync_at;
		$this->data['lastSyncedAt'] = (!empty($this->data['lastSyncedAt'])) ? date('j F Y, g:i a',strtotime($this->data['lastSyncedAt'])) : "";
	    $this->data['todayBirthdayList'] = array();//$this->employee->getEmpTodayBirthdayList();

		$queryStockData['tableName'] = "stock_transaction";  
		$queryStockData['select'] = "stock_transaction.*,item_master.item_code,item_master.item_name,item_master.hsn_code,item_master.gst_per,item_master.unit_id,unit_master.unit_name";

		$queryStockData['leftJoin']['item_master'] = "item_master.id = stock_transaction.item_id";
        $queryStockData['leftJoin']['unit_master'] = "item_master.unit_id = unit_master.id";
		$queryStockData['where']['stock_type'] = "NEW"; 
	    $this->data['charge_data'] = $this->masterModel->rows($queryStockData); 

		$queryData['tableName'] = "purity_master";   
		$queryData['order_by']['id'] = "DESC"; 
	    $this->data['today_rate'] = $this->masterModel->rows($queryData);
        $this->load->view('dashboard',$this->data);
    }
	
	public function getDailyAttendance(){
		$data = $this->input->post();
		$todayStats = $this->attendance->getAttendanceStatsByDate(formatDate($data['report_date'],'Y-m-d'));
		$i=1;$tBody='';
		if(!empty($todayStats['empInfo']))
		{
			foreach($todayStats['empInfo'] as $row)
			{
				$pnch = explode(',',$row->punch_time);
				if(!empty($pnch)){if($pnch[0] > $row->shiftStart){$row->attend_status = "Late Arrived";}}
				$statusCls = 'text-dark';$rowBg= '';
				if($row->attend_status == "Absent"){$statusCls = 'text-danger';}
				if($row->attend_status == "Late Arrived"){$statusCls = 'text-warning';}
				if($row->attend_status == "Week Off" OR $row->attend_status == "Holiday"){$rowBg = 'background:#efff0033';}
				$tBody .= '<tr style="'.$rowBg.'">';
					$tBody .= '<td class="text-center">'.$row->emp_code.'</td>';
					$tBody .= '<td>'.$row->emp_name.'</td>';
					$tBody .= '<td>'.$row->name.'</td>';
					$tBody .= '<td>'.$row->shift_name.'</td>';
					$tBody .= '<td>'.$row->title.'</td>';
					$tBody .= '<td>'.$row->category.'</td>';
					$tBody .= '<th class="'.$statusCls.'">'.$row->attend_status.'</th>';
					$tBody .= '<td class="text-center">'.$row->punch_time.'</td>';
				$tBody .= '</tr>';
			}
		}
		$this->printJson(['status'=>1,"totalEmp"=>$todayStats['totalEmp'],"present"=>$todayStats['present'],"late"=>$todayStats['late'],"absent"=>$todayStats['absent'],'tbody'=>$tBody]);
	}

    public function syncDeviceData(){
		$this->printJson($this->biometric->syncDeviceData());
    }

    public function decodeQRCode(){
        $qrValue = $this->input->post('qrValue');
        $decodeData='';$itemData=new stdClass();$part_no="";
        if(!empty($qrValue))
        {
            $code = explode('#',$qrValue);
            $part_no=substr($code[0], 1,8);
            $itemData=$this->item->getItemByPartNo($part_no);
        }
        $decodeData='<table class="table table-bordered table-striped">
            <tr><th style="width:100px;">Part No.</th><td>'.$part_no.'</td></tr>
            <tr><th>Part Name</th><td>'.((!empty($itemData->full_name)) ? $itemData->full_name : '').'</td></tr>
            <tr><th>Rev. No.</th><td>'.((!empty($itemData->rev_no)) ? $itemData->rev_no : substr($code[0], -1)).'</td></tr>
            <tr><th>Job No.</th><td>'.substr($code[1], 1).'</td></tr>
            <tr><th>Vendor Code</th><td>'.substr($code[2], 1).'</td></tr>
            <!--<tr><th>Customer</th><td>'.((!empty($itemData->party_name)) ? $itemData->party_name : '').'</td>--></tr>
        </table>';
		$this->printJson(['status'=>1,"decodeData"=>$decodeData]);
    }
	public function saveTodo(){
        $data = file_get_contents('php://input'); 
        $data = json_decode($data,true);
        
        $this->printJson($this->dashboard->saveTodo($data));
    }
    
    public function getTodoList(){
        $data = $this->input->get();
        //print_r($data);exit;
        $this->printJson($this->dashboard->getTodoList($data));
    }
    
    public function deleteTodo(){
        $data = file_get_contents('php://input'); 
        $data = json_decode($data,true);
        $this->printJson($this->dashboard->deleteTodo($data['id']));
    }
}
?>