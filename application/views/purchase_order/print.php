<html>
    <head>
        <title>Purchase Order</title>
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url();?>assets/images/favicon.png">
    </head>
    <body>
        <div class="row">
            <div class="col-12">
				<table>
					<tr>
						<td>
							<img src="<?=$letter_head?>" class="img">
						</td>
					</tr>
				</table>

				<table class="table bg-light-grey">
					<tr class="" style="letter-spacing: 2px;font-weight:bold;padding:2px !important; border-bottom:1px solid #000000;">
						<td style="width:33%;" class="fs-18 text-left">
							GSTIN: <?=$companyData->company_gst_no?>
						</td>
						<td style="width:33%;" class="fs-18 text-center">Sales Order</td>
						<td style="width:33%;" class="fs-18 text-right"></td>
					</tr>
				</table>               
                
                <table class="table item-list-bb fs-22" style="margin-top:5px;">
                    <tr >
                        <td rowspan="4" style="width:67%;vertical-align:top;">
                            <b>M/S. <?=$dataRow->party_name?></b><br>
                            <?=(!empty($dataRow->ship_address) ? $dataRow->ship_address ." - ".$dataRow->ship_pincode : '')?><br>
                            <b>Kind. Attn. : <?=$dataRow->contact_person?></b> <br>
                            Contact No. : <?=$dataRow->contact_no?><br>
                            Email : <?=$partyData->contact_email?><br><br>
                            GSTIN : <?=$dataRow->gstin?>
                        </td>
                        <td>
                            <b>PO. No.</b>
                        </td>
                        <td>
                            <?=$dataRow->trans_number?>
                        </td>
                    </tr>
                    <tr>
				        <th class="text-left">PO Date</th>
                        <td><?=formatDate($dataRow->trans_date)?></td>
                    </tr>
                    <tr>
                        <th class="text-left">Qtn. No.</th>
                        <td><?=$dataRow->doc_no?></td>
                    </tr>
                    <tr>
                        <th class="text-left">Qtn. Date</th>
                        <td><?=(!empty($dataRow->doc_date)) ? formatDate($dataRow->doc_date) : ""?></td>
                    </tr>
                </table>
                
                <table class="table item-list-bb" style="margin-top:10px;">
					<thead>
						<tr>
							<th style="width:40px;">No.</th>
							<th class="text-left">Item Description</th>
							<th style="width:75px;">HSN/SAC</th>
							<th style="width:80px;">Qty</th>
							<th style="width:75px;">Rate</th>
							<th style="width:60px;">Disc (%)</th>
                            <th style="width:60px;">GST <small>(%)</small></th>
							<th style="width:110px;">Taxable Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$i=1;$totalQty = 0;
							if(!empty($dataRow->itemList)):
								foreach($dataRow->itemList as $row):
									$indent = (!empty($row->ref_id)) ? '<br>Reference No:'.$row->ref_number : '';
									$delivery_date = (!empty($row->delivery_date)) ? '<br>Delivery Date :'.formatDate($row->delivery_date) : '';
									
									$item_remark=(!empty($row->item_remark))?'<br><small>Remark:.'.$row->item_remark.'</small>':'';
									
									echo '<tr>';
										echo '<td class="text-center" rowspan="2">'.$i++.'</td>';
										echo '<td>'.$row->item_name.$indent.$delivery_date.'</td>';
										echo '<td class="text-center">'.$row->hsn_code.'</td>';
										echo '<td class="text-right">'.sprintf('%0.2f',$row->qty).' <small>'.$row->unit_name.'</small></td>';
										echo '<td class="text-center">'.$row->price.'</td>';
										echo '<td class="text-center">'.$row->disc_per.'</td>';
										echo '<td class="text-center">'.$row->gst_per.'</td>';
										echo '<td rowspan="2" class="text-right">'.$row->taxable_amount.'</td>';
									echo '</tr>';
									echo '<tr><td colspan="6"><i>Notes:</i> '.$row->item_remark.'</td></tr>';
									$totalQty += $row->qty;
								endforeach;
							endif;
						?>
						<tr>
							<th colspan="3" class="text-right">Total Qty.</th>
							<th class="text-right"><?=sprintf('%.3f',$totalQty)?></th>
							<th class="text-right"></th>
							<th colspan="2" class="text-right">Sub Total</th>
							<th class="text-right"><?=sprintf('%.2f',$dataRow->taxable_amount)?></th>
						</tr>
					</tbody>
					<tfoot>
						<?php
							$rwspan= 0; $srwspan = '';
							$beforExp = "";
							$afterExp = "";
							$invExpenseData = (!empty($dataRow->expenseData)) ? $dataRow->expenseData : array();
							foreach ($expenseList as $row) :
								$expAmt = 0;
								$amtFiledName = $row->map_code . "_amount";
								if (!empty($invExpenseData) && $row->map_code != "roff") :
									$expAmt = floatVal($invExpenseData->{$amtFiledName});
								endif;

								if(!empty($expAmt)):
									if ($row->position == 1) :
										if($rwspan == 0):
											$beforExp .= '<th class="text-right" colspan="2">'.$row->exp_name.'</th>
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
								$taxAmt = floatVal($dataRow->{$taxRow->map_code.'_amount'});
								if(!empty($taxAmt)):
									if($rwspan == 0):
										$taxHtml .= '<th colspan="2" class="text-right">'.$taxRow->name.'</th>
										<td class="text-right">'.sprintf('%.2f',$taxAmt).'</td>';
									else:
										$taxHtml .= '<tr>
											<th colspan="2" class="text-right">'.$taxRow->name.'</th>
											<td class="text-right">'.sprintf('%.2f',$taxAmt).'</td>
										</tr>';
									endif;
								
									$rwspan++;
								endif;
							endforeach;

							$fixRwSpan = (!empty($rwspan))?3:0;
						?>
						<tr>
							<th class="text-left" colspan="5" rowspan="<?=$rwspan?>">
								<b>Note: </b> <?= $dataRow->remark?>
							</th>

							<?php if(empty($rwspan)): ?>
                                <th colspan="2" class="text-right">Round Off</th>
								<td class="text-right"><?=sprintf('%.2f',$dataRow->round_off_amount)?></td>
                            <?php endif; ?>
						</tr>
						<?=$beforExp.$taxHtml.$afterExp?>
						<tr>
							<th class="text-left" colspan="5" rowspan="3">
								Amount In Words : <br><?=numToWordEnglish(sprintf('%.2f',$dataRow->net_amount))?>
							</th>

							<?php if(empty($rwspan)): ?>
                                <th colspan="2" class="text-right">Grand Total</th>
                                <th class="text-right"><?=sprintf('%.2f',$dataRow->net_amount)?></th>
                            <?php endif; ?>
						</tr>

						<?php if(!empty($rwspan)): ?>
						<tr>
							<th colspan="2" class="text-right">Round Off</th>
							<td class="text-right"><?=sprintf('%.2f',$dataRow->round_off_amount)?></td>
						</tr>
						<tr>
							<th colspan="2" class="text-right">Grand Total</th>
							<th class="text-right"><?=sprintf('%.2f',$dataRow->net_amount)?></th>
						</tr>	
						<?php endif; ?>		
					</tfoot>
                </table>

                <h4>Terms & Conditions :-</h4>
                <table class="table top-table" style="margin-top:10px;">
                    <?php
                        if(!empty($dataRow->termsConditions)):
                            foreach($dataRow->termsConditions as $row):
                                echo '<tr>';
                                    /* echo '<th class="text-left fs-11" style="width:140px;">'.$row->term_title.'</th>'; */
                                    echo '<td class=" fs-11"><ul><li> '.$row->condition.' </li></ul></td>';
                                echo '</tr>';
                            endforeach;
                        endif;
                    ?>
                </table>
                
				<htmlpagefooter name="lastpage">
					<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
						<tr>
							<td style="width:50%;" rowspan="4"></td>
							<th colspan="2">For, <?=$companyData->company_name?></th>
						</tr>
						<tr>
							<td style="width:25%;" class="text-center"><?=$dataRow->prepareBy?></td>
							<td style="width:25%;" class="text-center"><?=$dataRow->approveBy?>'</td>
						</tr>
						<tr>
							<td style="width:25%;" class="text-center"><b>Prepared By</b></td>
							<td style="width:25%;" class="text-center"><b>Authorised By</b></td>
						</tr>
					</table>
					<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
						<tr>
							<td style="width:25%;">PO No. & Date : <?=$dataRow->trans_number.' ['.formatDate($dataRow->trans_date).']'?></td>
							<td style="width:25%;"></td>
							<td style="width:25%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
						</tr>
					</table>
                </htmlpagefooter>
				<sethtmlpagefooter name="lastpage" value="on" />
            </div>
        </div>        
    </body>
</html>
