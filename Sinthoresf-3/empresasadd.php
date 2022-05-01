<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "empresasinfo.php" ?>
<?php include_once "permissoesinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$empresas_add = NULL; // Initialize page object first

class cempresas_add extends cempresas {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'empresas';

	// Page object name
	var $PageObjName = 'empresas_add';

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

		// Table object (empresas)
		if (!isset($GLOBALS["empresas"]) || get_class($GLOBALS["empresas"]) == "cempresas") {
			$GLOBALS["empresas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["empresas"];
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
			define("EW_TABLE_NAME", 'empresas', TRUE);

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
		global $EW_EXPORT, $empresas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($empresas);
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
			if (@$_GET["cod_empresa"] != "") {
				$this->cod_empresa->setQueryStringValue($_GET["cod_empresa"]);
				$this->setKey("cod_empresa", $this->cod_empresa->CurrentValue); // Set up key
			} else {
				$this->setKey("cod_empresa", ""); // Clear key
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
					$this->Page_Terminate("empresaslist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "empresasview.php")
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
	}

	// Load default values
	function LoadDefaultValues() {
		$this->nome_empresa->CurrentValue = NULL;
		$this->nome_empresa->OldValue = $this->nome_empresa->CurrentValue;
		$this->endereco->CurrentValue = NULL;
		$this->endereco->OldValue = $this->endereco->CurrentValue;
		$this->numero->CurrentValue = NULL;
		$this->numero->OldValue = $this->numero->CurrentValue;
		$this->bairro->CurrentValue = NULL;
		$this->bairro->OldValue = $this->bairro->CurrentValue;
		$this->telefone->CurrentValue = NULL;
		$this->telefone->OldValue = $this->telefone->CurrentValue;
		$this->cidade->CurrentValue = NULL;
		$this->cidade->OldValue = $this->cidade->CurrentValue;
		$this->cgc->CurrentValue = NULL;
		$this->cgc->OldValue = $this->cgc->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nome_empresa->FldIsDetailKey) {
			$this->nome_empresa->setFormValue($objForm->GetValue("x_nome_empresa"));
		}
		if (!$this->endereco->FldIsDetailKey) {
			$this->endereco->setFormValue($objForm->GetValue("x_endereco"));
		}
		if (!$this->numero->FldIsDetailKey) {
			$this->numero->setFormValue($objForm->GetValue("x_numero"));
		}
		if (!$this->bairro->FldIsDetailKey) {
			$this->bairro->setFormValue($objForm->GetValue("x_bairro"));
		}
		if (!$this->telefone->FldIsDetailKey) {
			$this->telefone->setFormValue($objForm->GetValue("x_telefone"));
		}
		if (!$this->cidade->FldIsDetailKey) {
			$this->cidade->setFormValue($objForm->GetValue("x_cidade"));
		}
		if (!$this->cgc->FldIsDetailKey) {
			$this->cgc->setFormValue($objForm->GetValue("x_cgc"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nome_empresa->CurrentValue = $this->nome_empresa->FormValue;
		$this->endereco->CurrentValue = $this->endereco->FormValue;
		$this->numero->CurrentValue = $this->numero->FormValue;
		$this->bairro->CurrentValue = $this->bairro->FormValue;
		$this->telefone->CurrentValue = $this->telefone->FormValue;
		$this->cidade->CurrentValue = $this->cidade->FormValue;
		$this->cgc->CurrentValue = $this->cgc->FormValue;
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
		$this->cod_empresa->setDbValue($rs->fields('cod_empresa'));
		$this->nome_empresa->setDbValue($rs->fields('nome_empresa'));
		$this->endereco->setDbValue($rs->fields('endereco'));
		$this->numero->setDbValue($rs->fields('numero'));
		$this->bairro->setDbValue($rs->fields('bairro'));
		$this->telefone->setDbValue($rs->fields('telefone'));
		$this->cidade->setDbValue($rs->fields('cidade'));
		$this->cgc->setDbValue($rs->fields('cgc'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->cod_empresa->DbValue = $row['cod_empresa'];
		$this->nome_empresa->DbValue = $row['nome_empresa'];
		$this->endereco->DbValue = $row['endereco'];
		$this->numero->DbValue = $row['numero'];
		$this->bairro->DbValue = $row['bairro'];
		$this->telefone->DbValue = $row['telefone'];
		$this->cidade->DbValue = $row['cidade'];
		$this->cgc->DbValue = $row['cgc'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("cod_empresa")) <> "")
			$this->cod_empresa->CurrentValue = $this->getKey("cod_empresa"); // cod_empresa
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
		// cod_empresa
		// nome_empresa
		// endereco
		// numero
		// bairro
		// telefone
		// cidade
		// cgc

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

			// bairro
			$this->bairro->ViewValue = $this->bairro->CurrentValue;
			$this->bairro->ViewCustomAttributes = "";

			// telefone
			$this->telefone->ViewValue = $this->telefone->CurrentValue;
			$this->telefone->ViewCustomAttributes = "";

			// cidade
			$this->cidade->ViewValue = $this->cidade->CurrentValue;
			$this->cidade->ViewCustomAttributes = "";

			// cgc
			$this->cgc->ViewValue = $this->cgc->CurrentValue;
			$this->cgc->ViewCustomAttributes = "";

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

			// bairro
			$this->bairro->LinkCustomAttributes = "";
			$this->bairro->HrefValue = "";
			$this->bairro->TooltipValue = "";

			// telefone
			$this->telefone->LinkCustomAttributes = "";
			$this->telefone->HrefValue = "";
			$this->telefone->TooltipValue = "";

			// cidade
			$this->cidade->LinkCustomAttributes = "";
			$this->cidade->HrefValue = "";
			$this->cidade->TooltipValue = "";

			// cgc
			$this->cgc->LinkCustomAttributes = "";
			$this->cgc->HrefValue = "";
			$this->cgc->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nome_empresa
			$this->nome_empresa->EditAttrs["class"] = "form-control";
			$this->nome_empresa->EditCustomAttributes = "";
			$this->nome_empresa->EditValue = ew_HtmlEncode($this->nome_empresa->CurrentValue);
			$this->nome_empresa->PlaceHolder = ew_RemoveHtml($this->nome_empresa->FldCaption());

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

			// telefone
			$this->telefone->EditAttrs["class"] = "form-control";
			$this->telefone->EditCustomAttributes = "";
			$this->telefone->EditValue = ew_HtmlEncode($this->telefone->CurrentValue);
			$this->telefone->PlaceHolder = ew_RemoveHtml($this->telefone->FldCaption());

			// cidade
			$this->cidade->EditAttrs["class"] = "form-control";
			$this->cidade->EditCustomAttributes = "";
			$this->cidade->EditValue = ew_HtmlEncode($this->cidade->CurrentValue);
			$this->cidade->PlaceHolder = ew_RemoveHtml($this->cidade->FldCaption());

			// cgc
			$this->cgc->EditAttrs["class"] = "form-control";
			$this->cgc->EditCustomAttributes = "";
			$this->cgc->EditValue = ew_HtmlEncode($this->cgc->CurrentValue);
			$this->cgc->PlaceHolder = ew_RemoveHtml($this->cgc->FldCaption());

			// Edit refer script
			// nome_empresa

			$this->nome_empresa->HrefValue = "";

			// endereco
			$this->endereco->HrefValue = "";

			// numero
			$this->numero->HrefValue = "";

			// bairro
			$this->bairro->HrefValue = "";

			// telefone
			$this->telefone->HrefValue = "";

			// cidade
			$this->cidade->HrefValue = "";

			// cgc
			$this->cgc->HrefValue = "";
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
		if (!ew_CheckInteger($this->numero->FormValue)) {
			ew_AddMessage($gsFormError, $this->numero->FldErrMsg());
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
		}
		$rsnew = array();

		// nome_empresa
		$this->nome_empresa->SetDbValueDef($rsnew, $this->nome_empresa->CurrentValue, NULL, FALSE);

		// endereco
		$this->endereco->SetDbValueDef($rsnew, $this->endereco->CurrentValue, NULL, FALSE);

		// numero
		$this->numero->SetDbValueDef($rsnew, $this->numero->CurrentValue, NULL, FALSE);

		// bairro
		$this->bairro->SetDbValueDef($rsnew, $this->bairro->CurrentValue, NULL, FALSE);

		// telefone
		$this->telefone->SetDbValueDef($rsnew, $this->telefone->CurrentValue, NULL, FALSE);

		// cidade
		$this->cidade->SetDbValueDef($rsnew, $this->cidade->CurrentValue, NULL, FALSE);

		// cgc
		$this->cgc->SetDbValueDef($rsnew, $this->cgc->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
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
			$this->cod_empresa->setDbValue($conn->Insert_ID());
			$rsnew['cod_empresa'] = $this->cod_empresa->DbValue;
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
		$Breadcrumb->Add("list", $this->TableVar, "empresaslist.php", "", $this->TableVar, TRUE);
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
if (!isset($empresas_add)) $empresas_add = new cempresas_add();

// Page init
$empresas_add->Page_Init();

// Page main
$empresas_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empresas_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var empresas_add = new ew_Page("empresas_add");
empresas_add.PageID = "add"; // Page ID
var EW_PAGE_ID = empresas_add.PageID; // For backward compatibility

// Form object
var fempresasadd = new ew_Form("fempresasadd");

// Validate form
fempresasadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_numero");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($empresas->numero->FldErrMsg()) ?>");

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
fempresasadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempresasadd.ValidateRequired = true;
<?php } else { ?>
fempresasadd.ValidateRequired = false; 
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
<?php $empresas_add->ShowPageHeader(); ?>
<?php
$empresas_add->ShowMessage();
?>
<form name="fempresasadd" id="fempresasadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($empresas_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $empresas_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="empresas">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($empresas->nome_empresa->Visible) { // nome_empresa ?>
	<div id="r_nome_empresa" class="form-group">
		<label id="elh_empresas_nome_empresa" for="x_nome_empresa" class="col-sm-2 control-label ewLabel"><?php echo $empresas->nome_empresa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->nome_empresa->CellAttributes() ?>>
<span id="el_empresas_nome_empresa">
<input type="text" data-field="x_nome_empresa" name="x_nome_empresa" id="x_nome_empresa" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($empresas->nome_empresa->PlaceHolder) ?>" value="<?php echo $empresas->nome_empresa->EditValue ?>"<?php echo $empresas->nome_empresa->EditAttributes() ?>>
</span>
<?php echo $empresas->nome_empresa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->endereco->Visible) { // endereco ?>
	<div id="r_endereco" class="form-group">
		<label id="elh_empresas_endereco" for="x_endereco" class="col-sm-2 control-label ewLabel"><?php echo $empresas->endereco->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->endereco->CellAttributes() ?>>
<span id="el_empresas_endereco">
<input type="text" data-field="x_endereco" name="x_endereco" id="x_endereco" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($empresas->endereco->PlaceHolder) ?>" value="<?php echo $empresas->endereco->EditValue ?>"<?php echo $empresas->endereco->EditAttributes() ?>>
</span>
<?php echo $empresas->endereco->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->numero->Visible) { // numero ?>
	<div id="r_numero" class="form-group">
		<label id="elh_empresas_numero" for="x_numero" class="col-sm-2 control-label ewLabel"><?php echo $empresas->numero->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->numero->CellAttributes() ?>>
<span id="el_empresas_numero">
<input type="text" data-field="x_numero" name="x_numero" id="x_numero" size="30" placeholder="<?php echo ew_HtmlEncode($empresas->numero->PlaceHolder) ?>" value="<?php echo $empresas->numero->EditValue ?>"<?php echo $empresas->numero->EditAttributes() ?>>
</span>
<?php echo $empresas->numero->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->bairro->Visible) { // bairro ?>
	<div id="r_bairro" class="form-group">
		<label id="elh_empresas_bairro" for="x_bairro" class="col-sm-2 control-label ewLabel"><?php echo $empresas->bairro->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->bairro->CellAttributes() ?>>
<span id="el_empresas_bairro">
<input type="text" data-field="x_bairro" name="x_bairro" id="x_bairro" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($empresas->bairro->PlaceHolder) ?>" value="<?php echo $empresas->bairro->EditValue ?>"<?php echo $empresas->bairro->EditAttributes() ?>>
</span>
<?php echo $empresas->bairro->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->telefone->Visible) { // telefone ?>
	<div id="r_telefone" class="form-group">
		<label id="elh_empresas_telefone" for="x_telefone" class="col-sm-2 control-label ewLabel"><?php echo $empresas->telefone->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->telefone->CellAttributes() ?>>
<span id="el_empresas_telefone">
<input type="text" data-field="x_telefone" name="x_telefone" id="x_telefone" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($empresas->telefone->PlaceHolder) ?>" value="<?php echo $empresas->telefone->EditValue ?>"<?php echo $empresas->telefone->EditAttributes() ?>>
</span>
<?php echo $empresas->telefone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->cidade->Visible) { // cidade ?>
	<div id="r_cidade" class="form-group">
		<label id="elh_empresas_cidade" for="x_cidade" class="col-sm-2 control-label ewLabel"><?php echo $empresas->cidade->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->cidade->CellAttributes() ?>>
<span id="el_empresas_cidade">
<input type="text" data-field="x_cidade" name="x_cidade" id="x_cidade" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($empresas->cidade->PlaceHolder) ?>" value="<?php echo $empresas->cidade->EditValue ?>"<?php echo $empresas->cidade->EditAttributes() ?>>
</span>
<?php echo $empresas->cidade->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->cgc->Visible) { // cgc ?>
	<div id="r_cgc" class="form-group">
		<label id="elh_empresas_cgc" for="x_cgc" class="col-sm-2 control-label ewLabel"><?php echo $empresas->cgc->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->cgc->CellAttributes() ?>>
<span id="el_empresas_cgc">
<input type="text" data-field="x_cgc" name="x_cgc" id="x_cgc" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($empresas->cgc->PlaceHolder) ?>" value="<?php echo $empresas->cgc->EditValue ?>"<?php echo $empresas->cgc->EditAttributes() ?>>
</span>
<?php echo $empresas->cgc->CustomMsg ?></div></div>
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
fempresasadd.Init();
</script>
<?php
$empresas_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empresas_add->Page_Terminate();
?>
