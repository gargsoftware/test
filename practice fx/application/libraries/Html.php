<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 */
class html
{
	public function __construct()
	{
		$this->CI = &get_instance();
		$this->CI->load->model("Acc_Model");
	}

	public $cntTypeAr = array('0' => 'One Time', '1' => 'Monthly', '3' => 'Quarterly', '6' => 'Half Yearly', '12' => 'Yearly');

	public $nonExpInvoiceType = "'R','DE','SEWP','SEWOP'";
	public $expInvoiceType = "'WPAY','WOPAY'";
	public $ledgerRegistrationType = ['composition' => 'Composition', 'consumer' => 'Consumer', 'regular' => 'Regular', 'unregistered' => 'Unregistered'];
	public $ledgerPartyType = ['de' => 'Deemed Export', 'embassy' => 'Embassy/UN Body', 'sez' => 'Sez'];
	public $voucherType = ['cp' => 'CP', 'cr' => 'CR', 'bp' => 'BP', 'br' => 'BR', 'jb' => 'JB'];
	public $bookType = [
		'CP' => 'CP',
		'CR' => 'CR',
		'BP' => 'BP',
		'BR' => 'BR',
		'JB' => 'JB',
		'IN' => 'IN',
		'IR' => 'IR',
		'PU' => 'PU',
		'PR' => 'PR'
	];
	function lead_template_form_fileds()
	{
		return array_merge_recursive(array(
			'lead_date' => array('label' => 'Lead Date', 'name' => 'lead_date', 'data_type' => 'date', 'field_type' => 'date'),
			'agent_id[]' => array('label' => 'Account', 'name' => 'agent_id[]', 'data_type' => 'int', 'field_type' => 'multiselect'),
			'acc_head_state_code[]' => array('label' => 'State', 'name' => 'acc_head_state_code[]', 'data_type' => 'varchar', 'field_type' => 'multiselect'),
			'lead_category[]' => array('label' => 'Lead Category', 'name' => 'lead_category[]', 'data_type' => 'int', 'field_type' => 'multiselect'),
			'lead_type_id[]' => array('label' => 'Lead Type', 'name' => 'lead_type_id[]', 'data_type' => 'int', 'field_type' => 'multiselect'),
			'tags' => array('label' => 'Lead Tags', 'name' => 'tags', 'data_type' => 'varchar', 'field_type' => 'text'),
			'lead_source_id[]' => array('label' => 'Lead Source', 'name' => 'lead_source_id[]', 'data_type' => 'int', 'field_type' => 'multiselect'),
			'refer_by' => array('label' => 'Reference', 'name' => 'refer_by', 'data_type' => 'varchar', 'field_type' => 'text'),
			'lead_status_id[]' => array('label' => 'Lead Status', 'name' => 'lead_status_id[]', 'data_type' => 'int', 'field_type' => 'multiselect'),
			'notify_client_via[]' => array('label' => 'Client Notification', 'name' => 'notify_client_via[]', 'data_type' => 'varchar', 'field_type' => 'multiselect'),
			'pdf_title' => array('label' => 'PDF Title', 'name' => 'pdf_title', 'data_type' => 'varchar', 'field_type' => 'text')
		), $this->getSystemFormDynamicFieldsArr('lead'));
	}

	function contract_template_form_fileds()
	{
		return array(
			'branch_id[]' => array('label' => 'Branch', 'name' => 'branch_id[]', 'data_type' => 'int', 'field_type' => 'multiselect'),
			'contract_type[]' => array('label' => 'Contract Type', 'name' => 'contract_type[]', 'data_type' => 'int', 'field_type' => 'multiselect'),
			'sale_date' => array('label' => 'Sale Date', 'name' => 'sale_date', 'data_type' => 'date', 'field_type' => 'date'),
			'due_date' => array('label' => 'Contract Due Date', 'name' => 'due_date', 'data_type' => 'date', 'field_type' => 'date'),
			'end_date' => array('label' => 'Contract End Date', 'name' => 'end_date', 'data_type' => 'date', 'field_type' => 'date'),
			'DATEDIFF(CURRENT_DATE,due_date)[]' => array('label' => 'Days of Due Days', 'name' => 'DATEDIFF(CURRENT_DATE,due_date)[]', 'data_type' => 'int', 'field_type' => 'multiselect'),
			'DATEDIFF(CURRENT_DATE,due_date)>' => array('label' => 'After Due Date onwards', 'name' => 'DATEDIFF(CURRENT_DATE,due_date)>', 'data_type' => 'int', 'field_type' => 'checkbox'),
			'email_reminder' => array('label' => 'Email Reminder', 'name' => 'email_reminder', 'data_type' => 'int', 'field_type' => 'checkbox'),
			'sms_reminder' => array('label' => 'SMS Reminder', 'name' => 'sms_reminder', 'data_type' => 'int', 'field_type' => 'checkbox'),
			'whatsapp_reminder' => array('label' => 'Whatsapp Reminder', 'name' => 'whatsapp_reminder', 'data_type' => 'int', 'field_type' => 'checkbox'),
		);
	}

	function payment_reminder_template_form_fileds()
	{
		return array(
			'interval' => array('label' => 'Days Interval', 'name' => 'interval', 'data_type' => 'int', 'field_type' => 'select'),
		);
	}

	function payment_receipt_cash_form_fields()
	{
		return array(
			'branch_id[]' => array('label' => 'Branch', 'name' => 'branch_id[]', 'data_type' => 'int', 'field_type' => 'multiselect'),
			'voucher_no' => array('label' => 'Voucher No', 'name' => 'voucher_no', 'data_type' => 'varchar', 'field_type' => 'text'),
			'voucher_date' => array('label' => 'Voucher Date', 'name' => 'voucher_date', 'data_type' => 'date', 'field_type' => 'date'),
			'book_id[]' => array('label' => 'Acc Head', 'name' => 'book_id[]', 'data_type' => 'int', 'field_type' => 'multiselect')
		);
	}
	function payment_receipt_bank_form_fields()
	{
		return array(
			'branch_id[]' => array('label' => 'Branch', 'name' => 'branch_id[]', 'data_type' => 'int', 'field_type' => 'multiselect'),
			'voucher_no' => array('label' => 'Voucher No', 'name' => 'voucher_no', 'data_type' => 'varchar', 'field_type' => 'text'),
			'voucher_date' => array('label' => 'Voucher Date', 'name' => 'voucher_date', 'data_type' => 'date', 'field_type' => 'date'),
			'cheque_no' => array('label' => 'Cheque No', 'name' => 'cheque_no', 'data_type' => 'varchar', 'field_type' => 'text'),
			'cheque_date' => array('label' => 'Cheque Date', 'name' => 'cheque_date', 'data_type' => 'date', 'field_type' => 'date'),
			'book_id[]' => array('label' => 'Acc Head', 'name' => 'book_id[]', 'data_type' => 'int', 'field_type' => 'multiselect')
		);
	}
	function journal_book_form_fields()
	{
		return array(
			'branch_id[]' => array('label' => 'Branch', 'name' => 'branch_id[]', 'data_type' => 'int', 'field_type' => 'multiselect'),
			'voucher_no' => array('label' => 'Voucher No', 'name' => 'voucher_no', 'data_type' => 'varchar', 'field_type' => 'text'),
			'voucher_date' => array('label' => 'Voucher Date', 'name' => 'voucher_date', 'data_type' => 'date', 'field_type' => 'date'),
		);
	}


	public $htmlDataListStyle = array();
	public $einvoiceProcStatus = array(
		'1' => 'Request Added',
		'2' => 'Job Started',
		'3' => 'Job Terminated',
		'4' => 'Job Completed'
	);

	function getPaymentGatewayTypeDropdown($selected = '')
	{ ?>
		<option value="razorpay" <?php echo ($selected == '' || $selected == 'razorpay') ? 'selected' : ''; ?>>Razorpay</option>
	<?php
	}

	function getSalesTypeDropDown($selected = '')
	{
	?>
		<option value="R" <?php echo ($selected == 'R') ? 'selected' : ''; ?>>R</option>
		<option value="DE" <?php echo ($selected == 'DE') ? 'selected' : ''; ?>>DE</option>
		<option value="SEWP" <?php echo ($selected == 'SEWP') ? 'selected' : ''; ?>>SEWP</option>
		<option value="SEWOP" <?php echo ($selected == 'SEWOP') ? 'selected' : ''; ?>>SEWOP</option>
		<option value="WPAY" <?php echo ($selected == 'WPAY') ? 'selected' : ''; ?>>WPAY</option>
		<option value="WOPAY" <?php echo ($selected == 'WOPAY') ? 'selected' : ''; ?>>WOPAY</option>
		<?php
	}

	public function getProjectBarColor($value)
	{
		switch ($value) {
			case $value > 0 && $value <= 20:
				return '#fe3d3e';
				break;
			case $value > 20 && $value <= 60:
				return '#ffb627';
				break;
			case $value > 60:
				return '#62d03b';
				break;
			default:
				return '#62d03b';
				break;
		}
	}

	public function tableList($dataList, $recCount, $recToShow, $pageNo, $columns, $id, $edit = 0, $delete = 0)
	{
		$colSpan = count($columns) + 2;
		if (!empty($dataList) && count($dataList) > 0) {
			$i = 1;
			foreach ($dataList as $data) { ?>
				<tr>
					<td style="width:50px;"><?php echo $i + (($pageNo - 1) * $recToShow); ?></td>
					<?php foreach ($columns as $column) {
						//Add Link, Style ,Button,Div etc ****************************** Starts Here
						// array('column_name'=>array('linkString' => array('tagVal' => "<span style='color:#337ab7;cursor:pointer' onclick=\"getEditRec('##comp_id##');\">", 'replaceColumn' => array('comp_id'),'endString'=>'</span>'), 'tagString' => array('tagVal' => "style='color:#337ab7;cursor:pointer' onclick=\"getEditRec('##comp_id##');\" ", 'replaceColumn' => array('comp_id'))
						// ));
						//for one css text align right side
						// 'column_name'=>array('tagString' => array('tagVal' => "align='right'"))

						//New Code Starts Here
						$tagVal = $linkValBefore = $linkValAfter = '';
						if (count($this->htmlDataListStyle) > 0 && isset($this->htmlDataListStyle["$column"]) && count($this->htmlDataListStyle["$column"]) > 0) {
							if (isset($this->htmlDataListStyle["$column"]['tagString'])) {
								$tagVal = $this->htmlDataListStyle["$column"]['tagString']['tagVal'];
								if (isset($this->htmlDataListStyle["$column"]['tagString']['replaceColumn'])) {
									foreach ($this->htmlDataListStyle["$column"]['tagString']['replaceColumn'] as $tagArray) {
										$tagVal = str_replace("##$tagArray##", $data->$tagArray, $tagVal);
									}
								}
							}
							if (isset($this->htmlDataListStyle["$column"]['linkString'])) {
								$linkValBefore = $this->htmlDataListStyle["$column"]['linkString']['tagVal'];
								if (isset($this->htmlDataListStyle["$column"]['linkString']['replaceColumn'])) {
									foreach ($this->htmlDataListStyle["$column"]['linkString']['replaceColumn'] as $tagArray) {
										$linkValBefore = str_replace("##$tagArray##", $data->$tagArray, $linkValBefore);
									}
								}
								$linkValAfter = $this->htmlDataListStyle["$column"]['linkString']['endString'];
							}
						}
						// New Code Starts Here
						//Add Link, Style ,Button,Div etc ****************************** Ends Here

					?>
						<td <?php echo $tagVal; ?>> <?php echo $linkValBefore;
													if ($column == $id) {
														echo '#';
													}
													echo $data->$column;
													echo $linkValAfter; ?>

						</td>
					<?php } ?>
					<?php if ($id != NULL) { ?>
						<td style="white-space:nowrap; width:80px;">
							<?php if ($edit == 1) { ?>
								<a href="javascript:void(0)" class="btn btn-primary btn-xs" onClick="getEditRec('<?php echo $data->$id; ?>')"><i class="fa fa-pencil" aria-hidden="true"></i></a>
							<?php } else { ?>
								<a href="javascript:void(0)" class="btn btn-primary btn-xs disabled"><i class="fa fa-pencil" aria-hidden="true"></i></a>
							<?php } ?>
							<?php if ($delete == 1) { ?>
								<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="deleteRec('<?php echo $data->$id; ?>')"><i class="fa fa-trash" aria-hidden="true"></i></a>
							<?php } else { ?>
								<a href="javascript:void(0)" class="btn btn-danger btn-xs disabled"><i class="fa fa-trash" aria-hidden="true"></i></a>
							<?php } ?>
						</td>
					<?php } ?>
				</tr>
			<?php $i++;
			} ?>
			<tr>
				<td colspan="<?php echo $colSpan; ?>" style="background:#fff">
					<?php $this->pagination($recCount, $recToShow, $pageNo); ?>
				</td>
			</tr>
		<?php } else { ?>
			<tr>
				<td style="text-align:center;" colspan="<?php echo $colSpan; ?>">No Records Found</td>
			</tr>
		<?php }
	}

	public function pagination($recCount, $recToShow, $pageNo, $pageShow = 5, $type = 'AJAX')
	{
		$totalPage = ($recToShow > 0) ? ceil($recCount / $recToShow) : 1;
		if ($pageNo <= $pageShow) {
			$stPage = 1;
		} else {
			$stPage = $pageNo - $pageShow;
		}
		$endPage = $pageNo + $pageShow;
		if ($endPage >= $totalPage) {
			$endPage = $totalPage;
		}
		?>

		<ul class="pagination pagination-sm pull-left" style="display:block;" data-page="<?php echo $totalPage; ?>">
			<?php if ($pageNo != 1) { ?>
				<li onClick="pagination('F','<?php echo $type; ?>',this)"><a href="javascript:void(0)">First</a></li>
				<li onClick="pagination('P','<?php echo $type; ?>',this)"><a href="javascript:void(0)">Previous</a></li>
			<?php } ?>
			<?php for ($i = $stPage; $i <= $endPage; $i++) { ?>
				<li <?php if ($pageNo == $i) echo 'class="active"'; ?> onClick="pagination('<?php echo $i; ?>','<?php echo $type; ?>',this)"><a href="javascript:void(0)"><?php echo $i; ?></a></li>
			<?php } ?>
			<?php if ($pageNo != $totalPage) { ?>
				<li onClick="pagination('N','<?php echo $type; ?>',this)"><a href="javascript:void(0)">Next</a></li>
				<li onClick="pagination('L','<?php echo $type; ?>',this)"><a href="javascript:void(0)">Last</a></li>
			<?php } ?>
		</ul>

		<p class="pull-right" style="line-height:45px; margin:0; padding:0 10px;">
			Total Pages:<strong style="padding-right:15px;"> <?php echo $totalPage; ?></strong>
			Total Records: <strong> <?php echo $recCount; ?></strong></p>
		<?php
	}

	public function optionGen($dataList, $selected = '')
	{
		foreach ($dataList as $key => $val) { ?>
			<option value="<?php echo $key; ?>" <?php if ($key == $selected) {
													echo ' selected';
												} ?>><?php echo $val; ?></option>
		<?php
		}
	}
	public function contractTypeDropdown($selected = '')
	{ ?>
		<option value="" <?php echo ($selected == '') ? 'selected' : ''; ?>>Select</option>
		<?php
		foreach ($this->cntTypeAr as $key => $val) { ?>
			<option value="<?php echo $key; ?>" <?php if ($selected != '' && $key == $selected) {
													echo ' selected';
												} ?>><?php echo $val; ?></option>
		<?php
		}
	}

	public function selectList($dataList, $value, $option, $selected = '')
	{
		foreach ($dataList as $data) { ?>
			<option value="<?php echo $data->$value; ?>" <?php if ($data->$value == $selected) {
																echo ' selected';
															} ?>><?php echo $data->$option; ?></option>
		<?php
		}
	}
	public function getMetaDropdown($metaType, $selected = NULL, $where = NULL)
	{
		if ($where != NULL) $where = 'meta_type="' . $metaType . '" and ' . $where;
		else $where = 'meta_type="' . $metaType . '"';
		foreach ($this->CI->Acc_Model->getMetaTypeList($where) as $dropdown) { ?>
			<option value="<?php echo $dropdown->meta_id; ?>" <?php if ($dropdown->meta_id == $selected) echo ' selected'; ?>><?php echo $dropdown->meta_name; ?></option>
		<?php }
	}

	public function getStateDropdown($selected = NULL, $selArray = array())
	{
		foreach ($this->CI->Acc_Model->getStateList() as $dropdown) { ?>
			<option value="<?php echo $dropdown->state_code; ?>" <?php if ($dropdown->state_code == $selected || in_array($dropdown->state_code, $selArray) == true) echo ' selected'; ?>><?php echo $dropdown->state_name; ?></option>
		<?php }
	}

	public function getClientDropdown($status = NULL, $selected = NULL, $where = NULL)
	{
		$whereSt = "1=1 and parent_id=" . $this->CI->fx->clientAccessID . " and client_id!=" . $this->CI->fx->clientId;
		if ($status !== NULL) $whereSt .= " and status=" . $status;
		if ($where != NULL)  $whereSt .= " and " . $where;
		foreach ($this->CI->Acc_Model->getClientList($whereSt) as $dropdown) { ?>
			<option value="<?php echo $dropdown->client_id; ?>" <?php if ($dropdown->client_id == $selected) echo ' selected'; ?>><?php echo $dropdown->clientName; ?></option>
		<?php }
	}

	public function getClientDropdownWithParent($status = NULL, $selected = NULL, $where = NULL, $selectedArray = array())
	{
		$whereSt = "1=1 and (parent_id=" . $this->CI->fx->clientAccessID . " or client_id=" . $this->CI->fx->clientAccessID . ")";
		if ($status !== NULL) $whereSt .= " and status=" . $status;
		if ($where != NULL)  $whereSt .= " and " . $where;
		foreach ($this->CI->Acc_Model->getClientList($whereSt) as $dropdown) { ?>
			<option value="<?php echo $dropdown->client_id; ?>" <?php if ($dropdown->client_id == $selected || in_array($dropdown->client_id, $selectedArray)) echo ' selected'; ?>><?php echo $dropdown->clientName; ?></option>
			<?php }
	}

	public function getClientList($status = NULL, $selected = NULL, $where = NULL)
	{
		$whereSt = "1=1 and (parent_id='" . $this->CI->fx->clientAccessID . "' or client_id='" . $this->CI->fx->clientId . "')";
		if ($status !== NULL) $whereSt .= " and status=" . $status;
		if ($where != NULL)  $whereSt .= " and " . $where;
		return $this->CI->Acc_Model->getClientList($whereSt);
	}

	public function getCompanyDropdown($status = NULL, $selected = NULL, $where = NULL)
	{
		$whereSt = ($this->CI->fx->masterClient == 0) ? "1=1 and client_id=" . $this->CI->fx->clientId : "1=1 and sub_client_id=" . $this->CI->fx->clientId;
		if ($status !== NULL) $whereSt .= " and status=" . $status;
		if ($where != NULL)  $whereSt .= " and " . $where;
		$compList = $this->CI->Acc_Model->getCompanyList($whereSt);
		if (count($compList) > 0) {
			foreach ($compList as $dropdown) { ?>
				<option value="<?php echo $dropdown->comp_id; ?>" <?php if ($dropdown->comp_id == $selected) echo ' selected'; ?>><?php echo $dropdown->name; ?></option>
			<?php }
		} else { ?>
			<option value="">No Company Assigned to you</option>
		<?php
		}
	}

	public function getBranchDropdown($status = NULL, $selected = NULL, $where = NULL, $selectArr = array())
	{
		$whereSt = "1=1";
		if ($status !== NULL) $whereSt .= " and status=" . $status;
		if ($where != NULL)  $whereSt .= " and " . $where;

		$subUserBrnch = array();
		if ($this->CI->fx->masterClient != 0) {
			$subUserBrnch = array('sub_client_id' => $this->CI->fx->clientId, 'comp_id' => $this->CI->fx->clientCompId);
		}
		foreach ($this->CI->Acc_Model->getBranchList($whereSt, $this->CI->fx->clientCompDb, $subUserBrnch) as $dropdown) { ?>
			<option data-state_code="<?php echo $dropdown->state;  ?>" value="<?php echo $dropdown->branch_id; ?>" <?php if ($dropdown->branch_id == $selected || in_array($dropdown->branch_id, $selectArr)) echo ' selected'; ?>><?php echo $dropdown->branch_name; ?></option>
		<?php }
	}

	public function getInvoiceTypeDropdown($status = NULL, $selected = NULL, $where = NULL, $is_default_selected = false)
	{

		$whereSt = "1=1";
		if ($status !== NULL) $whereSt .= " and status=" . $status;
		if ($where != NULL)  $whereSt .= " and " . $where;
		foreach ($this->CI->Acc_Model->getInvoiceTypeList($whereSt, $this->CI->fx->clientCompDb) as $dropdown) { ?>
			<option data-is_default="<?php echo @$dropdown->is_default; ?>" value="<?php echo $dropdown->invoicetype_id; ?>" <?php if (($dropdown->invoicetype_id == $selected) or ($selected == '' and $is_default_selected == true and @$dropdown->is_default == 1)) echo ' selected'; ?>><?php echo $dropdown->invoice_type; ?></option>
		<?php }
	}

	public function getFinyrDropdown($db = NULL, $selected = NULL, $where = NULL)
	{
		$db = ($db != NULL) ? $db : $this->CI->fx->clientCompDb;
		$whereSt = "1=1";
		if ($where != NULL)  $whereSt .= " and " . $where;
		foreach ($this->CI->Acc_Model->getFinyrList($whereSt, $db) as $key => $dropdown) { ?>
			<option data-fy_index='<?php echo $key; ?>' value="<?php echo $dropdown->finyr_id; ?>" <?php if ($dropdown->finyr_id == $selected) echo ' selected'; ?>><?php echo $dropdown->finyr_name; ?></option>
		<?php }
	}

	public function getTaxDropdown($status = NULL, $selected = NULL, $where = NULL)
	{
		$whereSt = "1=1";
		if ($status !== NULL) $whereSt .= " and status=" . $status;
		if ($where != NULL)  $whereSt .= " and " . $where;
		foreach ($this->CI->Acc_Model->getTaxList($whereSt, $this->CI->fx->clientCompDb) as $dropdown) {
		?>
			<option value="<?php echo $dropdown->tax_id; ?>" <?php if ($dropdown->tax_id == $selected) echo ' selected'; ?>><?php echo $dropdown->tax_name; ?></option>
		<?php }
	}

	public function getTCSTaxDropdown($status = NULL, $selected = NULL, $where = NULL)
	{
		$whereSt = "1=1";
		if ($status !== NULL) $whereSt .= " and status=" . $status;
		if ($where != NULL)  $whereSt .= " and " . $where;
		foreach ($this->CI->Acc_Model->getTCSTaxList($whereSt, $this->CI->fx->clientCompDb) as $dropdown) {
			$ledger_name = str_replace(array("'", '"'), array(' ', ' '), $dropdown->acc_head);
		?>
			<option value="<?php echo $dropdown->tax_id; ?>" data-ledger_id="<?php echo $dropdown->ledger_id; ?>" data-ledger_name="<?php echo $dropdown->acc_head; ?>" <?php if ($dropdown->tax_id == $selected) echo ' selected'; ?>><?php echo $dropdown->tax_name; ?></option>
		<?php }
	}



	public function getItemCateDropdown($status = NULL, $selected = NULL, $where = NULL)
	{
		$whereSt = "1=1";
		if ($status !== NULL) $whereSt .= " and status=" . $status;
		if ($where != NULL)  $whereSt .= " and " . $where;
		foreach ($this->CI->Acc_Model->getItemCateList($whereSt, $this->CI->fx->clientCompDb) as $dropdown) { ?>
			<option value="<?php echo $dropdown->cat_id; ?>" <?php if ($dropdown->cat_id == $selected) echo ' selected'; ?>><?php echo $dropdown->cat_name; ?></option>
		<?php }
	}

	public function getItemMakeDropdown($status = NULL, $selected = NULL, $where = NULL)
	{
		$whereSt = "1=1";
		if ($status !== NULL) $whereSt .= " and status=" . $status;
		if ($where != NULL)  $whereSt .= " and " . $where;
		foreach ($this->CI->Acc_Model->getItemMakeList($whereSt, $this->CI->fx->clientCompDb) as $dropdown) { ?>
			<option value="<?php echo $dropdown->make_id; ?>" <?php if ($dropdown->make_id == $selected) echo ' selected'; ?>><?php echo $dropdown->make_name; ?></option>
		<?php }
	}

	public function getItemDropdown($status = NULL, $selected = NULL, $where = NULL)
	{
		$whereSt = "1=1";
		if ($status !== NULL) $whereSt .= " and status=" . $status;
		if ($where != NULL)  $whereSt .= " and " . $where;
		foreach ($this->CI->Acc_Model->getItemList($whereSt, $this->CI->fx->clientCompDb) as $dropdown) { ?>
			<option value="<?php echo $dropdown->item_id; ?>" <?php if ($dropdown->item_id == $selected) echo ' selected'; ?>><?php echo $dropdown->item_name; ?></option>
		<?php }
	}

	public function getGroupDropdown($selected = NULL, $where = NULL)
	{
		$whereSt = "1=1";
		if ($where != NULL)  $whereSt .= " and " . $where;
		foreach ($this->CI->Acc_Model->getGroupList($whereSt, $this->CI->fx->clientCompDb) as $dropdown) { ?>
			<option value="<?php echo $dropdown->group_id; ?>" <?php if ($dropdown->group_id == $selected) echo ' selected'; ?>><?php echo $dropdown->group_name; ?></option>
		<?php }
	}

	public function getSubGroupDropdown($selected = NULL, $where = NULL)
	{
		$whereSt = "1=1";
		if ($where != NULL)  $whereSt .= " and " . $where;
		foreach ($this->CI->Acc_Model->getSubGroupList($whereSt, $this->CI->fx->clientCompDb) as $dropdown) { ?>
			<option value="<?php echo $dropdown->sub_group_id; ?>" data-type="<?php echo $this->CI->fx->getDebitCreditByBehaiour($dropdown->behaviour); ?>" data-address="<?php echo $dropdown->accept_address; ?>" <?php if ($dropdown->sub_group_id == $selected) echo ' selected'; ?>><?php echo $dropdown->sub_group_name; ?></option>
		<?php }
	}

	public function getSubGroupCashBankDropdown()
	{
		foreach ($this->CI->Acc_Model->getSubGroupCashBankList() as $dropdown) { ?>
			<option value="<?php echo $dropdown->sub_group_id; ?>"><?php echo $dropdown->sub_group_name; ?></option>
		<?php }
	}

	public function getSubGroupDropdownWithDetail($selected = NULL, $where = array(), $behaviourOr = array())
	{
		$whereSt = "1=1";
		foreach ($this->CI->Acc_Model->getSubGroupListWithDetail($where, $behaviourOr, $this->CI->fx->clientCompDb) as $dropdown) { ?>
			<option data-behaviour="<?php echo $dropdown->behaviour; ?>" value="<?php echo $dropdown->sub_group_id; ?>" data-address="<?php echo $dropdown->accept_address; ?>" <?php if ($dropdown->sub_group_id == $selected) echo ' selected'; ?>><?php echo $dropdown->sub_group_name; ?></option>
		<?php }
	}
	public function getLedgerDropdown($status = NULL, $selected = NULL, $where = NULL, $join = false)
	{
		$whereSt = "1=1";
		if ($this->CI->fx->masterClient != 0) {
			$whereSt .= " and (branch_id IN ('" . implode("','", $this->fx->branches) . "') OR branch_id IS NULL)";
		}
		if ($status !== NULL) $whereSt .= " and status=" . $status;
		if ($where != NULL)  $whereSt .= " and " . $where;
		foreach ($this->CI->Acc_Model->getLedgerList($whereSt, $this->CI->fx->clientCompDb, $join) as $dropdown) { ?>
			<option value="<?php echo $dropdown->ledger_id; ?>" <?php if ($dropdown->ledger_id == $selected) echo ' selected'; ?>><?php echo $dropdown->acc_head; ?></option>
		<?php }
	}

	public function getAgentDropdown($status = NULL, $selected = NULL, $where = NULL)
	{
		$whereSt = "1=1";
		if ($status !== NULL) $whereSt .= " and status=" . $status;
		if ($where != NULL)  $whereSt .= " and " . $where;
		foreach ($this->CI->Acc_Model->getAgentList($whereSt, $this->CI->fx->clientCompDb) as $dropdown) { ?>
			<option value="<?php echo $dropdown->agent_id; ?>" <?php if ($dropdown->agent_id == $selected) echo ' selected'; ?>><?php echo $dropdown->agent_name; ?></option>
		<?php }
	}

	public function getChallanTypeDropdown($status = NULL, $selected = NULL, $where = NULL)
	{
		$whereSt = "1=1";
		if ($status !== NULL) $whereSt .= " and status=" . $status;
		if ($where != NULL)  $whereSt .= " and " . $where;
		foreach ($this->CI->Acc_Model->getChallanTypeList($whereSt, $this->CI->fx->clientCompDb) as $dropdown) { ?>
			<option value="<?php echo $dropdown->ch_type_id; ?>" <?php if ($dropdown->ch_type_id == $selected) echo ' selected'; ?>><?php echo $dropdown->ch_type; ?></option>
		<?php }
	}

	public function getCostCenterDropdown($status = NULL, $selected = NULL, $where = NULL)
	{
		$whereSt = "1=1";
		if ($status !== NULL) $whereSt .= " and status=" . $status;
		if ($where != NULL)  $whereSt .= " and " . $where;
		foreach ($this->CI->Acc_Model->getCostCenterList($whereSt, $this->CI->fx->clientCompDb) as $dropdown) { ?>
			<option value="<?php echo $dropdown->cc_id; ?>" <?php if ($dropdown->cc_id == $selected) echo ' selected'; ?>><?php echo $dropdown->cc_name . ' #' . $dropdown->cc_unique_id; ?></option>
		<?php }
	}

	public function getLedgerAddressDropdown($selectedVal = '', $ledger_id)
	{
		$this->CI->load->model("Getdata_Model");
		$dataArray = $this->CI->Getdata_Model->getLedgerMultipleAddress(array('ledger_id' => $ledger_id), array('ledger_master_id' => $ledger_id));
		$htmlString = "<option value=''>Select Address</option>";
		$data = $gstin = '';
		if (count($dataArray) > 0) {
			foreach ($dataArray as $key => $value) {
				$address = $value['address1'];
				if ($value['address2'] != '')
					$address .= ', ' . $value['address2'];

				if ($value['city'] != '')
					$address .= ', ' . $value['city'];

				if ($value['state'] != '')
					$address .= ', ' . $value['state'];

				if ($value['pincode'] != '')
					$address .= ', ' . $value['pincode'];

				if ($value['country'] != '')
					$address .= ', ' . $value['country'];

				$selected = "";
				if ($value['id'] == 0) {
					$data = $address;
					$gstin = $value['gstin'];
					$selected = "selected=''";
				}
				$gstin1 = $value['gstin'];
				$showTitle = $value['branch_name'];
				if ($value['pincode'] != '')
					$showTitle .= "-" . $value['pincode'];

				$state_code = $value['state_code'];
				$contact_person = str_replace(array("'", '"'), array(' ', ' '), $value['contact_person']);

				$htmlString .= "<option $selected value='" . $value['id'] . "' data-state_code='$state_code' data-gstin='$gstin1' data-email='$value[email]' data-mobile='$value[mobile]' data-contact_person='$contact_person' data-address='" . $address . "'>" . $showTitle . "</option>";
			}
		} else {
			$htmlString = "<option value=''>No Address Found</option>";
		}
		echo $htmlString;
	}
	public function getMessageTemplaeDropdown($selected = NULL, $queryArray = array())
	{
		$this->CI->load->model("Getdata_Model");
		foreach ($this->CI->Getdata_Model->getTemplateList($queryArray, array()) as $dropdown) { ?>
			<option data-temp_type="<?php echo $dropdown['temp_type']; ?>" value="<?php echo $dropdown['id']; ?>" <?php if ($dropdown['id'] == $selected) echo ' selected'; ?>><?php echo $dropdown['template_name'] . ' - ' . $dropdown['temp_type']; ?></option>
		<?php }
	}

	//This method does not work
	public function getLeadTypeDropdown_this_method_is_not_working($selected = NULL, $queryArray = array(), $selArray = array())
	{
		foreach ($this->CI->Acc_Model->getLeadTypeList($queryArray, array()) as $dropdown) { ?>
			<option value="<?php echo $dropdown['id']; ?>" <?php if ($dropdown['id'] == $selected || in_array($dropdown['id'], $selArray)) echo ' selected'; ?>><?php echo $dropdown['lead_type_name']; ?></option>
			<?php }
	}

	public function getLeadTypeDropdown($selected = NULL, $queryArray = array(), $selArray = array())
	{
		$leadTypes = $this->CI->Acc_Model->getLeadTypeList($queryArray, array());
		if (count($leadTypes) === 1) {
			echo '<option value="' . $leadTypes[0]['id'] . '" selected>' . $leadTypes[0]['lead_type_name'] . '</option>';
		} else {
			foreach ($leadTypes as $dropdown) { ?>
				<option value="<?php echo $dropdown['id']; ?>" <?php if ($dropdown['id'] == $selected || in_array($dropdown['id'], $selArray)) echo ' selected'; ?>><?php echo $dropdown['lead_type_name']; ?></option>
			<?php }
		}
	}

	////This method does not work
	public function getLeadSourceDropdown_this_method_is_not_working($selected = NULL, $queryArray = array(), $selArray = array())
	{
		foreach ($this->CI->Acc_Model->getLeadSourceList($queryArray, array()) as $dropdown) { ?>
			<option value="<?php echo $dropdown['id']; ?>" <?php if ($dropdown['id'] == $selected || in_array($dropdown['id'], $selArray) == true) echo ' selected'; ?>><?php echo $dropdown['lead_source_name']; ?></option>
			<?php }
	}

	public function getLeadSourceDropdown($selected = NULL, $queryArray = array(), $selArray = array())
	{
		$leadSource = $this->CI->Acc_Model->getLeadSourceList($queryArray, array());
		if (count($leadSource) === 1) {
			echo '<option value="' . $leadSource[0]['id'] . '" selected>' . $leadSource[0]['lead_source_name'] . '</option>';
		} else {
			foreach ($leadSource as $dropdown) { ?>
				<option value="<?php echo $dropdown['id']; ?>" <?php if ($dropdown['id'] == $selected || in_array($dropdown['id'], $selArray) == true) echo ' selected'; ?>><?php echo $dropdown['lead_source_name']; ?></option>
			<?php }
		}
	}

	public function getLeadOutsourceTypeDropdown($selected = NULL, $queryArray = array())
	{
		foreach ($this->CI->Acc_Model->getUniqueLeadOutsourceTypeList($queryArray, array()) as $dropdown) { ?>
			<option value="<?php echo $dropdown['name']; ?>" <?php if ($dropdown['name'] == $selected) echo ' selected'; ?>><?php echo $dropdown['name']; ?></option>
		<?php }
	}

	public function clientGroupDropdown($selected = NULL, $where = '')
	{
		$parent_id = $this->CI->fx->clientAccessID;
		$where1 = "(parent_id='$parent_id' or client_id='$parent_id') and group_name!=''";
		if ($where != '')
			$where1 .= " and $where";
		foreach ($this->CI->Acc_Model->getClientGroupList($where1) as $dropdown) { ?>
			<option value="<?php echo $dropdown['name']; ?>" <?php if ($dropdown['name'] == $selected) echo ' selected'; ?>><?php echo $dropdown['name']; ?></option>
		<?php }
	}

	////This method does not work
	public function getLeadStatusDropdown_this_method_is_not_working($selected = NULL, $queryArray = array(), $selArray = array())
	{
		foreach ($this->CI->Acc_Model->getLeadStatusList($queryArray, array()) as $dropdown) { ?>
			<option style="color: <?php echo @$dropdown['color_code'] ?>" value="<?php echo $dropdown['id']; ?>" <?php if ($dropdown['id'] == $selected) echo ' selected';
																													echo in_array($dropdown['id'], $selArray) ? 'selected' : '';   ?>><?php echo $dropdown['lead_status_name']; ?></option>
			<?php }
	}

	public function getLeadStatusDropdown($selected = NULL, $queryArray = array(), $selArray = array())
	{
		$leadStatus = $this->CI->Acc_Model->getLeadStatusList($queryArray, array());
		if (count($leadStatus) === 1) {
			echo '<option style="color:' . $leadStatus[0]['color_code'] . '" value="' . $leadStatus[0]['id'] . '" selected>' . $leadStatus[0]['lead_status_name'] . '</option>';
		} else {
			foreach ($leadStatus as $dropdown) { ?>
				<option style="color: <?php echo @$dropdown['color_code'] ?>" value="<?php echo $dropdown['id']; ?>" <?php if ($dropdown['id'] == $selected || in_array($dropdown['id'], $selArray) == true) echo ' selected'; ?>><?php echo $dropdown['lead_status_name']; ?></option>
			<?php }
		}
	}

	public function getleadStatusType($type)
	{
		switch ($type) {
			case '1':
				echo "In Progress";
				break;
			case '2':
				echo "Completed";
				break;
			case '3':
				echo "Lost";
				break;
			default:

				break;
		}
	}
	public function getHourDropDown($selected = "")
	{
		for ($i = 0; $i < 24; $i++) {
			$hour = sprintf("%02d", $i);
			?>
			<option <?php if ($selected == $hour) {
						echo 'selected';
					} ?> value="<?php echo $hour; ?>"><?php echo $hour; ?></option>
		<?php }
	}

	function contractReminderDaysDropdown($selected = "", $detault = 0)
	{
		$selectedArray = array();
		if ($selected != '') {
			$selectedArray = explode(',', $selected);
		} else if ($detault < 1) {
			$selectedArray = array();
			//30,10,0,-1,-10
		}
		for ($i = 30; $i >= -30; $i--) {
			if ($i % 5 != 0 && $i != -1) {
				continue;
			}
			$showText = "Before $i Days";
			if ($i < 0) {
				$showText = "After " . ($i * -1) . " Days";
			} else if ($i == 0) {
				$showText = "Contract Last Days";
			}
			$selected = (in_array($i, $selectedArray)) ? 'selected' : '';
		?>
			<option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $showText; ?></option>
		<?php
		}
	}
	function getMenuSubmenuDropdown($selected = '')
	{
		$menuParent = $this->CI->Acc_Model->parentMenuList($this->CI->fx->masterClient);

		foreach ($menuParent['menu'] as $mParent) { ?>
			<optgroup label="<?php echo $mParent->menu_name; ?>">
				<?php if ($mParent->menu_type == 1) { ?>
					<option value="<?php echo $mParent->menu_id; ?>"><?php echo $mParent->menu_name; ?></option>
					<?php }
				$menuChild = isset($menuParent['submenu'][$mParent->menu_id]) ? $menuParent['submenu'][$mParent->menu_id] : [];
				if (isset($menuChild) && count($menuChild) > 0) {
					foreach ($menuChild as $mChild) {
					?>
						<option <?php if ($selected == $mChild->menu_id) echo "selected"; ?> value="<?php echo $mChild->menu_id; ?>"><?php echo $mChild->menu_name; ?></option>
				<?php }
				} ?>

			</optgroup>
		<?php }
	}
	public function getSaleCategoryDropdown($selected = NULL, $queryArray = array())
	{
		foreach ($this->CI->Acc_Model->getSaleCategory($queryArray, array()) as $dropdown) { ?>
			<option value="<?php echo $dropdown['id']; ?>" <?php if ($dropdown['id'] == $selected) echo ' selected'; ?>><?php echo $dropdown['sale_category_title']; ?></option>
		<?php }
	}
	public function getCurrencyDropdown($selected = NULL, $where = NULL)
	{
		foreach ($this->CI->Acc_Model->getCurrencyList($where) as $dropdown) { ?>
			<option <?php if ($selected == '' && $dropdown['is_default'] == 1) {
						echo 'selected';
					} ?> value="<?php echo $dropdown['id']; ?>" <?php if ($dropdown['id'] == $selected) echo ' selected'; ?>><?php echo $dropdown['currency_name']; ?></option>
			<?php }
	}

	public function getPaginationRecords($selected = null)
	{
		$paginationRecord = $this->CI->Acc_Model->getPaginationRecords(array('status' => 1));
		if (count($paginationRecord) > 0) {
			foreach ($paginationRecord as $key => $value) { ?>
				<option <?php if ($value['is_default'] == 1) echo 'selected'; ?> value="<?php echo ($value['record_val'] == 0) ? 1000000 : $value['record_val']; ?>"> <?php echo $value['record_title']; ?></option>
			<?php	}
		} else { ?>
			<option value="50">50</option>
			<option value="100">100</option>
			<option value="200">200</option>
			<option value="500">500</option>
			<option value="1000">1000</option>
			<?php }
	}

	public function getUserDropdown($selected, $agent = 1, $chiledTree = false, $selArray = array())
	{
		$where = "(parent_id='" . $this->CI->fx->clientAccessID . "' or client_id='" . $this->CI->fx->clientId . "' or client_id='" . $this->CI->fx->clientAccessID . "')";

		if ($agent == 1)
			$where .= " and is_agent=1";

		if ($chiledTree == true && $this->CI->fx->masterClient != 0) {
			$where .= " and client_id IN (" . implode(',', $this->CI->fx->childTreeIds) . ")";
		}

		$paginationRecord = $this->CI->Acc_Model->getUserdropdownList(array(), $where);
		if (count($paginationRecord) > 0) {
			foreach ($paginationRecord as $key => $value) {
				if ($value->status != 1 and $selected != $value->client_id)
					continue;
			?>
				<option <?php if ($selected == $value->client_id || in_array($value->client_id, $selArray)) {
							echo 'selected';
						} ?> value="<?php echo $value->client_id; ?>"> <?php echo $value->clientName; ?></option>
		<?php }
		}
	}
	public function displayPoweredByinPdf()
	{
		return "<span style=''>Printed On : <b>" . date('d-m-Y h:i A') . " </b></span> || <span style=';float:left !important;text-align:left'>" . $this->CI->fx->crmPdfText . " || </span>";
	}
	public function showPreviousNextButtons($pn_url, $prevId, $nextId, $firstId = 0, $lastId = 0)
	{ ?>
		<div class="pull-right prev_next_btn_outer_div">

			<a class="btn btn-primary prev_next_btn btn-xs firstEntryShortCutButton <?php echo ($firstId == 0) ? 'disabled' : ''; ?>" href="<?php echo base_url("$pn_url/$firstId"); ?>"><i class="fa fa-angle-double-left" aria-hidden="true"></i>
			</a>
			<?php
			if ($prevId > 0 || $nextId > 0) {
			?>
				<a class="btn btn-primary prev_next_btn btn-xs previousEntryShortCutButton <?php echo ($prevId == 0) ? 'disabled' : ''; ?>" href="<?php echo base_url("$pn_url/$prevId"); ?>"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
				</a>
				<a class="btn btn-primary prev_next_btn btn-xs nextEntryShortCutButton <?php echo ($nextId == 0) ? 'disabled' : ''; ?>" href="<?php echo base_url("$pn_url/$nextId"); ?>"><i class="fa fa-long-arrow-right" aria-hidden="true"></i>
				</a>
			<?php } ?>

			<a class="btn btn-primary prev_next_btn btn-xs lastEntryShortCutButton <?php echo ($lastId == 0) ? 'disabled' : ''; ?>" href="<?php echo base_url("$pn_url/$lastId"); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i>
			</a>
		</div>
	<?php }

	public function printTableSortsIcon($name)
	{
		echo "<a class='table_sorting' data-name='$name'> <i class='fa fa-fw fa-sort'></i></a>";
	}

	public function printTableSortDescAsc($column, $sort_type, $selected_col)
	{ ?>
		<a class="table_sorting_new" data-name="<?php echo $column; ?>">
			<i class="fa fa-fw <?php echo ($column == $selected_col) ? 'fa-sort-amount-' . $sort_type : 'fa-sort' ?>"></i></a>
	<?php }

	public function getDispatchMode($mode)
	{
		switch ($mode) {
			case '1':
				return "Road";
				break;
			case '2':
				return "Rail";
				break;
			case '3':
				return "Air";
				break;
			case '4':
				return "Ship";
				break;
			default:
				return $mode;
				break;
		}
	}
	public function userLogTypeDropdown()
	{ ?>
		<option value="1">Entry</option>
		<option value="2">Update</option>
		<option value="3">Delete</option>
		<option value="4">Search</option>
		<option value="5">Login</option>
		<option value="6">Logout</option>
		<?php }
	public function getUserLogArray()
	{
		return array('1' => 'Entry', '2' => 'Update', '3' => 'Delete', '4' => 'Search', '5' => 'Login', '6' => 'Logout');
	}

	public function getExpensemasterDroudown($selected = '', $where = '')
	{
		$dataArray = $this->CI->Acc_Model->getExpenseMasterList($where);
		if (count($dataArray) > 0) {
			foreach ($dataArray as $key => $value) { ?>
				<option <?php if ($selected == $value->id) {
							echo 'selected';
						} ?> value="<?php echo $value->id; ?>"> <?php echo $value->expense_name; ?></option>
			<?php }
		}
	}

	public function getApproveStatusDropdown($selected = NULL, $where = NULL)
	{
		foreach ($this->CI->Acc_Model->getApproveStatusList($where) as $dropdown) { ?>
			<option <?php if ($selected == $dropdown['id']) {
						echo 'selected';
					} ?> value="<?php echo $dropdown['id']; ?>" <?php if ($dropdown['id'] == $selected) echo ' selected'; ?>><?php echo $dropdown['title']; ?></option>
		<?php }
	}
	public function getTaxCateDropdown($status = NULL, $selected = NULL, $where = NULL)
	{
		$whereSt = "1=1";
		if ($status !== NULL) $whereSt .= " and status=" . $status;
		if ($where != NULL)  $whereSt .= " and " . $where;
		foreach ($this->CI->Acc_Model->getTaxCateList($whereSt, $this->CI->fx->clientCompDb) as $dropdown) { ?>
			<option value="<?php echo $dropdown->cat_id; ?>" <?php if ($dropdown->cat_id == $selected) echo ' selected'; ?>><?php echo $dropdown->cat_name; ?></option>
		<?php }
	}

	public function getIndentDropdown($selected = NULL, $where = NULL)
	{
		foreach ($this->CI->Acc_Model->getIndentList($where) as $dropdown) { ?>
			<option <?php if ($selected == $dropdown['indent_id']) {
						echo 'selected';
					} ?> value="<?php echo $dropdown['indent_id']; ?>" <?php if ($dropdown['indent_id'] == $selected) echo ' selected'; ?>><?php echo $dropdown['indent_id']; ?></option>
		<?php }
	}

	public function getUniqueProjectDropdown($selected = '', $where = '')
	{
		if ($this->CI->fx->masterClient != 0)
			$where .= "t2.client_id=" . $this->CI->fx->clientId;
		foreach ($this->CI->Acc_Model->getProjectList($where) as $dropdown) { ?>
			<option value="<?php echo $dropdown['id']; ?>" <?php if ($selected == $dropdown['id']) {
																echo "selected";
															} ?>> <?php echo $dropdown['project_name']; ?></option>
		<?php }
	}

	public function getClientInfoForProject($where = '', $selected = '')
	{
		foreach ($this->CI->Acc_Model->getClientInfoForProject($where) as $dropdown) { ?>
			<option value="<?php echo $dropdown['client_id']; ?>" <?php if ($selected == $dropdown['client_id']) {
																		echo "selected";
																	} ?>> <?php echo $dropdown['clientName']; ?></option>
		<?php }
	}

	public function taskProrityDropdown($where = '', $selected = '')
	{
		foreach ($this->CI->Acc_Model->gettaskPriorityList($where) as $key => $value) { ?>
			<option value="<?php echo $value['id']; ?>" <?php if ($selected == $value['id']) {
															echo "selected";
														} ?>> <?php echo $value['title']; ?></option>
		<?php  }
	}

	public function tasktypeDropdown($where = '', $selected = '')
	{
		foreach ($this->CI->Acc_Model->getTaskTypeList($where) as $key => $value) { ?>
			<option value="<?php echo $value['id']; ?>" <?php if ($selected == $value['id']) {
															echo "selected";
														} ?>><?php echo $value['title']; ?></option>
		<?php  }
	}

	public function taskStatusDropdown($where = '', $selected = '')
	{
		foreach ($this->CI->Acc_Model->getTaskStatusList($where) as $key => $value) { ?>
			<option value="<?php echo $value['id']; ?>" <?php if ($selected == $value['id']) {
															echo "selected";
														} ?>> <?php echo $value['title']; ?></option>
		<?php  }
	}

	public function getItemUnitDropdown($where = '', $selected = '')
	{
		$where2 = " 1=1 and unit!='' ";
		$where2 .= ($where != '') ? ("and $where") : '';
		foreach ($this->CI->Acc_Model->getItemUnitDropdown($where2)  as $key => $value) { ?>
			<option data-unit_type='0' value="<?php echo $value['unit']; ?>" <?php if ($selected == $value['unit']) {
																					echo "selected";
																				} ?>> <?php echo $value['unit']; ?></option>
		<?php  }
	}

	public function getChallaninDropdown($where = '', $selected = '')
	{
		foreach ($this->CI->Acc_Model->getChallninList($where)  as $key => $value) { ?>
			<option data-date='<?php echo $value['challaninDate']; ?>' value="<?php echo $value['challanin_id']; ?>" <?php if ($selected == $value['challanin_id']) {
																															echo "selected";
																														} ?>> #<?php echo $value['challanin_id']; ?></option>
		<?php  }
	}

	public function getUniqueLocationRefType($where = '', $selected = '')
	{
		foreach ($this->CI->Acc_Model->getUniqueLocationRefType($where) as $key => $value) { ?>
			<option value="<?php echo $value['ref_type']; ?>" <?php if ($selected == $value['ref_type']) {
																	echo "selected";
																} ?>> <?php echo ucfirst($value['ref_type']); ?></option>
		<?php  }
	}

	public function getLeadCategoryDropdown($where = '', $selected = '', $selArray = array())
	{
		foreach ($this->CI->Acc_Model->getLeadCategoryList($where) as $key => $value) { ?>
			<option value="<?php echo $value['id']; ?>" <?php if ($selected == $value['id'] || in_array($value['id'], $selArray) == true) {
															echo "selected";
														} ?>> <?php echo ucfirst($value['title']); ?></option>
		<?php  }
	}

	public function leadApiType()
	{
		return array('indiamart' => 'India Mart', 'justdial' => 'Just Dial', 'tradeindia' => 'Trade India');
	}
	
	public function leadApiTypeDropdown($where = '', $selected = '')
	{
		foreach ($this->leadApiType() as $key => $value) { ?>
			<option value="<?php echo $key; ?>"> <?php echo $value; ?></option>
		<?php  }
	}

	public function showCallButton($mobile = '', $class = '', $uniqueid = "")
	{
		// $mobile = "/^\+?[0-9]+$/", "", "$mobile";
		$this->CI->load->library('dial');
		if ($mobile != '' && isset($this->CI->session->CLIENT->clientComp->dial_api) && $this->CI->session->CLIENT->clientComp->dial_api != '' && isset($this->CI->session->CLIENT->extenstion_no) && $this->CI->session->CLIENT->extenstion_no != '' && isset($this->CI->session->CLIENT->clientComp->dialer_method) && $this->CI->session->CLIENT->clientComp->dialer_method != '' && method_exists($this->CI->dial, $this->CI->session->CLIENT->clientComp->dialer_method)) {
		?>
			<a onclick="dialMobileNumberApi('<?php echo $this->CI->fx->encrypt_decrypt('encrypt', $mobile); ?>','<?php echo $uniqueid; ?>')" class="btn btn-xs btn-success <?php echo $class; ?>"><i class="fa fa-phone" aria-hidden="true"></i></a>
		<?php }
	}

	public function showCallButtonForMultipleNumbers($mobile_string, $uniqueid = '', $childclass = '', $pclass = '', $seprator = '')
	{

		$seprator = ($seprator != '') ? $seprator : ", ";
		if ($mobile_string == '')
			return;

		// $this-> CI -> session -> CLIENT -> hide_phone=0
		$hide_phone = $this->CI->fx->hide_phone;

		$strArray = explode(",", $mobile_string);
		foreach ($strArray as $key => $mobile) {
			$showMobile = $mobile = $mobile;

			if ($hide_phone == 1) {
				$showMobile = str_repeat("#", strlen($mobile));
				$showMobile = substr($mobile, 0, 2) . substr($showMobile, 0, strlen($showMobile) - 5) . substr($mobile, -3);
			}
		?>
			<label class="mobile_number <?php echo $pclass; ?>"><?php echo $showMobile; ?></label>
			<?php $this->showCallButton($mobile, $childclass, $uniqueid); ?>

		<?php
			if (count($strArray) - 1 != $key) {
				echo $seprator;
			}
		}
	}
	public function showNumberFancyString($mobile_string, $uniqueid = '', $childclass = '', $pclass = '', $seprator = '')
	{
		$seprator = ($seprator != '') ? $seprator : ", ";
		if ($mobile_string == '')
			return;
		// $this-> CI -> session -> CLIENT -> hide_phone=0
		$hide_phone = $this->CI->fx->hide_phone;

		$strArray = explode(",", $mobile_string);
		$finalString = "";
		foreach ($strArray as $key => $mobile) {
			$showMobile = $mobile; //= preg_replace("/^\+?[0-9]+$/", "", "$mobile");
			if ($hide_phone == 1) {
				$showMobile = str_repeat("#", strlen($mobile));
				$showMobile = substr($mobile, 0, 2) . substr($showMobile, 0, strlen($showMobile) - 5) . substr($mobile, -3);
			}
			$finalString .= $showMobile;
			if (count($strArray) - 1 != $key) {
				$finalString .= $seprator;
			}
		}
		return $finalString;
	}

	public function showLedgerContactLink($string, $ledger_id = '', $class = '')
	{ ?>
		<a role="button" class="<?php echo ($class != '') ? $class : ' blue_link '; ?>" onclick="getLedgerContactDetail('<?php echo $ledger_id; ?>')"><?php echo $string; ?> </a>
		<?php }

	public function getSubcategoryDropdown($where = '', $selected = '')
	{
		foreach ($this->CI->Acc_Model->getSubcategoryList($where)  as $key => $value) { ?>
			<option value="<?php echo $value['id']; ?>" <?php if ($selected == $value['id']) {
															echo "selected";
														} ?>>
				<?php echo $value['title']; ?></option>
		<?php  }
	}

	public function getMultipleContactPersonDropdown($where = '', $selected = '')
	{
		foreach ($this->CI->Acc_Model->getLedgerMultiContactPersonDropdownList($where)  as $key => $value) { ?>
			<option data-name="<?php echo $value['name']; ?>" data-email="<?php echo $value['email']; ?>" data-mobile="<?php echo $value['mobile']; ?>" data-alt_mobile="<?php echo $value['alt_mobile']; ?>" value="<?php echo $value['id']; ?>" <?php if ($selected == $value['id'] || $selected == $value['name']) {
																																																														echo "selected";
																																																													} ?>>
				<?php echo $value['name']; ?></option>
		<?php  }
	}

	public function getEmailLogRefNameDropdown($selected = NULL, $where = '')
	{
		foreach ($this->CI->Acc_Model->getEmailLogRefName($where) as $result) {
			$dropdown = $result['name'];
		?>
			<option value="<?php echo $dropdown; ?>" <?php if ($dropdown == $selected) echo ' selected'; ?>><?php echo ucfirst($dropdown); ?></option>
		<?php }
	}

	public function getMenuParentDropdown($selected = NULL, $where = '')
	{
		foreach ($this->CI->Acc_Model->getMenuParentList($where) as $dropdown) { ?>
			<option value="<?php echo $dropdown['menu_id']; ?>" <?php if ($dropdown['menu_id'] == $selected) echo ' selected'; ?>><?php echo $dropdown['menu_name']; ?></option>
		<?php }
	}

	public function getDyanmicFormDropdown($selected = NULL, $where = '')
	{
		foreach ($this->CI->Acc_Model->getDyanmicFormList($where) as $dropdown) { ?>
			<option value="<?php echo $dropdown['id']; ?>" <?php if ($dropdown['id'] == $selected) echo ' selected'; ?>><?php echo $dropdown['name']; ?></option>
		<?php }
	}

	public function getFieldGroupDropdown($where = '', $selected = '')
	{
		$where2 = " 1=1 AND name!='' ";
		$where2 .= ($where != '') ? ("AND $where") : '';
		foreach ($this->CI->Acc_Model->getFieldGroupDropdown($where2)  as $key => $value) { ?>
			<option data-group_type='0' value="<?php echo $value['id']; ?>" <?php if ($selected == $value['id']) {
																				echo "selected";
																			} ?>> <?php echo $value['name']; ?></option>
		<?php  }
	}
	public function getSystemFormMappingDropdown($selected = NULL, $where = '')
	{
		foreach ($this->CI->Acc_Model->getSystemFormMappingList($where) as $dropdown) { ?>
			<option value="<?php echo $dropdown['id']; ?>" <?php if ($dropdown['id'] == $selected) echo ' selected'; ?>><?php echo $dropdown['name']; ?></option>
		<?php }
	}

	public function getDynamicFormStatusDropdown($selected = NULL, $queryArray = array())
	{
		foreach ($this->CI->Acc_Model->getdynamicFormStatusList($queryArray, array()) as $dropdown) { ?>
			<option style="color: <?php echo @$dropdown['status_color'] ?>" value="<?php echo $dropdown['id']; ?>" <?php if ($dropdown['id'] == $selected) echo ' selected'; ?>><?php echo $dropdown['status_text']; ?></option>
		<?php }
	}

	public function getDyanmicFormListDropdown($selected = NULL, $where = '')
	{
		foreach ($this->CI->Acc_Model->getDyanmicFormDataList($where) as $dropdown) { ?>
			<option value="<?php echo $dropdown['id']; ?>" <?php if ($dropdown['id'] == $selected) echo ' selected'; ?>><?php echo $dropdown['form_name']; ?></option>
		<?php }
	}


	function showWebWhatsappURL($mobile, $text)
	{
		if ($mobile == '' || !empty($this->CI->session->CLIENT->clientComp->whatsapp_api))
			return;
		$mobile = (strlen(trim($mobile)) != 10) ? "$mobile" : "91$mobile";
		?>

		<a class="btn btn-xs pull-right btn-success sendWhatsappTextShortCutButton" href="https://web.whatsapp.com/send?phone=<?php echo $mobile; ?>&text=<?php echo urlencode($text) ?>&source=&data=" target="_blank">
			<i class="fa fa-whatsapp" aria-hidden="true"></i>
		</a>
		<?php
	}

	function getDocumentTypeDropdown($selected = '', $where = '')
	{
		foreach ($this->CI->Acc_Model->getDocumentTypeList($where) as $dropdown) { ?>
			<option value="<?php echo $dropdown['id']; ?>" <?php if ($dropdown['id'] == $selected) echo ' selected'; ?>><?php echo $dropdown['doc_type_name']; ?></option>
		<?php }
	}

	function getPaymentGatewayDropdown($selected = '', $where = '')
	{
		foreach ($this->CI->Acc_Model->getPaymentGatewayList($where) as $dropdown) { ?>
			<option value="<?php echo $dropdown['id']; ?>" <?php if ($dropdown['id'] == $selected) echo ' selected'; ?>><?php echo $dropdown['title']; ?></option>
		<?php }
	}

	function getMonthsDropdown($selected = '')
	{
		$currentTime = time();
		$nextMonth = strtotime("+1 month");
		$LastMonth = strtotime("-1 month");
		?>
		<option <?php echo ($selected == date('m-Y', $LastMonth)) ? 'selected' : ''; ?> value="<?php echo date('m', $LastMonth); ?>" data-first_day="<?php echo firstAndLastDayOfMonth($LastMonth)->first_day; ?>" data-last_day="<?php echo firstAndLastDayOfMonth($LastMonth)->last_day; ?>"><?php echo date('F, Y', $LastMonth); ?></option>

		<option <?php echo ($selected == date('m-Y', $currentTime)) ? 'selected' : ''; ?> value="<?php echo date('m', $currentTime); ?>" data-first_day="<?php echo firstAndLastDayOfMonth($currentTime)->first_day; ?>" data-last_day="<?php echo firstAndLastDayOfMonth($currentTime)->last_day; ?>"><?php echo date('F, Y', $currentTime); ?></option>

		<option <?php echo ($selected == date('m-Y', $nextMonth)) ? 'selected' : ''; ?> value="<?php echo date('m', $nextMonth); ?>" data-first_day="<?php echo firstAndLastDayOfMonth($nextMonth)->first_day; ?>" data-last_day="<?php echo firstAndLastDayOfMonth($nextMonth)->last_day; ?>"><?php echo date('F, Y', $nextMonth); ?></option>

		<?php
	}
	function getLeadTemplateFormDropdown($selected = '')
	{
		foreach ($this->lead_template_form_fileds() as $key => $value) { ?>
			<option value="<?php echo $value['name']; ?>" <?php echo ($selected == $value['name']) ? 'selected' : ''; ?>>
				<?php echo $value['label']; ?>
			</option>

		<?php
		}
	}

	function getContractReminderTemplateFormFields($selected = '')
	{
		foreach ($this->contract_template_form_fileds() as $key => $value) { ?>
			<option value="<?php echo $value['name']; ?>" <?php echo ($selected == $value['name']) ? 'selected' : ''; ?>>
				<?php echo $value['label']; ?>
			</option>

		<?php
		}
	}

	function getPaymentReminderTemplateFormFields($selected = '')
	{
		foreach ($this->payment_reminder_template_form_fileds() as $key => $value) { ?>
			<option value="<?php echo $value['name']; ?>" <?php echo ($selected == $value['name']) ? 'selected' : ''; ?>>
				<?php echo $value['label']; ?>
			</option>

		<?php
		}
	}

	function getPaymentReceiptCashTemplateFormFields($selected = '')
	{
		foreach ($this->payment_receipt_cash_form_fields() as $key => $value) { ?>
			<option value="<?php echo $value['name']; ?>" <?php echo ($selected == $value['name']) ? 'selected' : ''; ?>>
				<?php echo $value['label']; ?>
			</option>

		<?php
		}
	}

	function getPaymentReceiptBankTemplateFormFields($selected = '')
	{
		foreach ($this->payment_receipt_bank_form_fields() as $key => $value) { ?>
			<option value="<?php echo $value['name']; ?>" <?php echo ($selected == $value['name']) ? 'selected' : ''; ?>>
				<?php echo $value['label']; ?>
			</option>

		<?php
		}
	}

	function getJournalBookTemplateFormFields($selected = '')
	{
		foreach ($this->journal_book_form_fields() as $key => $value) { ?>
			<option value="<?php echo $value['name']; ?>" <?php echo ($selected == $value['name']) ? 'selected' : ''; ?>>
				<?php echo $value['label']; ?>
			</option>

		<?php
		}
	}

	function getSystemFormDynamicFieldsArr($form_name)
	{

		$colsArr	= array();
		$this->CI->load->model("Dynamicfields_Model");
		if (!empty($form_name)) {
			$system_form_id	= $this->CI->Dynamicfields_Model->getSystemFormDetail($form_name);
			$formList		= $this->CI->Dynamicfields_Model->getAllFormList("t1.system_form_mapping_id=$system_form_id");
			if (!empty($formList['data'])) {
				$form_id	= $formList['data'][0]->id;
				$formFields	= $this->CI->Dynamicfields_Model->getFormFieldsByFormId($form_id);

				foreach ($formFields->data as $lfky => $lfval) {
					$fieldky	= in_array(strtolower($lfval->field_type), array('multiselect', 'dropdown')) ? $lfval->field_name . '[]' : $lfval->field_name;
					$fieldtype	= in_array(strtolower($lfval->field_type), array('multiselect', 'dropdown')) ? 'multiselect' : $lfval->field_type;
					$colsArr[$fieldky]	=	array(
						'label'			=> $lfval->field_description,
						'name'			=> $fieldky,
						'data_type'		=> $lfval->data_type,
						'field_type'	=> $fieldtype,
						'is_dynamic'	=> true
					);
				}
			}
		}
		return $colsArr;
	}

	public function getContractTypeDropdown($selected = '', $selArray = array())
	{
		$contractType = array('1' => 'Monthly', '3' => 'Quarterly', '6' => 'Half Yearly', '12' => 'Yearly');
		foreach ($contractType as $key => $value) { ?>
			<option value="<?php echo $key; ?>" <?php if ($selected == $key || in_array($key, $selArray) == true) {
													echo "selected";
												} ?>> <?php echo ucfirst($value); ?></option>
		<?php  }
	}

	public function getDueDaysDropdown($selected = '', $selArray = array())
	{
		$contractType = array('1' => 'Before Due Date', '2' => 'After Due Date', '3' => 'On Due date');
		foreach ($contractType as $key => $value) { ?>
			<option value="<?php echo $key; ?>" <?php if ($selected == $key || in_array($key, $selArray) == true) {
													echo "selected";
												} ?>> <?php echo ucfirst($value); ?></option>
		<?php  }
	}

	public function getCashBookDropdown($selected = '', $selArray = array())
	{
		$this->CI->load->model("Payments_Model");
		$cashBookList = $this->CI->Payments_Model->getCashBookList(); ?>
		<option value="">Select</option>
	<?php
		$this->selectList($cashBookList, 'ledger_id', 'acc_head', isset($selected) ? $selected : '');
	}

	public function getBankList($selected = '', $selArray = array())
	{
		$this->CI->load->model("Payments_Model");
		$bankList = $this->CI->Payments_Model->getBankList(); ?>
		<option value="">Select</option>
	<?php
		$this->selectList($bankList, 'ledger_id', 'acc_head', isset($selected) ? $selected : '');
	}

	public function getContractReminderDaysOld($selected = '', $selArray = array())
	{
		$this->CI->load->model("Contract_Reminder_Day_Model");
		$contractRemDaysList = $this->CI->Contract_Reminder_Day_Model->getContractReminderDaysList(); ?>
		<option value="`due_date + INTERVAL 0 day`">On Due Date</option>
		<?php
		foreach ($contractRemDaysList as $rky => $rval) {
			$num      = (strtolower($rval->rem_time) == 'before') ? '-' . $rval->rem_day : $rval->rem_day;
			$numVal	  = '`due_date + INTERVAL ' . $num . ' day`';
			$numText  = ucfirst($rval->rem_time) . ' ' . $rval->rem_day;
		?>
			<option value="<?php echo $numVal ?>" <?php echo ($selected == $numVal || in_array($numVal, $selArray)) ? "selected" : ""; ?>>
				<?php echo $numText; ?>
			</option>
		<?php
		}
	}

	public function getContractReminderDays($selected = '', $selArray = array())
	{
		$this->CI->load->model("Contract_Reminder_Day_Model");
		$contractRemDaysList = $this->CI->Contract_Reminder_Day_Model->getContractReminderDaysList(); ?>
		<option value="0">On Due Date</option>
		<?php
		foreach ($contractRemDaysList as $rky => $rval) {
			$num      = (strtolower($rval->rem_time) == 'before') ? '-' . $rval->rem_day : $rval->rem_day;
			$numVal	  = $num;
			$numText  = ucfirst($rval->rem_time) . ' ' . $rval->rem_day;
		?>
			<option value="<?php echo $numVal ?>" <?php echo ($selected == $numVal || in_array($numVal, $selArray)) ? "selected" : ""; ?>>
				<?php echo $numText; ?>
			</option>
		<?php
		}
	}

	public function getPaymentReminderDays($selected = '', $selArray = array())
	{
		$this->CI->load->model("Contract_Reminder_Day_Model");
		$paymentRemDaysList = $this->CI->Contract_Reminder_Day_Model->getPaymentReminderDaysList(); ?>
		<option value="0">Select</option>
		<?php
		foreach ($paymentRemDaysList as $rky => $rval) {
			$num      = (strtolower($rval->rem_time) == 'after') ?  $rval->rem_day : $rval->rem_day;
			$numVal	  = $num;
			$numText  = ucfirst($rval->rem_time) . ' ' . $rval->rem_day;
		?>
			<option value="<?php echo $numVal ?>" <?php echo ($selected == $numVal || in_array($numVal, $selArray)) ? "selected" : ""; ?>>
				<?php echo $numText; ?>
			</option>
		<?php
		}
	}

	public function getWorkStatusDropdown($selected = NULL, $queryArray = array(), $selArray = array())
	{
		$this->CI->load->model("Work_Status_Model"); ?>
		<option value="">Select</option>
		<?php
		foreach ($this->CI->Work_Status_Model->getWorkStatusList($queryArray, array()) as $dropdown) { ?>
			<option style="color:<?php echo @$dropdown['color_code'] ?>" value="<?php echo $dropdown['id']; ?>" <?php if ($dropdown['id'] == $selected) echo ' selected';
																												echo in_array($dropdown['id'], $selArray) ? 'selected' : '';   ?>><?php echo $dropdown['status_name']; ?></option>
		<?php }
	}

	public function getAllUsers($status = NULL, $selected = NULL, $where = NULL, $selectedArray = array())
	{
		$whereSt = "is_user=1";
		if ($status !== NULL) $whereSt .= " and status=" . $status;
		if ($where != NULL)  $whereSt .= " and " . $where;
		foreach ($this->CI->Acc_Model->getClientList($whereSt) as $dropdown) { ?>
			<option value="<?php echo $dropdown->client_id; ?>" <?php if ($dropdown->client_id == $selected || in_array($dropdown->client_id, $selectedArray)) echo ' selected'; ?>><?php echo $dropdown->clientName; ?></option>
		<?php }
	}

	/*----------------------------- CHANGES FOR CUSTOM REPORT STARTS HERE-----------------------------*/

	function getAllTableDropdown($selected = '')
	{
		foreach ($this->CI->Acc_Model->getAllTableNames() as  $value) { ?>
			<option value="<?php echo $value['table_name'] ?>" <?php echo ($value['table_name'] == $selected) ? 'selected' : '' ?>>
				<?php echo $value['table_name']; ?></option>
		<?php }
	}

	function getAllTableColumnsDropdown($table_name = '', $selected = '')
	{
		if ($table_name == '')
			return;
		foreach ($this->CI->Acc_Model->getAllTableColumns($table_name) as  $value) { ?>
			<option value="<?php echo $value['COLUMN_NAME'] ?>" <?php echo ($value['COLUMN_NAME'] == $selected) ? 'selected' : '' ?>>
				<?php echo $value['COLUMN_NAME']; ?></option>
		<?php }
	}

	function getMultiTableColumnsDropdown($table_name = '', $selected = '')
	{
		if ($table_name == '')
			return;
		foreach ($this->CI->Acc_Model->getMultiTableColumns($table_name) as  $value) { ?>
			<option value="<?php echo $value['COLUMN_NAME'] ?>" <?php echo ($value['COLUMN_NAME'] == $selected) ? 'selected' : '' ?>>
				<?php echo $value['COLUMN_NAME']; ?></option>
		<?php }
	}

	function getAggrigateFunctionDropdown($select = '')
	{
		$array = ["count", "min", "max", "sum", "avg"];
		$this->bindSelectBox(array_combine($array, $array), $select);
	}


	function bindSelectBox($array, $select = '')
	{
		foreach ($array as $key => $value) { ?>
			<option value="<?php echo $key; ?>" <?php echo ($select == $key) ? 'selected' : ''; ?>><?php echo $value; ?></option>
<?php
		}
	}

	function searchFieldOperatorDropdown($select = '')
	{
		$array = ["<=", ">=", "=", "!=", "<", ">", "is null", "is not null"];
		$this->bindSelectBox(array_combine($array, $array), $select);
	}

	function searchDefaultOperatorDropdown($select = '')
	{
		$array = ["text", "dropdown", "multiselect", "datetime", "date"];
		$this->bindSelectBox(array_combine($array, $array), $select);
	}

	/*----------------------------- CHANGES FOR CUSTOM REPORT ENDS HERE-----------------------------*/
}



?>