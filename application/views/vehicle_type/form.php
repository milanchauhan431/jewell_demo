<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?= (!empty($dataRow->id)) ? $dataRow->id : ""; ?>" />
            
            <div class="col-md-12 form-group">
                <label for="vehicle_type">Vehicle Type</label>
                <input type="text" name="vehicle_type" id="vehicle_type" class="form-control req" value="<?= (!empty($dataRow->vehicle_type)) ? $dataRow->vehicle_type : "" ?>">
            </div>

            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" value="<?= (!empty($dataRow->remark)) ? $dataRow->remark : "" ?>">
            </div>
            
        </div>
    </div>
</form>