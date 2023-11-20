<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-5">
                            <ul class="nav nav-pills">
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('inwardReceiptTable',0);" id="pending_ir" class="nav-tab btn waves-effect waves-light btn-outline-danger active" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('inwardReceiptTable',1);" id="complete_ir" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Completed</button> 
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-2">
                                <h4 class="card-title">Inward Receipt</h4>
                            </div>
                            <div class="col-md-5">
                                <button type="button" class="btn waves-effect waves-light btn-outline-primary float-right addNew press-add-btn permission-write" data-button="both" data-modal_id="modal-xxl" data-function="addInward" data-form_title="Add Inward Receipt"><i class="fa fa-plus"></i> Add Inward</button>
                            </div>                             
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='inwardReceiptTable' class="table table-bordered ssTable ssTable-cf" data-url='/getDTRows' data-ninput="[0,1]"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<div class="modal fade" id="print_barcode_modal" role="dialog" aria-labelledby="exampleModalLabel1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document" style="max-width:80%;">
        <div class="modal-content animated slideDown">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>