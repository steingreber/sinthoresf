<?php

// Global variable for table object
$pessoas = NULL;

//
// Table class for pessoas
//
class cpessoas extends cTable {
	var $cod_pessoa;
	var $datacadastro;
	var $nome;
	var $endereco;
	var $numero;
	var $complemento;
	var $bairro;
	var $cidade;
	var $estado;
	var $CEP;
	var $telefone;
	var $sexo;
	var $datanasc;
	var $estado_civil;
	var $rg;
	var $cpf;
	var $carteira_trabalho;
	var $nacionalidade;
	var $naturalidade;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'pessoas';
		$this->TableName = 'pessoas';
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

		// cod_pessoa
		$this->cod_pessoa = new cField('pessoas', 'pessoas', 'x_cod_pessoa', 'cod_pessoa', '`cod_pessoa`', '`cod_pessoa`', 3, -1, FALSE, '`cod_pessoa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cod_pessoa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cod_pessoa'] = &$this->cod_pessoa;

		// datacadastro
		$this->datacadastro = new cField('pessoas', 'pessoas', 'x_datacadastro', 'datacadastro', '`datacadastro`', '`datacadastro`', 200, -1, FALSE, '`datacadastro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->datacadastro->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['datacadastro'] = &$this->datacadastro;

		// nome
		$this->nome = new cField('pessoas', 'pessoas', 'x_nome', 'nome', '`nome`', '`nome`', 200, -1, FALSE, '`nome`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nome'] = &$this->nome;

		// endereco
		$this->endereco = new cField('pessoas', 'pessoas', 'x_endereco', 'endereco', '`endereco`', '`endereco`', 200, -1, FALSE, '`endereco`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['endereco'] = &$this->endereco;

		// numero
		$this->numero = new cField('pessoas', 'pessoas', 'x_numero', 'numero', '`numero`', '`numero`', 3, -1, FALSE, '`numero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->numero->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['numero'] = &$this->numero;

		// complemento
		$this->complemento = new cField('pessoas', 'pessoas', 'x_complemento', 'complemento', '`complemento`', '`complemento`', 200, -1, FALSE, '`complemento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['complemento'] = &$this->complemento;

		// bairro
		$this->bairro = new cField('pessoas', 'pessoas', 'x_bairro', 'bairro', '`bairro`', '`bairro`', 200, -1, FALSE, '`bairro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['bairro'] = &$this->bairro;

		// cidade
		$this->cidade = new cField('pessoas', 'pessoas', 'x_cidade', 'cidade', '`cidade`', '`cidade`', 200, -1, FALSE, '`cidade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cidade'] = &$this->cidade;

		// estado
		$this->estado = new cField('pessoas', 'pessoas', 'x_estado', 'estado', '`estado`', '`estado`', 200, -1, FALSE, '`estado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['estado'] = &$this->estado;

		// CEP
		$this->CEP = new cField('pessoas', 'pessoas', 'x_CEP', 'CEP', '`CEP`', '`CEP`', 200, -1, FALSE, '`CEP`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CEP'] = &$this->CEP;

		// telefone
		$this->telefone = new cField('pessoas', 'pessoas', 'x_telefone', 'telefone', '`telefone`', '`telefone`', 200, -1, FALSE, '`telefone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->telefone->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['telefone'] = &$this->telefone;

		// sexo
		$this->sexo = new cField('pessoas', 'pessoas', 'x_sexo', 'sexo', '`sexo`', '`sexo`', 200, -1, FALSE, '`sexo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['sexo'] = &$this->sexo;

		// datanasc
		$this->datanasc = new cField('pessoas', 'pessoas', 'x_datanasc', 'datanasc', '`datanasc`', '`datanasc`', 200, 7, FALSE, '`datanasc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->datanasc->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['datanasc'] = &$this->datanasc;

		// estado_civil
		$this->estado_civil = new cField('pessoas', 'pessoas', 'x_estado_civil', 'estado_civil', '`estado_civil`', '`estado_civil`', 200, -1, FALSE, '`estado_civil`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->estado_civil->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['estado_civil'] = &$this->estado_civil;

		// rg
		$this->rg = new cField('pessoas', 'pessoas', 'x_rg', 'rg', '`rg`', '`rg`', 200, -1, FALSE, '`rg`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['rg'] = &$this->rg;

		// cpf
		$this->cpf = new cField('pessoas', 'pessoas', 'x_cpf', 'cpf', '`cpf`', '`cpf`', 200, -1, FALSE, '`cpf`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cpf->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cpf'] = &$this->cpf;

		// carteira_trabalho
		$this->carteira_trabalho = new cField('pessoas', 'pessoas', 'x_carteira_trabalho', 'carteira_trabalho', '`carteira_trabalho`', '`carteira_trabalho`', 200, -1, FALSE, '`carteira_trabalho`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['carteira_trabalho'] = &$this->carteira_trabalho;

		// nacionalidade
		$this->nacionalidade = new cField('pessoas', 'pessoas', 'x_nacionalidade', 'nacionalidade', '`nacionalidade`', '`nacionalidade`', 200, -1, FALSE, '`nacionalidade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nacionalidade'] = &$this->nacionalidade;

		// naturalidade
		$this->naturalidade = new cField('pessoas', 'pessoas', 'x_naturalidade', 'naturalidade', '`naturalidade`', '`naturalidade`', 200, -1, FALSE, '`naturalidade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['naturalidade'] = &$this->naturalidade;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`pessoas`";
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`cod_pessoa` DESC";
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
	var $UpdateTable = "`pessoas`";

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
			if (array_key_exists('cod_pessoa', $rs))
				ew_AddFilter($where, ew_QuotedName('cod_pessoa') . '=' . ew_QuotedValue($rs['cod_pessoa'], $this->cod_pessoa->FldDataType));
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
		return "`cod_pessoa` = @cod_pessoa@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->cod_pessoa->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@cod_pessoa@", ew_AdjustSql($this->cod_pessoa->CurrentValue), $sKeyFilter); // Replace key value
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
			return "pessoaslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "pessoaslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("pessoasview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("pessoasview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "pessoasadd.php?" . $this->UrlParm($parm);
		else
			return "pessoasadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("pessoasedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("pessoasadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("pessoasdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->cod_pessoa->CurrentValue)) {
			$sUrl .= "cod_pessoa=" . urlencode($this->cod_pessoa->CurrentValue);
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
			$arKeys[] = @$_GET["cod_pessoa"]; // cod_pessoa

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
			$this->cod_pessoa->CurrentValue = $key;
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
		$this->cod_pessoa->setDbValue($rs->fields('cod_pessoa'));
		$this->datacadastro->setDbValue($rs->fields('datacadastro'));
		$this->nome->setDbValue($rs->fields('nome'));
		$this->endereco->setDbValue($rs->fields('endereco'));
		$this->numero->setDbValue($rs->fields('numero'));
		$this->complemento->setDbValue($rs->fields('complemento'));
		$this->bairro->setDbValue($rs->fields('bairro'));
		$this->cidade->setDbValue($rs->fields('cidade'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->CEP->setDbValue($rs->fields('CEP'));
		$this->telefone->setDbValue($rs->fields('telefone'));
		$this->sexo->setDbValue($rs->fields('sexo'));
		$this->datanasc->setDbValue($rs->fields('datanasc'));
		$this->estado_civil->setDbValue($rs->fields('estado_civil'));
		$this->rg->setDbValue($rs->fields('rg'));
		$this->cpf->setDbValue($rs->fields('cpf'));
		$this->carteira_trabalho->setDbValue($rs->fields('carteira_trabalho'));
		$this->nacionalidade->setDbValue($rs->fields('nacionalidade'));
		$this->naturalidade->setDbValue($rs->fields('naturalidade'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// cod_pessoa
		// datacadastro
		// nome
		// endereco
		// numero
		// complemento
		// bairro
		// cidade
		// estado
		// CEP
		// telefone
		// sexo
		// datanasc
		// estado_civil
		// rg
		// cpf
		// carteira_trabalho
		// nacionalidade
		// naturalidade
		// cod_pessoa

		$this->cod_pessoa->ViewValue = $this->cod_pessoa->CurrentValue;
		$this->cod_pessoa->ViewCustomAttributes = "";

		// datacadastro
		$this->datacadastro->ViewValue = $this->datacadastro->CurrentValue;
		$this->datacadastro->ViewCustomAttributes = "";

		// nome
		$this->nome->ViewValue = $this->nome->CurrentValue;
		$this->nome->CssStyle = "font-weight: bold;";
		$this->nome->ViewCustomAttributes = "";

		// endereco
		$this->endereco->ViewValue = $this->endereco->CurrentValue;
		$this->endereco->ViewCustomAttributes = "";

		// numero
		$this->numero->ViewValue = $this->numero->CurrentValue;
		$this->numero->ViewValue = ew_FormatNumber($this->numero->ViewValue, 0, -2, -2, -2);
		$this->numero->ViewCustomAttributes = "";

		// complemento
		$this->complemento->ViewValue = $this->complemento->CurrentValue;
		$this->complemento->ViewCustomAttributes = "";

		// bairro
		$this->bairro->ViewValue = $this->bairro->CurrentValue;
		$this->bairro->ViewCustomAttributes = "";

		// cidade
		$this->cidade->ViewValue = $this->cidade->CurrentValue;
		$this->cidade->ViewCustomAttributes = "";

		// estado
		if (strval($this->estado->CurrentValue) <> "") {
			$sFilterWrk = "`sigla`" . ew_SearchString("=", $this->estado->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT `sigla`, `estado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estados`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->estado, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->estado->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->estado->ViewValue = $this->estado->CurrentValue;
			}
		} else {
			$this->estado->ViewValue = NULL;
		}
		$this->estado->ViewCustomAttributes = "";

		// CEP
		$this->CEP->ViewValue = $this->CEP->CurrentValue;
		$this->CEP->ViewCustomAttributes = "";

		// telefone
		$this->telefone->ViewValue = $this->telefone->CurrentValue;
		$this->telefone->ViewValue = ew_FormatNumber($this->telefone->ViewValue, 0, -2, -2, -2);
		$this->telefone->ViewCustomAttributes = "";

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

		// datanasc
		$this->datanasc->ViewValue = $this->datanasc->CurrentValue;
		$this->datanasc->ViewValue = ew_FormatDateTime($this->datanasc->ViewValue, 7);
		$this->datanasc->ViewCustomAttributes = "";

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

		// cod_pessoa
		$this->cod_pessoa->LinkCustomAttributes = "";
		$this->cod_pessoa->HrefValue = "";
		$this->cod_pessoa->TooltipValue = "";

		// datacadastro
		$this->datacadastro->LinkCustomAttributes = "";
		$this->datacadastro->HrefValue = "";
		$this->datacadastro->TooltipValue = "";

		// nome
		$this->nome->LinkCustomAttributes = "";
		if (!ew_Empty($this->nome->CurrentValue)) {
			$this->nome->HrefValue = "socioslist.php?x_socio=" . ((!empty($this->nome->ViewValue)) ? $this->nome->ViewValue : $this->nome->CurrentValue) . "&z_socio=LIKE&cmd=search"; // Add prefix/suffix
			$this->nome->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->nome->HrefValue = ew_ConvertFullUrl($this->nome->HrefValue);
		} else {
			$this->nome->HrefValue = "";
		}
		$this->nome->TooltipValue = "";

		// endereco
		$this->endereco->LinkCustomAttributes = "";
		$this->endereco->HrefValue = "";
		$this->endereco->TooltipValue = "";

		// numero
		$this->numero->LinkCustomAttributes = "";
		$this->numero->HrefValue = "";
		$this->numero->TooltipValue = "";

		// complemento
		$this->complemento->LinkCustomAttributes = "";
		$this->complemento->HrefValue = "";
		$this->complemento->TooltipValue = "";

		// bairro
		$this->bairro->LinkCustomAttributes = "";
		$this->bairro->HrefValue = "";
		$this->bairro->TooltipValue = "";

		// cidade
		$this->cidade->LinkCustomAttributes = "";
		$this->cidade->HrefValue = "";
		$this->cidade->TooltipValue = "";

		// estado
		$this->estado->LinkCustomAttributes = "";
		$this->estado->HrefValue = "";
		$this->estado->TooltipValue = "";

		// CEP
		$this->CEP->LinkCustomAttributes = "";
		$this->CEP->HrefValue = "";
		$this->CEP->TooltipValue = "";

		// telefone
		$this->telefone->LinkCustomAttributes = "";
		$this->telefone->HrefValue = "";
		$this->telefone->TooltipValue = "";

		// sexo
		$this->sexo->LinkCustomAttributes = "";
		$this->sexo->HrefValue = "";
		$this->sexo->TooltipValue = "";

		// datanasc
		$this->datanasc->LinkCustomAttributes = "";
		$this->datanasc->HrefValue = "";
		$this->datanasc->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// cod_pessoa
		$this->cod_pessoa->EditAttrs["class"] = "form-control";
		$this->cod_pessoa->EditCustomAttributes = "";
		$this->cod_pessoa->EditValue = $this->cod_pessoa->CurrentValue;
		$this->cod_pessoa->ViewCustomAttributes = "";

		// datacadastro
		$this->datacadastro->EditAttrs["class"] = "form-control";
		$this->datacadastro->EditCustomAttributes = "";
		$this->datacadastro->EditValue = ew_HtmlEncode($this->datacadastro->CurrentValue);
		$this->datacadastro->PlaceHolder = ew_RemoveHtml($this->datacadastro->FldCaption());

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

		// complemento
		$this->complemento->EditAttrs["class"] = "form-control";
		$this->complemento->EditCustomAttributes = "";
		$this->complemento->EditValue = ew_HtmlEncode($this->complemento->CurrentValue);
		$this->complemento->PlaceHolder = ew_RemoveHtml($this->complemento->FldCaption());

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

		// estado
		$this->estado->EditAttrs["class"] = "form-control";
		$this->estado->EditCustomAttributes = "";

		// CEP
		$this->CEP->EditAttrs["class"] = "form-control";
		$this->CEP->EditCustomAttributes = "";
		$this->CEP->EditValue = ew_HtmlEncode($this->CEP->CurrentValue);
		$this->CEP->PlaceHolder = ew_RemoveHtml($this->CEP->FldCaption());

		// telefone
		$this->telefone->EditAttrs["class"] = "form-control";
		$this->telefone->EditCustomAttributes = "";
		$this->telefone->EditValue = ew_HtmlEncode($this->telefone->CurrentValue);
		$this->telefone->PlaceHolder = ew_RemoveHtml($this->telefone->FldCaption());

		// sexo
		$this->sexo->EditAttrs["class"] = "form-control";
		$this->sexo->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->sexo->FldTagValue(1), $this->sexo->FldTagCaption(1) <> "" ? $this->sexo->FldTagCaption(1) : $this->sexo->FldTagValue(1));
		$arwrk[] = array($this->sexo->FldTagValue(2), $this->sexo->FldTagCaption(2) <> "" ? $this->sexo->FldTagCaption(2) : $this->sexo->FldTagValue(2));
		array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
		$this->sexo->EditValue = $arwrk;

		// datanasc
		$this->datanasc->EditAttrs["class"] = "form-control";
		$this->datanasc->EditCustomAttributes = "";
		$this->datanasc->EditValue = ew_HtmlEncode($this->datanasc->CurrentValue);
		$this->datanasc->PlaceHolder = ew_RemoveHtml($this->datanasc->FldCaption());

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
					if ($this->cod_pessoa->Exportable) $Doc->ExportCaption($this->cod_pessoa);
					if ($this->datacadastro->Exportable) $Doc->ExportCaption($this->datacadastro);
					if ($this->nome->Exportable) $Doc->ExportCaption($this->nome);
					if ($this->endereco->Exportable) $Doc->ExportCaption($this->endereco);
					if ($this->numero->Exportable) $Doc->ExportCaption($this->numero);
					if ($this->complemento->Exportable) $Doc->ExportCaption($this->complemento);
					if ($this->bairro->Exportable) $Doc->ExportCaption($this->bairro);
					if ($this->cidade->Exportable) $Doc->ExportCaption($this->cidade);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
					if ($this->CEP->Exportable) $Doc->ExportCaption($this->CEP);
					if ($this->telefone->Exportable) $Doc->ExportCaption($this->telefone);
					if ($this->sexo->Exportable) $Doc->ExportCaption($this->sexo);
					if ($this->datanasc->Exportable) $Doc->ExportCaption($this->datanasc);
					if ($this->estado_civil->Exportable) $Doc->ExportCaption($this->estado_civil);
					if ($this->rg->Exportable) $Doc->ExportCaption($this->rg);
					if ($this->cpf->Exportable) $Doc->ExportCaption($this->cpf);
					if ($this->carteira_trabalho->Exportable) $Doc->ExportCaption($this->carteira_trabalho);
					if ($this->nacionalidade->Exportable) $Doc->ExportCaption($this->nacionalidade);
					if ($this->naturalidade->Exportable) $Doc->ExportCaption($this->naturalidade);
				} else {
					if ($this->cod_pessoa->Exportable) $Doc->ExportCaption($this->cod_pessoa);
					if ($this->datacadastro->Exportable) $Doc->ExportCaption($this->datacadastro);
					if ($this->nome->Exportable) $Doc->ExportCaption($this->nome);
					if ($this->endereco->Exportable) $Doc->ExportCaption($this->endereco);
					if ($this->numero->Exportable) $Doc->ExportCaption($this->numero);
					if ($this->complemento->Exportable) $Doc->ExportCaption($this->complemento);
					if ($this->bairro->Exportable) $Doc->ExportCaption($this->bairro);
					if ($this->cidade->Exportable) $Doc->ExportCaption($this->cidade);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
					if ($this->CEP->Exportable) $Doc->ExportCaption($this->CEP);
					if ($this->telefone->Exportable) $Doc->ExportCaption($this->telefone);
					if ($this->sexo->Exportable) $Doc->ExportCaption($this->sexo);
					if ($this->datanasc->Exportable) $Doc->ExportCaption($this->datanasc);
					if ($this->estado_civil->Exportable) $Doc->ExportCaption($this->estado_civil);
					if ($this->rg->Exportable) $Doc->ExportCaption($this->rg);
					if ($this->cpf->Exportable) $Doc->ExportCaption($this->cpf);
					if ($this->carteira_trabalho->Exportable) $Doc->ExportCaption($this->carteira_trabalho);
					if ($this->nacionalidade->Exportable) $Doc->ExportCaption($this->nacionalidade);
					if ($this->naturalidade->Exportable) $Doc->ExportCaption($this->naturalidade);
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
						if ($this->cod_pessoa->Exportable) $Doc->ExportField($this->cod_pessoa);
						if ($this->datacadastro->Exportable) $Doc->ExportField($this->datacadastro);
						if ($this->nome->Exportable) $Doc->ExportField($this->nome);
						if ($this->endereco->Exportable) $Doc->ExportField($this->endereco);
						if ($this->numero->Exportable) $Doc->ExportField($this->numero);
						if ($this->complemento->Exportable) $Doc->ExportField($this->complemento);
						if ($this->bairro->Exportable) $Doc->ExportField($this->bairro);
						if ($this->cidade->Exportable) $Doc->ExportField($this->cidade);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
						if ($this->CEP->Exportable) $Doc->ExportField($this->CEP);
						if ($this->telefone->Exportable) $Doc->ExportField($this->telefone);
						if ($this->sexo->Exportable) $Doc->ExportField($this->sexo);
						if ($this->datanasc->Exportable) $Doc->ExportField($this->datanasc);
						if ($this->estado_civil->Exportable) $Doc->ExportField($this->estado_civil);
						if ($this->rg->Exportable) $Doc->ExportField($this->rg);
						if ($this->cpf->Exportable) $Doc->ExportField($this->cpf);
						if ($this->carteira_trabalho->Exportable) $Doc->ExportField($this->carteira_trabalho);
						if ($this->nacionalidade->Exportable) $Doc->ExportField($this->nacionalidade);
						if ($this->naturalidade->Exportable) $Doc->ExportField($this->naturalidade);
					} else {
						if ($this->cod_pessoa->Exportable) $Doc->ExportField($this->cod_pessoa);
						if ($this->datacadastro->Exportable) $Doc->ExportField($this->datacadastro);
						if ($this->nome->Exportable) $Doc->ExportField($this->nome);
						if ($this->endereco->Exportable) $Doc->ExportField($this->endereco);
						if ($this->numero->Exportable) $Doc->ExportField($this->numero);
						if ($this->complemento->Exportable) $Doc->ExportField($this->complemento);
						if ($this->bairro->Exportable) $Doc->ExportField($this->bairro);
						if ($this->cidade->Exportable) $Doc->ExportField($this->cidade);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
						if ($this->CEP->Exportable) $Doc->ExportField($this->CEP);
						if ($this->telefone->Exportable) $Doc->ExportField($this->telefone);
						if ($this->sexo->Exportable) $Doc->ExportField($this->sexo);
						if ($this->datanasc->Exportable) $Doc->ExportField($this->datanasc);
						if ($this->estado_civil->Exportable) $Doc->ExportField($this->estado_civil);
						if ($this->rg->Exportable) $Doc->ExportField($this->rg);
						if ($this->cpf->Exportable) $Doc->ExportField($this->cpf);
						if ($this->carteira_trabalho->Exportable) $Doc->ExportField($this->carteira_trabalho);
						if ($this->nacionalidade->Exportable) $Doc->ExportField($this->nacionalidade);
						if ($this->naturalidade->Exportable) $Doc->ExportField($this->naturalidade);
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
