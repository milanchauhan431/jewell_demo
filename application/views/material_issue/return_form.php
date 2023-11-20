<form>
    <div class="col-md-12">
        <input type="hidden" name="id" id="id" value="<?=$id?>" />
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4 form-group">
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
                        <tbody id="materialReturn">
                            <?php
                                $i = 0;$totalGw = $totalNw = 0;
                                foreach($issueItemList as $row):
                                    $i++;

                                    $checked = "";
                                    $checkBoxInput = '<input type="checkbox" name="itemData['.$i.'][trans_status]" id="'.$row->item_desc.'" class="filled-in chk-col-success" data-rowid="'.$i.'" check="'.$checked.'" '.$checked.' value="1" />
                                    <label for="'.$row->item_desc.'"></label>';

                                    if($row->sold_status == "SOLD"):
                                        $checkBoxInput = "";
                                    endif;

                                    if($row->trans_status > 0):
                                        $checkBoxInput = '<input type="hidden" name="itemData['.$i.'][trans_status]" value="'.$row->trans_status.'">';
                                    endif;

                                    if($row->sold_status != "SOLD" && $row->trans_status == 1):
                                        $row->sold_status = "RETURN";
                                    endif;

                                    echo '<tr>
                                        <td style="width:10%;">
                                            '.$checkBoxInput.'
                                        </td>
                                        <td>
                                            '.$row->item_name.'
                                            <input type="hidden" name="itemData['.$i.'][id]" value="'.$row->id.'">
                                            <input type="hidden" name="itemData['.$i.'][item_remark]" value="'.$row->sold_status.'">
                                            <input type="hidden" name="itemData['.$i.'][stData][id]" value="'.$row->ref_id.'">
                                            <input type="hidden" name="itemData['.$i.'][stData][location_id]" value="'.$row->initiate_by.'">
                                        </td>
                                        <td>'.$row->item_desc.'</td>
                                        <td>'.$row->gross_weight.'</td>
                                        <td>'.($row->gross_weight - $row->net_weight).'</td>
                                        <td>'.$row->net_weight.'</td>
                                        <td>'.$row->sold_status.'</td>
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
                                <th><?=sprintf('%.3f',round($totalGw,3))?></th>
                                <th><?=sprintf('%.3f',round(($totalGw - $totalNw),3))?></th>
                                <th><?=sprintf('%.3f',round($totalNw,3))?></th>
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
        $("#materialReturn #"+barcode).prop('checked',true);
        $("#barcode_no").val("");
    }else{
        $(".barcode_no").html("Barcode not found.");
    }
}
</script>