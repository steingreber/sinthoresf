<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "view_socios_a_vencerinfo.php" ?>
<?php include_once "permissoesinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$view_socios_a_vencer_list = NULL; // Initialize page object first

class cview_socios_a_vencer_list extends cview_socios_a_vencer {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'view_socios_a_vencer';

	// Page object name
	var $PageObjName = 'view_socios_a_vencer_list';

	// Grid form hidden field names
	var $FormName = 'fview_socios_a_vencerlist';
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

		// Table object (view_socios_a_vencer)
		if (!isset($GLOBALS["view_socios_a_vencer"]) || get_class($GLOBALS["view_socios_a_vencer"]) == "cview_socios_a_vencer") {
			$GLOBALS["view_socios_a_vencer"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view_socios_a_vencer"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "view_socios_a_venceradd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "view_socios_a_vencerdelete.php";
		$this->MultiUpdateUrl = "view_socios_a_vencerupdate.php";

		// Table object (permissoes)
		if (!isset($GLOBALS['permissoes'])) $GLOBALS['permissoes'] = new cpermissoes();

		// User table object (permissoes)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cpermissoes();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'view_socios_a_vencer', TRUE);

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
		global $EW_EXPORT, $view_socios_a_vencer;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($view_socios_a_vencer);
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
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
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

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
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
		if ($objForm->HasValue("x_ativo") && $objForm->HasValue("o_ativo") && $this->ativo->CurrentValue <> $this->ativo->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_validade") && $objForm->HasValue("o_validade") && $this->validade->CurrentValue <> $this->validade->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_nome") && $objForm->HasValue("o_nome") && $this->nome->CurrentValue <> $this->nome->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_cpf") && $objForm->HasValue("o_cpf") && $this->cpf->CurrentValue <> $this->cpf->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_nome_empresa") && $objForm->HasValue("o_nome_empresa") && $this->nome_empresa->CurrentValue <> $this->nome_empresa->OldValue)
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

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->nome, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->cpf, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nome_empresa, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$sCond = $sDefCond;
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
						$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->cod_socio); // cod_socio
			$this->UpdateSort($this->ativo); // ativo
			$this->UpdateSort($this->validade); // validade
			$this->UpdateSort($this->nome); // nome
			$this->UpdateSort($this->cpf); // cpf
			$this->UpdateSort($this->nome_empresa); // nome_empresa
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
				$this->cod_socio->setSort("");
				$this->ativo->setSort("");
				$this->validade->setSort("");
				$this->nome->setSort("");
				$this->cpf->setSort("");
				$this->nome_empresa->setSort("");
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
				if (is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"javascript:void(0);\" onclick=\"ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fview_socios_a_vencerlist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
					$item->Visible = FALSE;
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

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fview_socios_a_vencerlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

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
		$this->ativo->CurrentValue = 1;
		$this->ativo->OldValue = $this->ativo->CurrentValue;
		$this->validade->CurrentValue = NULL;
		$this->validade->OldValue = $this->validade->CurrentValue;
		$this->nome->CurrentValue = NULL;
		$this->nome->OldValue = $this->nome->CurrentValue;
		$this->cpf->CurrentValue = NULL;
		$this->cpf->OldValue = $this->cpf->CurrentValue;
		$this->nome_empresa->CurrentValue = NULL;
		$this->nome_empresa->OldValue = $this->nome_empresa->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->cod_socio->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->cod_socio->setFormValue($objForm->GetValue("x_cod_socio"));
		if (!$this->ativo->FldIsDetailKey) {
			$this->ativo->setFormValue($objForm->GetValue("x_ativo"));
		}
		if (!$this->validade->FldIsDetailKey) {
			$this->validade->setFormValue($objForm->GetValue("x_validade"));
			$this->validade->CurrentValue = ew_UnFormatDateTime($this->validade->CurrentValue, 7);
		}
		if (!$this->nome->FldIsDetailKey) {
			$this->nome->setFormValue($objForm->GetValue("x_nome"));
		}
		if (!$this->cpf->FldIsDetailKey) {
			$this->cpf->setFormValue($objForm->GetValue("x_cpf"));
		}
		if (!$this->nome_empresa->FldIsDetailKey) {
			$this->nome_empresa->setFormValue($objForm->GetValue("x_nome_empresa"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->cod_socio->CurrentValue = $this->cod_socio->FormValue;
		$this->ativo->CurrentValue = $this->ativo->FormValue;
		$this->validade->CurrentValue = $this->validade->FormValue;
		$this->validade->CurrentValue = ew_UnFormatDateTime($this->validade->CurrentValue, 7);
		$this->nome->CurrentValue = $this->nome->FormValue;
		$this->cpf->CurrentValue = $this->cpf->FormValue;
		$this->nome_empresa->CurrentValue = $this->nome_empresa->FormValue;
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
		$this->ativo->setDbValue($rs->fields('ativo'));
		$this->validade->setDbValue($rs->fields('validade'));
		$this->nome->setDbValue($rs->fields('nome'));
		$this->cpf->setDbValue($rs->fields('cpf'));
		$this->nome_empresa->setDbValue($rs->fields('nome_empresa'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->cod_socio->DbValue = $row['cod_socio'];
		$this->ativo->DbValue = $row['ativo'];
		$this->validade->DbValue = $row['validade'];
		$this->nome->DbValue = $row['nome'];
		$this->cpf->DbValue = $row['cpf'];
		$this->nome_empresa->DbValue = $row['nome_empresa'];
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
		// ativo
		// validade
		// nome
		// cpf
		// nome_empresa

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// cod_socio
			$this->cod_socio->ViewValue = $this->cod_socio->CurrentValue;
			$this->cod_socio->ViewCustomAttributes = "";

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

			// validade
			$this->validade->ViewValue = $this->validade->CurrentValue;
			$this->validade->ViewValue = ew_FormatDateTime($this->validade->ViewValue, 7);
			$this->validade->ViewCustomAttributes = "";

			// nome
			$this->nome->ViewValue = $this->nome->CurrentValue;
			$this->nome->CssStyle = "font-weight: bold;";
			$this->nome->ViewCustomAttributes = "";

			// cpf
			$this->cpf->ViewValue = $this->cpf->CurrentValue;
			$this->cpf->ViewCustomAttributes = "";

			// nome_empresa
			$this->nome_empresa->ViewValue = $this->nome_empresa->CurrentValue;
			$this->nome_empresa->ViewCustomAttributes = "";

			// cod_socio
			$this->cod_socio->LinkCustomAttributes = "";
			$this->cod_socio->HrefValue = "";
			$this->cod_socio->TooltipValue = "";

			// ativo
			$this->ativo->LinkCustomAttributes = "";
			$this->ativo->HrefValue = "";
			$this->ativo->TooltipValue = "";

			// validade
			$this->validade->LinkCustomAttributes = "";
			$this->validade->HrefValue = "";
			$this->validade->TooltipValue = "";

			// nome
			$this->nome->LinkCustomAttributes = "";
			$this->nome->HrefValue = "";
			$this->nome->TooltipValue = "";

			// cpf
			$this->cpf->LinkCustomAttributes = "";
			$this->cpf->HrefValue = "";
			$this->cpf->TooltipValue = "";

			// nome_empresa
			$this->nome_empresa->LinkCustomAttributes = "";
			$this->nome_empresa->HrefValue = "";
			$this->nome_empresa->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// cod_socio
			// ativo

			$this->ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ativo->FldTagValue(1), $this->ativo->FldTagCaption(1) <> "" ? $this->ativo->FldTagCaption(1) : $this->ativo->FldTagValue(1));
			$arwrk[] = array($this->ativo->FldTagValue(2), $this->ativo->FldTagCaption(2) <> "" ? $this->ativo->FldTagCaption(2) : $this->ativo->FldTagValue(2));
			$this->ativo->EditValue = $arwrk;

			// validade
			$this->validade->EditAttrs["class"] = "form-control";
			$this->validade->EditCustomAttributes = "";
			$this->validade->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->validade->CurrentValue, 7));
			$this->validade->PlaceHolder = ew_RemoveHtml($this->validade->FldCaption());

			// nome
			$this->nome->EditAttrs["class"] = "form-control";
			$this->nome->EditCustomAttributes = "";
			$this->nome->EditValue = ew_HtmlEncode($this->nome->CurrentValue);
			$this->nome->PlaceHolder = ew_RemoveHtml($this->nome->FldCaption());

			// cpf
			$this->cpf->EditAttrs["class"] = "form-control";
			$this->cpf->EditCustomAttributes = "";
			$this->cpf->EditValue = ew_HtmlEncode($this->cpf->CurrentValue);
			$this->cpf->PlaceHolder = ew_RemoveHtml($this->cpf->FldCaption());

			// nome_empresa
			$this->nome_empresa->EditAttrs["class"] = "form-control";
			$this->nome_empresa->EditCustomAttributes = "";
			$this->nome_empresa->EditValue = ew_HtmlEncode($this->nome_empresa->CurrentValue);
			$this->nome_empresa->PlaceHolder = ew_RemoveHtml($this->nome_empresa->FldCaption());

			// Edit refer script
			// cod_socio

			$this->cod_socio->HrefValue = "";

			// ativo
			$this->ativo->HrefValue = "";

			// validade
			$this->validade->HrefValue = "";

			// nome
			$this->nome->HrefValue = "";

			// cpf
			$this->cpf->HrefValue = "";

			// nome_empresa
			$this->nome_empresa->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// cod_socio
			$this->cod_socio->EditAttrs["class"] = "form-control";
			$this->cod_socio->EditCustomAttributes = "";
			$this->cod_socio->EditValue = $this->cod_socio->CurrentValue;
			$this->cod_socio->ViewCustomAttributes = "";

			// ativo
			$this->ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ativo->FldTagValue(1), $this->ativo->FldTagCaption(1) <> "" ? $this->ativo->FldTagCaption(1) : $this->ativo->FldTagValue(1));
			$arwrk[] = array($this->ativo->FldTagValue(2), $this->ativo->FldTagCaption(2) <> "" ? $this->ativo->FldTagCaption(2) : $this->ativo->FldTagValue(2));
			$this->ativo->EditValue = $arwrk;

			// validade
			$this->validade->EditAttrs["class"] = "form-control";
			$this->validade->EditCustomAttributes = "";
			$this->validade->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->validade->CurrentValue, 7));
			$this->validade->PlaceHolder = ew_RemoveHtml($this->validade->FldCaption());

			// nome
			$this->nome->EditAttrs["class"] = "form-control";
			$this->nome->EditCustomAttributes = "";
			$this->nome->EditValue = ew_HtmlEncode($this->nome->CurrentValue);
			$this->nome->PlaceHolder = ew_RemoveHtml($this->nome->FldCaption());

			// cpf
			$this->cpf->EditAttrs["class"] = "form-control";
			$this->cpf->EditCustomAttributes = "";
			$this->cpf->EditValue = ew_HtmlEncode($this->cpf->CurrentValue);
			$this->cpf->PlaceHolder = ew_RemoveHtml($this->cpf->FldCaption());

			// nome_empresa
			$this->nome_empresa->EditAttrs["class"] = "form-control";
			$this->nome_empresa->EditCustomAttributes = "";
			$this->nome_empresa->EditValue = ew_HtmlEncode($this->nome_empresa->CurrentValue);
			$this->nome_empresa->PlaceHolder = ew_RemoveHtml($this->nome_empresa->FldCaption());

			// Edit refer script
			// cod_socio

			$this->cod_socio->HrefValue = "";

			// ativo
			$this->ativo->HrefValue = "";

			// validade
			$this->validade->HrefValue = "";

			// nome
			$this->nome->HrefValue = "";

			// cpf
			$this->cpf->HrefValue = "";

			// nome_empresa
			$this->nome_empresa->HrefValue = "";
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
		if ($this->ativo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ativo->FldCaption(), $this->ativo->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->validade->FormValue)) {
			ew_AddMessage($gsFormError, $this->validade->FldErrMsg());
		}
		if (!$this->nome->FldIsDetailKey && !is_null($this->nome->FormValue) && $this->nome->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nome->FldCaption(), $this->nome->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->cpf->FormValue)) {
			ew_AddMessage($gsFormError, $this->cpf->FldErrMsg());
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
		if ($this->cpf->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`cpf` = '" . ew_AdjustSql($this->cpf->CurrentValue) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->cpf->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->cpf->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
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

			// ativo
			$this->ativo->SetDbValueDef($rsnew, $this->ativo->CurrentValue, 0, $this->ativo->ReadOnly);

			// validade
			$this->validade->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->validade->CurrentValue, 7), NULL, $this->validade->ReadOnly);

			// nome
			$this->nome->SetDbValueDef($rsnew, $this->nome->CurrentValue, NULL, $this->nome->ReadOnly);

			// cpf
			$this->cpf->SetDbValueDef($rsnew, $this->cpf->CurrentValue, NULL, $this->cpf->ReadOnly);

			// nome_empresa
			$this->nome_empresa->SetDbValueDef($rsnew, $this->nome_empresa->CurrentValue, NULL, $this->nome_empresa->ReadOnly);

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
		if ($this->cpf->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(cpf = '" . ew_AdjustSql($this->cpf->CurrentValue) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->cpf->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->cpf->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// ativo
		$this->ativo->SetDbValueDef($rsnew, $this->ativo->CurrentValue, 0, strval($this->ativo->CurrentValue) == "");

		// validade
		$this->validade->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->validade->CurrentValue, 7), NULL, FALSE);

		// nome
		$this->nome->SetDbValueDef($rsnew, $this->nome->CurrentValue, NULL, FALSE);

		// cpf
		$this->cpf->SetDbValueDef($rsnew, $this->cpf->CurrentValue, NULL, FALSE);

		// nome_empresa
		$this->nome_empresa->SetDbValueDef($rsnew, $this->nome_empresa->CurrentValue, NULL, FALSE);

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
		$item->Body = "<button id=\"emf_view_socios_a_vencer\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_view_socios_a_vencer',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fview_socios_a_vencerlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($view_socios_a_vencer_list)) $view_socios_a_vencer_list = new cview_socios_a_vencer_list();

// Page init
$view_socios_a_vencer_list->Page_Init();

// Page main
$view_socios_a_vencer_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view_socios_a_vencer_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($view_socios_a_vencer->Export == "") { ?>
<script type="text/javascript">

// Page object
var view_socios_a_vencer_list = new ew_Page("view_socios_a_vencer_list");
view_socios_a_vencer_list.PageID = "list"; // Page ID
var EW_PAGE_ID = view_socios_a_vencer_list.PageID; // For backward compatibility

// Form object
var fview_socios_a_vencerlist = new ew_Form("fview_socios_a_vencerlist");
fview_socios_a_vencerlist.FormKeyCountName = '<?php echo $view_socios_a_vencer_list->FormKeyCountName ?>';

// Validate form
fview_socios_a_vencerlist.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_ativo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $view_socios_a_vencer->ativo->FldCaption(), $view_socios_a_vencer->ativo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_validade");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_socios_a_vencer->validade->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nome");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $view_socios_a_vencer->nome->FldCaption(), $view_socios_a_vencer->nome->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cpf");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_socios_a_vencer->cpf->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fview_socios_a_vencerlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_socios_a_vencerlist.ValidateRequired = true;
<?php } else { ?>
fview_socios_a_vencerlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fview_socios_a_vencerlistsrch = new ew_Form("fview_socios_a_vencerlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($view_socios_a_vencer->Export == "") { ?>
<div class="ewToolbar">
<?php if ($view_socios_a_vencer->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($view_socios_a_vencer_list->TotalRecs > 0 && $view_socios_a_vencer_list->ExportOptions->Visible()) { ?>
<?php $view_socios_a_vencer_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($view_socios_a_vencer_list->SearchOptions->Visible()) { ?>
<?php $view_socios_a_vencer_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($view_socios_a_vencer->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($view_socios_a_vencer_list->TotalRecs <= 0)
			$view_socios_a_vencer_list->TotalRecs = $view_socios_a_vencer->SelectRecordCount();
	} else {
		if (!$view_socios_a_vencer_list->Recordset && ($view_socios_a_vencer_list->Recordset = $view_socios_a_vencer_list->LoadRecordset()))
			$view_socios_a_vencer_list->TotalRecs = $view_socios_a_vencer_list->Recordset->RecordCount();
	}
	$view_socios_a_vencer_list->StartRec = 1;
	if ($view_socios_a_vencer_list->DisplayRecs <= 0 || ($view_socios_a_vencer->Export <> "" && $view_socios_a_vencer->ExportAll)) // Display all records
		$view_socios_a_vencer_list->DisplayRecs = $view_socios_a_vencer_list->TotalRecs;
	if (!($view_socios_a_vencer->Export <> "" && $view_socios_a_vencer->ExportAll))
		$view_socios_a_vencer_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$view_socios_a_vencer_list->Recordset = $view_socios_a_vencer_list->LoadRecordset($view_socios_a_vencer_list->StartRec-1, $view_socios_a_vencer_list->DisplayRecs);

	// Set no record found message
	if ($view_socios_a_vencer->CurrentAction == "" && $view_socios_a_vencer_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$view_socios_a_vencer_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($view_socios_a_vencer_list->SearchWhere == "0=101")
			$view_socios_a_vencer_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$view_socios_a_vencer_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$view_socios_a_vencer_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($view_socios_a_vencer->Export == "" && $view_socios_a_vencer->CurrentAction == "") { ?>
<form name="fview_socios_a_vencerlistsrch" id="fview_socios_a_vencerlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($view_socios_a_vencer_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fview_socios_a_vencerlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="view_socios_a_vencer">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($view_socios_a_vencer_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($view_socios_a_vencer_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $view_socios_a_vencer_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($view_socios_a_vencer_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($view_socios_a_vencer_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($view_socios_a_vencer_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($view_socios_a_vencer_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $view_socios_a_vencer_list->ShowPageHeader(); ?>
<?php
$view_socios_a_vencer_list->ShowMessage();
?>
<?php if ($view_socios_a_vencer_list->TotalRecs > 0 || $view_socios_a_vencer->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($view_socios_a_vencer->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($view_socios_a_vencer->CurrentAction <> "gridadd" && $view_socios_a_vencer->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($view_socios_a_vencer_list->Pager)) $view_socios_a_vencer_list->Pager = new cPrevNextPager($view_socios_a_vencer_list->StartRec, $view_socios_a_vencer_list->DisplayRecs, $view_socios_a_vencer_list->TotalRecs) ?>
<?php if ($view_socios_a_vencer_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($view_socios_a_vencer_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view_socios_a_vencer_list->PageUrl() ?>start=<?php echo $view_socios_a_vencer_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view_socios_a_vencer_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view_socios_a_vencer_list->PageUrl() ?>start=<?php echo $view_socios_a_vencer_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view_socios_a_vencer_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($view_socios_a_vencer_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view_socios_a_vencer_list->PageUrl() ?>start=<?php echo $view_socios_a_vencer_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view_socios_a_vencer_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view_socios_a_vencer_list->PageUrl() ?>start=<?php echo $view_socios_a_vencer_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view_socios_a_vencer_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view_socios_a_vencer_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view_socios_a_vencer_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view_socios_a_vencer_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($view_socios_a_vencer_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="view_socios_a_vencer">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="30"<?php if ($view_socios_a_vencer_list->DisplayRecs == 30) { ?> selected="selected"<?php } ?>>30</option>
<option value="50"<?php if ($view_socios_a_vencer_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($view_socios_a_vencer_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($view_socios_a_vencer->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_socios_a_vencer_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fview_socios_a_vencerlist" id="fview_socios_a_vencerlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view_socios_a_vencer_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view_socios_a_vencer_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view_socios_a_vencer">
<div id="gmp_view_socios_a_vencer" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($view_socios_a_vencer_list->TotalRecs > 0) { ?>
<table id="tbl_view_socios_a_vencerlist" class="table ewTable">
<?php echo $view_socios_a_vencer->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$view_socios_a_vencer->RowType = EW_ROWTYPE_HEADER;

// Render list options
$view_socios_a_vencer_list->RenderListOptions();

// Render list options (header, left)
$view_socios_a_vencer_list->ListOptions->Render("header", "left");
?>
<?php if ($view_socios_a_vencer->cod_socio->Visible) { // cod_socio ?>
	<?php if ($view_socios_a_vencer->SortUrl($view_socios_a_vencer->cod_socio) == "") { ?>
		<th data-name="cod_socio"><div id="elh_view_socios_a_vencer_cod_socio" class="view_socios_a_vencer_cod_socio"><div class="ewTableHeaderCaption"><?php echo $view_socios_a_vencer->cod_socio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cod_socio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_socios_a_vencer->SortUrl($view_socios_a_vencer->cod_socio) ?>',1);"><div id="elh_view_socios_a_vencer_cod_socio" class="view_socios_a_vencer_cod_socio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_socios_a_vencer->cod_socio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_socios_a_vencer->cod_socio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_socios_a_vencer->cod_socio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_socios_a_vencer->ativo->Visible) { // ativo ?>
	<?php if ($view_socios_a_vencer->SortUrl($view_socios_a_vencer->ativo) == "") { ?>
		<th data-name="ativo"><div id="elh_view_socios_a_vencer_ativo" class="view_socios_a_vencer_ativo"><div class="ewTableHeaderCaption"><?php echo $view_socios_a_vencer->ativo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ativo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_socios_a_vencer->SortUrl($view_socios_a_vencer->ativo) ?>',1);"><div id="elh_view_socios_a_vencer_ativo" class="view_socios_a_vencer_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_socios_a_vencer->ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_socios_a_vencer->ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_socios_a_vencer->ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_socios_a_vencer->validade->Visible) { // validade ?>
	<?php if ($view_socios_a_vencer->SortUrl($view_socios_a_vencer->validade) == "") { ?>
		<th data-name="validade"><div id="elh_view_socios_a_vencer_validade" class="view_socios_a_vencer_validade"><div class="ewTableHeaderCaption"><?php echo $view_socios_a_vencer->validade->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="validade"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_socios_a_vencer->SortUrl($view_socios_a_vencer->validade) ?>',1);"><div id="elh_view_socios_a_vencer_validade" class="view_socios_a_vencer_validade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_socios_a_vencer->validade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_socios_a_vencer->validade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_socios_a_vencer->validade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_socios_a_vencer->nome->Visible) { // nome ?>
	<?php if ($view_socios_a_vencer->SortUrl($view_socios_a_vencer->nome) == "") { ?>
		<th data-name="nome"><div id="elh_view_socios_a_vencer_nome" class="view_socios_a_vencer_nome"><div class="ewTableHeaderCaption"><?php echo $view_socios_a_vencer->nome->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nome"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_socios_a_vencer->SortUrl($view_socios_a_vencer->nome) ?>',1);"><div id="elh_view_socios_a_vencer_nome" class="view_socios_a_vencer_nome">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_socios_a_vencer->nome->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_socios_a_vencer->nome->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_socios_a_vencer->nome->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_socios_a_vencer->cpf->Visible) { // cpf ?>
	<?php if ($view_socios_a_vencer->SortUrl($view_socios_a_vencer->cpf) == "") { ?>
		<th data-name="cpf"><div id="elh_view_socios_a_vencer_cpf" class="view_socios_a_vencer_cpf"><div class="ewTableHeaderCaption"><?php echo $view_socios_a_vencer->cpf->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cpf"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_socios_a_vencer->SortUrl($view_socios_a_vencer->cpf) ?>',1);"><div id="elh_view_socios_a_vencer_cpf" class="view_socios_a_vencer_cpf">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_socios_a_vencer->cpf->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_socios_a_vencer->cpf->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_socios_a_vencer->cpf->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_socios_a_vencer->nome_empresa->Visible) { // nome_empresa ?>
	<?php if ($view_socios_a_vencer->SortUrl($view_socios_a_vencer->nome_empresa) == "") { ?>
		<th data-name="nome_empresa"><div id="elh_view_socios_a_vencer_nome_empresa" class="view_socios_a_vencer_nome_empresa"><div class="ewTableHeaderCaption"><?php echo $view_socios_a_vencer->nome_empresa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nome_empresa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_socios_a_vencer->SortUrl($view_socios_a_vencer->nome_empresa) ?>',1);"><div id="elh_view_socios_a_vencer_nome_empresa" class="view_socios_a_vencer_nome_empresa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_socios_a_vencer->nome_empresa->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_socios_a_vencer->nome_empresa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_socios_a_vencer->nome_empresa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$view_socios_a_vencer_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($view_socios_a_vencer->ExportAll && $view_socios_a_vencer->Export <> "") {
	$view_socios_a_vencer_list->StopRec = $view_socios_a_vencer_list->TotalRecs;
} else {

	// Set the last record to display
	if ($view_socios_a_vencer_list->TotalRecs > $view_socios_a_vencer_list->StartRec + $view_socios_a_vencer_list->DisplayRecs - 1)
		$view_socios_a_vencer_list->StopRec = $view_socios_a_vencer_list->StartRec + $view_socios_a_vencer_list->DisplayRecs - 1;
	else
		$view_socios_a_vencer_list->StopRec = $view_socios_a_vencer_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($view_socios_a_vencer_list->FormKeyCountName) && ($view_socios_a_vencer->CurrentAction == "gridadd" || $view_socios_a_vencer->CurrentAction == "gridedit" || $view_socios_a_vencer->CurrentAction == "F")) {
		$view_socios_a_vencer_list->KeyCount = $objForm->GetValue($view_socios_a_vencer_list->FormKeyCountName);
		$view_socios_a_vencer_list->StopRec = $view_socios_a_vencer_list->StartRec + $view_socios_a_vencer_list->KeyCount - 1;
	}
}
$view_socios_a_vencer_list->RecCnt = $view_socios_a_vencer_list->StartRec - 1;
if ($view_socios_a_vencer_list->Recordset && !$view_socios_a_vencer_list->Recordset->EOF) {
	$view_socios_a_vencer_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $view_socios_a_vencer_list->StartRec > 1)
		$view_socios_a_vencer_list->Recordset->Move($view_socios_a_vencer_list->StartRec - 1);
} elseif (!$view_socios_a_vencer->AllowAddDeleteRow && $view_socios_a_vencer_list->StopRec == 0) {
	$view_socios_a_vencer_list->StopRec = $view_socios_a_vencer->GridAddRowCount;
}

// Initialize aggregate
$view_socios_a_vencer->RowType = EW_ROWTYPE_AGGREGATEINIT;
$view_socios_a_vencer->ResetAttrs();
$view_socios_a_vencer_list->RenderRow();
if ($view_socios_a_vencer->CurrentAction == "gridedit")
	$view_socios_a_vencer_list->RowIndex = 0;
while ($view_socios_a_vencer_list->RecCnt < $view_socios_a_vencer_list->StopRec) {
	$view_socios_a_vencer_list->RecCnt++;
	if (intval($view_socios_a_vencer_list->RecCnt) >= intval($view_socios_a_vencer_list->StartRec)) {
		$view_socios_a_vencer_list->RowCnt++;
		if ($view_socios_a_vencer->CurrentAction == "gridadd" || $view_socios_a_vencer->CurrentAction == "gridedit" || $view_socios_a_vencer->CurrentAction == "F") {
			$view_socios_a_vencer_list->RowIndex++;
			$objForm->Index = $view_socios_a_vencer_list->RowIndex;
			if ($objForm->HasValue($view_socios_a_vencer_list->FormActionName))
				$view_socios_a_vencer_list->RowAction = strval($objForm->GetValue($view_socios_a_vencer_list->FormActionName));
			elseif ($view_socios_a_vencer->CurrentAction == "gridadd")
				$view_socios_a_vencer_list->RowAction = "insert";
			else
				$view_socios_a_vencer_list->RowAction = "";
		}

		// Set up key count
		$view_socios_a_vencer_list->KeyCount = $view_socios_a_vencer_list->RowIndex;

		// Init row class and style
		$view_socios_a_vencer->ResetAttrs();
		$view_socios_a_vencer->CssClass = "";
		if ($view_socios_a_vencer->CurrentAction == "gridadd") {
			$view_socios_a_vencer_list->LoadDefaultValues(); // Load default values
		} else {
			$view_socios_a_vencer_list->LoadRowValues($view_socios_a_vencer_list->Recordset); // Load row values
		}
		$view_socios_a_vencer->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($view_socios_a_vencer->CurrentAction == "gridedit") { // Grid edit
			if ($view_socios_a_vencer->EventCancelled) {
				$view_socios_a_vencer_list->RestoreCurrentRowFormValues($view_socios_a_vencer_list->RowIndex); // Restore form values
			}
			if ($view_socios_a_vencer_list->RowAction == "insert")
				$view_socios_a_vencer->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$view_socios_a_vencer->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($view_socios_a_vencer->CurrentAction == "gridedit" && ($view_socios_a_vencer->RowType == EW_ROWTYPE_EDIT || $view_socios_a_vencer->RowType == EW_ROWTYPE_ADD) && $view_socios_a_vencer->EventCancelled) // Update failed
			$view_socios_a_vencer_list->RestoreCurrentRowFormValues($view_socios_a_vencer_list->RowIndex); // Restore form values
		if ($view_socios_a_vencer->RowType == EW_ROWTYPE_EDIT) // Edit row
			$view_socios_a_vencer_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$view_socios_a_vencer->RowAttrs = array_merge($view_socios_a_vencer->RowAttrs, array('data-rowindex'=>$view_socios_a_vencer_list->RowCnt, 'id'=>'r' . $view_socios_a_vencer_list->RowCnt . '_view_socios_a_vencer', 'data-rowtype'=>$view_socios_a_vencer->RowType));

		// Render row
		$view_socios_a_vencer_list->RenderRow();

		// Render list options
		$view_socios_a_vencer_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($view_socios_a_vencer_list->RowAction <> "delete" && $view_socios_a_vencer_list->RowAction <> "insertdelete" && !($view_socios_a_vencer_list->RowAction == "insert" && $view_socios_a_vencer->CurrentAction == "F" && $view_socios_a_vencer_list->EmptyRow())) {
?>
	<tr<?php echo $view_socios_a_vencer->RowAttributes() ?>>
<?php

// Render list options (body, left)
$view_socios_a_vencer_list->ListOptions->Render("body", "left", $view_socios_a_vencer_list->RowCnt);
?>
	<?php if ($view_socios_a_vencer->cod_socio->Visible) { // cod_socio ?>
		<td data-name="cod_socio"<?php echo $view_socios_a_vencer->cod_socio->CellAttributes() ?>>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_cod_socio" name="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_cod_socio" id="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_cod_socio" value="<?php echo ew_HtmlEncode($view_socios_a_vencer->cod_socio->OldValue) ?>">
<?php } ?>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $view_socios_a_vencer_list->RowCnt ?>_view_socios_a_vencer_cod_socio" class="form-group view_socios_a_vencer_cod_socio">
<span<?php echo $view_socios_a_vencer->cod_socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_socios_a_vencer->cod_socio->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_cod_socio" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_cod_socio" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_cod_socio" value="<?php echo ew_HtmlEncode($view_socios_a_vencer->cod_socio->CurrentValue) ?>">
<?php } ?>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $view_socios_a_vencer->cod_socio->ViewAttributes() ?>>
<?php echo $view_socios_a_vencer->cod_socio->ListViewValue() ?></span>
<?php } ?>
<a id="<?php echo $view_socios_a_vencer_list->PageObjName . "_row_" . $view_socios_a_vencer_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($view_socios_a_vencer->ativo->Visible) { // ativo ?>
		<td data-name="ativo"<?php echo $view_socios_a_vencer->ativo->CellAttributes() ?>>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $view_socios_a_vencer_list->RowCnt ?>_view_socios_a_vencer_ativo" class="form-group view_socios_a_vencer_ativo">
<div id="tp_x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" value="{value}"<?php echo $view_socios_a_vencer->ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $view_socios_a_vencer->ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_socios_a_vencer->ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_ativo" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $view_socios_a_vencer->ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<input type="hidden" data-field="x_ativo" name="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" id="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" value="<?php echo ew_HtmlEncode($view_socios_a_vencer->ativo->OldValue) ?>">
<?php } ?>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $view_socios_a_vencer_list->RowCnt ?>_view_socios_a_vencer_ativo" class="form-group view_socios_a_vencer_ativo">
<div id="tp_x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" value="{value}"<?php echo $view_socios_a_vencer->ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $view_socios_a_vencer->ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_socios_a_vencer->ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_ativo" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $view_socios_a_vencer->ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php } ?>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $view_socios_a_vencer->ativo->ViewAttributes() ?>>
<?php echo $view_socios_a_vencer->ativo->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($view_socios_a_vencer->validade->Visible) { // validade ?>
		<td data-name="validade"<?php echo $view_socios_a_vencer->validade->CellAttributes() ?>>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $view_socios_a_vencer_list->RowCnt ?>_view_socios_a_vencer_validade" class="form-group view_socios_a_vencer_validade">
<input type="text" data-field="x_validade" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_validade" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_validade" size="10" maxlength="10" placeholder="<?php echo ew_HtmlEncode($view_socios_a_vencer->validade->PlaceHolder) ?>" value="<?php echo $view_socios_a_vencer->validade->EditValue ?>"<?php echo $view_socios_a_vencer->validade->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_validade" name="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_validade" id="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_validade" value="<?php echo ew_HtmlEncode($view_socios_a_vencer->validade->OldValue) ?>">
<?php } ?>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $view_socios_a_vencer_list->RowCnt ?>_view_socios_a_vencer_validade" class="form-group view_socios_a_vencer_validade">
<input type="text" data-field="x_validade" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_validade" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_validade" size="10" maxlength="10" placeholder="<?php echo ew_HtmlEncode($view_socios_a_vencer->validade->PlaceHolder) ?>" value="<?php echo $view_socios_a_vencer->validade->EditValue ?>"<?php echo $view_socios_a_vencer->validade->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $view_socios_a_vencer->validade->ViewAttributes() ?>>
<?php echo $view_socios_a_vencer->validade->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($view_socios_a_vencer->nome->Visible) { // nome ?>
		<td data-name="nome"<?php echo $view_socios_a_vencer->nome->CellAttributes() ?>>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $view_socios_a_vencer_list->RowCnt ?>_view_socios_a_vencer_nome" class="form-group view_socios_a_vencer_nome">
<input type="text" data-field="x_nome" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($view_socios_a_vencer->nome->PlaceHolder) ?>" value="<?php echo $view_socios_a_vencer->nome->EditValue ?>"<?php echo $view_socios_a_vencer->nome->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nome" name="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome" id="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome" value="<?php echo ew_HtmlEncode($view_socios_a_vencer->nome->OldValue) ?>">
<?php } ?>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $view_socios_a_vencer_list->RowCnt ?>_view_socios_a_vencer_nome" class="form-group view_socios_a_vencer_nome">
<input type="text" data-field="x_nome" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($view_socios_a_vencer->nome->PlaceHolder) ?>" value="<?php echo $view_socios_a_vencer->nome->EditValue ?>"<?php echo $view_socios_a_vencer->nome->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $view_socios_a_vencer->nome->ViewAttributes() ?>>
<?php echo $view_socios_a_vencer->nome->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($view_socios_a_vencer->cpf->Visible) { // cpf ?>
		<td data-name="cpf"<?php echo $view_socios_a_vencer->cpf->CellAttributes() ?>>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $view_socios_a_vencer_list->RowCnt ?>_view_socios_a_vencer_cpf" class="form-group view_socios_a_vencer_cpf">
<input type="text" data-field="x_cpf" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_cpf" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_cpf" size="12" maxlength="15" placeholder="<?php echo ew_HtmlEncode($view_socios_a_vencer->cpf->PlaceHolder) ?>" value="<?php echo $view_socios_a_vencer->cpf->EditValue ?>"<?php echo $view_socios_a_vencer->cpf->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_cpf" name="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_cpf" id="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_cpf" value="<?php echo ew_HtmlEncode($view_socios_a_vencer->cpf->OldValue) ?>">
<?php } ?>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $view_socios_a_vencer_list->RowCnt ?>_view_socios_a_vencer_cpf" class="form-group view_socios_a_vencer_cpf">
<input type="text" data-field="x_cpf" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_cpf" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_cpf" size="12" maxlength="15" placeholder="<?php echo ew_HtmlEncode($view_socios_a_vencer->cpf->PlaceHolder) ?>" value="<?php echo $view_socios_a_vencer->cpf->EditValue ?>"<?php echo $view_socios_a_vencer->cpf->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $view_socios_a_vencer->cpf->ViewAttributes() ?>>
<?php echo $view_socios_a_vencer->cpf->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($view_socios_a_vencer->nome_empresa->Visible) { // nome_empresa ?>
		<td data-name="nome_empresa"<?php echo $view_socios_a_vencer->nome_empresa->CellAttributes() ?>>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $view_socios_a_vencer_list->RowCnt ?>_view_socios_a_vencer_nome_empresa" class="form-group view_socios_a_vencer_nome_empresa">
<input type="text" data-field="x_nome_empresa" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome_empresa" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome_empresa" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($view_socios_a_vencer->nome_empresa->PlaceHolder) ?>" value="<?php echo $view_socios_a_vencer->nome_empresa->EditValue ?>"<?php echo $view_socios_a_vencer->nome_empresa->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nome_empresa" name="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome_empresa" id="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome_empresa" value="<?php echo ew_HtmlEncode($view_socios_a_vencer->nome_empresa->OldValue) ?>">
<?php } ?>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $view_socios_a_vencer_list->RowCnt ?>_view_socios_a_vencer_nome_empresa" class="form-group view_socios_a_vencer_nome_empresa">
<input type="text" data-field="x_nome_empresa" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome_empresa" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome_empresa" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($view_socios_a_vencer->nome_empresa->PlaceHolder) ?>" value="<?php echo $view_socios_a_vencer->nome_empresa->EditValue ?>"<?php echo $view_socios_a_vencer->nome_empresa->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $view_socios_a_vencer->nome_empresa->ViewAttributes() ?>>
<?php echo $view_socios_a_vencer->nome_empresa->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$view_socios_a_vencer_list->ListOptions->Render("body", "right", $view_socios_a_vencer_list->RowCnt);
?>
	</tr>
<?php if ($view_socios_a_vencer->RowType == EW_ROWTYPE_ADD || $view_socios_a_vencer->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fview_socios_a_vencerlist.UpdateOpts(<?php echo $view_socios_a_vencer_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($view_socios_a_vencer->CurrentAction <> "gridadd")
		if (!$view_socios_a_vencer_list->Recordset->EOF) $view_socios_a_vencer_list->Recordset->MoveNext();
}
?>
<?php
	if ($view_socios_a_vencer->CurrentAction == "gridadd" || $view_socios_a_vencer->CurrentAction == "gridedit") {
		$view_socios_a_vencer_list->RowIndex = '$rowindex$';
		$view_socios_a_vencer_list->LoadDefaultValues();

		// Set row properties
		$view_socios_a_vencer->ResetAttrs();
		$view_socios_a_vencer->RowAttrs = array_merge($view_socios_a_vencer->RowAttrs, array('data-rowindex'=>$view_socios_a_vencer_list->RowIndex, 'id'=>'r0_view_socios_a_vencer', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($view_socios_a_vencer->RowAttrs["class"], "ewTemplate");
		$view_socios_a_vencer->RowType = EW_ROWTYPE_ADD;

		// Render row
		$view_socios_a_vencer_list->RenderRow();

		// Render list options
		$view_socios_a_vencer_list->RenderListOptions();
		$view_socios_a_vencer_list->StartRowCnt = 0;
?>
	<tr<?php echo $view_socios_a_vencer->RowAttributes() ?>>
<?php

// Render list options (body, left)
$view_socios_a_vencer_list->ListOptions->Render("body", "left", $view_socios_a_vencer_list->RowIndex);
?>
	<?php if ($view_socios_a_vencer->cod_socio->Visible) { // cod_socio ?>
		<td data-name="cod_socio">
<input type="hidden" data-field="x_cod_socio" name="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_cod_socio" id="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_cod_socio" value="<?php echo ew_HtmlEncode($view_socios_a_vencer->cod_socio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($view_socios_a_vencer->ativo->Visible) { // ativo ?>
		<td data-name="ativo">
<span id="el$rowindex$_view_socios_a_vencer_ativo" class="form-group view_socios_a_vencer_ativo">
<div id="tp_x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" value="{value}"<?php echo $view_socios_a_vencer->ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $view_socios_a_vencer->ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_socios_a_vencer->ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_ativo" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $view_socios_a_vencer->ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<input type="hidden" data-field="x_ativo" name="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" id="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_ativo" value="<?php echo ew_HtmlEncode($view_socios_a_vencer->ativo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($view_socios_a_vencer->validade->Visible) { // validade ?>
		<td data-name="validade">
<span id="el$rowindex$_view_socios_a_vencer_validade" class="form-group view_socios_a_vencer_validade">
<input type="text" data-field="x_validade" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_validade" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_validade" size="10" maxlength="10" placeholder="<?php echo ew_HtmlEncode($view_socios_a_vencer->validade->PlaceHolder) ?>" value="<?php echo $view_socios_a_vencer->validade->EditValue ?>"<?php echo $view_socios_a_vencer->validade->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_validade" name="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_validade" id="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_validade" value="<?php echo ew_HtmlEncode($view_socios_a_vencer->validade->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($view_socios_a_vencer->nome->Visible) { // nome ?>
		<td data-name="nome">
<span id="el$rowindex$_view_socios_a_vencer_nome" class="form-group view_socios_a_vencer_nome">
<input type="text" data-field="x_nome" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($view_socios_a_vencer->nome->PlaceHolder) ?>" value="<?php echo $view_socios_a_vencer->nome->EditValue ?>"<?php echo $view_socios_a_vencer->nome->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nome" name="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome" id="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome" value="<?php echo ew_HtmlEncode($view_socios_a_vencer->nome->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($view_socios_a_vencer->cpf->Visible) { // cpf ?>
		<td data-name="cpf">
<span id="el$rowindex$_view_socios_a_vencer_cpf" class="form-group view_socios_a_vencer_cpf">
<input type="text" data-field="x_cpf" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_cpf" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_cpf" size="12" maxlength="15" placeholder="<?php echo ew_HtmlEncode($view_socios_a_vencer->cpf->PlaceHolder) ?>" value="<?php echo $view_socios_a_vencer->cpf->EditValue ?>"<?php echo $view_socios_a_vencer->cpf->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_cpf" name="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_cpf" id="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_cpf" value="<?php echo ew_HtmlEncode($view_socios_a_vencer->cpf->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($view_socios_a_vencer->nome_empresa->Visible) { // nome_empresa ?>
		<td data-name="nome_empresa">
<span id="el$rowindex$_view_socios_a_vencer_nome_empresa" class="form-group view_socios_a_vencer_nome_empresa">
<input type="text" data-field="x_nome_empresa" name="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome_empresa" id="x<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome_empresa" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($view_socios_a_vencer->nome_empresa->PlaceHolder) ?>" value="<?php echo $view_socios_a_vencer->nome_empresa->EditValue ?>"<?php echo $view_socios_a_vencer->nome_empresa->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nome_empresa" name="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome_empresa" id="o<?php echo $view_socios_a_vencer_list->RowIndex ?>_nome_empresa" value="<?php echo ew_HtmlEncode($view_socios_a_vencer->nome_empresa->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$view_socios_a_vencer_list->ListOptions->Render("body", "right", $view_socios_a_vencer_list->RowCnt);
?>
<script type="text/javascript">
fview_socios_a_vencerlist.UpdateOpts(<?php echo $view_socios_a_vencer_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($view_socios_a_vencer->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $view_socios_a_vencer_list->FormKeyCountName ?>" id="<?php echo $view_socios_a_vencer_list->FormKeyCountName ?>" value="<?php echo $view_socios_a_vencer_list->KeyCount ?>">
<?php echo $view_socios_a_vencer_list->MultiSelectKey ?>
<?php } ?>
<?php if ($view_socios_a_vencer->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($view_socios_a_vencer_list->Recordset)
	$view_socios_a_vencer_list->Recordset->Close();
?>
<?php if ($view_socios_a_vencer->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($view_socios_a_vencer->CurrentAction <> "gridadd" && $view_socios_a_vencer->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($view_socios_a_vencer_list->Pager)) $view_socios_a_vencer_list->Pager = new cPrevNextPager($view_socios_a_vencer_list->StartRec, $view_socios_a_vencer_list->DisplayRecs, $view_socios_a_vencer_list->TotalRecs) ?>
<?php if ($view_socios_a_vencer_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($view_socios_a_vencer_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view_socios_a_vencer_list->PageUrl() ?>start=<?php echo $view_socios_a_vencer_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view_socios_a_vencer_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view_socios_a_vencer_list->PageUrl() ?>start=<?php echo $view_socios_a_vencer_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view_socios_a_vencer_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($view_socios_a_vencer_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view_socios_a_vencer_list->PageUrl() ?>start=<?php echo $view_socios_a_vencer_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view_socios_a_vencer_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view_socios_a_vencer_list->PageUrl() ?>start=<?php echo $view_socios_a_vencer_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view_socios_a_vencer_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view_socios_a_vencer_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view_socios_a_vencer_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view_socios_a_vencer_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($view_socios_a_vencer_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="view_socios_a_vencer">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="30"<?php if ($view_socios_a_vencer_list->DisplayRecs == 30) { ?> selected="selected"<?php } ?>>30</option>
<option value="50"<?php if ($view_socios_a_vencer_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($view_socios_a_vencer_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($view_socios_a_vencer->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_socios_a_vencer_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($view_socios_a_vencer_list->TotalRecs == 0 && $view_socios_a_vencer->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_socios_a_vencer_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($view_socios_a_vencer->Export == "") { ?>
<script type="text/javascript">
fview_socios_a_vencerlistsrch.Init();
fview_socios_a_vencerlist.Init();
</script>
<?php } ?>
<?php
$view_socios_a_vencer_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($view_socios_a_vencer->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$view_socios_a_vencer_list->Page_Terminate();
?>
