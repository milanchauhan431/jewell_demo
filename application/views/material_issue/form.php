<form>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="vou_acc_id">Collected By</label>
                <select name="vou_acc_id" id="vou_acc_id" class="form-control select2 req">
                    <option value="">Select Employee</option>
                    <?php
                        foreach($employeeList as $row):
                            echo '<option value="'.$row->id.'">'.$row->emp_name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="sales_executive">Sales Executive</label>
                <select name="sales_executive" id="sales_executive" class="form-control select2 req">
                    <option value="">Select Employee</option>
                    <?php
                        foreach($employeeList as $row):
                            echo '<option value="'.$row->id.'">'.$row->emp_name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="feasibility_by">To Location</label>
                <select name="feasibility_by" id="feasibility_by" class="form-control select2 req">
                    <option value="">Select Location</option>
                    <?=getLocationListOption($locationList)?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="issue_scanner">Scan Barcode</label>
                <input type="text" id="issue_scanner" class="form-control numericOnly float-right" value="" placeholder="Scan barcode"  >
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="error item_error"></div>
                <div class="table table-responsive">
                    <table id="issueItems" class="table table-bordered">
                        <thead class="thead-info">
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Gross Weight</th>
                                <th>Net Weight</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tempItem" class="temp_item">
                            <tr id="noData">
                                <td colspan="5" class="text-center">No data available in table</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="<?php echo base_url(); ?>assets/js/custom/calculate.js?v=<?= time() ?>"></script>
<script>
$(document).ready(function(){
    $(document).on('keypress','#issue_scanner',function(e){
		if(e.which == 13) {
			issueScan($(this).val());
            $('#issue_scanner').val("");
		}
	});
});
async function issueScan(barcode=""){
    if(barcode){
        $.ajax({
            url : base_url + '/stockTrans/getItemDetails',
            type:'post',
            data: {unique_id:barcode,stock_required:1},
            dataType : 'json',
        }).done(function(response){
            $('#issue_scanner').val("");

            var formData = response.data.itemDetail;
            if(formData == null){
                toastr.error("Sorry! Item not found or Out of stock.", 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
                return false;
            }
            
            
            if(formData.stock_type == "FREEZE"){
                toastr.error("Sorry! This item is already booked.", 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
                return false;
            }
            
            
            var unique_ids = $(".unique_id").map(function () { return $(this).val(); }).get();
            if ($.inArray(formData.unique_id, unique_ids) >= 0) {
                $(".issue_scanner").html("Item already added.");
                return false;
            }

            formData.row_index = "";
            AddRow(formData);
            return false;
        });
    }
}

function AddRow(data) {
    var tblName = "issueItems";

    //Remove blank line.
	$('table#'+tblName+' tr#noData').remove();

    //Get the reference of the Table's TBODY element.
    var tBody = $("#" + tblName + " > TBODY")[0];

    //Add Row.
    if (data.row_index != "") {
        var trRow = data.row_index;
        //$("tr").eq(trRow).remove();
        $("#" + tblName + " tbody tr:eq(" + trRow + ")").remove();
    }
    var ind = (data.row_index == "") ? -1 : data.row_index;
    row = tBody.insertRow(ind);

    //Add index cell
    var countRow = (data.row_index == "") ? ($('#' + tblName + ' tbody tr:last').index() + 1) : (parseInt(data.row_index) + 1);

    var idInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][id]", value: "" });
    var uniqueIdInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][item_desc]", class : 'unique_id', value: data.unique_id});
    var stockTransIdInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][ref_id]", value: data.id});
    var cell = $(row.insertCell(-1));
    cell.html(data.unique_id);
    cell.append(uniqueIdInput);
    cell.append(idInput);
    cell.append(stockTransIdInput);
    cell.append('<div class="error unique_id_'+countRow+'"></div>');
    cell.attr("style", "width:15%;");

    var itemIdInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][item_id]", class:"item_id", value: data.item_id });
	var itemNameInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][item_name]", value: data.item_name });
	var fromLoactionInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][initiate_by]", value: data.location_id });
    cell = $(row.insertCell(-1));
    cell.html(data.item_name);
    cell.append(itemIdInput);
    cell.append(itemNameInput);
    cell.append(fromLoactionInput);

    var grossWeightInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][gross_weight]", value: data.gross_weight });
    cell = $(row.insertCell(-1));
	cell.html(data.gross_weight);
    cell.append(grossWeightInput);

    var netWeightInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][net_weight]", value: data.net_weight });
    cell = $(row.insertCell(-1));
	cell.html(data.net_weight);
    cell.append(netWeightInput);

    //Add Button cell.
	cell = $(row.insertCell(-1));
	var btnRemove = $('<button><i class="ti-trash"></i></button>');
	btnRemove.attr("type", "button");
	btnRemove.attr("onclick", "Remove(this);");
	btnRemove.attr("style", "margin-left:4px;");
	btnRemove.attr("class", "btn btn-sm btn-outline-danger waves-effect waves-light");

    cell.append(btnRemove);
	cell.attr("class", "text-center");
	cell.attr("style", "width:10%;");
}

function Remove(button) {
    var tableId = "issueItems";
	//Determine the reference of the Row using the Button.
	var row = $(button).closest("TR");
	var table = $("#"+tableId)[0];
	table.deleteRow(row[0].rowIndex);
	var countTR = $('#'+tableId+' tbody tr:last').index() + 1;
	if (countTR == 0) {
		$("#tempItem").html('<tr id="noData"><td colspan="5" align="center">No data available in table</td></tr>');
	}
}
</script>