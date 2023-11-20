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
                                        <button onclick="statusTab('estimationTable',0);" id="pending_ed" class="nav-tab btn waves-effect waves-light btn-outline-danger active" style="outline:0px" data-toggle="tab" aria-expanded="false">New Job</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('estimationTable',1);" id="list_ed" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Job List</button> 
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 text-center">
                                <h4 class="card-title">Estimation & Design</h4>
                            </div>
                            <div class="col-md-4">
                            </div>                             
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='estimationTable' class="table table-bordered ssTable" data-url='/getEstimationDTRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>