<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "permissoesinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$default = NULL; // Initialize page object first

class cdefault {

	// Page ID
	var $PageID = 'default';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Page object name
	var $PageObjName = 'default';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		return $PageUrl;
	}

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

		// User table object (permissoes)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cpermissoes();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'default', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();

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

	//
	// Page main
	//
	function Page_Main() {
		global $Security, $Language;
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$this->Page_Terminate("inicio.php"); // Exit and go to default page
		if ($Security->AllowList(CurrentProjectID() . 'config'))
			$this->Page_Terminate("configlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'empresas'))
			$this->Page_Terminate("empresaslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'funcionarios'))
			$this->Page_Terminate("funcionarioslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'permissoes'))
			$this->Page_Terminate("permissoeslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_func'))
			$this->Page_Terminate("view_funclist.php");
		if ($Security->AllowList(CurrentProjectID() . 'rel_cart_clube_'))
			$this->Page_Terminate("rel_cart_clube_report.php");
		if ($Security->AllowList(CurrentProjectID() . 'rel_cart_socio_'))
			$this->Page_Terminate("rel_cart_socio_report.php");
		if ($Security->AllowList(CurrentProjectID() . 'rel_ficha_cadastro_'))
			$this->Page_Terminate("rel_ficha_cadastro_report.php");
		if ($Security->AllowList(CurrentProjectID() . 'rel_convite_'))
			$this->Page_Terminate("rel_convite_report.php");
		if ($Security->AllowList(CurrentProjectID() . 'rel_recibo_'))
			$this->Page_Terminate("rel_recibo_report.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_recibo_empresa'))
			$this->Page_Terminate("view_recibo_empresalist.php");
		if ($Security->AllowList(CurrentProjectID() . 'rel_recibo_empresa_'))
			$this->Page_Terminate("rel_recibo_empresa_report.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_func_empresa'))
			$this->Page_Terminate("view_func_empresalist.php");
		if ($Security->AllowList(CurrentProjectID() . 'rel_func_empresas'))
			$this->Page_Terminate("rel_func_empresasreport.php");
		if ($Security->AllowList(CurrentProjectID() . 'pessoas'))
			$this->Page_Terminate("pessoaslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'regioes'))
			$this->Page_Terminate("regioeslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_empresa_funcionarios'))
			$this->Page_Terminate("view_empresa_funcionarioslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'rel_n_empresa_funcionarios'))
			$this->Page_Terminate("rel_n_empresa_funcionariosreport.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_esmpresas_regioes'))
			$this->Page_Terminate("view_esmpresas_regioeslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'rel_empresas_regioes_'))
			$this->Page_Terminate("rel_empresas_regioes_report.php");
		if ($Security->AllowList(CurrentProjectID() . 'socios'))
			$this->Page_Terminate("socioslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_carteira_socio'))
			$this->Page_Terminate("view_carteira_sociolist.php");
		if ($Security->AllowList(CurrentProjectID() . 'rel_carteira_socio_'))
			$this->Page_Terminate("rel_carteira_socio_report.php");
		if ($Security->AllowList(CurrentProjectID() . 'rel_carteira_clube_'))
			$this->Page_Terminate("rel_carteira_clube_report.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_ficha_funcionario'))
			$this->Page_Terminate("view_ficha_funcionariolist.php");
		if ($Security->AllowList(CurrentProjectID() . 'rel_ficha_cadastral_'))
			$this->Page_Terminate("rel_ficha_cadastral_report.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_convite_festa'))
			$this->Page_Terminate("view_convite_festalist.php");
		if ($Security->AllowList(CurrentProjectID() . 'rel_convite_festa_'))
			$this->Page_Terminate("rel_convite_festa_report.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_recibo_socio'))
			$this->Page_Terminate("view_recibo_sociolist.php");
		if ($Security->AllowList(CurrentProjectID() . 'rel_recibo_socio_'))
			$this->Page_Terminate("rel_recibo_socio_report.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_socios_a_vencer'))
			$this->Page_Terminate("view_socios_a_vencerlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_socios_vencendo'))
			$this->Page_Terminate("view_socios_vencendolist.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_socios_recibo_empresa'))
			$this->Page_Terminate("view_socios_recibo_empresalist.php");
		if ($Security->AllowList(CurrentProjectID() . 'rel_socios_recibo_empresa_'))
			$this->Page_Terminate("rel_socios_recibo_empresa_report.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_lista_funcionarios'))
			$this->Page_Terminate("view_lista_funcionarioslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'rel_lista_funcionarios_'))
			$this->Page_Terminate("rel_lista_funcionarios_report.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_lista_whatsapp'))
			$this->Page_Terminate("view_lista_whatsapplist.php");
		if ($Security->IsLoggedIn()) {
			$this->setFailureMessage($Language->Phrase("NoPermission") . "<br><br><a href=\"logout.php\">" . $Language->Phrase("BackToLogin") . "</a>");
		} else {
			$this->Page_Terminate("login.php"); // Exit and go to login page
		}
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
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($default)) $default = new cdefault();

// Page init
$default->Page_Init();

// Page main
$default->Page_Main();
?>
<?php include_once "header.php" ?>
<?php
$default->ShowMessage();
?>
<?php include_once "footer.php" ?>
<?php
$default->Page_Terminate();
?>
