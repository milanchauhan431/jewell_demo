<?php $this->load->view('change_password');?>
<script>
	var base_url = '<?=base_url();?>'; 
	var controller = '<?=(isset($headData->controller)) ? $headData->controller : ''?>'; 
	var popupTitle = '<?=POPUP_TITLE;?>';
	var theads = '<?=(isset($tableHeader)) ? $tableHeader[0] : ''?>';
	var textAlign = '<?=(isset($tableHeader[1])) ? $tableHeader[1] : ''?>';
	var srnoPosition = '<?=(isset($tableHeader[2])) ? $tableHeader[2] : 1?>';
	var sortable = '<?=(isset($tableHeader[3])) ? $tableHeader[3] : ''?>';
	var tableHeaders = {'theads':theads,'textAlign':textAlign,'srnoPosition':srnoPosition,'sortable':sortable};

	var startYearDate = '<?=$this->startYearDate?>';
	var endYearDate = '<?=$this->endYearDate?>';
</script>
<div class="chat-windows"></div>

<!-- Permission Checking -->
<?php
	$script= "";
	if($permission = $this->session->userdata('emp_permission')):
		if(!empty($headData->pageUrl)):
			$empPermission = $permission[$headData->pageUrl];
			$script .= '<script>
				var permissionRead = "'.$empPermission['is_read'].'";
				var permissionWrite = "'.$empPermission['is_write'].'";
				var permissionModify = "'.$empPermission['is_modify'].'";
				var permissionRemove = "'.$empPermission['is_remove'].'";
				var permissionApprove = "'.$empPermission['is_approve'].'";
			</script>';
			echo $script;
		else:
			$script .= '<script>
				var permissionRead = "1";
				var permissionWrite = "1";
				var permissionModify = "1";
				var permissionRemove = "1";
				var permissionApprove = "1";
			</script>';
			echo $script;
		endif;
	else:
		$script .= '<script>
				var permissionRead = "";
				var permissionWrite = "";
				var permissionModify = "";
				var permissionRemove = "";
				var permissionApprove = "";
			</script>';
		echo $script;
	endif;
?>

<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="<?=base_url()?>assets/libs/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap tether Core JavaScript -->
<script src="<?=base_url()?>assets/libs/popper.js/dist/umd/popper.min.js"></script>
<script src="<?=base_url()?>assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- apps -->
<script src="<?=base_url()?>assets/js/app.min.js"></script>
<!-- Theme settings -->
<script src="<?=base_url()?>assets/js/app.init.light-sidebar.js"></script>
<script src="<?=base_url()?>assets/js/app-style-switcher.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="<?=base_url()?>assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
<script src="<?=base_url()?>assets/extra-libs/sparkline/sparkline.js"></script>
<!--Wave Effects -->
<script src="<?=base_url()?>assets/js/waves.js"></script>
<!--Menu sidebar -->
<script src="<?=base_url()?>assets/js/sidebarmenu.js"></script>
<!--Custom JavaScript -->
<script src="<?=base_url()?>assets/js/custom.min.js"></script>
<script src="<?=base_url()?>assets/extra-libs/jquery-ui/jquery-ui.min.js"></script>
<!--This page plugins -->
<script src="<?=base_url()?>assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/extra-libs/bootstrap-datatable/js/dataTables.bootstrap4.min.js"></script>
<script src="<?=base_url()?>assets/extra-libs/bootstrap-datatable/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>assets/extra-libs/bootstrap-datatable/js/buttons.bootstrap4.min.js"></script>
<script src="<?=base_url()?>assets/extra-libs/bootstrap-datatable/js/jszip.min.js"></script>
<script src="<?=base_url()?>assets/extra-libs/bootstrap-datatable/js/pdfmake.min.js"></script>
<script src="<?=base_url()?>assets/extra-libs/bootstrap-datatable/js/vfs_fonts.js"></script>
<script src="<?=base_url()?>assets/extra-libs/bootstrap-datatable/js/buttons.html5.min.js"></script>
<script src="<?=base_url()?>assets/extra-libs/bootstrap-datatable/js/buttons.print.min.js"></script>
<script src="<?=base_url()?>assets/extra-libs/bootstrap-datatable/js/buttons.colVis.min.js"></script>
<script src="<?=base_url()?>assets/extra-libs/bootstrap-datatable/js/natural.js"></script>
<script src="<?=base_url()?>assets/extra-libs/bootstrap-datatable/js/moment.js"></script>
<script src="<?=base_url()?>assets/extra-libs/bootstrap-datatable/js/dataTables.fixedHeader.min.js"></script>
<script src="<?=base_url()?>assets/js/jquery.resize.js"></script>
<!--This page JavaScript -->
<!--c3 JavaScript -->
<script src="<?=base_url()?>assets/extra-libs/c3/d3.min.js"></script>
<script src="<?=base_url()?>assets/extra-libs/c3/c3.min.js"></script>

<!-- Custom Scripts -->
<script src="<?=base_url()?>assets/extra-libs/toastr/dist/build/toastr.min.js"></script>
<script src="<?=base_url()?>assets/js/custom/comman-js.js?v=<?=time()?>"></script>
<script src="<?=base_url()?>assets/js/custom/custom_ajax.js?v=<?=time()?>"></script>
<script src="<?=base_url()?>assets/js/custom/jpstt.js?v=<?=time()?>"></script>
<script src="<?=base_url()?>assets/js/typehead.js?v=<?=time()?>"></script>
<script src="<?=base_url();?>assets/js/jquery-confirm.js"></script>
<script src="<?=base_url();?>assets/js/custom/jquery.alphanum.js"></script>
<script src="<?=base_url();?>assets/libs/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script src="<?=base_url();?>assets/libs/sweetalert2/sweet-alert.init.js"></script>

<!-- Combo Select -->
<script src="<?=base_url()?>assets/extra-libs/comboSelect/jquery.combo.select.js"></script>

<!-- Select2 js -->
<script src="<?=base_url()?>assets/extra-libs/select2/js/select2.full.min.js"></script>
<script src="<?=base_url()?>assets/js/pages/multiselect/js/bootstrap-multiselect.js"></script>
<script src="<?=base_url();?>assets/js/custom/jp-tagsinput.min.js"></script>

<!-- Switchery js -->
<!--<script src="<?php echo base_url();?>assets/extra-libs/switchery/switchery.js"></script>
<script src="<?php echo base_url();?>assets/extra-libs/switchery/js/switchery.min.js"></script>-->
<script src="<?=base_url()?>assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
<script src="<?=base_url()?>assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>
<script src="<?=base_url()?>assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>

<div class="ajaxModal"></div>
<div class="centerImg">
	<img src="<?=base_url()?>assets/images/logo.png" style="width:85%;height:auto;"><br>
	<img src="<?=base_url()?>assets/images/ajaxLoading.gif" style="margin-top:-7px;">
</div>