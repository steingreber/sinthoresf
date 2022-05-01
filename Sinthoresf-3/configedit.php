<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "configinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$config_edit = NULL; // Initialize page object first

class cconfig_edit extends cconfig {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'config';

	// Page object name
	var $PageObjName = 'config_edit';

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

		// Table object (config)
		if (!isset($GLOBALS["config"]) || get_class($GLOBALS["config"]) == "cconfig") {
			$GLOBALS["config"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["config"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'config', TRUE);

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
		global $EW_EXPORT, $config;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($config);
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["A00ID"] <> "") {
			$this->A00ID->setQueryStringValue($_GET["A00ID"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->A00ID->CurrentValue == "")
			$this->Page_Terminate("configlist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("configlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->A08NOME->FldIsDetailKey) {
			$this->A08NOME->setFormValue($objForm->GetValue("x_A08NOME"));
		}
		if (!$this->A24DATA->FldIsDetailKey) {
			$this->A24DATA->setFormValue($objForm->GetValue("x_A24DATA"));
		}
		if (!$this->valor_mensal->FldIsDetailKey) {
			$this->valor_mensal->setFormValue($objForm->GetValue("x_valor_mensal"));
		}
		if (!$this->valor_extenso->FldIsDetailKey) {
			$this->valor_extenso->setFormValue($objForm->GetValue("x_valor_extenso"));
		}
		if (!$this->referente_ao_mes->FldIsDetailKey) {
			$this->referente_ao_mes->setFormValue($objForm->GetValue("x_referente_ao_mes"));
		}
		if (!$this->A00ID->FldIsDetailKey)
			$this->A00ID->setFormValue($objForm->GetValue("x_A00ID"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->A00ID->CurrentValue = $this->A00ID->FormValue;
		$this->A08NOME->CurrentValue = $this->A08NOME->FormValue;
		$this->A24DATA->CurrentValue = $this->A24DATA->FormValue;
		$this->valor_mensal->CurrentValue = $this->valor_mensal->FormValue;
		$this->valor_extenso->CurrentValue = $this->valor_extenso->FormValue;
		$this->referente_ao_mes->CurrentValue = $this->referente_ao_mes->FormValue;
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->A00ID->DbValue = $row['A00ID'];
		$this->A01TOPOIMG->DbValue = $row['A01TOPOIMG'];
		$this->A02TITULO->DbValue = $row['A02TITULO'];
		$this->A03EMAIL->DbValue = $row['A03EMAIL'];
		$this->A04COR_FONTE_SITE->DbValue = $row['A04COR_FONTE_SITE'];
		$this->A05MODELO->DbValue = $row['A05MODELO'];
		$this->A06USUARIO->DbValue = $row['A06USUARIO'];
		$this->A07SENHA->DbValue = $row['A07SENHA'];
		$this->A08NOME->DbValue = $row['A08NOME'];
		$this->A09DESCRICAO->DbValue = $row['A09DESCRICAO'];
		$this->A10PALAVRAS->DbValue = $row['A10PALAVRAS'];
		$this->A11TIPO_TOPO->DbValue = $row['A11TIPO_TOPO'];
		$this->A12TIPO_SITE->DbValue = $row['A12TIPO_SITE'];
		$this->A13ACESSOS->DbValue = $row['A13ACESSOS'];
		$this->A14ULTIMO->DbValue = $row['A14ULTIMO'];
		$this->A15ENDRECO->DbValue = $row['A15ENDRECO'];
		$this->A16CIDADE->DbValue = $row['A16CIDADE'];
		$this->A17ESTADO->DbValue = $row['A17ESTADO'];
		$this->A18CEP->DbValue = $row['A18CEP'];
		$this->A19FONE->DbValue = $row['A19FONE'];
		$this->A20CELULAR->DbValue = $row['A20CELULAR'];
		$this->A21EMPRESA->DbValue = $row['A21EMPRESA'];
		$this->A22INFORMATIVO->DbValue = $row['A22INFORMATIVO'];
		$this->A23ENQUETE->DbValue = $row['A23ENQUETE'];
		$this->A24DATA->DbValue = $row['A24DATA'];
		$this->valor_mensal->DbValue = $row['valor_mensal'];
		$this->valor_extenso->DbValue = $row['valor_extenso'];
		$this->referente_ao_mes->DbValue = $row['referente_ao_mes'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// A00ID
			$this->A00ID->ViewValue = $this->A00ID->CurrentValue;
			$this->A00ID->ViewCustomAttributes = "";

			// A08NOME
			$this->A08NOME->ViewValue = $this->A08NOME->CurrentValue;
			$this->A08NOME->ViewCustomAttributes = "";

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

			// A08NOME
			$this->A08NOME->LinkCustomAttributes = "";
			$this->A08NOME->HrefValue = "";
			$this->A08NOME->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// A08NOME
			$this->A08NOME->EditAttrs["class"] = "form-control";
			$this->A08NOME->EditCustomAttributes = "";
			$this->A08NOME->EditValue = ew_HtmlEncode($this->A08NOME->CurrentValue);
			$this->A08NOME->PlaceHolder = ew_RemoveHtml($this->A08NOME->FldCaption());

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

			// Edit refer script
			// A08NOME

			$this->A08NOME->HrefValue = "";

			// A24DATA
			$this->A24DATA->HrefValue = "";

			// valor_mensal
			$this->valor_mensal->HrefValue = "";

			// valor_extenso
			$this->valor_extenso->HrefValue = "";

			// referente_ao_mes
			$this->referente_ao_mes->HrefValue = "";
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
		if (!$this->A24DATA->FldIsDetailKey && !is_null($this->A24DATA->FormValue) && $this->A24DATA->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->A24DATA->FldCaption(), $this->A24DATA->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->A24DATA->FormValue)) {
			ew_AddMessage($gsFormError, $this->A24DATA->FldErrMsg());
		}
		if (!$this->valor_mensal->FldIsDetailKey && !is_null($this->valor_mensal->FormValue) && $this->valor_mensal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->valor_mensal->FldCaption(), $this->valor_mensal->ReqErrMsg));
		}
		if (!$this->valor_extenso->FldIsDetailKey && !is_null($this->valor_extenso->FormValue) && $this->valor_extenso->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->valor_extenso->FldCaption(), $this->valor_extenso->ReqErrMsg));
		}
		if (!$this->referente_ao_mes->FldIsDetailKey && !is_null($this->referente_ao_mes->FormValue) && $this->referente_ao_mes->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->referente_ao_mes->FldCaption(), $this->referente_ao_mes->ReqErrMsg));
		}

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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// A08NOME
			$this->A08NOME->SetDbValueDef($rsnew, $this->A08NOME->CurrentValue, NULL, $this->A08NOME->ReadOnly);

			// A24DATA
			$this->A24DATA->SetDbValueDef($rsnew, $this->A24DATA->CurrentValue, NULL, $this->A24DATA->ReadOnly);

			// valor_mensal
			$this->valor_mensal->SetDbValueDef($rsnew, $this->valor_mensal->CurrentValue, "", $this->valor_mensal->ReadOnly);

			// valor_extenso
			$this->valor_extenso->SetDbValueDef($rsnew, $this->valor_extenso->CurrentValue, "", $this->valor_extenso->ReadOnly);

			// referente_ao_mes
			$this->referente_ao_mes->SetDbValueDef($rsnew, $this->referente_ao_mes->CurrentValue, "", $this->referente_ao_mes->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "configlist.php", "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($config_edit)) $config_edit = new cconfig_edit();

// Page init
$config_edit->Page_Init();

// Page main
$config_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$config_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var config_edit = new ew_Page("config_edit");
config_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = config_edit.PageID; // For backward compatibility

// Form object
var fconfigedit = new ew_Form("fconfigedit");

// Validate form
fconfigedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_A24DATA");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $config->A24DATA->FldCaption(), $config->A24DATA->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_A24DATA");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($config->A24DATA->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_valor_mensal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $config->valor_mensal->FldCaption(), $config->valor_mensal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_valor_extenso");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $config->valor_extenso->FldCaption(), $config->valor_extenso->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_referente_ao_mes");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $config->referente_ao_mes->FldCaption(), $config->referente_ao_mes->ReqErrMsg)) ?>");

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
fconfigedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fconfigedit.ValidateRequired = true;
<?php } else { ?>
fconfigedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
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
<?php $config_edit->ShowPageHeader(); ?>
<?php
$config_edit->ShowMessage();
?>
<form name="fconfigedit" id="fconfigedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($config_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $config_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="config">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($config->A08NOME->Visible) { // A08NOME ?>
	<div id="r_A08NOME" class="form-group">
		<label id="elh_config_A08NOME" for="x_A08NOME" class="col-sm-2 control-label ewLabel"><?php echo $config->A08NOME->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $config->A08NOME->CellAttributes() ?>>
<span id="el_config_A08NOME">
<input type="text" data-field="x_A08NOME" name="x_A08NOME" id="x_A08NOME" size="50" maxlength="50" placeholder="<?php echo ew_HtmlEncode($config->A08NOME->PlaceHolder) ?>" value="<?php echo $config->A08NOME->EditValue ?>"<?php echo $config->A08NOME->EditAttributes() ?>>
</span>
<?php echo $config->A08NOME->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($config->A24DATA->Visible) { // A24DATA ?>
	<div id="r_A24DATA" class="form-group">
		<label id="elh_config_A24DATA" for="x_A24DATA" class="col-sm-2 control-label ewLabel"><?php echo $config->A24DATA->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $config->A24DATA->CellAttributes() ?>>
<span id="el_config_A24DATA">
<input type="text" data-field="x_A24DATA" name="x_A24DATA" id="x_A24DATA" placeholder="<?php echo ew_HtmlEncode($config->A24DATA->PlaceHolder) ?>" value="<?php echo $config->A24DATA->EditValue ?>"<?php echo $config->A24DATA->EditAttributes() ?>>
<?php if (!$config->A24DATA->ReadOnly && !$config->A24DATA->Disabled && !isset($config->A24DATA->EditAttrs["readonly"]) && !isset($config->A24DATA->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fconfigedit", "x_A24DATA", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $config->A24DATA->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($config->valor_mensal->Visible) { // valor_mensal ?>
	<div id="r_valor_mensal" class="form-group">
		<label id="elh_config_valor_mensal" for="x_valor_mensal" class="col-sm-2 control-label ewLabel"><?php echo $config->valor_mensal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $config->valor_mensal->CellAttributes() ?>>
<span id="el_config_valor_mensal">
<input type="text" data-field="x_valor_mensal" name="x_valor_mensal" id="x_valor_mensal" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($config->valor_mensal->PlaceHolder) ?>" value="<?php echo $config->valor_mensal->EditValue ?>"<?php echo $config->valor_mensal->EditAttributes() ?>>
</span>
<?php echo $config->valor_mensal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($config->valor_extenso->Visible) { // valor_extenso ?>
	<div id="r_valor_extenso" class="form-group">
		<label id="elh_config_valor_extenso" for="x_valor_extenso" class="col-sm-2 control-label ewLabel"><?php echo $config->valor_extenso->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $config->valor_extenso->CellAttributes() ?>>
<span id="el_config_valor_extenso">
<input type="text" data-field="x_valor_extenso" name="x_valor_extenso" id="x_valor_extenso" size="50" maxlength="200" placeholder="<?php echo ew_HtmlEncode($config->valor_extenso->PlaceHolder) ?>" value="<?php echo $config->valor_extenso->EditValue ?>"<?php echo $config->valor_extenso->EditAttributes() ?>>
</span>
<?php echo $config->valor_extenso->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($config->referente_ao_mes->Visible) { // referente_ao_mes ?>
	<div id="r_referente_ao_mes" class="form-group">
		<label id="elh_config_referente_ao_mes" for="x_referente_ao_mes" class="col-sm-2 control-label ewLabel"><?php echo $config->referente_ao_mes->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $config->referente_ao_mes->CellAttributes() ?>>
<span id="el_config_referente_ao_mes">
<input type="text" data-field="x_referente_ao_mes" name="x_referente_ao_mes" id="x_referente_ao_mes" size="50" maxlength="200" placeholder="<?php echo ew_HtmlEncode($config->referente_ao_mes->PlaceHolder) ?>" value="<?php echo $config->referente_ao_mes->EditValue ?>"<?php echo $config->referente_ao_mes->EditAttributes() ?>>
</span>
<?php echo $config->referente_ao_mes->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-field="x_A00ID" name="x_A00ID" id="x_A00ID" value="<?php echo ew_HtmlEncode($config->A00ID->CurrentValue) ?>">
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fconfigedit.Init();
</script>
<?php
$config_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$config_edit->Page_Terminate();
?>
