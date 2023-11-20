<?php
class Migration extends CI_Controller{
    public function __construct(){
        parent::__construct();
    }

    /* public function addColumnInTable(){
        $result = $this->db->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'ascent' AND TABLE_NAME NOT IN ( SELECT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME = 'updated_at' AND TABLE_SCHEMA = 'ascent' )")->result();


        foreach($result as $row):
            if(!in_array($row->TABLE_NAME,["instrument"])):
                $this->db->query("ALTER TABLE ".$row->TABLE_NAME." ADD `updated_at` INT NOT NULL DEFAULT '0' AFTER `updated_by`;");
            endif;
        endforeach;

        echo "success";exit;
    } */

    /* public function defualtLedger(){
        $accounts = [
            ['name' => 'Sales Account', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESACC'],
            
            ['name' => 'Sales Account GST', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESGSTACC'],

            ['name' => 'Sales Account IGST', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESIGSTACC'],

            ['name' => 'Sales Account Tax Free', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESTFACC'],

            ['name' => 'Exempted Sales (Nill Rated)', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESEXEMPTEDTFACC'],

            ['name' => 'Sales Account GST JOBWORK', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESJOBGSTACC'],

            ['name' => 'Sales Account IGST JOBWORK', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESJOBIGSTACC'],

            ['name' => 'Export With Payment', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'EXPORTGSTACC'],

            ['name' => 'Export Without Payment', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'EXPORTTFACC'],

            ['name' => 'SEZ Supplies With Payment', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SEZSGSTACC'],

            ['name' => 'SEZ Supplies Without Payment', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SEZSTFACC'],

            ['name' => 'Deemed Export', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'DEEMEDEXP'],
            
            ['name' => 'CGST (O/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'CGSTOPACC'],
            
            ['name' => 'SGST (O/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'SGSTOPACC'],
            
            ['name' => 'IGST (O/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'IGSTOPACC'],
            
            ['name' => 'UTGST (O/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'UTGSTOPACC'],
            
            ['name' => 'CESS (O/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'TCS ON SALES', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'Purchase Account', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURACC'],
            
            ['name' => 'Purchase Account GST', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURGSTACC'],

            ['name' => 'Purchase Account IGST', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURIGSTACC'],

            ['name' => 'Purchase Account URD GST', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURURDGSTACC'],

            ['name' => 'Purchase Account URD IGST', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURURDIGSTACC'],

            ['name' => 'Purchase Account Tax Free', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURTFACC'],

            ['name' => 'Exempted Purchase (Nill Rated)', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PUREXEMPTEDTFACC'],

            ['name' => 'Purchase Account GST JOBWORK', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURJOBGSTACC'],

            ['name' => 'Purchase Account IGST JOBWORK', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURJOBIGSTACC'],

            ['name' => 'Import', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'IMPORTACC'],

            ['name' => 'Import of Services', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'IMPORTSACC'],

            ['name' => 'Received from SEZ', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'SEZRACC'],
            
            ['name' => 'CGST (I/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'CGSTIPACC'],
            
            ['name' => 'SGST (I/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'SGSTIPACC'],
            
            ['name' => 'IGST (I/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'IGSTIPACC'],
            
            ['name' => 'UTGST (I/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'UTGSTIPACC'],
            
            ['name' => 'CESS (I/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'TCS ON PURCHASE', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'TDS PAYABLE', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'TDS RECEIVABLE', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'GST PAYABLE', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'GST RECEIVABLE', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'ROUNDED OFF', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => 'ROFFACC'],
            
            ['name' => 'CASH ACCOUNT', 'group_name' => 'Cash-In-Hand', 'group_code' => 'CS', 'system_code' => 'CASHACC'],
            
            ['name' => 'ELECTRICITY EXP', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'OFFICE RENT EXP', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'GODOWN RENT EXP', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'TELEPHONE AND INTERNET CHARGES', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'PETROL EXP', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'SALES INCENTIVE', 'group_name' => 'Expenses (Direct)', 'group_code' => 'ED', 'system_code' => ''],
            
            ['name' => 'INTEREST PAID', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'INTEREST RECEIVED', 'group_name' => 'Income (Indirect)', 'group_code' => 'II', 'system_code' => ''],
            
            ['name' => 'SAVING BANK INTEREST', 'group_name' => 'Income (Indirect)', 'group_code' => 'II', 'system_code' => ''],
            
            ['name' => 'DISCOUNT RECEIVED', 'group_name' => 'Income (Indirect)', 'group_code' => 'II', 'system_code' => ''],
            
            ['name' => 'DISCOUNT PAID', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'SUSPENSE A/C', 'group_name' => 'Suspense A/C', 'group_code' => 'AS', 'system_code' => ''],
            
            ['name' => 'PROFESSIONAL FEES PAID', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'AUDIT FEE', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'ACCOUNTING CHARGES PAID', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'LEGAL FEE', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'SALARY', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'WAGES', 'group_name' => 'Expenses (Direct)', 'group_code' => 'ED', 'system_code' => ''],
            
            ['name' => 'FREIGHT CHARGES', 'group_name' => 'Expenses (Direct)', 'group_code' => 'ED', 'system_code' => ''],
            
            ['name' => 'PACKING AND FORWARDING CHARGES', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'REMUNERATION TO PARTNERS', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'TRANSPORTATION CHARGES', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'DEPRICIATION', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'PLANT AND MACHINERY', 'group_name' => 'Fixed Assets', 'group_code' => 'FA', 'system_code' => ''],
            
            ['name' => 'FURNITURE AND FIXTURES', 'group_name' => 'Fixed Assets', 'group_code' => 'FA', 'system_code' => ''],
            
            ['name' => 'FIXED DEPOSITS', 'group_name' => 'Deposits (Assets)', 'group_code' => 'DA', 'system_code' => ''],
            
            ['name' => 'RENT DEPOSITS', 'group_name' => 'Deposits (Assets)', 'group_code' => 'DA', 'system_code' => '']	            
        ];
        try{
            $this->db->trans_begin();
            $accounts = (object) $accounts;
            foreach($accounts as $row):
                $row = (object) $row;

                $groupData = $this->db->where('group_code',$row->group_code)->get('group_master')->row();

                $ledgerData = [
                    'party_category' => 4,
                    'group_name' => $groupData->name,
                    'group_code' => $groupData->group_code,
                    'group_id' => $groupData->id,
                    'party_name' => $row->name,                    
                    'system_code' => $row->system_code
                ];

                $this->db->where('party_name',$row->name);
                $this->db->where('is_delete',0);
                $this->db->where('party_category',4);
                $checkLedger = $this->db->get('party_master');

                if($checkLedger->num_rows() > 0):
                    $id = $checkLedger->row()->id;
                    $this->db->where('id',$id);
                    $this->db->update('party_master',$ledgerData);
                else:
                    $this->db->insert('party_master',$ledgerData);
                endif;
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                echo "Defualt Ledger Migration Success.";
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    } */
    
    public function updateLedgerClosingBalance(){
        try{
            $this->db->trans_begin();

            $partyData = $this->db->where('is_delete',0)->get("party_master")->result();

            foreach($partyData as $row):
                //Set oprning balance as closing balance
                $this->db->where('id',$row->id);
                $this->db->update('party_master',['cl_balance'=>'opening_balance']);

                //get ledger trans amount total
                $this->db->select("SUM(amount * p_or_m) as ledger_amount");
                $this->db->where('vou_acc_id',$row->id);
                $this->db->where('is_delete',0);
                $ledgerTrans = $this->db->get('trans_ledger')->row();
                $ledgerAmount = (!empty($ledgerTrans->ledger_amount))?$ledgerTrans->ledger_amount:0;

                //update colsing balance
                $this->db->set("cl_balance","`cl_balance` + ".$ledgerAmount,FALSE);
                $this->db->where('id',$row->id);
                $this->db->update('party_master');
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                echo "Closing Balance Migration Success.";
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    }

    /* public function dimondAmount(){
        try{
    
            $this->db->where('party_id',75);
            $result = $this->db->get('inward_receipt')->result();
            foreach($result as $row):
                $this->db->where('main_ref_id',$row->id);;
                $this->db->update('stock_transaction',['party_id'=>75]);
            endforeach;

    
        }catch(\Throwable $e){ 
            echo $e->getMessage();exit;
        } 
    } */

    /* public function migrateInwardApproval(){
        try{
            $this->db->trans_begin();

            $this->db->where('approved_by',0);
            $this->db->where('is_delete',0);
            $result = $this->db->get('inward_receipt')->result();

            foreach($result as $row):
                $stockTransData = array();

                $stockTransData = [
                    'entry_type' => $row->entry_type,
                    'ref_date' => $row->trans_date, 
                    'ref_no' => $row->trans_number,
                    'main_ref_id' => $row->id,
                    'location_id' => 7,
                    'party_id' => $row->party_id,
                    'item_id' => $row->item_id,
                    'p_or_m' => 1,
                    'purity' => $row->purity,
                    'stock_category' => $row->inward_type,
                    'standard_qty' => $row->standard_qty,
                    'gross_weight' => $row->gross_weight,
                    'net_weight' => $row->net_weight,
                    'making_per' => $row->macking_charge,
                    'otc_amount' => $row->variety_charge,
                    'vrc_amount' => $row->other_charge,
                    'stock_type' => "NEW",
                ];

                for($i=1;$i<=$row->qty;$i++):
                    $random_numbers = mt_rand(1000000000,9999999999);
                    $stockTransData['unique_id'] = $random_numbers;
                    $stockTransData['batch_no'] = $random_numbers;
                    $stockTransData['qty'] = 1;

                    $this->db->insert("stock_transaction",$stockTransData);
                endfor;

                $this->db->where('id',$row->id);;
                $this->db->update('inward_receipt',['approved_by'=>15,'approved_at'=>date("Y-m-d H:i:s")]);
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                echo "Inward approved Successfully.";
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        } 
    } */

    /* public function migrateGoldWeight(){
        try{
            $this->db->trans_begin();

            $this->db->where('is_delete',0);
            $result = $this->db->get('inward_receipt')->result();

            foreach($result as $row):
                $gold_weight = 0;
                $gold_weight = round(($row->gross_weight - $row->kundan_weight - $row->stone_weight - $row->moti_weight-  $row->diamond_weight - $row->net_weight),3);

                if($gold_weight > 0):
                    //print_r($gold_weight);print_r("<br><br>");

                    $this->db->reset_query();
                    $this->db->where('id',$row->id);
                    $this->db->update('inward_receipt',['gold_weight'=>$gold_weight]);

                    $this->db->reset_query();
                    $this->db->where('entry_type',$row->entry_type);
                    $this->db->where('ref_no',$row->trans_number);
                    $this->db->where('main_ref_id',$row->id);
                    $this->db->where('is_delete',0);
                    $stockTrans = $this->db->get('stock_transaction')->result();

                    foreach($stockTrans as $st):
                        $this->db->reset_query();
                        $this->db->where('unique_id',$st->unique_id);
                        $this->db->update('stock_transaction',['gold_weight'=>$gold_weight]);

                        //print_r("unique_id : ".$st->unique_id);print_r("<br><br>");

                        $this->db->reset_query();
                        $this->db->select('trans_child.id,trans_details.t_col_1 as unique_id');
                        $this->db->join("trans_details", "trans_child.trans_main_id = trans_details.main_ref_id AND trans_details.child_ref_id = trans_child.id AND trans_details.table_name = 'trans_child'");
                        $this->db->where('trans_details.t_col_1',$st->unique_id);
                        $this->db->where('trans_child.is_delete',0);
                        $transChild = $this->db->get('trans_child')->result();

                        if(!empty($transChild)):
                            $transIds = [];
                            $transIds = array_column($transChild,'id');
                            //print_r("transIds : ");print_r($transIds);print_r("<br><br>");

                            $this->db->reset_query();
                            $this->db->where_in('id',$transIds);
                            $this->db->update('trans_child',['gold_weight'=>$gold_weight]);
                        endif;
                    endforeach;
                    //print_r("<hr>");
                endif;
            endforeach;exit;




            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                //echo "Gold Weight updated Successfully.";
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        } 
    } */

    public function clearItemStock(){
        try{
            $this->db->trans_begin();

            /*$this->db->select("item_master.item_name,SUM((CASE WHEN stock_transaction.p_or_m = 1 THEN stock_transaction.qty ELSE 0 END)) as inward_qty, SUM((CASE WHEN stock_transaction.p_or_m = -1 THEN stock_transaction.qty ELSE 0 END)) as sold_qty, SUM(stock_transaction.qty * stock_transaction.p_or_m) as stock_qty");
            $this->db->join("item_master","stock_transaction.item_id = item_master.id","left");
            $this->db->where_in('stock_transaction.stock_category',["Platinum Items","Platinum + Diamond Items","Platinum + Gold + Diamond Items"]);
            $this->db->where('stock_transaction.is_delete',0);
            $this->db->group_by("stock_transaction.item_id");
            $itemStock = $this->db->get('stock_transaction')->result();

            echo '<table>
                <tr>
                    <td>Item Name</td>
                    <td>In Qty</td>
                    <td>Out Qty</td>
                    <td>Stock Qty</td>
                </tr>';
            foreach($itemStock as $row):
                echo '<tr>
                    <td>'.$row->item_name.'</td>
                    <td>'.$row->inward_qty.'</td>
                    <td>'.$row->sold_qty.'</td>
                    <td>'.$row->stock_qty.'</td>
                </tr>';
            endforeach;
            echo '</table>';
            exit;*/

            $this->db->select('id,entry_type,trans_number,qty');
			$this->db->where_in('inward_type',["Lab Grown Diamond"]);
			$this->db->where('party_id',81);
            $this->db->where('is_delete',0);
            $result = $this->db->get('inward_receipt')->result();

            foreach($result as $row):
                //print_r("<hr>");
                /* if(floatval($row->qty) > 1):
                    print_r("IR No. : ".$row->trans_number." Qty. : ".$row->qty);print_r("<hr>");
                endif; */
                $this->db->select('id,unique_id,item_id');
                $this->db->where('main_ref_id',$row->id);
                $this->db->where('entry_type',$row->entry_type);
                $this->db->where('p_or_m',1);
                $stockTrans = $this->db->get('stock_transaction')->result();

                $serialNo = [];
                foreach($stockTrans as $trans):
                    $this->db->select('SUM(qty * p_or_m) as qty');
                    $this->db->where('batch_no',$trans->unique_id);
                    $this->db->where('item_id',$trans->item_id);
                    $this->db->having("SUM(qty * p_or_m) > 0");
                    $this->db->group_by("unique_id");
                    $itemStock = $this->db->get('stock_transaction')->row();

                    if(empty($itemStock)):
                        $serialNo[] = $trans->unique_id;
                    else:
                        //print_r($trans->unique_id);print_r("<br>");
                        $this->db->where('id',$trans->id);
                        $this->db->update('stock_transaction',['is_delete'=>2]);
                    endif;
                endforeach;

                if(empty($serialNo)):
                    //print_r($row->trans_number);print_r("<br>");
                    $this->db->where('id',$row->id);
                    $this->db->update('inward_receipt',['is_delete'=>2]);
                endif;
            endforeach;            

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                echo "Item Stock Cleared Successfully.";
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        } 
    }
}
?>