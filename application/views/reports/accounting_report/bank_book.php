<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-5">
                                <h4 class="card-title pageHeader"><?=$pageHeader?></h4>
                            </div>       
							<div class="col-md-3">   
                                <input type="date" name="from_date" id="from_date" class="form-control" value="<?=$startDate?>" />
                                <div class="error fromDate"></div>
                            </div>     
                            <div class="col-md-4">  
                                <div class="input-group">
                                    <input type="date" name="to_date" id="to_date" class="form-control" value="<?=$endDate?>" />
                                    <div class="input-group-append ml-2">
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
                            <table id='commanTable' class="table table-bordered">
								<thead class="thead-info" id="theadData">
                                    <tr class="text-center"><th colspan="12"><?=$pageHeader?></th></tr>
									<tr>
                                        <th>#</th>
										<th class="text-left">Bank Name</th>
										<th class="text-left">Group Name</th>
										<th class="text-rigth">Opening Amount</th>
										<th class="text-rigth">Credit Amount</th>
										<th class="text-rigth">Debit Amount</th>
										<th class="text-rigth">Closing Amount</th>
										<th class="text-rigth">As Per Bank<br>Closing Amount</th>
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
var tableOption = {
    responsive: true,
    "scrollY": '52vh',
    "scrollX": true,
    deferRender: true,
    scroller: true,
    destroy: true,
    "autoWidth" : false,
    order: [],
    "columnDefs": [
        {type: 'natural',targets: 0},
        {orderable: false,targets: "_all"},
        {className: "text-center",targets: [0, 1]},
        {className: "text-center","targets": "_all"}
    ],
    pageLength: 25,
    language: {search: ""},
    lengthMenu: [
        [ 10, 20, 25, 50, 75, 100, 250,500 ],
        [ '10 rows', '20 rows', '25 rows', '50 rows', '75 rows', '100 rows','250 rows','500 rows' ]
    ],
    dom: "<'row'<'col-sm-7'B><'col-sm-5'f>>" + "<'row'<'col-sm-12't>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: ['pageLength', 'excel',{ text: 'Pdf',action: function () {loadData('pdf'); } } , {text: 'Refresh',action: function (){ $(".refreshReportData").trigger('click'); } }],
    "fnInitComplete":function(){ $('.dataTables_scrollBody').perfectScrollbar(); },
    "fnDrawCallback": function() { $('.dataTables_scrollBody').perfectScrollbar('destroy').perfectScrollbar(); }
};

$(document).ready(function(){
	loadData();
    $(document).on('click','.loadData',function(){
		loadData();
	});  
});

function loadData(pdf=""){
	$(".error").html("");
	var valid = 1;
	var from_date = $('#from_date').val();
	var to_date = $('#to_date').val();
	if($("#from_date").val() == ""){$(".fromDate").html("From Date is required.");valid=0;}
	if($("#to_date").val() == ""){$(".toDate").html("To Date is required.");valid=0;}
	if($("#to_date").val() < $("#from_date").val()){$(".toDate").html("Invalid Date.");valid=0;}

	var postData = {from_date:from_date,to_date:to_date,group_code:"'BA','BOL','BOA'",pdf:pdf};
	if(valid){
		if(pdf == ""){
			$.ajax({
				url: base_url + controller + '/getBankBookData',
				data: postData,
				type: "POST",
				dataType:'json',
				success:function(data){
					$("#commanTable").DataTable().clear().destroy();
					/* $("#theadData").html("");
                    $("#theadData").html(data.thead); */
					$("#tbodyData").html("");
					$("#tbodyData").html(data.tbody);
					/* $("#tfootData").html("");
					$("#tfootData").html(data.tfoot); */
					reportTable('commanTable',tableOption);
				}
			});
		}else{
			var url = base_url + controller + '/getBankBookData/' + encodeURIComponent(window.btoa(JSON.stringify(postData)));
			window.open(url);
		}

	}
}
</script>