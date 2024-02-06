
 <?php
	/**
	 *
	 */

	class Acc_Model extends CI_Model
	{

		function __construct()
		{
			parent::__construct();

			$this->db->query("set sql_mode='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
		}

		public function setTables()
		{

			if (isset($this->session->CLIENT)) {
				$this->compDb = @$this->session->CLIENT->clientComp->compDb;
				$this->finyrId = @$this->session->CLIENT->clientComp->finYr;
				$this->compId = @$this->session->CLIENT->clientComp->compId;
			} else {
				$this->compDb = $this->fx->clientCompDb;
				$this->finyrId = $this->fx->clientFinYr;
				$this->compId = @$this->fx->clientCompId;
			}
			$this->saleMasterTable = "$this->compDb.comp_sale_master_$this->finyrId";
			$this->saleItemListTable = "$this->compDb.comp_sale_itemlist_$this->finyrId";
			$this->saleItemTaxTable = "$this->compDb.comp_sale_itemtax_$this->finyrId";
			$this->invrtnMasterTable = "$this->compDb.comp_invrtn_master_$this->finyrId";
			$this->invrtnItemListTable = "$this->compDb.comp_invrtn_itemlist_$this->finyrId";
			$this->invrtnItemTaxTable = "$this->compDb.comp_invrtn_itemtax_$this->finyrId";
			$this->purchaseMasterTable = "$this->compDb.comp_purchase_master_$this->finyrId";
			$this->purchaseItemListTable = "$this->compDb.comp_purchase_itemlist_$this->finyrId";
			$this->purchaseItemTaxTable = "$this->compDb.comp_purchase_itemtax_$this->finyrId";
			$this->prtnMasterTable = "$this->compDb.comp_prtn_master_$this->finyrId";
			$this->prtnItemListTable = "$this->compDb.comp_prtn_itemlist_$this->finyrId";
			$this->prtnItemTaxTable = "$this->compDb.comp_prtn_itemtax_$this->finyrId";
			$this->paymentTable = "$this->compDb.comp_payment_transactions_$this->finyrId";
			$this->transactionTable = "$this->compDb.comp_transactions";
			$this->lead_souce_table = $this->compDb . '.comp_lead_source';
			$this->lead_type_table = $this->compDb . '.comp_lead_type';
			$this->lead_status_table = $this->compDb . '.comp_lead_status';
			$this->notification_log = $this->compDb . ".comp_notification_log";
			$this->sale_master = "$this->compDb.comp_sale_master_$this->finyrId";
			$this->purchase_master = "$this->compDb.comp_purchase_master_$this->finyrId";
			$this->billAdjTable = "$this->compDb.comp_bill_adjustment";
			$this->favMenuTable = "$this->compDb.comp_favourite_menu";
			$this->cntrctMaster = "$this->compDb.comp_contract_master";
			$this->template_master = $this->compDb . '.comp_template';
			$this->template_assign = $this->compDb . '.comp_template_assign';
			$this->challaninMasterTable = "$this->compDb.comp_challanin_master";
			$this->challaninItemListTable = "$this->compDb.comp_challanin_itemlist";
		}

		public function getScalerCol($column, $table, $where, $masterDb = NULL)
		{
			$this->setTables();
			$this->db->select($column);
			if ($masterDb == 1) {
				$this->db->from($table);
			} else {
				$this->db->from("$this->compDb." . $table);
			}
			$this->db->where($where, NULL, false);
			return $this->db->get()->row();
		}

		function getDefaultURLAfterLogin($client_id)
		{
			$result =  $this->db->query("SELECT comp_id,t1.menu_id,t2.page_id,t1.menu_name,t1.menu_url,t1.menu_parent,t1.menu_priority,t1.menu_icon, IFNULL(t2.view_right,0) view_right, IFNULL(t2.add_right,0) add_right, IFNULL(t2.edit_right,0) edit_right, IFNULL(t2.delete_right,0) delete_right, IFNULL(t2.excel_right,0) excel_right,IFNULL(t2.is_default,0) is_default
		FROM acc_client_menu_master t1
		inner JOIN acc_client_rights t2 ON t1.menu_id=t2.page_id
		where client_id='$client_id' and is_default='1'
		ORDER BY t1.menu_parent asc,t1.menu_priority asc")->row();

			if (isset($result->menu_url)) {
				return $result->menu_url;
			} else {
				return 'home';
			}
		}

		public function parentMenuList($masterClient)
		{
			$client_id = @$this->fx->clientId;
			//~ $redisResult = $this->redisclient->getMenu("menu_$client_id");
			//~ if($redisResult!='')
			//~ return $redisResult;

			$comp_id = @$this->session->CLIENT->clientComp->compId;
			if ($masterClient == 0) {
				$this->db->from("acc_client_menu_master");
				$this->db->where('menu_parent', 0);
				$this->db->where("(comp_id='0' or comp_id is null or comp_id='$comp_id')", null, false);
				if (count($this->fx->ignrMenu) > 0)
					$this->db->where_not_in('menu_id', $this->fx->ignrMenu);
				$this->db->order_by("menu_priority asc", "menu_id desc");
				$result = $this->db->get()->result();
			} else {
				$ignrMenu = implode(",", $this->fx->ignrMenu);
				if ($ignrMenu != '') {
					$menuIdWhere = " and menu_id not IN ($ignrMenu)";
					$menuPrntWhere = " and menu_parent not IN ($ignrMenu)";
				} else {
					$menuIdWhere = "";
					$menuPrntWhere = "";
				}
				$menuParent = $this->db->query("SELECT * from acc_client_menu_master where menu_id in (Select MM.menu_parent from acc_client_menu_master MM JOIN acc_client_rights UR ON UR.page_id=MM.menu_id Where UR.client_id=" . $this->fx->clientId . " and view_right=1 AND MM.status=1 " . $menuPrntWhere . ") union (Select MM.* from acc_client_menu_master MM JOIN acc_client_rights UR ON UR.page_id=MM.menu_id Where (comp_id='0' or comp_id is null or comp_id='$comp_id') and UR.client_id=" . $this->fx->clientId . " and view_right=1 and menu_parent=0 AND MM.status=1 " . $menuIdWhere . ")  order by menu_priority,menu_id desc");
				$result = $menuParent->result();
			}
			$subMenu = $this->getAllChildMenus($masterClient);
			$response = ['menu' => $result, 'submenu' => $subMenu];
			//~ $redisResult = $this->redisclient->set("menu_$client_id", $response,3600);
			return $response;
		}

		function getAllChildMenus($masterClient)
		{
			$comp_id = @$this->session->CLIENT->clientComp->compId;
			if ($masterClient == 0) {
				$this->db->from("acc_client_menu_master");

				$this->db->order_by("menu_priority asc", "menu_id desc");
				$this->db->where("(comp_id='0' or comp_id is null or comp_id='$comp_id') AND status=1", null, false);
			} else {
				$this->db->select("MM.*");
				$this->db->from("acc_client_menu_master as MM");
				$this->db->join("acc_client_rights as CUR", "CUR.page_id=MM.menu_id");
				$this->db->where('CUR.client_id', $this->fx->clientId);
				$this->db->where('view_right', 1);
				$this->db->where("(comp_id='0' or comp_id is null or comp_id='$comp_id') AND MM.status=1", null, false);
				$this->db->order_by("menu_priority asc", "menu_id desc");
			}
			$sunMenuArray = [];
			foreach ($this->db->get()->result() as $key => $value) {
				$sunMenuArray[$value->menu_parent][] = $value;
			}
			return $sunMenuArray;
		}

		public function childMenuList($masterClient, $menuId)
		{
			$comp_id = @$this->session->CLIENT->clientComp->compId;
			if ($masterClient == 0) {
				$this->db->from("acc_client_menu_master");
				$this->db->where('menu_parent', $menuId);
				$this->db->order_by("menu_priority asc", "menu_id desc");
				$this->db->where("(comp_id='0' or comp_id is null or comp_id='$comp_id')", null, false);
			} else {
				$this->db->select("MM.*");
				$this->db->from("acc_client_menu_master as MM");
				$this->db->join("acc_client_rights as CUR", "CUR.page_id=MM.menu_id");
				$this->db->where('CUR.client_id', $this->fx->clientId);
				$this->db->where('view_right', 1);
				$this->db->where('menu_parent', $menuId);
				$this->db->where("(comp_id='0' or comp_id is null or comp_id='$comp_id')", null, false);
				$this->db->order_by("menu_priority asc", "menu_id desc");
			}
			return $this->db->get()->result();
		}

		// public function getPageId($pagename)
		// {
		// 	$this->db->select("menu_id");
		// 	$this->db->from("acc_client_menu_master");
		// 	$this->db->where('menu_url', $pagename);
		// 	$db = $this->db->get();
		// 	if ($db->num_rows() > 0) {
		// 		return $db->row()->menu_id;
		// 	} else {
		// 		return 0;
		// 	}
		// }

		public function getPageId($pagename)
		{
			$comp_id = @$this->session->CLIENT->clientComp->compId;
			$string = "dynamictable/init/";
			if (strpos($pagename, $string) !== false) {
				$this->db->select("menu_id");
				$this->db->from("acc_client_menu_master");
				$this->db->where('menu_url', $pagename);
				$this->db->where('comp_id', $comp_id);
				$db = $this->db->get();
				if ($db->num_rows() > 0) {
					return $db->row()->menu_id;
				} else {
					return 0;
				}
			} else {
				$this->db->select("menu_id");
				$this->db->from("acc_client_menu_master");
				$this->db->where('menu_url', $pagename);
				$db = $this->db->get();
				if ($db->num_rows() > 0) {
					return $db->row()->menu_id;
				} else {
					return 0;
				}
			}
		}

		public function CheckPerm($clientId, $pageId)
		{
			$this->db->select("view_right, add_right, edit_right, delete_right,excel_right");
			$this->db->from("acc_client_rights");
			$this->db->where("client_id", $clientId);
			$this->db->where("page_id", $pageId);
			$db = $this->db->get();
			if ($db->num_rows() > 0) {
				$result = $db->row();
				$data['VIEW_RIGHT'] = $result->view_right;
				$data['ADD_RIGHT'] = $result->add_right;
				$data['EDIT_RIGHT'] = $result->edit_right;
				$data['DELETE_RIGHT'] = $result->delete_right;
				$data['EXCEL_RIGHT'] = $result->excel_right;
			} else {
				$data['VIEW_RIGHT'] = 0;
				$data['ADD_RIGHT'] = 0;
				$data['EDIT_RIGHT'] = 0;
				$data['DELETE_RIGHT'] = 0;
				$data['EXCEL_RIGHT'] = 0;
			}
			return $data;
		}

		function getMultiplePagePermissionCheck($pages, $clientId)
		{
			$this->db->select("t1.menu_id,t1.menu_url,t2.view_right,t2.add_right,t2.edit_right,t2.delete_right,t2.excel_right");
			$this->db->from("acc_client_menu_master t1");
			$this->db->join("acc_client_rights t2", 't1.menu_id=t2.page_id', 'inner');
			$this->db->where_in('menu_url', $pages);
			$this->db->where("t2.client_id", $clientId);
			return $this->db->get()->result();
		}

		public function getFeaturePages($ignrMenuAr)
		{
			$ignrMenu = implode(",", $ignrMenuAr);
			$ftrPage = $this->db->query("SELECT menu_url FROM acc_client_menu_master WHERE (menu_parent IN ($ignrMenu) or menu_id IN ($ignrMenu)) and menu_url!='javascript:void(0)'");
			$ftrPageList = $ftrPage->result();
			foreach ($ftrPageList as $key => $val) {
				$pageAr[$key] = $val->menu_url;
			}
			return $pageAr;
		}

		public function getMetaTypeList($where)
		{
			$this->db->select("meta_id,meta_name");
			$this->db->from("acc_metadata");
			$this->db->where($where, NULL, false);
			$this->db->order_by("meta_id");
			return $this->db->get()->result();
		}

		public function getStateList($where = '')
		{
			$this->db->select("state_code,state_name");
			$this->db->from("acc_state_master");
			$this->db->order_by("state_name");
			if ($where != '')
				$this->db->where($where, NULL, false);

			return $this->db->get()->result();
		}

		public function getStateListArray()
		{
			$this->db->select("state_code,state_name");
			$this->db->from("acc_state_master");
			$this->db->order_by("state_name");
			return $this->db->get()->result_array();
		}

		public function getClientList($where)
		{
			$this->db->select("client_id,concat(client_firstname,' ',client_lastname) clientName,profile_image");
			$this->db->from("acc_client_master");
			$this->db->where($where, NULL, false);
			$this->db->where('status', '1');
			$this->db->order_by("clientName");
			return $this->db->get()->result();
		}

		public function getCompanyList($where)
		{
			$this->db->select("t1.comp_id,name");
			$this->db->distinct();
			$this->db->from("acc_client_company t1");
			$this->db->join("acc_subclient_branch t2", "t1.comp_id=t2.comp_id", "left");
			$this->db->where($where, NULL, false);
			$this->db->order_by("name");
			return $this->db->get()->result();
		}

		public function createClientDb($clientId)
		{
			$sql = file_get_contents(FCPATH . "clientDbSql/acc_clientDb.sql");
			$dbName = "sanacc_clientDb_" . $clientId;
			$this->db->query("CREATE DATABASE IF NOT EXISTS " . $dbName . ";");
			$this->db->query("USE " . $dbName . ";");
			$sqls = explode(';', $sql);
			array_pop($sqls);
			foreach ($sqls as $statement) {
				$statment = $statement . ";";
				$this->db->query($statement);
			}
			// To RUN DEFAULT INSERT QUERY INTO DATABASE ***************** START
			$sql = file_get_contents(FCPATH . "clientDbSql/acc_clientDb_insert_qry.sql");
			$sqls = explode('##SAN##', $sql);
			foreach ($sqls as $statement) {
				$statment = $statement . ";";
				$this->db->query($statement);
			}
			// To RUN DEFAULT INSERT QUERY INTO DATABASE ***************** END
			$this->db->close();
			$this->load->database();
			return $dbName;
		}

		public function dropCompanyDatabase($comp_id)
		{
			$currentDomain = parse_url(base_url());
			if ($currentDomain['host'] == WEBSITE_DOMAIN_HOST)
				return;
			$dbName = "sanacc_clientDb_" . $comp_id;
			return $this->db->query("DROP DATABASE IF EXISTS " . $dbName . ";");
		}

		public function getCompDb($compId)
		{
			$this->db->select("comp_db");
			$this->db->from("acc_client_company");
			$this->db->where("comp_id", $compId);
			$result = $this->db->get()->row();
			if (empty($result->comp_db)) {
				fx::pr($compId, 1);
			}
			return $result->comp_db;
		}

		public function getCompDetails($compId, $clientId = '', $detail = false)
		{
			$this->db->from("acc_client_company as t1");
			$this->db->where("t1.comp_id", $compId);
			if ($clientId != '')
				$this->db->where("t1.client_id", $clientId);

			if ($detail == true) {
				$this->db->select("t1.*,t3.state_name");
				$this->db->join("acc_state_master t3", "t1.state=t3.state_code", "left");
			}
			return $this->db->get()->row();
		}

		public function getCompanyDbList($where)
		{
			$this->db->select("comp_id,comp_db");
			$this->db->from("acc_client_company");
			if ($where != '')
				$this->db->where($where, NULL, false);
			$this->db->order_by("name");
			return $this->db->get()->result();
		}

		public function getBranchList($where, $db, $subUserBrnch = array())
		{
			if (count($subUserBrnch) > 0) {
				$brIds = $this->db->select("branch_id")->where($subUserBrnch)->get("acc_subclient_branch")->result();
				foreach ($brIds as $brId) {
					$brAr[] = $brId->branch_id;
				}
				if (count($brAr) < 1)
					return [];

				$brSt = implode(',', $brAr);
				$where .= " and branch_id in ($brSt)";
			}

			$this->db->select("branch_id,branch_name,is_default,state");
			$this->db->from("$db.comp_branch_master");
			$this->db->where($where, NULL, false);
			$this->db->order_by("branch_id");
			return $this->db->get()->result();
		}

		public function getDefBranchList($where, $db, $subUserBrnch = array())
		{
			if (count($subUserBrnch) > 0) {
				return $brIds = $this->db->select("branch_id")->where($subUserBrnch)->where("default", 1)->get("acc_subclient_branch")->row();
			}

			// $this->db->select("branch_id,branch_name,is_default,state");
			// $this->db->from("$db.comp_branch_master");
			// $this->db->where($where, NULL, false);
			// $this->db->order_by("branch_id");
			// return $this->db->get()->result();
		}

		public function getInvoiceTypeList($where, $db)
		{
			$this->db->select("invoicetype_id,invoice_type,is_default");
			$this->db->from("$db.comp_invoice_type");
			$this->db->where($where, NULL, false);
			$this->db->order_by("invoicetype_id");
			return $this->db->get()->result();
		}

		public function getFinyrCount($db)
		{
			$this->db->select("count(finyr_id) cnt");
			$this->db->from("$db.comp_financial_year");
			return $this->db->get()->row()->cnt;
		}

		public function getFinyrList($where, $db)
		{
			$this->db->select("finyr_id,finyr_name");
			$this->db->from("$db.comp_financial_year");
			$this->db->where($where, NULL, false);
			$this->db->order_by("finyr_st_date");
			return $this->db->get()->result();
		}

		public function getLastFindancialYear($where)
		{
			$this->setTables();
			$this->db->select("finyr_id,finyr_name");
			$this->db->from("$this->compDb.comp_financial_year");
			$this->db->where($where, NULL, false);
			$this->db->order_by("finyr_st_date desc");
			$this->db->limit(1);
			return $this->db->get()->row();
		}

		public function getTaxList($where, $db)
		{
			$this->db->select("tax_id,tax_name");
			$this->db->from("$db.comp_tax_master");
			$this->db->where($where, NULL, false);
			$this->db->order_by("tax_id");
			return $this->db->get()->result();
		}
		public function getTCSTaxList($where, $db)
		{
			$this->setTables();
			if ($db == '')
				$db = $this->compDb;
			$this->db->select("t1.tax_id,t1.tax_name,t1.ledger_id,t2.tax_percentage,t2.acc_head");
			$this->db->from("$db.comp_tax_master t1");
			$this->db->join("$db.comp_ledger_master t2", "t1.ledger_id=t2.ledger_id", 'left');
			$this->db->where($where, NULL, false);
			$this->db->order_by("t1.tax_name");
			return $this->db->get()->result();
		}

		public function getItemCateList($where, $db)
		{
			$this->db->select("cat_id,cat_name");
			$this->db->from("$db.comp_item_category");
			$this->db->where($where, NULL, false);
			$this->db->order_by("cat_name");
			return $this->db->get()->result();
		}

		public function getTaxCateList($where, $db)
		{
			$this->db->select("cat_id,cat_name");
			$this->db->from("$db.comp_tax_category");
			$this->db->where($where, NULL, false);
			$this->db->order_by("cat_name");
			return $this->db->get()->result();
		}

		public function getItemMakeList($where, $db)
		{
			$this->db->select("make_id,make_name");
			$this->db->from("$db.comp_item_make");
			$this->db->where($where, NULL, false);
			$this->db->order_by("make_name");
			return $this->db->get()->result();
		}

		public function getItemList($where, $db)
		{
			$this->db->select("item_id,item_name");
			$this->db->from("$db.comp_item_master");
			$this->db->where($where, NULL, false);
			$this->db->order_by("item_name");
			return $this->db->get()->result();
		}

		public function getGroupList($where, $db)
		{
			$this->db->select("group_id,group_name");
			$this->db->from("$db.comp_group");
			$this->db->where($where, NULL, false);
			$this->db->order_by("group_name");
			return $this->db->get()->result();
		}

		public function getSubGroupList($where, $db)
		{
			$this->db->select("sub_group_id,sub_group_name,accept_address,g.behaviour");
			$this->db->from("$db.comp_sub_group as s");
			$this->db->join("$db.comp_group as g", "g.group_id=s.group_id", 'left');
			$this->db->where($where, NULL, false);
			$this->db->order_by("sub_group_name");
			return $this->db->get()->result();
		}

		public function getLedgerList($where, $db, $join = false)
		{
			$this->db->select("ledger_id,acc_head");
			$this->db->from("$db.comp_ledger_master");
			if ($join == true) {
				$this->db->join("$db.comp_sub_group t2", "$db.comp_ledger_master.acc_sub_group=t2.sub_group_id", "left");
			}
			$this->db->where($where, NULL, false);
			$this->db->order_by("acc_head");
			return $this->db->get()->result();
		}

		public function getAgentList($where, $db)
		{
			$this->db->select("agent_id,agent_name");
			$this->db->from("$db.comp_agent_master");
			$this->db->where($where, NULL, false);
			$this->db->order_by("agent_name");
			return $this->db->get()->result();
		}

		public function getChallanTypeList($where, $db)
		{
			$this->db->select("ch_type_id,ch_type");
			$this->db->from("$db.comp_challantype_master");
			$this->db->where($where, NULL, false);
			$this->db->order_by("ch_type");
			return $this->db->get()->result();
		}

		public function getCostCenterList($where, $db)
		{
			$this->db->select("cc_id,cc_name,cc_unique_id");
			$this->db->from("$db.comp_cost_center");
			$this->db->where($where, NULL, false);
			$this->db->order_by("cc_name");
			return $this->db->get()->result();
		}

		public function saleItemList($saleId)
		{
			$this->setTables();
			$this->db->select("branch_id,item_id,(qty+free_qty) as qty,service_item");
			$this->db->from("$this->saleMasterTable t1");
			$this->db->join("$this->saleItemListTable t2", "t1.sale_id=t2.sale_id", "left");
			$this->db->where("t1.sale_id", $saleId);
			return $this->db->get()->result();
		}

		public function purchaseItemList($purchaseId)
		{
			$this->setTables();
			$this->db->select("branch_id,item_id,(qty+free_qty) as qty,service_item");
			$this->db->from("$this->purchaseMasterTable t1");
			$this->db->join("$this->purchaseItemListTable t2", "t1.purchase_id=t2.purchase_id", "left");
			$this->db->where("t1.purchase_id", $purchaseId);
			return $this->db->get()->result();
		}

		public function challanInItemList($challanin_id)
		{
			$this->setTables();
			$this->db->select("branch_id,item_id,(qty+free_qty) as qty,service_item");
			$this->db->from("$this->challaninMasterTable t1");
			$this->db->join("$this->challaninItemListTable t2", "t1.challanin_id=t2.challanin_id", "left");
			$this->db->where("t1.challanin_id", $challanin_id);
			return $this->db->get()->result();
		}

		public function pRtnItemList($prtnId)
		{
			$this->setTables();
			$this->db->select("branch_id,item_id,(qty+free_qty) as qty,service_item");
			$this->db->from("$this->prtnMasterTable t1");
			$this->db->join("$this->prtnItemListTable t2", "t1.prtn_id=t2.prtn_id", "left");
			$this->db->where("t1.prtn_id", $prtnId);
			return $this->db->get()->result();
		}

		public function invRtnItemList($prtnId)
		{
			$this->setTables();
			$this->db->select("branch_id,item_id,(qty+free_qty) as qty,service_item");
			$this->db->from("$this->invrtnMasterTable t1");
			$this->db->join("$this->invrtnItemListTable t2", "t1.invrtn_id=t2.invrtn_id", "left");
			$this->db->where("t1.invrtn_id", $prtnId);
			return $this->db->get()->result();
		}

		public function challanItemList($chlnId)
		{
			$this->setTables();
			$this->db->select("branch_id,t2.item_id,qty,t3.service_item");
			$this->db->from("$this->compDb.comp_challan_master t1");
			$this->db->join("$this->compDb.comp_challan_itemlist t2", "t1.challan_id=t2.challan_id", "left");
			$this->db->join("$this->compDb.comp_item_master t3", "t2.item_id=t3.item_id", "left");
			$this->db->where("t1.challan_id", $chlnId);
			return $this->db->get()->result();
		}

		public function stockIssueItemList($id)
		{
			$this->setTables();
			$this->db->select("branch_id,t2.item_id,qty,t3.service_item");
			$this->db->from("$this->compDb.comp_stockissue_master t1");
			$this->db->join("$this->compDb.comp_stockissue_itemlist t2", "t1.id=t2.stock_issue_id", "left");
			$this->db->join("$this->compDb.comp_item_master t3", "t2.item_id=t3.item_id", "left");
			$this->db->where("t1.id", $id);
			return $this->db->get()->result();
		}

		public function onGoingInvItemList($inv_id)
		{
			$this->setTables();
			$this->db->select("branch_id,t2.item_id,qty,t3.service_item");
			$this->db->from("$this->compDb.comp_ongoing_inv_master t1");
			$this->db->join("$this->compDb.comp_ongoing_inv_itemlist t2", "t1.inv_id=t2.inv_id", "left");
			$this->db->join("$this->compDb.comp_item_master t3", "t2.item_id=t3.item_id", "left");
			$this->db->where("t1.inv_id", $inv_id);
			return $this->db->get()->result();
		}

		public function challanRtnItemList($chlnRtnId)
		{
			$this->setTables();
			$this->db->select("branch_id,t2.item_id,qty,t3.service_item");
			$this->db->from("$this->compDb.comp_challanrtn_master t1");
			$this->db->join("$this->compDb.comp_challanrtn_itemlist t2", "t1.challan_rtn_id=t2.challan_rtn_id", "left");
			$this->db->join("$this->compDb.comp_item_master t3", "t2.item_id=t3.item_id", "left");
			$this->db->where("t1.challan_rtn_id", $chlnRtnId);
			return $this->db->get()->result();
		}

		public function stockTrnsfrItemList($trnsfrId, $br)
		{
			$this->setTables();
			$this->db->select("$br branch_id,t2.item_id,qty,t3.service_item");
			$this->db->from("$this->compDb.comp_stocktransfer t1");
			$this->db->join("$this->compDb.comp_stocktransfer_itemlist t2", "t1.trnsfr_id=t2.trnsfr_id", "left");
			$this->db->join("$this->compDb.comp_item_master t3", "t2.item_id=t3.item_id", "left");
			$this->db->where("t1.trnsfr_id", $trnsfrId);
			return $this->db->get()->result();
		}

		public function dailyProductionItemList($production_id, $br)
		{
			$this->setTables();
			$this->db->select("$br branch_id,t2.item_id,qty,t3.service_item");
			$this->db->from("$this->compDb.comp_production_master t1");
			$this->db->join("$this->compDb.comp_production_itemlist t2", "t1.id=t2.production_id", "left");
			$this->db->join("$this->compDb.comp_item_master t3", "t2.item_id=t3.item_id", "left");
			$this->db->where("t1.id", $production_id);
			return $this->db->get()->result();
		}

		public function dailyProductionRawItemList($production_id, $br)
		{
			$this->setTables();
			$this->db->select("$br branch_id,t2.raw_item_id as item_id,raw_qty as qty,t3.service_item");
			$this->db->from("$this->compDb.comp_production_master t1");
			$this->db->join("$this->compDb.comp_production_rawitem t2", "t1.id=t2.production_id", "left");
			$this->db->join("$this->compDb.comp_item_master t3", "t2.raw_item_id=t3.item_id", "left");
			$this->db->where("t1.id", $production_id);
			return $this->db->get()->result();
		}

		public function getProductionItemSrNo($production_id)
		{
			$this->setTables();
			$this->db->select('production_id,sr_no,sr_no_reuired,item_id,branch as branch_id,item_name,is_reverse');
			$this->db->from($this->compDb . '.comp_production_master as t1');
			$this->db->join($this->compDb . '.comp_production_itemlist as t2', "t2.production_id=t1.id", 'eft');
			return $this->db->where(array('id' => $production_id, 'sr_no_reuired' => 1))->get()->result_array();
		}

		public function getRawItemSrNo($production_id)
		{
			$this->setTables();
			$this->db->select('production_id,raw_sr_no as sr_no,raw_sr_no_required as sr_no_reuired,raw_item_id item_id,branch as branch_id,is_reverse');
			$this->db->from($this->compDb . '.comp_production_master as t1');
			$this->db->join($this->compDb . '.comp_production_rawitem as t2', "t2.production_id=t1.id", 'eft');
			return $this->db->where(array('id' => $production_id, 'raw_sr_no_required' => 1))->get()->result_array();
		}

		public function deleteSerialNumber($branch_id, $item_id, $sr_no, $finyr_id = '')
		{
			$this->db->where(array('branch_id' => $branch_id, 'item_id' => $item_id));
			$this->db->where_in('item_srno', $sr_no);
			$finyr_id = ($finyr_id != '') ? $finyr_id : $this->fx->clientFinYr;
			$this->db->where("finyr_id", $finyr_id);
			return $this->db->delete("$this->compDb.comp_item_srno");
		}

		public function stockCheck($itemId, $brId, $finyr_id = '')
		{
			$this->db->from("$this->compDb.comp_item_stock_master");
			$this->db->where("item_id", $itemId);
			$this->db->where("branch_id", $brId);
			$finyr_id = ($finyr_id != '') ? $finyr_id : $this->fx->clientFinYr;
			$this->db->where("finyr_id", $finyr_id);
			$result = $this->db->get();
			if ($result->num_rows() > 0) {
				return 1;
			} else {
				return 0;
			}
		}

		public function stockInUpdate($itemId, $brId, $qty, $rollback = 0,	$finyr_id = '')
		{
			if ($itemId < 1)
				return;
			$this->setTables();
			$finyr_id = ($finyr_id != '') ? $finyr_id : $this->fx->clientFinYr;
			if ($this->stockCheck($itemId, $brId) == 1) {
				$this->db->set("item_id", $itemId);
				$this->db->set("branch_id", $brId);
				if ($rollback == 0) {
					$this->db->set("stock_in", "stock_in+$qty", false);
					$this->db->set("stock", "stock+$qty", false);
				} else {
					$this->db->set("stock_in", "stock_in-$qty", false);
					$this->db->set("stock", "stock-$qty", false);
				}
				$this->db->where("item_id", $itemId);
				$this->db->where("branch_id", $brId);
				$this->db->where("finyr_id", $finyr_id);
				return $this->db->update("$this->compDb.comp_item_stock_master");
			} else {
				$data = array('item_id' => $itemId, 'branch_id' => $brId, 'stock_in' => $qty, 'stock' => $qty, 'finyr_id' => $finyr_id);
				return $this->db->insert("$this->compDb.comp_item_stock_master", $data);
			}
		}

		public function stockOutUpdate($itemId, $brId, $qty, $rollback = 0, $finyr_id = '')
		{
			if ($itemId < 1)
				return;
			$this->setTables();
			$finyr_id = ($finyr_id != '') ? $finyr_id : $this->fx->clientFinYr;
			if ($this->stockCheck($itemId, $brId) == 1) {
				$this->db->set("item_id", $itemId);
				$this->db->set("branch_id", $brId);
				if ($rollback == 0) {
					$this->db->set("stock_out", "stock_out+$qty", false);
					$this->db->set("stock", "stock-$qty", false);
				} else {
					$this->db->set("stock_out", "stock_out-$qty", false);
					$this->db->set("stock", "stock+$qty", false);
				}
				$this->db->where("item_id", $itemId);
				$this->db->where("branch_id", $brId);
				$this->db->where("finyr_id", $finyr_id);
				$this->db->update("$this->compDb.comp_item_stock_master");
			} else {
				$data = array("item_id" => $itemId, "branch_id" => $brId, "stock_out" => $qty, "stock" => -$qty, 'finyr_id' => $finyr_id);
				$this->db->insert("$this->compDb.comp_item_stock_master", $data);
			}
		}

		public function partyExists_check($partyName)
		{
			$this->setTables();
			$this->db->where('acc_head', $partyName);
			$query = $this->db->get("$this->compDb.comp_ledger_master");
			if ($query->num_rows() > 0) {
				return true;
			} else {
				return false;
			}
		}

		public function itemExists_check($itemName)
		{
			$this->setTables();
			$this->db->where('item_name', $itemName);
			$query = $this->db->get("$this->compDb.comp_item_master");
			if ($query->num_rows() > 0) {
				return true;
			} else {
				return false;
			}
		}

		/*For PDF Start*/
		public function branchDetails($brId)
		{
			$this->setTables();
			$this->db->select("t1.name,t2.branch_name,t1.comp_id,t2.comp_desc,t1.website, t1.logo_path,t1.letterhead_img, t2.email, CONCAT(t2.phone, IF(t2.tel_no='','', CONCAT(', ',t2.tel_no))) contact,
		case when t2.invoice_address!='' then t2.invoice_address
		else
		CONCAT(IF(t2.address='','', CONCAT(t2.address,', ')), IF(t2.city='','', CONCAT(t2.city,', ')), t3.state_name, ', ', t2.country, IF(t2.pincode='','', CONCAT(' - ',t2.pincode)))
		end address,
		t3.state_code, t3.state_name, t2.signatory, t2.sign_img_path, t2.invoice_remarks, t2.lead_remarks,t2.saleorder_remarks, t2.gstin, t2.pan_no,t2.bank_name,t2.bank_acc_no,t2.bank_branch,t2.bank_ifsc_code,t2.branch_code,t1.fax,t2.city,t2.pincode,t2.address original_address,t1.so_pdf_item_image,t1.po_pdf_item_image,t2.stamp_img_path,t1.is_export,t1.so_top_margin,t1.so_header_margin,t1.so_footer_margin,t1.lead_top_margin,t1.lead_footer_margin,t1.lead_header_margin,t1.in_top_margin,t1.in_header_margin,t1.in_footer_margin,t2.einv_username,t2.einv_password,t2.einv_authtoken,t1.einv_enable");

			$this->db->from("acc_client_company as t1");
			$this->db->join("$this->compDb.comp_branch_master as t2", "t1.comp_id=t2.comp_id", "left");
			$this->db->join("acc_state_master t3", "t2.state=t3.state_code", "left");
			$this->db->where("branch_id", $brId);
			$resut = $this->db->get()->row();
			if (isset($resut->stamp_img_path) && $resut->stamp_img_path != '') {
				$resut->stamp_img_path = base_url('uploads/branchSign/' . $resut->stamp_img_path);
			}
			if (isset($resut->sign_img_path) && $resut->sign_img_path != '') {
				$signImg = explode('/', $resut->sign_img_path);
				if (isset($signImg[count($signImg) - 1])) {
					$resut->sign_img_path = base_url('uploads/branchSign/' . $signImg[count($signImg) - 1]);
				}
			}
			return $resut;
		}

		public function partyDetails($partyId, $address_id = 0)
		{
			$this->setTables();
			$this->db->from("$this->compDb.comp_ledger_master as t1");
			if ($address_id > 0) {
				// 				$this->db->select("t1.acc_head partyName,
				// CONCAT(IF(t1.add1='', '', CONCAT(t1.add1, ', ')), IF(t1.add2='', '', CONCAT(t1.add2, ', ')), IF((t1.city='' or t1.city is null), '', CONCAT(t1.city, ', ')), t2.state_name, ', ', t1.country, IF(t1.pincode='' OR t1.pincode is null, '',concat('-',if(t1.pincode!='',t1.pincode,'')))) AS partyAddress,
				// t3.gstin partyGST,
				//  t2.state_name partyState,
				//  t2.state_code partyStateCode,t1.city,t1.pincode, if(t3.mobile='' or t3.mobile is null,t1.mobile,t3.mobile) mobile,t1.pan_no,t1.cin_no,t3.gstin,if(t3.contact_person='' or t3.contact_person is null,t1.contact_person,t3.contact_person) contact_person,t1.tel_no,t1.email");
				// 				$this->db->join("$this->compDb.comp_ledger_address as t3", 't3.ledger_master_id=t1.ledger_id');
				// 				$this->db->join("acc_state_master as t2", "t2.state_code=t3.state", "left");
				// 				$this->db->where(array('t3.id' => $address_id));
				$this->db->select("t1.acc_head partyName,
				CONCAT(IF(t3.address1='', '', CONCAT(t3.address1, ', ')), IF(t3.address2='', '', CONCAT(t3.address2, ', ')), IF((t3.city='' or t1.city is null), '', CONCAT(t3.city, ', ')), `t2`.`state_name`, ', ', `t3`.`country`, IF(t3.pincode='' OR t3.pincode is null, '', concat('-', if(t3.pincode!='', `t3`.`pincode`, '')))) AS partyAddress,
		t3.gstin partyGST,
		 t2.state_name partyState,
		 t2.state_code partyStateCode,t1.city,t1.pincode, if(t3.mobile='' or t3.mobile is null,t1.mobile,t3.mobile) mobile,t1.pan_no,t1.cin_no,t3.gstin,if(t3.contact_person='' or t3.contact_person is null,t1.contact_person,t3.contact_person) contact_person,t1.tel_no,t1.email,t1.not_allow_invoice_after_limit,t1.credit_limit,t1.due_days");
						$this->db->join("$this->compDb.comp_ledger_address as t3", 't3.ledger_master_id=t1.ledger_id');
						$this->db->join("acc_state_master as t2", "t2.state_code=t3.state", "left");
						$this->db->where(array('t3.id' => $address_id));
					} else {
						$this->db->select("t1.acc_head partyName,
		CONCAT(IF(t1.add1='', '', CONCAT(t1.add1, ', ')), IF(t1.add2='', '', CONCAT(t1.add2, ', ')), IF((t1.city='' or t1.city is null), '', CONCAT(t1.city, ', ')), t2.state_name, ', ', t1.country, IF(t1.pincode='' OR t1.pincode is null, '',concat('-',if(t1.pincode!='',t1.pincode,'')))) AS partyAddress,
		t1.gstin partyGST,
		 t2.state_name partyState,
		 t2.state_code partyStateCode,t1.city,t1.pincode,t1.mobile,t1.pan_no,t1.cin_no,t1.gstin,t1.contact_person,t1.tel_no,contact_person,mobile,t1.email,t1.not_allow_invoice_after_limit,t1.credit_limit,t1.due_days");
				$this->db->join("acc_state_master as t2", "t1.state=t2.state_code", "left");
			}

			$this->db->where("ledger_id", $partyId);
			return $this->db->get()->row();
		}

		function getLedgerDetailWithAgent($queryArray = array())
		{
			$this->setTables();
			$this->db->select("l.acc_head as party_name,l.mobile as party_mobile,l.email as party_email,a.agent_name,a.agent_email");
			$this->db->where($queryArray);
			$this->db->from($this->compDb . ".comp_ledger_master as l");
			$this->db->join($this->compDb . ".comp_agent_master as a", 'a.agent_id=l.agent_id', 'left');
			return $this->db->get()->row_array();
		}

		/*For PDF End*/

		public function updateTransaction($txnData, $txnList)
		{
			$this->setTables();
			foreach ($txnList as $txn) {
				$txn['finyr_id'] = $this->finyrId;
				$data[] = array_merge($txnData, $txn);
			}
			return $this->db->insert_batch($this->transactionTable, $data);
		}

		public function validateLastTransactionChange($where, $bookType, $refNo)
		{
			// And Delete payment adjustemnt if there is a change in anything
			$this->setTables();
			if ($this->db->select("count(1) as count")->where($where, null, FALSE)->get("$this->transactionTable t1")->row()->count < 1) {
				return $this->deletePayAdjByTrx($bookType, $refNo);
			}
			return TRUE;
		}

		public function deleteTransaction($bookType, $refNo)
		{
			// $this -> deletePayAdjByTrx($bookType, $refNo);
			$this->setTables();
			$this->db->where('book_type', $bookType);
			$this->db->where('ref_no', $refNo);
			$this->db->where('finyr_id', $this->finyrId);
			return $this->db->delete($this->transactionTable);
		}

		function updateTransactionAdjustmentAmount($ref_no, $amount)
		{
			$this->db->set("adjusted_amount", "adjusted_amount-$amount", false);
			$this->db->where("CONCAT(book_type,'-',ref_no)='$ref_no' and finyr_id='$this->finyrId'");
			return $this->db->update($this->transactionTable);
		}

		function deletePayAdjByTrx($bookType, $refNo)
		{
			$this->setTables();
			$a = $this->billAdjTable;
			$b = $this->transactionTable;
			$finyr_id = $this->finyrId;
			$qry = $this->db->query("select (a.id) as id,a.amount,a.ref_no1,a.ref_no2,a.finyr_id,a.txn_finyr_id from $a a
			INNER JOIN $b b on (CONCAT(b.book_type,'-',b.ref_no)=a.ref_no1 and a.ledger_id=b.ledger_id)
			where ((a.ref_no1='$bookType-$refNo' and a.finyr_id='$finyr_id') OR (a.ref_no2='$bookType-$refNo' and txn_finyr_id = '$finyr_id'))");
			if ($qry->num_rows() > 0) {
				$adj_id = array();
				foreach ($qry->result_array() as $key => $value) {
					$adj_id[] = $value['id'];
					// if ($value['ref_no1'] == "$bookType-$refNo") {
					$this->updateTransactionAdjustmentAmount($value['ref_no1'], $value['amount'], $value['finyr_id']);
					// }
				}
				return $this->db->where_in('id', $adj_id)->delete($a);
			}
			return;
		}

		public function updateTransactionAdjustedAmount($book_type, $ref_no, $ledger_id, $finyr_id = '')
		{
			$finyr_id = !empty($finyr_id) ? $finyr_id : $this->finyrId;
			return $this->db->query("update $this->transactionTable set adjusted_amount=(select ifnull(sum(amount),0) as amount from $this->billAdjTable where finyr_id='$finyr_id' and ref_no1='$book_type-$ref_no' and ledger_id='$ledger_id') where finyr_id='$finyr_id' and ((book_type='$book_type' and ref_no='$ref_no') or (book_type='IP' and ref_no='$ref_no'))");
			// echo $this -> db -> last_query();die;
		}

		public function getOpeningBal($partyId, $queryArray = array())
		{
			$this->setTables();
			$this->db->select("IFNULL(opening_balance,0) opBal,IFNULL(balance_type,'Cr') balType");
			$this->db->where('ledger_id', $partyId);
			$this->db->where('finyr_id', $this->finyrId);
			$data = $this->db->get("$this->compDb.comp_party_opening_balance")->row();

			if (count($queryArray) < 1) {
				return $data;
			}
			if ($data == '') {
				$data['balType'] = 'Dr';
				$data['opBal'] = 0;
				$data = (object)$data;
			}
			$this->db->reset_query();
			$queryArray = array_merge($queryArray, array('finyr_id' => $this->finyrId));
			$transAmt = $this->db->select("ifnull(sum(credit)-sum(debit),0) as bal_amt")->get_where($this->transactionTable, $queryArray)->row()->bal_amt;
			//echo $this->db->last_query();die;
			if ($transAmt >= 0) { // Credit Amount
				$dataOpening['opBal'] = ($data->balType == 'Cr') ? $transAmt + $data->opBal : $transAmt - $data->opBal;
				if ($dataOpening['opBal'] >= 0) {
					$dataOpening['balType'] = 'Cr';
				} else {
					$dataOpening['opBal'] = $dataOpening['opBal'] * -1;
					$dataOpening['balType'] = 'Dr';
				}
			} else { //debit Amount
				if ($data->balType == 'Dr') {
					$transAmt = (-1) * $transAmt;
				}
				$dataOpening['opBal'] = ($data->balType == 'Dr') ? $transAmt + $data->opBal : $transAmt - $data->opBal;
				$dataOpening['balType'] = ($dataOpening['opBal'] >= 0) ? 'Dr' : 'Cr';
				if ($dataOpening['opBal'] < 0)
					$dataOpening['opBal'] = -1 * $dataOpening['opBal'];
			}
			return (object)$dataOpening;
		}

		public function compUpdSt()
		{
			$this->db->where("comp_id", $this->fx->clientCompId);
			return $this->db->select("update_available,case when update_available='1' then 'Software Update Available' else 'No Action Required.' end updateSt,last_updated")->get("acc_client_company")->row();
		}

		public function getOpBalSt()
		{
			$finYrId = $this->fx->clientFinYr;
			$compDb = $this->fx->clientCompDb;
			$this->db->select("finyr_id,opening_balance_st,(select finyr_id from $compDb.comp_financial_year where finyr_id<$finYrId order by finyr_id desc limit 1) lastFinyr");
			$this->db->where("finyr_id", $finYrId);
			return $this->db->get("$compDb.comp_financial_year")->row();
		}

		public function validateSrnoData($data, $chkType, $finyr_id = '')
		{
			$compDb = $this->fx->clientCompDb;
			$msg = array();
			$srno = (count($data->srno) == 0) ? "''" : $data->srno;
			$oldSrno = (count($data->oldSrno) == 0) ? "''" : $data->oldSrno;

			$finyr_id = ($finyr_id != '') ? $finyr_id : $this->fx->clientFinYr;

			if (in_array('SRNO_INLIST', $chkType)) {
				$this->db->select("count(srno_stck_id) cnt");
				$this->db->from("$compDb.comp_item_srno");
				$this->db->where("branch_id", $data->brId);
				$this->db->where("item_id", $data->itmId);
				$this->db->where("finyr_id", $finyr_id);
				$this->db->where_in("item_srno", $srno);
				if (isset($data->refno) && $data->refno != NULL)
					$this->db->where("refno !=", $data->refno);
				$result = $this->db->get();
				$msg['SRNO_INLIST'] = $result->row()->cnt;
				return $msg;
			}

			if (in_array('SRNO_INUSE', $chkType)) {
				$this->db->select("count(srno_stck_id) cnt");
				$this->db->from("$compDb.comp_item_srno");
				$this->db->where("branch_id=$data->brId and item_id=$data->itmId and (issued_to != NULL or issued_to != '')", NULL, false);
				$this->db->where_in("item_srno", $oldSrno);
				$this->db->where("finyr_id", $finyr_id);
				if (isset($data->refno) && $data->refno != NULL)
					$this->db->where("refno !=", $data->refno);

				$result = $this->db->get();
				$msg['SRNO_INUSE'] = $result->row()->cnt;
				return $msg;
			}

			if (in_array('SRNO_INSTK', $chkType)) {
				$this->db->select("count(srno_stck_id) cnt");
				$this->db->from("$compDb.comp_item_srno");
				$this->db->where("branch_id=$data->brId and item_id=$data->itmId and stock_status=1", NULL, false);
				$this->db->where_in("item_srno", $srno);
				$this->db->where("finyr_id", $finyr_id);
				$result = $this->db->get();
				$msg['SRNO_INSTK'] = $result->row()->cnt;
				return $msg;
			}

			if (in_array('SRNO_ONRENT', $chkType)) {
				$this->db->select("count(srno_stck_id) cnt");
				$this->db->from("$compDb.comp_item_srno");
				$this->db->where("branch_id=$data->brId and item_id=$data->itmId and stock_status=0 and issued_type='On Rent'", NULL, false);
				$this->db->where("finyr_id", $finyr_id);
				$this->db->where_in("item_srno", $srno);
				$result = $this->db->get();
				$msg['SRNO_ONRENT'] = $result->row()->cnt;
				return $msg;
			}

			if (in_array('SRNO_RENTRTN', $chkType)) {
				$this->db->select("count(srno_stck_id) cnt");
				$this->db->from("$compDb.comp_item_srno");
				$this->db->where("branch_id=$data->brId and item_id=$data->itmId and stock_status=1 and issued_type='Rent Return'", NULL, false);
				$this->db->where_in("item_srno", $oldSrno);
				$this->db->where("finyr_id", $finyr_id);
				$result = $this->db->get();
				$msg['SRNO_RENTRTN'] = $result->row()->cnt;
				return $msg;
			}

			if (in_array('SRNO_SOLD', $chkType)) {
				$this->db->select("count(srno_stck_id) cnt");
				$this->db->from("$compDb.comp_item_srno");
				$this->db->where("branch_id=$data->brId and item_id=$data->itmId and stock_status=0 and issued_type='Sold'", NULL, false);
				$this->db->where("finyr_id", $finyr_id);
				$this->db->where_in("item_srno", $srno);
				$result = $this->db->get();
				$msg['SRNO_SOLD'] = $result->row()->cnt;
				return $msg;
			}

			if (in_array('SRNO_STK_ISS', $chkType)) {

				$this->db->select("count(srno_stck_id) cnt");
				$this->db->from("$compDb.comp_item_srno");
				$this->db->where("branch_id=$data->brId and item_id=$data->itmId and assign_id is not null and stock_status=1", NULL, false);
				$this->db->where_in("item_srno", $srno);
				$this->db->where("finyr_id", $finyr_id);
				$result = $this->db->get();
				$msg['SRNO_STK_ISS'] = $result->row()->cnt;
				return $msg;
			}
			if (in_array('SRNO_STK_ISS_RTN', $chkType)) {
				$this->db->select("count(srno_stck_id) cnt");
				$this->db->from("$compDb.comp_item_srno");
				$this->db->where("branch_id=$data->brId and item_id=$data->itmId and (assign_id!='$data->refno' or assign_id is null) and stock_status=1", NULL, false);
				$this->db->where_in("item_srno", $srno);
				$this->db->where("finyr_id", $finyr_id);
				$result = $this->db->get();
				$msg['SRNO_STK_ISS_RTN'] = $result->row()->cnt;
				return $msg;
			}

			if (in_array('SRNO_INLIST_IN_USE', $chkType)) {
				$this->db->select("count(srno_stck_id) cnt,ifnull(sum(stock_status),0) stock_count");
				$this->db->from("$compDb.comp_item_srno");
				$this->db->where("branch_id", $data->brId);
				$this->db->where("finyr_id", $finyr_id);
				$this->db->where("item_id", $data->itmId);
				$this->db->where_in("item_srno", $srno);
				if (isset($data->refno) && $data->refno != NULL)
					$this->db->where("refno !=", $data->refno);
				$result = $this->db->get()->row();
				$msg['SRNO_INLIST_IN_USE'] = 0;
				if (is_array($srno) && $result->cnt != count($srno))
					$msg['SRNO_INLIST_IN_USE'] = 1;

				if (is_array($srno) && $result->stock_count != count($srno) && $msg['SRNO_INLIST_IN_USE'] == 0)
					$msg['SRNO_INLIST_IN_USE'] = 1;
				return $msg;
			}

			if (count($srno) == $data->qty) {
				$msg['SRNO_QTY'] = 1;
				return $msg;
			} else {
				$msg['SRNO_QTY'] = 0;
				return $msg;
			}
		}

		public function updateSrNo($brId, $data, $finyr_id = '')
		{
			$compDb = $this->fx->clientCompDb;
			foreach ($data as $key => $itm) {
				$itemSrNo = array();
				foreach ($itm['srno'] as $iSrNo) {
					$itemSrNo[] = trim($iSrNo);
				}
				$this->db->where('branch_id', $brId);
				$this->db->where('item_id', $key);
				$finyr_id = ($finyr_id != '') ? $finyr_id : $this->fx->clientFinYr;
				$this->db->where("finyr_id", $finyr_id);
				$this->db->where_in('item_srno', $itemSrNo);
				$this->db->update("$compDb.comp_item_srno", $itm['data']);
			}
			return;
		}

		public function getPurchaseReturnItemWithSrNo($id)
		{
			$compDb = $this->fx->clientCompDb;
			$finyr_id = $this->fx->clientFinYr;
			$this->db->select("prtn_id as id,item_id,sr_no_required,sr_no");
			$this->db->from("$compDb.comp_prtn_itemlist_$finyr_id t1");
			$this->db->where("t1.prtn_id", "$id");
			$this->db->where("t1.sr_no_required", "1");
			return $this->db->get()->result_array();
		}
		
		public function getInvoiceItemWithSrNo($id)
		{
			$compDb = $this->fx->clientCompDb;
			$finyr_id = $this->fx->clientFinYr;
			$this->db->select("t1.sale_id as id,item_id,sr_no_required,sr_no,t2.challan_id,t2.ledger_id,t2.acc_head,t2.invoice_date");
			$this->db->from("$compDb.comp_sale_itemlist_$finyr_id t1");
			$this->db->from("$compDb.comp_sale_master_$finyr_id t2", "t1.sale_id=t2.sale_id");
			$this->db->where("t1.sale_id", "$id");
			$this->db->where("t1.sr_no_required", "1");
			return $this->db->get()->result_array();
		}

		public function getChallanItemWithSrNo($id)
		{
			$compDb = $this->fx->clientCompDb;
			$finyr_id = $this->fx->clientFinYr;
			$this->db->select("t1.challan_id as id,item_id,sr_no_required,sr_no");
			$this->db->from("$compDb.comp_challan_itemlist t1");
			$this->db->where("t1.challan_id", "$id");
			$this->db->where("t1.sr_no_required", "1");
			return $this->db->get()->result_array();
		}
		public function getChallanReturnItemWithSrNo($id)
		{
			$compDb = $this->fx->clientCompDb;
			$finyr_id = $this->fx->clientFinYr;
			$this->db->select("t1.challan_rtn_id as id,item_id,sr_no_required,sr_no,t2.ledger_id,t2.acc_head,t2.challan_rtn_date invoice_date");
			$this->db->from("$compDb.comp_challanrtn_itemlist t1");
			$this->db->from("$compDb.comp_challanrtn_master t2", "t1.challan_rtn_id=t2.challan_rtn_id");
			$this->db->where("t1.challan_rtn_id", "$id");
			$this->db->where("t1.sr_no_required", "1");
			return $this->db->get()->result_array();
		}

		public function getInvoiceReturnItemWithSrNo($id)
		{
			$compDb = $this->fx->clientCompDb;
			$finyr_id = $this->fx->clientFinYr;
			$this->db->select("t1.invrtn_id as id,item_id,sr_no_required,sr_no,t2.ledger_id,t2.acc_head,t2.invoice_date");
			$this->db->from("$compDb.comp_invrtn_itemlist_$finyr_id t1");
			$this->db->join("$compDb.comp_invrtn_master_$finyr_id t2", "t2.invrtn_id=t1.invrtn_id");
			$this->db->where("t1.invrtn_id", "$id");
			$this->db->where("t1.sr_no_required", "1");
			return $this->db->get()->result_array();
		}

		public function getPurchaseItemWithSrNoForRollBack($id)
		{
			$compDb = $this->fx->clientCompDb;
			$finyr_id = $this->fx->clientFinYr;
			$this->db->select("t1.purchase_id as id,item_id,sr_no_required,sr_no,t3.ledger_id,t3.acc_head,t3.challanin_date invoice_date,t3.challanin_id");
			$this->db->from("$compDb.comp_purchase_itemlist_$finyr_id t1");
			$this->db->join("$compDb.comp_purchase_master_$finyr_id t2", "t2.purchase_id=t1.purchase_id");
			$this->db->join("$compDb.comp_challanin_master t3", "t3.challanin_id=t2.challanin_no", 'left');
			$this->db->where("t1.purchase_id", "$id");
			$this->db->where("t1.sr_no_required", "1");
			return $this->db->get()->result_array();
		}

		public function addItemSrNoTxn($txnData)
		{
			// SI for Stock Assign or issue For User
			// SR for Stock Assign or issue Return For User
			$compDb = $this->fx->clientCompDb;
			$txnDataNew = array();
			foreach ($txnData as $key => $row) {
				if (!in_array('finyr_id', array_keys($row))) {
					$row['finyr_id'] = $this->fx->clientFinYr;
				}
				foreach ($row as $col => $val) {
					$txnDataNew[$key][$col] = (trim($val) != '') ? trim($val) : null;
				}
			}
			return $this->db->insert_batch("$compDb.comp_item_srno_txn", $txnDataNew);
		}

		public function deleteSrNoTxn($refId, $refType, $finyr_id = '')
		{
			$compDb = $this->fx->clientCompDb;
			$this->db->where("ref_id", $refId);
			$this->db->where("ref_type", $refType);
			$finyr_id = ($finyr_id != '') ? $finyr_id : $this->fx->clientFinYr;
			$this->db->where("finyr_id", $finyr_id);
			return $this->db->delete("$compDb.comp_item_srno_txn");
		}

		public function getSrnoRBData($brId, $itmId, $srno, $refType, $finyr_id = '')
		{
			$compDb = $this->fx->clientCompDb;
			$this->db->from("$compDb.comp_item_srno_txn");
			$this->db->where("branch_id", $brId);
			$this->db->where("item_id", $itmId);
			$this->db->where_in("item_srno", $srno);
			$this->db->where("ref_type", $refType);
			$finyr_id = ($finyr_id != '') ? $finyr_id : $this->fx->clientFinYr;
			$this->db->where("finyr_id", $finyr_id);

			$this->db->order_by("date desc,txn_id desc");

			$this->db->limit(1);
			$result = $this->db->get()->row();
			return $result;
		}

		public function getLeadSourceList($queryArray = array(), $field = false)
		{
			$this->setTables();
			if ($field == false) {
				$this->db->select("id,lead_source_name");
			}
			return $this->db->order_by('lead_source_name', 'asc')->get_where($this->lead_souce_table, $queryArray)->result_array();
		}

		public function getUniqueLeadOutsourceTypeList($queryArray = array(), $field = false)
		{
			$this->setTables();
			if ($field == false) {
				$this->db->select("DISTINCT (leadSource) as name");
			}
			$this->db->where("leadSource is not null and leadSource!=''", NULL, FALSE);
			$this->db->where($queryArray);
			return $this->db->order_by('leadSource', 'asc')->get("$this->compDb.comp_lead_outsource")->result_array();
		}

		public function getLeadStatusList($queryArray = array(), $field = false)
		{
			$this->setTables();
			if ($field == false) {
				$this->db->select("id,lead_status_name,color_code");
			}
			return $this->db->order_by('lead_status_name', 'asc')->get_where($this->lead_status_table, $queryArray)->result_array();
		}

		public function getLeadTypeList($queryArray = array(), $field = false)
		{
			$this->setTables();
			if ($field == false) {
				$this->db->select("id,lead_type_name");
			}
			return $this->db->order_by('lead_type_name', 'asc')->get_where($this->lead_type_table, $queryArray)->result_array();
		}

		public function createMultiEmailSmsLogs($queryArray = array(), $data)
		{

			$this->setTables();
			if (count($queryArray) > 0) {
				if (count($queryArray) > 0)
					$this->db->where($queryArray);
				return $this->db->update($this->notification_log, $data);
			}
			return $this->db->insert_batch($this->notification_log, $data);
		}

		function getVoucherPaymentDetails($queryArray, $tableName)
		{
			return $this->db->select('id,paid_payment,invoice_id')->get_where($tableName, $queryArray)->result_array();
		}

		function updateVoucherBillPayment($invoice_id, $amount, $rollback = 0, $type = 'SL')
		{
			$this->setTables();
			if ($rollback == 0) {
				$this->db->set("paid_payment", "paid_payment+$amount", false);
			} else {
				$this->db->set("paid_payment", "paid_payment-$amount", false);
			}
			if ($type == 'SL') {
				$this->db->where("sale_id", $invoice_id, FALSE);
				$table = $this->sale_master;
			} else if ($type == 'PU') {
				$this->db->where("purchase_id", $invoice_id);
				$table = $this->purchase_master;
			}
			return $this->db->update($table);
		}

		public function getSubGroupListWithDetail($where = array(), $behaviourOr = array(), $db)
		{
			$this->db->select("sub_group_id,sub_group_name,accept_address,behaviour");
			$this->db->from("$db.comp_sub_group as s");
			$this->db->join("$db.comp_group as g", "g.group_id=s.group_id", 'left');
			if (count($where) > 0)
				$this->db->where($where);
			if (count($behaviourOr) > 0) {
				$this->db->group_start();
				foreach ($behaviourOr as $key => $value) {
					$this->db->or_where(array('behaviour' => $value));
				}
				$this->db->group_end();
			}
			$this->db->order_by("sub_group_name", 'asc');
			return $this->db->get()->result();
		}

		public function deletePaymentAdjustment($where = null)
		{
			$this->setTables();
			$this->db->where($where, NULL, false);
			return $this->db->delete($this->billAdjTable);
		}

		public function getBillAdjustmentTransactions($ref_no1, $date1)
		{
			$this->setTables();
			$data = $this->db->select("ref_no1 as ref_no,amount as adj_amount,date1 as date")->where("(ref_no2 = '$ref_no1' and date2='$date1')", NULL, false)->get($this->billAdjTable)->result_array();

			$this->db->reset_query();

			$data[] = $this->db->select("ref_no1 ref_no,sum(amount) as adj_amount,date1 as date")->where("(ref_no1 = '$ref_no1' and date1 = '$date1')", NULL, false)->group_by('ref_no1,date1')->get($this->billAdjTable)->row_array();
			return $data;
		}

		function updateBillAdjustmentTransaction($ref_no, $amount, $rollback = 0, $type = 'BA', $date = '')
		{
			$this->setTables();
			if ($rollback == 0) {
				$this->db->set("adjusted_amount", "adjusted_amount+$amount", false);
			} else {
				$this->db->set("adjusted_amount", "adjusted_amount-$amount", false);
			}
			if ($type == 'BA') {
				$this->db->where("concat(book_type,'-',ref_no)='$ref_no' and date='$date'", null, FALSE);
				$table = $this->transactionTable;
			}
			return $this->db->update($table);
		}

		public function getCompList($where = "", $type = 0)
		{
			$this->db->select("*");
			$this->db->from("acc_client_company t1");
			if ($type == 0)
				$this->db->join("acc_client_master t2", "t1.client_id=t2.client_id", "left");
			if ($where != '')
				$this->db->where($where, NULL, FALSE);
			$data = $this->db->get()->result();
			return $data;
		}

		public function compMaxFinyr($compDb)
		{
			return $this->db->select("max(finyr_id) fnYr")->get("$compDb.comp_financial_year")->row()->fnYr;
		}

		public function addFavouriteMenu($queryArray = array(), $data)
		{
			$this->setTables();
			$this->db->where($queryArray)->delete($this->favMenuTable);
			$this->db->reset_query();
			if (count($data) > 0)
				return $this->db->insert_batch($this->favMenuTable, $data);
			return 0;
		}

		public function getFavMenuList($queryArray = array(), $detail = false)
		{
			$this->setTables();
			$this->db->from($this->favMenuTable . ' as f');

			if (count($queryArray) > 0)
				$this->db->where($queryArray);

			if ($detail == TRUE) {
			}
			$this->db->order_by('f.sequence', 'asc');
			return $this->db->get()->result_array();
		}

		public function getFavMenuID()
		{
			$this->setTables();
			return $this->db->select('menu_id')->order_by('sequence', 'asc')->get_where($this->favMenuTable, array('user_id' => $this->fx->clientId))->result_array();
		}

		// public function getLedgerOpeningBalance($ledger_id, $type = 'Cr', $queryArray = array()) {
		// $this -> setTables();
		// if ($type == 'Cr') {
		// $this -> db -> select("IFNULL(sum(credit)-sum(debit),0) + IFNULL((SELECT if(balance_type='Cr',opening_balance,-opening_balance) as opening_bal FROM " . $this -> compDb . ".comp_party_opening_balance where ledger_id=$ledger_id and finyr_id= '" . $this -> finyrId . "'),0) as opening_balance");
		// } else if ($type == 'Dr') {
		// $this -> db -> select("IFNULL(sum(debit)-sum(credit),0) + IFNULL((SELECT if(balance_type='Dr',opening_balance,-opening_balance) as opening_bal FROM " . $this -> compDb . ".comp_party_opening_balance where ledger_id=$ledger_id and finyr_id='" . $this -> finyrId . "'),0) as opening_balance");
		// }
		// return $this -> db -> get_where($this -> transactionTable, $queryArray) -> row();
		// }

		public function getLedgerOpeningBalance($ledger_id, $type = 'Cr', $queryArray = array())
		{
			$this->setTables();

			$this->db->select("IFNULL(sum(debit)-sum(credit),0) + IFNULL((SELECT if(balance_type='Dr',opening_balance,-opening_balance) as opening_bal FROM " . $this->compDb . ".comp_party_opening_balance where ledger_id=$ledger_id and finyr_id='" . $this->finyrId . "'),0) as opening_balance");

			return $this->db->get_where($this->transactionTable, $queryArray)->row();
		}

		public function updateContractDetail($queryArray, $data)
		{
			$this->setTables();
			return $this->db->where($queryArray)->update($this->cntrctMaster, $data);
		}

		public function getSaleCategory($queryArray)
		{
			$this->setTables();
			if (count($queryArray) > 0)
				$this->db->where($queryArray);
			return $this->db->select("id,sale_category_title,margin")->get($this->compDb . '.comp_sale_category')->result_array();
		}

		public function uploadGlobalDocuments($table_name, $data)
		{
			$this->setTables();
			return $this->db->insert_batch($this->compDb . "." . $table_name, $data);
		}

		public function getUploadedDocument($table_name, $where)
		{
			$this->setTables();
			$this->db->where($where, null, FALSE);
			return $this->db->select("count(id) as count")->get($this->compDb . "." . $table_name)->row()->count;
		}

		public function getUploadedDocumentList($table_name, $queryArray, $file_path = '')
		{
			$this->setTables();
			$this->db->select("id,ref_id,document_name");
			return 	$this->db->get_where($this->compDb . "." . $table_name, $queryArray)->result_array();
		}

		public function getUploadedDocumentListDocumentMaster($table_name, $queryArray, $file_path = '')
		{
			$this->setTables();
			$this->db->select("t1.id,t1.ref_id,t1.document_name,t1.doc_type_id,t2.doc_type_name");
			$this->db->from($this->compDb . "." . $table_name . " t1");
			$this->db->join("$this->compDb.comp_documents_type t2", "t1.doc_type_id=t2.id", 'left');
			$this->db->where($queryArray);
			return 	$this->db->get()->result_array();
		}

		public function deleteGbobalUplodedDoc($table_name, $queryArray)
		{
			$this->setTables();
			if (!in_array($table_name, array('comp_contract_document', 'comp_lead_document', 'comp_ledger_document'))) {
				$queryArray['finyr_id'] = $this->fx->clientFinYr;
			}
			return $this->db->delete($this->compDb . "." . $table_name, $queryArray);
		}

		private function checkTableExist($db_name, $table_name)
		{
			$qry = $this->db->query("SELECT table_name FROM information_schema.tables  where table_schema='$db_name';");
			$status = false;
			foreach ($qry->result_array() as $key => $value) {
				if ($value['table_name'] == $table_name) {
					$status = true;
				}
			}
			return $status;
		}

		public function getPaginationRecords($queryArray = array())
		{
			$this->setTables();
			if ($this->checkTableExist($this->compDb, 'comp_pagination_master') == false)
				return array();

			return $this->db->select('record_val,record_title,is_default')->order_by('record_val asc')->get_where($this->compDb . ".comp_pagination_master", $queryArray)->result_array();
		}

		public function getUserdropdownList($queryArray = array(), $where = '')
		{
			if (count($queryArray) > 0)
				$this->db->where($queryArray);

			if ($where != '')
				$this->db->where($where, NULL, FALSE);

			$this->db->select("client_id,concat(client_firstname,' ',client_lastname) clientName,status");
			$this->db->from("acc_client_master");
			$this->db->order_by("clientName Asc");
			return $this->db->get()->result();
		}

		public function getClientDetailForEmail($client_ids, $where)
		{
			$this->db->where($where, null, FALSE);
			$this->db->where_in('client_id', $client_ids);
			$this->db->select("client_id,concat(client_firstname,' ',client_lastname) clientName,client_email");
			$this->db->from("acc_client_master");
			return $this->db->get()->result_array();
		}

		public function getSubGroupCashBankList()
		{
			$this->db->where('bank_acc_check', 1);
			$this->db->select("t2.sub_group_id,t2.sub_group_name");
			$this->db->from("$this->compDb.comp_ledger_master t1");
			$this->db->join("$this->compDb.comp_sub_group t2", "t1.acc_sub_group = t2.sub_group_id", "left");
			return  $this->db->get()->result();
		}

		public function updateItemSrNo($finYearData)
		{
			// $this->db->trans_begin();
			// fx::pr($finyrData,1);
			$compDb = $this->fx->clientCompDb;
			$postSrno = array();
			$whereItem = '';

			if (!empty($_POST['item_id'])) {
				$whereItem = "t2.item_id='$_POST[item_id]'";
			}

			$msgString = '';
			foreach ($finYearData as $finyrKey => $finData) {
				$qry = array();
				$finYear = $finData['finyr_id'];
				$startDate = $finData['finyr_st_date'];
				$endDate = $finData['finyr_end_date'];

				// To calculate Prvios entry for serial number which are not in stock ***************** START
				$previousYear = !empty($finYearData[$finyrKey - 1]) ? $finYearData[$finyrKey - 1]['finyr_id'] : '';

				if (isset($_POST['type']) && $_POST['type'] == 2) {

					$this->db->where("finyr_st_date < '$startDate'", null, false);
					$this->db->order_by("finyr_st_date desc");
					$prevFinData = $this->db->limit(1)->get("$compDb.comp_financial_year t1")->row();
					if (isset($prevFinData->finyr_id)) {
						$previousYear = $prevFinData->finyr_id;
					}
				}

				$prevFinYrInstData = array();
				if ($previousYear != '') {
					$this->db->select("*");
					$this->db->from("$compDb.comp_item_srno");
					$this->db->where("finyr_id='$previousYear' and stock_status=0", null, false);
					if (!empty($_POST['item_id'])) {
						$this->db->where(str_replace("t2.", '', $whereItem), null, false);
					}
					$lastFinYrData = $this->db->get()->result_array();
					foreach ($lastFinYrData as $lastFinyrSrno) {
						unset($lastFinyrSrno['srno_stck_id']);
						$lastFinyrSrno['finyr_id'] = $finYear;
						$prevFinYrInstData[] = $lastFinyrSrno;
					}
				}
				// To calculate Prvios entry for serial number which are not in stock ***************** END

				/******************DELETE SR NO DATA *****************STARTS **************** */
				// DELETE SR NO. for this financial year

				if (!empty($_POST['item_id']))
					$this->db->where("item_id='$_POST[item_id]'", null, false);

				$this->db->where("finyr_id='$finYear'", null, false);
				$this->db->delete("$compDb.comp_item_srno");

				$this->db->reset_query();
				// DELETE SR NO. TRANSACTIONS for this financial year
				//	$this->db->where("item_id='$_POST[item_id]'", null, false);

				if (!empty($_POST['item_id']))
					$this->db->where("item_id='$_POST[item_id]'", null, false);

				$this->db->where("finyr_id='$finYear'", null, false);
				$this->db->delete("$compDb.comp_item_srno_txn");

				/******************DELETE SR NO DATA *****************ENDS **************** */

				/************** Opening Stock ----Stock In ******************/
				$this->db->select("'OP#$finYear' ref_no,t1.branch_id,t2.item_id,t2.item_name,t2.item_desc,sr_no,'' as purchased_frm,'Opening' as purchase_head,date('$startDate') as purchase_date,'' purchase_cost_center,1 stock_status,'In Stock' issued_type,'' issued_to,'' issued_head,'' issued_date,t2.md_usr md_usr,t2.md_date as md_date,t2.cr_usr cr_usr,t2.cr_date,'' as ref_id,'OP' ref_type,'' as date,'' as ledger_id,'' as remarks,$finYear fin_yr")->from("$compDb.comp_item_stock_master as t1")->join("$compDb.comp_item_master as t2", "t1.item_id=t2.item_id", 'left')->where(array('t2.item_sr_no' => 1, 'finyr_id' => $finYear, 'sr_no != ' => ''));

				if ($whereItem != '')
					$this->db->where($whereItem, null, false);

				$this->db->group_by('branch_id,item_id,sr_no,t2.item_name,t2.item_desc,t1.item_id,t2.md_usr,t2.md_date,t2.cr_usr,t2.cr_date,fin_yr');
				$qry[] = $this->db->get_compiled_select();


				/************** Challan ----Stock OUT ******************/
				$this->db->select("CONCAT('CH#',t1.challan_id) ref_no,t1.branch_id,t2.item_id,t2.item_name,t2.item_description,sr_no,'' purchased_frm,'' as purchase_head,'' as purchase_date,'' purchase_cost_center,0 stock_status,'On Rent' issued_type,t1.ledger_id issued_to,t1.acc_head issued_head,t1.challan_date issued_date,t1.md_usr md_usr,t1.md_date as md_date,t1.cr_usr cr_usr,t1.cr_date,t1.challan_id as ref_id,'CH' ref_type,challan_date as date,ledger_id as ledger_id,t2.item_description as remarks,$finYear fin_yr")->from("$compDb.comp_challan_master as t1")->join("$compDb.comp_challan_itemlist as t2", "t1.challan_id=t2.challan_id", 'left')->where(array('sr_no_required' => 1));
				$this->db->where("date(challan_date) >= '$startDate' and date(challan_date) <= '$endDate'", null, false);
				if ($whereItem != '')
					$this->db->where($whereItem, null, false);

				$this->db->group_by('branch_id,item_id,sr_no,t1.challan_id,t2.item_name,t2.item_description,t1.acc_head,t1.challan_date,t1.md_usr,t1.md_date,t1.cr_usr,t1.cr_date,ledger_id,fin_yr');

				$qry[] = $this->db->get_compiled_select();

				/************** On going Inventory ----Stock OUT ******************/
				$this->db->select("CONCAT('OI#',t1.inv_id) ref_no,t1.branch_id,t2.item_id,t2.item_name,t2.item_description,sr_no,'' purchased_frm,'' as purchase_head,'' as purchase_date,'' purchase_cost_center,0 stock_status,'On Rent' issued_type,t1.ledger_id issued_to,t1.acc_head issued_head,t1.handover_date issued_date,t1.md_usr md_usr,t1.md_date as md_date,t1.cr_usr cr_usr,t1.cr_date,t1.inv_id as ref_id,'OI' ref_type,handover_date as date,ledger_id as ledger_id,t2.item_description as remarks,$finYear fin_yr")->from("$compDb.comp_ongoing_inv_master as t1")->join("$compDb.comp_ongoing_inv_itemlist as t2", "t1.inv_id=t2.inv_id", 'left')->where(array('sr_no_required' => 1));

				$this->db->where("date(delivery_date) >= '$startDate' and date(delivery_date) <= '$endDate'", null, false);

				if ($whereItem != '')
					$this->db->where($whereItem, null, false);

				$this->db->group_by('branch_id,item_id,sr_no,t1.inv_id,t2.item_name,t2.item_description,t1.acc_head,t1.handover_date,t1.md_usr,t1.md_date,t1.cr_usr,t1.cr_date,ledger_id,fin_yr');
				$qry[] = $this->db->get_compiled_select();

				/************** Challan Return ----Stock In ******************/
				$this->db->select("CONCAT('CR#',t1.challan_rtn_id) ref_no,t1.branch_id,t2.item_id,t2.item_name,t2.item_description,sr_no,'' purchased_frm,'' as purchase_head,'' as purchase_date,'' purchase_cost_center,1 stock_status,'Rent Return' issued_type,t1.ledger_id issued_to,t1.acc_head issued_head,t1.challan_rtn_date issued_date,t1.md_usr md_usr,t1.md_date as md_date,t1.cr_usr cr_usr,t1.cr_date,t1.challan_rtn_id as ref_id,'CR' ref_type,challan_rtn_date as date,ledger_id as ledger_id,t2.item_description as remarks,$finYear fin_yr")->from("$compDb.comp_challanrtn_master as t1")->join("$compDb.comp_challanrtn_itemlist as t2", "t1.challan_rtn_id=t2.challan_rtn_id", 'left')->where(array('sr_no_required' => 1));

				$this->db->where("date(challan_rtn_date) >= '$startDate' and date(challan_rtn_date) <= '$endDate'", null, false);

				if ($whereItem != '')
					$this->db->where($whereItem, null, false);

				$this->db->group_by('branch_id,item_id,sr_no,t1.challan_rtn_id,t2.item_name,t2.item_description,t1.acc_head,t1.challan_rtn_date,t1.md_usr,t1.md_date,t1.cr_usr,t1.cr_date,ledger_id,fin_yr');
				$qry[] = $this->db->get_compiled_select();

				/************** Purchase ----Stock In ******************/
				$this->db->select("CONCAT('PU#',t1.purchase_id) ref_no,t1.branch_id,t2.item_id,t2.item_name,t2.item_desc,sr_no,t1.ledger_id as purchased_frm,acc_head as purchase_head,invoice_date as purchase_date,cost_center purchase_cost_center,1 stock_status,'In Stock' issued_type,'' issued_to,'' issued_head,'' issued_date,t1.md_usr md_usr,t1.md_date as md_date,t1.cr_usr cr_usr,t1.cr_date,t1.purchase_id as ref_id,'PU' ref_type,invoice_date as date,ledger_id as ledger_id,t2.item_desc as remarks,$finYear fin_yr")->from("$compDb.comp_purchase_master_$finYear as t1")->join("$compDb.comp_purchase_itemlist_$finYear as t2", "t1.purchase_id=t2.purchase_id", 'left')->where("sr_no_required=1 and (challanin_no is null or challanin_no='')", NULL, FALSE);

				if ($whereItem != '')
					$this->db->where($whereItem, null, false);

				$this->db->group_by('branch_id,item_id,sr_no,t1.purchase_id,t2.item_name,t2.item_desc,t1.acc_head,t1.invoice_date,t1.md_usr,t1.md_date,t1.cr_usr,t1.cr_date,ledger_id,cost_center,fin_yr');
				$qry[] = $this->db->get_compiled_select();

				/************** Purchase Return ----Stock OUT ******************/
				$this->db->select("CONCAT('PR#',t1.prtn_id) ref_no,t1.branch_id,t2.item_id,t2.item_name,t2.item_desc,sr_no,t1.prtn_id as purchased_frm,acc_head as purchase_head,prtn_date as purchase_date,'' purchase_cost_center,0 stock_status,'Purchase Return' issued_type,ledger_id issued_to,acc_head issued_head,prtn_date issued_date,t1.md_usr md_usr,t1.md_date as md_date,t1.cr_usr cr_usr,t1.cr_date,t1.prtn_id as ref_id,'PR' ref_type,prtn_date as date,ledger_id as ledger_id,t2.item_desc as remarks,$finYear fin_yr")->from("$compDb.comp_prtn_master_$finYear as t1")->join("$compDb.comp_prtn_itemlist_$finYear as t2", "t1.prtn_id=t2.prtn_id", 'left')->where(array('sr_no_required' => 1));

				if ($whereItem != '')
					$this->db->where($whereItem, null, false);

				$this->db->group_by('branch_id,item_id,sr_no,t1.prtn_id,t2.item_name,t2.item_desc,t1.acc_head,t1.prtn_date,t1.md_usr,t1.md_date,t1.cr_usr,t1.cr_date,ledger_id,fin_yr');
				$qry[] = $this->db->get_compiled_select();

				/************** Invoice ----Stock OUT ******************/
				$this->db->select("CONCAT('IN#',t1.sale_id) ref_no,t1.branch_id,t2.item_id,t2.item_name,t2.item_desc,sr_no,'' purchased_frm,'' as purchase_head,'' as purchase_date,'' purchase_cost_center,0 stock_status,'Sold' issued_type,t1.ledger_id issued_to,t1.acc_head issued_head,t1.invoice_date issued_date,t1.md_usr md_usr,t1.md_date as md_date,t1.cr_usr cr_usr,t1.cr_date,t1.sale_id as ref_id,'IN' ref_type,invoice_date as date,ledger_id as ledger_id,t2.item_desc as remarks,$finYear fin_yr")->from("$compDb.comp_sale_master_$finYear as t1")->join("$compDb.comp_sale_itemlist_$finYear as t2", "t1.sale_id=t2.sale_id", 'left')->where("sr_no_required=1 and t1.status=1 and challan_id is null");


				if ($whereItem != '')
					$this->db->where($whereItem, null, false);

				$this->db->group_by('branch_id,item_id,sr_no,t1.sale_id,t2.item_name,t2.item_desc,t1.acc_head,t1.invoice_date,t1.md_usr,t1.md_date,t1.cr_usr,t1.cr_date,ledger_id');
				$qry[] = $this->db->get_compiled_select();

				/************** Invoice Return ----Stock In ******************/
				$this->db->select("CONCAT('IR#',t1.invrtn_id) ref_no,t1.branch_id,t2.item_id,t2.item_name,t2.item_desc,sr_no,'' purchased_frm,'' as purchase_head,'' as purchase_date,'' purchase_cost_center,1 stock_status,'Invoice Return' issued_type,t1.ledger_id issued_to,t1.acc_head issued_head,t1.invrtn_date issued_date,t1.md_usr md_usr,t1.md_date as md_date,t1.cr_usr cr_usr,t1.cr_date,t1.invrtn_id as ref_id,'IR' ref_type,invrtn_date as date,ledger_id as ledger_id,t2.item_desc as remarks,$finYear fin_yr")->from("$compDb.comp_invrtn_master_$finYear as t1")->join("$compDb.comp_invrtn_itemlist_$finYear as t2", "t1.invrtn_id=t2.invrtn_id", 'left')->where(array('sr_no_required' => 1));

				if ($whereItem != '')
					$this->db->where($whereItem, null, false);

				$this->db->group_by('branch_id,item_id,sr_no,t1.invrtn_id,t2.item_name,t2.item_desc,t1.acc_head,t1.invrtn_date,t1.md_usr,t1.md_date,t1.cr_usr,t1.cr_date,ledger_id,fin_yr');
				$qry[] = $this->db->get_compiled_select();


				/************** STOCK TRANSFER ******************/
				$this->db->select("CONCAT('ST#',t1.trnsfr_id) ref_no,t1.from_branch as branch_id,t2.item_id,t2.item_name,t2.item_desc,sr_no,t1.to_branch as purchased_frm,'' as purchase_head,trnsfr_date as purchase_date,'' purchase_cost_center,1 stock_status,'In Stock' issued_type,'' issued_to,'' issued_head,trnsfr_date issued_date,t1.md_usr md_usr,t1.md_date as md_date,t1.cr_usr cr_usr,t1.cr_date,t1.trnsfr_id as ref_id,'ST' ref_type,trnsfr_date as date,'' as ledger_id,t2.item_desc as remarks,$finYear fin_yr")->from("$compDb.comp_stocktransfer as t1")->join("$compDb.comp_stocktransfer_itemlist as t2", "t1.trnsfr_id=t2.trnsfr_id", 'left')->where(array('sr_no_required' => 1));

				$this->db->where("date(trnsfr_date) >= '$startDate' and date(trnsfr_date) <= '$endDate'", null, false);

				if ($whereItem != '')
					$this->db->where($whereItem, null, false);

				$this->db->group_by('to_branch,from_branch,item_id,sr_no,t1.trnsfr_id,t2.item_name,t2.item_desc,t1.trnsfr_date,t1.md_usr,t1.md_date,t1.cr_usr,t1.cr_date,fin_yr');
				$qry[] = $this->db->get_compiled_select();

				/************** DAILY PRODUCTION - PRODUCED ITEM ******************/
				$this->db->select("CONCAT('DP#',t1.id) ref_no,t1.branch as branch_id,t2.item_id,t2.item_name,t2.item_desc,sr_no,'' purchased_frm,'Production' as purchase_head,production_date as purchase_date,'' purchase_cost_center,1 stock_status,'In Stock' issued_type,'' issued_to,'' issued_head,'' issued_date,t1.md_usr md_usr,t1.md_date as md_date,t1.cr_usr cr_usr,t1.cr_date,t1.id as ref_id,'DP' ref_type,production_date as date,'' as ledger_id,t2.item_desc as remarks,$finYear fin_yr")->from("$compDb.comp_production_master as t1")->join("$compDb.comp_production_itemlist as t2", "t1.id=t2.production_id", 'left')->where('t1.is_reverse', '0')->where(array('sr_no_reuired' => 1));

				$this->db->where("date(production_date) >= '$startDate' and date(production_date) <= '$endDate'", null, false);

				if ($whereItem != '')
					$this->db->where($whereItem, null, false);

				$this->db->group_by('branch_id,item_id,sr_no,t1.id,t2.item_name,t2.item_desc,t1.production_date,t1.md_usr,t1.md_date,t1.cr_usr,t1.cr_date,fin_yr');
				$qry[] = $this->db->get_compiled_select();

				/************** DAILY PRODUCTION - RAW ITEM ******************/
				$this->db->select("CONCAT('DP#',t1.id) ref_no,t1.branch as branch_id,t2.raw_item_id as item_id,t2.raw_item_name as item_name,t2.raw_item_desc as item_desc,raw_sr_no as sr_no,'' purchased_frm,'' as purchase_head,'' as purchase_date,'' purchase_cost_center,0 stock_status,'Stock Out' issued_type,t1.id issued_to,'Production' issued_head,production_date issued_date,t1.md_usr md_usr,t1.md_date as md_date,t1.cr_usr cr_usr,t1.cr_date,t1.id as ref_id,'DP_R' ref_type,production_date as date,'' as ledger_id,t2.raw_item_desc as remarks,$finYear fin_yr")->from("$compDb.comp_production_master as t1")->join("$compDb.comp_production_rawitem as t2", "t1.id=t2.production_id", 'left')->where('t1.is_reverse', '0')->where(array('raw_sr_no_required' => 1));

				$this->db->where("date(production_date) >= '$startDate' and date(production_date) <= '$endDate'", null, false);

				if ($whereItem != '')
					$this->db->where(str_replace('item_id', 'raw_item_id', $whereItem), null, false);

				$this->db->group_by('branch_id,raw_item_id,raw_sr_no,t1.id,t2.raw_item_name,t2.raw_item_desc,t1.production_date,t1.md_usr,t1.md_date,t1.cr_usr,t1.cr_date,fin_yr');
				$qry[] = $this->db->get_compiled_select();


				/************** REVERSE PRODUCTION PRODUCTION - PRODUCED ITEM ******************/
				$this->db->select("CONCAT('RP#',t1.id) ref_no,t1.branch as branch_id,t2.item_id,t2.item_name,t2.item_desc,sr_no,'' purchased_frm,'' as purchase_head,'' as purchase_date,'' purchase_cost_center,0 stock_status,'Stock Out' issued_type,'' issued_to,'Production' issued_head,production_date issued_date,t1.md_usr md_usr,t1.md_date as md_date,t1.cr_usr cr_usr,t1.cr_date,t1.id as ref_id,'RP' ref_type,production_date as date,'' as ledger_id,t2.item_desc as remarks,$finYear fin_yr")->from("$compDb.comp_production_master as t1")->join("$compDb.comp_production_itemlist as t2", "t1.id=t2.production_id", 'left')->where('t1.is_reverse', '1')->where(array('sr_no_reuired' => 1));

				$this->db->where("date(production_date) >= '$startDate' and date(production_date) <= '$endDate'", null, false);

				if ($whereItem != '')
					$this->db->where($whereItem, null, false);

				$this->db->group_by('branch_id,item_id,sr_no,t1.id,t2.item_name,t2.item_desc,t1.production_date,t1.md_usr,t1.md_date,t1.cr_usr,t1.cr_date,fin_yr');
				$qry[] = $this->db->get_compiled_select();

				/**************  REVERSE PRODUCTION PRODUCTION - RAW ITEM ******************/
				$this->db->select("CONCAT('DP#',t1.id) ref_no,t1.branch as branch_id,t2.raw_item_id as item_id,t2.raw_item_name as item_name,t2.raw_item_desc as item_desc,raw_sr_no as sr_no,'' purchased_frm,'Production' as purchase_head,production_date as purchase_date,'' purchase_cost_center,1 stock_status,'In Stock' issued_type,'' issued_to,'' issued_head,'' issued_date,t1.md_usr md_usr,t1.md_date as md_date,t1.cr_usr cr_usr,t1.cr_date,t1.id as ref_id,'RP_R' ref_type,production_date as date,'' as ledger_id,t2.raw_item_desc as remarks,$finYear fin_yr")->from("$compDb.comp_production_master as t1")->join("$compDb.comp_production_rawitem as t2", "t1.id=t2.production_id", 'left')->where('t1.is_reverse', '1')->where(array('raw_sr_no_required' => 1));

				$this->db->where("date(production_date) >= '$startDate' and date(production_date) <= '$endDate'", null, false);

				if ($whereItem != '')
					$this->db->where(str_replace('item_id', 'raw_item_id', $whereItem), null, false);

				$this->db->group_by('branch_id,raw_item_id,raw_sr_no,t1.id,t2.raw_item_name,t2.raw_item_desc,t1.production_date,t1.md_usr,t1.md_date,t1.cr_usr,t1.cr_date,fin_yr');
				$qry[] = $this->db->get_compiled_select();

				/************** CHALLAN IN ----Stock In ******************/
				$this->db->select("CONCAT('CI#',t1.challanin_id) ref_no,t1.branch_id,t2.item_id,t2.item_name,t2.item_desc,sr_no,t1.ledger_id as purchased_frm,acc_head as purchase_head,challanin_date as purchase_date,cost_center purchase_cost_center,1 stock_status,'In Stock' issued_type,'' issued_to,'' issued_head,'' issued_date,t1.md_usr md_usr,t1.md_date as md_date,t1.cr_usr cr_usr,t1.cr_date,t1.challanin_id as ref_id,'CI' ref_type,challanin_date as date,ledger_id as ledger_id,t2.item_desc as remarks,$finYear fin_yr")->from("$compDb.comp_challanin_master as t1")->join("$compDb.comp_challanin_itemlist as t2", "t1.challanin_id=t2.challanin_id", 'left')->where("sr_no_required=1", NULL, FALSE);

				$this->db->where("date(challanin_date) >= '$startDate' and date(challanin_date) <= '$endDate'", null, false);

				if ($whereItem != '')
					$this->db->where($whereItem, null, false);

				$this->db->group_by('branch_id,item_id,sr_no,t1.challanin_id,t2.item_name,t2.item_desc,t1.acc_head,t1.challanin_date,t1.md_usr,t1.md_date,t1.cr_usr,t1.cr_date,ledger_id,cost_center,fin_yr');
				$qry[] = $this->db->get_compiled_select();

				/************** Stock ISSUE ONLY FOR SR No TRANSACTION ******************/
				$this->db->select("CONCAT('SI#',t1.id) ref_no,t1.branch_id,t2.item_id,t2.item_name,t2.item_description,sr_no,'' as purchased_frm,'' as purchase_head,'' as purchase_date,'' purchase_cost_center,1 stock_status,'Stock Issue' issued_type,user_id issued_to,user_name issued_head,t1.issue_date as issued_date,t1.md_usr md_usr,t1.md_date as md_date,t1.cr_usr cr_usr,t1.cr_date,t1.id as ref_id,'SI' ref_type,issue_date as date,user_id as ledger_id,t2.item_description as remarks,$finYear fin_yr")->from("$compDb.comp_stockissue_master as t1")->join("$compDb.comp_stockissue_itemlist as t2", "t1.id=t2.stock_issue_id", 'left')->where("sr_no_required=1", NULL, FALSE);

				$this->db->where("date(issue_date) >= '$startDate' and date(issue_date) <= '$endDate'", null, false);

				if ($whereItem != '')
					$this->db->where($whereItem, null, false);

				$this->db->group_by('branch_id,item_id,sr_no,t1.id,t2.item_name,t2.item_description,t1.user_name,t1.issue_date,t1.md_usr,t1.md_date,t1.cr_usr,t1.cr_date,user_id,fin_yr');
				$qry[] = $this->db->get_compiled_select();

				/************** Stock ISSUE RETURN FOR SR No TRANSACTION ******************/
				$this->db->select("CONCAT('SR#',t1.id) ref_no,t1.branch_id,t2.item_id,t2.item_name,t2.item_description,sr_no,'' as purchased_frm,'' as purchase_head,'' as purchase_date,'' purchase_cost_center,1 stock_status,'Stock Issue Return ' issued_type,user_id issued_to,user_name issued_head,t1.return_date issued_date,t1.md_usr md_usr,t1.md_date as md_date,t1.cr_usr cr_usr,t1.cr_date,t1.id as ref_id,'SR' ref_type,return_date as date,user_id as ledger_id,t2.item_description as remarks,$finYear fin_yr")->from("$compDb.comp_stockissue_rtn_master as t1")->join("$compDb.comp_stockissue_rtn_itemlist as t2", "t1.id=t2.stock_issue_rtnid", 'left')->where("sr_no_required=1", NULL, FALSE);


				if ($whereItem != '')
					$this->db->where($whereItem, null, false);

				$this->db->group_by('branch_id,item_id,sr_no,t1.id,t2.item_name,t2.item_description,t1.user_name,t1.return_date,t1.md_usr,t1.md_date,t1.cr_usr,t1.cr_date,user_id');
				$qry[] = $this->db->get_compiled_select();

				$this->db->reset_query();
				$union_all = implode(" union all ", $qry);

				$whereSrItemId = "1=1 and fin_yr='$finYear'";
				if (!empty($_POST['item_id'])) {
					$whereSrItemId .= " and item_id='$_POST[item_id]'";
				}
				$dataArray = $this->db->query("select * from ($union_all) AS tn
		where $whereSrItemId
		ORDER BY date,CASE WHEN ref_type='OP' THEN 0 WHEN ref_type='PU' THEN 1 WHEN ref_type='CI' THEN 2 WHEN ref_type='DP' THEN 3 WHEN ref_type='DP_R' THEN 3  WHEN ref_type='RP' THEN 3 WHEN ref_type='DP_R' THEN 3  WHEN ref_type='PR' THEN 5 WHEN ref_type='CH' THEN 6 WHEN ref_type='CR' THEN 6  WHEN ref_type='IN' THEN 8  WHEN ref_type='IR' THEN 8 WHEN ref_type='SI' THEN 10 WHEN ref_type='SR' THEN 10 END ASC,cr_date")->result_array();
				// fx::pr($dataArray,1);
				// Entry For Previous year that are not in stock
				if (count($prevFinYrInstData) > 0)
					$this->db->insert_batch("$compDb.comp_item_srno", $prevFinYrInstData);
				// Entry For Previous year that are not in stock


				$calculateCurrentStock = true;
				if (!empty($calculateCurrentStock)) {
					foreach ($dataArray as $key => $value) {
						$new_finyr = $finYear;
						/**************** ITEM Exist in Item Sr no table ***************/
						$srNoArray = explode(',', preg_replace("/\r|\n/", "", $value['sr_no']));
						$insertSrNoArray = $insertSrNoArray2 = $transactionArray = array();
						foreach ($srNoArray as $sr_no) {
							$sr_no = trim($sr_no);
							if ($sr_no == '')
								continue;

							if (count($postSrno) > 0 && !in_array($sr_no, $postSrno)) {
								continue;
							}

							$refType = $value['ref_type'];
							if ($value['ref_type'] == 'DR_R') {
								$refType = "DP";
							} else if ($value['ref_type'] == 'RP_R') {
								$refType = "DP";
							}
							$updateArray = array();
							$qryItemCheck = $this->db->select("srno_stck_id")->get_where("$compDb.comp_item_srno", array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_srno' => $sr_no, 'finyr_id' => $finYear));

							if ($qryItemCheck->num_rows() > 0) { // Update Item
								$statusUpdate = true;
								$ref_id_validate = $value['ref_id'];

								$updateArray = array('stock_status' => $value['stock_status'], 'issued_type' => $value['issued_type'], 'issued_to' => (!empty($value['issued_to'])) ? $value['issued_to'] : null, 'issued_head' => $value['issued_head'], 'issued_date' => $value['issued_date'], 'md_date' => $value['cr_date'], 'md_usr' => $value['cr_usr']);

								switch ($value['ref_type']) {
									case 'PR':
										//  'issued_type' => 'In Stock'
										$this->db->where(array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_srno' => $sr_no, 'finyr_id' => $finYear))->update("$compDb.comp_item_srno", $updateArray);

										if ($this->db->affected_rows() < 1) {
											$msgString .= "Serial no #$sr_no does not exist in purchase (PR-$ref_id_validate)Fin Year - $new_finyr <br>";
											$statusUpdate = FALSE;
										}
										break;
									case 'CH':
										//  -> where_in('issued_type', array('In Stock', 'Invoice Return', 'Invoice Return', 'Rent Return'))
										$this->db->where(array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_srno' => $sr_no, 'finyr_id' => $finYear))->update("$compDb.comp_item_srno", $updateArray);
										if ($this->db->affected_rows() < 1) {
											$msgString .= "Serial no #$sr_no does not exist in stock (CH-$ref_id_validate)  Fin Year - $new_finyr <br>";
											$statusUpdate = FALSE;
										}
										break;
									case 'OI':
										// -> where_in('issued_type', array('In Stock', 'Invoice Return', 'Invoice Return', 'Rent Return'))
										$this->db->where(array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_srno' => $sr_no, 'finyr_id' => $finYear))->update("$compDb.comp_item_srno", $updateArray);
										if ($this->db->affected_rows() < 1) {
											$msgString .= "Serial no #$sr_no does not exist in stock (OI-$ref_id_validate) Fin Year - $new_finyr<br>";
											$statusUpdate = FALSE;
										}
										break;

									case 'CR':
										// , 'issued_type' => 'On Rent'
										$this->db->where(array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_srno' => $sr_no, 'finyr_id' => $finYear))->update("$compDb.comp_item_srno", $updateArray);
										if ($this->db->affected_rows() < 1) {
											$msgString .= "Serial no #$sr_no does not exist in Challan (CR-$ref_id_validate)  <br>";
											$statusUpdate = FALSE;
										}
										break;
									case 'IN':
										//  -> where_in('issued_type', array('On Rent', 'In Stock', 'Invoice Return', 'Rent Return'))
										$this->db->where(array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_srno' => $sr_no, 'finyr_id' => $finYear))->update("$compDb.comp_item_srno", $updateArray);

										if ($this->db->affected_rows() < 1) {
											$msgString .= "Serial no #$sr_no does not exist in stock (IN-$ref_id_validate) Fin Year - $new_finyr<br>";
											$statusUpdate = FALSE;
										}
										break;
									case 'IR':
										// , 'issued_type' => 'Sold'
										$this->db->where(array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_srno' => $sr_no, 'finyr_id' => $finYear))->update("$compDb.comp_item_srno", $updateArray);

										if ($this->db->affected_rows() < 1) {
											$msgString .= "Serial no #$sr_no does not exist in Invoice (IR-$ref_id_validate) Fin Year - $new_finyr<br>";
											$statusUpdate = FALSE;
										}
										break;
									case 'ST':
										$updateArray = array('branch_id' => $value['branch_id'], 'md_date' => $value['cr_date'], 'md_usr' => $value['cr_usr']);
										// -> where_in('issued_type', array('On Rent', 'In Stock', 'Invoice Return', 'Rent Return'))
										$this->db->where(array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_srno' => $sr_no, 'finyr_id' => $finYear))->update("$compDb.comp_item_srno", $updateArray);
										$statusUpdate = FALSE;

										if ($this->db->affected_rows() < 1) {
											$msgString .= "Serial no #$sr_no does not in stock (ST-$ref_id_validate) Fin Year - $new_finyr<br>";
										} else {

											/******* From Branch Stock Transfer ****************/
											$transactionArray[] = array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_name' => $value['item_name'], 'item_srno' => $sr_no, 'ref_id' => $value['ref_id'], 'ref_type' => $value['ref_type'], 'date' => $value['date'], 'ledger_id' => !empty($value['ledger_id']) ? $value['ledger_id'] : null, 'acc_head' => $value['purchase_head'], 'finyr_id' => $finYear);

											/******* To Branch Stock Transfer ****************/
											$transactionArray[] = array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_name' => $value['item_name'], 'item_srno' => $sr_no, 'ref_id' => $value['ref_id'], 'ref_type' => $value['ref_type'], 'date' => $value['date'], 'ledger_id' => !empty($value['ledger_id']) ? $value['ledger_id'] : null, 'acc_head' => $value['purchase_head'], 'finyr_id' => $finYear);
										}
										break;
									case 'DP_R':
										//Daily Production for raw material
										$statusUpdate = FALSE;
										//  -> where_in('issued_type', array('On Rent', 'In Stock', 'Invoice Return', 'Rent Return'))
										$this->db->where(array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_srno' => $sr_no, 'finyr_id' => $finYear))->update("$compDb.comp_item_srno", $updateArray);
										if ($this->db->affected_rows() < 1) {
											$msgString .= "Serial no #$sr_no does not exist in Stock for Daily Production Raw Item (DP_R-$ref_id_validate) Fin Year - $new_finyr <br>";
										} else {
											$transactionArray[] = array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_name' => $value['item_name'], 'item_srno' => $sr_no, 'ref_id' => $value['ref_id'], 'ref_type' => "DP", 'date' => $value['date'], 'ledger_id' => NULL, 'acc_head' => $value['issued_head'], 'finyr_id' => $finYear);
										}
										break;
									case 'RP':
										//Daily Production for raw material
										$statusUpdate = FALSE;
										// ->where_in('issued_type', array('On Rent', 'In Stock', 'Invoice Return', 'Rent Return'))
										$this->db->where(array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_srno' => $sr_no, 'finyr_id' => $finYear))->update("$compDb.comp_item_srno", $updateArray);
										if ($this->db->affected_rows() < 1) {
											$msgString .= "Serial no #$sr_no does not exist in Stock for Daily Production Raw Item (DP_R-$ref_id_validate) Fin Year - $new_finyr <br>";
										} else {
											$transactionArray[] = array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_name' => $value['item_name'], 'item_srno' => $sr_no, 'ref_id' => $value['ref_id'], 'ref_type' => "RP", 'date' => $value['date'], 'ledger_id' => NULL, 'acc_head' => $value['issued_head'], 'finyr_id' => $finYear);
										}
										break;
									case 'SI':
										$updateArray = array('assign_id' => $value['issued_to'], 'assign_name' => $value['issued_head'], 'assign_date' => $value['issued_date']);
										$this->db->where("assign_id is null", null, FALSE);
										$this->db->where(array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_srno' => $sr_no, 'finyr_id' => $finYear))->update("$compDb.comp_item_srno", $updateArray);
										$statusUpdate = true;
										break;
									case 'SR':
										$updateArray = array('assign_id' => NULL, 'assign_name' => NULL, 'assign_date' => NULL);
										$this->db->where("assign_id is not null", null, FALSE);
										$this->db->where(array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_srno' => $sr_no, 'finyr_id' => $finYear))->update("$compDb.comp_item_srno", $updateArray);
										$statusUpdate = true;
										break;
									default:

										if (in_array($value['ref_type'], array('DP', 'OP', 'PU', 'CI', 'RP_R'))) {
											$updateArray = array('refno' => $refType . "#$value[ref_id]", 'purchase_head' => $value['purchase_head'], 'purchase_date' => $value['purchase_date'], 'purchased_frm' => !empty($value['purchased_frm']) ? $value['purchased_frm'] : null, 'stock_status' => $value['stock_status'], 'issued_type' => $value['issued_type'], 'md_date' => $value['md_date'], 'md_date' => $value['md_date']);

											$msgString .= "Invalid date entry for #$sr_no - ($value[ref_type] -$ref_id_validate)   Fin Year - $new_finyr <br>";
										} else {
											$msgString .= "Invalid date entry for #$sr_no - ($value[ref_type] -$ref_id_validate)   Fin Year - $new_finyr  <br>";
										}
										$this->db->where(array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_srno' => $sr_no, 'finyr_id' => $finYear))->update("$compDb.comp_item_srno", $updateArray);
										$statusUpdate = FALSE;
										break;
								}

								$data = $transactionArray[] = array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_name' => $value['item_name'], 'item_srno' => $sr_no, 'ref_id' => !empty($value['ref_id']) ? $value['ref_id'] : null, 'ref_type' => $refType, 'date' => !(empty($value['date'])) ? $value['date'] : null, 'ledger_id' => !empty($value['ledger_id']) ? $value['ledger_id'] : null, 'acc_head' => $value['issued_head'], 'finyr_id' => $finYear);
							} else {
								switch ($value['ref_type']) {
									case 'OP':
										$insertSrNoArray[] = array('refno' => $value['ref_no'], 'branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_name' => $value['item_name'], 'item_srno' => $sr_no, 'purchased_frm' => null, 'purchase_head' => $value['purchase_head'], 'purchase_date' => $value['purchase_date'], 'stock_status' => $value['stock_status'], 'issued_type' => $value['issued_type'], 'md_date' => $value['md_date'], 'md_date' => $value['md_date'], 'cr_usr' => $value['cr_usr'], 'cr_date' => $value['cr_date'], 'finyr_id' => $finYear);

										$transactionArray[] = array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_name' => $value['item_name'], 'item_srno' => $sr_no, 'ref_id' => NULL, 'ref_type' => $value['ref_type'], 'date' => ($value['purchase_date'] != '') ? $value['purchase_date'] : NULL, 'ledger_id' => NULL, 'acc_head' => $value['purchase_head'], 'finyr_id' => $finYear);

										break;
									case 'DP':
										$insertSrNoArray[] = array('refno' => $value['ref_no'], 'branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_name' => $value['item_name'], 'item_srno' => $sr_no, 'purchased_frm' => null, 'purchase_head' => $value['purchase_head'], 'purchase_date' => $value['purchase_date'], 'stock_status' => $value['stock_status'], 'issued_type' => $value['issued_type'], 'md_date' => $value['md_date'], 'md_date' => $value['md_date'], 'cr_usr' => $value['cr_usr'], 'cr_date' => $value['cr_date'], 'finyr_id' => $finYear);

										$transactionArray[] = array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_name' => $value['item_name'], 'item_srno' => $sr_no, 'ref_id' => $value['ref_id'], 'ref_type' => $value['ref_type'], 'date' => ($value['purchase_date'] != '') ? $value['purchase_date'] : NULL, 'ledger_id' => NULL, 'acc_head' => $value['purchase_head'], 'finyr_id' => $finYear);

										break;
									case 'PU':
										$insertSrNoArray[] = array('refno' => $value['ref_no'], 'branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_name' => $value['item_name'], 'item_srno' => $sr_no, 'purchased_frm' => $value['purchased_frm'], 'purchase_head' => $value['purchase_head'], 'purchase_date' => $value['purchase_date'], 'stock_status' => $value['stock_status'], 'issued_type' => $value['issued_type'], 'md_date' => $value['md_date'], 'md_date' => $value['md_date'], 'cr_usr' => $value['cr_usr'], 'cr_date' => $value['cr_date'], 'finyr_id' => $finYear);

										$transactionArray[] = array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_name' => $value['item_name'], 'item_srno' => $sr_no, 'ref_id' => $value['ref_id'], 'ref_type' => $value['ref_type'], 'date' => ($value['date'] != '') ? $value['date'] : NULL, 'ledger_id' => $value['ledger_id'], 'acc_head' => $value['purchase_head'], 'finyr_id' => $finYear);

										break;
									case 'CI':
										$insertSrNoArray[] = array('refno' => $value['ref_no'], 'branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_name' => $value['item_name'], 'item_srno' => $sr_no, 'purchased_frm' => $value['purchased_frm'], 'purchase_head' => $value['purchase_head'], 'purchase_date' => $value['purchase_date'], 'stock_status' => $value['stock_status'], 'issued_type' => $value['issued_type'], 'md_date' => $value['md_date'], 'md_date' => $value['md_date'], 'cr_usr' => $value['cr_usr'], 'cr_date' => $value['cr_date'], 'finyr_id' => $finYear);

										$transactionArray[] = array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_name' => $value['item_name'], 'item_srno' => $sr_no, 'ref_id' => $value['ref_id'], 'ref_type' => $value['ref_type'], 'date' => ($value['date'] != '') ? $value['date'] : NULL, 'ledger_id' => $value['ledger_id'], 'acc_head' => $value['purchase_head'], 'finyr_id' => $finYear);

										break;
									case 'RP_R':
										$insertSrNoArray[] = array('refno' => $refType . "#" . $value['ref_id'], 'branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_name' => $value['item_name'], 'item_srno' => $sr_no, 'purchased_frm' => (!empty($value['purchased_frm'])) ? $value['purchased_frm'] : null, 'purchase_head' => $value['purchase_head'], 'purchase_date' => $value['purchase_date'], 'stock_status' => $value['stock_status'], 'issued_type' => $refType, 'md_date' => $value['md_date'], 'md_date' => $value['md_date'], 'cr_usr' => $value['cr_usr'], 'cr_date' => $value['cr_date'], 'finyr_id' => $finYear);

										$transactionArray[] = array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_name' => $value['item_name'], 'item_srno' => $sr_no, 'ref_id' => !empty($value['ref_id']) ? $value['ref_id'] : null, 'ref_type' => $refType, 'date' => ($value['date'] != '') ? $value['date'] : NULL, 'ledger_id' => !empty($value['ledger_id']) ? $value['ledger_id'] : null, 'acc_head' => $value['purchase_head'], 'finyr_id' => $finYear);

										break;
									default:
										if (in_array($value['ref_type'], array('PR', 'CH', 'OI', 'CR', 'IN', 'IR', 'ST', 'DP_R', 'RP'))) {
											$insertSrNoArray2[] = array('refno' => '', 'branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_name' => $value['item_name'], 'item_srno' => $sr_no, 'purchased_frm' => (!empty($value['purchased_frm'])) ? $value['purchased_frm'] : null, 'purchase_head' => '', 'purchase_date' => null, 'stock_status' => $value['stock_status'], 'issued_type' => $value['issued_type'], 'issued_to' => !empty($value['issued_to']) ? $value['issued_to'] : null, 'issued_head' => $value['issued_head'], 'issued_date' => $value['issued_date'], 'md_date' => $value['md_date'], 'md_date' => $value['md_date'], 'cr_usr' => $value['cr_usr'], 'cr_date' => $value['cr_date'], 'finyr_id' => $finYear);

											$transactionArray[] = array('branch_id' => $value['branch_id'], 'item_id' => $value['item_id'], 'item_name' => $value['item_name'], 'item_srno' => $sr_no, 'ref_id' => !empty($value['ref_id']) ? $value['ref_id'] : null, 'ref_type' => $refType, 'date' => ($value['date'] != '') ? $value['date'] : NULL, 'ledger_id' => !empty($value['ledger_id']) ? $value['ledger_id'] : null, 'acc_head' => $value['purchase_head'], 'finyr_id' => $finYear);
											// Wrong Entry For Date
										} else {
											// Wrong Entry For Date
										}
										$statusUpdate = FALSE;
										$msgString .= "No entry found against this $sr_no.(" . $value['ref_type'] . "-" . $value['ref_id'] . ")" . " Fin Year - $new_finyr Please check date <br>";
										break;
								}
							}
							/*************** Explodede Sr No loop ends *******************/
						}
						// fx::pr($insertSrNoArray,1);
						if (count($insertSrNoArray) > 0)
							$this->db->insert_batch("$compDb.comp_item_srno", $insertSrNoArray);

						if (count($insertSrNoArray2) > 0) {
							$this->db->insert_batch("$compDb.comp_item_srno", $insertSrNoArray2);
						}

						if (count($transactionArray) > 0)
							$this->db->insert_batch("$compDb.comp_item_srno_txn", $transactionArray);
						/*************** item loop end here *******************/
					}
				}
				// echo "Successfully Transfered";

				// TRANSFER DATA TO NEXT FINACIAL YEAR OPENING BALANCE ************************************* START

				$insertSrNoArray = $transactionArray = array();
				$itemIdArray = $itemDbArray = $itemDbArraySrNo = array();
				$is_update_next_finyr_opening = true;
				$nextFinYrid = !empty($finYearData[$finyrKey + 1]) ? $finYearData[$finyrKey + 1]['finyr_id'] : '';

				if (isset($_POST['type']) && $_POST['type'] == 2) {
					$is_update_next_finyr_opening = false;
				}
				if ($nextFinYrid != '' && $is_update_next_finyr_opening == true) {

					// Update OPending stock value to '' ***************** START
					$this->db->reset_query();
					$this->db->where("finyr_id='$nextFinYrid'", null, false);
					$this->db->update("$compDb.comp_item_stock_master", array('sr_no' => ''));
					$this->db->reset_query();
					// Update OPending stock value to '' ***************** START


					$nextFinyrDate = @$this->getScalerCol('finyr_st_date', 'comp_financial_year', "finyr_id='$nextFinYrid'")->finyr_st_date;

					$this->db->select("*");
					$this->db->from("$compDb.comp_item_srno");
					if (!empty($_POST['item_id']))
						$this->db->where("item_id='$_POST[item_id]'", null, false);
					$this->db->where("finyr_id='$finYear' and stock_status=1");
					$currFinyrDataFOpSt = $this->db->get()->result_array();
					foreach ($currFinyrDataFOpSt as $curIemArray) {
						// fx::pr($curIemArray,1);
						$itemIdArray[$curIemArray['branch_id']][] = $curIemArray['item_id'];
						$itemDbArray[$curIemArray['branch_id']][$curIemArray['item_id']] = $curIemArray;
						$itemDbArraySrNo[$curIemArray['branch_id']][$curIemArray['item_id']][] = $curIemArray['item_srno'];
					}
					foreach ($itemIdArray as $btId => $item_ids) {
						$item_ids = array_unique($item_ids);
						foreach ($item_ids as $itemid) {
							$this->db->select("group_concat(item_srno) item_srno");
							$this->db->where("finyr_id='$nextFinYrid' and item_id='$itemid' and branch_id='$btId'", null, false);
							$this->db->where("item_srno in ('" . implode("','", $itemDbArraySrNo[$btId][$itemid]) . "')", null, false);
							$dbSrNoOpening = $this->db->from("$compDb.comp_item_srno")->get()->row_array();

							$opSrnoExst = !empty($dbSrNoOpening['item_srno']) ? (explode(',', $dbSrNoOpening['item_srno'])) : array();

							$updateSrNoData = array();
							// Item Sr No Loop
							foreach ($itemDbArraySrNo[$btId][$itemid] as $sr_no) {
								$value['md_date'] = $value['cr_date'] = date('Y-m-d H:i:s');
								if (in_array($sr_no, $opSrnoExst)) { // UPDATE ONLY OPENING STOCK
									$updateSrNoData = array('refno' => "OP#$nextFinYrid", 'purchased_frm' => null, 'purchase_head' => "Opening", 'purchase_date' => $nextFinyrDate,   'md_date' => date('Y-m-d H:i:s'));
								} else { // INSERT SRNO
									$insertSrNoArray[] = array('refno' => "OP#$nextFinYrid", 'branch_id' => $btId, 'item_id' => $itemid, 'item_name' => $itemDbArray[$btId][$itemid]['item_name'], 'item_srno' => $sr_no, 'purchased_frm' => null, 'purchase_head' => "Opening", 'purchase_date' => $nextFinyrDate, 'stock_status' => 1, 'issued_type' => 'In Stock', 'md_date' => $value['md_date'], 'md_date' => $value['md_date'], 'cr_usr' => $value['cr_usr'], 'cr_date' => $value['cr_date'], 'finyr_id' => $nextFinYrid);
								}
								// Sr No Transactions
								$transactionArray[] = array('branch_id' => $btId, 'item_id' => $itemid, 'item_name' => $itemDbArray[$btId][$itemid]['item_name'], 'item_srno' => $sr_no, 'ref_id' => null, 'ref_type' => 'OP', 'date' =>  "$nextFinyrDate", 'ledger_id' => null, 'acc_head' => 'Opening', 'finyr_id' => $nextFinYrid);
							}
							// Update Openinig balance
							if (count($updateSrNoData) > 0 && count($opSrnoExst) > 0) {
								$this->db->reset_query();
								$this->db->where(array('branch_id' => $btId, 'item_id' => $itemid, 'finyr_id' => $nextFinYrid));

								$this->db->where_in('item_srno', $opSrnoExst);
								$this->db->update("$compDb.comp_item_srno", $updateSrNoData);
							}

							$this->db->reset_query();
							// Update SR No to ITEM STOCK TABLE (for comma seperated values)
							$this->db->where(array('branch_id' => $btId, 'item_id' => $itemid, 'finyr_id' => $nextFinYrid));

							$this->db->update("$compDb.comp_item_stock_master", array('sr_no' => implode(',', $itemDbArraySrNo[$btId][$itemid])));

							$openingStockCount = count(array_unique($itemDbArraySrNo[$btId][$itemid]));
							$this->db->reset_query();

							$this->db->set("stock", "$openingStockCount+stock_in-stock_out", false);
							$this->db->set("opening_stock", "$openingStockCount", false);
							$this->db->where(array('branch_id' => $btId, 'item_id' => $itemid, 'finyr_id' => $nextFinYrid));
							$this->db->update("$compDb.comp_item_stock_master");

							$this->db->reset_query();
						}
						// item_srno
					}

					if (count($insertSrNoArray) > 0) {
						$this->db->insert_batch("$compDb.comp_item_srno", $insertSrNoArray);
						$this->db->reset_query();
					}

					if (count($transactionArray) > 0) {
						$this->db->insert_batch("$compDb.comp_item_srno_txn", $transactionArray);
						$this->db->reset_query();
					}
				}
				// TRANSFER DATA TO NEXT FINACIAL YEAR OPENING BALANCE ************************************* END

			} // FINANCIAL YEAR LOOP ******************************************ENDK

			// if($msgString != '' && !empty($_POST['item_id'])){
			// 	$this->db->trans_rollback();
			// }else{
			// 	$this->db->trans_commit();
			// }
			//echo $msgString;
			return $msgString;
			// fx::prtable($dataArray);
		}



		public function updateItemStock($stype = '')
		{
			$this->db->trans_begin();
			// TYPE 1 TO CALCULATE STOCK FOR ALL FINANCILA YEAR
			// TYPE 2 TO CALCULATE STOCK FOR SELECTED FINANCILA YEAR
			// TYPE 3 TO TRANSFER OPENING STOCK TO NEXT FINANCIAL YEAR
			$type = $_POST['type'];
			// fx::pr($_POST,1);
			set_time_limit(0);
			$whereItem = $where_item_id = '';
			// $whereItem = "t2.item_id='239'";
			// $_POST['item_id']=44;
			// $where_item_id = " and m2.item_id='44'";
			if (!empty($_POST['item_id'])) {
				$whereItem = "t2.item_id='$_POST[item_id]'";
				$where_item_id = "and m2.item_id='$_POST[item_id]'";
			}

			$compDb = $this->fx->clientCompDb;
			/********** GET ALL FINANCIAL YEAR *********************** START */

			if (isset($_POST['finyr_id']) && $type == 2) {
				$this->db->where("finyr_id='$_POST[finyr_id]'", null, false);
			}
			$this->db->order_by("finyr_from asc,finyr_st_date asc");
			$finYearData = $this->db->get("$compDb.comp_financial_year t1")->result_array();

			//fx::pr($finYearData,1);
			/********** GET ALL FINANCIAL YEAR *********************** END */

			foreach ($finYearData as $finyrKey => $finData) {
				$finYear = $finData['finyr_id'];
				$startDate = $finData['finyr_st_date'];
				$endDate = $finData['finyr_end_date'];

				$qry = array();
				/************** Challan ---- Stock Out ******************/
				$this->db->select("t1.branch_id,t2.item_id,0 stkIn,SUM(t2.qty) stkOut")->from("$compDb.comp_challan_master as t1")->join("$compDb.comp_challan_itemlist as t2", "t1.challan_id=t2.challan_id", 'inner');
				$this->db->where("date(challan_date) >= '$startDate' and date(challan_date) <= '$endDate'", null, false);
				if ($whereItem != '')
					$this->db->where("$whereItem", null, false);

				$this->db->group_by('branch_id,item_id');
				$qry[] = $this->db->get_compiled_select();

				/************** Challan Return  ---- Stock In  ******************/
				$this->db->select("t1.branch_id,t2.item_id,SUM(t2.qty) stkIn,0 stkOut")->from("$compDb.comp_challanrtn_master as t1")->join("$compDb.comp_challanrtn_itemlist as t2", "t1.challan_rtn_id=t2.challan_rtn_id", 'left');
				$this->db->where("date(challan_rtn_date) >= '$startDate' and date(challan_rtn_date) <= '$endDate'", null, false);
				if ($whereItem != '')
					$this->db->where("$whereItem", null, false);

				$this->db->group_by('branch_id,item_id');
				$qry[] = $this->db->get_compiled_select();

				/************** Stock Transfer To Branch  ---- Stock In  ******************/
				$this->db->select("t1.to_branch as branch,t2.item_id,SUM(t2.qty) stkIn,0 stkOut")->from("$compDb.comp_stocktransfer as t1")->join("$compDb.comp_stocktransfer_itemlist as t2", "t1.trnsfr_id=t2.trnsfr_id", 'left');

				$this->db->where("date(trnsfr_date) >= '$startDate' and date(trnsfr_date) <= '$endDate'", null, false);
				if ($whereItem != '')
					$this->db->where("$whereItem", null, false);

				$this->db->group_by('to_branch,item_id');
				$qry[] = $this->db->get_compiled_select();

				/************** Stock Transfer From Branch ---- Stock Out ******************/
				$this->db->select("t1.from_branch as branch,t2.item_id,0 stkIn,SUM(t2.qty) stkOut")->from("$compDb.comp_stocktransfer as t1")->join("$compDb.comp_stocktransfer_itemlist as t2", "t1.trnsfr_id=t2.trnsfr_id", 'left');
				$this->db->where("date(trnsfr_date) >= '$startDate' and date(trnsfr_date) <= '$endDate'", null, false);
				if ($whereItem != '')
					$this->db->where("$whereItem", null, false);

				$this->db->group_by('from_branch,item_id');
				$qry[] = $this->db->get_compiled_select();

				/************** Fin Years --------- STARTS  ******************/

				/************** Invoice ---- Stock Out ******************/
				$this->db->select("t1.branch_id,t2.item_id,0 stkIn,SUM(t2.qty+t2.free_qty) stkOut")->from("$compDb.comp_sale_master_$finYear as t1")->join("$compDb.comp_sale_itemlist_$finYear as t2", "t1.sale_id=t2.sale_id", 'left')->where("t1.status=1 and (challan_id is null or challan_id='')", NULL, FALSE);
				if ($whereItem != '')
					$this->db->where("$whereItem", null, false);

				$this->db->group_by('branch_id,item_id');
				$qry[] = $this->db->get_compiled_select();

				/************** Invoice Return  ---- Stock In  ******************/
				$this->db->select("t1.branch_id,t2.item_id,SUM(t2.qty+t2.free_qty) stkIn,0 stkOut")->from("$compDb.comp_invrtn_master_$finYear as t1")->join("$compDb.comp_invrtn_itemlist_$finYear as t2", "t1.invrtn_id=t2.invrtn_id", 'left');
				if ($whereItem != '')
					$this->db->where("$whereItem", null, false);

				$this->db->group_by('branch_id,item_id');
				$qry[] = $this->db->get_compiled_select();

				/************** Purchase  ---- Stock In  ******************/
				$this->db->select("t1.branch_id,t2.item_id,SUM(t2.qty+t2.free_qty) stkIn,0 stkOut")->from("$compDb.comp_purchase_master_$finYear as t1")->join("$compDb.comp_purchase_itemlist_$finYear as t2", "t1.purchase_id=t2.purchase_id", 'left')->where("(challanin_no is null or challanin_no='')");
				if ($whereItem != '')
					$this->db->where("$whereItem", null, false);

				$this->db->group_by('branch_id,item_id');
				$qry[] = $this->db->get_compiled_select();

				/************** Purchase Return ---- Stock Out ******************/
				$this->db->select("t1.branch_id,t2.item_id,0 stkIn,SUM(t2.qty+t2.free_qty) stkOut")->from("$compDb.comp_prtn_master_$finYear as t1")->join("$compDb.comp_prtn_itemlist_$finYear as t2", "t1.prtn_id=t2.prtn_id", 'left');

				if ($whereItem != '')
					$this->db->where("$whereItem", null, false);

				$this->db->group_by('branch_id,item_id');
				$qry[] = $this->db->get_compiled_select();

				/************** Fin Years --------- ENDS  ******************/

				/************** Production - Produced Item ---- Stock In ******************/
				$this->db->select("t1.branch as branch,t2.item_id,SUM(t2.qty) stkIn,0 stkOut")->from("$compDb.comp_production_master as t1")->join("$compDb.comp_production_itemlist as t2", "t1.id=t2.production_id", 'left')->where('t1.is_reverse', '0');

				$this->db->where("date(production_date) >= '$startDate' and date(production_date) <= '$endDate'", null, false);
				if ($whereItem != '')
					$this->db->where("$whereItem", null, false);

				$this->db->group_by('branch,item_id');
				$qry[] = $this->db->get_compiled_select();

				/************** Production - Raw Item ---- Stock Out ******************/
				$this->db->select("t1.branch as branch,t2.raw_item_id as item_id,0 stkIn,SUM(t2.raw_qty) stkOut")->from("$compDb.comp_production_master as t1")->join("$compDb.comp_production_rawitem as t2", "t1.id=t2.production_id", 'left')->where('t1.is_reverse', '0');
				$this->db->where("date(production_date) >= '$startDate' and date(production_date) <= '$endDate'", null, false);
				if ($whereItem != '')
					$this->db->where(str_replace(array('item_id'), array('raw_item_id'), $whereItem), null, false);

				$this->db->group_by('branch,item_id');
				$qry[] = $this->db->get_compiled_select();

				/************** Reverse Production - Produced Item ---- Stock OUT ******************/
				$this->db->select("t1.branch as branch,t2.item_id,0 stkIn,SUM(t2.qty) stkOut")->from("$compDb.comp_production_master as t1")->join("$compDb.comp_production_itemlist as t2", "t1.id=t2.production_id", 'left')->where('t1.is_reverse', '1');

				$this->db->where("date(production_date) >= '$startDate' and date(production_date) <= '$endDate'", null, false);
				if ($whereItem != '')
					$this->db->where("$whereItem", null, false);

				$this->db->group_by('branch,item_id');
				$qry[] = $this->db->get_compiled_select();

				/************** Reverse Production - Raw Item ---- Stock IN ******************/
				$this->db->select("t1.branch as branch,t2.raw_item_id as item_id,SUM(t2.raw_qty) stkIn,0 stkOut")->from("$compDb.comp_production_master as t1")->join("$compDb.comp_production_rawitem as t2", "t1.id=t2.production_id", 'left')->where('t1.is_reverse', '1');
				$this->db->where("date(production_date) >= '$startDate' and date(production_date) <= '$endDate'", null, false);
				if ($whereItem != '')
					$this->db->where(str_replace(array('item_id'), array('raw_item_id'), $whereItem), null, false);

				$this->db->group_by('branch,item_id');
				$qry[] = $this->db->get_compiled_select();

				/************** ON Going Inventory ---- Stock Out ******************/
				$this->db->select("t1.branch_id,t2.item_id,0 stkIn,SUM(t2.qty) stkOut")->from("$compDb.comp_ongoing_inv_master as t1")->join("$compDb.comp_ongoing_inv_itemlist as t2", "t1.inv_id=t2.inv_id", 'left');
				$this->db->where("date(delivery_date) >= '$startDate' and date(delivery_date) <= '$endDate'", null, false);
				if ($whereItem != '')
					$this->db->where("$whereItem", null, false);

				$this->db->group_by('branch_id,item_id');
				$qry[] = $this->db->get_compiled_select();

				/************** Challanin  ---- Stock In  ******************/
				$this->db->select("t1.branch_id,t2.item_id,SUM(t2.qty+t2.free_qty) stkIn,0 stkOut")->from("$compDb.comp_challanin_master as t1")->join("$compDb.comp_challanin_itemlist as t2", "t1.challanin_id=t2.challanin_id", 'left');
				$this->db->where("date(challanin_date) >= '$startDate' and date(challanin_date) <= '$endDate'", null, false);
				if ($whereItem != '')
					$this->db->where("$whereItem", null, false);

				$this->db->group_by('branch_id,item_id');
				$qry[] = $this->db->get_compiled_select();

				$this->db->reset_query();
				$union_all = implode(" union all ", $qry);

				$dataArray = $this->db->query("SELECT $finYear as finyr_id,m2.item_name,m2.item_id,case when m1.branch_id IS NULL then m3.branch_id ELSE m1.branch_id END branch_id,m3.opening_stock,ifnull(sum(stkIn),0) stock_in,ifnull(sum(stkOut),0) stock_out, m3.opening_stock+ifnull(sum(stkIn),0)-ifnull(sum(stkOut),0) stock,m2.item_sr_no,m3.sr_no,ifnull(m3.rol,0) rol  from
					$compDb.comp_item_master m2
					left join ($union_all) m1 on m1.item_id=m2.item_id
					left join $compDb.comp_item_stock_master m3 on m2.item_id=m3.item_id AND (m1.branch_id=m3.branch_id OR m1.branch_id IS NULL) and m3.finyr_id='$finYear'
					where m2.service_item=0 and m3.finyr_id='$finYear' $where_item_id
					group by m2.item_name,item_id,branch_id,opening_stock,item_sr_no,sr_no,rol")->result_array();

				$is_update_current = true;
				$is_update_next_finyr_opening = true;

				$nextFinYr = '';
				if (!isset($finYearData[$finyrKey + 1])) {
					// echo " ---- $finYear";die;
					$is_update_next_finyr_opening = false;
				} else {
					$nextFinYr = $finYearData[$finyrKey + 1]['finyr_id'];
				}
				// No Need to transfer opening balance to next financial year
				if ($type == 2) {
					$is_update_next_finyr_opening = false;
				}

				foreach ($dataArray as $key => $value) {
					// fx::pr($value,1);
					// UPDATE DATA TO CURRENCT FINANCIAL YEAR **********************START
					if ($is_update_current == true) {
						$numRows = $this->db->select('item_id')->get_where("$compDb.comp_item_stock_master", array('item_id' => $value['item_id'], 'branch_id' => $value['branch_id'], 'finyr_id' => $finYear))->num_rows();

						// // Delete Duplicate rows ********************** START **************
						// if($numRows >1 ){
						// 	$this->db->reset_query();
						// 	$this -> db -> where(array('item_id' => $value['item_id'], 'branch_id' => $value['branch_id'], 'finyr_id' => $finYear));
						// 	$this -> db -> limit($numRows-1);
						// 	$this->db->delete("$compDb.comp_item_stock_master");
						// 	$this->db->reset_query();
						// }
						// // Delete Duplicate rows ********************** END **************

						if ($numRows > 0) {
							$updateStockQty = array('stock_in' => ($value['stock_in'] != '') ? $value['stock_in'] : 0, 'stock_out' => ($value['stock_out'] != '') ? $value['stock_out'] : 0, 'stock' => ($value['stock'] != '') ? $value['stock'] : 0, 'item_sr_no' => $value['item_sr_no']);

							$this->db->where(array('item_id' => $value['item_id'], 'branch_id' => $value['branch_id'], 'finyr_id' => $finYear))->update("$compDb.comp_item_stock_master", $updateStockQty);
						} else {
							$updateStockQty = array('item_id' => $value['item_id'], 'branch_id' => $value['branch_id'], 'opening_stock' => 0.00, 'stock_in' => ($value['stock_in'] != '') ? $value['stock_in'] : 0, 'stock_out' => ($value['stock_out'] != '') ? $value['stock_out'] : 0, 'stock' => ($value['stock'] != '') ? $value['stock'] : 0, 'finyr_id' => $finYear, 'item_sr_no' => $value['item_sr_no']);
							$this->db->insert("$compDb.comp_item_stock_master", $updateStockQty);
						}
					}

					$this->db->reset_query();
					// UPDATE DATA TO CURRENCT FINANCIAL YEAR **********************END

					// UPDATE DATA TO NEXT FINANCIAL YEAR IN OPENING BALANCE **********************SRART
					if ($is_update_next_finyr_opening == true && $nextFinYr != '') {
						$nextStockData =  $this->db->select('item_id,ifnull(stock,0)-ifnull(opening_stock,0) stock')->get_where("$compDb.comp_item_stock_master", array('item_id' => $value['item_id'], 'branch_id' => $value['branch_id'], 'finyr_id' => $nextFinYr))->row_array();
						if (isset($nextStockData['item_id'])) {
							$updateStockQty = array(
								'opening_stock' => ($value['stock'] != '') ? $value['stock'] : 0,
								'rol' => !empty($value['rol']) ? $value['rol'] : 0,
								'stock' => ($value['stock'] != '') ? $value['stock'] + $nextStockData['stock'] : $nextStockData['stock'], 'item_sr_no' => $value['item_sr_no']
							);
							$this->db->where(array('item_id' => $value['item_id'], 'branch_id' => $value['branch_id'], 'finyr_id' => $nextFinYr))->update("$compDb.comp_item_stock_master", $updateStockQty);
						} else {
							$updateStockQty = array('item_id' => $value['item_id'], 'branch_id' => $value['branch_id'], 'opening_stock' => ($value['stock'] != '') ? $value['stock'] : 0, 'stock_in' => 0.00, 'stock_out' => 0.00, 'stock' => ($value['stock'] != '') ? $value['stock'] : 0, 'finyr_id' => $nextFinYr, 'item_sr_no' => $value['item_sr_no'], 'rol' => !empty($value['rol']) ? $value['rol'] : 0);
							$this->db->insert("$compDb.comp_item_stock_master", $updateStockQty);
						}
						$this->db->reset_query();
					}
					// UPDATE DATA TO NEXT FINANCIAL YEAR IN OPENING BALANCE **********************END
				}
			}

			/************* Update Item Serial Number *************************/
			$msgString = "Item Stock Updated Successfully";
			$msgString = $this->updateItemSrNo($finYearData);
			$this->db->trans_complete();
			return $msgString;
		}

		function updateSrnoStatusToFirstFInYearOpStock()
		{
			$compDb = $this->fx->clientCompDb;
			$compID = @$this->fx->clientCompId;
			$where = "comp_db='$compDb'";
			if ($compID != '')
				$where = "comp_db='$compDb'";

			$is_sr_no_update = $this->getScalerCol("is_sr_no_update", "acc_client_company", "$where", 1)->is_sr_no_update;

			if ($is_sr_no_update == 1)
				return;

			$this->db->order_by("finyr_from asc,finyr_st_date asc");
			$finYearData = $this->db->limit(1)->get("$compDb.comp_financial_year t1")->row_array();
			$finyr_id = @$finYearData['finyr_id'];
			$this->db->query("UPDATE $compDb.comp_item_stock_master SET finyr_id='$finyr_id'");
			$this->db->query("UPDATE acc_client_company SET is_sr_no_update='1' where $where");
			return;
		}

		public function getAllCompanyList()
		{
			return $this->db->select("comp_id,comp_db")->get_where("acc_client_company")->result_array();
		}

		public function getAllFinYearList($db)
		{
			return $this->db->select("finyr_id")->get_where("$db.comp_financial_year")->result_array();
		}

		public function getLastFinYear($db)
		{
			return $this->db->select("finyr_id")->order_by('finyr_id', 'desc')->get_where("$db.comp_financial_year")->row()->finyr_id;
		}

		public function mergeSalesAccToItemGrid()
		{
			// function to update sales account to item grid
			$companyArray = $this->getAllCompanyList();
			$finYearArray = array();
			echo "CLI Request Start \n";
			foreach ($companyArray as $compDbAr) {
				$compDB = $compDbAr['comp_db'];
				$finYearArray = $this->getAllFinYearList($compDB);

				// Sale Acc In *************** Contract
				$dataArray = $this->db->select("sales_acc,sales_acc_id,sales_acc_state_code,contract_id")->from($compDB . ".comp_contract_master")->get()->result_array();

				foreach ($dataArray as $key => $value) {
					$data = array('sales_acc' => $value['sales_acc'], 'sales_acc_id' => $value['sales_acc_id'], 'sales_acc_state_code' => $value['sales_acc_state_code']);
					$this->db->where(array('contract_id' => $value['contract_id']))->update($compDB . ".comp_contract_itemlist", $data);
				}

				foreach ($finYearArray as $finYr) {
					// Financial Year array starts here
					$finyr_id = $finYr['finyr_id'];

					$dataArray = $this->db->select("sale_acc,sale_acc_id,sale_acc_state_code,sale_id")->from($compDB . ".comp_sale_master_$finyr_id")->get()->result_array();

					foreach ($dataArray as $key => $value) {
						$data = array('sale_acc' => $value['sale_acc'], 'sale_acc_id' => $value['sale_acc_id'], 'sale_acc_state_code' => $value['sale_acc_state_code']);
						$this->db->where(array('sale_id' => $value['sale_id']))->update($compDB . ".comp_sale_itemlist_$finyr_id", $data);
					}

					// Invoice return Acc In *************** INVOICE return
					$dataArray = $this->db->select("invrtn_acc,invrtn_acc_id,invrtn_acc_state_code,invrtn_id")->from($compDB . ".comp_invrtn_master_$finyr_id")->get()->result_array();
					foreach ($dataArray as $key => $value) {
						$data = array('invrtn_acc' => $value['invrtn_acc'], 'invrtn_acc_id' => $value['invrtn_acc_id'], 'invrtn_acc_state_code' => $value['invrtn_acc_state_code']);
						$this->db->where(array('invrtn_id' => $value['invrtn_id']))->update($compDB . ".comp_invrtn_itemlist_$finyr_id", $data);
					}

					// Purchase Acc In *************** Purchase
					$dataArray = $this->db->select("purchase_acc,purchase_acc_id,purchase_acc_state_code,purchase_id")->from($compDB . ".comp_purchase_master_$finyr_id")->get()->result_array();
					foreach ($dataArray as $key => $value) {
						$data = array('purchase_acc' => $value['purchase_acc'], 'purchase_acc_id' => $value['purchase_acc_id'], 'purchase_acc_state_code' => $value['purchase_acc_state_code']);
						$this->db->where(array('purchase_id' => $value['purchase_id']))->update($compDB . ".comp_purchase_itemlist_$finyr_id", $data);
					}

					// Purchase Return Acc In *************** Purchase
					$dataArray = $this->db->select("prtn_acc,prtn_acc_id,prtn_acc_state_code,prtn_id")->from($compDB . ".comp_prtn_master_$finyr_id")->get()->result_array();
					foreach ($dataArray as $key => $value) {
						$data = array('prtn_acc' => $value['prtn_acc'], 'prtn_acc_id' => $value['prtn_acc_id'], 'prtn_acc_state_code' => $value['prtn_acc_state_code']);
						$this->db->where(array('prtn_id' => $value['prtn_id']))->update($compDB . ".comp_prtn_itemlist_$finyr_id", $data);
					}
				}
				// Fin Year Loop ******** END
				echo "Data Update for DB - $compDB \n";
			} // Company Loop ******** END
			echo "CLI Request End \n";
		}

		public function updateGstInToInvPurchase()
		{ //function to update sales account to item grid
			$companyArray = $this->getAllCompanyList();
			$finYearArray = array();
			echo "CLI Request Start \n";
			foreach ($companyArray as $compDbAr) {
				$compDB = $compDbAr['comp_db'];
				$finYearArray = $this->getAllFinYearList($compDB);

				foreach ($finYearArray as $finYr) { //Financial Year array starts here
					$finyr_id = $finYr['finyr_id'];
					/* GET INVOICE ARRAY ************************* STARTS*/
					$this->db->select("t1.sale_id,t1.ledger_id,t1.ship_to_id,t1.acc_head_address_id,t1.ship_to_address_id,t2.gstin as acc_head_ledger_gstin,t3.gstin as ship_to_ledger_gstin,t4.gstin as acc_head_address_gstin,t5.gstin as ship_to_address_gstin")->from("$compDB.comp_sale_master_$finyr_id as t1");
					$this->db->join("$compDB.comp_ledger_master as t2", "t2.ledger_id=t1.ledger_id", "left");
					$this->db->join("$compDB.comp_ledger_master as t3", "t3.ledger_id=t1.ship_to_id", "left");
					$this->db->join("$compDB.comp_ledger_address as t4", "t4.id=t1.acc_head_address_id", "left");
					$this->db->join("$compDB.comp_ledger_address as t5", "t5.id=t1.ship_to_address_id", "left");

					$dataArray = $this->db->get()->result_array();

					foreach ($dataArray as $salesArray) {
						$sale_id = $salesArray['sale_id'];

						$acc_head_gstin = ($salesArray['acc_head_address_id'] > 0) ? $salesArray['acc_head_address_gstin'] : $salesArray['acc_head_ledger_gstin'];

						$ship_to_gstin = ($salesArray['ship_to_address_id'] > 0) ? $salesArray['ship_to_address_gstin'] : $salesArray['ship_to_ledger_gstin'];

						$this->db->where(array('sale_id' => $sale_id))->update("$compDB.comp_sale_master_$finyr_id", array('acc_head_gstin' => $acc_head_gstin, 'ship_to_gstin' => $ship_to_gstin));
					}
					echo "$compDB.comp_sale_master_$finyr_id updated successfully <br>\n";
					/* UPDATE INVOICE GSTIN DETAIL TO INVOICE TABLE ************************* ENDS */

					/* GET PURCHASE GSTIN AND ADDRESS DETAIL ******************* STARTS*/
					$this->db->select("t1.purchase_id, t1.ledger_id, t2.gstin, CONCAT(t2.add1, if(t2.add2!='',concat(',',t2.add2),''), if(t2.city!='',concat(',',t2.city),''),if(t2.state!='',concat(',',t2.state),''),if(t2.pincode!='',concat(',',t2.pincode),''),if(t2.country!='',concat(',',t2.country),'')) AS ledger_address")->from("$compDB.comp_purchase_master_$finyr_id as t1");
					$this->db->join("$compDB.comp_ledger_master as t2", "t2.ledger_id=t1.ledger_id", "left");

					$dataArray = $this->db->get()->result_array();
					foreach ($dataArray as $purchase) {
						$updateData = array('acc_head_address_id' => 0, 'acc_gstin' => $purchase['gstin'], 'acc_address_textarea' => $purchase['ledger_address']);

						$this->db->where(array('purchase_id' => $purchase['purchase_id']))->update("$compDB.comp_purchase_master_$finyr_id", $updateData);
					}
					echo "$compDB.comp_purchase_master_$finyr_id updated successfully <br>\n";
				} // Fin Year Loop ******** END
				echo "Data Update for DB - $compDB \n";
			} // Company Loop ******** END
			echo "CLI Request End \n";
		}

		public function getPreviousNextMasterId($table_name, $key, $id)
		{
			$prevId = $nextId = NULL;
			if ($id > 0) {
				$prevId = $this->db->select("max($key) as id")->where("$key<$id", NULL, FALSE)->get($table_name)->row()->id;
				$nextId = $this->db->select("min($key) as id")->where("$key>$id", NULL, FALSE)->get($table_name)->row()->id;
			}
			$firstId = $this->db->select("min($key) as id")->get($table_name)->row()->id;
			$lastId = $this->db->select("max($key) as id")->get($table_name)->row()->id;

			return array('prevId' => ($prevId != NULL) ? $prevId : 0, 'nextId' => ($nextId != NULL) ? $nextId : 0, 'firstId' => ($firstId != NULL) ? $firstId : 0, 'lastId' => ($lastId != NULL) ? $lastId : 0);
		}

		public function getPreviousIdAfterDelete($table_name, $key, $id)
		{
			$vNewid = $this->db->select("max($key) as id")->where("$key<$id", NULL, FALSE)->get($table_name)->row()->id;
			if ($vNewid > 0)
				return $vNewid;

			$vNewid = $this->db->select("min($key) as id")->where("$key>$id", NULL, FALSE)->get($table_name)->row()->id;
			if ($vNewid > 0)
				return $vNewid;

			return 0;
		}

		public function updateItemPurchaseRate($purchase_id)
		{
			$this->setTables();
			$dataArray = $this->db->select('rate,item_id')->get_where($this->purchaseItemListTable, array('purchase_id' => $purchase_id, 'service_item' => 0))->result();

			foreach ($dataArray as $key => $value) {
				$this->db->where(array('item_id' => $value->item_id, 'service_item' => 0))->update("$this->compDb.comp_item_master", array('purchase_rate' => $value->rate));
			}
		}

		public function getLedgerDetailsForEmail($where)
		{
			$this->db->select('ledger_id,cc_email_ids,email,mobile,alt_mobile,acc_head,contact_person,acc_head,agent_id,acc_head,add1,add2,city,state,pincode,country,gstin,state_name,t2.state_code');
			$this->setTables();
			$this->db->where($where, null, FALSE);
			$this->db->from("$this->compDb.comp_ledger_master t1");
			$this->db->join("acc_state_master t2", 't2.state_code=t1.state', 'left');
			return $this->db->get()->row();
		}

		public function getUserDetails($where)
		{
			$this->db->where($where, null, FALSE);
			$this->db->select("client_id,concat(client_firstname,' ',client_lastname) clientName,smtp_email_id,smtp_email_password,smtp_email_server,smtp_port,smtp_enable_ssl,smtp_verified_ssl");
			$this->db->from("acc_client_master");
			return $this->db->get()->row();
		}

		public function getAgentIdForLedger($ledger_id)
		{
			$this->setTables();
			$qry = $this->db->select('agent_id')->get_where("$this->compDb.comp_ledger_master", array('ledger_id' => $ledger_id));
			if ($qry->num_rows() > 0)
				return $qry->row()->agent_id;
			return 0;
		}

		public function getCurrencyList($where)
		{
			$this->setTables();
			if ($where != '')
				$this->db->where($where, null, FALSE);
			return $this->db->get("$this->compDb.comp_currency")->result_array();
		}

		public function getPaymentGatewayList($where)
		{
			$this->setTables();
			if ($where != '')
				$this->db->where($where, null, FALSE);
			return $this->db->get("$this->compDb.comp_payment_gateway")->result_array();
		}

		public function getDocumentTypeList($where)
		{
			$this->setTables();
			if (!isset($this->compDb) || $this->compDb == '')
				return array();

			if ($where != '')
				$this->db->where($where, null, FALSE);
			return $this->db->get("$this->compDb.comp_documents_type")->result_array();
		}

		public function getApproveStatusList($where)
		{
			$this->setTables();
			if ($where != '')
				$this->db->where($where, null, FALSE);
			return $this->db->get("$this->compDb.comp_approve_status")->result_array();
		}

		public function getExpenseMasterList($where)
		{
			$this->setTables();
			if ($where != '')
				$this->db->where($where, null, FALSE);
			return $this->db->get("$this->compDb.comp_expense_master")->result();
		}

		public function enterLog($data)
		{
			$this->setTables();
			return $this->db->insert("$this->compDb.comp_software_log", $data);
		}

		public function enterUserLog($data)
		{
			$this->setTables();
			return $this->db->insert("$this->compDb.comp_client_logs", $data);
		}

		public function getLedgerAddress($ledger_id, $address_id)
		{
			$this->setTables();
			$this->db->from("$this->compDb.comp_ledger_master as l");
			if ($address_id > 0) {
				$this->db->select("acc_head,a.address1 add1,a.address2 add2,a.city,a.state,a.pincode,a.country,a.gstin");
				$this->db->join("$this->compDb.comp_ledger_address as a", 'l.ledger_id=a.ledger_master_id', 'left');
				$this->db->where("a.id='$address_id'", NULL, FALSE);
			} else {
				$this->db->select("acc_head,add1,add2,city,state,pincode,country,gstin");
			}
			$this->db->where("l.ledger_id='$ledger_id'", NULL, FALSE);
			return $this->db->get()->row();
		}

		public function getClientDetails($queryArray)
		{
			return $this->db->limit(1)->get_where("acc_client_master", $queryArray)->row();
		}

		public function getTodaysFollowupCount($where)
		{
			//$client_id = $this->fx->clientId;
			//$this->setTables();
			//return $this->db->query("
			//select COUNT(id) as count FROM (
			//select id from $this->compDb.comp_lead_followup where $where and id in (SELECT MAX(id) FROM $this->compDb.comp_lead_followup t1
			//inner join $this->compDb.comp_lead_master t2 on t1.lead_id=t2.lead_id where agent_id='$client_id' GROUP BY t1.lead_id)
			//) as a")->row()->count;

			//echo $this->db->last_query();

			$client_id = $this->fx->clientId;
			$branch_id = $this->fx->compDetails->Defbranches;
			$where2 = '';
			if (!empty($branch_id)) {
				$where2 = " AND  branch_id = $branch_id";
			}
			$this->setTables();
			$query = $this->db->query("SELECT COUNT(folloup) AS count
										FROM (
									SELECT master.id AS folloup
									FROM $this->compDb.comp_lead_followup AS master
								JOIN $this->compDb.comp_lead_status t3 ON t3.id = master.lead_status_id
									WHERE $where  and master.id in (
									SELECT MAX(t1.id)
									FROM $this->compDb.comp_lead_followup t1
									INNER JOIN $this->compDb.comp_lead_master t2 ON t1.lead_id=t2.lead_id
									WHERE agent_id='$client_id' $where2
									GROUP BY t1.lead_id)) AS a")->row();
			return $query->count;
		}



		public function getLeadFollowupforNotification($where)
		{

			$client_id = $this->fx->clientId;
			return $this->db->query("select *,date_format(follow_up_datetime,'%d-%m-%Y %H:%i %p') as formated_time,date_format(comment_time,'%d-%m-%Y %H:%i %p') as comment_time  FROM (SELECT MAX(id) AS id FROM $this->compDb.comp_lead_followup t1 inner join $this->compDb.comp_lead_master t2 on t1.lead_id=t2.lead_id where agent_id='$client_id' or commented_by='$client_id' GROUP BY t1.lead_id) AS t1
		LEFT JOIN $this->compDb.comp_lead_followup  t2 ON t1.id=t2.id where $where order by follow_up_datetime asc")->result_array();
			echo $this->db->last_query();
		}

		public function getTemplateDetail($queryArray = array())
		{
			$this->setTables();
			$this->db->select("t.template_name,t.subject,t.template_body,t.temp_type");
			$this->db->where($queryArray);
			$this->db->from($this->template_master . ' as t');
			$this->db->join($this->template_assign . ' as a', 'a.template_id=t.id', 'left');
			$qry = $this->db->get();
			return $qry->row();
		}

		public function updateLastLedgerReminderDate($data, $qryArray)
		{
			$this->setTables();
			$this->db->where($qryArray);
			return $this->db->update("$this->compDb.comp_ledger_master", $data);
		}

		public function getIndentList($where = '')
		{
			$this->setTables();
			if ($where != '')
				$this->db->where($where);
			$this->db->select("indent_id");
			return $this->db->get("$this->compDb.comp_indent_master_$this->finyrId")->result_array();
		}

		public function getIndentItemList($where = '')
		{
			$this->setTables();
			$this->db->where($where);
			$this->db->select("t2.item_id,sum(qty) qty,t3.item_id,t3.item_name,t3.mrp,t3.item_sr_no,t3.item_desc");
			$this->db->from("$this->compDb.comp_indent_master_$this->finyrId t1");
			$this->db->join("$this->compDb.comp_indent_itemlist_$this->finyrId t2", 't1.indent_id=t2.indent_id', 'left');
			$this->db->join("$this->compDb.comp_item_master t3", "t2.item_id=t3.item_id", 'left');
			$this->db->group_by("t2.item_id,t3.item_id,t3.item_name,t3.mrp,t3.item_sr_no,t3.item_desc");
			return $this->db->get()->result();
		}

		public function getPoItemListForIndent($where = '')
		{
			$this->setTables();
			$this->db->where($where);
			$this->db->select('indent_id,qty,item_id');
			$this->db->from("$this->compDb.comp_po_master_$this->finyrId t1");
			$this->db->join("$this->compDb.comp_po_itemlist_$this->finyrId t2", 't1.po_id=t2.po_id', 'left');
			return $this->db->get()->result();
		}

		public function getChallanItemList($where = '')
		{
			$this->setTables();
			$this->db->where($where);
			$this->db->select('order_no,indent_id,qty,item_id');
			$this->db->from("$this->compDb.comp_challan_master t1");
			$this->db->join("$this->compDb.comp_challan_itemlist t2", 't1.challan_id=t2.challan_id', 'left');
			return $this->db->get()->result();
		}

		public function updateIndentPurchaseChallanQty($indent_id, $item_id, $qty, $type = 'PO', $rollback = 0)
		{
			$this->setTables();
			$this->db->from("$this->compDb.comp_indent_itemlist_$this->finyrId");
			$this->db->where("indent_id", $indent_id);
			$this->db->where("item_id", $item_id);
			$result = $this->db->get();
			if ($result->num_rows() > 0) {
				$this->db->reset_query();

				$this->db->where("indent_id", $indent_id);
				$this->db->where("item_id", $item_id);

				if ($rollback == 1)
					$qty = $qty * -1;

				if ($type == 'PO') {
					$this->db->set("po_qty", "ifnull(po_qty,0)+$qty", false);
				} else if ($type == 'CH') {
					$this->db->set("challan_qty", "ifnull(challan_qty,0)+$qty", false);
				}
				return $this->db->update("$this->compDb.comp_indent_itemlist_$this->finyrId");
			} else {
				return 0;
			}
		}

		public function updateSaleOrderItemQty($so_id, $item_id, $qty, $type = 'CH', $rollback = 0)
		{
			$this->setTables();
			$this->db->from("$this->compDb.comp_so_itemlist_$this->finyrId");
			$this->db->where("so_id", $so_id);
			$this->db->where("item_id", $item_id);
			$result = $this->db->get();
			if ($result->num_rows() > 0) {
				$this->db->reset_query();
				$this->db->where("so_id", $so_id);
				$this->db->where("item_id", $item_id);

				if ($rollback == 1)
					$qty = $qty * -1;

				if ($type == 'CH') {
					$this->db->set("challan_qty", "ifnull(challan_qty,0)+$qty", false);
				}
				return $this->db->update("$this->compDb.comp_so_itemlist_$this->finyrId");
			} else {
				return 0;
			}
		}

		function getAccCompanyLicenseKeyAndLastChecked()
		{
			return $this->db->select("license_key,license")->get("acc_company")->row();
		}
		function getAccCompanyDetail($select = "*")
		{
			$this->db->select("$select");
			return $this->db->get("acc_company")->row();
		}

		function updateClientCompanyDetails($data, $where)
		{
			$this->db->where($where, null, false);
			return $this->db->update("acc_client_company", $data);
		}

		function getTotalClientCompany()
		{
			return $this->db->select("count(comp_id) as count")->get("acc_client_company")->row()->count;
		}

		function updateAccCompanyDetail($data)
		{
			return $this->db->update("acc_company", $data);
		}

		function getCRMDetail()
		{
			$currentDomain = parse_url(base_url());

			$base_url = base_url('uploads/crm_logo/');
			$this->db->select("t1.crm_name,t1.crm_domain,t1.support_phone,concat('$base_url',t1.logo_image) logo_image,t1.host_name,t1.login_bg_image,t1.policy_terms_url,t1.dealer_id,
		t2.smtp_email_id,t2.smtp_email_password,t2.smtp_email_server,t2.smtp_port,smtp_enable_ssl,t2.smtp_verified_ssl,t2.sms_api,whatsapp_api,'' pdf_text,'' footer_text");

			$this->db->where("host_name='$currentDomain[host]'");
			$this->db->from("acc_domain_setting t1");
			$this->db->join("acc_users t2", "t1.dealer_id=t2.user_id", 'left');
			$result = $this->db->get()->row();

			if (!empty($result->crm_name) && !empty($result->crm_domain)) {
				return $result;
			}
			$this->db->reset_query();
			// IF NO RECORD FOUND FOR DOMAIN
			$base_url = base_url();
			$this->db->select("t1.crm_name,t1.crm_domain,t1.support_phone,concat('$base_url',t1.logo_image) logo_image,t1.host_name,'' login_bg_image,t1.policy_terms_url,'' dealer_id,
		t1.smtp_email_id,t1.smtp_email_password,t1.smtp_email_server,t1.smtp_port,smtp_enable_ssl,t1.smtp_verified_ssl,t1.sms_api,whatsapp_api,pdf_text,footer_text");
			$this->db->from("acc_company t1");
			$this->db->limit(1);
			$result = $this->db->get()->row();
			return $result;
		}

		function updateDocumentCron($table, $data, $where)
		{
			$this->db->where($where, null, FALSE);
			return $this->db->update("$table", $data);
		}

		public function getProjectList($where)
		{
			$this->setTables();
			if ($where != '')
				$this->db->where($where, null, false);
			$this->db->distinct();
			$this->db->select("t1.id,t1.project_name");
			$this->db->from("$this->compDb.comp_project_master t1");
			$this->db->join("$this->compDb.comp_project_client_mapping t2", "t1.id=t2.project_id", 'left');
			$this->db->order_by('project_name', 'asc');
			return $this->db->get()->result_array();
		}

		public function getProjectIds($where)
		{
			$this->setTables();
			if ($where != '')
				$this->db->where($where, null, false);
			$this->db->select("group_concat(t1.id) as project_ids");
			$this->db->from("$this->compDb.comp_project_master t1");
			$this->db->join("$this->compDb.comp_project_client_mapping t2", "t1.id=t2.project_id", 'left');
			$this->db->group_by('t2.client_id');
			return $this->db->get()->row();
		}

		public function getClientInfoForProject($where)
		{
			$this->setTables();
			if ($where != '')
				$this->db->where($where, null, false);

			$this->db->select("t1.client_id,t1.project_id,concat(t2.client_firstname,' ',t2.client_lastname) as clientName,t2.client_email,t1.client_type,t2.profile_image,t3.project_name,t3.project_image");
			$this->db->from("$this->compDb.comp_project_client_mapping t1");
			$this->db->join("acc_client_master t2", "t1.client_id=t2.client_id", 'left');
			$this->db->join("$this->compDb.comp_project_master t3", "t1.project_id=t3.id", 'left');
			$this->db->order_by('clientName asc,t1.project_id desc');
			$this->db->group_by("t1.client_id,t1.project_id,t2.client_firstname,t2.client_lastname,t2.client_email,t1.client_type,t2.profile_image,t3.project_name,t3.project_image");
			return $this->db->get()->result_array();
		}

		public function getItemUnitDropdown($where)
		{
			$this->db->distinct();
			$this->db->select('unit');
			$this->setTables();
			if ($where != '')
				$this->db->where($where, null, false);
			return $this->db->get("$this->compDb.comp_item_master")->result_array();
		}

		public function getChallninList($where)
		{
			$this->db->select("challanin_id,date_format(challanin_date,'%d-%m-%y') as challaninDate");
			$this->setTables();
			if ($where != '')
				$this->db->where($where, null, false);
			return $this->db->get("$this->compDb.comp_challanin_master")->result_array();
		}

		public function validatePurchaseInvoice($where)
		{
			$this->setTables();
			return $this->db->select('purchase_id')->where("$where", NULL, FALSE)->get("$this->compDb.comp_purchase_master_$this->finyrId")->num_rows();
		}

		public function updateChallaninAfterPurchase($whereIn, $data)
		{
			$this->setTables();
			$this->db->where_in('challanin_id', $whereIn);
			return $this->db->update("$this->compDb.comp_challanin_master", $data);
		}

		public function getItemDetail($where)
		{
			$this->setTables();
			$this->db->select("item_id,item_name");
			$this->db->from("$this->compDb.comp_item_master");
			$this->db->where($where, NULL, false);
			$this->db->order_by("item_name");
			return $this->db->get()->row();
		}

		public function getUniqueLocationRefType()
		{
			$this->setTables();
			$this->db->select("ref_type");
			$this->db->distinct();
			return $this->db->get("$this->compDb.comp_client_location t1")->result_array();
		}

		public function getAgentLastTarget($where)
		{
			$this->setTables();
			$this->db->select("target_amount");
			$this->db->where($where, null, FALSE);
			$result = $this->db->from("$this->compDb.comp_agent_target")->order_by('id', 'desc')->limit(1)->get()->row();
			if (isset($result->target_amount) && $result->target_amount > 0)
				return $result->target_amount;
			return 0;
		}

		public function getCompanyDialerData($where)
		{
			$this->db->select("comp_id,dial_api,dialer_method,dialer_data,access_token,serv_api_key");
			$this->db->where($where, null, FALSE);
			$this->db->from("acc_client_company");
			return $this->db->get()->row();
		}

		public function getLeadCategoryList($where = '')
		{
			$this->setTables();
			$this->db->select("id,title");
			if ($where != '')
				$this->db->where($where, null, FALSE);
			return $this->db->from("$this->compDb.comp_lead_category")->get()->result_array();
		}

		public function getSubcategoryList($where = '')
		{
			$this->setTables();
			$this->db->select("subcat_id id,subcat_name title,cat_id");
			if ($where != '')
				$this->db->where($where, null, FALSE);
			return $this->db->from("$this->compDb.comp_item_subcategory")->get()->result_array();
		}

		public function getClientResetPasswordLastLog($where = '')
		{
			$this->setTables();
			$this->db->select("id");
			$this->db->order_by('id', 'desc');
			if ($where != '')
				$this->db->where($where, null, FALSE);
			// $this -> db -> group_by("date");
			$this->db->from("$this->compDb.comp_password_reset_log")->limit(1);
			return $this->db->get()->num_rows();
		}

		public function addPasswordLog($data)
		{
			return $this->db->insert("$this->compDb.comp_password_reset_log", $data);
		}

		public function getLedgerMultiContactPersonList($where = '')
		{
			$this->setTables();
			if ($where != '')
				$this->db->where($where, null, FALSE);
			return $this->db->from("$this->compDb.comp_ledger_contact_person")->get()->result_array();
		}

		public function getLedgerMultiContactPersonDropdownList($ledger_id)
		{
			$this->setTables();
			$this->db->select("0 id,ledger_id,contact_person name,email,mobile,alt_mobile");
			$this->db->where("ledger_id='$ledger_id'", null, FALSE);
			$this->db->from("$this->compDb.comp_ledger_master");
			$qry1 = $this->db->get_compiled_select();
			$this->db->reset_query();
			$this->db->select("id,ledger_id,name,email,mobile,alt_mobile");
			$this->db->where("ledger_id='$ledger_id'", null, FALSE);
			$this->db->from("$this->compDb.comp_ledger_contact_person");
			$qry2 = $this->db->get_compiled_select();
			return $this->db->query("select * from ($qry1 union all $qry2) t1")->result_array();
		}

		// Used to Share the Invoice to client using URL************START

		function insertPublicInvURL($data)
		{
			$this->db->insert('comp_public_url', $data);
			return $this->db->insert_id();
		}

		// Used to Share the Invoice to client using URL************END

		// Get the list of all the entries thant does not exist or wrong in transaction table *********** START
		function getTransactionMismatchList($data)
		{
			$data['from_date'] = date('Y-m-d', strtotime($data['from_date']));
			$data['to_date'] = date('Y-m-d', strtotime($data['to_date']));
			$this->setTables();

			// For Purchase 'PU' if sum of import_amount > 0 then cr = sum(amount) else  cr = total_net_amount *** If Import goods is selected in company
			if (in_array('PU', $data['book_type'])) {
				$this->db->select("'PU' book_type,t1.purchase_id as ref_no,t1.acc_head as acc_head,date_format(t1.invoice_date,'%d-%m-%Y') as ref_date,total_net_amount as ref_amount,t2.credit as tr_amt");
				$this->db->from("$this->compDb.comp_purchase_master_$this->finyrId t1");
				$this->db->join("(select purchase_id,sum(import_amount) as total_import_amt,sum(amount) as itm_amt from $this->compDb.comp_purchase_itemlist_$this->finyrId t3 group by purchase_id) t3", 't1.purchase_id=t3.purchase_id', 'left');
				$this->db->join("$this->compDb.comp_transactions t2", "t1.purchase_id=t2.ref_no and t2.book_type='PU' and t2.finyr_id='$this->finyrId' and t1.ledger_id=t2.ledger_id", 'left');
				$this->db->where("(t1.invoice_date >= '$data[from_date]' and t1.invoice_date <= '$data[to_date]') and (t2.tr_id is null or t2.credit!= CASE WHEN t3.total_import_amt>0 then t3.itm_amt else t1.total_net_amount END)", null, FALSE);
				$qry[] = $this->db->get_compiled_select();
			}

			// For Purchase if sum of import_amount > 0 then dr = sum(amount) else  dr = total_net_amount *** If Import goods is selected in company
			if (in_array('PR', $data['book_type'])) {
				$this->db->select("'PR' book_type,t1.prtn_id as ref_no,t1.acc_head as acc_head,date_format(t1.prtn_date,'%d-%m-%Y') as ref_date,total_net_amount as ref_amount,t2.debit as tr_amt");
				$this->db->from("$this->compDb.comp_prtn_master_$this->finyrId t1");
				$this->db->join("(select prtn_id,sum(import_amount) as total_import_amt,sum(amount) as itm_amt from $this->compDb.comp_prtn_itemlist_$this->finyrId t3 group by prtn_id) t3", 't1.prtn_id=t3.prtn_id', 'left');
				$this->db->join("$this->compDb.comp_transactions t2", "t1.prtn_id=t2.ref_no and t2.book_type='PR' and t2.finyr_id='$this->finyrId' and t1.ledger_id=t2.ledger_id", 'left');
				$this->db->where("(t1.prtn_date >= '$data[from_date]' and t1.prtn_date <= '$data[to_date]') and (t2.tr_id is null or t2.debit!= CASE WHEN t3.total_import_amt>0 then t3.itm_amt else t1.total_net_amount END)", null, FALSE);
				$qry[] = $this->db->get_compiled_select();
			}
			// For  Expense/Cr Note EX credit = total_amount
			if (in_array('EX', $data['book_type'])) {
				$this->db->select("'EX' book_type,t1.exp_id as ref_no,t1.acc_head as acc_head,date_format(t1.voucher_date,'%d-%m-%Y') as ref_date,total_amount as ref_amount,t2.credit as tr_amt");
				$this->db->from("$this->compDb.comp_exp_master_$this->finyrId t1");
				$this->db->join("$this->compDb.comp_transactions t2", "t1.exp_id=t2.ref_no and t2.book_type='EX' and t2.finyr_id='$this->finyrId' and t1.acc_head_id=t2.ledger_id", 'left');
				$this->db->where("(t1.voucher_date >= '$data[from_date]' and t1.voucher_date <= '$data[to_date]') and (t2.tr_id is null or t2.credit!= t1.total_amount)", null, FALSE);
				$qry[] = $this->db->get_compiled_select();
			}
			// For Invoice IN debit = total_net_amount
			if (in_array('IN', $data['book_type'])) {
				$this->db->select("'IN' book_type,t1.sale_id as ref_no,t1.acc_head as acc_head,date_format(t1.invoice_date,'%d-%m-%Y') as ref_date,total_net_amount as ref_amount,t2.debit as tr_amt");
				$this->db->from("$this->compDb.comp_sale_master_$this->finyrId t1");
				$this->db->join("$this->compDb.comp_transactions t2", "t1.sale_id=t2.ref_no and t2.book_type='IN' and t2.finyr_id='$this->finyrId' and t1.ledger_id=t2.ledger_id", 'left');
				$this->db->where("(t1.invoice_date >= '$data[from_date]' and t1.invoice_date <= '$data[to_date]') and t1.status=1 and (t2.tr_id is null or t2.debit!= t1.total_net_amount)", null, FALSE);
				$qry[] = $this->db->get_compiled_select();
			}
			// For Inv Dr/Cr Note 'DN' note type = debit then debit = total_net_amount else t2.credit= total_net_amount
			if (in_array('DN', $data['book_type'])) {
				$this->db->select("'DN' book_type,t1.note_id as ref_no,t1.acc_head as acc_head,date_format(t1.note_date,'%d-%m-%Y') as ref_date,total_net_amount as ref_amount,CASE WHEN t1.note_type='dr' THEN t2.debit ELSE t2.credit END as tr_amt");
				$this->db->from("$this->compDb.comp_inv_debitnote_master_$this->finyrId t1");
				$this->db->join("$this->compDb.comp_transactions t2", "t1.note_id=t2.ref_no and t2.book_type='DN' and t2.finyr_id='$this->finyrId' and t1.ledger_id=t2.ledger_id", 'left');
				$this->db->where("(t1.note_date >= '$data[from_date]' and t1.note_date <= '$data[to_date]') and t1.status=1 and (t2.tr_id is null or (CASE WHEN t1.note_type='dr' THEN t2.debit ELSE t2.credit END)!= t1.total_net_amount)", null, FALSE);
				$qry[] = $this->db->get_compiled_select();
			}
			// For Invoice Return IR credit = total_net_amount
			if (in_array('IR', $data['book_type'])) {
				$this->db->select("'IR' book_type,t1.invrtn_id as ref_no,t1.acc_head as acc_head,date_format(t1.invrtn_date,'%d-%m-%Y') as ref_date,total_net_amount as ref_amount,t2.credit as tr_amt");
				$this->db->from("$this->compDb.comp_invrtn_master_$this->finyrId t1");
				$this->db->join("$this->compDb.comp_transactions t2", "t1.invrtn_id=t2.ref_no and t2.book_type='IR' and t2.finyr_id='$this->finyrId' and t1.ledger_id=t2.ledger_id", 'left');
				$this->db->where("(t1.invrtn_date >= '$data[from_date]' and t1.invrtn_date <= '$data[to_date]') and t1.status=1 and (t2.tr_id is null or t2.credit!= t1.total_net_amount)", null, FALSE);
				$qry[] = $this->db->get_compiled_select();
			}
			// For  Income/Debit Note  'IC' debit = total_amount
			if (in_array('IC', $data['book_type'])) {
				$this->db->select("'IC' book_type,t1.inc_id as ref_no,t1.acc_head as acc_head,date_format(t1.voucher_date,'%d-%m-%Y') as ref_date,total_amount as ref_amount,t2.debit as tr_amt");
				$this->db->from("$this->compDb.comp_inc_master_$this->finyrId t1");
				$this->db->join("$this->compDb.comp_transactions t2", "t1.inc_id=t2.ref_no and t2.book_type='IC' and t2.finyr_id='$this->finyrId' and t1.acc_head_id=t2.ledger_id", 'left');
				$this->db->where("(t1.voucher_date >= '$data[from_date]' and t1.voucher_date <= '$data[to_date]') and (t2.tr_id is null or t2.debit!= t1.total_amount)", null, FALSE);
				$qry[] = $this->db->get_compiled_select();
			}
			// For  Cash Payment  'CP' credit = total_amount
			if (in_array('CP', $data['book_type'])) {
				$this->db->select("'CP' book_type,t1.cash_payment_id as ref_no,t1.book as acc_head,date_format(t1.voucher_date,'%d-%m-%Y') as ref_date,total_amount as ref_amount,t2.credit as tr_amt");
				$this->db->from("$this->compDb.comp_ve_cashpayment_$this->finyrId t1");
				$this->db->join("$this->compDb.comp_transactions t2", "t1.cash_payment_id=t2.ref_no and t2.book_type='CP' and t2.finyr_id='$this->finyrId' and t1.book_id=t2.ledger_id", 'left');
				$this->db->where("(t1.voucher_date >= '$data[from_date]' and t1.voucher_date <= '$data[to_date]') and (t2.tr_id is null or t2.credit!= t1.total_amount)", null, FALSE);
				$qry[] = $this->db->get_compiled_select();
			}
			// For  Cash Receipt  'CR' debit = total_amount
			if (in_array('CR', $data['book_type'])) {
				$this->db->select("'CR' book_type,t1.cash_receipt_id as ref_no,t1.book as acc_head,date_format(t1.voucher_date,'%d-%m-%Y') as ref_date,total_amount as ref_amount,t2.debit as tr_amt");
				$this->db->from("$this->compDb.comp_ve_cashreceipt_$this->finyrId t1");
				$this->db->join("$this->compDb.comp_transactions t2", "t1.cash_receipt_id=t2.ref_no and t2.book_type='CR' and t2.finyr_id='$this->finyrId' and t1.book_id=t2.ledger_id", 'left');
				$this->db->where("(t1.voucher_date >= '$data[from_date]' and t1.voucher_date <= '$data[to_date]') and (t2.tr_id is null or t2.debit!= t1.total_amount)", null, FALSE);
				$qry[] = $this->db->get_compiled_select();
			}
			// For  Bank Payment  'BP' credit = total_amount
			if (in_array('BP', $data['book_type'])) {
				$this->db->select("'BP' book_type,t1.bank_payment_id as ref_no,t1.book as acc_head,date_format(t1.voucher_date,'%d-%m-%Y') as ref_date,total_amount as ref_amount,t2.credit as tr_amt");
				$this->db->from("$this->compDb.comp_ve_bankpayment_$this->finyrId t1");
				$this->db->join("$this->compDb.comp_transactions t2", "t1.bank_payment_id=t2.ref_no and t2.book_type='BP' and t2.finyr_id='$this->finyrId' and t1.book_id=t2.ledger_id", 'left');
				$this->db->where("(t1.voucher_date >= '$data[from_date]' and t1.voucher_date <= '$data[to_date]') and (t2.tr_id is null or t2.credit!= t1.total_amount)", null, FALSE);
				$qry[] = $this->db->get_compiled_select();
			}
			// For  Bank Receipt  'BR' debit = total_amount
			if (in_array('BR', $data['book_type'])) {
				$this->db->select("'BR' book_type,t1.bank_receipt_id as ref_no,t1.book as acc_head,date_format(t1.voucher_date,'%d-%m-%Y') as ref_date,total_amount as ref_amount,t2.debit as tr_amt");
				$this->db->from("$this->compDb.comp_ve_bankreceipt_$this->finyrId t1");
				$this->db->join("$this->compDb.comp_transactions t2", "t1.bank_receipt_id=t2.ref_no and t2.book_type='BR' and t2.finyr_id='$this->finyrId' and t1.book_id=t2.ledger_id", 'left');
				$this->db->where("(t1.voucher_date >= '$data[from_date]' and t1.voucher_date <= '$data[to_date]') and (t2.tr_id is null or t2.debit!= t1.total_amount)", null, FALSE);
				$qry[] = $this->db->get_compiled_select();
			}

			$query = implode(" union all ", $qry);
			return $this->db->query("SELECT * FROM ($query) t1 order by t1.ref_date asc")->result();
		}

		// Get the list of all the entries thant does not exist or wrong in transaction table *********** END

		public function getDiffrenceInLedgOpBal($lastFinYr, $current_finyr)
		{
			return $this->db->query("SELECT t1.ledger_id,t1.acc_head,t5.finyr_name cur_finyr_name,t7.finyr_name last_finyr_name,'$current_finyr' cur_finyrId, CASE WHEN cBal<0 THEN 'Cr' ELSE 'Dr' END BalType, ABS(cBal) cbal,t6.opening_balance,t6.balance_type OpBalType
		FROM (
		SELECT t1.ledger_id, acc_head, t4.sub_group_name, balance_type, opening_balance, SUM(debit) dbAmt, SUM(credit) crAmt, CASE WHEN IF((opening_balance IS NULL OR opening_balance=0.00) AND (balance_type IS NULL OR balance_type = ''), 'Cr', balance_type)='Dr' THEN IFNULL((SUM(debit)- SUM(credit)), 0)+ IFNULL(opening_balance, 0) WHEN IF((opening_balance IS NULL OR opening_balance=0.00) AND (balance_type IS NULL OR balance_type = ''), 'Cr', balance_type)='Cr' THEN IFNULL((SUM(debit)- SUM(credit)), 0)- IFNULL(opening_balance, 0) END cBal
		FROM $this->compDb.comp_ledger_master t1
		LEFT JOIN $this->compDb.comp_party_opening_balance t2 ON t1.ledger_id=t2.ledger_id AND t2.finyr_id=$lastFinYr
		LEFT JOIN $this->compDb.comp_transactions t3 ON t1.ledger_id=t3.ledger_id AND t3.finyr_id=$lastFinYr
		LEFT JOIN $this->compDb.comp_sub_group t4 ON t1.acc_sub_group=t4.sub_group_id
		WHERE 1=1
		GROUP BY t1.ledger_id,t1.acc_head,balance_type, t2.opening_balance,acc_head,t4.sub_group_name,balance_type,opening_balance) t1
		LEFT JOIN $this->compDb.comp_financial_year t5 ON t5.finyr_id='$current_finyr'
		LEFT JOIN $this->compDb.comp_financial_year t7 ON t7.finyr_id='$lastFinYr'
		LEFT JOIN $this->compDb.comp_party_opening_balance t6 ON t6.ledger_id=t1.ledger_id and t6.finyr_id='$current_finyr'
		WHERE cBal!='0.00' and ABS(cBal) != ABS(IFNULL(t6.opening_balance,0))
		order by t1.acc_head asc")->result();
		}

		public function getLedgerName($ledger_id)
		{
			$this->setTables();
			$this->db->select("ledger_id,acc_head");
			$this->db->from("$this->compDb.comp_ledger_master");
			$this->db->where("ledger_id='$ledger_id'", NULL, false);
			$this->db->order_by("acc_head");
			$data = $this->db->get()->row();
			if (isset($data->acc_head) && $data->acc_head != '')
				return $data->acc_head;
			return '';
		}

		//Export In XML for TELLY ********************************** START ************************

		// INVOICE ******* START
		public function getSaleDetailForExport($data)
		{
			$where = "1=1 ";
			if ($data['date_from'] != '')
				$where .= "and t1.invoice_date>='$data[date_from]' ";

			if ($data['date_to'] != '')
				$where .= "and t1.invoice_date<='$data[date_to]' ";

			$this->setTables();
			$this->db->select("t1.sale_id,'$this->finyrId' as finyr_id,t1.invoice_no,date_format(invoice_date, '%Y%m%d') as invDate,t11.acc_head,t1.ledger_id,t1.acc_head_gstin,t1.acc_head_state_code, t2.state_name,'Sales' voucher_type,'India' country,t1.acc_head_address,t1.ship_to_name,t1.ship_to_state_code,t5.state_name as shipto_state,t1.ship_to_gstin,
		DATE_FORMAT(t1.cr_date,'%d-%b-%Y at %H:%i') as cr_time,concat(t6.client_firstname,' ',t6.client_lastname) created_by,
		t12.acc_head tx_acc_head,(t3.credit-t3.debit) as amt,t3.ledger_id as tx_ledger_id, t4.tax_ledger,t4.tax_percentage");
			$this->db->from("$this->saleMasterTable t1");
			$this->db->join("acc_state_master t2", "t1.acc_head_state_code=t2.state_code", "left");
			$this->db->join("acc_state_master t5", "t1.ship_to_state_code=t5.state_code", "left");
			$this->db->join("$this->compDb.comp_transactions t3", "t3.finyr_id='$this->finyrId' and book_type='IN' and t3.ref_no=t1.sale_id", 'left');
			$this->db->join("$this->compDb.comp_ledger_master t4", 't3.ledger_id=t4.ledger_id');
			$this->db->join("acc_client_master t6", "t1.cr_usr=t6.client_id", "left");
			$this->db->join("$this->compDb.comp_ledger_master t11", "t1.ledger_id=t11.ledger_id", 'left');
			$this->db->join("$this->compDb.comp_ledger_master t12", "t3.ledger_id=t12.ledger_id", 'left');
			$this->db->where($where, NULL, FALSE);
			$this->db->order_by("t1.invoice_date asc,(case when t1.acc_head=t3.acc_desc then '1' else '0' end) desc");
			$dbArray = $this->db->get()->result_array();
			$invoiceArray = array();
			$ledgerArray = array();
			foreach ($dbArray as $key => $value) {
				$invoiceArray[$value['sale_id']][] = $value;
				$ledgerArray[] = $value['tx_ledger_id'];
			}
			return array('invoiceArray' => $invoiceArray, 'ledgerArray' => $ledgerArray);
		}

		// Cancel Invoice
		public function getCancelSaleDetailForExport($data)
		{
			$where = "1=1 and t1.status = 0 ";
			if ($data['date_from'] != '')
				$where .= "and t1.invoice_date>='$data[date_from]' ";

			if ($data['date_to'] != '')
				$where .= "and t1.invoice_date<='$data[date_to]' ";

			$this->setTables();
			$this->db->select("t1.status,t1.sale_id,'$this->finyrId' as finyr_id,t1.invoice_no, date_format(invoice_date, '%Y%m%d') as invDate, t1.ledger_id, t1.acc_head_gstin, t1.acc_head_state_code, t2.state_name, 'Sales' voucher_type, 'India' country, t1.acc_head_address, t1.ship_to_name, t1.ship_to_state_code, t5.state_name as shipto_state, t1.ship_to_gstin, DATE_FORMAT(t1.cr_date, '%d-%b-%Y at %H:%i') as cr_time, concat(t6.client_firstname, ' ', t6.client_lastname) as amt");
			$this->db->from("$this->saleMasterTable t1");
			$this->db->join("acc_state_master t2", "t1.acc_head_state_code=t2.state_code", "left");
			$this->db->join("acc_state_master t5", "t1.ship_to_state_code=t5.state_code", "left");
			$this->db->join("acc_client_master t6", "t1.cr_usr=t6.client_id", "left");
			$this->db->where($where, NULL, FALSE);
			$this->db->order_by("t1.invoice_date asc");
			$dbArray = $this->db->get()->result_array();
			$cancelinvoiceArray = array();
			foreach ($dbArray as $key => $value) {
				$cancelinvoiceArray[$value['sale_id']][] = $value;
			}
			return array('cancelinvoiceArray' => $cancelinvoiceArray);
		}

		// Invoice return
		public function getSaleReturnDetailForExport($data)
		{
			$where = "1=1 ";
			if ($data['date_from'] != '')
				$where .= "and t1.invrtn_date>='$data[date_from]' ";

			if ($data['date_to'] != '')
				$where .= "and t1.invrtn_date<='$data[date_to]' ";

			$this->setTables();
			$this->db->select("t1.invrtn_id sale_id,t1.invoice_no as sale_inv_no,'$this->finyrId' as finyr_id,t1.invrtn_id invoice_no,date_format(invrtn_date, '%Y%m%d') as invDate,date_format(t1.invoice_date, '%Y%m%d') as saleDate,t11.acc_head acc_head,t1.ledger_id,t7.acc_head_gstin,t7.acc_head_state_code, t2.state_name,'Sales' voucher_type,'India' country,t7.acc_head_address,t7.ship_to_name,t7.ship_to_state_code,t5.state_name as shipto_state,t7.ship_to_gstin,
		DATE_FORMAT(t1.cr_date,'%d-%b-%Y at %H:%i') as cr_time,concat(t6.client_firstname,' ',t6.client_lastname) created_by,
		t12.acc_head tx_acc_head,(t3.credit-t3.debit) as amt,t3.ledger_id as tx_ledger_id, t4.tax_ledger,t4.tax_percentage");
			$this->db->from("$this->invrtnMasterTable t1");
			$this->db->join("$this->saleMasterTable t7", "t1.invoice_no=t7.invoice_no", "left");
			$this->db->join("acc_state_master t2", "t7.acc_head_state_code=t2.state_code", "left");
			$this->db->join("acc_state_master t5", "t7.ship_to_state_code=t5.state_code", "left");
			$this->db->join("$this->compDb.comp_transactions t3", "t3.finyr_id='$this->finyrId' and book_type='IR' and t3.ref_no=t1.invrtn_id", 'left');
			$this->db->join("$this->compDb.comp_ledger_master t4", 't3.ledger_id=t4.ledger_id');
			$this->db->join("acc_client_master t6", "t1.cr_usr=t6.client_id", "left");
			$this->db->join("$this->compDb.comp_ledger_master t11", "t1.ledger_id=t11.ledger_id", 'left');
			$this->db->join("$this->compDb.comp_ledger_master t12", "t3.ledger_id=t12.ledger_id", 'left');

			$this->db->where($where, NULL, FALSE);
			$this->db->order_by("t1.invrtn_date asc,(case when t1.acc_head=t3.acc_desc then '1' else '0' end) desc");
			$dbArray = $this->db->get()->result_array();
			$invoiceArray = array();
			$ledgerArray = array();
			foreach ($dbArray as $key => $value) {
				$invoiceArray[$value['sale_id']][] = $value;
				$ledgerArray[] = $value['tx_ledger_id'];
			}
			return array('invoiceArray' => $invoiceArray, 'ledgerArray' => $ledgerArray);
		}

		// INcome Debit Note
		public function getIncomeDebitNoteDetailForExport($data)
		{
			$where = "1=1 ";
			if ($data['date_from'] != '')
				$where .= "and t1.voucher_date>='$data[date_from]' ";

			if ($data['date_to'] != '')
				$where .= "and t1.voucher_date<='$data[date_to]' ";

			$this->setTables();
			$this->db->select("t1.inc_id sale_id,t1.invoice_no as sale_inv_no,'$this->finyrId' as finyr_id,t1.inc_id invoice_no,date_format(voucher_date, '%Y%m%d') as invDate,date_format(t7.invoice_date, '%Y%m%d') as saleDate,t11.acc_head acc_head,t1.acc_head_id ledger_id,
		concat(t11.add1,if(t11.add2!='',concat(', ',t11.add2),''),if(t11.city!='',concat(', ',t11.city),''),if(t2.state_name!='',concat(', ',t2.state_name),''),if(t11.country!='',concat(', ',t11.country),''),if(t11.pincode!='',concat(' - ',t11.pincode),'')) as acc_head_address
		, t11.gstin acc_head_gstin,t11.state acc_head_state_code, t2.state_name,'Debit Note' voucher_type,'India' country,t7.ship_to_name,t7.ship_to_state_code,t5.state_name as shipto_state,t7.ship_to_gstin,
		DATE_FORMAT(t1.cr_date,'%d-%b-%Y at %H:%i') as cr_time,concat(t6.client_firstname,' ',t6.client_lastname) created_by,
		t12.acc_head tx_acc_head,(t3.credit-t3.debit) as amt,t3.ledger_id as tx_ledger_id, t4.tax_ledger,t4.tax_percentage");
			$this->db->from("$this->compDb.comp_inc_master_$this->finyrId t1");
			$this->db->join("$this->saleMasterTable t7", "t1.invoice_no=t7.invoice_no", "left");

			$this->db->join("acc_state_master t5", "t7.ship_to_state_code=t5.state_code", "left");
			$this->db->join("$this->compDb.comp_transactions t3", "t3.finyr_id='$this->finyrId' and book_type='IC' and t3.ref_no=t1.inc_id", 'left');
			$this->db->join("$this->compDb.comp_ledger_master t4", 't3.ledger_id=t4.ledger_id');
			$this->db->join("acc_client_master t6", "t1.cr_usr=t6.client_id", "left");
			$this->db->join("$this->compDb.comp_ledger_master t11", "t1.acc_head_id=t11.ledger_id", 'left');
			$this->db->join("$this->compDb.comp_ledger_master t12", "t3.ledger_id=t12.ledger_id", 'left');
			$this->db->join("acc_state_master t2", "t11.state=t2.state_code", "left");

			$this->db->where($where, NULL, FALSE);
			$this->db->order_by("t1.voucher_date asc, (case when t1.acc_head=t3.acc_desc then '1' else '0' end) desc");
			$dbArray = $this->db->get()->result_array();
			$invoiceArray = array();
			$ledgerArray = array();
			foreach ($dbArray as $key => $value) {
				$invoiceArray[$value['sale_id']][] = $value;
				$ledgerArray[] = $value['tx_ledger_id'];
			}
			return array('invoiceArray' => $invoiceArray, 'ledgerArray' => $ledgerArray);
		}

		// PURCHASE *******************START
		public function getPurchaseDetailForExport($data)
		{
			$where = "1=1 ";
			if ($data['date_from'] != '')
				$where .= "and t1.invoice_date>='$data[date_from]' ";

			if ($data['date_to'] != '')
				$where .= "and t1.invoice_date<='$data[date_to]' ";

			$this->setTables();
			$this->db->select("t1.purchase_id id,'$this->finyrId' as finyr_id,t1.invoice_no,date_format(invoice_date, '%Y%m%d') as invDate,t11.acc_head,t1.ledger_id,t7.gstin acc_head_gstin,t1.acc_head_state_code, t2.state_name,'India' country,t1.acc_address_textarea acc_head_address,
		t8.name ship_to_name,t9.state ship_to_state_code,t5.state_name as shipto_state,case when t9.gstin!='' then t9.gstin else t8.gstin end ship_to_gstin,concat(t9.address,', ',t9.city,', ',t5.state_name,', - ',t9.pincode,'India') as shipto_address,t9.email as shipto_email,

		DATE_FORMAT(t1.cr_date,'%d-%b-%Y at %H:%i') as cr_time,concat(t6.client_firstname,' ',t6.client_lastname) created_by,
		t12.acc_head tx_acc_head,(t3.credit-t3.debit) as amt,t3.ledger_id as tx_ledger_id, t4.tax_ledger,t4.tax_percentage");
			$this->db->from("$this->purchaseMasterTable t1");
			$this->db->join("acc_state_master t2", "t1.acc_head_state_code=t2.state_code", "left");

			$this->db->join("$this->compDb.comp_ledger_master t7", 't1.ledger_id=t7.ledger_id');
			$this->db->join("$this->compDb.comp_transactions t3", "t3.finyr_id='$this->finyrId' and book_type='PU' and t3.ref_no=t1.purchase_id", 'left');
			$this->db->join("$this->compDb.comp_ledger_master t4", 't3.ledger_id=t4.ledger_id');

			$this->db->join("acc_client_master t6", "t1.cr_usr=t6.client_id", "left");
			$this->db->join("acc_client_company t8", "t8.comp_id=$this->compId", "left");
			$this->db->join("$this->compDb.comp_branch_master t9", "t9.branch_id=t1.branch_id", "left");
			$this->db->join("acc_state_master t5", "t9.state=t5.state_code", "left");

			$this->db->join("$this->compDb.comp_ledger_master t11", "t1.ledger_id=t11.ledger_id", 'left');
			$this->db->join("$this->compDb.comp_ledger_master t12", "t3.ledger_id=t12.ledger_id", 'left');

			$this->db->where($where, NULL, FALSE);
			$this->db->order_by("t1.invoice_date asc, (case when t1.acc_head=t12.acc_head then '1' else '0' end) desc");
			$dbArray = $this->db->get()->result_array();
			$invoiceArray = array();
			$ledgerArray = array();
			foreach ($dbArray as $key => $value) {
				$invoiceArray[$value['id']][] = $value;
				$ledgerArray[] = $value['tx_ledger_id'];
			}
			return array('invoiceArray' => $invoiceArray, 'ledgerArray' => $ledgerArray);
		}

		// PURCHASE RETURN *******************START
		public function getPurchaseRtnDetailForExport($data)
		{
			$where = "1=1 ";
			if ($data['date_from'] != '')
				$where .= "and t1.prtn_date>='$data[date_from]' ";

			if ($data['date_to'] != '')
				$where .= "and t1.prtn_date<='$data[date_to]' ";

			$this->setTables();
			$this->db->select("t1.prtn_id id,'$this->finyrId' as finyr_id,t1.prtn_id as invoice_no,date_format(t1.prtn_date, '%Y%m%d') as invDate,date_format(t1.invoice_date, '%Y%m%d') as purDate,t11.acc_head,t1.ledger_id,t7.gstin acc_head_gstin,t1.acc_head_state_code, t2.state_name,'India' country,t10.acc_address_textarea acc_head_address,t1.invoice_no as puinv_no,
		t8.name ship_to_name,t9.state ship_to_state_code,t5.state_name as shipto_state,case when t9.gstin!='' then t9.gstin else t8.gstin end ship_to_gstin,concat(t9.address,', ',t9.city,', ',t5.state_name,', - ',t9.pincode,'India') as shipto_address,t9.email as shipto_email,

		DATE_FORMAT(t1.cr_date,'%d-%b-%Y at %H:%i') as cr_time,concat(t6.client_firstname,' ',t6.client_lastname) created_by,
		t12.acc_head tx_acc_head,(t3.credit-t3.debit) as amt,t3.ledger_id as tx_ledger_id, t4.tax_ledger,t4.tax_percentage");
			$this->db->from("$this->prtnMasterTable t1");
			$this->db->join("$this->purchaseMasterTable t10", 't1.invoice_no=t10.invoice_no', 'left');
			$this->db->join("acc_state_master t2", "t1.acc_head_state_code=t2.state_code", "left");

			$this->db->join("$this->compDb.comp_ledger_master t7", 't1.ledger_id=t7.ledger_id');
			$this->db->join("$this->compDb.comp_transactions t3", "t3.finyr_id='$this->finyrId' and book_type='PR' and t3.ref_no=t1.prtn_id", 'left');
			$this->db->join("$this->compDb.comp_ledger_master t4", 't3.ledger_id=t4.ledger_id');

			$this->db->join("acc_client_master t6", "t1.cr_usr=t6.client_id", "left");
			$this->db->join("acc_client_company t8", "t8.comp_id=$this->compId", "left");
			$this->db->join("$this->compDb.comp_branch_master t9", "t9.branch_id=t1.branch_id", "left");
			$this->db->join("acc_state_master t5", "t9.state=t5.state_code", "left");

			$this->db->join("$this->compDb.comp_ledger_master t11", "t1.ledger_id=t11.ledger_id", 'left');
			$this->db->join("$this->compDb.comp_ledger_master t12", "t3.ledger_id=t12.ledger_id", 'left');

			$this->db->where($where, NULL, FALSE);
			$this->db->order_by("t1.prtn_date asc, (case when t1.acc_head=t3.acc_desc then '1' else '0' end) desc");
			$dbArray = $this->db->get()->result_array();
			$invoiceArray = array();
			$ledgerArray = array();
			foreach ($dbArray as $key => $value) {
				$invoiceArray[$value['id']][] = $value;
				$ledgerArray[] = $value['tx_ledger_id'];
			}
			return array('invoiceArray' => $invoiceArray, 'ledgerArray' => $ledgerArray);
		}

		// EXPENSE/ DEBIT NOTE *******************START
		public function getExpenseCreditNoteDetailForExport($data)
		{
			$where = "1=1 ";
			if ($data['date_from'] != '')
				$where .= "and t1.voucher_date>='$data[date_from]' ";

			if ($data['date_to'] != '')
				$where .= "and t1.voucher_date<='$data[date_to]' ";

			$this->setTables();
			$this->db->select("t1.exp_id id,'$this->finyrId' as finyr_id,t1.exp_id as invoice_no,date_format(t1.voucher_date, '%Y%m%d') as invDate,date_format(t10.invoice_date, '%Y%m%d') as purDate,t11.acc_head,t1.acc_head_id ledger_id,t7.gstin acc_head_gstin,t10.acc_head_state_code, t2.state_name,'India' country,t10.acc_address_textarea acc_head_address,t1.invoice_no as puinv_no,
		t8.name ship_to_name,t9.state ship_to_state_code,t5.state_name as shipto_state,case when t9.gstin!='' then t9.gstin else t8.gstin end ship_to_gstin,concat(t9.address,', ',t9.city,', ',t5.state_name,', - ',t9.pincode,'India') as shipto_address,t9.email as shipto_email,
		DATE_FORMAT(t1.cr_date,'%d-%b-%Y at %H:%i') as cr_time,concat(t6.client_firstname,' ',t6.client_lastname) created_by,
		t12.acc_head tx_acc_head,(t3.credit-t3.debit) as amt,t3.ledger_id as tx_ledger_id, t4.tax_ledger,t4.tax_percentage");
			$this->db->from("$this->compDb.comp_exp_master_$this->finyrId t1");
			$this->db->join("$this->purchaseMasterTable t10", 't1.invoice_no=t10.invoice_no', 'left');
			$this->db->join("acc_state_master t2", "t10.acc_head_state_code=t2.state_code", "left");
			$this->db->join("$this->compDb.comp_ledger_master t7", 't1.acc_head_id=t7.ledger_id');
			$this->db->join("$this->compDb.comp_transactions t3", "t3.finyr_id='$this->finyrId' and book_type='EX' and t3.ref_no=t1.exp_id", 'left');
			$this->db->join("$this->compDb.comp_ledger_master t4", 't3.ledger_id=t4.ledger_id');
			$this->db->join("acc_client_master t6", "t1.cr_usr=t6.client_id", "left");
			$this->db->join("acc_client_company t8", "t8.comp_id=$this->compId", "left");
			$this->db->join("$this->compDb.comp_branch_master t9", "t9.branch_id=t1.branch_id", "left");
			$this->db->join("acc_state_master t5", "t9.state=t5.state_code", "left");
			$this->db->join("$this->compDb.comp_ledger_master t11", "t1.acc_head_id=t11.ledger_id", 'left');
			$this->db->join("$this->compDb.comp_ledger_master t12", "t3.ledger_id=t12.ledger_id", 'left');

			$this->db->where($where, NULL, FALSE);
			$this->db->order_by('t1.voucher_date asc');
			$this->db->order_by("(case when t1.acc_head=t3.acc_desc then '1' else '0' end) desc");
			$dbArray = $this->db->get()->result_array();
			$invoiceArray = array();
			$ledgerArray = array();
			foreach ($dbArray as $key => $value) {
				$invoiceArray[$value['id']][] = $value;
				$ledgerArray[] = $value['tx_ledger_id'];
			}
			return array('invoiceArray' => $invoiceArray, 'ledgerArray' => $ledgerArray);
		}

		// VOUCHER  *******************START
		public function getVoucherDetailDetailForExport($data, $type)
		{
			if ($type == 'br') {
				$book_type = "BR";
				$id = "bank_receipt_id";
				$subid = "br_id";
				$table1 = "$this->compDb.comp_ve_bankreceipt_$this->finyrId";
				$table2 = "$this->compDb.comp_ve_bankreceipt_list_$this->finyrId";
			} elseif ($type == 'cr') {
				$book_type = "CR";
				$id = "cash_receipt_id";
				$subid = "cr_id";
				$table1 = "$this->compDb.comp_ve_cashreceipt_$this->finyrId";
				$table2 = "$this->compDb.comp_ve_cashreceipt_list_$this->finyrId";
			} elseif ($type == 'bp') {
				$book_type = "BP";
				$id = "bank_payment_id";
				$subid = "bp_id";
				$table1 = "$this->compDb.comp_ve_bankpayment_$this->finyrId";
				$table2 = "$this->compDb.comp_ve_bankpayment_list_$this->finyrId";
			} elseif ($type == 'cp') {
				$book_type = "CP";
				$id = "cash_payment_id";
				$subid = "cp_id";
				$table1 = "$this->compDb.comp_ve_cashpayment_$this->finyrId";
				$table2 = "$this->compDb.comp_ve_cashpayment_list_$this->finyrId";
			} elseif ($type == 'jb') {
				$book_type = "JB";
				$id = "journal_book_id";
				$subid = "jb_id";
				$table1 = "$this->compDb.comp_ve_journalbook_$this->finyrId";
				$table2 = "$this->compDb.comp_ve_journalbook_list_$this->finyrId";
			}
			$where = "1=1 ";
			if ($data['date_from'] != '')
				$where .= "and t1.voucher_date>='$data[date_from]' ";

			if ($data['date_to'] != '')
				$where .= "and t1.voucher_date<='$data[date_to]' ";

			$this->db->from("$table1 t1");
			$this->db->join("$this->compDb.comp_transactions t3", "t3.finyr_id='$this->finyrId' and book_type='$book_type' and t3.ref_no=t1.$id", 'left');
			$this->db->join("$this->compDb.comp_ledger_master t4", 't3.ledger_id=t4.ledger_id', 'left');
			$this->db->join("acc_client_master t6", "t1.cr_usr=t6.client_id", "left");

			$this->setTables();
			if ($type == 'jb') {
				$this->db->select("t1.$id as id,'$this->finyrId' as finyr_id,t1.$id voucher_no,date_format(voucher_date, '%Y%m%d') as invDate,
		DATE_FORMAT(t1.cr_date,'%d-%b-%Y at %H:%i') as cr_time,concat(t6.client_firstname,' ',t6.client_lastname) created_by,
		t11.acc_head,t3.ledger_id as acc_head_id, t4.acc_head tx_acc_head,(t3.credit-t3.debit) as amt,t3.ledger_id as tx_ledger_id, t4.tax_ledger,t4.tax_percentage");
				$this->db->join("$this->compDb.comp_ledger_master t11", "t3.ledger_id=t11.ledger_id", 'left');
			} else {
				$this->db->select("t1.$id as id,'$this->finyrId' as finyr_id,t1.$id voucher_no,date_format(voucher_date, '%Y%m%d') as invDate,
			DATE_FORMAT(t1.cr_date,'%d-%b-%Y at %H:%i') as cr_time,concat(t6.client_firstname,' ',t6.client_lastname) created_by,
			t11.acc_head,t2.acc_head_id, t4.acc_head tx_acc_head,(t3.credit-t3.debit) as amt,t3.ledger_id as tx_ledger_id, t4.tax_ledger,t4.tax_percentage");

				$this->db->join("$table2 t2", "t1.$id=t2.$subid", 'left');
				$this->db->join("$this->compDb.comp_ledger_master t11", "t2.acc_head_id=t11.ledger_id", 'left');
			}
			$this->db->where($where, NULL, FALSE);
			$this->db->order_by('t1.voucher_date asc');


			$dbArray = $this->db->get()->result_array();
			$invoiceArray = array();
			$ledgerArray = array();
			foreach ($dbArray as $key => $value) {
				$invoiceArray[$value['id']][] = $value;
				$ledgerArray[] = $value['tx_ledger_id'];
			}
			return array('invoiceArray' => $invoiceArray, 'ledgerArray' => $ledgerArray);
		}

		public function getLedgerMasterForExport($ledgerId)
		{
			$this->db->select("t1.*,date_format(t1.cr_date,'%Y%m%d') as createdOn,date_format(t1.md_date,'%Y%m%d') as modifiedOn,case when t2.balance_type='dr' then ifnull(t2.opening_balance,0)*-1 else ifnull(t2.opening_balance,0) end as opening_bal,concat(t3.client_firstname,' ',t3.client_lastname) created_by,concat(t4.client_firstname,' ',t4.client_lastname) modified_by,t5.state_name,
		concat(t1.add1,if(t1.add2!='',concat(', ',t1.add2),''),if(t1.city!='',concat(', ',t1.city),''),if(t5.state_name!='',concat(', ',t5.state_name),''),if(t1.country!='',concat(', ',t1.country),''),if(t1.pincode!='',concat(' - ',t1.pincode),'')) as address
		,t6.sub_group_name,
		t7.gstin as add_gstin,t7.branch_name as add_branch_name,t7.id as address_id,concat(t7.branch_name,', ',t7.address1,if(t7.address2!='',concat(', ',t7.address2),''),if(t7.city!='',concat(', ',t7.city),''),if(t8.state_name!='',concat(', ',t8.state_name),''),if(t7.country!='',concat(', ',t7.country),''),if(t7.pincode!='',concat(' - ',t7.pincode),'')) as multi_address,t8.state_name multi_state,case when t1.acc_head like '%cgst%' then 'Central Tax' when t1.acc_head like '%sgst%' then 'State Tax' when t1.acc_head like '%igst%' then 'Integrated Tax' when t1.acc_head like '%cess%' then 'Cess' else '' end as tax_ledger_type
		");
			$this->db->where_in('t1.ledger_id', $ledgerId);
			$this->db->from("$this->compDb.comp_ledger_master t1");
			$this->db->join("$this->compDb.comp_party_opening_balance t2", "t1.ledger_id=t2.ledger_id and finyr_id='$this->finyrId'", 'left');
			$this->db->join("acc_client_master t3", "t1.cr_usr=t3.client_id", "left");
			$this->db->join("acc_client_master t4", "t1.md_usr=t4.client_id", "left");
			$this->db->join("acc_state_master t5", "t1.state=t5.state_code", "left");
			$this->db->join("$this->compDb.comp_sub_group t6", "t6.sub_group_id=t1.acc_sub_group", 'left');
			$this->db->join("$this->compDb.comp_ledger_address t7", "t7.ledger_master_id=t1.ledger_id", 'left');
			$this->db->join("acc_state_master t8", "t7.state=t8.state_code", "left");
			$dbArray = $this->db->get()->result_array();
			$ledgerArray = array();
			$groupArray = array();
			foreach ($dbArray as $key => $value) {
				$ledgerArray[$value['ledger_id']] = $value;
				if ($value['multi_address'] != ', , Other, India' && $value['address_id'] != '') {
					$ledgerArray[$value['ledger_id']]['multiAddressArray'][$value['address_id']] = array('address_id' => $value['address_id'], 'address' => $value['multi_address'], 'state' => $value['multi_state'], 'gstin' => $value['add_gstin'], 'branch' => $value['add_branch_name']);
				}
				$groupArray[$value['acc_sub_group']] = $value['sub_group_name'];
			}
			return array('ledgerArray' => $ledgerArray, 'groupArray' => $groupArray);
		}

		public function getInvoiceDebitCreditNoteDetailForExport($data)
		{
			$where = "1=1 ";

			if ($data['date_from'] != '')
				$where .= "and t1.note_date>='$data[date_from]' ";

			if ($data['date_to'] != '')
				$where .= "and t1.note_date<='$data[date_to]' ";

			$this->setTables();

			$this->db->select("t1.note_id sale_id,t1.note_type,t1.invoice_no as sale_inv_no,t1.invoice_no as puinv_no,'$this->finyrId' as finyr_id,t1.note_id invoice_no,date_format(note_date, '%Y%m%d') as invDate,date_format(t1.invoice_date, '%Y%m%d') as saleDate,date_format(t1.invoice_date, '%Y%m%d') purDate,t11.acc_head acc_head,t1.ledger_id,t7.acc_head_gstin,t7.acc_head_state_code, t2.state_name,'Debi/Credit' voucher_type,'India' country,t7.acc_head_address,t7.ship_to_name,t7.ship_to_state_code,t5.state_name as shipto_state,t7.ship_to_gstin,
		DATE_FORMAT(t1.cr_date,'%d-%b-%Y at %H:%i') as cr_time,concat(t6.client_firstname,' ',t6.client_lastname) created_by,
		t12.acc_head tx_acc_head,(t3.credit-t3.debit) as amt,t3.ledger_id as tx_ledger_id, t4.tax_ledger,t4.tax_percentage,t8.name ship_to_name,t9.state ship_to_state_code,t5.state_name as shipto_state,case when t9.gstin!='' then t9.gstin else t8.gstin end ship_to_gstin,concat(t9.address,', ',t9.city,', ',t5.state_name,', - ',t9.pincode,'India') as shipto_address,t9.email as shipto_email,");
			$this->db->from("$this->compDb.comp_inv_debitnote_master_$this->finyrId t1");
			$this->db->join("$this->saleMasterTable t7", "t1.invoice_no=t7.invoice_no", "left");
			$this->db->join("acc_state_master t2", "t7.acc_head_state_code=t2.state_code", "left");
			$this->db->join("acc_state_master t5", "t7.ship_to_state_code=t5.state_code", "left");
			$this->db->join("$this->compDb.comp_transactions t3", "t3.finyr_id='$this->finyrId' and book_type='DN' and t3.ref_no=t1.note_id", 'left');
			$this->db->join("$this->compDb.comp_ledger_master t4", 't3.ledger_id=t4.ledger_id');
			$this->db->join("acc_client_master t6", "t1.cr_usr=t6.client_id", "left");
			$this->db->join("acc_client_company t8", "t8.comp_id=$this->compId", "left");
			$this->db->join("$this->compDb.comp_branch_master t9", "t9.branch_id=t1.branch_id", "left");
			$this->db->join("$this->compDb.comp_ledger_master t11", "t1.ledger_id=t11.ledger_id", 'left');
			$this->db->join("$this->compDb.comp_ledger_master t12", "t3.ledger_id=t12.ledger_id", 'left');

			$this->db->where($where, NULL, FALSE);
			$this->db->order_by("t1.note_date asc,(case when t1.acc_head=t3.acc_desc then '1' else '0' end) desc");
			$dbArray = $this->db->get()->result_array();

			$debitNoteArray = $creditNoteArray = array();
			$ledgerArray = array();
			foreach ($dbArray as $key => $value) {
				if ($value['note_type'] == 'dr') {
					$debitNoteArray[$value['sale_id']][] = $value;
				} else {
					$creditNoteArray[$value['sale_id']][] = $value;
				}
				$ledgerArray[] = $value['tx_ledger_id'];
			}
			return array('debitNoteArray' => $debitNoteArray, 'creditNoteArray' => $creditNoteArray, 'ledgerArray' => $ledgerArray);
		}

		// Export In XML for TELLY ********************************** ENDS ************************


		public function getEmailLogRefName($where = '')
		{
			$this->setTables();
			return $this->db->select("DISTINCT (reference_name) as name")->from("$this->compDb.comp_notification_log")->get()->result_array();
		}

		public function getInvoiceAmountForEmail($invoiceId)
		{
			$this->setTables();
			$this->db->where('t1.sale_id', $invoiceId);
			$this->db->select("t1.total_net_amount,t1.sale_id,t1.invoice_no,t1.branch_id");
			$this->db->from("$this->compDb.comp_sale_master_$this->finyrId t1");
			return $this->db->get()->row();
		}

		public function getsaleAmountForEmail($saleId)
		{
			$this->setTables();
			$this->db->where('t1.so_id', $saleId);
			$this->db->select("t1.total_net_amount,t1.so_id,t1.branch_id");
			$this->db->from("$this->compDb.comp_so_master_$this->finyrId t1");
			return $this->db->get()->row();
		}

		public function getleadAmountForEmail($leadId)
		{
			$this->setTables();
			$this->db->where('t1.lead_id', $leadId);
			$this->db->select("t1.total_net_amount,t1.lead_id,t1.branch_id");
			$this->db->from("$this->compDb.comp_lead_master t1");
			return $this->db->get()->row();
		}

		function getAllHostNames()
		{
			$dbData = $this->db->select("host_name")->get("acc_domain_setting")->result_array();
			$hostArray = array();
			if (count($dbData) > 0) {
				$hostArray = array_column($dbData, 'host_name');
			}
			return $hostArray;
		}

		public function getMenuParentList($where = '')
		{
			$this->db->where("menu_parent='0'");
			if ($where != '')
				$this->db->where($where);
			$this->db->order_by('menu_priority', 'asc');
			return $this->db->select('menu_id,menu_name')->get('acc_client_menu_master')->result_array();
		}
		public function getDyanmicFormList($where = '')
		{
			$this->setTables();
			$this->db->select("name,id,key");
			if ($where != '')
				$this->db->where($where);
			$this->db->order_by('name', 'asc');
			return $this->db->get("$this->compDb.comp_dynamic_form")->result_array();
		}

		public function getFieldGroupDropdown($where)
		{
			$this->db->distinct();
			$this->db->select('id, name');
			$this->setTables();
			if ($where != '')
				$this->db->where($where, null, false);
			return $this->db->get("$this->compDb.comp_dynamicform_field_group")->result_array();
		}

		public function getSystemFormMappingList($where = '')
		{
			$this->setTables();
			$this->db->select("id, name, table_name, is_finyr");
			if ($where != '')
				$this->db->where($where, null, false);
			$this->db->order_by('name', 'asc');
			return $this->db->get("acc_dynamic_form_mapping")->result_array();
		}

		public function getdynamicFormStatusList($queryArray = array(), $field = false)
		{
			$this->setTables();
			if ($field == false) {
				$this->db->select("id, status_text, status_color");
			}
			return $this->db->order_by('status_text', 'asc')->get_where("$this->compDb.comp_dynamicform_status_list", $queryArray)->result_array();
		}

		public function getChallanInItemSrNoList($branch_id, $item_id, $srno, $ref_id = null, $finyr_id = '')
		{
			$this->setTables();
			$finyr_id = ($finyr_id != '') ? $finyr_id : $this->fx->clientFinYr;
			$data['old_srno'] = $data['new_srno'] = array();
			if ($ref_id != '') {
				$this->db->select("t3.item_id,t2.branch_id,t3.item_name,t3.item_srno,t3.purchased_frm,t3.stock_status,t3.issued_type,t3.issued_to");
				$this->db->from("$this->compDb.comp_challanin_itemlist t1");
				$this->db->join("$this->compDb.comp_challanin_master t2", "t1.challanin_id=t2.challanin_id", 'inner');
				$this->db->join("$this->compDb.comp_item_srno t3", "t1.item_id='$item_id' AND t2.branch_id=t3.branch_id AND finyr_id='$finyr_id' AND FIND_IN_SET (t3.item_srno,t1.sr_no)");
				$this->db->where("t1.challanin_id='$ref_id' AND t1.item_id='$item_id'", null, false);
				$data['old_srno'] = $this->db->get()->result_array();
				$this->db->reset_query();
				$oldsrNo = [];
				if (count($data['old_srno']) > 0 && $data['old_srno'][0]['branch_id'] == $branch_id) {
					$oldsrNo = array_column($data['old_srno'], 'item_srno');
				}
				$srno = array_diff($srno, $oldsrNo);
			}
			if (count($srno) > 0) {
				$this->db->select("t1.item_id,t1.branch_id,t1.item_name,t1.item_srno,t1.purchased_frm,t1.stock_status,t1.issued_type,t1.issued_to");
				$this->db->where("finyr_id", $finyr_id);
				$this->db->where("branch_id", $branch_id);
				$this->db->where("item_id", $item_id);
				$this->db->where_in("item_srno", $srno);
				$this->db->from("$this->compDb.comp_item_srno t1");
				$data['new_srno'] = $this->db->get()->result_array();
			}
			return $data;
		}

		public function getPurchaseItemSrNoList($branch_id, $item_id, $srno, $ref_id = null, $finyr_id = '')
		{
			$this->setTables();
			$finyr_id = ($finyr_id != '') ? $finyr_id : $this->fx->clientFinYr;
			$data['old_srno'] = $data['new_srno'] = array();
			if ($ref_id != '') {
				$this->db->select("t3.item_id,t2.branch_id,t3.item_name,t3.item_srno,t3.purchased_frm,t3.stock_status,t3.issued_type,t3.issued_to,t4.challanin_id");
				$this->db->from("$this->compDb.comp_purchase_itemlist_$finyr_id t1");
				$this->db->join("$this->compDb.comp_purchase_master_$finyr_id t2", "t1.purchase_id=t2.purchase_id", 'inner');
				$this->db->join("$this->compDb.comp_item_srno t3", "t1.item_id='$item_id' AND t2.branch_id=t3.branch_id AND finyr_id='$finyr_id' AND FIND_IN_SET (t3.item_srno,t1.sr_no)");
				$this->db->join("$this->compDb.comp_challanin_master t4", "t4.challanin_id=t2.challanin_no", 'left');
				$this->db->where("t1.purchase_id='$ref_id' AND t1.item_id='$item_id'", null, false);
				$data['old_srno'] = $this->db->get()->result_array();
				// echo $this->db->last_query();die;
				$this->db->reset_query();
				$oldsrNo = [];
				if (count($data['old_srno']) > 0 && $data['old_srno'][0]['branch_id'] == $branch_id) {
					$oldsrNo = array_column($data['old_srno'], 'item_srno');
				}
				//fx::pr($oldsrNo, 1);
				$srno = array_diff($srno, $oldsrNo);
			}
			if (count($srno) > 0) {
				$this->db->select("t1.item_id,t1.branch_id,t1.item_name,t1.item_srno,t1.purchased_frm,t1.stock_status,t1.issued_type,t1.issued_to");
				$this->db->where("finyr_id", $finyr_id);
				$this->db->where("branch_id", $branch_id);
				$this->db->where("item_id", $item_id);
				$this->db->where_in("item_srno", $srno);
				$this->db->from("$this->compDb.comp_item_srno t1");
				$data['new_srno'] = $this->db->get()->result_array();
			}
			return $data;
		}

		public function getPurchaseReturnItemSrNoList($branch_id, $item_id, $srno, $ref_id = null, $finyr_id = '')
		{
			$this->setTables();
			$finyr_id = ($finyr_id != '') ? $finyr_id : $this->fx->clientFinYr;
			$data['old_srno'] = $data['new_srno'] = array();
			if ($ref_id != '') {
				$this->db->select("t3.item_id,t2.branch_id,t3.item_name,t3.item_srno,t3.purchased_frm,t3.stock_status,t3.issued_type,t3.issued_to");
				$this->db->from("$this->compDb.comp_prtn_itemlist_$finyr_id t1");
				$this->db->join("$this->compDb.comp_prtn_master_$finyr_id t2", "t1.prtn_id=t2.prtn_id", 'inner');
				$this->db->join("$this->compDb.comp_item_srno t3", "t1.item_id='$item_id' AND t2.branch_id=t3.branch_id AND finyr_id='$finyr_id' AND FIND_IN_SET (t3.item_srno,t1.sr_no)");
				$this->db->where("t1.prtn_id='$ref_id' AND t1.item_id='$item_id'", null, false);
				$data['old_srno'] = $this->db->get()->result_array();
				$this->db->reset_query();
				$oldsrNo = [];
				if (count($data['old_srno']) > 0 && $data['old_srno'][0]['branch_id'] == $branch_id) {
					$oldsrNo = array_column($data['old_srno'], 'item_srno');
				}
				$srno = array_diff($srno, $oldsrNo);
			}
			if (count($srno) > 0) {
				$this->db->select("t1.item_id,t1.branch_id,t1.item_name,t1.item_srno,t1.purchased_frm,t1.stock_status,t1.issued_type,t1.issued_to");
				$this->db->where("finyr_id", $finyr_id);
				$this->db->where("branch_id", $branch_id);
				$this->db->where("item_id", $item_id);
				$this->db->where_in("item_srno", $srno);
				$this->db->from("$this->compDb.comp_item_srno t1");
				$data['new_srno'] = $this->db->get()->result_array();
			}
			return $data;
		}

		public function getChallanItemSrNoList($branch_id, $item_id, $srno, $ref_id = null, $finyr_id = '')
		{
			$this->setTables();
			$finyr_id = ($finyr_id != '') ? $finyr_id : $this->fx->clientFinYr;
			$data['old_srno'] = $data['new_srno'] = array();
			if ($ref_id != '') {
				$this->db->select("t3.item_id,t2.branch_id,t3.item_name,t3.item_srno,t3.purchased_frm,t3.stock_status,t3.issued_type,t3.issued_to");
				$this->db->from("$this->compDb.comp_challan_itemlist t1");
				$this->db->join("$this->compDb.comp_challan_master t2", "t1.challan_id=t2.challan_id", 'inner');
				$this->db->join("$this->compDb.comp_item_srno t3", "t1.item_id='$item_id' AND t2.branch_id=t3.branch_id AND finyr_id='$finyr_id' AND FIND_IN_SET (t3.item_srno,t1.sr_no)");
				$this->db->where("t1.challan_id='$ref_id' AND t1.item_id='$item_id'", null, false);
				$data['old_srno'] = $this->db->get()->result_array();
				$this->db->reset_query();
				$oldsrNo = [];
				if (count($data['old_srno']) > 0 && $data['old_srno'][0]['branch_id'] == $branch_id) {
					$oldsrNo = array_column($data['old_srno'], 'item_srno');
				}
				$srno = array_diff($srno, $oldsrNo);
			}
			if (count($srno) > 0) {
				$this->db->select("t1.item_id,t1.branch_id,t1.item_name,t1.item_srno,t1.purchased_frm,t1.stock_status,t1.issued_type,t1.issued_to");
				$this->db->where("finyr_id", $finyr_id);
				$this->db->where("branch_id", $branch_id);
				$this->db->where("item_id", $item_id);
				$this->db->where_in("item_srno", $srno);
				$this->db->from("$this->compDb.comp_item_srno t1");
				$data['new_srno'] = $this->db->get()->result_array();
			}
			return $data;
		}

		public function getChallanReturnItemSrNoList($branch_id, $item_id, $srno, $ref_id = null, $finyr_id = '')
		{
			$this->setTables();
			$finyr_id = ($finyr_id != '') ? $finyr_id : $this->fx->clientFinYr;
			$data['old_srno'] = $data['new_srno'] = array();
			if ($ref_id != '') {
				$this->db->select("t3.item_id,t2.branch_id,t3.item_name,t3.item_srno,t3.purchased_frm,t3.stock_status,t3.issued_type,t3.issued_to");
				$this->db->from("$this->compDb.comp_challanrtn_itemlist t1");
				$this->db->join("$this->compDb.comp_challanrtn_master t2", "t1.challan_rtn_id=t2.challan_rtn_id", 'inner');
				$this->db->join("$this->compDb.comp_item_srno t3", "t1.item_id='$item_id' AND t2.branch_id=t3.branch_id AND finyr_id='$finyr_id' AND FIND_IN_SET (t3.item_srno,t1.sr_no)");
				$this->db->where("t1.challan_rtn_id='$ref_id' AND t1.item_id='$item_id'", null, false);
				$data['old_srno'] = $this->db->get()->result_array();
				$this->db->reset_query();
				$oldsrNo = [];
				if (count($data['old_srno']) > 0 && $data['old_srno'][0]['branch_id'] == $branch_id) {
					$oldsrNo = array_column($data['old_srno'], 'item_srno');
				}
				$srno = array_diff($srno, $oldsrNo);
			}
			if (count($srno) > 0) {
				$this->db->select("t1.item_id,t1.branch_id,t1.item_name,t1.item_srno,t1.purchased_frm,t1.stock_status,t1.issued_type,t1.issued_to");
				$this->db->where("finyr_id", $finyr_id);
				$this->db->where("branch_id", $branch_id);
				$this->db->where("item_id", $item_id);
				$this->db->where_in("item_srno", $srno);
				$this->db->from("$this->compDb.comp_item_srno t1");
				$data['new_srno'] = $this->db->get()->result_array();
			}
			return $data;
		}

		public function getInvoiceItemSrNoList($branch_id, $item_id, $srno, $ref_id = null, $finyr_id = '')
		{
			$this->setTables();
			$finyr_id = ($finyr_id != '') ? $finyr_id : $this->fx->clientFinYr;
			$data['old_srno'] = $data['new_srno'] = array();
			if ($ref_id != '') {
				$this->db->select("t3.item_id,t2.branch_id,t3.item_name,t3.item_srno,t3.purchased_frm,t3.stock_status,t3.issued_type,t3.issued_to,t4.challanin_id");
				$this->db->from("$this->compDb.comp_purchase_itemlist_$finyr_id t1");
				$this->db->join("$this->compDb.comp_purchase_master_$finyr_id t2", "t1.purchase_id=t2.purchase_id", 'inner');
				$this->db->join("$this->compDb.comp_item_srno t3", "t1.item_id='$item_id' AND t2.branch_id=t3.branch_id AND finyr_id='$finyr_id' AND FIND_IN_SET (t3.item_srno,t1.sr_no)");
				$this->db->join("$this->compDb.comp_challanin_master t4", "t4.challanin_id=t2.challanin_no", 'left');
				$this->db->where("t1.purchase_id='$ref_id' AND t1.item_id='$item_id'", null, false);
				$data['old_srno'] = $this->db->get()->result_array();
				$this->db->reset_query();
				$oldsrNo = [];
				if (count($data['old_srno']) > 0 && $data['old_srno'][0]['branch_id'] == $branch_id) {
					$oldsrNo = array_column($data['old_srno'], 'item_srno');
				}
				$srno = array_diff($srno, $oldsrNo);
			}
			if (count($srno) > 0) {
				$this->db->select("t1.item_id,t1.branch_id,t1.item_name,t1.item_srno,t1.purchased_frm,t1.stock_status,t1.issued_type,t1.issued_to");
				$this->db->where("finyr_id", $finyr_id);
				$this->db->where("branch_id", $branch_id);
				$this->db->where("item_id", $item_id);
				$this->db->where_in("item_srno", $srno);
				$this->db->from("$this->compDb.comp_item_srno t1");
				$data['new_srno'] = $this->db->get()->result_array();
			}
			return $data;
		}

		public function getDyanmicFormDataList($where = '')
		{
			$this->setTables();
			$this->db->select("id, form_name");
			if ($where != '')
				$this->db->where($where, NULL, False);
			$this->db->order_by('form_name', 'asc');
			$this->db->from("$this->compDb.comp_dynamicform");
			return $this->db->get()->result_array();
		}

		function getClientCompanyDetailForEInvoice($where)
		{
			$this->db->where($where, null, false);
			$this->db->from("acc_client_company t1");
			return $this->db->get()->row();
		}

		function addEinvoiceJobRequest($data, $where = '')
		{
			$this->setTables();
			if ($where != '') {
				$this->db->where($where, null, false);
				$this->db->update("$this->compDb.comp_sale_einv_request", $data);
				return $this->db->affected_rows();
			}
			$this->db->insert("$this->compDb.comp_sale_einv_request", $data);
			return $this->db->insert_id();
		}

		function getEinvoiceRequestData($where)
		{
			$this->setTables();
			$this->db->where($where, null, false);
			$this->db->from("$this->compDb.comp_sale_einv_request t1");
			return $this->db->get()->result();
		}

		function addEinvoiceRequestLogs($data)
		{
			$this->setTables();
			return $this->db->insert_batch("$this->compDb.comp_sale_einv_request_log", $data);
		}

		public function getEinvoiceRequestDataList($where = NULL, $pageNo, $limit, $order_by = '')
		{
			$this->setTables();
			$offset = ($pageNo - 1) * $limit;

			$this->db->select("t1.*,t1.id as req_id,date_format(cr_time,'%d-%m-%Y %r') as createTime,
			,date_format(start_time,'%d-%m-%Y %r') as startTime,
			,date_format(end_time,'%d-%m-%Y %r') as endTime,
			,date_format(last_comment_time,'%d-%m-%Y %r') as lastCommentTime,
			,concat(t3.client_firstname,' ',t3.client_lastname) as crUsr");
			$this->db->from("$this->compDb.comp_sale_einv_request t1");
			$this->db->join("acc_client_master t3", "t1.cr_usr=t3.client_id", "left");

			$this->db->where($where, NULL, false);

			$tempdb = clone $this->db;
			$count = $tempdb->count_all_results();
			($order_by != NULL) ? $this->db->order_by("$order_by", null, FALSE) : $this->db->order_by('t1.id desc');
			$this->db->limit($limit, $offset);
			$data = $this->db->get();

			return (object) array('count' => $count, 'data' => $data->result());
		}

		public function getEinvoiceRequestLogsList($where = NULL, $pageNo, $limit, $order_by = '')
		{
			$this->setTables();
			$offset = ($pageNo - 1) * $limit;
			$this->db->select("t1.*");
			$this->db->from("$this->compDb.comp_sale_einv_request_log t1");
			$this->db->where($where, NULL, false);
			$tempdb = clone $this->db;
			$count = $tempdb->count_all_results();
			($order_by != NULL) ? $this->db->order_by("$order_by", null, FALSE) : $this->db->order_by('t1.id desc');
			$this->db->limit($limit, $offset);
			$data = $this->db->get();
			return (object) array('count' => $count, 'data' => $data->result());
		}

		function updateTotalRequestCountFromLog($req_id)
		{
			$this->setTables();
			$successCount = @$this->db->select("count(id) as total_count")->where("req_id='$req_id' and (type='cancel_irn' OR type='inv' OR type='credit_note') and success_status=1", null, false)->get("$this->compDb.comp_sale_einv_request_log")->row()->total_count;
			$successCount = (int)$successCount;
			$this->db->set("processed_count", "total_inv", false);
			$this->db->set("success_count", "$successCount", false);
			$this->db->set("failure_count", "total_inv-$successCount", false);
			$this->db->where("id='$req_id'", null, false);
			return $this->db->update("$this->compDb.comp_sale_einv_request");
		}

		function getClientChildTreeByClientId($client_id)
		{
			return $this->db->query("SELECT GROUP_CONCAT(DISTINCT _ids) as clients_ids
								FROM(
								SELECT
								@ids AS _ids,
								(
								SELECT @ids := GROUP_CONCAT(client_id)
								FROM acc_client_master
								WHERE FIND_IN_SET(manager_id, @ids)
								) AS cids,
								@l := @l+1 AS LEVEL
								FROM acc_client_master,
								(
								SELECT @ids :='$client_id', @l := 0) b
								WHERE @ids IS NOT NULL
								) client_id, acc_client_master DATA
								WHERE FIND_IN_SET(DATA.client_id, client_id._ids)
								")->row()->clients_ids;
		}

		public function getClientGroupNamesList($where)
		{
			$this->db->select("Distinct(group_name) group_name");
			$this->db->from("acc_client_master");
			$this->db->where($where, NULL, false);
			$this->db->order_by("group_name");
			return $this->db->get()->result();
		}

		public function getClientNameAndGroupList($where, $val)
		{
			$this->db->select("Distinct(group_name) name,'1' as type");
			$this->db->from("acc_client_master");
			$this->db->where($where, NULL, false);
			$this->db->where("group_name like '%$val%'", NULL, false);

			$qry[] = $this->db->get_compiled_select();

			$this->db->select("Distinct(concat(client_firstname,' ',client_lastname)) name,'2' as type");
			$this->db->from("acc_client_master");
			$this->db->where($where, NULL, false);
			$this->db->where("concat(client_firstname,' ',client_lastname) like '%$val%' or (client_firstname like '%$val%' or client_lastname like '%$val%')", NULL, false);

			$qry[] = $this->db->get_compiled_select();
			return $this->db->query("select * from (" . implode(' union all ', $qry) . ") t1 order by type asc,name asc")->result();
		}

		function getClientGroupList($where)
		{
			$this->db->select("Distinct(group_name) name");
			$this->db->from("acc_client_master");
			$this->db->where($where, NULL, false);
			return $this->db->get()->result_array();
		}

		public function gettaskStatusList($where = '')
		{
			$this->setTables();
			$this->db->select("id,title,color_code,status_type");
			$this->db->from("$this->compDb.comp_task_status");
			if ($where != '')
				$this->db->where($where, NULL, false);
			$this->db->order_by("title");
			return $this->db->get()->result_array();
		}

		public function getTaskPriorityList($where = '')
		{
			$this->setTables();
			$this->db->select("id,title,color_code");
			$this->db->from("$this->compDb.comp_task_priority");
			if ($where != '')
				$this->db->where($where, NULL, false);
			$this->db->order_by("title");
			return $this->db->get()->result_array();
		}
		public function getTaskTypeList($where = '')
		{
			$this->setTables();
			$this->db->select("id,title");
			$this->db->from("$this->compDb.comp_task_type");
			if ($where != '')
				$this->db->where($where, NULL, false);
			$this->db->order_by("title");
			return $this->db->get()->result_array();
		}

		public function getDefaultPaymentGateway($where = '')
		{
			$this->setTables();
			$this->db->select("t1.*");
			$this->db->from("$this->compDb.comp_payment_gateway t1");
			$this->db->where($where, NULL, false);
			$this->db->order_by("is_default desc,t1.id desc");
			return $this->db->get()->row();
		}

		public function checkTemplateLogicCondition($where = '', $ref_type = 'lead', $ref_id = '')
		{
			$this->setTables();
			$this->db->query("SELECT GROUP_CONCAT(CONCAT('case when ', conditions, ' then concat(',template_id,','',',template_for,''')',' end AS col_',template_id,'_',id, '')) INTO @SQL FROM $this->compDb.comp_template_logic_query where $where");
			//$this -> db -> query("select @SQL");
			$this->db->query("SET @SQL = IF((SUBSTRING(@SQL, -1) = ','), SUBSTRING(@SQL, 1, LENGTH(@SQL)-1), @SQL);");

			if ($ref_type == 'lead') {
				$this->db->query("SET @SQL = CONCAT('SELECT ', @SQL, ' FROM $this->compDb.comp_lead_master t1 where lead_id=$ref_id'); ");
			}
			$dbArray = $this->db->query("select @SQL")->row_array();

			if (empty($dbArray['@SQL'])) {
				return;
			}
			$this->db->query("PREPARE stmt FROM @SQL;");
			$data = $this->db->query("EXECUTE stmt")->row_array();
			$this->db->query("DEALLOCATE PREPARE stmt;");
			return $data;
		}

		// METHODS FOR GSTR2A STARTS HERE
		public function GetClientIdForGstr2a($where)
		{
			// $this->db->select("ledger_id,acc_head");
			// $this->db->from("$this->compDb.comp_ledger_master");
			// $this->db->where('gstin', $where);
			// return $this->db->get()->row();

			$this->db->select("l2.ledger_id,l2.acc_head");
			$this->db->from("$this->compDb.comp_ledger_address l1");
			$this->db->join("$this->compDb.comp_ledger_master l2", "l1.ledger_master_id = l2.ledger_id");
			$this->db->where('l1.gstin', $where);
			return $this->db->get()->row();
		}

		public function GetPurchaseIdForGstr2a($where = '')
		{
			$find = array("/", "-", ":", "\\", " ");
			$replace = array('');
			$inv_no = str_replace($find, $replace, $where['invoice_no']);

			// $this->db->select("purchase_id");
			// $this->db->from("$this->purchaseMasterTable");
			// $this->db->where("REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(invoice_no, '(/)', ''), '(-)', ''),'(\)',''),' ',''),':','')", $inv_no);
			// $this->db->where("acc_gstin", $where['acc_gstin']);
			// $this->db->where("invoice_date", $where['invoice_date']);
			// $this->db->where("total_net_amount", $where['amt']);
			// $qry =  $this->db->get()->row();

			return $this->db->query("SELECT `purchase_id`
			FROM $this->purchaseMasterTable
			WHERE REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(invoice_no, '/', ''), '-', ''),'',''),' ',''),':',''),'\\\','') = '$inv_no'
			AND `acc_gstin` = '$where[acc_gstin]'
			AND `invoice_date` = '$where[invoice_date]'
			AND `total_net_amount` = '$where[amt]'")->row();
		}

		public function UpdatePrchseIdInvNoMisMatch()
		{
			$this->db->query("UPDATE $this->compDb.comp_gstr2a g1
			JOIN $this->purchaseMasterTable p ON 
			g1.gstin = p.acc_gstin AND
			g1.amt = p.total_net_amount AND
			g1.inv_date = p.invoice_date AND
			REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(g1.inv_no, '/', ''), '-', ''),'',''),' ',''),':',''),'\\\','') != REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(p.invoice_no, '/', ''), '-', ''),'',''),' ',''),':',''),'\\\','')
			SET g1.purchase_id = p.purchase_id,g1.remark = 'INVOICE NO MISMATCH'
			WHERE g1.purchase_id IS  NULL OR g1.purchase_id = 0");
			return $this->db->affected_rows();
		}
		public function UpdatePrchseIdInvDateMisMatch()
		{
			$this->db->query("UPDATE $this->compDb.comp_gstr2a g1
			JOIN $this->purchaseMasterTable p ON 
			g1.gstin = p.acc_gstin AND
			g1.amt = p.total_net_amount AND
			g1.inv_date != p.invoice_date AND
			REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(g1.inv_no, '/', ''), '-', ''),'',''),' ',''),':',''),'\\\','') = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(p.invoice_no, '/', ''), '-', ''),'',''),' ',''),':',''),'\\\','')
			SET g1.purchase_id = p.purchase_id,g1.remark = 'INVOICE DATE MISMATCH'
			WHERE g1.purchase_id IS  NULL OR g1.purchase_id = 0");
			return $this->db->affected_rows();
		}
		public function UpdatePrchseIdGstinMisMatch()
		{
			$this->db->query("UPDATE $this->compDb.comp_gstr2a g1
			JOIN $this->purchaseMasterTable p ON 
			g1.gstin != p.acc_gstin AND
			g1.amt = p.total_net_amount AND
			g1.inv_date = p.invoice_date AND
			REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(g1.inv_no, '/', ''), '-', ''),'',''),' ',''),':',''),'\\\','') = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(p.invoice_no, '/', ''), '-', ''),'',''),' ',''),':',''),'\\\','')
			SET g1.purchase_id = p.purchase_id,g1.remark = 'GSTIN MISMATCH'
			WHERE g1.purchase_id IS  NULL OR g1.purchase_id = 0");
			return $this->db->affected_rows();
		}
		public function UpdatePrchseIdAmtMisMatch()
		{
			$this->db->query("UPDATE $this->compDb.comp_gstr2a g1
			JOIN $this->purchaseMasterTable p ON 
			g1.gstin = p.acc_gstin AND
			g1.amt != p.total_net_amount AND
			g1.inv_date = p.invoice_date AND
			REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(g1.inv_no, '/', ''), '-', ''),'',''),' ',''),':',''),'\\\','') = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(p.invoice_no, '/', ''), '-', ''),'',''),' ',''),':',''),'\\\','')
			SET g1.purchase_id = p.purchase_id,g1.remark = 'AMOUNT MISMATCH'  
			WHERE g1.purchase_id IS  NULL OR g1.purchase_id = 0");
			return  $this->db->affected_rows();
		}

		public function InsertGstr2aData($insertArr, $mnthYear)
		{
			// $mnthYear = "$mnthYear";
			// $this->db->where('date_format(inv_date,"%Y-%m")',$mnthYear,NULL,FALSE);
			// $this->db->delete("$this->compDb.comp_gstr2a");
			return $this->db->insert_batch("$this->compDb.comp_gstr2a", $insertArr);
		}
		public function DeleteGstr2aData($mnthYear)
		{
			$mnthYear = "$mnthYear";
			$this->db->where('date_format(inv_date,"%Y-%m")', $mnthYear, NULL, FALSE);
			$this->db->delete("$this->compDb.comp_gstr2a");
		}
		// METHODS FOR GSTR2A ENDS HERE

		public function reqLogDetails($where)
		{
			$this->setTables();
			$this->db->where($where, null, false);
			$this->db->from("$this->compDb.comp_sale_einv_request");
			return $this->db->get()->row();
		}

		public function saleEinvDetails($where)
		{
			$this->setTables();
			$this->db->where($where, null, false);
			$this->db->from("$this->compDb.comp_sale_master_$this->finyrId");
			return  $this->db->get()->row();
		}

		public function branchEivDetails($where)
		{
			$this->setTables();
			$this->db->where($where, null, false);
			$this->db->from("$this->compDb.comp_branch_master");
			return  $this->db->get()->row();
		}

		public function invoiceRtnEinvDetails($where)
		{
			$this->setTables();
			$this->db->where($where, null, false);
			$this->db->from("$this->compDb.comp_invrtn_master_$this->finyrId");
			return  $this->db->get()->row();
		}

		function updateBranchDetails($data, $where)
		{
			$this->db->where($where, null, false);
			return $this->db->update("$this->compDb.comp_branch_master", $data);
		}

		public function partnerExists_check($partnerName)
		{
			$this->setTables();
			$this->db->where('acc_head', $partnerName);
			//$this->db->where('t2.sub_group_name', 'Partner ');
			$this->db->where("(t2.sub_group_name = 'Partner Admin' or t2.sub_group_name = 'Partner Crm')", null, false);
			$this->db->select("t1.*");
			$this->db->from("$this->compDb.comp_ledger_master as t1");
			$this->db->join("$this->compDb.comp_sub_group as t2", "t2.sub_group_id = t1.acc_sub_group", "left");
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return true;
			} else {
				return false;
			}
		}

		public function resellerExists_check($resellerName)
		{
			$this->setTables();
			$this->db->where('acc_head', $resellerName);
			// $this->db->where('t2.sub_group_name', 'Reseller');
			$this->db->where("(t2.sub_group_name = 'Reseller Admin' or t2.sub_group_name = 'Reseller Crm')", null, false);
			$this->db->select("t1.*");
			$this->db->from("$this->compDb.comp_ledger_master as t1");
			$this->db->join("$this->compDb.comp_sub_group as t2", "t2.sub_group_id = t1.acc_sub_group", "left");
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return true;
			} else {
				return false;
			}
		}

		public function getLedgerDetailForEinv($where)
		{
			$this->setTables();
			$this->db->select('t3.city,t3.pincode,t3.state');
			$this->db->from("acc_state_master as t1");
			$this->db->join("$this->compDb.comp_sale_master_$this->finyrId as t2", "t1.state_code = t2.acc_head_state_code", "left");
			$this->db->join("$this->compDb.comp_ledger_address as t3", "t3.ledger_master_id = t2.ledger_id", "left");
			$this->db->where($where, NULL, False);
			return $this->db->get()->row();
		}

		public function getLedgerDetailForEinvRtn($where)
		{
			$this->setTables();
			$this->db->select('t3.city,t3.pincode,t3.state');
			$this->db->from("acc_state_master as t1");
			$this->db->join("$this->compDb.comp_invrtn_master_$this->finyrId as t2", "t1.state_code = t2.acc_head_state_code", "left");
			$this->db->join("$this->compDb.comp_ledger_address as t3", "t3.ledger_master_id = t2.ledger_id", "left");
			$this->db->where($where, NULL, False);
			return $this->db->get()->row();
		}

		/*----------------------------- CHANGES FOR CUSTOM REPORT STARTS HERE-----------------------------*/

		public function getAllTableNames()
		{
			$db_name = $this->compDb;
			return $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '$db_name' order by table_name asc")->result_array();
		}

		function getAllTableColumns($table_name)
		{
			$db_name = $this->compDb;
			return $this->db->query("select * from information_schema.columns where table_schema = '$db_name' AND TABLE_NAME='$table_name' order by table_name,column_name")->result_array();
		}

		function getMultiTableColumns($table_names)
		{
			$db_name = $this->compDb;
			return $this->db->query("SELECT CONCAT(TABLE_NAME,'.',COLUMN_NAME) AS COLUMN_NAME FROM information_schema.columns WHERE table_schema = '$db_name' AND TABLE_NAME in ($table_names) ORDER BY table_name,column_name")->result_array();
		}

		function getCompanyDeatils($comp_id)
		{
			$this->db->select('map_api_key');
			$this->db->from('acc_client_company');
			$this->db->where('comp_id', $comp_id);
			return $this->db->get()->row();
		}

		/*----------------------------- CHANGES FOR CUSTOM REPORT ENDS HERE-----------------------------*/

		function getOldAmount($columnName, $tableName, $primaryColumnName, $id, $finyr)
		{
			$this->db->select($columnName);
			if ($finyr == 'true') {
				$this->db->from("" . $this->compDb . "." . $tableName . "_" . $this->finyrId . "");
			} else {
				$this->db->from("" . $this->compDb . "." . $tableName . "");
			}
			$this->db->where($primaryColumnName, $id);
			return $this->db->get()->row();
		}
	}

	?>
