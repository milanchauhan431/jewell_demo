<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
           <input type="hidden" name="party_category" id="party_category" value="<?=(!empty($dataRow->party_category))?$dataRow->party_category:$party_category?>" />

            <div class="col-md-12 form-group">
                <label for="party_name">Ladger Name</label>
                <input type="text" name="party_name" class="form-control text-capitalize req" value="<?=(!empty($dataRow->party_name))?$dataRow->party_name:""; ?>" />
            </div>

            <div class="col-md-8 form-group">
                <label for="group_id">Group Name</label>
                <select name="group_id" id="group_id" class="form-control select2 req">
                    <option value="">Select Group</option>
                    <?php
                        foreach($groupList as $row):
                            $selected = (!empty($dataRow->group_id) && $row->id == $dataRow->group_id)?"selected":"";
                            echo "<option value='".$row->id."' ".$selected.">".$row->name."</option>";
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label for="is_gst_applicable">Gst Applicable</label>
                <select name="is_gst_applicable" id="is_gst_applicable" class="form-control req" >
                    <option value="0" <?=(!empty($dataRow->is_gst_applicable) && $dataRow->is_gst_applicable == 0)?"selected":""?>>No</option>
                    <option value="1" <?=(!empty($dataRow->is_gst_applicable) && $dataRow->is_gst_applicable == 1)?"selected":""?>>Yes</option>
                </select>
            </div>

            <div class="col-md-4 form-group applicable <?=(empty($dataRow->is_gst_applicable))?'hidden':'';?>">
                <label for="hsn_code">Hsn Code</label>
                <select name="hsn_code" id="hsn_code" class="form-control select2">
                    <option value="">Select HSN Code</option>
                    <?=getHsnCodeListOption($hsnList)?>
                </select>
            </div>

            <div class="col-md-4 form-group applicable <?=(empty($dataRow->is_gst_applicable))?'hidden':'';?>" >
                <label for="gst_per">GST Per.</label>
                <select name="gst_per" id="gst_per" class="form-control select2">
                    <?php
                        foreach($this->gstPer as $per=>$text):
                            $selected = (!empty($dataRow->gst_per) && floatVal($dataRow->gst_per) == $per)?"selected":"";
                            echo '<option value="'.$per.'" '.$selected.'>'.$text.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-4 form-group applicable <?=(empty($dataRow->is_gst_applicable))?'hidden':'';?>" >
                <label for="cess_per">Cess Per.</label>
                <input type="text" name="cess_per" class="form-control numericOnly" value="<?=(!empty($dataRow->cess_per))?$dataRow->cess_per:""?>" />
            </div>           

        </div>
    </div>
</form>

<script type="text/javascript">
$(document).ready(function(){
    $(document).on('change','#is_gst_applicable',function(){
		var is_gst_applicable = $(this).val();
		if(is_gst_applicable == 1){
			$('.applicable').removeClass('hidden');
		} else {
            $('.applicable').addClass('hidden');
        }
	});

    $(document).on('change','#hsn_code',function(){
		$("#gst_per").val(($("#hsn_code :selected").data('gst_per') || 0));
		$("#gst_per").select2();
	});
});
</script>
