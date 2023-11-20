var zindex = 9000;
$(document).ready(function(){
	$(".logo-icon").hide();
	$(document).on('click','.sidebartoggler',function(){
		if($(".logo-icon").is(':visible')){$(".logo-icon").hide();}
		else{$(".logo-icon").show();}		
	});

	var lastActivityTime = new Date();

	// Update last activity time on user interaction events //mousemove
	$(document).on('click change keydown', function() {
		var idleTime = 7200; //Session Time
		var currentDateTime = new Date();

		// Calculate the time difference in milliseconds
		var idleThreshold = currentDateTime - lastActivityTime;

		// Convert the time difference to seconds
        var secondsDifference = Math.floor(idleThreshold / 1000);

		if (secondsDifference > idleTime) {
			// Idle time exceeded threshold, perform actions or redirect user
			//console.log('User is idle');
			window.location.reload();
			// Perform any necessary actions or redirect the user
		} else {
			// User is active, perform any necessary actions
			lastActivityTime = new Date();
		}		
	});

	// Check last activity time every second
	setInterval(function() {
		var idleTime = 7200; //Session Time
		var currentDateTime = new Date();

		// Calculate the time difference in milliseconds
		var idleThreshold = currentDateTime - lastActivityTime;

		// Convert the time difference to seconds
        var secondsDifference = Math.floor(idleThreshold / 1000);

		if (secondsDifference > idleTime) {
			// Idle time exceeded threshold, perform actions or redirect user
			//console.log('User is idle');
			window.location.reload();
			// Perform any necessary actions or redirect the user
		} else {
			// User is active, perform any necessary actions
			//console.log('User is active, Seconds : '+ secondsDifference);
		}
	}, 1000); // Check every second (adjust interval as needed)
    
    /* document.addEventListener('contextmenu', function(e) {e.preventDefault();});
	document.onkeydown = function(e) {
		if(event.keyCode == 123) {return false;}
		if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {return false;}
		if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {return false;}
		if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {return false;}
		if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {return false;}
	} */
    
	// initSpeech Recognitation Menu();
	$('[data-tooltip="tooltip"]').tooltip();
	ssTableInit();
	initMultiSelect();
	checkPermission();
	setMinMaxDate();
	setPlaceHolder();
	//getDynamicItemList();
	$(".single-select").comboSelect();
	$('.select2').select2({ width: null});
	
	$(document).on("keypress",".numericOnly",function (e) {
		if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
	});	

	$(document).on("keypress",'.floatOnly',function(event) {
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {event.preventDefault();}
	});
	
	// Focus QR Code input
	$( "#qrCodeModal" ).on('shown.bs.modal', function(){$('.decodeData').html('');$('#decodeQr').val('');$('#decodeQr').focus();});	
	
	/*** Keep Selected Tab after page loading ***/
	var selectedTab = localStorage.getItem('selected_tab');
	if (selectedTab != null) { $("#"+selectedTab).trigger('click'); }
	$(document).on('click','.nav-tab',function(){
		var id = $(this).attr('id');
    	localStorage.setItem('selected_tab', id);
    });
	
	
	$(document).on('click',".addNew",function(){
        var functionName = $(this).data("function");
        var modalId = $(this).data('modal_id');
        var button = $(this).data('button');
		var title = $(this).data('form_title');
		var formId = $(this).data('form_id') || functionName.split('/')[0];
		var controllerName = $(this).data('controller') || controller;
		var postData = $(this).data('postdata') || {};
		var fnsave = $(this).data("fnsave") || "save";
		var resFunction = $(this).data('res_function') || "";
		var jsStoreFn = $(this).data('js_store_fn') || 'store';
		var savebtn_text = $(this).data('savebtn_text') || '<i class="fa fa-check"></i> Save';

		var fnJson = "{'formId':'"+formId+"','controller':'"+controllerName+"','fnsave':'"+fnsave+"'}";

		if(typeof postData === "string"){
			postData = JSON.parse(postData);
		}

        $.ajax({ 
            type: "post",   
            url: base_url + controllerName + '/' + functionName,
            data: postData
        }).done(function(response){
            $("#"+modalId).modal({show:true});
            $("#"+modalId).css({'z-index':zindex++,'overflow':'auto'});
			$("#"+modalId+'').addClass(formId+"Modal");
			$("#"+modalId+' .modal-title').html(title);
			$("#"+modalId+' .modal-body').html("");
            $("#"+modalId+' .modal-body').html(response);
            $("#"+modalId+" .modal-body form").attr('id',formId);
			if(resFunction != ""){
				$("#"+modalId+" .modal-body form").attr('data-res_function',resFunction);
			}
			$("#"+modalId+" .modal-footer .btn-save").html(savebtn_text);
			$("#"+modalId+" .modal-footer .btn-save").attr('onclick',jsStoreFn+"("+fnJson+");");

			$("#"+modalId+" .modal-header .close").attr('data-modal_id',modalId);
			$("#"+modalId+" .modal-header .close").attr('data-modal_class',formId+"Modal");
			$("#"+modalId+" .modal-footer .btn-close").attr('data-modal_id',modalId);
			$("#"+modalId+" .modal-footer .btn-close").attr('data-modal_class',formId+"Modal");

            if(button == "close"){
                $("#"+modalId+" .modal-footer .btn-close").show();
                $("#"+modalId+" .modal-footer .btn-save").hide();
            }else if(button == "save"){
                $("#"+modalId+" .modal-footer .btn-close").hide();
                $("#"+modalId+" .modal-footer .btn-save").show();
            }else{
                $("#"+modalId+" .modal-footer .btn-close").show();
                $("#"+modalId+" .modal-footer .btn-save").show();
            }

			initModalPlugin(modalId);
        });
    });	

	$(document).on('change','.country_list',function(){
		var id = $(this).val();
		var state_id = $(this).data('state_id');
		var selected_state_id = $(this).data('selected_state_id') || "";
		var city_id = $("#"+state_id).data('city_id');
		if(id == ""){
			$("#"+state_id).html('<option value="">Select State</option>');
			$("#"+city_id).html('<option value="">Select City</option>');
			$("#"+state_id+",#"+city_id).select2();//.comboSelect();
		}else{
			$.ajax({
				url: base_url + 'parties/getStatesOptions',
				type:'post',
				data:{country_id:id},
				dataType:'json',
				success:function(data){
					$("#"+state_id).html(data.result);
					if(selected_state_id != ""){
						$("#"+state_id).val(selected_state_id);
						$(".state_list").trigger('change');
						$("#"+state_id).select2();//.comboSelect();
					}else{
						$("#"+state_id).select2();//.comboSelect();
						//$("#"+state_id).focus();
					}	
				}
			});
		}
	});

	$(document).on('change',".state_list",function(){
		var id = $(this).val();
		var city_id = $(this).data('city_id');
		var selected_city_id = $(this).data('selected_city_id') || "";
		if(id == ""){
			$("#"+city_id).html('<option value="">Select City</option>');
			$("#"+city_id).select2();//.comboSelect();
		}else{
			$.ajax({
				url: base_url + 'parties/getCitiesOptions',
				type:'post',
				data:{state_id:id},
				dataType:'json',
				success:function(data){
					$("#"+city_id).html(data.result);
					if(selected_city_id != ""){
						$("#"+city_id).val(selected_city_id);
						$("#"+city_id).select2();//.comboSelect();
					}else{
						$("#"+city_id).select2();//.comboSelect();
						//$("#"+city_id).focus();
					}					
				}
			});
		}
	});	

	$(document).on('click','.pswHideShow',function(){
		var type = $('.pswType').attr('type');
		if(type == "password"){
			$(".pswType").attr('type','text');
			$(this).html('<i class="fa fa-eye-slash"></i>');
		}else{
			$(".pswType").attr('type','password');
			$(this).html('<i class="fa fa-eye"></i>');
		}
	});

	$(document).on('mouseenter', '.mainButton', function(e){
		e.preventDefault();
		$(this).addClass('open');
		$(this).addClass('showAction');
		$(this).children('.fa').removeClass('fa-cog');
		$(this).children('.fa').addClass('fa-times');
		$(this).parent().children('.btnDiv').css('z-index','9');
	});

	$(document).on('mouseleave', '.actionButtons', function(e){
		e.preventDefault();
		$('.mainButton').removeClass('open');
		$('.mainButton').removeClass('showAction');
		$('.mainButton').children('.fa').removeClass('fa-times');
		$('.mainButton').children('.fa').addClass('fa-cog');
		$('.mainButton').parent().children('.btnDiv').css('z-index','-1');
	});
	
	$(document).ajaxStart(function(){
		$('.ajaxModal').show();$('.centerImg').show();$(".error").html("");
		$('.btn-save').attr('disabled','disabled');
	});
	
	$(document).ajaxComplete(function(){
		$('.ajaxModal').hide();$('.centerImg').hide();
		$('.btn-save').removeAttr('disabled');
		checkPermission();
	});
	
	$(document).on('change','#financialYearSelection',function(){
		var send_data = { year:$(this).val() };
		$.confirm({
			title: 'Confirm!',
			content: 'Are you sure want to change this Financial Year ?',
			type: 'red',
			buttons: {   
				ok: {
					text: "ok!",
					btnClass: 'btn waves-effect waves-light btn-outline-success',
					keys: ['enter'],
					action: function(){
						$.ajax({
							url: base_url + 'login/setFinancialYear',
							data: send_data,
							type: "POST",
							dataType:"json",
							success:function(data)
							{
								if(data.status==0)
								{
									toastr.error(data.message, 'Sorry...!', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
								}
								else
								{
									toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
									window.location.reload();
								}
							}
						});
					}
				},
				cancel: {
					btnClass: 'btn waves-effect waves-light btn-outline-secondary',
					action: function(){
	
					}
				}
			}
		});
	});

	$(document).on('blur','.fyDates',function(){
		setMinMaxDate();
		var inputName = $(this).attr('name');
		var date = $(this).val();
		var minAttr = $(this).attr('min');
		var maxAttr = $(this).attr('max');			

		fDate = Date.parse(minAttr);
		lDate = Date.parse(maxAttr);
		cDate = Date.parse(date);

		$("."+inputName).html("");
		if((cDate < fDate || cDate > lDate)) {
			$("."+inputName).html("Please select valid Date.");
			$(this).val("");
			$(".btn-save").attr('disabled','disabled');
		}else{
			$('.btn-save').removeAttr('disabled');
		}
	});	

	$(document).on('change',".partyDetails",function(){
		var party_id = $(this).val();
		var resFunctionName = $(this).data('res_function') || "";

		if(party_id){
			$.ajax({
				url : base_url + '/parties/getPartyDetails',
				type:'post',
				data: {id:party_id},
				dataType : 'json',
			}).done(function(response){
				window[resFunctionName](response);
			});
		}else{
			window[resFunctionName]();
		}
	});

	$(document).on('change click',".itemDetails",function(){
		var item_id = $(this).val();
		var resFunctionName = $(this).data('res_function') || "";
		
		if(item_id){
			$.ajax({
				url : base_url + '/items/getItemDetails',
				type:'post',
				data: {id:item_id},
				dataType : 'json',
			}).done(function(response){
				window[resFunctionName](response);
			});
		}else{
			window[resFunctionName]();
		}
	});

	$(document).on('click','.btn-close,.close',function(){
		zindex = zindex--;
		var modal_id = $(this).data('modal_id');
		var modal_class = $(this).data('modal_class');
		$("#"+modal_id).removeClass(modal_class);
		$("#"+modal_id+' .modal-body').html("");
		$(".modal").css({'overflow':'auto'});
		$("#"+modal_id+" .modal-header .close").attr('data-modal_id',"");
		$("#"+modal_id+" .modal-header .close").attr('data-modal_class',"");
		$("#"+modal_id+" .modal-footer .btn-close").attr('data-modal_id',"");
		$("#"+modal_id+" .modal-footer .btn-close").attr('data-modal_class',"");
		$(".select2").select2();
	});

	$(document).on('change','.custom-file-input',function(){
		var inputId = $(this).attr('id');
		if($('#'+inputId).hasClass("multifiles")){
			var files = $('#'+inputId).prop("files")
			var fileNames = $.map(files, function(val) { return val.name; });
			var fileName = fileNames.join(", ") || "Choose file";
		}else{
			var fileName = $('#'+inputId).val().split('\\').pop() || "Choose file";
		}
        $('label[for="' + inputId + '"]').html(fileName);
	});

	$(document).on('click','.removeImage',function(){
        $(this).parent('div').remove();
    });

	//$("#print_dialog").modal();
	$(document).on("click",".printDialog",function(){
		$("#printModel").attr('action',base_url + controller + "/" + ($(this).data('fn_name') || ""));
		$("#printModel #id").val($(this).data('id'));
		$("#print_dialog").modal();
	});

	// on first focus (bubbles up to document), open the menu
	$(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
		$(this).closest(".select2-container").siblings('select:enabled').select2('open');
	});
	
	// steal focus during close - only capture once and stop propogation
	$('select.select2').on('select2:closing', function (e) {
		$(e.target).data("select2").$selection.one('focus focusin', function (e) {
			e.stopPropagation();
		});
	});
});

$(window).on('pageshow', function() {
	$('form').off();
	checkPermission();setMinMaxDate();
});

function setMinMaxDate(){
	$.each($('.fyDates'),function(){
		var minAttr = $(this).attr('min');
		var maxAttr = $(this).attr('max');	
		if(typeof minAttr === 'undefined' || minAttr === false){ $(this).attr('min',startYearDate); }
		if(typeof maxAttr === 'undefined' || maxAttr === false){ $(this).attr('max',endYearDate); }	
	});	
}

function setPlaceHolder(){
	var label="";
	$('input').each(function () {
		if(!$(this).hasClass('combo-input') && $(this).attr("type")!="hidden" )
		{
			label="";
			inputElement = $(this).parent();
			if($(this).parent().hasClass('input-group')){inputElement = $(this).parent().parent();}else{inputElement = $(this).parent();}
			label = inputElement.children("label").text();
			label = label.replace('*','');
			label = $.trim(label);
			if($(this).hasClass('req')){inputElement.children("label").html(label + ' <strong class="text-danger">*</strong>');}
			if(!$(this).attr("placeholder")){if(label){$(this).attr("placeholder", label);}}
			$(this).attr("autocomplete", 'off');
			var errorClass="";
			var nm = $(this).attr('name');
			if($(this).attr('id')){errorClass=$(this).attr('id');}else{errorClass=$(this).attr('name');if(errorClass){errorClass = errorClass.replace("[]", "");}}
			if(inputElement.find('.'+errorClass).length <= 0){inputElement.append('<div class="error '+ errorClass +'"></div>');}
		}
		else{$(this).attr("autocomplete", 'off');}
	});
	$('textarea').each(function () {
		label="";
		label = $(this).parent().children("label").text();
		label = label.replace('*','');
		label = $.trim(label);
		if($(this).hasClass('req')){$(this).parent().children("label").html(label + ' <strong class="text-danger">*</strong>');}
		if(label){$(this).attr("placeholder", label);}
		$(this).attr("autocomplete", 'off');
		var errorClass="";
		var nm = $(this).attr('name');
		if($(this).attr('name')){errorClass=$(this).attr('name');}else{errorClass=$(this).attr('id');}
		if($(this).parent().find('.'+errorClass).length <= 0){$(this).parent().append('<div class="error '+ errorClass +'"></div>');}
	});
	$('select').each(function () {
		label="";
		var selectElement = $(this).parent();
		if($(this).hasClass('single-select')){selectElement = $(this).parent().parent();}
		label = selectElement.children("label").text();
		label = label.replace('*','');
		label = $.trim(label);
		if($(this).hasClass('req')){selectElement.children("label").html(label + ' <strong class="text-danger">*</strong>');}
		var errorClass="";
		var nm = $(this).attr('name');
		if($(this).attr('name')){errorClass=$(this).attr('name');}else{errorClass=$(this).attr('id');}
		if(selectElement.find('.'+errorClass).length <= 0){selectElement.append('<div class="error '+ errorClass +'"></div>');}
	});
}

function initMultiSelect(){
	$('.jp_multiselect').multiselect({
		includeSelectAllOption:false,
		enableFiltering:true,
        enableCaseInsensitiveFiltering: true,
		buttonWidth: '100%',
		onChange: function() {
			var inputId = this.$select.data('input_id');
			var selected = this.$select.val();$('#' + inputId).val(selected);
		}
	});
	$('.form-check-input').addClass('filled-in');
	$('.multiselect-filter i').removeClass('fas');
	$('.multiselect-filter i').removeClass('fa-sm');
	$('.multiselect-filter i').addClass('fa');
	$('.multiselect-container.dropdown-menu').addClass('scrollable');
	$('.multiselect-container.dropdown-menu').css('max-height','200px');
	$('.scrollable').perfectScrollbar({wheelPropagation: !0});
}

function reInitMultiSelect(){
	$('.jp_multiselect').multiselect('rebuild');
	$('.form-check-input').addClass('filled-in');
	$('.multiselect-filter i').removeClass('fas');
	$('.multiselect-filter i').removeClass('fa-sm');
	$('.multiselect-filter i').addClass('fa');
	$('.multiselect-container.dropdown-menu').addClass('scrollable');
	$('.multiselect-container.dropdown-menu').css('height','200px');
	$('.scrollable').perfectScrollbar({wheelPropagation: !0});
}

function statusTab(tableId,status,hp_fn_name="",page=""){

    $("#"+tableId).attr("data-url",$("#"+tableId).data('url')+'/'+status);

	$("#"+tableId).data("hp_fn_name","");
    $("#"+tableId).data("page","");
    $("#"+tableId).data("hp_fn_name",hp_fn_name);
    $("#"+tableId).data("page",page);

    ssTable.state.clear();
	initTable();
}

function ssTableInit(){
	var tableId = $('.ssTable').attr('id');
	$("#"+tableId).data("hp_fn_name","");
    $("#"+tableId).data("page","");
	var tableOptions = {pageLength: 25,'stateSave':false};
    ssDatatable($('.ssTable'),tableHeaders,tableOptions);
}

function initTable(postData = {}){
	$('.ssTable').DataTable().clear().destroy();
	var tableId = $('.ssTable').attr('id');
	
	var hp_fn_name = $("#"+tableId).data("hp_fn_name") || "";
	var page = $("#"+tableId).data("page") || "";

	if(hp_fn_name != "" && page != ""){
		$.ajax({
			url : base_url + controller + '/getTableHeader',
			type : 'POST',
			data : {'hp_fn_name':hp_fn_name,'page':page},
			dataType: 'json',
			success: function(response) {
				var tableOptions = {pageLength: 25,'stateSave':false};
				var dataSet = postData;
				var tableHeaders = response.data;
				tableHeaders.reInit = 1;
				
				$('.ssTable').html("");
				ssDatatable($('.ssTable'),tableHeaders,tableOptions,dataSet);
			},
			error: function() {
				console.log('Error occurred while fetching table headers.');
			}
		});
	}else{
		var tableOptions = {pageLength: 25,'stateSave':false};
		var tableHeaders = {'theads':'','textAlign':textAlign,'sortable':sortable,'reInit':'1'};
		var dataSet = postData;
		ssDatatable($('.ssTable'),tableHeaders,tableOptions,dataSet);
	}	
}

function initDataTable(tableId = "commanTable"){
	var table = $('#'+tableId).DataTable( {
		lengthChange: false,
		responsive: true,
		'stateSave':true,
		retrieve: true,
		buttons: [ 'pageLength','copy', 'excel' ]
	});
	table.buttons().container().appendTo( '#'+tableId+'_wrapper .col-md-6:eq(0)' );
	return table;
};

function closeModal(formId){
	zindex = zindex--;
	var modal_id = $("."+formId+"Modal").attr('id');
	$("#"+modal_id).removeClass(formId+"Modal");
	$("#"+modal_id+' .modal-body').html("");
	$("#"+modal_id).modal('hide');	
	$("#"+modal_id).modal({'overflow':'auto'});	
	$(".modal").css({'overflow':'auto'});

	$("#"+modal_id+" .modal-header .close").attr('data-modal_id',"");
	$("#"+modal_id+" .modal-header .close").attr('data-modal_class',"");
	$("#"+modal_id+" .modal-footer .btn-close").attr('data-modal_id',"");
	$("#"+modal_id+" .modal-footer .btn-close").attr('data-modal_class',"");
	$(".select2").select2();
}

function store(postData){
	setPlaceHolder();

	var formId = postData.formId;
	var fnsave = postData.fnsave || "save";
	var controllerName = postData.controller || controller;

	var form = $('#'+formId)[0];
	var fd = new FormData(form);
	$.ajax({
		url: base_url + controllerName + '/' + fnsave,
		data:fd,
		type: "POST",
		processData:false,
		contentType:false,
		dataType:"json",
	}).done(function(data){
		if(data.status==1){
			initTable(); $('#'+formId)[0].reset(); closeModal(formId);
			
			toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
		}else{
			if(typeof data.message === "object"){
				$(".error").html("");
				$.each( data.message, function( key, value ) {$("."+key).html(value);});
			}else{
				initTable();
				toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
			}			
		}				
	});
}

function customStore(postData){
	setPlaceHolder();

	var formId = postData.formId;
	var fnsave = postData.fnsave || "save";
	var controllerName = postData.controller || controller;

	var form = $('#'+formId)[0];
	var fd = new FormData(form);
	var resFunctionName = $("#"+formId).data('res_function') || "";

	$.ajax({
		url: base_url + controllerName + '/' + fnsave,
		data:fd,
		type: "POST",
		processData:false,
		contentType:false,
		dataType:"json",
	}).done(function(data){
		if(resFunctionName != ""){
			window[resFunctionName](data,formId);
		}else{
			if(data.status==1){
				initTable();
				toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
			}else{
				if(typeof data.message === "object"){
					$(".error").html("");
					$.each( data.message, function( key, value ) {$("."+key).html(value);});
				}else{
					initTable();
					toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
				}			
			}	
		}			
	});
}

function confirmStore(data){
	setPlaceHolder();

	var formId = data.formId || "";
	var fnsave = data.fnsave || "save";
	var controllerName = data.controller || controller;

	if(formId != ""){
		var form = $('#'+formId)[0];
		var fd = new FormData(form);
		var resFunctionName = $("#"+formId).data('res_function') || "";
		var msg = data.confirm_msg || "Are you sure want to save this record ?";
		var ajaxParam = {
			url: base_url + controllerName + '/' + fnsave,
			data:fd,
			type: "POST",
			processData:false,
			contentType:false,
			dataType:"json"
		};
	}else{
		var fd = data.postData;
		var resFunctionName = data.res_function || "";
		var msg = data.message || "Are you sure want to save this change ?";
		var ajaxParam = {
			url: base_url + controllerName + '/' + fnsave,
			data:fd,
			type: "POST",
			dataType:"json"
		};
	}

	$.confirm({
		title: 'Confirm!',
		content: msg,
		type: 'orange',
		buttons: {   
			ok: {
				text: "ok!",
				btnClass: 'btn waves-effect waves-light btn-outline-success',
				keys: ['enter'],
				action: function(){
					$.ajax(ajaxParam).done(function(response){
						if(resFunctionName != ""){
							window[resFunctionName](response,formId);
						}else{
							if(response.status==1){
								initTable();

								if(formId != ""){
									$('#'+formId)[0].reset(); closeModal(formId);
								}

								toastr.success(response.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
							}else{
								if(typeof response.message === "object"){
									$(".error").html("");
									$.each( response.message, function( key, value ) {$("."+key).html(value);});
								}else{
									initTable();
									toastr.error(response.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
								}			
							}	
						}			
					});
				}
			},
			cancel: {
                btnClass: 'btn waves-effect waves-light btn-outline-secondary',
                action: function(){

				}
            }
		}
	});
}

function edit(data){
	var button = data.button;if(button == "" || button == null){button="both";};
	var fnedit = data.fnedit;if(fnedit == "" || fnedit == null){fnedit="edit";}
	var fnsave = data.fnsave;if(fnsave == "" || fnsave == null){fnsave="save";}
	var controllerName = data.controller;if(controllerName == "" || controllerName == null){controllerName=controller;}
	var savebtn_text = data.savebtn_text;
	if(savebtn_text == "" || savebtn_text == null){savebtn_text='<i class="fa fa-check"></i> Save';}

	var resFunction = data.res_function || "";
	var jsStoreFn = data.js_store_fn || 'store';

	var fnJson = "{'formId':'"+data.form_id+"','fnsave':'"+fnsave+"','controller':'"+controllerName+"','confirm_msg':'"+(data.confirm_msg || "")+"'}";

	$.ajax({ 
		type: "POST",   
		url: base_url + controllerName + '/' + fnedit,   
		data: data.postData,
	}).done(function(response){
		$("#"+data.modal_id).modal({show:true});
		$("#"+data.modal_id).css({'z-index':zindex++,'overflow':'auto'});
		$("#"+data.modal_id).addClass(data.form_id+"Modal");
		$("#"+data.modal_id+' .modal-title').html(data.title);
		$("#"+data.modal_id+' .modal-body').html('');
		$("#"+data.modal_id+' .modal-body').html(response);
		$("#"+data.modal_id+" .modal-body form").attr('id',data.form_id);
		if(resFunction != ""){
			$("#"+data.modal_id+" .modal-body form").attr('data-res_function',resFunction);
		}
		$("#"+data.modal_id+" .modal-footer .btn-save").html(savebtn_text);
		$("#"+data.modal_id+" .modal-footer .btn-save").attr('onclick',jsStoreFn+"("+fnJson+");");

		$("#"+data.modal_id+" .modal-header .close").attr('data-modal_id',data.modal_id);
		$("#"+data.modal_id+" .modal-header .close").attr('data-modal_class',data.form_id+"Modal");
		$("#"+data.modal_id+" .modal-footer .btn-close").attr('data-modal_id',data.modal_id);
		$("#"+data.modal_id+" .modal-footer .btn-close").attr('data-modal_class',data.form_id+"Modal");

		if(button == "close"){
			$("#"+data.modal_id+" .modal-footer .btn-close").show();
			$("#"+data.modal_id+" .modal-footer .btn-save").hide();
		}else if(button == "save"){
			$("#"+data.modal_id+" .modal-footer .btn-close").hide();
			$("#"+data.modal_id+" .modal-footer .btn-save").show();
		}else{
			$("#"+data.modal_id+" .modal-footer .btn-close").show();
			$("#"+data.modal_id+" .modal-footer .btn-save").show();
		}

		initModalPlugin(data.modal_id);
	});
}

function trash(data){
	var controllerName = data.controller || controller;
	var fnName = data.fndelete || "delete";
	var msg = data.message || "Record";
	var send_data = data.postData;
	var resFunctionName = data.res_function || "";

	$.confirm({
		title: 'Confirm!',
		content: 'Are you sure want to delete this '+msg+'?',
		type: 'red',
		buttons: {   
			ok: {
				text: "ok!",
				btnClass: 'btn waves-effect waves-light btn-outline-success',
				keys: ['enter'],
				action: function(){
					$.ajax({
						url: base_url + controllerName + '/' + fnName,
						data: send_data,
						type: "POST",
						dataType:"json",
					}).done(function(response){
						if(resFunctionName != ""){
							window[resFunctionName](response);
						}else{
							if(response.status==0){
								toastr.error(response.message, 'Sorry...!', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
							}else{
								initTable();
								toastr.success(response.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
							}	
						}
					});
				}
			},
			cancel: {
                btnClass: 'btn waves-effect waves-light btn-outline-secondary',
                action: function(){

				}
            }
		}
	});
}

function getTransHtml(data){
	var postData = data.postData || {};
	var fnget = data.fnget || "";
	var controllerName = data.controller || controller;
	var resFunctionName = data.res_function || "";

	var table_id = data.table_id || "";
	var thead_id = data.thead_id || "";
	var tbody_id = data.tbody_id || "";
	var tfoot_id = data.tfoot_id || "";	

	if(thead_id != ""){
		$("#"+table_id+" #"+thead_id).html(data.thead);
	}
	
	$.ajax({
		url: base_url + controllerName + '/' + fnget,
		data:postData,
		type: "POST",
		dataType:"json",
		beforeSend: function() {
			if(table_id != ""){
				var columnCount = $('#'+table_id+' thead tr').first().children().length;
				$("#"+table_id+" #"+tbody_id).html('<tr><td colspan="'+columnCount+'" class="text-center">Loading...</td></tr>');
			}
		},
	}).done(function(res){
		if(resFunctionName != ""){
			window[resFunctionName](res);
		}else{
			$("#"+table_id+" #"+tbody_id).html('');
			$("#"+table_id+" #"+tbody_id).html(res.tbodyData);

			if(tfoot_id != ""){
				$("#"+table_id+" #"+tfoot_id).html('');
				$("#"+table_id+" #"+tfoot_id).html(res.tfootData);
			}
		}
	});
}

function changePsw(formId){
	var fd = $('#'+formId).serialize();
	$.ajax({
		url: base_url + 'hr/employees/changePassword',
		data:fd,
		type: "POST",
		dataType:"json",
	}).done(function(data){
		if(data.status===0){
			$(".error").html("");
			$.each( data.message, function( key, value ) {
				$("."+key).html(value);
			});
		}else if(data.status==1){
			initTable(); $("#change-psw").modal('hide');
			toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
		}else{
			initTable(); $("#change-psw").modal('hide');
			toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
		}		
	});
}

/***** Get Select2 Data *****/
function getDynamicItemList(dataSet = {},eleClass = "large-select2")
{   
	var eleID = $('.' + eleClass).attr('id');
	var url = base_url + $('.' + eleClass).data('url');
	var pholder = $('.' + eleClass).data('pholder');
	
	var base_element = $('.' + eleClass);
	
	$(base_element).select2({
		placeholder: pholder,
		closeOnSelect: true,
		ajax: {
			url: url,
			type: "post",
			dataType: 'json',
			//delay: 250,
			global:false,
			data: function (params) {var dataObj = {searchTerm: params.term,item_type:$(this).attr('data-item_type'),category_id:$(this).attr('data-category_id'),family_id:$(this).attr('data-family_id'),default_val:$(this).attr('data-default_val')};return $.extend(dataObj, dataSet);},
			processResults: function (response) {return {results: response};},
			templateSelection: function (item) { return item.name; },
			cache: true
		},
		dropdownParent: $(base_element).parent()
	});
	
	if(dataSet.id)
	{
    	setTimeout(function()
    	{
    	    if(dataSet.id != "" && dataSet.row != "" && dataSet.text != "")
    	    {
    		    var $option = "<option value='"+dataSet.id+"' data-row='"+dataSet.row+"' selected>"+dataSet.text+"</option>";
                $('.' + eleClass).append($option).trigger('change');
    	    }
    	}, 200);
    }
}

function isInteger(x) { return typeof x === "number" && isFinite(x) && Math.floor(x) === x; }
function isFloat(x) { return !!(x % 1); }

function checkPermission(){
	$('.permission-read').show();
	$('.permission-write').show();
	$('.permission-modify').show();
	$('.permission-remove').show();
	$('.permission-approve').show();

	//view permission
	if(permissionRead == "1"){ 
		$('.permission-read').prop('disabled', false);
		$('.permission-read').show(); 
	}else{ 
		$('.permission-read').prop('disabled', true);
		$('.permission-read').hide(); 
		window.location.href = base_url + 'error_403';
	}

	//write permission
	if(permissionWrite == "1"){ 
		$('.permission-write').prop('disabled', false);
		$('.permission-write').show(); 
	}else{ 
		$('.permission-write').prop('disabled', true);
		$('.permission-write').hide(); 
	}

	//update permission
	if(permissionModify == "1"){ 
		$('.permission-modify').prop('disabled', false);
		$('.permission-modify').show(); 
	}else{ 
		$('.permission-modify').prop('disabled', true);
		$('.permission-modify').hide(); 
	}

	//delete permission
	if(permissionRemove == "1"){ 
		$('.permission-remove').prop('disabled', false);
		$('.permission-remove').show(); 
	}else{ 
		$('.permission-remove').prop('disabled', true);
		$('.permission-remove').hide(); 
	}

	//Approve permission
	if(permissionApprove == "1"){ 
		$('.permission-approve').prop('disabled', false);
		$('.permission-approve').show(); 
	}else{ 
		$('.permission-approve').prop('disabled', true);
		$('.permission-approve').hide(); 
	}
}

function toFixTableHeader(){
    var scroll = $(window).scrollTop();
    $('.ssTable body').css("visibility", "hidden");

    if (scroll >= $('.table-responsive').offset().top) {$(".ssTable thead tr th").css({ top: scroll - $('.table-responsive').offset().top+10 });} else {$(".ssTable thead tr th").css({top: 0 });}
	$(".ssTable thead tr th").css('z-index','99');
    $('.ssTable body').css("visibility", "visible");
	checkPermission();
}

function initModalPlugin(modalId){
	$("#"+modalId+" .single-select").comboSelect();
	$("#"+modalId+" .select2").select2({with:null});
	$("#"+modalId+" .scrollable").perfectScrollbar({suppressScrollX: true});
	setTimeout(function(){ 
		initMultiSelect();setPlaceHolder();setMinMaxDate();
	}, 5);
	setTimeout(function(){
		$('#'+modalId+' input[type="text"]:first').focus();
	},500);
}

function formatResult(node) {
    var level = "1";
    if(node.element !== undefined){
      level = (node.element.className);
      if(level.trim() !== ''){var l = level.split("_");level = l[1];}
    }
	
	var lArr = level.split(".");
	level = lArr.length-1;
    var $result = $('<span style="padding-left:' + (20 * level) + 'px;">' + node.text + '</span>');
    return $result;
};

function formatDate(date,format='Y-m-d') {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;
        
    var convertedDate = date;
    if(format == "Y-m-d"){return [year, month, day].join('-');}
    if(format == "y-m-d"){year = year.toString().substr(-2); convertedDate = [year, month, day].join('-');}
    if(format == "d-m-Y"){return [day, month, year].join('-');}
    if(format == "d-m-y"){year = year.toString().substr(-2); convertedDate = [day, month, year].join('-');}
    
    return convertedDate;
}

function formatSymbol(selection) {
    var img_path = $(selection.element).data('img_path');
    
    if(!img_path){return selection.text;}
    else {
        var $selection = $('<img src="' + img_path + '" style="width:20px;"><span class="img-changer-text">' + $(selection.element).text() + '</span>');
        return $selection;
    }
}

function decodeQrCode(){
    var qrValue = $('#decodeQr').val();
	var sendData = {qrValue:qrValue};
	$.ajax({ 
		type: "POST",   
		url: base_url + 'dashboard/decodeQRCode',   
		data: sendData,
	}).done(function(response){
	    $('.decodeData').html(response.decodeData);
	});
}

$(document).on('keypress','#decodeQr',function(e){ 
	if(e.which == 13) {
		var qrValue = $(this).val();
		$.ajax({
			type: "POST",
			url: base_url + 'dashboard/decodeQRCode',
			data:{qrValue:qrValue},
			dataType:'json'
		}).done(function (response) {
	    $('.decodeData').html(response.decodeData);});
	}
});

function calcTimeDiffInHrs(start_time,end_time,type="H"){
    var time1 = start_time.split(':'), time2 = end_time.split(':');
    var hours1 = parseInt(time1[0], 10), 
    hours2 = parseInt(time2[0], 10),
    mins1 = parseInt(time1[1], 10),
    mins2 = parseInt(time2[1], 10);
    var hours = hours2 - hours1, mins = 0;
    
    if(hours < 0) hours = 24 + hours;
    
    if(mins2 >= mins1) {mins = mins2 - mins1;}
    else {mins = (mins2 + 60) - mins1;hours--;}
    
    var minute = (hours * 60) + mins;
    mins = mins / 60; 
    
    hours += mins;
    hours = hours.toFixed(2);
    if(type=="H"){return hours;}
    else{return minute;}
}

function inrFormat(no){
    if(no){
        no=no.toString();
        var afterPoint = '';
        if(no.indexOf('.') > 0)
           afterPoint = no.substring(no.indexOf('.'),no.length);
        no = Math.floor(no);
        no=no.toString();
        var lastThree = no.substring(no.length-3);
        var otherNumbers = no.substring(0,no.length-3);
        if(otherNumbers != ''){lastThree = ',' + lastThree;}
            
        var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;
    	return res;
    }else{return no;}        
}

function resPartyMaster(response,formId){
	if(response.status==1){
        $('#'+formId)[0].reset();closeModal(formId);
        toastr.success(response.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

		$.ajax({
			url : base_url + '/parties/getPartyList',
			type : 'post',
			data : {"party_category":$("#party_id").data('party_category'),"party_type":($("#party_id").data('party_type') || 1)},
			dataType : 'json',
		}).done(function(res){
			$(".partyOptions").html(res.data.partyOptions);
			$("#party_id").val(response.id);
			$("#party_id").select2();
			$(".partyDetails").trigger('change');
		});		
    }else{
        if(typeof response.message === "object"){
            $(".error").html("");
            $.each( response.message, function( key, value ) {$("."+key).html(value);});
        }else{
            toastr.error(response.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }	
}

function resItemMaster(response,formId){
	if(response.status==1){
        $('#'+formId)[0].reset();closeModal(formId);
        toastr.success(response.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

		$.ajax({
			url : base_url + '/items/getItemList',
			type : 'post',
			data : {"item_type":$("#item_id").data('item_type')},
			dataType : 'json',
		}).done(function(res){
			$(".itemOptions").html(res.data.itemOptions);
			$("#item_id").val(response.id);
			$("#item_id").select2();
			$(".itemDetails").trigger('change');
		});
    }else{
        if(typeof response.message === "object"){
            $(".error").html("");
            $.each( response.message, function( key, value ) {$("."+key).html(value);});
        }else{
            toastr.error(response.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }	
}

function resPurityMaster(response,formId){
	if(response.status==1){
        $('#'+formId)[0].reset();closeModal(formId);
        toastr.success(response.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

		$.ajax({
			url : base_url + '/purity/getPurityList',
			type : 'post',
			data : {},
			dataType : 'json',
		}).done(function(res){
			$(".purityOptions").html(res.data.purityOptions);
			$("#purity_id").val(response.id);
			$("#purity_id").select2();
		});
    }else{
        if(typeof response.message === "object"){
            $(".error").html("");
            $.each( response.message, function( key, value ) {$("."+key).html(value);});
        }else{
            toastr.error(response.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }
}

function resFineMaster(response,formId){
	if(response.status==1){
        $('#'+formId)[0].reset();closeModal(formId);
        toastr.success(response.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

		$.ajax({
			url : base_url + '/fine/getFineList',
			type : 'post',
			data : {},
			dataType : 'json',
		}).done(function(res){
			$(".fineOptions").html(res.data.fineOptions);
			$("#fine_id").val(response.id);
			$("#fine_id").select2();
		});
    }else{
        if(typeof response.message === "object"){
            $(".error").html("");
            $.each( response.message, function( key, value ) {$("."+key).html(value);});
        }else{
            toastr.error(response.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }
}

function resPolishMaster(response,formId){
	if(response.status==1){
        $('#'+formId)[0].reset();closeModal(formId);
        toastr.success(response.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

		$.ajax({
			url : base_url + '/polish/getPolishList',
			type : 'post',
			data : {},
			dataType : 'json',
		}).done(function(res){
			$(".polishOptions").html(res.data.polishOptions);
			$("#polish_id").val(response.id);
			$("#polish_id").select2();
		});
    }else{
        if(typeof response.message === "object"){
            $(".error").html("");
            $.each( response.message, function( key, value ) {$("."+key).html(value);});
        }else{
            toastr.error(response.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }
}

function resColorMaster(response,formId){
	if(response.status==1){
        $('#'+formId)[0].reset();closeModal(formId);
        toastr.success(response.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

		$.ajax({
			url : base_url + '/color/getColorList',
			type : 'post',
			data : {},
			dataType : 'json',
		}).done(function(res){
			$(".colorOptions").html(res.data.colorOptions);
			$("#color_id").val(response.id);
			$("#color_id").select2();
		});
    }else{
        if(typeof response.message === "object"){
            $(".error").html("");
            $.each( response.message, function( key, value ) {$("."+key).html(value);});
        }else{
            toastr.error(response.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }
}

function resClarityMaster(response,formId){
	if(response.status==1){
        $('#'+formId)[0].reset();closeModal(formId);
        toastr.success(response.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

		$.ajax({
			url : base_url + '/clarity/getClarityList',
			type : 'post',
			data : {},
			dataType : 'json',
		}).done(function(res){
			$(".clarityOptions").html(res.data.clarityOptions);
			$("#clarity_id").val(response.id);
			$("#clarity_id").select2();
		});
    }else{
        if(typeof response.message === "object"){
            $(".error").html("");
            $.each( response.message, function( key, value ) {$("."+key).html(value);});
        }else{
            toastr.error(response.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }
}

function resDiamondQualityMaster(response,formId){
	if(response.status==1){
        $('#'+formId)[0].reset();closeModal(formId);
        toastr.success(response.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

		$.ajax({
			url : base_url + '/diamondQuality/getList',
			type : 'post',
			data : {},
			dataType : 'json',
		}).done(function(res){
			$(".qualityOptions").html(res.data.qualityOptions);
			$("#diamond_quality_id").val(response.id);
			$("#diamond_quality_id").select2();
		});
    }else{
        if(typeof response.message === "object"){
            $(".error").html("");
            $.each( response.message, function( key, value ) {$("."+key).html(value);});
        }else{
            toastr.error(response.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }
}

function getItemBybarcode(barcode=""){
	var item_code = barcode || $("#barcode_scanner").val(); 
	if(item_code){
		$.ajax({
			url : base_url + '/stockTrans/getItemDetails',
			type:'post',
			data: {unique_id:item_code,stock_required:1},
			dataType : 'json',
		}).done(function(response){
			if(barcode != ""){
				barcodeDetails(item_code,response.data.itemDetail)
			}else{
				barcodeScan(response.data.itemDetail)
			}
		});
	}else{
		 
	}
}

function resChangeRate(response,formId){
	if(response.status==1){
        $('#'+formId)[0].reset();closeModal(formId);
		console.log("okok");
        toastr.success(response.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

		window.location.reload();
    }else{
        if(typeof response.message === "object"){
            $(".error").html("");
            $.each( response.message, function( key, value ) {$("."+key).html(value);});
        }else{
            toastr.error(response.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }
}