<?php

// Global variable for table object
$view_recibo_empresa = NULL;

//
// Table class for view_recibo_empresa
//
class cview_recibo_empresa extends cTable {
	var $nome_empresa;
	var $nome;
	var $ativo;
	var $A08NOME;
	var $valor_mensal;
	var $valor_extenso;
	var $cod_empresa;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'view_recibo_empresa';
		$this->TableName = 'view_recibo_empresa';
		$this->TableType = 'VIEW';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// nome_empresa
		$this->nome_empresa = new cField('view_recibo_empresa', 'view_recibo_empresa', 'x_nome_empresa', 'nome_empresa', '`nome_empresa`', '`nome_empresa`', 200, -1, FALSE, '`nome_empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nome_empresa'] = &$this->nome_empresa;

		// nome
		$this->nome = new cField('view_recibo_empresa', 'view_recibo_empresa', 'x_nome', 'nome', '`nome`', '`nome`', 200, -1, FALSE, '`nome`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nome'] = &$this->nome;

		// ativo
		$this->ativo = new cField('view_recibo_empresa', 'view_recibo_empresa', 'x_ativo', 'ativo', '`ativo`', '`ativo`', 3, -1, FALSE, '`ativo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ativo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ativo'] = &$this->ativo;

		// A08NOME
		$this->A08NOME = new cField('view_recibo_empresa', 'view_recibo_empresa', 'x_A08NOME', 'A08NOME', '`A08NOME`', '`A08NOME`', 200, -1, FALSE, '`A08NOME`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A08NOME'] = &$this->A08NOME;

		// valor_mensal
		$this->valor_mensal = new cField('view_recibo_empresa', 'view_recibo_empresa', 'x_valor_mensal', 'valor_mensal', '`valor_mensal`', '`valor_mensal`', 200, -1, FALSE, '`valor_mensal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['valor_mensal'] = &$this->valor_mensal;

		// valor_extenso
		$this->valor_extenso = new cField('view_recibo_empresa', 'view_recibo_empresa', 'x_valor_extenso', 'valor_extenso', '`valor_extenso`', '`valor_extenso`', 200, -1, FALSE, '`valor_extenso`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['valor_extenso'] = &$this->valor_extenso;

		// cod_empresa
		$this->cod_empresa = new cField('view_recibo_empresa', 'view_recibo_empresa', 'x_cod_empresa', 'cod_empresa', '`cod_empresa`', '`cod_empresa`', 3, -1, FALSE, '`cod_empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cod_empresa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cod_empresa'] = &$this->cod_empresa;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`view_recibo_empresa`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`view_recibo_empresa`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('cod_empresa', $rs))
				ew_AddFilter($where, ew_QuotedName('cod_empresa') . '=' . ew_QuotedValue($rs['cod_empresa'], $this->cod_empresa->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`cod_empresa` = @cod_empresa@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->cod_empresa->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@cod_empresa@", ew_AdjustSql($this->cod_empresa->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "view_recibo_empresalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "view_recibo_empresalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("view_recibo_empresaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("view_recibo_empresaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "view_recibo_empresaadd.php?" . $this->UrlParm($parm);
		else
			return "view_recibo_empresaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("view_recibo_empresaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("view_recibo_empresaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("view_recibo_empresadelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->cod_empresa->CurrentValue)) {
			$sUrl .= "cod_empresa=" . urlencode($this->cod_empresa->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["cod_empresa"]; // cod_empresa

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->cod_empresa->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->nome_empresa->setDbValue($rs->fields('nome_empresa'));
		$this->nome->setDbValue($rs->fields('nome'));
		$this->ativo->setDbValue($rs->fields('ativo'));
		$this->A08NOME->setDbValue($rs->fields('A08NOME'));
		$this->valor_mensal->setDbValue($rs->fields('valor_mensal'));
		$this->valor_extenso->setDbValue($rs->fields('valor_extenso'));
		$this->cod_empresa->setDbValue($rs->fields('cod_empresa'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nome_empresa
		// nome
		// ativo
		// A08NOME
		// valor_mensal
		// valor_extenso
		// cod_empresa
		// nome_empresa

		$this->nome_empresa->ViewValue = $this->nome_empresa->CurrentValue;
		$this->nome_empresa->ViewCustomAttributes = "";

		// nome
		$this->nome->ViewValue = $this->nome->CurrentValue;
		$this->nome->ViewCustomAttributes = "";

		// ativo
		if (strval($this->ativo->CurrentValue) <> "") {
			switch ($this->ativo->CurrentValue) {
				case $this->ativo->FldTagValue(1):
					$this->ativo->ViewValue = $this->ativo->FldTagCaption(1) <> "" ? $this->ativo->FldTagCaption(1) : $this->ativo->CurrentValue;
					break;
				case $this->ativo->FldTagValue(2):
					$this->ativo->ViewValue = $this->ativo->FldTagCaption(2) <> "" ? $this->ativo->FldTagCaption(2) : $this->ativo->CurrentValue;
					break;
				default:
					$this->ativo->ViewValue = $this->ativo->CurrentValue;
			}
		} else {
			$this->ativo->ViewValue = NULL;
		}
		$this->ativo->ViewCustomAttributes = "";

		// A08NOME
		$this->A08NOME->ViewValue = $this->A08NOME->CurrentValue;
		$this->A08NOME->ViewCustomAttributes = "";

		// valor_mensal
		$this->valor_mensal->ViewValue = $this->valor_mensal->CurrentValue;
		$this->valor_mensal->ViewCustomAttributes = "";

		// valor_extenso
		$this->valor_extenso->ViewValue = $this->valor_extenso->CurrentValue;
		$this->valor_extenso->ViewCustomAttributes = "";

		// cod_empresa
		$this->cod_empresa->ViewValue = $this->cod_empresa->CurrentValue;
		$this->cod_empresa->ViewCustomAttributes = "";

		// nome_empresa
		$this->nome_empresa->LinkCustomAttributes = "";
		$this->nome_empresa->HrefValue = "";
		$this->nome_empresa->TooltipValue = "";

		// nome
		$this->nome->LinkCustomAttributes = "";
		$this->nome->HrefValue = "";
		$this->nome->TooltipValue = "";

		// ativo
		$this->ativo->LinkCustomAttributes = "";
		$this->ativo->HrefValue = "";
		$this->ativo->TooltipValue = "";

		// A08NOME
		$this->A08NOME->LinkCustomAttributes = "";
		$this->A08NOME->HrefValue = "";
		$this->A08NOME->TooltipValue = "";

		// valor_mensal
		$this->valor_mensal->LinkCustomAttributes = "";
		$this->valor_mensal->HrefValue = "";
		$this->valor_mensal->TooltipValue = "";

		// valor_extenso
		$this->valor_extenso->LinkCustomAttributes = "";
		$this->valor_extenso->HrefValue = "";
		$this->valor_extenso->TooltipValue = "";

		// cod_empresa
		$this->cod_empresa->LinkCustomAttributes = "";
		$this->cod_empresa->HrefValue = "";
		$this->cod_empresa->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// nome_empresa
		$this->nome_empresa->EditAttrs["class"] = "form-control";
		$this->nome_empresa->EditCustomAttributes = "";
		$this->nome_empresa->EditValue = ew_HtmlEncode($this->nome_empresa->CurrentValue);
		$this->nome_empresa->PlaceHolder = ew_RemoveHtml($this->nome_empresa->FldCaption());

		// nome
		$this->nome->EditAttrs["class"] = "form-control";
		$this->nome->EditCustomAttributes = "";
		$this->nome->EditValue = ew_HtmlEncode($this->nome->CurrentValue);
		$this->nome->PlaceHolder = ew_RemoveHtml($this->nome->FldCaption());

		// ativo
		$this->ativo->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->ativo->FldTagValue(1), $this->ativo->FldTagCaption(1) <> "" ? $this->ativo->FldTagCaption(1) : $this->ativo->FldTagValue(1));
		$arwrk[] = array($this->ativo->FldTagValue(2), $this->ativo->FldTagCaption(2) <> "" ? $this->ativo->FldTagCaption(2) : $this->ativo->FldTagValue(2));
		$this->ativo->EditValue = $arwrk;

		// A08NOME
		$this->A08NOME->EditAttrs["class"] = "form-control";
		$this->A08NOME->EditCustomAttributes = "";
		$this->A08NOME->EditValue = ew_HtmlEncode($this->A08NOME->CurrentValue);
		$this->A08NOME->PlaceHolder = ew_RemoveHtml($this->A08NOME->FldCaption());

		// valor_mensal
		$this->valor_mensal->EditAttrs["class"] = "form-control";
		$this->valor_mensal->EditCustomAttributes = "";
		$this->valor_mensal->EditValue = ew_HtmlEncode($this->valor_mensal->CurrentValue);
		$this->valor_mensal->PlaceHolder = ew_RemoveHtml($this->valor_mensal->FldCaption());

		// valor_extenso
		$this->valor_extenso->EditAttrs["class"] = "form-control";
		$this->valor_extenso->EditCustomAttributes = "";
		$this->valor_extenso->EditValue = ew_HtmlEncode($this->valor_extenso->CurrentValue);
		$this->valor_extenso->PlaceHolder = ew_RemoveHtml($this->valor_extenso->FldCaption());

		// cod_empresa
		$this->cod_empresa->EditAttrs["class"] = "form-control";
		$this->cod_empresa->EditCustomAttributes = "";
		$this->cod_empresa->EditValue = $this->cod_empresa->CurrentValue;
		$this->cod_empresa->ViewCustomAttributes = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->nome_empresa->Exportable) $Doc->ExportCaption($this->nome_empresa);
					if ($this->nome->Exportable) $Doc->ExportCaption($this->nome);
					if ($this->ativo->Exportable) $Doc->ExportCaption($this->ativo);
					if ($this->A08NOME->Exportable) $Doc->ExportCaption($this->A08NOME);
					if ($this->valor_mensal->Exportable) $Doc->ExportCaption($this->valor_mensal);
					if ($this->valor_extenso->Exportable) $Doc->ExportCaption($this->valor_extenso);
					if ($this->cod_empresa->Exportable) $Doc->ExportCaption($this->cod_empresa);
				} else {
					if ($this->nome_empresa->Exportable) $Doc->ExportCaption($this->nome_empresa);
					if ($this->nome->Exportable) $Doc->ExportCaption($this->nome);
					if ($this->ativo->Exportable) $Doc->ExportCaption($this->ativo);
					if ($this->A08NOME->Exportable) $Doc->ExportCaption($this->A08NOME);
					if ($this->valor_mensal->Exportable) $Doc->ExportCaption($this->valor_mensal);
					if ($this->valor_extenso->Exportable) $Doc->ExportCaption($this->valor_extenso);
					if ($this->cod_empresa->Exportable) $Doc->ExportCaption($this->cod_empresa);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->nome_empresa->Exportable) $Doc->ExportField($this->nome_empresa);
						if ($this->nome->Exportable) $Doc->ExportField($this->nome);
						if ($this->ativo->Exportable) $Doc->ExportField($this->ativo);
						if ($this->A08NOME->Exportable) $Doc->ExportField($this->A08NOME);
						if ($this->valor_mensal->Exportable) $Doc->ExportField($this->valor_mensal);
						if ($this->valor_extenso->Exportable) $Doc->ExportField($this->valor_extenso);
						if ($this->cod_empresa->Exportable) $Doc->ExportField($this->cod_empresa);
					} else {
						if ($this->nome_empresa->Exportable) $Doc->ExportField($this->nome_empresa);
						if ($this->nome->Exportable) $Doc->ExportField($this->nome);
						if ($this->ativo->Exportable) $Doc->ExportField($this->ativo);
						if ($this->A08NOME->Exportable) $Doc->ExportField($this->A08NOME);
						if ($this->valor_mensal->Exportable) $Doc->ExportField($this->valor_mensal);
						if ($this->valor_extenso->Exportable) $Doc->ExportField($this->valor_extenso);
						if ($this->cod_empresa->Exportable) $Doc->ExportField($this->cod_empresa);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
