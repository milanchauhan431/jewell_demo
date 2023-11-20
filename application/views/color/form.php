<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id"  value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">

            <div class="col-md-12 form-group">
                <label for="color">Color</label>
                <input type="text" name="color" id="color" class="form-control" value="<?=(!empty($dataRow->color))?$dataRow->color:""?>">
            </div>

            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
            </div>
        </div>
    </div>
</form>