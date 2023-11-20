<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="card-title pageHeader"><?=$pageHeader?></h4>
                            </div>
							<div class="col-md-6"> 
							    <div class="input-group ">
                                    <div class="input-group-append" style="width:30%;">
                                        <select id="report_type" name="report_type" class="form-control select2">
                                            <option value="Sale">Sales</option>
                                            <option value="Purc">Purchase</option>
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
                                        <th>HSN</th>
                                        <th>UQC</th>
                                        <th>Total Qty</th>
                                        <th>GST Rate</th>
                                        <th>Taxable Value</th>
                                        <th>IGST Amount</th>
                                        <th>CGST Amount</th>
                                        <th>SGST Amount</th>
                                        <th>Total Value</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyData"></tbody>
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
        var from_date = $('#from_date').val();
	    var to_date = $('#to_date').val();
        if($("#from_date").val() == ""){$(".fromDate").html("From Date is required.");valid=0;}
	    if($("#to_date").val() == ""){$(".toDate").html("To Date is required.");valid=0;}
	    if($("#to_date").val() < $("#from_date").val()){$(".toDate").html("Invalid Date.");valid=0;}

		if(valid){
            $.ajax({
                url: base_url + controller + '/getHsnSummaryData',
                data: {report:report_type,from_date:from_date,to_date:to_date},
				type: "POST",
				dataType:'json',
				success:function(data){
                    $("#reportTable").DataTable().clear().destroy();
					$("#tbodyData").html(data.tbody);
					reportTable();
                }
            });
        }
    });   
});
</script>