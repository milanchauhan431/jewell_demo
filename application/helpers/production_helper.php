<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getProductionDtHeader($page){

    /* Estimation & Design Header */
    $data['estimation'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['estimation'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['estimation'][] = ["name"=>"Job No."];
	$data['estimation'][] = ["name"=>"Job Date"];
	$data['estimation'][] = ["name"=>"Customer Name"];
	$data['estimation'][] = ["name"=>"Item Name"];
    $data['estimation'][] = ["name"=>"Order Qty"];
    $data['estimation'][] = ["name"=>"Bom Status"];
    $data['estimation'][] = ["name"=>"Priority"];
    $data['estimation'][] = ["name"=>"FAB. PRODUCTION NOTE"];
    $data['estimation'][] = ["name"=>"POWER COATING NOTE"];
    $data['estimation'][] = ["name"=>"ASSEMBALY NOTE"];
    $data['estimation'][] = ["name"=>"GENERAL NOTE"];

    return tableHeader($data[$page]);
}

function getEstimationData($data){

    $soBomParam = "{'postData':{'trans_main_id' : ".$data->trans_main_id.",'trans_child_id':".$data->trans_child_id."},'modal_id' : 'modal-xxl', 'form_id' : 'addOrderBom', 'fnedit':'orderBom', 'fnsave':'saveOrderBom','title' : 'Order Bom','res_function':'resSaveOrderBom','js_store_fn':'customStore'}";
    $soBom = '<a class="btn btn-info btn-delete permission-write" href="javascript:void(0)" onclick="edit('.$soBomParam.');" datatip="SO Bom" flow="down"><i class="fa fa-database"></i></a>';

    $viewBomParam = "{'postData':{'trans_child_id':".$data->trans_child_id."},'modal_id' : 'modal-xl','fnedit':'viewOrderBom','title' : 'View Bom [Item Name : ".$data->item_name."]','button':'close'}";
    $viewBom = '<a class="btn btn-primary permission-read" href="javascript:void(0)" onclick="edit('.$viewBomParam.');" datatip="View Item Bom" flow="down"><i class="fa fa-eye"></i></a>';

    $reqParam = "{'postData':{'trans_child_id':".$data->trans_child_id.",'trans_number':'".$data->trans_number."','item_name':'".$data->item_name."'},'modal_id' : 'modal-xl', 'form_id' : 'addOrderBom', 'fnedit':'purchaseRequest', 'fnsave':'savePurchaseRequest','title' : 'Send Purchase Request'}";
    $reqButton = '<a class="btn btn-info btn-delete permission-write" href="javascript:void(0)" onclick="edit('.$reqParam.');" datatip="Purchase Request" flow="down"><i class="fa fa-paper-plane"></i></a>';

    $estimationParam = "{'postData':{'id':'".$data->id."','trans_child_id':".$data->trans_child_id.",'trans_main_id':'".$data->trans_main_id."'},'modal_id' : 'modal-xl', 'form_id' : 'estimation', 'fnedit':'addEstimation', 'fnsave':'saveEstimation','title' : 'Estimation & Design'}";
    $estimationButton = '<a class="btn btn-success permission-write" href="javascript:void(0)" onclick="edit('.$estimationParam.');" datatip="Estimation" flow="down"><i class="fa fa-plus"></i></a>';

    if($data->priority == 1):
        $data->priority_status = '<span class="badge badge-pill badge-danger m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 2):
        $data->priority_status = '<span class="badge badge-pill badge-warning m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 3):
        $data->priority_status = '<span class="badge badge-pill badge-info m-1">'.$data->priority_status.'</span>';
    endif;

    $data->bom_status = '<span class="badge badge-pill badge-'.(($data->bom_status == "Generated")?"success":"danger").' m-1">'.$data->bom_status.'</span>';

    $action = getActionButton($soBom.$viewBom.$reqButton.$estimationButton);

    return [$action,$data->sr_no,$data->job_number,$data->trans_date,$data->party_name,$data->item_name,$data->qty,$data->bom_status,$data->priority_status,$data->fab_dept_note,$data->pc_dept_note,$data->ass_dept_note,$data->remark];
}
?>