<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "pessoasinfo.php" ?>
<?php include_once "permissoesinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$pessoas_view = NULL; // Initialize page object first

class cpessoas_view extends cpessoas {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'pessoas';

	// Page object name
	var $PageObjName = 'pessoas_view';

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

		// Table object (pessoas)
		if (!isset($GLOBALS["pessoas"]) || get_class($GLOBALS["pessoas"]) == "cpessoas") {
			$GLOBALS["pessoas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pessoas"];
		}
		$KeyUrl = "";
		if (@$_GET["cod_pessoa"] <> "") {
			$this->RecKey["cod_pessoa"] = $_GET["cod_pessoa"];
			$KeyUrl .= "&amp;cod_pessoa=" . urlencode($this->RecKey["cod_pessoa"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (permissoes)
		if (!isset($GLOBALS['permissoes'])) $GLOBALS['permissoes'] = new cpermissoes();

		// User table object (permissoes)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cpermissoes();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pessoas', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["cod_pessoa"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["cod_pessoa"]);
		}

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
		$this->cod_pessoa->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $pessoas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pessoas);
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["cod_pessoa"] <> "") {
				$this->cod_pessoa->setQueryStringValue($_GET["cod_pessoa"]);
				$this->RecKey["cod_pessoa"] = $this->cod_pessoa->QueryStringValue;
			} elseif (@$_POST["cod_pessoa"] <> "") {
				$this->cod_pessoa->setFormValue($_POST["cod_pessoa"]);
				$this->RecKey["cod_pessoa"] = $this->cod_pessoa->FormValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					$this->StartRec = 1; // Initialize start position
					if ($this->Recordset = $this->LoadRecordset()) // Load records
						$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
					if ($this->TotalRecs <= 0) { // No record found
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$this->Page_Terminate("pessoaslist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->cod_pessoa->CurrentValue) == strval($this->Recordset->fields('cod_pessoa'))) {
								$this->setStartRecordNumber($this->StartRec); // Save record position
								$bMatchRecord = TRUE;
								break;
							} else {
								$this->StartRec++;
								$this->Recordset->MoveNext();
							}
						}
					}
					if (!$bMatchRecord) {
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "pessoaslist.php"; // No matching record, return to list
					} else {
						$this->LoadRowValues($this->Recordset); // Load row values
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "pessoaslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a onclick=\"return ew_Confirm(ewLanguage.Phrase('DeleteConfirmMsg'));\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = TRUE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
		$conn->raiseErrorFn = '';

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->cod_pessoa->DbValue = $row['cod_pessoa'];
		$this->datacadastro->DbValue = $row['datacadastro'];
		$this->nome->DbValue = $row['nome'];
		$this->endereco->DbValue = $row['endereco'];
		$this->numero->DbValue = $row['numero'];
		$this->complemento->DbValue = $row['complemento'];
		$this->bairro->DbValue = $row['bairro'];
		$this->cidade->DbValue = $row['cidade'];
		$this->estado->DbValue = $row['estado'];
		$this->CEP->DbValue = $row['CEP'];
		$this->telefone->DbValue = $row['telefone'];
		$this->sexo->DbValue = $row['sexo'];
		$this->datanasc->DbValue = $row['datanasc'];
		$this->estado_civil->DbValue = $row['estado_civil'];
		$this->rg->DbValue = $row['rg'];
		$this->cpf->DbValue = $row['cpf'];
		$this->carteira_trabalho->DbValue = $row['carteira_trabalho'];
		$this->nacionalidade->DbValue = $row['nacionalidade'];
		$this->naturalidade->DbValue = $row['naturalidade'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_pessoas\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_pessoas',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fpessoasview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

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

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "v");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "view");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Call Page Exported server event
		$this->Page_Exported();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "pessoaslist.php", "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($pessoas_view)) $pessoas_view = new cpessoas_view();

// Page init
$pessoas_view->Page_Init();

// Page main
$pessoas_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pessoas_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($pessoas->Export == "") { ?>
<script type="text/javascript">

// Page object
var pessoas_view = new ew_Page("pessoas_view");
pessoas_view.PageID = "view"; // Page ID
var EW_PAGE_ID = pessoas_view.PageID; // For backward compatibility

// Form object
var fpessoasview = new ew_Form("fpessoasview");

// Form_CustomValidate event
fpessoasview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpessoasview.ValidateRequired = true;
<?php } else { ?>
fpessoasview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpessoasview.Lists["x_estado"] = {"LinkField":"x_sigla","Ajax":null,"AutoFill":false,"DisplayFields":["x_estado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($pessoas->Export == "") { ?>
<div class="ewToolbar">
<?php if ($pessoas->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $pessoas_view->ExportOptions->Render("body") ?>
<?php
	foreach ($pessoas_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if ($pessoas->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $pessoas_view->ShowPageHeader(); ?>
<?php
$pessoas_view->ShowMessage();
?>
<?php if ($pessoas->Export == "") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pessoas_view->Pager)) $pessoas_view->Pager = new cPrevNextPager($pessoas_view->StartRec, $pessoas_view->DisplayRecs, $pessoas_view->TotalRecs) ?>
<?php if ($pessoas_view->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pessoas_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pessoas_view->PageUrl() ?>start=<?php echo $pessoas_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pessoas_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pessoas_view->PageUrl() ?>start=<?php echo $pessoas_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pessoas_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pessoas_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pessoas_view->PageUrl() ?>start=<?php echo $pessoas_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pessoas_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pessoas_view->PageUrl() ?>start=<?php echo $pessoas_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pessoas_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fpessoasview" id="fpessoasview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pessoas_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pessoas_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pessoas">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($pessoas->cod_pessoa->Visible) { // cod_pessoa ?>
	<tr id="r_cod_pessoa">
		<td><span id="elh_pessoas_cod_pessoa"><?php echo $pessoas->cod_pessoa->FldCaption() ?></span></td>
		<td<?php echo $pessoas->cod_pessoa->CellAttributes() ?>>
<span id="el_pessoas_cod_pessoa">
<span<?php echo $pessoas->cod_pessoa->ViewAttributes() ?>>
<?php echo $pessoas->cod_pessoa->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->datacadastro->Visible) { // datacadastro ?>
	<tr id="r_datacadastro">
		<td><span id="elh_pessoas_datacadastro"><?php echo $pessoas->datacadastro->FldCaption() ?></span></td>
		<td<?php echo $pessoas->datacadastro->CellAttributes() ?>>
<span id="el_pessoas_datacadastro">
<span<?php echo $pessoas->datacadastro->ViewAttributes() ?>>
<?php echo $pessoas->datacadastro->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->nome->Visible) { // nome ?>
	<tr id="r_nome">
		<td><span id="elh_pessoas_nome"><?php echo $pessoas->nome->FldCaption() ?></span></td>
		<td<?php echo $pessoas->nome->CellAttributes() ?>>
<span id="el_pessoas_nome">
<span<?php echo $pessoas->nome->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($pessoas->nome->ViewValue)) && $pessoas->nome->LinkAttributes() <> "") { ?>
<a<?php echo $pessoas->nome->LinkAttributes() ?>><?php echo $pessoas->nome->ViewValue ?></a>
<?php } else { ?>
<?php echo $pessoas->nome->ViewValue ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->endereco->Visible) { // endereco ?>
	<tr id="r_endereco">
		<td><span id="elh_pessoas_endereco"><?php echo $pessoas->endereco->FldCaption() ?></span></td>
		<td<?php echo $pessoas->endereco->CellAttributes() ?>>
<span id="el_pessoas_endereco">
<span<?php echo $pessoas->endereco->ViewAttributes() ?>>
<?php echo $pessoas->endereco->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->numero->Visible) { // numero ?>
	<tr id="r_numero">
		<td><span id="elh_pessoas_numero"><?php echo $pessoas->numero->FldCaption() ?></span></td>
		<td<?php echo $pessoas->numero->CellAttributes() ?>>
<span id="el_pessoas_numero">
<span<?php echo $pessoas->numero->ViewAttributes() ?>>
<?php echo $pessoas->numero->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->complemento->Visible) { // complemento ?>
	<tr id="r_complemento">
		<td><span id="elh_pessoas_complemento"><?php echo $pessoas->complemento->FldCaption() ?></span></td>
		<td<?php echo $pessoas->complemento->CellAttributes() ?>>
<span id="el_pessoas_complemento">
<span<?php echo $pessoas->complemento->ViewAttributes() ?>>
<?php echo $pessoas->complemento->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->bairro->Visible) { // bairro ?>
	<tr id="r_bairro">
		<td><span id="elh_pessoas_bairro"><?php echo $pessoas->bairro->FldCaption() ?></span></td>
		<td<?php echo $pessoas->bairro->CellAttributes() ?>>
<span id="el_pessoas_bairro">
<span<?php echo $pessoas->bairro->ViewAttributes() ?>>
<?php echo $pessoas->bairro->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->cidade->Visible) { // cidade ?>
	<tr id="r_cidade">
		<td><span id="elh_pessoas_cidade"><?php echo $pessoas->cidade->FldCaption() ?></span></td>
		<td<?php echo $pessoas->cidade->CellAttributes() ?>>
<span id="el_pessoas_cidade">
<span<?php echo $pessoas->cidade->ViewAttributes() ?>>
<?php echo $pessoas->cidade->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->estado->Visible) { // estado ?>
	<tr id="r_estado">
		<td><span id="elh_pessoas_estado"><?php echo $pessoas->estado->FldCaption() ?></span></td>
		<td<?php echo $pessoas->estado->CellAttributes() ?>>
<span id="el_pessoas_estado">
<span<?php echo $pessoas->estado->ViewAttributes() ?>>
<?php echo $pessoas->estado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->CEP->Visible) { // CEP ?>
	<tr id="r_CEP">
		<td><span id="elh_pessoas_CEP"><?php echo $pessoas->CEP->FldCaption() ?></span></td>
		<td<?php echo $pessoas->CEP->CellAttributes() ?>>
<span id="el_pessoas_CEP">
<span<?php echo $pessoas->CEP->ViewAttributes() ?>>
<?php echo $pessoas->CEP->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->telefone->Visible) { // telefone ?>
	<tr id="r_telefone">
		<td><span id="elh_pessoas_telefone"><?php echo $pessoas->telefone->FldCaption() ?></span></td>
		<td<?php echo $pessoas->telefone->CellAttributes() ?>>
<span id="el_pessoas_telefone">
<span<?php echo $pessoas->telefone->ViewAttributes() ?>>
<?php echo $pessoas->telefone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->sexo->Visible) { // sexo ?>
	<tr id="r_sexo">
		<td><span id="elh_pessoas_sexo"><?php echo $pessoas->sexo->FldCaption() ?></span></td>
		<td<?php echo $pessoas->sexo->CellAttributes() ?>>
<span id="el_pessoas_sexo">
<span<?php echo $pessoas->sexo->ViewAttributes() ?>>
<?php echo $pessoas->sexo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->datanasc->Visible) { // datanasc ?>
	<tr id="r_datanasc">
		<td><span id="elh_pessoas_datanasc"><?php echo $pessoas->datanasc->FldCaption() ?></span></td>
		<td<?php echo $pessoas->datanasc->CellAttributes() ?>>
<span id="el_pessoas_datanasc">
<span<?php echo $pessoas->datanasc->ViewAttributes() ?>>
<?php echo $pessoas->datanasc->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->estado_civil->Visible) { // estado_civil ?>
	<tr id="r_estado_civil">
		<td><span id="elh_pessoas_estado_civil"><?php echo $pessoas->estado_civil->FldCaption() ?></span></td>
		<td<?php echo $pessoas->estado_civil->CellAttributes() ?>>
<span id="el_pessoas_estado_civil">
<span<?php echo $pessoas->estado_civil->ViewAttributes() ?>>
<?php echo $pessoas->estado_civil->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->rg->Visible) { // rg ?>
	<tr id="r_rg">
		<td><span id="elh_pessoas_rg"><?php echo $pessoas->rg->FldCaption() ?></span></td>
		<td<?php echo $pessoas->rg->CellAttributes() ?>>
<span id="el_pessoas_rg">
<span<?php echo $pessoas->rg->ViewAttributes() ?>>
<?php echo $pessoas->rg->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->cpf->Visible) { // cpf ?>
	<tr id="r_cpf">
		<td><span id="elh_pessoas_cpf"><?php echo $pessoas->cpf->FldCaption() ?></span></td>
		<td<?php echo $pessoas->cpf->CellAttributes() ?>>
<span id="el_pessoas_cpf">
<span<?php echo $pessoas->cpf->ViewAttributes() ?>>
<?php echo $pessoas->cpf->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->carteira_trabalho->Visible) { // carteira_trabalho ?>
	<tr id="r_carteira_trabalho">
		<td><span id="elh_pessoas_carteira_trabalho"><?php echo $pessoas->carteira_trabalho->FldCaption() ?></span></td>
		<td<?php echo $pessoas->carteira_trabalho->CellAttributes() ?>>
<span id="el_pessoas_carteira_trabalho">
<span<?php echo $pessoas->carteira_trabalho->ViewAttributes() ?>>
<?php echo $pessoas->carteira_trabalho->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->nacionalidade->Visible) { // nacionalidade ?>
	<tr id="r_nacionalidade">
		<td><span id="elh_pessoas_nacionalidade"><?php echo $pessoas->nacionalidade->FldCaption() ?></span></td>
		<td<?php echo $pessoas->nacionalidade->CellAttributes() ?>>
<span id="el_pessoas_nacionalidade">
<span<?php echo $pessoas->nacionalidade->ViewAttributes() ?>>
<?php echo $pessoas->nacionalidade->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pessoas->naturalidade->Visible) { // naturalidade ?>
	<tr id="r_naturalidade">
		<td><span id="elh_pessoas_naturalidade"><?php echo $pessoas->naturalidade->FldCaption() ?></span></td>
		<td<?php echo $pessoas->naturalidade->CellAttributes() ?>>
<span id="el_pessoas_naturalidade">
<span<?php echo $pessoas->naturalidade->ViewAttributes() ?>>
<?php echo $pessoas->naturalidade->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($pessoas->Export == "") { ?>
<?php if (!isset($pessoas_view->Pager)) $pessoas_view->Pager = new cPrevNextPager($pessoas_view->StartRec, $pessoas_view->DisplayRecs, $pessoas_view->TotalRecs) ?>
<?php if ($pessoas_view->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pessoas_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pessoas_view->PageUrl() ?>start=<?php echo $pessoas_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pessoas_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pessoas_view->PageUrl() ?>start=<?php echo $pessoas_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pessoas_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pessoas_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pessoas_view->PageUrl() ?>start=<?php echo $pessoas_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pessoas_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pessoas_view->PageUrl() ?>start=<?php echo $pessoas_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pessoas_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
fpessoasview.Init();
</script>
<?php
$pessoas_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($pessoas->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$pessoas_view->Page_Terminate();
?>
