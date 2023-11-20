<form enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="row">

            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" name="entry_type" id="entry_type" value="<?=(!empty($dataRow->entry_type))?$dataRow->entry_type:$entry_type?>">
            <input type="hidden" name="trans_main_id" id="trans_main_id" value="<?=(!empty($dataRow->trans_main_id))?$dataRow->trans_main_id:""?>">
            <input type="hidden" name="trans_child_id" id="trans_child_id" value="<?=(!empty($dataRow->trans_child_id))?$dataRow->trans_child_id:""?>">
            <input type="hidden" name="ga_file_name" id="ga_file_name" value="<?=(!empty($dataRow->ga_file))?$dataRow->ga_file:""?>">

            <div class="col-md-4 form-group">
                <label for="entry_date">Date</label>
                <input type="date" name="entry_date" id="entry_date" class="form-control" value="<?=(!empty($dataRow->entry_date))?$dataRow->entry_date:getFyDate()?>">
            </div>

            <div class="col-md-4 form-group">
                <label for="priority">Priority</label>
                <select name="priority" id="priority" class="form-control single-select">
                    <option value="0">Select</option>
                    <option value="1" <?=(!empty($dataRow->priority) && $dataRow->priority == 1)?"selected":""?> >HIGH</option>
                    <option value="2" <?=(!empty($dataRow->priority) && $dataRow->priority == 2)?"selected":""?> >MEDIUM</option>
                    <option value="3" <?=(!empty($dataRow->priority) && $dataRow->priority == 3)?"selected":""?> >LOW</option>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label for="department_ids">Department</label>
                <select id="department" data-input_id="department_ids" class="form-control jp_multiselect req" multiple="multiple">
                    <?php
                        foreach ($departmentList as $dept_id=>$dept):
                            if (!empty($dataRow->department_ids)):
                                if(in_array($dept_id, explode(',', $dataRow->department_ids))):
                                    $selected = "selected";
                                else:
                                    $selected = '';
                                endif;
                            else:
                                $selected = 'selected';
                            endif;
                            echo '<option value="' . $dept_id . '" ' . $selected . '>' . $dept . '</option>';
                        endforeach;
                    ?>
                </select>
                <input type="hidden" name="department_ids" id="department_ids" value="<?= (!empty($dataRow->department_ids)) ? $dataRow->department_ids : implode(",",array_keys($departmentList)) ?>" />
                <div class="error type"></div>
            </div>

            <div class="col-md-4 form-group">
                <label for="">GA File</label>
                <div class="input-group">

                    <a href="<?=(!empty($dataRow->ga_file))?base_url("assets/uploads/production/".$dataRow->ga_file):"#"?>" class="btn btn-outline-info <?=(empty($dataRow->ga_file))?"hidden":""?>" title="Download File" download><i class="fa fa-download"></i></a>

                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="ga_file" id="ga_file" accept=".jpg, .png, .jpeg, .pdf, .xlsx, .xls">
                        <label class="custom-file-label" for="ga_file">Choose file</label>
                    </div>

                </div>
                <div class="error ga_file"></div>
            </div>

            <div class="col-md-4 form-group">
                <label for="">Technical Specification File</label>
                <div class="input-group">

                    <a href="<?=(!empty($dataRow->technical_specification_file))?base_url("assets/uploads/production/".$dataRow->technical_specification_file):"#"?>" class="btn btn-outline-info <?=(empty($dataRow->technical_specification_file))?"hidden":""?>" title="Download File" download><i class="fa fa-download"></i></a>

                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="technical_specification_file" id="technical_specification_file" accept=".jpg, .png, .jpeg, .pdf, .xlsx, .xls">
                        <label class="custom-file-label" for="technical_specification_file">Choose file</label>
                    </div>

                </div>
                <div class="error technical_specification_file"></div>
            </div>

            <div class="col-md-4 form-group">
                <label for="">SLD File</label>
                <div class="input-group">

                    <a href="<?=(!empty($dataRow->sld_file))?base_url("assets/uploads/production/".$dataRow->sld_file):"#"?>" class="btn btn-outline-info <?=(empty($dataRow->sld_file))?"hidden":""?>" title="Download File" download><i class="fa fa-download"></i></a>

                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="sld_file" id="sld_file" accept=".jpg, .png, .jpeg, .pdf, .xlsx, .xls">
                        <label class="custom-file-label" for="sld_file">Choose file</label>
                    </div>

                </div>
                <div class="error sld_file"></div>
            </div>

            <div class="col-md-6 form-group">
                <label for="fab_dept_note">FAB. PRODUCTION NOTE</label>
                <input type="text" name="fab_dept_note" id="fab_dept_note" class="form-control" value="<?=(!empty($dataRow->fab_dept_note))?$dataRow->fab_dept_note:""?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="pc_dept_note">POWER COATING NOTE</label>
                <input type="text" name="pc_dept_note" id="pc_dept_note" class="form-control" value="<?=(!empty($dataRow->pc_dept_note))?$dataRow->pc_dept_note:""?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="ass_dept_note">ASSEMBALY NOTE</label>
                <input type="text" name="ass_dept_note" id="ass_dept_note" class="form-control" value="<?=(!empty($dataRow->ass_dept_note))?$dataRow->ass_dept_note:""?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="remark">General NOTE</label>
                <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
            </div>

            

        </div>
    </div>
</form>