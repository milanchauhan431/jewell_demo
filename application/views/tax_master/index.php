<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="card-title">Tax Master</h4>
                            </div>
                            <div class="col-md-6">
                                <!-- <button type="button" class="btn waves-effect waves-light btn-outline-primary float-right addNew press-add-btn permission-write" data-button="both" data-modal_id="modal-lg" data-function="addTaxMaster" data-form_title="Add Tax"><i class="fa fa-plus"></i> Add Tax</button> -->
                            </div>                             
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='taxMasterTable' class="table table-bordered ssTable ssTable-cf" data-url='/getDTRows' ></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
