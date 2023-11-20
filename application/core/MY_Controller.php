<?php 
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class MY_Controller extends CI_Controller{

	public $termsTypeArray = ["Purchase","Sales"];
	public $gstPer = ['0'=>"NILL",'0.10'=>'0.10 %','0.25'=>"0.25 %",'1'=>"1 %",'3'=>"3%",'5'=>"5 %","6"=>"6 %","7.50"=>"7.50 %",'12'=>"12 %",'18'=>"18 %",'28'=>"28 %"];
	public $deptCategory = ["1"=>"Admin","2"=>"HR","3"=>"Purchase","4"=>"Sales","5"=>"Store","6"=>"QC","7"=>"General","8"=>"Machining"];
	public $empRole = ["1"=>"Admin",/* "2"=>"Production Manager", */"3"=>"Accountant","4"=>"Sales Manager","5"=>"Purchase Manager","6"=>"Employee"];
    public $gender = ["M"=>"Male","F"=>"Female","O"=>"Other"];
    public $systemDesignation = [1=>"Machine Operator",2=>"Line Inspector",3=>"Setter Inspector",4=>"Process Setter",5=>"FQC Inspector",6=>"Sale Executive",7=>"Designer",8=>"Production Executive"];
	public $maritalStatus = ["Married","UnMarried","Widow"];
	public $empType = [1=>"Permanent (Fix)",2=>"Permanent (Hourly)",3=>"Temporary"];
	public $empGrade = ["Grade A","Grade B","Grade C","Grade D"];
	//public $paymentMode = ['CASH','CHEQUE','NEFT','UPI'];
	public $paymentMode = ['CASH','CHEQUE','NEFT/RTGS/IMPS ','CARD','UPI'];

	public $partyCategory = [1=>'Customer',2=>'Supplier',3=>'Karigar',4=>'Ledger'];
	public $suppliedType = [1=>'Goods',2=>'Services',3=>'Goods & Services'];
	public $gstRegistrationTypes = [1=>'Registerd',2=>'Composition',3=>'Overseas',4=>'Un-Registerd',5=>"Consumer"];
	public $automotiveArray = ["1" => 'Yes', "2" => "No"];
	public $vendorTypes = ['Manufacture', 'Service'];

	public $itemTypes = [1 => "Finish Goods", 2 => "Consumable", 3 => "Raw Material"/* , 4 => "Capital Goods", 5 => "Machineries", 6 => "Instruments", 7 => "Gauges", 8 => "Services", 9 => "Packing Material", 10 => "Scrap" */];
	public $stockTypes = [0=>"None",1=>'Batch Wise',2=>"Serial Wise"];
	public $fgColorCode = ["WHITE"=>"W","GREY"=>"G"];
	public $fgCapacity = ["3 Ton"=>"3T","5 Ton"=>"5T"];

	//Crm Status
	public $leadFrom = ["Facebook","Indiamart","Instagram","Facebook Comments","Trade India","Exporter India","Facebook Admanager"];
	public $leadStatus = ["Initited", "Appointment Fixed", "Qualified", "Enquiry Generated", "Proposal", "In Negotiation", "Confirm", "Close"];
	public $appointmentMode = [1 => "Phone", 2 => "Email", 3 => "Visit", 4 => "Other"];
	public $followupStage = [0 => 'Open', 1 => "Confirmed", 2 => "Hold", 3 => "Won", 4 => "Lost", 5 => "Enquiry" , 6 => "Quatation"];

	//Types of Invoice
	public $purchaseTypeCodes = ["'PURGSTACC'","'PURIGSTACC'","'PURJOBGSTACC'","'PURJOBIGSTACC'","'PURURDGSTACC'","'PURURDIGSTACC'","'PURTFACC'","'PUREXEMPTEDTFACC'","'IMPORTACC'","'IMPORTSACC'","'SEZRACC'","'SEZSGSTACC'","'SEZSTFACC'","'DEEMEDEXP'"];

	public $salesTypeCodes = ["'SALESGSTACC'","'SALESIGSTACC'","'SALESJOBGSTACC'","'SALESJOBIGSTACC'","'SALESTFACC'","'SALESEXEMPTEDTFACC'","'EXPORTGSTACC'","'EXPORTTFACC'","'SEZSGSTACC'","'SEZSTFACC'","'DEEMEDEXP'"];

	//Inward Types
	public $inwardTypes = ["Gold","Gold Items","Gold + Diamond Items","Silver","Silver Items","Loos Diamond","Diamond Items","Lab Grown Diamond","Lab Grown Diamond + Gold Items","Platinum Items","Platinum + Diamond Items","Palladium","Platinum + Gold + Diamond Items"];
	
	public function __construct(){
		parent::__construct();
		//echo '<br><br><hr><h1 style="text-align:center;color:red;">We are sorry!<br>Your ERP is Updating New Features</h1><hr><h2 style="text-align:center;color:green;">Thanks For Co-operate</h1>';exit;
		$this->isLoggedin();
		$this->data['headData'] = new StdClass;
		$this->load->library('form_validation');
		
		$this->load->model('masterModel');
		$this->load->model('DashboardModel','dashboard');
		$this->load->model('PermissionModel','permission');
		$this->load->model('StockTransModel','itemStock');

		/* Configration Models */
		$this->load->model("TermsModel","terms");
		$this->load->model("TransportModel","transport");
		$this->load->model("HsnMasterModel","hsnModel");
		$this->load->model("MaterialGradeModel","materialGrade");
		$this->load->model("VehicleTypeModel","vehicleType");
		$this->load->model('MakingChargeModel','makingCharge');

		/* HR Models */
		$this->load->model("hr/DepartmentModel","department");
		$this->load->model("hr/DesignationModel","designation");
		$this->load->model("hr/EmployeeCategoryModel","employeeCategory");
		$this->load->model("hr/ShiftModel","shiftModel");
		$this->load->model("hr/EmployeeModel","employee");

		/* Master Model */
		$this->load->model('PartyModel','party');
		$this->load->model('DiamondQualityModel','diamondQuality');
		$this->load->model('ItemCategoryModel','itemCategory');
		$this->load->model('BrandMasterModel','brandMaster');
		$this->load->model('SizeMasterModel','sizeMaster');
		$this->load->model('PurityModel','purity');
		$this->load->model('FineModel','fine');
		$this->load->model('PolishModel','polish');
		$this->load->model('ColorModel','color');
		$this->load->model('ClarityModel','clarity');
		$this->load->model('ItemModel','item');

		/* Sales Model */
		$this->load->model('TransactionMainModel','transMainModel');
		$this->load->model('TaxMasterModel','taxMaster');
		$this->load->model('ExpenseMasterModel','expenseMaster');
		$this->load->model('LeadModel','leads');
		$this->load->model('SalesOrderModel','salesOrder');
		$this->load->model('SalesEnquiryModel','salesEnquiry');
		$this->load->model('SalesQuotationModel','salesQuotation');
		$this->load->model('DeliveryChallanModel','deliveryChallan');

		/* Purchase Model */
		$this->load->model('PurchaseOrderModel','purchaseOrder');
		$this->load->model('PurchaseIndentModel','purchaseIndent');
		$this->load->model('InwardReceiptModel','inwardReceipt');

		/* Store Model */
		$this->load->model('StoreLocationModel','storeLocation');
		$this->load->model('MaterialIssueModel','materialIssue');
		$this->load->model('GateEntryModel','gateEntry');
		$this->load->model('GateInwardModel','gateInward');

		/* Accounting Model */
		$this->load->model("PurchaseInvoiceModel","purchaseInvoice");
		$this->load->model("DebitNoteModel","debitNote");
		$this->load->model("SalesInvoiceModel","salesInvoice");
		$this->load->model("CreditNoteModel","creditNote");
		$this->load->model("GstExpenseModel","gstExpense");
		$this->load->model("GstIncomeModel","gstIncome");
		$this->load->model("JournalEntryModel","journalEntry");
		$this->load->model("PaymentVoucherModel","paymentVoucher");
		$this->load->model("ProformaInvoiceModel","proformaInvoice");

		/* Store Report Model */
		$this->load->model('report/StoreReportModel','storeReport');		

		/* Accounting Report Model */
		$this->load->model('report/AccountingReportModel','accountReport');

		/* GST Report Model */
		$this->load->model("report/GstReportModel","gstReport");

		/* Estimation Model [Cash Entry] */
		$this->load->model("EstimateModel",'estimate');

		$this->data['compamyInfoMaster'] = $this->masterModel->getCompanyInfo(); 
		$this->setSessionVariables(["masterModel","dashboard","permission","terms","transport","hsnModel","materialGrade","itemCategory","brandMaster","sizeMaster","purity","fine","polish", "color", "clarity", "item","department","designation","employeeCategory","shiftModel","employee","party","transMainModel","taxMaster","expenseMaster","salesOrder","purchaseOrder","purchaseIndent","vehicleType","storeLocation","gateEntry","gateInward","salesInvoice","estimate","paymentVoucher","leads","salesEnquiry","salesQuotation","gstExpense","gstIncome","journalEntry","creditNote","debitNote","inwardReceipt","diamondQuality","proformaInvoice","deliveryChallan","materialIssue","makingCharge"]);
	}

	public function setSessionVariables($modelNames){
		$this->data['dates'] = $this->dates = explode(' AND ',$this->session->userdata('financialYear'));
        $this->data['shortYear'] = $this->shortYear = date('y',strtotime($this->dates[0])).'-'.date('y',strtotime($this->dates[1]));
		$this->data['startYear'] = $this->startYear = date('Y',strtotime($this->dates[0]));
		$this->data['endYear'] = $this->endYear = date('Y',strtotime($this->dates[1]));
		$this->data['startYearDate'] = $this->startYearDate = date('Y-m-d',strtotime($this->dates[0]));
		$this->data['endYearDate'] = $this->endYearDate = date('Y-m-d',strtotime($this->dates[1]));

		$this->loginId = $this->session->userdata('loginId');
		$this->userName = $this->session->userdata('user_name');
		$this->userRole = $this->session->userdata('role');
		$this->userRoleName = $this->session->userdata('roleName');

		$this->RTD_STORE = $this->session->userdata('RTD_STORE');

		$models = $modelNames;
		foreach($models as $modelName):
			$modelName = trim($modelName);
			$this->{$modelName}->dates = $this->dates;
			$this->{$modelName}->shortYear = $this->shortYear;
			$this->{$modelName}->startYear = $this->startYear;
			$this->{$modelName}->endYear = $this->endYear;
			$this->{$modelName}->startYearDate = $this->startYearDate;
			$this->{$modelName}->endYearDate = $this->endYearDate;

			$this->{$modelName}->loginId = $this->loginId;
			$this->{$modelName}->userName = $this->userName;
			$this->{$modelName}->userRole = $this->userRole;
			$this->{$modelName}->userRoleName = $this->userRoleName;

			$this->{$modelName}->RTD_STORE = $this->RTD_STORE;
		endforeach;
		return true;
	}
	
	public function isLoggedin(){
		if(!$this->session->userdata("loginId")):
			echo '<script>window.location.href="'.base_url().'";</script>';
		endif;
		return true;
	}
	
	public function printJson($data){
		print json_encode($data);exit;
	}
	
	public function checkGrants($url){
		$empPer = $this->session->userdata('emp_permission');
		if(!array_key_exists($url,$empPer)):
			redirect(base_url('error_403'));
		endif;
		return true;
	}
	
	/**** Generate QR Code ****/
	public function getQRCode($qrData,$dir,$file_name){
		if(isset($qrData) AND isset($file_name)):
			$file_name .= '.png';
			/* Load QR Code Library */
			$this->load->library('ciqrcode');
			
			if (!file_exists($dir)) {mkdir($dir, 0775, true);}

			/* QR Configuration  */
			$config['cacheable']    = true;
			$config['imagedir']     = $dir;
			$config['quality']      = true;
			$config['size']         = '1024';
			$config['black']        = array(255,255,255);
			$config['white']        = array(255,255,255);
			$this->ciqrcode->initialize($config);
	  
			/* QR Data  */
			$params['data']     = $qrData;
			$params['level']    = 'L';
			$params['size']     = 10;
			$params['savename'] = FCPATH.$config['imagedir']. $file_name;
			
			$this->ciqrcode->generate($params);

			return $dir. $file_name;
		endif;

		return false;
	}

	public function getTableHeader(){
		$data = $this->input->post();

		$response = call_user_func_array($data['hp_fn_name'],[$data['page']]);
		
		$result['theads'] = (isset($response[0])) ? $response[0] : '';
		$result['textAlign'] = (isset($response[1])) ? $response[1] : '';
		$result['srnoPosition'] = (isset($response[2])) ? $response[2] : 1;
		$result['sortable'] = (isset($response[3])) ? $response[3] : '';

		$this->printJson(['status'=>1,'data'=>$result]);
	}

	public function getPartyInvoiceList(){
        $data = $this->input->post();
        $this->printJson($this->transMainModel->getPartyInvoiceList($data));
    }
}
?>