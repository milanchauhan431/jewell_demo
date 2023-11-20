<?php $this->load->view('includes/header'); ?>

<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h4><u>Purchase Order</u></h4>
                    </div>
                    <div class="card-body">
                        <form autocomplete="off" id="saveSalesOrder" data-res_function="resSaveOrder" enctype="multipart/form-data">
                            <div class="col-md-12">
                                <div class="row">

                                    <div class="hiddenInput">
                                        <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
                                        <input type="hidden" name="entry_type" id="entry_type" value="<?=(!empty($dataRow->entry_type))?$dataRow->entry_type:$entry_type?>">
                                        <input type="hidden" name="from_entry_type" id="from_entry_type" value="<?=(!empty($dataRow->from_entry_type))?$dataRow->from_entry_type:((!empty($from_entry_type))?$from_entry_type:"")?>">
                                        <input type="hidden" name="ref_id" id="ref_id" value="<?=(!empty($dataRow->ref_id))?$dataRow->ref_id:((!empty($ref_id))?$ref_id:"")?>">

                                        <input type="hidden" name="trans_prefix" id="trans_prefix" value="<?=(!empty($dataRow->trans_prefix))?$dataRow->trans_prefix:((!empty($trans_prefix))?$trans_prefix:"")?>">
                                        <input type="hidden" name="trans_no" id="trans_no" value="<?=(!empty($dataRow->trans_no))?$dataRow->trans_no:((!empty($trans_no))?$trans_no:"")?>">

                                        <input type="hidden" name="party_name" id="party_name" value="<?=(!empty($dataRow->party_name))?$dataRow->party_name:""?>">
                                        <input type="hidden" name="gst_type" id="gst_type" value="<?=(!empty($dataRow->gst_type))?$dataRow->gst_type:""?>">
                                        <input type="hidden" name="party_state_code" id="party_state_code" value="<?=(!empty($dataRow->party_state_code))?$dataRow->party_state_code:""?>">
                                        <input type="hidden" name="apply_round" id="apply_round" value="<?=(!empty($dataRow->apply_round))?$dataRow->apply_round:"1"?>">

                                        <input type="hidden" name="ledger_eff" id="ledger_eff" value="0">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label for="trans_number">PO. No.</label>
                                        <input type="text" name="trans_number" id="trans_number" class="form-control" value="<?=(!empty($dataRow->trans_number))?$dataRow->trans_number:((!empty($trans_number))?$trans_number:"")?>" readonly>
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label for="trans_date">PO. Date</label>
                                        <input type="date" name="trans_date" id="trans_date" class="form-control" value="<?=(!empty($dataRow->trans_date))?$dataRow->trans_date:getFyDate()?>">
                                    </div>

                                    <div class="col-md-5 form-group">
                                        <label for="party_id">Party Name</label>
                                        <div class="float-right">	
											<span class="dropdown float-right">
												<a class="text-primary font-bold waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" datatip="Progress" flow="down">+ Add New</a>

												<div class="dropdown-menu dropdown-menu-left user-dd animated flipInY" x-placement="start-left">
													<div class="d-flex no-block align-items-center p-10 bg-primary text-white">ACTION</div>

                                                    <!-- <a class="dropdown-item addNew" href="javascript:void(0)" data-button="both" data-modal_id="modal-xl" data-function="addParty" data-controller="parties" data-postdata='{"party_category" : 1 }' data-res_function="resPartyMaster" data-js_store_fn="customStore" data-form_title="Add Customer">+ Customer</a> -->
													
													<a class="dropdown-item addNew" href="javascript:void(0)" data-button="both" data-modal_id="modal-xl" data-function="addParty" data-controller="parties" data-postdata='{"party_category" : 2 }' data-res_function="resPartyMaster" data-js_store_fn="customStore" data-form_title="Add Supplier">+ Supplier</a>

                                                    <a class="dropdown-item addNew" href="javascript:void(0)" data-button="both" data-modal_id="modal-xl" data-function="addParty" data-controller="parties" data-postdata='{"party_category" : 3 }' data-res_function="resPartyMaster" data-js_store_fn="customStore" data-form_title="Add Vendor">+ Vendor</a>
													
												</div>
											</span>
										</div>
                                        <select name="party_id" id="party_id" class="form-control select2 partyDetails partyOptions req" data-res_function="resPartyDetail">
											<option value="">Select Party</option>
											<?=getPartyListOption($partyList,((!empty($dataRow->party_id))?$dataRow->party_id:0))?>
										</select>

                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="gstin">GST NO.</label>
                                        <select name="gstin" id="gstin" class="form-control select2">
                                            <option value="">Select GST No.</option>
                                            <?php
                                                if(!empty($dataRow->party_id)):
                                                    foreach($gstinList as $row):
                                                        $selected = ($dataRow->gstin == $row->gstin)?"selected":"";
                                                        echo '<option value="'.$row->gstin.'" '.$selected.'>'.$row->gstin.'</option>';
                                                    endforeach;

                                                    //echo '<option value="URP" '.(($dataRow->gstin == 'URP')?"selected":"").'>URP</option>';
                                                endif;
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="master_i_col_1">Transport Name</label>
                                        <select name="masterDetails[i_col_1]" id="master_i_col_1" class="form-control select2">
                                            <option value="">Select Transport</option>
                                            <?php
                                                foreach($transportList as $row):
                                                    $selected = (!empty($dataRow->transport_id) && $dataRow->transport_id == $row->id)?"selected":"";
                                                    echo '<option value="'.$row->id.'" '.$selected.'>'.$row->transport_name.'</option>';
                                                endforeach;
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="master_t_col_1">Contact Person</label>
                                        <input type="text" name="masterDetails[t_col_1]" id="master_t_col_1" class="form-control" value="<?=(!empty($dataRow->contact_person))?$dataRow->contact_person:""?>">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="master_t_col_2">Contact No.</label>
                                        <input type="text" name="masterDetails[t_col_2]" id="master_t_col_2" class="form-control numericOnly" value="<?=(!empty($dataRow->contact_no))?$dataRow->contact_no:""?>">
                                    </div>

                                    <div class="col-md-12 form-group">
                                        <label for="master_t_col_3">Delivery Address</label>
                                        <input type="text" name="masterDetails[t_col_3]" id="master_t_col_3" class="form-control" value="<?=(!empty($dataRow->delivery_address))?$dataRow->delivery_address:""?>">
                                    </div>
                                </div>

                                <hr>

                                <div class="col-md-12 row">
                                    <div class="col-md-6"><h4>Item Details : </h4></div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-outline-success waves-effect float-right add-item"><i class="fa fa-plus"></i> Add Item</button>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <div class="error itemData"></div>
                                    <div class="row form-group">
                                        <div class="table-responsive">
                                            <table id="purchaseOrderItems" class="table table-striped table-borderless">
                                                <thead class="thead-info">
                                                    <tr>
                                                        <th style="width:5%;">#</th>
                                                        <th>Item Name</th>
                                                        <th>HSN Code</th>
                                                        <th>Qty.</th>
                                                        <th>Unit</th>
                                                        <th>Price</th>
                                                        <th>Disc.</th>
                                                        <th class="igstCol">IGST</th>
                                                        <th class="cgstCol">CGST</th>
                                                        <th class="sgstCol">SGST</th>
                                                        <th class="amountCol">Amount</th>
                                                        <th class="netAmtCol">Amount</th>
                                                        <th>Remark</th>
                                                        <th class="text-center" style="width:10%;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tempItem" class="temp_item">
                                                    <tr id="noData">
                                                        <td colspan="14" class="text-center">No data available in table</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>

                                <?php $this->load->view('includes/tax_summary',['expenseList'=>$expenseList,'taxList'=>$taxList,'ledgerList'=>array(),'dataRow'=>((!empty($dataRow))?$dataRow:array())])?>

                                <hr>

                                <div class="row">                                    
                                    <div class="col-md-9 form-group">
                                        <label for="remark">Remark</label>
                                        <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="">&nbsp;</label>
                                        <button type="button" class="btn btn-outline-success waves-effect btn-block" data-toggle="modal" data-target="#termModel">Terms & Conditions (<span id="termsCounter">0</span>)</button>
                                        <div class="error term_id"></div>
                                    </div>
                                    <?php $this->load->view('includes/terms_form',['termsList'=>$termsList,'termsConditions'=>(!empty($dataRow->termsConditions)) ? $dataRow->termsConditions : array()])?>    
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="col-md-12">
                            <button type="button" class="btn waves-effect waves-light btn-outline-success float-right save-form" onclick="customStore({'formId':'saveSalesOrder'});" ><i class="fa fa-check"></i> Save</button>
                            <a href="javascript:void(0)" onclick="window.location.href='<?=base_url($headData->controller)?>'" class="btn waves-effect waves-light btn-outline-secondary float-right btn-close  press-close-btn save-form" style="margin-right:10px;"><i class="fa fa-times"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<div class="modal fade" id="itemModel" role="dialog" aria-labelledby="exampleModalLabel1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content animated slideDown">
            <div class="modal-header" style="display:block;"><h4 class="modal-title">Add or Update Item</h4></div>
            <div class="modal-body">
                <form id="itemForm">
                    <div class="col-md-12">

                        <div class="row form-group">
							<div id="itemInputs">
								<input type="hidden" name="id" id="id" value="" />
								<input type="hidden" name="from_entry_type" id="from_entry_type" value="" />
                                <input type="hidden" name="ref_id" id="ref_id" value=""  />
                                
								<input type="hidden" name="row_index" id="row_index" value="">
								<input type="hidden" name="item_code" id="item_code" value="" />
                                <input type="hidden" name="item_name" id="item_name" value="" />
                                <input type="hidden" name="item_type" id="item_type" value="" />
                            </div>
                            

                            <div class="col-md-12 form-group">
								<label for="item_id">Product Name</label>
                                <select name="item_id" id="item_id" class="form-control select2 itemDetails itemOptions" data-res_function="resItemDetail">
                                    <option value="">Select Product Name</option>
                                    <?=getItemListOption($itemList)?>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="qty">Quantity</label>
                                <input type="text" name="qty" id="qty" class="form-control floatOnly req" value="0">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="disc_per">Disc. (%)</label>
                                <input type="text" name="disc_per" id="disc_per" class="form-control floatOnly" value="0">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="price">Price</label>
                                <input type="text" name="price" id="price" class="form-control floatOnly req" value="0" />
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="unit_id">Unit</label>
                                <select name="unit_id" id="unit_id" class="form-control select2 req">
                                    <option value="0" selected>--</option>
                                    <?=getItemUnitListOption($unitList)?>
                                </select>
                                <input type="hidden" name="unit_name" id="unit_name" value="" />
                            </div>
							<div class="col-md-4 form-group">
                                <label for="hsn_code">HSN Code</label>
                                <select name="hsn_code" id="hsn_code" class="form-control select2">
                                    <option value="">Select HSN Code</option>
                                    <?=getHsnCodeListOption($hsnList)?>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="gst_per">GST Per.(%)</label>
                                <select name="gst_per" id="gst_per" class="form-control select2">
                                    <?php
                                        foreach($this->gstPer as $per=>$text):
                                            echo '<option value="'.$per.'">'.$text.'</option>';
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 form-group hidden">
                                <label for="make">Make</label>
                                <input type="text" name="make" id="make" class="form-control" value="" />
                            </div>  
                            <div class="col-md-12 form-group">
                                <label for="item_remark">Remark</label>
                                <input type="text" name="item_remark" id="item_remark" class="form-control" value="" />
                            </div>                            
                        </div>
                    </div> 
                         
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn waves-effect waves-light btn-outline-success saveItem btn-save" data-fn="save"><i class="fa fa-check"></i> Save</button>
                <button type="button" class="btn waves-effect waves-light btn-outline-warning saveItem btn-save-close" data-fn="save_close"><i class="fa fa-check"></i> Save & Close</button>
                <button type="button" class="btn waves-effect waves-light btn-outline-secondary btn-close" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('includes/footer'); ?>
<script src="<?php echo base_url(); ?>assets/js/custom/purchase-order-form.js?v=<?= time() ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/custom/calculate.js?v=<?= time() ?>"></script>

<?php
if(!empty($dataRow->itemList)):
    foreach($dataRow->itemList as $row):
        $row->row_index = "";
        $row->gst_per = floatVal($row->gst_per);
        echo '<script>AddRow('.json_encode($row).');</script>';
    endforeach;
endif;

if(!empty($orderItemList)):
    foreach($orderItemList as $row):
        $row->row_index = "";
        $row->ref_id = $row->id;
        $row->id = "";
        $row->qty = $row->req_qty;
        $row->amount = round(($row->qty * $row->price),2);
        if(!empty($row->disc_per)):
            $row->disc_amount = round((($row->amount * $row->disc_per) / 100),2);
            $row->taxable_amount = round(($row->amount - $row->disc_amount),2);
        else:
            $row->taxable_amount = $row->amount;
        endif;

        $row->gst_per = $row->igst_per = floatVal($row->gst_per);
        $row->gst_amount = $row->igst_amount = round((($row->taxable_amount * $row->gst_per) / 100),2);

        $row->cgst_per = $row->sgst_per = round(($row->gst_per / 2),2);
        $row->cgst_amount = $row->sgst_amount = round(($row->gst_amount / 2),2);

        $row->net_amount = round(($row->taxable_amount + $row->gst_amount),2);
        $row->item_remark = "";

        echo '<script>AddRow('.json_encode($row).');</script>';
    endforeach;
endif;
?>