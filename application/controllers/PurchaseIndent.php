<?php
class PurchaseIndent extends MY_Controller{
    private $indexPage = "purchase_indent/index";
    private $form = "purchase_indent/form";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "PurchaseIndent";
        $this->data['headData']->controller = "purchaseIndent";
        $this->data['headData']->pageUrl = "purchaseIndent";
    }

    public function index(){
        $this->data['tableHeader'] = getPurchaseDtHeader("purchaseIndent");
        $this->load->view($this->indexPage, $this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post(); $data['status'] = $status;
        $result = $this->purchaseIndent->getDTRows($data);
        $sendData = array(); $i = ($data['start']+1);

        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;

            if ($row->order_status == 0) :
                $row->order_status_label = '<span class="badge badge-pill badge-danger m-1">Pending</span>';
            elseif ($row->order_status == 1) :
                $row->order_status_label = '<span class="badge badge-pill badge-info m-1">Accepted</span>';
            elseif ($row->order_status == 2) :
                $row->order_status_label = '<span class="badge badge-pill badge-success m-1">Complete</span>';
            elseif ($row->order_status == 3) :
                $row->order_status_label = '<span class="badge badge-pill badge-dark m-1">Reject</span>';
            endif;

            $sendData[] = getPurchaseIndentData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function changeRequestStatus(){
        $data = $this->input->post();
        $this->printJson($this->purchaseIndent->changeRequestStatus($data));
    }
}
?>