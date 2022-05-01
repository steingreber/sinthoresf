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

$permissoes_add = NULL; // Initialize page object first

class cpermissoes_add extends cpermissoes {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'permissoes';

	// Page object name
	var $PageObjName = 'permissoes_add';

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

		// Table object (permissoes)
		if (!isset($GLOBALS["permissoes"]) || get_class($GLOBALS["permissoes"]) == "cpermissoes") {
			$GLOBALS["permissoes"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["permissoes"];
		}

		// User table object (permissoes)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cpermissoes();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'permissoes', TRUE);

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
		global $EW_EXPORT, $permissoes;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($permissoes);
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
			if (@$_GET["a00id"] != "") {
				$this->a00id->setQueryStringValue($_GET["a00id"]);
				$this->setKey("a00id", $this->a00id->CurrentValue); // Set up key
			} else {
				$this->setKey("a00id", ""); // Clear key
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
					$this->Page_Terminate("permissoeslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "permissoesview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
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
		$this->a01nome->CurrentValue = NULL;
		$this->a01nome->OldValue = $this->a01nome->CurrentValue;
		$this->a02email->CurrentValue = NULL;
		$this->a02email->OldValue = $this->a02email->CurrentValue;
		$this->a03senha->CurrentValue = NULL;
		$this->a03senha->OldValue = $this->a03senha->CurrentValue;
		$this->a04ativo->CurrentValue = NULL;
		$this->a04ativo->OldValue = $this->a04ativo->CurrentValue;
		$this->a05cadastro->CurrentValue = NULL;
		$this->a05cadastro->OldValue = $this->a05cadastro->CurrentValue;
		$this->a06permissoes->CurrentValue = NULL;
		$this->a06permissoes->OldValue = $this->a06permissoes->CurrentValue;
		$this->a07ultimo->CurrentValue = NULL;
		$this->a07ultimo->OldValue = $this->a07ultimo->CurrentValue;
		$this->a08acessos->CurrentValue = NULL;
		$this->a08acessos->OldValue = $this->a08acessos->CurrentValue;
		$this->a09tipo->CurrentValue = NULL;
		$this->a09tipo->OldValue = $this->a09tipo->CurrentValue;
		$this->a10full->CurrentValue = NULL;
		$this->a10full->OldValue = $this->a10full->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->a01nome->FldIsDetailKey) {
			$this->a01nome->setFormValue($objForm->GetValue("x_a01nome"));
		}
		if (!$this->a02email->FldIsDetailKey) {
			$this->a02email->setFormValue($objForm->GetValue("x_a02email"));
		}
		if (!$this->a03senha->FldIsDetailKey) {
			$this->a03senha->setFormValue($objForm->GetValue("x_a03senha"));
		}
		if (!$this->a04ativo->FldIsDetailKey) {
			$this->a04ativo->setFormValue($objForm->GetValue("x_a04ativo"));
		}
		if (!$this->a05cadastro->FldIsDetailKey) {
			$this->a05cadastro->setFormValue($objForm->GetValue("x_a05cadastro"));
			$this->a05cadastro->CurrentValue = ew_UnFormatDateTime($this->a05cadastro->CurrentValue, 7);
		}
		if (!$this->a06permissoes->FldIsDetailKey) {
			$this->a06permissoes->setFormValue($objForm->GetValue("x_a06permissoes"));
		}
		if (!$this->a07ultimo->FldIsDetailKey) {
			$this->a07ultimo->setFormValue($objForm->GetValue("x_a07ultimo"));
			$this->a07ultimo->CurrentValue = ew_UnFormatDateTime($this->a07ultimo->CurrentValue, 7);
		}
		if (!$this->a08acessos->FldIsDetailKey) {
			$this->a08acessos->setFormValue($objForm->GetValue("x_a08acessos"));
		}
		if (!$this->a09tipo->FldIsDetailKey) {
			$this->a09tipo->setFormValue($objForm->GetValue("x_a09tipo"));
		}
		if (!$this->a10full->FldIsDetailKey) {
			$this->a10full->setFormValue($objForm->GetValue("x_a10full"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->a01nome->CurrentValue = $this->a01nome->FormValue;
		$this->a02email->CurrentValue = $this->a02email->FormValue;
		$this->a03senha->CurrentValue = $this->a03senha->FormValue;
		$this->a04ativo->CurrentValue = $this->a04ativo->FormValue;
		$this->a05cadastro->CurrentValue = $this->a05cadastro->FormValue;
		$this->a05cadastro->CurrentValue = ew_UnFormatDateTime($this->a05cadastro->CurrentValue, 7);
		$this->a06permissoes->CurrentValue = $this->a06permissoes->FormValue;
		$this->a07ultimo->CurrentValue = $this->a07ultimo->FormValue;
		$this->a07ultimo->CurrentValue = ew_UnFormatDateTime($this->a07ultimo->CurrentValue, 7);
		$this->a08acessos->CurrentValue = $this->a08acessos->FormValue;
		$this->a09tipo->CurrentValue = $this->a09tipo->FormValue;
		$this->a10full->CurrentValue = $this->a10full->FormValue;
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
		$this->a00id->setDbValue($rs->fields('a00id'));
		$this->a01nome->setDbValue($rs->fields('a01nome'));
		$this->a02email->setDbValue($rs->fields('a02email'));
		$this->a03senha->setDbValue($rs->fields('a03senha'));
		$this->a04ativo->setDbValue($rs->fields('a04ativo'));
		$this->a05cadastro->setDbValue($rs->fields('a05cadastro'));
		$this->a06permissoes->setDbValue($rs->fields('a06permissoes'));
		$this->a07ultimo->setDbValue($rs->fields('a07ultimo'));
		$this->a08acessos->setDbValue($rs->fields('a08acessos'));
		$this->a09tipo->setDbValue($rs->fields('a09tipo'));
		$this->a10full->setDbValue($rs->fields('a10full'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->a00id->DbValue = $row['a00id'];
		$this->a01nome->DbValue = $row['a01nome'];
		$this->a02email->DbValue = $row['a02email'];
		$this->a03senha->DbValue = $row['a03senha'];
		$this->a04ativo->DbValue = $row['a04ativo'];
		$this->a05cadastro->DbValue = $row['a05cadastro'];
		$this->a06permissoes->DbValue = $row['a06permissoes'];
		$this->a07ultimo->DbValue = $row['a07ultimo'];
		$this->a08acessos->DbValue = $row['a08acessos'];
		$this->a09tipo->DbValue = $row['a09tipo'];
		$this->a10full->DbValue = $row['a10full'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("a00id")) <> "")
			$this->a00id->CurrentValue = $this->getKey("a00id"); // a00id
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
		// a00id
		// a01nome
		// a02email
		// a03senha
		// a04ativo
		// a05cadastro
		// a06permissoes
		// a07ultimo
		// a08acessos
		// a09tipo
		// a10full

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// a00id
			$this->a00id->ViewValue = $this->a00id->CurrentValue;
			$this->a00id->ViewCustomAttributes = "";

			// a01nome
			$this->a01nome->ViewValue = $this->a01nome->CurrentValue;
			$this->a01nome->ViewCustomAttributes = "";

			// a02email
			$this->a02email->ViewValue = $this->a02email->CurrentValue;
			$this->a02email->ViewCustomAttributes = "";

			// a03senha
			$this->a03senha->ViewValue = "********";
			$this->a03senha->ViewCustomAttributes = "";

			// a04ativo
			$this->a04ativo->ViewValue = $this->a04ativo->CurrentValue;
			$this->a04ativo->ViewCustomAttributes = "";

			// a05cadastro
			$this->a05cadastro->ViewValue = $this->a05cadastro->CurrentValue;
			$this->a05cadastro->ViewValue = ew_FormatDateTime($this->a05cadastro->ViewValue, 7);
			$this->a05cadastro->ViewCustomAttributes = "";

			// a06permissoes
			$this->a06permissoes->ViewValue = $this->a06permissoes->CurrentValue;
			$this->a06permissoes->ViewCustomAttributes = "";

			// a07ultimo
			$this->a07ultimo->ViewValue = $this->a07ultimo->CurrentValue;
			$this->a07ultimo->ViewValue = ew_FormatDateTime($this->a07ultimo->ViewValue, 7);
			$this->a07ultimo->ViewCustomAttributes = "";

			// a08acessos
			$this->a08acessos->ViewValue = $this->a08acessos->CurrentValue;
			$this->a08acessos->ViewCustomAttributes = "";

			// a09tipo
			$this->a09tipo->ViewValue = $this->a09tipo->CurrentValue;
			$this->a09tipo->ViewCustomAttributes = "";

			// a10full
			$this->a10full->ViewValue = $this->a10full->CurrentValue;
			$this->a10full->ViewCustomAttributes = "";

			// a01nome
			$this->a01nome->LinkCustomAttributes = "";
			$this->a01nome->HrefValue = "";
			$this->a01nome->TooltipValue = "";

			// a02email
			$this->a02email->LinkCustomAttributes = "";
			$this->a02email->HrefValue = "";
			$this->a02email->TooltipValue = "";

			// a03senha
			$this->a03senha->LinkCustomAttributes = "";
			$this->a03senha->HrefValue = "";
			$this->a03senha->TooltipValue = "";

			// a04ativo
			$this->a04ativo->LinkCustomAttributes = "";
			$this->a04ativo->HrefValue = "";
			$this->a04ativo->TooltipValue = "";

			// a05cadastro
			$this->a05cadastro->LinkCustomAttributes = "";
			$this->a05cadastro->HrefValue = "";
			$this->a05cadastro->TooltipValue = "";

			// a06permissoes
			$this->a06permissoes->LinkCustomAttributes = "";
			$this->a06permissoes->HrefValue = "";
			$this->a06permissoes->TooltipValue = "";

			// a07ultimo
			$this->a07ultimo->LinkCustomAttributes = "";
			$this->a07ultimo->HrefValue = "";
			$this->a07ultimo->TooltipValue = "";

			// a08acessos
			$this->a08acessos->LinkCustomAttributes = "";
			$this->a08acessos->HrefValue = "";
			$this->a08acessos->TooltipValue = "";

			// a09tipo
			$this->a09tipo->LinkCustomAttributes = "";
			$this->a09tipo->HrefValue = "";
			$this->a09tipo->TooltipValue = "";

			// a10full
			$this->a10full->LinkCustomAttributes = "";
			$this->a10full->HrefValue = "";
			$this->a10full->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// a01nome
			$this->a01nome->EditAttrs["class"] = "form-control";
			$this->a01nome->EditCustomAttributes = "";
			$this->a01nome->EditValue = ew_HtmlEncode($this->a01nome->CurrentValue);
			$this->a01nome->PlaceHolder = ew_RemoveHtml($this->a01nome->FldCaption());

			// a02email
			$this->a02email->EditAttrs["class"] = "form-control";
			$this->a02email->EditCustomAttributes = "";
			$this->a02email->EditValue = ew_HtmlEncode($this->a02email->CurrentValue);
			$this->a02email->PlaceHolder = ew_RemoveHtml($this->a02email->FldCaption());

			// a03senha
			$this->a03senha->EditAttrs["class"] = "form-control";
			$this->a03senha->EditCustomAttributes = "";
			$this->a03senha->EditValue = ew_HtmlEncode($this->a03senha->CurrentValue);
			$this->a03senha->PlaceHolder = ew_RemoveHtml($this->a03senha->FldCaption());

			// a04ativo
			$this->a04ativo->EditAttrs["class"] = "form-control";
			$this->a04ativo->EditCustomAttributes = "";
			$this->a04ativo->EditValue = ew_HtmlEncode($this->a04ativo->CurrentValue);
			$this->a04ativo->PlaceHolder = ew_RemoveHtml($this->a04ativo->FldCaption());

			// a05cadastro
			$this->a05cadastro->EditAttrs["class"] = "form-control";
			$this->a05cadastro->EditCustomAttributes = "";
			$this->a05cadastro->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->a05cadastro->CurrentValue, 7));
			$this->a05cadastro->PlaceHolder = ew_RemoveHtml($this->a05cadastro->FldCaption());

			// a06permissoes
			$this->a06permissoes->EditAttrs["class"] = "form-control";
			$this->a06permissoes->EditCustomAttributes = "";
			$this->a06permissoes->EditValue = ew_HtmlEncode($this->a06permissoes->CurrentValue);
			$this->a06permissoes->PlaceHolder = ew_RemoveHtml($this->a06permissoes->FldCaption());

			// a07ultimo
			$this->a07ultimo->EditAttrs["class"] = "form-control";
			$this->a07ultimo->EditCustomAttributes = "";
			$this->a07ultimo->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->a07ultimo->CurrentValue, 7));
			$this->a07ultimo->PlaceHolder = ew_RemoveHtml($this->a07ultimo->FldCaption());

			// a08acessos
			$this->a08acessos->EditAttrs["class"] = "form-control";
			$this->a08acessos->EditCustomAttributes = "";
			$this->a08acessos->EditValue = ew_HtmlEncode($this->a08acessos->CurrentValue);
			$this->a08acessos->PlaceHolder = ew_RemoveHtml($this->a08acessos->FldCaption());

			// a09tipo
			$this->a09tipo->EditAttrs["class"] = "form-control";
			$this->a09tipo->EditCustomAttributes = "";
			$this->a09tipo->EditValue = ew_HtmlEncode($this->a09tipo->CurrentValue);
			$this->a09tipo->PlaceHolder = ew_RemoveHtml($this->a09tipo->FldCaption());

			// a10full
			$this->a10full->EditAttrs["class"] = "form-control";
			$this->a10full->EditCustomAttributes = "";
			$this->a10full->EditValue = ew_HtmlEncode($this->a10full->CurrentValue);
			$this->a10full->PlaceHolder = ew_RemoveHtml($this->a10full->FldCaption());

			// Edit refer script
			// a01nome

			$this->a01nome->HrefValue = "";

			// a02email
			$this->a02email->HrefValue = "";

			// a03senha
			$this->a03senha->HrefValue = "";

			// a04ativo
			$this->a04ativo->HrefValue = "";

			// a05cadastro
			$this->a05cadastro->HrefValue = "";

			// a06permissoes
			$this->a06permissoes->HrefValue = "";

			// a07ultimo
			$this->a07ultimo->HrefValue = "";

			// a08acessos
			$this->a08acessos->HrefValue = "";

			// a09tipo
			$this->a09tipo->HrefValue = "";

			// a10full
			$this->a10full->HrefValue = "";
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
		if (!$this->a01nome->FldIsDetailKey && !is_null($this->a01nome->FormValue) && $this->a01nome->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->a01nome->FldCaption(), $this->a01nome->ReqErrMsg));
		}
		if (!$this->a02email->FldIsDetailKey && !is_null($this->a02email->FormValue) && $this->a02email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->a02email->FldCaption(), $this->a02email->ReqErrMsg));
		}
		if (!ew_CheckEmail($this->a02email->FormValue)) {
			ew_AddMessage($gsFormError, $this->a02email->FldErrMsg());
		}
		if (!$this->a03senha->FldIsDetailKey && !is_null($this->a03senha->FormValue) && $this->a03senha->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->a03senha->FldCaption(), $this->a03senha->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->a04ativo->FormValue)) {
			ew_AddMessage($gsFormError, $this->a04ativo->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->a05cadastro->FormValue)) {
			ew_AddMessage($gsFormError, $this->a05cadastro->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->a07ultimo->FormValue)) {
			ew_AddMessage($gsFormError, $this->a07ultimo->FldErrMsg());
		}
		if (!ew_CheckInteger($this->a08acessos->FormValue)) {
			ew_AddMessage($gsFormError, $this->a08acessos->FldErrMsg());
		}
		if (!ew_CheckInteger($this->a09tipo->FormValue)) {
			ew_AddMessage($gsFormError, $this->a09tipo->FldErrMsg());
		}
		if (!ew_CheckInteger($this->a10full->FormValue)) {
			ew_AddMessage($gsFormError, $this->a10full->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// a01nome
		$this->a01nome->SetDbValueDef($rsnew, $this->a01nome->CurrentValue, NULL, FALSE);

		// a02email
		$this->a02email->SetDbValueDef($rsnew, $this->a02email->CurrentValue, NULL, FALSE);

		// a03senha
		$this->a03senha->SetDbValueDef($rsnew, $this->a03senha->CurrentValue, NULL, FALSE);

		// a04ativo
		$this->a04ativo->SetDbValueDef($rsnew, $this->a04ativo->CurrentValue, NULL, FALSE);

		// a05cadastro
		$this->a05cadastro->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->a05cadastro->CurrentValue, 7), NULL, FALSE);

		// a06permissoes
		$this->a06permissoes->SetDbValueDef($rsnew, $this->a06permissoes->CurrentValue, NULL, FALSE);

		// a07ultimo
		$this->a07ultimo->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->a07ultimo->CurrentValue, 7), NULL, FALSE);

		// a08acessos
		$this->a08acessos->SetDbValueDef($rsnew, $this->a08acessos->CurrentValue, NULL, FALSE);

		// a09tipo
		$this->a09tipo->SetDbValueDef($rsnew, $this->a09tipo->CurrentValue, NULL, FALSE);

		// a10full
		$this->a10full->SetDbValueDef($rsnew, $this->a10full->CurrentValue, NULL, FALSE);

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
			$this->a00id->setDbValue($conn->Insert_ID());
			$rsnew['a00id'] = $this->a00id->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "permissoeslist.php", "", $this->TableVar, TRUE);
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
if (!isset($permissoes_add)) $permissoes_add = new cpermissoes_add();

// Page init
$permissoes_add->Page_Init();

// Page main
$permissoes_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$permissoes_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var permissoes_add = new ew_Page("permissoes_add");
permissoes_add.PageID = "add"; // Page ID
var EW_PAGE_ID = permissoes_add.PageID; // For backward compatibility

// Form object
var fpermissoesadd = new ew_Form("fpermissoesadd");

// Validate form
fpermissoesadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_a01nome");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $permissoes->a01nome->FldCaption(), $permissoes->a01nome->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_a02email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $permissoes->a02email->FldCaption(), $permissoes->a02email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_a02email");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($permissoes->a02email->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_a03senha");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $permissoes->a03senha->FldCaption(), $permissoes->a03senha->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_a04ativo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($permissoes->a04ativo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_a05cadastro");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($permissoes->a05cadastro->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_a07ultimo");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($permissoes->a07ultimo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_a08acessos");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($permissoes->a08acessos->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_a09tipo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($permissoes->a09tipo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_a10full");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($permissoes->a10full->FldErrMsg()) ?>");

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
fpermissoesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpermissoesadd.ValidateRequired = true;
<?php } else { ?>
fpermissoesadd.ValidateRequired = false; 
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
<?php $permissoes_add->ShowPageHeader(); ?>
<?php
$permissoes_add->ShowMessage();
?>
<form name="fpermissoesadd" id="fpermissoesadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($permissoes_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $permissoes_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="permissoes">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($permissoes->a01nome->Visible) { // a01nome ?>
	<div id="r_a01nome" class="form-group">
		<label id="elh_permissoes_a01nome" for="x_a01nome" class="col-sm-2 control-label ewLabel"><?php echo $permissoes->a01nome->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $permissoes->a01nome->CellAttributes() ?>>
<span id="el_permissoes_a01nome">
<input type="text" data-field="x_a01nome" name="x_a01nome" id="x_a01nome" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($permissoes->a01nome->PlaceHolder) ?>" value="<?php echo $permissoes->a01nome->EditValue ?>"<?php echo $permissoes->a01nome->EditAttributes() ?>>
</span>
<?php echo $permissoes->a01nome->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($permissoes->a02email->Visible) { // a02email ?>
	<div id="r_a02email" class="form-group">
		<label id="elh_permissoes_a02email" for="x_a02email" class="col-sm-2 control-label ewLabel"><?php echo $permissoes->a02email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $permissoes->a02email->CellAttributes() ?>>
<span id="el_permissoes_a02email">
<input type="text" data-field="x_a02email" name="x_a02email" id="x_a02email" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($permissoes->a02email->PlaceHolder) ?>" value="<?php echo $permissoes->a02email->EditValue ?>"<?php echo $permissoes->a02email->EditAttributes() ?>>
</span>
<?php echo $permissoes->a02email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($permissoes->a03senha->Visible) { // a03senha ?>
	<div id="r_a03senha" class="form-group">
		<label id="elh_permissoes_a03senha" for="x_a03senha" class="col-sm-2 control-label ewLabel"><?php echo $permissoes->a03senha->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $permissoes->a03senha->CellAttributes() ?>>
<span id="el_permissoes_a03senha">
<input type="password" data-field="x_a03senha" name="x_a03senha" id="x_a03senha" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($permissoes->a03senha->PlaceHolder) ?>"<?php echo $permissoes->a03senha->EditAttributes() ?>>
</span>
<?php echo $permissoes->a03senha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($permissoes->a04ativo->Visible) { // a04ativo ?>
	<div id="r_a04ativo" class="form-group">
		<label id="elh_permissoes_a04ativo" for="x_a04ativo" class="col-sm-2 control-label ewLabel"><?php echo $permissoes->a04ativo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $permissoes->a04ativo->CellAttributes() ?>>
<span id="el_permissoes_a04ativo">
<input type="text" data-field="x_a04ativo" name="x_a04ativo" id="x_a04ativo" size="30" placeholder="<?php echo ew_HtmlEncode($permissoes->a04ativo->PlaceHolder) ?>" value="<?php echo $permissoes->a04ativo->EditValue ?>"<?php echo $permissoes->a04ativo->EditAttributes() ?>>
</span>
<?php echo $permissoes->a04ativo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($permissoes->a05cadastro->Visible) { // a05cadastro ?>
	<div id="r_a05cadastro" class="form-group">
		<label id="elh_permissoes_a05cadastro" for="x_a05cadastro" class="col-sm-2 control-label ewLabel"><?php echo $permissoes->a05cadastro->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $permissoes->a05cadastro->CellAttributes() ?>>
<span id="el_permissoes_a05cadastro">
<input type="text" data-field="x_a05cadastro" name="x_a05cadastro" id="x_a05cadastro" placeholder="<?php echo ew_HtmlEncode($permissoes->a05cadastro->PlaceHolder) ?>" value="<?php echo $permissoes->a05cadastro->EditValue ?>"<?php echo $permissoes->a05cadastro->EditAttributes() ?>>
</span>
<?php echo $permissoes->a05cadastro->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($permissoes->a06permissoes->Visible) { // a06permissoes ?>
	<div id="r_a06permissoes" class="form-group">
		<label id="elh_permissoes_a06permissoes" for="x_a06permissoes" class="col-sm-2 control-label ewLabel"><?php echo $permissoes->a06permissoes->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $permissoes->a06permissoes->CellAttributes() ?>>
<span id="el_permissoes_a06permissoes">
<input type="text" data-field="x_a06permissoes" name="x_a06permissoes" id="x_a06permissoes" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($permissoes->a06permissoes->PlaceHolder) ?>" value="<?php echo $permissoes->a06permissoes->EditValue ?>"<?php echo $permissoes->a06permissoes->EditAttributes() ?>>
</span>
<?php echo $permissoes->a06permissoes->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($permissoes->a07ultimo->Visible) { // a07ultimo ?>
	<div id="r_a07ultimo" class="form-group">
		<label id="elh_permissoes_a07ultimo" for="x_a07ultimo" class="col-sm-2 control-label ewLabel"><?php echo $permissoes->a07ultimo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $permissoes->a07ultimo->CellAttributes() ?>>
<span id="el_permissoes_a07ultimo">
<input type="text" data-field="x_a07ultimo" name="x_a07ultimo" id="x_a07ultimo" placeholder="<?php echo ew_HtmlEncode($permissoes->a07ultimo->PlaceHolder) ?>" value="<?php echo $permissoes->a07ultimo->EditValue ?>"<?php echo $permissoes->a07ultimo->EditAttributes() ?>>
</span>
<?php echo $permissoes->a07ultimo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($permissoes->a08acessos->Visible) { // a08acessos ?>
	<div id="r_a08acessos" class="form-group">
		<label id="elh_permissoes_a08acessos" for="x_a08acessos" class="col-sm-2 control-label ewLabel"><?php echo $permissoes->a08acessos->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $permissoes->a08acessos->CellAttributes() ?>>
<span id="el_permissoes_a08acessos">
<input type="text" data-field="x_a08acessos" name="x_a08acessos" id="x_a08acessos" size="30" placeholder="<?php echo ew_HtmlEncode($permissoes->a08acessos->PlaceHolder) ?>" value="<?php echo $permissoes->a08acessos->EditValue ?>"<?php echo $permissoes->a08acessos->EditAttributes() ?>>
</span>
<?php echo $permissoes->a08acessos->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($permissoes->a09tipo->Visible) { // a09tipo ?>
	<div id="r_a09tipo" class="form-group">
		<label id="elh_permissoes_a09tipo" for="x_a09tipo" class="col-sm-2 control-label ewLabel"><?php echo $permissoes->a09tipo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $permissoes->a09tipo->CellAttributes() ?>>
<span id="el_permissoes_a09tipo">
<input type="text" data-field="x_a09tipo" name="x_a09tipo" id="x_a09tipo" size="30" placeholder="<?php echo ew_HtmlEncode($permissoes->a09tipo->PlaceHolder) ?>" value="<?php echo $permissoes->a09tipo->EditValue ?>"<?php echo $permissoes->a09tipo->EditAttributes() ?>>
</span>
<?php echo $permissoes->a09tipo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($permissoes->a10full->Visible) { // a10full ?>
	<div id="r_a10full" class="form-group">
		<label id="elh_permissoes_a10full" for="x_a10full" class="col-sm-2 control-label ewLabel"><?php echo $permissoes->a10full->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $permissoes->a10full->CellAttributes() ?>>
<span id="el_permissoes_a10full">
<input type="text" data-field="x_a10full" name="x_a10full" id="x_a10full" size="30" placeholder="<?php echo ew_HtmlEncode($permissoes->a10full->PlaceHolder) ?>" value="<?php echo $permissoes->a10full->EditValue ?>"<?php echo $permissoes->a10full->EditAttributes() ?>>
</span>
<?php echo $permissoes->a10full->CustomMsg ?></div></div>
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
fpermissoesadd.Init();
</script>
<?php
$permissoes_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$permissoes_add->Page_Terminate();
?>
