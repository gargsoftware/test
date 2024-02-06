<?php
date_default_timezone_set('Asia/Kolkata');
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *21640194    01642
 */
class Fx
{
	public $pageName;
	public $pageController;
	public $pageID;
	public $clientDetails;
	public $compDetails;
	public $rights;
	public $masterClient;
	public $clientId;
	public $clientCompId;
	public $clientCompDb;
	public $planDetails;
	public $accMenu;
	public $gstMenu;
	public $favMenu;
	public $favMenuList;
	public $paginationRecords;
	public $is_agent;
	public $hide_phone;
	public $barcode_billing;
	public $barcode_on_srno;
	public $is_export = 0;
	public $is_tcs_applicable = 0;
	public $ledger_tcs_amount = 0.00;
	public $notIncludeFollowup = array('setdata', 'login');
	public $finyr_startdate, $finyr_enddate;

	public $crmName = "";
	public $crmLogo = "";
	public $crmSupportNo = "";
	public $crmLoginBG = "";
	public $crmFooterText = '';
	public $crmPdfText = '';

	public $leadTemplate = "default";
	public $leadPdfTitle = "Proposal";
	public $saleorderTemplate = "default";
	public $change_tax_on_invoice = 0;
	public $multipleHostName = array("acc.sansoftwares.com", "acc.vedaerp.com", "192.168.1.163", "192.168.1.155", 'acc.me', 'vedas2.sansoftwares.com');
	public $reset_password_status = false;

	public $dial_api = '';
	public $mobile_prefix = '';

	public $ext_no = '';
	public $did_no = '';
	public $dialer_method = '';
	public $einv_enable = 0;
	public $gstr2a_enable = 0;
	public $calendar_event_count = 6;
	public $is_paymentgateway = 0;
	public $childTreeIds = array();
	public $dbPrventWords = "drop |delete |update |truncate |insert |create ";
	public $baseurl;
	public $branches = [];

	//contract_due_das=$this->fx->compDetails->contract_due_days

	public $templateTypeArray = array('contract_before' => 'Contract Before', 'contract_after' => 'Contract After', 'invoice' => 'Invoice', 'lead' => 'Lead', 'contract_pdf' => 'Contract Pdf', 'ledger_reminder' => 'Ledger Reminder', 'sale_order' => 'Sale Order', 'purchase_order' => 'Purchase Order', 'reminders' => 'Reminders', 'create_reminders' => 'Create Reminder', 'lead_status' => 'Lead Status', 'followup_reminder_agent' => 'Folowup reminder agent', 'payment_cash' => 'Cash Payments', 'receipt_cash' => 'Cash Receipts', 'payment_bank' => 'Bank Payments', 'receipt_bank' => 'Bank Receipts', 'journal_book' => 'Journal Book');

	public $templateArray = array(
		'contract' => array(array('{{branch_name}}' => 'Branch Name', '{{party_name}}' => 'Party Name', '{{party_email}}' => 'Party Email', '{{reference_name}}' => 'Refrence Name', '{{sale_date}}' => 'Sale Date', '{{due_date}}' => 'Due Date', '{{end_date}}' => 'Contract End Date', '{{contract_type}}' => 'Contract Type', '{{invoice_no}}' => 'Invoice No', '{{total_amount}}' => 'Total Contract Amount', '{{contract_percentage}}' => 'Contract Percentage', '{{contract_amount}}' => 'Contract Amount', '{{share_url}}' => 'Share Contract By URL'), array('##ITEMSTART##' => 'Contract Item Loop Start', '##ITEMEND##' => 'Contract Item Loop End', '{{item_name}}' => 'Item Name', '{{item_description}}' => 'Item Description', '{{item_rate}}' => 'Item rate', '{{item_quantity}}' => 'Item Quantity', '{{item_amount}}' => 'Item Amount')),
		// 'lead' => array( array('{{lead_id}}' => 'Lead No', '{{master_lead_id}}' => 'Master Lead No',
		// 	'{{branch_name}}' => 'Branch Name', '{{party_name}}' => 'Party Name', '{{party_email}}' => 'Party Email', '{{party_mobile}}' => 'Party Mobile', '{{party_contact_person}}' => 'Party Contact Person', '{{party_address}}' => 'Party Address', '{{party_state_name}}' => 'Party State', '{{agent_name}}' => 'Agent Name', '{{lead_date}}' => 'Lead Date', '{{lead_source_name}}' => 'Lead Source Name', '{{lead_type_name}}' => 'Lead Type Name', '{{lead_status_name}}' => 'Lead Status', '{{payment_terms}}' => 'Payment Terms', '{{packing}}' => 'Packing', '{{ship_to}}' => 'Ship To', '{{total_amount}}' => 'Total Amount', '{{total_net_amount}}' => 'Net Amount', '{{notes}}' => 'Notes','{{share_url}}' => 'Share Lead By URL'), array('##ITEMSTART##' => 'Contract Item Loop Start', '##ITEMEND##' => 'Contract Item Loop End', '{{item_name}}' => 'Item Name', '{{item_description}}' => 'Item Description', '{{item_rate}}' => 'Item rate', '{{item_quantity}}' => 'Item Quantity', '{{item_amount}}' => 'Item Amount')),
		'ledger_reminder' => array(array('{{party_name}}' => 'Party Name', '{{party_email}}' => 'Party Email', '{{party_mobile}}' => 'Party Mobile', '{{current_balance}}' => 'Current Balance', '{{party_contact_person}}' => 'Party Contact Person', '{{fin_year}}' => 'Financial Year', '{{payment_url}}' => 'Payment URL')),
		'invoice' => array(array('{{branch_name}}' => 'Branch Name', '{{invoice_no}}' => 'Invoice No', '{{invoice_date}}' => 'Invoice Date', '{{so_no}}' => 'Sale Order No', '{{so_date}}' => 'Sale Order Date', '{{po_no}}' => 'PO No', '{{po_date}}' => 'Po Date', '{{acc_head}}' => 'Acc Head', '{{acc_head_address}}' => 'Acc Head Address', '{{acc_head_gstin}}' => 'Acc Head GSTIN', '{{ship_to_address}}' => 'Ship To Address', '{{ship_to_gstin}}' => 'Ship To GSTIN', '{{eway_bill_no}}' => 'Ewaybill No', '{{ewaybill_date}}' => 'Ewaybill Date', '{{vehicle_no}}' => 'Vehicle no', '{{total_no_of_item}}' => 'Total Item Count', '{{total_gross_amount}}' => 'Gross Amount', '{{total_discount_amount}}' => 'Discount Amount', '{{total_tax_amount}}' => 'Tax Amount', '{{round_off_amount}}' => 'Round Off Amount', '{{total_net_amount}}' => 'Net Amount', '{{payment_term}}' => 'Payment Terms', '{{orc_amount}}' => 'ORC Amount', '{{contact_person}}' => 'Acc Head Contact Person', '{{agent_name}}' => 'Agent Name', '{{share_url}}' => 'Share Lead By URL', '{{payment_url}}' => 'Payment URL'), array('##ITEMSTART##' => 'Invoice Item Loop Start', '##ITEMEND##' => 'Invoice Item Loop End', '{{item_name}}' => 'Item Name', '{{item_desc}}' => 'Item Description', '{{sale_acc}}' => 'Sales Account', '{{hsn_sac_code}}' => 'HSN Code', '{{rate}}' => 'Item rate', '{{sr_no}}' => 'Sr No', '{{discount_percentage}}' => 'Discount Percentage', '{{discount_amount}}' => 'Discount Amount', '{{qty}}' => 'Quantity', '{{free_qty}}' => 'Free Quantity', '{{unit}}' => 'Unit', '{{amount}}' => 'Amount', '{{tax_percentage}}' => 'Tax Percentage', '{{tax_amount}}' => 'Tax Amount', '{{total_amount}}' => 'Total Amount')),
		'sale_order' => array(
			array(
				'{{branch_name}}' => 'Branch Name',
				'{{so_no}}' => 'SO No.',
				'{{so_date}}' => 'SO Date',
				'{{po_no}}' => 'PO No',
				'{{po_date}}' => 'PO Date',
				'{{acc_head}}' => 'Acc Head',
				'{{payment_terms}}' => 'Payment Terms',
				'{{delivery_terms}}' => 'Delivery Terms',
				'{{packing}}' => 'Packing',
				'{{ship_to}}' => 'Ship To',
				'{{transport}}' => 'Transport',
				'{{insurance}}' => 'Insurance',
				'{{freight}}' => 'Freight',
				'{{valid_from}}' => 'Valid From',
				'{{valid_to}}' => 'Valid To',
				'{{close_date}}' => 'Close Date',
				'{{total_gross_amount}}' => 'Gross Amount',
				'{{total_discount_amount}}' => 'Total Discount Amt',
				'{{total_amount}}' => 'Total Amount',
				'{{total_tax_amount}}' => 'Total Tax Amount',
				'{{round_off_amount}}' => 'Round Off Amount',
				'{{total_net_amount}}' => 'Net Amount',
				'{{notes}}' => 'Notes',
				'{{pdf_title}}' => 'PDF Title',
				'{{share_url}}' => 'Share Lead By URL',
				'{{payment_url}}' => 'Payment URL',
				'{{contact_person}}' => 'Acc Head Contact Person'
			),
			array('##ITEMSTART##' => 'Sale Order Item Loop Start', '##ITEMEND##' => 'Sale Order Item Loop End', '{{item_name}}' => 'Item Name', '{{item_desc}}' => 'Item Description', '{{hsn_sac_code}}' => 'HSN Code', '{{rate}}' => 'Item rate', '{{discount_percentage}}' => 'Discount Percentage', '{{discount_amount}}' => 'Discount Amount', '{{qty}}' => 'Quantity', '{{free_qty}}' => 'Free Quantity', '{{unit}}' => 'Unit', '{{amount}}' => 'Amount', '{{tax_percentage}}' => 'Tax Percentage', '{{tax_amount}}' => 'Tax Amount', '{{total_amount}}' => 'Total Amount')
		),
		'lead' => array(
			array(
				'{{lead_id}}' => 'Lead No', '{{master_lead_id}}' => 'Master Lead No', '{{branch_name}}' => 'Branch Name', '{{party_name}}' => 'Party Name', '{{party_email}}' => 'Party Email', '{{party_mobile}}' => 'Party Mobile', '{{party_contact_person}}' => 'Party Contact Person', '{{party_address}}' => 'Party Address', '{{party_state_name}}' => 'Party State', '{{agent_name}}' => 'Agent Name', '{{lead_date}}' => 'Lead Date', '{{lead_source_name}}' => 'Lead Source Name', '{{lead_type_name}}' => 'Lead Type Name', '{{lead_status_name}}' => 'Lead Status', '{{payment_terms}}' => 'Payment Terms', '{{packing}}' => 'Packing', '{{ship_to}}' => 'Ship To', '{{total_amount}}' => 'Total Amount', '{{total_net_amount}}' => 'Net Amount', '{{notes}}' => 'Notes', '{{share_url}}' => 'Share Lead By URL',
				'{{lead_status_update_time}}' => 'Last Status Update Time',
				'{{next_followup_date}}' => 'Next Followup Date',
				'{{next_followup_date_time}}' => 'Next Followup Date Time',
				'{{status_comment}}' => 'Status Comment',
				'{{payment_url}}' => 'Payment URL'
			),
			array('##ITEMSTART##' => 'Item Loop Start', '##ITEMEND##' => 'Item Loop End', '{{item_name}}' => 'Item Name', '{{item_description}}' => 'Item Description', '{{item_rate}}' => 'Item rate', '{{item_quantity}}' => 'Item Quantity', '{{item_amount}}' => 'Item Amount')
		),
		'purchase_order' => array(
			array(
				'{{branch_name}}' => 'Branch Name',
				'{{po_no}}' => 'PO No.',
				'{{po_date}}' => 'PO Date',
				'{{po_date}}' => 'PO Date',
				'{{acc_head}}' => 'Acc Head',
				'{{state_name}}' => 'Acc Head State',
				'{{payment_terms}}' => 'Payment Terms',
				'{{delivery_terms}}' => 'Delivery Terms',
				'{{packing}}' => 'Packing',
				'{{ship_to}}' => 'Ship To',
				'{{transport}}' => 'Transport',
				'{{insurance}}' => 'Insurance',
				'{{freight}}' => 'Freight',
				'{{valid_from}}' => 'Valid From',
				'{{valid_to}}' => 'Valid To',
				'{{close_date}}' => 'Close Date',
				'{{total_gross_amount}}' => 'Gross Amount',
				'{{total_discount_amount}}' => 'Total Discount Amt',
				'{{total_amount}}' => 'Total Amount',
				'{{total_tax_amount}}' => 'Total Tax Amount',
				'{{round_off_amount}}' => 'Round Off Amount',
				'{{total_net_amount}}' => 'Net Amount',
				'{{notes}}' => 'Notes',
				'{{share_url}}' => 'Share Contract By URL',
				'{{contact_person}}' => 'Acc Head Contact Person'
			),
			array('##ITEMSTART##' => 'Purchase Order Item Loop Start', '##ITEMEND##' => 'Purchase Order Item Loop End', '{{item_name}}' => 'Item Name', '{{item_desc}}' => 'Item Description', '{{hsn_sac_code}}' => 'HSN Code', '{{rate}}' => 'Item rate', '{{discount_percentage}}' => 'Discount Percentage', '{{discount_amount}}' => 'Discount Amount', '{{qty}}' => 'Quantity', '{{free_qty}}' => 'Free Quantity', '{{unit}}' => 'Unit', '{{amount}}' => 'Amount', '{{tax_percentage}}' => 'Tax Percentage', '{{tax_amount}}' => 'Tax Amount', '{{total_amount}}' => 'Total Amount')
		),
		'Reminders' => array(array(
			"{{Reminder}}" => "Reminder Details",
			"{{Type}}" => "Reminder Type",
			"{{ReminderAt}}" => "Reminder Time",
			"{{RepetType}}" => "Reminder Repet Type",
			"{{Mobile}}" => "Reminder Mobile",
			"{{Email}}" => "Reminder Email",
			"{{whatsApp}}" => "WhatsApp Number",
			"{{contactName}}" => "Contact Name",
			"{{contactPhone}}" => "Contact Phone",
			"{{ContactEmail}}" => "Contact Email",
			"{{ContactCompany}}" => "Contact Company",
			"{{logRemark}}" => "Status Remark",
			"{{logDate}}" => "Current Status Date",
			"{{logStatus}}" => "Reminder Current Status",
			"{{alertTime}}" => "Alert Time",
			"{{alertTimeUnit}}" => "Alert Time Unit"
		)),
		'payment_cash' => array(
			array(
				'{{branch_name}}' => 'Branch Name',
				'{{voucher_date}}' => 'Voucher Date',
				'{{voucher_no}}' => 'Voucher No.',
				'{{book}}' => 'Acc Head',
				'{{total_amount}}' => 'Total Amount',
				'{{created_by}}' => 'Created By',
				'{{created_at}}' => 'Created At',
				'{{share_url}}' => 'Share Voucher By URL'
			),
			array(
				'##ITEMSTART##' => 'Cash Payment Item Loop Start',
				'##ITEMEND##' => 'Cash Payment Item Loop End',
				'{{acc_head}}' => 'ACC. DESCRIPTION',
				'{{particular}}' => 'PARTICULAR',
				'{{amount}}' => 'AMOUNT',
			)
		),
		'receipt_cash' => array(
			array(
				'{{branch_name}}' => 'Branch Name',
				'{{voucher_date}}' => 'Voucher Date',
				'{{voucher_no}}' => 'Voucher No.',
				'{{book}}' => 'Acc Head',
				'{{total_amount}}' => 'Total Amount',
				'{{created_by}}' => 'Created By',
				'{{created_at}}' => 'Created At',
				'{{share_url}}' => 'Share Voucher By URL'
			),
			array(
				'##ITEMSTART##' => 'Cash Receipt Item Loop Start',
				'##ITEMEND##' => 'Cash Receipt Item Loop End',
				'{{acc_head}}' => 'ACC. DESCRIPTION',
				'{{particular}}' => 'PARTICULAR',
				'{{amount}}' => 'AMOUNT',
			)
		),
		'payment_bank' => array(
			array(
				'{{branch_name}}' => 'Branch Name',
				'{{voucher_date}}' => 'Voucher Date',
				'{{voucher_no}}' => 'Voucher No.',
				'{{cheque_no}}' => 'Cheque No.',
				'{{cheque_date}}' => 'Cheque Date',
				'{{book}}' => 'Acc Head',
				'{{total_amount}}' => 'Total Amount',
				'{{created_by}}' => 'Created By',
				'{{created_at}}' => 'Created At',
				'{{share_url}}' => 'Share Voucher By URL'
			),
			array(
				'##ITEMSTART##' => 'Bank Payment Item Loop Start',
				'##ITEMEND##' => 'Bank Payment Item Loop End',
				'{{acc_head}}' => 'ACC. DESCRIPTION',
				'{{particular}}' => 'PARTICULAR',
				'{{amount}}' => 'AMOUNT',
			)
		),
		'receipt_bank' => array(
			array(
				'{{branch_name}}' => 'Branch Name',
				'{{voucher_date}}' => 'Voucher Date',
				'{{voucher_no}}' => 'Voucher No.',
				'{{cheque_no}}' => 'Cheque No.',
				'{{cheque_date}}' => 'Cheque Date',
				'{{book}}' => 'Acc Head',
				'{{total_amount}}' => 'Total Amount',
				'{{created_by}}' => 'Created By',
				'{{created_at}}' => 'Created At',
				'{{share_url}}' => 'Share Voucher By URL'
			),
			array(
				'##ITEMSTART##' => 'Bank Payment Item Loop Start',
				'##ITEMEND##' => 'Bank Payment Item Loop End',
				'{{acc_head}}' => 'ACC. DESCRIPTION',
				'{{particular}}' => 'PARTICULAR',
				'{{amount}}' => 'AMOUNT',
			)
		),
		'journal_book' => array(
			array(
				'{{branch_name}}' => 'Branch Name',
				'{{voucher_date}}' => 'Voucher Date',
				'{{voucher_no}}' => 'Voucher No.',
				'{{total_amount}}' => 'Total Amount',
				'{{created_by}}' => 'Created By',
				'{{created_at}}' => 'Created At',
				'{{share_url}}' => 'Share Voucher By URL'
			),
			array(
				'##ITEMSTART##' => 'Journal Book Item Loop Start',
				'##ITEMEND##' => 'Bank Payment Item Loop End',
				'{{acc_head}}' => 'ACC. DESCRIPTION',
				'{{particular}}' => 'PARTICULAR',
				'{{amount}}' => 'AMOUNT',
			)
		),
	);

	function __construct()
	{
		// $this->validate();
		//$this->templateArray['lead_status_client'] = $this->templateArray['client_lead'];
		$this->templateArray['lead_status'] = $this->templateArray['lead'];
		$this->templateArray['followup_reminder_agent'] = $this->templateArray['lead'];
		$this->templateArray['create_reminders'] = $this->templateArray['Reminders'];
	}

	static function pr($ar, $ex = 0)
	{
		echo '<pre>';
		print_r($ar);
		echo '</pre>';
		if ($ex == 1) {
			exit;
		}
	}

	// Used to set the logo and portal name in the software
	public function setCRMDetail()
	{
		define('WEBSITE_TITLE_PREFIX', ((!empty($_SESSION['CRMDetail']->crm_name)) ? $_SESSION['CRMDetail']->crm_name : 'VedaERP') . '-');

		$this->crmName = ((!empty($_SESSION['CRMDetail']->crm_name)) ? $_SESSION['CRMDetail']->crm_name : 'VedaERP');

		$this->crmDomain = ((!empty($_SESSION['CRMDetail']->crm_domain)) ? $_SESSION['CRMDetail']->crm_domain : 'https://www.vedaerp.com/');

		$this->policyTerms = ((!empty($_SESSION['CRMDetail']->policy_terms_url)) ? $_SESSION['CRMDetail']->policy_terms_url : 'https://www.vedaerp.com/terms-condition/');

		$this->crmLogo = ((isset($_SESSION['CRMDetail']->logo_image)) ? ($_SESSION['CRMDetail']->logo_image) : base_url('html/images/san-logo.png'));

		$this->crmLoginBG = ((!empty($_SESSION['CRMDetail']->login_bg_image)) ? ('uploads/crm_logo/' . $_SESSION['CRMDetail']->login_bg_image) : '');

		$this->crmSupportNo = ((!empty($_SESSION['CRMDetail']->support_phone)) ? $_SESSION['CRMDetail']->support_phone : '0124-4310735');

		$this->crmFooterText =  ((!empty($_SESSION['CRMDetail']->footer_text)) ? $_SESSION['CRMDetail']->footer_text : "$this->crmName | Support No : $this->crmSupportNo ");

		$this->crmPdfText = ((!empty($_SESSION['CRMDetail']->pdf_text)) ? $_SESSION['CRMDetail']->pdf_text : "Powered By : $this->crmName || Call Us : $this->crmSupportNo");
		// fx::pr($_SESSION['CRMDetail']->pdf_text,1);
	}



	public function escPost()
	{
		$this->CI = &get_instance();

		if (!empty($_POST))
			$_POST = $this->CI->db->escape_str($_POST);

		if (!empty($_GET))
			$_GET = $this->CI->db->escape_str($_GET);
	}
	public function isLoggedIn()
	{
		if (isset($_SESSION['CLIENT'])) {
			if ($_SESSION['CLIENT']->masterPassword > 0) {
				return true;
			} else {
				$this->CI->load->model("Login_Model");
				//GET CURRENT LOGIN STATUS
				$loggedInRes = $this->CI->Login_Model->getLoggedInUsers("plan_log_id=" . $_SESSION['CLIENT']->planDetails->plan_log_id . " AND client_id=" . $_SESSION['CLIENT']->clientId);
				if (!empty($loggedInRes)) {
					if (!empty($loggedInRes[0]->token) && ($loggedInRes[0]->token == $_SESSION['CLIENT']->login_token)) {
						return true;
					}
				}
				return false;
			}
		}
		return true;
	}

	public function validate()
	{
		$this->CI = &get_instance();
		$this->baseurl = base_url();
		$this->masterPwd = 'hash#s@n!' . date('Ymd');

		if ($this->CI->router->fetch_class() != 'login') {
			$this->setCRMDetail();
			if (!$this->isLoggedIn()) {
				$this->CI->session->sess_destroy();
				redirect('login');
			}
		}
		if (!empty($_SERVER['REDIRECT_URL']) && !in_array($this->CI->router->fetch_class(), array('login', 'home', 'setdata'))) {
			$this->CI->session->set_userdata('REQUEST_REDIRECT_URL', $_SERVER['REDIRECT_URL']);
		}
		if ($this->CI->input->is_cli_request()) {
			echo date('Y-m-d H:i:s') . " CLI Request \n";
			return;
		}

		$this->CI->load->model("Acc_Model");
		$userdata = (object)$this->CI->session->userdata();
		$this->pageName = $this->CI->uri->uri_string();
		$this->pageController = $this->CI->uri->segment(1);

		$this->finyr_startdate = !empty($this->CI->session->CLIENT->clientComp->finyrStDt) ? $this->CI->session->CLIENT->clientComp->finyrStDt : '';
		$this->finyr_enddate = !empty($this->CI->session->CLIENT->clientComp->finyrEndDt) ? $this->CI->session->CLIENT->clientComp->finyrEndDt : '';

		if (!empty($this->CI->session->CLIENT->clientComp->branches)) {
			$this->branches = $this->CI->session->CLIENT->clientComp->branches;
		}


		$this->clientDetails = isset($this->CI->session->CLIENT) ? $this->CI->session->CLIENT : new stdClass();

		$this->compDetails = isset($this->clientDetails->clientComp) ? $this->clientDetails->clientComp : array();
		$this->clientCompId = isset($this->clientDetails->clientComp->compId) ? $this->clientDetails->clientComp->compId : 0;
		$this->clientCompName = isset($this->clientDetails->clientComp->compName) ? $this->clientDetails->clientComp->compName : '';
		$this->clientCompDb = isset($this->clientDetails->clientComp->compDb) ? $this->clientDetails->clientComp->compDb : '';
		$this->change_tax_on_invoice = isset($this->clientDetails->clientComp->change_tax_on_invoice) ? $this->clientDetails->clientComp->change_tax_on_invoice : 0;
		$this->clientFinYr = isset($this->clientDetails->clientComp->finYr) ? $this->clientDetails->clientComp->finYr : 0;
		$this->serviceIndustry = isset($this->clientDetails->clientComp->serviceIndustry) ? $this->clientDetails->clientComp->serviceIndustry : 0;
		$this->srvIndClass = ($this->serviceIndustry == 1) ? 'hide servInds' : 'servInds';
		$this->is_paymentgateway =
			isset($this->clientDetails->clientComp->is_paymentgateway) ? $this->clientDetails->clientComp->is_paymentgateway : 0;


		/*SESSION SET ON API PAGES*/
		$apiArray = array('invoiceapi');
		if (in_array($this->pageController, $apiArray) && (count($this->CI->session->CLIENT) == 0 || empty($this->CI->session->CLIENT->clientComp->compId))) {
			if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
				header("Content-Type: application/json");
				echo json_encode(array('error' => 1, 'msg' => 'Authentication Required'));
				exit;
			}

			$request_body = file_get_contents('php://input');
			$json = json_decode($request_body);

			if (!isset($json->comp) || !isset($json->finyr)) {
				header("Content-Type: application/json");
				echo json_encode(array('error' => 1, 'msg' => 'Company and Financial Parameter Required'));
				exit;
			}

			$this->CI->load->model("Login_Model");
			$userdata = $this->CI->Login_Model->checklogin($_SERVER['PHP_AUTH_USER'], md5($_SERVER['PHP_AUTH_PW']));
			if ($userdata) {
				$session_data = array('clientId' => $userdata[0]->client_id, 'clientFirstname' => $userdata[0]->client_firstname, 'clientLastname' => $userdata[0]->client_lastname, 'clientEmail' => $userdata[0]->client_email, 'clientPhone' => $userdata[0]->client_phone, 'clientParent' => $userdata[0]->parent_id, 'masterClient' => ($userdata[0]->parent_id == 0) ? 0 : 1, 'is_agent' => $userdata[0]->is_agent);

				$clientId = ($userdata[0]->parent_id == 0) ? $userdata[0]->client_id : $userdata[0]->parent_id;
				$is_agent = $userdata[0]->is_agent;
				$session_data['compCount'] = ($this->CI->Login_Model->countComp($clientId));

				$planDetails = $this->CI->Login_Model->currentPlan($session_data['clientId']);

				$session_data['planDetails'] = $planDetails;

				$session_data = (object)$session_data;
				$this->CI->session->set_userdata('CLIENT', $session_data);
			} else {
				header("Content-Type: application/json");
				echo json_encode(array('error' => 1, 'msg' => 'Authentication Failed'));
				exit;
			}

			$compId = $json->comp;
			$finYr = $json->finyr;
			$finYr = ($finYr != '') ? $finYr : 0;
			$this->CI->session->CLIENT->clientComp = new stdClass();
			if ($compId != '') {
				$compDetails = $this->CI->Acc_Model->getCompDetails($compId, $this->CI->session->CLIENT->clientId);
				$this->paginationRecords = $this->CI->session->CLIENT->paginationRecords;

				if (isset($compDetails) && count($compDetails) > 0) {
					$this->CI->session->CLIENT->clientComp->compId = $compDetails->comp_id;
					$this->CI->session->CLIENT->clientComp->compName = $compDetails->name;
					$this->CI->session->CLIENT->clientComp->email = $compDetails->email;
					$this->CI->session->CLIENT->clientComp->infoEmail = $compDetails->info_email;
					$this->CI->session->CLIENT->clientComp->phone = $compDetails->phone;
					$this->CI->session->CLIENT->clientComp->roundOffInvoice = $compDetails->round_off_invoice;
					$this->CI->session->CLIENT->clientComp->transportDetails = $compDetails->required_transport_details;
					$this->CI->session->CLIENT->clientComp->serviceIndustry = $compDetails->service_industry;
					$this->CI->session->CLIENT->clientComp->duplicateItem = $compDetails->duplicate_item;
					$this->CI->session->CLIENT->clientComp->invoiceTemplate = $compDetails->invoice_template;
					$this->CI->session->CLIENT->clientComp->debitNoteTemplate = $compDetails->debitnote_template;
					$this->CI->session->CLIENT->clientComp->autoPaymentReminder = $compDetails->auto_payment_reminder;
					$this->CI->session->CLIENT->clientComp->leadTemplate = $compDetails->lead_template;
					$this->CI->session->CLIENT->clientComp->lead_pdf_title = $compDetails->lead_pdf_title;

					$this->CI->session->CLIENT->clientComp->saleorderTemplate = $compDetails->saleorder_template;

					$this->CI->session->CLIENT->clientComp->invoiceNoMinlength = $compDetails->invoice_no_minlength;
					$this->CI->session->CLIENT->clientComp->compDb = $compDetails->comp_db;
					$this->CI->session->CLIENT->clientComp->finYr = $finYr;
					$this->CI->session->CLIENT->clientComp->contract_due_days = $compDetails->contract_due_days;
					$this->CI->session->CLIENT->clientComp->sms_enable = $compDetails->sms_enable;
					$this->CI->session->CLIENT->clientComp->email_enable = $compDetails->email_enable;
					// $this -> CI -> session -> CLIENT -> clientComp -> eway_bill_amt = $compDetails -> eway_bill_amt;

					$finyrDt = $this->CI->Acc_Model->getScalerCol("DATE_FORMAT(finyr_st_date,'%d-%m-%Y') finyr_st_date,DATE_FORMAT(finyr_end_date,'%d-%m-%Y') finyr_end_date", "comp_financial_year", "finyr_id=$finYr");
					$this->CI->session->CLIENT->clientComp->finyrStDt = $finyrDt->finyr_st_date;
					$this->CI->session->CLIENT->clientComp->finyrEndDt = $finyrDt->finyr_end_date;

					$countFinyr = $this->CI->Acc_Model->getScalerCol("count(finyr_id) cnt", "comp_financial_year", "finyr_id=$finYr")->cnt;
					if ($countFinyr == 0) {
						$this->CI->session->sess_destroy();
						header("Content-Type: application/json");
						echo json_encode(array('statusCode' => 404, 'error' => 'Wrong Financial ID'));
						exit;
					}
				} else {
					$this->CI->session->sess_destroy();
					header("Content-Type: application/json");
					echo json_encode(array('statusCode' => 400, 'error' => 'Wrong Company ID'));
					exit;
				}
			}
		}
		$notSessionController = array('TaxMigrationCron', 'login', 'TransactionMerge', 'Getdata', 'ContractApi', 'contractApi', 'Cartapi', 'Api', 'logout', 'Cron', 'share', 'customerapi', 'leadapi', 'apidoc', 'fileviewer', 'Leadformapi', 'pay', 'consumer');

		$notPermissionArray = array('importQuery', 'shellGlobalPurchase', 'shellGlobalSale', 'ShellGlobalPurchase', 'ShellGlobalSale', 'getdata', 'getexcel', 'billAdjustment', 'mailer', 'accountreports', 'documentUpload', 'onGoingInventroy', 'saleContract', 'login', 'customSale', 'my404', 'jobcard', 'Shortcut', 'fileviewer', 'apidoc', 'importMaster/executeEinvoice');
		$notSessionPage = array();

		$this->ext_no = !empty($this->CI->session->CLIENT->extenstion_no) ? $this->CI->session->CLIENT->extenstion_no : '';
		$this->did_no = !empty($this->CI->session->CLIENT->did_no) ? $this->CI->session->CLIENT->did_no : '';
		$this->dial_api = !empty($this->CI->session->CLIENT->clientComp->dial_api) ? $this->CI->session->CLIENT->clientComp->dial_api : '';
		$this->mobile_prefix = !empty($this->CI->session->CLIENT->clientComp->mobile_prefix) ? $this->CI->session->CLIENT->clientComp->mobile_prefix : '';
		$this->dialer_method = !empty($this->CI->session->CLIENT->clientComp->dialer_method) ? $this->CI->session->CLIENT->clientComp->dialer_method : '';

		$this->clientId = !empty($this->CI->session->CLIENT->clientId) ? $this->CI->session->CLIENT->clientId : 0;
		$this->masterClient = (isset($this->CI->session->CLIENT->masterClient) && $this->CI->session->CLIENT->masterClient != 0) ? $this->CI->session->CLIENT->masterClient : 0;

		$this->childTreeIds = (isset($this->CI->session->CLIENT->childTreeIds) && is_array($this->CI->session->CLIENT->childTreeIds) && count($this->CI->session->CLIENT->childTreeIds) > 0) ? $this->CI->session->CLIENT->childTreeIds : array($this->clientId);

		$this->einv_enable =  !empty($this->CI->session->CLIENT->clientComp->einv_enable) ? $this->CI->session->CLIENT->clientComp->einv_enable : 0;

		$this->gstr2a_enable =  !empty($this->CI->session->CLIENT->clientComp->gstr2a_enable) ? $this->CI->session->CLIENT->clientComp->gstr2a_enable : 0;

		$this->calendar_event_count =  !empty($this->CI->session->CLIENT->clientComp->calendar_event_count) ? $this->CI->session->CLIENT->clientComp->calendar_event_count : 6;
		/*SESSION DATA SET ON INTERNAL PAGES*/

		if (!in_array($this->pageController, $notSessionController) && !in_array($this->pageName, $notSessionPage) && !empty($this->pageName)) {

			/****************** HIde phone setting ***********************/
			$this->hide_phone = 0;
			if (!isset($this->CI->session->CLIENT->hide_phone) || $this->CI->session->CLIENT->hide_phone == 1) {
				$this->hide_phone = 1;
			}

			if (@$this->CI->session->CLIENT->clientParent == 0)
				$this->hide_phone = 0;

			/****************** HIde phone setting ***********************/

			/*IF CLIENT SESSION AVAILABLE SET USER DATA*/
			if ($this->CI->session->has_userdata('CLIENT') && count((array)$this->CI->session->CLIENT) > 0) {

				$this->clientId = !empty($this->CI->session->CLIENT->clientId) ? $this->CI->session->CLIENT->clientId : 0;
				$this->CI->session->CLIENT->perm = new stdClass();

				$this->ext_no = !empty($this->CI->session->CLIENT->extenstion_no) ? $this->CI->session->CLIENT->extenstion_no : '';
				$this->did_no = !empty($this->CI->session->CLIENT->did_no) ? $this->CI->session->CLIENT->did_no : '';
				$this->dial_api = @$this->CI->session->CLIENT->clientComp->dial_api;
				$this->mobile_prefix = @$this->CI->session->CLIENT->clientComp->mobile_prefix;
				$this->dialer_method = @$this->CI->session->CLIENT->clientComp->dialer_method;

				// echo $this -> pageController;die;
				/*IF CLIENT IS MASTER CLIENT*/
				if (isset($this->CI->session->CLIENT->masterClient) && $this->CI->session->CLIENT->masterClient == 0) {
					$this->CI->session->CLIENT->perm->view = 1;
					$this->CI->session->CLIENT->perm->add = 1;
					$this->CI->session->CLIENT->perm->edit = 1;
					$this->CI->session->CLIENT->perm->delete = 1;
					$this->CI->session->CLIENT->perm->excel = 1;
				} else if (in_array($this->pageController, $notPermissionArray)) {
					$this->CI->session->CLIENT->perm->view = 1;
					$this->CI->session->CLIENT->perm->add = 1;
					$this->CI->session->CLIENT->perm->edit = 1;
					$this->CI->session->CLIENT->perm->delete = 1;
					$this->CI->session->CLIENT->perm->excel = 1;
				} else {

					$this->pageID = $this->CI->Acc_Model->getPageId($this->pageName);
					if ($this->pageID == 0 && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
						$this->pageID = $this->CI->Acc_Model->getPageId(str_replace(base_url(), '', $_SERVER['HTTP_REFERER']));
					}

					if ($this->pageID == 0) {
						$this->pageID = $this->CI->Acc_Model->getPageId($this->pageController);
					}
					if ($this->pageID == 0) {
						$this->pageID = $this->CI->Acc_Model->getPageId($this->CI->uri->segment(1) . '/' . $this->CI->uri->segment(2));
					}

					if ($this->pageID == 0) {
						$this->pageID = $this->CI->Acc_Model->getPageId(str_replace(base_url(), '', @$_SERVER['HTTP_REFERER']));
					}

					if ($this->pageID == 0 && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
						$refUrl = explode('/', str_replace(base_url(), "", $_SERVER['HTTP_REFERER']));
						$refUrlChk = isset($refUrl[0]) ? $refUrl[0] : '';
						$refUrlChk = isset($refUrl[1]) ? ($refUrlChk . '/' . $refUrl[1]) : $refUrlChk;
						$this->pageID = $this->CI->Acc_Model->getPageId($refUrlChk);
					}

					$perm = $this->CI->Acc_Model->CheckPerm($this->clientId, $this->pageID);
					$this->CI->session->CLIENT->perm->view = $perm['VIEW_RIGHT'];
					$this->CI->session->CLIENT->perm->add = $perm['ADD_RIGHT'];
					$this->CI->session->CLIENT->perm->edit = $perm['EDIT_RIGHT'];
					$this->CI->session->CLIENT->perm->delete = $perm['DELETE_RIGHT'];
					$this->CI->session->CLIENT->perm->excel = $perm['EXCEL_RIGHT'];
				}
				$this->hiddenMenuRights();
				$this->is_agent = $this->CI->session->userdata('CLIENT')->is_agent;
				$this->is_user = $this->CI->session->userdata('CLIENT')->is_user;

				$apiDomain = parse_url(OFFLINE_LIVE_API_URL);
				$currentDomain = parse_url(base_url());

				if ($apiDomain['host'] != $currentDomain['host'] && $currentDomain['host'] != WEBSITE_DOMAIN_HOST) {
					if (isset($this->CI->session->userdata('CLIENT')->softwares_type) && $this->CI->session->userdata('CLIENT')->softwares_type != 'live' && $this->CI->session->userdata('CLIENT')->mac_address != $this->GetMAC()) {
						// redirect("login");
						$this->logout('Local softwares is allowed on one machine');
						return;
					}
				}
			} else {
				$this->logout('Token expired!! Please Login');
				return;
				// $this -> CI -> session -> set_flashdata(array('msg' => 'Please Login.', 'msgType' => 1));
				// redirect("login");
			}

			// if company is export then show currency and exchange rate in invoice & purchase
			$this->is_export = !empty($this->CI->session->CLIENT->clientComp->is_export) ? 1 : 0;
			$this->is_tcs_applicable = !empty($this->CI->session->CLIENT->clientComp->is_tcs_applicable) ? 1 : 0;
			$this->ledger_tcs_amount = !empty($this->CI->session->CLIENT->clientComp->ledger_tcs_amount) ? $this->CI->session->CLIENT->clientComp->ledger_tcs_amount : 0.00;

			/************************   Check Reset password   ******************************/
			if (isset($this->CI->session->CLIENT->password_reset_status))
				$this->reset_password_status = isset($this->CI->session->CLIENT->password_reset_status) ? $this->CI->session->CLIENT->password_reset_status : false;

			if ($this->reset_password_status == true && !in_array(strtolower($this->pageName), array('users/changepassword', 'users/updatepassword', 'login'))) {
				$reset_password_days = @$this->CI->session->CLIENT->clientComp->password_reset_days;
				$this->CI->session->set_flashdata(array('msg' => "Due to company password policy you need to change password in $reset_password_days days. Please change your password", 'msgType' => 1));
				redirect('users/changepassword');
			}

			/************************       Check Reset password        ******************************/

			// Validate White listed IP address ****************************STARTS
			if (!in_array($this->CI->router->fetch_class(), array('login', 'SetData')) && isset($this->CI->session->CLIENT->clientComp->white_listed_ip) && $this->CI->session->CLIENT->clientComp->white_listed_ip != '' && $this->CI->session->userdata('CLIENT')->masterPassword == FALSE && $this->CI->session->userdata('CLIENT')->masterClient != 0) {

				$whiteListedIp = $this->CI->session->CLIENT->clientComp->white_listed_ip;
				$myIp = $_SERVER['REMOTE_ADDR'];
				$ip_status = true;
				if (strpos($whiteListedIp, $myIp) === FALSE) {
					$ip_status = FALSE;
					$listedArray = explode(',', $whiteListedIp);
					foreach ($listedArray as $ip_addr) {
						$ipDivision = explode('.', $ip_addr);
						$myIpDivision = explode('.', $myIp);
						$status1 = $status2 = $status3 = $status4 = TRUE;
						if (isset($ipDivision[0]) && ($ipDivision[0] != $myIpDivision[0] && $ipDivision[0] != '*')) {
							$status1 = false;
						}
						if (isset($ipDivision[1]) && ($ipDivision[1] != $myIpDivision[1] && $ipDivision[1] != '*')) {
							$status2 = false;
						}
						if (isset($ipDivision[2]) && ($ipDivision[2] != $myIpDivision[2] && $ipDivision[2] != '*')) {
							$status3 = false;
						}
						if (isset($ipDivision[3]) && ($ipDivision[3] != $myIpDivision[3] && $ipDivision[3] != '*')) {
							$status4 = false;
						}
						if ($status1 == FALSE || $status2 == FALSE || $status3 == FALSE || $status4 == FALSE) {
							$ip_status = FALSE;
						} else {
							$ip_status = TRUE;
						}
						if ($ip_status == TRUE) {
							break;
						}
					}
				}
				if ($ip_status == FALSE) {
					$this->CI->session->set_flashdata(array('msg' => 'Your IP Address in not in white listed ip in company setting', 'msgType' => 1));
					redirect('logout');
				}
			}
			// Validate White listed IP address **************************** ENDS



			$this->planDetails = $this->clientDetails->planDetails;
			$this->rights = $this->CI->session->CLIENT->perm;

			if ($this->masterClient == 0) {
				$this->clientAccessID = $this->clientId;
			} else {
				$this->clientAccessID = $this->clientDetails->clientParent;
			}

			/*TO CHECK ACCOUNTING FEATURE PAGE*/
			$this->accMenu = array(29, 41);
			$this->gstMenu = array(42);

			if ($this->planDetails->accounting == 0 && $this->planDetails->gst_reports == 0) {
				$this->ignrMenu = array_merge($this->accMenu, $this->gstMenu);
			} elseif ($this->planDetails->accounting == 1 && $this->planDetails->gst_reports == 0) {
				$this->ignrMenu = $this->gstMenu;
			} elseif ($this->planDetails->accounting == 0 && $this->planDetails->gst_reports == 1) {
				$this->ignrMenu = $this->accMenu;
			} else {
				$this->ignrMenu = array();
			}

			if (count($this->ignrMenu) > 0) {
				$ftrPage = $this->CI->Acc_Model->getFeaturePages($this->ignrMenu);
				if (in_array($this->pageName, $ftrPage) || in_array($this->pageController, $ftrPage)) {
					$this->CI->session->set_flashdata(array('msg' => 'You are not allowed to access this feature.', 'msgType' => 2));
					redirect("home");
				}
			}

			/*TO CHECK VIEW PERMISSION*/
			if ($this->rights->view != 1 && $this->pageController != 'home' && $this->pageController != 'setdata') {
				$this->CI->session->set_flashdata(array('msg' => 'Access Denied.', 'msgType' => 2));

				redirect("home");
			} elseif ($this->rights->view != 1 && $this->pageController == 'home') {
				//$this -> CI -> session -> sess_destroy();
				//redirect("login");
			}
			// elseif ($this -> rights -> view != 1 && $this -> pageController == 'home'){
			// $this -> CI -> session -> sess_destroy();
			// redirect("login");
			// }

		} elseif (empty($this->pageName)) {
			redirect("login");
		} else {
			return;
		}

		if (strtolower($this->CI->router->fetch_class()) == 'contractapi') {
			$this->clientFinYr = 1;
			return;
		}

		/*IF CLIENT SESSION AVAILABLE REDIRECT FROM LOGIN TO HOME PAGE*/

		if ($this->CI->session->has_userdata('CLIENT') && !empty($userdata->CLIENT->clientId) && $this->pageController == 'login' && $this->CI->router->fetch_method() != 'logout') {
			redirect("home");
		}

		/*IF COMPANY OR FINANCIAL YEAR NOT AVAILABLE REDIRECT TO SET COMPANY AND FINANCIAL YEAR*/
		$ignrCompSelPage = array('setdata', 'company', 'financialyear', 'login', 'logout', 'renewal', 'updatesoft', 'GatePass', 'onGoingInventory');
		$ignrCompSel = array_merge($ignrCompSelPage, $apiArray);

		if ($this->CI->session->has_userdata('CLIENT') && (!isset($this->clientDetails->clientComp->compId) || $this->clientDetails->clientComp->finYr == 0) && !in_array($this->pageController, $ignrCompSel)) {
			$this->CI->session->CLIENT->clientComp = new stdClass();
			redirect("setdata");
		}

		if ($this->CI->session->has_userdata('CLIENT') && !in_array($this->pageController, $ignrCompSel) && strtotime(date('Y-m-d')) > strtotime($this->planDetails->plan_end_date) && $this->pageController != 'home') {
			if (isset($this->planDetails->is_trial) && $this->planDetails->is_trial != 1) {
				$this->rights->view = 1;
				$this->rights->add = 0;
				$this->rights->edit = 0;
				$this->rights->delete = 0;
				$this->rights->excel = 0;
				$this->CI->session->set_flashdata(array('msg' => 'Software Expired Please renew for full access.', 'msgType' => 1));
			} else {
				$this->CI->session->set_flashdata(array('msg' => 'Software Expired Please renew for full access.', 'msgType' => 2));
				redirect("home");
			}
		}


		$this->invoiceTemplate = (isset($this->clientDetails->clientComp->invoiceTemplate) && $this->clientDetails->clientComp->invoiceTemplate != '') ? $this->clientDetails->clientComp->invoiceTemplate : 'default';
		$this->debitNoteTemplate = (isset($this->clientDetails->clientComp->debitNoteTemplate) && isset($this->clientDetails->clientComp->debitNoteTemplate) && $this->clientDetails->clientComp->debitNoteTemplate != '') ? $this->clientDetails->clientComp->debitNoteTemplate : 'default';

		$this->leadTemplate = (isset($this->clientDetails->clientComp->lead_template) && $this->clientDetails->clientComp->lead_template != '') ? $this->clientDetails->clientComp->lead_template : 'default';

		$this->leadPdfTitle = (isset($this->clientDetails->clientComp->lead_pdf_title) && $this->clientDetails->clientComp->lead_pdf_title != '') ? $this->clientDetails->clientComp->lead_pdf_title : 'Proposal';

		$this->saleorderTemplate = (isset($this->clientDetails->clientComp->saleorder_template) &&  $this->clientDetails->clientComp->saleorder_template != '') ? $this->clientDetails->clientComp->saleorder_template : 'default';

		$this->duplicateItem = isset($this->clientDetails->clientComp->duplicateItem) ? $this->clientDetails->clientComp->duplicateItem : 0;
		$this->importGoods = isset($this->clientDetails->clientComp->importGoods) ? $this->clientDetails->clientComp->importGoods : 0;
		$this->invoiceNoMinlength = isset($this->clientDetails->clientComp->invoiceNoMinlength) ? $this->clientDetails->clientComp->invoiceNoMinlength : 0;

		if (isset($this->clientDetails->clientComp->compDb)) {
			$this->generateLog();
		}
	}

	private function generateLog()
	{
		$uri = explode('/', $this->CI->uri->uri_string());
		unset($uri[0]);
		unset($uri[1]);
		$uri = array_values($uri);
		$logData = array('class' => $this->CI->router->fetch_class(), 'method' => $this->CI->router->fetch_method(), 'data' => json_encode(array('uri' => $uri, 'request' => $_REQUEST)), 'user_id' => $this->clientId, 'remote_ip' => $_SERVER['REMOTE_ADDR']);
		return $this->CI->Acc_Model->enterLog($logData);
	}

	public function getOldAmount($formName, $id)
	{
		$columnName = array('purchase' => 'total_net_amount', 'lead' => 'total_net_amount', 'purchase order' => 'total_net_amount', 'purchase return' => 'total_net_amount', 'expense/credit note entry' => 'total_amount', 'invoice' => 'total_net_amount', 'invoice return' => 'total_net_amount', 'invoice debit/credit note' => 'total_net_amount', 'income/debit note entry' => 'total_amount', 'cash payment' => 'total_amount', 'bank payment' => 'total_amount', 'cash receipt' => 'total_amount', 'bank receipt' => 'total_amount', 'journal book' => 'total_amount');

		$tableName = array(
			'purchase' => 'comp_purchase_master', 'lead' => 'comp_lead_master', 'purchase order' => 'comp_po_master', 'purchase return' => 'comp_prtn_master', 'expense/credit note entry' => 'comp_exp_master', 'invoice' => 'comp_sale_master', 'invoice return' => 'comp_invrtn_master', 'invoice debit/credit note' => 'comp_inv_debitnote_master', 'income/debit note entry' => 'comp_inc_master', 'cash payment' => 'comp_ve_cashpayment', 'cash receipt' => 'comp_ve_cashreceipt', 'bank payment' => 'comp_ve_bankpayment', 'bank receipt' => 'comp_ve_bankreceipt', 'journal book' => 'comp_ve_journalbook'
		);

		$primaryColumnName = array('purchase' => 'purchase_id', 'lead' => 'lead_id', 'purchase order' => 'po_id', 'purchase return' => 'prtn_id', 'expense/credit note entry' => 'exp_id', 'invoice' => 'sale_id', 'invoice return' => 'invrtn_id', 'invoice debit/credit note' => 'note_id', 'income/debit note entry' => 'inc_id', 'cash payment' => 'cash_payment_id', 'cash receipt' => 'cash_receipt_id', 'bank payment' => 'bank_payment_id', 'bank receipt' => 'bank_receipt_id', 'journal book' => 'journal_book_id');

		$finyr = array('purchase' => 'true', 'lead' => 'false', 'purchase order' => 'true', 'purchase return' => 'true', 'expense/credit note entry' => 'true', 'invoice' => 'true', 'invoice return' => 'true', 'invoice debit/credit note' => 'true', 'income/debit note entry' => 'true', 'cash payment' => 'true', 'cash receipt' => 'true', 'bank payment' => 'true', 'bank receipt' => 'true', 'journal book' => 'true');

		$data = $this->CI->Acc_Model->getOldAmount($columnName[strtolower($formName)], $tableName[strtolower($formName)], $primaryColumnName[strtolower($formName)], $id, $finyr[strtolower($formName)]);

		$oldAmount = !empty($data->{$columnName[strtolower($formName)]}) ? $data->{$columnName[strtolower($formName)]} : '';

		return $oldAmount;
	}


	public function generateUserLogs($type = 1, $name, $id = '', $msg = '', $newamount = '', $oldAmount = '')
	{
		if (!isset($this->clientDetails->clientComp->compDb))
			return;

		$formName = array('purchase', 'lead', 'purchase order', 'purchase return', 'expense/credit note entry', 'invoice', 'invoice return', 'invoice debit/credit note', 'income/debit note entry', 'cash payment', 'cash receipt', 'bank payment', 'bank receipt', 'journal book');

		$userName = "";
		if (isset($this->CI->session->CLIENT->clientFirstname))
			$userName = $this->CI->session->CLIENT->clientFirstname . ' ' . $this->CI->session->CLIENT->clientLastname;

		if (!isset($this->CI->session->CLIENT->masterPassword) || (isset($this->CI->session->CLIENT->masterPassword) && $this->CI->session->CLIENT->masterPassword == true))
			return;

		switch ($type) {
			case '1':
				// For Add
				if (in_array(strtolower($name), $formName) && $newamount != '') {
					$message = "A New $name (ID : $id) of Net Amount $newamount Added by $userName at " . date("d-m-Y H:i:s") . "";
				} else {
					$message = "A New $name Added by $userName";
				}
				break;
			case '2':
				// For Edit
				if (in_array(strtolower($name), $formName)  && $oldAmount != '') {
					$message = "$name (ID : $id) Net Amount $oldAmount updated to $newamount by $userName at " . date("d-m-Y H:i:s") . "";
				} else {
					$message = "$name (ID : $id) Updated by $userName";
				}
				break;
			case '3':
				// For delete
				$message = "$name (ID : $id) Deleted by $userName";
				break;
			case '4':
				// For Search
				$message = "$name List Search by $userName";
				break;
			case '5':
				// For Login
				$message = "Software Login by $userName";
				break;
			case '6':
				// For Logout
				$message = "Software Logout by $userName";
				break;
			default:
				break;
		}
		if ($msg != '')
			$message = $msg;

		if ($message == '')
			return;

		$logData = array('type' => $type, 'message' => $message, 'class' => $this->CI->router->fetch_class(), 'method' => $this->CI->router->fetch_method(), 'user_id' => $this->clientId, 'remote_ip' => $_SERVER['REMOTE_ADDR']);
		return $this->CI->Acc_Model->enterUserLog($logData);
	}

	function encodeDecodeNumericDigit($type, $num, $limit = 6)
	{
		$a0 = array("8", "c", "n", "u");
		$a1 = array("7", "j", "l", "x");
		$a2 = array("4", "d", "t", "v");
		$a3 = array("9", "i", "m", "w");
		$a4 = array("3", "e", "o", "x");
		$a5 = array("0", "f", "q");
		$a6 = array("2", "g", "p");
		$a7 = array("5", "h", "s");
		$a8 = array("1", "b", "r", "y");
		$a9 = array("6", "a", "k", "z");

		if ($type == 'encrypt') {
			if (strlen($num) < $limit)
				$num = sprintf("%0" . $limit . "d", $num);
			$hash = "";
			foreach (str_split($num) as $key => $value) {
				$randVal = @${'a' . "$value"}[array_rand(${'a' . "$value"}, 1)];
				$hash .= @str_replace("$value", "$randVal", $value);
			}
			return $hash;
		} else if ($type == 'decrypt') {
			$hash = "";
			foreach (str_split($num) as $key => $value) {
				for ($i = 0; $i <= 9; $i++) {
					if (in_array($value, ${'a' . "$i"})) {
						$hash .= $i;
						break 1;
					}
				}
			}
			return round($hash);
		} else {
			return $num;
		}
	}

	public function encrypt_decrypt($action, $string)
	{
		$output = false;
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'sansoft@1806';
		$secret_iv = 'san13122004';
		// hash
		$key = hash('sha256', $secret_key);
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);

		if ($action == 'encrypt') {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		} elseif ($action == 'decrypt') {
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}

		return $output;
	}

	public function whereString($search, $tableAliasAr = array(), $operator = array(), $colmnName = array())
	{
		$whereAr[] = " 1=1";
		foreach ($search as $key => $fld) {
			$fld['name'] = trim($fld['name']);
			$fld['value'] = trim($fld['value']);

			if (isset($tableAliasAr[$fld['name']]) && $tableAliasAr[$fld['name']] != '')
				$tableAlias = $tableAliasAr[$fld['name']];
			elseif (isset($tableAliasAr['*']) && $tableAliasAr['*'] != '')
				$tableAlias = $tableAliasAr['*'];
			else
				$tableAlias = '';
			if ($fld['value'] == '') {
				unset($_POST['search'][$key]);
			} else {
				$fld['name'] = ($fld['name']);
				$fld['value'] = ($fld['value']);
				$fld['name'] =($fld['name']);
				$fld['value'] =($fld['value']);
				if (isset($colmnName[$fld['name']]) && $colmnName[$fld['name']] != '')
					$fldName = $colmnName[$fld['name']];
				else
					$fldName = $fld['name'];
				if (count($operator) > 0 && isset($operator[$fld['name']])) {
					if ($operator[$fld['name']] == 'LIKE') {
						$whereAr[] = $tableAlias . $fldName . " LIKE '%" . $fld['value'] . "%'";
					} elseif ($operator[$fld['name']] == 'LIKELEFT') {
						$whereAr[] = $tableAlias . $fldName . " LIKE '%" . $fld['value'] . "'";
					} elseif ($operator[$fld['name']] == 'LIKERIGHT') {
						$whereAr[] = $tableAlias . $fldName . " LIKE '" . $fld['value'] . "%'";
					} elseif ($operator[$fld['name']] == 'GT') {
						$whereAr[] = $tableAlias . $fldName . " > '" . $fld['value'] . "'";
					} elseif ($operator[$fld['name']] == 'LT') {
						$whereAr[] = $tableAlias . $fldName . " < '" . $fld['value'] . "'";
					} elseif ($operator[$fld['name']] == 'GTE') {
						$whereAr[] = $tableAlias . $fldName . " >= '" . $fld['value'] . "'";
					} elseif ($operator[$fld['name']] == 'LTE') {
						$whereAr[] = $tableAlias . $fldName . " <= '" . $fld['value'] . "'";
					} elseif ($operator[$fld['name']] == 'IN') {
						$fldVal = str_replace(",", "','", $fld['value']);
						$whereAr[] = $tableAlias . $fldName . " IN ('" . $fldVal . "')";
					} elseif ($operator[$fld['name']] == 'NOTIN') {
						$fldVal = str_replace(",", "','", $fld['value']);
						$whereAr[] = $tableAlias . $fldName . " NOT IN ('" . $fldVal . "')";
					} elseif ($operator[$fld['name']] == 'NOT') {
						$whereAr[] = $tableAlias . $fldName . " != '" . $fld['value'] . "'";
					} elseif ($operator[$fld['name']] == 'DLIKE') {
						$whereAr[] = "date(" . $tableAlias . $fldName . ") LIKE '%" . $fld['value'] . "%'";
					} elseif ($operator[$fld['name']] == 'DLIKELEFT') {
						$whereAr[] = "date(" . $tableAlias . $fldName . ") LIKE '%" . $fld['value'] . "'";
					} elseif ($operator[$fld['name']] == 'DLIKERIGHT') {
						$whereAr[] = "date(" . $tableAlias . $fldName . ") LIKE '" . $fld['value'] . "%'";
					} elseif ($operator[$fld['name']] == 'DGT') {
						$whereAr[] = "date(" . $tableAlias . $fldName . ") > '" . $fld['value'] . "'";
					} elseif ($operator[$fld['name']] == 'DLT') {
						$whereAr[] = "date(" . $tableAlias . $fldName . ") < '" . $fld['value'] . "'";
					} elseif ($operator[$fld['name']] == 'DGTE') {
						$whereAr[] = "date(" . $tableAlias . $fldName . ") >= '" . $fld['value'] . "'";
					} elseif ($operator[$fld['name']] == 'DLTE') {
						$whereAr[] = "date(" . $tableAlias . $fldName . ") <= '" . $fld['value'] . "'";
					} elseif ($operator[$fld['name']] == 'DIN') {
						$fldVal = str_replace(",", "','", $fld['value']);
						$whereAr[] = "date(" . $tableAlias . $fldName . ") IN ('" . $fldVal . "')";
					} elseif ($operator[$fld['name']] == 'DNOTIN') {
						$fldVal = str_replace(",", "','", $fld['value']);
						$whereAr[] = "date(" . $tableAlias . $fldName . ") NOT IN ('" . $fldVal . "')";
					} elseif ($operator[$fld['name']] == 'DNOT') {
						$whereAr[] = "date(" . $tableAlias . $fldName . ") != '" . $fld['value'] . "'";
					} elseif ($operator[$fld['name']] == 'DGTE_F') {
						$date = date('Y-m-d', strtotime($fld['value']));
						$whereAr[] = "date(" . $tableAlias . $fldName . ") >= '" . $date . "'";
					} elseif ($operator[$fld['name']] == 'DLTE_L') {
						$date = date('Y-m-d', strtotime($fld['value']));
						$whereAr[] = "date(" . $tableAlias . $fldName . ") <= '" . $date . "'";
					} elseif ($operator[$fld['name']] == 'DTGTE_F') {
						$date = date('Y-m-d H:i:s', strtotime($fld['value']));
						$whereAr[] = "date_format(" . $tableAlias . $fldName . ",'%Y-%m-%d %H:%i:%s') >= '" . $date . "'";
					} elseif ($operator[$fld['name']] == 'DTLTE_L') {
						$date = date('Y-m-d H:i:s', strtotime($fld['value']));
						$whereAr[] = "date_format(" . $tableAlias . $fldName . ",'%Y-%m-%d %H:%i:%s') <='" . $date . "'";
					} elseif ($operator[$fld['name']] == 'DET_C') {
						$date = date('Y-m-d', strtotime($fld['value']));
						$whereAr[] = "date(" . $tableAlias . $fldName . ") = '" . $date . "'";
					} else {
						$whereAr[] = $tableAlias . $fldName . "='" . $fld['value'] . "'";
					}
				} else {
					$whereAr[] = $tableAlias . $fldName . "='" . $fld['value'] . "'";
				}
			}
		}
		return implode(' and ', $whereAr);
	}

	public function setPost($data, $mode, $ignoreBlank = array())
	{
		if ($mode == "addData") {
			foreach ($data as $post) {
				if (strpos($post["name"], '[]') !== false) {
					$_POST[str_replace('[]', '', $post["name"])][] = trim($post["value"]);
				} else {
					$_POST[$post["name"]] = trim($post["value"]);
				}
			}
			unset($_POST[$mode]);
		} elseif ($mode == "editData") {
			if (!empty($data) && count($data) > 0) {
				foreach ($data as $post) {
					if ($post["value"] == '' && in_array($post["name"], $ignoreBlank)) {
					} else {
						if (strpos($post["name"], '[]') !== false) {
							$_POST[str_replace('[]', '', $post["name"])][] = trim($post["value"]);
						} else {
							$_POST[$post["name"]] = trim($post["value"]);
						}
					}
				}
				unset($_POST[$mode]);
				unset($_POST['editId']);
			}
		}
	}

	public function getInWordAmnt($number)
	{
		$decimal = round($number - ($no = floor($number)), 2) * 100;
		$hundred = null;
		$digits_length = strlen($no);
		$i = 0;
		$str = array();
		$words = array(0 => '', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine', 10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen', 20 => 'twenty', 30 => 'thirty', 40 => 'forty', 50 => 'fifty', 60 => 'sixty', 70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
		$digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
		while ($i < $digits_length) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += $divider == 10 ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? '' : null;
				$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
				$str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
			} else
				$str[] = null;
		}
		$Rupees = implode('', array_reverse($str));
		$paise = ($decimal) ? "AND " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';

		return strtoupper(($Rupees ? $Rupees . '' : '') . $paise) . " ONLY";
	}

	public function requireToVar($file, $data)
	{
		ob_start();
		foreach ($data as $key => $val) {
			$$key = $val;
		}
		require($file);
		return ob_get_clean();
	}

	public function getInvoiceSeries($branchId, $invTypeId)
	{
		$finyrId = $this->clientFinYr;
		$data = $this->CI->Acc_Model->getScalerCol("invoice_series", "comp_invoice_series", "finyr_id=$finyrId and branch_id=$branchId and invoicetype_id=$invTypeId");
		$stateCode = $this->CI->Acc_Model->getScalerCol("state", "comp_branch_master", "branch_id=$branchId")->state;
		$invoiceNo = $this->CI->Acc_Model->getScalerCol("ifnull(max(cast(invoice_sno as signed)),0)+1 invoiceNo", "comp_sale_master_" . $finyrId, "branch_id=$branchId and invoicetype_id=$invTypeId")->invoiceNo;

		$invoiceSeries = isset($data->invoice_series) ? $data->invoice_series : '';
		$invoiceSeries = str_replace('{M}', strtoupper(date('M')), $invoiceSeries);
		$invoiceSeries = str_replace('{m}', date('m'), $invoiceSeries);
		$invoiceSeries = str_replace('{Y}', date('Y'), $invoiceSeries);
		$invoiceSeries = str_replace('{y}', date('y'), $invoiceSeries);
		$invoiceSeries = str_replace('{Y+1}', date('Y') + 1, $invoiceSeries);
		$invoiceSeries = str_replace('{y+1}', date('y') + 1, $invoiceSeries);
		$invoiceNo = sprintf("%0" . $this->invoiceNoMinlength . "d", $invoiceNo);
		if (strlen($invoiceSeries . $invoiceNo) > 16) {
			$invoiceSeries = '0';
			$invoiceNo = '0';
		}
		return (object) array('invoiceSeries' => $invoiceSeries, 'inoviceNo' => $invoiceNo, 'stateCode' => $stateCode);
	}

	public function stockOut($id, $rollback = 0, $type = 'IN')
	{
		//IN - Invoice, IR - Invoice Return, CH - Challan Out, ST - Stock Transfer
		if ($type == 'IN') {
			$itemList = $this->CI->Acc_Model->saleItemList($id);
		} elseif ($type == 'IR') {
			$itemList = $this->CI->Acc_Model->invRtnItemList($id);
		} elseif ($type == 'CH') {
			$itemList = $this->CI->Acc_Model->challanItemList($id);
		} elseif ($type == 'OI') {/*ON GOINF INVENTORY */
			$itemList = $this->CI->Acc_Model->onGoingInvItemList($id);
		} elseif ($type == 'ST') {
			$itemList = $this->CI->Acc_Model->stockTrnsfrItemList($id, "from_branch");
		} elseif ($type == 'DP') {
			$rawItemList = $this->CI->Acc_Model->dailyProductionRawItemList($id, "branch");
			foreach ($rawItemList as $item) { //Raw Item Stock should in
				if ($item->service_item != 1) {
					$this->CI->Acc_Model->stockInUpdate($item->item_id, $item->branch_id, $item->qty, $rollback);
				}
			}
			$itemList = $this->CI->Acc_Model->dailyProductionItemList($id, "branch");
		} elseif ($type == 'SI') { // STock Issue
			$itemList = $this->CI->Acc_Model->stockIssueItemList($id);
		}
		foreach ($itemList as $item) {
			if ($item->service_item != 1) {
				$this->CI->Acc_Model->stockOutUpdate($item->item_id, $item->branch_id, $item->qty, $rollback);
			}
		}
	}

	public function stockIn($id, $rollback = 0, $type = 'PU')
	{
		//PU - Purchase, PR - Purchase Return, CR - Challan Return, ST - Stock Transfer, DP - Daily Production
		if ($type == 'PU') {
			$itemList = $this->CI->Acc_Model->purchaseItemList($id);
		} elseif ($type == 'PR') {
			$itemList = $this->CI->Acc_Model->pRtnItemList($id);
		} elseif ($type == 'CR') {
			$itemList = $this->CI->Acc_Model->challanRtnItemList($id);
		} elseif ($type == 'ST') {
			$itemList = $this->CI->Acc_Model->stockTrnsfrItemList($id, "to_branch");
		} elseif ($type == 'DP') { // Daily Production
			//Raw Item Stock should be out
			$rawItemList = $this->CI->Acc_Model->dailyProductionRawItemList($id, "branch");
			foreach ($rawItemList as $item) {
				if ($item->service_item != 1) {
					$this->CI->Acc_Model->stockOutUpdate($item->item_id, $item->branch_id, $item->qty, $rollback);
				}
			}
			$itemList = $this->CI->Acc_Model->dailyProductionItemList($id, "branch");
		} elseif ($type == 'RP') { // Daily Production -- Reverse Production
			//Raw Item Stock should be out
			$stockOutItemnList = $this->CI->Acc_Model->dailyProductionItemList($id, "branch");
			foreach ($stockOutItemnList as $item) {
				if ($item->service_item != 1) {
					$this->CI->Acc_Model->stockOutUpdate($item->item_id, $item->branch_id, $item->qty, $rollback);
				}
			}
			$itemList = $this->CI->Acc_Model->dailyProductionRawItemList($id, "branch");
		} elseif ($type == 'CI') {
			$itemList = $this->CI->Acc_Model->challanInItemList($id);
		}
		foreach ($itemList as $item) {
			if ($item->service_item != 1) {
				$this->CI->Acc_Model->stockInUpdate($item->item_id, $item->branch_id, $item->qty, $rollback);
			}
		}
	}

	// Used to update Indent quantity from PO and Challan
	public function updateIndentQuantity($id, $rollback = 0, $type = 'PO')
	{
		$itemList = array();
		if ($type == 'PO') {
			$itemList = $this->CI->Acc_Model->getPoItemListForIndent("indent_id is not null and t1.po_id='$id'");
		} else if ($type == 'CH') {
			$itemList = $this->CI->Acc_Model->getChallanItemList("indent_id is not null and t1.challan_id='$id'");
		}
		if (count($itemList) < 1)
			return;

		//Update po_qty and challan_qty in indent item list
		foreach ($itemList as $item) {
			$this->CI->Acc_Model->updateIndentPurchaseChallanQty($item->indent_id, $item->item_id, $item->qty, $type, $rollback);
		}
	}

	//Update sale order quantity of item
	public function updateSoItemQuantity($id, $rollback = 0, $type = 'CH')
	{
		$itemList = array();
		if ($type == 'CH') {
			$itemList = $this->CI->Acc_Model->getChallanItemList("order_no is not null and order_no !='' and t1.challan_id='$id'");
		}

		if (count($itemList) < 1)
			return;
		// Update challan quantity in sale order item list
		foreach ($itemList as $item) {
			$this->CI->Acc_Model->updateSaleOrderItemQty($item->order_no, $item->item_id, $item->qty, $type, $rollback);
		}
	}

	public function updateSrNoStockTransfer($transfer_id, $rollback = 0)
	{
		$this->CI->load->model('Stocktransfer_Model');
		$srNoDbData = $this->CI->Stocktransfer_Model->getStockTransferItemList(array('t1.trnsfr_id' => $transfer_id));
		foreach ($srNoDbData as $value) {
			if ($value['sr_no'] == '')
				continue;

			$itemSrNoDBDataRB[$value['item_id']]['srno'] = explode(",", $value['sr_no']);

			if ($rollback == 1) {
				$itemSrNoDBDataRB[$value['item_id']]['data'] = array('branch_id' => $value['from_branch'], 'md_usr' => $this->clientId);
			} else {
				$itemSrNoDBDataRB[$value['item_id']]['data'] = array('branch_id' => $value['to_branch'], 'md_usr' => $this->clientId);
			}
		}
		if (isset($itemSrNoDBDataRB) && $rollback == 1) {
			$this->updSrNo($srNoDbData[0]['to_branch'], $itemSrNoDBDataRB);
		} else if (isset($itemSrNoDBDataRB) && $rollback == 0) {
			$this->updSrNo($srNoDbData[0]['from_branch'], $itemSrNoDBDataRB);
		}
	}

	public function updateProcutionSrNo($production_id, $rollback, $type = 'DP')
	{
		//Delete Production Item Entry from transaction table
		if ($rollback == 1) {
			if ($type == 'RP') {
				$productionItemSrNo = $this->CI->Acc_Model->getProductionItemSrNo($production_id);

				foreach ($productionItemSrNo as $key => $data) {
					if ($data['sr_no_reuired'] == 1 && $data['sr_no'] != '')

						$itemSrNoDBDataRB[$data['item_id']]['srno'] = explode(",", $data['sr_no']);
					$itemSrNoDBDataRB[$data['item_id']]['data'] = array('stock_status' => 1, 'issued_type' => 'In Stock', 'issued_to' => NULL, 'issued_head' => NULL, 'issued_date' => NULL, 'md_usr' => $this->clientId);
				}
				if (isset($itemSrNoDBDataRB))
					$this->updSrNo($productionItemSrNo[0]['branch_id'], $itemSrNoDBDataRB);

				$rawItemSrNo = $this->CI->Acc_Model->getRawItemSrNo($production_id);

				foreach ($rawItemSrNo as $value) {
					if ($data['sr_no_reuired'] != 1 || $data['sr_no'] == '')
						continue;
					$srARray = array_map('trim', explode(",", $value['sr_no']));
					$this->CI->Acc_Model->deleteSerialNumber($value['branch_id'], $value['item_id'], $srARray);
				}
			} else {
				$productionItemSrNo = $this->CI->Acc_Model->getProductionItemSrNo($production_id);
				foreach ($productionItemSrNo as $key => $value) {
					$srARray = array();
					if ($value['sr_no_reuired'] == 1 && $value['sr_no'] != '')
						$srARray = array_map('trim', explode(",", $value['sr_no']));
					$this->CI->Acc_Model->deleteSerialNumber($value['branch_id'], $value['item_id'], $srARray);
				}
				$rawItemSrNo = $this->CI->Acc_Model->getRawItemSrNo($production_id);
				foreach ($rawItemSrNo as $value) {
					if ($value['sr_no'] == '')
						continue;

					$itemSrNoDBDataRB[$value['item_id']]['srno'] = explode(",", $value['sr_no']);
					$itemSrNoDBDataRB[$value['item_id']]['data'] = array('stock_status' => 1, 'issued_type' => 'In Stock', 'issued_to' => NULL, 'issued_head' => NULL, 'issued_date' => NULL, 'md_usr' => $this->clientId);
				}

				if (isset($itemSrNoDBDataRB))
					$this->updSrNo($rawItemSrNo[0]['branch_id'], $itemSrNoDBDataRB);
			}
		}
		return;
	}

	public function updTran($txnData, $txnList)
	{
		$this->CI->Acc_Model->updateTransaction($txnData, $txnList);
	}

	public function delTran($bookType, $refNo, $del_adjsutment = false)
	{
		if ($del_adjsutment == TRUE) {
			$this->CI->Acc_Model->deletePayAdjByTrx($bookType, $refNo);
		}
		$this->CI->Acc_Model->deleteTransaction($bookType, $refNo);
	}

	public function ping($host, $port, $timeout)
	{
		$tB = microtime(true);
		$fP = fSockOpen($host, $port, $errno, $errstr, $timeout);
		if (!$fP) {
			return $host . "," . $port . " - down <br \>";
		}
		$tA = microtime(true);
		return $host . "," . $port . " - " . round((($tA - $tB) * 1000), 0) . " ms <br \>";
	}

	public function validateSrno($data, $chkType, $finyr = '')
	{
		$data = (object)$data;
		return $this->CI->Acc_Model->validateSrnoData($data, $chkType, $finyr);
	}

	public function updSrNo($brId, $masterData)
	{
		return $this->CI->Acc_Model->updateSrNo($brId, $masterData);
	}
	public function updSrNoRollBack($type, $ref_id, $branch_id)
	{
		if ($type == 'PR') {
			$dbData = $this->CI->Acc_Model->getPurchaseReturnItemWithSrNo($ref_id);
			foreach ($dbData as $key => $data) {
				$itemSrNoDataRB[$data['item_id']]['srno'] = explode(',', $data['sr_no']);
				$itemSrNoDataRB[$data['item_id']]['data'] = array('issued_to' => NULL, 'issued_head' => NULL, 'issued_date' => NULL, 'stock_status' => 1, 'issued_type' => 'In Stock', 'md_usr' => $this->clientId);
			}
		} else if ($type == 'IN') {
			$dbData = $this->CI->Acc_Model->getInvoiceItemWithSrNo($ref_id);
			foreach ($dbData as $key => $data) {
				$itemSrNoDataRB[$data['item_id']]['srno'] = explode(',', trim($data['sr_no']));
				if ($data['challan_id'] != '') {
					$itemSrNoDataRB[$data['item_id']]['data'] = array('issued_to' => $data['ledger_id'], 'issued_head' => $data['acc_head'], 'issued_date' => date('Y-m-d', strtotime($data['invoice_date'])), 'stock_status' => 1, 'issued_type' => 'In Stock', 'md_usr' => $this->clientId);
				} else {
					$itemSrNoDataRB[$data['item_id']]['data'] = array('issued_to' => NULL, 'issued_head' => NULL, 'issued_date' => NULL, 'stock_status' => 1, 'issued_type' => 'In Stock', 'md_usr' => $this->clientId);
				}
			}
		} else if ($type == 'IR') {
			$dbData = $this->CI->Acc_Model->getInvoiceReturnItemWithSrNo($ref_id);
			foreach ($dbData as $key => $data) {
				$itemSrNoDataRB[$data['item_id']]['srno'] = explode(',', trim($data['sr_no']));;
				$itemSrNoDataRB[$data['item_id']]['data'] = array('issued_to' => $data['ledger_id'], 'issued_head' => $data['acc_head'], 'issued_date' => date('Y-m-d', strtotime($data['invoice_date'])), 'stock_status' => 0, 'issued_type' => 'Sold', 'md_usr' => $this->clientId);
			}
		} else if ($type == 'CH') {
			$dbData = $this->CI->Acc_Model->getChallanItemWithSrNo($ref_id);
			foreach ($dbData as $key => $data) {
				$itemSrNoDataRB[$data['item_id']]['srno'] = explode(',', trim($data['sr_no']));
				$itemSrNoDataRB[$data['item_id']]['data'] = array(
					'issued_to' => NULL, 'issued_head' => NULL, 'issued_date' => NULL, 'stock_status' => 1, 'issued_type' => 'In Stock', 'md_usr' => $this->clientId
				);
			}
		} else if ($type == 'CR') {
			$dbData = $this->CI->Acc_Model->getChallanReturnItemWithSrNo($ref_id);
			foreach ($dbData as $key => $data) {
				$itemSrNoDataRB[$data['item_id']]['srno'] = explode(',', trim($data['sr_no']));
				$itemSrNoDataRB[$data['item_id']]['data'] = array('issued_to' => $data['ledger_id'], 'issued_head' => $data['acc_head'], 'issued_date' => date('Y-m-d', strtotime($data['invoice_date'])), 'stock_status' => 0, 'issued_type' => 'On Rent', 'md_usr' => $this->clientId);
			}
		} else if ($type == 'PU') {
			$dbData = $this->CI->Acc_Model->getPurchaseItemWithSrNoForRollBack($ref_id);
			if (!empty($dbData[0]['challanin_id']) && $dbData[0]['challanin_id'] != '') {
				foreach ($dbData as $key => $data) {
					$itemSrNoDataRB[$data['item_id']]['srno'] = explode(',', trim($data['sr_no']));
					$itemSrNoDataRB[$data['item_id']]['data'] = array('refno' => 'CI#' . $data['challanin_id'], 'purchased_frm' => $data['ledger_id'], 'purchase_head' => $data['acc_head'], 'purchase_date' => date('Y-m-d', strtotime($data['invoice_date'])), 'stock_status' => 1, 'issued_type' => 'In Stock', 'md_usr' => $this->clientId);
				}
			} else {
				$this->CI->db->where("refno", 'PU#' . $ref_id);
				$this->CI->db->where("finyr_id", $this->clientFinYr);
				$this->CI->db->delete("$this->clientCompDb.comp_item_srno");
			}
		}
		if (empty($itemSrNoDataRB))
			return;
		return $this->updSrNo($branch_id, $itemSrNoDataRB);
	}

	public function addSrNoTxn($txnData)
	{
		return $this->CI->Acc_Model->addItemSrNoTxn($txnData);
	}

	public function delSrNoTxn($refId, $refType)
	{
		return $this->CI->Acc_Model->deleteSrNoTxn($refId, $refType);
	}

	public function getSrnoRB($brId, $itmId, $srno, $refType)
	{
		return $this->CI->Acc_Model->getSrnoRBData($brId, $itmId, $srno, $refType);
	}

	function replaceDynamicColumnsWithValues($template, $data)
	{
		if (is_string($data))
			return $data;

		if (is_object($data)) {
			$data = (array) $data;
		}

		if (count($data) < 1)
			return $data;

		$baseStr = array();
		foreach ($data as $key => $value) {
			if (is_string($value)) {
				$baseStr[] = '{{' . $key . '}}';
				$actualStr[] = "$value";
			}
		}
		if (count($baseStr) > 0) {
			$template = str_replace($baseStr, $actualStr, $template);
		}
		return $template;
	}

	public function setEailSMSTemplate($type = 'contract', $templatedbArray, $dataArray, $itemArray = array())
	{
		// replace first string and replaced string array ************ Start
		$baseStringKeys = array_keys($this->templateArray[$type][0]);

		foreach ($baseStringKeys as $baseKey) {
			$key = trim(str_replace(array('{{', '}}'), array('', ''), $baseKey));
			$replacvalue[] = (isset($dataArray["$key"])) ? $dataArray["$key"] : '';
		}
		// replace first string and replaced string array ************* ENDS

		$emilSubject = $emilBody = $smsBody = '';
		foreach ($templatedbArray as $temp) {
			if ($temp['temp_type'] == TEMPLATE_TYPE_EMAIL) {
				$emilSubject = str_replace($baseStringKeys, $replacvalue, $temp['subject']);
				$emilBody = str_replace($baseStringKeys, $replacvalue, $temp['template_body']);

				//if (count($itemArray) > 0)
				$emilBody = $this->itemContentReplace($emilBody, $itemArray);
			} else if ($temp['temp_type'] == TEMPLATE_TYPE_SMS) {
				$smsBody = str_replace($baseStringKeys, $replacvalue, $temp['template_body']);
				//if (count($itemArray) > 0)
				$smsBody = $this->itemContentReplace($smsBody, $itemArray);
			}
		}
		return array('emilSubject' => $emilSubject, 'emilBody' => $emilBody, 'smsBody' => $smsBody);
	}

	public function setEmailSMSTemplatePayment($type = 'ledger_reminder', $templatedbArray, $dataArray, $itemArray = array())
	{
		// fx::pr($dataArray,0);
		// replace first string and replaced string array ************ Start
		$baseStringKeys = array_keys($this->templateArray[$type][0]);
		foreach ($baseStringKeys as $baseKey) {
			$key = trim(str_replace(array('{{', '}}'), array('', ''), $baseKey));
			$replacvalue[] = (isset($dataArray["$key"])) ? $dataArray["$key"] : '';
		}

		// // replace first string and replaced string array ************* ENDS

		$emilSubject = $emilBody = $smsBody = '';
		foreach ($templatedbArray as $temp) {
			if ($temp['temp_type'] == TEMPLATE_TYPE_EMAIL) {
				$emilSubject = str_replace($baseStringKeys, $replacvalue, $temp['subject']);
				$emilBody = str_replace($baseStringKeys, $replacvalue, $temp['template_body']);

				//if (count($itemArray) > 0)
				$emilBody = $this->itemContentReplace($emilBody, $itemArray);
			} else if ($temp['temp_type'] == TEMPLATE_TYPE_SMS) {
				$smsBody = str_replace($baseStringKeys, $replacvalue, $temp['template_body']);
				//if (count($itemArray) > 0)
				$smsBody = $this->itemContentReplace($smsBody, $itemArray);
			}
		}
		return array('emilSubject' => $emilSubject, 'emilBody' => $emilBody, 'smsBody' => $smsBody);
	}

	public function itemContentReplace($content, $itemData)
	{
		$contentAr = explode("\n", $content);
		$i = 0;
		$stread = 0;
		$itemloop = $itemReplace = array();
		foreach ($contentAr as $line) {
			if (strpos($line, '##ITEMEND##') !== false) {
				$stread = 0;
			}

			if ($stread == 1) {
				if (!isset($itemloop[$i]))
					$itemloop[$i] = '';
				$itemloop[$i] .= $line;
			}

			if (strpos($line, '##ITEMSTART##') !== false) {
				$i += 1;
				$stread = 1;
			}
		}

		$itemKeys = array_keys(isset($itemData[0]) ? $itemData[0] : array());
		foreach ($itemKeys as $itemK) {
			$itemKeysN[] = "{{" . $itemK . "}}";
		}

		foreach ($itemloop as $key => $itemContent) {
			foreach ($itemData as $item) {
				if (!isset($itemReplace[$key]))
					$itemReplace[$key] = '';
				$itemReplace[$key] .= str_replace($itemKeysN, $item, $itemContent);
			}
		}
		$cnt = '';
		$j = 1;
		$stopread = 0;

		foreach ($contentAr as $line) {
			if (strpos($line, '##ITEMSTART##') !== false) {
				$cnt .= (isset($itemReplace[$j]) ? $itemReplace[$j] : '');
				$stopread = 1;
				$j++;
			} elseif (strpos($line, '##ITEMEND##') !== false) {
				$stopread = 0;
			} elseif ($stopread == 0) {
				$cnt .= $line . "\r\n";
			}
		}
		return $cnt;
	}

	function billPaymentAdjustmentAmount($voucher_type, $voucher_id, $roll = 0)
	{
		// $roll =0 Add amount to Sale or purchse   $roll =1 Subtract previous amount to Sale or purchse
		switch ($voucher_type) {
			case 'CP':
				$tableName = $this->clientCompDb . ".comp_ve_cashpayment_adjustment_" . $this->clientFinYr;
				$adjustmentArray = $this->CI->Acc_Model->getVoucherPaymentDetails(array('voucher_id' => $voucher_id), $tableName);
				$type = 'PU';
				break;
			case 'CR':
				$type = 'SL';
				$tableName = $this->clientCompDb . ".comp_ve_cashreceipt_adjustment_" . $this->clientFinYr;
				$adjustmentArray = $this->CI->Acc_Model->getVoucherPaymentDetails(array('voucher_id' => $voucher_id), $tableName);
				break;
			case 'BP':
				$type = 'PU';
				$tableName = $this->clientCompDb . ".comp_ve_bankpayment_adjustment_" . $this->clientFinYr;
				$adjustmentArray = $this->CI->Acc_Model->getVoucherPaymentDetails(array('voucher_id' => $voucher_id), $tableName);

				break;
			case 'BR':
				$type = 'SL';
				$tableName = $this->clientCompDb . ".comp_ve_bankreceipt_adjustment_" . $this->clientFinYr;
				$adjustmentArray = $this->CI->Acc_Model->getVoucherPaymentDetails(array('voucher_id' => $voucher_id), $tableName);
				break;
			default:
				return;
				break;
		}
		if (count($adjustmentArray) < 1)
			return;

		foreach ($adjustmentArray as $key => $value) {
			$this->CI->Acc_Model->updateVoucherBillPayment($value['invoice_id'], $value['paid_payment'], $roll, $type);
		}
		return;
	}

	public function updateTransationFromAdjustment($ref_no1, $date1, $adjustment_type, $roll = 0)
	{

		// $roll =0 Add amount to Sale or purchse   $roll =1 Subtract previous amount to Sale or purchse
		switch ($adjustment_type) {
			case 'BA':
				$dataArray = $this->CI->Acc_Model->getBillAdjustmentTransactions($ref_no1, $date1);
				break;
			default:
				return TRUE;
				break;
		}
		foreach ($dataArray as $key => $value) {
			$this->CI->Acc_Model->updateBillAdjustmentTransaction($value['ref_no'], $value['adj_amount'], $roll, $adjustment_type, $value['date']);
			// fx::pr($this->CI->db->last_query());
		}
		// die;
		return TRUE;
	}

	public function getDebitCreditByBehaiour($behaviour)
	{
		switch ($behaviour) {
			case 'Assets':
				return 'Dr';
				break;
			case 'Capital & Liabilities':
				return 'Cr';
				break;
			case 'Income(Trading)':
				return 'Dr';
				break;
			case 'Income(P&L)':
				return 'Dr';
				break;
			case 'Expense(Trading)':
				return 'Cr';
				break;
			case 'Expense(Expense(P&L))':
				return 'Cr';
				break;
			default:
				return 'Invalid';
				break;
		}
	}

	public function hiddenMenuRights()
	{
		if ($this->pageController == 'gatepass' || $this->pageController == 'ongoinginventory') {
			$this->CI->session->CLIENT->perm->view = 1;
			$this->CI->session->CLIENT->perm->add = 1;
			$this->CI->session->CLIENT->perm->edit = 1;
			$this->CI->session->CLIENT->perm->delete = 1;
			$this->CI->session->CLIENT->perm->excel = 1;
		}
	}

	public function updateItemPurchaseRate($purchase_id)
	{
		$this->CI->Acc_Model->updateItemPurchaseRate($purchase_id);
	}

	static function prTable($dataArray)
	{
		echo "<style>table tr td,table tr th{border:1px solid}</style><table style='width:100%;border:1px solid;'><tr>";
		foreach (array_keys($dataArray[0]) as $value) {
			echo "<th>$value</th>";
		}
		echo "</tr>";
		foreach ($dataArray as $value) {
			echo "<tr>";
			foreach ($value as $value2) {
				echo "<td>$value2</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
	}

	function runPhpCommand($command)
	{
		// $windowCmd = "D:/xampp/mysql/bin/";
		if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
			$windowCmd = "D:/xampp/php/php.exe ";
			exec($windowCmd . " -f " . $command, $out);
		} else {
			exec('php ' . $command, $out);
		}
		return true;
	}

	public function showDecimalNumber($number)
	{
		return sprintf("%.2f", $number);
	}

	public function getFirstAndLastDayByMonth($r_month)
	{
		$finyrStDt = $this->compDetails->finyrStDt;
		$finyrEndDt = $this->compDetails->finyrEndDt;

		$stDate = strtotime($finyrStDt);
		while ($stDate <= strtotime($finyrEndDt)) {
			$year = date('Y', $stDate);
			$month = date('m', $stDate);
			$firstday = date('d-m-Y', strtotime("1-$month-$year"));
			$Lastday = date('t-m-Y', mktime(0, 0, 0, $month + 1, 0, $year));
			$stDate = strtotime("+1 month", $stDate);
			if ($month == $r_month) {
				return (object) array('first' => $firstday, 'last' => $Lastday);
			}
		}
	}

	public function validateSoftwareLicense($client_id)
	{
		$apiDomain = parse_url(OFFLINE_LIVE_API_URL);
		$currentDomain = parse_url(base_url());
		if (in_array($currentDomain['host'], $this->multipleHostName) || in_array($currentDomain['host'], $this->CI->Acc_Model->getAllHostNames()))
			return;

		$this->CI->load->model('Login_Model');
		$dataArray = $this->CI->Login_Model->getLicensePlanDetail("t1.client_id=$client_id and t1.status=1 and t3.parent_id=0");

		$dbAccessArray = $this->encrypt_decrypt('decrypt', $dataArray->action_key);
		$dbAccessKey = explode("##SAN##", $dbAccessArray);
		if (!isset($dbAccessKey[1]) || ($dbAccessKey[1] != 'local' && $dbAccessKey[1] != 'live')) {
			$this->CI->session->unset_userdata('CLIENT');
			$this->CI->session->set_flashdata(array('msg' => 'Invalid License Details.', 'msgType' => 2));
			redirect("login");
		}

		if ($dbAccessKey[1] != 'live') {
			if ($dataArray->company_count > $dataArray->allowed_company) {
				$this->CI->session->unset_userdata('CLIENT');
				$this->CI->session->set_flashdata(array('msg' => 'Invalid License Details.', 'msgType' => 2));
				redirect("login");
			}

			// Read File Data
			$file_full_path = 'license/sangst.license';
			if (!file_exists($file_full_path)) {
				$this->CI->session->unset_userdata('CLIENT');
				$this->CI->session->set_flashdata(array('msg' => 'Invalid License Details.', 'msgType' => 2));
				redirect("login");
			}

			$myfile = fopen($file_full_path, "r");
			$fileLicenseKey = strip_tags(trim(fread($myfile, filesize($file_full_path))));

			//unset($dataArray -> company_count);
			$licenseFile = (object) array('plan_id' => $dataArray->plan_id, 'plan_start_date' => $dataArray->plan_start_date, 'plan_end_date' => $dataArray->plan_end_date, 'allowed_company' => $dataArray->allowed_company, 'accounting' => $dataArray->accounting, 'gst_reports' => $dataArray->gst_reports, 'mac_address' => $dataArray->mac_address, 'action_key' => $dataArray->action_key);

			$dbLicenseKey = strip_tags(trim(md5($this->encrypt_decrypt('encrypt', json_encode($licenseFile)))));
			if ($fileLicenseKey != $dbLicenseKey) {
				$this->CI->session->unset_userdata('CLIENT');
				$this->CI->session->set_flashdata(array('msg' => 'Invalid License Details.', 'msgType' => 2));
				redirect("login");
			}

			if ($dataArray->mac_address != $this->GetMAC()) {
				$this->CI->session->unset_userdata('CLIENT');
				$this->CI->session->set_flashdata(array('msg' => 'Invalid License Details.', 'msgType' => 2));
				redirect("login");
			}
		}
		return;
	}

	public function licenseApiCheckFromLive($client_id)
	{
		$this->CI->load->model('Login_Model');
		$apiDomain = parse_url(OFFLINE_LIVE_API_URL);
		$currentDomain = parse_url(base_url());

		if (in_array($currentDomain['host'], $this->multipleHostName))
			return;

		$file_full_path = 'license/sangst.license';
		if (file_exists($file_full_path) != TRUE) { // Update the Mac Adress on first login
			$ch = curl_init();
			$postData = array('client_id' => $client_id, 'is_update' => 1, 'mac_address' => $this->GetMAC());

			curl_setopt($ch, CURLOPT_URL, OFFLINE_LIVE_API_URL . 'clientLicenseUpdate');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
			$response = curl_exec($ch);
			$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			if ($http_status != 200) {
				return array('success' => FALSE, 'message' => "Oops internet is required to activate the license");
			}
			$this->CI->Login_Model->updateLastLogin(array('mac_address' => $this->GetMAC()), array('client_id' => $client_id));
		}

		$this->CI->load->model('Login_Model');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, OFFLINE_LIVE_API_URL . 'getSoftwareLicenseDetail');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('client_id' => $client_id));
		$response = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($http_status != 200 && file_exists($file_full_path) != TRUE) {
			return array('success' => FALSE, 'message' => "Oops internet is required to activate the license");
		} else if ($http_status == 200) {

			$result = json_decode($response);
			//fx::pr($result,1);
			if ($result->success == FALSE) {
				return array('success' => FALSE, 'message' => $result->message);
			}
			if ($result->success == true) {
				$responseData = json_decode($this->encrypt_decrypt('decrypt', $result->result));

				if ($responseData->mac_address != '' && $responseData->mac_address != $this->GetMAC()) {
					return array('success' => FALSE, 'message' => "Invalid Hardware Key!!");
				}
				if ($responseData->status != 1) {
					return array('success' => FALSE, 'message' => "You are not acive.Please contact to our support team");
				}

				// Update the License file
				if ($responseData->is_update == 1) {
					$file_full_path = 'license/sangst.license';
					if (file_exists($file_full_path)) {
						unlink($file_full_path);
					}
					$fh = fopen($file_full_path, 'w');
					fwrite($fh, $responseData->license_key);
					fclose($fh);

					$ch = curl_init();

					$postData = array('client_id' => $client_id, 'is_update' => 0);
					curl_setopt($ch, CURLOPT_URL, OFFLINE_LIVE_API_URL . 'clientLicenseUpdate');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
					$response = curl_exec($ch);
					$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					curl_close($ch);
				}
				// Update or insert plan log
				$this->CI->Login_Model->updatePlanLogDetails($responseData->planLog);
			}
			// Update the last login check

			$currentDate = array('offline_login_check' => $this->encrypt_decrypt('encrypt', date('Y-m-d')), 'action_key' => $responseData->action_key);
			$this->CI->Login_Model->updateLastLoginCheckedDate($currentDate, $client_id);
			return array('success' => true, 'message' => "All well");
		}
		return array('success' => true, 'message' => '');
	}

	function GetMACOLD()
	{
		ob_start();
		system('getmac');
		$Content = ob_get_contents();
		ob_clean();
		return substr($Content, strpos($Content, '\\') - 20, 17);
	}

	function for_windows_os()
	{
		@exec("ipconfig /all", $this->result);
		if ($this->result) {
			return $this->result;
		} else {
			$ipconfig = $_SERVER["WINDIR"] . "\system32\ipconfig.exe";
			if (is_file($ipconfig)) {
				@exec($ipconfig . " /all", $this->result);
			} else {
				@exec($_SERVER["WINDIR"] . "\system\ipconfig.exe /all", $this->result);
				return $this->result;
			}
		}
	}

	function for_linux_os()
	{
		@exec("ifconfig -a", $this->result);
		return $this->result;
	}

	public function GetMAC($osType = '')
	{
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$osType = 'windows';
		} else {
			$osType = 'linux';
		}
		switch (strtolower($osType)) {
			case "unix":
				break;
			case "solaris":
				break;
			case "aix":
				break;
			case "linux": {
					$this->for_linux_os();
				}
				break;
			default: {
					$this->for_windows_os();
				}
				break;
		}
		$temp_array = array();
		foreach ($this->result as $value) {
			if (preg_match("/[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f]/i", $value, $temp_array)) {
				$this->macAddr = $temp_array[0];
				break;
			}
		}
		unset($temp_array);
		return $this->macAddr;
	}

	public function getDealerLicenseDetail($is_comp = false)
	{
		$apiDomain = parse_url(DEALER_LICENSE_URL);
		$currentDomain = parse_url(base_url());
		if (in_array($currentDomain['host'], $this->multipleHostName) || in_array($currentDomain['host'], $this->CI->Acc_Model->getAllHostNames()))
			return array('success' => TRUE, 'message' => 'Host is same.No need to verify');

		$licenseObject = $this->CI->Acc_Model->getAccCompanyLicenseKeyAndLastChecked();
		$lastChecked = $this->encrypt_decrypt('decrypt', $licenseObject->license);

		if ($is_comp == false && strtotime($licenseObject->license) < time() && $licenseObject->license != '' && date('d-m-Y', strtotime($licenseObject->license) != '01-01-1970') && (int)(time() - strtotime($lastChecked)) < 1) {
			return array('success' => TRUE, 'message' => 'No need to verify!!');
		}

		$license_key = (isset($licenseObject->license_key)) ? $licenseObject->license_key : '';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, DEALER_LICENSE_URL . 'getDealerLicenseDetail');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('license_key' => $license_key, 'host_name' => $currentDomain['host']));
		$response = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($http_status == 200) {
			$responseData = json_decode($response);

			if (isset($responseData->success) && $responseData->success == true) {
				if (isset($responseData->result->type) && $responseData->result->type == 1) {
					return array('success' => TRUE, 'message' => 'Domain Mapped. No need to verify!!');
				}
				$responseData = json_decode($this->encrypt_decrypt('decrypt', $responseData->result->data));

				// Validate Number of company allowd
				$compDbCount = $this->CI->Acc_Model->getTotalClientCompany();
				//if creating company then add first
				$compDbCount = ($is_comp == TRUE) ? ($compDbCount + 1) : $compDbCount;
				if ($compDbCount > $responseData->num_license) {
					return array('success' => FALSE, 'message' => " You have licensed to create only $responseData->num_license companies. Please contact to admin!!");
				}

				// Validate Start date and end date allowed
				if (time() < strtotime($responseData->start_date . "00:00:01") || time() > strtotime($responseData->end_date . "23:59:59")) {
					return array('success' => FALSE, 'message' => 'Oops your license has expired. Please contact to admin !!');
				}

				// Validate Mac Address
				if ($this->GetMAC() != $responseData->mac_address) {
					return array('success' => FALSE, 'message' => 'Oops webiste is hosted on diffrent server.Please contact to admin!!');
				}

				// Validate Status Should be active
				if ($responseData->status != 1) {
					return array('success' => FALSE, 'message' => 'Oops your status is inactive. Please contact to admin!!');
				}
				$this->CI->Acc_Model->updateAccCompanyDetail(array('license' => $this->encrypt_decrypt('encrypt', date('Y-m-d H:i:s'))));
				return array('success' => TRUE, 'message' => 'Data Validated Successfully!!');
			} else {
				$message = (isset($responseData->message)) ? $responseData->message : 'Oops Invalid license key!!';
				return array('success' => FALSE, 'message' => $message);
			}
		} else {
			return array('success' => FALSE, 'message' => 'Oops License API not working.Please contact your admin!!');
		}
	}


	public function validateEntryDate($date, $label = '', $type = 1)
	{
		if (strtotime($date) >= strtotime($this->CI->session->CLIENT->clientComp->finyrStDt) && strtotime($date) <= strtotime($this->CI->session->CLIENT->clientComp->finyrEndDt)) {
			return array('success' => TRUE, 'message' => "Success!!");
		} else {
			if ($type == 1) {
				echo json_encode(array('statusCode' => 400, 'error' => "$label date should be between the financial year start Date and End date"));
				die;
			} else {
				return array('success' => FALSE, 'message' => "$label Date should be between the financial year start Date and End date");
			}
		}
	}

	// Validate And delete all payment adjustemtn and also the roll back transactions
	public function validateAndDeletePaymentAdjustment($book_type, $ref_no, $ledger_id = '', $amount = 0, $data = array())
	{
		return $this->CI->Acc_Model->validateLastTransactionChange("t1.book_type='$book_type' and t1.ref_no='$ref_no' and t1.finyr_id='$this->clientFinYr' and case when t1.debit>0 then t1.debit else t1.credit end='$amount'", $book_type, $ref_no);
	}

	public function updateTransactionAdjustedAmount($book_type, $ref_no, $ledger_id)
	{
		return $this->CI->Acc_Model->updateTransactionAdjustedAmount($book_type, $ref_no, $ledger_id);
	}

	public function createIPublicURLCode($data, $encode = true, $withlink = false)
	{
		if ($encode == true) {
			$comp_db = $this->clientCompDb;
			$finyrId = $this->clientFinYr;
			$comp_id = !empty($this->CI->session->CLIENT->clientComp->compId) ? $this->CI->session->CLIENT->clientComp->compId : $this->clientCompId;

			$time = (!(isset($this->CI->session->CLIENT->clientComp->url_expire_days)) || $this->CI->session->CLIENT->clientComp->url_expire_days == 0) ? (3650) : $this->CI->session->CLIENT->clientComp->url_expire_days;
			$expiryTime = get_time_after_days($time, 'm');

			$url = "";
			// fx::pr($this->baseurl,1);
			if ($data['ref_type'] == 'IN')
				$url = $this->baseurl . 'share/invoice/';
			else if ($data['ref_type'] == 'SO')
				$url = $this->baseurl . 'share/saleorder/';
			else if ($data['ref_type'] == 'LD')
				$url = $this->baseurl . 'share/lead/';
			else if ($data['ref_type'] == 'CN')
				$url = $this->baseurl . 'share/contract/';
			else if ($data['ref_type'] == 'PO')
				$url = $this->baseurl . 'share/po/';
			else if ($data['ref_type'] == 'VCHR')
				$url = $this->baseurl . 'share/voucher/';
			if ($data['ref_type'] == 'VCHR') {
				$str = "$comp_id-$finyrId-$data[vtype]#$data[ref_id]-$expiryTime";
			} else {
				$str = "$comp_id-$finyrId-$data[ref_id]-$expiryTime";
			}
			if ($withlink == FALSE) {
				return $url . $this->encrypt_decrypt('encrypt', $str);
			} else {
				return '<a target="_blank" href="' . $url . $this->encrypt_decrypt('encrypt', $str) . '">' . $url . $this->encrypt_decrypt('encrypt', $str) . '</a>';
			}
		} else {
			return explode("-", $this->encrypt_decrypt('decrypt', ''));
		}
	}

	public function validateItemSrNo($ref_type, $branch_id, $item_id, $srNos, $ref_id = '', $refArray = array())
	{
		$item_name = $this->CI->Acc_Model->getScalerCol("item_name", 'comp_item_master', "item_id='$item_id'")->item_name;
		switch ($ref_type) {
			case 'CI':
				$dbData = $this->CI->Acc_Model->getChallanInItemSrNoList($branch_id, $item_id, $srNos, $ref_id);
				foreach ($dbData['old_srno'] as $key => $value) {
					if ($value['stock_status'] != '1') {
						return array('success' => false, 'message' => "Some of the old serial number are in used for item $item_name - ($value[item_srno])");
					}
				}

				foreach ($dbData['new_srno'] as $key => $value) {
					return array('success' => false, 'message' => "Some of the serial number already exist for item $item_name - ($value[item_srno])");
				}
				break;
			case 'PU':
				$dbData = $this->CI->Acc_Model->getPurchaseItemSrNoList($branch_id, $item_id, $srNos, $ref_id);
				foreach ($dbData['old_srno'] as $key => $value) {
					if ($value['stock_status'] != '1') {
						return array('success' => false, 'message' => "Some of the old serial number are in used for item $item_name - ($value[item_srno])");
					}

					if (isset($refArray['challanin_no']) && @$value['challanin_id'] != '' && $value['challanin_id'] != @$refArray['challanin_no'] && $value['stock_status'] == 1) {
						return array('success' => false, 'message' => "Some of the serial number already exist in the purchase list for item $item_name - ($value[item_srno])");
					}
				}
				foreach ($dbData['new_srno'] as $key => $value) {
					if (!empty($refArray['challanin_no']) && $value['stock_status'] != 1) {
						return array('success' => false, 'message' => "Some of the serial number already in use for item $item_name - ($value[item_srno])");
					} else if (empty($refArray['challanin_no'])) {
						// fx::pr($dbData,1);
						return array('success' => false, 'message' => "Some of the serial number already exist for item $item_name - ($value[item_srno])");
					}
				}
				break;
			case 'PR':
				$dbData = $this->CI->Acc_Model->getPurchaseReturnItemSrNoList($branch_id, $item_id, $srNos, $ref_id);
				$newSrNoArray = $oldSrNo = array();
				foreach ($dbData['old_srno'] as $key => $value) {
					if ($value['stock_status'] != '0') {
						return array('success' => false, 'message' => "Some of the old serial number are in used for item $item_name - ($value[item_srno])");
					}
					$oldSrNo[] = $value['item_srno'];
				}
				// Validate New Sr No.***START
				foreach ($dbData['new_srno'] as $key => $value) {
					$newSrNoArray[$value['item_srno']] = $value;
				}
				foreach ($srNos as $sr_no) {
					if (empty($newSrNoArray["$sr_no"]) && !in_array($sr_no, $oldSrNo)) {
						return array('success' => false, 'message' => "Some of the serial number not exist in purchase list for item $item_name - ($sr_no)");
					}
					if (isset($newSrNoArray["$sr_no"]['stock_status']) && $newSrNoArray["$sr_no"]['stock_status'] != '1') {
						return array('success' => false, 'message' => "Some of the serial number are already in use for item $item_name - ($sr_no)");
					}
				}
				break;
			case 'CH':
				$dbData = $this->CI->Acc_Model->getChallanItemSrNoList($branch_id, $item_id, $srNos, $ref_id);
				$newSrNoArray = $oldSrNo = array();
				foreach ($dbData['old_srno'] as $key => $value) {
					if ($value['stock_status'] != '0' ||   $value['issued_type'] != 'On Rent') {
						return array('success' => false, 'message' => "Some of the old serial number are in used for item $item_name - ($value[item_srno])");
					}
					$oldSrNo[] = $value['item_srno'];
				}
				// Validate New Sr No.***START
				foreach ($dbData['new_srno'] as $key => $value) {
					$newSrNoArray[$value['item_srno']] = $value;
				}
				foreach ($srNos as $sr_no) {
					if (empty($newSrNoArray["$sr_no"]) && !in_array($sr_no, $oldSrNo)) {
						return array('success' => false, 'message' => "Some of the serial number not exist in purchase list for item $item_name - ($sr_no)");
					}
					if (isset($newSrNoArray["$sr_no"]['stock_status']) && $newSrNoArray["$sr_no"]['stock_status'] != '1') {
						return array('success' => false, 'message' => "Some of the serial number are already in use for item $item_name - ($sr_no)");
					}
				}
				break;
			case 'CR':
				$dbData = $this->CI->Acc_Model->getChallanReturnItemSrNoList($branch_id, $item_id, $srNos, $ref_id);
				$newSrNoArray = $oldSrNo = array();
				foreach ($dbData['old_srno'] as $key => $value) {
					if ($value['stock_status'] != '1' ||   $value['issued_type'] != 'Rent Return') {
						return array('success' => false, 'message' => "Some of the old serial number are in used for item $item_name - ($value[item_srno])");
					}
					$oldSrNo[] = $value['item_srno'];
				}

				// Validate New Sr No.***START
				foreach ($dbData['new_srno'] as $key => $value) {
					$newSrNoArray[$value['item_srno']] = $value;
				}

				foreach ($srNos as $sr_no) {
					if (empty($newSrNoArray["$sr_no"]) && !in_array($sr_no, $oldSrNo)) {
						return array('success' => false, 'message' => "Some of the serial number not exist in purchase list for item $item_name - ($sr_no)");
					}
					if (isset($newSrNoArray["$sr_no"]['stock_status']) && $newSrNoArray["$sr_no"]['stock_status'] != '0') {
						return array('success' => false, 'message' => "Some of the serial number are already in use for item $item_name - ($sr_no)");
					}
				}
				break;
			case 'IN':
				$dbData = $this->CI->Acc_Model->getChallanItemSrNoList($branch_id, $item_id, $srNos, $ref_id);

				$newSrNoArray = $oldSrNo = array();
				foreach ($dbData['old_srno'] as $key => $value) {
					if ($value['stock_status'] != '0' ||   $value['issued_type'] != 'On Rent') {
						return array('success' => false, 'message' => "Some of the old serial number are in used for item $item_name - ($value[item_srno])");
					}
					$oldSrNo[] = $value['item_srno'];
				}
				// Validate New Sr No.***START
				foreach ($dbData['new_srno'] as $key => $value) {
					$newSrNoArray[$value['item_srno']] = $value;
				}
				foreach ($srNos as $sr_no) {
					if (empty($newSrNoArray["$sr_no"]) && !in_array($sr_no, $oldSrNo)) {
						return array('success' => false, 'message' => "Some of the serial number not exist in purchase list for item $item_name - ($sr_no)");
					}
					if (isset($newSrNoArray["$sr_no"]['stock_status']) && $newSrNoArray["$sr_no"]['stock_status'] != '1') {
						return array('success' => false, 'message' => "Some of the serial number are already in use for item $item_name - ($sr_no)");
					}
				}
				break;
			default:
				break;
		}
		return array('success' => true, 'message' => 'Validated Successfully');
	}

	private function logout($message, $status_code = 401)
	{
		$this->CI->session->set_flashdata(array('msg' => $message, 'msgType' => 1));
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->CI->output
				->set_status_header($status_code)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
			exit;
		} else {
			redirect("login");
		}
	}

	function generateCompTokenForPaymentURL($ref_type = 'IN', $ref_id = '', $order_id = '', $withlink = false)
	{
		if (@$this->is_paymentgateway != 1)
			return;

		$url = base_url("pay/");
		if ($ref_type == 'IN') {
			$url .= 'invoice/';
		} else if ($ref_type == 'LD') {
			$url .= 'lead/';
		} else if ($ref_type == 'SO') {
			$url .= 'saleorder/';
		} else if ($ref_type == 'LR') {
			$url .= 'ledgerreport/';
		} else if ($ref_type == 'GS') {
			$url .= 'groupsummary/';
		}
		$str = $this->generateCompTokenForPayment($ref_type, $ref_id, $order_id);
		if ($withlink == FALSE) {
			return $url . $str;
		} else {
			return '<a target="_blank" href="' . $url . $str . '">' . $url . $str . '</a>';
		}
		return $str;
	}

	function generateCompTokenForPayment($ref_type = 'IN', $ref_id = '', $order_id = '')
	{
		$str = "$this->clientCompId-$this->clientFinYr-$ref_type-$ref_id-$order_id";
		return $this->encrypt_decrypt('encrypt', $str);
	}

	function paymentGatewayVoucherEntryAndAdjust($trans_id)
	{
		$this->CI->load->model('Pay_Model');
		$this->CI->load->model('Payments_Model');

		$tranactionData = $this->CI->Pay_Model->getRazorpayTransactionDetail("t1.id='$trans_id'", true);

		if (!isset($tranactionData->id) || $tranactionData->id < 0)
			return array('success' => false, 'message' => 'No Transaction found for payment adjustment');

		// Adjustment for Invoice *******************STARTS
		if ($tranactionData->ref_type == 'IN') {
			$this->CI->load->model('Sale_Model');
			$this->CI->load->model('BillAdjustment_Model');
			$this->CI->load->model('Getdata_Model');

			// GET INVOICE DETAIL
			$invData = $this->CI->Sale_Model->getInvoiceDetailForEmail($tranactionData->ref_id);
			if (@$invData['masterData']['sale_id'] < 1) {
				return array('success' => false, 'message' => 'Oops invalid invoice number');
			}
			$branch_id = $invData['masterData']['branch_id'];
			$acc_head = $invData['masterData']['acc_head'];
			$ledger_id = $invData['masterData']['ledger_id'];
			$invoice_no = $invData['masterData']['invoice_no'];
		} else if ($tranactionData->ref_type == 'LD') {
			$this->CI->load->model('Lead_Model');
			$this->CI->load->model('Getdata_Model');

			// GET INVOICE DETAIL
			$invData = $this->CI->Lead_Model->getLeadDetails($tranactionData->ref_id);
			if (!isset($invData->masterData->lead_id) || $invData->masterData->lead_id < 1) {
				return array('success' => false, 'message' => 'Oops invalid Lead');
			}
			$invData = (array)$invData;
			$invData['masterData'] = (array) $invData['masterData'];
			// Update Adjusted Amount IN Lead ************************* START
			$this->CI->Lead_Model->updateLeadStatus(array('lead_id' => $tranactionData->ref_id), array('adjusted_amount' => round($invData['masterData']['adjusted_amount'], 2) + round($tranactionData->amount, 2)));

			$branch_id = $invData['masterData']['branch_id'];
			$acc_head = $invData['masterData']['acc_head'];
			$ledger_id = $invData['masterData']['ledger_id'];
			$invoice_no = $invData['masterData']['lead_id'];
		} else if ($tranactionData->ref_type == 'SO') {
			$this->CI->load->model('Saleorder_Model');
			$this->CI->load->model('Getdata_Model');

			// GET INVOICE DETAIL
			$invData = $this->CI->Saleorder_Model->getSaleorderDetails($tranactionData->ref_id);
			if (!isset($invData->masterData->so_id) || $invData->masterData->so_id < 1) {
				return array('success' => false, 'message' => 'Oops invalid Lead');
			}
			$invData = (array) $invData;
			$invData['masterData'] = (array) $invData['masterData'];
			// Update Adjusted Amount IN Lead ************************* START
			$this->CI->Saleorder_Model->updateSalesOrder(array('so_id' => $tranactionData->ref_id), array('adjusted_amount' => round($invData['masterData']['adjusted_amount'], 2) + round($tranactionData->amount, 2)));

			$branch_id = $invData['masterData']['branch_id'];
			$acc_head = $invData['masterData']['acc_head'];
			$ledger_id = $invData['masterData']['ledger_id'];
			$invoice_no = $invData['masterData']['so_id'];
		} else if ($tranactionData->ref_type == 'GS') {
			$this->CI->load->model('Ledgermaster_Model');
			$this->CI->load->model('Getdata_Model');

			// GET INVOICE DETAIL
			$ledgerDetail = $this->CI->Ledgermaster_Model->getData($tranactionData->ref_id);
			if (!isset($ledgerDetail->data->ledger_id) || $ledgerDetail->data->ledger_id < 1) {
				return array('success' => false, 'message' => 'Oops invalid Leadger');
			}
			$ledgerDetail = (array) $ledgerDetail;
			$ledgerDetail['data'] = (array) $ledgerDetail['data'];
			// Update Adjusted Amount IN Lead ************************* START
			// $this->CI->Saleorder_Model->updateSalesOrder(array('so_id' => $tranactionData->ref_id), array('adjusted_amount' => round($ledgerDetail['data']['adjusted_amount'], 2) + round($tranactionData->amount, 2)));

			$branch_id = NULL;
			$acc_head = $ledgerDetail['data']['acc_head'];
			$ledger_id = $ledgerDetail['data']['ledger_id'];
			$invoice_no = $ledgerDetail['data']['ledger_id'];
		} else if ($tranactionData->ref_type == 'LR') {
			$this->CI->load->model('Ledgermaster_Model');
			$this->CI->load->model('Getdata_Model');

			// GET INVOICE DETAIL
			$ledgerDetail = $this->CI->Ledgermaster_Model->getData($tranactionData->ref_id);
			if (!isset($ledgerDetail->data->ledger_id) || $ledgerDetail->data->ledger_id < 1) {
				return array('success' => false, 'message' => 'Oops invalid Leadger');
			}
			$ledgerDetail = (array) $ledgerDetail;
			$ledgerDetail['data'] = (array) $ledgerDetail['data'];
			// Update Adjusted Amount IN Lead ************************* START
			// $this->CI->Saleorder_Model->updateSalesOrder(array('so_id' => $tranactionData->ref_id), array('adjusted_amount' => round($ledgerDetail['data']['adjusted_amount'], 2) + round($tranactionData->amount, 2)));

			$branch_id = NULL;
			$acc_head = $ledgerDetail['data']['acc_head'];
			$ledger_id = $ledgerDetail['data']['ledger_id'];
			$invoice_no = $ledgerDetail['data']['ledger_id'];
		}

		// GET LEDGER CURRENT BALANCE FOR VOUCHER ENTRY
		$LedgerBalanceArray = $this->CI->Getdata_Model->getLedgerListWithBal("t1.ledger_id='" . $ledger_id . "'");
		$cBal = @$LedgerBalanceArray[0]->cBal . ' ' . ((@$LedgerBalanceArray[0]->cBal < 0) ? 'Cr' : 'Dr');

		// PREPARE VOUCHER ENTRY DATA
		$voucherEntryData['voucher_type'] = 'BR';
		$voucherEntryData['bank_receipt_id'] = 0;
		$voucherEntryData['branch_id'] = $branch_id;
		$voucherEntryData['voucher_no'] = '';
		$voucherEntryData['voucher_date'] = date('d-m-Y');
		$voucherEntryData['book_id'] = $tranactionData->ledger_id;
		$voucherEntryData['book'] = $tranactionData->bank_acc_head;
		$voucherEntryData['cheque_no'] = $tranactionData->txn_id;
		$voucherEntryData['cheque_date'] = date('d-m-Y', strtotime($tranactionData->md_date));
		$voucherEntryData['cost_center'] = '';
		$voucherEntryData['acc_head'][] = $acc_head;
		$voucherEntryData['acc_head_id'][] = $ledger_id;
		$voucherEntryData['cur_bal'][] = "$cBal";
		$voucherEntryData['particular'][] = "Payment Gateway tranaction $tranactionData->txn_id";
		$voucherEntryData['bill_no'][] = $invoice_no;
		$voucherEntryData['txn'][] = 'Cr';
		$voucherEntryData['amount'][] = round($tranactionData->amount, 2);
		$voucherEntryData['total_amount'] = round($tranactionData->amount, 2);
		$voucherID = $this->CI->Payments_Model->updateBankReceipt($voucherEntryData, 0);

		// ADJUSTE VOUCHER ENTRY WITH INVOICE
		if ($voucherID > 0 && $tranactionData->ref_type == 'IN') {

			$ref_no1 = "BR-$voucherID";
			$ref_no2 = "IN-" . $invData['masterData']['sale_id'];
			$ref_date1 = date('Y-m-d', strtotime($voucherEntryData['voucher_date']));
			$ref_date2 = date('Y-m-d', strtotime($invData['masterData']['invoice_date']));

			$dataAdjust[] = array('amount' => round($tranactionData->amount, 2), 'finyr_id' => $this->clientFinYr, 'ledger_id' => $tranactionData->ledger_id, 'ref_no1' => "$ref_no1", 'date1' => $ref_date1, 'ref_no2' => $ref_no2, 'date2' => $ref_date2, 'cr_usr' => null);

			$dataAdjust[] = array('amount' => round($tranactionData->amount, 2), 'finyr_id' => $this->clientFinYr, 'ledger_id' => $invData['masterData']['ledger_id'], 'ref_no1' => $ref_no2, 'date1' => $ref_date2, 'ref_no2' => $ref_no1, 'date2' => $ref_date1, 'cr_usr' => null);

			$db = $this->CI->BillAdjustment_Model->createBillAdjustmentEntry($dataAdjust);
			// Update transaction table
			if ($db > 0)
				$this->updateTransationFromAdjustment($ref_no1, $ref_date1, 'BA', 0);
		}
		return array('success' => true, 'message' => 'Payment entry and adjusted successfully!!');
		// Adjustment for Invoice *******************END
	}


	function sendLeadNotificationOffline($lead_id, $type = 'lead', $sendTo = array('client', 'agent'), $forseReset = false)
	{
		try {

			// lead_status ----------  for lead status
			// lead --------------- To send mail to agent on lead created

			if (!is_array($sendTo)) {
				return ['success' => false, 'message' => 'Client or agent is not defined'];
			}
			$sendToTypeStr = implode("','", $sendTo);
			$qrtyWhere = "1=1 and form_type='$type' and template_for in ('$sendToTypeStr')";

			$temIdArray = $this->CI->Acc_Model->checkTemplateLogicCondition($qrtyWhere, 'lead', $lead_id);

			if (!is_array($temIdArray) || count($temIdArray) < 1) {
				return array('success' => false, 'message' => 'Oops no template assigned');
			}

			$this->CI->load->library('sendmail', array('redirect' => false));
			if ($forseReset === true) {
				if (isset($this->CI->sendmail->compDtls))
					unset($this->CI->sendmail->compDtls);
				if (isset($this->CI->sendmail->email))
					unset($this->CI->sendmail->email);

				$this->CI->sendmail->resetConfig(array('redirect' => false));
			}

			$this->CI->load->model('Home_Model');
			$this->CI->load->model('Lead_Model');

			// GET LEAD AND ITS ITEM DETAILS
			$dataArray = $this->CI->Lead_Model->getLeadDetailForEmail(array('t1.lead_id' => $lead_id));
			$itemArray = $this->CI->Lead_Model->getLeadItemList(array('lead_id' => $lead_id));

			$notifyArray = explode(',', $dataArray['notify_client_via']);

			if (@$dataArray == 0)
				return array('success' => false, 'message' => 'No data found!');
			// write_to_file($temIdArray);
			foreach ($temIdArray as $key => $value) {

				$tempLogicArray = explode(",", $value);
				if (!isset($tempLogicArray[1]) || $tempLogicArray[1] == '')
					continue;

				if ($tempLogicArray[1] == 'client' && empty($notifyArray[0]))
					return array('success' => false, 'message' => 'No medium found to send notification');


				$templatedbArray = $this->CI->Home_Model->getTemplateDetail(array('t.id' => $tempLogicArray[0], 't.status' => 1));

				if (@$templatedbArray == 0) {
					return array('success' => FALSE, 'message' => 'Please select template to send email !!', 'data' => array());
				}

				$template_name = $type;

				$dataArray['share_url'] = $this->createIPublicURLCode(array('ref_type' => 'LD', 'ref_id' => "$lead_id"), true, true);
				$finalTemplate = $this->setEailSMSTemplate($template_name, $templatedbArray, $dataArray, $itemArray);

				$dataArray['share_url'] = $this->createIPublicURLCode(array('ref_type' => 'LD', 'ref_id' => "$lead_id"), true, false);

				$finalTemplateSMS = $this->setEailSMSTemplate($template_name, $templatedbArray, $dataArray, $itemArray);

				// Send Email to client
				$emailSmsArrayLog = array();
				$sendToEmail = (($tempLogicArray[1] == 'agent')) ? $dataArray['agent_email'] : $dataArray['acc_head_email'];
				$sendToName = (($tempLogicArray[1] == 'agent')) ? $dataArray['agent_name'] : $dataArray['acc_head'];

				if ($tempLogicArray[1] == 'client' && in_array(TEMPLATE_TYPE_EMAIL, $notifyArray)  && $finalTemplate['emilSubject'] != '' && $finalTemplate['emilBody'] != '' && $sendToEmail != '' && $templatedbArray[0]['temp_type'] == 'Email') {

					$sendToArray = array('send_to' => $dataArray['acc_head_email'], 'send_to_name' => $dataArray['acc_head'], 'cc' => '');
					$data['to'] = $dataArray['acc_head_email'];
					$data['name'] = $dataArray['acc_head'];
					$data['cc'] = '';
					$data['subject'] = $finalTemplate['emilSubject'];
					$data['email_body_new'] = $finalTemplate['emilBody'];
					$data['attach_pdf'] = ($template_name == 'lead' && $tempLogicArray[1] == 'client') ? 1 : 0;
					$data['ref_type'] = $template_name;
					$data['ref_id'] = $lead_id;
					$this->CI->sendmail->sendEmailTemplate($data);
					$this->CI->sendmail->resetToClient(array('redirect' => false));
				}

				if ($tempLogicArray[1] == 'agent' && $finalTemplate['emilSubject'] != '' && $finalTemplate['emilBody'] != '' && $sendToEmail != '' && $templatedbArray[0]['temp_type'] == 'Email') {
					$sendToArray = array('send_to' => $dataArray['agent_email'], 'send_to_name' => $dataArray['agent_name'], 'cc' => '');
					$data['to'] = $dataArray['agent_email'];
					$data['name'] = $dataArray['agent_name'];
					$data['cc'] = '';
					$data['subject'] = $finalTemplate['emilSubject'];
					$data['email_body_new'] = $finalTemplate['emilBody'];
					$data['attach_pdf'] = ($template_name == 'lead' && $tempLogicArray[1] == 'client') ? 1 : 0;
					$data['ref_type'] = $template_name;
					$data['ref_id'] = $lead_id;
					$this->CI->sendmail->sendEmailTemplate($data);
					$this->CI->sendmail->resetToClient(array('redirect' => false));
				}

				// Send Email To client *****************END
				$temdata = array('reference_id' => $lead_id, 'client_name' => $dataArray['acc_head'], 'reference_name' => $template_name, 'sendto_name' => $sendToName);

				// Send SMS To client
				$senToMobile = ($tempLogicArray[1] == 'agent') ? $dataArray['agent_phone'] : $dataArray['acc_head_mobile'];

				if ($tempLogicArray[1] == 'client' && in_array(TEMPLATE_TYPE_SMS, $notifyArray) && $finalTemplateSMS['smsBody'] != '' && $senToMobile != '' && $templatedbArray[0]['temp_type'] == 'SMS') {
					$this->CI->sendmail->sendSMSTemplate($finalTemplateSMS['smsBody'], $senToMobile, $temdata, true, false, @$templatedbArray[0]['gov_template_id']);
				}
				if ($tempLogicArray[1] == 'agent' && $finalTemplateSMS['smsBody'] != '' && $senToMobile != '' && $templatedbArray[0]['temp_type'] == 'SMS') {
					$this->CI->sendmail->sendSMSTemplate($finalTemplateSMS['smsBody'], $senToMobile, $temdata, true, false, @$templatedbArray[0]['gov_template_id']);
				}
				// Send SMS To client *****************END

				// Send Whatsapp To client *****************START
				if ($tempLogicArray[1] == 'client' && in_array(TEMPLATE_TYPE_WHATSAPP, $notifyArray) && $finalTemplateSMS['smsBody'] != '' && $senToMobile != '' && $templatedbArray[0]['temp_type'] == 'SMS') {
					$this->CI->sendmail->sendSMSTemplate($finalTemplateSMS['smsBody'], $senToMobile, $temdata, false, true);
				}

				if ($tempLogicArray[1] == 'agent' && $finalTemplateSMS['smsBody'] != '' && $senToMobile != '' && $templatedbArray[0]['temp_type'] == 'SMS') {
					$this->CI->sendmail->sendSMSTemplate($finalTemplateSMS['smsBody'], $senToMobile, $temdata, false, true);
				}
			}
			return array('success' => true, 'message' => 'Notification sent successfully');
		} catch (Exception $e) {
			return array('success' => false, 'message' => $e->getMessage());
		}
	}

	function sendLeadNotification($lead_id, $type = 'lead', $sendTo = ['client', 'agent'])
	{
		$this->CI->load->library('producer');
		$data['fx'] = $this->getFxVariables();
		$data['data'] = ['lead_id' => $lead_id, 'type' => $type, 'send_to' => $sendTo];
		$proStatus = $this->CI->producer->send('lead', 'notification', $data);
		if ($proStatus === false) {
			return $this->sendLeadNotificationOffline($lead_id, $type, $sendTo);
		}
	}

	public function getFxVariables()
	{
		return ['clientCompId' => $this->clientCompId, 'clientId' => $this->clientId, 'clientCompDb' => $this->clientCompDb, 'clientCompId' => $this->clientCompId, 'clientAccessID' => $this->clientAccessID, 'clientFinYr' => $this->clientFinYr, 'baseurl' => base_url()];
	}

	function api_response($response, $status_code = 200)
	{
		$CI = &get_instance();
		$CI->output
			->set_status_header($status_code)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
		exit;
	}

	function dbError($db, $exit = false)
	{
		$message = @$db->error()['message'];
		if ($exit == true && $message != '')
			$this->api_response(['success' => false, 'message' => $message], 400);
		return $message;
	}

	function getValueFromDb($query)
	{
		$db_debug = $this->CI->db->db_debug; // save setting
		$this->CI->db->trans_begin();
		$this->CI->db->db_debug = false;
		$result = $this->CI->db->query($query)->result_array();
		if ($this->dbError($this->CI->db, false) != '')
			return [];

		$this->CI->db->trans_rollback();
		$this->CI->db->db_debug = $db_debug; //disable debugging for queries
		return $result;
	}

	public function getVoucherSeries($branch_id, $voucher_type, $invoice_min_lenght = '')
	{
		$finyrId = $this->clientFinYr;
		$data = $this->CI->Acc_Model->getScalerCol("voucher_series", "comp_voucher_series", "finyr_id=$finyrId and branch_id=$branch_id and voucher_type='$voucher_type'");
		// $stateCode = $this->CI->Acc_Model->getScalerCol("state", "comp_branch_master", "branch_id=$branch_id")->state;
		$invoice_min_lenght = isset($this->invoiceNoMinlength) ? $this->invoiceNoMinlength : $invoice_min_lenght;

		$tablename = '';
		if ($voucher_type == 'CP') {
			$tablename = "comp_ve_cashpayment_$finyrId";
		} elseif ($voucher_type == 'CR') {
			$tablename = "comp_ve_cashreceipt_$finyrId";
		} elseif ($voucher_type == 'BP') {
			$tablename = "comp_ve_bankpayment_$finyrId";
		} elseif ($voucher_type == 'BR') {
			$tablename = "comp_ve_bankreceipt_$finyrId";
		} elseif ($voucher_type == 'JB') {
			$tablename = "comp_ve_journalbook_$finyrId";
		}

		$voucherNo = $this->CI->Acc_Model->getScalerCol("ifnull(max(cast(voucher_sno as signed)),0)+1 voucherNo", $tablename, "branch_id=$branch_id")->voucherNo;


		$voucherSeries = isset($data->voucher_series) ? $data->voucher_series : '';
		$voucherSeries = str_replace('{M}', strtoupper(date('M')), $voucherSeries);
		$voucherSeries = str_replace('{m}', date('m'), $voucherSeries);
		$voucherSeries = str_replace('{Y}', date('Y'), $voucherSeries);
		$voucherSeries = str_replace('{y}', date('y'), $voucherSeries);
		$voucherSeries = str_replace('{Y+1}', date('Y') + 1, $voucherSeries);
		$voucherSeries = str_replace('{y+1}', date('y') + 1, $voucherSeries);

		$voucherNo = sprintf("%0" . $invoice_min_lenght . "d", $voucherNo);
		if (strlen($voucherSeries . $voucherNo) > 16) {
			$voucherSeries = '0';
			$voucherNo = '0';
		}
		return (object) array('voucherSeries' => $voucherSeries, 'voucherNo' => $voucherNo, 'vouchertype' => $voucher_type);
	}


	function getAddress($lat, $lng, $key)
	{
		//$key = $_SESSION['key'];
		$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key=$key";
		$response = file_get_contents($url);
		$data = json_decode($response);
		$address = $data->results[0]->formatted_address;
		return $address;
	}
}
