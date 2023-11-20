<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getHrDtHeader($page){
    /* Department Header */
    $data['departments'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['departments'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['departments'][] = ["name"=>"Department Name"];
    $data['departments'][] = ["name"=>"Category"];

    /* Designation Header */
    $data['designation'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['designation'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['designation'][] = ["name"=>"Designation Name"];
    $data['designation'][] = ["name"=>"Remark"];

    /* Employee Category Header */
    $data['employeeCategory'][] = ["name"=>"Action","style"=>"width:5%;"];
    $data['employeeCategory'][] = ["name"=>"#","style"=>"width:5%;"];
    $data['employeeCategory'][] = ["name"=>"Category Name"];
    $data['employeeCategory'][] = ["name"=>"Over Time"];

    /* Shift Header */
    $data['shift'][] = ["name"=>"Action","style"=>"width:5%;"];
	$data['shift'][] = ["name"=>"#","style"=>"width:5%;"];
	$data['shift'][] = ["name"=>"Shift Name"];
	$data['shift'][] = ["name"=>"Start Time"];
	$data['shift'][] = ["name"=>"End Time"];
	$data['shift'][] = ["name"=>"Production Time"];
	$data['shift'][] = ["name"=>"Lunch Time"];
	$data['shift'][] = ["name"=>"Shift Hour"];

    /* Employee Header */
    $data['employees'][] = ["name"=>"Action","style"=>"width:5%;"];
	$data['employees'][] = ["name"=>"#","style"=>"width:5%;","textAlign"=>'center']; 
    $data['employees'][] = ["name"=>"Employee Name"];
    $data['employees'][] = ["name"=>"Emp Code","textAlign"=>'center'];
    $data['employees'][] = ["name"=>"Department"];
    $data['employees'][] = ["name"=>"Designation"];
    $data['employees'][] = ["name"=>"Category","textAlign"=>'center'];
    $data['employees'][] = ["name"=>"Shift","textAlign"=>'center'];
    $data['employees'][] = ["name"=>"Contact No.","textAlign"=>'center'];

    return tableHeader($data[$page]);
}

/* Department Table Data */
function getDepartmentData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Department'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'editDepartment', 'title' : 'Update Department'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->name,$data->category];
}

/* Designation Table Data */
function getDesignationData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Designation'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'editDesignation', 'title' : 'Update Designation'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->title,$data->description];
}

/* Employee Category Table Data */
function getEmployeeCategoryData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Employee Category'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'editEmployeeCategory', 'title' : 'Update Employee Category'}";


    $editButton = '<a class="btn btn-success btn-edit" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->category,$data->overtime];
}

/* get Shift Data */
function getShiftData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Shift'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'editShift', 'title' : 'Update Shift'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

	$action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->shift_name,$data->shift_start,$data->shift_end,$data->production_hour,$data->total_lunch_time,$data->total_shift_time];
}

/* Employee Table Data */
function getEmployeeData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Employee'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-xl', 'form_id' : 'editEmployee', 'title' : 'Update Employee'}";
    
    $leaveButton = '';$addInDevice = '';$activeButton = '';$empRelieveBtn = '';$editButton = '';$deleteButton = '';
    
    //$emprelieveParam = "{'id' : ".$data->id.", 'modal_id' : 'modal-lg', 'form_id' : 'empEdu', 'title' : 'Employee Relieve', 'fnEdit' : 'empRelive', 'fnsave' : 'saveEmpRelieve' ,'button' : 'both'}";
    //$empRelieveBtn = '<a class="btn btn-dark btn-edit permission-remove" href="javascript:void(0)" datatip="Relieve" flow="down" onclick="edit('.$emprelieveParam.');"><i class="ti-close" ></i></a>';
    
    if($data->is_active == 1):
        $activeParam = "{'postData':{'id' : ".$data->id.", 'is_active' : 0},'fnsave':'activeInactive','message':'Are you sure want to De-Active this Employee?'}";
        $activeButton = '<a class="btn btn-youtube permission-modify" href="javascript:void(0)" datatip="De-Active" flow="down" onclick="confirmStore('.$activeParam.');"><i class="fa fa-ban"></i></a>';    

        //$leaveButton = '<a class="btn btn-warning btn-LeaveAuthority permission-modify" href="javascript:void(0)" datatip="Approval Hierarchy" data-id="'.$data->id.'" data-button="close" data-modal_id="modal-lg" data-function="getEmpLeaveAuthority" data-form_title="Update Approval Hierarchy" flow="down"><i class="fa fa-list"></i></a>';
        //$addInDevice = '<a class="btn btn-dark addInDevice permission-modify" href="javascript:void(0)" datatip="Device" data-id="'.$data->id.'" data-button="close" data-modal_id="modal-lg" data-function="addEmployeeInDevice" data-form_title="Add Employee In Device" flow="down"><i class="fa fa-desktop"></i></a>';

        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

        $empName = '<a href="'.base_url("hr/employees/empProfile/".$data->id).'" datatip="View Profile" flow="down">'.$data->emp_name.'</a>';
    else:
        $activeParam = "{'postData':{'id' : ".$data->id.", 'is_active' : 1},'fnsave':'activeInactive','message':'Are you sure want to Active this Employee?'}";
        $activeButton = '<a class="btn btn-success permission-remove" href="javascript:void(0)" datatip="Active" flow="down" onclick="confirmStore('.$activeParam.');"><i class="fa fa-check"></i></a>';  
          
        $empName = $data->emp_name;
    endif;
    
    $CI = & get_instance();
    $userRole = $CI->session->userdata('role');

    $resetPsw='';
    if(in_array($userRole,[-1,1])):
        $resetParam = "{'postData':{'id' : ".$data->id."},'fnsave':'resetPassword','message':'Are you sure want to Change ".$data->emp_name." Password?'}";
        $resetPsw='<a class="btn btn-danger" href="javascript:void(0)" onclick="confirmStore('.$resetParam.');" datatip="Reset Password" flow="down"><i class="fa fa-key"></i></a>';
    endif;
    
    $action = getActionButton($resetPsw.$leaveButton.$addInDevice.$activeButton.$empRelieveBtn.$editButton.$deleteButton);

    return [$action,$data->sr_no,$empName,$data->emp_code,$data->dept_name,$data->emp_designation,$data->emp_category,$data->shift_name,$data->emp_contact];
}

?>