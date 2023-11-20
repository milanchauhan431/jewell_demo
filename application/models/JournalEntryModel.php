<?php
class JournalEntryModel extends MasterModel{
    private $transMain = "trans_main";
    private $transLedger = "trans_ledger";

    public function getDTRows($data){
        $data['tableName'] = $this->transLedger;
        $data['select'] = 'trans_main.id,trans_ledger.trans_number,trans_ledger.trans_date,party_master.party_name as acc_name,trans_ledger.amount,trans_ledger.remark,trans_ledger.c_or_d';
        
        $data['join']['trans_main'] = "trans_main.id = trans_ledger.trans_main_id";
        $data['leftJoin']['party_master'] = "party_master.id = trans_ledger.vou_acc_id";

        $data['where']['trans_ledger.entry_type'] = $data['entry_type'];
        $data['order_by']['trans_ledger.trans_date'] = "DESC";
        $data['order_by']['trans_ledger.id'] = "DESC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "trans_ledger.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(trans_ledger.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "trans_ledger.amount";
        $data['searchCol'][] = "trans_ledger.remark'";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();
            
            // Filter the array based on 'cr_dr' value 'CR'
            $filteredCrArray = array_filter($data['itemData'], fn($item) => $item['cr_dr'] === 'CR');
            $filteredDrArray = array_filter($data['itemData'], fn($item) => $item['cr_dr'] === 'DR');

            // Get the first key of the filtered array
            $firstCrKey = key($filteredCrArray);
            $firstDrKey = key($filteredDrArray);
            
            $data['vou_acc_id'] = $data['itemData'][$firstDrKey]['acc_id'];
            $data['opp_acc_id'] = $data['itemData'][$firstCrKey]['acc_id'];
            $data['party_id'] = $data['itemData'][$firstCrKey]['acc_id'];
            $data['total_amount'] = $data['itemData'][$firstDrKey]['debit_amount'];	
            $data['taxable_amount'] = $data['itemData'][$firstDrKey]['debit_amount'];
            $data['net_amount'] = $data['itemData'][$firstDrKey]['debit_amount'];
            $data['ledger_eff'] = 1;

            $itemData = $data['itemData'];unset($data['itemData']);
            
            $result = $this->store($this->transMain,$data,"Journal Entry");
            
            //remove old trans
            $this->transMainModel->deleteLedgerTrans($result['id']);

            foreach($itemData as $row):
                
                $transLedgerData = [
                    'id'=>"",
                    'entry_type'=>$data['entry_type'],
                    'trans_main_id'=>$result['id'],
                    'trans_date'=>$data['trans_date'],
                    'trans_number'=>$data['trans_number'],
                    'doc_date'=>$data['trans_date'],
                    'doc_no'=>$data['trans_number'],
                    'c_or_d'=>$row['cr_dr'],
                    'vou_name_l'=>$data['vou_name_l'],
                    'vou_name_s'=>$data['vou_name_s'],
                    'remark'=>$row['item_remark']
                ];

                $transLedgerData['vou_acc_id'] = $row['acc_id'];
                if($row['cr_dr'] == "DR"):
                    $transLedgerData['opp_acc_id'] = $itemData[$firstCrKey]['acc_id'];
                    $transLedgerData['amount'] = $row['debit_amount'];
                else:
                    $transLedgerData['opp_acc_id'] = $itemData[$firstDrKey]['acc_id'];
                    $transLedgerData['amount'] = $row['credit_amount'];
                endif;

                $this->transMainModel->storeTransLedger($transLedgerData);
            endforeach;

            if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Throwable $e){
			$this->db->trans_rollback();
			return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}		
    }

    public function getJournalEntry($id){
        $queryData = array();
        $queryData['tableName']  = $this->transMain;
        $queryData['where']['id'] = $id;
        $result = $this->row($queryData);

        $result->ledgerData = $this->getLedgerTrans($id);
        return $result;
    }

    public function getLedgerTrans($id){
        $queryData = array();
        $queryData['tableName']  = $this->transLedger;
        $queryData['select'] = "trans_ledger.*,party_master.party_name as ledger_name,trans_ledger.vou_acc_id as acc_id";
        $queryData['leftJoin']['party_master'] = "party_master.id = trans_ledger.vou_acc_id";
        $queryData['where']['trans_ledger.trans_main_id'] = $id;
        $result = $this->rows($queryData);
        return $result;
    }

    public function delete($id){
		try{
            $this->db->trans_begin();

			$result = $this->trash($this->transMain,['id'=>$id],'Journal Entry');
            $this->transMainModel->deleteLedgerTrans($id);

			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Throwable $e){
			$this->db->trans_rollback();
		    return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}	
	}
}
?>