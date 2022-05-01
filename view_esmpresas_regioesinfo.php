<?php

// Global variable for table object
$view_esmpresas_regioes = NULL;

//
// Table class for view_esmpresas_regioes
//
class cview_esmpresas_regioes extends cTable {
	var $A04imagem;
	var $cod_empresa;
	var $nome_empresa;
	var $endereco;
	var $numero;
	var $telefone;
	var $regiao;
	var $A21EMPRESA;
	var $A01URL;
	var $regiao1;
	var $Count28funcionarios_cod_func29;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'view_esmpresas_regioes';
		$this->TableName = 'view_esmpresas_regioes';
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

		// A04imagem
		$this->A04imagem = new cField('view_esmpresas_regioes', 'view_esmpresas_regioes', 'x_A04imagem', 'A04imagem', '`A04imagem`', '`A04imagem`', 200, -1, FALSE, '`A04imagem`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A04imagem'] = &$this->A04imagem;

		// cod_empresa
		$this->cod_empresa = new cField('view_esmpresas_regioes', 'view_esmpresas_regioes', 'x_cod_empresa', 'cod_empresa', '`cod_empresa`', '`cod_empresa`', 3, -1, FALSE, '`cod_empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cod_empresa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cod_empresa'] = &$this->cod_empresa;

		// nome_empresa
		$this->nome_empresa = new cField('view_esmpresas_regioes', 'view_esmpresas_regioes', 'x_nome_empresa', 'nome_empresa', '`nome_empresa`', '`nome_empresa`', 200, -1, FALSE, '`nome_empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nome_empresa'] = &$this->nome_empresa;

		// endereco
		$this->endereco = new cField('view_esmpresas_regioes', 'view_esmpresas_regioes', 'x_endereco', 'endereco', '`endereco`', '`endereco`', 200, -1, FALSE, '`endereco`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['endereco'] = &$this->endereco;

		// numero
		$this->numero = new cField('view_esmpresas_regioes', 'view_esmpresas_regioes', 'x_numero', 'numero', '`numero`', '`numero`', 3, -1, FALSE, '`numero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->numero->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['numero'] = &$this->numero;

		// telefone
		$this->telefone = new cField('view_esmpresas_regioes', 'view_esmpresas_regioes', 'x_telefone', 'telefone', '`telefone`', '`telefone`', 200, -1, FALSE, '`telefone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['telefone'] = &$this->telefone;

		// regiao
		$this->regiao = new cField('view_esmpresas_regioes', 'view_esmpresas_regioes', 'x_regiao', 'regiao', '`regiao`', '`regiao`', 3, -1, FALSE, '`regiao`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->regiao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['regiao'] = &$this->regiao;

		// A21EMPRESA
		$this->A21EMPRESA = new cField('view_esmpresas_regioes', 'view_esmpresas_regioes', 'x_A21EMPRESA', 'A21EMPRESA', '`A21EMPRESA`', '`A21EMPRESA`', 200, -1, FALSE, '`A21EMPRESA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A21EMPRESA'] = &$this->A21EMPRESA;

		// A01URL
		$this->A01URL = new cField('view_esmpresas_regioes', 'view_esmpresas_regioes', 'x_A01URL', 'A01URL', '`A01URL`', '`A01URL`', 200, -1, FALSE, '`A01URL`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A01URL'] = &$this->A01URL;

		// regiao1
		$this->regiao1 = new cField('view_esmpresas_regioes', 'view_esmpresas_regioes', 'x_regiao1', 'regiao1', '`regiao1`', '`regiao1`', 200, -1, FALSE, '`regiao1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['regiao1'] = &$this->regiao1;

		// Count(funcionarios.cod_func)
		$this->Count28funcionarios_cod_func29 = new cField('view_esmpresas_regioes', 'view_esmpresas_regioes', 'x_Count28funcionarios_cod_func29', 'Count(funcionarios.cod_func)', '`Count(funcionarios.cod_func)`', '`Count(funcionarios.cod_func)`', 20, -1, FALSE, '`Count(funcionarios.cod_func)`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Count28funcionarios_cod_func29->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Count(funcionarios.cod_func)'] = &$this->Count28funcionarios_cod_func29;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`view_esmpresas_regioes`";
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
	var $UpdateTable = "`view_esmpresas_regioes`";

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
			return "view_esmpresas_regioeslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "view_esmpresas_regioeslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("view_esmpresas_regioesview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("view_esmpresas_regioesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "view_esmpresas_regioesadd.php?" . $this->UrlParm($parm);
		else
			return "view_esmpresas_regioesadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("view_esmpresas_regioesedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("view_esmpresas_regioesadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("view_esmpresas_regioesdelete.php", $this->UrlParm());
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
		$this->A04imagem->setDbValue($rs->fields('A04imagem'));
		$this->cod_empresa->setDbValue($rs->fields('cod_empresa'));
		$this->nome_empresa->setDbValue($rs->fields('nome_empresa'));
		$this->endereco->setDbValue($rs->fields('endereco'));
		$this->numero->setDbValue($rs->fields('numero'));
		$this->telefone->setDbValue($rs->fields('telefone'));
		$this->regiao->setDbValue($rs->fields('regiao'));
		$this->A21EMPRESA->setDbValue($rs->fields('A21EMPRESA'));
		$this->A01URL->setDbValue($rs->fields('A01URL'));
		$this->regiao1->setDbValue($rs->fields('regiao1'));
		$this->Count28funcionarios_cod_func29->setDbValue($rs->fields('Count(funcionarios.cod_func)'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// A04imagem
		// cod_empresa
		// nome_empresa
		// endereco
		// numero
		// telefone
		// regiao
		// A21EMPRESA
		// A01URL
		// regiao1
		// Count(funcionarios.cod_func)
		// A04imagem

		$this->A04imagem->ViewValue = $this->A04imagem->CurrentValue;
		$this->A04imagem->ViewCustomAttributes = "";

		// cod_empresa
		$this->cod_empresa->ViewValue = $this->cod_empresa->CurrentValue;
		$this->cod_empresa->ViewCustomAttributes = "";

		// nome_empresa
		$this->nome_empresa->ViewValue = $this->nome_empresa->CurrentValue;
		$this->nome_empresa->ViewCustomAttributes = "";

		// endereco
		$this->endereco->ViewValue = $this->endereco->CurrentValue;
		$this->endereco->ViewCustomAttributes = "";

		// numero
		$this->numero->ViewValue = $this->numero->CurrentValue;
		$this->numero->ViewCustomAttributes = "";

		// telefone
		$this->telefone->ViewValue = $this->telefone->CurrentValue;
		$this->telefone->ViewCustomAttributes = "";

		// regiao
		$this->regiao->ViewValue = $this->regiao->CurrentValue;
		$this->regiao->ViewCustomAttributes = "";

		// A21EMPRESA
		$this->A21EMPRESA->ViewValue = $this->A21EMPRESA->CurrentValue;
		$this->A21EMPRESA->ViewCustomAttributes = "";

		// A01URL
		$this->A01URL->ViewValue = $this->A01URL->CurrentValue;
		$this->A01URL->ViewCustomAttributes = "";

		// regiao1
		$this->regiao1->ViewValue = $this->regiao1->CurrentValue;
		$this->regiao1->ViewCustomAttributes = "";

		// Count(funcionarios.cod_func)
		$this->Count28funcionarios_cod_func29->ViewValue = $this->Count28funcionarios_cod_func29->CurrentValue;
		$this->Count28funcionarios_cod_func29->ViewCustomAttributes = "";

		// A04imagem
		$this->A04imagem->LinkCustomAttributes = "";
		$this->A04imagem->HrefValue = "";
		$this->A04imagem->TooltipValue = "";

		// cod_empresa
		$this->cod_empresa->LinkCustomAttributes = "";
		$this->cod_empresa->HrefValue = "";
		$this->cod_empresa->TooltipValue = "";

		// nome_empresa
		$this->nome_empresa->LinkCustomAttributes = "";
		$this->nome_empresa->HrefValue = "";
		$this->nome_empresa->TooltipValue = "";

		// endereco
		$this->endereco->LinkCustomAttributes = "";
		$this->endereco->HrefValue = "";
		$this->endereco->TooltipValue = "";

		// numero
		$this->numero->LinkCustomAttributes = "";
		$this->numero->HrefValue = "";
		$this->numero->TooltipValue = "";

		// telefone
		$this->telefone->LinkCustomAttributes = "";
		$this->telefone->HrefValue = "";
		$this->telefone->TooltipValue = "";

		// regiao
		$this->regiao->LinkCustomAttributes = "";
		$this->regiao->HrefValue = "";
		$this->regiao->TooltipValue = "";

		// A21EMPRESA
		$this->A21EMPRESA->LinkCustomAttributes = "";
		$this->A21EMPRESA->HrefValue = "";
		$this->A21EMPRESA->TooltipValue = "";

		// A01URL
		$this->A01URL->LinkCustomAttributes = "";
		$this->A01URL->HrefValue = "";
		$this->A01URL->TooltipValue = "";

		// regiao1
		$this->regiao1->LinkCustomAttributes = "";
		$this->regiao1->HrefValue = "";
		$this->regiao1->TooltipValue = "";

		// Count(funcionarios.cod_func)
		$this->Count28funcionarios_cod_func29->LinkCustomAttributes = "";
		$this->Count28funcionarios_cod_func29->HrefValue = "";
		$this->Count28funcionarios_cod_func29->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// A04imagem
		$this->A04imagem->EditAttrs["class"] = "form-control";
		$this->A04imagem->EditCustomAttributes = "";
		$this->A04imagem->EditValue = ew_HtmlEncode($this->A04imagem->CurrentValue);
		$this->A04imagem->PlaceHolder = ew_RemoveHtml($this->A04imagem->FldCaption());

		// cod_empresa
		$this->cod_empresa->EditAttrs["class"] = "form-control";
		$this->cod_empresa->EditCustomAttributes = "";
		$this->cod_empresa->EditValue = $this->cod_empresa->CurrentValue;
		$this->cod_empresa->ViewCustomAttributes = "";

		// nome_empresa
		$this->nome_empresa->EditAttrs["class"] = "form-control";
		$this->nome_empresa->EditCustomAttributes = "";
		$this->nome_empresa->EditValue = ew_HtmlEncode($this->nome_empresa->CurrentValue);
		$this->nome_empresa->PlaceHolder = ew_RemoveHtml($this->nome_empresa->FldCaption());

		// endereco
		$this->endereco->EditAttrs["class"] = "form-control";
		$this->endereco->EditCustomAttributes = "";
		$this->endereco->EditValue = ew_HtmlEncode($this->endereco->CurrentValue);
		$this->endereco->PlaceHolder = ew_RemoveHtml($this->endereco->FldCaption());

		// numero
		$this->numero->EditAttrs["class"] = "form-control";
		$this->numero->EditCustomAttributes = "";
		$this->numero->EditValue = ew_HtmlEncode($this->numero->CurrentValue);
		$this->numero->PlaceHolder = ew_RemoveHtml($this->numero->FldCaption());

		// telefone
		$this->telefone->EditAttrs["class"] = "form-control";
		$this->telefone->EditCustomAttributes = "";
		$this->telefone->EditValue = ew_HtmlEncode($this->telefone->CurrentValue);
		$this->telefone->PlaceHolder = ew_RemoveHtml($this->telefone->FldCaption());

		// regiao
		$this->regiao->EditAttrs["class"] = "form-control";
		$this->regiao->EditCustomAttributes = "";
		$this->regiao->EditValue = ew_HtmlEncode($this->regiao->CurrentValue);
		$this->regiao->PlaceHolder = ew_RemoveHtml($this->regiao->FldCaption());

		// A21EMPRESA
		$this->A21EMPRESA->EditAttrs["class"] = "form-control";
		$this->A21EMPRESA->EditCustomAttributes = "";
		$this->A21EMPRESA->EditValue = ew_HtmlEncode($this->A21EMPRESA->CurrentValue);
		$this->A21EMPRESA->PlaceHolder = ew_RemoveHtml($this->A21EMPRESA->FldCaption());

		// A01URL
		$this->A01URL->EditAttrs["class"] = "form-control";
		$this->A01URL->EditCustomAttributes = "";
		$this->A01URL->EditValue = ew_HtmlEncode($this->A01URL->CurrentValue);
		$this->A01URL->PlaceHolder = ew_RemoveHtml($this->A01URL->FldCaption());

		// regiao1
		$this->regiao1->EditAttrs["class"] = "form-control";
		$this->regiao1->EditCustomAttributes = "";
		$this->regiao1->EditValue = ew_HtmlEncode($this->regiao1->CurrentValue);
		$this->regiao1->PlaceHolder = ew_RemoveHtml($this->regiao1->FldCaption());

		// Count(funcionarios.cod_func)
		$this->Count28funcionarios_cod_func29->EditAttrs["class"] = "form-control";
		$this->Count28funcionarios_cod_func29->EditCustomAttributes = "";
		$this->Count28funcionarios_cod_func29->EditValue = ew_HtmlEncode($this->Count28funcionarios_cod_func29->CurrentValue);
		$this->Count28funcionarios_cod_func29->PlaceHolder = ew_RemoveHtml($this->Count28funcionarios_cod_func29->FldCaption());

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
					if ($this->A04imagem->Exportable) $Doc->ExportCaption($this->A04imagem);
					if ($this->cod_empresa->Exportable) $Doc->ExportCaption($this->cod_empresa);
					if ($this->nome_empresa->Exportable) $Doc->ExportCaption($this->nome_empresa);
					if ($this->endereco->Exportable) $Doc->ExportCaption($this->endereco);
					if ($this->numero->Exportable) $Doc->ExportCaption($this->numero);
					if ($this->telefone->Exportable) $Doc->ExportCaption($this->telefone);
					if ($this->regiao->Exportable) $Doc->ExportCaption($this->regiao);
					if ($this->A21EMPRESA->Exportable) $Doc->ExportCaption($this->A21EMPRESA);
					if ($this->A01URL->Exportable) $Doc->ExportCaption($this->A01URL);
					if ($this->regiao1->Exportable) $Doc->ExportCaption($this->regiao1);
					if ($this->Count28funcionarios_cod_func29->Exportable) $Doc->ExportCaption($this->Count28funcionarios_cod_func29);
				} else {
					if ($this->A04imagem->Exportable) $Doc->ExportCaption($this->A04imagem);
					if ($this->cod_empresa->Exportable) $Doc->ExportCaption($this->cod_empresa);
					if ($this->nome_empresa->Exportable) $Doc->ExportCaption($this->nome_empresa);
					if ($this->endereco->Exportable) $Doc->ExportCaption($this->endereco);
					if ($this->numero->Exportable) $Doc->ExportCaption($this->numero);
					if ($this->telefone->Exportable) $Doc->ExportCaption($this->telefone);
					if ($this->regiao->Exportable) $Doc->ExportCaption($this->regiao);
					if ($this->A21EMPRESA->Exportable) $Doc->ExportCaption($this->A21EMPRESA);
					if ($this->A01URL->Exportable) $Doc->ExportCaption($this->A01URL);
					if ($this->regiao1->Exportable) $Doc->ExportCaption($this->regiao1);
					if ($this->Count28funcionarios_cod_func29->Exportable) $Doc->ExportCaption($this->Count28funcionarios_cod_func29);
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
						if ($this->A04imagem->Exportable) $Doc->ExportField($this->A04imagem);
						if ($this->cod_empresa->Exportable) $Doc->ExportField($this->cod_empresa);
						if ($this->nome_empresa->Exportable) $Doc->ExportField($this->nome_empresa);
						if ($this->endereco->Exportable) $Doc->ExportField($this->endereco);
						if ($this->numero->Exportable) $Doc->ExportField($this->numero);
						if ($this->telefone->Exportable) $Doc->ExportField($this->telefone);
						if ($this->regiao->Exportable) $Doc->ExportField($this->regiao);
						if ($this->A21EMPRESA->Exportable) $Doc->ExportField($this->A21EMPRESA);
						if ($this->A01URL->Exportable) $Doc->ExportField($this->A01URL);
						if ($this->regiao1->Exportable) $Doc->ExportField($this->regiao1);
						if ($this->Count28funcionarios_cod_func29->Exportable) $Doc->ExportField($this->Count28funcionarios_cod_func29);
					} else {
						if ($this->A04imagem->Exportable) $Doc->ExportField($this->A04imagem);
						if ($this->cod_empresa->Exportable) $Doc->ExportField($this->cod_empresa);
						if ($this->nome_empresa->Exportable) $Doc->ExportField($this->nome_empresa);
						if ($this->endereco->Exportable) $Doc->ExportField($this->endereco);
						if ($this->numero->Exportable) $Doc->ExportField($this->numero);
						if ($this->telefone->Exportable) $Doc->ExportField($this->telefone);
						if ($this->regiao->Exportable) $Doc->ExportField($this->regiao);
						if ($this->A21EMPRESA->Exportable) $Doc->ExportField($this->A21EMPRESA);
						if ($this->A01URL->Exportable) $Doc->ExportField($this->A01URL);
						if ($this->regiao1->Exportable) $Doc->ExportField($this->regiao1);
						if ($this->Count28funcionarios_cod_func29->Exportable) $Doc->ExportField($this->Count28funcionarios_cod_func29);
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
