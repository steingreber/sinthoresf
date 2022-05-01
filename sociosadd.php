<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "sociosinfo.php" ?>
<?php include_once "permissoesinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$socios_add = NULL; // Initialize page object first

class csocios_add extends csocios {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'socios';

	// Page object name
	var $PageObjName = 'socios_add';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
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
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
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

		// Table object (socios)
		if (!isset($GLOBALS["socios"]) || get_class($GLOBALS["socios"]) == "csocios") {
			$GLOBALS["socios"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["socios"];
		}

		// Table object (permissoes)
		if (!isset($GLOBALS['permissoes'])) $GLOBALS['permissoes'] = new cpermissoes();

		// User table object (permissoes)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cpermissoes();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'socios', TRUE);

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
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $socios;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($socios);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["cod_socio"] != "") {
				$this->cod_socio->setQueryStringValue($_GET["cod_socio"]);
				$this->setKey("cod_socio", $this->cod_socio->CurrentValue); // Set up key
			} else {
				$this->setKey("cod_socio", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Set up detail parameters
		$this->SetUpDetailParms();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("socioslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "sociosview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->foto->Upload->Index = $objForm->Index;
		$this->foto->Upload->UploadFile();
		$this->foto->CurrentValue = $this->foto->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->socio->CurrentValue = NULL;
		$this->socio->OldValue = $this->socio->CurrentValue;
		$this->cod_empresa->CurrentValue = NULL;
		$this->cod_empresa->OldValue = $this->cod_empresa->CurrentValue;
		$this->dt_cadastro->CurrentValue = date('d/m/Y');
		$this->validade->CurrentValue = date('d/m/Y', strtotime('+182 days'));
		$this->ativo->CurrentValue = 1;
		$this->funcao->CurrentValue = NULL;
		$this->funcao->OldValue = $this->funcao->CurrentValue;
		$this->dt_carteira->CurrentValue = NULL;
		$this->dt_carteira->OldValue = $this->dt_carteira->CurrentValue;
		$this->dt_entrou_empresa->CurrentValue = NULL;
		$this->dt_entrou_empresa->OldValue = $this->dt_entrou_empresa->CurrentValue;
		$this->dt_entrou_categoria->CurrentValue = NULL;
		$this->dt_entrou_categoria->OldValue = $this->dt_entrou_categoria->CurrentValue;
		$this->acompanhante->CurrentValue = NULL;
		$this->acompanhante->OldValue = $this->acompanhante->CurrentValue;
		$this->dependentes->CurrentValue = NULL;
		$this->dependentes->OldValue = $this->dependentes->CurrentValue;
		$this->foto->Upload->DbValue = NULL;
		$this->foto->OldValue = $this->foto->Upload->DbValue;
		$this->foto->CurrentValue = NULL; // Clear file related field
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->socio->FldIsDetailKey) {
			$this->socio->setFormValue($objForm->GetValue("x_socio"));
		}
		if (!$this->cod_empresa->FldIsDetailKey) {
			$this->cod_empresa->setFormValue($objForm->GetValue("x_cod_empresa"));
		}
		if (!$this->dt_cadastro->FldIsDetailKey) {
			$this->dt_cadastro->setFormValue($objForm->GetValue("x_dt_cadastro"));
			$this->dt_cadastro->CurrentValue = ew_UnFormatDateTime($this->dt_cadastro->CurrentValue, 7);
		}
		if (!$this->validade->FldIsDetailKey) {
			$this->validade->setFormValue($objForm->GetValue("x_validade"));
			$this->validade->CurrentValue = ew_UnFormatDateTime($this->validade->CurrentValue, 7);
		}
		if (!$this->ativo->FldIsDetailKey) {
			$this->ativo->setFormValue($objForm->GetValue("x_ativo"));
		}
		if (!$this->funcao->FldIsDetailKey) {
			$this->funcao->setFormValue($objForm->GetValue("x_funcao"));
		}
		if (!$this->dt_carteira->FldIsDetailKey) {
			$this->dt_carteira->setFormValue($objForm->GetValue("x_dt_carteira"));
			$this->dt_carteira->CurrentValue = ew_UnFormatDateTime($this->dt_carteira->CurrentValue, 7);
		}
		if (!$this->dt_entrou_empresa->FldIsDetailKey) {
			$this->dt_entrou_empresa->setFormValue($objForm->GetValue("x_dt_entrou_empresa"));
			$this->dt_entrou_empresa->CurrentValue = ew_UnFormatDateTime($this->dt_entrou_empresa->CurrentValue, 7);
		}
		if (!$this->dt_entrou_categoria->FldIsDetailKey) {
			$this->dt_entrou_categoria->setFormValue($objForm->GetValue("x_dt_entrou_categoria"));
			$this->dt_entrou_categoria->CurrentValue = ew_UnFormatDateTime($this->dt_entrou_categoria->CurrentValue, 7);
		}
		if (!$this->acompanhante->FldIsDetailKey) {
			$this->acompanhante->setFormValue($objForm->GetValue("x_acompanhante"));
		}
		if (!$this->dependentes->FldIsDetailKey) {
			$this->dependentes->setFormValue($objForm->GetValue("x_dependentes"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->socio->CurrentValue = $this->socio->FormValue;
		$this->cod_empresa->CurrentValue = $this->cod_empresa->FormValue;
		$this->dt_cadastro->CurrentValue = $this->dt_cadastro->FormValue;
		$this->dt_cadastro->CurrentValue = ew_UnFormatDateTime($this->dt_cadastro->CurrentValue, 7);
		$this->validade->CurrentValue = $this->validade->FormValue;
		$this->validade->CurrentValue = ew_UnFormatDateTime($this->validade->CurrentValue, 7);
		$this->ativo->CurrentValue = $this->ativo->FormValue;
		$this->funcao->CurrentValue = $this->funcao->FormValue;
		$this->dt_carteira->CurrentValue = $this->dt_carteira->FormValue;
		$this->dt_carteira->CurrentValue = ew_UnFormatDateTime($this->dt_carteira->CurrentValue, 7);
		$this->dt_entrou_empresa->CurrentValue = $this->dt_entrou_empresa->FormValue;
		$this->dt_entrou_empresa->CurrentValue = ew_UnFormatDateTime($this->dt_entrou_empresa->CurrentValue, 7);
		$this->dt_entrou_categoria->CurrentValue = $this->dt_entrou_categoria->FormValue;
		$this->dt_entrou_categoria->CurrentValue = ew_UnFormatDateTime($this->dt_entrou_categoria->CurrentValue, 7);
		$this->acompanhante->CurrentValue = $this->acompanhante->FormValue;
		$this->dependentes->CurrentValue = $this->dependentes->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->cod_socio->setDbValue($rs->fields('cod_socio'));
		$this->socio->setDbValue($rs->fields('socio'));
		if (array_key_exists('EV__socio', $rs->fields)) {
			$this->socio->VirtualValue = $rs->fields('EV__socio'); // Set up virtual field value
		} else {
			$this->socio->VirtualValue = ""; // Clear value
		}
		$this->cod_empresa->setDbValue($rs->fields('cod_empresa'));
		if (array_key_exists('EV__cod_empresa', $rs->fields)) {
			$this->cod_empresa->VirtualValue = $rs->fields('EV__cod_empresa'); // Set up virtual field value
		} else {
			$this->cod_empresa->VirtualValue = ""; // Clear value
		}
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
		$this->foto->CurrentValue = $this->foto->Upload->DbValue;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->cod_socio->DbValue = $row['cod_socio'];
		$this->socio->DbValue = $row['socio'];
		$this->cod_empresa->DbValue = $row['cod_empresa'];
		$this->dt_cadastro->DbValue = $row['dt_cadastro'];
		$this->validade->DbValue = $row['validade'];
		$this->ativo->DbValue = $row['ativo'];
		$this->funcao->DbValue = $row['funcao'];
		$this->dt_carteira->DbValue = $row['dt_carteira'];
		$this->dt_entrou_empresa->DbValue = $row['dt_entrou_empresa'];
		$this->dt_entrou_categoria->DbValue = $row['dt_entrou_categoria'];
		$this->acompanhante->DbValue = $row['acompanhante'];
		$this->dependentes->DbValue = $row['dependentes'];
		$this->foto->Upload->DbValue = $row['foto'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("cod_socio")) <> "")
			$this->cod_socio->CurrentValue = $this->getKey("cod_socio"); // cod_socio
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// socio
			$this->socio->EditAttrs["class"] = "form-control";
			$this->socio->EditCustomAttributes = "";
			if (trim(strval($this->socio->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`cod_pessoa`" . ew_SearchString("=", $this->socio->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `cod_pessoa`, `nome` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `pessoas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->socio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nome` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->socio->EditValue = $arwrk;

			// cod_empresa
			$this->cod_empresa->EditAttrs["class"] = "form-control";
			$this->cod_empresa->EditCustomAttributes = "";
			if (trim(strval($this->cod_empresa->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`cod_empresa`" . ew_SearchString("=", $this->cod_empresa->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `empresas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->cod_empresa, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nome_empresa` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->cod_empresa->EditValue = $arwrk;

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
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->foto);

			// Edit refer script
			// socio

			if (!ew_Empty($this->socio->CurrentValue)) {
				$this->socio->HrefValue = "pessoaslist.php?cmd=search&t=pessoas&psearch=" . ((!empty($this->socio->EditValue)) ? $this->socio->EditValue : $this->socio->CurrentValue) . "&psearchtype="; // Add prefix/suffix
				$this->socio->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->socio->HrefValue = ew_ConvertFullUrl($this->socio->HrefValue);
			} else {
				$this->socio->HrefValue = "";
			}

			// cod_empresa
			if (!ew_Empty($this->cod_empresa->CurrentValue)) {
				$this->cod_empresa->HrefValue = "empresaslist.php?x_nome_empresa=" . ((!empty($this->cod_empresa->EditValue)) ? $this->cod_empresa->EditValue : $this->cod_empresa->CurrentValue) . "&z_nome_empresa=LIKE&cmd=search"; // Add prefix/suffix
				$this->cod_empresa->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->cod_empresa->HrefValue = ew_ConvertFullUrl($this->cod_empresa->HrefValue);
			} else {
				$this->cod_empresa->HrefValue = "";
			}

			// dt_cadastro
			$this->dt_cadastro->HrefValue = "";

			// validade
			$this->validade->HrefValue = "";

			// ativo
			$this->ativo->HrefValue = "";

			// funcao
			$this->funcao->HrefValue = "";

			// dt_carteira
			$this->dt_carteira->HrefValue = "";

			// dt_entrou_empresa
			$this->dt_entrou_empresa->HrefValue = "";

			// dt_entrou_categoria
			$this->dt_entrou_categoria->HrefValue = "";

			// acompanhante
			$this->acompanhante->HrefValue = "";

			// dependentes
			$this->dependentes->HrefValue = "";

			// foto
			$this->foto->UploadPath = sistema;
			if (!ew_Empty($this->foto->Upload->DbValue)) {
				$this->foto->HrefValue = ew_UploadPathEx(FALSE, $this->foto->UploadPath) . $this->foto->Upload->DbValue; // Add prefix/suffix
				$this->foto->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->foto->HrefValue = ew_ConvertFullUrl($this->foto->HrefValue);
			} else {
				$this->foto->HrefValue = "";
			}
			$this->foto->HrefValue2 = $this->foto->UploadPath . $this->foto->Upload->DbValue;
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->socio->FldIsDetailKey && !is_null($this->socio->FormValue) && $this->socio->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->socio->FldCaption(), $this->socio->ReqErrMsg));
		}
		if (!$this->cod_empresa->FldIsDetailKey && !is_null($this->cod_empresa->FormValue) && $this->cod_empresa->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cod_empresa->FldCaption(), $this->cod_empresa->ReqErrMsg));
		}
		if (!$this->dt_cadastro->FldIsDetailKey && !is_null($this->dt_cadastro->FormValue) && $this->dt_cadastro->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dt_cadastro->FldCaption(), $this->dt_cadastro->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->dt_cadastro->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_cadastro->FldErrMsg());
		}
		if (!$this->validade->FldIsDetailKey && !is_null($this->validade->FormValue) && $this->validade->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->validade->FldCaption(), $this->validade->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->validade->FormValue)) {
			ew_AddMessage($gsFormError, $this->validade->FldErrMsg());
		}
		if ($this->ativo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ativo->FldCaption(), $this->ativo->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->dt_carteira->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_carteira->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->dt_entrou_empresa->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_entrou_empresa->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->dt_entrou_categoria->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_entrou_categoria->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
			$this->foto->OldUploadPath = sistema;
			$this->foto->UploadPath = $this->foto->OldUploadPath;
		}
		$rsnew = array();

		// socio
		$this->socio->SetDbValueDef($rsnew, $this->socio->CurrentValue, 0, FALSE);

		// cod_empresa
		$this->cod_empresa->SetDbValueDef($rsnew, $this->cod_empresa->CurrentValue, 0, FALSE);

		// dt_cadastro
		$this->dt_cadastro->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_cadastro->CurrentValue, 7), NULL, FALSE);

		// validade
		$this->validade->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->validade->CurrentValue, 7), NULL, FALSE);

		// ativo
		$this->ativo->SetDbValueDef($rsnew, $this->ativo->CurrentValue, 0, strval($this->ativo->CurrentValue) == "");

		// funcao
		$this->funcao->SetDbValueDef($rsnew, $this->funcao->CurrentValue, NULL, FALSE);

		// dt_carteira
		$this->dt_carteira->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_carteira->CurrentValue, 7), NULL, FALSE);

		// dt_entrou_empresa
		$this->dt_entrou_empresa->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_entrou_empresa->CurrentValue, 7), NULL, FALSE);

		// dt_entrou_categoria
		$this->dt_entrou_categoria->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_entrou_categoria->CurrentValue, 7), NULL, FALSE);

		// acompanhante
		$this->acompanhante->SetDbValueDef($rsnew, $this->acompanhante->CurrentValue, NULL, FALSE);

		// dependentes
		$this->dependentes->SetDbValueDef($rsnew, $this->dependentes->CurrentValue, NULL, FALSE);

		// foto
		if (!$this->foto->Upload->KeepFile) {
			$this->foto->Upload->DbValue = ""; // No need to delete old file
			if ($this->foto->Upload->FileName == "") {
				$rsnew['foto'] = NULL;
			} else {
				$rsnew['foto'] = $this->foto->Upload->FileName;
			}
			$this->foto->ImageWidth = 130; // Resize width
			$this->foto->ImageHeight = 141; // Resize height
		}
		if (!$this->foto->Upload->KeepFile) {
			$this->foto->UploadPath = sistema;
			if (!ew_Empty($this->foto->Upload->Value)) {
				$rsnew['foto'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->foto->UploadPath), $rsnew['foto']); // Get new file name
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if (!$this->foto->Upload->KeepFile) {
					if (!ew_Empty($this->foto->Upload->Value)) {
						$this->foto->Upload->Resize($this->foto->ImageWidth, $this->foto->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY);
						$this->foto->Upload->SaveToFile($this->foto->UploadPath, $rsnew['foto'], TRUE);
					}
				}
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->cod_socio->setDbValue($conn->Insert_ID());
			$rsnew['cod_socio'] = $this->cod_socio->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// foto
		ew_CleanUploadTempPath($this->foto, $this->foto->Upload->Index);
		return $AddRow;
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "socioslist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($socios_add)) $socios_add = new csocios_add();

// Page init
$socios_add->Page_Init();

// Page main
$socios_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$socios_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var socios_add = new ew_Page("socios_add");
socios_add.PageID = "add"; // Page ID
var EW_PAGE_ID = socios_add.PageID; // For backward compatibility

// Form object
var fsociosadd = new ew_Form("fsociosadd");

// Validate form
fsociosadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_socio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->socio->FldCaption(), $socios->socio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cod_empresa");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->cod_empresa->FldCaption(), $socios->cod_empresa->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dt_cadastro");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->dt_cadastro->FldCaption(), $socios->dt_cadastro->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dt_cadastro");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios->dt_cadastro->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_validade");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->validade->FldCaption(), $socios->validade->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_validade");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios->validade->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ativo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->ativo->FldCaption(), $socios->ativo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dt_carteira");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios->dt_carteira->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dt_entrou_empresa");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios->dt_entrou_empresa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dt_entrou_categoria");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios->dt_entrou_categoria->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fsociosadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsociosadd.ValidateRequired = true;
<?php } else { ?>
fsociosadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsociosadd.Lists["x_socio"] = {"LinkField":"x_cod_pessoa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsociosadd.Lists["x_cod_empresa"] = {"LinkField":"x_cod_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_empresa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $socios_add->ShowPageHeader(); ?>
<?php
$socios_add->ShowMessage();
?>
<form name="fsociosadd" id="fsociosadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($socios_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $socios_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="socios">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($socios->socio->Visible) { // socio ?>
	<div id="r_socio" class="form-group">
		<label id="elh_socios_socio" for="x_socio" class="col-sm-2 control-label ewLabel"><?php echo $socios->socio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $socios->socio->CellAttributes() ?>>
<span id="el_socios_socio">
<select data-field="x_socio" id="x_socio" name="x_socio"<?php echo $socios->socio->EditAttributes() ?>>
<?php
if (is_array($socios->socio->EditValue)) {
	$arwrk = $socios->socio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->socio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $socios->socio->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_socio',url:'pessoasaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_socio"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $socios->socio->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `cod_pessoa`, `nome` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pessoas`";
$sWhereWrk = "";

// Call Lookup selecting
$socios->Lookup_Selecting($socios->socio, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `nome` ASC";
?>
<input type="hidden" name="s_x_socio" id="s_x_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`cod_pessoa` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $socios->socio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->cod_empresa->Visible) { // cod_empresa ?>
	<div id="r_cod_empresa" class="form-group">
		<label id="elh_socios_cod_empresa" for="x_cod_empresa" class="col-sm-2 control-label ewLabel"><?php echo $socios->cod_empresa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $socios->cod_empresa->CellAttributes() ?>>
<span id="el_socios_cod_empresa">
<select data-field="x_cod_empresa" id="x_cod_empresa" name="x_cod_empresa"<?php echo $socios->cod_empresa->EditAttributes() ?>>
<?php
if (is_array($socios->cod_empresa->EditValue)) {
	$arwrk = $socios->cod_empresa->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->cod_empresa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $socios->cod_empresa->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_cod_empresa',url:'empresasaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_cod_empresa"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $socios->cod_empresa->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
$sWhereWrk = "";

// Call Lookup selecting
$socios->Lookup_Selecting($socios->cod_empresa, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `nome_empresa` ASC";
?>
<input type="hidden" name="s_x_cod_empresa" id="s_x_cod_empresa" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`cod_empresa` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $socios->cod_empresa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->dt_cadastro->Visible) { // dt_cadastro ?>
	<div id="r_dt_cadastro" class="form-group">
		<label id="elh_socios_dt_cadastro" for="x_dt_cadastro" class="col-sm-2 control-label ewLabel"><?php echo $socios->dt_cadastro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $socios->dt_cadastro->CellAttributes() ?>>
<span id="el_socios_dt_cadastro">
<input type="text" data-field="x_dt_cadastro" name="x_dt_cadastro" id="x_dt_cadastro" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->dt_cadastro->PlaceHolder) ?>" value="<?php echo $socios->dt_cadastro->EditValue ?>"<?php echo $socios->dt_cadastro->EditAttributes() ?>>
<?php if (!$socios->dt_cadastro->ReadOnly && !$socios->dt_cadastro->Disabled && !isset($socios->dt_cadastro->EditAttrs["readonly"]) && !isset($socios->dt_cadastro->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsociosadd", "x_dt_cadastro", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $socios->dt_cadastro->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->validade->Visible) { // validade ?>
	<div id="r_validade" class="form-group">
		<label id="elh_socios_validade" for="x_validade" class="col-sm-2 control-label ewLabel"><?php echo $socios->validade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $socios->validade->CellAttributes() ?>>
<span id="el_socios_validade">
<input type="text" data-field="x_validade" name="x_validade" id="x_validade" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->validade->PlaceHolder) ?>" value="<?php echo $socios->validade->EditValue ?>"<?php echo $socios->validade->EditAttributes() ?>>
<?php if (!$socios->validade->ReadOnly && !$socios->validade->Disabled && !isset($socios->validade->EditAttrs["readonly"]) && !isset($socios->validade->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsociosadd", "x_validade", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $socios->validade->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->ativo->Visible) { // ativo ?>
	<div id="r_ativo" class="form-group">
		<label id="elh_socios_ativo" class="col-sm-2 control-label ewLabel"><?php echo $socios->ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $socios->ativo->CellAttributes() ?>>
<span id="el_socios_ativo">
<div id="tp_x_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ativo" id="x_ativo" value="{value}"<?php echo $socios->ativo->EditAttributes() ?>></div>
<div id="dsl_x_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $socios->ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_ativo" name="x_ativo" id="x_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $socios->ativo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->funcao->Visible) { // funcao ?>
	<div id="r_funcao" class="form-group">
		<label id="elh_socios_funcao" for="x_funcao" class="col-sm-2 control-label ewLabel"><?php echo $socios->funcao->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $socios->funcao->CellAttributes() ?>>
<span id="el_socios_funcao">
<input type="text" data-field="x_funcao" name="x_funcao" id="x_funcao" size="50" maxlength="50" placeholder="<?php echo ew_HtmlEncode($socios->funcao->PlaceHolder) ?>" value="<?php echo $socios->funcao->EditValue ?>"<?php echo $socios->funcao->EditAttributes() ?>>
</span>
<?php echo $socios->funcao->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->dt_carteira->Visible) { // dt_carteira ?>
	<div id="r_dt_carteira" class="form-group">
		<label id="elh_socios_dt_carteira" for="x_dt_carteira" class="col-sm-2 control-label ewLabel"><?php echo $socios->dt_carteira->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $socios->dt_carteira->CellAttributes() ?>>
<span id="el_socios_dt_carteira">
<input type="text" data-field="x_dt_carteira" name="x_dt_carteira" id="x_dt_carteira" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->dt_carteira->PlaceHolder) ?>" value="<?php echo $socios->dt_carteira->EditValue ?>"<?php echo $socios->dt_carteira->EditAttributes() ?>>
<?php if (!$socios->dt_carteira->ReadOnly && !$socios->dt_carteira->Disabled && !isset($socios->dt_carteira->EditAttrs["readonly"]) && !isset($socios->dt_carteira->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsociosadd", "x_dt_carteira", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $socios->dt_carteira->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->dt_entrou_empresa->Visible) { // dt_entrou_empresa ?>
	<div id="r_dt_entrou_empresa" class="form-group">
		<label id="elh_socios_dt_entrou_empresa" for="x_dt_entrou_empresa" class="col-sm-2 control-label ewLabel"><?php echo $socios->dt_entrou_empresa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $socios->dt_entrou_empresa->CellAttributes() ?>>
<span id="el_socios_dt_entrou_empresa">
<input type="text" data-field="x_dt_entrou_empresa" name="x_dt_entrou_empresa" id="x_dt_entrou_empresa" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->dt_entrou_empresa->PlaceHolder) ?>" value="<?php echo $socios->dt_entrou_empresa->EditValue ?>"<?php echo $socios->dt_entrou_empresa->EditAttributes() ?>>
<?php if (!$socios->dt_entrou_empresa->ReadOnly && !$socios->dt_entrou_empresa->Disabled && !isset($socios->dt_entrou_empresa->EditAttrs["readonly"]) && !isset($socios->dt_entrou_empresa->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsociosadd", "x_dt_entrou_empresa", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $socios->dt_entrou_empresa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->dt_entrou_categoria->Visible) { // dt_entrou_categoria ?>
	<div id="r_dt_entrou_categoria" class="form-group">
		<label id="elh_socios_dt_entrou_categoria" for="x_dt_entrou_categoria" class="col-sm-2 control-label ewLabel"><?php echo $socios->dt_entrou_categoria->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $socios->dt_entrou_categoria->CellAttributes() ?>>
<span id="el_socios_dt_entrou_categoria">
<input type="text" data-field="x_dt_entrou_categoria" name="x_dt_entrou_categoria" id="x_dt_entrou_categoria" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->dt_entrou_categoria->PlaceHolder) ?>" value="<?php echo $socios->dt_entrou_categoria->EditValue ?>"<?php echo $socios->dt_entrou_categoria->EditAttributes() ?>>
<?php if (!$socios->dt_entrou_categoria->ReadOnly && !$socios->dt_entrou_categoria->Disabled && !isset($socios->dt_entrou_categoria->EditAttrs["readonly"]) && !isset($socios->dt_entrou_categoria->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsociosadd", "x_dt_entrou_categoria", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $socios->dt_entrou_categoria->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->acompanhante->Visible) { // acompanhante ?>
	<div id="r_acompanhante" class="form-group">
		<label id="elh_socios_acompanhante" for="x_acompanhante" class="col-sm-2 control-label ewLabel"><?php echo $socios->acompanhante->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $socios->acompanhante->CellAttributes() ?>>
<span id="el_socios_acompanhante">
<textarea data-field="x_acompanhante" name="x_acompanhante" id="x_acompanhante" cols="60" rows="4" placeholder="<?php echo ew_HtmlEncode($socios->acompanhante->PlaceHolder) ?>"<?php echo $socios->acompanhante->EditAttributes() ?>><?php echo $socios->acompanhante->EditValue ?></textarea>
</span>
<?php echo $socios->acompanhante->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->dependentes->Visible) { // dependentes ?>
	<div id="r_dependentes" class="form-group">
		<label id="elh_socios_dependentes" for="x_dependentes" class="col-sm-2 control-label ewLabel"><?php echo $socios->dependentes->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $socios->dependentes->CellAttributes() ?>>
<span id="el_socios_dependentes">
<textarea data-field="x_dependentes" name="x_dependentes" id="x_dependentes" cols="60" rows="4" placeholder="<?php echo ew_HtmlEncode($socios->dependentes->PlaceHolder) ?>"<?php echo $socios->dependentes->EditAttributes() ?>><?php echo $socios->dependentes->EditValue ?></textarea>
</span>
<?php echo $socios->dependentes->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->foto->Visible) { // foto ?>
	<div id="r_foto" class="form-group">
		<label id="elh_socios_foto" class="col-sm-2 control-label ewLabel"><?php echo $socios->foto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $socios->foto->CellAttributes() ?>>
<span id="el_socios_foto">
<div id="fd_x_foto">
<span title="<?php echo $socios->foto->FldTitle() ? $socios->foto->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($socios->foto->ReadOnly || $socios->foto->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_foto" name="x_foto" id="x_foto">
</span>
<input type="hidden" name="fn_x_foto" id= "fn_x_foto" value="<?php echo $socios->foto->Upload->FileName ?>">
<input type="hidden" name="fa_x_foto" id= "fa_x_foto" value="0">
<input type="hidden" name="fs_x_foto" id= "fs_x_foto" value="50">
<input type="hidden" name="fx_x_foto" id= "fx_x_foto" value="<?php echo $socios->foto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_foto" id= "fm_x_foto" value="<?php echo $socios->foto->UploadMaxFileSize ?>">
</div>
<table id="ft_x_foto" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $socios->foto->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fsociosadd.Init();
</script>
<?php
$socios_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$socios_add->Page_Terminate();
?>
