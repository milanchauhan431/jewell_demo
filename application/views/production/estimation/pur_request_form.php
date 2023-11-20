<form>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="req_date">Request Date</label>
                <input type="date" name="req_date" id="req_date" class="form-control" value="<?=getFyDate()?>">
            </div>
            <div class="col-md-3 form-group">
                <label for="trans_number">SO No.</label>
                <input type="text" id="trans_number" class="form-control" value="<?=(!empty($postData->trans_number))?$postData->trans_number:""?>" readonly>
            </div>
            <div class="col-md-6 form-group">
                <label for="item_name">Product Name</label>
                <input type="text" id="item_name" class="form-control" value="<?=(!empty($postData->item_name))?$postData->item_name:""?>" readonly>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="error itemData"></div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-info">
                            <tr>
                                <th>#</th>
                                <th>CAT No.</th>
                                <th>Item Name</th>
                                <th>Make</th>
                                <th>UOM</th>
                                <th>Reuired Qty</th>
                                <th>Stock Qty</th>
                                <th>Requested Qty</th>
                                <th style="width:10%;">Request Qty</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i=1;
                                foreach($dataRow as $row):
                                    $requestQty = 0;
                                    if(!empty($row->stock_qty)):
                                        if($row->stock_qty < $row->reqired_qty):
                                            $requestQty = abs($row->stock_qty - $row->reqired_qty) - $row->req_qty;
                                        endif;
                                    else:
                                        $requestQty = $row->reqired_qty - $row->req_qty;
                                    endif;

                                    $requestQty = ($requestQty > 0)?$requestQty:0;
                            ?>
                                <tr>
                                    <td><?=$i?></td>
                                    <td><?=$row->item_code?></td>
                                    <td><?=$row->item_name?></td>
                                    <td><?=$row->make?></td>
                                    <td><?=$row->uom?></td>
                                    <td><?=floatVal($row->reqired_qty)?></td>
                                    <td><?=floatVal($row->stock_qty)?></td>
                                    <td><?=floatVal($row->req_qty)?></td>
                                    <td>
                                        <input type="hidden" name="itemData[<?=$i?>][bom_id]" value="<?=$row->id?>">
                                        <input type="text" name="itemData[<?=$i?>][req_qty]" class="form-control floatOnly" value="<?=$requestQty?>">
                                    </td>
                                    <td>
                                        <input type="text" name="itemData[<?=$i?>][remark]" class="form-control" value="">
                                    </td>
                                </tr>
                            <?php
                                $i++;
                                endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>