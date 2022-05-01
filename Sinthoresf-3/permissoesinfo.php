<?php

// Global variable for table object
$permissoes = NULL;

//
// Table class for permissoes
//
class cpermissoes extends cTable {
	var $a00id;
	var $a01nome;
	var $a02email;
	var $a03senha;
	var $a04ativo;
	var $a05cadastro;
	var $a06permissoes;
	var $a07ultimo;
	var $a08acessos;
	var $a09tipo;
	var $a10full;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'permissoes';
		$this->TableName = 'permissoes';
		$this->TableType = 'TABLE';
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

		// a00id
		$this->a00id = new cField('permissoes', 'permissoes', 'x_a00id', 'a00id', '`a00id`', '`a00id`', 3, -1, FALSE, '`a00id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->a00id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['a00id'] = &$this->a00id;

		// a01nome
		$this->a01nome = new cField('permissoes', 'permissoes', 'x_a01nome', 'a01nome', '`a01nome`', '`a01nome`', 200, -1, FALSE, '`a01nome`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['a01nome'] = &$this->a01nome;

		// a02email
		$this->a02email = new cField('permissoes', 'permissoes', 'x_a02email', 'a02email', '`a02email`', '`a02email`', 200, -1, FALSE, '`a02email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->a02email->FldDefaultErrMsg = $Language->Phrase("IncorrectEmail");
		$this->fields['a02email'] = &$this->a02email;

		// a03senha
		$this->a03senha = new cField('permissoes', 'permissoes', 'x_a03senha', 'a03senha', '`a03senha`', '`a03senha`', 200, -1, FALSE, '`a03senha`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['a03senha'] = &$this->a03senha;

		// a04ativo
		$this->a04ativo = new cField('permissoes', 'permissoes', 'x_a04ativo', 'a04ativo', '`a04ativo`', '`a04ativo`', 3, -1, FALSE, '`a04ativo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->a04ativo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['a04ativo'] = &$this->a04ativo;

		// a05cadastro
		$this->a05cadastro = new cField('permissoes', 'permissoes', 'x_a05cadastro', 'a05cadastro', '`a05cadastro`', 'DATE_FORMAT(`a05cadastro`, \'%d/%m/%Y\')', 135, 7, FALSE, '`a05cadastro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->a05cadastro->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['a05cadastro'] = &$this->a05cadastro;

		// a06permissoes
		$this->a06permissoes = new cField('permissoes', 'permissoes', 'x_a06permissoes', 'a06permissoes', '`a06permissoes`', '`a06permissoes`', 200, -1, FALSE, '`a06permissoes`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['a06permissoes'] = &$this->a06permissoes;

		// a07ultimo
		$this->a07ultimo = new cField('permissoes', 'permissoes', 'x_a07ultimo', 'a07ultimo', '`a07ultimo`', 'DATE_FORMAT(`a07ultimo`, \'%d/%m/%Y\')', 135, 7, FALSE, '`a07ultimo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->a07ultimo->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['a07ultimo'] = &$this->a07ultimo;

		// a08acessos
		$this->a08acessos = new cField('permissoes', 'permissoes', 'x_a08acessos', 'a08acessos', '`a08acessos`', '`a08acessos`', 3, -1, FALSE, '`a08acessos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->a08acessos->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['a08acessos'] = &$this->a08acessos;

		// a09tipo
		$this->a09tipo = new cField('permissoes', 'permissoes', 'x_a09tipo', 'a09tipo', '`a09tipo`', '`a09tipo`', 3, -1, FALSE, '`a09tipo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->a09tipo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['a09tipo'] = &$this->a09tipo;

		// a10full
		$this->a10full = new cField('permissoes', 'permissoes', 'x_a10full', 'a10full', '`a10full`', '`a10full`', 3, -1, FALSE, '`a10full`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->a10full->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['a10full'] = &$this->a10full;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`permissoes`";
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
	var $UpdateTable = "`permissoes`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			if (EW_ENCRYPTED_PASSWORD && $name == 'a03senha')
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
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
			if (EW_ENCRYPTED_PASSWORD && $name == 'a03senha') {
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			}
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
			if (array_key_exists('a00id', $rs))
				ew_AddFilter($where, ew_QuotedName('a00id') . '=' . ew_QuotedValue($rs['a00id'], $this->a00id->FldDataType));
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
		return "`a00id` = @a00id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->a00id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@a00id@", ew_AdjustSql($this->a00id->CurrentValue), $sKeyFilter); // Replace key value
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
			return "permissoeslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "permissoeslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("permissoesview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("permissoesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "permissoesadd.php?" . $this->UrlParm($parm);
		else
			return "permissoesadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("permissoesedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("permissoesadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("permissoesdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->a00id->CurrentValue)) {
			$sUrl .= "a00id=" . urlencode($this->a00id->CurrentValue);
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
			$arKeys[] = @$_GET["a00id"]; // a00id

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
			$this->a00id->CurrentValue = $key;
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
		$this->a00id->setDbValue($rs->fields('a00id'));
		$this->a01nome->setDbValue($rs->fields('a01nome'));
		$this->a02email->setDbValue($rs->fields('a02email'));
		$this->a03senha->setDbValue($rs->fields('a03senha'));
		$this->a04ativo->setDbValue($rs->fields('a04ativo'));
		$this->a05cadastro->setDbValue($rs->fields('a05cadastro'));
		$this->a06permissoes->setDbValue($rs->fields('a06permissoes'));
		$this->a07ultimo->setDbValue($rs->fields('a07ultimo'));
		$this->a08acessos->setDbValue($rs->fields('a08acessos'));
		$this->a09tipo->setDbValue($rs->fields('a09tipo'));
		$this->a10full->setDbValue($rs->fields('a10full'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// a00id
		// a01nome
		// a02email
		// a03senha
		// a04ativo

		$this->a04ativo->CellCssStyle = "white-space: nowrap;";

		// a05cadastro
		$this->a05cadastro->CellCssStyle = "white-space: nowrap;";

		// a06permissoes
		$this->a06permissoes->CellCssStyle = "white-space: nowrap;";

		// a07ultimo
		$this->a07ultimo->CellCssStyle = "white-space: nowrap;";

		// a08acessos
		$this->a08acessos->CellCssStyle = "white-space: nowrap;";

		// a09tipo
		$this->a09tipo->CellCssStyle = "white-space: nowrap;";

		// a10full
		$this->a10full->CellCssStyle = "white-space: nowrap;";

		// a00id
		$this->a00id->ViewValue = $this->a00id->CurrentValue;
		$this->a00id->ViewCustomAttributes = "";

		// a01nome
		$this->a01nome->ViewValue = $this->a01nome->CurrentValue;
		$this->a01nome->ViewCustomAttributes = "";

		// a02email
		$this->a02email->ViewValue = $this->a02email->CurrentValue;
		$this->a02email->ViewCustomAttributes = "";

		// a03senha
		$this->a03senha->ViewValue = "********";
		$this->a03senha->ViewCustomAttributes = "";

		// a04ativo
		$this->a04ativo->ViewValue = $this->a04ativo->CurrentValue;
		$this->a04ativo->ViewCustomAttributes = "";

		// a05cadastro
		$this->a05cadastro->ViewValue = $this->a05cadastro->CurrentValue;
		$this->a05cadastro->ViewValue = ew_FormatDateTime($this->a05cadastro->ViewValue, 7);
		$this->a05cadastro->ViewCustomAttributes = "";

		// a06permissoes
		$this->a06permissoes->ViewValue = $this->a06permissoes->CurrentValue;
		$this->a06permissoes->ViewCustomAttributes = "";

		// a07ultimo
		$this->a07ultimo->ViewValue = $this->a07ultimo->CurrentValue;
		$this->a07ultimo->ViewValue = ew_FormatDateTime($this->a07ultimo->ViewValue, 7);
		$this->a07ultimo->ViewCustomAttributes = "";

		// a08acessos
		$this->a08acessos->ViewValue = $this->a08acessos->CurrentValue;
		$this->a08acessos->ViewCustomAttributes = "";

		// a09tipo
		$this->a09tipo->ViewValue = $this->a09tipo->CurrentValue;
		$this->a09tipo->ViewCustomAttributes = "";

		// a10full
		$this->a10full->ViewValue = $this->a10full->CurrentValue;
		$this->a10full->ViewCustomAttributes = "";

		// a00id
		$this->a00id->LinkCustomAttributes = "";
		$this->a00id->HrefValue = "";
		$this->a00id->TooltipValue = "";

		// a01nome
		$this->a01nome->LinkCustomAttributes = "";
		$this->a01nome->HrefValue = "";
		$this->a01nome->TooltipValue = "";

		// a02email
		$this->a02email->LinkCustomAttributes = "";
		$this->a02email->HrefValue = "";
		$this->a02email->TooltipValue = "";

		// a03senha
		$this->a03senha->LinkCustomAttributes = "";
		$this->a03senha->HrefValue = "";
		$this->a03senha->TooltipValue = "";

		// a04ativo
		$this->a04ativo->LinkCustomAttributes = "";
		$this->a04ativo->HrefValue = "";
		$this->a04ativo->TooltipValue = "";

		// a05cadastro
		$this->a05cadastro->LinkCustomAttributes = "";
		$this->a05cadastro->HrefValue = "";
		$this->a05cadastro->TooltipValue = "";

		// a06permissoes
		$this->a06permissoes->LinkCustomAttributes = "";
		$this->a06permissoes->HrefValue = "";
		$this->a06permissoes->TooltipValue = "";

		// a07ultimo
		$this->a07ultimo->LinkCustomAttributes = "";
		$this->a07ultimo->HrefValue = "";
		$this->a07ultimo->TooltipValue = "";

		// a08acessos
		$this->a08acessos->LinkCustomAttributes = "";
		$this->a08acessos->HrefValue = "";
		$this->a08acessos->TooltipValue = "";

		// a09tipo
		$this->a09tipo->LinkCustomAttributes = "";
		$this->a09tipo->HrefValue = "";
		$this->a09tipo->TooltipValue = "";

		// a10full
		$this->a10full->LinkCustomAttributes = "";
		$this->a10full->HrefValue = "";
		$this->a10full->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// a00id
		$this->a00id->EditAttrs["class"] = "form-control";
		$this->a00id->EditCustomAttributes = "";
		$this->a00id->EditValue = $this->a00id->CurrentValue;
		$this->a00id->ViewCustomAttributes = "";

		// a01nome
		$this->a01nome->EditAttrs["class"] = "form-control";
		$this->a01nome->EditCustomAttributes = "";
		$this->a01nome->EditValue = ew_HtmlEncode($this->a01nome->CurrentValue);
		$this->a01nome->PlaceHolder = ew_RemoveHtml($this->a01nome->FldCaption());

		// a02email
		$this->a02email->EditAttrs["class"] = "form-control";
		$this->a02email->EditCustomAttributes = "";
		$this->a02email->EditValue = ew_HtmlEncode($this->a02email->CurrentValue);
		$this->a02email->PlaceHolder = ew_RemoveHtml($this->a02email->FldCaption());

		// a03senha
		$this->a03senha->EditAttrs["class"] = "form-control";
		$this->a03senha->EditCustomAttributes = "";
		$this->a03senha->EditValue = ew_HtmlEncode($this->a03senha->CurrentValue);
		$this->a03senha->PlaceHolder = ew_RemoveHtml($this->a03senha->FldCaption());

		// a04ativo
		$this->a04ativo->EditAttrs["class"] = "form-control";
		$this->a04ativo->EditCustomAttributes = "";
		$this->a04ativo->EditValue = ew_HtmlEncode($this->a04ativo->CurrentValue);
		$this->a04ativo->PlaceHolder = ew_RemoveHtml($this->a04ativo->FldCaption());

		// a05cadastro
		$this->a05cadastro->EditAttrs["class"] = "form-control";
		$this->a05cadastro->EditCustomAttributes = "";
		$this->a05cadastro->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->a05cadastro->CurrentValue, 7));
		$this->a05cadastro->PlaceHolder = ew_RemoveHtml($this->a05cadastro->FldCaption());

		// a06permissoes
		$this->a06permissoes->EditAttrs["class"] = "form-control";
		$this->a06permissoes->EditCustomAttributes = "";
		$this->a06permissoes->EditValue = ew_HtmlEncode($this->a06permissoes->CurrentValue);
		$this->a06permissoes->PlaceHolder = ew_RemoveHtml($this->a06permissoes->FldCaption());

		// a07ultimo
		$this->a07ultimo->EditAttrs["class"] = "form-control";
		$this->a07ultimo->EditCustomAttributes = "";
		$this->a07ultimo->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->a07ultimo->CurrentValue, 7));
		$this->a07ultimo->PlaceHolder = ew_RemoveHtml($this->a07ultimo->FldCaption());

		// a08acessos
		$this->a08acessos->EditAttrs["class"] = "form-control";
		$this->a08acessos->EditCustomAttributes = "";
		$this->a08acessos->EditValue = ew_HtmlEncode($this->a08acessos->CurrentValue);
		$this->a08acessos->PlaceHolder = ew_RemoveHtml($this->a08acessos->FldCaption());

		// a09tipo
		$this->a09tipo->EditAttrs["class"] = "form-control";
		$this->a09tipo->EditCustomAttributes = "";
		$this->a09tipo->EditValue = ew_HtmlEncode($this->a09tipo->CurrentValue);
		$this->a09tipo->PlaceHolder = ew_RemoveHtml($this->a09tipo->FldCaption());

		// a10full
		$this->a10full->EditAttrs["class"] = "form-control";
		$this->a10full->EditCustomAttributes = "";
		$this->a10full->EditValue = ew_HtmlEncode($this->a10full->CurrentValue);
		$this->a10full->PlaceHolder = ew_RemoveHtml($this->a10full->FldCaption());

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
					if ($this->a00id->Exportable) $Doc->ExportCaption($this->a00id);
					if ($this->a01nome->Exportable) $Doc->ExportCaption($this->a01nome);
					if ($this->a02email->Exportable) $Doc->ExportCaption($this->a02email);
					if ($this->a03senha->Exportable) $Doc->ExportCaption($this->a03senha);
				} else {
					if ($this->a00id->Exportable) $Doc->ExportCaption($this->a00id);
					if ($this->a01nome->Exportable) $Doc->ExportCaption($this->a01nome);
					if ($this->a02email->Exportable) $Doc->ExportCaption($this->a02email);
					if ($this->a03senha->Exportable) $Doc->ExportCaption($this->a03senha);
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
						if ($this->a00id->Exportable) $Doc->ExportField($this->a00id);
						if ($this->a01nome->Exportable) $Doc->ExportField($this->a01nome);
						if ($this->a02email->Exportable) $Doc->ExportField($this->a02email);
						if ($this->a03senha->Exportable) $Doc->ExportField($this->a03senha);
					} else {
						if ($this->a00id->Exportable) $Doc->ExportField($this->a00id);
						if ($this->a01nome->Exportable) $Doc->ExportField($this->a01nome);
						if ($this->a02email->Exportable) $Doc->ExportField($this->a02email);
						if ($this->a03senha->Exportable) $Doc->ExportField($this->a03senha);
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
