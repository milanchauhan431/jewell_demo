<form  enctype="multipart/form-data">
    <div class="col-md-12">

        <div class="hiddenInputs">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
        </div>

        <div class="row">
            <div class="col-md-12 form-group">
                <label for="close_reason">Reason</label>
                <input type="text" name="close_reason" id="close_reason" class="form-control req" value="<?=(!empty($dataRow->close_reason))?$dataRow->close_reason:''?>">
            </div>
        </div>
        
    </div>
</form>
 