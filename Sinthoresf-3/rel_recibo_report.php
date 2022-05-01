<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php

// Global variable for table object
$rel_recibo_ = NULL;

//
// Table class for rel_recibo_
//
class crel_recibo_ extends cTableBase {
	var $nome_empresa;
	var $nome;
	var $ativo;
	var $A08NOME;
	var $valor_mensal;
	var $valor_extenso;
	var $cod_empresa;
	var $cod_func;
	var $referente_ao_mes;
	var $A24DATA;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'rel_recibo_';
		$this->TableName = 'rel_recibo_';
		$this->TableType = 'REPORT';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// nome_empresa
		$this->nome_empresa = new cField('rel_recibo_', 'rel_recibo_', 'x_nome_empresa', 'nome_empresa', '`nome_empresa`', '`nome_empresa`', 200, -1, FALSE, '`nome_empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nome_empresa'] = &$this->nome_empresa;

		// nome
		$this->nome = new cField('rel_recibo_', 'rel_recibo_', 'x_nome', 'nome', '`nome`', '`nome`', 200, -1, FALSE, '`nome`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nome'] = &$this->nome;

		// ativo
		$this->ativo = new cField('rel_recibo_', 'rel_recibo_', 'x_ativo', 'ativo', '`ativo`', '`ativo`', 3, -1, FALSE, '`ativo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ativo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ativo'] = &$this->ativo;

		// A08NOME
		$this->A08NOME = new cField('rel_recibo_', 'rel_recibo_', 'x_A08NOME', 'A08NOME', '`A08NOME`', '`A08NOME`', 200, -1, FALSE, '`A08NOME`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A08NOME'] = &$this->A08NOME;

		// valor_mensal
		$this->valor_mensal = new cField('rel_recibo_', 'rel_recibo_', 'x_valor_mensal', 'valor_mensal', '`valor_mensal`', '`valor_mensal`', 200, -1, FALSE, '`valor_mensal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['valor_mensal'] = &$this->valor_mensal;

		// valor_extenso
		$this->valor_extenso = new cField('rel_recibo_', 'rel_recibo_', 'x_valor_extenso', 'valor_extenso', '`valor_extenso`', '`valor_extenso`', 200, -1, FALSE, '`valor_extenso`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['valor_extenso'] = &$this->valor_extenso;

		// cod_empresa
		$this->cod_empresa = new cField('rel_recibo_', 'rel_recibo_', 'x_cod_empresa', 'cod_empresa', '`cod_empresa`', '`cod_empresa`', 3, -1, FALSE, '`cod_empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cod_empresa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cod_empresa'] = &$this->cod_empresa;

		// cod_func
		$this->cod_func = new cField('rel_recibo_', 'rel_recibo_', 'x_cod_func', 'cod_func', '`cod_func`', '`cod_func`', 3, -1, FALSE, '`cod_func`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cod_func->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cod_func'] = &$this->cod_func;

		// referente_ao_mes
		$this->referente_ao_mes = new cField('rel_recibo_', 'rel_recibo_', 'x_referente_ao_mes', 'referente_ao_mes', '`referente_ao_mes`', '`referente_ao_mes`', 201, -1, FALSE, '`referente_ao_mes`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['referente_ao_mes'] = &$this->referente_ao_mes;

		// A24DATA
		$this->A24DATA = new cField('rel_recibo_', 'rel_recibo_', 'x_A24DATA', 'A24DATA', '`A24DATA`', '`A24DATA`', 201, -1, FALSE, '`A24DATA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['A24DATA'] = &$this->A24DATA;
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
		return ($this->_SqlDetailSelect <> "") ? $this->_SqlDetailSelect : "SELECT * FROM `view_func`";
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
			return "rel_recibo_report.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "rel_recibo_report.php";
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
		if (!is_null($this->cod_func->CurrentValue)) {
			$sUrl .= "&cod_func=" . urlencode($this->cod_func->CurrentValue);
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
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET)) {
			$arKey[] = @$_GET["cod_empresa"]; // cod_empresa
			$arKey[] = @$_GET["cod_func"]; // cod_func
			$arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_array($key) || count($key) <> 2)
				continue; // Just skip so other keys will still work
			if (!is_numeric($key[0])) // cod_empresa
				continue;
			if (!is_numeric($key[1])) // cod_func
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
			$this->cod_empresa->CurrentValue = $key[0];
			$this->cod_func->CurrentValue = $key[1];
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

$rel_recibo__report = NULL; // Initialize page object first

class crel_recibo__report extends crel_recibo_ {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'rel_recibo_';

	// Page object name
	var $PageObjName = 'rel_recibo__report';

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

		// Table object (rel_recibo_)
		if (!isset($GLOBALS["rel_recibo_"]) || get_class($GLOBALS["rel_recibo_"]) == "crel_recibo_") {
			$GLOBALS["rel_recibo_"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["rel_recibo_"];
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
			define("EW_TABLE_NAME", 'rel_recibo_', TRUE);

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
		$this->ReportTotals = &ew_Init2DArray(1, 11, 0);
		$this->ReportMaxs = &ew_Init2DArray(1, 11, 0);
		$this->ReportMins = &ew_Init2DArray(1, 11, 0);

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
		// nome_empresa
		// nome
		// ativo
		// A08NOME
		// valor_mensal
		// valor_extenso
		// cod_empresa
		// cod_func
		// referente_ao_mes
		// A24DATA

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

			// cod_func
			$this->cod_func->ViewValue = $this->cod_func->CurrentValue;
			$this->cod_func->ViewCustomAttributes = "";

			// referente_ao_mes
			$this->referente_ao_mes->ViewValue = $this->referente_ao_mes->CurrentValue;
			$this->referente_ao_mes->ViewCustomAttributes = "";

			// A24DATA
			$this->A24DATA->ViewValue = $this->A24DATA->CurrentValue;
			$this->A24DATA->ViewCustomAttributes = "";

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

			// cod_func
			$this->cod_func->LinkCustomAttributes = "";
			$this->cod_func->HrefValue = "";
			$this->cod_func->TooltipValue = "";

			// referente_ao_mes
			$this->referente_ao_mes->LinkCustomAttributes = "";
			$this->referente_ao_mes->HrefValue = "";
			$this->referente_ao_mes->TooltipValue = "";

			// A24DATA
			$this->A24DATA->LinkCustomAttributes = "";
			$this->A24DATA->HrefValue = "";
			$this->A24DATA->TooltipValue = "";
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
if (!isset($rel_recibo__report)) $rel_recibo__report = new crel_recibo__report();

// Page init
$rel_recibo__report->Page_Init();

// Page main
$rel_recibo__report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$rel_recibo__report->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($rel_recibo_->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<div class="ewToolbar">
<?php if ($rel_recibo_->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($rel_recibo_->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
$rel_recibo__report->RecCnt = 1; // No grouping
if ($rel_recibo__report->DbDetailFilter <> "") {
	if ($rel_recibo__report->ReportFilter <> "") $rel_recibo__report->ReportFilter .= " AND ";
	$rel_recibo__report->ReportFilter .= "(" . $rel_recibo__report->DbDetailFilter . ")";
}

// Set up detail SQL
$rel_recibo_->CurrentFilter = $rel_recibo__report->ReportFilter;
$rel_recibo__report->ReportSql = $rel_recibo_->DetailSQL();

// Load recordset
$rel_recibo__report->Recordset = $conn->Execute($rel_recibo__report->ReportSql);
$rel_recibo__report->RecordExists = !$rel_recibo__report->Recordset->EOF;
?>
<?php if ($rel_recibo_->Export == "") { ?>
<?php if ($rel_recibo__report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $rel_recibo__report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $rel_recibo__report->ShowPageHeader(); ?>
<!--<table class="ewReportTable">-->
<?php

	// Get detail records
	$rel_recibo__report->ReportFilter = $rel_recibo__report->DefaultFilter;
	if ($rel_recibo__report->DbDetailFilter <> "") {
		if ($rel_recibo__report->ReportFilter <> "")
			$rel_recibo__report->ReportFilter .= " AND ";
		$rel_recibo__report->ReportFilter .= "(" . $rel_recibo__report->DbDetailFilter . ")";
	}

	// Set up detail SQL
	$rel_recibo_->CurrentFilter = $rel_recibo__report->ReportFilter;
	$rel_recibo__report->ReportSql = $rel_recibo_->DetailSQL();

	// Load detail records
	$rel_recibo__report->DetailRecordset = $conn->Execute($rel_recibo__report->ReportSql);
	$rel_recibo__report->DtlRecordCount = $rel_recibo__report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$rel_recibo__report->DetailRecordset->EOF) {
		$rel_recibo__report->RecCnt++;
	}
	if ($rel_recibo__report->RecCnt == 1) {
		$rel_recibo__report->ReportCounts[0] = 0;
	}
	$rel_recibo__report->ReportCounts[0] += $rel_recibo__report->DtlRecordCount;
	if ($rel_recibo__report->RecordExists) {
?>
<!--
	<tr>
		<td class="ewGroupHeader"><?php echo $rel_recibo_->nome_empresa->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_recibo_->nome->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_recibo_->ativo->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_recibo_->A08NOME->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_recibo_->valor_mensal->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_recibo_->valor_extenso->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_recibo_->cod_empresa->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_recibo_->cod_func->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_recibo_->referente_ao_mes->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $rel_recibo_->A24DATA->FldCaption() ?></td>
	</tr>
-->
<?php
	}
	while (!$rel_recibo__report->DetailRecordset->EOF) {
		$rel_recibo_->nome_empresa->setDbValue($rel_recibo__report->DetailRecordset->fields('nome_empresa'));
		$rel_recibo_->nome->setDbValue($rel_recibo__report->DetailRecordset->fields('nome'));
		$rel_recibo_->ativo->setDbValue($rel_recibo__report->DetailRecordset->fields('ativo'));
		$rel_recibo_->A08NOME->setDbValue($rel_recibo__report->DetailRecordset->fields('A08NOME'));
		$rel_recibo_->valor_mensal->setDbValue($rel_recibo__report->DetailRecordset->fields('valor_mensal'));
		$rel_recibo_->valor_extenso->setDbValue($rel_recibo__report->DetailRecordset->fields('valor_extenso'));
		$rel_recibo_->cod_empresa->setDbValue($rel_recibo__report->DetailRecordset->fields('cod_empresa'));
		$rel_recibo_->cod_func->setDbValue($rel_recibo__report->DetailRecordset->fields('cod_func'));
		$rel_recibo_->referente_ao_mes->setDbValue($rel_recibo__report->DetailRecordset->fields('referente_ao_mes'));
		$rel_recibo_->A24DATA->setDbValue($rel_recibo__report->DetailRecordset->fields('A24DATA'));

		// Render for view
		$rel_recibo_->RowType = EW_ROWTYPE_VIEW;
		$rel_recibo_->ResetAttrs();
		$rel_recibo__report->RenderRow();
?>
<!--
	<tr>
		<td<?php echo $rel_recibo_->nome_empresa->CellAttributes() ?>>
<span<?php echo $rel_recibo_->nome_empresa->ViewAttributes() ?>>
<?php echo $rel_recibo_->nome_empresa->ViewValue ?></span>
</td>
		<td<?php echo $rel_recibo_->nome->CellAttributes() ?>>
<span<?php echo $rel_recibo_->nome->ViewAttributes() ?>>
<?php echo $rel_recibo_->nome->ViewValue ?></span>
</td>
		<td<?php echo $rel_recibo_->ativo->CellAttributes() ?>>
<span<?php echo $rel_recibo_->ativo->ViewAttributes() ?>>
<?php echo $rel_recibo_->ativo->ViewValue ?></span>
</td>
		<td<?php echo $rel_recibo_->A08NOME->CellAttributes() ?>>
<span<?php echo $rel_recibo_->A08NOME->ViewAttributes() ?>>
<?php echo $rel_recibo_->A08NOME->ViewValue ?></span>
</td>
		<td<?php echo $rel_recibo_->valor_mensal->CellAttributes() ?>>
<span<?php echo $rel_recibo_->valor_mensal->ViewAttributes() ?>>
<?php echo $rel_recibo_->valor_mensal->ViewValue ?></span>
</td>
		<td<?php echo $rel_recibo_->valor_extenso->CellAttributes() ?>>
<span<?php echo $rel_recibo_->valor_extenso->ViewAttributes() ?>>
<?php echo $rel_recibo_->valor_extenso->ViewValue ?></span>
</td>
		<td<?php echo $rel_recibo_->cod_empresa->CellAttributes() ?>>
<span<?php echo $rel_recibo_->cod_empresa->ViewAttributes() ?>>
<?php echo $rel_recibo_->cod_empresa->ViewValue ?></span>
</td>
		<td<?php echo $rel_recibo_->cod_func->CellAttributes() ?>>
<span<?php echo $rel_recibo_->cod_func->ViewAttributes() ?>>
<?php echo $rel_recibo_->cod_func->ViewValue ?></span>
</td>
		<td<?php echo $rel_recibo_->referente_ao_mes->CellAttributes() ?>>
<span<?php echo $rel_recibo_->referente_ao_mes->ViewAttributes() ?>>
<?php echo $rel_recibo_->referente_ao_mes->ViewValue ?></span>
</td>
		<td<?php echo $rel_recibo_->A24DATA->CellAttributes() ?>>
<span<?php echo $rel_recibo_->A24DATA->ViewAttributes() ?>>
<?php echo $rel_recibo_->A24DATA->ViewValue ?></span>
</td>
	</tr>
<?php
		$rel_recibo__report->DetailRecordset->MoveNext();
	}
	$rel_recibo__report->DetailRecordset->Close();
?>
<?php if ($rel_recibo__report->RecordExists) { ?>
	<tr><td colspan=10>&nbsp;<br></td></tr>
	<tr><td colspan=10 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($rel_recibo__report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
<?php if ($rel_recibo__report->RecordExists) { ?>
	<tr><td colspan=10>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>
-->
<table align="center" border="0" cellpadding="1" cellspacing="1" height="550" width="752" style=" border-width:1px; border-color:rgb(51,51,51); border-style:solid;">
			<tbody>
				<tr>
					<td colspan="2">
						<p>
							<img alt="" src="sistema/sinthoresf-logo.png" style="width: 100px; height: 88px; float: left;" /><span style="font-family:verdana,geneva,sans-serif;"><span style="font-size: 18px;">SINDICATO DO COM&Eacute;RCIO HOTELEIRO DE</span></span></p>
						<p>
							<span style="font-family:verdana,geneva,sans-serif;"><span style="font-size: 18px;">FRANCA E REGI&Atilde;O</span></span></p>
					</td>
				</tr>
				<tr>
					<td>
						<h2>
							<span style="font-family:verdana,geneva,sans-serif;">&nbsp;Recibo</span></h2>
					</td>
					<td>
						<h2>
							<span style="font-family:verdana,geneva,sans-serif;">Valor R$ <?php echo $rel_recibo_->valor_mensal->ViewValue ?></span></h2>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<p>
							<span style="font-size:14px;"><span style="font-family: courier new,courier,monospace;">&nbsp;Recebemos de.............:&nbsp;<strong><?php echo $rel_recibo_->nome->ViewValue ?></strong></span></span></p>
						<p>
							<span style="font-size:14px;"><span style="font-family: courier new,courier,monospace;">&nbsp;Da empresa...............:&nbsp;<strong><?php echo $rel_recibo_->nome_empresa->ViewValue ?></strong></span></span></p>
						<p>
							<span style="font-size:14px;"><span style="font-family: courier new,courier,monospace;">&nbsp;A import&acirc;ncia de.........:&nbsp;<strong><?php echo $rel_recibo_->valor_extenso->ViewValue ?></strong></span></span></p>
						<p>
							<span style="font-size:14px;"><span style="font-family: courier new,courier,monospace;">&nbsp;Referente &agrave; mesalidade de:&nbsp;<strong><?php echo $rel_recibo_->referente_ao_mes->ViewValue ?></strong></span></span></p>
					</td>
				</tr>
				<tr>
					<td>
						<span style="font-family:verdana,geneva,sans-serif;">&nbsp;Para maior clareza firmamos o presente</span></td>
					<td>
						<span style="font-family:verdana,geneva,sans-serif;">Franca, <?php echo $rel_recibo_->A24DATA->ViewValue ?><span></td>
				</tr>
				<tr>
					<td colspan="2">
						<p>&nbsp;____________________________________________________</p>
						<span style="font-family:verdana,geneva,sans-serif;">&nbsp;<?php echo $rel_recibo_->A08NOME->ViewValue ?></span>
						
					</td>
				</tr>
			</tbody>
		</table>
		<br /><br />
<!--
<?php
$rel_recibo__report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($rel_recibo_->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
-->
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$rel_recibo__report->Page_Terminate();
?>
