<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4">
                                <h4 class="card-title pageHeader"><?=$pageHeader?></h4>
                            </div>
							<div class="col-md-8"> 
							    <div class="input-group ">
                                    <div class="input-group-append" style="width:20%;">
                                        <select id="report_type" name="report_type" class="form-control select2">
                                            <option value="1">Bill Wise</option>
                                            <option value="2">Item Wise</option>
                                        </select>
                                    </div>
                                    <div class="input-group-append" style="width:20%;">
                                        <select id="state_code" name="state_code" class="form-control select2">
                                            <option value="">All States</option>
                                            <option value="1">IntraState</option>
                                            <option value="2">InterState</option>
                                        </select>
                                    </div>
                                    <input type="date" name="from_date" id="from_date" class="form-control" value="<?=$startDate?>" />
                                    <div class="error fromDate"></div>
                                    <input type="date" name="to_date" id="to_date" class="form-control" value="<?=$endDate?>" />
                                    <div class="input-group-append">
                                        <button type="button" class="btn waves-effect waves-light btn-success float-right refreshReportData loadData" title="Load Data">
									        <i class="fas fa-sync-alt"></i> Load
								        </button>
                                    </div>
                                </div>
                                <div class="error toDate"></div>
                            </div>                
                        </div>                                         
                    </div>
                    <div class="card-body reportDiv" style="min-height:75vh">
                        <div class="table-responsive">
                            <table id='reportTable' class="table table-bordered">
								<thead id="theadData" class="thead-info">
                                    <tr>
                                        <th>#</th>
                                        <th>Inv Date</th>
                                        <th>Inv No.</th>
                                        <th>Party Name</th>
                                        <th>Gst No.</th>
                                        <th>Total Amount</th>
                                        <th>Disc. Amount</th>
                                        <th>Taxable Amount</th>
                                        <th>CGST Amount</th>
                                        <th>SGST Amount</th>
                                        <th>IGST Amount</th>
                                        <th>Other Amount</th>
                                        <th>Net Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyData"></tbody>
                                <tfoot id="tfootData" class="thead-info">
                                    <tr>
                                        <th colspan="5" class="text-right">Total</th>
                                        <th>0</th>
                                        <th>0</th>
                                        <th>0</th>
                                        <th>0</th>
                                        <th>0</th>
                                        <th>0</th>
                                        <th>0</th>
                                        <th>0</th>
                                    </tr>
                                </tfoot>
							</table>
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
	reportTable();
    setTimeout(function(){$(".loadData").trigger('click');},500);
    
    $(document).on('click','.loadData',function(e){
		$(".error").html("");
		var valid = 1;
        var report_type = $("#report_type").val();
		var state_code = $('#state_code').val();
        var from_date = $('#from_date').val();
	    var to_date = $('#to_date').val();
        if($("#from_date").val() == ""){$(".fromDate").html("From Date is required.");valid=0;}
	    if($("#to_date").val() == ""){$(".toDate").html("To Date is required.");valid=0;}
	    if($("#to_date").val() < $("#from_date").val()){$(".toDate").html("Invalid Date.");valid=0;}

		if(valid){
            $.ajax({
                url: base_url + controller + '/getSalesRegisterData',
                data: {report_type:report_type,state_code:state_code,from_date:from_date,to_date:to_date,vou_name_s:["'Sale'"]},
				type: "POST",
				dataType:'json',
				success:function(data){
                    $("#reportTable").DataTable().clear().destroy();
					$("#theadData").html(data.thead);
					$("#tbodyData").html(data.tbody);
					$("#tfootData").html(data.tfoot);
					reportTable();
                }
            });
        }
    });   
});
</script>