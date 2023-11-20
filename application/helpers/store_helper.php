<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getStoreDtHeader($page){
    /* Location Master header */
    $data['storeLocation'][] = ["name"=>"Action","style"=>"width:5%;",'textAlign'=>'center'];
    $data['storeLocation'][] = ["name"=>"#","style"=>"width:5%;",'textAlign'=>'center']; 
    $data['storeLocation'][] = ["name"=>"Store Name"];
    $data['storeLocation'][] = ["name"=>"Location"];
    $data['storeLocation'][] = ["name"=>"Remark"];

    /* Gate Entry */
    $data['gateEntry'][] = ["name" => "Action", "style" => "width:5%;", "textAlign" => "center"];
    $data['gateEntry'][] = ["name" => "#", "style" => "width:5%;", "textAlign" => "center"];
    $data['gateEntry'][] = ["name"=> "GE No.", "textAlign" => "center"];
    $data['gateEntry'][] = ["name" => "GE Date", "textAlign" => "center"];
    $data['gateEntry'][] = ["name" => "Transport"];
    $data['gateEntry'][] = ["name" => "LR No."];
    $data['gateEntry'][] = ["name" => "Vehicle Type"];
    $data['gateEntry'][] = ["name" => "Vehicle No."];
    $data['gateEntry'][] = ['name' => "Invoice No."];
    $data['gateEntry'][] = ['name' => "Invoice Date"];
    $data['gateEntry'][] = ['name' => "Challan No."];
    $data['gateEntry'][] = ['name' => "Challan Date"];

    /* Gate Inward Pending GE Tab Header */
    $data['pendingGE'][] = ["name" => "Action", "style" => "width:5%;", "textAlign" => "center"];
    $data['pendingGE'][] = ["name" => "#", "style" => "width:5%;", "textAlign" => "center"];
    $data['pendingGE'][] = ["name"=> "GE No.", "textAlign" => "center"];
    $data['pendingGE'][] = ["name" => "GE Date", "textAlign" => "center"];
    $data['pendingGE'][] = ["name" => "Party Name"];
    $data['pendingGE'][] = ["name" => "Inv. No."];
    $data['pendingGE'][] = ["name" => "Inv. Date"];
    $data['pendingGE'][] = ['name' => "CH. NO."];
    $data['pendingGE'][] = ['name' => "CH. Date"];

    /* Gate Inward Pending/Compeleted Tab Header */
    $data['gateInward'][] = ["name" => "Action", "style" => "width:5%;", "textAlign" => "center"];
    $data['gateInward'][] = ["name" => "#", "style" => "width:5%;", "textAlign" => "center"];
    $data['gateInward'][] = ["name"=> "GI No.", "textAlign" => "center"];
    $data['gateInward'][] = ["name" => "GI Date", "textAlign" => "center"];
    $data['gateInward'][] = ["name" => "Party Name"];
    $data['gateInward'][] = ["name" => "Item Name"];
    $data['gateInward'][] = ["name" => "Qty"];
    $data['gateInward'][] = ["name" => "PO. NO."]; 
    
    /* FG Stock Inward Table Header */
    $data['stockTrans'][] = ["name" => "Action", "style" => "width:5%;", "textAlign" => "center"];
    $data['stockTrans'][] = ["name" => "#", "style" => "width:5%;", "textAlign" => "center"];
    $data['stockTrans'][] = ["name" => "Date"];
    $data['stockTrans'][] = ["name"=> "Item Code"];
    $data['stockTrans'][] = ["name" => "Item Name"];
    $data['stockTrans'][] = ["name" => "Qty"];
    $data['stockTrans'][] = ["name" => "Packing Standard"];
    $data['stockTrans'][] = ["name" => "Remark"];

    /* Material Issue Header */
    $data['materialIssue'][] = ["name" => "Action", "style" => "width:5%;", "textAlign" => "center"];
    $data['materialIssue'][] = ["name" => "#", "style" => "width:5%;", "textAlign" => "center"];
    $data['materialIssue'][] = ["name" => "Issue No."];
    $data['materialIssue'][] = ["name"=> "Issue Date"];
    $data['materialIssue'][] = ["name" => "Collected By"];
    $data['materialIssue'][] = ["name" => "Sales Executive"];
    $data['materialIssue'][] = ["name" => "Total Issue Qty"];
    $data['materialIssue'][] = ["name" => "Total Return Qty"];
    $data['materialIssue'][] = ["name" => "Total Sold Qty"];
    $data['materialIssue'][] = ["name" => "Pending Qty"];

    return tableHeader($data[$page]);
}

/* Store Location Data */
function getStoreLocationData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Store Location'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'editStoreLocation', 'title' : 'Update Store Location'}";

    $editButton = ''; $deleteButton = '';
    if(!empty($data->ref_id) && empty($data->store_type)):
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';

        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
    endif;

    if($data->final_location == 0):
        $locationName = '<a href="' . base_url("storeLocation/list/" . $data->id) . '">' . $data->location . '</a>';
    else:
        $locationName = $data->location;
    endif;
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->store_name,$locationName,$data->remark];
}

/* Gate Entry Data  */
function getGateEntryData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Gate Entry'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-lg', 'form_id' : 'editGateEntry', 'title' : 'Update Gate Entry'}";

    $editButton = "";
    $deleteButton = "";
    if($data->trans_status == 0):
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';

        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
    endif;

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->transport_name,$data->lr,$data->vehicle_type_name,$data->vehicle_no,$data->inv_no,((!empty($data->inv_date))?formatDate($data->inv_date):""),$data->doc_no,((!empty($data->doc_date))?formatDate($data->doc_date):"")];
}

/* GateInward Data Data  */
function getGateInwardData($data){
    $action = '';$editButton='';$deleteButton="";$pallatePrint="";
    if($data->trans_type == 1): //Pending GE Data
        $createGI = "";
        $createGIParam = "{postData:{'id' : ".$data->id."}, 'modal_id' : 'modal-xl', 'form_id' : 'addGateInward', 'title' : 'Gate Inward',fnsave: 'save',fnedit: 'createGI'}";

        $createGI = '<a class="btn btn-success btn-edit permission-write" href="javascript:void(0)" datatip="Create GI" flow="down" onclick="edit('.$createGIParam.');"><i class="fa fa-plus" ></i></a>';

        $action = getActionButton($createGI);

        return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->inv_no,$data->inv_date,$data->doc_no,$data->doc_date];
    else: // Gate Inward Pending/Completed Data

        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Gate Inward'}";
        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-xl', 'form_id' : 'editGateInward', 'title' : 'Update Gate Inward'}";

        $editButton = "";
        $deleteButton = "";
        if($data->trans_status == 0):
            $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';

            $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
        endif;

        $insParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-xl', 'form_id' : 'materialInspection', 'title' : 'Material Inspection','fnedit':'materialInspection','fnsave':'saveInspectedMaterial'}";
        $inspection = '<a href="javscript:voide(0);" type="button" class="btn btn-warning permission-modify" datatip="Inspection" flow="down" onclick="edit('.$insParam.');"><i class="fas fa-search"></i></a>';

	    $iirPrint = '<a href="'.base_url('gateInward/ir_print/'.$data->id).'" type="button" class="btn btn-primary" datatip="IIR Print" flow="down" target="_blank"><i class="fas fa-print"></i></a>';

	    $action = getActionButton($iirPrint.$inspection.$editButton.$deleteButton);

        return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->item_name,$data->qty,$data->po_number];
    endif;
}

/* FG Stock Inward Table Data */
function getStockTransData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Stock'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $action = getActionButton($deleteButton);

    return [$action,$data->sr_no,formatDate($data->ref_date),$data->item_code,$data->item_name,$data->qty,$data->size,$data->remark];
}

function getMaterialIssueData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Recored'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $returnParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-xl', 'form_id' : 'returnMaterialForm','fnedit':'returnMaterial','controller':'materialIssue','fnsave':'saveReturnMaterial', 'title' : 'Return Material','js_store_fn':'confirmStore', 'confirm_msg':'Are you sure want to return this material ?'}";
    $returnButton = '<a class="btn btn-success btn-edit" href="javascript:void(0)" datatip="Return" flow="down" onclick="edit('.$returnParam.');"><i class="fa fa-reply"></i></a>';

    if($data->is_approve > 0):$deleteButton = ""; else: $returnButton = ""; endif;
    if(!empty($data->trans_status)): $returnButton = ""; endif;
    $action = getActionButton($returnButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->collected_by_name,$data->sales_executive_name,$data->total_issue,$data->total_return,$data->total_sold,($data->total_issue - ($data->total_return+$data->total_sold))];
}
?>