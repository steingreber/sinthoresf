<?php

// Global variable for table object
$config = NULL;

//
// Table class for config
//
class cconfig extends cTable {
	var $A00ID;
	var $A01TOPOIMG;
	var $A02TITULO;
	var $A03EMAIL;
	var $A04COR_FONTE_SITE;
	var $A05MODELO;
	var $A06USUARIO;
	var $A07SENHA;
	var $A08NOME;
	var $A09DESCRICAO;
	var $A10PALAVRAS;
	var $A11TIPO_TOPO;
	var $A12TIPO_SITE;
	var $A13ACESSOS;
	var $A14ULTIMO;
	var $A15ENDRECO;
	var $A16CIDADE;
	var $A17ESTADO;
	var $A18CEP;
	var $A19FONE;
	var $A20CELULAR;
	var $A21EMPRESA;
	var $A22INFORMATIVO;
	var $A23ENQUETE;
	var $A24DATA;
	var $valor_mensal;
	var $valor_extenso;
	var $referente_ao_mes;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'config';
		$this->TableName = 'config';
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

		// A00ID
		$this->A00ID = new cField('config', 'config', 'x_A00ID', 'A00ID', '`A00ID`', '`A00ID`', 3, -1, FALSE, '`A00ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->A00ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['A00ID'] = &$this->A00ID;

		// A01TOPOIMG
		$this->A01TOPOIMG = new cField('config', 'config', 'x_A01TOPOIMG', 'A01TOPOIMG', '`A01TOPOIMG`', '`A01TOPOIMG`', 200, -1, FALSE, '`A01TOPOIMG`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A01TOPOIMG'] = &$this->A01TOPOIMG;

		// A02TITULO
		$this->A02TITULO = new cField('config', 'config', 'x_A02TITULO', 'A02TITULO', '`A02TITULO`', '`A02TITULO`', 200, -1, FALSE, '`A02TITULO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A02TITULO'] = &$this->A02TITULO;

		// A03EMAIL
		$this->A03EMAIL = new cField('config', 'config', 'x_A03EMAIL', 'A03EMAIL', '`A03EMAIL`', '`A03EMAIL`', 200, -1, FALSE, '`A03EMAIL`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A03EMAIL'] = &$this->A03EMAIL;

		// A04COR_FONTE_SITE
		$this->A04COR_FONTE_SITE = new cField('config', 'config', 'x_A04COR_FONTE_SITE', 'A04COR_FONTE_SITE', '`A04COR_FONTE_SITE`', '`A04COR_FONTE_SITE`', 200, -1, FALSE, '`A04COR_FONTE_SITE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A04COR_FONTE_SITE'] = &$this->A04COR_FONTE_SITE;

		// A05MODELO
		$this->A05MODELO = new cField('config', 'config', 'x_A05MODELO', 'A05MODELO', '`A05MODELO`', '`A05MODELO`', 3, -1, FALSE, '`A05MODELO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->A05MODELO->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['A05MODELO'] = &$this->A05MODELO;

		// A06USUARIO
		$this->A06USUARIO = new cField('config', 'config', 'x_A06USUARIO', 'A06USUARIO', '`A06USUARIO`', '`A06USUARIO`', 200, -1, FALSE, '`A06USUARIO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A06USUARIO'] = &$this->A06USUARIO;

		// A07SENHA
		$this->A07SENHA = new cField('config', 'config', 'x_A07SENHA', 'A07SENHA', '`A07SENHA`', '`A07SENHA`', 200, -1, FALSE, '`A07SENHA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A07SENHA'] = &$this->A07SENHA;

		// A08NOME
		$this->A08NOME = new cField('config', 'config', 'x_A08NOME', 'A08NOME', '`A08NOME`', '`A08NOME`', 200, -1, FALSE, '`A08NOME`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A08NOME'] = &$this->A08NOME;

		// A09DESCRICAO
		$this->A09DESCRICAO = new cField('config', 'config', 'x_A09DESCRICAO', 'A09DESCRICAO', '`A09DESCRICAO`', '`A09DESCRICAO`', 201, -1, FALSE, '`A09DESCRICAO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A09DESCRICAO'] = &$this->A09DESCRICAO;

		// A10PALAVRAS
		$this->A10PALAVRAS = new cField('config', 'config', 'x_A10PALAVRAS', 'A10PALAVRAS', '`A10PALAVRAS`', '`A10PALAVRAS`', 201, -1, FALSE, '`A10PALAVRAS`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A10PALAVRAS'] = &$this->A10PALAVRAS;

		// A11TIPO_TOPO
		$this->A11TIPO_TOPO = new cField('config', 'config', 'x_A11TIPO_TOPO', 'A11TIPO_TOPO', '`A11TIPO_TOPO`', '`A11TIPO_TOPO`', 3, -1, FALSE, '`A11TIPO_TOPO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->A11TIPO_TOPO->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['A11TIPO_TOPO'] = &$this->A11TIPO_TOPO;

		// A12TIPO_SITE
		$this->A12TIPO_SITE = new cField('config', 'config', 'x_A12TIPO_SITE', 'A12TIPO_SITE', '`A12TIPO_SITE`', '`A12TIPO_SITE`', 3, -1, FALSE, '`A12TIPO_SITE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->A12TIPO_SITE->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['A12TIPO_SITE'] = &$this->A12TIPO_SITE;

		// A13ACESSOS
		$this->A13ACESSOS = new cField('config', 'config', 'x_A13ACESSOS', 'A13ACESSOS', '`A13ACESSOS`', '`A13ACESSOS`', 3, -1, FALSE, '`A13ACESSOS`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->A13ACESSOS->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['A13ACESSOS'] = &$this->A13ACESSOS;

		// A14ULTIMO
		$this->A14ULTIMO = new cField('config', 'config', 'x_A14ULTIMO', 'A14ULTIMO', '`A14ULTIMO`', 'DATE_FORMAT(`A14ULTIMO`, \'%d/%m/%Y\')', 135, 7, FALSE, '`A14ULTIMO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->A14ULTIMO->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['A14ULTIMO'] = &$this->A14ULTIMO;

		// A15ENDRECO
		$this->A15ENDRECO = new cField('config', 'config', 'x_A15ENDRECO', 'A15ENDRECO', '`A15ENDRECO`', '`A15ENDRECO`', 200, -1, FALSE, '`A15ENDRECO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A15ENDRECO'] = &$this->A15ENDRECO;

		// A16CIDADE
		$this->A16CIDADE = new cField('config', 'config', 'x_A16CIDADE', 'A16CIDADE', '`A16CIDADE`', '`A16CIDADE`', 200, -1, FALSE, '`A16CIDADE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A16CIDADE'] = &$this->A16CIDADE;

		// A17ESTADO
		$this->A17ESTADO = new cField('config', 'config', 'x_A17ESTADO', 'A17ESTADO', '`A17ESTADO`', '`A17ESTADO`', 200, -1, FALSE, '`A17ESTADO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A17ESTADO'] = &$this->A17ESTADO;

		// A18CEP
		$this->A18CEP = new cField('config', 'config', 'x_A18CEP', 'A18CEP', '`A18CEP`', '`A18CEP`', 200, -1, FALSE, '`A18CEP`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A18CEP'] = &$this->A18CEP;

		// A19FONE
		$this->A19FONE = new cField('config', 'config', 'x_A19FONE', 'A19FONE', '`A19FONE`', '`A19FONE`', 200, -1, FALSE, '`A19FONE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A19FONE'] = &$this->A19FONE;

		// A20CELULAR
		$this->A20CELULAR = new cField('config', 'config', 'x_A20CELULAR', 'A20CELULAR', '`A20CELULAR`', '`A20CELULAR`', 200, -1, FALSE, '`A20CELULAR`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A20CELULAR'] = &$this->A20CELULAR;

		// A21EMPRESA
		$this->A21EMPRESA = new cField('config', 'config', 'x_A21EMPRESA', 'A21EMPRESA', '`A21EMPRESA`', '`A21EMPRESA`', 200, -1, FALSE, '`A21EMPRESA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A21EMPRESA'] = &$this->A21EMPRESA;

		// A22INFORMATIVO
		$this->A22INFORMATIVO = new cField('config', 'config', 'x_A22INFORMATIVO', 'A22INFORMATIVO', '`A22INFORMATIVO`', '`A22INFORMATIVO`', 3, -1, FALSE, '`A22INFORMATIVO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->A22INFORMATIVO->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['A22INFORMATIVO'] = &$this->A22INFORMATIVO;

		// A23ENQUETE
		$this->A23ENQUETE = new cField('config', 'config', 'x_A23ENQUETE', 'A23ENQUETE', '`A23ENQUETE`', '`A23ENQUETE`', 3, -1, FALSE, '`A23ENQUETE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->A23ENQUETE->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['A23ENQUETE'] = &$this->A23ENQUETE;

		// A24DATA
		$this->A24DATA = new cField('config', 'config', 'x_A24DATA', 'A24DATA', '`A24DATA`', '`A24DATA`', 201, -1, FALSE, '`A24DATA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->A24DATA->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['A24DATA'] = &$this->A24DATA;

		// valor_mensal
		$this->valor_mensal = new cField('config', 'config', 'x_valor_mensal', 'valor_mensal', '`valor_mensal`', '`valor_mensal`', 200, -1, FALSE, '`valor_mensal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['valor_mensal'] = &$this->valor_mensal;

		// valor_extenso
		$this->valor_extenso = new cField('config', 'config', 'x_valor_extenso', 'valor_extenso', '`valor_extenso`', '`valor_extenso`', 200, -1, FALSE, '`valor_extenso`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['valor_extenso'] = &$this->valor_extenso;

		// referente_ao_mes
		$this->referente_ao_mes = new cField('config', 'config', 'x_referente_ao_mes', 'referente_ao_mes', '`referente_ao_mes`', '`referente_ao_mes`', 201, -1, FALSE, '`referente_ao_mes`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['referente_ao_mes'] = &$this->referente_ao_mes;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`config`";
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
	var $UpdateTable = "`config`";

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
			if (array_key_exists('A00ID', $rs))
				ew_AddFilter($where, ew_QuotedName('A00ID') . '=' . ew_QuotedValue($rs['A00ID'], $this->A00ID->FldDataType));
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
		return "`A00ID` = @A00ID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->A00ID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@A00ID@", ew_AdjustSql($this->A00ID->CurrentValue), $sKeyFilter); // Replace key value
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
			return "configlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "configlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("configview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("configview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "configadd.php?" . $this->UrlParm($parm);
		else
			return "configadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("configedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("configadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("configdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->A00ID->CurrentValue)) {
			$sUrl .= "A00ID=" . urlencode($this->A00ID->CurrentValue);
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
			$arKeys[] = @$_GET["A00ID"]; // A00ID

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
			$this->A00ID->CurrentValue = $key;
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
		$this->A00ID->setDbValue($rs->fields('A00ID'));
		$this->A01TOPOIMG->setDbValue($rs->fields('A01TOPOIMG'));
		$this->A02TITULO->setDbValue($rs->fields('A02TITULO'));
		$this->A03EMAIL->setDbValue($rs->fields('A03EMAIL'));
		$this->A04COR_FONTE_SITE->setDbValue($rs->fields('A04COR_FONTE_SITE'));
		$this->A05MODELO->setDbValue($rs->fields('A05MODELO'));
		$this->A06USUARIO->setDbValue($rs->fields('A06USUARIO'));
		$this->A07SENHA->setDbValue($rs->fields('A07SENHA'));
		$this->A08NOME->setDbValue($rs->fields('A08NOME'));
		$this->A09DESCRICAO->setDbValue($rs->fields('A09DESCRICAO'));
		$this->A10PALAVRAS->setDbValue($rs->fields('A10PALAVRAS'));
		$this->A11TIPO_TOPO->setDbValue($rs->fields('A11TIPO_TOPO'));
		$this->A12TIPO_SITE->setDbValue($rs->fields('A12TIPO_SITE'));
		$this->A13ACESSOS->setDbValue($rs->fields('A13ACESSOS'));
		$this->A14ULTIMO->setDbValue($rs->fields('A14ULTIMO'));
		$this->A15ENDRECO->setDbValue($rs->fields('A15ENDRECO'));
		$this->A16CIDADE->setDbValue($rs->fields('A16CIDADE'));
		$this->A17ESTADO->setDbValue($rs->fields('A17ESTADO'));
		$this->A18CEP->setDbValue($rs->fields('A18CEP'));
		$this->A19FONE->setDbValue($rs->fields('A19FONE'));
		$this->A20CELULAR->setDbValue($rs->fields('A20CELULAR'));
		$this->A21EMPRESA->setDbValue($rs->fields('A21EMPRESA'));
		$this->A22INFORMATIVO->setDbValue($rs->fields('A22INFORMATIVO'));
		$this->A23ENQUETE->setDbValue($rs->fields('A23ENQUETE'));
		$this->A24DATA->setDbValue($rs->fields('A24DATA'));
		$this->valor_mensal->setDbValue($rs->fields('valor_mensal'));
		$this->valor_extenso->setDbValue($rs->fields('valor_extenso'));
		$this->referente_ao_mes->setDbValue($rs->fields('referente_ao_mes'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// A00ID
		// A01TOPOIMG
		// A02TITULO
		// A03EMAIL
		// A04COR_FONTE_SITE
		// A05MODELO
		// A06USUARIO
		// A07SENHA
		// A08NOME
		// A09DESCRICAO
		// A10PALAVRAS
		// A11TIPO_TOPO
		// A12TIPO_SITE
		// A13ACESSOS
		// A14ULTIMO
		// A15ENDRECO
		// A16CIDADE
		// A17ESTADO
		// A18CEP
		// A19FONE
		// A20CELULAR
		// A21EMPRESA
		// A22INFORMATIVO
		// A23ENQUETE
		// A24DATA
		// valor_mensal
		// valor_extenso
		// referente_ao_mes
		// A00ID

		$this->A00ID->ViewValue = $this->A00ID->CurrentValue;
		$this->A00ID->ViewCustomAttributes = "";

		// A01TOPOIMG
		$this->A01TOPOIMG->ViewValue = $this->A01TOPOIMG->CurrentValue;
		$this->A01TOPOIMG->ViewCustomAttributes = "";

		// A02TITULO
		$this->A02TITULO->ViewValue = $this->A02TITULO->CurrentValue;
		$this->A02TITULO->ViewCustomAttributes = "";

		// A03EMAIL
		$this->A03EMAIL->ViewValue = $this->A03EMAIL->CurrentValue;
		$this->A03EMAIL->ViewCustomAttributes = "";

		// A04COR_FONTE_SITE
		$this->A04COR_FONTE_SITE->ViewValue = $this->A04COR_FONTE_SITE->CurrentValue;
		$this->A04COR_FONTE_SITE->ViewCustomAttributes = "";

		// A05MODELO
		$this->A05MODELO->ViewValue = $this->A05MODELO->CurrentValue;
		$this->A05MODELO->ViewCustomAttributes = "";

		// A06USUARIO
		$this->A06USUARIO->ViewValue = $this->A06USUARIO->CurrentValue;
		$this->A06USUARIO->ViewCustomAttributes = "";

		// A07SENHA
		$this->A07SENHA->ViewValue = $this->A07SENHA->CurrentValue;
		$this->A07SENHA->ViewCustomAttributes = "";

		// A08NOME
		$this->A08NOME->ViewValue = $this->A08NOME->CurrentValue;
		$this->A08NOME->ViewCustomAttributes = "";

		// A09DESCRICAO
		$this->A09DESCRICAO->ViewValue = $this->A09DESCRICAO->CurrentValue;
		$this->A09DESCRICAO->ViewCustomAttributes = "";

		// A10PALAVRAS
		$this->A10PALAVRAS->ViewValue = $this->A10PALAVRAS->CurrentValue;
		$this->A10PALAVRAS->ViewCustomAttributes = "";

		// A11TIPO_TOPO
		$this->A11TIPO_TOPO->ViewValue = $this->A11TIPO_TOPO->CurrentValue;
		$this->A11TIPO_TOPO->ViewCustomAttributes = "";

		// A12TIPO_SITE
		$this->A12TIPO_SITE->ViewValue = $this->A12TIPO_SITE->CurrentValue;
		$this->A12TIPO_SITE->ViewCustomAttributes = "";

		// A13ACESSOS
		$this->A13ACESSOS->ViewValue = $this->A13ACESSOS->CurrentValue;
		$this->A13ACESSOS->ViewCustomAttributes = "";

		// A14ULTIMO
		$this->A14ULTIMO->ViewValue = $this->A14ULTIMO->CurrentValue;
		$this->A14ULTIMO->ViewValue = ew_FormatDateTime($this->A14ULTIMO->ViewValue, 7);
		$this->A14ULTIMO->ViewCustomAttributes = "";

		// A15ENDRECO
		$this->A15ENDRECO->ViewValue = $this->A15ENDRECO->CurrentValue;
		$this->A15ENDRECO->ViewCustomAttributes = "";

		// A16CIDADE
		$this->A16CIDADE->ViewValue = $this->A16CIDADE->CurrentValue;
		$this->A16CIDADE->ViewCustomAttributes = "";

		// A17ESTADO
		$this->A17ESTADO->ViewValue = $this->A17ESTADO->CurrentValue;
		$this->A17ESTADO->ViewCustomAttributes = "";

		// A18CEP
		$this->A18CEP->ViewValue = $this->A18CEP->CurrentValue;
		$this->A18CEP->ViewCustomAttributes = "";

		// A19FONE
		$this->A19FONE->ViewValue = $this->A19FONE->CurrentValue;
		$this->A19FONE->ViewCustomAttributes = "";

		// A20CELULAR
		$this->A20CELULAR->ViewValue = $this->A20CELULAR->CurrentValue;
		$this->A20CELULAR->ViewCustomAttributes = "";

		// A21EMPRESA
		$this->A21EMPRESA->ViewValue = $this->A21EMPRESA->CurrentValue;
		$this->A21EMPRESA->ViewCustomAttributes = "";

		// A22INFORMATIVO
		$this->A22INFORMATIVO->ViewValue = $this->A22INFORMATIVO->CurrentValue;
		$this->A22INFORMATIVO->ViewCustomAttributes = "";

		// A23ENQUETE
		$this->A23ENQUETE->ViewValue = $this->A23ENQUETE->CurrentValue;
		$this->A23ENQUETE->ViewCustomAttributes = "";

		// A24DATA
		$this->A24DATA->ViewValue = $this->A24DATA->CurrentValue;
		$this->A24DATA->ViewCustomAttributes = "";

		// valor_mensal
		$this->valor_mensal->ViewValue = $this->valor_mensal->CurrentValue;
		$this->valor_mensal->ViewCustomAttributes = "";

		// valor_extenso
		$this->valor_extenso->ViewValue = $this->valor_extenso->CurrentValue;
		$this->valor_extenso->ViewCustomAttributes = "";

		// referente_ao_mes
		$this->referente_ao_mes->ViewValue = $this->referente_ao_mes->CurrentValue;
		$this->referente_ao_mes->ViewCustomAttributes = "";

		// A00ID
		$this->A00ID->LinkCustomAttributes = "";
		$this->A00ID->HrefValue = "";
		$this->A00ID->TooltipValue = "";

		// A01TOPOIMG
		$this->A01TOPOIMG->LinkCustomAttributes = "";
		$this->A01TOPOIMG->HrefValue = "";
		$this->A01TOPOIMG->TooltipValue = "";

		// A02TITULO
		$this->A02TITULO->LinkCustomAttributes = "";
		$this->A02TITULO->HrefValue = "";
		$this->A02TITULO->TooltipValue = "";

		// A03EMAIL
		$this->A03EMAIL->LinkCustomAttributes = "";
		$this->A03EMAIL->HrefValue = "";
		$this->A03EMAIL->TooltipValue = "";

		// A04COR_FONTE_SITE
		$this->A04COR_FONTE_SITE->LinkCustomAttributes = "";
		$this->A04COR_FONTE_SITE->HrefValue = "";
		$this->A04COR_FONTE_SITE->TooltipValue = "";

		// A05MODELO
		$this->A05MODELO->LinkCustomAttributes = "";
		$this->A05MODELO->HrefValue = "";
		$this->A05MODELO->TooltipValue = "";

		// A06USUARIO
		$this->A06USUARIO->LinkCustomAttributes = "";
		$this->A06USUARIO->HrefValue = "";
		$this->A06USUARIO->TooltipValue = "";

		// A07SENHA
		$this->A07SENHA->LinkCustomAttributes = "";
		$this->A07SENHA->HrefValue = "";
		$this->A07SENHA->TooltipValue = "";

		// A08NOME
		$this->A08NOME->LinkCustomAttributes = "";
		$this->A08NOME->HrefValue = "";
		$this->A08NOME->TooltipValue = "";

		// A09DESCRICAO
		$this->A09DESCRICAO->LinkCustomAttributes = "";
		$this->A09DESCRICAO->HrefValue = "";
		$this->A09DESCRICAO->TooltipValue = "";

		// A10PALAVRAS
		$this->A10PALAVRAS->LinkCustomAttributes = "";
		$this->A10PALAVRAS->HrefValue = "";
		$this->A10PALAVRAS->TooltipValue = "";

		// A11TIPO_TOPO
		$this->A11TIPO_TOPO->LinkCustomAttributes = "";
		$this->A11TIPO_TOPO->HrefValue = "";
		$this->A11TIPO_TOPO->TooltipValue = "";

		// A12TIPO_SITE
		$this->A12TIPO_SITE->LinkCustomAttributes = "";
		$this->A12TIPO_SITE->HrefValue = "";
		$this->A12TIPO_SITE->TooltipValue = "";

		// A13ACESSOS
		$this->A13ACESSOS->LinkCustomAttributes = "";
		$this->A13ACESSOS->HrefValue = "";
		$this->A13ACESSOS->TooltipValue = "";

		// A14ULTIMO
		$this->A14ULTIMO->LinkCustomAttributes = "";
		$this->A14ULTIMO->HrefValue = "";
		$this->A14ULTIMO->TooltipValue = "";

		// A15ENDRECO
		$this->A15ENDRECO->LinkCustomAttributes = "";
		$this->A15ENDRECO->HrefValue = "";
		$this->A15ENDRECO->TooltipValue = "";

		// A16CIDADE
		$this->A16CIDADE->LinkCustomAttributes = "";
		$this->A16CIDADE->HrefValue = "";
		$this->A16CIDADE->TooltipValue = "";

		// A17ESTADO
		$this->A17ESTADO->LinkCustomAttributes = "";
		$this->A17ESTADO->HrefValue = "";
		$this->A17ESTADO->TooltipValue = "";

		// A18CEP
		$this->A18CEP->LinkCustomAttributes = "";
		$this->A18CEP->HrefValue = "";
		$this->A18CEP->TooltipValue = "";

		// A19FONE
		$this->A19FONE->LinkCustomAttributes = "";
		$this->A19FONE->HrefValue = "";
		$this->A19FONE->TooltipValue = "";

		// A20CELULAR
		$this->A20CELULAR->LinkCustomAttributes = "";
		$this->A20CELULAR->HrefValue = "";
		$this->A20CELULAR->TooltipValue = "";

		// A21EMPRESA
		$this->A21EMPRESA->LinkCustomAttributes = "";
		$this->A21EMPRESA->HrefValue = "";
		$this->A21EMPRESA->TooltipValue = "";

		// A22INFORMATIVO
		$this->A22INFORMATIVO->LinkCustomAttributes = "";
		$this->A22INFORMATIVO->HrefValue = "";
		$this->A22INFORMATIVO->TooltipValue = "";

		// A23ENQUETE
		$this->A23ENQUETE->LinkCustomAttributes = "";
		$this->A23ENQUETE->HrefValue = "";
		$this->A23ENQUETE->TooltipValue = "";

		// A24DATA
		$this->A24DATA->LinkCustomAttributes = "";
		$this->A24DATA->HrefValue = "";
		$this->A24DATA->TooltipValue = "";

		// valor_mensal
		$this->valor_mensal->LinkCustomAttributes = "";
		$this->valor_mensal->HrefValue = "";
		$this->valor_mensal->TooltipValue = "";

		// valor_extenso
		$this->valor_extenso->LinkCustomAttributes = "";
		$this->valor_extenso->HrefValue = "";
		$this->valor_extenso->TooltipValue = "";

		// referente_ao_mes
		$this->referente_ao_mes->LinkCustomAttributes = "";
		$this->referente_ao_mes->HrefValue = "";
		$this->referente_ao_mes->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// A00ID
		$this->A00ID->EditAttrs["class"] = "form-control";
		$this->A00ID->EditCustomAttributes = "";
		$this->A00ID->EditValue = $this->A00ID->CurrentValue;
		$this->A00ID->ViewCustomAttributes = "";

		// A01TOPOIMG
		$this->A01TOPOIMG->EditAttrs["class"] = "form-control";
		$this->A01TOPOIMG->EditCustomAttributes = "";
		$this->A01TOPOIMG->EditValue = ew_HtmlEncode($this->A01TOPOIMG->CurrentValue);
		$this->A01TOPOIMG->PlaceHolder = ew_RemoveHtml($this->A01TOPOIMG->FldCaption());

		// A02TITULO
		$this->A02TITULO->EditAttrs["class"] = "form-control";
		$this->A02TITULO->EditCustomAttributes = "";
		$this->A02TITULO->EditValue = ew_HtmlEncode($this->A02TITULO->CurrentValue);
		$this->A02TITULO->PlaceHolder = ew_RemoveHtml($this->A02TITULO->FldCaption());

		// A03EMAIL
		$this->A03EMAIL->EditAttrs["class"] = "form-control";
		$this->A03EMAIL->EditCustomAttributes = "";
		$this->A03EMAIL->EditValue = ew_HtmlEncode($this->A03EMAIL->CurrentValue);
		$this->A03EMAIL->PlaceHolder = ew_RemoveHtml($this->A03EMAIL->FldCaption());

		// A04COR_FONTE_SITE
		$this->A04COR_FONTE_SITE->EditAttrs["class"] = "form-control";
		$this->A04COR_FONTE_SITE->EditCustomAttributes = "";
		$this->A04COR_FONTE_SITE->EditValue = ew_HtmlEncode($this->A04COR_FONTE_SITE->CurrentValue);
		$this->A04COR_FONTE_SITE->PlaceHolder = ew_RemoveHtml($this->A04COR_FONTE_SITE->FldCaption());

		// A05MODELO
		$this->A05MODELO->EditAttrs["class"] = "form-control";
		$this->A05MODELO->EditCustomAttributes = "";
		$this->A05MODELO->EditValue = ew_HtmlEncode($this->A05MODELO->CurrentValue);
		$this->A05MODELO->PlaceHolder = ew_RemoveHtml($this->A05MODELO->FldCaption());

		// A06USUARIO
		$this->A06USUARIO->EditAttrs["class"] = "form-control";
		$this->A06USUARIO->EditCustomAttributes = "";
		$this->A06USUARIO->EditValue = ew_HtmlEncode($this->A06USUARIO->CurrentValue);
		$this->A06USUARIO->PlaceHolder = ew_RemoveHtml($this->A06USUARIO->FldCaption());

		// A07SENHA
		$this->A07SENHA->EditAttrs["class"] = "form-control";
		$this->A07SENHA->EditCustomAttributes = "";
		$this->A07SENHA->EditValue = ew_HtmlEncode($this->A07SENHA->CurrentValue);
		$this->A07SENHA->PlaceHolder = ew_RemoveHtml($this->A07SENHA->FldCaption());

		// A08NOME
		$this->A08NOME->EditAttrs["class"] = "form-control";
		$this->A08NOME->EditCustomAttributes = "";
		$this->A08NOME->EditValue = ew_HtmlEncode($this->A08NOME->CurrentValue);
		$this->A08NOME->PlaceHolder = ew_RemoveHtml($this->A08NOME->FldCaption());

		// A09DESCRICAO
		$this->A09DESCRICAO->EditAttrs["class"] = "form-control";
		$this->A09DESCRICAO->EditCustomAttributes = "";
		$this->A09DESCRICAO->EditValue = ew_HtmlEncode($this->A09DESCRICAO->CurrentValue);
		$this->A09DESCRICAO->PlaceHolder = ew_RemoveHtml($this->A09DESCRICAO->FldCaption());

		// A10PALAVRAS
		$this->A10PALAVRAS->EditAttrs["class"] = "form-control";
		$this->A10PALAVRAS->EditCustomAttributes = "";
		$this->A10PALAVRAS->EditValue = ew_HtmlEncode($this->A10PALAVRAS->CurrentValue);
		$this->A10PALAVRAS->PlaceHolder = ew_RemoveHtml($this->A10PALAVRAS->FldCaption());

		// A11TIPO_TOPO
		$this->A11TIPO_TOPO->EditAttrs["class"] = "form-control";
		$this->A11TIPO_TOPO->EditCustomAttributes = "";
		$this->A11TIPO_TOPO->EditValue = ew_HtmlEncode($this->A11TIPO_TOPO->CurrentValue);
		$this->A11TIPO_TOPO->PlaceHolder = ew_RemoveHtml($this->A11TIPO_TOPO->FldCaption());

		// A12TIPO_SITE
		$this->A12TIPO_SITE->EditAttrs["class"] = "form-control";
		$this->A12TIPO_SITE->EditCustomAttributes = "";
		$this->A12TIPO_SITE->EditValue = ew_HtmlEncode($this->A12TIPO_SITE->CurrentValue);
		$this->A12TIPO_SITE->PlaceHolder = ew_RemoveHtml($this->A12TIPO_SITE->FldCaption());

		// A13ACESSOS
		$this->A13ACESSOS->EditAttrs["class"] = "form-control";
		$this->A13ACESSOS->EditCustomAttributes = "";
		$this->A13ACESSOS->EditValue = ew_HtmlEncode($this->A13ACESSOS->CurrentValue);
		$this->A13ACESSOS->PlaceHolder = ew_RemoveHtml($this->A13ACESSOS->FldCaption());

		// A14ULTIMO
		$this->A14ULTIMO->EditAttrs["class"] = "form-control";
		$this->A14ULTIMO->EditCustomAttributes = "";
		$this->A14ULTIMO->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->A14ULTIMO->CurrentValue, 7));
		$this->A14ULTIMO->PlaceHolder = ew_RemoveHtml($this->A14ULTIMO->FldCaption());

		// A15ENDRECO
		$this->A15ENDRECO->EditAttrs["class"] = "form-control";
		$this->A15ENDRECO->EditCustomAttributes = "";
		$this->A15ENDRECO->EditValue = ew_HtmlEncode($this->A15ENDRECO->CurrentValue);
		$this->A15ENDRECO->PlaceHolder = ew_RemoveHtml($this->A15ENDRECO->FldCaption());

		// A16CIDADE
		$this->A16CIDADE->EditAttrs["class"] = "form-control";
		$this->A16CIDADE->EditCustomAttributes = "";
		$this->A16CIDADE->EditValue = ew_HtmlEncode($this->A16CIDADE->CurrentValue);
		$this->A16CIDADE->PlaceHolder = ew_RemoveHtml($this->A16CIDADE->FldCaption());

		// A17ESTADO
		$this->A17ESTADO->EditAttrs["class"] = "form-control";
		$this->A17ESTADO->EditCustomAttributes = "";
		$this->A17ESTADO->EditValue = ew_HtmlEncode($this->A17ESTADO->CurrentValue);
		$this->A17ESTADO->PlaceHolder = ew_RemoveHtml($this->A17ESTADO->FldCaption());

		// A18CEP
		$this->A18CEP->EditAttrs["class"] = "form-control";
		$this->A18CEP->EditCustomAttributes = "";
		$this->A18CEP->EditValue = ew_HtmlEncode($this->A18CEP->CurrentValue);
		$this->A18CEP->PlaceHolder = ew_RemoveHtml($this->A18CEP->FldCaption());

		// A19FONE
		$this->A19FONE->EditAttrs["class"] = "form-control";
		$this->A19FONE->EditCustomAttributes = "";
		$this->A19FONE->EditValue = ew_HtmlEncode($this->A19FONE->CurrentValue);
		$this->A19FONE->PlaceHolder = ew_RemoveHtml($this->A19FONE->FldCaption());

		// A20CELULAR
		$this->A20CELULAR->EditAttrs["class"] = "form-control";
		$this->A20CELULAR->EditCustomAttributes = "";
		$this->A20CELULAR->EditValue = ew_HtmlEncode($this->A20CELULAR->CurrentValue);
		$this->A20CELULAR->PlaceHolder = ew_RemoveHtml($this->A20CELULAR->FldCaption());

		// A21EMPRESA
		$this->A21EMPRESA->EditAttrs["class"] = "form-control";
		$this->A21EMPRESA->EditCustomAttributes = "";
		$this->A21EMPRESA->EditValue = ew_HtmlEncode($this->A21EMPRESA->CurrentValue);
		$this->A21EMPRESA->PlaceHolder = ew_RemoveHtml($this->A21EMPRESA->FldCaption());

		// A22INFORMATIVO
		$this->A22INFORMATIVO->EditAttrs["class"] = "form-control";
		$this->A22INFORMATIVO->EditCustomAttributes = "";
		$this->A22INFORMATIVO->EditValue = ew_HtmlEncode($this->A22INFORMATIVO->CurrentValue);
		$this->A22INFORMATIVO->PlaceHolder = ew_RemoveHtml($this->A22INFORMATIVO->FldCaption());

		// A23ENQUETE
		$this->A23ENQUETE->EditAttrs["class"] = "form-control";
		$this->A23ENQUETE->EditCustomAttributes = "";
		$this->A23ENQUETE->EditValue = ew_HtmlEncode($this->A23ENQUETE->CurrentValue);
		$this->A23ENQUETE->PlaceHolder = ew_RemoveHtml($this->A23ENQUETE->FldCaption());

		// A24DATA
		$this->A24DATA->EditAttrs["class"] = "form-control";
		$this->A24DATA->EditCustomAttributes = "";
		$this->A24DATA->EditValue = ew_HtmlEncode($this->A24DATA->CurrentValue);
		$this->A24DATA->PlaceHolder = ew_RemoveHtml($this->A24DATA->FldCaption());

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

		// referente_ao_mes
		$this->referente_ao_mes->EditAttrs["class"] = "form-control";
		$this->referente_ao_mes->EditCustomAttributes = "";
		$this->referente_ao_mes->EditValue = ew_HtmlEncode($this->referente_ao_mes->CurrentValue);
		$this->referente_ao_mes->PlaceHolder = ew_RemoveHtml($this->referente_ao_mes->FldCaption());

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
					if ($this->A00ID->Exportable) $Doc->ExportCaption($this->A00ID);
					if ($this->A01TOPOIMG->Exportable) $Doc->ExportCaption($this->A01TOPOIMG);
					if ($this->A02TITULO->Exportable) $Doc->ExportCaption($this->A02TITULO);
					if ($this->A03EMAIL->Exportable) $Doc->ExportCaption($this->A03EMAIL);
					if ($this->A04COR_FONTE_SITE->Exportable) $Doc->ExportCaption($this->A04COR_FONTE_SITE);
					if ($this->A05MODELO->Exportable) $Doc->ExportCaption($this->A05MODELO);
					if ($this->A06USUARIO->Exportable) $Doc->ExportCaption($this->A06USUARIO);
					if ($this->A07SENHA->Exportable) $Doc->ExportCaption($this->A07SENHA);
					if ($this->A08NOME->Exportable) $Doc->ExportCaption($this->A08NOME);
					if ($this->A09DESCRICAO->Exportable) $Doc->ExportCaption($this->A09DESCRICAO);
					if ($this->A10PALAVRAS->Exportable) $Doc->ExportCaption($this->A10PALAVRAS);
					if ($this->A11TIPO_TOPO->Exportable) $Doc->ExportCaption($this->A11TIPO_TOPO);
					if ($this->A12TIPO_SITE->Exportable) $Doc->ExportCaption($this->A12TIPO_SITE);
					if ($this->A13ACESSOS->Exportable) $Doc->ExportCaption($this->A13ACESSOS);
					if ($this->A14ULTIMO->Exportable) $Doc->ExportCaption($this->A14ULTIMO);
					if ($this->A15ENDRECO->Exportable) $Doc->ExportCaption($this->A15ENDRECO);
					if ($this->A16CIDADE->Exportable) $Doc->ExportCaption($this->A16CIDADE);
					if ($this->A17ESTADO->Exportable) $Doc->ExportCaption($this->A17ESTADO);
					if ($this->A18CEP->Exportable) $Doc->ExportCaption($this->A18CEP);
					if ($this->A19FONE->Exportable) $Doc->ExportCaption($this->A19FONE);
					if ($this->A20CELULAR->Exportable) $Doc->ExportCaption($this->A20CELULAR);
					if ($this->A21EMPRESA->Exportable) $Doc->ExportCaption($this->A21EMPRESA);
					if ($this->A22INFORMATIVO->Exportable) $Doc->ExportCaption($this->A22INFORMATIVO);
					if ($this->A23ENQUETE->Exportable) $Doc->ExportCaption($this->A23ENQUETE);
					if ($this->A24DATA->Exportable) $Doc->ExportCaption($this->A24DATA);
					if ($this->valor_mensal->Exportable) $Doc->ExportCaption($this->valor_mensal);
					if ($this->valor_extenso->Exportable) $Doc->ExportCaption($this->valor_extenso);
					if ($this->referente_ao_mes->Exportable) $Doc->ExportCaption($this->referente_ao_mes);
				} else {
					if ($this->A00ID->Exportable) $Doc->ExportCaption($this->A00ID);
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
						if ($this->A00ID->Exportable) $Doc->ExportField($this->A00ID);
						if ($this->A01TOPOIMG->Exportable) $Doc->ExportField($this->A01TOPOIMG);
						if ($this->A02TITULO->Exportable) $Doc->ExportField($this->A02TITULO);
						if ($this->A03EMAIL->Exportable) $Doc->ExportField($this->A03EMAIL);
						if ($this->A04COR_FONTE_SITE->Exportable) $Doc->ExportField($this->A04COR_FONTE_SITE);
						if ($this->A05MODELO->Exportable) $Doc->ExportField($this->A05MODELO);
						if ($this->A06USUARIO->Exportable) $Doc->ExportField($this->A06USUARIO);
						if ($this->A07SENHA->Exportable) $Doc->ExportField($this->A07SENHA);
						if ($this->A08NOME->Exportable) $Doc->ExportField($this->A08NOME);
						if ($this->A09DESCRICAO->Exportable) $Doc->ExportField($this->A09DESCRICAO);
						if ($this->A10PALAVRAS->Exportable) $Doc->ExportField($this->A10PALAVRAS);
						if ($this->A11TIPO_TOPO->Exportable) $Doc->ExportField($this->A11TIPO_TOPO);
						if ($this->A12TIPO_SITE->Exportable) $Doc->ExportField($this->A12TIPO_SITE);
						if ($this->A13ACESSOS->Exportable) $Doc->ExportField($this->A13ACESSOS);
						if ($this->A14ULTIMO->Exportable) $Doc->ExportField($this->A14ULTIMO);
						if ($this->A15ENDRECO->Exportable) $Doc->ExportField($this->A15ENDRECO);
						if ($this->A16CIDADE->Exportable) $Doc->ExportField($this->A16CIDADE);
						if ($this->A17ESTADO->Exportable) $Doc->ExportField($this->A17ESTADO);
						if ($this->A18CEP->Exportable) $Doc->ExportField($this->A18CEP);
						if ($this->A19FONE->Exportable) $Doc->ExportField($this->A19FONE);
						if ($this->A20CELULAR->Exportable) $Doc->ExportField($this->A20CELULAR);
						if ($this->A21EMPRESA->Exportable) $Doc->ExportField($this->A21EMPRESA);
						if ($this->A22INFORMATIVO->Exportable) $Doc->ExportField($this->A22INFORMATIVO);
						if ($this->A23ENQUETE->Exportable) $Doc->ExportField($this->A23ENQUETE);
						if ($this->A24DATA->Exportable) $Doc->ExportField($this->A24DATA);
						if ($this->valor_mensal->Exportable) $Doc->ExportField($this->valor_mensal);
						if ($this->valor_extenso->Exportable) $Doc->ExportField($this->valor_extenso);
						if ($this->referente_ao_mes->Exportable) $Doc->ExportField($this->referente_ao_mes);
					} else {
						if ($this->A00ID->Exportable) $Doc->ExportField($this->A00ID);
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
