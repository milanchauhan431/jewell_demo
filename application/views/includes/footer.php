
			<footer class="footer text-center">
                Developed By <span><a href="https://finter.in" class="text-danger">FINTER</a> BUSINESS SOLUTIONS LLP</span>                 
            </footer>
		</div>
	</div>
		<?php $this->load->view('includes/modal_md');?>
		<?php $this->load->view('includes/modal_lg');?>
		<?php $this->load->view('includes/modal_xl');?>
		<?php $this->load->view('includes/modal_xxl');?>
		<?php $this->load->view('includes/modal_master');?>
		<?php $this->load->view('includes/print_modal');?>
		<?php $this->load->view('includes/custom_panel');?>
		<?php $this->load->view('includes/footerfiles');?>
		
		<div class="modal fade" id="qrCodeModal" role="dialog" tabindex="-1" aria-labelledby="exampleModalLabel1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content animated slideDown" style="min-height:350px;">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1">DECODE QR CODE</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group ">
						    <input type="text" name="decodeQr" id="decodeQr" class="form-control" style="border:1px solid #000000;background:#deffe0;">
							<!--<button type="button" class="btn waves-effect waves-light btn-success" onclick="decodeQrCode()"><i class="fa fa-check"></i> Decode</button>-->
						</div>
                        <div class="decodeData"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn waves-effect waves-light btn-outline-secondary btn-close press-close-btn save-form" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        
                    </div>
                </div>
            </div>
        </div>
	</body>
</html>