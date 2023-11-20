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
                                        <button onclick="statusTab('salesQuotationTable',0);" id="pending_sq" class="nav-tab btn waves-effect waves-light btn-outline-danger active" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('salesQuotationTable',1);" id="complete_sq" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Completed</button> 
                                    </li>
                                    <!-- <li class="nav-item"> 
                                        <button onclick="statusTab('salesQuotationTable',2);" id="closed_sq" class="nav-tab btn waves-effect waves-light btn-outline-dark" style="outline:0px" data-toggle="tab" aria-expanded="false">Closed/Cancel</button> 
                                    </li> -->
                                </ul>
                            </div>
                            <div class="col-md-4 text-center">
                                <h4 class="card-title">Sales Quotation</h4>
                            </div>
                            <div class="col-md-4">
                                <a href="javascript:void(0)" class="btn waves-effect waves-light btn-outline-primary float-right permission-write press-add-btn" onclick="window.location.href='<?=base_url($headData->controller.'/addQuotation')?>'"><i class="fa fa-plus"></i> Add Quotation</a>
                            </div>                             
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='salesQuotationTable' class="table table-bordered ssTable ssTable-cf" data-url='/getDTRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>