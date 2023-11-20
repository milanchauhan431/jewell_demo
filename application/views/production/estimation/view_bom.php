<div class="col-md-12">
    <input type="hidden" id="trans_child_id" value="<?=$postData->trans_child_id?>">
    <div class="row">
        <div class="table-responsive">
            <table id="bomItems" class="table table-bordered">
                <thead class="thead-info">
                    <tr>
                        <th>#</th>
                        <th>Material Description</th>
                        <th>Make</th>
                        <th>Cat. No.</th>
                        <th>UOM</th>
                        <th>Total Qty.</th>
                        <th>OTHER MRP</th>
                        <th>OTHER AMOUNT</th>
                        <th>DISC (IN %)</th>
                        <th>FINAL OTHER AMOUNT</th>
                        <th class="text-center">Action</th>
                    </tr>          
                </thead>
                <tbody id="bomItemsDetails">
                    <tr><td colspan="11" class="text-center">No data available in table</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
var bomTrans = {'postData':{'trans_child_id':$("#trans_child_id").val()},'table_id':"bomItems",'tbody_id':'bomItemsDetails','tfoot_id':'','fnget':'getOrderBomHtml'};
getTransHtml(bomTrans);

function resDeleteBomItem(response){
    if(response.status==0){
        toastr.error(response.message, 'Sorry...!', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
    }else{
        var bomTrans = {'postData':{'trans_child_id':$("#trans_child_id").val()},'table_id':"bomItems",'tbody_id':'bomItemsDetails','tfoot_id':'','fnget':'getOrderBomHtml'};
        getTransHtml(bomTrans);

        toastr.success(response.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
    }	
}
</script>