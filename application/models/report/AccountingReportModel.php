<?php
class AccountingReportModel extends MasterModel{

    public function getLedgerSummary($data){
        $startDate = (!empty($data['form_date']))?$data['form_date']:$this->startYearDate;
        $endDate = (!empty($toDate))?$toDate:$this->endYearDate;
        $startDate = date("Y-m-d",strtotime($startDate));
        $endDate = date("Y-m-d",strtotime($endDate));

        $ledgerSummary = $this->db->query("SELECT lb.id as id, am.party_name as account_name, CONCAT(am.credit_days, ' Days') as credit_days , CASE WHEN lb.op_balance > 0 THEN CONCAT(abs(lb.op_balance),' CR.') WHEN lb.op_balance < 0 THEN CONCAT(abs(lb.op_balance),' DR.') ELSE lb.op_balance END op_balance,am.group_name, lb.cr_balance, lb.dr_balance, CASE WHEN lb.cl_balance > 0 THEN CONCAT(abs(lb.cl_balance),' CR.') WHEN lb.cl_balance < 0 THEN CONCAT(abs(lb.cl_balance),' DR.') ELSE lb.cl_balance END as cl_balance 
        FROM (
            SELECT am.id, ((am.opening_balance) + SUM( CASE WHEN tl.trans_date < '".$startDate."' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as op_balance, 
            SUM( CASE WHEN tl.trans_date >= '".$startDate."' AND tl.trans_date <= '".$endDate."' THEN CASE WHEN tl.c_or_d = 'DR' THEN tl.amount ELSE 0 END ELSE 0 END) as dr_balance,
            SUM( CASE WHEN tl.trans_date >= '".$startDate."' AND tl.trans_date <= '".$endDate."' THEN CASE WHEN tl.c_or_d = 'CR' THEN tl.amount ELSE 0 END ELSE 0 END) as cr_balance,
            ((am.opening_balance) + SUM( CASE WHEN tl.trans_date <= '".$endDate."' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as cl_balance 
            FROM party_master as am 
            LEFT JOIN trans_ledger as tl ON am.id = tl.vou_acc_id 
            WHERE am.is_delete = 0 GROUP BY am.id, am.opening_balance) as lb 
        LEFT JOIN party_master as am ON lb.id = am.id WHERE am.is_delete = 0 
        ORDER BY am.party_name ")->result();
        
        return $ledgerSummary;
    }

    public function getLedgerDetail($data){
        //tl.trans_number AS trans_number, 
        $ledgerTransactions = $this->db->query ("SELECT 
        tl.trans_main_id AS id, 
        tl.entry_type, 
        tl.trans_date, 
        tl.trans_number,
        tl.vou_name_s, 
        tl.amount,
        tl.c_or_d,
        tl.p_or_m,
        am.party_name AS account_name, 
        CASE WHEN tl.c_or_d = 'DR' THEN tl.amount ELSE 0 END AS dr_amount, 
        CASE WHEN tl.c_or_d = 'CR' THEN tl.amount ELSE 0 END AS cr_amount, 
        tl.remark AS remark 
        FROM ( trans_ledger AS tl LEFT JOIN party_master AS am ON am.id = tl.opp_acc_id ) 
        WHERE tl.vou_acc_id = ".$data['acc_id']." 
        AND tl.trans_date >= '".$data['from_date']."' 
        AND tl.trans_date <= '".$data['to_date']."'
        AND tl.is_delete = 0
        ORDER BY tl.trans_date, tl.trans_number")->result();
        return $ledgerTransactions;
    }

    public function getLedgerBalance($data){

        $ledgerBalance = $this->db->query ("SELECT am.id, am.party_name AS account_name,am.group_code, am.party_mobile AS contact_no, (am.opening_balance + SUM( CASE WHEN tl.trans_date < '".$data['from_date']."' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as op_balance, 
        SUM( CASE WHEN tl.trans_date >= '".$data['from_date']."' AND tl.trans_date <= '".$data['to_date']."' THEN CASE WHEN tl.c_or_d = 'DR' THEN tl.amount ELSE 0 END ELSE 0 END) as dr_balance,
        SUM( CASE WHEN tl.trans_date >= '".$data['from_date']."' AND tl.trans_date <= '".$data['to_date']."' THEN CASE WHEN tl.c_or_d = 'CR' THEN tl.amount ELSE 0 END ELSE 0 END) as cr_balance,
        (am.opening_balance  + SUM( CASE WHEN tl.trans_date <= '".$data['to_date']."' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as cl_balance,
        (am.opening_balance + SUM( CASE WHEN tl.recon_date IS NOT NULL AND tl.recon_date <= '".$data['to_date']."' THEN ((tl.amount * tl.p_or_m) * -1) ELSE 0 END )) as bcl_balance
        FROM party_master as am 
        LEFT JOIN trans_ledger as tl ON am.id = tl.vou_acc_id AND tl.is_delete = 0
        WHERE am.is_delete = 0 
        AND am.id = ".$data['acc_id']."
        GROUP BY am.id, am.opening_balance")->row();

        $ledgerBalance->op_balance_type=(!empty($ledgerBalance->op_balance) && $ledgerBalance->op_balance >= 0)?(($ledgerBalance->op_balance > 0)?'CR':''):(($ledgerBalance->op_balance < 0)?'DR':'');
        $ledgerBalance->cl_balance_type=(!empty($ledgerBalance->cl_balance) && $ledgerBalance->cl_balance >= 0)?(($ledgerBalance->cl_balance > 0)?'CR':''):(($ledgerBalance->cl_balance < 0)?'DR':'');
        $ledgerBalance->bcl_balance_type=(!empty($ledgerBalance->bcl_balance) && $ledgerBalance->bcl_balance >= 0)?(($ledgerBalance->bcl_balance > 0)?'CR':''):(($ledgerBalance->bcl_balance < 0)?'DR':'');

        return $ledgerBalance;
    }

    public function getRegisterData($data){
        $queryData['tableName'] = 'trans_main';
        $queryData['select'] = 'trans_main.id,trans_main.trans_number,trans_main.doc_no,trans_main.trans_date,trans_main.order_type,trans_main.party_name,trans_main.party_state_code,trans_main.doc_no,trans_main.gstin,trans_main.currency,trans_main.vou_name_s,trans_main.total_amount,trans_main.disc_amount,trans_main.taxable_amount,trans_main.cgst_amount,trans_main.sgst_amount,trans_main.igst_amount,trans_main.cess_amount,trans_main.gst_amount,(trans_main.net_amount - trans_main.taxable_amount - trans_main.gst_amount) as other_amount,trans_main.net_amount';


        $queryData['where_in']['trans_main.vou_name_s'] = $data['vou_name_s'];
        $queryData['where']['trans_main.trans_date >='] = $data['from_date'];
        $queryData['where']['trans_main.trans_date <='] = $data['to_date'];

        if (!empty($data['party_id'])):
            $queryData['where']['trans_main.party_id'] = $data['party_id'];
        endif;

        if (!empty($data['state_code'])):
            if ($data['state_code'] == 1):
                $queryData['where_in']['trans_main.party_state_code']=[24,96];
            endif;
            if ($data['state_code'] == 2) :
                $queryData['where_not_in']['trans_main.party_state_code']=[24,96];
            endif;
        endif;

        $queryData['order_by']['trans_date']='ASC';
        return $this->rows($queryData);
    }

    public function getRegisterDataItemWise($data){
        $queryData['tableName'] = 'trans_child';
        $queryData['select'] = 'trans_main.id,trans_main.trans_number,trans_main.doc_no,trans_main.trans_date,trans_main.order_type,trans_main.party_name,trans_main.party_state_code,trans_main.doc_no,trans_main.gstin,trans_main.currency,trans_main.vou_name_s,trans_child.item_name,trans_child.qty,trans_child.gross_weight,trans_child.net_weight,trans_child.price,trans_child.amount,trans_child.disc_amount,trans_child.taxable_amount,(CASE WHEN trans_main.gst_type = 1 THEN trans_child.cgst_amount ELSE 0 END) as cgst_amount,(CASE WHEN trans_main.gst_type = 1 THEN trans_child.sgst_amount ELSE 0 END) as sgst_amount,(CASE WHEN trans_main.gst_type = 2 THEN trans_child.igst_amount ELSE 0 END) as igst_amount,trans_child.cess_amount,trans_child.gst_amount,(trans_main.net_amount - trans_main.taxable_amount - trans_main.gst_amount) as other_amount,trans_main.net_amount';

        $queryData['leftJoin']['trans_main'] = "trans_main.id = trans_child.trans_main_id";

        $queryData['where_in']['trans_main.vou_name_s'] = $data['vou_name_s'];
        $queryData['where']['trans_main.trans_date >='] = $data['from_date'];
        $queryData['where']['trans_main.trans_date <='] = $data['to_date'];

        if (!empty($data['party_id'])):
            $queryData['where']['trans_main.party_id'] = $data['party_id'];
        endif;

        if (!empty($data['state_code'])):
            if ($data['state_code'] == 1):
                $queryData['where_in']['trans_main.party_state_code']=[24,96];
            endif;
            if ($data['state_code'] == 2) :
                $queryData['where_not_in']['trans_main.party_state_code']=[24,96];
            endif;
        endif;

        $queryData['order_by']['trans_main.trans_date']='ASC';
        $queryData['order_by']['trans_main.id']='ASC';
        return $this->rows($queryData);
    }

    public function getHsnDetailData($data){
        $decreaseEntryType = ($data['report'] == "Sale")?'"C.N."':'"D.N."';

        $queryData = array();
        $queryData['tableName'] = "trans_child";
        $queryData['select'] = "trans_main.trans_number,trans_main.trans_date,trans_main.vou_name_s,trans_main.party_name,trans_child.item_name,trans_child.hsn_code,trans_child.gst_per,trans_child.unit_name,

        IF(trans_main.vou_name_s = $decreaseEntryType,(trans_child.qty * -1),trans_child.qty) as qty,

        IF(trans_main.vou_name_s = $decreaseEntryType,(trans_child.taxable_amount * -1),trans_child.taxable_amount) as taxable_amount,
        (CASE WHEN trans_main.gst_type = 1 THEN IF(trans_main.vou_name_s = $decreaseEntryType,(trans_child.cgst_amount * -1),trans_child.cgst_amount) ELSE 0 END) as cgst_amount,
        (CASE WHEN trans_main.gst_type = 1 THEN IF(trans_main.vou_name_s = $decreaseEntryType,(trans_child.sgst_amount * -1),trans_child.sgst_amount) ELSE 0 END) as sgst_amount,
        (CASE WHEN trans_main.gst_type = 2 THEN IF(trans_main.vou_name_s = $decreaseEntryType,(trans_child.igst_amount * -1),trans_child.igst_amount) ELSE 0 END) as igst_amount,
        IF(trans_main.vou_name_s = $decreaseEntryType,(trans_child.net_amount * -1),trans_child.net_amount) as net_amount";

        $queryData['leftJoin']['trans_main'] = "trans_main.id = trans_child.trans_main_id";

        $queryData['customWhere'][] = ($data['report'] == "Sale")?' (trans_main.vou_name_s IN ('.$data['vou_name_s'].') OR (trans_main.vou_name_s = "C.N." AND trans_main.order_type IN ("Decrease Sales","Sales Return") ) OR (trans_main.vou_name_s = "D.N." AND trans_main.order_type IN ("Increase Sales") ))':'  (trans_main.vou_name_s IN ('.$data['vou_name_s'].') OR (trans_main.vou_name_s = "C.N." AND trans_main.order_type IN ("Increase Purchase") ) OR (trans_main.vou_name_s = "D.N." AND trans_main.order_type IN ("Decrease Purchase","Purchase Return") ))';

        $queryData['where']['trans_main.trans_date >='] = $data['from_date'];
        $queryData['where']['trans_main.trans_date <='] = $data['to_date'];
        $queryData['where']['trans_main.trans_status != '] = 3;
        $queryData['where']['trans_child.hsn_code'] = $data['hsn_code'];

        $result = $this->rows($queryData);
        return $result;
    }

    public function getOutstandingData($postData){
        $os_type = ($postData['os_type']=="R") ? '<' : '>';
		$daysCondition = ',';$daysFields = '';
		
        if(!empty($postData['days_range'])):
		    $i=1;$rangeLength = count($postData['days_range']);$ele=1;
		    $daysCondition = ($rangeLength > 0) ? ',' : '';
		    foreach($postData['days_range'] as $days):		        
		        if($i == 1):
                    $daysCondition .='(am.opening_balance + SUM( CASE WHEN DATEDIFF(DATE_ADD( tl.trans_date,INTERVAL am.credit_days day),NOW()) <= '.$days.' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as d'.$ele++.',';
                endif;

		        if($i == $rangeLength):
                    $daysCondition .='(am.opening_balance + SUM( CASE WHEN DATEDIFF(DATE_ADD( tl.trans_date,INTERVAL am.credit_days day),NOW()) > '.$days.' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as d'.$ele++.',';
                endif;

		        if($i < $rangeLength):
                    $daysCondition .='(am.opening_balance + SUM( CASE WHEN DATEDIFF(DATE_ADD( tl.trans_date,INTERVAL am.credit_days day),NOW()) BETWEEN '.($days + 1).' AND '.$postData['days_range'][$i].' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as d'.$ele++.',';
                endif;
		        $i++;
            endforeach;
		    for($x=1;$x<=($rangeLength+1);$x++): $daysFields .= ',abs(lb.d'.$x.') as d'.$x; endfor;
		endif;
		
		$dueDaysBetween = "AND DATEDIFF(DATE_ADD( lb.trans_date,INTERVAL am.credit_days day),NOW()) >= 16 AND DATEDIFF(DATE_ADD( lb.trans_date,INTERVAL am.credit_days day),NOW()) <= 30";

        $receivable = $this->db->query ("SELECT lb.id as id, am.party_name as account_name,am.group_name,am.contact_person, am.party_mobile, ct.name as city_name, abs(lb.cl_balance) as cl_balance ".$daysFields.", lb.trans_date,  DATE_ADD( lb.trans_date,INTERVAL am.credit_days day) as due_date, DATEDIFF(DATE_ADD( lb.trans_date,INTERVAL am.credit_days day),NOW()) as pending_days
        FROM (
            SELECT am.id, (am.opening_balance + SUM( CASE WHEN tl.trans_date < '".$postData['from_date']."' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as op_balance,
            SUM( CASE WHEN tl.trans_date >= '".$postData['from_date']."' AND tl.trans_date <= '".$postData['to_date']."' THEN CASE WHEN tl.c_or_d = 'DR' THEN tl.amount ELSE 0 END ELSE 0 END) as dr_balance,
            SUM( CASE WHEN tl.trans_date >= '".$postData['from_date']."' AND tl.trans_date <= '".$postData['to_date']."' THEN CASE WHEN tl.c_or_d = 'CR' THEN tl.amount ELSE 0 END ELSE 0 END) as cr_balance,
            (am.opening_balance + SUM( CASE WHEN tl.trans_date <= '".$postData['to_date']."' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as cl_balance ".$daysCondition."
            tl.trans_date           
            FROM party_master as am 
            LEFT JOIN trans_ledger as tl ON am.id = tl.vou_acc_id  AND tl.is_delete = 0          
            WHERE am.group_code IN ( 'SD','SC' ) AND am.is_delete = 0 GROUP BY am.id, am.opening_balance 
        ) as lb
        LEFT JOIN party_master as am ON lb.id = am.id 
        LEFT JOIN cities as ct ON ct.id = am.city_id
        WHERE lb.cl_balance ".$os_type." 0 AND am.group_code IN ( 'SD','SC' ) AND am.is_delete = 0 ORDER BY am.party_name")->result();
        
        return $receivable;
    }

    public function getBankCashBook($postData){
        $fromDate = $postData['from_date'];
        $toDate = $postData['to_date'];
        $groupCode = $postData['group_code'];

        $bankCashBook = $this->db->query ("SELECT lb.id as id, am.party_name as account_name, am.group_name, lb.op_balance, lb.cr_balance, lb.dr_balance, lb.cl_balance, lb.bcl_balance,
        (CASE WHEN lb.op_balance > 0 THEN CONCAT(abs(lb.op_balance),' CR.') WHEN lb.op_balance < 0 THEN CONCAT(abs(lb.op_balance),' DR.') ELSE lb.op_balance END) op_balance_text, 

        (CASE WHEN lb.cl_balance > 0 THEN CONCAT(abs(lb.cl_balance),' CR.') WHEN lb.cl_balance < 0 THEN CONCAT(abs(lb.cl_balance),' DR.') ELSE lb.cl_balance END) as cl_balance_text,

        (CASE WHEN lb.bcl_balance > 0 THEN CONCAT(abs(lb.bcl_balance),' CR.') WHEN lb.bcl_balance < 0 THEN CONCAT(abs(lb.bcl_balance),' DR.') ELSE lb.bcl_balance END) as bcl_balance_text 
        FROM (
            SELECT am.id, (am.opening_balance + SUM( CASE WHEN tl.trans_date < '".$fromDate."' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as op_balance, 

            SUM( CASE WHEN tl.trans_date >= '".$fromDate."' AND tl.trans_date <= '".$toDate."' THEN CASE WHEN tl.c_or_d = 'DR' THEN tl.amount ELSE 0 END ELSE 0 END) as dr_balance,

            SUM( CASE WHEN tl.trans_date >= '".$fromDate."' AND tl.trans_date <= '".$toDate."' THEN CASE WHEN tl.c_or_d = 'CR' THEN tl.amount  ELSE 0 END ELSE 0 END) as cr_balance,

            (am.opening_balance + SUM( CASE WHEN tl.trans_date <= '".$toDate."' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as cl_balance, 

            (am.opening_balance + SUM( CASE WHEN tl.recon_date IS NOT NULL AND tl.recon_date <= '".$toDate."' THEN ((tl.amount * tl.p_or_m) * -1) ELSE 0 END )) as bcl_balance

            FROM party_master as am 
            LEFT JOIN trans_ledger as tl ON am.id = tl.vou_acc_id AND tl.is_delete = 0
            WHERE am.is_delete = 0 AND am.group_code IN ($groupCode) GROUP BY am.id, am.opening_balance
            ) as lb 
        LEFT JOIN party_master as am ON lb.id = am.id WHERE am.is_delete = 0
        ORDER BY am.party_name")->result();

        return $bankCashBook;
    }

    public function _productOpeningAndClosingAmount($data){
        $from_date = date("Y-m-d",strtotime($data['from_date']));
        $to_date = date("Y-m-d",strtotime($data['to_date']));

        $result = $this->db->query("SELECT pm.item_type, (CASE WHEN pm.item_type = 1 THEN 'Finish Goods' WHEN pm.item_type = 2 THEN 'Consumable' WHEN pm.item_type = 3 THEN 'Raw Material' ELSE '' END) as ledger_name, ifnull(SUM(pl.op_amount),0) as op_amount, ifnull(SUM(pl.cl_amount),0) as cl_amount 
        FROM (
        
            SELECT pm.id, pm.item_type,  ost.stock_qty as op_stock, (ost.avg_price * ost.stock_qty) as op_amount, cst.stock_qty as cl_stock,(cst.avg_price * cst.stock_qty) as cl_amount 
            FROM  item_master AS pm 
            LEFT JOIN (	
                SELECT SUM(qty * p_or_m) AS stock_qty, (SUM(CASE WHEN price > 0 THEN (qty * price) ELSE 0 END) / SUM(qty * p_or_m)) as avg_price, item_id FROM stock_transaction WHERE is_delete = 0 AND ref_date < '$from_date' GROUP BY item_id 
            ) AS ost ON ost.item_id = pm.id 
            LEFT JOIN ( 
                SELECT SUM(qty * p_or_m) AS stock_qty, (SUM(CASE WHEN price > 0 THEN (qty * price) ELSE 0 END) / SUM(qty * p_or_m)) as avg_price, item_id FROM stock_transaction WHERE is_delete = 0 AND ref_date <= '$to_date' GROUP BY item_id 
            ) AS cst ON cst.item_id = pm.id 
            WHERE pm.is_delete = 0 and pm.item_type IN (1,2,3) and (ost.stock_qty <> 0 OR cst.stock_qty <> 0) GROUP BY pm.id
            
        ) as pl 
        LEFT JOIN item_master AS pm ON pl.id = pm.id 
        WHERE ( pl.op_amount <> 0 OR pl.cl_amount <> 0 ) 
        AND pm.is_delete = 0 
        GROUP BY pl.item_type")->result();

        return $result;
    }

    public function _trailAccountSummary($data){
        $from_date = date("Y-m-d",strtotime($data['from_date']));
        $to_date = date("Y-m-d",strtotime($data['to_date']));

        $result = $this->db->query("SELECT am.party_name as name, am.group_name, am.group_id,
        ifnull((CASE WHEN lb.cl_balance < 0 THEN abs(lb.cl_balance) ELSE 0 END),0) as debit_amount,
        ifnull((CASE WHEN lb.cl_balance > 0 THEN lb.cl_balance ELSE 0 END),0) as credit_amount,
        ifnull(lb.cl_balance,0) as cl_balance
        FROM ( party_master am LEFT JOIN group_master gm ON am.group_id = gm.id ) 
        LEFT JOIN ( 
            SELECT am.id as id, ((am.opening_balance) + SUM( CASE WHEN tl.trans_date <= '$to_date' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as cl_balance FROM party_master as am LEFT JOIN trans_ledger as tl ON am.id = tl.vou_acc_id AND tl.is_delete = 0 WHERE am.is_delete = 0 GROUP BY am.id 
        ) as lb ON am.id = lb.id 
        WHERE am.is_delete = 0 
        AND lb.cl_balance <> 0
        ORDER BY gm.bs_type_code, am.group_name, am.party_name")->result();

        return $result;
    }

    public function _trailSubGroupSummary($data){
        $from_date = date("Y-m-d",strtotime($data['from_date']));
        $to_date = date("Y-m-d",strtotime($data['to_date']));
        $extraWhere = (!empty($data['extra_where']))?" AND ".$data['extra_where']:" ";

        $result = $this->db->query("SELECT gm.id,gm.name as group_name, gm.nature ,gm.bs_type_code, gm.base_group_id, gm.under_group_id,
        (CASE WHEN gm.base_group_id = 0 THEN gm.under_group_id ELSE gm.base_group_id END) as bs_id,
        ifnull((CASE WHEN gs.cl_balance < 0 THEN abs(gs.cl_balance) ELSE 0 END),0) as debit_amount,
        ifnull((CASE WHEN gs.cl_balance > 0 THEN gs.cl_balance ELSE 0 END),0) as credit_amount,
        ifnull(gs.cl_balance,0) as cl_balance
        FROM  group_master as gm 
        LEFT JOIN ( 
            SELECT am.group_id,(SUM(am.opening_balance) + SUM( CASE WHEN tl.trans_date <= '$to_date' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as cl_balance FROM ( party_master as am LEFT JOIN trans_ledger as tl ON am.id = tl.vou_acc_id AND tl.is_delete = 0) WHERE  am.is_delete = 0 GROUP BY am.group_id
        ) AS gs on gm.id = gs.group_id 
        WHERE  gm.is_delete = 0 
        $extraWhere
        ORDER BY gm.seq")->result();

        return $result;
    }

    public function _trailMainGroupSummary($data){
        $from_date = date("Y-m-d",strtotime($data['from_date']));
        $to_date = date("Y-m-d",strtotime($data['to_date']));
        $extraWhere = (!empty($data['extra_where']))?" AND ".$data['extra_where']:" ";

        $result = $this->db->query("SELECT bsgm.id,bsgm.name as group_name, bsgm.nature, sugm.debit_amount, sugm.credit_amount, ifnull((sugm.credit_amount - sugm.debit_amount),0) as cl_balance
        FROM group_master as bsgm
        LEFT JOIN (
            SELECT (CASE WHEN gm.base_group_id = 0 THEN gm.under_group_id ELSE gm.base_group_id END) as bs_id,
            ifnull(SUM((CASE WHEN gs.cl_balance < 0 THEN abs(gs.cl_balance) ELSE 0 END)),0) as debit_amount,
            ifnull(SUM((CASE WHEN gs.cl_balance > 0 THEN gs.cl_balance ELSE 0 END)),0) as credit_amount
            FROM  group_master as gm 
            LEFT JOIN ( 
                SELECT am.id,am.group_id,
                (SUM(am.opening_balance) + SUM( CASE WHEN tl.trans_date <= '$to_date' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as cl_balance FROM ( party_master as am LEFT JOIN trans_ledger as tl ON am.id = tl.vou_acc_id AND tl.is_delete = 0) WHERE  am.is_delete = 0 GROUP BY am.id
            ) AS gs on gm.id = gs.group_id 
            WHERE  gm.is_delete = 0 
            $extraWhere
            GROUP BY (CASE WHEN gm.base_group_id = 0 THEN gm.under_group_id ELSE gm.base_group_id END)
            ORDER BY gm.seq
        ) as sugm ON bsgm.id = sugm.bs_id
        WHERE bsgm.is_delete = 0
        AND ( sugm.debit_amount <> 0 OR sugm.credit_amount <> 0)
        ORDER BY bsgm.seq")->result();

        return $result;
    }

    public function _accountWiseDetail($data){
        $from_date = date("Y-m-d",strtotime($data['from_date']));
        $to_date = date("Y-m-d",strtotime($data['to_date']));
        $nature = $data['nature'];
        $bs_type_code = $data['bs_type_code'];
        $balance_type = $data['balance_type'];
        $balance = ($data['balance_type'] == "lb.cl_balance > 0")?"lb.cl_balance":"abs(lb.cl_balance) AS cl_balance";

		$innerSelect = "SELECT am.id as id,am.party_name AS name,am.group_id,am.group_name,gm.nature, ((am.opening_balance) + SUM( CASE WHEN  tl.trans_date <= '$to_date' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as cl_balance ";
		
		if(!empty($data['acctp']) AND $data['acctp']=='PL'):
			$innerSelect = "SELECT am.id as id,am.party_name AS name,am.group_id,am.group_name,gm.nature, (SUM( CASE WHEN tl.trans_date >= '$from_date' AND tl.trans_date <= '$to_date' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as cl_balance ";
		endif;

        $result = $this->db->query("SELECT lb.id,lb.name,lb.group_id,lb.group_name,lb.nature, $balance
        FROM (
            $innerSelect 
            FROM party_master as am 
            LEFT JOIN trans_ledger as tl ON am.id = tl.vou_acc_id AND tl.is_delete = 0
            LEFT JOIN group_master AS gm ON gm.id = am.group_id
            WHERE am.is_delete = 0
            AND gm.nature IN ($nature)
            AND gm.bs_type_code IN ($bs_type_code)
            GROUP BY am.id
        ) AS lb
        WHERE $balance_type")->result();

        return $result;
    }

    public function _groupWiseSummary($data){
        $from_date = date("Y-m-d",strtotime($data['from_date']));
        $to_date = date("Y-m-d",strtotime($data['to_date']));
        $nature = $data['nature'];
        $bs_type_code = $data['bs_type_code'];
        $balance_type = $data['balance_type'];
        $balance = ($data['balance_type'] == "gs.cl_balance > 0")?"SUM(gs.cl_balance) AS cl_balance":"SUM(abs(gs.cl_balance)) AS cl_balance";
		
		$innerSelect = "SELECT gm.id, gm.name AS group_name, gm.nature, gm.bs_type_code, gm.seq, ((am.opening_balance) + SUM( CASE WHEN tl.trans_date <= '$to_date' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as cl_balance ";
		
		if(!empty($data['acctp']) AND $data['acctp']=='PL'):
			$innerSelect = "SELECT gm.id, gm.name AS group_name, gm.nature, gm.bs_type_code, gm.seq, (SUM( CASE WHEN tl.trans_date >= '$from_date' AND tl.trans_date <= '$to_date' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as cl_balance ";
		endif;
		
        $result = $this->db->query("SELECT gs.id, gs.group_name, gs.nature, gs.bs_type_code, gs.seq, $balance
        FROM (
             $innerSelect
            FROM party_master as am 
            LEFT JOIN trans_ledger as tl ON am.id = tl.vou_acc_id AND tl.is_delete = 0
            LEFT JOIN group_master AS gm ON gm.id = am.group_id
            WHERE am.is_delete = 0
            AND gm.nature IN ($nature)
            AND gm.bs_type_code IN ($bs_type_code)            
            GROUP BY am.id
        ) AS gs
        WHERE $balance_type
        GROUP BY gs.id ORDER BY gs.seq")->result();

        return $result;
    }

    public function _netPnlAmount($data){
        $closingStockAmount = (!empty($data['closingAmount']))?$data['closingAmount']:0;
        $openingStockAmount = (!empty($data['openingAmount']))?$data['openingAmount']:0;
        $from_date = date("Y-m-d",strtotime($data['from_date']));
        $to_date = date("Y-m-d",strtotime($data['to_date']));
        $extraWhere = (!empty($data['extra_where']))?" AND ".$data['extra_where']:" ";

        $result = $this->db->query("SELECT ($closingStockAmount + ifnull(pnl.income,0)) - ($openingStockAmount + ifnull((CASE WHEN pnl.expense < 0 THEN abs(pnl.expense) ELSE pnl.expense * -1 END),0)) as net_pnl_amount 
        FROM ( 
            SELECT SUM(am.opening_balance) + SUM( CASE WHEN gm.nature = 'Income' AND tl.trans_date <= '$to_date' THEN (tl.amount * tl.p_or_m) ELSE 0 END) as income, 
            SUM(am.opening_balance) + SUM( CASE WHEN gm.nature = 'Expenses' AND tl.trans_date <= '$to_date' THEN (tl.amount * tl.p_or_m) ELSE 0 END) as expense 
            FROM ( ( party_master as am LEFT JOIN trans_ledger as tl ON am.id = tl.vou_acc_id AND tl.is_delete = 0) 
            LEFT JOIN group_master gm ON am.group_id = gm.id ) 
            WHERE am.is_delete = 0 AND tl.is_delete = 0 $extraWhere 
        ) as pnl")->row();

        return $result;
    }

}
?>