<div class="row">
    <div class="col-12">
        <?php if(!empty($header_footer)): ?>
            <table class="table table-bordered" style="margin-bottom:10px;">
                <tr>
                    <!-- <td>
                        <img src="<?= $letter_head ?>" class="img">
                    </td> -->
                    <th class="text-center fs-30"> 
                        <?=$companyData->company_name?>
                    </th>                    
                </tr>
                <tr>
                    <td class="text-center"> 
                        <?=$companyData->company_address.', '.$companyData->company_city.' - '.$companyData->company_pincode?><br>
                        Contact No. : <?=$companyData->company_contact.((!empty($companyData->company_phone))?" / ".$companyData->company_phone:"")?> Email : <?=$companyData->company_email?>
                    </td>
                </tr>
            </table>
        <?php endif; ?>

        <table class="table bg-light-grey">
            <tr class="" style="letter-spacing: 2px;font-weight:bold;padding:2px !important; border-bottom:1px solid #000000;">
                <td style="width:33%;" class="fs-18 text-left">
                    GSTIN: <?=$companyData->company_gst_no?>
                </td>
                <td style="width:33%;" class="fs-18 text-center">Proforma Invocie</td>
                <td style="width:33%;" class="fs-18 text-right"><?=$printType?></td>
            </tr>
        </table>
        
        <table class="table item-list-bb fs-22" style="margin-top:5px;">
            <tr>
                <td style="width:60%; vertical-align:top;" rowspan="3">
                    <b>BILL TO</b><br>
                    <b><?=$invData->party_name?></b><br>
                    <?=(!empty($partyData->party_address) ? $partyData->party_address : '')?><br>
                    <b><?= (!empty($invData->gstin) && $invData->gstin != "URP")?"GSTIN : ".$invData->gstin:""?>
                    <?=(!empty($invData->gstin) && $invData->gstin != "URP")?" | STATE CODE: ".substr($invData->gstin, 0, 2)." | ":""?>
                    <?=(!empty($partyData->city_name))?"CITY : ".$partyData->city_name:""?></b>
                </td>
                <td>
                    <b>Invoice No. : <?=$invData->trans_prefix . $invData->trans_no?></b>
                </td>
                <td>
                    <b>Date : <?=date('d/m/Y', strtotime($invData->trans_date))?></b>
                </td>
            </tr>
            <tr>
                <td style="width:40%;" colspan="2">
                    <b>Memo Type</b> : <?=$invData->memo_type?><br>
                    <b>P.O. No.</b> : <?=$invData->doc_no?><br>
                    <b>Challan No</b> : <?=$invData->challan_no?>
                </td>
            </tr>
        </table>
        
        <table class="table item-list-bb" style="margin-top:10px;">
            <?php $thead = '<thead>
                    <tr>
                        <th style="width:40px;">No.</th>
                        <th class="text-left">Description of Goods</th>
                        <th style="width:10%;">HSN/SAC</th>
                        <th style="width:40px;">Qty</th>
                        <th style="width:40px;">G.W.</th>
                        <th style="width:40px;">S.W.</th>
                        <th style="width:40px;">N.W.</th>
                        <th style="width:40px;">Rate<br><small>('.$partyData->currency.')</small></th>
                        <th>Making<br>Charges<br><small>('.$partyData->currency.')</small></th>
                        <th style="width:50px;">Disc.</th>
                        <th style="width:50px;">GST <small>(%)</small></th>
                        <th style="width:80px;">Amount<br><small>('.$partyData->currency.')</small></th>
                    </tr>
                </thead>';
                echo $thead;
            ?>
            <tbody>
                <?php
                    $i=1;$totalQty = $totalGW = $totalNW = 0;$migst=0;$mcgst=0;$msgst=0;
                    $rowCount = 1;$pageCount = 1;
                    if(!empty($invData->itemList)):
                        foreach($invData->itemList as $row):
                            $gold_weight = 0;
                            $gold_weight = $row->gold_weight;
                            $row->gold_weight = 0;
                            echo '<tr>';
                                echo '<td class="text-center">'.$i++.'</td>';
                                echo '<td>';
                                    echo   '<b>'.$row->item_name . '</b><br>';
                                    echo   '<small>Serial No. : '.$row->unique_id . '</small><br>';

                                    if(!empty($row->gold_platinum_price) && $row->gold_platinum_price != 0 && !in_array($row->stock_category,["Lab Grown Diamond","Loos Diamond","Diamond Items"])):
                                        echo '<small>Gold Amount : ' . floatVal($row->gold_platinum_price) . '</small><br>';
                                    endif;
                                    if(!empty($gold_weight) && $gold_weight > 0 && !in_array($row->stock_category,["Lab Grown Diamond","Loos Diamond","Diamond Items"])):
                                        $row->gold_weight = $gold_weight;
                                        echo '<small>Gold Weight : ' . floatVal($row->gold_weight) . '</small><br>';
                                    endif;
                                    if(!empty($row->other_charge) && $row->other_charge > 0):
                                        echo '<small>Other Charge : ' . floatVal($row->other_charge) . '</small><br>';
                                    endif;
                                    if(!empty($row->vrc_charge) && $row->vrc_charge > 0):
                                        echo '<small>Variety Charge : ' . floatVal($row->vrc_charge) . '</small><br>';
                                    endif;
                                    if(!empty($row->diamond_amount) && $row->diamond_amount > 0):
                                        echo '<small>Diamond Amount : ' . floatVal($row->diamond_amount) . '</small><br>';
                                    endif;
                                    if(!empty($row->color)):
                                        echo '<small>Diamond Color :</small> ' . $row->color . '<br>';
                                    endif;
                                    if(!empty($row->diamond_carat)):
                                        echo '<small>Diamond Carat :</small> ' . $row->diamond_carat . '<br>';
                                    endif;
                                    if(!empty($row->diamond_pcs) && $row->diamond_pcs > 0):
                                        echo '<small>Diamond Pcs. :</small> ' . floatVal($row->diamond_pcs) . '<br>';
                                    endif;
                                echo '</td>';
                                echo '<td class="text-center">'.$row->hsn_code.'</td>';
                                echo '<td class="text-center">'.floatVal($row->qty).' ('.$row->unit_name.')</td>';
                                echo '<td class="text-right">'.($row->gross_weight).'</td>';
                                echo '<td class="text-right">'.sprintf('%.3f',round($row->gross_weight - $row->net_weight - $row->gold_weight,3)).'</td>';
                                echo '<td class="text-right">'.($row->net_weight).'</td>';
                                echo '<td class="text-right">'.floatVal($row->price).'</td>';
                                echo '<td class="text-right">' . floatVal($row->making_charge - $row->making_charge_dicount) . '</td>';
                                echo '<td class="text-right">'.floatVal($row->disc_amount).'</td>';
                                echo '<td class="text-center">'.$row->gst_per.'</td>';
                                echo '<td class="text-right">'.$row->taxable_amount.'</td>';
                            echo '</tr>';
                            
                            $totalQty += $row->qty;
                            $totalGW += $row->gross_weight;
                            $totalNW += $row->net_weight;
                            if($row->gst_per > $migst){$migst=$row->gst_per;$mcgst=$row->cgst_per;$msgst=$row->sgst_per;}
                        endforeach;
                    endif;

                    $blankLines = ($maxLinePP - $i);
                    if($blankLines > 0):
                        for($j=1;$j<=$blankLines;$j++):
                            echo '<tr>
                                <td style="border-top:none;border-bottom:none;">&nbsp;</td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                            </tr>';
                        endfor;
                    endif;
                    
                    $rwspan= 0; $srwspan = '';
                    $beforExp = "";
                    $afterExp = "";
                    $invExpenseData = (!empty($invData->expenseData)) ? $invData->expenseData : array();
                    foreach ($expenseList as $row) :
                        $expAmt = 0;
                        $amtFiledName = $row->map_code . "_amount";
                        if (!empty($invExpenseData) && $row->map_code != "roff") :
                            $expAmt = floatVal($invExpenseData->{$amtFiledName});
                        endif;

                        if(!empty($expAmt)):
                            if ($row->position == 1) :
                                if($rwspan == 0):
                                    $beforExp .= '<th colspan="2" class="text-right">'.$row->exp_name.'</th>
                                    <td class="text-right">'.sprintf('%.2f',$expAmt).'</td>';
                                else:
                                    $beforExp .= '<tr>
                                        <th colspan="2" class="text-right">'.$row->exp_name.'</th>
                                        <td class="text-right">'.sprintf('%.2f',$expAmt).'</td>
                                    </tr>';
                                endif;                                
                            else:
                                $afterExp .= '<tr>
                                    <th colspan="2" class="text-right">'.$row->exp_name.'</th>
                                    <td class="text-right">'.sprintf('%.2f',$expAmt).'</td>
                                </tr>';
                            endif;
                            $rwspan++;
                        endif;
                    endforeach;

                    $taxHtml = '';
                    foreach ($taxList as $taxRow) :
                        $taxAmt = 0;
                        $taxAmt = floatVal($invData->{$taxRow->map_code.'_amount'});
                        if(!empty($taxAmt)):
                            if($rwspan == 0):
                                $taxHtml .= '<th colspan="2" class="text-right">'.$taxRow->name.' @'.(($invData->gst_type == 1)?floatVal($migst/2):$migst).'%</th>
                                <td class="text-right">'.sprintf('%.2f',$taxAmt).'</td>';
                            else:
                                $taxHtml .= '<tr>
                                    <th colspan="2" class="text-right">'.$taxRow->name.' @'.(($invData->gst_type == 1)?floatVal($migst/2):$migst).'%</th>
                                    <td class="text-right">'.sprintf('%.2f',$taxAmt).'</td>
                                </tr>';
                            endif;
                        
                            $rwspan++;
                        endif;
                    endforeach;
                    $fixRwSpan = (!empty($rwspan))?3:0;
                ?>
                <tr>
                    <th colspan="3" class="text-right">Total Qty.</th>
                    <th class="text-right"><?=sprintf('%.3f',$totalQty)?></th>
                    <th class="text-right"><?=sprintf('%.3f',$totalGW)?></th>
                    <th class="text-right"><?=sprintf('%.3f',($totalGW - $totalNW))?></th>
                    <th class="text-right"><?=sprintf('%.3f',$totalNW)?></th>
                    <th></th>
                    <th></th>
                    <th colspan="2" class="text-right">Sub Total</th>
                    <th class="text-right"><?=sprintf('%.2f',$invData->taxable_amount)?></th>
                </tr>
                <tr>
                    <th class="text-left" colspan="9" rowspan="<?=$rwspan?>">
                        <b>Bank Name : </b> <?=$companyData->company_bank_name?><br>
                        <b>Account Name : </b> <?=$companyData->company_acc_name?><br>
                        <b>A/c. No. : </b><?=$companyData->company_acc_no?><br>
                        <b>IFSC Code : </b><?=$companyData->company_ifsc_code?><br>
                        <b>Branch : </b><?=$companyData->company_bank_branch?>
                        <hr>
                        <b>Note : </b> <?=$invData->remark?>
                    </th>
                    <?php if(empty($rwspan)): ?>
                        <th colspan="2" class="text-right">Round Off</th>
                        <td class="text-right"><?=sprintf('%.2f',$invData->round_off_amount)?></td>
                    <?php endif; ?>
                </tr>
                <?=$beforExp.$taxHtml.$afterExp?>
                <tr>
                    <th class="text-left" colspan="9" rowspan="<?=$fixRwSpan?>">
                        Amount In Words : <br><?=numToWordEnglish(sprintf('%.2f',$invData->net_amount))?>
                    </th>	
                    
                    <?php if(empty($rwspan)): ?>
                        <th colspan="2" class="text-right">Grand Total</th>
                        <th class="text-right"><?=sprintf('%.2f',$invData->net_amount)?></th>
                    <?php else: ?>
                        <th colspan="2" class="text-right">Round Off</th>
                        <td class="text-right"><?=sprintf('%.2f',$invData->round_off_amount)?></td>
                    <?php endif; ?>
                </tr>
                
                <?php if(!empty($rwspan)): ?>
                <tr>
                    <th colspan="2" class="text-right">Grand Total</th>
                    <th class="text-right"><?=sprintf('%.2f',$invData->net_amount)?></th>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <table class="table top-table" style="margin-top:10px;">
            <tr>
                <th class="text-left">
                    <h4>Terms & Conditions :-</h4>
                </th>
            </tr>
            <?php
                if(!empty($invData->termsConditions)):
                    foreach($invData->termsConditions as $row):
                        echo '<tr>';
                            /* echo '<th class="text-left fs-11" style="width:140px;">'.$row->term_title.'</th>'; */
                            echo '<td class=" fs-11"><ul><li> '.$row->condition.' </li></ul></td>';
                        echo '</tr>';
                    endforeach;
                endif;
            ?>
        </table>

        <table style="border-bottom:1px solid #545454;margin-top:10px;">
			<tr>
				<th colspan="2" style="vertical-align:bottom;text-align:right;font-size:1rem;padding:5px 2px;">
					For, <?=$companyData->company_name?><br>
				</th>
			</tr>
			<tr>
				<td colspan="2" height="35"></td>
			</tr>
			<tr>
				<td colspan="2" style="vertical-align:bottom;text-align:right;font-size:1rem;padding:5px 2px;"><b>Authorised Signature</b></td>
			</tr>
		</table>

        <!--<htmlpagefooter name="lastpage">
            
        </htmlpagefooter>
		<sethtmlpagefooter name="lastpage" value="on" />--> 
    </div>
</div>        
    
