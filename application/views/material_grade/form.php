<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?= (!empty($dataRow->id)) ? $dataRow->id : ""; ?>" />

            <div class="col-md-6 form-group">
                <label for="material_grade">Material Grade</label>
                <input type="text" name="material_grade" id="material_grade" class="form-control req" value="<?= (!empty($dataRow->material_grade)) ? $dataRow->material_grade : "" ?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="standard">Standard</label>
                <select id="standardName" class="form-control select2 req" >
                    <option value="">Select Standard</option>
                    <?php $i=1; 
                        foreach($standard as $row):
                            if(!empty($row->standard)):
                                $selected = (!empty($dataRow->standard) && $dataRow->standard == $row->standard)?"selected":"";
                                echo '<option value="'.$row->standard.'" '.$selected.'>'.$row->standard.'</option>';
                            endif;
                    endforeach; 
                    ?>
                </select>
                <input type="hidden" name="standard" id="standard" value="<?=(!empty($dataRow->standard))?$dataRow->standard:""?>" />

            </div>

            <div class="col-md-6 form-group">
                <label for="scrap_group">Scrap Group</label>
                <select name="scrap_group" id="scrap_group" class="form-control select2 req">
                    <option value="">Select Scrap Group</option>
                    <?php
                        foreach ($scrapData as $row) :
                            $selected = (!empty($dataRow->scrap_group) && $dataRow->scrap_group == $row->id) ? "selected" : "";
                            echo '<option value="'. $row->id .'" '.$selected.'>'.$row->item_name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            
            <div class="col-md-6 form-group">
                <label for="color_code">Colour Code</label>
                <select id="colorCodeName" class="form-control select2">
                    <option value="">Select</option>
                    <?php   
                        foreach($colorList as $row):
                            $selected = (!empty($dataRow->color_code) && $dataRow->color_code == $row->color_code) ? "selected" : "";
                            echo '<option value="' . $row->color_code . '" ' . $selected . '>' . $row->color_code . '</option>';
                        endforeach;                                        
                    ?>
                </select>
                <input type="hidden" name="color_code" id="color_code" value="<?=(!empty($dataRow->color_code))?$dataRow->color_code:""?>" />
            </div>
        </div>
    </div>
</form>
<script>
$(document).ready(function(){
    $(document).on('keyup','#standardNamec',function(){
        $('#standard').val($(this).val());
    });

    $(document).on('keyup','#colorCodeNamec',function(){
        $('#color_code').val($(this).val());
    });
});
</script>