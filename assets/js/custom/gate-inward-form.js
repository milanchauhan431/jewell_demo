var itemCount = 0;
$(document).ready(function(){
    $("#party_id").trigger('change');
    $(document).on('change',"#party_id",function(){
        var party_id = $(this).val();
        getPoList(party_id);
    });

    $(document).on('change',"#po_id",function(){
        var po_id = $(this).val();
        getItemList(po_id);
    });

    /* $(document).on('change',"#item_id",function(){
        var item_id = $(this).val();
        getPoList(item_id);
    }); */
    
    /* $(document).on('change',"#po_trans_id",function(){
        var po_trans_id = $(this).val();
        if(po_trans_id){
            var po_id = $(this).find(":selected").data('po_id');
            $("#po_id").val(po_id);
            $("#price").val(($(this).find(":selected").data('price') || 0));
            $("#disc_per").val(($(this).find(":selected").data('disc_per') || 0));
        }else{
            $("#po_id").val("");
            $("#price").val("");
            $("#disc_per").val("");
        }
    }); */

    $(document).on('click','.addBatch',function(){
        var formData = {};

        formData.mir_id = "";
        formData.id = "";

        /* formData.location_id = $("#location_id").val();
        formData.location_name = $("#location_id :selected").text(); */
        formData.po_number = $("#po_id :selected").data('po_no');
        formData.item_name = $("#item_id :selected").text();
        formData.heat_no = $("#heat_no").val();
        formData.mill_heat_no = $("#mill_heat_no").val();
        formData.qty = $("#qty").val();
        formData.disc_per = $("#disc_per").val();
        formData.price = $("#price").val();
        formData.po_trans_id = $("#po_trans_id").val();
        formData.po_id = $("#po_id").val();
        formData.item_stock_type = $("#item_stock_type").val();
        formData.item_id = $("#item_id").val();
        formData.item_type = $("#item_type").val();
        formData.trans_status = 0;        

        $(".error").html("");

        /* if(formData.po_trans_id == ""){ 
            $('.po_trans_id').html("PO is required.");
        } */
        if(formData.item_id == ""){ 
            $('.item_id').html("Item Name is required.");
        }
        if(formData.qty == "" || parseFloat(formData.qty) == 0){ 
            $('.qty').html("Qty is required.");
        }
        /* if(formData.location_id == ""){ 
            $('.location_id').html("Location is required.");
        } */
        /* if(formData.item_stock_type == 1 && formData.heat_no == ""){ 
            $('.heat_no').html("Heat No. is required.");
        } */
        
        var errorCount = $('.error:not(:empty)').length;

		if(errorCount == 0){
           
            AddBatchRow(formData);

            //$("#location_id").val("");$("#location_id").select2();
            $("#heat_no").val("");
            $("#mill_heat_no").val("");
            $("#qty").val("");
            $("#disc_per").val("");
            $("#item_stock_type").val("");
            $("#item_id").val("");$("#item_id").select2();
            $("#item_type").val("");
            $("#po_trans_id").val("");
            $("#po_id").val("");$("#po_id").select2();            
            $("#price").val("");
            $(".error").html("");
        }
    });
});

function resItemDetail(response = ""){
    if(response != ""){
        var itemDetail = response.data.itemDetail;
        $("#item_type").val((itemDetail.item_type || 0)); 
        $("#item_stock_type").val((itemDetail.stock_type || 0));
        if($("#po_id").find(":selected").val() == ""){
            $("#disc_per").val((itemDetail.defualt_disc || 0));
            $("#price").val((itemDetail.price || 0));
            $("#po_trans_id").val("");
        }else{
            $("#disc_per").val(($("#item_id").find(":selected").data('disc_per') || 0));
            $("#price").val(($("#item_id").find(":selected").data('price') || 0));
            $("#po_trans_id").val(($("#item_id").find(":selected").data('po_trans_id') || 0));
        }        
    }else{
        $("#item_type").val(""); 
        $("#item_stock_type").val("");
        $("#disc_per").val("");
        $("#price").val("");
        $("#po_trans_id").val("");
    }
}

function getPoList(party_id){
    if(party_id){
        $.ajax({
            url : base_url + controller + '/getPoNumberList',
            type : 'post',
            data : {party_id : party_id},
            dataType : 'json'
        }).done(function(response){
            $("#po_id").html(response.poOptions);
            $("#po_id").select2();
        });
    }else{
        $("#po_id").html('<option value="">Select Purchase Order</option>');
        $("#po_id").select2();
    }
}

function getItemList(po_id){
    $.ajax({
        url : base_url + controller + '/getItemList',
        type : 'post',
        data : {po_id : po_id},
        dataType : 'json'
    }).done(function(response){
        $("#item_id").html(response.itemOptions);
        $("#item_id").select2();
    });
}

/* function getPoList(item_id,po_trans_id = ""){
    if(item_id){
        $.ajax({
            url : base_url + controller + '/getPoNumberListOnItemId',
            type : 'post',
            data : {item_id : item_id, po_trans_id : po_trans_id},
            dataType : 'json'
        }).done(function(response){
            $("#po_trans_id").html(response.poOptions);
            $("#po_trans_id").comboSelect();
        });
    }else{
        $("#po_trans_id").html('<option value="">Select Purchase Order</option>');
        $("#po_trans_id").comboSelect();
    }
} */


function AddBatchRow(data){
    $('table#batchTable tr#noData').remove();
    //Get the reference of the Table's TBODY element.
	var tblName = "batchTable";
	
	var tBody = $("#"+tblName+" > TBODY")[0];
	
	//Add Row.
	row = tBody.insertRow(-1);
    //Add index cell
	var countRow = $('#'+tblName+' tbody tr:last').index() + 1;
	var cell = $(row.insertCell(-1));
	cell.html(countRow);
	cell.attr("style","width:5%;");	

    var poIdInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][po_id]",value:data.po_id});
    var poTransIdInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][po_trans_id]",value:data.po_trans_id});
    var itemIdInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][item_id]",value:data.item_id});
    var itemTypeInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][item_type]",value:data.item_type});
    var itemStockInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][item_stock_type]",value:data.item_stock_type});
    var cell = $(row.insertCell(-1));
	cell.html(data.po_number);
	cell.attr("style","width:5%;");	
    cell.append(poIdInput);
	cell.append(poTransIdInput);
	cell.append(itemIdInput);
	cell.append(itemTypeInput);
	cell.append(itemStockInput);

    var cell = $(row.insertCell(-1));
	cell.html(data.item_name);
	cell.attr("style","width:5%;");	

    var mirIdInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][mir_id]",value:data.mir_id});
    var mirTransIdInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][id]",value:data.id});
    /* var locationIdInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][location_id]",value:data.location_id});
    cell = $(row.insertCell(-1));
	cell.html(data.location_name); */
    cell.append(mirIdInput);
    cell.append(mirTransIdInput);
	//cell.append(locationIdInput);

    /* var batchNoInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][batch_no]",value:data.batch_no});
    cell = $(row.insertCell(-1));
	cell.html(data.batch_no);
    cell.append(batchNoInput); */

    var heatNoInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][heat_no]",value:data.heat_no});
    /* cell = $(row.insertCell(-1));
	cell.html(data.heat_no); */
    cell.append(heatNoInput);
    
    var millHeatNoInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][mill_heat_no]",value:data.mill_heat_no});
    /* cell = $(row.insertCell(-1));
	cell.html(data.mill_heat_no); */
    cell.append(millHeatNoInput);

    var batchQtyInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][qty]",value:data.qty});   
    var discPerInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][disc_per]",value:data.disc_per});   
    var priceInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][price]",value:data.price});   
    cell = $(row.insertCell(-1));
	cell.html(data.qty);
    cell.append(batchQtyInput);
    cell.append(discPerInput);
    cell.append(priceInput);

    //Add Button cell.	
	var btnRemove = $('<button><i class="ti-trash"></i></button>');
	btnRemove.attr("type", "button");
	btnRemove.attr("onclick", "batchRemove(this);");
    btnRemove.attr("style","margin-left:4px;");
	btnRemove.attr("class", "btn btn-outline-danger waves-effect waves-light");
    
    cell = $(row.insertCell(-1));
    if(data.trans_status == 0){
    	cell.append(btnRemove);
    }
    else{
    	cell.append('');
    }
    cell.attr("class","text-center");
    cell.attr("style","width:10%;");
    itemCount++;
}

function batchRemove(button){
    var row = $(button).closest("TR");
	var table = $("#batchTable")[0];
	table.deleteRow(row[0].rowIndex);

	$('#batchTable tbody tr td:nth-child(1)').each(function(idx, ele) {
        ele.textContent = idx + 1;
    });
	var countTR = $('#batchTable tbody tr:last').index() + 1;

    if (countTR == 0) {
        $("#batchTable tbody").html('<tr id="noData"><td colspan="9" align="center">No data available in table</td></tr>');
    }
}
