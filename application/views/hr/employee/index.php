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
                                        <button onclick="statusTab('employeeTable',0);" class=" btn waves-effect waves-light btn-outline-success active" style="outline:0px" data-toggle="tab" aria-expanded="false">Active</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('employeeTable',1);" class=" btn waves-effect waves-light btn-outline-danger" style="outline:0px" data-toggle="tab" aria-expanded="false">Inactive</button> 
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h4 class="card-title text-center">Employee</h4>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn waves-effect waves-light btn-outline-primary float-right addNew press-add-btn permission-write" data-button="both" data-modal_id="modal-xl" data-function="addEmployee" data-form_title="Add Employee"><i class="fa fa-plus"></i> Add Employee</button>
                            </div>                             
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='employeeTable' class="table table-bordered ssTable ssTable-cf bt-switch1" data-url="/getDTRows" data-ninput="[0,1]"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<div class="modal fade" id="print_dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog" style="min-width:30%;">
		<div class="modal-content animated zoomIn border-light">
			<div class="modal-header bg-light">
                <h5 class="modal-title text-dark"><i class="fa fa-print"></i> Print Experience Certificate</h5>
				<button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="printModel" method="post" action="<?=base_url($headData->controller.'/printExperienceCertificate')?>" target="_blank">
				<div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <input type="hidden" name="id" id="id" value="" />
                        <div class="col-md-12 form-group">
                            <label for="new_exp_responsibilities">Experience Responsibilities </label>
                            <textarea name="new_exp_responsibilities" class="form-control" style="resize:none;" rows="3" value="" ></textarea>
                        </div>
                     </div>
                    </div>
				</div>
				<div class="modal-footer">
					<a href="#" data-dismiss="modal" class="btn btn-secondary"><i class="fa fa-times"></i> Close</a>
					<button type="submit" class="btn btn-success" onclick="closeModal('print_dialog');"><i class="fa fa-print"></i> Print</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php $this->load->view('includes/footer'); ?>
<script src="<?php echo base_url();?>assets/js/custom/employee.js?v=<?=time()?>"></script>

<script>
$(document).ready(function(){
	
	// Check For Employee is under child act or not
    $(document).on('change','#emp_birthdate',function(){
        var dob = new Date($(this).val());
        var today = new Date();
        var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
        $('#age').html(age+' years old');
        //console.log(dob + " & " + today + " = " + age);
        if (age < 18) {
            $(".emp_birthdate").html("Under Child Labour Act");
        }
        else{ $(".emp_birthdate").html(""); }
    });

    <?php if(!empty($printID)): ?>
        $("#printModel").attr('action',base_url + controller + '/printExperienceCertificate');
        $("#printsid").val(<?=$printID?>);
        $("#print_dialog").modal();
    <?php endif; ?>

    $(document).on("click",".printCertificate",function(){
        $("#printModel").attr('action',base_url + controller + '/printExperienceCertificate');
        $("#id").val($(this).data('id'));
        $("#print_dialog").modal();
    });		
});

/* function closeModal(modalId){
    $("#"+ modalId).modal('hide');
    
    <?php if(!empty($printID)): ?>
        window.location = base_url + controller;
    <?php endif; ?>
} */		
</script>