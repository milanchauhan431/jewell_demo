<form>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4 form-group">
                <label for="location_id">Store Location</label>
                <select name="location_id" id="location_id" class="form-control select2 req">
                    <option value="">Select Location</option>
                    <?=getLocationListOption($locationList)?>
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label for="barcode_no">Scan Barcode</label>
                <div class="input-group">
                    <input type="text" id="barcode_no" class="form-control numericOnly float-right" value="" placeholder="Scan barcode" data-stock_effect="1">
                    <div class="input-group-append">
                        <button type="button" id="check_barcode" class="btn btn-info"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="table table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-info">
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Barcode No.</th>
                                <th>Gross Weight</th>
                                <th>Stone Weight</th>
                                <th>Net Weight</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="salesReturnItems">
                            <?php
                                $i = 0;$totalGw = $totalNw = 0;
                                foreach($returnItemList as $row):
                                    $i++;

                                    $checked = "";
                                    $checkBoxInput = '<input type="checkbox" name="itemData['.$i.'][trans_status]" id="'.$row->unique_id.'" class="filled-in chk-col-success" data-rowid="'.$i.'" check="'.$checked.'" '.$checked.' value="1" />
                                    <label for="'.$row->unique_id.'"></label>';

                                    echo '<tr>
                                        <td style="width:10%;">
                                            '.$checkBoxInput.'
                                        </td>
                                        <td>
                                            '.$row->item_name.'
                                            <input type="hidden" name="itemData['.$i.'][id]" value="'.$row->id.'">
                                        </td>
                                        <td>'.$row->unique_id.'</td>
                                        <td>'.$row->gross_weight.'</td>
                                        <td>'.($row->gross_weight - $row->net_weight).'</td>
                                        <td>'.$row->net_weight.'</td>
                                        <td>Sales Return</td>
                                    </tr>';
                                    $totalGw += $row->gross_weight;
                                    $totalNw += $row->net_weight;
                                    
                                endforeach;
                            ?>
                        </tbody>
                        <tfoot class="thead-info">
                            <tr>
                                <th colspan="2">Total</th>
                                <th><?=$i?></th>
                                <th><?=$totalGw?></th>
                                <th><?=($totalGw - $totalNw)?></th>
                                <th><?=$totalNw?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
$(document).ready(function(){
    $(document).on('keypress','#barcode_no',function(e){
		if(e.which == 13) {
			findBarcode();
		}
	});

	$(document).on('click','#check_barcode',function(){
		findBarcode();
	});
});
function findBarcode(){
    $(".barcode_no").html("");
    var barcode = $("#barcode_no").val();
    console.log($("#" + barcode).length);
    if($("#" + barcode).length > 0){
        $("#salesReturnItems #"+barcode).prop('checked',true);
        $("#barcode_no").val("");
    }else{
        $(".barcode_no").html("Barcode not found.");
    }
}
</script>