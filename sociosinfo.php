<?php

// Global variable for table object
$socios = NULL;

//
// Table class for socios
//
class csocios extends cTable {
	var $cod_socio;
	var $socio;
	var $cod_empresa;
	var $dt_cadastro;
	var $validade;
	var $ativo;
	var $funcao;
	var $dt_carteira;
	var $dt_entrou_empresa;
	var $dt_entrou_categoria;
	var $acompanhante;
	var $dependentes;
	var $foto;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'socios';
		$this->TableName = 'socios';
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

		// cod_socio
		$this->cod_socio = new cField('socios', 'socios', 'x_cod_socio', 'cod_socio', '`cod_socio`', '`cod_socio`', 3, -1, FALSE, '`cod_socio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cod_socio->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cod_socio'] = &$this->cod_socio;

		// socio
		$this->socio = new cField('socios', 'socios', 'x_socio', 'socio', '`socio`', '`socio`', 3, -1, FALSE, '`EV__socio`', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->socio->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['socio'] = &$this->socio;

		// cod_empresa
		$this->cod_empresa = new cField('socios', 'socios', 'x_cod_empresa', 'cod_empresa', '`cod_empresa`', '`cod_empresa`', 3, -1, FALSE, '`EV__cod_empresa`', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->cod_empresa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cod_empresa'] = &$this->cod_empresa;

		// dt_cadastro
		$this->dt_cadastro = new cField('socios', 'socios', 'x_dt_cadastro', 'dt_cadastro', '`dt_cadastro`', 'DATE_FORMAT(`dt_cadastro`, \'%d/%m/%Y\')', 133, 7, FALSE, '`dt_cadastro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_cadastro->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_cadastro'] = &$this->dt_cadastro;

		// validade
		$this->validade = new cField('socios', 'socios', 'x_validade', 'validade', '`validade`', 'DATE_FORMAT(`validade`, \'%d/%m/%Y\')', 133, 7, FALSE, '`validade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->validade->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['validade'] = &$this->validade;

		// ativo
		$this->ativo = new cField('socios', 'socios', 'x_ativo', 'ativo', '`ativo`', '`ativo`', 3, -1, FALSE, '`ativo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ativo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ativo'] = &$this->ativo;

		// funcao
		$this->funcao = new cField('socios', 'socios', 'x_funcao', 'funcao', '`funcao`', '`funcao`', 200, -1, FALSE, '`funcao`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['funcao'] = &$this->funcao;

		// dt_carteira
		$this->dt_carteira = new cField('socios', 'socios', 'x_dt_carteira', 'dt_carteira', '`dt_carteira`', 'DATE_FORMAT(`dt_carteira`, \'%d/%m/%Y\')', 133, 7, FALSE, '`dt_carteira`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_carteira->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_carteira'] = &$this->dt_carteira;

		// dt_entrou_empresa
		$this->dt_entrou_empresa = new cField('socios', 'socios', 'x_dt_entrou_empresa', 'dt_entrou_empresa', '`dt_entrou_empresa`', 'DATE_FORMAT(`dt_entrou_empresa`, \'%d/%m/%Y\')', 133, 7, FALSE, '`dt_entrou_empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_entrou_empresa->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_entrou_empresa'] = &$this->dt_entrou_empresa;

		// dt_entrou_categoria
		$this->dt_entrou_categoria = new cField('socios', 'socios', 'x_dt_entrou_categoria', 'dt_entrou_categoria', '`dt_entrou_categoria`', 'DATE_FORMAT(`dt_entrou_categoria`, \'%d/%m/%Y\')', 133, 7, FALSE, '`dt_entrou_categoria`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_entrou_categoria->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_entrou_categoria'] = &$this->dt_entrou_categoria;

		// acompanhante
		$this->acompanhante = new cField('socios', 'socios', 'x_acompanhante', 'acompanhante', '`acompanhante`', '`acompanhante`', 201, -1, FALSE, '`acompanhante`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['acompanhante'] = &$this->acompanhante;

		// dependentes
		$this->dependentes = new cField('socios', 'socios', 'x_dependentes', 'dependentes', '`dependentes`', '`dependentes`', 201, -1, FALSE, '`dependentes`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dependentes'] = &$this->dependentes;

		// foto
		$this->foto = new cField('socios', 'socios', 'x_foto', 'foto', '`foto`', '`foto`', 200, -1, TRUE, '`foto`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->foto->ImageResize = TRUE;
		$this->foto->ResizeQuality = EW_THUMBNAIL_DEFAULT_QUALITY;
		$this->fields['foto'] = &$this->foto;
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
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($sDetailUrl == "") {
			$sDetailUrl = "socioslist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`socios`";
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
	var $_SqlSelectList = "";

	function getSqlSelectList() { // Select for List page
		$select = "";
		$select = "SELECT * FROM (" .
			"SELECT *, (SELECT `nome` FROM `pessoas` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`cod_pessoa` = `socios`.`socio` LIMIT 1) AS `EV__socio`, (SELECT `nome_empresa` FROM `empresas` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`cod_empresa` = `socios`.`cod_empresa` LIMIT 1) AS `EV__cod_empresa` FROM `socios`" .
			") `EW_TMP_TABLE`";
		return ($this->_SqlSelectList <> "") ? $this->_SqlSelectList : $select;
	}

	function SqlSelectList() { // For backward compatibility
    	return $this->getSqlSelectList();
	}

	function setSqlSelectList($v) {
    	$this->_SqlSelectList = $v;
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`cod_socio` DESC";
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
		if ($this->UseVirtualFields()) {
			$sSort = $this->getSessionOrderByList();
			return ew_BuildSelectSql($this->getSqlSelectList(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		} else {
			$sSort = $this->getSessionOrderBy();
			return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		}
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->getSessionWhere();
		$sOrderBy = $this->getSessionOrderByList();
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->socio->AdvancedSearch->SearchValue <> "" ||
			$this->socio->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->socio->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->socio->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->cod_empresa->AdvancedSearch->SearchValue <> "" ||
			$this->cod_empresa->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->cod_empresa->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->cod_empresa->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
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
	var $UpdateTable = "`socios`";

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
			if (array_key_exists('cod_socio', $rs))
				ew_AddFilter($where, ew_QuotedName('cod_socio') . '=' . ew_QuotedValue($rs['cod_socio'], $this->cod_socio->FldDataType));
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
		return "`cod_socio` = @cod_socio@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->cod_socio->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@cod_socio@", ew_AdjustSql($this->cod_socio->CurrentValue), $sKeyFilter); // Replace key value
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
			return "socioslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "socioslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("sociosview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("sociosview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "sociosadd.php?" . $this->UrlParm($parm);
		else
			return "sociosadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("sociosedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("sociosedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("sociosadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("sociosadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("sociosdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->cod_socio->CurrentValue)) {
			$sUrl .= "cod_socio=" . urlencode($this->cod_socio->CurrentValue);
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
			$arKeys[] = @$_GET["cod_socio"]; // cod_socio

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
			$this->cod_socio->CurrentValue = $key;
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
		$this->cod_socio->setDbValue($rs->fields('cod_socio'));
		$this->socio->setDbValue($rs->fields('socio'));
		$this->cod_empresa->setDbValue($rs->fields('cod_empresa'));
		$this->dt_cadastro->setDbValue($rs->fields('dt_cadastro'));
		$this->validade->setDbValue($rs->fields('validade'));
		$this->ativo->setDbValue($rs->fields('ativo'));
		$this->funcao->setDbValue($rs->fields('funcao'));
		$this->dt_carteira->setDbValue($rs->fields('dt_carteira'));
		$this->dt_entrou_empresa->setDbValue($rs->fields('dt_entrou_empresa'));
		$this->dt_entrou_categoria->setDbValue($rs->fields('dt_entrou_categoria'));
		$this->acompanhante->setDbValue($rs->fields('acompanhante'));
		$this->dependentes->setDbValue($rs->fields('dependentes'));
		$this->foto->Upload->DbValue = $rs->fields('foto');
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// cod_socio
		// socio
		// cod_empresa
		// dt_cadastro
		// validade
		// ativo
		// funcao
		// dt_carteira
		// dt_entrou_empresa
		// dt_entrou_categoria
		// acompanhante
		// dependentes
		// foto
		// cod_socio

		$this->cod_socio->ViewValue = $this->cod_socio->CurrentValue;
		$this->cod_socio->ViewCustomAttributes = "";

		// socio
		if ($this->socio->VirtualValue <> "") {
			$this->socio->ViewValue = $this->socio->VirtualValue;
		} else {
		if (strval($this->socio->CurrentValue) <> "") {
			$sFilterWrk = "`cod_pessoa`" . ew_SearchString("=", $this->socio->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `cod_pessoa`, `nome` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pessoas`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->socio, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `nome` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->socio->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->socio->ViewValue = $this->socio->CurrentValue;
			}
		} else {
			$this->socio->ViewValue = NULL;
		}
		}
		$this->socio->ViewCustomAttributes = "";

		// cod_empresa
		if ($this->cod_empresa->VirtualValue <> "") {
			$this->cod_empresa->ViewValue = $this->cod_empresa->VirtualValue;
		} else {
		if (strval($this->cod_empresa->CurrentValue) <> "") {
			$sFilterWrk = "`cod_empresa`" . ew_SearchString("=", $this->cod_empresa->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->cod_empresa, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `nome_empresa` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->cod_empresa->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->cod_empresa->ViewValue = $this->cod_empresa->CurrentValue;
			}
		} else {
			$this->cod_empresa->ViewValue = NULL;
		}
		}
		$this->cod_empresa->ViewCustomAttributes = "";

		// dt_cadastro
		$this->dt_cadastro->ViewValue = $this->dt_cadastro->CurrentValue;
		$this->dt_cadastro->ViewValue = ew_FormatDateTime($this->dt_cadastro->ViewValue, 7);
		$this->dt_cadastro->ViewCustomAttributes = "";

		// validade
		$this->validade->ViewValue = $this->validade->CurrentValue;
		$this->validade->ViewValue = ew_FormatDateTime($this->validade->ViewValue, 7);
		$this->validade->ViewCustomAttributes = "";

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

		// funcao
		$this->funcao->ViewValue = $this->funcao->CurrentValue;
		$this->funcao->ViewCustomAttributes = "";

		// dt_carteira
		$this->dt_carteira->ViewValue = $this->dt_carteira->CurrentValue;
		$this->dt_carteira->ViewValue = ew_FormatDateTime($this->dt_carteira->ViewValue, 7);
		$this->dt_carteira->ViewCustomAttributes = "";

		// dt_entrou_empresa
		$this->dt_entrou_empresa->ViewValue = $this->dt_entrou_empresa->CurrentValue;
		$this->dt_entrou_empresa->ViewValue = ew_FormatDateTime($this->dt_entrou_empresa->ViewValue, 7);
		$this->dt_entrou_empresa->ViewCustomAttributes = "";

		// dt_entrou_categoria
		$this->dt_entrou_categoria->ViewValue = $this->dt_entrou_categoria->CurrentValue;
		$this->dt_entrou_categoria->ViewValue = ew_FormatDateTime($this->dt_entrou_categoria->ViewValue, 7);
		$this->dt_entrou_categoria->ViewCustomAttributes = "";

		// acompanhante
		$this->acompanhante->ViewValue = $this->acompanhante->CurrentValue;
		$this->acompanhante->ViewCustomAttributes = "";

		// dependentes
		$this->dependentes->ViewValue = $this->dependentes->CurrentValue;
		$this->dependentes->ViewCustomAttributes = "";

		// foto
		$this->foto->UploadPath = sistema;
		if (!ew_Empty($this->foto->Upload->DbValue)) {
			$this->foto->ImageWidth = EW_THUMBNAIL_DEFAULT_WIDTH;
			$this->foto->ImageHeight = EW_THUMBNAIL_DEFAULT_HEIGHT;
			$this->foto->ImageAlt = $this->foto->FldAlt();
			$this->foto->ViewValue = "ewbv11.php?fn=" . urlencode($this->foto->UploadPath . $this->foto->Upload->DbValue) . "&width=" . $this->foto->ImageWidth . "&height=" . $this->foto->ImageHeight;
			if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
				$tmpimage = file_get_contents(ew_UploadPathEx(TRUE, $this->foto->UploadPath) . $this->foto->Upload->DbValue);
				ew_ResizeBinary($tmpimage, $this->foto->ImageWidth, $this->foto->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY);
				$this->foto->ViewValue = ew_TmpImage($tmpimage);
			}
		} else {
			$this->foto->ViewValue = "";
		}
		$this->foto->ViewCustomAttributes = "";

		// cod_socio
		$this->cod_socio->LinkCustomAttributes = "";
		$this->cod_socio->HrefValue = "";
		$this->cod_socio->TooltipValue = "";

		// socio
		$this->socio->LinkCustomAttributes = "";
		if (!ew_Empty($this->socio->CurrentValue)) {
			$this->socio->HrefValue = "pessoaslist.php?cmd=search&t=pessoas&psearch=" . ((!empty($this->socio->ViewValue)) ? $this->socio->ViewValue : $this->socio->CurrentValue) . "&psearchtype="; // Add prefix/suffix
			$this->socio->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->socio->HrefValue = ew_ConvertFullUrl($this->socio->HrefValue);
		} else {
			$this->socio->HrefValue = "";
		}
		$this->socio->TooltipValue = "";

		// cod_empresa
		$this->cod_empresa->LinkCustomAttributes = "";
		if (!ew_Empty($this->cod_empresa->CurrentValue)) {
			$this->cod_empresa->HrefValue = "empresaslist.php?x_nome_empresa=" . ((!empty($this->cod_empresa->ViewValue)) ? $this->cod_empresa->ViewValue : $this->cod_empresa->CurrentValue) . "&z_nome_empresa=LIKE&cmd=search"; // Add prefix/suffix
			$this->cod_empresa->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->cod_empresa->HrefValue = ew_ConvertFullUrl($this->cod_empresa->HrefValue);
		} else {
			$this->cod_empresa->HrefValue = "";
		}
		$this->cod_empresa->TooltipValue = "";

		// dt_cadastro
		$this->dt_cadastro->LinkCustomAttributes = "";
		$this->dt_cadastro->HrefValue = "";
		$this->dt_cadastro->TooltipValue = "";

		// validade
		$this->validade->LinkCustomAttributes = "";
		$this->validade->HrefValue = "";
		$this->validade->TooltipValue = "";

		// ativo
		$this->ativo->LinkCustomAttributes = "";
		$this->ativo->HrefValue = "";
		$this->ativo->TooltipValue = "";

		// funcao
		$this->funcao->LinkCustomAttributes = "";
		$this->funcao->HrefValue = "";
		$this->funcao->TooltipValue = "";

		// dt_carteira
		$this->dt_carteira->LinkCustomAttributes = "";
		$this->dt_carteira->HrefValue = "";
		$this->dt_carteira->TooltipValue = "";

		// dt_entrou_empresa
		$this->dt_entrou_empresa->LinkCustomAttributes = "";
		$this->dt_entrou_empresa->HrefValue = "";
		$this->dt_entrou_empresa->TooltipValue = "";

		// dt_entrou_categoria
		$this->dt_entrou_categoria->LinkCustomAttributes = "";
		$this->dt_entrou_categoria->HrefValue = "";
		$this->dt_entrou_categoria->TooltipValue = "";

		// acompanhante
		$this->acompanhante->LinkCustomAttributes = "";
		$this->acompanhante->HrefValue = "";
		$this->acompanhante->TooltipValue = "";

		// dependentes
		$this->dependentes->LinkCustomAttributes = "";
		$this->dependentes->HrefValue = "";
		$this->dependentes->TooltipValue = "";

		// foto
		$this->foto->LinkCustomAttributes = "";
		$this->foto->UploadPath = sistema;
		if (!ew_Empty($this->foto->Upload->DbValue)) {
			$this->foto->HrefValue = ew_UploadPathEx(FALSE, $this->foto->UploadPath) . $this->foto->Upload->DbValue; // Add prefix/suffix
			$this->foto->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->foto->HrefValue = ew_ConvertFullUrl($this->foto->HrefValue);
		} else {
			$this->foto->HrefValue = "";
		}
		$this->foto->HrefValue2 = $this->foto->UploadPath . $this->foto->Upload->DbValue;
		$this->foto->TooltipValue = "";
		if ($this->foto->UseColorbox) {
			$this->foto->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->foto->LinkAttrs["data-rel"] = "socios_x_foto";
			$this->foto->LinkAttrs["class"] = "ewLightbox";
		}

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// cod_socio
		$this->cod_socio->EditAttrs["class"] = "form-control";
		$this->cod_socio->EditCustomAttributes = "";
		$this->cod_socio->EditValue = $this->cod_socio->CurrentValue;
		$this->cod_socio->ViewCustomAttributes = "";

		// socio
		$this->socio->EditAttrs["class"] = "form-control";
		$this->socio->EditCustomAttributes = "";

		// cod_empresa
		$this->cod_empresa->EditAttrs["class"] = "form-control";
		$this->cod_empresa->EditCustomAttributes = "";

		// dt_cadastro
		$this->dt_cadastro->EditAttrs["class"] = "form-control";
		$this->dt_cadastro->EditCustomAttributes = "";
		$this->dt_cadastro->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_cadastro->CurrentValue, 7));
		$this->dt_cadastro->PlaceHolder = ew_RemoveHtml($this->dt_cadastro->FldCaption());

		// validade
		$this->validade->EditAttrs["class"] = "form-control";
		$this->validade->EditCustomAttributes = "";
		$this->validade->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->validade->CurrentValue, 7));
		$this->validade->PlaceHolder = ew_RemoveHtml($this->validade->FldCaption());

		// ativo
		$this->ativo->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->ativo->FldTagValue(1), $this->ativo->FldTagCaption(1) <> "" ? $this->ativo->FldTagCaption(1) : $this->ativo->FldTagValue(1));
		$arwrk[] = array($this->ativo->FldTagValue(2), $this->ativo->FldTagCaption(2) <> "" ? $this->ativo->FldTagCaption(2) : $this->ativo->FldTagValue(2));
		$this->ativo->EditValue = $arwrk;

		// funcao
		$this->funcao->EditAttrs["class"] = "form-control";
		$this->funcao->EditCustomAttributes = "";
		$this->funcao->EditValue = ew_HtmlEncode($this->funcao->CurrentValue);
		$this->funcao->PlaceHolder = ew_RemoveHtml($this->funcao->FldCaption());

		// dt_carteira
		$this->dt_carteira->EditAttrs["class"] = "form-control";
		$this->dt_carteira->EditCustomAttributes = "";
		$this->dt_carteira->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_carteira->CurrentValue, 7));
		$this->dt_carteira->PlaceHolder = ew_RemoveHtml($this->dt_carteira->FldCaption());

		// dt_entrou_empresa
		$this->dt_entrou_empresa->EditAttrs["class"] = "form-control";
		$this->dt_entrou_empresa->EditCustomAttributes = "";
		$this->dt_entrou_empresa->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_entrou_empresa->CurrentValue, 7));
		$this->dt_entrou_empresa->PlaceHolder = ew_RemoveHtml($this->dt_entrou_empresa->FldCaption());

		// dt_entrou_categoria
		$this->dt_entrou_categoria->EditAttrs["class"] = "form-control";
		$this->dt_entrou_categoria->EditCustomAttributes = "";
		$this->dt_entrou_categoria->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_entrou_categoria->CurrentValue, 7));
		$this->dt_entrou_categoria->PlaceHolder = ew_RemoveHtml($this->dt_entrou_categoria->FldCaption());

		// acompanhante
		$this->acompanhante->EditAttrs["class"] = "form-control";
		$this->acompanhante->EditCustomAttributes = "";
		$this->acompanhante->EditValue = ew_HtmlEncode($this->acompanhante->CurrentValue);
		$this->acompanhante->PlaceHolder = ew_RemoveHtml($this->acompanhante->FldCaption());

		// dependentes
		$this->dependentes->EditAttrs["class"] = "form-control";
		$this->dependentes->EditCustomAttributes = "";
		$this->dependentes->EditValue = ew_HtmlEncode($this->dependentes->CurrentValue);
		$this->dependentes->PlaceHolder = ew_RemoveHtml($this->dependentes->FldCaption());

		// foto
		$this->foto->EditAttrs["class"] = "form-control";
		$this->foto->EditCustomAttributes = "";
		$this->foto->UploadPath = sistema;
		if (!ew_Empty($this->foto->Upload->DbValue)) {
			$this->foto->ImageWidth = EW_THUMBNAIL_DEFAULT_WIDTH;
			$this->foto->ImageHeight = EW_THUMBNAIL_DEFAULT_HEIGHT;
			$this->foto->ImageAlt = $this->foto->FldAlt();
			$this->foto->EditValue = "ewbv11.php?fn=" . urlencode($this->foto->UploadPath . $this->foto->Upload->DbValue) . "&width=" . $this->foto->ImageWidth . "&height=" . $this->foto->ImageHeight;
			if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
				$tmpimage = file_get_contents(ew_UploadPathEx(TRUE, $this->foto->UploadPath) . $this->foto->Upload->DbValue);
				ew_ResizeBinary($tmpimage, $this->foto->ImageWidth, $this->foto->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY);
				$this->foto->EditValue = ew_TmpImage($tmpimage);
			}
		} else {
			$this->foto->EditValue = "";
		}
		if (!ew_Empty($this->foto->CurrentValue))
			$this->foto->Upload->FileName = $this->foto->CurrentValue;

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
					if ($this->cod_socio->Exportable) $Doc->ExportCaption($this->cod_socio);
					if ($this->socio->Exportable) $Doc->ExportCaption($this->socio);
					if ($this->cod_empresa->Exportable) $Doc->ExportCaption($this->cod_empresa);
					if ($this->dt_cadastro->Exportable) $Doc->ExportCaption($this->dt_cadastro);
					if ($this->validade->Exportable) $Doc->ExportCaption($this->validade);
					if ($this->ativo->Exportable) $Doc->ExportCaption($this->ativo);
					if ($this->funcao->Exportable) $Doc->ExportCaption($this->funcao);
					if ($this->dt_carteira->Exportable) $Doc->ExportCaption($this->dt_carteira);
					if ($this->dt_entrou_empresa->Exportable) $Doc->ExportCaption($this->dt_entrou_empresa);
					if ($this->dt_entrou_categoria->Exportable) $Doc->ExportCaption($this->dt_entrou_categoria);
					if ($this->acompanhante->Exportable) $Doc->ExportCaption($this->acompanhante);
					if ($this->dependentes->Exportable) $Doc->ExportCaption($this->dependentes);
					if ($this->foto->Exportable) $Doc->ExportCaption($this->foto);
				} else {
					if ($this->cod_socio->Exportable) $Doc->ExportCaption($this->cod_socio);
					if ($this->socio->Exportable) $Doc->ExportCaption($this->socio);
					if ($this->cod_empresa->Exportable) $Doc->ExportCaption($this->cod_empresa);
					if ($this->dt_cadastro->Exportable) $Doc->ExportCaption($this->dt_cadastro);
					if ($this->validade->Exportable) $Doc->ExportCaption($this->validade);
					if ($this->ativo->Exportable) $Doc->ExportCaption($this->ativo);
					if ($this->funcao->Exportable) $Doc->ExportCaption($this->funcao);
					if ($this->dt_carteira->Exportable) $Doc->ExportCaption($this->dt_carteira);
					if ($this->dt_entrou_empresa->Exportable) $Doc->ExportCaption($this->dt_entrou_empresa);
					if ($this->dt_entrou_categoria->Exportable) $Doc->ExportCaption($this->dt_entrou_categoria);
					if ($this->foto->Exportable) $Doc->ExportCaption($this->foto);
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
						if ($this->cod_socio->Exportable) $Doc->ExportField($this->cod_socio);
						if ($this->socio->Exportable) $Doc->ExportField($this->socio);
						if ($this->cod_empresa->Exportable) $Doc->ExportField($this->cod_empresa);
						if ($this->dt_cadastro->Exportable) $Doc->ExportField($this->dt_cadastro);
						if ($this->validade->Exportable) $Doc->ExportField($this->validade);
						if ($this->ativo->Exportable) $Doc->ExportField($this->ativo);
						if ($this->funcao->Exportable) $Doc->ExportField($this->funcao);
						if ($this->dt_carteira->Exportable) $Doc->ExportField($this->dt_carteira);
						if ($this->dt_entrou_empresa->Exportable) $Doc->ExportField($this->dt_entrou_empresa);
						if ($this->dt_entrou_categoria->Exportable) $Doc->ExportField($this->dt_entrou_categoria);
						if ($this->acompanhante->Exportable) $Doc->ExportField($this->acompanhante);
						if ($this->dependentes->Exportable) $Doc->ExportField($this->dependentes);
						if ($this->foto->Exportable) $Doc->ExportField($this->foto);
					} else {
						if ($this->cod_socio->Exportable) $Doc->ExportField($this->cod_socio);
						if ($this->socio->Exportable) $Doc->ExportField($this->socio);
						if ($this->cod_empresa->Exportable) $Doc->ExportField($this->cod_empresa);
						if ($this->dt_cadastro->Exportable) $Doc->ExportField($this->dt_cadastro);
						if ($this->validade->Exportable) $Doc->ExportField($this->validade);
						if ($this->ativo->Exportable) $Doc->ExportField($this->ativo);
						if ($this->funcao->Exportable) $Doc->ExportField($this->funcao);
						if ($this->dt_carteira->Exportable) $Doc->ExportField($this->dt_carteira);
						if ($this->dt_entrou_empresa->Exportable) $Doc->ExportField($this->dt_entrou_empresa);
						if ($this->dt_entrou_categoria->Exportable) $Doc->ExportField($this->dt_entrou_categoria);
						if ($this->foto->Exportable) $Doc->ExportField($this->foto);
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
