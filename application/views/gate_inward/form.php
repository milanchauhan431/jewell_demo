<form>
    <div class="col-md-12">
        <div class="row">

            <input type="hidden" name="id" id="id" value="<?=(!empty($gateInwardData->id))?$gateInwardData->id:""?>">
            <input type="hidden" name="ref_id" id="ref_id" value="<?=(!empty($gateInwardData->ref_id))?$gateInwardData->ref_id:((!empty($gateEntryData->id))?$gateEntryData->id:"")?>">
            <input type="hidden" name="trans_prefix" id="trans_prefix" value="<?=(!empty($gateInwardData->trans_prefix))?$gateInwardData->trans_prefix:$trans_prefix?>">
            <input type="hidden" name="trans_no" id="trans_no" value="<?=(!empty($gateInwardData->trans_no))?$gateInwardData->trans_no:$trans_no?>">

            <div class="col-md-3 form-group">
                <label for="trans_no">GI No.</label>
                <input type="text" name="trans_number" id="trans_number" class="form-control" value="<?=(!empty($gateInwardData->trans_number))?$gateInwardData->trans_number:$trans_number?>" readonly>
            </div>

            <div class="col-md-3 form-group">
                <label for="trans_date">GI Date</label>
                <input type="datetime-local" name="trans_date" id="trans_date" class="form-control" value="<?=(!empty($gateInwardData->trans_date))?$gateInwardData->trans_date:getFyDate("Y-m-d H:i:s")?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="party_id">Party Name</label>
                <select name="party_id" id="party_id" class="form-control select2">
                    <option value="">Select Party Name</option>
                    <?=getPartyListOption($partyList,((!empty($gateInwardData->party_id))?$gateInwardData->party_id:((!empty($gateEntryData->party_id))?$gateEntryData->party_id:"")))?>
                </select>                
            </div>

            <div class="col-md-3 form-group">
                <label for="inv_no">Invoice No.</label>
                <input type="text" name="inv_no" id="inv_no" class="form-control req text-uppercase" value="<?=(!empty($gateInwardData->inv_no))?$gateInwardData->inv_no:((!empty($gateEntryData->inv_no))?$gateEntryData->inv_no:"")?>">
            </div>

            <div class="col-md-3 form-group">
                <label for="inv_date">Invoice Date</label>
                <input type="date" name="inv_date" id="inv_date" class="form-control req" value="<?=(!empty($gateInwardData->inv_date))?$gateInwardData->inv_date:((!empty($gateEntryData->inv_date))?$gateEntryData->inv_date:"")?>" >
            </div>

            <div class="col-md-3 form-group">
                <label for="doc_no">Challan No.</label>
                <input type="text" name="doc_no" id="doc_no" class="form-control req text-uppercase" value="<?=(!empty($gateInwardData->doc_no))?$gateInwardData->doc_no:((!empty($gateEntryData->doc_no))?$gateEntryData->doc_no:"")?>">
            </div>

            <div class="col-md-3 form-group">
                <label for="doc_date">Challan Date</label>
                <input type="date" name="doc_date" id="doc_date" class="form-control req" value="<?=(!empty($gateInwardData->doc_date))?$gateInwardData->doc_date:((!empty($gateEntryData->doc_date))?$gateEntryData->doc_date:"")?>">
            </div>

            <div class="col-md-3 form-group">
                <!-- <label for="po_trans_id">Purchase Order</label>
                <select id="po_trans_id" class="form-control select2">
                    <option value="">Select Purchase Order</option>
                </select>
                <div class="error po_trans_id"></div>
                <input type="hidden" id="po_id" value=""> -->
                <label for="po_id">Purchase Order</label>
                <select id="po_id" class="form-control select2">
                    <option value="">Select Purchase Order</option>
                </select>
                <div class="error po_id"></div>
                <input type="hidden" id="po_trans_id" value="">
            </div>

            <div class="col-md-3 form-group">
                <label for="item_id">Item Name</label>
                <select id="item_id" class="form-control itemDetails select2 req" data-res_function="resItemDetail">
                    <option value="">Select Item Name</option>
                    <?=getItemListOption($itemList)?>
                </select>

                <input type="hidden" id="item_stock_type" value="">
                <input type="hidden" id="item_type" value="">
            </div>            

            <div class="col-md-2 form-group">
                <label for="qty">Qty</label>
                <input type="text" id="qty" class="form-control floatOnly req" value="">
            </div>

            <div class="col-md-2 form-group">
                <label for="disc_per">Disc. (%)</label>
                <input type="text" id="disc_per" class="form-control" value="">
            </div>

            <div class="col-md-2 form-group">
                <label for="price">Price</label>
                <input type="text" id="price" class="form-control floatVal" value="">
            </div>

            <!-- <div class="col-md-4 form-group">
                <label for="location_id">Location</label>
                <select id="location_id" class="form-control model-select2">
                    <option value="">Select Location</option>
                    <?php
                        /* if(!empty($locationList)):
                            echo getLocationListOption($locationList);
                        endif; */
                    ?>
                </select>
                <div class="error location_id"></div>
            </div> -->

            <input type="hidden" id="heat_no" class="form-control" value="">
            <!-- <div class="col-md-4 form-group" >
                <label for="heat_no">Heat No.</label>
                <input type="text" id="heat_no" class="form-control" value="">
                <div class="error heat_no"></div>
            </div> -->

            <input type="hidden" id="mill_heat_no" class="form-control" value="">
            <!-- <div class="col-md-4 form-group" >
                <label for="mill_heat_no">Mill Heat No.</label>
                <input type="text" id="mill_heat_no" class="form-control" value="">
                <div class="error mill_heat_no"></div>
            </div> -->

        </div>

        <hr>

        <div class="row">

            <div class="col-md-12 row" id="palateDivs"></div>

            <div class="col-md-12 form-group">
                <button type="button" class="btn btn-outline-info float-right addBatch"><i class="fa fa-plus"></i> Add</button>
            </div>

        </div>

        <hr>

        <div class="row">
            <div class="error batch_details"></div>
            <div class="table-responsive">
                <table id="batchTable" class="table table-bordered">
                    <thead class="thead-info">
                        <tr>
                            <th>#</th>
                            <th>PO No</th>
                            <th style="width:30%;">Item Name</th>
                            <!-- <th>Location</th>
                            <th>Batch NO</th> -->
                            <!-- <th>Heat No</th>
                            <th>Mill Heat No</th> -->
                            <th>Qty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="batchData">                            
                        <tr id="noData">
                            <td class="text-center" colspan="9">No data available in table</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
<script src="<?php echo base_url();?>assets/js/custom/gate-inward-form.js?v=<?=time()?>"></script>
<?php
    if(!empty($gateInwardData->itemData)):
        foreach($gateInwardData->itemData as $row):
            echo "<script>AddBatchRow(".json_encode($row).");</script>";
        endforeach;
    endif;
?>