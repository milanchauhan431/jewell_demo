<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getSalesDtHeader($page){
    /* Lead Header  */
    $data['lead'][] = ["name"=>"Action","style"=>"width:5%;"];
	$data['lead'][] = ["name"=>"#","style"=>"width:5%;","textAlign"=>"center"]; 
	$data['lead'][] = ["name"=>"Approach Date"];
	$data['lead'][] = ["name"=>"Approach No"];
	$data['lead'][] = ["name"=>"Lead From"];
	$data['lead'][] = ["name"=>"Party Name"];
    $data['lead'][] = ["name"=>"Contact No."];
    $data['lead'][] = ["name"=>"Sales Executive"];
    $data['lead'][] = ["name"=>"Appointmens","textAlign"=>"center","sortable"=>"FALSE"];
    $data['lead'][] = ["name"=>"Followup Date","sortable"=>"FALSE"];
    $data['lead'][] = ["name"=>"Followup Remark","sortable"=>"FALSE"];
    $data['lead'][] = ["name"=>"Next Followup Date","sortable"=>"FALSE"];

    $data['lead_won'][] = ["name"=>"Action","style"=>"width:5%;"];
	$data['lead_won'][] = ["name"=>"#","style"=>"width:5%;","textAlign"=>"center"]; 
	$data['lead_won'][] = ["name"=>"Approach Date"];
	$data['lead_won'][] = ["name"=>"Approach No"];
	$data['lead_won'][] = ["name"=>"Lead From"];
	$data['lead_won'][] = ["name"=>"Party Name"];
    $data['lead_won'][] = ["name"=>"Contact No."];
    $data['lead_won'][] = ["name"=>"Sales Executive"];
    $data['lead_won'][] = ["name"=>"Followup Date","sortable"=>"FALSE"];
    $data['lead_won'][] = ["name"=>"Followup Remark","sortable"=>"FALSE"];

    $data['lead_lost'][] = ["name"=>"Action","style"=>"width:5%;"];
	$data['lead_lost'][] = ["name"=>"#","style"=>"width:5%;","textAlign"=>"center"]; 
	$data['lead_lost'][] = ["name"=>"Approach Date"];
	$data['lead_lost'][] = ["name"=>"Approach No"];
	$data['lead_lost'][] = ["name"=>"Lead From"];
	$data['lead_lost'][] = ["name"=>"Party Name"];
    $data['lead_lost'][] = ["name"=>"Contact No."];
    $data['lead_lost'][] = ["name"=>"Sales Executive"];
    $data['lead_lost'][] = ["name"=>"Lost Remark"];

    /* Sales Enquiry Header */
    $data['salesEnquiry'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['salesEnquiry'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['salesEnquiry'][] = ["name"=>"SE. No."];
	$data['salesEnquiry'][] = ["name"=>"SE. Date"];
	$data['salesEnquiry'][] = ["name"=>"Customer Name"];
	$data['salesEnquiry'][] = ["name"=>"Item Name"];
    $data['salesEnquiry'][] = ["name"=>"Qty"];

    /* Sales Quotation Header */
    $data['salesQuotation'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['salesQuotation'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	//$data['salesQuotation'][] = ["name"=>"Rev. No.","textAlign"=>"center"];
	$data['salesQuotation'][] = ["name"=>"SQ. No."];
	$data['salesQuotation'][] = ["name"=>"SQ. Date"];
	$data['salesQuotation'][] = ["name"=>"Customer Name"];
	$data['salesQuotation'][] = ["name"=>"Item Name"];
    $data['salesQuotation'][] = ["name"=>"Qty"];
    $data['salesQuotation'][] = ["name"=>"Price"];
    /* $data['salesQuotation'][] = ["name"=>"Confirmed BY"];
    $data['salesQuotation'][] = ["name"=>"Confirmed Date"];
    $data['salesQuotation'][] = ["name"=>"Confirmed Note"]; */

    /* Proforma Invoice Header */
    $data['proformaInvoice'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['proformaInvoice'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['proformaInvoice'][] = ["name"=>"Inv No."];
	$data['proformaInvoice'][] = ["name"=>"Inv Date"];
	$data['proformaInvoice'][] = ["name"=>"Customer Name"];
	$data['proformaInvoice'][] = ["name"=>"Taxable Amount"];
	$data['proformaInvoice'][] = ["name"=>"GST Amount"];
    $data['proformaInvoice'][] = ["name"=>"Net Amount"];
	
    /* Sales Order Header */
    $data['salesOrders'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['salesOrders'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['salesOrders'][] = ["name"=>"SO. No."];
	$data['salesOrders'][] = ["name"=>"SO. Date"];
	$data['salesOrders'][] = ["name"=>"Customer Name"];
	$data['salesOrders'][] = ["name"=>"Item Name"];
    $data['salesOrders'][] = ["name"=>"Order Qty"];
    $data['salesOrders'][] = ["name"=>"Dispatch Qty"];
    $data['salesOrders'][] = ["name"=>"Pending Qty"];

    /* Estimate [Cash] Header */
    $data['estimate'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['estimate'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['estimate'][] = ["name"=>"Inv No."];
	$data['estimate'][] = ["name"=>"Inv Date"];
	$data['estimate'][] = ["name"=>"Customer Name"];
	$data['estimate'][] = ["name"=>"Taxable Amount"];
    $data['estimate'][] = ["name"=>"Net Amount"];

    /* Delivery Challan Header */
    $data['deliveryChallan'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['deliveryChallan'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['deliveryChallan'][] = ["name"=>"DC No."];
    $data['deliveryChallan'][] = ["name"=>"DC Date"];
    $data['deliveryChallan'][] = ["name"=>"Customer Name"];
    $data['deliveryChallan'][] = ["name"=>"Remark"];

    return tableHeader($data[$page]);
}

/* Proforma Invoice Table Data */
function getProformaInvoiceData($data){
    $editButton = '<a class="btn btn-warning btn-edit permission-modify" href="'.base_url('proformaInvoice/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="ti-pencil-alt"></i></a>';

    $approveButton = '<a class="btn btn-success btn-edit permission-approve" href="'.base_url('proformaInvoice/edit/'.$data->id."/1").'" datatip="Approve" flow="down" ><i class="ti-check"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Sales Invoice'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $print = '<a href="javascript:void(0)" class="btn btn-info btn-edit printDialog permission-approve1" datatip="Print Invoice" flow="down" data-id="'.$data->id.'" data-fn_name="printInvoice"><i class="fa fa-print"></i></a>';

    //$print = '<a href="'.base_url('proformaInvoice/printRecipet/'.$data->id).'" target="_blank" class="btn btn-info btn-edit permission-approve1" datatip="Print Invoice" flow="down" data-id="'.$data->id.'" data-fn_name="printRecipet"><i class="fa fa-print"></i></a>';
   
    $unapprovedParam = "{'postData':{'id' : ".$data->id.",'is_approve':0},'modal_id' : 'modal-md', 'form_id' : 'reversalApproval', 'title' : 'Approval reversal','fnedit':'reversalApproval','fnsave':'saveReversalApproval'}";
    $unapprovedButton = '<a class="btn btn-danger btn-edit permission-approve" href="javascript:void(0)" datatip="Approval Reversal" flow="down" onclick="edit('.$unapprovedParam.');"><i class="ti-close"></i></a>';
	
    if(!empty($data->is_approve)):
        $approveButton = $editButton = $deleteButton = '';
    else:
        $unapprovedButton = '';
    endif;

	if(!empty($data->trans_status)):
		$approveButton = $editButton = $deleteButton = '';
    endif;

    $action = getActionButton($print.$approveButton.$unapprovedButton.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->taxable_amount,$data->gst_amount,$data->net_amount];
}

/* Lead Table Data */
function getLeadData($data){

    $followupBtn = '';$appointmentBtn ='';$enqBtn='';$editButton="";$deleteButton="";$leadStatusButton = "";
       
    if(in_array($data->lead_status,[0,4])):
        $followupParam = "{'postData': {'id' : ".$data->id.",'party_id':".$data->party_id.",'sales_executive':".$data->sales_executive.",'entry_type':1}, 'modal_id' : 'modal-lg', 'form_id' : 'followUp', 'title' : 'Follow up', 'fnedit' : 'addFollowup', 'fnsave' : 'saveFollowup','res_function' : 'resFollowup', 'button' : 'close'}";
        $followupBtn = '<a class="btn btn-primary" href="javascript:void(0)" datatip="Followup" flow="down" onclick="edit('.$followupParam.');" ><i class="fas fa-clipboard-check"></i></a>';

        $appointmentParam = "{'postData': {'id' : ".$data->id.",'party_id':".$data->party_id.",'entry_type':2}, 'modal_id' : 'modal-lg', 'form_id' : 'appointment', 'title' : 'Appointments', 'fnedit' : 'addAppointment', 'fnsave' : 'saveAppointment','res_function' : 'resAppointments', 'button' : 'close'}";
        $appointmentBtn = '<a class="btn btn-info leadAction" href="javascript:void(0)" datatip="Appointment" flow="down" onclick="edit('.$appointmentParam.');"><i class="far fa-calendar-check"></i></a>';
    endif;

    if($data->lead_status == 0 && empty($data->enq_id)):      
        $editParam = "{'postData' : {'id' : ".$data->id."}, 'modal_id' : 'modal-xl', 'form_id' : 'editLead', 'title' : 'Update Approach'}";
    
        $editButton = '<a class="btn btn-warning btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';

        $leadParam = "{'postData' : {'id' : ".$data->id."}, 'modal_id' : 'modal-md', 'form_id' : 'approachStatus', 'title' : 'Update Approach Status','fnedit':'approachStatus','fnsave':'saveApproachStatus'}";
        $leadStatusButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Approach Status" flow="down" onclick="edit('.$leadParam.');"><i class="fa fa-check"></i></a>';
    endif;

    if(in_array($data->lead_status,[0,4])):
        $postData = ['party_id'=>$data->party_id,'lead_id'=>$data->id];
        $encodedData = urlencode(base64_encode(json_encode($postData)));
        $enqBtn = '<a class="btn btn-info" href="'.base_url('salesEnquiry/create/'.$encodedData).'" datatip="Carete Enquiry" flow="down" ><i class="fa fa-file-alt"></i></a>';
    endif;

    $action = getActionButton($enqBtn.$appointmentBtn.$followupBtn.$leadStatusButton.$editButton.$deleteButton);

    if($data->lead_status == 3):
        $responseData = [$action,$data->sr_no,formatDate($data->lead_date),sprintf("%04d",$data->lead_no),$data->lead_from,$data->party_name,$data->party_phone,$data->emp_name,$data->followupDate,$data->followupNote];
    elseif($data->lead_status == 4):
        $responseData = [$action,$data->sr_no,formatDate($data->lead_date),sprintf("%04d",$data->lead_no),$data->lead_from,$data->party_name,$data->party_phone,$data->emp_name,$data->reason];
    else:
        $responseData = [$action,$data->sr_no,formatDate($data->lead_date),sprintf("%04d",$data->lead_no),$data->lead_from,$data->party_name,$data->party_phone,$data->emp_name,$data->appointments,$data->followupDate,$data->followupNote,$data->next_fup_date];
    endif;

    return $responseData;
}

/* Sales Enquiry Table data */
function getSalesEnquiryData($data){
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('salesEnquiry/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="ti-pencil-alt"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Sales Enquiry'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $postData = ['enq_id'=>$data->id];
    $encodedData = urlencode(base64_encode(json_encode($postData)));
    $quotationBtn = '<a class="btn btn-info" href="'.base_url('salesQuotation/create/'.$encodedData).'" datatip="Carete Quotation" flow="down" ><i class="fa fa-file-alt"></i></a>';    

    if($data->trans_status > 0):
        $quotationBtn = $editButton = $deleteButton = "";
    endif;

    $action = getActionButton($quotationBtn.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,$data->trans_date,$data->party_name,$data->item_name,floatVal($data->qty)];
}

/* Sales Quotation Table data */
function getSalesQuotationData($data){
    $editButton = '<a class="btn btn-warning btn-edit permission-modify" href="'.base_url('salesQuotation/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="ti-pencil-alt"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Sales Quotation'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    //$revision = '<a href="'.base_url('salesQuotation/reviseQuotation/'.$data->id).'" class="btn btn-primary btn-edit permission-modify" datatip="Revision" flow="down"><i class="fa fa-retweet"></i></a>';

    //$followupParam = "{'postData': {'id' : ".$data->id.",'party_id':".$data->party_id.",'sales_executive':".$data->sales_executive.",'entry_type':4}, 'modal_id' : 'modal-lg', 'form_id' : 'followUp', 'title' : 'Follow up', 'fnedit' : 'addFollowup', 'fnsave' : 'saveFollowup','res_function' : 'resFollowup', 'button' : 'close','controller':'lead'}";
    //$followupBtn = '<a class="btn btn-info" href="javascript:void(0)" datatip="Followup" flow="down" onclick="edit('.$followupParam.');" ><i class="fas fa-clipboard-check"></i></a>';

    //$quoteParam = "{'postData' : {'id' : ".$data->id.",'entry_type':4}, 'modal_id' : 'modal-md', 'form_id' : 'approachStatus', 'title' : 'Update Quotation Status','fnedit':'approachStatus','fnsave':'saveApproachStatus','controller':'lead'}";
    //$quoteStatusButton = '<a class="btn btn-success btn-edit permission-approve" href="javascript:void(0)" datatip="Quotation Status" flow="down" onclick="edit('.$quoteParam.');"><i class="fa fa-check"></i></a>';

    $printBtn = '<a class="btn btn-success btn-edit permission-approve" href="'.base_url('salesQuotation/printPdfQuotation/'.$data->id).'" target="_blank" datatip="Download PDF" flow="down"><i class="fas fa-file-pdf" ></i></a>';

    $pdfBtn = '<a class="btn btn-info btn-edit permission-approve" href="'.base_url('salesQuotation/printQuotation/'.$data->id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print" ></i></a>';

    if($data->trans_status > 0):
        /* $revision = */ $editButton = $deleteButton = "";
    endif;

    if(!empty($data->is_approve)):
        /* $quoteStatusButton = $followupBtn = $revision =  */$editButton = $deleteButton = "";
    endif;

    $action = getActionButton($printBtn.$pdfBtn.$editButton.$deleteButton);

    /* $rev_no = sprintf("%02d",$data->quote_rev_no);
    if($data->quote_rev_no > 0):
        $revParam = "{'postData' : {'trans_number' : '".$data->trans_number."'}, 'modal_id' : 'modal-md', 'form_id' : 'revisionList', 'title' : 'Quotation Revision History','fnedit':'revisionHistory','button':'close'}";
        $rev_no = '<a href="javascript:void(0)" datatip="Revision History" flow="down" onclick="edit('.$revParam.');">'.sprintf("%02d",$data->quote_rev_no).'</a>';
    endif; */

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->item_name,$data->qty,$data->price/* ,$data->approve_by_name,((!empty($data->approve_date))?formatDate($data->approve_date):""),$data->close_reason */];
}

/* Sales Order Table data */
function getSalesOrderData($data){
    $editButton = '<a class="btn btn-warning btn-edit permission-modify" href="'.base_url('salesOrders/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="ti-pencil-alt"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Sales Order'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $printBtn = '<a class="btn btn-success btn-edit permission-approve1" href="'.base_url('salesOrders/printOrder/'.$data->id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print" ></i></a>';

    if($data->trans_status > 0):
        $editButton = $deleteButton = "";
    endif;

    $action = getActionButton($printBtn.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,$data->trans_date,$data->party_name,$data->item_name,$data->qty,$data->dispatch_qty,$data->pending_qty];
}

/* Estimate [Cash] Table Data */
function getEstimateData($data){
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('estimate/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="ti-pencil-alt"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Sales Invoice'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $print = '';
    //$print = '<a href="javascript:void(0)" class="btn btn-warning btn-edit printDialog permission-approve1" datatip="Print Invoice" flow="down" data-id="'.$data->id.'" data-fn_name="printInvoice"><i class="fa fa-print"></i></a>';

    if($data->trans_no == 0):
        $editButton = $deleteButton = "";
    endif;

    $action = getActionButton($print.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,$data->trans_date,$data->party_name,$data->taxable_amount,$data->net_amount];
}


/* Delivery Challan  Table Data */
function getDeliveryChallanData($data){
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('deliveryChallan/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="ti-pencil-alt"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Delivery Challan'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $print = '<a class="btn btn-success btn-edit permission-approve1" href="'.base_url('deliveryChallan/printChallan/'.$data->id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print" ></i></a>';

    $action = getActionButton($print.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->remark];
}
?>