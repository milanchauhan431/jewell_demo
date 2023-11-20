<form>
	<div class="col-md-12">
        <div class="row">
			<input type="hidden" name="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>" />
			
			<div class="col-md-12 form-group">
				<label for='name' class="control-label">Department Name</label>
				<input type="text" id="name" name="name" class="form-control req" value="<?=(!empty($dataRow->name))?$dataRow->name:""?>">
			</div>
            
			<div class="col-md-12 form-group">
				<label for="category">Category</label>
				<select name="category" id="category" class="form-control select2 req">
                    <?php
                        foreach($categoryData as  $key => $value):
							$selected = (!empty($dataRow->category) && $key == $dataRow->category)?"selected":"";
                            echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                        endforeach;
                    ?>
                </select>
			</div>
		</div>
	</div>	
</form>
            
