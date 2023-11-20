<?php $this->load->view('includes/header');?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                            <ul class="nav nav-pills">
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('leadTable',0,'getSalesDtHeader','lead');" id="pending_lead" class="nav-tab btn waves-effect waves-light btn-outline-primary active" style="outline:0px" data-toggle="tab" aria-expanded="false">Lead</button> 
                                    </li>

                                    <li class="nav-item"> 
                                        <button onclick="statusTab('leadTable',3,'getSalesDtHeader','lead_won');" id="lead_won" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Won</button> 
                                    </li>

                                    <li class="nav-item"> 
                                        <button onclick="statusTab('leadTable',4,'getSalesDtHeader','lead_lost');" id="lead_lost" class="nav-tab btn waves-effect waves-light btn-outline-dark" style="outline:0px" data-toggle="tab" aria-expanded="false">Lost</button> 
                                    </li>
                                </ul>
                            </div>
							<div class="col-md-4">
								<button type="button" class="btn waves-effect waves-light btn-outline-info float-right addNew press-add-btn permission-write" data-button="both" data-modal_id="modal-lg" data-function="addLead" data-form_title="Add Approach"><i class="fa fa-plus"></i> New Approach</button>
							</div>                            
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='leadTable' class="table table-bordered ssTable" data-url='/getDTRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer');?>