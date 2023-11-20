<!-- <html>
    <head>
        <title>Payment Voucher</title>
        
        <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url();?>assets/images/favicon.png">
    </head>
    <body> -->
        <div class="row" style="<?=($printType == "Duplicate")?"margin-top:10%;":""?> page-break-inside: avoid;">
            <div class="col-12">
                <table class="table table-bordered" style="margin-bottom:10px;">
                    <tr>
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

                <table class="table bg-light-grey">
                    <tr class="" style="letter-spacing: 2px;font-weight:bold;padding:2px !important; border-bottom:1px solid #000000;">
                        <td style="width:33%;" class="fs-18 text-left">
                            GSTIN: <?=$companyData->company_gst_no?>
                        </td>
                        <td style="width:33%;" class="fs-18 text-center">Receipt Voucher</td>
                        <td style="width:33%;" class="fs-18 text-right"><?=$printType?></td>
                    </tr>
                </table>
                
                <table class="table item-list-bb fs-22" style="margin-top:5px;">
                    <tr >
                        <th style="width:50%;" class="text-left">
                            Vou. No. : <?=$dataRow->trans_number?>
                        </th>
                        <th style="width:50%;" class="text-right">
                            Vou. Date. : <?=formatDate($dataRow->trans_date)?>
                        </th>
                    </tr>
                    <tr>
				        <th class="text-left" colspan="2">
                            Party Name : <?=$dataRow->party_name?> 
                        </th>
                    </tr>
                    <tr>
                        <th  style="width:50%;" class="text-left">
                            Payment Mode : <?=$dataRow->payment_mode?>
                        </th>
                        <th style="width:50%;" class="text-left">                            
                            Amount : <?=$dataRow->net_amount?>                            
                        </th>
                    </tr>
                    <tr>
                        <th class="text-left" colspan="2">
                            Amount In Words : <?=numToWordEnglish(sprintf('%.2f',$dataRow->net_amount))?>
                        </th>
                    </tr>
                    <tr>
                        <th style="width:50%;" class="text-left">
                            Transaction No. : <?=$dataRow->doc_no?>
                        </th>
                        <th style="width:50%;" class="text-left">
                            Transaction Date. : <?=(!empty($dataRow->doc_no))?formatDate($dataRow->doc_date):""?>
                        </th>
                    </tr>
                    <tr>
                        <th class="text-left"  colspan="2">Note : <?=$dataRow->remark?></th>
                    </tr>
                </table>
                
                <?php if(!empty($dataRow->invoiceRef)): ?>
                <table class="table item-list-bb" style="margin-top:10px;">
                    <tr>
                        <th class="text-center" colspan="4">Invoice/Payment Details</th>
                    </tr>
                    <tr>
                        <th class="text-left">Vou. No.</th>
                        <th class="text-left">Vou. Date</th>
                        <th class="text-right">Net Amount</th>
                        <th class="text-right">Due Amount</th>
                    </tr>
                    <?php
                        $totalAmt = $totalPenAmt = $totalAdjAmt = $totalDueAmt = 0;
                        foreach($dataRow->invoiceRef as $row):
                            $row = (object) $row;

                            $totalDueAmt += (in_array($row->vou_name_s,['Sale','Purc']))?($row->net_amount):($row->net_amount * -1);
                            echo '<tr>
                                <td>'.$row->trans_number.'</td>
                                <td>'.formatDate($row->trans_date).'</td>
                                <td class="text-right">'.$row->net_amount.'</td>
                                <td class="text-right">'.$totalDueAmt.'</td>
                            </tr>';

                            /* $totalAmt += $row->net_amount;
                            $totalPenAmt += ($row->net_amount - $row->rop_amount + $row->adjust_amount);
                            $totalAdjAmt += $row->adjust_amount;
                            $totalDueAmt += ($row->net_amount - $row->rop_amount); */
                        endforeach;
                    ?>
                    <!-- <tr>
                        <th colspan="2" class="text-right">Total Qty.</th>
                        <th class="text-right"><?=sprintf('%.2f',$totalAmt)?></th>
                        <th class="text-right"><?=sprintf('%.2f',$totalPenAmt)?></th>
                        <th class="text-right"><?=sprintf('%.2f',$totalAdjAmt)?></th>
                        <th class="text-right"><?=sprintf('%.2f',$totalDueAmt)?></th>
                    </tr> -->
                </table>
                <?php endif; ?>
		                
                <table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;border-bottom:1px solid #545454;">
                    <tr>
                        <td style="width:50%;"></td>
                        <td style="width:20%;"></td>
                        <th class="text-center">For, <?=$companyData->company_name?></th>
                    </tr>
                    <tr>
                        <td colspan="3" height="30"></td>
                    </tr>
                    <tr>
                        <td><br>This is a computer-generated voucher.</td>
                        <td class="text-center"><?=$dataRow->created_name?><br>Prepared By</td>
                        <td class="text-center"><br>Authorised By</td>
                    </tr>
                </table>          
            </div>
        </div>        
    <!-- </body>
</html> -->
