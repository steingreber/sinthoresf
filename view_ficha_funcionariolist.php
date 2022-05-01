<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "view_ficha_funcionarioinfo.php" ?>
<?php include_once "permissoesinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$view_ficha_funcionario_list = NULL; // Initialize page object first

class cview_ficha_funcionario_list extends cview_ficha_funcionario {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'view_ficha_funcionario';

	// Page object name
	var $PageObjName = 'view_ficha_funcionario_list';

	// Grid form hidden field names
	var $FormName = 'fview_ficha_funcionariolist';
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

		// Table object (view_ficha_funcionario)
		if (!isset($GLOBALS["view_ficha_funcionario"]) || get_class($GLOBALS["view_ficha_funcionario"]) == "cview_ficha_funcionario") {
			$GLOBALS["view_ficha_funcionario"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view_ficha_funcionario"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "view_ficha_funcionarioadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "view_ficha_funcionariodelete.php";
		$this->MultiUpdateUrl = "view_ficha_funcionarioupdate.php";

		// Table object (permissoes)
		if (!isset($GLOBALS['permissoes'])) $GLOBALS['permissoes'] = new cpermissoes();

		// User table object (permissoes)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cpermissoes();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'view_ficha_funcionario', TRUE);

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
		$this->cod_pessoa->Visible = !$this->IsAddOrEdit();

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
		global $EW_EXPORT, $view_ficha_funcionario;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($view_ficha_funcionario);
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

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->datacadastro, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nome, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->endereco, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->complemento, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->bairro, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->cidade, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->estado, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->CEP, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->telefone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->sexo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->datanasc, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->rg, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->cpf, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->carteira_trabalho, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nacionalidade, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->naturalidade, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->funcao, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->dependentes, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->A09DESCRICAO, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->A04imagem, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->A01URL, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->A21EMPRESA, $arKeywords, $type);
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
			$this->UpdateSort($this->socio); // socio
			$this->UpdateSort($this->nome); // nome
			$this->UpdateSort($this->complemento); // complemento
			$this->UpdateSort($this->telefone); // telefone
			$this->UpdateSort($this->cod_socio); // cod_socio
			$this->UpdateSort($this->cod_pessoa); // cod_pessoa
			$this->UpdateSort($this->A04imagem); // A04imagem
			$this->UpdateSort($this->A01URL); // A01URL
			$this->UpdateSort($this->A21EMPRESA); // A21EMPRESA
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
				$this->setSessionOrderByList($sOrderBy);
				$this->socio->setSort("");
				$this->nome->setSort("");
				$this->complemento->setSort("");
				$this->telefone->setSort("");
				$this->cod_socio->setSort("");
				$this->cod_pessoa->setSort("");
				$this->A04imagem->setSort("");
				$this->A01URL->setSort("");
				$this->A21EMPRESA->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

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

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->cod_socio->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
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
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fview_ficha_funcionariolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fview_ficha_funcionariolistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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
		$this->socio->setDbValue($rs->fields('socio'));
		if (array_key_exists('EV__socio', $rs->fields)) {
			$this->socio->VirtualValue = $rs->fields('EV__socio'); // Set up virtual field value
		} else {
			$this->socio->VirtualValue = ""; // Clear value
		}
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
		$this->cod_socio->setDbValue($rs->fields('cod_socio'));
		$this->funcao->setDbValue($rs->fields('funcao'));
		$this->dependentes->setDbValue($rs->fields('dependentes'));
		$this->cod_pessoa->setDbValue($rs->fields('cod_pessoa'));
		$this->A09DESCRICAO->setDbValue($rs->fields('A09DESCRICAO'));
		$this->A04imagem->setDbValue($rs->fields('A04imagem'));
		$this->A01URL->setDbValue($rs->fields('A01URL'));
		$this->A21EMPRESA->setDbValue($rs->fields('A21EMPRESA'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->socio->DbValue = $row['socio'];
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
		$this->cod_socio->DbValue = $row['cod_socio'];
		$this->funcao->DbValue = $row['funcao'];
		$this->dependentes->DbValue = $row['dependentes'];
		$this->cod_pessoa->DbValue = $row['cod_pessoa'];
		$this->A09DESCRICAO->DbValue = $row['A09DESCRICAO'];
		$this->A04imagem->DbValue = $row['A04imagem'];
		$this->A01URL->DbValue = $row['A01URL'];
		$this->A21EMPRESA->DbValue = $row['A21EMPRESA'];
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
		// socio
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
		// cod_socio
		// funcao
		// dependentes
		// cod_pessoa
		// A09DESCRICAO
		// A04imagem
		// A01URL
		// A21EMPRESA

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

			// cod_socio
			$this->cod_socio->ViewValue = $this->cod_socio->CurrentValue;
			$this->cod_socio->ViewCustomAttributes = "";

			// funcao
			$this->funcao->ViewValue = $this->funcao->CurrentValue;
			$this->funcao->ViewCustomAttributes = "";

			// cod_pessoa
			$this->cod_pessoa->ViewValue = $this->cod_pessoa->CurrentValue;
			$this->cod_pessoa->ViewCustomAttributes = "";

			// A04imagem
			$this->A04imagem->ViewValue = $this->A04imagem->CurrentValue;
			$this->A04imagem->ViewCustomAttributes = "";

			// A01URL
			$this->A01URL->ViewValue = $this->A01URL->CurrentValue;
			$this->A01URL->ViewCustomAttributes = "";

			// A21EMPRESA
			$this->A21EMPRESA->ViewValue = $this->A21EMPRESA->CurrentValue;
			$this->A21EMPRESA->ViewCustomAttributes = "";

			// socio
			$this->socio->LinkCustomAttributes = "";
			$this->socio->HrefValue = "";
			$this->socio->TooltipValue = "";

			// nome
			$this->nome->LinkCustomAttributes = "";
			$this->nome->HrefValue = "";
			$this->nome->TooltipValue = "";

			// complemento
			$this->complemento->LinkCustomAttributes = "";
			$this->complemento->HrefValue = "";
			$this->complemento->TooltipValue = "";

			// telefone
			$this->telefone->LinkCustomAttributes = "";
			$this->telefone->HrefValue = "";
			$this->telefone->TooltipValue = "";

			// cod_socio
			$this->cod_socio->LinkCustomAttributes = "";
			$this->cod_socio->HrefValue = "";
			$this->cod_socio->TooltipValue = "";

			// cod_pessoa
			$this->cod_pessoa->LinkCustomAttributes = "";
			$this->cod_pessoa->HrefValue = "";
			$this->cod_pessoa->TooltipValue = "";

			// A04imagem
			$this->A04imagem->LinkCustomAttributes = "";
			$this->A04imagem->HrefValue = "";
			$this->A04imagem->TooltipValue = "";

			// A01URL
			$this->A01URL->LinkCustomAttributes = "";
			$this->A01URL->HrefValue = "";
			$this->A01URL->TooltipValue = "";

			// A21EMPRESA
			$this->A21EMPRESA->LinkCustomAttributes = "";
			$this->A21EMPRESA->HrefValue = "";
			$this->A21EMPRESA->TooltipValue = "";
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
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_view_ficha_funcionario\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_view_ficha_funcionario',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fview_ficha_funcionariolist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($view_ficha_funcionario_list)) $view_ficha_funcionario_list = new cview_ficha_funcionario_list();

// Page init
$view_ficha_funcionario_list->Page_Init();

// Page main
$view_ficha_funcionario_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view_ficha_funcionario_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($view_ficha_funcionario->Export == "") { ?>
<script type="text/javascript">

// Page object
var view_ficha_funcionario_list = new ew_Page("view_ficha_funcionario_list");
view_ficha_funcionario_list.PageID = "list"; // Page ID
var EW_PAGE_ID = view_ficha_funcionario_list.PageID; // For backward compatibility

// Form object
var fview_ficha_funcionariolist = new ew_Form("fview_ficha_funcionariolist");
fview_ficha_funcionariolist.FormKeyCountName = '<?php echo $view_ficha_funcionario_list->FormKeyCountName ?>';

// Form_CustomValidate event
fview_ficha_funcionariolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_ficha_funcionariolist.ValidateRequired = true;
<?php } else { ?>
fview_ficha_funcionariolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fview_ficha_funcionariolist.Lists["x_socio"] = {"LinkField":"x_cod_pessoa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fview_ficha_funcionariolistsrch = new ew_Form("fview_ficha_funcionariolistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($view_ficha_funcionario->Export == "") { ?>
<div class="ewToolbar">
<?php if ($view_ficha_funcionario->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($view_ficha_funcionario_list->TotalRecs > 0 && $view_ficha_funcionario_list->ExportOptions->Visible()) { ?>
<?php $view_ficha_funcionario_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($view_ficha_funcionario_list->SearchOptions->Visible()) { ?>
<?php $view_ficha_funcionario_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($view_ficha_funcionario->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($view_ficha_funcionario_list->TotalRecs <= 0)
			$view_ficha_funcionario_list->TotalRecs = $view_ficha_funcionario->SelectRecordCount();
	} else {
		if (!$view_ficha_funcionario_list->Recordset && ($view_ficha_funcionario_list->Recordset = $view_ficha_funcionario_list->LoadRecordset()))
			$view_ficha_funcionario_list->TotalRecs = $view_ficha_funcionario_list->Recordset->RecordCount();
	}
	$view_ficha_funcionario_list->StartRec = 1;
	if ($view_ficha_funcionario_list->DisplayRecs <= 0 || ($view_ficha_funcionario->Export <> "" && $view_ficha_funcionario->ExportAll)) // Display all records
		$view_ficha_funcionario_list->DisplayRecs = $view_ficha_funcionario_list->TotalRecs;
	if (!($view_ficha_funcionario->Export <> "" && $view_ficha_funcionario->ExportAll))
		$view_ficha_funcionario_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$view_ficha_funcionario_list->Recordset = $view_ficha_funcionario_list->LoadRecordset($view_ficha_funcionario_list->StartRec-1, $view_ficha_funcionario_list->DisplayRecs);

	// Set no record found message
	if ($view_ficha_funcionario->CurrentAction == "" && $view_ficha_funcionario_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$view_ficha_funcionario_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($view_ficha_funcionario_list->SearchWhere == "0=101")
			$view_ficha_funcionario_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$view_ficha_funcionario_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$view_ficha_funcionario_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($view_ficha_funcionario->Export == "" && $view_ficha_funcionario->CurrentAction == "") { ?>
<form name="fview_ficha_funcionariolistsrch" id="fview_ficha_funcionariolistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($view_ficha_funcionario_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fview_ficha_funcionariolistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="view_ficha_funcionario">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($view_ficha_funcionario_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($view_ficha_funcionario_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $view_ficha_funcionario_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($view_ficha_funcionario_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($view_ficha_funcionario_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($view_ficha_funcionario_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($view_ficha_funcionario_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $view_ficha_funcionario_list->ShowPageHeader(); ?>
<?php
$view_ficha_funcionario_list->ShowMessage();
?>
<?php if ($view_ficha_funcionario_list->TotalRecs > 0 || $view_ficha_funcionario->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($view_ficha_funcionario->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($view_ficha_funcionario->CurrentAction <> "gridadd" && $view_ficha_funcionario->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($view_ficha_funcionario_list->Pager)) $view_ficha_funcionario_list->Pager = new cPrevNextPager($view_ficha_funcionario_list->StartRec, $view_ficha_funcionario_list->DisplayRecs, $view_ficha_funcionario_list->TotalRecs) ?>
<?php if ($view_ficha_funcionario_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($view_ficha_funcionario_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view_ficha_funcionario_list->PageUrl() ?>start=<?php echo $view_ficha_funcionario_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view_ficha_funcionario_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view_ficha_funcionario_list->PageUrl() ?>start=<?php echo $view_ficha_funcionario_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view_ficha_funcionario_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($view_ficha_funcionario_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view_ficha_funcionario_list->PageUrl() ?>start=<?php echo $view_ficha_funcionario_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view_ficha_funcionario_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view_ficha_funcionario_list->PageUrl() ?>start=<?php echo $view_ficha_funcionario_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view_ficha_funcionario_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view_ficha_funcionario_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view_ficha_funcionario_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view_ficha_funcionario_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($view_ficha_funcionario_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="view_ficha_funcionario">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="30"<?php if ($view_ficha_funcionario_list->DisplayRecs == 30) { ?> selected="selected"<?php } ?>>30</option>
<option value="50"<?php if ($view_ficha_funcionario_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($view_ficha_funcionario_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($view_ficha_funcionario->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_ficha_funcionario_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fview_ficha_funcionariolist" id="fview_ficha_funcionariolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view_ficha_funcionario_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view_ficha_funcionario_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view_ficha_funcionario">
<div id="gmp_view_ficha_funcionario" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($view_ficha_funcionario_list->TotalRecs > 0) { ?>
<table id="tbl_view_ficha_funcionariolist" class="table ewTable">
<?php echo $view_ficha_funcionario->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$view_ficha_funcionario->RowType = EW_ROWTYPE_HEADER;

// Render list options
$view_ficha_funcionario_list->RenderListOptions();

// Render list options (header, left)
$view_ficha_funcionario_list->ListOptions->Render("header", "left");
?>
<?php if ($view_ficha_funcionario->socio->Visible) { // socio ?>
	<?php if ($view_ficha_funcionario->SortUrl($view_ficha_funcionario->socio) == "") { ?>
		<th data-name="socio"><div id="elh_view_ficha_funcionario_socio" class="view_ficha_funcionario_socio"><div class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->socio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="socio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_ficha_funcionario->SortUrl($view_ficha_funcionario->socio) ?>',1);"><div id="elh_view_ficha_funcionario_socio" class="view_ficha_funcionario_socio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->socio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_ficha_funcionario->socio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_ficha_funcionario->socio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_ficha_funcionario->nome->Visible) { // nome ?>
	<?php if ($view_ficha_funcionario->SortUrl($view_ficha_funcionario->nome) == "") { ?>
		<th data-name="nome"><div id="elh_view_ficha_funcionario_nome" class="view_ficha_funcionario_nome"><div class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->nome->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nome"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_ficha_funcionario->SortUrl($view_ficha_funcionario->nome) ?>',1);"><div id="elh_view_ficha_funcionario_nome" class="view_ficha_funcionario_nome">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->nome->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_ficha_funcionario->nome->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_ficha_funcionario->nome->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_ficha_funcionario->complemento->Visible) { // complemento ?>
	<?php if ($view_ficha_funcionario->SortUrl($view_ficha_funcionario->complemento) == "") { ?>
		<th data-name="complemento"><div id="elh_view_ficha_funcionario_complemento" class="view_ficha_funcionario_complemento"><div class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->complemento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="complemento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_ficha_funcionario->SortUrl($view_ficha_funcionario->complemento) ?>',1);"><div id="elh_view_ficha_funcionario_complemento" class="view_ficha_funcionario_complemento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->complemento->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_ficha_funcionario->complemento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_ficha_funcionario->complemento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_ficha_funcionario->telefone->Visible) { // telefone ?>
	<?php if ($view_ficha_funcionario->SortUrl($view_ficha_funcionario->telefone) == "") { ?>
		<th data-name="telefone"><div id="elh_view_ficha_funcionario_telefone" class="view_ficha_funcionario_telefone"><div class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->telefone->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefone"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_ficha_funcionario->SortUrl($view_ficha_funcionario->telefone) ?>',1);"><div id="elh_view_ficha_funcionario_telefone" class="view_ficha_funcionario_telefone">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->telefone->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_ficha_funcionario->telefone->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_ficha_funcionario->telefone->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_ficha_funcionario->cod_socio->Visible) { // cod_socio ?>
	<?php if ($view_ficha_funcionario->SortUrl($view_ficha_funcionario->cod_socio) == "") { ?>
		<th data-name="cod_socio"><div id="elh_view_ficha_funcionario_cod_socio" class="view_ficha_funcionario_cod_socio"><div class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->cod_socio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cod_socio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_ficha_funcionario->SortUrl($view_ficha_funcionario->cod_socio) ?>',1);"><div id="elh_view_ficha_funcionario_cod_socio" class="view_ficha_funcionario_cod_socio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->cod_socio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_ficha_funcionario->cod_socio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_ficha_funcionario->cod_socio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_ficha_funcionario->cod_pessoa->Visible) { // cod_pessoa ?>
	<?php if ($view_ficha_funcionario->SortUrl($view_ficha_funcionario->cod_pessoa) == "") { ?>
		<th data-name="cod_pessoa"><div id="elh_view_ficha_funcionario_cod_pessoa" class="view_ficha_funcionario_cod_pessoa"><div class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->cod_pessoa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cod_pessoa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_ficha_funcionario->SortUrl($view_ficha_funcionario->cod_pessoa) ?>',1);"><div id="elh_view_ficha_funcionario_cod_pessoa" class="view_ficha_funcionario_cod_pessoa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->cod_pessoa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_ficha_funcionario->cod_pessoa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_ficha_funcionario->cod_pessoa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_ficha_funcionario->A04imagem->Visible) { // A04imagem ?>
	<?php if ($view_ficha_funcionario->SortUrl($view_ficha_funcionario->A04imagem) == "") { ?>
		<th data-name="A04imagem"><div id="elh_view_ficha_funcionario_A04imagem" class="view_ficha_funcionario_A04imagem"><div class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->A04imagem->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="A04imagem"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_ficha_funcionario->SortUrl($view_ficha_funcionario->A04imagem) ?>',1);"><div id="elh_view_ficha_funcionario_A04imagem" class="view_ficha_funcionario_A04imagem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->A04imagem->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_ficha_funcionario->A04imagem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_ficha_funcionario->A04imagem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_ficha_funcionario->A01URL->Visible) { // A01URL ?>
	<?php if ($view_ficha_funcionario->SortUrl($view_ficha_funcionario->A01URL) == "") { ?>
		<th data-name="A01URL"><div id="elh_view_ficha_funcionario_A01URL" class="view_ficha_funcionario_A01URL"><div class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->A01URL->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="A01URL"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_ficha_funcionario->SortUrl($view_ficha_funcionario->A01URL) ?>',1);"><div id="elh_view_ficha_funcionario_A01URL" class="view_ficha_funcionario_A01URL">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->A01URL->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_ficha_funcionario->A01URL->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_ficha_funcionario->A01URL->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_ficha_funcionario->A21EMPRESA->Visible) { // A21EMPRESA ?>
	<?php if ($view_ficha_funcionario->SortUrl($view_ficha_funcionario->A21EMPRESA) == "") { ?>
		<th data-name="A21EMPRESA"><div id="elh_view_ficha_funcionario_A21EMPRESA" class="view_ficha_funcionario_A21EMPRESA"><div class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->A21EMPRESA->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="A21EMPRESA"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_ficha_funcionario->SortUrl($view_ficha_funcionario->A21EMPRESA) ?>',1);"><div id="elh_view_ficha_funcionario_A21EMPRESA" class="view_ficha_funcionario_A21EMPRESA">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_ficha_funcionario->A21EMPRESA->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_ficha_funcionario->A21EMPRESA->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_ficha_funcionario->A21EMPRESA->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$view_ficha_funcionario_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($view_ficha_funcionario->ExportAll && $view_ficha_funcionario->Export <> "") {
	$view_ficha_funcionario_list->StopRec = $view_ficha_funcionario_list->TotalRecs;
} else {

	// Set the last record to display
	if ($view_ficha_funcionario_list->TotalRecs > $view_ficha_funcionario_list->StartRec + $view_ficha_funcionario_list->DisplayRecs - 1)
		$view_ficha_funcionario_list->StopRec = $view_ficha_funcionario_list->StartRec + $view_ficha_funcionario_list->DisplayRecs - 1;
	else
		$view_ficha_funcionario_list->StopRec = $view_ficha_funcionario_list->TotalRecs;
}
$view_ficha_funcionario_list->RecCnt = $view_ficha_funcionario_list->StartRec - 1;
if ($view_ficha_funcionario_list->Recordset && !$view_ficha_funcionario_list->Recordset->EOF) {
	$view_ficha_funcionario_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $view_ficha_funcionario_list->StartRec > 1)
		$view_ficha_funcionario_list->Recordset->Move($view_ficha_funcionario_list->StartRec - 1);
} elseif (!$view_ficha_funcionario->AllowAddDeleteRow && $view_ficha_funcionario_list->StopRec == 0) {
	$view_ficha_funcionario_list->StopRec = $view_ficha_funcionario->GridAddRowCount;
}

// Initialize aggregate
$view_ficha_funcionario->RowType = EW_ROWTYPE_AGGREGATEINIT;
$view_ficha_funcionario->ResetAttrs();
$view_ficha_funcionario_list->RenderRow();
while ($view_ficha_funcionario_list->RecCnt < $view_ficha_funcionario_list->StopRec) {
	$view_ficha_funcionario_list->RecCnt++;
	if (intval($view_ficha_funcionario_list->RecCnt) >= intval($view_ficha_funcionario_list->StartRec)) {
		$view_ficha_funcionario_list->RowCnt++;

		// Set up key count
		$view_ficha_funcionario_list->KeyCount = $view_ficha_funcionario_list->RowIndex;

		// Init row class and style
		$view_ficha_funcionario->ResetAttrs();
		$view_ficha_funcionario->CssClass = "";
		if ($view_ficha_funcionario->CurrentAction == "gridadd") {
		} else {
			$view_ficha_funcionario_list->LoadRowValues($view_ficha_funcionario_list->Recordset); // Load row values
		}
		$view_ficha_funcionario->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$view_ficha_funcionario->RowAttrs = array_merge($view_ficha_funcionario->RowAttrs, array('data-rowindex'=>$view_ficha_funcionario_list->RowCnt, 'id'=>'r' . $view_ficha_funcionario_list->RowCnt . '_view_ficha_funcionario', 'data-rowtype'=>$view_ficha_funcionario->RowType));

		// Render row
		$view_ficha_funcionario_list->RenderRow();

		// Render list options
		$view_ficha_funcionario_list->RenderListOptions();
?>
	<tr<?php echo $view_ficha_funcionario->RowAttributes() ?>>
<?php

// Render list options (body, left)
$view_ficha_funcionario_list->ListOptions->Render("body", "left", $view_ficha_funcionario_list->RowCnt);
?>
	<?php if ($view_ficha_funcionario->socio->Visible) { // socio ?>
		<td data-name="socio"<?php echo $view_ficha_funcionario->socio->CellAttributes() ?>>
<span<?php echo $view_ficha_funcionario->socio->ViewAttributes() ?>>
<?php echo $view_ficha_funcionario->socio->ListViewValue() ?></span>
<a id="<?php echo $view_ficha_funcionario_list->PageObjName . "_row_" . $view_ficha_funcionario_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($view_ficha_funcionario->nome->Visible) { // nome ?>
		<td data-name="nome"<?php echo $view_ficha_funcionario->nome->CellAttributes() ?>>
<span<?php echo $view_ficha_funcionario->nome->ViewAttributes() ?>>
<?php echo $view_ficha_funcionario->nome->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_ficha_funcionario->complemento->Visible) { // complemento ?>
		<td data-name="complemento"<?php echo $view_ficha_funcionario->complemento->CellAttributes() ?>>
<span<?php echo $view_ficha_funcionario->complemento->ViewAttributes() ?>>
<?php echo $view_ficha_funcionario->complemento->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_ficha_funcionario->telefone->Visible) { // telefone ?>
		<td data-name="telefone"<?php echo $view_ficha_funcionario->telefone->CellAttributes() ?>>
<span<?php echo $view_ficha_funcionario->telefone->ViewAttributes() ?>>
<?php echo $view_ficha_funcionario->telefone->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_ficha_funcionario->cod_socio->Visible) { // cod_socio ?>
		<td data-name="cod_socio"<?php echo $view_ficha_funcionario->cod_socio->CellAttributes() ?>>
<span<?php echo $view_ficha_funcionario->cod_socio->ViewAttributes() ?>>
<?php echo $view_ficha_funcionario->cod_socio->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_ficha_funcionario->cod_pessoa->Visible) { // cod_pessoa ?>
		<td data-name="cod_pessoa"<?php echo $view_ficha_funcionario->cod_pessoa->CellAttributes() ?>>
<span<?php echo $view_ficha_funcionario->cod_pessoa->ViewAttributes() ?>>
<?php echo $view_ficha_funcionario->cod_pessoa->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_ficha_funcionario->A04imagem->Visible) { // A04imagem ?>
		<td data-name="A04imagem"<?php echo $view_ficha_funcionario->A04imagem->CellAttributes() ?>>
<span<?php echo $view_ficha_funcionario->A04imagem->ViewAttributes() ?>>
<?php echo $view_ficha_funcionario->A04imagem->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_ficha_funcionario->A01URL->Visible) { // A01URL ?>
		<td data-name="A01URL"<?php echo $view_ficha_funcionario->A01URL->CellAttributes() ?>>
<span<?php echo $view_ficha_funcionario->A01URL->ViewAttributes() ?>>
<?php echo $view_ficha_funcionario->A01URL->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_ficha_funcionario->A21EMPRESA->Visible) { // A21EMPRESA ?>
		<td data-name="A21EMPRESA"<?php echo $view_ficha_funcionario->A21EMPRESA->CellAttributes() ?>>
<span<?php echo $view_ficha_funcionario->A21EMPRESA->ViewAttributes() ?>>
<?php echo $view_ficha_funcionario->A21EMPRESA->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$view_ficha_funcionario_list->ListOptions->Render("body", "right", $view_ficha_funcionario_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($view_ficha_funcionario->CurrentAction <> "gridadd")
		$view_ficha_funcionario_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($view_ficha_funcionario->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($view_ficha_funcionario_list->Recordset)
	$view_ficha_funcionario_list->Recordset->Close();
?>
<?php if ($view_ficha_funcionario->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($view_ficha_funcionario->CurrentAction <> "gridadd" && $view_ficha_funcionario->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($view_ficha_funcionario_list->Pager)) $view_ficha_funcionario_list->Pager = new cPrevNextPager($view_ficha_funcionario_list->StartRec, $view_ficha_funcionario_list->DisplayRecs, $view_ficha_funcionario_list->TotalRecs) ?>
<?php if ($view_ficha_funcionario_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($view_ficha_funcionario_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view_ficha_funcionario_list->PageUrl() ?>start=<?php echo $view_ficha_funcionario_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view_ficha_funcionario_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view_ficha_funcionario_list->PageUrl() ?>start=<?php echo $view_ficha_funcionario_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view_ficha_funcionario_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($view_ficha_funcionario_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view_ficha_funcionario_list->PageUrl() ?>start=<?php echo $view_ficha_funcionario_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view_ficha_funcionario_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view_ficha_funcionario_list->PageUrl() ?>start=<?php echo $view_ficha_funcionario_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view_ficha_funcionario_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view_ficha_funcionario_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view_ficha_funcionario_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view_ficha_funcionario_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($view_ficha_funcionario_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="view_ficha_funcionario">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="30"<?php if ($view_ficha_funcionario_list->DisplayRecs == 30) { ?> selected="selected"<?php } ?>>30</option>
<option value="50"<?php if ($view_ficha_funcionario_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($view_ficha_funcionario_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($view_ficha_funcionario->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_ficha_funcionario_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($view_ficha_funcionario_list->TotalRecs == 0 && $view_ficha_funcionario->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_ficha_funcionario_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($view_ficha_funcionario->Export == "") { ?>
<script type="text/javascript">
fview_ficha_funcionariolistsrch.Init();
fview_ficha_funcionariolist.Init();
</script>
<?php } ?>
<?php
$view_ficha_funcionario_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($view_ficha_funcionario->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$view_ficha_funcionario_list->Page_Terminate();
?>
