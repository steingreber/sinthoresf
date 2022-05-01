<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php

// Global variable for table object
$rel_empresas_regioes_ = NULL;

//
// Table class for rel_empresas_regioes_
//
class crel_empresas_regioes_ extends cTableBase {
	var $cod_empresa;
	var $nome_empresa;
	var $endereco;
	var $numero;
	var $telefone;
	var $regiao;
	var $A21EMPRESA;
	var $A04imagem;
	var $A01URL;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'rel_empresas_regioes_';
		$this->TableName = 'rel_empresas_regioes_';
		$this->TableType = 'REPORT';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// cod_empresa
		$this->cod_empresa = new cField('rel_empresas_regioes_', 'rel_empresas_regioes_', 'x_cod_empresa', 'cod_empresa', '`cod_empresa`', '`cod_empresa`', 3, -1, FALSE, '`cod_empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cod_empresa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cod_empresa'] = &$this->cod_empresa;

		// nome_empresa
		$this->nome_empresa = new cField('rel_empresas_regioes_', 'rel_empresas_regioes_', 'x_nome_empresa', 'nome_empresa', '`nome_empresa`', '`nome_empresa`', 200, -1, FALSE, '`nome_empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nome_empresa'] = &$this->nome_empresa;

		// endereco
		$this->endereco = new cField('rel_empresas_regioes_', 'rel_empresas_regioes_', 'x_endereco', 'endereco', '`endereco`', '`endereco`', 200, -1, FALSE, '`endereco`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['endereco'] = &$this->endereco;

		// numero
		$this->numero = new cField('rel_empresas_regioes_', 'rel_empresas_regioes_', 'x_numero', 'numero', '`numero`', '`numero`', 3, -1, FALSE, '`numero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->numero->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['numero'] = &$this->numero;

		// telefone
		$this->telefone = new cField('rel_empresas_regioes_', 'rel_empresas_regioes_', 'x_telefone', 'telefone', '`telefone`', '`telefone`', 200, -1, FALSE, '`telefone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['telefone'] = &$this->telefone;

		// regiao
		$this->regiao = new cField('rel_empresas_regioes_', 'rel_empresas_regioes_', 'x_regiao', 'regiao', '`regiao`', '`regiao`', 3, -1, FALSE, '`regiao`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->regiao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['regiao'] = &$this->regiao;

		// A21EMPRESA
		$this->A21EMPRESA = new cField('rel_empresas_regioes_', 'rel_empresas_regioes_', 'x_A21EMPRESA', 'A21EMPRESA', '`A21EMPRESA`', '`A21EMPRESA`', 200, -1, FALSE, '`A21EMPRESA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A21EMPRESA'] = &$this->A21EMPRESA;

		// A04imagem
		$this->A04imagem = new cField('rel_empresas_regioes_', 'rel_empresas_regioes_', 'x_A04imagem', 'A04imagem', '`A04imagem`', '`A04imagem`', 200, -1, FALSE, '`A04imagem`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A04imagem'] = &$this->A04imagem;

		// A01URL
		$this->A01URL = new cField('rel_empresas_regioes_', 'rel_empresas_regioes_', 'x_A01URL', 'A01URL', '`A01URL`', '`A01URL`', 200, -1, FALSE, '`A01URL`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A01URL'] = &$this->A01URL;
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
		if ($this->getCurrentMasterTable() == "regioes") {
			if ($this->regiao->getSessionValue() <> "")
				$sMasterFilter .= "`id_regiao`=" . ew_QuotedValue($this->regiao->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "regioes") {
			if ($this->regiao->getSessionValue() <> "")
				$sDetailFilter .= "`regiao`=" . ew_QuotedValue($this->regiao->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_regioes() {
		return "`id_regiao`=@id_regiao@";
	}

	// Detail filter
	function SqlDetailFilter_regioes() {
		return "`regiao`=@regiao@";
	}

	// Report group level SQL
	var $_SqlGroupSelect = "";

	function getSqlGroupSelect() { // Select
		return ($this->_SqlGroupSelect <> "") ? $this->_SqlGroupSelect : "SELECT DISTINCT `A04imagem`,`A21EMPRESA`,`A01URL` FROM `view_esmpresas_regioes`";
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
		return ($this->_SqlGroupOrderBy <> "") ? $this->_SqlGroupOrderBy : "`A04imagem` ASC,`A21EMPRESA` ASC,`A01URL` ASC";
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
		return ($this->_SqlDetailSelect <> "") ? $this->_SqlDetailSelect : "SELECT * FROM `view_esmpresas_regioes`";
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
			return "rel_empresas_regioes_report.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "rel_empresas_regioes_report.php";
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
<?php include_once "regioesinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$rel_empresas_regioes__report = NULL; // Initialize page object first

class crel_empresas_regioes__report extends crel_empresas_regioes_ {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'rel_empresas_regioes_';

	// Page object name
	var $PageObjName = 'rel_empresas_regioes__report';

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

		// Table object (rel_empresas_regioes_)
		if (!isset($GLOBALS["rel_empresas_regioes_"]) || get_class($GLOBALS["rel_empresas_regioes_"]) == "crel_empresas_regioes_") {
			$GLOBALS["rel_empresas_regioes_"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["rel_empresas_regioes_"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Table object (permissoes)
		if (!isset($GLOBALS['permissoes'])) $GLOBALS['permissoes'] = new cpermissoes();

		// Table object (regioes)
		if (!isset($GLOBALS['regioes'])) $GLOBALS['regioes'] = new cregioes();

		// User table object (permissoes)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cpermissoes();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'report', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'rel_empresas_regioes_', TRUE);

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
		$this->ReportTotals = &ew_Init2DArray(4, 7, 0);
		$this->ReportMaxs = &ew_Init2DArray(4, 7, 0);
		$this->ReportMins = &ew_Init2DArray(4, 7, 0);

		// Get reset command
		if (@$_GET["cmd"] <> "") {
			$this->Command = strtolower($_GET["cmd"]);

			// Reset master/detail
			if ($this->Command == "resetall") {
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->regiao->setSessionValue("");
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
		global $regioes;
		if ($this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "regioes") {
			$rsmaster = $regioes->LoadRs($this->GetMasterFilter());
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
				$this->Page_Terminate("regioeslist.php"); // Return to master page
			} else {
				$regioes->LoadListRowValues($rsmaster);
				$regioes->RowType = EW_ROWTYPE_MASTER; // Master row
				$regioes->RenderListRow();
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
			if (!ew_CompareValue($this->A21EMPRESA->CurrentValue, $this->ReportGroups[1])) {
				$this->LevelBreak[2] = TRUE;
				$this->LevelBreak[3] = TRUE;
			}
			if (!ew_CompareValue($this->A01URL->CurrentValue, $this->ReportGroups[2])) {
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
		// cod_empresa
		// nome_empresa
		// endereco
		// numero
		// telefone
		// regiao
		// A21EMPRESA
		// A04imagem
		// A01URL

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
			$this->regiao->CellCssStyle .= "text-align: center;";
			$this->regiao->ViewCustomAttributes = "";

			// A21EMPRESA
			$this->A21EMPRESA->ViewValue = $this->A21EMPRESA->CurrentValue;
			$this->A21EMPRESA->ViewCustomAttributes = "";

			// A04imagem
			$this->A04imagem->ViewValue = $this->A04imagem->CurrentValue;
			$this->A04imagem->ViewCustomAttributes = "";

			// A01URL
			$this->A01URL->ViewValue = $this->A01URL->CurrentValue;
			$this->A01URL->ViewCustomAttributes = "";

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

			// A04imagem
			$this->A04imagem->LinkCustomAttributes = "";
			$this->A04imagem->HrefValue = "";
			$this->A04imagem->TooltipValue = "";

			// A01URL
			$this->A01URL->LinkCustomAttributes = "";
			$this->A01URL->HrefValue = "";
			$this->A01URL->TooltipValue = "";
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
			if ($sMasterTblVar == "regioes") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_id_regiao"] <> "") {
					$GLOBALS["regioes"]->id_regiao->setQueryStringValue($_GET["fk_id_regiao"]);
					$this->regiao->setQueryStringValue($GLOBALS["regioes"]->id_regiao->QueryStringValue);
					$this->regiao->setSessionValue($this->regiao->QueryStringValue);
					if (!is_numeric($GLOBALS["regioes"]->id_regiao->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "regioes") {
				if ($this->regiao->QueryStringValue == "") $this->regiao->setSessionValue("");
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
if (!isset($rel_empresas_regioes__report)) $rel_empresas_regioes__report = new crel_empresas_regioes__report();

// Page init
$rel_empresas_regioes__report->Page_Init();

// Page main
$rel_empresas_regioes__report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$rel_empresas_regioes__report->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($rel_empresas_regioes_->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<div class="ewToolbar">
<?php if ($rel_empresas_regioes_->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($rel_empresas_regioes_->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
$rel_empresas_regioes__report->DefaultFilter = "";
$rel_empresas_regioes__report->ReportFilter = $rel_empresas_regioes__report->DefaultFilter;
if (!$Security->CanReport()) {
	if ($rel_empresas_regioes__report->ReportFilter <> "") $rel_empresas_regioes__report->ReportFilter .= " AND ";
	$rel_empresas_regioes__report->ReportFilter .= "(0=1)";
}
if ($rel_empresas_regioes__report->DbDetailFilter <> "") {
	if ($rel_empresas_regioes__report->ReportFilter <> "") $rel_empresas_regioes__report->ReportFilter .= " AND ";
	$rel_empresas_regioes__report->ReportFilter .= "(" . $rel_empresas_regioes__report->DbDetailFilter . ")";
}

// Set up filter and load Group level sql
$rel_empresas_regioes_->CurrentFilter = $rel_empresas_regioes__report->ReportFilter;
$rel_empresas_regioes__report->ReportSql = $rel_empresas_regioes_->GroupSQL();

// Load recordset
$rel_empresas_regioes__report->Recordset = $conn->Execute($rel_empresas_regioes__report->ReportSql);
$rel_empresas_regioes__report->RecordExists = !$rel_empresas_regioes__report->Recordset->EOF;
?>
<?php if ($rel_empresas_regioes_->Export == "") { ?>
<?php if ($rel_empresas_regioes__report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $rel_empresas_regioes__report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $rel_empresas_regioes__report->ShowPageHeader(); ?>
<table align="center" border="0" class="ewReportTable">
<?php

// Get First Row
if ($rel_empresas_regioes__report->RecordExists) {
	$rel_empresas_regioes_->A04imagem->setDbValue($rel_empresas_regioes__report->Recordset->fields('A04imagem'));
	$rel_empresas_regioes__report->ReportGroups[0] = $rel_empresas_regioes_->A04imagem->DbValue;
	$rel_empresas_regioes_->A21EMPRESA->setDbValue($rel_empresas_regioes__report->Recordset->fields('A21EMPRESA'));
	$rel_empresas_regioes__report->ReportGroups[1] = $rel_empresas_regioes_->A21EMPRESA->DbValue;
	$rel_empresas_regioes_->A01URL->setDbValue($rel_empresas_regioes__report->Recordset->fields('A01URL'));
	$rel_empresas_regioes__report->ReportGroups[2] = $rel_empresas_regioes_->A01URL->DbValue;
}
$rel_empresas_regioes__report->RecCnt = 0;
$rel_empresas_regioes__report->ReportCounts[0] = 0;
$rel_empresas_regioes__report->ChkLvlBreak();
while (!$rel_empresas_regioes__report->Recordset->EOF) {

	// Render for view
	$rel_empresas_regioes_->RowType = EW_ROWTYPE_VIEW;
	$rel_empresas_regioes_->ResetAttrs();
	$rel_empresas_regioes__report->RenderRow();

	// Show group headers
	if ($rel_empresas_regioes__report->LevelBreak[1]) { // Reset counter and aggregation
?>
	<tr>
	<td colspan=6 class="ewGroupName">
	<p><img alt="" src="<?php echo strtolower($rel_empresas_regioes_->A01URL->ViewValue) ?><?php echo strtolower($rel_empresas_regioes_->A04imagem->ViewValue) ?>" style="float:left; height:71px; width:80px" /><br />
<?php echo $rel_empresas_regioes_->A21EMPRESA->ViewValue ?></p>
	</td>
	</tr>
<?php
	}
	if ($rel_empresas_regioes__report->LevelBreak[2]) { // Reset counter and aggregation
?>

<?php
	}
	if ($rel_empresas_regioes__report->LevelBreak[3]) { // Reset counter and aggregation
?>

<?php
	}

	// Get detail records
	$rel_empresas_regioes__report->ReportFilter = $rel_empresas_regioes__report->DefaultFilter;
	if ($rel_empresas_regioes__report->ReportFilter <> "") $rel_empresas_regioes__report->ReportFilter .= " AND ";
	if (is_null($rel_empresas_regioes_->A04imagem->CurrentValue)) {
		$rel_empresas_regioes__report->ReportFilter .= "(`A04imagem` IS NULL)";
	} else {
		$rel_empresas_regioes__report->ReportFilter .= "(`A04imagem` = '" . ew_AdjustSql($rel_empresas_regioes_->A04imagem->CurrentValue) . "')";
	}
	if ($rel_empresas_regioes__report->ReportFilter <> "") $rel_empresas_regioes__report->ReportFilter .= " AND ";
	if (is_null($rel_empresas_regioes_->A21EMPRESA->CurrentValue)) {
		$rel_empresas_regioes__report->ReportFilter .= "(`A21EMPRESA` IS NULL)";
	} else {
		$rel_empresas_regioes__report->ReportFilter .= "(`A21EMPRESA` = '" . ew_AdjustSql($rel_empresas_regioes_->A21EMPRESA->CurrentValue) . "')";
	}
	if ($rel_empresas_regioes__report->ReportFilter <> "") $rel_empresas_regioes__report->ReportFilter .= " AND ";
	if (is_null($rel_empresas_regioes_->A01URL->CurrentValue)) {
		$rel_empresas_regioes__report->ReportFilter .= "(`A01URL` IS NULL)";
	} else {
		$rel_empresas_regioes__report->ReportFilter .= "(`A01URL` = '" . ew_AdjustSql($rel_empresas_regioes_->A01URL->CurrentValue) . "')";
	}
	if ($rel_empresas_regioes__report->DbDetailFilter <> "") {
		if ($rel_empresas_regioes__report->ReportFilter <> "")
			$rel_empresas_regioes__report->ReportFilter .= " AND ";
		$rel_empresas_regioes__report->ReportFilter .= "(" . $rel_empresas_regioes__report->DbDetailFilter . ")";
	}
	if (!$Security->CanReport()) {
		if ($sFilter <> "") $sFilter .= " AND ";
		$sFilter .= "(0=1)";
	}

	// Set up detail SQL
	$rel_empresas_regioes_->CurrentFilter = $rel_empresas_regioes__report->ReportFilter;
	$rel_empresas_regioes__report->ReportSql = $rel_empresas_regioes_->DetailSQL();

	// Load detail records
	$rel_empresas_regioes__report->DetailRecordset = $conn->Execute($rel_empresas_regioes__report->ReportSql);
	$rel_empresas_regioes__report->DtlRecordCount = $rel_empresas_regioes__report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$rel_empresas_regioes__report->DetailRecordset->EOF) {
		$rel_empresas_regioes__report->RecCnt++;
	}
	if ($rel_empresas_regioes__report->RecCnt == 1) {
		$rel_empresas_regioes__report->ReportCounts[0] = 0;
	}
	for ($i = 1; $i <= 3; $i++) {
		if ($rel_empresas_regioes__report->LevelBreak[$i]) { // Reset counter and aggregation
			$rel_empresas_regioes__report->ReportCounts[$i] = 0;
		}
	}
	$rel_empresas_regioes__report->ReportCounts[0] += $rel_empresas_regioes__report->DtlRecordCount;
	$rel_empresas_regioes__report->ReportCounts[1] += $rel_empresas_regioes__report->DtlRecordCount;
	$rel_empresas_regioes__report->ReportCounts[2] += $rel_empresas_regioes__report->DtlRecordCount;
	$rel_empresas_regioes__report->ReportCounts[3] += $rel_empresas_regioes__report->DtlRecordCount;
	if ($rel_empresas_regioes__report->RecordExists) {
?>
	<tr>
		<td class="ewGroupHeader"><?php echo $rel_empresas_regioes_->cod_empresa->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_empresas_regioes_->nome_empresa->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_empresas_regioes_->endereco->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_empresas_regioes_->numero->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_empresas_regioes_->telefone->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_empresas_regioes_->regiao->FldCaption() ?></td>
	</tr>
<?php
	}
	while (!$rel_empresas_regioes__report->DetailRecordset->EOF) {
		$rel_empresas_regioes_->cod_empresa->setDbValue($rel_empresas_regioes__report->DetailRecordset->fields('cod_empresa'));
		$rel_empresas_regioes_->nome_empresa->setDbValue($rel_empresas_regioes__report->DetailRecordset->fields('nome_empresa'));
		$rel_empresas_regioes_->endereco->setDbValue($rel_empresas_regioes__report->DetailRecordset->fields('endereco'));
		$rel_empresas_regioes_->numero->setDbValue($rel_empresas_regioes__report->DetailRecordset->fields('numero'));
		$rel_empresas_regioes_->telefone->setDbValue($rel_empresas_regioes__report->DetailRecordset->fields('telefone'));
		$rel_empresas_regioes_->regiao->setDbValue($rel_empresas_regioes__report->DetailRecordset->fields('regiao'));

		// Render for view
		$rel_empresas_regioes_->RowType = EW_ROWTYPE_VIEW;
		$rel_empresas_regioes_->ResetAttrs();
		$rel_empresas_regioes__report->RenderRow();
?>
	<tr>
		<td<?php echo $rel_empresas_regioes_->cod_empresa->CellAttributes() ?>>
<span<?php echo $rel_empresas_regioes_->cod_empresa->ViewAttributes() ?>>
<?php echo $rel_empresas_regioes_->cod_empresa->ViewValue ?></span>
</td>
		<td<?php echo $rel_empresas_regioes_->nome_empresa->CellAttributes() ?>>
<span<?php echo $rel_empresas_regioes_->nome_empresa->ViewAttributes() ?>>
<?php echo $rel_empresas_regioes_->nome_empresa->ViewValue ?></span>
</td>
		<td<?php echo $rel_empresas_regioes_->endereco->CellAttributes() ?>>
<span<?php echo $rel_empresas_regioes_->endereco->ViewAttributes() ?>>
<?php echo $rel_empresas_regioes_->endereco->ViewValue ?></span>
</td>
		<td<?php echo $rel_empresas_regioes_->numero->CellAttributes() ?>>
<span<?php echo $rel_empresas_regioes_->numero->ViewAttributes() ?>>
<?php echo $rel_empresas_regioes_->numero->ViewValue ?></span>
</td>
		<td<?php echo $rel_empresas_regioes_->telefone->CellAttributes() ?>>
<span<?php echo $rel_empresas_regioes_->telefone->ViewAttributes() ?>>
<?php echo $rel_empresas_regioes_->telefone->ViewValue ?></span>
</td>
		<td<?php echo $rel_empresas_regioes_->regiao->CellAttributes() ?>>
<span<?php echo $rel_empresas_regioes_->regiao->ViewAttributes() ?>>
<?php echo $rel_empresas_regioes_->regiao->ViewValue ?></span>
</td>
	</tr>
<?php
		$rel_empresas_regioes__report->DetailRecordset->MoveNext();
	}
	$rel_empresas_regioes__report->DetailRecordset->Close();

	// Save old group data
	$rel_empresas_regioes__report->ReportGroups[0] = $rel_empresas_regioes_->A04imagem->CurrentValue;
	$rel_empresas_regioes__report->ReportGroups[1] = $rel_empresas_regioes_->A21EMPRESA->CurrentValue;
	$rel_empresas_regioes__report->ReportGroups[2] = $rel_empresas_regioes_->A01URL->CurrentValue;

	// Get next record
	$rel_empresas_regioes__report->Recordset->MoveNext();
	if ($rel_empresas_regioes__report->Recordset->EOF) {
		$rel_empresas_regioes__report->RecCnt = 0; // EOF, force all level breaks
	} else {
		$rel_empresas_regioes_->A04imagem->setDbValue($rel_empresas_regioes__report->Recordset->fields('A04imagem'));
		$rel_empresas_regioes_->A21EMPRESA->setDbValue($rel_empresas_regioes__report->Recordset->fields('A21EMPRESA'));
		$rel_empresas_regioes_->A01URL->setDbValue($rel_empresas_regioes__report->Recordset->fields('A01URL'));
	}
	$rel_empresas_regioes__report->ChkLvlBreak();

	// Show footers
	if ($rel_empresas_regioes__report->LevelBreak[3]) {
		$rel_empresas_regioes_->A01URL->CurrentValue = $rel_empresas_regioes__report->ReportGroups[2];

		// Render row for view
		$rel_empresas_regioes_->RowType = EW_ROWTYPE_VIEW;
		$rel_empresas_regioes_->ResetAttrs();
		$rel_empresas_regioes__report->RenderRow();
		$rel_empresas_regioes_->A01URL->CurrentValue = $rel_empresas_regioes_->A01URL->DbValue;
?>
<?php
}
	if ($rel_empresas_regioes__report->LevelBreak[2]) {
		$rel_empresas_regioes_->A21EMPRESA->CurrentValue = $rel_empresas_regioes__report->ReportGroups[1];

		// Render row for view
		$rel_empresas_regioes_->RowType = EW_ROWTYPE_VIEW;
		$rel_empresas_regioes_->ResetAttrs();
		$rel_empresas_regioes__report->RenderRow();
		$rel_empresas_regioes_->A21EMPRESA->CurrentValue = $rel_empresas_regioes_->A21EMPRESA->DbValue;
?>
<?php
}
	if ($rel_empresas_regioes__report->LevelBreak[1]) {
		$rel_empresas_regioes_->A04imagem->CurrentValue = $rel_empresas_regioes__report->ReportGroups[0];

		// Render row for view
		$rel_empresas_regioes_->RowType = EW_ROWTYPE_VIEW;
		$rel_empresas_regioes_->ResetAttrs();
		$rel_empresas_regioes__report->RenderRow();
		$rel_empresas_regioes_->A04imagem->CurrentValue = $rel_empresas_regioes_->A04imagem->DbValue;
?>
<?php
}
}

// Close recordset
$rel_empresas_regioes__report->Recordset->Close();
?>
<?php if ($rel_empresas_regioes__report->RecordExists) { ?>
	<tr><td colspan=6>&nbsp;<br></td></tr>
	<tr><td colspan=6 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($rel_empresas_regioes__report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
<?php if ($rel_empresas_regioes__report->RecordExists) { ?>
	<tr><td colspan=6>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>
<?php
$rel_empresas_regioes__report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($rel_empresas_regioes_->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$rel_empresas_regioes__report->Page_Terminate();
?>
