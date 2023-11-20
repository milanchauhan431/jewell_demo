<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id"  value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">

            <div class="col-md-12 form-group">
                <label for="quality">Quality</label>
                <input type="text" name="quality" id="quality" class="form-control" value="<?=(!empty($dataRow->quality))?$dataRow->quality:""?>">
            </div>

            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
            </div>
        </div>
    </div>
</form>