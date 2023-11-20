<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
	<div class="container-fluid bg-container">
		<div class="row"> 
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<div class="row">
							<div class="col-md-3"><h4><?=$pageHeader?></h4></div>
							<div class="col-md-9">
                                <form enctype="multipart/form-data">
								<div class="input-group">		
									<div class="input-group-append"  style="width:35%;">
										<div class="custom-file">
											<input type="file" class="custom-file-input" name="json_file" id="json_file" accept=".json">
											<label class="custom-file-label" for="json_file">Choose file</label>
										</div>
									</div>
                                    <select name="match_status" id="match_status" class="form-control">
                                        <option value="0">ALL</option>
                                        <option value="1">Matched</option>
                                        <option value="2">Unmatched</option>
                                    </select>						
									<input type="date" name="from_date" id="from_date" class="form-control" value="<?= $startDate ?>" />
									<div class="error fromDate"></div>
									<input type="date" name="to_date" id="to_date" class="form-control" value="<?= $endDate ?>" />
									<div class="input-group-append">
										<button type="button" class="btn waves-effect waves-light btn-success float-right " onclick="loadData(this.form,'VIEW')" title="Load Data">
											<i class="fas fa-sync-alt"></i> Load
										</button>
									</div>
								</div>
								<div class="error toDate"></div>
                                </form>
							</div>
						</div>
					</div>
					<div class="card-body reportDiv" style="min-height:75vh">
						<div class="table-responsive">
							<table id='gstr2bTable' class="table table-bordered jpDataTable">
                                <thead class="thead-info">
                                    <tr>
                                        <th>Status</th>
                                        <th>Section</th>
                                        <th>GSTIN</th>
                                        <th>Party Name</th>
                                        <th>INV. No.</th>
                                        <th>INV Date</th>
                                        <th>Invoice Value</th>
                                        <th>Revarse Charge</th>
                                        <th>POS</th>
                                        <th>Invoice Type</th>
                                        <th>Taxable Value</th>
                                        <th>Rate</th>
                                        <th>CGST</th>
                                        <th>SGST</th>
                                        <th>IGST</th>
                                        <th>CESS</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
reportTable('gstr2bTable');
function loadData(form,file_type = "VIEW") {
	$(".error").html("");
	var valid = 1;
	var fd = new FormData(form);
    $.ajax({
        url: base_url + controller + '/matchGstr2bData',
        data: fd,
        type: "POST",
        processData: false,
		contentType: false,
        dataType: 'json',
        success: function(data) {	
            if(data.status===0){
                $(".error").html("");
                $.each( data.message, function( key, value ) {$("."+key).html(value);});
		    }else{
                $("#gstr2bTable").DataTable().clear().destroy();

                $("#gstr2bTable").html("");
                $("#gstr2bTable").html(data.tableData);

                reportTable('gstr2bTable');
            }
            
        }
    });		
}

function reportTable(tableId) {
	var reportTable = $('#'+tableId).DataTable({
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
		pageLength: -1,
		language: {search: ""},
		/* lengthMenu: [
			[ 10, 20, 25, 50, 75, 100, 250,-1 ],
			[ '10 rows', '20 rows', '25 rows', '50 rows', '75 rows', '100 rows','250 rows','Show All' ]
		], */
		dom: "<'row'<'col-sm-7'B><'col-sm-5'f>>" + "<'row'<'col-sm-12't>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: ['excel'],
		"fnInitComplete":function(){
		    $('.dataTables_scrollBody').perfectScrollbar();
		    $("#"+tableId+" tbody tr").each(function () {
                $('td', this).each(function (i,v) {
                    var td = $(this);
                    var styleProps = $("#"+tableId+" thead tr th:eq("+i+")").css(["text-align"]);
                    $.each( styleProps, function( prop, value ){td.css( prop, value ); });
                 });
            });
		},
	    "fnDrawCallback": function( oSettings ) {$('.dataTables_scrollBody').perfectScrollbar('destroy').perfectScrollbar();}
	});
	reportTable.buttons().container().appendTo('#'+tableId+'_wrapper toolbar');
	$('.dataTables_filter .form-control-sm').css("width", "97%");
	$('.dataTables_filter .form-control-sm').attr("placeholder", "Search.....");
	$('.dataTables_filter').css("text-align", "left");
	$('.dataTables_filter label').css("display", "block");
	$('.btn-group>.btn:first-child').css("border-top-right-radius", "0");
	$('.btn-group>.btn:first-child').css("border-bottom-right-radius", "0");
	setTimeout(function(){ reportTable.columns.adjust().draw();}, 550);
	//$('.page-wrapper').resizer(function() {jpReportTable.columns.adjust().draw(); });
	return reportTable;
}
</script>