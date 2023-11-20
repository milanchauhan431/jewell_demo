<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id"  value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <div class="error size"></div>

            <div class="col-md-4 form-group">
                <label for="shape">Shape</label>
                <select name="shape" id="shape" class="form-control select2">
                    <option value="">Select Shape</option>
                    <option value="SQUARE" <?=(!empty($dataRow->shape) && $dataRow->shape = "SQUARE")?"selected":""?> >SQUARE</option>
                    <option value="RECTANGLE" <?=(!empty($dataRow->shape) && $dataRow->shape = "RECTANGLE")?"selected":""?> >RECTANGLE</option>
                    <option value="CIRCLE" <?=(!empty($dataRow->shape) && $dataRow->shape = "CIRCLE")?"selected":""?> >CIRCLE</option>
                </select>
            </div>
            
            <div class="col-md-4 form-group">
                <label for="size">Size (Inch)</label>
                <input type="text" name="size" id="size" class="form-control req" value="<?=(!empty($dataRow->size))?$dataRow->size:""?>">
            </div>

            <div class="col-md-4 form-group">
                <label for="size_mm">Size (mm)</label>
                <input type="text" name="size_mm" id="size_mm" class="form-control req" value="<?=(!empty($dataRow->size_mm))?$dataRow->size_mm:""?>">
            </div>

            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
            </div>
        </div>
    </div>
</form>