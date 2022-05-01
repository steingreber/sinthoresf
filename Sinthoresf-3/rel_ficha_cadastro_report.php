<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php

// Global variable for table object
$rel_ficha_cadastro_ = NULL;

//
// Table class for rel_ficha_cadastro_
//
class crel_ficha_cadastro_ extends cTableBase {
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
		$this->TableVar = 'rel_ficha_cadastro_';
		$this->TableName = 'rel_ficha_cadastro_';
		$this->TableType = 'REPORT';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// cod_func
		$this->cod_func = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_cod_func', 'cod_func', '`cod_func`', '`cod_func`', 3, -1, FALSE, '`cod_func`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cod_func->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cod_func'] = &$this->cod_func;

		// nome
		$this->nome = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_nome', 'nome', '`nome`', '`nome`', 200, -1, FALSE, '`nome`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nome'] = &$this->nome;

		// endereco
		$this->endereco = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_endereco', 'endereco', '`endereco`', '`endereco`', 200, -1, FALSE, '`endereco`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['endereco'] = &$this->endereco;

		// numero
		$this->numero = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_numero', 'numero', '`numero`', '`numero`', 3, -1, FALSE, '`numero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->numero->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['numero'] = &$this->numero;

		// bairro
		$this->bairro = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_bairro', 'bairro', '`bairro`', '`bairro`', 200, -1, FALSE, '`bairro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['bairro'] = &$this->bairro;

		// cidade
		$this->cidade = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_cidade', 'cidade', '`cidade`', '`cidade`', 200, -1, FALSE, '`cidade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cidade'] = &$this->cidade;

		// sexo
		$this->sexo = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_sexo', 'sexo', '`sexo`', '`sexo`', 200, -1, FALSE, '`sexo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['sexo'] = &$this->sexo;

		// estado_civil
		$this->estado_civil = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_estado_civil', 'estado_civil', '`estado_civil`', '`estado_civil`', 3, -1, FALSE, '`estado_civil`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->estado_civil->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['estado_civil'] = &$this->estado_civil;

		// rg
		$this->rg = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_rg', 'rg', '`rg`', '`rg`', 200, -1, FALSE, '`rg`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['rg'] = &$this->rg;

		// cpf
		$this->cpf = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_cpf', 'cpf', '`cpf`', '`cpf`', 200, -1, FALSE, '`cpf`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cpf'] = &$this->cpf;

		// carteira_trabalho
		$this->carteira_trabalho = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_carteira_trabalho', 'carteira_trabalho', '`carteira_trabalho`', '`carteira_trabalho`', 200, -1, FALSE, '`carteira_trabalho`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['carteira_trabalho'] = &$this->carteira_trabalho;

		// nacionalidade
		$this->nacionalidade = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_nacionalidade', 'nacionalidade', '`nacionalidade`', '`nacionalidade`', 200, -1, FALSE, '`nacionalidade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nacionalidade'] = &$this->nacionalidade;

		// naturalidade
		$this->naturalidade = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_naturalidade', 'naturalidade', '`naturalidade`', '`naturalidade`', 200, -1, FALSE, '`naturalidade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['naturalidade'] = &$this->naturalidade;

		// datanasc
		$this->datanasc = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_datanasc', 'datanasc', '`datanasc`', '`datanasc`', 200, -1, FALSE, '`datanasc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->datanasc->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['datanasc'] = &$this->datanasc;

		// funcao
		$this->funcao = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_funcao', 'funcao', '`funcao`', '`funcao`', 200, -1, FALSE, '`funcao`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['funcao'] = &$this->funcao;

		// cod_empresa
		$this->cod_empresa = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_cod_empresa', 'cod_empresa', '`cod_empresa`', '`cod_empresa`', 3, -1, FALSE, '`cod_empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cod_empresa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cod_empresa'] = &$this->cod_empresa;

		// dt_entrou_empresa
		$this->dt_entrou_empresa = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_dt_entrou_empresa', 'dt_entrou_empresa', '`dt_entrou_empresa`', '`dt_entrou_empresa`', 200, -1, FALSE, '`dt_entrou_empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_entrou_empresa->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_entrou_empresa'] = &$this->dt_entrou_empresa;

		// dt_entrou_categoria
		$this->dt_entrou_categoria = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_dt_entrou_categoria', 'dt_entrou_categoria', '`dt_entrou_categoria`', '`dt_entrou_categoria`', 200, -1, FALSE, '`dt_entrou_categoria`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_entrou_categoria->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_entrou_categoria'] = &$this->dt_entrou_categoria;

		// foto
		$this->foto = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_foto', 'foto', '`foto`', '`foto`', 200, -1, TRUE, '`foto`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['foto'] = &$this->foto;

		// ativo
		$this->ativo = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_ativo', 'ativo', '`ativo`', '`ativo`', 3, -1, FALSE, '`ativo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ativo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ativo'] = &$this->ativo;

		// dependentes
		$this->dependentes = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_dependentes', 'dependentes', '`dependentes`', '`dependentes`', 201, -1, FALSE, '`dependentes`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dependentes'] = &$this->dependentes;

		// dtcad
		$this->dtcad = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_dtcad', 'dtcad', '`dtcad`', '`dtcad`', 200, -1, FALSE, '`dtcad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dtcad->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dtcad'] = &$this->dtcad;

		// dtcarteira
		$this->dtcarteira = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_dtcarteira', 'dtcarteira', '`dtcarteira`', '`dtcarteira`', 200, -1, FALSE, '`dtcarteira`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dtcarteira->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dtcarteira'] = &$this->dtcarteira;

		// telefone
		$this->telefone = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_telefone', 'telefone', '`telefone`', '`telefone`', 200, -1, FALSE, '`telefone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['telefone'] = &$this->telefone;

		// acompanhante
		$this->acompanhante = new cField('rel_ficha_cadastro_', 'rel_ficha_cadastro_', 'x_acompanhante', 'acompanhante', '`acompanhante`', '`acompanhante`', 201, -1, FALSE, '`acompanhante`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['acompanhante'] = &$this->acompanhante;
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
		if ($this->getCurrentMasterTable() == "funcionarios") {
			if ($this->cod_func->getSessionValue() <> "")
				$sMasterFilter .= "`cod_func`=" . ew_QuotedValue($this->cod_func->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "funcionarios") {
			if ($this->cod_func->getSessionValue() <> "")
				$sDetailFilter .= "`cod_func`=" . ew_QuotedValue($this->cod_func->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_funcionarios() {
		return "`cod_func`=@cod_func@";
	}

	// Detail filter
	function SqlDetailFilter_funcionarios() {
		return "`cod_func`=@cod_func@";
	}

	// Report detail level SQL
	var $_SqlDetailSelect = "";

	function getSqlDetailSelect() { // Select
		return ($this->_SqlDetailSelect <> "") ? $this->_SqlDetailSelect : "SELECT * FROM `funcionarios`";
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
		return ($this->_SqlDetailOrderBy <> "") ? $this->_SqlDetailOrderBy : "`cod_func` DESC";
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
			return "rel_ficha_cadastro_report.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "rel_ficha_cadastro_report.php";
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
<?php include_once "funcionariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$rel_ficha_cadastro__report = NULL; // Initialize page object first

class crel_ficha_cadastro__report extends crel_ficha_cadastro_ {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'rel_ficha_cadastro_';

	// Page object name
	var $PageObjName = 'rel_ficha_cadastro__report';

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

		// Table object (rel_ficha_cadastro_)
		if (!isset($GLOBALS["rel_ficha_cadastro_"]) || get_class($GLOBALS["rel_ficha_cadastro_"]) == "crel_ficha_cadastro_") {
			$GLOBALS["rel_ficha_cadastro_"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["rel_ficha_cadastro_"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Table object (funcionarios)
		if (!isset($GLOBALS['funcionarios'])) $GLOBALS['funcionarios'] = new cfuncionarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'report', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'rel_ficha_cadastro_', TRUE);

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
		$this->ReportGroups = &ew_InitArray(1, NULL);
		$this->ReportCounts = &ew_InitArray(1, 0);
		$this->LevelBreak = &ew_InitArray(1, FALSE);
		$this->ReportTotals = &ew_Init2DArray(1, 21, 0);
		$this->ReportMaxs = &ew_Init2DArray(1, 21, 0);
		$this->ReportMins = &ew_Init2DArray(1, 21, 0);

		// Get reset command
		if (@$_GET["cmd"] <> "") {
			$this->Command = strtolower($_GET["cmd"]);

			// Reset master/detail
			if ($this->Command == "resetall") {
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->cod_func->setSessionValue("");
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
		global $funcionarios;
		if ($this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "funcionarios") {
			$rsmaster = $funcionarios->LoadRs($this->GetMasterFilter());
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
				$this->Page_Terminate("funcionarioslist.php"); // Return to master page
			} else {
				$funcionarios->LoadListRowValues($rsmaster);
				$funcionarios->RowType = EW_ROWTYPE_MASTER; // Master row
				$funcionarios->RenderListRow();
				$rsmaster->Close();
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
			switch (@$gsLanguage) {
				case "br":
					$sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
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
				if ($this->Export == "excel" && defined('EW_USE_PHPEXCEL')) {
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
		$item->Visible = FALSE;

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
			if ($sMasterTblVar == "funcionarios") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_cod_func"] <> "") {
					$GLOBALS["funcionarios"]->cod_func->setQueryStringValue($_GET["fk_cod_func"]);
					$this->cod_func->setQueryStringValue($GLOBALS["funcionarios"]->cod_func->QueryStringValue);
					$this->cod_func->setSessionValue($this->cod_func->QueryStringValue);
					if (!is_numeric($GLOBALS["funcionarios"]->cod_func->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "funcionarios") {
				if ($this->cod_func->QueryStringValue == "") $this->cod_func->setSessionValue("");
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
if (!isset($rel_ficha_cadastro__report)) $rel_ficha_cadastro__report = new crel_ficha_cadastro__report();

// Page init
$rel_ficha_cadastro__report->Page_Init();

// Page main
$rel_ficha_cadastro__report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$rel_ficha_cadastro__report->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($rel_ficha_cadastro_->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<div class="ewToolbar">
<?php if ($rel_ficha_cadastro_->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($rel_ficha_cadastro_->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
$rel_ficha_cadastro__report->RecCnt = 1; // No grouping
if ($rel_ficha_cadastro__report->DbDetailFilter <> "") {
	if ($rel_ficha_cadastro__report->ReportFilter <> "") $rel_ficha_cadastro__report->ReportFilter .= " AND ";
	$rel_ficha_cadastro__report->ReportFilter .= "(" . $rel_ficha_cadastro__report->DbDetailFilter . ")";
}

// Set up detail SQL
$rel_ficha_cadastro_->CurrentFilter = $rel_ficha_cadastro__report->ReportFilter;
$rel_ficha_cadastro__report->ReportSql = $rel_ficha_cadastro_->DetailSQL();

// Load recordset
$rel_ficha_cadastro__report->Recordset = $conn->Execute($rel_ficha_cadastro__report->ReportSql);
$rel_ficha_cadastro__report->RecordExists = !$rel_ficha_cadastro__report->Recordset->EOF;
?>
<?php if ($rel_ficha_cadastro_->Export == "") { ?>
<?php if ($rel_ficha_cadastro__report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $rel_ficha_cadastro__report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $rel_ficha_cadastro__report->ShowPageHeader(); ?>
<!--<table class="ewReportTable">-->
<?php

	// Get detail records
	$rel_ficha_cadastro__report->ReportFilter = $rel_ficha_cadastro__report->DefaultFilter;
	if ($rel_ficha_cadastro__report->DbDetailFilter <> "") {
		if ($rel_ficha_cadastro__report->ReportFilter <> "")
			$rel_ficha_cadastro__report->ReportFilter .= " AND ";
		$rel_ficha_cadastro__report->ReportFilter .= "(" . $rel_ficha_cadastro__report->DbDetailFilter . ")";
	}

	// Set up detail SQL
	$rel_ficha_cadastro_->CurrentFilter = $rel_ficha_cadastro__report->ReportFilter;
	$rel_ficha_cadastro__report->ReportSql = $rel_ficha_cadastro_->DetailSQL();

	// Load detail records
	$rel_ficha_cadastro__report->DetailRecordset = $conn->Execute($rel_ficha_cadastro__report->ReportSql);
	$rel_ficha_cadastro__report->DtlRecordCount = $rel_ficha_cadastro__report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$rel_ficha_cadastro__report->DetailRecordset->EOF) {
		$rel_ficha_cadastro__report->RecCnt++;
	}
	if ($rel_ficha_cadastro__report->RecCnt == 1) {
		$rel_ficha_cadastro__report->ReportCounts[0] = 0;
	}
	$rel_ficha_cadastro__report->ReportCounts[0] += $rel_ficha_cadastro__report->DtlRecordCount;
	if ($rel_ficha_cadastro__report->RecordExists) {
?>
<!--
	<tr>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->cod_func->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->nome->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->endereco->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->numero->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->bairro->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->cidade->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->sexo->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->estado_civil->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->rg->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->cpf->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->carteira_trabalho->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->nacionalidade->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->naturalidade->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->datanasc->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->funcao->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->cod_empresa->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->dependentes->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->dtcad->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->dtcarteira->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_ficha_cadastro_->telefone->FldCaption() ?></td>
	</tr>
-->
<?php
	}
	while (!$rel_ficha_cadastro__report->DetailRecordset->EOF) {
		$rel_ficha_cadastro_->cod_func->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('cod_func'));
		$rel_ficha_cadastro_->nome->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('nome'));
		$rel_ficha_cadastro_->endereco->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('endereco'));
		$rel_ficha_cadastro_->numero->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('numero'));
		$rel_ficha_cadastro_->bairro->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('bairro'));
		$rel_ficha_cadastro_->cidade->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('cidade'));
		$rel_ficha_cadastro_->sexo->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('sexo'));
		$rel_ficha_cadastro_->estado_civil->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('estado_civil'));
		$rel_ficha_cadastro_->rg->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('rg'));
		$rel_ficha_cadastro_->cpf->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('cpf'));
		$rel_ficha_cadastro_->carteira_trabalho->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('carteira_trabalho'));
		$rel_ficha_cadastro_->nacionalidade->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('nacionalidade'));
		$rel_ficha_cadastro_->naturalidade->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('naturalidade'));
		$rel_ficha_cadastro_->datanasc->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('datanasc'));
		$rel_ficha_cadastro_->funcao->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('funcao'));
		$rel_ficha_cadastro_->cod_empresa->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('cod_empresa'));
		$rel_ficha_cadastro_->dependentes->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('dependentes'));
		$rel_ficha_cadastro_->dtcad->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('dtcad'));
		$rel_ficha_cadastro_->dtcarteira->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('dtcarteira'));
		$rel_ficha_cadastro_->telefone->setDbValue($rel_ficha_cadastro__report->DetailRecordset->fields('telefone'));

		// Render for view
		$rel_ficha_cadastro_->RowType = EW_ROWTYPE_VIEW;
		$rel_ficha_cadastro_->ResetAttrs();
		$rel_ficha_cadastro__report->RenderRow();
?>
<!--
	<tr>
		<td<?php echo $rel_ficha_cadastro_->cod_func->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->cod_func->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->cod_func->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->nome->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->nome->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->nome->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->endereco->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->endereco->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->endereco->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->numero->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->numero->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->numero->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->bairro->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->bairro->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->bairro->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->cidade->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->cidade->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->cidade->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->sexo->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->sexo->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->sexo->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->estado_civil->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->estado_civil->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->estado_civil->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->rg->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->rg->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->rg->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->cpf->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->cpf->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->cpf->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->carteira_trabalho->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->carteira_trabalho->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->carteira_trabalho->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->nacionalidade->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->nacionalidade->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->nacionalidade->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->naturalidade->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->naturalidade->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->naturalidade->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->datanasc->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->datanasc->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->datanasc->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->funcao->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->funcao->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->funcao->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->cod_empresa->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->cod_empresa->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->cod_empresa->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->dependentes->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->dependentes->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->dependentes->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->dtcad->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->dtcad->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->dtcad->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->dtcarteira->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->dtcarteira->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->dtcarteira->ViewValue ?></span>
</td>
		<td<?php echo $rel_ficha_cadastro_->telefone->CellAttributes() ?>>
<span<?php echo $rel_ficha_cadastro_->telefone->ViewAttributes() ?>>
<?php echo $rel_ficha_cadastro_->telefone->ViewValue ?></span>
</td>
	</tr>
-->
<!-- ficha inicio -->
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2">
			<p>
				<img alt="" src="sistema/sinthoresf-logo.png" style="width: 100px; height: 88px; float: left;" /><span style="font-family:verdana,geneva,sans-serif;"><span style="font-size: 18px;">(SINTHORESF) SINDICATO DOS EMPREGADOS NO COMERCIO HOTELEIRO E SIMILARES DE FRANCA E REGIÃO<br /></span></span></p>
			<p>
				<span style="font-family:verdana,geneva,sans-serif;"><span style="font-size: 18px;">AV. RIO BRANCO, 245 FRANCA-SP Fone:(16) 3721-3532</span></span></p>
		</td>
	</tr>
    <tr>
        <td height="43" align="center">
			<hr width="100%" align="left" noshade color="black" size="1">
            <h3>Ficha do Sócio #<?php echo $rel_ficha_cadastro_->cod_func->ViewValue ?>#</h3>
			<hr width="100%" align="left" noshade color="black" size="1">
        </td>
    </tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" height="512">
                <tr>
                    <td height="30"><strong>Nome </strong><?php echo $rel_ficha_cadastro_->nome->ViewValue ?></td>
					<td height="30"><strong>Estado Civil:</strong> <?php echo $rel_ficha_cadastro_->estado_civil->ViewValue ?></td>
                </tr>
                <tr>
                    <td width="50%" height="30"><strong>Endereço:</strong> <?php echo $rel_ficha_cadastro_->endereco->ViewValue ?>, <?php echo $rel_ficha_cadastro_->numero->ViewValue ?></td>
                    <td width="50%" height="30"><strong>Bairro:</strong> <?php echo $rel_ficha_cadastro_->bairro->ViewValue ?></td>
                </tr>
                <tr>
                    <td width="50%" height="30"><strong>Cidade/UF:</strong> <?php echo $rel_ficha_cadastro_->cidade->ViewValue ?></td>
                    <td width="50%" height="30"><strong>Sexo:</strong> <?php echo $rel_ficha_cadastro_->sexo->ViewValue ?></td>
                </tr>
                <tr>
                    <td width="50%" height="30"><strong>RG:</strong> <?php echo $rel_ficha_cadastro_->rg->ViewValue ?></td>
                    <td width="50%" height="30"><strong>CPF:</strong> <?php echo $rel_ficha_cadastro_->cpf->ViewValue ?></td>
                </tr>
                <tr>
                    <td width="50%" height="30"><strong>Carteira Trabalho:</strong> <?php echo $rel_ficha_cadastro_->carteira_trabalho->ViewValue ?></td>
                    <td width="50%" height="30"><strong>Dt. Nascimento:</strong> <?php echo $rel_ficha_cadastro_->datanasc->ViewValue ?></td>
                </tr>
                <tr>
                    <td width="50%" height="30"><strong>Naturalidade:</strong> <?php echo $rel_ficha_cadastro_->naturalidade->ViewValue ?></td>
                    <td width="50%" height="30"><strong>Nacionalidade:</strong> <?php echo $rel_ficha_cadastro_->nacionalidade->ViewValue ?></td>
                </tr>
                <tr>
                    <td width="50%" height="30"><strong>Cargo:</strong> <?php echo $rel_ficha_cadastro_->funcao->ViewValue ?></td>
                    <td width="50%" height="30"><strong>Empresa:</strong> <?php echo $rel_ficha_cadastro_->cod_empresa->ViewValue ?></td>
                </tr>
                <tr>
                    <td width="50%" height="30"><strong>Data de contrato:</strong> </td>
                    <td width="50%" height="30"><strong>Data entrou categoria:</strong> </td>
                </tr>
                <tr>
                    <td height="30" colspan="2"><strong>Dependentes:</strong><br><?php echo $rel_ficha_cadastro_->dependentes->ViewValue ?></td>
                </tr>
                <tr>
                    <td height="30" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td height="134" colspan="2" align="center">Pelo presente instrumento autorizo os descontos das contribuições estipuladas em assembléia bem como ora por mim contratados,
					ficando ciente que para a baixa de associado deverei comparecer pessoalmente, comunicando-o por escrito de próprio punho.</td>
                </tr>
                <tr>
                    <td height="30" colspan="2">Franca, <?php echo date("d/m/Y")?></td>
                </tr>
                <tr>
                    <td height="78" colspan="2" valign="bottom">
					<hr width="50%" align="left" noshade color="black" size="1"><?php echo $rel_ficha_cadastro_->nome->ViewValue ?>
					</td>
                </tr>
                <tr>
                    <td height="120" colspan="2" align="center" valign="bottom">DEPENDENTES<BR />Filhos(as) até 16 anos.</td>
                </tr>
</table>				
<!-- ficha fim -->
<!--
<?php
		$rel_ficha_cadastro__report->DetailRecordset->MoveNext();
	}
	$rel_ficha_cadastro__report->DetailRecordset->Close();
?>
<?php if ($rel_ficha_cadastro__report->RecordExists) { ?>
	<tr><td colspan=20>&nbsp;<br></td></tr>
	<tr><td colspan=20 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($rel_ficha_cadastro__report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
<?php if ($rel_ficha_cadastro__report->RecordExists) { ?>
	<tr><td colspan=20>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>
<?php
$rel_ficha_cadastro__report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($rel_ficha_cadastro_->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
-->
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$rel_ficha_cadastro__report->Page_Terminate();
?>
