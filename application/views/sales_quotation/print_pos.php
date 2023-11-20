<html>

<head>
    <title>Quotation</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url(); ?>assets/images/favicon.png">
    <style>
        <?php echo $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css?v=' . time())); ?>body {
            margin: 0px;
            padding: 0px;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-12">
            <table>
                <tr align="center">
                    <td>
                        <p style="font-size:25px;font-weight:bold">Estimate</p>
                    </td>
                </tr>
            </table>
            <p> <b>BILL TO</b><br>
                <b>M/S. <?= $dataRow->party_name ?></b><br>
                <?= (!empty($dataRow->ship_address) ? $dataRow->ship_address . " - " . $dataRow->ship_pincode : '') ?><br>
            </p>
            <p>
                <b>Est. No. : <?= $dataRow->trans_number ?></b><br>
                <b>Est. Date</b> : <?= formatDate($dataRow->trans_date) ?><br>
            </p>

            <h4>Item Details: </h4>
            <hr />
			<?php 
			    $i = 1;
                    $totalQty = 0;
                    $migst = 0;
                    $mcgst = 0;
                    $msgst = 0;
                    if (!empty($dataRow->itemList)) :
                        foreach ($dataRow->itemList as $row) :
                            echo '<strong>Item:</strong> ' . $row->item_name . ' -  ' . $row->unique_id . '<br>';
                            echo '<strong>HSN/SAC:</strong> ' . $row->hsn_code . '<br>';
                            echo '<strong>Qty:</strong> ' . floatVal($row->qty) . ' (' . $row->unit_name . ')<br>';
                            echo '<strong>Rate:</strong> ' . floatVal($row->price) . '<br>';
                            if(!empty($row->gold_platinum_price)):
                                echo '<strong>Gold Amount :</strong> ' . floatVal($row->gold_platinum_price) . '<br>';
                            endif;
                            echo '<strong>Making Charge:</strong> ' . floatVal($row->making_charge - $row->making_charge_dicount) . '<br>';
                            echo '<strong>Disc :</strong> ' . floatVal($row->disc_amount) . '<br>';
                            echo '<strong>Other Charge :</strong> ' . floatVal($row->other_charge) . '<br>';
                            echo '<strong>Variety Charge :</strong> ' . floatVal($row->vrc_charge) . '<br>';
                            echo '<strong>Diamond Amount :</strong> ' . floatVal($row->diamond_amount) . '<br>';
                            echo '<strong>GST(%):</strong> ' . $row->gst_per . '<br>';
                            echo '<strong>Amount (' . $partyData->currency . '):</strong> ' . $row->taxable_amount . '<br>';
                            echo '<hr />';

                            $totalQty += $row->qty;
                            if ($row->gst_per > $migst) {
                                $migst = $row->gst_per;
                                $mcgst = $row->cgst_per;
                                $msgst = $row->sgst_per;
                            }
                        endforeach;
                    endif;
			?>
            <table class="table item-list-bb" width="100%" style="margin-top:10px;">

                <tbody> 
                    <?php
                


                    $rwspan = 0;
                    $srwspan = '';
                    $beforExp = "";
                    $afterExp = "";
                    $invExpenseData = (!empty($dataRow->expenseData)) ? $dataRow->expenseData : array();
                    foreach ($expenseList as $row) :
                        $expAmt = 0;
                        $amtFiledName = $row->map_code . "_amount";
                        if (!empty($invExpenseData) && $row->map_code != "roff") :
                            $expAmt = floatVal($invExpenseData->{$amtFiledName});
                        endif;

                        if (!empty($expAmt)) :
                            if ($row->position == 1) :
                                if ($rwspan == 0) :
                                    $beforExp .= '<th class="text-left">' . $row->exp_name . ': ' . sprintf('%.2f', $expAmt) . '</th>
                                       ';
                                else :
                                    $beforExp .= '<tr>
                                            <th class="text-left">' . $row->exp_name . ': ' . sprintf('%.2f', $expAmt) . '</th> 
                                        </tr>';
                                endif;
                            else :
                                $afterExp .= '<tr>
                                        <th class="text-left">' . $row->exp_name . ': ' . sprintf('%.2f', $expAmt) . '</th> 
                                    </tr>';
                            endif;
                            $rwspan++;
                        endif;
                    endforeach;

                    $taxHtml = '';
                    foreach ($taxList as $taxRow) :
                        $taxAmt = 0;
                        $taxAmt = floatVal($dataRow->{$taxRow->map_code . '_amount'});
                        if (!empty($taxAmt)) :
                            if ($rwspan == 0) :
                                $taxHtml .= '<th class="text-left">' . $taxRow->name . ' @' . (($dataRow->gst_type == 1) ? floatVal($migst / 2) : $migst) . '%:' . sprintf('%.2f', $taxAmt) . '</th>
                                   ';
                            else :
                                $taxHtml .= '<tr>
                                        <th class="text-left">' . $taxRow->name . ' @' . (($dataRow->gst_type == 1) ? floatVal($migst / 2) : $migst) . '% : '.sprintf('%.2f', $taxAmt).'</th>
                                   
                                    </tr>';
                            endif;

                            $rwspan++;
                        endif;
                    endforeach;
                    $fixRwSpan = (!empty($rwspan)) ? 3 : 0;
                    ?> 
                    <tr>
                        <th class="text-left">Total Qty.: <?= sprintf('%.3f', $totalQty) ?></th> 

                    </tr>
                    <tr>
                        <th class="text-left">Sub Total: <?= sprintf('%.2f', $dataRow->taxable_amount) ?></th> 
                    </tr>
                    <tr>

                    </tr> 
                    
                    <tr>
                        <th class="text-left">Round Off: <?= sprintf('%.2f', $dataRow->round_off_amount) ?></th> 
                    </tr>
                    <tr>
                        <th class="text-left">Grand Total: <?= sprintf('%.2f', $dataRow->net_amount) ?></th> 
                    </tr>
                    <tr>
                        <th class="text-left" >
                            Amount In Words:<br>
							<?= numToWordEnglish(sprintf('%.2f', $dataRow->net_amount)) ?>
                        </th> 
                    </tr>
                </tbody>
            </table>
            <h4>Valid for today only</h4>
             
        </div>
    </div>
</body>

</html>
<script>

window.print();
setTimeout(function () {
    //window.close(); // Replace this line with your own 'afterprint' logic.
}, 2000);

</script>