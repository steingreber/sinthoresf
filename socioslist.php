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

$socios_list = NULL; // Initialize page object first

class csocios_list extends csocios {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'socios';

	// Page object name
	var $PageObjName = 'socios_list';

	// Grid form hidden field names
	var $FormName = 'fsocioslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "sociosadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "sociosdelete.php";
		$this->MultiUpdateUrl = "sociosupdate.php";

		// Table object (permissoes)
		if (!isset($GLOBALS['permissoes'])) $GLOBALS['permissoes'] = new cpermissoes();

		// User table object (permissoes)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cpermissoes();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'socios', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set "checkbox" visible
		if (count($this->CustomActions) > 0)
			$this->ListOptions->Items["checkbox"]->Visible = TRUE;
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 30;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to grid edit mode
				if ($this->CurrentAction == "gridedit")
					$this->GridEditMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Grid Update
					if (($this->CurrentAction == "gridupdate" || $this->CurrentAction == "gridoverwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridedit") {
						if ($this->ValidateGridForm()) {
							$bGridUpdate = $this->GridUpdate();
						} else {
							$bGridUpdate = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridUpdate) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
						}
					}
				}
			}

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 30; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = EW_SELECT_LIMIT;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Set up number of records displayed per page
	function SetUpDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 30; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	//  Exit inline mode
	function ClearInlineMode() {
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Perform update to grid
	function GridUpdate() {
		global $conn, $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		if ($this->CurrentFilter == "")
			$this->CurrentFilter = "0=1";
		$sSql = $this->SQL();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}

		// Call Grid Updating event
		if (!$this->Grid_Updating($rsold)) {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("GridEditCancelled")); // Set grid edit cancelled message
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
		}
		return $bGridUpdate;
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->cod_socio->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->cod_socio->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_socio") && $objForm->HasValue("o_socio") && $this->socio->CurrentValue <> $this->socio->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_cod_empresa") && $objForm->HasValue("o_cod_empresa") && $this->cod_empresa->CurrentValue <> $this->cod_empresa->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_dt_cadastro") && $objForm->HasValue("o_dt_cadastro") && $this->dt_cadastro->CurrentValue <> $this->dt_cadastro->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_validade") && $objForm->HasValue("o_validade") && $this->validade->CurrentValue <> $this->validade->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ativo") && $objForm->HasValue("o_ativo") && $this->ativo->CurrentValue <> $this->ativo->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Get all form values of the grid
	function GetGridFormValues() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;
		$rows = array();

		// Loop through all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else {
					$rows[] = $this->GetFieldValues("FormValue"); // Return row as array
				}
			}
		}
		return $rows; // Return as array of array
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->socio, $Default, FALSE); // socio
		$this->BuildSearchSql($sWhere, $this->validade, $Default, FALSE); // validade
		$this->BuildSearchSql($sWhere, $this->ativo, $Default, FALSE); // ativo

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->socio->AdvancedSearch->Save(); // socio
			$this->validade->AdvancedSearch->Save(); // validade
			$this->ativo->AdvancedSearch->Save(); // ativo
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Check if search parm exists
	function CheckSearchParms() {
		if ($this->socio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->validade->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ativo->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->socio->AdvancedSearch->UnsetSession();
		$this->validade->AdvancedSearch->UnsetSession();
		$this->ativo->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->socio->AdvancedSearch->Load();
		$this->validade->AdvancedSearch->Load();
		$this->ativo->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->cod_socio); // cod_socio
			$this->UpdateSort($this->socio); // socio
			$this->UpdateSort($this->cod_empresa); // cod_empresa
			$this->UpdateSort($this->dt_cadastro); // dt_cadastro
			$this->UpdateSort($this->validade); // validade
			$this->UpdateSort($this->ativo); // ativo
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
				$this->cod_socio->setSort("DESC");
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->setSessionOrderByList($sOrderBy);
				$this->cod_socio->setSort("");
				$this->socio->setSort("");
				$this->cod_empresa->setSort("");
				$this->dt_cadastro->setSort("");
				$this->validade->setSort("");
				$this->ativo->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = TRUE;
			$item->Visible = FALSE; // Default hidden
		}

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = TRUE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;

		// "detailreport_rel_carteira_socio_"
		$item = &$this->ListOptions->Add("detailreport_rel_carteira_socio_");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'rel_carteira_socio_') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;

		// "detailreport_rel_carteira_clube_"
		$item = &$this->ListOptions->Add("detailreport_rel_carteira_clube_");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'rel_carteira_clube_') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;

		// "detailreport_rel_ficha_cadastral_"
		$item = &$this->ListOptions->Add("detailreport_rel_ficha_cadastral_");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'rel_ficha_cadastral_') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;

		// "detailreport_rel_recibo_socio_"
		$item = &$this->ListOptions->Add("detailreport_rel_recibo_socio_");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'rel_recibo_socio_') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;

		// "detailreport_rel_convite_festa_"
		$item = &$this->ListOptions->Add("detailreport_rel_convite_festa_");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'rel_convite_festa_') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssStyle = "white-space: nowrap;";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = TRUE;
			$item->ShowInButtonGroup = FALSE;
		}

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				if (!$Security->CanDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"javascript:void(0);\" onclick=\"ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . " onclick=\"ew_ClickDelete(this);return ew_ConfirmDelete(ewLanguage.Phrase('DeleteConfirmMsg'), this);\"" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detailreport_rel_carteira_socio_"
		$oListOpt = &$this->ListOptions->Items["detailreport_rel_carteira_socio_"];
		if ($Security->AllowList(CurrentProjectID() . 'rel_carteira_socio_')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("rel_carteira_socio_", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink\" href=\"" . ew_HtmlEncode("rel_carteira_socio_report.php?" . EW_TABLE_SHOW_MASTER . "=socios&fk_socio=" . urlencode(strval($this->socio->CurrentValue)) . "") . "\">" . $body . "</a>";
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detailreport_rel_carteira_clube_"
		$oListOpt = &$this->ListOptions->Items["detailreport_rel_carteira_clube_"];
		if ($Security->AllowList(CurrentProjectID() . 'rel_carteira_clube_')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("rel_carteira_clube_", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink\" href=\"" . ew_HtmlEncode("rel_carteira_clube_report.php?" . EW_TABLE_SHOW_MASTER . "=socios&fk_socio=" . urlencode(strval($this->socio->CurrentValue)) . "") . "\">" . $body . "</a>";
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detailreport_rel_ficha_cadastral_"
		$oListOpt = &$this->ListOptions->Items["detailreport_rel_ficha_cadastral_"];
		if ($Security->AllowList(CurrentProjectID() . 'rel_ficha_cadastral_')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("rel_ficha_cadastral_", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink\" href=\"" . ew_HtmlEncode("rel_ficha_cadastral_report.php?" . EW_TABLE_SHOW_MASTER . "=socios&fk_socio=" . urlencode(strval($this->socio->CurrentValue)) . "") . "\">" . $body . "</a>";
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detailreport_rel_recibo_socio_"
		$oListOpt = &$this->ListOptions->Items["detailreport_rel_recibo_socio_"];
		if ($Security->AllowList(CurrentProjectID() . 'rel_recibo_socio_')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("rel_recibo_socio_", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink\" href=\"" . ew_HtmlEncode("rel_recibo_socio_report.php?" . EW_TABLE_SHOW_MASTER . "=socios&fk_socio=" . urlencode(strval($this->socio->CurrentValue)) . "") . "\">" . $body . "</a>";
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detailreport_rel_convite_festa_"
		$oListOpt = &$this->ListOptions->Items["detailreport_rel_convite_festa_"];
		if ($Security->AllowList(CurrentProjectID() . 'rel_convite_festa_')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("rel_convite_festa_", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink\" href=\"" . ew_HtmlEncode("rel_convite_festa_report.php?" . EW_TABLE_SHOW_MASTER . "=socios&fk_socio=" . urlencode(strval($this->socio->CurrentValue)) . "") . "\">" . $body . "</a>";
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
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
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->cod_socio->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->cod_socio->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["detail"];
		$DetailTableLink = "";

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink) . "\">" . $Language->Phrase("AddMasterDetailLink") . "</a>";
			$item->Visible = ($DetailTableLink <> "" && $Security->CanAdd());

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detailadd_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->CanEdit());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = TRUE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "gridedit") { // Not grid add/edit mode
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fsocioslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
		} else { // Grid add/edit mode

			// Hide all options first
			foreach ($options as &$option)
				$option->HideAllOptions();
			if ($this->CurrentAction == "gridedit") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
					$item = &$option->Add("gridsave");
					$item->Body = "<a class=\"ewAction ewGridSave\" title=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit();\">" . $Language->Phrase("GridSaveLink") . "</a>";
					$item = &$option->Add("gridcancel");
					$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
		}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
			}
		}
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		if (ew_IsMobile())
			$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"sociossrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		else
			$item->Body = "<button type=\"button\" class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" onclick=\"ew_SearchDialogShow({lnk:this,url:'sociossrch.php'});\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		$item->Visible = TRUE;

		// Search highlight button
		$item = &$this->SearchOptions->Add("searchhighlight");
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewHighlight active\" title=\"" . $Language->Phrase("Highlight") . "\" data-caption=\"" . $Language->Phrase("Highlight") . "\" data-toggle=\"button\" data-form=\"fsocioslistsrch\" data-name=\"" . $this->HighlightName() . "\">" . $Language->Phrase("HighlightBtn") . "</button>";
		$item->Visible = ($this->SearchWhere <> "" && $this->TotalRecs > 0);

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch())
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load default values
	function LoadDefaultValues() {
		$this->cod_socio->CurrentValue = NULL;
		$this->cod_socio->OldValue = $this->cod_socio->CurrentValue;
		$this->socio->CurrentValue = NULL;
		$this->socio->OldValue = $this->socio->CurrentValue;
		$this->cod_empresa->CurrentValue = NULL;
		$this->cod_empresa->OldValue = $this->cod_empresa->CurrentValue;
		$this->dt_cadastro->CurrentValue = date('d/m/Y');
		$this->dt_cadastro->OldValue = $this->dt_cadastro->CurrentValue;
		$this->validade->CurrentValue = date('d/m/Y', strtotime('+182 days'));
		$this->validade->OldValue = $this->validade->CurrentValue;
		$this->ativo->CurrentValue = 1;
		$this->ativo->OldValue = $this->ativo->CurrentValue;
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// socio

		$this->socio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_socio"]);
		if ($this->socio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->socio->AdvancedSearch->SearchOperator = @$_GET["z_socio"];

		// validade
		$this->validade->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_validade"]);
		if ($this->validade->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->validade->AdvancedSearch->SearchOperator = @$_GET["z_validade"];
		$this->validade->AdvancedSearch->SearchCondition = @$_GET["v_validade"];
		$this->validade->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_validade"]);
		if ($this->validade->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->validade->AdvancedSearch->SearchOperator2 = @$_GET["w_validade"];

		// ativo
		$this->ativo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ativo"]);
		if ($this->ativo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ativo->AdvancedSearch->SearchOperator = @$_GET["z_ativo"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->cod_socio->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->cod_socio->setFormValue($objForm->GetValue("x_cod_socio"));
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
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->cod_socio->CurrentValue = $this->cod_socio->FormValue;
		$this->socio->CurrentValue = $this->socio->FormValue;
		$this->cod_empresa->CurrentValue = $this->cod_empresa->FormValue;
		$this->dt_cadastro->CurrentValue = $this->dt_cadastro->FormValue;
		$this->dt_cadastro->CurrentValue = ew_UnFormatDateTime($this->dt_cadastro->CurrentValue, 7);
		$this->validade->CurrentValue = $this->validade->FormValue;
		$this->validade->CurrentValue = ew_UnFormatDateTime($this->validade->CurrentValue, 7);
		$this->ativo->CurrentValue = $this->ativo->FormValue;
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
			if ($this->Export == "")
				$this->socio->ViewValue = ew_Highlight($this->HighlightName(), $this->socio->ViewValue, "", "", $this->socio->AdvancedSearch->getValue("x"), "");

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// cod_socio
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

			// Edit refer script
			// cod_socio

			$this->cod_socio->HrefValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// cod_socio
			$this->cod_socio->EditAttrs["class"] = "form-control";
			$this->cod_socio->EditCustomAttributes = "";
			$this->cod_socio->EditValue = $this->cod_socio->CurrentValue;
			$this->cod_socio->ViewCustomAttributes = "";

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

			// Edit refer script
			// cod_socio

			$this->cod_socio->HrefValue = "";

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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['cod_socio'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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
			$this->foto->OldUploadPath = sistema;
			$this->foto->UploadPath = $this->foto->OldUploadPath;
			$rsnew = array();

			// socio
			$this->socio->SetDbValueDef($rsnew, $this->socio->CurrentValue, 0, $this->socio->ReadOnly);

			// cod_empresa
			$this->cod_empresa->SetDbValueDef($rsnew, $this->cod_empresa->CurrentValue, 0, $this->cod_empresa->ReadOnly);

			// dt_cadastro
			$this->dt_cadastro->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_cadastro->CurrentValue, 7), NULL, $this->dt_cadastro->ReadOnly);

			// validade
			$this->validade->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->validade->CurrentValue, 7), NULL, $this->validade->ReadOnly);

			// ativo
			$this->ativo->SetDbValueDef($rsnew, $this->ativo->CurrentValue, 0, $this->ativo->ReadOnly);

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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

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
			$this->cod_socio->setDbValue($conn->Insert_ID());
			$rsnew['cod_socio'] = $this->cod_socio->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->socio->AdvancedSearch->Load();
		$this->validade->AdvancedSearch->Load();
		$this->ativo->AdvancedSearch->Load();
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
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_socios\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_socios',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fsocioslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = EW_SELECT_LIMIT;

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

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
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
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
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
if (!isset($socios_list)) $socios_list = new csocios_list();

// Page init
$socios_list->Page_Init();

// Page main
$socios_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$socios_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($socios->Export == "") { ?>
<script type="text/javascript">

// Page object
var socios_list = new ew_Page("socios_list");
socios_list.PageID = "list"; // Page ID
var EW_PAGE_ID = socios_list.PageID; // For backward compatibility

// Form object
var fsocioslist = new ew_Form("fsocioslist");
fsocioslist.FormKeyCountName = '<?php echo $socios_list->FormKeyCountName ?>';

// Validate form
fsocioslist.Validate = function() {
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

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fsocioslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsocioslist.ValidateRequired = true;
<?php } else { ?>
fsocioslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsocioslist.Lists["x_socio"] = {"LinkField":"x_cod_pessoa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsocioslist.Lists["x_cod_empresa"] = {"LinkField":"x_cod_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_empresa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fsocioslistsrch = new ew_Form("fsocioslistsrch");
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
<?php if ($socios_list->TotalRecs > 0 && $socios_list->ExportOptions->Visible()) { ?>
<?php $socios_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($socios_list->SearchOptions->Visible()) { ?>
<?php $socios_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($socios->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($socios_list->TotalRecs <= 0)
			$socios_list->TotalRecs = $socios->SelectRecordCount();
	} else {
		if (!$socios_list->Recordset && ($socios_list->Recordset = $socios_list->LoadRecordset()))
			$socios_list->TotalRecs = $socios_list->Recordset->RecordCount();
	}
	$socios_list->StartRec = 1;
	if ($socios_list->DisplayRecs <= 0 || ($socios->Export <> "" && $socios->ExportAll)) // Display all records
		$socios_list->DisplayRecs = $socios_list->TotalRecs;
	if (!($socios->Export <> "" && $socios->ExportAll))
		$socios_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$socios_list->Recordset = $socios_list->LoadRecordset($socios_list->StartRec-1, $socios_list->DisplayRecs);

	// Set no record found message
	if ($socios->CurrentAction == "" && $socios_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$socios_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($socios_list->SearchWhere == "0=101")
			$socios_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$socios_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$socios_list->RenderOtherOptions();
?>
<?php $socios_list->ShowPageHeader(); ?>
<?php
$socios_list->ShowMessage();
?>
<?php if ($socios_list->TotalRecs > 0 || $socios->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($socios->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($socios->CurrentAction <> "gridadd" && $socios->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($socios_list->Pager)) $socios_list->Pager = new cPrevNextPager($socios_list->StartRec, $socios_list->DisplayRecs, $socios_list->TotalRecs) ?>
<?php if ($socios_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($socios_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $socios_list->PageUrl() ?>start=<?php echo $socios_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($socios_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $socios_list->PageUrl() ?>start=<?php echo $socios_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $socios_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($socios_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $socios_list->PageUrl() ?>start=<?php echo $socios_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($socios_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $socios_list->PageUrl() ?>start=<?php echo $socios_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $socios_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $socios_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $socios_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $socios_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($socios_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="socios">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="30"<?php if ($socios_list->DisplayRecs == 30) { ?> selected="selected"<?php } ?>>30</option>
<option value="50"<?php if ($socios_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($socios_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($socios->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($socios_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fsocioslist" id="fsocioslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($socios_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $socios_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="socios">
<div id="gmp_socios" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($socios_list->TotalRecs > 0) { ?>
<table id="tbl_socioslist" class="table ewTable">
<?php echo $socios->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$socios->RowType = EW_ROWTYPE_HEADER;

// Render list options
$socios_list->RenderListOptions();

// Render list options (header, left)
$socios_list->ListOptions->Render("header", "left");
?>
<?php if ($socios->cod_socio->Visible) { // cod_socio ?>
	<?php if ($socios->SortUrl($socios->cod_socio) == "") { ?>
		<th data-name="cod_socio"><div id="elh_socios_cod_socio" class="socios_cod_socio"><div class="ewTableHeaderCaption"><?php echo $socios->cod_socio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cod_socio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $socios->SortUrl($socios->cod_socio) ?>',1);"><div id="elh_socios_cod_socio" class="socios_cod_socio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->cod_socio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->cod_socio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->cod_socio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->socio->Visible) { // socio ?>
	<?php if ($socios->SortUrl($socios->socio) == "") { ?>
		<th data-name="socio"><div id="elh_socios_socio" class="socios_socio"><div class="ewTableHeaderCaption"><?php echo $socios->socio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="socio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $socios->SortUrl($socios->socio) ?>',1);"><div id="elh_socios_socio" class="socios_socio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->socio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->socio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->socio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->cod_empresa->Visible) { // cod_empresa ?>
	<?php if ($socios->SortUrl($socios->cod_empresa) == "") { ?>
		<th data-name="cod_empresa"><div id="elh_socios_cod_empresa" class="socios_cod_empresa"><div class="ewTableHeaderCaption"><?php echo $socios->cod_empresa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cod_empresa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $socios->SortUrl($socios->cod_empresa) ?>',1);"><div id="elh_socios_cod_empresa" class="socios_cod_empresa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->cod_empresa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->cod_empresa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->cod_empresa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->dt_cadastro->Visible) { // dt_cadastro ?>
	<?php if ($socios->SortUrl($socios->dt_cadastro) == "") { ?>
		<th data-name="dt_cadastro"><div id="elh_socios_dt_cadastro" class="socios_dt_cadastro"><div class="ewTableHeaderCaption"><?php echo $socios->dt_cadastro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dt_cadastro"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $socios->SortUrl($socios->dt_cadastro) ?>',1);"><div id="elh_socios_dt_cadastro" class="socios_dt_cadastro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->dt_cadastro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->dt_cadastro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->dt_cadastro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->validade->Visible) { // validade ?>
	<?php if ($socios->SortUrl($socios->validade) == "") { ?>
		<th data-name="validade"><div id="elh_socios_validade" class="socios_validade"><div class="ewTableHeaderCaption"><?php echo $socios->validade->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="validade"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $socios->SortUrl($socios->validade) ?>',1);"><div id="elh_socios_validade" class="socios_validade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->validade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->validade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->validade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->ativo->Visible) { // ativo ?>
	<?php if ($socios->SortUrl($socios->ativo) == "") { ?>
		<th data-name="ativo"><div id="elh_socios_ativo" class="socios_ativo"><div class="ewTableHeaderCaption"><?php echo $socios->ativo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ativo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $socios->SortUrl($socios->ativo) ?>',1);"><div id="elh_socios_ativo" class="socios_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$socios_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($socios->ExportAll && $socios->Export <> "") {
	$socios_list->StopRec = $socios_list->TotalRecs;
} else {

	// Set the last record to display
	if ($socios_list->TotalRecs > $socios_list->StartRec + $socios_list->DisplayRecs - 1)
		$socios_list->StopRec = $socios_list->StartRec + $socios_list->DisplayRecs - 1;
	else
		$socios_list->StopRec = $socios_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($socios_list->FormKeyCountName) && ($socios->CurrentAction == "gridadd" || $socios->CurrentAction == "gridedit" || $socios->CurrentAction == "F")) {
		$socios_list->KeyCount = $objForm->GetValue($socios_list->FormKeyCountName);
		$socios_list->StopRec = $socios_list->StartRec + $socios_list->KeyCount - 1;
	}
}
$socios_list->RecCnt = $socios_list->StartRec - 1;
if ($socios_list->Recordset && !$socios_list->Recordset->EOF) {
	$socios_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $socios_list->StartRec > 1)
		$socios_list->Recordset->Move($socios_list->StartRec - 1);
} elseif (!$socios->AllowAddDeleteRow && $socios_list->StopRec == 0) {
	$socios_list->StopRec = $socios->GridAddRowCount;
}

// Initialize aggregate
$socios->RowType = EW_ROWTYPE_AGGREGATEINIT;
$socios->ResetAttrs();
$socios_list->RenderRow();
if ($socios->CurrentAction == "gridedit")
	$socios_list->RowIndex = 0;
while ($socios_list->RecCnt < $socios_list->StopRec) {
	$socios_list->RecCnt++;
	if (intval($socios_list->RecCnt) >= intval($socios_list->StartRec)) {
		$socios_list->RowCnt++;
		if ($socios->CurrentAction == "gridadd" || $socios->CurrentAction == "gridedit" || $socios->CurrentAction == "F") {
			$socios_list->RowIndex++;
			$objForm->Index = $socios_list->RowIndex;
			if ($objForm->HasValue($socios_list->FormActionName))
				$socios_list->RowAction = strval($objForm->GetValue($socios_list->FormActionName));
			elseif ($socios->CurrentAction == "gridadd")
				$socios_list->RowAction = "insert";
			else
				$socios_list->RowAction = "";
		}

		// Set up key count
		$socios_list->KeyCount = $socios_list->RowIndex;

		// Init row class and style
		$socios->ResetAttrs();
		$socios->CssClass = "";
		if ($socios->CurrentAction == "gridadd") {
			$socios_list->LoadDefaultValues(); // Load default values
		} else {
			$socios_list->LoadRowValues($socios_list->Recordset); // Load row values
		}
		$socios->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($socios->CurrentAction == "gridedit") { // Grid edit
			if ($socios->EventCancelled) {
				$socios_list->RestoreCurrentRowFormValues($socios_list->RowIndex); // Restore form values
			}
			if ($socios_list->RowAction == "insert")
				$socios->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$socios->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($socios->CurrentAction == "gridedit" && ($socios->RowType == EW_ROWTYPE_EDIT || $socios->RowType == EW_ROWTYPE_ADD) && $socios->EventCancelled) // Update failed
			$socios_list->RestoreCurrentRowFormValues($socios_list->RowIndex); // Restore form values
		if ($socios->RowType == EW_ROWTYPE_EDIT) // Edit row
			$socios_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$socios->RowAttrs = array_merge($socios->RowAttrs, array('data-rowindex'=>$socios_list->RowCnt, 'id'=>'r' . $socios_list->RowCnt . '_socios', 'data-rowtype'=>$socios->RowType));

		// Render row
		$socios_list->RenderRow();

		// Render list options
		$socios_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($socios_list->RowAction <> "delete" && $socios_list->RowAction <> "insertdelete" && !($socios_list->RowAction == "insert" && $socios->CurrentAction == "F" && $socios_list->EmptyRow())) {
?>
	<tr<?php echo $socios->RowAttributes() ?>>
<?php

// Render list options (body, left)
$socios_list->ListOptions->Render("body", "left", $socios_list->RowCnt);
?>
	<?php if ($socios->cod_socio->Visible) { // cod_socio ?>
		<td data-name="cod_socio"<?php echo $socios->cod_socio->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_cod_socio" name="o<?php echo $socios_list->RowIndex ?>_cod_socio" id="o<?php echo $socios_list->RowIndex ?>_cod_socio" value="<?php echo ew_HtmlEncode($socios->cod_socio->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_cod_socio" class="form-group socios_cod_socio">
<span<?php echo $socios->cod_socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->cod_socio->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_cod_socio" name="x<?php echo $socios_list->RowIndex ?>_cod_socio" id="x<?php echo $socios_list->RowIndex ?>_cod_socio" value="<?php echo ew_HtmlEncode($socios->cod_socio->CurrentValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->cod_socio->ViewAttributes() ?>>
<?php echo $socios->cod_socio->ListViewValue() ?></span>
<?php } ?>
<a id="<?php echo $socios_list->PageObjName . "_row_" . $socios_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($socios->socio->Visible) { // socio ?>
		<td data-name="socio"<?php echo $socios->socio->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_socio" class="form-group socios_socio">
<select data-field="x_socio" id="x<?php echo $socios_list->RowIndex ?>_socio" name="x<?php echo $socios_list->RowIndex ?>_socio"<?php echo $socios->socio->EditAttributes() ?>>
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
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $socios->socio->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $socios_list->RowIndex ?>_socio',url:'pessoasaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $socios_list->RowIndex ?>_socio"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $socios->socio->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `cod_pessoa`, `nome` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pessoas`";
$sWhereWrk = "";

// Call Lookup selecting
$socios->Lookup_Selecting($socios->socio, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `nome` ASC";
?>
<input type="hidden" name="s_x<?php echo $socios_list->RowIndex ?>_socio" id="s_x<?php echo $socios_list->RowIndex ?>_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`cod_pessoa` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_socio" name="o<?php echo $socios_list->RowIndex ?>_socio" id="o<?php echo $socios_list->RowIndex ?>_socio" value="<?php echo ew_HtmlEncode($socios->socio->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_socio" class="form-group socios_socio">
<select data-field="x_socio" id="x<?php echo $socios_list->RowIndex ?>_socio" name="x<?php echo $socios_list->RowIndex ?>_socio"<?php echo $socios->socio->EditAttributes() ?>>
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
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $socios->socio->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $socios_list->RowIndex ?>_socio',url:'pessoasaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $socios_list->RowIndex ?>_socio"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $socios->socio->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `cod_pessoa`, `nome` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pessoas`";
$sWhereWrk = "";

// Call Lookup selecting
$socios->Lookup_Selecting($socios->socio, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `nome` ASC";
?>
<input type="hidden" name="s_x<?php echo $socios_list->RowIndex ?>_socio" id="s_x<?php echo $socios_list->RowIndex ?>_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`cod_pessoa` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->socio->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($socios->socio->ListViewValue())) && $socios->socio->LinkAttributes() <> "") { ?>
<a<?php echo $socios->socio->LinkAttributes() ?>><?php echo $socios->socio->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $socios->socio->ListViewValue() ?>
<?php } ?>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->cod_empresa->Visible) { // cod_empresa ?>
		<td data-name="cod_empresa"<?php echo $socios->cod_empresa->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_cod_empresa" class="form-group socios_cod_empresa">
<select data-field="x_cod_empresa" id="x<?php echo $socios_list->RowIndex ?>_cod_empresa" name="x<?php echo $socios_list->RowIndex ?>_cod_empresa"<?php echo $socios->cod_empresa->EditAttributes() ?>>
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
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $socios->cod_empresa->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $socios_list->RowIndex ?>_cod_empresa',url:'empresasaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $socios_list->RowIndex ?>_cod_empresa"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $socios->cod_empresa->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
$sWhereWrk = "";

// Call Lookup selecting
$socios->Lookup_Selecting($socios->cod_empresa, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `nome_empresa` ASC";
?>
<input type="hidden" name="s_x<?php echo $socios_list->RowIndex ?>_cod_empresa" id="s_x<?php echo $socios_list->RowIndex ?>_cod_empresa" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`cod_empresa` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_cod_empresa" name="o<?php echo $socios_list->RowIndex ?>_cod_empresa" id="o<?php echo $socios_list->RowIndex ?>_cod_empresa" value="<?php echo ew_HtmlEncode($socios->cod_empresa->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_cod_empresa" class="form-group socios_cod_empresa">
<select data-field="x_cod_empresa" id="x<?php echo $socios_list->RowIndex ?>_cod_empresa" name="x<?php echo $socios_list->RowIndex ?>_cod_empresa"<?php echo $socios->cod_empresa->EditAttributes() ?>>
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
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $socios->cod_empresa->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $socios_list->RowIndex ?>_cod_empresa',url:'empresasaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $socios_list->RowIndex ?>_cod_empresa"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $socios->cod_empresa->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
$sWhereWrk = "";

// Call Lookup selecting
$socios->Lookup_Selecting($socios->cod_empresa, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `nome_empresa` ASC";
?>
<input type="hidden" name="s_x<?php echo $socios_list->RowIndex ?>_cod_empresa" id="s_x<?php echo $socios_list->RowIndex ?>_cod_empresa" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`cod_empresa` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->cod_empresa->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($socios->cod_empresa->ListViewValue())) && $socios->cod_empresa->LinkAttributes() <> "") { ?>
<a<?php echo $socios->cod_empresa->LinkAttributes() ?>><?php echo $socios->cod_empresa->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $socios->cod_empresa->ListViewValue() ?>
<?php } ?>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->dt_cadastro->Visible) { // dt_cadastro ?>
		<td data-name="dt_cadastro"<?php echo $socios->dt_cadastro->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_dt_cadastro" class="form-group socios_dt_cadastro">
<input type="text" data-field="x_dt_cadastro" name="x<?php echo $socios_list->RowIndex ?>_dt_cadastro" id="x<?php echo $socios_list->RowIndex ?>_dt_cadastro" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->dt_cadastro->PlaceHolder) ?>" value="<?php echo $socios->dt_cadastro->EditValue ?>"<?php echo $socios->dt_cadastro->EditAttributes() ?>>
<?php if (!$socios->dt_cadastro->ReadOnly && !$socios->dt_cadastro->Disabled && !isset($socios->dt_cadastro->EditAttrs["readonly"]) && !isset($socios->dt_cadastro->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsocioslist", "x<?php echo $socios_list->RowIndex ?>_dt_cadastro", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_dt_cadastro" name="o<?php echo $socios_list->RowIndex ?>_dt_cadastro" id="o<?php echo $socios_list->RowIndex ?>_dt_cadastro" value="<?php echo ew_HtmlEncode($socios->dt_cadastro->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_dt_cadastro" class="form-group socios_dt_cadastro">
<input type="text" data-field="x_dt_cadastro" name="x<?php echo $socios_list->RowIndex ?>_dt_cadastro" id="x<?php echo $socios_list->RowIndex ?>_dt_cadastro" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->dt_cadastro->PlaceHolder) ?>" value="<?php echo $socios->dt_cadastro->EditValue ?>"<?php echo $socios->dt_cadastro->EditAttributes() ?>>
<?php if (!$socios->dt_cadastro->ReadOnly && !$socios->dt_cadastro->Disabled && !isset($socios->dt_cadastro->EditAttrs["readonly"]) && !isset($socios->dt_cadastro->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsocioslist", "x<?php echo $socios_list->RowIndex ?>_dt_cadastro", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->dt_cadastro->ViewAttributes() ?>>
<?php echo $socios->dt_cadastro->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->validade->Visible) { // validade ?>
		<td data-name="validade"<?php echo $socios->validade->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_validade" class="form-group socios_validade">
<input type="text" data-field="x_validade" name="x<?php echo $socios_list->RowIndex ?>_validade" id="x<?php echo $socios_list->RowIndex ?>_validade" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->validade->PlaceHolder) ?>" value="<?php echo $socios->validade->EditValue ?>"<?php echo $socios->validade->EditAttributes() ?>>
<?php if (!$socios->validade->ReadOnly && !$socios->validade->Disabled && !isset($socios->validade->EditAttrs["readonly"]) && !isset($socios->validade->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsocioslist", "x<?php echo $socios_list->RowIndex ?>_validade", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_validade" name="o<?php echo $socios_list->RowIndex ?>_validade" id="o<?php echo $socios_list->RowIndex ?>_validade" value="<?php echo ew_HtmlEncode($socios->validade->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_validade" class="form-group socios_validade">
<input type="text" data-field="x_validade" name="x<?php echo $socios_list->RowIndex ?>_validade" id="x<?php echo $socios_list->RowIndex ?>_validade" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->validade->PlaceHolder) ?>" value="<?php echo $socios->validade->EditValue ?>"<?php echo $socios->validade->EditAttributes() ?>>
<?php if (!$socios->validade->ReadOnly && !$socios->validade->Disabled && !isset($socios->validade->EditAttrs["readonly"]) && !isset($socios->validade->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsocioslist", "x<?php echo $socios_list->RowIndex ?>_validade", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->validade->ViewAttributes() ?>>
<?php echo $socios->validade->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->ativo->Visible) { // ativo ?>
		<td data-name="ativo"<?php echo $socios->ativo->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_ativo" class="form-group socios_ativo">
<div id="tp_x<?php echo $socios_list->RowIndex ?>_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $socios_list->RowIndex ?>_ativo" id="x<?php echo $socios_list->RowIndex ?>_ativo" value="{value}"<?php echo $socios->ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $socios_list->RowIndex ?>_ativo" data-repeatcolumn="5" class="ewItemList">
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
<label class="radio-inline"><input type="radio" data-field="x_ativo" name="x<?php echo $socios_list->RowIndex ?>_ativo" id="x<?php echo $socios_list->RowIndex ?>_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<input type="hidden" data-field="x_ativo" name="o<?php echo $socios_list->RowIndex ?>_ativo" id="o<?php echo $socios_list->RowIndex ?>_ativo" value="<?php echo ew_HtmlEncode($socios->ativo->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_ativo" class="form-group socios_ativo">
<div id="tp_x<?php echo $socios_list->RowIndex ?>_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $socios_list->RowIndex ?>_ativo" id="x<?php echo $socios_list->RowIndex ?>_ativo" value="{value}"<?php echo $socios->ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $socios_list->RowIndex ?>_ativo" data-repeatcolumn="5" class="ewItemList">
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
<label class="radio-inline"><input type="radio" data-field="x_ativo" name="x<?php echo $socios_list->RowIndex ?>_ativo" id="x<?php echo $socios_list->RowIndex ?>_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->ativo->ViewAttributes() ?>>
<?php echo $socios->ativo->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$socios_list->ListOptions->Render("body", "right", $socios_list->RowCnt);
?>
	</tr>
<?php if ($socios->RowType == EW_ROWTYPE_ADD || $socios->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fsocioslist.UpdateOpts(<?php echo $socios_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($socios->CurrentAction <> "gridadd")
		if (!$socios_list->Recordset->EOF) $socios_list->Recordset->MoveNext();
}
?>
<?php
	if ($socios->CurrentAction == "gridadd" || $socios->CurrentAction == "gridedit") {
		$socios_list->RowIndex = '$rowindex$';
		$socios_list->LoadDefaultValues();

		// Set row properties
		$socios->ResetAttrs();
		$socios->RowAttrs = array_merge($socios->RowAttrs, array('data-rowindex'=>$socios_list->RowIndex, 'id'=>'r0_socios', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($socios->RowAttrs["class"], "ewTemplate");
		$socios->RowType = EW_ROWTYPE_ADD;

		// Render row
		$socios_list->RenderRow();

		// Render list options
		$socios_list->RenderListOptions();
		$socios_list->StartRowCnt = 0;
?>
	<tr<?php echo $socios->RowAttributes() ?>>
<?php

// Render list options (body, left)
$socios_list->ListOptions->Render("body", "left", $socios_list->RowIndex);
?>
	<?php if ($socios->cod_socio->Visible) { // cod_socio ?>
		<td data-name="cod_socio">
<input type="hidden" data-field="x_cod_socio" name="o<?php echo $socios_list->RowIndex ?>_cod_socio" id="o<?php echo $socios_list->RowIndex ?>_cod_socio" value="<?php echo ew_HtmlEncode($socios->cod_socio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->socio->Visible) { // socio ?>
		<td data-name="socio">
<span id="el$rowindex$_socios_socio" class="form-group socios_socio">
<select data-field="x_socio" id="x<?php echo $socios_list->RowIndex ?>_socio" name="x<?php echo $socios_list->RowIndex ?>_socio"<?php echo $socios->socio->EditAttributes() ?>>
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
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $socios->socio->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $socios_list->RowIndex ?>_socio',url:'pessoasaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $socios_list->RowIndex ?>_socio"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $socios->socio->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `cod_pessoa`, `nome` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pessoas`";
$sWhereWrk = "";

// Call Lookup selecting
$socios->Lookup_Selecting($socios->socio, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `nome` ASC";
?>
<input type="hidden" name="s_x<?php echo $socios_list->RowIndex ?>_socio" id="s_x<?php echo $socios_list->RowIndex ?>_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`cod_pessoa` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_socio" name="o<?php echo $socios_list->RowIndex ?>_socio" id="o<?php echo $socios_list->RowIndex ?>_socio" value="<?php echo ew_HtmlEncode($socios->socio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->cod_empresa->Visible) { // cod_empresa ?>
		<td data-name="cod_empresa">
<span id="el$rowindex$_socios_cod_empresa" class="form-group socios_cod_empresa">
<select data-field="x_cod_empresa" id="x<?php echo $socios_list->RowIndex ?>_cod_empresa" name="x<?php echo $socios_list->RowIndex ?>_cod_empresa"<?php echo $socios->cod_empresa->EditAttributes() ?>>
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
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $socios->cod_empresa->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $socios_list->RowIndex ?>_cod_empresa',url:'empresasaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $socios_list->RowIndex ?>_cod_empresa"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $socios->cod_empresa->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
$sWhereWrk = "";

// Call Lookup selecting
$socios->Lookup_Selecting($socios->cod_empresa, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `nome_empresa` ASC";
?>
<input type="hidden" name="s_x<?php echo $socios_list->RowIndex ?>_cod_empresa" id="s_x<?php echo $socios_list->RowIndex ?>_cod_empresa" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`cod_empresa` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_cod_empresa" name="o<?php echo $socios_list->RowIndex ?>_cod_empresa" id="o<?php echo $socios_list->RowIndex ?>_cod_empresa" value="<?php echo ew_HtmlEncode($socios->cod_empresa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->dt_cadastro->Visible) { // dt_cadastro ?>
		<td data-name="dt_cadastro">
<span id="el$rowindex$_socios_dt_cadastro" class="form-group socios_dt_cadastro">
<input type="text" data-field="x_dt_cadastro" name="x<?php echo $socios_list->RowIndex ?>_dt_cadastro" id="x<?php echo $socios_list->RowIndex ?>_dt_cadastro" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->dt_cadastro->PlaceHolder) ?>" value="<?php echo $socios->dt_cadastro->EditValue ?>"<?php echo $socios->dt_cadastro->EditAttributes() ?>>
<?php if (!$socios->dt_cadastro->ReadOnly && !$socios->dt_cadastro->Disabled && !isset($socios->dt_cadastro->EditAttrs["readonly"]) && !isset($socios->dt_cadastro->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsocioslist", "x<?php echo $socios_list->RowIndex ?>_dt_cadastro", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_dt_cadastro" name="o<?php echo $socios_list->RowIndex ?>_dt_cadastro" id="o<?php echo $socios_list->RowIndex ?>_dt_cadastro" value="<?php echo ew_HtmlEncode($socios->dt_cadastro->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->validade->Visible) { // validade ?>
		<td data-name="validade">
<span id="el$rowindex$_socios_validade" class="form-group socios_validade">
<input type="text" data-field="x_validade" name="x<?php echo $socios_list->RowIndex ?>_validade" id="x<?php echo $socios_list->RowIndex ?>_validade" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->validade->PlaceHolder) ?>" value="<?php echo $socios->validade->EditValue ?>"<?php echo $socios->validade->EditAttributes() ?>>
<?php if (!$socios->validade->ReadOnly && !$socios->validade->Disabled && !isset($socios->validade->EditAttrs["readonly"]) && !isset($socios->validade->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsocioslist", "x<?php echo $socios_list->RowIndex ?>_validade", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_validade" name="o<?php echo $socios_list->RowIndex ?>_validade" id="o<?php echo $socios_list->RowIndex ?>_validade" value="<?php echo ew_HtmlEncode($socios->validade->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->ativo->Visible) { // ativo ?>
		<td data-name="ativo">
<span id="el$rowindex$_socios_ativo" class="form-group socios_ativo">
<div id="tp_x<?php echo $socios_list->RowIndex ?>_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $socios_list->RowIndex ?>_ativo" id="x<?php echo $socios_list->RowIndex ?>_ativo" value="{value}"<?php echo $socios->ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $socios_list->RowIndex ?>_ativo" data-repeatcolumn="5" class="ewItemList">
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
<label class="radio-inline"><input type="radio" data-field="x_ativo" name="x<?php echo $socios_list->RowIndex ?>_ativo" id="x<?php echo $socios_list->RowIndex ?>_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<input type="hidden" data-field="x_ativo" name="o<?php echo $socios_list->RowIndex ?>_ativo" id="o<?php echo $socios_list->RowIndex ?>_ativo" value="<?php echo ew_HtmlEncode($socios->ativo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$socios_list->ListOptions->Render("body", "right", $socios_list->RowCnt);
?>
<script type="text/javascript">
fsocioslist.UpdateOpts(<?php echo $socios_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($socios->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $socios_list->FormKeyCountName ?>" id="<?php echo $socios_list->FormKeyCountName ?>" value="<?php echo $socios_list->KeyCount ?>">
<?php echo $socios_list->MultiSelectKey ?>
<?php } ?>
<?php if ($socios->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($socios_list->Recordset)
	$socios_list->Recordset->Close();
?>
<?php if ($socios->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($socios->CurrentAction <> "gridadd" && $socios->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($socios_list->Pager)) $socios_list->Pager = new cPrevNextPager($socios_list->StartRec, $socios_list->DisplayRecs, $socios_list->TotalRecs) ?>
<?php if ($socios_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($socios_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $socios_list->PageUrl() ?>start=<?php echo $socios_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($socios_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $socios_list->PageUrl() ?>start=<?php echo $socios_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $socios_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($socios_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $socios_list->PageUrl() ?>start=<?php echo $socios_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($socios_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $socios_list->PageUrl() ?>start=<?php echo $socios_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $socios_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $socios_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $socios_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $socios_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($socios_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="socios">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="30"<?php if ($socios_list->DisplayRecs == 30) { ?> selected="selected"<?php } ?>>30</option>
<option value="50"<?php if ($socios_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($socios_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($socios->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($socios_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($socios_list->TotalRecs == 0 && $socios->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($socios_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($socios->Export == "") { ?>
<script type="text/javascript">
fsocioslistsrch.Init();
fsocioslist.Init();
</script>
<?php } ?>
<?php
$socios_list->ShowPageFooter();
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
$socios_list->Page_Terminate();
?>
