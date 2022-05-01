<?php

// Global variable for table object
$funcionarios = NULL;

//
// Table class for funcionarios
//
class cfuncionarios extends cTable {
	var $cod_func;
	var $nome;
	var $endereco;
	var $numero;
	var $bairro;
	var $cidade;
	var $sexo;
	var $estado_civil;
	var $rg;
	var $cpf;
	var $carteira_trabalho;
	var $nacionalidade;
	var $naturalidade;
	var $datanasc;
	var $funcao;
	var $cod_empresa;
	var $dt_entrou_empresa;
	var $dt_entrou_categoria;
	var $foto;
	var $ativo;
	var $dependentes;
	var $dtcad;
	var $dtcarteira;
	var $telefone;
	var $acompanhante;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'funcionarios';
		$this->TableName = 'funcionarios';
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

		// cod_func
		$this->cod_func = new cField('funcionarios', 'funcionarios', 'x_cod_func', 'cod_func', '`cod_func`', '`cod_func`', 3, -1, FALSE, '`cod_func`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cod_func->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cod_func'] = &$this->cod_func;

		// nome
		$this->nome = new cField('funcionarios', 'funcionarios', 'x_nome', 'nome', '`nome`', '`nome`', 200, -1, FALSE, '`nome`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nome'] = &$this->nome;

		// endereco
		$this->endereco = new cField('funcionarios', 'funcionarios', 'x_endereco', 'endereco', '`endereco`', '`endereco`', 200, -1, FALSE, '`endereco`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['endereco'] = &$this->endereco;

		// numero
		$this->numero = new cField('funcionarios', 'funcionarios', 'x_numero', 'numero', '`numero`', '`numero`', 3, -1, FALSE, '`numero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->numero->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['numero'] = &$this->numero;

		// bairro
		$this->bairro = new cField('funcionarios', 'funcionarios', 'x_bairro', 'bairro', '`bairro`', '`bairro`', 200, -1, FALSE, '`bairro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['bairro'] = &$this->bairro;

		// cidade
		$this->cidade = new cField('funcionarios', 'funcionarios', 'x_cidade', 'cidade', '`cidade`', '`cidade`', 200, -1, FALSE, '`cidade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cidade'] = &$this->cidade;

		// sexo
		$this->sexo = new cField('funcionarios', 'funcionarios', 'x_sexo', 'sexo', '`sexo`', '`sexo`', 200, -1, FALSE, '`sexo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['sexo'] = &$this->sexo;

		// estado_civil
		$this->estado_civil = new cField('funcionarios', 'funcionarios', 'x_estado_civil', 'estado_civil', '`estado_civil`', '`estado_civil`', 3, -1, FALSE, '`estado_civil`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->estado_civil->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['estado_civil'] = &$this->estado_civil;

		// rg
		$this->rg = new cField('funcionarios', 'funcionarios', 'x_rg', 'rg', '`rg`', '`rg`', 200, -1, FALSE, '`rg`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['rg'] = &$this->rg;

		// cpf
		$this->cpf = new cField('funcionarios', 'funcionarios', 'x_cpf', 'cpf', '`cpf`', '`cpf`', 200, -1, FALSE, '`cpf`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cpf'] = &$this->cpf;

		// carteira_trabalho
		$this->carteira_trabalho = new cField('funcionarios', 'funcionarios', 'x_carteira_trabalho', 'carteira_trabalho', '`carteira_trabalho`', '`carteira_trabalho`', 200, -1, FALSE, '`carteira_trabalho`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['carteira_trabalho'] = &$this->carteira_trabalho;

		// nacionalidade
		$this->nacionalidade = new cField('funcionarios', 'funcionarios', 'x_nacionalidade', 'nacionalidade', '`nacionalidade`', '`nacionalidade`', 200, -1, FALSE, '`nacionalidade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nacionalidade'] = &$this->nacionalidade;

		// naturalidade
		$this->naturalidade = new cField('funcionarios', 'funcionarios', 'x_naturalidade', 'naturalidade', '`naturalidade`', '`naturalidade`', 200, -1, FALSE, '`naturalidade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['naturalidade'] = &$this->naturalidade;

		// datanasc
		$this->datanasc = new cField('funcionarios', 'funcionarios', 'x_datanasc', 'datanasc', '`datanasc`', '`datanasc`', 200, -1, FALSE, '`datanasc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->datanasc->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['datanasc'] = &$this->datanasc;

		// funcao
		$this->funcao = new cField('funcionarios', 'funcionarios', 'x_funcao', 'funcao', '`funcao`', '`funcao`', 200, -1, FALSE, '`funcao`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['funcao'] = &$this->funcao;

		// cod_empresa
		$this->cod_empresa = new cField('funcionarios', 'funcionarios', 'x_cod_empresa', 'cod_empresa', '`cod_empresa`', '`cod_empresa`', 3, -1, FALSE, '`cod_empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cod_empresa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cod_empresa'] = &$this->cod_empresa;

		// dt_entrou_empresa
		$this->dt_entrou_empresa = new cField('funcionarios', 'funcionarios', 'x_dt_entrou_empresa', 'dt_entrou_empresa', '`dt_entrou_empresa`', '`dt_entrou_empresa`', 200, -1, FALSE, '`dt_entrou_empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_entrou_empresa->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_entrou_empresa'] = &$this->dt_entrou_empresa;

		// dt_entrou_categoria
		$this->dt_entrou_categoria = new cField('funcionarios', 'funcionarios', 'x_dt_entrou_categoria', 'dt_entrou_categoria', '`dt_entrou_categoria`', '`dt_entrou_categoria`', 200, -1, FALSE, '`dt_entrou_categoria`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_entrou_categoria->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_entrou_categoria'] = &$this->dt_entrou_categoria;

		// foto
		$this->foto = new cField('funcionarios', 'funcionarios', 'x_foto', 'foto', '`foto`', '`foto`', 200, -1, TRUE, '`foto`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['foto'] = &$this->foto;

		// ativo
		$this->ativo = new cField('funcionarios', 'funcionarios', 'x_ativo', 'ativo', '`ativo`', '`ativo`', 3, -1, FALSE, '`ativo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ativo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ativo'] = &$this->ativo;

		// dependentes
		$this->dependentes = new cField('funcionarios', 'funcionarios', 'x_dependentes', 'dependentes', '`dependentes`', '`dependentes`', 201, -1, FALSE, '`dependentes`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dependentes'] = &$this->dependentes;

		// dtcad
		$this->dtcad = new cField('funcionarios', 'funcionarios', 'x_dtcad', 'dtcad', '`dtcad`', '`dtcad`', 200, -1, FALSE, '`dtcad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dtcad->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dtcad'] = &$this->dtcad;

		// dtcarteira
		$this->dtcarteira = new cField('funcionarios', 'funcionarios', 'x_dtcarteira', 'dtcarteira', '`dtcarteira`', '`dtcarteira`', 200, -1, FALSE, '`dtcarteira`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dtcarteira->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dtcarteira'] = &$this->dtcarteira;

		// telefone
		$this->telefone = new cField('funcionarios', 'funcionarios', 'x_telefone', 'telefone', '`telefone`', '`telefone`', 200, -1, FALSE, '`telefone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['telefone'] = &$this->telefone;

		// acompanhante
		$this->acompanhante = new cField('funcionarios', 'funcionarios', 'x_acompanhante', 'acompanhante', '`acompanhante`', '`acompanhante`', 201, -1, FALSE, '`acompanhante`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['acompanhante'] = &$this->acompanhante;
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
			$sDetailUrl = "funcionarioslist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`funcionarios`";
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
		$this->TableFilter = "`ativo` = 1";
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`cod_func` DESC";
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
	var $UpdateTable = "`funcionarios`";

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
			if (array_key_exists('cod_func', $rs))
				ew_AddFilter($where, ew_QuotedName('cod_func') . '=' . ew_QuotedValue($rs['cod_func'], $this->cod_func->FldDataType));
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
		return "`cod_func` = @cod_func@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->cod_func->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@cod_func@", ew_AdjustSql($this->cod_func->CurrentValue), $sKeyFilter); // Replace key value
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
			return "funcionarioslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "funcionarioslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("funcionariosview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("funcionariosview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "funcionariosadd.php?" . $this->UrlParm($parm);
		else
			return "funcionariosadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("funcionariosedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("funcionariosedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("funcionariosadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("funcionariosadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("funcionariosdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->cod_func->CurrentValue)) {
			$sUrl .= "cod_func=" . urlencode($this->cod_func->CurrentValue);
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
			$arKeys[] = @$_GET["cod_func"]; // cod_func

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
			$this->cod_func->CurrentValue = $key;
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
		$this->cod_func->setDbValue($rs->fields('cod_func'));
		$this->nome->setDbValue($rs->fields('nome'));
		$this->endereco->setDbValue($rs->fields('endereco'));
		$this->numero->setDbValue($rs->fields('numero'));
		$this->bairro->setDbValue($rs->fields('bairro'));
		$this->cidade->setDbValue($rs->fields('cidade'));
		$this->sexo->setDbValue($rs->fields('sexo'));
		$this->estado_civil->setDbValue($rs->fields('estado_civil'));
		$this->rg->setDbValue($rs->fields('rg'));
		$this->cpf->setDbValue($rs->fields('cpf'));
		$this->carteira_trabalho->setDbValue($rs->fields('carteira_trabalho'));
		$this->nacionalidade->setDbValue($rs->fields('nacionalidade'));
		$this->naturalidade->setDbValue($rs->fields('naturalidade'));
		$this->datanasc->setDbValue($rs->fields('datanasc'));
		$this->funcao->setDbValue($rs->fields('funcao'));
		$this->cod_empresa->setDbValue($rs->fields('cod_empresa'));
		$this->dt_entrou_empresa->setDbValue($rs->fields('dt_entrou_empresa'));
		$this->dt_entrou_categoria->setDbValue($rs->fields('dt_entrou_categoria'));
		$this->foto->Upload->DbValue = $rs->fields('foto');
		$this->ativo->setDbValue($rs->fields('ativo'));
		$this->dependentes->setDbValue($rs->fields('dependentes'));
		$this->dtcad->setDbValue($rs->fields('dtcad'));
		$this->dtcarteira->setDbValue($rs->fields('dtcarteira'));
		$this->telefone->setDbValue($rs->fields('telefone'));
		$this->acompanhante->setDbValue($rs->fields('acompanhante'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// cod_func
		// nome
		// endereco
		// numero
		// bairro
		// cidade
		// sexo
		// estado_civil
		// rg
		// cpf
		// carteira_trabalho
		// nacionalidade
		// naturalidade
		// datanasc
		// funcao
		// cod_empresa
		// dt_entrou_empresa
		// dt_entrou_categoria
		// foto
		// ativo
		// dependentes
		// dtcad
		// dtcarteira
		// telefone
		// acompanhante
		// cod_func

		$this->cod_func->ViewValue = $this->cod_func->CurrentValue;
		$this->cod_func->ViewCustomAttributes = "";

		// nome
		$this->nome->ViewValue = $this->nome->CurrentValue;
		$this->nome->ViewCustomAttributes = "";

		// endereco
		$this->endereco->ViewValue = $this->endereco->CurrentValue;
		$this->endereco->ViewCustomAttributes = "";

		// numero
		$this->numero->ViewValue = $this->numero->CurrentValue;
		$this->numero->ViewCustomAttributes = "";

		// bairro
		$this->bairro->ViewValue = $this->bairro->CurrentValue;
		$this->bairro->ViewCustomAttributes = "";

		// cidade
		$this->cidade->ViewValue = $this->cidade->CurrentValue;
		$this->cidade->ViewCustomAttributes = "";

		// sexo
		if (strval($this->sexo->CurrentValue) <> "") {
			switch ($this->sexo->CurrentValue) {
				case $this->sexo->FldTagValue(1):
					$this->sexo->ViewValue = $this->sexo->FldTagCaption(1) <> "" ? $this->sexo->FldTagCaption(1) : $this->sexo->CurrentValue;
					break;
				case $this->sexo->FldTagValue(2):
					$this->sexo->ViewValue = $this->sexo->FldTagCaption(2) <> "" ? $this->sexo->FldTagCaption(2) : $this->sexo->CurrentValue;
					break;
				default:
					$this->sexo->ViewValue = $this->sexo->CurrentValue;
			}
		} else {
			$this->sexo->ViewValue = NULL;
		}
		$this->sexo->ViewCustomAttributes = "";

		// estado_civil
		if (strval($this->estado_civil->CurrentValue) <> "") {
			switch ($this->estado_civil->CurrentValue) {
				case $this->estado_civil->FldTagValue(1):
					$this->estado_civil->ViewValue = $this->estado_civil->FldTagCaption(1) <> "" ? $this->estado_civil->FldTagCaption(1) : $this->estado_civil->CurrentValue;
					break;
				case $this->estado_civil->FldTagValue(2):
					$this->estado_civil->ViewValue = $this->estado_civil->FldTagCaption(2) <> "" ? $this->estado_civil->FldTagCaption(2) : $this->estado_civil->CurrentValue;
					break;
				case $this->estado_civil->FldTagValue(3):
					$this->estado_civil->ViewValue = $this->estado_civil->FldTagCaption(3) <> "" ? $this->estado_civil->FldTagCaption(3) : $this->estado_civil->CurrentValue;
					break;
				case $this->estado_civil->FldTagValue(4):
					$this->estado_civil->ViewValue = $this->estado_civil->FldTagCaption(4) <> "" ? $this->estado_civil->FldTagCaption(4) : $this->estado_civil->CurrentValue;
					break;
				case $this->estado_civil->FldTagValue(5):
					$this->estado_civil->ViewValue = $this->estado_civil->FldTagCaption(5) <> "" ? $this->estado_civil->FldTagCaption(5) : $this->estado_civil->CurrentValue;
					break;
				default:
					$this->estado_civil->ViewValue = $this->estado_civil->CurrentValue;
			}
		} else {
			$this->estado_civil->ViewValue = NULL;
		}
		$this->estado_civil->ViewCustomAttributes = "";

		// rg
		$this->rg->ViewValue = $this->rg->CurrentValue;
		$this->rg->ViewCustomAttributes = "";

		// cpf
		$this->cpf->ViewValue = $this->cpf->CurrentValue;
		$this->cpf->ViewCustomAttributes = "";

		// carteira_trabalho
		$this->carteira_trabalho->ViewValue = $this->carteira_trabalho->CurrentValue;
		$this->carteira_trabalho->ViewCustomAttributes = "";

		// nacionalidade
		$this->nacionalidade->ViewValue = $this->nacionalidade->CurrentValue;
		$this->nacionalidade->ViewCustomAttributes = "";

		// naturalidade
		$this->naturalidade->ViewValue = $this->naturalidade->CurrentValue;
		$this->naturalidade->ViewCustomAttributes = "";

		// datanasc
		$this->datanasc->ViewValue = $this->datanasc->CurrentValue;
		$this->datanasc->ViewCustomAttributes = "";

		// funcao
		$this->funcao->ViewValue = $this->funcao->CurrentValue;
		$this->funcao->ViewCustomAttributes = "";

		// cod_empresa
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
		$this->cod_empresa->ViewCustomAttributes = "";

		// dt_entrou_empresa
		$this->dt_entrou_empresa->ViewValue = $this->dt_entrou_empresa->CurrentValue;
		$this->dt_entrou_empresa->ViewCustomAttributes = "";

		// dt_entrou_categoria
		$this->dt_entrou_categoria->ViewValue = $this->dt_entrou_categoria->CurrentValue;
		$this->dt_entrou_categoria->ViewCustomAttributes = "";

		// foto
		$this->foto->UploadPath = sistema;
		if (!ew_Empty($this->foto->Upload->DbValue)) {
			$this->foto->ImageAlt = $this->foto->FldAlt();
			$this->foto->ViewValue = ew_UploadPathEx(FALSE, $this->foto->UploadPath) . $this->foto->Upload->DbValue;
			if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
				$this->foto->ViewValue = ew_UploadPathEx(TRUE, $this->foto->UploadPath) . $this->foto->Upload->DbValue;
			}
		} else {
			$this->foto->ViewValue = "";
		}
		$this->foto->ViewCustomAttributes = "";

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

		// dependentes
		$this->dependentes->ViewValue = $this->dependentes->CurrentValue;
		$this->dependentes->ViewCustomAttributes = "";

		// dtcad
		$this->dtcad->ViewValue = $this->dtcad->CurrentValue;
		$this->dtcad->ViewCustomAttributes = "";

		// dtcarteira
		$this->dtcarteira->ViewValue = $this->dtcarteira->CurrentValue;
		$this->dtcarteira->ViewCustomAttributes = "";

		// telefone
		$this->telefone->ViewValue = $this->telefone->CurrentValue;
		$this->telefone->ViewCustomAttributes = "";

		// acompanhante
		$this->acompanhante->ViewValue = $this->acompanhante->CurrentValue;
		$this->acompanhante->ViewCustomAttributes = "";

		// cod_func
		$this->cod_func->LinkCustomAttributes = "";
		$this->cod_func->HrefValue = "";
		$this->cod_func->TooltipValue = "";

		// nome
		$this->nome->LinkCustomAttributes = "";
		$this->nome->HrefValue = "";
		$this->nome->TooltipValue = "";

		// endereco
		$this->endereco->LinkCustomAttributes = "";
		$this->endereco->HrefValue = "";
		$this->endereco->TooltipValue = "";

		// numero
		$this->numero->LinkCustomAttributes = "";
		$this->numero->HrefValue = "";
		$this->numero->TooltipValue = "";

		// bairro
		$this->bairro->LinkCustomAttributes = "";
		$this->bairro->HrefValue = "";
		$this->bairro->TooltipValue = "";

		// cidade
		$this->cidade->LinkCustomAttributes = "";
		$this->cidade->HrefValue = "";
		$this->cidade->TooltipValue = "";

		// sexo
		$this->sexo->LinkCustomAttributes = "";
		$this->sexo->HrefValue = "";
		$this->sexo->TooltipValue = "";

		// estado_civil
		$this->estado_civil->LinkCustomAttributes = "";
		$this->estado_civil->HrefValue = "";
		$this->estado_civil->TooltipValue = "";

		// rg
		$this->rg->LinkCustomAttributes = "";
		$this->rg->HrefValue = "";
		$this->rg->TooltipValue = "";

		// cpf
		$this->cpf->LinkCustomAttributes = "";
		$this->cpf->HrefValue = "";
		$this->cpf->TooltipValue = "";

		// carteira_trabalho
		$this->carteira_trabalho->LinkCustomAttributes = "";
		$this->carteira_trabalho->HrefValue = "";
		$this->carteira_trabalho->TooltipValue = "";

		// nacionalidade
		$this->nacionalidade->LinkCustomAttributes = "";
		$this->nacionalidade->HrefValue = "";
		$this->nacionalidade->TooltipValue = "";

		// naturalidade
		$this->naturalidade->LinkCustomAttributes = "";
		$this->naturalidade->HrefValue = "";
		$this->naturalidade->TooltipValue = "";

		// datanasc
		$this->datanasc->LinkCustomAttributes = "";
		$this->datanasc->HrefValue = "";
		$this->datanasc->TooltipValue = "";

		// funcao
		$this->funcao->LinkCustomAttributes = "";
		$this->funcao->HrefValue = "";
		$this->funcao->TooltipValue = "";

		// cod_empresa
		$this->cod_empresa->LinkCustomAttributes = "";
		$this->cod_empresa->HrefValue = "";
		$this->cod_empresa->TooltipValue = "";

		// dt_entrou_empresa
		$this->dt_entrou_empresa->LinkCustomAttributes = "";
		$this->dt_entrou_empresa->HrefValue = "";
		$this->dt_entrou_empresa->TooltipValue = "";

		// dt_entrou_categoria
		$this->dt_entrou_categoria->LinkCustomAttributes = "";
		$this->dt_entrou_categoria->HrefValue = "";
		$this->dt_entrou_categoria->TooltipValue = "";

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
			$this->foto->LinkAttrs["data-rel"] = "funcionarios_x_foto";
			$this->foto->LinkAttrs["class"] = "ewLightbox";
		}

		// ativo
		$this->ativo->LinkCustomAttributes = "";
		$this->ativo->HrefValue = "";
		$this->ativo->TooltipValue = "";

		// dependentes
		$this->dependentes->LinkCustomAttributes = "";
		$this->dependentes->HrefValue = "";
		$this->dependentes->TooltipValue = "";

		// dtcad
		$this->dtcad->LinkCustomAttributes = "";
		$this->dtcad->HrefValue = "";
		$this->dtcad->TooltipValue = "";

		// dtcarteira
		$this->dtcarteira->LinkCustomAttributes = "";
		$this->dtcarteira->HrefValue = "";
		$this->dtcarteira->TooltipValue = "";

		// telefone
		$this->telefone->LinkCustomAttributes = "";
		$this->telefone->HrefValue = "";
		$this->telefone->TooltipValue = "";

		// acompanhante
		$this->acompanhante->LinkCustomAttributes = "";
		$this->acompanhante->HrefValue = "";
		$this->acompanhante->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// cod_func
		$this->cod_func->EditAttrs["class"] = "form-control";
		$this->cod_func->EditCustomAttributes = "";
		$this->cod_func->EditValue = $this->cod_func->CurrentValue;
		$this->cod_func->ViewCustomAttributes = "";

		// nome
		$this->nome->EditAttrs["class"] = "form-control";
		$this->nome->EditCustomAttributes = "";
		$this->nome->EditValue = ew_HtmlEncode($this->nome->CurrentValue);
		$this->nome->PlaceHolder = ew_RemoveHtml($this->nome->FldCaption());

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

		// bairro
		$this->bairro->EditAttrs["class"] = "form-control";
		$this->bairro->EditCustomAttributes = "";
		$this->bairro->EditValue = ew_HtmlEncode($this->bairro->CurrentValue);
		$this->bairro->PlaceHolder = ew_RemoveHtml($this->bairro->FldCaption());

		// cidade
		$this->cidade->EditAttrs["class"] = "form-control";
		$this->cidade->EditCustomAttributes = "";
		$this->cidade->EditValue = ew_HtmlEncode($this->cidade->CurrentValue);
		$this->cidade->PlaceHolder = ew_RemoveHtml($this->cidade->FldCaption());

		// sexo
		$this->sexo->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->sexo->FldTagValue(1), $this->sexo->FldTagCaption(1) <> "" ? $this->sexo->FldTagCaption(1) : $this->sexo->FldTagValue(1));
		$arwrk[] = array($this->sexo->FldTagValue(2), $this->sexo->FldTagCaption(2) <> "" ? $this->sexo->FldTagCaption(2) : $this->sexo->FldTagValue(2));
		$this->sexo->EditValue = $arwrk;

		// estado_civil
		$this->estado_civil->EditAttrs["class"] = "form-control";
		$this->estado_civil->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->estado_civil->FldTagValue(1), $this->estado_civil->FldTagCaption(1) <> "" ? $this->estado_civil->FldTagCaption(1) : $this->estado_civil->FldTagValue(1));
		$arwrk[] = array($this->estado_civil->FldTagValue(2), $this->estado_civil->FldTagCaption(2) <> "" ? $this->estado_civil->FldTagCaption(2) : $this->estado_civil->FldTagValue(2));
		$arwrk[] = array($this->estado_civil->FldTagValue(3), $this->estado_civil->FldTagCaption(3) <> "" ? $this->estado_civil->FldTagCaption(3) : $this->estado_civil->FldTagValue(3));
		$arwrk[] = array($this->estado_civil->FldTagValue(4), $this->estado_civil->FldTagCaption(4) <> "" ? $this->estado_civil->FldTagCaption(4) : $this->estado_civil->FldTagValue(4));
		$arwrk[] = array($this->estado_civil->FldTagValue(5), $this->estado_civil->FldTagCaption(5) <> "" ? $this->estado_civil->FldTagCaption(5) : $this->estado_civil->FldTagValue(5));
		array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
		$this->estado_civil->EditValue = $arwrk;

		// rg
		$this->rg->EditAttrs["class"] = "form-control";
		$this->rg->EditCustomAttributes = "";
		$this->rg->EditValue = ew_HtmlEncode($this->rg->CurrentValue);
		$this->rg->PlaceHolder = ew_RemoveHtml($this->rg->FldCaption());

		// cpf
		$this->cpf->EditAttrs["class"] = "form-control";
		$this->cpf->EditCustomAttributes = "";
		$this->cpf->EditValue = ew_HtmlEncode($this->cpf->CurrentValue);
		$this->cpf->PlaceHolder = ew_RemoveHtml($this->cpf->FldCaption());

		// carteira_trabalho
		$this->carteira_trabalho->EditAttrs["class"] = "form-control";
		$this->carteira_trabalho->EditCustomAttributes = "";
		$this->carteira_trabalho->EditValue = ew_HtmlEncode($this->carteira_trabalho->CurrentValue);
		$this->carteira_trabalho->PlaceHolder = ew_RemoveHtml($this->carteira_trabalho->FldCaption());

		// nacionalidade
		$this->nacionalidade->EditAttrs["class"] = "form-control";
		$this->nacionalidade->EditCustomAttributes = "";
		$this->nacionalidade->EditValue = ew_HtmlEncode($this->nacionalidade->CurrentValue);
		$this->nacionalidade->PlaceHolder = ew_RemoveHtml($this->nacionalidade->FldCaption());

		// naturalidade
		$this->naturalidade->EditAttrs["class"] = "form-control";
		$this->naturalidade->EditCustomAttributes = "";
		$this->naturalidade->EditValue = ew_HtmlEncode($this->naturalidade->CurrentValue);
		$this->naturalidade->PlaceHolder = ew_RemoveHtml($this->naturalidade->FldCaption());

		// datanasc
		$this->datanasc->EditAttrs["class"] = "form-control";
		$this->datanasc->EditCustomAttributes = "";
		$this->datanasc->EditValue = ew_HtmlEncode($this->datanasc->CurrentValue);
		$this->datanasc->PlaceHolder = ew_RemoveHtml($this->datanasc->FldCaption());

		// funcao
		$this->funcao->EditAttrs["class"] = "form-control";
		$this->funcao->EditCustomAttributes = "";
		$this->funcao->EditValue = ew_HtmlEncode($this->funcao->CurrentValue);
		$this->funcao->PlaceHolder = ew_RemoveHtml($this->funcao->FldCaption());

		// cod_empresa
		$this->cod_empresa->EditAttrs["class"] = "form-control";
		$this->cod_empresa->EditCustomAttributes = "";

		// dt_entrou_empresa
		$this->dt_entrou_empresa->EditAttrs["class"] = "form-control";
		$this->dt_entrou_empresa->EditCustomAttributes = "";
		$this->dt_entrou_empresa->EditValue = ew_HtmlEncode($this->dt_entrou_empresa->CurrentValue);
		$this->dt_entrou_empresa->PlaceHolder = ew_RemoveHtml($this->dt_entrou_empresa->FldCaption());

		// dt_entrou_categoria
		$this->dt_entrou_categoria->EditAttrs["class"] = "form-control";
		$this->dt_entrou_categoria->EditCustomAttributes = "";
		$this->dt_entrou_categoria->EditValue = ew_HtmlEncode($this->dt_entrou_categoria->CurrentValue);
		$this->dt_entrou_categoria->PlaceHolder = ew_RemoveHtml($this->dt_entrou_categoria->FldCaption());

		// foto
		$this->foto->EditAttrs["class"] = "form-control";
		$this->foto->EditCustomAttributes = "";
		$this->foto->UploadPath = sistema;
		if (!ew_Empty($this->foto->Upload->DbValue)) {
			$this->foto->ImageAlt = $this->foto->FldAlt();
			$this->foto->EditValue = ew_UploadPathEx(FALSE, $this->foto->UploadPath) . $this->foto->Upload->DbValue;
			if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
				$this->foto->EditValue = ew_UploadPathEx(TRUE, $this->foto->UploadPath) . $this->foto->Upload->DbValue;
			}
		} else {
			$this->foto->EditValue = "";
		}
		if (!ew_Empty($this->foto->CurrentValue))
			$this->foto->Upload->FileName = $this->foto->CurrentValue;

		// ativo
		$this->ativo->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->ativo->FldTagValue(1), $this->ativo->FldTagCaption(1) <> "" ? $this->ativo->FldTagCaption(1) : $this->ativo->FldTagValue(1));
		$arwrk[] = array($this->ativo->FldTagValue(2), $this->ativo->FldTagCaption(2) <> "" ? $this->ativo->FldTagCaption(2) : $this->ativo->FldTagValue(2));
		$this->ativo->EditValue = $arwrk;

		// dependentes
		$this->dependentes->EditAttrs["class"] = "form-control";
		$this->dependentes->EditCustomAttributes = "";
		$this->dependentes->EditValue = ew_HtmlEncode($this->dependentes->CurrentValue);
		$this->dependentes->PlaceHolder = ew_RemoveHtml($this->dependentes->FldCaption());

		// dtcad
		$this->dtcad->EditAttrs["class"] = "form-control";
		$this->dtcad->EditCustomAttributes = "";
		$this->dtcad->EditValue = ew_HtmlEncode($this->dtcad->CurrentValue);
		$this->dtcad->PlaceHolder = ew_RemoveHtml($this->dtcad->FldCaption());

		// dtcarteira
		$this->dtcarteira->EditAttrs["class"] = "form-control";
		$this->dtcarteira->EditCustomAttributes = "";
		$this->dtcarteira->EditValue = ew_HtmlEncode($this->dtcarteira->CurrentValue);
		$this->dtcarteira->PlaceHolder = ew_RemoveHtml($this->dtcarteira->FldCaption());

		// telefone
		$this->telefone->EditAttrs["class"] = "form-control";
		$this->telefone->EditCustomAttributes = "";
		$this->telefone->EditValue = ew_HtmlEncode($this->telefone->CurrentValue);
		$this->telefone->PlaceHolder = ew_RemoveHtml($this->telefone->FldCaption());

		// acompanhante
		$this->acompanhante->EditAttrs["class"] = "form-control";
		$this->acompanhante->EditCustomAttributes = "";
		$this->acompanhante->EditValue = ew_HtmlEncode($this->acompanhante->CurrentValue);
		$this->acompanhante->PlaceHolder = ew_RemoveHtml($this->acompanhante->FldCaption());

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
					if ($this->cod_func->Exportable) $Doc->ExportCaption($this->cod_func);
					if ($this->nome->Exportable) $Doc->ExportCaption($this->nome);
					if ($this->endereco->Exportable) $Doc->ExportCaption($this->endereco);
					if ($this->numero->Exportable) $Doc->ExportCaption($this->numero);
					if ($this->bairro->Exportable) $Doc->ExportCaption($this->bairro);
					if ($this->cidade->Exportable) $Doc->ExportCaption($this->cidade);
					if ($this->sexo->Exportable) $Doc->ExportCaption($this->sexo);
					if ($this->estado_civil->Exportable) $Doc->ExportCaption($this->estado_civil);
					if ($this->rg->Exportable) $Doc->ExportCaption($this->rg);
					if ($this->cpf->Exportable) $Doc->ExportCaption($this->cpf);
					if ($this->carteira_trabalho->Exportable) $Doc->ExportCaption($this->carteira_trabalho);
					if ($this->nacionalidade->Exportable) $Doc->ExportCaption($this->nacionalidade);
					if ($this->naturalidade->Exportable) $Doc->ExportCaption($this->naturalidade);
					if ($this->datanasc->Exportable) $Doc->ExportCaption($this->datanasc);
					if ($this->funcao->Exportable) $Doc->ExportCaption($this->funcao);
					if ($this->cod_empresa->Exportable) $Doc->ExportCaption($this->cod_empresa);
					if ($this->dt_entrou_empresa->Exportable) $Doc->ExportCaption($this->dt_entrou_empresa);
					if ($this->dt_entrou_categoria->Exportable) $Doc->ExportCaption($this->dt_entrou_categoria);
					if ($this->foto->Exportable) $Doc->ExportCaption($this->foto);
					if ($this->ativo->Exportable) $Doc->ExportCaption($this->ativo);
					if ($this->dependentes->Exportable) $Doc->ExportCaption($this->dependentes);
					if ($this->dtcad->Exportable) $Doc->ExportCaption($this->dtcad);
					if ($this->dtcarteira->Exportable) $Doc->ExportCaption($this->dtcarteira);
					if ($this->telefone->Exportable) $Doc->ExportCaption($this->telefone);
					if ($this->acompanhante->Exportable) $Doc->ExportCaption($this->acompanhante);
				} else {
					if ($this->cod_func->Exportable) $Doc->ExportCaption($this->cod_func);
					if ($this->nome->Exportable) $Doc->ExportCaption($this->nome);
					if ($this->endereco->Exportable) $Doc->ExportCaption($this->endereco);
					if ($this->numero->Exportable) $Doc->ExportCaption($this->numero);
					if ($this->bairro->Exportable) $Doc->ExportCaption($this->bairro);
					if ($this->cidade->Exportable) $Doc->ExportCaption($this->cidade);
					if ($this->sexo->Exportable) $Doc->ExportCaption($this->sexo);
					if ($this->estado_civil->Exportable) $Doc->ExportCaption($this->estado_civil);
					if ($this->rg->Exportable) $Doc->ExportCaption($this->rg);
					if ($this->cpf->Exportable) $Doc->ExportCaption($this->cpf);
					if ($this->carteira_trabalho->Exportable) $Doc->ExportCaption($this->carteira_trabalho);
					if ($this->nacionalidade->Exportable) $Doc->ExportCaption($this->nacionalidade);
					if ($this->naturalidade->Exportable) $Doc->ExportCaption($this->naturalidade);
					if ($this->datanasc->Exportable) $Doc->ExportCaption($this->datanasc);
					if ($this->funcao->Exportable) $Doc->ExportCaption($this->funcao);
					if ($this->cod_empresa->Exportable) $Doc->ExportCaption($this->cod_empresa);
					if ($this->dt_entrou_empresa->Exportable) $Doc->ExportCaption($this->dt_entrou_empresa);
					if ($this->dt_entrou_categoria->Exportable) $Doc->ExportCaption($this->dt_entrou_categoria);
					if ($this->foto->Exportable) $Doc->ExportCaption($this->foto);
					if ($this->ativo->Exportable) $Doc->ExportCaption($this->ativo);
					if ($this->dtcad->Exportable) $Doc->ExportCaption($this->dtcad);
					if ($this->dtcarteira->Exportable) $Doc->ExportCaption($this->dtcarteira);
					if ($this->telefone->Exportable) $Doc->ExportCaption($this->telefone);
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
						if ($this->cod_func->Exportable) $Doc->ExportField($this->cod_func);
						if ($this->nome->Exportable) $Doc->ExportField($this->nome);
						if ($this->endereco->Exportable) $Doc->ExportField($this->endereco);
						if ($this->numero->Exportable) $Doc->ExportField($this->numero);
						if ($this->bairro->Exportable) $Doc->ExportField($this->bairro);
						if ($this->cidade->Exportable) $Doc->ExportField($this->cidade);
						if ($this->sexo->Exportable) $Doc->ExportField($this->sexo);
						if ($this->estado_civil->Exportable) $Doc->ExportField($this->estado_civil);
						if ($this->rg->Exportable) $Doc->ExportField($this->rg);
						if ($this->cpf->Exportable) $Doc->ExportField($this->cpf);
						if ($this->carteira_trabalho->Exportable) $Doc->ExportField($this->carteira_trabalho);
						if ($this->nacionalidade->Exportable) $Doc->ExportField($this->nacionalidade);
						if ($this->naturalidade->Exportable) $Doc->ExportField($this->naturalidade);
						if ($this->datanasc->Exportable) $Doc->ExportField($this->datanasc);
						if ($this->funcao->Exportable) $Doc->ExportField($this->funcao);
						if ($this->cod_empresa->Exportable) $Doc->ExportField($this->cod_empresa);
						if ($this->dt_entrou_empresa->Exportable) $Doc->ExportField($this->dt_entrou_empresa);
						if ($this->dt_entrou_categoria->Exportable) $Doc->ExportField($this->dt_entrou_categoria);
						if ($this->foto->Exportable) $Doc->ExportField($this->foto);
						if ($this->ativo->Exportable) $Doc->ExportField($this->ativo);
						if ($this->dependentes->Exportable) $Doc->ExportField($this->dependentes);
						if ($this->dtcad->Exportable) $Doc->ExportField($this->dtcad);
						if ($this->dtcarteira->Exportable) $Doc->ExportField($this->dtcarteira);
						if ($this->telefone->Exportable) $Doc->ExportField($this->telefone);
						if ($this->acompanhante->Exportable) $Doc->ExportField($this->acompanhante);
					} else {
						if ($this->cod_func->Exportable) $Doc->ExportField($this->cod_func);
						if ($this->nome->Exportable) $Doc->ExportField($this->nome);
						if ($this->endereco->Exportable) $Doc->ExportField($this->endereco);
						if ($this->numero->Exportable) $Doc->ExportField($this->numero);
						if ($this->bairro->Exportable) $Doc->ExportField($this->bairro);
						if ($this->cidade->Exportable) $Doc->ExportField($this->cidade);
						if ($this->sexo->Exportable) $Doc->ExportField($this->sexo);
						if ($this->estado_civil->Exportable) $Doc->ExportField($this->estado_civil);
						if ($this->rg->Exportable) $Doc->ExportField($this->rg);
						if ($this->cpf->Exportable) $Doc->ExportField($this->cpf);
						if ($this->carteira_trabalho->Exportable) $Doc->ExportField($this->carteira_trabalho);
						if ($this->nacionalidade->Exportable) $Doc->ExportField($this->nacionalidade);
						if ($this->naturalidade->Exportable) $Doc->ExportField($this->naturalidade);
						if ($this->datanasc->Exportable) $Doc->ExportField($this->datanasc);
						if ($this->funcao->Exportable) $Doc->ExportField($this->funcao);
						if ($this->cod_empresa->Exportable) $Doc->ExportField($this->cod_empresa);
						if ($this->dt_entrou_empresa->Exportable) $Doc->ExportField($this->dt_entrou_empresa);
						if ($this->dt_entrou_categoria->Exportable) $Doc->ExportField($this->dt_entrou_categoria);
						if ($this->foto->Exportable) $Doc->ExportField($this->foto);
						if ($this->ativo->Exportable) $Doc->ExportField($this->ativo);
						if ($this->dtcad->Exportable) $Doc->ExportField($this->dtcad);
						if ($this->dtcarteira->Exportable) $Doc->ExportField($this->dtcarteira);
						if ($this->telefone->Exportable) $Doc->ExportField($this->telefone);
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
