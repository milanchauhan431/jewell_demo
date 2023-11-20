<form  enctype="multipart/form-data">
    <div class="col-md-12">

        <div class="hiddenInputs">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
			     <input type="hidden" name="entry_type" id="entry_type" value="<?=(!empty($dataRow->entry_type))?$dataRow->entry_type:''?>">
             </div>

        <div class="row">
            <div class="col-md-12 form-group">
                <label for="reversal_reason">Reason</label>
                <input type="text" name="reversal_reason" id="reversal_reason" class="form-control req" value="<?=(!empty($dataRow->reversal_reason))?$dataRow->reversal_reason:''?>">
            </div>

           
        </div>
    </div>
</form>
 