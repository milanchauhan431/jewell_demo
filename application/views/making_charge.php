<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="card-title">Update Charges</h4>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div style="width:50%;">
                                        <select name="party_id" id="party_id" class="form-control select2">
                                            <option value="">Select Party</option>
                                            <?=getPartyListOption($partyList)?>
                                        </select>
                                    </div>
                                    <input type="date" id="ref_date" class="form-control fyDates" value="">
                                    <div class="input-group-append">
                                        <button type="button" class="btn waves-effect waves-light btn-success float-right loadData" title="Load Data">
                                            <i class="fas fa-sync-alt"></i> Load
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="addCharge">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="table table-responsive">
                                        <table id="inwardCharge" class="table table-bordered">
                                            <thead class="thead-info">
                                                <tr>
                                                    <th style="width:5%;">#</th>
                                                    <th>Item Name</th>
                                                    <th>Ref</th>
                                                    <th>Qty.</th>
                                                    <th>G.W.</th>
                                                    <th>N.W.</th>
                                                    <th>Unit</th>
                                                    <th>Purchase Price</th>
                                                    <th>Making Charge(%)</th>
                                                    <th>Making Charge Discount(%)</th>
                                                    <th>Other Charge</th>
                                                    <th>Verity Charge</th>
                                                    <th>Diamond Amount </th> 
                                                </tr>
                                            </thead>
                                            <tbody id="tempItem">
                                                <tr><td colspan="13" class="text-center">No data available in table</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn waves-effect waves-light btn-outline-success btn-save float-right save-form permission-write" onclick="customStore({'formId':'addCharge','fnsave':'save'});"><i class="fa fa-check"></i> Save</button>
                            </div>
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
    var inwardTrans = {'postData':{'ref_date':$("#ref_date").val()},'table_id':"inwardCharge",'tbody_id':'tempItem','tfoot_id':'','fnget':'getApprovedInwardList'};
    getTransHtml(inwardTrans);

    $(document).on('click','.loadData',function(){
        var inwardTrans = {'postData':{'ref_date':$("#ref_date").val(), 'party_id':$("#party_id").val()},'table_id':"inwardCharge",'tbody_id':'tempItem','tfoot_id':'','fnget':'getApprovedInwardList'};
        getTransHtml(inwardTrans);
    });
});
</script>