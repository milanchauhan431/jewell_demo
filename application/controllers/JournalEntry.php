<?php
class JournalEntry extends MY_Controller{
    private $indexPage = "journal_entry/index";
    private $form = "journal_entry/form";    

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Journal Entry";
		$this->data['headData']->controller = "journalEntry";        
        $this->data['headData']->pageUrl = "journalEntry";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'journalEntry']);
	}

    public function index(){
        $this->data['tableHeader'] = getAccountingDtHeader("journalEntry");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();$data['status'] = $status;
        $data['entry_type'] = $this->data['entryData']->id;
        $result = $this->journalEntry->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getJournalEntryData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addJournalEntry(){
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList();
        $this->load->view($this->form, $this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if(empty($data['itemData'])):
            $errorMessage['item_name_error'] = 'Entry is required.';
        else:
            if(array_sum(array_column($data['itemData'],'credit_amount')) != array_sum(array_column($data['itemData'],'debit_amount'))):
                $errorMessage['total_cr_dr_amt'] = "Cr. Amount and Dr. Amount mismatch.";
            endif;
        endif;
        
        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
                        
            if(empty($data['id'])):
                $data['trans_prefix'] = $this->data['entryData']->trans_prefix;
                $data['trans_no'] = $this->transMainModel->nextTransNo($data['entry_type']);
                $data['trans_number'] = $data['trans_prefix'].$data['trans_no'];
            endif;

            $data['vou_name_l'] = $this->data['entryData']->vou_name_long;
            $data['vou_name_s'] = $this->data['entryData']->vou_name_short;

            $this->printJson($this->journalEntry->save($data));
        endif;
    }

    public function edit($id){
        $this->data['dataRow'] = $this->journalEntry->getJournalEntry($id);
        $this->data['partyList'] = $this->party->getPartyList();
        $this->load->view($this->form, $this->data);
    }

    public function delete(){
		$id = $this->input->post('id');
		if(empty($id)):
			$this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
		else:
			$this->printJson($this->journalEntry->delete($id));
		endif;
	}
}
?>