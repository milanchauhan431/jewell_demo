<?php
class StockTrans extends MY_Controller{
    private $indexPage = "stock_trans/index";
    private $form = "stock_trans/form";    

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "FG Stock Inward";
		$this->data['headData']->controller = "stockTrans";        
        $this->data['headData']->pageUrl = "stockTrans";
        //$this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'stockTrans']);
	}

    public function index(){
        $this->data['tableHeader'] = getStoreDtHeader("stockTrans");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();
        $data['entry_type'] = $this->data['entryData']->id;
        $result = $this->itemStock->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getStockTransData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addStock(){
        $this->data['itemList'] = $this->item->getItemList();
        $this->load->view($this->form, $this->data);
    }

    public function getItemDetails(){
		$data = $this->input->post();
        $itemDetail = $this->itemStock->getStockTrans($data);
		if($itemDetail){
			$itemDetail->price =  (float)getItemPriceByRate($itemDetail);
			$itemDetail->gold_platinum_price =  (float)getPltinumPriceByGn($itemDetail);
            if (in_array($itemDetail->stock_category, array("Lab Grown Diamond", "Loos Diamond","Diamond Items","Lab Grown Diamond + Gold Items"))){
                $itemDetail->diamond_amount = 0;
            }
		}
        $this->printJson(['status'=>1,'data'=>['itemDetail'=>$itemDetail]]);
    }

    public function save(){
        $data = $this->input->post();
		$errorMessage = array();		

        if(empty($data['item_id']))
			$errorMessage['item_id'] = "Item Name is required.";
        if(empty(floatVal($data['qty'])))
			$errorMessage['qty'] = "Qty is required.";
        if(empty($data['size']))
            $errorMessage['size'] = "Packing Standard is required.";

        if(!empty(floatVal($data['qty'])) && !empty($data['size'])):
            if(is_int(($data['qty'] / $data['size'])) == false):
                $errorMessage['qty'] = "Invalid qty against packing standard.";
            endif;
        endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['entry_type'] = $this->data['entryData']->id;
            $data['unique_id'] = 0;
            $data['location_id'] = $this->RTD_STORE->id;
            $data['batch_no'] = "GB";
            $this->printJson($this->itemStock->save($data));
        endif;
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->itemStock->delete($id));
        endif;
    }
}
?>