$(document).ready(function(){
	$(".cgstCol").show();$(".sgstCol").show();$(".igstCol").hide();
	$(".amountCol").hide();$(".netAmtCol").show();

	var gst_type = $("#gst_type").val() || 1;
	if (gst_type == 1) {
		$(".cgstCol").show(); $(".sgstCol").show(); $(".igstCol").hide();
		$(".amountCol").hide(); $(".netAmtCol").show();
	} else if (gst_type == 2) {
		$(".cgstCol").hide(); $(".sgstCol").hide(); $(".igstCol").show();
		$(".amountCol").hide(); $(".netAmtCol").show();
	} else {
		$(".cgstCol").hide(); $(".sgstCol").hide(); $(".igstCol").hide();
		$(".amountCol").show(); $(".netAmtCol").hide();
	}

	var numberOfChecked = $('.termCheck:checkbox:checked').length;
	$("#termsCounter").html(numberOfChecked);
	$(document).on("click", ".termCheck", function () {
		var id = $(this).data('rowid');
		var numberOfChecked = $('.termCheck:checkbox:checked').length;
		$("#termsCounter").html(numberOfChecked);
		if ($("#md_checkbox" + id).attr('check') == "checked") {
			$("#md_checkbox" + id).attr('check', '');
			$("#md_checkbox" + id).removeAttr('checked');
			$("#term_id" + id).attr('disabled', 'disabled');
			$("#term_title" + id).attr('disabled', 'disabled');
			$("#condition" + id).attr('disabled', 'disabled');
		} else {
			$("#md_checkbox" + id).attr('check', 'checked');
			$("#term_id" + id).removeAttr('disabled');
			$("#term_title" + id).removeAttr('disabled');
			$("#condition" + id).removeAttr('disabled');
		}
	});

    $(document).on('keyup', '.calculateSummary', function () { claculateColumn(); });
    $(document).on('change','#gstin', function(){ gstin(); });
    /* $(document).on('change','#sales_type', function(){ gstin(); }); */
	$(document).on('change',"#apply_round",function(){ claculateColumn(); });

	$(document).on('change',"#sp_acc_id",function(){
		var tax_class = $(this).find(":selected").data('tax_class');
		var gstin = $("#gstin").find(":selected").val();	
		$("#tax_class").val(tax_class);

		var gst_type = 1;var stateCode = 24;
		if(gstin != ""){		
			stateCode = gstin.substr(0, 2);
			if(stateCode == 24 || stateCode == "24"){
				gst_type = 1;
			}else{
				gst_type = 2;
			}
			$('#sp_acc_id').select2();
		}else{
			stateCode = 96;
		}

		if($.inArray(tax_class, ["SALESGSTACC","SALESJOBGSTACC","PURGSTACC","PURJOBGSTACC"]) >= 0){
			gst_type = 1;
		}else if($.inArray(tax_class, ["SALESIGSTACC","SALESJOBIGSTACC","EXPORTGSTACC","SEZSTFACC","SEZSGSTACC","DEEMEDEXP","PURIGSTACC","PURJOBIGSTACC","IMPORTACC","IMPORTSACC","SEZRACC"]) >= 0){
			gst_type = 2;
		}else if($.inArray(tax_class, ["SALESTFACC","SALESEXEMPTEDTFACC","EXPORTTFACC","PURTFACC","PURURDGSTACC","PURURDIGSTACC","PUREXEMPTEDTFACC"]) >= 0){
			gst_type = 3;
		}

		if($.inArray(tax_class, ["EXPORTGSTACC","EXPORTTFACC","IMPORTACC","IMPORTSACC"]) >= 0){
			$(".exportData").removeClass("hidden");
		}else{
			$(".exportData").addClass("hidden");
		}

		$("#gst_type").val(gst_type);
		$("#party_state_code").val(stateCode);

		if(gst_type == 1){ 
			$(".cgstCol").show();$(".sgstCol").show();$(".igstCol").hide();
			$(".amountCol").hide();$(".netAmtCol").show();
		}else if(gst_type == 2){
			$(".cgstCol").hide();$(".sgstCol").hide();$(".igstCol").show();
			$(".amountCol").hide();$(".netAmtCol").show();
		}else{
			$(".cgstCol").hide();$(".sgstCol").hide();$(".igstCol").hide();
			$(".amountCol").show();$(".netAmtCol").hide();
		}

		claculateColumn();
	});

	$(document).on('keypress','#itemForm #unique_id',function(e){
		if(e.which == 13) {
			barcodeDetails($(this).val());
	   	}
	});

	$(document).on('click','#itemForm #unique_search',function(){
		barcodeDetails($("#itemForm #unique_id").val());
	});

	$(document).on('keypress','#barcode_scanner',function(e){
		if(e.which == 13) {
			barcodeScan();
		}
	});

	$(document).on('click','#barcode_search',function(){
		barcodeScan();
	});
	
});

function gstin(){
    var gstin = $("#gstin").find(":selected").val();	

    var gst_type = 1; var stateCode = 24;
	var inv_type = $("#inv_type").val(); 
	if(inv_type == "SALES"){
		$('#sp_acc_id').find('option[data-tax_class="SALESGSTACC"]').prop('selected', true);
		$("#tax_class").val("SALESGSTACC");
	}else{
		$('#sp_acc_id').find('option[data-tax_class="PURGSTACC"]').prop('selected', true);
		$("#tax_class").val("PURGSTACC");
	}
	
	if(gstin != "" && gstin != "URP"){
		stateCode = gstin.substr(0, 2);
		if(stateCode == 24 || stateCode == "24"){
			gst_type = 1;
			if(inv_type == "SALES"){
				$('#sp_acc_id').find('option[data-tax_class="SALESGSTACC"]').prop('selected', true);
				$("#tax_class").val("SALESGSTACC");
			}else{
				$('#sp_acc_id').find('option[data-tax_class="PURGSTACC"]').prop('selected', true);
				$("#tax_class").val("PURGSTACC");
			}
		}else{
			gst_type = 2;//stateCode = 96;
			if(inv_type == "SALES"){
				$('#sp_acc_id').find('option[data-tax_class="SALESIGSTACC"]').prop('selected', true);
				$("#tax_class").val("SALESIGSTACC");
			}else{
				$('#sp_acc_id').find('option[data-tax_class="PURIGSTACC"]').prop('selected', true);
				$("#tax_class").val("PURIGSTACC");
			}			
		}
	}else{
		if(inv_type == "PURCHASE"){
			$('#sp_acc_id').find('option[data-tax_class="PURTFACC"]').prop('selected', true);
			$("#tax_class").val("PURTFACC");
			gst_type = 3;
		}else if(inv_type == "SALES"){
			$('#sp_acc_id').find('option[data-tax_class="SALESGSTACC"]').prop('selected', true);
			$("#tax_class").val("SALESGSTACC");
			gst_type = 1;
		}
		stateCode = 96;
	}

	if($.inArray(inv_type, ["PURCHASE","SALES"]) >= 0){
		var tax_class = $(this).find(":selected").data('tax_class');
		if($.inArray(tax_class, ["EXPORTGSTACC","EXPORTTFACC","IMPORTACC","IMPORTSACC"]) >= 0){
			$(".exportData").removeClass("hidden");
		}else{
			$(".exportData").addClass("hidden");
		}
	}

    $("#gst_type").val(gst_type);
    $("#party_state_code").val(stateCode);
	$('#sp_acc_id').select2();

    if(gst_type == 1){ 
		$(".cgstCol").show();$(".sgstCol").show();$(".igstCol").hide();
		$(".amountCol").hide();$(".netAmtCol").show();
	}else if(gst_type == 2){
		$(".cgstCol").hide();$(".sgstCol").hide();$(".igstCol").show();
		$(".amountCol").hide();$(".netAmtCol").show();
	}else{
		$(".cgstCol").hide();$(".sgstCol").hide();$(".igstCol").hide();
		$(".amountCol").show();$(".netAmtCol").hide();
	}

    claculateColumn();
}

function claculateColumn() {
	var amountArray = $(".amount").map(function () { return $(this).val(); }).get();
	var amountSum = 0;
	$.each(amountArray, function () { amountSum += parseFloat(this) || 0; });
	$("#total_amount").html(amountSum.toFixed(2));

	var taxableAmountArray = $(".taxable_amount").map(function () { return $(this).val(); }).get();
	var taxableAmountSum = 0;
	$.each(taxableAmountArray, function () { taxableAmountSum += parseFloat(this) || 0; });
	$("#taxable_amount").val(taxableAmountSum.toFixed(2));

	calculateSummary();
}

function calculateSummary() {
	$(".calculateSummary").each(function () {
		var row = $(this).data('row');

		var map_code = row.map_code;
		var amtField = $("#" + map_code + "_amt");
		var netAmountField = $("#" + map_code + "_amount");
		var perField = $("#" + map_code + "_per");
		var sm_type = amtField.data('sm_type');

		if (sm_type == "exp") {
			if (row.position == "1") {
				var itemGstArray = $(".gst_per").map(function () { return $(this).val(); }).get();
				var maxGstPer = Math.max.apply(Math, itemGstArray);
				maxGstPer = (maxGstPer != "" && !isNaN(maxGstPer)) ? maxGstPer : 0;

				if (row.calc_type == "1") {
					var amount = (amtField.val() != "") ? amtField.val() : 0;
					amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(2);
					netAmountField.val(amount);
					var gstAmount = parseFloat((parseFloat(maxGstPer) * parseFloat(amount)) / 100).toFixed(2);
				} else {
					var taxable_amount = ($("#taxable_amount").val() != "") ? $("#taxable_amount").val() : 0;
					var per = (perField.val() != "") ? perField.val() : 0;

					var amount = parseFloat((parseFloat(taxable_amount) * parseFloat(per)) / 100).toFixed(2);
					amtField.val(amount);
					amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(2);
					netAmountField.val(amount);
					var gstAmount = parseFloat((parseFloat(maxGstPer) * parseFloat(amount)) / 100).toFixed(2);
				}

				$("#other_" + map_code + "_amount").val(gstAmount);

			} else {
				if (row.calc_type == "1") {
					var amount = (amtField.val() != "") ? amtField.val() : 0;
					amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(2);
					netAmountField.val(amount);
				} else {
					var taxable_amount = ($("#taxable_amount").val() != "") ? $("#taxable_amount").val() : 0;
					var per = (perField.val() != "") ? perField.val() : 0;
					var amount = parseFloat((parseFloat(taxable_amount) * parseFloat(per)) / 100).toFixed(2);
					amtField.val(amount);
					amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(2);
					netAmountField.val(amount);
				}
			}
		}

		if (sm_type == "tax") {
			if(row.calculation_type == 2){
				var oldAmt = amtField.val();
				oldAmt = (parseFloat(oldAmt) > 0)?oldAmt:0;	
				calculateSummaryAmount();		

				var summaryAmtArray = $(".summaryAmount").map(function(){return $(this).val();}).get();
				var summaryAmtSum = 0;
				$.each(summaryAmtArray,function(){summaryAmtSum += parseFloat(this) || 0;});
				
				if(parseFloat(summaryAmtSum) > 0){
					summaryAmtSum = parseFloat(parseFloat(summaryAmtSum) - parseFloat(oldAmt)).toFixed(2);
				}else{
					amtField.val(0);
				}
				
				var per = (perField.val() != "")?perField.val():0;				
				var amount = parseFloat((parseFloat(summaryAmtSum) * parseFloat(per)) / 100).toFixed(2);				
				amtField.val(amount);
				amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(2);
				netAmountField.val(amount);
			}else if (row.calculation_type == 1) {
				var taxable_amount = ($("#taxable_amount").val() != "") ? $("#taxable_amount").val() : 0;
				var per = (perField.val() != "") ? perField.val() : 0;
				var amount = parseFloat((parseFloat(taxable_amount) * parseFloat(per)) / 100).toFixed(2);
				amtField.val(amount);
				amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(2);
				netAmountField.val(amount);
			} else {
				var qtyArray = $(".item_qty").map(function () { return $(this).val(); }).get();
				var qtySum = 0;
				$.each(qtyArray, function () { qtySum += parseFloat(this) || 0; });

				var per = (perField.val() != "") ? perField.val() : 0;
				var amount = parseFloat(parseFloat(qtySum) * parseFloat(per)).toFixed(2);
				amtField.val(amount);
				amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(2);
				netAmountField.val(amount);
			}
		}


	});

	calculateSummaryAmount();
}

function calculateSummaryAmount() {
	var gst_type = $("#gst_type").val();

	$('#cgst_amount').val("0");
	$('#sgst_amount').val("0");
	if (gst_type == 1) {
		var cgstAmtArr = $(".cgst_amount").map(function () { return $(this).val(); }).get();
		var cgstAmtSum = 0;
		$.each(cgstAmtArr, function () { cgstAmtSum += parseFloat(this) || 0; });
		$('#cgst_amount').val(parseFloat(cgstAmtSum).toFixed(2));

		var sgstAmtArr = $(".sgst_amount").map(function () { return $(this).val(); }).get();
		var sgstAmtSum = 0;
		$.each(sgstAmtArr, function () { sgstAmtSum += parseFloat(this) || 0; });
		$('#sgst_amount').val(parseFloat(sgstAmtSum).toFixed(2));
	}

	$('#igst_amount').val("0");
	if (gst_type == 2) {
		var igstAmtArr = $(".igst_amount").map(function () { return $(this).val(); }).get();
		var igstAmtSum = 0;
		$.each(igstAmtArr, function () { igstAmtSum += parseFloat(this) || 0; });
		$('#igst_amount').val(parseFloat(igstAmtSum).toFixed(2));
	}

	var otherGstAmtArray = $(".otherGstAmount").map(function () { return $(this).val(); }).get();
	var otherGstAmtSum = 0;
	$.each(otherGstAmtArray, function () { otherGstAmtSum += parseFloat(this) || 0; });

	var cgstAmt = 0;
	var sgstAmt = 0;
	var igstAmt = 0;
	if (gst_type == 1) {
		cgstAmt = parseFloat(parseFloat(otherGstAmtSum) / 2).toFixed(2);
		sgstAmt = parseFloat(parseFloat(otherGstAmtSum) / 2).toFixed(2);
		$("#cgst_amount").val(parseFloat(parseFloat($("#cgst_amount").val()) + parseFloat(cgstAmt)).toFixed(2));
		$("#sgst_amount").val(parseFloat(parseFloat($("#sgst_amount").val()) + parseFloat((sgstAmt))).toFixed(2));
	} else if (gst_type == 2) {
		igstAmt = otherGstAmtSum;
		$("#igst_amount").val(parseFloat(parseFloat($("#igst_amount").val()) + parseFloat((igstAmt))).toFixed(2));
	}

	var summaryAmtArray = $(".summaryAmount").map(function () { return $(this).val(); }).get();
	var summaryAmtSum = 0;
	$.each(summaryAmtArray, function () { summaryAmtSum += parseFloat(this) || 0; });

	if ($("#roff_amount").length > 0) {
		var totalAmount = parseFloat(summaryAmtSum).toFixed(2);
		var decimal = totalAmount.split('.')[1];
		var roundOff = 0;
		var netAmount = 0;
		if (decimal !== 0) {
			if (decimal >= 50) {
				if ($('#apply_round').val() == "1") { roundOff = (100 - decimal) / 100; }
				netAmount = parseFloat(parseFloat(totalAmount) + parseFloat(roundOff)).toFixed(2);
			} else {
				if ($('#apply_round').val() == "1") { roundOff = (decimal - (decimal * 2)) / 100; }
				netAmount = parseFloat(parseFloat(totalAmount) + parseFloat(roundOff)).toFixed(2);
			}
			$("#roff_amount").val(parseFloat(roundOff).toFixed(2));
		}
		$("#net_amount").val(netAmount);
	} else {
		$("#net_amount").val(summaryAmtSum.toFixed(2));
	}
}

async function barcodeDetails(barcode="",formData=""){	
	if(formData ==""){
		var formData = await getItemBybarcode(barcode); 
	}else{ 
		if(formData == null){
			toastr.error("Sorry! Item not found or Out of stock.", 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
			return false;
		}

		if(formData.stock_type == "FREEZE"){
			toastr.error("Sorry! This item is already booked.", 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
			return false;
		}

		if(formData.item_id != $("#itemForm #item_id :selected").val()){
			toastr.error("Sorry! Scaned barcode item and selected item mismatch.", 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
			$('#itemForm #qty').val(1);
			$('#itemForm #unique_id').val("");
			$("#itemForm #stock_trans_id").val("");
			$("#itemForm #location_id").val("");
			$("#itemForm #standard_qty").val("");
			$("#itemForm #purity").val("");
			$("#itemForm #stock_category").val("");
			$("#itemForm #org_price").val("");
			$("#itemForm #price").val("");
			$("#itemForm #gross_weight").val("");
			$("#itemForm #net_weight").val("");
			$("#itemForm #making_per").val("");
			$("#itemForm #making_disc_per").val("");
			$("#itemForm #other_charge").val("");
			$("#itemForm #vrc_charge").val("");
			$("#itemForm #diamond_amount").val("");
			$("#itemForm #color").val("");
			$("#itemForm #diamond_carat").val("");
			$("#itemForm #diamond_pcs").val("");
			return false;
		}

		$('#itemForm #qty').val(1);
		$('#itemForm #unique_id').val(formData.unique_id);
		$("#itemForm #stock_trans_id").val(formData.id);
		$("#itemForm #location_id").val(formData.location_id);
		$("#itemForm #standard_qty").val(formData.standard_qty);
		$("#itemForm #purity").val(formData.purity);
		$("#itemForm #stock_category").val(formData.stock_category);
		$("#itemForm #org_price").val(formData.sales_price);
		$("#itemForm #price").val(formData.price);
		$("#itemForm #gross_weight").val(formData.gross_weight);
		$("#itemForm #net_weight").val(formData.net_weight);
		$("#itemForm #making_per").val(formData.making_per);
		$("#itemForm #making_disc_per").val(formData.making_disc_per);
		$("#itemForm #other_charge").val(formData.otc_amount);
		$("#itemForm #vrc_charge").val(formData.vrc_amount);
		$("#itemForm #diamond_amount").val(formData.diamond_amount);
		$("#itemForm #color").val(formData.color);
		$("#itemForm #diamond_carat").val(formData.diamond_carat);
		$("#itemForm #diamond_pcs").val(formData.diamond_pcs);
		if(parseFloat(formData.gold_platinum_price) > 0){
			$("#itemForm #gold_platinum_price_div").show();
			$("#itemForm #gold_platinum_price").val(formData.gold_platinum_price);
			$("#itemForm #gold_weight").val(formData.gold_weight);
		}else{
			$("#itemForm #gold_platinum_price_div").hide();
			$("#itemForm #gold_platinum_price").val(0);
			$("#itemForm #gold_weight").val(0);
		}
	}
}

async function barcodeScan(formData=""){
	if(formData ==""){
		var formData = await getItemBybarcode();
	}else{ 
		if(formData == null){
			toastr.error("Sorry! Item not found or Out of stock.", 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
			return false;
		}

		if(formData.stock_type == "FREEZE"){
			toastr.error("Sorry! This item is already booked.", 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
			return false;
		}

		$('#barcode_scanner').val("");
		formData.stock_trans_id = formData.id;
		formData.id = "";
		formData.qty = 1;
		formData.disc_per = (parseFloat(formData.disc_per) > 0)?formData.disc_per:0;
		var amount = 0; var taxable_amount = 0; var disc_amt = 0; var igst_amt = 0;
		var gst_amount = 0; var cgst_amt = 0; var sgst_amt = 0; var net_amount = 0; 
		var gst_per = 0; var cgst_per = 0; var sgst_per = 0; var igst_per = 0;
		var mackingChargeAmt = 0;var mcDiscAmt = 0;var otherChargeAmt = 0; 
		var varietyChargeAmt = 0; var diamondAmount = 0; var gold_platinum_price = 0;
		var totalMcAmt = 0;

		totalMcAmt = amount = parseFloat(parseFloat(formData.net_weight) * parseFloat(formData.price)).toFixed(2);		

		if(parseFloat(formData.gold_platinum_price) > 0){
			gold_platinum_price = parseFloat(formData.gold_platinum_price).toFixed(2);
			totalMcAmt = parseFloat(parseFloat(totalMcAmt) + parseFloat(gold_platinum_price)).toFixed(2);
		}	

		mackingChargeAmt = parseFloat((parseFloat(totalMcAmt) * parseFloat(formData.making_per))/100).toFixed(2);
		if (formData.making_disc_per != "" && formData.making_disc_per != "0") {
			mcDiscAmt = parseFloat((parseFloat(mackingChargeAmt) * parseFloat(formData.making_disc_per)) / 100).toFixed(2);
		}

		otherChargeAmt = parseFloat((formData.otc_amount || 0)).toFixed(2);
		varietyChargeAmt = parseFloat((formData.vrc_amount || 0)).toFixed(2);
		diamondAmount = parseFloat((formData.diamond_amount || 0)).toFixed(2);

		taxable_amount = parseFloat(parseFloat(amount) + (parseFloat(mackingChargeAmt) - parseFloat(mcDiscAmt)) + parseFloat(otherChargeAmt) + parseFloat(varietyChargeAmt) + parseFloat(diamondAmount) + parseFloat(gold_platinum_price)).toFixed(2);

		if(formData.disc_amount != "" && parseFloat(formData.disc_amount) > 0){
			taxable_amount = parseFloat(parseFloat(amount) - parseFloat(formData.disc_amount)).toFixed(2);
		}
		
		formData.making_charge = mackingChargeAmt;
		formData.making_charge_dicount = mcDiscAmt;
		formData.other_charge = otherChargeAmt;
		formData.vrc_charge = varietyChargeAmt;
		formData.diamond_amount = diamondAmount;
		
		formData.gst_per = igst_per = parseFloat(formData.gst_per).toFixed(0);
		formData.gst_amount = igst_amt = parseFloat((igst_per * taxable_amount) / 100).toFixed(2);

		cgst_per = parseFloat(parseFloat(igst_per) / 2).toFixed(2);
		sgst_per = parseFloat(parseFloat(igst_per) / 2).toFixed(2);

		cgst_amt = parseFloat((cgst_per * taxable_amount) / 100).toFixed(2);
		sgst_amt = parseFloat((sgst_per * taxable_amount) / 100).toFixed(2);

		net_amount = parseFloat(parseFloat(taxable_amount) + parseFloat(igst_amt)).toFixed(2);
		formData.gst_per = parseFloat(formData.gst_per);
		formData.qty = parseFloat(formData.qty).toFixed(2);
		formData.cgst_per = cgst_per;
		formData.cgst_amount = cgst_amt;
		formData.sgst_per = sgst_per;
		formData.sgst_amount = sgst_amt;
		formData.igst_per = igst_per;
		formData.igst_amount = igst_amt;
		formData.disc_amount = disc_amt;
		formData.amount = amount;
		formData.taxable_amount = taxable_amount;
		formData.net_amount = net_amount;
		formData.row_index = '';
		formData.stock_eff = $('#barcode_scanner').data("stock_effect");
		AddRow(formData); 
	}
}


