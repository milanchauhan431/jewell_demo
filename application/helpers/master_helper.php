<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getMasterDtHeader($page){
    /* Customer Header */
    $data['customer'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['customer'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['customer'][] = ["name"=>"Company Name"];
	$data['customer'][] = ["name"=>"Contact Person"];
    $data['customer'][] = ["name"=>"Contact No."];
    $data['customer'][] = ["name"=>"Party Code"];
    $data['customer'][] = ["name"=>"Currency"];

    /* Supplier Header */
    $data['supplier'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['supplier'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['supplier'][] = ["name"=>"Company Name"];
	$data['supplier'][] = ["name"=>"Contact Person"];
    $data['supplier'][] = ["name"=>"Contact No."];
    $data['supplier'][] = ["name"=>"Party Code"];

    /* Vendor Header */
    $data['vendor'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['vendor'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['vendor'][] = ["name"=>"Company Name"];
    $data['vendor'][] = ["name"=>"Contact Person"];
    $data['vendor'][] = ["name"=>"Contact No."];
    $data['vendor'][] = ["name"=>"Address"];

    /* Ledger Header */
    $data['ledger'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['ledger'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['ledger'][] = ["name"=>"Ledger Name"];
    $data['ledger'][] = ["name"=>"Group Name"];
    $data['ledger'][] = ["name"=>"Op. Balance"];
    $data['ledger'][] = ["name"=>"Cl. Balance"];

    /* Item Category Header */
    $data['itemCategory'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['itemCategory'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['itemCategory'][] = ["name"=>"Category Name"];
    $data['itemCategory'][] = ["name"=>"Parent Category"];
    $data['itemCategory'][] = ["name"=>"Is Final ?"];
    //$data['itemCategory'][] = ["name"=>"Stock Type"];
    $data['itemCategory'][] = ["name"=>"Is Returnable ?"];
    $data['itemCategory'][] = ["name"=>"Remark"];

    /* Brand Master Header */
    $data['brandMaster'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['brandMaster'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['brandMaster'][] = ["name"=>"Brand Name"];
    $data['brandMaster'][] = ["name"=>"Remark"];

    /* Size Master Header */
    $data['sizeMaster'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['sizeMaster'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['sizeMaster'][] = ["name"=>"Shape"];
    $data['sizeMaster'][] = ["name"=>"Size (Inch)"];
    $data['sizeMaster'][] = ["name"=>"Size (mm)"];
    $data['sizeMaster'][] = ["name"=>"Remark"];

    /* Finish Goods Header */
    $data['finish_goods'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['finish_goods'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['finish_goods'][] = ["name"=>"Item Code"];
    $data['finish_goods'][] = ["name"=>"Item Name"];
    $data['finish_goods'][] = ["name"=>"Category Name"];
    $data['finish_goods'][] = ["name"=>"Unit"];
    $data['finish_goods'][] = ["name"=>"Price"];
    $data['finish_goods'][] = ["name"=>"HSN Code"];
    $data['finish_goods'][] = ["name"=>"GST (%)"];
    $data['finish_goods'][] = ["name"=>"Defual Disc. (%)"];

    /* Row Material Header */
    $data['raw_material'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['raw_material'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['raw_material'][] = ["name"=>"Item Code"];
    $data['raw_material'][] = ["name"=>"Item Name"];
    $data['raw_material'][] = ["name"=>"Category Name"];
    $data['raw_material'][] = ["name"=>"Unit"];
    $data['raw_material'][] = ["name"=>"Price"];
    $data['raw_material'][] = ["name"=>"HSN Code"];
    $data['raw_material'][] = ["name"=>"GST (%)"];
    $data['raw_material'][] = ["name"=>"Defual Disc. (%)"];

    /* Consumable Header */
    $data['consumable'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['consumable'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['consumable'][] = ["name"=>"Item Code"];
    $data['consumable'][] = ["name"=>"Item Name"];
    $data['consumable'][] = ["name"=>"Category Name"];
    $data['consumable'][] = ["name"=>"Unit"];
    $data['consumable'][] = ["name"=>"Price"];
    $data['consumable'][] = ["name"=>"HSN Code"];
    $data['consumable'][] = ["name"=>"GST (%)"];
    $data['consumable'][] = ["name"=>"Defual Disc. (%)"];

    /* Purity Header */
    $data['purity'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['purity'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['purity'][] = ["name"=>"Purity"];
    $data['purity'][] = ["name"=>"Gold 10grm"];
    $data['purity'][] = ["name"=>"Silver 10grm"];
    $data['purity'][] = ["name"=>"Platinum 10grm"];
    $data['purity'][] = ["name"=>"Palladium 10grm"];
    $data['purity'][] = ["name"=>"Remark"];

    /* Fine Header */
    $data['fine'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['fine'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['fine'][] = ["name"=>"Fine"];
    $data['fine'][] = ["name"=>"Remark"];

    /* Polish Header */
    $data['polish'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['polish'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['polish'][] = ["name"=>"Polish"];
    $data['polish'][] = ["name"=>"Remark"];

    /* Color Header */
    $data['color'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['color'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['color'][] = ["name"=>"Color"];
    $data['color'][] = ["name"=>"Remark"];

    /* Clarity Header */
    $data['clarity'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['clarity'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['clarity'][] = ["name"=>"Clarity"];
    $data['clarity'][] = ["name"=>"Remark"];

    /* Clarity Header */
    $data['diamondQuality'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['diamondQuality'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['diamondQuality'][] = ["name"=>"Quality"];
    $data['diamondQuality'][] = ["name"=>"Remark"];

    return tableHeader($data[$page]);
}

function getPartyData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : '".$data->party_category_name."'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : '".(($data->table_status!=4)?"modal-xl":"modal-md")."', 'form_id' : 'edit".$data->party_category_name."', 'title' : 'Update ".$data->party_category_name."'}";

    /* $approvalParam = "{'id' : ".$data->id.", 'modal_id' : 'modal-md', 'form_id' : 'partyApproval', 'title' : 'Party Approval', 'fnEdit' : 'partyApproval', 'fnsave' : 'savePartyApproval'}";
    $approvalButton = '<a class="btn btn-info btn-approval permission-approve" href="javascript:void(0)" datatip="Party Approval" flow="down" onclick="edit('.$approvalParam.');"><i class="fa fa-check" ></i></a>'; */

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $gstJsonBtn="";$contactBtn="";
    if($data->party_category == 1):
        $gstParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'modal-lg','button' : 'close', 'form_id' : 'gstDetail', 'title' : 'GST Detail', 'fnedit' : 'gstDetail', 'fnsave' : 'saveGstDetail','js_store_fn' : 'customStore'}";
        $gstJsonBtn = '<a class="btn btn-warning btn-contact permission-modify" href="javascript:void(0)" datatip="GST Detail" flow="down" onclick="edit('.$gstParam.');"><i class="fab fa-google"></i></a>';

        /* $contactParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'modal-lg','button' : 'close', 'form_id' : 'contactDetail', 'title' : 'Contact Detail', 'fnedit' : 'contactDetail', 'fnsave' : 'saveContactDetail','js_store_fn' : 'customStore'}";
        $contactBtn = '<a class="btn btn-info btn-contact permission-modify" href="javascript:void(0)" datatip="Contact Detail" flow="down" onclick="edit('.$contactParam.');"><i class="fa fa-address-book"></i></a>'; */
    endif;

    $action = getActionButton($contactBtn.$gstJsonBtn.$editButton.$deleteButton);

    if($data->table_status == 1):
        $responseData = [$action,$data->sr_no,$data->party_name,$data->contact_person,$data->party_mobile,$data->party_code,$data->currency];
    elseif($data->table_status == 2):
        $responseData = [$action,$data->sr_no,$data->party_name,$data->contact_person,$data->party_mobile,$data->party_code];
    elseif($data->table_status == 3):        
        $responseData = [$action,$data->sr_no,$data->party_name,$data->contact_person,$data->party_mobile,$data->party_address];
    else:
        if($data->system_code != ""):
            $gstJsonBtn = $editButton = $deleteButton = "";
        endif;

        if(in_array($data->group_code,["SC","SD"])):
            $gstJsonBtn = $editButton = $deleteButton = "";
        endif;

        $action = getActionButton($contactBtn.$gstJsonBtn.$editButton.$deleteButton);

        $responseData = [$action,$data->sr_no,$data->party_name,$data->group_name,$data->op_balance,$data->cl_balance];
    endif;

    return $responseData;
}

function getItemCategoryData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Item Category'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'editItemCategory', 'title' : 'Update Item Category'}";

    $editButton=''; $deleteButton='';
	if(!empty($data->ref_id)):
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
    endif;

    $cat_code ='';
	if($data->ref_id ==6 || $data->ref_id == 7):
        $cat_code = (!empty($data->tool_type))?'['.str_pad($data->tool_type,3,'0',STR_PAD_LEFT).'] ':'';
    endif;

    if($data->final_category == 0):
        $data->category_name = $cat_code.'<a href="' . base_url("itemCategory/list/" . $data->id) . '">' . $data->category_name . '</a>';
    else:
        $data->category_name = $cat_code.$data->category_name;
    endif;

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->category_name,$data->parent_category_name,$data->is_final_text,/* $data->stock_type_text, */$data->is_returnable_text,$data->remark];
}

function getBrandData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Brand'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'editBrand', 'title' : 'Update Brand'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->brand_name,$data->remark];
}

function getSizeData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Size'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'editSize', 'title' : 'Update Size'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->shape,$data->size,$data->size_mm,$data->remark];
}

function getProductData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : '".$data->item_type_text."'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-xl', 'form_id' : 'editItem', 'title' : 'Update ".$data->item_type_text."'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no." (".$data->id.")",$data->item_code,$data->item_name,$data->category_name,$data->unit_name,floatVal($data->price),$data->hsn_code,floatVal($data->gst_per),floatVal($data->defualt_disc)];
}

function getPurityData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Purity'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'edit', 'title' : 'Update Purity'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->purity,$data->gold_rate,$data->silver_rate,$data->platinum_rate,$data->palladium_rate,$data->remark];
}

function getFineData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Fine'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'edit', 'title' : 'Update Fine'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->fine,$data->remark];
}

function getPolishData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Polish'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'edit', 'title' : 'Update Polish'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->polish,$data->remark];
}

function getColorData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Color'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'edit', 'title' : 'Update Color'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->color,$data->remark];
}

function getClarityData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Clarity'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'edit', 'title' : 'Update Clarity'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->clarity,$data->remark];
}
function getDiamondQualityData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Diamond Quality'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'edit', 'title' : 'Update Diamond Quality'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->quality,$data->remark];
}