<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php

// Global variable for table object
$rel_ficha_cadastral_ = NULL;

//
// Table class for rel_ficha_cadastral_
//
class crel_ficha_cadastral_ extends cTableBase {
	var $socio;
	var $datacadastro;
	var $nome;
	var $nome_empresa;
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
	var $cod_socio;
	var $funcao;
	var $dependentes;
	var $cod_pessoa;
	var $A09DESCRICAO;
	var $A04imagem;
	var $A01URL;
	var $A21EMPRESA;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'rel_ficha_cadastral_';
		$this->TableName = 'rel_ficha_cadastral_';
		$this->TableType = 'REPORT';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// socio
		$this->socio = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_socio', 'socio', '`socio`', '`socio`', 3, -1, FALSE, '`EV__socio`', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->socio->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['socio'] = &$this->socio;

		// datacadastro
		$this->datacadastro = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_datacadastro', 'datacadastro', '`datacadastro`', '`datacadastro`', 200, -1, FALSE, '`datacadastro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->datacadastro->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['datacadastro'] = &$this->datacadastro;

		// nome
		$this->nome = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_nome', 'nome', '`nome`', '`nome`', 200, -1, FALSE, '`nome`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nome'] = &$this->nome;

		// nome_empresa
		$this->nome_empresa = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_nome_empresa', 'nome_empresa', '`nome_empresa`', '`nome_empresa`', 200, -1, FALSE, '`nome_empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nome_empresa'] = &$this->nome_empresa;

		// endereco
		$this->endereco = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_endereco', 'endereco', '`endereco`', '`endereco`', 200, -1, FALSE, '`endereco`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['endereco'] = &$this->endereco;

		// numero
		$this->numero = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_numero', 'numero', '`numero`', '`numero`', 3, -1, FALSE, '`numero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->numero->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['numero'] = &$this->numero;

		// complemento
		$this->complemento = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_complemento', 'complemento', '`complemento`', '`complemento`', 200, -1, FALSE, '`complemento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['complemento'] = &$this->complemento;

		// bairro
		$this->bairro = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_bairro', 'bairro', '`bairro`', '`bairro`', 200, -1, FALSE, '`bairro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['bairro'] = &$this->bairro;

		// cidade
		$this->cidade = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_cidade', 'cidade', '`cidade`', '`cidade`', 200, -1, FALSE, '`cidade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cidade'] = &$this->cidade;

		// estado
		$this->estado = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_estado', 'estado', '`estado`', '`estado`', 200, -1, FALSE, '`estado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['estado'] = &$this->estado;

		// CEP
		$this->CEP = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_CEP', 'CEP', '`CEP`', '`CEP`', 200, -1, FALSE, '`CEP`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CEP'] = &$this->CEP;

		// telefone
		$this->telefone = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_telefone', 'telefone', '`telefone`', '`telefone`', 200, -1, FALSE, '`telefone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->telefone->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['telefone'] = &$this->telefone;

		// sexo
		$this->sexo = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_sexo', 'sexo', '`sexo`', '`sexo`', 200, -1, FALSE, '`sexo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['sexo'] = &$this->sexo;

		// datanasc
		$this->datanasc = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_datanasc', 'datanasc', '`datanasc`', '`datanasc`', 200, 7, FALSE, '`datanasc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->datanasc->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['datanasc'] = &$this->datanasc;

		// estado_civil
		$this->estado_civil = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_estado_civil', 'estado_civil', '`estado_civil`', '`estado_civil`', 3, -1, FALSE, '`estado_civil`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->estado_civil->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['estado_civil'] = &$this->estado_civil;

		// rg
		$this->rg = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_rg', 'rg', '`rg`', '`rg`', 200, -1, FALSE, '`rg`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['rg'] = &$this->rg;

		// cpf
		$this->cpf = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_cpf', 'cpf', '`cpf`', '`cpf`', 200, -1, FALSE, '`cpf`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cpf->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cpf'] = &$this->cpf;

		// carteira_trabalho
		$this->carteira_trabalho = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_carteira_trabalho', 'carteira_trabalho', '`carteira_trabalho`', '`carteira_trabalho`', 200, -1, FALSE, '`carteira_trabalho`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['carteira_trabalho'] = &$this->carteira_trabalho;

		// nacionalidade
		$this->nacionalidade = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_nacionalidade', 'nacionalidade', '`nacionalidade`', '`nacionalidade`', 200, -1, FALSE, '`nacionalidade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nacionalidade'] = &$this->nacionalidade;

		// naturalidade
		$this->naturalidade = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_naturalidade', 'naturalidade', '`naturalidade`', '`naturalidade`', 200, -1, FALSE, '`naturalidade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['naturalidade'] = &$this->naturalidade;

		// cod_socio
		$this->cod_socio = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_cod_socio', 'cod_socio', '`cod_socio`', '`cod_socio`', 3, -1, FALSE, '`cod_socio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cod_socio->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cod_socio'] = &$this->cod_socio;

		// funcao
		$this->funcao = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_funcao', 'funcao', '`funcao`', '`funcao`', 200, -1, FALSE, '`funcao`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['funcao'] = &$this->funcao;

		// dependentes
		$this->dependentes = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_dependentes', 'dependentes', '`dependentes`', '`dependentes`', 201, -1, FALSE, '`dependentes`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dependentes'] = &$this->dependentes;

		// cod_pessoa
		$this->cod_pessoa = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_cod_pessoa', 'cod_pessoa', '`cod_pessoa`', '`cod_pessoa`', 3, -1, FALSE, '`cod_pessoa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cod_pessoa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cod_pessoa'] = &$this->cod_pessoa;

		// A09DESCRICAO
		$this->A09DESCRICAO = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_A09DESCRICAO', 'A09DESCRICAO', '`A09DESCRICAO`', '`A09DESCRICAO`', 201, -1, FALSE, '`A09DESCRICAO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A09DESCRICAO'] = &$this->A09DESCRICAO;

		// A04imagem
		$this->A04imagem = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_A04imagem', 'A04imagem', '`A04imagem`', '`A04imagem`', 200, -1, FALSE, '`A04imagem`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A04imagem'] = &$this->A04imagem;

		// A01URL
		$this->A01URL = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_A01URL', 'A01URL', '`A01URL`', '`A01URL`', 200, -1, FALSE, '`A01URL`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A01URL'] = &$this->A01URL;

		// A21EMPRESA
		$this->A21EMPRESA = new cField('rel_ficha_cadastral_', 'rel_ficha_cadastral_', 'x_A21EMPRESA', 'A21EMPRESA', '`A21EMPRESA`', '`A21EMPRESA`', 200, -1, FALSE, '`A21EMPRESA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A21EMPRESA'] = &$this->A21EMPRESA;
	}

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "socios") {
			if ($this->cod_pessoa->getSessionValue() <> "")
				$sMasterFilter .= "`socio`=" . ew_QuotedValue($this->cod_pessoa->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "socios") {
			if ($this->cod_pessoa->getSessionValue() <> "")
				$sDetailFilter .= "`cod_pessoa`=" . ew_QuotedValue($this->cod_pessoa->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_socios() {
		return "`socio`=@socio@";
	}

	// Detail filter
	function SqlDetailFilter_socios() {
		return "`cod_pessoa`=@cod_pessoa@";
	}

	// Report group level SQL
	var $_SqlGroupSelect = "";

	function getSqlGroupSelect() { // Select
		return ($this->_SqlGroupSelect <> "") ? $this->_SqlGroupSelect : "SELECT DISTINCT `A04imagem`,`A01URL`,`A21EMPRESA` FROM `view_ficha_funcionario`";
	}

	function SqlGroupSelect() { // For backward compatibility
    	return $this->getSqlGroupSelect();
	}

	function setSqlGroupSelect($v) {
    	$this->_SqlGroupSelect = $v;
	}
	var $_SqlGroupWhere = "";

	function getSqlGroupWhere() { // Where
		return ($this->_SqlGroupWhere <> "") ? $this->_SqlGroupWhere : "";
	}

	function SqlGroupWhere() { // For backward compatibility
    	return $this->getSqlGroupWhere();
	}

	function setSqlGroupWhere($v) {
    	$this->_SqlGroupWhere = $v;
	}
	var $_SqlGroupGroupBy = "";

	function getSqlGroupGroupBy() { // Group By
		return ($this->_SqlGroupGroupBy <> "") ? $this->_SqlGroupGroupBy : "";
	}

	function SqlGroupGroupBy() { // For backward compatibility
    	return $this->getSqlGroupGroupBy();
	}

	function setSqlGroupGroupBy($v) {
    	$this->_SqlGroupGroupBy = $v;
	}
	var $_SqlGroupHaving = "";

	function getSqlGroupHaving() { // Having
		return ($this->_SqlGroupHaving <> "") ? $this->_SqlGroupHaving : "";
	}

	function SqlGroupHaving() { // For backward compatibility
    	return $this->getSqlGroupHaving();
	}

	function setSqlGroupHaving($v) {
    	$this->_SqlGroupHaving = $v;
	}
	var $_SqlGroupOrderBy = "";

	function getSqlGroupOrderBy() { // Order By
		return ($this->_SqlGroupOrderBy <> "") ? $this->_SqlGroupOrderBy : "`A04imagem` ASC,`A01URL` ASC,`A21EMPRESA` ASC";
	}

	function SqlGroupOrderBy() { // For backward compatibility
    	return $this->getSqlGroupOrderBy();
	}

	function setSqlGroupOrderBy($v) {
    	$this->_SqlGroupOrderBy = $v;
	}

	// Report detail level SQL
	var $_SqlDetailSelect = "";

	function getSqlDetailSelect() { // Select
		return ($this->_SqlDetailSelect <> "") ? $this->_SqlDetailSelect : "SELECT * FROM `view_ficha_funcionario`";
	}

	function SqlDetailSelect() { // For backward compatibility
    	return $this->getSqlDetailSelect();
	}

	function setSqlDetailSelect($v) {
    	$this->_SqlDetailSelect = $v;
	}
	var $_SqlDetailWhere = "";

	function getSqlDetailWhere() { // Where
		return ($this->_SqlDetailWhere <> "") ? $this->_SqlDetailWhere : "";
	}

	function SqlDetailWhere() { // For backward compatibility
    	return $this->getSqlDetailWhere();
	}

	function setSqlDetailWhere($v) {
    	$this->_SqlDetailWhere = $v;
	}
	var $_SqlDetailGroupBy = "";

	function getSqlDetailGroupBy() { // Group By
		return ($this->_SqlDetailGroupBy <> "") ? $this->_SqlDetailGroupBy : "";
	}

	function SqlDetailGroupBy() { // For backward compatibility
    	return $this->getSqlDetailGroupBy();
	}

	function setSqlDetailGroupBy($v) {
    	$this->_SqlDetailGroupBy = $v;
	}
	var $_SqlDetailHaving = "";

	function getSqlDetailHaving() { // Having
		return ($this->_SqlDetailHaving <> "") ? $this->_SqlDetailHaving : "";
	}

	function SqlDetailHaving() { // For backward compatibility
    	return $this->getSqlDetailHaving();
	}

	function setSqlDetailHaving($v) {
    	$this->_SqlDetailHaving = $v;
	}
	var $_SqlDetailOrderBy = "";

	function getSqlDetailOrderBy() { // Order By
		return ($this->_SqlDetailOrderBy <> "") ? $this->_SqlDetailOrderBy : "";
	}

	function SqlDetailOrderBy() { // For backward compatibility
    	return $this->getSqlDetailOrderBy();
	}

	function setSqlDetailOrderBy($v) {
    	$this->_SqlDetailOrderBy = $v;
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

	// Report group SQL
	function GroupSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->getSqlGroupSelect(), $this->getSqlGroupWhere(),
			 $this->getSqlGroupGroupBy(), $this->getSqlGroupHaving(),
			 $this->getSqlGroupOrderBy(), $sFilter, $sSort);
	}

	// Report detail SQL
	function DetailSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->getSqlDetailSelect(), $this->getSqlDetailWhere(),
			$this->getSqlDetailGroupBy(), $this->getSqlDetailHaving(),
			$this->getSqlDetailOrderBy(), $sFilter, $sSort);
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
			return "rel_ficha_cadastral_report.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "rel_ficha_cadastral_report.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("", $this->UrlParm($parm));
		else
			return $this->KeyUrl("", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "?" . $this->UrlParm($parm);
		else
			return "";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("", $this->UrlParm());
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
<?php include_once "permissoesinfo.php" ?>
<?php include_once "sociosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$rel_ficha_cadastral__report = NULL; // Initialize page object first

class crel_ficha_cadastral__report extends crel_ficha_cadastral_ {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'rel_ficha_cadastral_';

	// Page object name
	var $PageObjName = 'rel_ficha_cadastral__report';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		return TRUE;
	}
	var $Token = "";
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (rel_ficha_cadastral_)
		if (!isset($GLOBALS["rel_ficha_cadastral_"]) || get_class($GLOBALS["rel_ficha_cadastral_"]) == "crel_ficha_cadastral_") {
			$GLOBALS["rel_ficha_cadastral_"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["rel_ficha_cadastral_"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Table object (permissoes)
		if (!isset($GLOBALS['permissoes'])) $GLOBALS['permissoes'] = new cpermissoes();

		// Table object (socios)
		if (!isset($GLOBALS['socios'])) $GLOBALS['socios'] = new csocios();

		// User table object (permissoes)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cpermissoes();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'report', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'rel_ficha_cadastral_', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn, $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT_REPORT;
		if ($this->Export <> "" && array_key_exists($this->Export, $EW_EXPORT_REPORT)) {
			$sContent = ob_get_contents();
			$fn = $EW_EXPORT_REPORT[$this->Export];
			$this->$fn($sContent);
		}
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $ExportOptions; // Export options
	var $RecCnt = 0;
	var $ReportSql = "";
	var $ReportFilter = "";
	var $DefaultFilter = "";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $MasterRecordExists;
	var $Command;
	var $DtlRecordCount;
	var $ReportGroups;
	var $ReportCounts;
	var $LevelBreak;
	var $ReportTotals;
	var $ReportMaxs;
	var $ReportMins;
	var $Recordset;
	var $DetailRecordset;
	var $RecordExists;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$this->ReportGroups = &ew_InitArray(4, NULL);
		$this->ReportCounts = &ew_InitArray(4, 0);
		$this->LevelBreak = &ew_InitArray(4, FALSE);
		$this->ReportTotals = &ew_Init2DArray(4, 26, 0);
		$this->ReportMaxs = &ew_Init2DArray(4, 26, 0);
		$this->ReportMins = &ew_Init2DArray(4, 26, 0);

		// Get reset command
		if (@$_GET["cmd"] <> "") {
			$this->Command = strtolower($_GET["cmd"]);

			// Reset master/detail
			if ($this->Command == "resetall") {
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->cod_pessoa->setSessionValue("");
			}
		}

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Build master record SQL
		global $socios;
		if ($this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "socios") {
			$rsmaster = $socios->LoadRs($this->GetMasterFilter());
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
				$this->Page_Terminate("socioslist.php"); // Return to master page
			} else {
				$socios->LoadListRowValues($rsmaster);
				$socios->RowType = EW_ROWTYPE_MASTER; // Master row
				$socios->RenderListRow();
				$rsmaster->Close();
			}
		}
	}

	// Check level break
	function ChkLvlBreak() {
		$this->LevelBreak[1] = FALSE;
		$this->LevelBreak[2] = FALSE;
		$this->LevelBreak[3] = FALSE;
		if ($this->RecCnt == 0) { // Start Or End of Recordset
			$this->LevelBreak[1] = TRUE;
			$this->LevelBreak[2] = TRUE;
			$this->LevelBreak[3] = TRUE;
		} else {
			if (!ew_CompareValue($this->A04imagem->CurrentValue, $this->ReportGroups[0])) {
				$this->LevelBreak[1] = TRUE;
				$this->LevelBreak[2] = TRUE;
				$this->LevelBreak[3] = TRUE;
			}
			if (!ew_CompareValue($this->A01URL->CurrentValue, $this->ReportGroups[1])) {
				$this->LevelBreak[2] = TRUE;
				$this->LevelBreak[3] = TRUE;
			}
			if (!ew_CompareValue($this->A21EMPRESA->CurrentValue, $this->ReportGroups[2])) {
				$this->LevelBreak[3] = TRUE;
			}
		}
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// socio
		// datacadastro
		// nome
		// nome_empresa
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
		// cod_socio
		// funcao
		// dependentes
		// cod_pessoa
		// A09DESCRICAO
		// A04imagem
		// A01URL
		// A21EMPRESA

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

			// datacadastro
			$this->datacadastro->ViewValue = $this->datacadastro->CurrentValue;
			$this->datacadastro->ViewCustomAttributes = "";

			// nome
			$this->nome->ViewValue = $this->nome->CurrentValue;
			$this->nome->CssStyle = "font-weight: bold;";
			$this->nome->ViewCustomAttributes = "";

			// nome_empresa
			$this->nome_empresa->ViewValue = $this->nome_empresa->CurrentValue;
			$this->nome_empresa->ViewCustomAttributes = "";

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

			// cod_socio
			$this->cod_socio->ViewValue = $this->cod_socio->CurrentValue;
			$this->cod_socio->ViewCustomAttributes = "";

			// funcao
			$this->funcao->ViewValue = $this->funcao->CurrentValue;
			$this->funcao->ViewCustomAttributes = "";

			// dependentes
			$this->dependentes->ViewValue = $this->dependentes->CurrentValue;
			$this->dependentes->ViewCustomAttributes = "";

			// cod_pessoa
			$this->cod_pessoa->ViewValue = $this->cod_pessoa->CurrentValue;
			$this->cod_pessoa->ViewCustomAttributes = "";

			// A09DESCRICAO
			$this->A09DESCRICAO->ViewValue = $this->A09DESCRICAO->CurrentValue;
			$this->A09DESCRICAO->ViewCustomAttributes = "";

			// A04imagem
			$this->A04imagem->ViewValue = $this->A04imagem->CurrentValue;
			$this->A04imagem->ViewCustomAttributes = "";

			// A01URL
			$this->A01URL->ViewValue = $this->A01URL->CurrentValue;
			$this->A01URL->ViewCustomAttributes = "";

			// A21EMPRESA
			$this->A21EMPRESA->ViewValue = $this->A21EMPRESA->CurrentValue;
			$this->A21EMPRESA->ViewCustomAttributes = "";

			// socio
			$this->socio->LinkCustomAttributes = "";
			$this->socio->HrefValue = "";
			$this->socio->TooltipValue = "";

			// datacadastro
			$this->datacadastro->LinkCustomAttributes = "";
			$this->datacadastro->HrefValue = "";
			$this->datacadastro->TooltipValue = "";

			// nome
			$this->nome->LinkCustomAttributes = "";
			$this->nome->HrefValue = "";
			$this->nome->TooltipValue = "";

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

			// cod_socio
			$this->cod_socio->LinkCustomAttributes = "";
			$this->cod_socio->HrefValue = "";
			$this->cod_socio->TooltipValue = "";

			// funcao
			$this->funcao->LinkCustomAttributes = "";
			$this->funcao->HrefValue = "";
			$this->funcao->TooltipValue = "";

			// dependentes
			$this->dependentes->LinkCustomAttributes = "";
			$this->dependentes->HrefValue = "";
			$this->dependentes->TooltipValue = "";

			// cod_pessoa
			$this->cod_pessoa->LinkCustomAttributes = "";
			$this->cod_pessoa->HrefValue = "";
			$this->cod_pessoa->TooltipValue = "";

			// A09DESCRICAO
			$this->A09DESCRICAO->LinkCustomAttributes = "";
			$this->A09DESCRICAO->HrefValue = "";
			$this->A09DESCRICAO->TooltipValue = "";

			// A04imagem
			$this->A04imagem->LinkCustomAttributes = "";
			$this->A04imagem->HrefValue = "";
			$this->A04imagem->TooltipValue = "";

			// A01URL
			$this->A01URL->LinkCustomAttributes = "";
			$this->A01URL->HrefValue = "";
			$this->A01URL->TooltipValue = "";

			// A21EMPRESA
			$this->A21EMPRESA->LinkCustomAttributes = "";
			$this->A21EMPRESA->HrefValue = "";
			$this->A21EMPRESA->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = FALSE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "socios") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_socio"] <> "") {
					$GLOBALS["socios"]->socio->setQueryStringValue($_GET["fk_socio"]);
					$this->cod_pessoa->setQueryStringValue($GLOBALS["socios"]->socio->QueryStringValue);
					$this->cod_pessoa->setSessionValue($this->cod_pessoa->QueryStringValue);
					if (!is_numeric($GLOBALS["socios"]->socio->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "socios") {
				if ($this->cod_pessoa->QueryStringValue == "") $this->cod_pessoa->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("report", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Export report to HTML
	function ExportReportHtml($html) {

		//global $gsExportFile;
		//header('Content-Type: text/html' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		//header('Content-Disposition: attachment; filename=' . $gsExportFile . '.html');
		//echo $html;

	}

	// Export report to WORD
	function ExportReportWord($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-word' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.doc');
		echo $html;
	}

	// Export report to EXCEL
	function ExportReportExcel($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-excel' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xls');
		echo $html;
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($rel_ficha_cadastral__report)) $rel_ficha_cadastral__report = new crel_ficha_cadastral__report();

// Page init
$rel_ficha_cadastral__report->Page_Init();

// Page main
$rel_ficha_cadastral__report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$rel_ficha_cadastral__report->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($rel_ficha_cadastral_->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<div class="ewToolbar">
<?php if ($rel_ficha_cadastral_->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($rel_ficha_cadastral_->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
$rel_ficha_cadastral__report->DefaultFilter = "";
$rel_ficha_cadastral__report->ReportFilter = $rel_ficha_cadastral__report->DefaultFilter;
if (!$Security->CanReport()) {
	if ($rel_ficha_cadastral__report->ReportFilter <> "") $rel_ficha_cadastral__report->ReportFilter .= " AND ";
	$rel_ficha_cadastral__report->ReportFilter .= "(0=1)";
}
if ($rel_ficha_cadastral__report->DbDetailFilter <> "") {
	if ($rel_ficha_cadastral__report->ReportFilter <> "") $rel_ficha_cadastral__report->ReportFilter .= " AND ";
	$rel_ficha_cadastral__report->ReportFilter .= "(" . $rel_ficha_cadastral__report->DbDetailFilter . ")";
}

// Set up filter and load Group level sql
$rel_ficha_cadastral_->CurrentFilter = $rel_ficha_cadastral__report->ReportFilter;
$rel_ficha_cadastral__report->ReportSql = $rel_ficha_cadastral_->GroupSQL();

// Load recordset
$rel_ficha_cadastral__report->Recordset = $conn->Execute($rel_ficha_cadastral__report->ReportSql);
$rel_ficha_cadastral__report->RecordExists = !$rel_ficha_cadastral__report->Recordset->EOF;
?>
<?php if ($rel_ficha_cadastral_->Export == "") { ?>
<?php if ($rel_ficha_cadastral__report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $rel_ficha_cadastral__report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $rel_ficha_cadastral__report->ShowPageHeader(); ?>
<table align="center" border="0" class="ewReportTable">
<?php

// Get First Row
if ($rel_ficha_cadastral__report->RecordExists) {
	$rel_ficha_cadastral_->A04imagem->setDbValue($rel_ficha_cadastral__report->Recordset->fields('A04imagem'));
	$rel_ficha_cadastral__report->ReportGroups[0] = $rel_ficha_cadastral_->A04imagem->DbValue;
	$rel_ficha_cadastral_->A01URL->setDbValue($rel_ficha_cadastral__report->Recordset->fields('A01URL'));
	$rel_ficha_cadastral__report->ReportGroups[1] = $rel_ficha_cadastral_->A01URL->DbValue;
	$rel_ficha_cadastral_->A21EMPRESA->setDbValue($rel_ficha_cadastral__report->Recordset->fields('A21EMPRESA'));
	$rel_ficha_cadastral__report->ReportGroups[2] = $rel_ficha_cadastral_->A21EMPRESA->DbValue;
}
$rel_ficha_cadastral__report->RecCnt = 0;
$rel_ficha_cadastral__report->ReportCounts[0] = 0;
$rel_ficha_cadastral__report->ChkLvlBreak();
while (!$rel_ficha_cadastral__report->Recordset->EOF) {

	// Render for view
	$rel_ficha_cadastral_->RowType = EW_ROWTYPE_VIEW;
	$rel_ficha_cadastral_->ResetAttrs();
	$rel_ficha_cadastral__report->RenderRow();

	// Show group headers
	if ($rel_ficha_cadastral__report->LevelBreak[1]) { // Reset counter and aggregation
?>
	<tr>
	<td colspan=25 class="ewGroupName">
	<p><img alt="" src="<?php echo strtolower($rel_ficha_cadastral_->A01URL->ViewValue) ?><?php echo strtolower($rel_ficha_cadastral_->A04imagem->ViewValue) ?>" style="float:left; height:71px; width:80px" /><br /><?php echo $rel_ficha_cadastral_->A21EMPRESA->ViewValue ?></p>
</td></tr>
	<tr>
	<td colspan=25 class="ewGroupName" style="text-align: center;">
	<hr />
	- Ficha cadastral -
	<hr />
	</td></tr>
<?php
	}
	if ($rel_ficha_cadastral__report->LevelBreak[2]) { // Reset counter and aggregation
?>
<?php
	}
	if ($rel_ficha_cadastral__report->LevelBreak[3]) { // Reset counter and aggregation
?>
<?php
	}

	// Get detail records
	$rel_ficha_cadastral__report->ReportFilter = $rel_ficha_cadastral__report->DefaultFilter;
	if ($rel_ficha_cadastral__report->ReportFilter <> "") $rel_ficha_cadastral__report->ReportFilter .= " AND ";
	if (is_null($rel_ficha_cadastral_->A04imagem->CurrentValue)) {
		$rel_ficha_cadastral__report->ReportFilter .= "(`A04imagem` IS NULL)";
	} else {
		$rel_ficha_cadastral__report->ReportFilter .= "(`A04imagem` = '" . ew_AdjustSql($rel_ficha_cadastral_->A04imagem->CurrentValue) . "')";
	}
	if ($rel_ficha_cadastral__report->ReportFilter <> "") $rel_ficha_cadastral__report->ReportFilter .= " AND ";
	if (is_null($rel_ficha_cadastral_->A01URL->CurrentValue)) {
		$rel_ficha_cadastral__report->ReportFilter .= "(`A01URL` IS NULL)";
	} else {
		$rel_ficha_cadastral__report->ReportFilter .= "(`A01URL` = '" . ew_AdjustSql($rel_ficha_cadastral_->A01URL->CurrentValue) . "')";
	}
	if ($rel_ficha_cadastral__report->ReportFilter <> "") $rel_ficha_cadastral__report->ReportFilter .= " AND ";
	if (is_null($rel_ficha_cadastral_->A21EMPRESA->CurrentValue)) {
		$rel_ficha_cadastral__report->ReportFilter .= "(`A21EMPRESA` IS NULL)";
	} else {
		$rel_ficha_cadastral__report->ReportFilter .= "(`A21EMPRESA` = '" . ew_AdjustSql($rel_ficha_cadastral_->A21EMPRESA->CurrentValue) . "')";
	}
	if ($rel_ficha_cadastral__report->DbDetailFilter <> "") {
		if ($rel_ficha_cadastral__report->ReportFilter <> "")
			$rel_ficha_cadastral__report->ReportFilter .= " AND ";
		$rel_ficha_cadastral__report->ReportFilter .= "(" . $rel_ficha_cadastral__report->DbDetailFilter . ")";
	}
	if (!$Security->CanReport()) {
		if ($sFilter <> "") $sFilter .= " AND ";
		$sFilter .= "(0=1)";
	}

	// Set up detail SQL
	$rel_ficha_cadastral_->CurrentFilter = $rel_ficha_cadastral__report->ReportFilter;
	$rel_ficha_cadastral__report->ReportSql = $rel_ficha_cadastral_->DetailSQL();

	// Load detail records
	$rel_ficha_cadastral__report->DetailRecordset = $conn->Execute($rel_ficha_cadastral__report->ReportSql);
	$rel_ficha_cadastral__report->DtlRecordCount = $rel_ficha_cadastral__report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$rel_ficha_cadastral__report->DetailRecordset->EOF) {
		$rel_ficha_cadastral__report->RecCnt++;
	}
	if ($rel_ficha_cadastral__report->RecCnt == 1) {
		$rel_ficha_cadastral__report->ReportCounts[0] = 0;
	}
	for ($i = 1; $i <= 3; $i++) {
		if ($rel_ficha_cadastral__report->LevelBreak[$i]) { // Reset counter and aggregation
			$rel_ficha_cadastral__report->ReportCounts[$i] = 0;
		}
	}
	$rel_ficha_cadastral__report->ReportCounts[0] += $rel_ficha_cadastral__report->DtlRecordCount;
	$rel_ficha_cadastral__report->ReportCounts[1] += $rel_ficha_cadastral__report->DtlRecordCount;
	$rel_ficha_cadastral__report->ReportCounts[2] += $rel_ficha_cadastral__report->DtlRecordCount;
	$rel_ficha_cadastral__report->ReportCounts[3] += $rel_ficha_cadastral__report->DtlRecordCount;
	if ($rel_ficha_cadastral__report->RecordExists) {
?>
<?php
	}
	while (!$rel_ficha_cadastral__report->DetailRecordset->EOF) {
		$rel_ficha_cadastral_->socio->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('socio'));
		$rel_ficha_cadastral_->datacadastro->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('datacadastro'));
		$rel_ficha_cadastral_->nome->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('nome'));
		$rel_ficha_cadastral_->nome_empresa->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('nome_empresa'));
		$rel_ficha_cadastral_->endereco->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('endereco'));
		$rel_ficha_cadastral_->numero->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('numero'));
		$rel_ficha_cadastral_->complemento->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('complemento'));
		$rel_ficha_cadastral_->bairro->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('bairro'));
		$rel_ficha_cadastral_->cidade->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('cidade'));
		$rel_ficha_cadastral_->estado->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('estado'));
		$rel_ficha_cadastral_->CEP->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('CEP'));
		$rel_ficha_cadastral_->telefone->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('telefone'));
		$rel_ficha_cadastral_->sexo->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('sexo'));
		$rel_ficha_cadastral_->datanasc->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('datanasc'));
		$rel_ficha_cadastral_->estado_civil->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('estado_civil'));
		$rel_ficha_cadastral_->rg->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('rg'));
		$rel_ficha_cadastral_->cpf->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('cpf'));
		$rel_ficha_cadastral_->carteira_trabalho->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('carteira_trabalho'));
		$rel_ficha_cadastral_->nacionalidade->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('nacionalidade'));
		$rel_ficha_cadastral_->naturalidade->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('naturalidade'));
		$rel_ficha_cadastral_->cod_socio->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('cod_socio'));
		$rel_ficha_cadastral_->funcao->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('funcao'));
		$rel_ficha_cadastral_->dependentes->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('dependentes'));
		$rel_ficha_cadastral_->cod_pessoa->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('cod_pessoa'));
		$rel_ficha_cadastral_->A09DESCRICAO->setDbValue($rel_ficha_cadastral__report->DetailRecordset->fields('A09DESCRICAO'));

		// Render for view
		$rel_ficha_cadastral_->RowType = EW_ROWTYPE_VIEW;
		$rel_ficha_cadastral_->ResetAttrs();
		$rel_ficha_cadastral__report->RenderRow();
?>
	<tr>
	<td>
	
	<table border="0" cellpadding="1" cellspacing="1" style="width:800px">
	<tbody>
		<tr>
			<td><strong><?php echo $rel_ficha_cadastral_->datacadastro->FldCaption() ?>:</strong></td>
			<td rowspan="1"><?php echo $rel_ficha_cadastral_->datacadastro->ViewValue ?></td>
			<td rowspan="1"><strong>Matrícula</strong></td>
			<td rowspan="1">#<?php echo $rel_ficha_cadastral_->cod_socio->ViewValue ?>#</td>			
		</tr>
		<tr>
			<td><strong><?php echo $rel_ficha_cadastral_->nome->FldCaption() ?>:</strong></td>
			<td><?php echo $rel_ficha_cadastral_->nome->ViewValue ?></td>
			<td><strong><?php echo $rel_ficha_cadastral_->estado_civil->FldCaption() ?></strong></td>
			<td><?php echo $rel_ficha_cadastral_->estado_civil->ViewValue ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $rel_ficha_cadastral_->endereco->FldCaption() ?></strong></td>
			<td><?php echo $rel_ficha_cadastral_->endereco->ViewValue ?>, <?php echo $rel_ficha_cadastral_->numero->ViewValue ?></td>
			<td><strong><?php echo $rel_ficha_cadastral_->bairro->FldCaption() ?></strong></td>
			<td><?php echo $rel_ficha_cadastral_->bairro->ViewValue ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $rel_ficha_cadastral_->cidade->FldCaption() ?>/<?php echo $rel_ficha_cadastral_->estado->FldCaption() ?></strong></td>
			<td><?php echo $rel_ficha_cadastral_->cidade->ViewValue ?>/<?php echo $rel_ficha_cadastral_->estado->ViewValue ?></td>
			<td><strong><?php echo $rel_ficha_cadastral_->sexo->FldCaption() ?></strong></td>
			<td><?php echo $rel_ficha_cadastral_->sexo->ViewValue ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $rel_ficha_cadastral_->rg->FldCaption() ?></strong></td>
			<td><?php echo $rel_ficha_cadastral_->rg->ViewValue ?></td>
			<td><strong><?php echo $rel_ficha_cadastral_->cpf->FldCaption() ?></strong></td>
			<td><?php echo $rel_ficha_cadastral_->cpf->ViewValue ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $rel_ficha_cadastral_->carteira_trabalho->FldCaption() ?></strong></td>
			<td><?php echo $rel_ficha_cadastral_->carteira_trabalho->ViewValue ?></td>
			<td><strong><?php echo $rel_ficha_cadastral_->datanasc->FldCaption() ?><strong></td>
			<td><?php echo $rel_ficha_cadastral_->datanasc->ViewValue ?><td>
		</tr>
		<tr>
			<td><strong><?php echo $rel_ficha_cadastral_->naturalidade->FldCaption() ?></strong></td>
			<td><?php echo $rel_ficha_cadastral_->naturalidade->ViewValue ?></td>
			<td><strong><?php echo $rel_ficha_cadastral_->nacionalidade->FldCaption() ?></strong></td>
			<td><?php echo $rel_ficha_cadastral_->nacionalidade->ViewValue ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $rel_ficha_cadastral_->funcao->FldCaption() ?></strong></td>
			<td><?php echo $rel_ficha_cadastral_->funcao->ViewValue ?></td>
			<td><strong><?php echo $rel_ficha_cadastral_->nome_empresa->FldCaption() ?></strong></td>
			<td><?php echo $rel_ficha_cadastral_->nome_empresa->ViewValue ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $rel_ficha_cadastral_->dependentes->FldCaption() ?></strong></td>
			<td colspan="3" rowspan="1"><?php echo $rel_ficha_cadastral_->dependentes->ViewValue ?></td>
		</tr>
		<tr>
			<td colspan="4" style="height:100px"><?php echo $rel_ficha_cadastral_->A09DESCRICAO->ViewValue ?></td>
		</tr>
		<tr>
			<td style="text-align:center"><b>Fanca, <?php echo date("d/m/Y")?></b></td>
			<td colspan="3" rowspan="1" style="height:120px; text-align:center; vertical-align:bottom">------------------------------------------------------------<br />
			<?php echo $rel_ficha_cadastral_->socio->ViewValue ?><br />
			DEPENDENTES<br />
			Filhos(as) at&eacute; 16 anos.</td>
		</tr>
	</tbody>
</table>
	
	</td>
	</tr>

<?php
		$rel_ficha_cadastral__report->DetailRecordset->MoveNext();
	}
	$rel_ficha_cadastral__report->DetailRecordset->Close();

	// Save old group data
	$rel_ficha_cadastral__report->ReportGroups[0] = $rel_ficha_cadastral_->A04imagem->CurrentValue;
	$rel_ficha_cadastral__report->ReportGroups[1] = $rel_ficha_cadastral_->A01URL->CurrentValue;
	$rel_ficha_cadastral__report->ReportGroups[2] = $rel_ficha_cadastral_->A21EMPRESA->CurrentValue;

	// Get next record
	$rel_ficha_cadastral__report->Recordset->MoveNext();
	if ($rel_ficha_cadastral__report->Recordset->EOF) {
		$rel_ficha_cadastral__report->RecCnt = 0; // EOF, force all level breaks
	} else {
		$rel_ficha_cadastral_->A04imagem->setDbValue($rel_ficha_cadastral__report->Recordset->fields('A04imagem'));
		$rel_ficha_cadastral_->A01URL->setDbValue($rel_ficha_cadastral__report->Recordset->fields('A01URL'));
		$rel_ficha_cadastral_->A21EMPRESA->setDbValue($rel_ficha_cadastral__report->Recordset->fields('A21EMPRESA'));
	}
	$rel_ficha_cadastral__report->ChkLvlBreak();

	// Show footers
	if ($rel_ficha_cadastral__report->LevelBreak[3]) {
		$rel_ficha_cadastral_->A21EMPRESA->CurrentValue = $rel_ficha_cadastral__report->ReportGroups[2];

		// Render row for view
		$rel_ficha_cadastral_->RowType = EW_ROWTYPE_VIEW;
		$rel_ficha_cadastral_->ResetAttrs();
		$rel_ficha_cadastral__report->RenderRow();
		$rel_ficha_cadastral_->A21EMPRESA->CurrentValue = $rel_ficha_cadastral_->A21EMPRESA->DbValue;
?>
<?php
}
	if ($rel_ficha_cadastral__report->LevelBreak[2]) {
		$rel_ficha_cadastral_->A01URL->CurrentValue = $rel_ficha_cadastral__report->ReportGroups[1];

		// Render row for view
		$rel_ficha_cadastral_->RowType = EW_ROWTYPE_VIEW;
		$rel_ficha_cadastral_->ResetAttrs();
		$rel_ficha_cadastral__report->RenderRow();
		$rel_ficha_cadastral_->A01URL->CurrentValue = $rel_ficha_cadastral_->A01URL->DbValue;
?>
<?php
}
	if ($rel_ficha_cadastral__report->LevelBreak[1]) {
		$rel_ficha_cadastral_->A04imagem->CurrentValue = $rel_ficha_cadastral__report->ReportGroups[0];

		// Render row for view
		$rel_ficha_cadastral_->RowType = EW_ROWTYPE_VIEW;
		$rel_ficha_cadastral_->ResetAttrs();
		$rel_ficha_cadastral__report->RenderRow();
		$rel_ficha_cadastral_->A04imagem->CurrentValue = $rel_ficha_cadastral_->A04imagem->DbValue;
?>
<?php
}
}

// Close recordset
$rel_ficha_cadastral__report->Recordset->Close();
?>
<!--
<?php if ($rel_ficha_cadastral__report->RecordExists) { ?>
	<tr><td colspan=3>&nbsp;<br></td></tr>
	<tr><td colspan=3 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($rel_ficha_cadastral__report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
<?php if ($rel_ficha_cadastral__report->RecordExists) { ?>
	<tr><td colspan=3>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>-->
</table>
<?php
$rel_ficha_cadastral__report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($rel_ficha_cadastral_->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$rel_ficha_cadastral__report->Page_Terminate();
?>
