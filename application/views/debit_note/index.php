<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <!-- <div class="col-md-4">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('salesInvoiceTable',0);" id="pending_so" class="nav-tab btn waves-effect waves-light btn-outline-success active" style="outline:0px" data-toggle="tab" aria-expanded="false">Tax Invoice</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('salesInvoiceTable',1);" id="complete_so" class="nav-tab btn waves-effect waves-light btn-outline-dark" style="outline:0px" data-toggle="tab" aria-expanded="false">Canceled Invoice</button> 
                                    </li>
                                </ul>
                            </div> -->
                            <div class="col-md-6 text-left">
                                <h4 class="card-title">Debit Note</h4>
                            </div>
                            <div class="col-md-6">
                                <a href="javascript:void(0)" class="btn waves-effect waves-light btn-outline-primary float-right permission-write press-add-btn" onclick="window.location.href='<?=base_url($headData->controller.'/addDebitNote')?>'"><i class="fa fa-plus"></i> Add Debit Note</a>
                            </div>                             
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='debitNoteTable' class="table table-bordered ssTable" data-url='/getDTRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>