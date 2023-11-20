<form autocomplete="off">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
            <input type="hidden" name="allowed_visitors" value="0" />

            <div class="col-md-4 form-group">
                <label for="emp_name">Employee Name</label>
                <input type="text" name="emp_name" class="form-control text-capitalize req" value="<?=(!empty($dataRow->emp_name))?$dataRow->emp_name:""; ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="father_name">Father Name</label>
                <input type="text" name="father_name" class="form-control" value="<?=(!empty($dataRow->father_name))?$dataRow->father_name:""?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="emp_email">Email ID</label>
                <input type="text" name="emp_email" class="form-control" value="<?=(!empty($dataRow->emp_email))?$dataRow->emp_email:""?>" />
            </div>
            
            <div class="col-md-3 form-group">
                <label for="husband_name">Emp. Type</label>
                <select name="emp_type" id="emp_type" class="form-control req">
                    <option value="">Select Type</option>
                    <?php
                        foreach($this->empType as $id=>$type):
                            $selected = (!empty($dataRow->emp_type) && $dataRow->emp_type == $id)?"selected":"";
                            echo '<option value="'.$id.'" '.$selected.'>'.$type.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="emp_code">Emp. Code</label>
                <input type="text" name="emp_code" class="form-control numericOnly req" value="<?=(!empty($dataRow->biomatric_id))?sprintf("AE%03d",$dataRow->biomatric_id):sprintf("AE%03d",$emp_no)?>" readonly/>
                <input type="hidden" name="biomatric_id" id="biomatric_id" value="<?=(!empty($dataRow->biomatric_id))?$dataRow->biomatric_id:$emp_no?>">
            </div>
            <div class="col-md-3 form-group">
                <label for="emp_contact">Phone No.</label>
                <input type="text" name="emp_contact" class="form-control numericOnly req" value="<?=(!empty($dataRow->emp_contact))?$dataRow->emp_contact:""?>" />
            </div>
            <div class="col-md-3 form-group">
                <label for="emp_alt_contact">Emergency Contact</label>
                <input type="text" name="emp_alt_contact" class="form-control numericOnly req" value="<?=(!empty($dataRow->emp_alt_contact))?$dataRow->emp_alt_contact:""?>" />
            </div>

            <div class="col-md-3">
                <label for="emp_birthdate">Date of Birth</label>
                <input type="date" name="emp_birthdate" id="emp_birthdate" class="form-control" value="<?=(!empty($dataRow->emp_birthdate))?$dataRow->emp_birthdate:date("Y-m-d")?>" max="<?=(!empty($dataRow->emp_birthdate))?$dataRow->emp_birthdate:date("Y-m-d")?>" />
            </div>
            <div class="col-md-3">
                <label for="emp_joining_date">Date of Joining</label>
                <input type="date" name="emp_joining_date" id="emp_joining_date" class="form-control" value="<?=(!empty($dataRow->emp_joining_date))?$dataRow->emp_joining_date:date("Y-m-d")?>" max="<?=(!empty($dataRow->emp_joining_date))?$dataRow->emp_joining_date:date("Y-m-d")?>" />
            </div>
            <div class="col-md-3 form-group">
                <label for="attendance_type">Attendance</label>
                <select name="attendance_type" id="attendance_type" class="form-control req ">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="emp_category">Emp Category</label>
                <select name="emp_category" id="emp_category" class="form-control select2">
                    <option value="0">Select Category</option>
                    <?php
                        foreach($empCategoryList as $row):
                            $selected = (!empty($dataRow->emp_category) && $row->category == $dataRow->emp_category)?"selected":"";
                            echo '<option value="'.$row->id.'" '.$selected.'> '.$row->category.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="emp_gender">Gender</label>
                <select name="emp_gender" id="emp_gender" class="form-control">
                    <option value="">Select Gender</option>
                    <?php
                        foreach($genderList as $value):
                            $selected = (!empty($dataRow->emp_gender) && $value == $dataRow->emp_gender)?"selected":"";
                            echo '<option value="'.$value.'" '.$selected.'>'.$value.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="marital_status">Marital Status</label>
                <select name="marital_status" id="marital_status" class="form-control">
                    <option value="">Select</option>
                    <?php
                        foreach($this->maritalStatus as $status):
                            $selected = ((!empty($dataRow->marital_status)) && $dataRow->marital_status == $status)?"selected":"";
                            echo '<option value="'.$status.'">'.$status.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="emp_experience">Exp. (In Months)</label>
                <input type="text" name="emp_experience" class="form-control numericOnly" value="<?=(!empty($dataRow->emp_experience))?$dataRow->emp_experience:""?>" />
            </div>
            
            
            <div class="col-md-3 form-group">
                <label for="shift_id">Shift</label>
                <select name="shift_id" id="shift_id" class="form-control select2 req">
                    <option value="">Select Shift</option>
                    <?php
                        foreach($shiftList as $row):
                            $selected = (!empty($dataRow->shift_id) && $row->id == $dataRow->shift_id)?"selected":"";
                            echo '<option value="'.$row->id.'" '.$selected.'>'.$row->shift_name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="emp_dept_id">Department</label>
                <select name="emp_dept_id" id="emp_dept_id" class="form-control select2 req">
                    <option value="">Select Department</option>
                    <?php
                        foreach($departmentList as $row):
                            $selected = (!empty($dataRow->emp_dept_id) && $row->id == $dataRow->emp_dept_id)?"selected":"";
                            echo '<option value="'.$row->id.'" '.$selected.'>'.$row->name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            <div class="col-md-3 from-group">
                <label for="emp_designation">Designation</label>
                <select name="emp_designation" id="emp_designation" class="form-control select2 req">
                    <option value="0">Select Designation</option>
                    <?php
                        foreach($designationList as $row):
                            $selected = (!empty($dataRow->emp_designation) && $row->id == $dataRow->emp_designation)?"selected":"";
                            echo '<option value="'.$row->id.'" '.$selected.'>'.$row->title.'</option>';
                        endforeach;
                    ?>
                </select>
                <input type="hidden" id="designationTitle" name="designationTitle" value="" />
            </div>
            <div class="col-md-3 form-group hidden">
                <label for="emp_sys_desc_id">System Designation</label>
                <select name="sysdescSelect" id="sysdescSelect" data-input_id="emp_sys_desc_id" class="form-control jp_multiselect" multiple="multiple">
                    <?php
                        foreach($systemDesignation as $key=>$value) : 
                        $selected= $emp_sys_desc_idArr = (!empty($dataRow->emp_sys_desc_id)) ? explode(',',$dataRow->emp_sys_desc_id) : array();
                        if(!empty($dataRow->emp_sys_desc_id) && in_array($key,$emp_sys_desc_idArr)){$selected = "selected";}else{ $selected=''; }
                        echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
                    endforeach;
                    ?>
                        <input type="hidden" id="emp_sys_desc_id" name="emp_sys_desc_id" value="<?=!empty($dataRow->emp_sys_desc_id)?$dataRow->emp_sys_desc_id:''?>" />
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="emp_role">Role</label>
                <select name="emp_role" id="emp_role" class="form-control select2 req">
                    <option value="">Select Role</option>
                    <?php
                        foreach($roleList as $key => $value):
                            $selected = (!empty($dataRow->emp_role) && $key == $dataRow->emp_role)?"selected":"";
                            echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            <!-- <div class="col-md-3 form-group">
                <label for="team_leader">Team Leader</label>
                <select name="team_leader" id="team_leader" class="form-control single-select req">
                    <option value="">Select Team Leader</option>
                    <?php
                        /* foreach($empList as $row):
                            $selected = (!empty($dataRow->team_leader) && $row->id == $dataRow->team_leader)?"selected":"";
                            echo '<option value="'.$row->id.'" '.$selected.'>'.$row->emp_name.'</option>';
                        endforeach; */
                    ?>
                </select>
            </div> -->
            <!-- Mark of Identification -->
            <!-- <div class="col-md-3 form-group">
                <label for="function">Function</label>
                <input type="text" class="form-control" id="function" name="function" value="<?=!empty($dataRow->function)?$dataRow->function:''?>">
            </div>
            <div class="col-md-3 form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="<?=!empty($dataRow->location)?$dataRow->location:''?>">
            </div>
            <div class="col-md-3 form-group">
                <label for="esi_no">ESI No</label>
                <input type="text" class="form-control" id="esi_no" name="esi_no" value="<?=!empty($dataRow->esi_no)?$dataRow->esi_no:''?>">
            </div>
            <div class="col-md-3 form-group">
                <label for="pran">PRAN</label>
                <input type="text" class="form-control" id="pran" name="pran" value="<?=!empty($dataRow->pran)?$dataRow->pran:''?>">
            </div> -->
            <div class="col-md-12 form-group">
                <label for="emp_address">Address</label>
                <textarea name="emp_address" class="form-control" style="resize:none;" rows="1"><?=(!empty($dataRow->emp_address))?$dataRow->emp_address:""?></textarea>
            </div>

            <div class="col-md-12 form-group">
                <label for="permenant_address">Permenant Address</label>
                <textarea name="permenant_address" class="form-control" style="resize:none;" rows="1"><?=(!empty($dataRow->permenant_address))?$dataRow->permenant_address:""?></textarea>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function(){
    $(document).on('change keyup','#emp_designation',function(){
        if($(this).val()){
            $('#designationTitle').val($('#emp_designation :selected').text());
        }else{
            $('#designationTitle').val("");
        }        
    });
});
</script>