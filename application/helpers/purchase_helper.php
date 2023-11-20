<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getPurchaseDtHeader($page){
    /* Sales Order Header */
    $data['purchaseOrders'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['purchaseOrders'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['purchaseOrders'][] = ["name"=>"PO. No."];
	$data['purchaseOrders'][] = ["name"=>"PO. Date"];
	$data['purchaseOrders'][] = ["name"=>"Party Name"];
	$data['purchaseOrders'][] = ["name"=>"Item Name"];
    $data['purchaseOrders'][] = ["name"=>"Order Qty"];
    $data['purchaseOrders'][] = ["name"=>"Remark"];

    /* Purchase Indent Header */
    $masterCheckBox = '<input type="checkbox" id="masterSelect" class="filled-in chk-col-success BulkRequest" value=""><label for="masterSelect">ALL</label>';

    $data['purchaseIndent'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['purchaseIndent'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['purchaseIndent'][] = ["name"=>$masterCheckBox,"style"=>"width:10%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['purchaseIndent'][] = ["name"=>"Indent Date"];
    $data['purchaseIndent'][] = ["name"=>"Indent No"];
    $data['purchaseIndent'][] = ["name"=>"Job No"];
    $data['purchaseIndent'][] = ["name"=>"Make"];
    $data['purchaseIndent'][] = ["name"=>"Item Name"];
    $data['purchaseIndent'][] = ["name"=>"UOM"];   
    $data['purchaseIndent'][] = ["name"=>"Req. Qty"];    
    $data['purchaseIndent'][] = ["name"=>"Remark"]; 
    $data['purchaseIndent'][] = ["name"=>"Status"];

    /* Inward Receipt Header */
    $data['inwardReceipt'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['inwardReceipt'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['inwardReceipt'][] = ["name"=>"Inward No."];
	$data['inwardReceipt'][] = ["name"=>"Inward Date"];
	$data['inwardReceipt'][] = ["name"=>"Party Name"];
	$data['inwardReceipt'][] = ["name"=>"Item Name"];
	$data['inwardReceipt'][] = ["name"=>"Design No."];
    $data['inwardReceipt'][] = ["name"=>"Qty"];
    $data['inwardReceipt'][] = ["name"=>"Gross Weight"];
    $data['inwardReceipt'][] = ["name"=>"Net Weight"];
    $data['inwardReceipt'][] = ["name"=>"Purchase Price"];
    $data['inwardReceipt'][] = ["name"=>"Sales Price"];
    $data['inwardReceipt'][] = ["name"=>"Remark"];

    return tableHeader($data[$page]);
}

function getPurchaseOrderData($data){
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('purchaseOrders/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="ti-pencil-alt"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Purchase Order'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $printBtn = '<a class="btn btn-success btn-info" href="'.base_url('purchaseOrders/printPO/'.$data->id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print" ></i></a>';

    $action = getActionButton($printBtn.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,$data->trans_date,$data->party_name,$data->item_name,$data->qty,$data->item_remark];
}

/* Purchase Request Data  */
function getPurchaseIndentData($data){
    $approveReq=""; $closeReq="";

    $closeParam = "{'postData':{'id':".$data->id.",'order_status':3},'message':'Are you sure want to Close this Request?','fnsave':'changeRequestStatus'}";
    $closeReq = '<a href="javascript:void(0)" class="btn btn-dark closePreq permission-modify" onclick="confirmStore('.$closeParam.');" data-msg="Close" datatip="Close Purchase Request" flow="down" ><i class="ti-close"></i></a>';

    $selectBox ="";
    if($data->order_status ==0):
        $approveParam = "{'postData':{'id':".$data->id.",'order_status':1},'message':'Are you sure want to Approve this Request?','fnsave':'changeRequestStatus'}";
        $approveReq = '<a href="javascript:void(0)" class="btn btn-facebook permission-modify" onclick="confirmStore('.$approveParam.');" data-msg="Approve" datatip="Approve Purchase Request" flow="down" ><i class="fa fa-check"></i></a>';
    elseif($data->order_status == 1):
        $selectBox = '<input type="checkbox" name="ref_id[]" id="ref_id_'.$data->sr_no.'" class="filled-in chk-col-success BulkRequest" value="'.$data->id.'"><label for="ref_id_'.$data->sr_no.'"></label>';
    else:
        $closeReq="";
    endif;

    $action = getActionButton($approveReq.$closeReq);

    return [$action,$data->sr_no,$selectBox,(!empty($data->req_date))?date("d-m-Y",strtotime($data->req_date)):"",sprintf("IND%03d",$data->req_no),$data->job_number,$data->make,$data->item_name,$data->uom,$data->req_qty,$data->remark,$data->order_status_label];
   
}

function getInwardReceiptData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Inward Receipt'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-xxl', 'form_id' : 'editInward', 'title' : 'Update Inward Receipt'}";
    $barcodeParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'print_barcode_modal', 'form_id' : 'printBarcode', 'title' : 'Print Barcode','fnedit':'loadBarcode','fnsave':'printBarcode'}";
    $approveParam = "{'postData':{'id' : ".$data->id.",'is_approve':1},'modal_id' : 'modal-xxl', 'form_id' : 'editInward', 'title' : 'Approve Inward Receipt'}";
    $unapprovedParam = "{'postData':{'id' : ".$data->id.",'is_approve':0},'modal_id' : 'modal-md', 'form_id' : 'editInward', 'title' : 'Approval reversal','fnedit':'reversalApproval','fnsave':'saveReversalApproval'}";

    $editButton = '<a class="btn btn-warning btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
    $approveButton = '<a class="btn btn-success btn-edit permission-approve" href="javascript:void(0)" datatip="Approve Inward" flow="down" onclick="edit('.$approveParam.');"><i class="ti-check"></i></a>'; 
	
	$unapprovedButton = '<a class="btn btn-danger btn-edit permission-approve" href="javascript:void(0)" datatip="Approval reversal" flow="down" onclick="edit('.$unapprovedParam.');"><i class="ti-close"></i></a>';
	
    $barcode ='';
    if(!empty($data->approved_by)):
        $approveButton = $editButton = $deleteButton = "";
       // $barcode = '<a class="btn btn-success permission-approve" href="'.base_url('inwardReceipt/getBarcode/'.$data->id).'" datatip="Print Barcode" flow="down" ><i class="fa fa-barcode"></i></a>'; 
       $barcode = '<a class="btn btn-warning btn-edit" href="javascript:void(0)" datatip="Barcode Print" flow="down" onclick="edit('.$barcodeParam.');"><i class="fa fa-barcode"></i></a>';
    else:
		$unapprovedButton = '';
	endif;
	

    $action = getActionButton($unapprovedButton.$approveButton.$editButton.$deleteButton.$barcode);

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->item_name,$data->design_no,$data->qty,$data->gross_weight,$data->net_weight,$data->purchase_price,$data->sales_price,$data->remark];

}
?>