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
                                        <button onclick="statusTab('giTable','1/0','getStoreDtHeader','pendingGE');" class="nav-tab btn waves-effect waves-light btn-outline-info active" id="pending_ge" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending GE</button> 
                                    </li>
                                    <li class="nav-item">
                                        <button onclick="statusTab('giTable','2/0','getStoreDtHeader','gateInward');" class="nav-tab btn waves-effect waves-light btn-outline-danger" id="pending_gi" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending GI</button>
                                    </li>
                                    <li class="nav-item">
                                        <button onclick="statusTab('giTable','2/1','getStoreDtHeader','gateInward');" class="nav-tab btn waves-effect waves-light btn-outline-success" id="completed_gi" style="outline:0px" data-toggle="tab" aria-expanded="false">Completed GI</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 text-center">
                                <h4 class="card-title">Gate Inward Register</h4>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn waves-effect waves-light btn-outline-primary float-right permission-write addNew press-add-btn" data-button="both" data-modal_id="modal-xl" data-function="addGateInward" data-form_title="Gate Inward"><i class="fa fa-plus"></i> Add GI</button>
                            </div>                             
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='giTable' class="table table-bordered ssTable" data-url='/getDTRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
