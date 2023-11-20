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
                                        <button onclick="statusTab('issueItemTable',0);" id="pending_mi" class="nav-tab btn waves-effect waves-light btn-outline-danger active" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('issueItemTable',1);" id="complete_mi" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Completed</button> 
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-2">
                                <h4 class="card-title">Material Issue</h4>
                            </div>
                            <div class="col-md-5">
                                <button type="button" class="btn waves-effect waves-light btn-outline-primary float-right addNew permission-write press-add-btn" data-button="both" data-modal_id="modal-xl" data-function="addIssue" data-form_title="Issue Items" ><i class="fa fa-plus"></i> Issue Item</button>

                                <button type="button" class="btn waves-effect waves-light btn-outline-warning float-right addNew permission-write press-add-btn" data-button="both" data-modal_id="modal-xl" data-function="salesReturn" data-form_title="Sales Return Items" data-fnsave="saveSalesReturnMaterial" ><i class="fa fa-plus"></i> Sales Return</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='issueItemTable' class="table table-bordered ssTable ssTable-cf" data-url='/getDTRows' data-ninput="[0,1]"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>