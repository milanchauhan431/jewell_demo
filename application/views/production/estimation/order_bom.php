<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" id="trans_main_id" value="<?=(!empty($dataRow->trans_main_id))?$dataRow->trans_main_id:""?>">
            <input type="hidden" id="trans_child_id" value="<?=(!empty($dataRow->trans_child_id))?$dataRow->trans_child_id:""?>">
            <div class="col-md-6 form-group">
                <div class="input-group">
                    <a href="<?=base_url("assets/uploads/defualt/so_bom.xlsx")?>" class="btn btn-outline-info" title="Download Example File" download><i class="fa fa-download"></i></a>
                    <div class="input-group-append">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="excelFile" accept=".xlsx, .xls">
                            <label class="custom-file-label" for="excelFile">Choose file</label>
                        </div>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" id="readButton" type="button">Read Excel</button>
                    </div>
                    <!-- <div class="input-group-append">
                        <button class="btn btn-outline-secondary" id="clearData" type="button">Reset</button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    
    <hr>

    <div class="col-md-12">
        <div class="error itemData"></div>
        <div class="table-responsive">
            <table id="salesOrderBomItems" class="table table-bordered">
                <thead class="thead-info">
                    <tr>
                        <th>#</th>
                        <th>Material Description</th>
                        <th>Make</th>
                        <th>Cat. No.</th>
                        <th>UOM</th>
                        <th>Total Qty.</th>
                        <th>OTHER MRP</th>
                        <th>OTHER AMOUNT</th>
                        <th>DISC (IN %)</th>
                        <th>FINAL OTHER AMOUNT</th>
                        <th>Action</th>
                    </tr>                    
                </thead>
                <tbody>
                    <tr id="noData">
                        <td colspan="11" class="text-center">No data available in table</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>

<script src="<?php echo base_url(); ?>assets/js/xlsx.full.min.js?v=<?=time()?>"></script>
<script>
var clickedTr = 0;

$(document).ready(function() {
    
    /* $(document).on('change','#excelFile',function() {
        var fileName = $('#excelFile').val().split('\\').pop() || "Choose file";
        $(".custom-file-label").html(fileName);
    }); */

    $('#readButton').click(function() {
        var fileInput = document.getElementById('excelFile');
        var file = fileInput.files[0];

        var reader = new FileReader();
        reader.onload = function(e) {
            var data = new Uint8Array(e.target.result);
            var workbook = XLSX.read(data, { type: 'array' });

            var sheetName = workbook.SheetNames[0]; // Assuming the first sheet
            var worksheet = workbook.Sheets[sheetName];

            var jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

            // Process the data or display it in the table

            //Remove blank line.
            $('table#salesOrderBomItems > TBODY').html("");

            $.each(jsonData,function(ind,row){ 
                if(ind > 0){
                    var item_id = "";
                    if(row[1]){
                        row[3] = row[3] || -1;
                        $.ajax({
                            url : base_url + '/items/getItemDetails',
                            type : 'post',
                            data : { item_code : row[3], item_types : "2,3"},
                            global:false ,
                            dataType:'json',
                        }).done(function(res){
                            item_id = "";
                            if(res != ""){
                                var itemDetail = res.data.itemDetail;
                                if(itemDetail != null){
                                    item_id = itemDetail.id;
                                }                            
                            }
                            row[3] = (row[3] != -1)?row[3]:"";
                            row.push(item_id);
                            AddRow(row);
                        });
                    } 
                } 
            });
        };

        reader.readAsArrayBuffer(file);      
    });

    $(document).on('click','.addNew',function(){ 
        clickedTr = $(this).data('row_id'); 
        var formData = $("#add-item-"+clickedTr).data('form_data');  
        
        setTimeout(function(){
            console.log(formData[3]);
            $("#addItem input[name='item_code']").val(formData[3]);
            $("#addItem input[name='item_name']").val(formData[1]);
            $("#addItem input[name='make_brand']").val(formData[2]);
        },1000);
    });
});

function AddRow(data){
    var tblName = "salesOrderBomItems";

    //Remove blank line.
	$('table#'+tblName+' tr#noData').remove();

    //Get the reference of the Table's TBODY element.
	var tBody = $("#" + tblName + " > TBODY")[0];

    

    var ind = -1 ;
	row = tBody.insertRow(ind);
    $(row).attr('style',((data[10] == "")?"background:#f7b4b4;":"background:#8ce1d3;"));
    $(row).attr('class',((data[10] == "")?"none":"success"));
    

    var disable = ((data[10] == "")?true:false);
    
    //Add index cell
	var countRow = ($('#' + tblName + ' tbody tr:last').index() + 1);
	var cell = $(row.insertCell(-1));
	cell.html(countRow);
	cell.attr("style", "width:5%;");

    $(row).attr('id',countRow);

    var transMainIdInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][trans_main_id]",  value: $("#trans_main_id").val(), disabled:disable });
    var transChildInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][trans_child_id]",  value: $("#trans_child_id").val(), disabled:disable });
    var materialInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][material_description]",  value: data[1], disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data[1]);
    cell.append(materialInput);
    cell.append(transMainIdInput);
    cell.append(transChildInput);

    var makeInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][make]",  value: data[2], disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data[2]);
    cell.append(makeInput);

    var itemCodeInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][item_code]",  value: data[3], disabled:disable });
    var itemIdInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][item_id]",id:'item_id_'+countRow,  value: data[10], disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data[3]);
    cell.append(itemCodeInput);
    cell.append(itemIdInput);

    var unitInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][uom]",  value: data[4], disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data[4]);
    cell.append(unitInput);

    var qtyInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][qty]",  value: data[5], disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data[5]);
    cell.append(qtyInput);

    var priceInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][price]",  value: data[6], disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data[6]);
    cell.append(priceInput);

    var amountInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][amount]",  value: data[7], disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data[7]);
    cell.append(amountInput);

    var discPerInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][disc_per]",  value: data[8], disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data[8]);
    cell.append(discPerInput);

    var netAmtInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][net_amount]",  value: data[9], disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data[9]);
    cell.append(netAmtInput);

    var addNewItem = $('<button><i class="fa fa-plus"></i></button>');
	addNewItem.attr("type", "button");
	addNewItem.attr("id", "add-item-"+countRow);
	//addNewItem.attr("onclick", "Edit(" + JSON.stringify(data) + ",this);");
	addNewItem.attr("class", "btn waves-effect waves-light btn-outline-primary float-right addNew permission-write press-add-btn");
	addNewItem.attr("data-button", "both");
	addNewItem.attr("data-row_id", countRow);
	addNewItem.attr("data-modal_id", "modal-xl");
	addNewItem.attr("data-controller", "items");
	addNewItem.attr("data-function", "addItem");
	addNewItem.attr("data-res_function", "resBomItem");
	addNewItem.attr("data-js_store_fn", "customStore");
	addNewItem.attr("data-form_title", "Add Raw Material");
	addNewItem.attr("data-postdata", '{"item_type" : 3 }');
	addNewItem.attr("data-form_data", JSON.stringify(data));

    cell = $(row.insertCell(-1));
    cell.append(((data[10] == "")?addNewItem:""));
    cell.attr('id','cell-'+countRow)
}

function resBomItem(data,formId){
    if(data.status==1){
        $('#'+formId)[0].reset();$("#modal-xl").modal('hide');
        $('#salesOrderBomItems tbody #'+clickedTr).attr('style',"background:#8ce1d3;");
        $('#salesOrderBomItems tbody #'+clickedTr).removeClass('none');
        $('#salesOrderBomItems tbody #'+clickedTr).addClass('success');
        $('#salesOrderBomItems tbody #'+clickedTr+' input').prop('disabled',false);
        $("#cell-"+clickedTr).html("");  
        $("#salesOrderBomItems tbody #item_id_"+clickedTr).val(data.id);
        clickedTr = 0;

        $(".modal").css({'overflow':'auto'});

        toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
            toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }
}

function resSaveOrderBom(data,formId){
    if(data.status==1){
        $("#salesOrderBomItems tr.success").remove();
        
        toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
            toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }
}
</script>