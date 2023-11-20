<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-7">
                                <h4 class="card-title pageHeader"><?=$pageHeader?></h4>
                            </div>
							<div class="col-md-5"> 
							    <div class="input-group ">
                                    <input type="hidden" id="report_type" value="<?=$report_type?>">
                                    <input type="hidden" id="hsn_code" value="<?=$hsn_code?>">
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
                                        <th>Vou Date</th>
                                        <th>Vou No.</th>
                                        <th>Vou Type</th>
                                        <th>Party Name</th>
                                        <th>Item Name</th>
                                        <th>HSN</th>
                                        <th>UQC</th>
                                        <th>GST Rate</th>
                                        <th>Total Qty</th>
                                        <th>Taxable Value</th>
                                        <th>IGST Amount</th>
                                        <th>CGST Amount</th>
                                        <th>SGST Amount</th>
                                        <th>Total Value</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyData"></tbody>
                                <tfoot  id="tfootData" class="thead-info">
                                    <tr>
                                        <th colspan="9" class="text-right">Total</th>
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
        var hsn_code = $("#hsn_code").val();
        var from_date = $('#from_date').val();
	    var to_date = $('#to_date').val();
        if($("#from_date").val() == ""){$(".fromDate").html("From Date is required.");valid=0;}
	    if($("#to_date").val() == ""){$(".toDate").html("To Date is required.");valid=0;}
	    if($("#to_date").val() < $("#from_date").val()){$(".toDate").html("Invalid Date.");valid=0;}

		if(valid){
            $.ajax({
                url: base_url + controller + '/getHsnDetailData',
                data: {report:report_type,hsn_code:hsn_code,from_date:from_date,to_date:to_date},
				type: "POST",
				dataType:'json',
				success:function(data){
                    $("#reportTable").DataTable().clear().destroy();
					$("#tbodyData").html(data.tbody);
					$("#tfootData").html(data.tfoot);
					reportTable();
                }
            });
        }
    });   
});
</script>