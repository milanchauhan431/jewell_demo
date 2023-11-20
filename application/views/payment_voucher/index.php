<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('paymentVoucherTable','BCRct');" id="received_vou" class="nav-tab btn waves-effect waves-light btn-outline-success active" style="outline:0px" data-toggle="tab" aria-expanded="false">Received Voucher</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('paymentVoucherTable','BCPmt');" id="paid_vou" class="nav-tab btn waves-effect waves-light btn-outline-warning" style="outline:0px" data-toggle="tab" aria-expanded="false">Paid Voucher</button> 
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 text-center">
                                <h4 class="card-title">Payment Voucher</h4>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn waves-effect waves-light btn-outline-primary float-right addNew press-add-btn permission-write" data-button="both" data-modal_id="modal-lg" data-function="addPaymentVoucher" data-form_title="Add Voucher"><i class="fa fa-plus"></i> Add Voucher</button>
                            </div>                             
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='paymentVoucherTable' class="table table-bordered ssTable ssTable-cf" data-url='/getDtRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
