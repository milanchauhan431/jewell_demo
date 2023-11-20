<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id"  value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">

            <div class="col-md-12 form-group">
                <label for="purity">Purity</label>
                <input type="text" name="purity" id="purity" class="form-control" value="<?=(!empty($dataRow->purity))?$dataRow->purity:""?>">
            </div>
			
            <div class="col-md-12 form-group">
                <label for="purity">Gold 10grm</label>
                <input type="text" name="gold_rate" id="gold_rate" class="form-control" value="<?=(!empty($dataRow->gold_rate))?$dataRow->gold_rate:""?>">
            </div>
            <div class="col-md-12 form-group">
                <label for="purity">Platinum 10grm</label>
                <input type="text" name="platinum_rate" id="platinum_rate" class="form-control" value="<?=(!empty($dataRow->platinum_rate))?$dataRow->platinum_rate:""?>">
            </div>
            <div class="col-md-12 form-group">
                <label for="purity">Silver 10grm</label>
                <input type="text" name="silver_rate" id="silver_rate" class="form-control" value="<?=(!empty($dataRow->silver_rate))?$dataRow->silver_rate:""?>">
            </div>
            <div class="col-md-12 form-group">
                <label for="purity">Palladium 10grm</label>
                <input type="text" name="palladium_rate" id="palladium_rate" class="form-control" value="<?=(!empty($dataRow->palladium_rate))?$dataRow->palladium_rate:""?>">
            </div>
			

            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
            </div>
        </div>
    </div>
</form>