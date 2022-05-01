<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "funcionariosinfo.php" ?>
<?php include_once "permissoesinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$funcionarios_view = NULL; // Initialize page object first

class cfuncionarios_view extends cfuncionarios {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'funcionarios';

	// Page object name
	var $PageObjName = 'funcionarios_view';

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

		// Table object (funcionarios)
		if (!isset($GLOBALS["funcionarios"]) || get_class($GLOBALS["funcionarios"]) == "cfuncionarios") {
			$GLOBALS["funcionarios"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["funcionarios"];
		}
		$KeyUrl = "";
		if (@$_GET["cod_func"] <> "") {
			$this->RecKey["cod_func"] = $_GET["cod_func"];
			$KeyUrl .= "&amp;cod_func=" . urlencode($this->RecKey["cod_func"]);
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
			define("EW_TABLE_NAME", 'funcionarios', TRUE);

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
		if (@$_GET["cod_func"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["cod_func"]);
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
		$this->cod_func->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $funcionarios;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($funcionarios);
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
			if (@$_GET["cod_func"] <> "") {
				$this->cod_func->setQueryStringValue($_GET["cod_func"]);
				$this->RecKey["cod_func"] = $this->cod_func->QueryStringValue;
			} elseif (@$_POST["cod_func"] <> "") {
				$this->cod_func->setFormValue($_POST["cod_func"]);
				$this->RecKey["cod_func"] = $this->cod_func->FormValue;
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
						$this->Page_Terminate("funcionarioslist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->cod_func->CurrentValue) == strval($this->Recordset->fields('cod_func'))) {
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
						$sReturnUrl = "funcionarioslist.php"; // No matching record, return to list
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
			$sReturnUrl = "funcionarioslist.php"; // Not page request, return to list
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

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a onclick=\"return ew_Confirm(ewLanguage.Phrase('DeleteConfirmMsg'));\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());
		$option = &$options["detail"];
		$DetailTableLink = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detailreport_rel_ficha_cadastro_"
		$item = &$option->Add("detailreport_rel_ficha_cadastro_");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("rel_ficha_cadastro_", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink\" href=\"" . ew_HtmlEncode("rel_ficha_cadastro_report.php?" . EW_TABLE_SHOW_MASTER . "=funcionarios&fk_cod_func=" . urlencode(strval($this->cod_func->CurrentValue)) . "") . "\">" . $body . "</a>";
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'rel_ficha_cadastro_');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "rel_ficha_cadastro_";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detailreport_rel_cart_socio_"
		$item = &$option->Add("detailreport_rel_cart_socio_");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("rel_cart_socio_", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink\" href=\"" . ew_HtmlEncode("rel_cart_socio_report.php?" . EW_TABLE_SHOW_MASTER . "=funcionarios&fk_cod_func=" . urlencode(strval($this->cod_func->CurrentValue)) . "") . "\">" . $body . "</a>";
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'rel_cart_socio_');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "rel_cart_socio_";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detailreport_rel_recibo_"
		$item = &$option->Add("detailreport_rel_recibo_");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("rel_recibo_", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink\" href=\"" . ew_HtmlEncode("rel_recibo_report.php?" . EW_TABLE_SHOW_MASTER . "=funcionarios&fk_cod_func=" . urlencode(strval($this->cod_func->CurrentValue)) . "") . "\">" . $body . "</a>";
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'rel_recibo_');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "rel_recibo_";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detailreport_rel_convite_"
		$item = &$option->Add("detailreport_rel_convite_");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("rel_convite_", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink\" href=\"" . ew_HtmlEncode("rel_convite_report.php?" . EW_TABLE_SHOW_MASTER . "=funcionarios&fk_cod_func=" . urlencode(strval($this->cod_func->CurrentValue)) . "") . "\">" . $body . "</a>";
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'rel_convite_');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "rel_convite_";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detailreport_rel_cart_clube_"
		$item = &$option->Add("detailreport_rel_cart_clube_");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("rel_cart_clube_", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink\" href=\"" . ew_HtmlEncode("rel_cart_clube_report.php?" . EW_TABLE_SHOW_MASTER . "=funcionarios&fk_cod_func=" . urlencode(strval($this->cod_func->CurrentValue)) . "") . "\">" . $body . "</a>";
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'rel_cart_clube_');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "rel_cart_clube_";
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
		$this->cod_func->setDbValue($rs->fields('cod_func'));
		$this->nome->setDbValue($rs->fields('nome'));
		$this->endereco->setDbValue($rs->fields('endereco'));
		$this->numero->setDbValue($rs->fields('numero'));
		$this->bairro->setDbValue($rs->fields('bairro'));
		$this->cidade->setDbValue($rs->fields('cidade'));
		$this->sexo->setDbValue($rs->fields('sexo'));
		$this->estado_civil->setDbValue($rs->fields('estado_civil'));
		$this->rg->setDbValue($rs->fields('rg'));
		$this->cpf->setDbValue($rs->fields('cpf'));
		$this->carteira_trabalho->setDbValue($rs->fields('carteira_trabalho'));
		$this->nacionalidade->setDbValue($rs->fields('nacionalidade'));
		$this->naturalidade->setDbValue($rs->fields('naturalidade'));
		$this->datanasc->setDbValue($rs->fields('datanasc'));
		$this->funcao->setDbValue($rs->fields('funcao'));
		$this->cod_empresa->setDbValue($rs->fields('cod_empresa'));
		$this->dt_entrou_empresa->setDbValue($rs->fields('dt_entrou_empresa'));
		$this->dt_entrou_categoria->setDbValue($rs->fields('dt_entrou_categoria'));
		$this->foto->Upload->DbValue = $rs->fields('foto');
		$this->foto->CurrentValue = $this->foto->Upload->DbValue;
		$this->ativo->setDbValue($rs->fields('ativo'));
		$this->dependentes->setDbValue($rs->fields('dependentes'));
		$this->dtcad->setDbValue($rs->fields('dtcad'));
		$this->dtcarteira->setDbValue($rs->fields('dtcarteira'));
		$this->telefone->setDbValue($rs->fields('telefone'));
		$this->acompanhante->setDbValue($rs->fields('acompanhante'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->cod_func->DbValue = $row['cod_func'];
		$this->nome->DbValue = $row['nome'];
		$this->endereco->DbValue = $row['endereco'];
		$this->numero->DbValue = $row['numero'];
		$this->bairro->DbValue = $row['bairro'];
		$this->cidade->DbValue = $row['cidade'];
		$this->sexo->DbValue = $row['sexo'];
		$this->estado_civil->DbValue = $row['estado_civil'];
		$this->rg->DbValue = $row['rg'];
		$this->cpf->DbValue = $row['cpf'];
		$this->carteira_trabalho->DbValue = $row['carteira_trabalho'];
		$this->nacionalidade->DbValue = $row['nacionalidade'];
		$this->naturalidade->DbValue = $row['naturalidade'];
		$this->datanasc->DbValue = $row['datanasc'];
		$this->funcao->DbValue = $row['funcao'];
		$this->cod_empresa->DbValue = $row['cod_empresa'];
		$this->dt_entrou_empresa->DbValue = $row['dt_entrou_empresa'];
		$this->dt_entrou_categoria->DbValue = $row['dt_entrou_categoria'];
		$this->foto->Upload->DbValue = $row['foto'];
		$this->ativo->DbValue = $row['ativo'];
		$this->dependentes->DbValue = $row['dependentes'];
		$this->dtcad->DbValue = $row['dtcad'];
		$this->dtcarteira->DbValue = $row['dtcarteira'];
		$this->telefone->DbValue = $row['telefone'];
		$this->acompanhante->DbValue = $row['acompanhante'];
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
			$sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->cod_empresa, $sWhereWrk);
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
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
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

			// acompanhante
			$this->acompanhante->ViewValue = $this->acompanhante->CurrentValue;
			$this->acompanhante->ViewCustomAttributes = "";

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

			// dt_entrou_empresa
			$this->dt_entrou_empresa->LinkCustomAttributes = "";
			$this->dt_entrou_empresa->HrefValue = "";
			$this->dt_entrou_empresa->TooltipValue = "";

			// dt_entrou_categoria
			$this->dt_entrou_categoria->LinkCustomAttributes = "";
			$this->dt_entrou_categoria->HrefValue = "";
			$this->dt_entrou_categoria->TooltipValue = "";

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
				$this->foto->LinkAttrs["data-rel"] = "funcionarios_x_foto";
				$this->foto->LinkAttrs["class"] = "ewLightbox";
			}

			// ativo
			$this->ativo->LinkCustomAttributes = "";
			$this->ativo->HrefValue = "";
			$this->ativo->TooltipValue = "";

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

			// acompanhante
			$this->acompanhante->LinkCustomAttributes = "";
			$this->acompanhante->HrefValue = "";
			$this->acompanhante->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_funcionarios\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_funcionarios',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.ffuncionariosview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Export detail records (rel_ficha_cadastro_)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("rel_ficha_cadastro_", explode(",", $this->getCurrentDetailTable()))) {
			global $rel_ficha_cadastro_;
			if (!isset($rel_ficha_cadastro_)) $rel_ficha_cadastro_ = new crel_ficha_cadastro_;
			$rsdetail = $rel_ficha_cadastro_->LoadRs($rel_ficha_cadastro_->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$rel_ficha_cadastro_->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}

		// Export detail records (rel_cart_socio_)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("rel_cart_socio_", explode(",", $this->getCurrentDetailTable()))) {
			global $rel_cart_socio_;
			if (!isset($rel_cart_socio_)) $rel_cart_socio_ = new crel_cart_socio_;
			$rsdetail = $rel_cart_socio_->LoadRs($rel_cart_socio_->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$rel_cart_socio_->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}

		// Export detail records (rel_recibo_)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("rel_recibo_", explode(",", $this->getCurrentDetailTable()))) {
			global $rel_recibo_;
			if (!isset($rel_recibo_)) $rel_recibo_ = new crel_recibo_;
			$rsdetail = $rel_recibo_->LoadRs($rel_recibo_->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$rel_recibo_->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}

		// Export detail records (rel_convite_)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("rel_convite_", explode(",", $this->getCurrentDetailTable()))) {
			global $rel_convite_;
			if (!isset($rel_convite_)) $rel_convite_ = new crel_convite_;
			$rsdetail = $rel_convite_->LoadRs($rel_convite_->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$rel_convite_->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}

		// Export detail records (rel_cart_clube_)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("rel_cart_clube_", explode(",", $this->getCurrentDetailTable()))) {
			global $rel_cart_clube_;
			if (!isset($rel_cart_clube_)) $rel_cart_clube_ = new crel_cart_clube_;
			$rsdetail = $rel_cart_clube_->LoadRs($rel_cart_clube_->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$rel_cart_clube_->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
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
		$Breadcrumb->Add("list", $this->TableVar, "funcionarioslist.php", "", $this->TableVar, TRUE);
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
if (!isset($funcionarios_view)) $funcionarios_view = new cfuncionarios_view();

// Page init
$funcionarios_view->Page_Init();

// Page main
$funcionarios_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$funcionarios_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($funcionarios->Export == "") { ?>
<script type="text/javascript">

// Page object
var funcionarios_view = new ew_Page("funcionarios_view");
funcionarios_view.PageID = "view"; // Page ID
var EW_PAGE_ID = funcionarios_view.PageID; // For backward compatibility

// Form object
var ffuncionariosview = new ew_Form("ffuncionariosview");

// Form_CustomValidate event
ffuncionariosview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffuncionariosview.ValidateRequired = true;
<?php } else { ?>
ffuncionariosview.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
ffuncionariosview.MultiPage = new ew_MultiPage("ffuncionariosview",
	[["x_cod_func",1],["x_nome",0],["x_endereco",2],["x_numero",2],["x_bairro",2],["x_cidade",2],["x_sexo",1],["x_estado_civil",1],["x_rg",3],["x_cpf",3],["x_carteira_trabalho",3],["x_nacionalidade",3],["x_naturalidade",3],["x_datanasc",1],["x_funcao",4],["x_cod_empresa",0],["x_dt_entrou_empresa",4],["x_dt_entrou_categoria",4],["x_foto",4],["x_ativo",1],["x_dependentes",4],["x_dtcad",4],["x_dtcarteira",4],["x_telefone",1],["x_acompanhante",4]]
);

// Dynamic selection lists
ffuncionariosview.Lists["x_cod_empresa"] = {"LinkField":"x_cod_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_empresa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($funcionarios->Export == "") { ?>
<div class="ewToolbar">
<?php if ($funcionarios->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $funcionarios_view->ExportOptions->Render("body") ?>
<?php
	foreach ($funcionarios_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if ($funcionarios->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $funcionarios_view->ShowPageHeader(); ?>
<?php
$funcionarios_view->ShowMessage();
?>
<?php if ($funcionarios->Export == "") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($funcionarios_view->Pager)) $funcionarios_view->Pager = new cPrevNextPager($funcionarios_view->StartRec, $funcionarios_view->DisplayRecs, $funcionarios_view->TotalRecs) ?>
<?php if ($funcionarios_view->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($funcionarios_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $funcionarios_view->PageUrl() ?>start=<?php echo $funcionarios_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($funcionarios_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $funcionarios_view->PageUrl() ?>start=<?php echo $funcionarios_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $funcionarios_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($funcionarios_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $funcionarios_view->PageUrl() ?>start=<?php echo $funcionarios_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($funcionarios_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $funcionarios_view->PageUrl() ?>start=<?php echo $funcionarios_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $funcionarios_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="ffuncionariosview" id="ffuncionariosview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($funcionarios_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $funcionarios_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="funcionarios">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($funcionarios->nome->Visible) { // nome ?>
	<tr id="r_nome">
		<td><span id="elh_funcionarios_nome"><?php echo $funcionarios->nome->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->nome->CellAttributes() ?>>
<span id="el_funcionarios_nome">
<span<?php echo $funcionarios->nome->ViewAttributes() ?>>
<?php echo $funcionarios->nome->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->cod_empresa->Visible) { // cod_empresa ?>
	<tr id="r_cod_empresa">
		<td><span id="elh_funcionarios_cod_empresa"><?php echo $funcionarios->cod_empresa->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->cod_empresa->CellAttributes() ?>>
<span id="el_funcionarios_cod_empresa">
<span<?php echo $funcionarios->cod_empresa->ViewAttributes() ?>>
<?php echo $funcionarios->cod_empresa->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($funcionarios->Export == "") { ?>
<div>
<div class="tabbable" id="funcionarios_view">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_funcionarios1" data-toggle="tab"><?php echo $funcionarios->PageCaption(1) ?></a></li>
		<li><a href="#tab_funcionarios2" data-toggle="tab"><?php echo $funcionarios->PageCaption(2) ?></a></li>
		<li><a href="#tab_funcionarios3" data-toggle="tab"><?php echo $funcionarios->PageCaption(3) ?></a></li>
		<li><a href="#tab_funcionarios4" data-toggle="tab"><?php echo $funcionarios->PageCaption(4) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
<?php if ($funcionarios->Export == "") { ?>
		<div class="tab-pane active" id="tab_funcionarios1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($funcionarios->cod_func->Visible) { // cod_func ?>
	<tr id="r_cod_func">
		<td><span id="elh_funcionarios_cod_func"><?php echo $funcionarios->cod_func->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->cod_func->CellAttributes() ?>>
<span id="el_funcionarios_cod_func">
<span<?php echo $funcionarios->cod_func->ViewAttributes() ?>>
<?php echo $funcionarios->cod_func->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->sexo->Visible) { // sexo ?>
	<tr id="r_sexo">
		<td><span id="elh_funcionarios_sexo"><?php echo $funcionarios->sexo->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->sexo->CellAttributes() ?>>
<span id="el_funcionarios_sexo">
<span<?php echo $funcionarios->sexo->ViewAttributes() ?>>
<?php echo $funcionarios->sexo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->estado_civil->Visible) { // estado_civil ?>
	<tr id="r_estado_civil">
		<td><span id="elh_funcionarios_estado_civil"><?php echo $funcionarios->estado_civil->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->estado_civil->CellAttributes() ?>>
<span id="el_funcionarios_estado_civil">
<span<?php echo $funcionarios->estado_civil->ViewAttributes() ?>>
<?php echo $funcionarios->estado_civil->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->datanasc->Visible) { // datanasc ?>
	<tr id="r_datanasc">
		<td><span id="elh_funcionarios_datanasc"><?php echo $funcionarios->datanasc->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->datanasc->CellAttributes() ?>>
<span id="el_funcionarios_datanasc">
<span<?php echo $funcionarios->datanasc->ViewAttributes() ?>>
<?php echo $funcionarios->datanasc->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->ativo->Visible) { // ativo ?>
	<tr id="r_ativo">
		<td><span id="elh_funcionarios_ativo"><?php echo $funcionarios->ativo->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->ativo->CellAttributes() ?>>
<span id="el_funcionarios_ativo">
<span<?php echo $funcionarios->ativo->ViewAttributes() ?>>
<?php echo $funcionarios->ativo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->telefone->Visible) { // telefone ?>
	<tr id="r_telefone">
		<td><span id="elh_funcionarios_telefone"><?php echo $funcionarios->telefone->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->telefone->CellAttributes() ?>>
<span id="el_funcionarios_telefone">
<span<?php echo $funcionarios->telefone->ViewAttributes() ?>>
<?php echo $funcionarios->telefone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($funcionarios->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($funcionarios->Export == "") { ?>
		<div class="tab-pane" id="tab_funcionarios2">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($funcionarios->endereco->Visible) { // endereco ?>
	<tr id="r_endereco">
		<td><span id="elh_funcionarios_endereco"><?php echo $funcionarios->endereco->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->endereco->CellAttributes() ?>>
<span id="el_funcionarios_endereco">
<span<?php echo $funcionarios->endereco->ViewAttributes() ?>>
<?php echo $funcionarios->endereco->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->numero->Visible) { // numero ?>
	<tr id="r_numero">
		<td><span id="elh_funcionarios_numero"><?php echo $funcionarios->numero->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->numero->CellAttributes() ?>>
<span id="el_funcionarios_numero">
<span<?php echo $funcionarios->numero->ViewAttributes() ?>>
<?php echo $funcionarios->numero->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->bairro->Visible) { // bairro ?>
	<tr id="r_bairro">
		<td><span id="elh_funcionarios_bairro"><?php echo $funcionarios->bairro->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->bairro->CellAttributes() ?>>
<span id="el_funcionarios_bairro">
<span<?php echo $funcionarios->bairro->ViewAttributes() ?>>
<?php echo $funcionarios->bairro->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->cidade->Visible) { // cidade ?>
	<tr id="r_cidade">
		<td><span id="elh_funcionarios_cidade"><?php echo $funcionarios->cidade->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->cidade->CellAttributes() ?>>
<span id="el_funcionarios_cidade">
<span<?php echo $funcionarios->cidade->ViewAttributes() ?>>
<?php echo $funcionarios->cidade->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($funcionarios->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($funcionarios->Export == "") { ?>
		<div class="tab-pane" id="tab_funcionarios3">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($funcionarios->rg->Visible) { // rg ?>
	<tr id="r_rg">
		<td><span id="elh_funcionarios_rg"><?php echo $funcionarios->rg->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->rg->CellAttributes() ?>>
<span id="el_funcionarios_rg">
<span<?php echo $funcionarios->rg->ViewAttributes() ?>>
<?php echo $funcionarios->rg->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->cpf->Visible) { // cpf ?>
	<tr id="r_cpf">
		<td><span id="elh_funcionarios_cpf"><?php echo $funcionarios->cpf->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->cpf->CellAttributes() ?>>
<span id="el_funcionarios_cpf">
<span<?php echo $funcionarios->cpf->ViewAttributes() ?>>
<?php echo $funcionarios->cpf->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->carteira_trabalho->Visible) { // carteira_trabalho ?>
	<tr id="r_carteira_trabalho">
		<td><span id="elh_funcionarios_carteira_trabalho"><?php echo $funcionarios->carteira_trabalho->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->carteira_trabalho->CellAttributes() ?>>
<span id="el_funcionarios_carteira_trabalho">
<span<?php echo $funcionarios->carteira_trabalho->ViewAttributes() ?>>
<?php echo $funcionarios->carteira_trabalho->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->nacionalidade->Visible) { // nacionalidade ?>
	<tr id="r_nacionalidade">
		<td><span id="elh_funcionarios_nacionalidade"><?php echo $funcionarios->nacionalidade->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->nacionalidade->CellAttributes() ?>>
<span id="el_funcionarios_nacionalidade">
<span<?php echo $funcionarios->nacionalidade->ViewAttributes() ?>>
<?php echo $funcionarios->nacionalidade->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->naturalidade->Visible) { // naturalidade ?>
	<tr id="r_naturalidade">
		<td><span id="elh_funcionarios_naturalidade"><?php echo $funcionarios->naturalidade->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->naturalidade->CellAttributes() ?>>
<span id="el_funcionarios_naturalidade">
<span<?php echo $funcionarios->naturalidade->ViewAttributes() ?>>
<?php echo $funcionarios->naturalidade->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($funcionarios->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($funcionarios->Export == "") { ?>
		<div class="tab-pane" id="tab_funcionarios4">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($funcionarios->funcao->Visible) { // funcao ?>
	<tr id="r_funcao">
		<td><span id="elh_funcionarios_funcao"><?php echo $funcionarios->funcao->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->funcao->CellAttributes() ?>>
<span id="el_funcionarios_funcao">
<span<?php echo $funcionarios->funcao->ViewAttributes() ?>>
<?php echo $funcionarios->funcao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->dt_entrou_empresa->Visible) { // dt_entrou_empresa ?>
	<tr id="r_dt_entrou_empresa">
		<td><span id="elh_funcionarios_dt_entrou_empresa"><?php echo $funcionarios->dt_entrou_empresa->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->dt_entrou_empresa->CellAttributes() ?>>
<span id="el_funcionarios_dt_entrou_empresa">
<span<?php echo $funcionarios->dt_entrou_empresa->ViewAttributes() ?>>
<?php echo $funcionarios->dt_entrou_empresa->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->dt_entrou_categoria->Visible) { // dt_entrou_categoria ?>
	<tr id="r_dt_entrou_categoria">
		<td><span id="elh_funcionarios_dt_entrou_categoria"><?php echo $funcionarios->dt_entrou_categoria->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->dt_entrou_categoria->CellAttributes() ?>>
<span id="el_funcionarios_dt_entrou_categoria">
<span<?php echo $funcionarios->dt_entrou_categoria->ViewAttributes() ?>>
<?php echo $funcionarios->dt_entrou_categoria->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->foto->Visible) { // foto ?>
	<tr id="r_foto">
		<td><span id="elh_funcionarios_foto"><?php echo $funcionarios->foto->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->foto->CellAttributes() ?>>
<span id="el_funcionarios_foto">
<span>
<?php echo ew_GetFileViewTag($funcionarios->foto, $funcionarios->foto->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->dependentes->Visible) { // dependentes ?>
	<tr id="r_dependentes">
		<td><span id="elh_funcionarios_dependentes"><?php echo $funcionarios->dependentes->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->dependentes->CellAttributes() ?>>
<span id="el_funcionarios_dependentes">
<span<?php echo $funcionarios->dependentes->ViewAttributes() ?>>
<?php echo $funcionarios->dependentes->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->dtcad->Visible) { // dtcad ?>
	<tr id="r_dtcad">
		<td><span id="elh_funcionarios_dtcad"><?php echo $funcionarios->dtcad->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->dtcad->CellAttributes() ?>>
<span id="el_funcionarios_dtcad">
<span<?php echo $funcionarios->dtcad->ViewAttributes() ?>>
<?php echo $funcionarios->dtcad->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->dtcarteira->Visible) { // dtcarteira ?>
	<tr id="r_dtcarteira">
		<td><span id="elh_funcionarios_dtcarteira"><?php echo $funcionarios->dtcarteira->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->dtcarteira->CellAttributes() ?>>
<span id="el_funcionarios_dtcarteira">
<span<?php echo $funcionarios->dtcarteira->ViewAttributes() ?>>
<?php echo $funcionarios->dtcarteira->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($funcionarios->acompanhante->Visible) { // acompanhante ?>
	<tr id="r_acompanhante">
		<td><span id="elh_funcionarios_acompanhante"><?php echo $funcionarios->acompanhante->FldCaption() ?></span></td>
		<td<?php echo $funcionarios->acompanhante->CellAttributes() ?>>
<span id="el_funcionarios_acompanhante">
<span<?php echo $funcionarios->acompanhante->ViewAttributes() ?>>
<?php echo $funcionarios->acompanhante->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($funcionarios->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($funcionarios->Export == "") { ?>
	</div>
</div>
</div>
<?php } ?>
<?php if ($funcionarios->Export == "") { ?>
<?php if (!isset($funcionarios_view->Pager)) $funcionarios_view->Pager = new cPrevNextPager($funcionarios_view->StartRec, $funcionarios_view->DisplayRecs, $funcionarios_view->TotalRecs) ?>
<?php if ($funcionarios_view->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($funcionarios_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $funcionarios_view->PageUrl() ?>start=<?php echo $funcionarios_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($funcionarios_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $funcionarios_view->PageUrl() ?>start=<?php echo $funcionarios_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $funcionarios_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($funcionarios_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $funcionarios_view->PageUrl() ?>start=<?php echo $funcionarios_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($funcionarios_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $funcionarios_view->PageUrl() ?>start=<?php echo $funcionarios_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $funcionarios_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
ffuncionariosview.Init();
</script>
<?php
$funcionarios_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($funcionarios->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$funcionarios_view->Page_Terminate();
?>
