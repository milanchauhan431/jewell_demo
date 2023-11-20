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
                                        <button onclick="statusTab('purchaseRequestTable',0);" id="PurIndPending" class="nav-tab btn waves-effect waves-light btn-outline-info active" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending</button> 
                                    </li>
									<li class="nav-item"> 
                                        <button onclick="statusTab('purchaseRequestTable',2);" id="PurIndCompleted" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Completed</button> 
                                    </li>
									<li class="nav-item"> 
                                        <button onclick="statusTab('purchaseRequestTable',3);" id="PurIndClose" class="nav-tab btn waves-effect waves-light btn-outline-primary" style="outline:0px" data-toggle="tab" aria-expanded="false">Close</button> 
                                    </li>
								</ul>
							</div>
							<div class="col-md-4 text-center">
								<h4 class="card-title">Purchase Indent</h4>
							</div>
                            <div class="col-md-4">
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id='purchaseRequestTable' class="table table-bordered ssTable" data-url='/getDTRows'></table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('includes/footer'); ?>
<script>
$(document).ready(function(){
    initBulkCreateButton();
    $(document).on('click','.nav-tab, .nav-tab-refresh',function(){
        initBulkCreateButton();
    });
        
    $(document).on('click', '.BulkRequest', function() {
        if ($(this).attr('id') == "masterSelect") {
            if ($(this).prop('checked') == true) {
                $(".bulkPO").show();
                $("input[name='ref_id[]']").prop('checked', true);
            } else {
                $(".bulkPO").hide();
                $("input[name='ref_id[]']").prop('checked', false);
            }
        } else {
            var checkboxLength = $("input[name='ref_id[]']").length;
            var checkedLength = $("input[name='ref_id[]']:checked").length;

            if(checkedLength == 0){
                $("#masterSelect").prop('checked', false);
                $(".bulkPO").hide();
            }else{
                $(".bulkPO").show();
                if(checkedLength == checkboxLength){
                    $("#masterSelect").prop('checked', true);
                }else{
                    $("#masterSelect").prop('checked', false);
                }
            }
        }
    });

    $(document).on('click', '.bulkPO', function() {
        var ref_id = [];
        $("input[name='ref_id[]']:checked").each(function() {
            ref_id.push(this.value);
        });
        var ids = ref_id.join("~");
        var send_data = {
            ids
        };
        $.confirm({
            title: 'Confirm!',
            content: 'Are you sure want to generate PO?',
            type: 'red',
            buttons: {
                ok: {
                    text: "ok!",
                    btnClass: 'btn waves-effect waves-light btn-outline-success',
                    keys: ['enter'],
                    action: function() {
                        window.open(base_url + 'purchaseOrders/createOrder/' + ids, '_self');

                    }
                },
                cancel: {
                    btnClass: 'btn waves-effect waves-light btn-outline-secondary',
                    action: function() {

                    }
                }
            }
        });
    });
});

function initBulkCreateButton() {
    var bulkPOBtn = '<button class="btn btn-outline-primary bulkPO" tabindex="0" aria-controls="purchaseRequestTable" type="button"><span>Generate PO</span></button>';
    $("#purchaseRequestTable_wrapper .dt-buttons").append(bulkPOBtn);
    $(".bulkPO").hide();
}
</script>