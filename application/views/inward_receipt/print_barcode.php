<form method="post" action="<?= base_url("inwardReceipt/printBarcode") ?>" target="_blank">
    <input type="hidden" name="id" id="id" value="<?= $id ?>" />

    <div class="col-md-12">
        <div class="row">
            <div class="table table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-info">
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>Barcode No.</th>
                            <th>Gross Weight</th>
                            <th>Net Weight</th>
                            <th>Product ID</th>
                            <th>Design No</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0;
                        foreach ($dataRow as $row) :
                            $checked = "checked";
                            $checkBoxInput = '<input type="checkbox" name="trans_id[]" id="return_checkbox' . $i . '" class="filled-in chk-col-success" data-rowid="' . $i . '" check="' . $checked . '" ' . $checked . ' value="' . $row->id . '" />
                                <label for="return_checkbox' . $i . '"></label>';


                            echo '<tr>
                                    <td style="width:10%;">
                                        ' . $checkBoxInput . '
                                    </td>
                                    <td>
                                        ' . $row->item_name . ' 
                                       </td>
                                    <td>' . $row->unique_id . '</td>
                                    <td>' . $row->gross_weight . '</td> 
                                    <td>' . $row->net_weight . '</td> 
                                    <td>' . $row->party_code . '-' . $row->unique_id . '</td> 
                                    <td>' . $dcode . '</td> 

                                </tr>';
                            $i++;
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr>
    <div class="float-right">
        <a href="#" data-dismiss="modal" class="btn btn-secondary"><i class="fa fa-times"></i> Close</a>
        <button type="submit" class="btn btn-success" onclick="printModal('print_barcode_modal');"><i class="fa fa-print"></i> Print</button>
    </div>
</form>