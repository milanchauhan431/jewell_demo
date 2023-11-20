<form>
<input type="hidden" name="id" value="<?php echo $dataRow->id?>"/> 
	<div class="col-md-12">
		<div class="row">
			<div class="form-group col-md-12">
				<label for="gold_rate">Gold-24k</label>
				<input type="text" name="gold_rate" id="gold_rate" class="form-control numericOnly" value="<?php echo $dataRow->gold_rate?>"/> 
				
			</div>
			<div class="form-group col-md-12">
				<label for="silver_rate">Silver-1kg</label> 
				<input type="text" name="silver_rate" id="silver_rate" class="form-control numericOnly"  value="<?php echo $dataRow->silver_rate?>">
			</div>
			<div class="form-group col-md-12">
				<label for="platinum_rate">Platinum-24k</label>
				<input type="text" name="platinum_rate" id="platinum_rate" class="form-control numericOnly" value="<?php echo $dataRow->platinum_rate?>"/> 
			</div>
			<div class="form-group col-md-12 d-none">
				<label for="palladium_rate">Palladium</label>
				<input type="text" name="palladium_rate" id="palladium_rate" class="form-control numericOnly" value="<?php echo $dataRow->palladium_rate?>"/> 
			</div>
		</div>
	</div>	
</form>