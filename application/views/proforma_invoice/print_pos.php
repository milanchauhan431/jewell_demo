<style>
    <?php echo $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css?v=' . time())); ?>body {
        margin: 0px;
        padding: 0px;
    }
</style>
<div class="row">
    <div class="col-12">
        <?php if (!empty($header_footer)) : ?>
            <table>
                <tr align="center">
                    <td>
                        <p style="font-size:25px;font-weight:bold">RIVA</p>
                    </td>
                </tr>
            </table>
        <?php endif; ?>
        <p class="fs-18 text-center">Proforma Invoice</p>

        <p class="fs-18 text-left">
            GSTIN: <?= $companyData->company_gst_no ?>
        </p>
        <p> <b>BILL TO</b><br>
            <b><?= $invData->party_name ?></b><br>
            <?= (!empty($partyData->party_address) ? $partyData->party_address : '') ?><br>
            <b>GSTIN : <?= $invData->gstin ?> | STATE CODE: <?= substr($invData->gstin, 0, 2) ?> | CITY : <?= $partyData->city_name ?></b>
        </p>
        <p>
            <b>Invoice No. : <?= $invData->trans_prefix . $invData->trans_no ?></b><br>
            <b>Date : <?= date('d/m/Y', strtotime($invData->trans_date)) ?></b>

        </p>
        <p>
            <b>Memo Type</b> : <?= $invData->memo_type ?><br>
            <b>P.O. No.</b> : <?= $invData->doc_no ?><br>
            <b>Challan No</b> : <?= $invData->challan_no ?>
        </p>
        <h4>Item Details: </h4>
        <hr />
        <table class="table item-list-bb" style="margin-top:10px;">

            <tbody>
                <?php
                $i = 1;
                $totalQty = 0;
                $migst = 0;
                $mcgst = 0;
                $msgst = 0;
                $rowCount = 1;
                $pageCount = 1;
                if (!empty($invData->itemList)) :
                    foreach ($invData->itemList as $row) :
                        echo '<strong>Item:</strong> ' . $row->item_name . ' -  ' . $row->unique_id . '<br>';
                        echo '<strong>HSN/SAC:</strong> ' . $row->hsn_code . '<br>';
                        echo '<strong>Qty:</strong> ' . floatVal($row->qty) . ' (' . $row->unit_name . ')<br>';
                        echo '<strong>Rate:</strong> ' . floatVal($row->price) . '<br>';
                        if(!empty($row->gold_platinum_price)):
                            echo '<strong>Gold/Platinum Amount :</strong> ' . floatVal($row->gold_platinum_price) . '<br>';
                        endif;
                        echo '<strong>Making Charge:</strong> ' . floatVal($row->making_charge - $row->making_charge_dicount) . '<br>';
                        echo '<strong>Disc (%):</strong> ' . floatVal($row->disc_per) . '<br>';
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


                $rwspan = 0;
                $srwspan = '';
                $beforExp = "";
                $afterExp = "";
                $invExpenseData = (!empty($invData->expenseData)) ? $invData->expenseData : array();
                foreach ($expenseList as $row) :
                    $expAmt = 0;
                    $amtFiledName = $row->map_code . "_amount";
                    if (!empty($invExpenseData) && $row->map_code != "roff") :
                        $expAmt = floatVal($invExpenseData->{$amtFiledName});
                    endif;

                    if (!empty($expAmt)) :
                        if ($row->position == 1) :
                            if ($rwspan == 0) :
                                $beforExp .= '<th colspan="4" class="text-right">' . $row->exp_name . '</th>
                                    <td class="text-right" colspan="4" >' . sprintf('%.2f', $expAmt) . '</td>';
                            else :
                                $beforExp .= '<tr>
                                        <th colspan="4" class="text-right">' . $row->exp_name . '</th>
                                        <td class="text-right" colspan="4" >' . sprintf('%.2f', $expAmt) . '</td>
                                    </tr>';
                            endif;
                        else :
                            $afterExp .= '<tr>
                                    <th colspan="4" class="text-right">' . $row->exp_name . '</th>
                                    <td class="text-right" colspan="4">  ' . sprintf('%.2f', $expAmt) . '</td>
                                </tr>';
                        endif;
                        $rwspan++;
                    endif;
                endforeach;

                $taxHtml = '';
                foreach ($taxList as $taxRow) :
                    $taxAmt = 0;
                    $taxAmt = floatVal($invData->{$taxRow->map_code . '_amount'});
                    if (!empty($taxAmt)) :
                        if ($rwspan == 0) :
                            $taxHtml .= '<th colspan="4" class="text-right">' . $taxRow->name . ' @' . (($invData->gst_type == 1) ? floatVal($migst / 2) : $migst) . '%</th>
                                <td class="text-right" colspan="4" >' . sprintf('%.2f', $taxAmt) . '</td>';
                        else :
                            $taxHtml .= '<tr>
                                    <th colspan="4" class="text-right">' . $taxRow->name . ' @' . (($invData->gst_type == 1) ? floatVal($migst / 2) : $migst) . '%</th>
                                    <td class="text-right" colspan="4" >' . sprintf('%.2f', $taxAmt) . '</td>
                                </tr>';
                        endif;

                        $rwspan++;
                    endif;
                endforeach;
                $fixRwSpan = (!empty($rwspan)) ? 4 : 0;
                ?>
                <tr>
                    <th colspan="4" class="text-right">Total Qty.</th>
                    <th class="text-right" colspan="4"><?= sprintf('%.3f', $totalQty) ?></th>

                </tr>
                <tr>
                    <th colspan="4" class="text-right">Sub Total</th>
                    <th class="text-right" colspan="4"><?= sprintf('%.2f', $invData->taxable_amount) ?></th>
                </tr>
                <tr>

                    <th colspan="4" class="text-right">Round Off</th>
                    <td class="text-right" colspan="4"><?= sprintf('%.2f', $invData->round_off_amount) ?></td>

                </tr>
                <?= $beforExp . $taxHtml . $afterExp ?>
                <tr>
                    <th colspan="4" class="text-right">Grand Total</th>
                    <th class="text-right" colspan="4"><?= sprintf('%.2f', $invData->net_amount) ?></th>

                </tr>
				<tr>
                    <th colspan="4" class="text-right">Amount In Words</th>
                    <th class="text-right" colspan="4"><?= numToWordEnglish(sprintf('%.2f', $invData->net_amount)) ?></th>

                </tr> 

            </tbody>
        </table>
        <p>
            <b>Bank Name : </b> <?= $companyData->company_bank_name ?><br>
            <b>Account Name : </b> <?=$companyData->company_acc_name?><br>
            <b>A/c. No. : </b><?= $companyData->company_acc_no ?><br>
            <b>IFSC Code : </b><?= $companyData->company_ifsc_code ?><br>
            <b>Branch : </b><?= $companyData->company_bank_branch ?>
            <hr>
            <b>Note : </b> <?= $invData->remark ?>
        </p>
        <h4>Terms & Conditions :-</h4>
        <table class="table top-table" style="margin-top:10px;">
            <?php
            if (!empty($invData->termsConditions)) :
                foreach ($invData->termsConditions as $row) :
                    echo '<tr>';
                    /* echo '<th class="text-left fs-11" style="width:140px;">'.$row->term_title.'</th>'; */
                    echo '<td class=" fs-11"><ul><li> ' . $row->condition . ' </li></ul></td>';
                    echo '</tr>';
                endforeach;
            endif;
            ?>
        </table>

        <htmlpagefooter name="lastpage">
            <table style="border-bottom:1px solid #545454;">
                <tr>
                    <th colspan="2" style="vertical-align:bottom;text-align:right;font-size:1rem;padding:5px 2px;">
                        For, <?= $companyData->company_name ?><br>
                    </th>
                </tr>
                <tr>
                    <td colspan="2" height="35"></td>
                </tr>
                <tr>
                    <td colspan="2" style="vertical-align:bottom;text-align:right;font-size:1rem;padding:5px 2px;"><b>Authorised Signature</b></td>
                </tr>
            </table>
        </htmlpagefooter>
        <sethtmlpagefooter name="lastpage" value="on" />
    </div>
</div>

<script>
window.print();
setTimeout(function () {
    window.close(); // Replace this line with your own 'afterprint' logic.
}, 2000);

</script>