<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getConfigDtHeader($page){
    /* terms header */
    $data['terms'][] = ["name"=>"Action","style"=>"width:5%;",'textAlign'=>'center',"sortable"=>"FALSE"];
	$data['terms'][] = ["name"=>"#","style"=>"width:5%;",'textAlign'=>'center',"sortable"=>"FALSE"]; 
    $data['terms'][] = ["name"=>"Title"];
    $data['terms'][] = ["name"=>"Type"];
    $data['terms'][] = ["name"=>"Conditions"];

    /* Transport Header*/
    $data['transport'][] = ["name"=>"Action","style"=>"width:5%;",'textAlign'=>'center',"sortable"=>"FALSE"];
    $data['transport'][] = ["name"=>"#","style"=>"width:5%;",'textAlign'=>'center',"sortable"=>"FALSE"];
    $data['transport'][] = ["name"=>"Transport Name"];
    $data['transport'][] = ["name"=>"Transport ID"];
    $data['transport'][] = ["name"=>"Address"];

    /* HSN Master header */
    $data['hsnMaster'][] = ["name"=>"Action","style"=>"width:5%;",'textAlign'=>'center',"sortable"=>"FALSE"];
    $data['hsnMaster'][] = ["name"=>"#","style"=>"width:5%;",'textAlign'=>'center',"sortable"=>"FALSE"]; 
    $data['hsnMaster'][] = ["name"=>"HSN"];
    $data['hsnMaster'][] = ["name"=>"CGST"];
    $data['hsnMaster'][] = ["name"=>"SGST"];
    $data['hsnMaster'][] = ["name"=>"IGST"];
    $data['hsnMaster'][] = ["name"=>"Description"];

    /* Material Grade header */
    $data['materialGrade'][] = ["name"=>"Action","style"=>"width:5%;",'textAlign'=>'center',"sortable"=>"FALSE"];
    $data['materialGrade'][] = ["name"=>"#","style"=>"width:5%;",'textAlign'=>'center',"sortable"=>"FALSE"]; 
    $data['materialGrade'][] = ["name"=>"Material Grade"];
    $data['materialGrade'][] = ["name"=>"Standard"];
    $data['materialGrade'][] = ["name"=>"Scrap Group"];
    $data['materialGrade'][] = ["name"=>"Colour Code"];

    /* Scrap Group Header*/
    $data['scrapGroup'][] = ["name"=>"Action","style"=>"width:5%;",'textAlign'=>'center',"sortable"=>"FALSE"];
    $data['scrapGroup'][] = ["name"=>"#","style"=>"width:5%;",'textAlign'=>'center',"sortable"=>"FALSE"];
    $data['scrapGroup'][] = ["name"=>"Scrap Group Name"];
    $data['scrapGroup'][] = ["name"=>"Unit Name"];

    /* Vehicle Type header */
    $data['vehicleType'][] = ["name"=>"Action","style"=>"width:5%;",'textAlign'=>'center',"sortable"=>"FALSE"];
    $data['vehicleType'][] = ["name"=>"#","style"=>"width:5%;",'textAlign'=>'center',"sortable"=>"FALSE"]; 
    $data['vehicleType'][] = ["name"=>"Vehicle Type"];
    $data['vehicleType'][] = ["name"=>"Remark"];

    /* Tax Master Header */
    $data['taxMaster'][] = ["name" => "Action", "style" => "width:5%;","sortable"=>"FALSE"];
    $data['taxMaster'][] = ["name" => "#", "style" => "width:5%;","sortable"=>"FALSE"];
    $data['taxMaster'][] = ["name" => "Tax Name"];
    $data['taxMaster'][] = ["name" => "Tax Type"];
    $data['taxMaster'][] = ["name" => "Calcu. Type"];
    $data['taxMaster'][] = ["name" => "Ledger Name"];
    $data['taxMaster'][] = ["name" => "Is Active"];
    $data['taxMaster'][] = ["name" => "Add/Deduct"];

    /* Expense Master Header */
    $data['expenseMaster'][] = ["name" => "Action", "style" => "width:5%;","sortable"=>"FALSE"];
    $data['expenseMaster'][] = ["name" => "#", "style" => "width:5%;","sortable"=>"FALSE"];
    $data['expenseMaster'][] = ["name" => "Exp. Name"];
    $data['expenseMaster'][] = ["name" => "Entry Name"];
    $data['expenseMaster'][] = ["name" => "Sequence"];
    $data['expenseMaster'][] = ["name" => "Calcu. Type"];
    $data['expenseMaster'][] = ["name" => "Ledger Name"];
    $data['expenseMaster'][] = ["name" => "Is Active"];
    $data['expenseMaster'][] = ["name" => "Add/Deduct"];

    return tableHeader($data[$page]);
}

/* Terms Table Data */
function getTermsData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Terms'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'editTerms', 'title' : 'Update Terms'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';

    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->title,str_replace(',',', ',$data->type),$data->conditions];
}

/* Transport Data */
function getTransportData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Transport'}";
    $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'modal-md', 'form_id' : 'editTransport', 'title' : 'Update Transport'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';

    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->transport_name,$data->transport_id,$data->address];
}

/* HSN Master Table Data */
function getHSNMasterData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'HSN Master'}";
    $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'modal-md', 'form_id' : 'editHsnMaster', 'title' : 'Update HSN Master'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';

    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->hsn,$data->cgst,$data->sgst,$data->igst,$data->description];
}

/* Material Grade Table Data */
function getMaterialData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Material Grade'}";
    $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'modal-md', 'form_id' : 'editMaterialGrade', 'title' : 'Update Material Grade'}";

    $editButton = '<a class="btn btn-success btn-edit" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->material_grade,$data->standard,$data->group_name,$data->color_code];
}

/* Scrap Group Data */
function getScrapGroupData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Scrap Group'}";
    $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'modal-md', 'form_id' : 'editScrap', 'title' : 'Update Scrap Group'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->item_name,$data->unit_name];
}

/* Vehicle Type Data */
function getVehicleTypeData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Vehicle Type'}";
    $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'modal-md', 'form_id' : 'editVehicleType', 'title' : 'Update Vehicle Type'}";

    $editButton = '<a class="btn btn-success btn-edit" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->vehicle_type,$data->remark];
}

/* Tax Master Table Data */
function getTaxMasterData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Tax'}";
    $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'modal-lg', 'form_id' : 'editTax', 'title' : 'Update Tax'}";
    
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
    $deleteButton = "";

    $action = getActionButton($editButton.$deleteButton);    

    return [$action,$data->sr_no,$data->name,$data->tax_type_name,$data->calc_type_name,$data->acc_name,$data->is_active_name,$data->add_or_deduct_name];
}

/* Expense Master Table Data */
function getExpenseMasterData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Expense'}";
    $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'modal-lg', 'form_id' : 'editExpense', 'title' : 'Update Expense'}";
    

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $action = getActionButton($editButton.$deleteButton);    

    return [$action,$data->sr_no,$data->exp_name,$data->entry_name,$data->seq,$data->calc_type_name,$data->party_name,$data->is_active_name,$data->add_or_deduct_name];
}

?>