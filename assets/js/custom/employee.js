$(document).ready(function(){
	
	// Check For Employee is under child act or not
    $(document).on('change','#emp_birthdate',function(){
        var dob = new Date($(this).val());
        var today = new Date();
        var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
        $('#age').html(age+' years old');
        //console.log(dob + " & " + today + " = " + age);
        if (age < 18) {
            $(".emp_birthdate").html("Under Child Labour Act");
        }
        else{$(".emp_birthdate").html("");}        
    });
    
	/* $(document).on('click','.btn-LeaveAuthority',function(){
        var id = $(this).data('id');
        var functionName = $(this).data("function");
        var modalId = $(this).data('modal_id');
        var button = $(this).data('button');
		var title = $(this).data('form_title');

        $.ajax({ 
            type: "POST",   
            url: base_url + controller + '/' + functionName,   
            data: {id:id}
        }).done(function(response){
            $("#"+modalId).modal();
			$("#"+modalId+' .modal-title').html(title);
            $("#"+modalId+' .modal-body').html(response);
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
			$('.single-select').comboSelect();setPlaceHolder();
            $("#leaveHierarchy tbody").sortable({
                items: 'tr',
                cursor: 'pointer',
                axis: 'y',
                dropOnEmpty: false,
                //helper: fixWidthHelper,
                start: function (e, ui) {
                    ui.item.addClass("selected");
                },
                stop: function (e, ui) {
                    ui.item.removeClass("selected");
                    $(this).find("tr").each(function (index) {
                        $(this).find("td").eq(3).html(index+1);
                    });
                },
                update: function () 
                {
                    var ids='';
                    $(this).find("tr").each(function (index) {ids += $(this).attr("id")+",";});
                    var lastChar = ids.slice(-1);
                    if (lastChar == ',') {ids = ids.slice(0, -1);}
                    
                    $.ajax({
                        url: base_url + controller + '/setLeaveApprovalPriority',
                        type:'post',
                        data:{id:ids},
                        dataType:'json',
                        global:false,
                        success:function(data){}
                    });
                }
            });             
        });		
	}); */ 
	
	/* $(document).on('click','.addInDevice',function(){
        var id = $(this).data('id');
        var functionName = $(this).data("function");
        var modalId = $(this).data('modal_id');
        var button = $(this).data('button');
		var title = $(this).data('form_title');

        $.ajax({ 
            type: "POST",   
            url: base_url + controller + '/' + functionName,   
            data: {id:id}
        }).done(function(response){
            $("#"+modalId).modal();
			$("#"+modalId+' .modal-title').html(title);
            $("#"+modalId+' .modal-body').html(response);
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
			$('.single-select').comboSelect();setPlaceHolder();
            $("#leaveHierarchy tbody").sortable({
                items: 'tr',
                cursor: 'pointer',
                axis: 'y',
                dropOnEmpty: false,
                //helper: fixWidthHelper,
                start: function (e, ui) {
                    ui.item.addClass("selected");
                },
                stop: function (e, ui) {
                    ui.item.removeClass("selected");
                    $(this).find("tr").each(function (index) {
                        $(this).find("td").eq(3).html(index+1);
                    });
                },
                update: function () 
                {
                    var ids='';
                    $(this).find("tr").each(function (index) {ids += $(this).attr("id")+",";});
                    var lastChar = ids.slice(-1);
                    if (lastChar == ',') {ids = ids.slice(0, -1);}
                    
                    $.ajax({
                        url: base_url + controller + '/setLeaveApprovalPriority',
                        type:'post',
                        data:{id:ids},
                        dataType:'json',
                        global:false,
                        success:function(data){}
                    });
                }
            });             
        });		
	}); */ 
});
// Change Employee Active Status
/* function changeActiveStatus(id,is_active=1){
    var q = 'Are you sure want to Active this Employee?';
    if(is_active === 0){q = 'Are you sure want to De-Active this Employee?';}
	var send_data = { id:id,is_active:is_active };
	$.confirm({
		title: 'Confirm!',
		content: q,
		type: 'green',
		buttons: {   
			ok: {
				text: "ok!",
				btnClass: 'btn waves-effect waves-light btn-outline-success',
				keys: ['enter'],
				action: function(){
            		$.ajax({
            			url: base_url + controller + '/activeInactive',
            			type:'post',
            			data:send_data,
            			dataType:'json',
            			success:function(data){
            			    if(data.status==0)
							{
								toastr.error(data.message, 'Sorry...!', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
							}
							else
							{
								initTable(); initMultiSelect();
								toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
							}
            			}
            		});
				}
			},
			cancel: {
                btnClass: 'btn waves-effect waves-light btn-outline-secondary',
                action: function(){}
            }
		}
	});
} */

//Created By Karmi @13/01/2022
/* function changeEmpPsw(id,name='Password'){
	var send_data = { id:id };
	$.confirm({
		title: 'Confirm!',
		content: 'Are you sure want to Change Employee Password?',
		type: 'red',
		buttons: {   
			ok: {
				text: "ok!",
				btnClass: 'btn waves-effect waves-light btn-outline-success',
				keys: ['enter'],
				action: function(){
					$.ajax({
						url: base_url + controller + '/changeEmpPsw',
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
								initTable(); initMultiSelect();
								toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
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
} */


/* function addInDevice(id,name='Record'){
	var send_data = { id:id };
	$.confirm({
		title: 'Confirm!',
		content: 'Are you sure want to add in device this '+name+'?',
		type: 'green',
		buttons: {   
			ok: {
				text: "ok!",
				btnClass: 'btn waves-effect waves-light btn-outline-success',
				keys: ['enter'],
				action: function(){
					$.ajax({
						url: base_url + controller + '/addEmployeeInDevice',
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
								initTable(); initMultiSelect();
								toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
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
} */