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

$socios_view = NULL; // Initialize page object first

class csocios_view extends csocios {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'socios';

	// Page object name
	var $PageObjName = 'socios_view';

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

		// Table object (socios)
		if (!isset($GLOBALS["socios"]) || get_class($GLOBALS["socios"]) == "csocios") {
			$GLOBALS["socios"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["socios"];
		}
		$KeyUrl = "";
		if (@$_GET["cod_socio"] <> "") {
			$this->RecKey["cod_socio"] = $_GET["cod_socio"];
			$KeyUrl .= "&amp;cod_socio=" . urlencode($this->RecKey["cod_socio"]);
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
			define("EW_TABLE_NAME", 'socios', TRUE);

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
		if (@$_GET["cod_socio"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["cod_socio"]);
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
		$this->cod_socio->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["cod_socio"] <> "") {
				$this->cod_socio->setQueryStringValue($_GET["cod_socio"]);
				$this->RecKey["cod_socio"] = $this->cod_socio->QueryStringValue;
			} elseif (@$_POST["cod_socio"] <> "") {
				$this->cod_socio->setFormValue($_POST["cod_socio"]);
				$this->RecKey["cod_socio"] = $this->cod_socio->FormValue;
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
						$this->Page_Terminate("socioslist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->cod_socio->CurrentValue) == strval($this->Recordset->fields('cod_socio'))) {
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
						$sReturnUrl = "socioslist.php"; // No matching record, return to list
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
			$sReturnUrl = "socioslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();

		// Set up detail parameters
		$this->SetUpDetailParms();
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
		$option = &$options["detail"];
		$DetailTableLink = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detailreport_rel_carteira_socio_"
		$item = &$option->Add("detailreport_rel_carteira_socio_");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("rel_carteira_socio_", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink\" href=\"" . ew_HtmlEncode("rel_carteira_socio_report.php?" . EW_TABLE_SHOW_MASTER . "=socios&fk_socio=" . urlencode(strval($this->socio->CurrentValue)) . "") . "\">" . $body . "</a>";
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'rel_carteira_socio_');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "rel_carteira_socio_";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detailreport_rel_carteira_clube_"
		$item = &$option->Add("detailreport_rel_carteira_clube_");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("rel_carteira_clube_", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink\" href=\"" . ew_HtmlEncode("rel_carteira_clube_report.php?" . EW_TABLE_SHOW_MASTER . "=socios&fk_socio=" . urlencode(strval($this->socio->CurrentValue)) . "") . "\">" . $body . "</a>";
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'rel_carteira_clube_');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "rel_carteira_clube_";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detailreport_rel_ficha_cadastral_"
		$item = &$option->Add("detailreport_rel_ficha_cadastral_");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("rel_ficha_cadastral_", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink\" href=\"" . ew_HtmlEncode("rel_ficha_cadastral_report.php?" . EW_TABLE_SHOW_MASTER . "=socios&fk_socio=" . urlencode(strval($this->socio->CurrentValue)) . "") . "\">" . $body . "</a>";
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'rel_ficha_cadastral_');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "rel_ficha_cadastral_";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detailreport_rel_recibo_socio_"
		$item = &$option->Add("detailreport_rel_recibo_socio_");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("rel_recibo_socio_", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink\" href=\"" . ew_HtmlEncode("rel_recibo_socio_report.php?" . EW_TABLE_SHOW_MASTER . "=socios&fk_socio=" . urlencode(strval($this->socio->CurrentValue)) . "") . "\">" . $body . "</a>";
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'rel_recibo_socio_');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "rel_recibo_socio_";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detailreport_rel_convite_festa_"
		$item = &$option->Add("detailreport_rel_convite_festa_");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("rel_convite_festa_", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink\" href=\"" . ew_HtmlEncode("rel_convite_festa_report.php?" . EW_TABLE_SHOW_MASTER . "=socios&fk_socio=" . urlencode(strval($this->socio->CurrentValue)) . "") . "\">" . $body . "</a>";
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'rel_convite_festa_');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "rel_convite_festa_";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$option->Add("details");
			$oListOpt->Body = $body;
		}

		// Set up detail default
		$option = &$options["detail"];
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$option->UseImageAndText = TRUE;
		$ar = explode(",", $DetailTableLink);
		$cnt = count($ar);
		$option->UseDropDownButton = ($cnt > 1);
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

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

			// cod_socio
			$this->cod_socio->LinkCustomAttributes = "";
			$this->cod_socio->HrefValue = "";
			$this->cod_socio->TooltipValue = "";

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
		$item->Body = "<button id=\"emf_socios\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_socios',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fsociosview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Export detail records (rel_carteira_socio_)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("rel_carteira_socio_", explode(",", $this->getCurrentDetailTable()))) {
			global $rel_carteira_socio_;
			if (!isset($rel_carteira_socio_)) $rel_carteira_socio_ = new crel_carteira_socio_;
			$rsdetail = $rel_carteira_socio_->LoadRs($rel_carteira_socio_->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$rel_carteira_socio_->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}

		// Export detail records (rel_carteira_clube_)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("rel_carteira_clube_", explode(",", $this->getCurrentDetailTable()))) {
			global $rel_carteira_clube_;
			if (!isset($rel_carteira_clube_)) $rel_carteira_clube_ = new crel_carteira_clube_;
			$rsdetail = $rel_carteira_clube_->LoadRs($rel_carteira_clube_->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$rel_carteira_clube_->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}

		// Export detail records (rel_ficha_cadastral_)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("rel_ficha_cadastral_", explode(",", $this->getCurrentDetailTable()))) {
			global $rel_ficha_cadastral_;
			if (!isset($rel_ficha_cadastral_)) $rel_ficha_cadastral_ = new crel_ficha_cadastral_;
			$rsdetail = $rel_ficha_cadastral_->LoadRs($rel_ficha_cadastral_->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$rel_ficha_cadastral_->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}

		// Export detail records (rel_recibo_socio_)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("rel_recibo_socio_", explode(",", $this->getCurrentDetailTable()))) {
			global $rel_recibo_socio_;
			if (!isset($rel_recibo_socio_)) $rel_recibo_socio_ = new crel_recibo_socio_;
			$rsdetail = $rel_recibo_socio_->LoadRs($rel_recibo_socio_->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$rel_recibo_socio_->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}

		// Export detail records (rel_convite_festa_)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("rel_convite_festa_", explode(",", $this->getCurrentDetailTable()))) {
			global $rel_convite_festa_;
			if (!isset($rel_convite_festa_)) $rel_convite_festa_ = new crel_convite_festa_;
			$rsdetail = $rel_convite_festa_->LoadRs($rel_convite_festa_->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$rel_convite_festa_->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}
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
if (!isset($socios_view)) $socios_view = new csocios_view();

// Page init
$socios_view->Page_Init();

// Page main
$socios_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$socios_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($socios->Export == "") { ?>
<script type="text/javascript">

// Page object
var socios_view = new ew_Page("socios_view");
socios_view.PageID = "view"; // Page ID
var EW_PAGE_ID = socios_view.PageID; // For backward compatibility

// Form object
var fsociosview = new ew_Form("fsociosview");

// Form_CustomValidate event
fsociosview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsociosview.ValidateRequired = true;
<?php } else { ?>
fsociosview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsociosview.Lists["x_socio"] = {"LinkField":"x_cod_pessoa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsociosview.Lists["x_cod_empresa"] = {"LinkField":"x_cod_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_empresa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($socios->Export == "") { ?>
<div class="ewToolbar">
<?php if ($socios->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $socios_view->ExportOptions->Render("body") ?>
<?php
	foreach ($socios_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if ($socios->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $socios_view->ShowPageHeader(); ?>
<?php
$socios_view->ShowMessage();
?>
<?php if ($socios->Export == "") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($socios_view->Pager)) $socios_view->Pager = new cPrevNextPager($socios_view->StartRec, $socios_view->DisplayRecs, $socios_view->TotalRecs) ?>
<?php if ($socios_view->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($socios_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $socios_view->PageUrl() ?>start=<?php echo $socios_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($socios_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $socios_view->PageUrl() ?>start=<?php echo $socios_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $socios_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($socios_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $socios_view->PageUrl() ?>start=<?php echo $socios_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($socios_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $socios_view->PageUrl() ?>start=<?php echo $socios_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $socios_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fsociosview" id="fsociosview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($socios_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $socios_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="socios">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($socios->cod_socio->Visible) { // cod_socio ?>
	<tr id="r_cod_socio">
		<td><span id="elh_socios_cod_socio"><?php echo $socios->cod_socio->FldCaption() ?></span></td>
		<td<?php echo $socios->cod_socio->CellAttributes() ?>>
<span id="el_socios_cod_socio">
<span<?php echo $socios->cod_socio->ViewAttributes() ?>>
<?php echo $socios->cod_socio->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($socios->socio->Visible) { // socio ?>
	<tr id="r_socio">
		<td><span id="elh_socios_socio"><?php echo $socios->socio->FldCaption() ?></span></td>
		<td<?php echo $socios->socio->CellAttributes() ?>>
<span id="el_socios_socio">
<span<?php echo $socios->socio->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($socios->socio->ViewValue)) && $socios->socio->LinkAttributes() <> "") { ?>
<a<?php echo $socios->socio->LinkAttributes() ?>><?php echo $socios->socio->ViewValue ?></a>
<?php } else { ?>
<?php echo $socios->socio->ViewValue ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($socios->cod_empresa->Visible) { // cod_empresa ?>
	<tr id="r_cod_empresa">
		<td><span id="elh_socios_cod_empresa"><?php echo $socios->cod_empresa->FldCaption() ?></span></td>
		<td<?php echo $socios->cod_empresa->CellAttributes() ?>>
<span id="el_socios_cod_empresa">
<span<?php echo $socios->cod_empresa->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($socios->cod_empresa->ViewValue)) && $socios->cod_empresa->LinkAttributes() <> "") { ?>
<a<?php echo $socios->cod_empresa->LinkAttributes() ?>><?php echo $socios->cod_empresa->ViewValue ?></a>
<?php } else { ?>
<?php echo $socios->cod_empresa->ViewValue ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($socios->dt_cadastro->Visible) { // dt_cadastro ?>
	<tr id="r_dt_cadastro">
		<td><span id="elh_socios_dt_cadastro"><?php echo $socios->dt_cadastro->FldCaption() ?></span></td>
		<td<?php echo $socios->dt_cadastro->CellAttributes() ?>>
<span id="el_socios_dt_cadastro">
<span<?php echo $socios->dt_cadastro->ViewAttributes() ?>>
<?php echo $socios->dt_cadastro->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($socios->validade->Visible) { // validade ?>
	<tr id="r_validade">
		<td><span id="elh_socios_validade"><?php echo $socios->validade->FldCaption() ?></span></td>
		<td<?php echo $socios->validade->CellAttributes() ?>>
<span id="el_socios_validade">
<span<?php echo $socios->validade->ViewAttributes() ?>>
<?php echo $socios->validade->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($socios->ativo->Visible) { // ativo ?>
	<tr id="r_ativo">
		<td><span id="elh_socios_ativo"><?php echo $socios->ativo->FldCaption() ?></span></td>
		<td<?php echo $socios->ativo->CellAttributes() ?>>
<span id="el_socios_ativo">
<span<?php echo $socios->ativo->ViewAttributes() ?>>
<?php echo $socios->ativo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($socios->funcao->Visible) { // funcao ?>
	<tr id="r_funcao">
		<td><span id="elh_socios_funcao"><?php echo $socios->funcao->FldCaption() ?></span></td>
		<td<?php echo $socios->funcao->CellAttributes() ?>>
<span id="el_socios_funcao">
<span<?php echo $socios->funcao->ViewAttributes() ?>>
<?php echo $socios->funcao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($socios->dt_carteira->Visible) { // dt_carteira ?>
	<tr id="r_dt_carteira">
		<td><span id="elh_socios_dt_carteira"><?php echo $socios->dt_carteira->FldCaption() ?></span></td>
		<td<?php echo $socios->dt_carteira->CellAttributes() ?>>
<span id="el_socios_dt_carteira">
<span<?php echo $socios->dt_carteira->ViewAttributes() ?>>
<?php echo $socios->dt_carteira->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($socios->dt_entrou_empresa->Visible) { // dt_entrou_empresa ?>
	<tr id="r_dt_entrou_empresa">
		<td><span id="elh_socios_dt_entrou_empresa"><?php echo $socios->dt_entrou_empresa->FldCaption() ?></span></td>
		<td<?php echo $socios->dt_entrou_empresa->CellAttributes() ?>>
<span id="el_socios_dt_entrou_empresa">
<span<?php echo $socios->dt_entrou_empresa->ViewAttributes() ?>>
<?php echo $socios->dt_entrou_empresa->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($socios->dt_entrou_categoria->Visible) { // dt_entrou_categoria ?>
	<tr id="r_dt_entrou_categoria">
		<td><span id="elh_socios_dt_entrou_categoria"><?php echo $socios->dt_entrou_categoria->FldCaption() ?></span></td>
		<td<?php echo $socios->dt_entrou_categoria->CellAttributes() ?>>
<span id="el_socios_dt_entrou_categoria">
<span<?php echo $socios->dt_entrou_categoria->ViewAttributes() ?>>
<?php echo $socios->dt_entrou_categoria->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($socios->acompanhante->Visible) { // acompanhante ?>
	<tr id="r_acompanhante">
		<td><span id="elh_socios_acompanhante"><?php echo $socios->acompanhante->FldCaption() ?></span></td>
		<td<?php echo $socios->acompanhante->CellAttributes() ?>>
<span id="el_socios_acompanhante">
<span<?php echo $socios->acompanhante->ViewAttributes() ?>>
<?php echo $socios->acompanhante->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($socios->dependentes->Visible) { // dependentes ?>
	<tr id="r_dependentes">
		<td><span id="elh_socios_dependentes"><?php echo $socios->dependentes->FldCaption() ?></span></td>
		<td<?php echo $socios->dependentes->CellAttributes() ?>>
<span id="el_socios_dependentes">
<span<?php echo $socios->dependentes->ViewAttributes() ?>>
<?php echo $socios->dependentes->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($socios->foto->Visible) { // foto ?>
	<tr id="r_foto">
		<td><span id="elh_socios_foto"><?php echo $socios->foto->FldCaption() ?></span></td>
		<td<?php echo $socios->foto->CellAttributes() ?>>
<span id="el_socios_foto">
<span>
<?php echo ew_GetFileViewTag($socios->foto, $socios->foto->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($socios->Export == "") { ?>
<?php if (!isset($socios_view->Pager)) $socios_view->Pager = new cPrevNextPager($socios_view->StartRec, $socios_view->DisplayRecs, $socios_view->TotalRecs) ?>
<?php if ($socios_view->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($socios_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $socios_view->PageUrl() ?>start=<?php echo $socios_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($socios_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $socios_view->PageUrl() ?>start=<?php echo $socios_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $socios_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($socios_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $socios_view->PageUrl() ?>start=<?php echo $socios_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($socios_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $socios_view->PageUrl() ?>start=<?php echo $socios_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $socios_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
fsociosview.Init();
</script>
<?php
$socios_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($socios->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$socios_view->Page_Terminate();
?>
