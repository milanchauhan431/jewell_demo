<div class="modal fade" id="print_dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog" style="min-width:30%;">
		<div class="modal-content animated zoomIn border-light">
			<div class="modal-header bg-light">
				<h5 class="modal-title text-dark"><i class="fa fa-print"></i> Print Options</h5>
				<button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="printModel" method="post" action="" target="_blank">
				<div class="modal-body">
					<div class="col-md-12">
						<div class="row">
                            <input type="hidden" name="id" id="id" value="0">
                            
							<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" name="original" id="original" class="filled-in chk-col-success" value="1" checked>
									<label for="original">Original</label>
								</div>
								<div class="custom-control custom-checkbox">
									<input type="checkbox" name="duplicate" id="duplicate" class="filled-in chk-col-success" value="1" checked>
									<label for="duplicate">Duplicate</label>
								</div>
								<div class="custom-control custom-checkbox">
									<input type="checkbox" name="triplicate" id="triplicate" class="filled-in chk-col-success" value="1">
									<label for="triplicate">Triplicate</label>
								</div>
								<div class="custom-control custom-checkbox">
									<input type="checkbox" name="header_footer" id="header_footer" class="filled-in chk-col-success" value="1" checked>
									<label for="header_footer">Without Letterhead</label>
								</div>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <div class="col-md-12 form-group">
								<label>No. of Extra Copy</label>
								<input type="text" name="extra_copy" id="extra_copy" class="form-control numericOnly" value="0">
                                </div>

                                <div class="col-md-12 form-group">
                                <label>Max Lines Per Page</label>
								<input type="text" name="max_lines" id="max_lines" class="form-control numericOnly" placeholder="Max Lines Per Page" value="">
                                </div>
								<label class="error_extra_copy text-danger"></label>
							</div>
                            
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<a href="#" data-dismiss="modal" class="btn btn-secondary"><i class="fa fa-times"></i> Close</a>
					<button type="submit" class="btn btn-success" onclick="printModal('print_dialog');"><i class="fa fa-print"></i> Print</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
function printModal(modalId){
	$("#"+ modalId).modal('hide');
}
</script>