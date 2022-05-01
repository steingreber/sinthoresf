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

$config_view = NULL; // Initialize page object first

class cconfig_view extends cconfig {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'config';

	// Page object name
	var $PageObjName = 'config_view';

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

		// Table object (config)
		if (!isset($GLOBALS["config"]) || get_class($GLOBALS["config"]) == "cconfig") {
			$GLOBALS["config"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["config"];
		}
		$KeyUrl = "";
		if (@$_GET["A00ID"] <> "") {
			$this->RecKey["A00ID"] = $_GET["A00ID"];
			$KeyUrl .= "&amp;A00ID=" . urlencode($this->RecKey["A00ID"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'config', TRUE);

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
		if (@$_GET["A00ID"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["A00ID"]);
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
		$this->A00ID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["A00ID"] <> "") {
				$this->A00ID->setQueryStringValue($_GET["A00ID"]);
				$this->RecKey["A00ID"] = $this->A00ID->QueryStringValue;
			} elseif (@$_POST["A00ID"] <> "") {
				$this->A00ID->setFormValue($_POST["A00ID"]);
				$this->RecKey["A00ID"] = $this->A00ID->FormValue;
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
						$this->Page_Terminate("configlist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->A00ID->CurrentValue) == strval($this->Recordset->fields('A00ID'))) {
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
						$sReturnUrl = "configlist.php"; // No matching record, return to list
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
			$sReturnUrl = "configlist.php"; // Not page request, return to list
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

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "");

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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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

			// A01TOPOIMG
			$this->A01TOPOIMG->ViewValue = $this->A01TOPOIMG->CurrentValue;
			$this->A01TOPOIMG->ViewCustomAttributes = "";

			// A02TITULO
			$this->A02TITULO->ViewValue = $this->A02TITULO->CurrentValue;
			$this->A02TITULO->ViewCustomAttributes = "";

			// A03EMAIL
			$this->A03EMAIL->ViewValue = $this->A03EMAIL->CurrentValue;
			$this->A03EMAIL->ViewCustomAttributes = "";

			// A04COR_FONTE_SITE
			$this->A04COR_FONTE_SITE->ViewValue = $this->A04COR_FONTE_SITE->CurrentValue;
			$this->A04COR_FONTE_SITE->ViewCustomAttributes = "";

			// A05MODELO
			$this->A05MODELO->ViewValue = $this->A05MODELO->CurrentValue;
			$this->A05MODELO->ViewCustomAttributes = "";

			// A06USUARIO
			$this->A06USUARIO->ViewValue = $this->A06USUARIO->CurrentValue;
			$this->A06USUARIO->ViewCustomAttributes = "";

			// A07SENHA
			$this->A07SENHA->ViewValue = $this->A07SENHA->CurrentValue;
			$this->A07SENHA->ViewCustomAttributes = "";

			// A08NOME
			$this->A08NOME->ViewValue = $this->A08NOME->CurrentValue;
			$this->A08NOME->ViewCustomAttributes = "";

			// A09DESCRICAO
			$this->A09DESCRICAO->ViewValue = $this->A09DESCRICAO->CurrentValue;
			$this->A09DESCRICAO->ViewCustomAttributes = "";

			// A10PALAVRAS
			$this->A10PALAVRAS->ViewValue = $this->A10PALAVRAS->CurrentValue;
			$this->A10PALAVRAS->ViewCustomAttributes = "";

			// A11TIPO_TOPO
			$this->A11TIPO_TOPO->ViewValue = $this->A11TIPO_TOPO->CurrentValue;
			$this->A11TIPO_TOPO->ViewCustomAttributes = "";

			// A12TIPO_SITE
			$this->A12TIPO_SITE->ViewValue = $this->A12TIPO_SITE->CurrentValue;
			$this->A12TIPO_SITE->ViewCustomAttributes = "";

			// A13ACESSOS
			$this->A13ACESSOS->ViewValue = $this->A13ACESSOS->CurrentValue;
			$this->A13ACESSOS->ViewCustomAttributes = "";

			// A14ULTIMO
			$this->A14ULTIMO->ViewValue = $this->A14ULTIMO->CurrentValue;
			$this->A14ULTIMO->ViewValue = ew_FormatDateTime($this->A14ULTIMO->ViewValue, 7);
			$this->A14ULTIMO->ViewCustomAttributes = "";

			// A15ENDRECO
			$this->A15ENDRECO->ViewValue = $this->A15ENDRECO->CurrentValue;
			$this->A15ENDRECO->ViewCustomAttributes = "";

			// A16CIDADE
			$this->A16CIDADE->ViewValue = $this->A16CIDADE->CurrentValue;
			$this->A16CIDADE->ViewCustomAttributes = "";

			// A17ESTADO
			$this->A17ESTADO->ViewValue = $this->A17ESTADO->CurrentValue;
			$this->A17ESTADO->ViewCustomAttributes = "";

			// A18CEP
			$this->A18CEP->ViewValue = $this->A18CEP->CurrentValue;
			$this->A18CEP->ViewCustomAttributes = "";

			// A19FONE
			$this->A19FONE->ViewValue = $this->A19FONE->CurrentValue;
			$this->A19FONE->ViewCustomAttributes = "";

			// A20CELULAR
			$this->A20CELULAR->ViewValue = $this->A20CELULAR->CurrentValue;
			$this->A20CELULAR->ViewCustomAttributes = "";

			// A21EMPRESA
			$this->A21EMPRESA->ViewValue = $this->A21EMPRESA->CurrentValue;
			$this->A21EMPRESA->ViewCustomAttributes = "";

			// A22INFORMATIVO
			$this->A22INFORMATIVO->ViewValue = $this->A22INFORMATIVO->CurrentValue;
			$this->A22INFORMATIVO->ViewCustomAttributes = "";

			// A23ENQUETE
			$this->A23ENQUETE->ViewValue = $this->A23ENQUETE->CurrentValue;
			$this->A23ENQUETE->ViewCustomAttributes = "";

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

			// A00ID
			$this->A00ID->LinkCustomAttributes = "";
			$this->A00ID->HrefValue = "";
			$this->A00ID->TooltipValue = "";

			// A01TOPOIMG
			$this->A01TOPOIMG->LinkCustomAttributes = "";
			$this->A01TOPOIMG->HrefValue = "";
			$this->A01TOPOIMG->TooltipValue = "";

			// A02TITULO
			$this->A02TITULO->LinkCustomAttributes = "";
			$this->A02TITULO->HrefValue = "";
			$this->A02TITULO->TooltipValue = "";

			// A03EMAIL
			$this->A03EMAIL->LinkCustomAttributes = "";
			$this->A03EMAIL->HrefValue = "";
			$this->A03EMAIL->TooltipValue = "";

			// A04COR_FONTE_SITE
			$this->A04COR_FONTE_SITE->LinkCustomAttributes = "";
			$this->A04COR_FONTE_SITE->HrefValue = "";
			$this->A04COR_FONTE_SITE->TooltipValue = "";

			// A05MODELO
			$this->A05MODELO->LinkCustomAttributes = "";
			$this->A05MODELO->HrefValue = "";
			$this->A05MODELO->TooltipValue = "";

			// A06USUARIO
			$this->A06USUARIO->LinkCustomAttributes = "";
			$this->A06USUARIO->HrefValue = "";
			$this->A06USUARIO->TooltipValue = "";

			// A07SENHA
			$this->A07SENHA->LinkCustomAttributes = "";
			$this->A07SENHA->HrefValue = "";
			$this->A07SENHA->TooltipValue = "";

			// A08NOME
			$this->A08NOME->LinkCustomAttributes = "";
			$this->A08NOME->HrefValue = "";
			$this->A08NOME->TooltipValue = "";

			// A09DESCRICAO
			$this->A09DESCRICAO->LinkCustomAttributes = "";
			$this->A09DESCRICAO->HrefValue = "";
			$this->A09DESCRICAO->TooltipValue = "";

			// A10PALAVRAS
			$this->A10PALAVRAS->LinkCustomAttributes = "";
			$this->A10PALAVRAS->HrefValue = "";
			$this->A10PALAVRAS->TooltipValue = "";

			// A11TIPO_TOPO
			$this->A11TIPO_TOPO->LinkCustomAttributes = "";
			$this->A11TIPO_TOPO->HrefValue = "";
			$this->A11TIPO_TOPO->TooltipValue = "";

			// A12TIPO_SITE
			$this->A12TIPO_SITE->LinkCustomAttributes = "";
			$this->A12TIPO_SITE->HrefValue = "";
			$this->A12TIPO_SITE->TooltipValue = "";

			// A13ACESSOS
			$this->A13ACESSOS->LinkCustomAttributes = "";
			$this->A13ACESSOS->HrefValue = "";
			$this->A13ACESSOS->TooltipValue = "";

			// A14ULTIMO
			$this->A14ULTIMO->LinkCustomAttributes = "";
			$this->A14ULTIMO->HrefValue = "";
			$this->A14ULTIMO->TooltipValue = "";

			// A15ENDRECO
			$this->A15ENDRECO->LinkCustomAttributes = "";
			$this->A15ENDRECO->HrefValue = "";
			$this->A15ENDRECO->TooltipValue = "";

			// A16CIDADE
			$this->A16CIDADE->LinkCustomAttributes = "";
			$this->A16CIDADE->HrefValue = "";
			$this->A16CIDADE->TooltipValue = "";

			// A17ESTADO
			$this->A17ESTADO->LinkCustomAttributes = "";
			$this->A17ESTADO->HrefValue = "";
			$this->A17ESTADO->TooltipValue = "";

			// A18CEP
			$this->A18CEP->LinkCustomAttributes = "";
			$this->A18CEP->HrefValue = "";
			$this->A18CEP->TooltipValue = "";

			// A19FONE
			$this->A19FONE->LinkCustomAttributes = "";
			$this->A19FONE->HrefValue = "";
			$this->A19FONE->TooltipValue = "";

			// A20CELULAR
			$this->A20CELULAR->LinkCustomAttributes = "";
			$this->A20CELULAR->HrefValue = "";
			$this->A20CELULAR->TooltipValue = "";

			// A21EMPRESA
			$this->A21EMPRESA->LinkCustomAttributes = "";
			$this->A21EMPRESA->HrefValue = "";
			$this->A21EMPRESA->TooltipValue = "";

			// A22INFORMATIVO
			$this->A22INFORMATIVO->LinkCustomAttributes = "";
			$this->A22INFORMATIVO->HrefValue = "";
			$this->A22INFORMATIVO->TooltipValue = "";

			// A23ENQUETE
			$this->A23ENQUETE->LinkCustomAttributes = "";
			$this->A23ENQUETE->HrefValue = "";
			$this->A23ENQUETE->TooltipValue = "";

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
		$item->Visible = FALSE;

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
		$item->Body = "<button id=\"emf_config\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_config',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fconfigview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		$Breadcrumb->Add("list", $this->TableVar, "configlist.php", "", $this->TableVar, TRUE);
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
if (!isset($config_view)) $config_view = new cconfig_view();

// Page init
$config_view->Page_Init();

// Page main
$config_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$config_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($config->Export == "") { ?>
<script type="text/javascript">

// Page object
var config_view = new ew_Page("config_view");
config_view.PageID = "view"; // Page ID
var EW_PAGE_ID = config_view.PageID; // For backward compatibility

// Form object
var fconfigview = new ew_Form("fconfigview");

// Form_CustomValidate event
fconfigview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fconfigview.ValidateRequired = true;
<?php } else { ?>
fconfigview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($config->Export == "") { ?>
<div class="ewToolbar">
<?php if ($config->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $config_view->ExportOptions->Render("body") ?>
<?php
	foreach ($config_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if ($config->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $config_view->ShowPageHeader(); ?>
<?php
$config_view->ShowMessage();
?>
<?php if ($config->Export == "") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($config_view->Pager)) $config_view->Pager = new cPrevNextPager($config_view->StartRec, $config_view->DisplayRecs, $config_view->TotalRecs) ?>
<?php if ($config_view->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($config_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $config_view->PageUrl() ?>start=<?php echo $config_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($config_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $config_view->PageUrl() ?>start=<?php echo $config_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $config_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($config_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $config_view->PageUrl() ?>start=<?php echo $config_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($config_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $config_view->PageUrl() ?>start=<?php echo $config_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $config_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fconfigview" id="fconfigview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($config_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $config_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="config">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($config->A00ID->Visible) { // A00ID ?>
	<tr id="r_A00ID">
		<td><span id="elh_config_A00ID"><?php echo $config->A00ID->FldCaption() ?></span></td>
		<td<?php echo $config->A00ID->CellAttributes() ?>>
<span id="el_config_A00ID">
<span<?php echo $config->A00ID->ViewAttributes() ?>>
<?php echo $config->A00ID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A01TOPOIMG->Visible) { // A01TOPOIMG ?>
	<tr id="r_A01TOPOIMG">
		<td><span id="elh_config_A01TOPOIMG"><?php echo $config->A01TOPOIMG->FldCaption() ?></span></td>
		<td<?php echo $config->A01TOPOIMG->CellAttributes() ?>>
<span id="el_config_A01TOPOIMG">
<span<?php echo $config->A01TOPOIMG->ViewAttributes() ?>>
<?php echo $config->A01TOPOIMG->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A02TITULO->Visible) { // A02TITULO ?>
	<tr id="r_A02TITULO">
		<td><span id="elh_config_A02TITULO"><?php echo $config->A02TITULO->FldCaption() ?></span></td>
		<td<?php echo $config->A02TITULO->CellAttributes() ?>>
<span id="el_config_A02TITULO">
<span<?php echo $config->A02TITULO->ViewAttributes() ?>>
<?php echo $config->A02TITULO->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A03EMAIL->Visible) { // A03EMAIL ?>
	<tr id="r_A03EMAIL">
		<td><span id="elh_config_A03EMAIL"><?php echo $config->A03EMAIL->FldCaption() ?></span></td>
		<td<?php echo $config->A03EMAIL->CellAttributes() ?>>
<span id="el_config_A03EMAIL">
<span<?php echo $config->A03EMAIL->ViewAttributes() ?>>
<?php echo $config->A03EMAIL->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A04COR_FONTE_SITE->Visible) { // A04COR_FONTE_SITE ?>
	<tr id="r_A04COR_FONTE_SITE">
		<td><span id="elh_config_A04COR_FONTE_SITE"><?php echo $config->A04COR_FONTE_SITE->FldCaption() ?></span></td>
		<td<?php echo $config->A04COR_FONTE_SITE->CellAttributes() ?>>
<span id="el_config_A04COR_FONTE_SITE">
<span<?php echo $config->A04COR_FONTE_SITE->ViewAttributes() ?>>
<?php echo $config->A04COR_FONTE_SITE->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A05MODELO->Visible) { // A05MODELO ?>
	<tr id="r_A05MODELO">
		<td><span id="elh_config_A05MODELO"><?php echo $config->A05MODELO->FldCaption() ?></span></td>
		<td<?php echo $config->A05MODELO->CellAttributes() ?>>
<span id="el_config_A05MODELO">
<span<?php echo $config->A05MODELO->ViewAttributes() ?>>
<?php echo $config->A05MODELO->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A06USUARIO->Visible) { // A06USUARIO ?>
	<tr id="r_A06USUARIO">
		<td><span id="elh_config_A06USUARIO"><?php echo $config->A06USUARIO->FldCaption() ?></span></td>
		<td<?php echo $config->A06USUARIO->CellAttributes() ?>>
<span id="el_config_A06USUARIO">
<span<?php echo $config->A06USUARIO->ViewAttributes() ?>>
<?php echo $config->A06USUARIO->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A07SENHA->Visible) { // A07SENHA ?>
	<tr id="r_A07SENHA">
		<td><span id="elh_config_A07SENHA"><?php echo $config->A07SENHA->FldCaption() ?></span></td>
		<td<?php echo $config->A07SENHA->CellAttributes() ?>>
<span id="el_config_A07SENHA">
<span<?php echo $config->A07SENHA->ViewAttributes() ?>>
<?php echo $config->A07SENHA->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A08NOME->Visible) { // A08NOME ?>
	<tr id="r_A08NOME">
		<td><span id="elh_config_A08NOME"><?php echo $config->A08NOME->FldCaption() ?></span></td>
		<td<?php echo $config->A08NOME->CellAttributes() ?>>
<span id="el_config_A08NOME">
<span<?php echo $config->A08NOME->ViewAttributes() ?>>
<?php echo $config->A08NOME->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A09DESCRICAO->Visible) { // A09DESCRICAO ?>
	<tr id="r_A09DESCRICAO">
		<td><span id="elh_config_A09DESCRICAO"><?php echo $config->A09DESCRICAO->FldCaption() ?></span></td>
		<td<?php echo $config->A09DESCRICAO->CellAttributes() ?>>
<span id="el_config_A09DESCRICAO">
<span<?php echo $config->A09DESCRICAO->ViewAttributes() ?>>
<?php echo $config->A09DESCRICAO->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A10PALAVRAS->Visible) { // A10PALAVRAS ?>
	<tr id="r_A10PALAVRAS">
		<td><span id="elh_config_A10PALAVRAS"><?php echo $config->A10PALAVRAS->FldCaption() ?></span></td>
		<td<?php echo $config->A10PALAVRAS->CellAttributes() ?>>
<span id="el_config_A10PALAVRAS">
<span<?php echo $config->A10PALAVRAS->ViewAttributes() ?>>
<?php echo $config->A10PALAVRAS->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A11TIPO_TOPO->Visible) { // A11TIPO_TOPO ?>
	<tr id="r_A11TIPO_TOPO">
		<td><span id="elh_config_A11TIPO_TOPO"><?php echo $config->A11TIPO_TOPO->FldCaption() ?></span></td>
		<td<?php echo $config->A11TIPO_TOPO->CellAttributes() ?>>
<span id="el_config_A11TIPO_TOPO">
<span<?php echo $config->A11TIPO_TOPO->ViewAttributes() ?>>
<?php echo $config->A11TIPO_TOPO->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A12TIPO_SITE->Visible) { // A12TIPO_SITE ?>
	<tr id="r_A12TIPO_SITE">
		<td><span id="elh_config_A12TIPO_SITE"><?php echo $config->A12TIPO_SITE->FldCaption() ?></span></td>
		<td<?php echo $config->A12TIPO_SITE->CellAttributes() ?>>
<span id="el_config_A12TIPO_SITE">
<span<?php echo $config->A12TIPO_SITE->ViewAttributes() ?>>
<?php echo $config->A12TIPO_SITE->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A13ACESSOS->Visible) { // A13ACESSOS ?>
	<tr id="r_A13ACESSOS">
		<td><span id="elh_config_A13ACESSOS"><?php echo $config->A13ACESSOS->FldCaption() ?></span></td>
		<td<?php echo $config->A13ACESSOS->CellAttributes() ?>>
<span id="el_config_A13ACESSOS">
<span<?php echo $config->A13ACESSOS->ViewAttributes() ?>>
<?php echo $config->A13ACESSOS->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A14ULTIMO->Visible) { // A14ULTIMO ?>
	<tr id="r_A14ULTIMO">
		<td><span id="elh_config_A14ULTIMO"><?php echo $config->A14ULTIMO->FldCaption() ?></span></td>
		<td<?php echo $config->A14ULTIMO->CellAttributes() ?>>
<span id="el_config_A14ULTIMO">
<span<?php echo $config->A14ULTIMO->ViewAttributes() ?>>
<?php echo $config->A14ULTIMO->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A15ENDRECO->Visible) { // A15ENDRECO ?>
	<tr id="r_A15ENDRECO">
		<td><span id="elh_config_A15ENDRECO"><?php echo $config->A15ENDRECO->FldCaption() ?></span></td>
		<td<?php echo $config->A15ENDRECO->CellAttributes() ?>>
<span id="el_config_A15ENDRECO">
<span<?php echo $config->A15ENDRECO->ViewAttributes() ?>>
<?php echo $config->A15ENDRECO->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A16CIDADE->Visible) { // A16CIDADE ?>
	<tr id="r_A16CIDADE">
		<td><span id="elh_config_A16CIDADE"><?php echo $config->A16CIDADE->FldCaption() ?></span></td>
		<td<?php echo $config->A16CIDADE->CellAttributes() ?>>
<span id="el_config_A16CIDADE">
<span<?php echo $config->A16CIDADE->ViewAttributes() ?>>
<?php echo $config->A16CIDADE->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A17ESTADO->Visible) { // A17ESTADO ?>
	<tr id="r_A17ESTADO">
		<td><span id="elh_config_A17ESTADO"><?php echo $config->A17ESTADO->FldCaption() ?></span></td>
		<td<?php echo $config->A17ESTADO->CellAttributes() ?>>
<span id="el_config_A17ESTADO">
<span<?php echo $config->A17ESTADO->ViewAttributes() ?>>
<?php echo $config->A17ESTADO->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A18CEP->Visible) { // A18CEP ?>
	<tr id="r_A18CEP">
		<td><span id="elh_config_A18CEP"><?php echo $config->A18CEP->FldCaption() ?></span></td>
		<td<?php echo $config->A18CEP->CellAttributes() ?>>
<span id="el_config_A18CEP">
<span<?php echo $config->A18CEP->ViewAttributes() ?>>
<?php echo $config->A18CEP->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A19FONE->Visible) { // A19FONE ?>
	<tr id="r_A19FONE">
		<td><span id="elh_config_A19FONE"><?php echo $config->A19FONE->FldCaption() ?></span></td>
		<td<?php echo $config->A19FONE->CellAttributes() ?>>
<span id="el_config_A19FONE">
<span<?php echo $config->A19FONE->ViewAttributes() ?>>
<?php echo $config->A19FONE->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A20CELULAR->Visible) { // A20CELULAR ?>
	<tr id="r_A20CELULAR">
		<td><span id="elh_config_A20CELULAR"><?php echo $config->A20CELULAR->FldCaption() ?></span></td>
		<td<?php echo $config->A20CELULAR->CellAttributes() ?>>
<span id="el_config_A20CELULAR">
<span<?php echo $config->A20CELULAR->ViewAttributes() ?>>
<?php echo $config->A20CELULAR->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A21EMPRESA->Visible) { // A21EMPRESA ?>
	<tr id="r_A21EMPRESA">
		<td><span id="elh_config_A21EMPRESA"><?php echo $config->A21EMPRESA->FldCaption() ?></span></td>
		<td<?php echo $config->A21EMPRESA->CellAttributes() ?>>
<span id="el_config_A21EMPRESA">
<span<?php echo $config->A21EMPRESA->ViewAttributes() ?>>
<?php echo $config->A21EMPRESA->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A22INFORMATIVO->Visible) { // A22INFORMATIVO ?>
	<tr id="r_A22INFORMATIVO">
		<td><span id="elh_config_A22INFORMATIVO"><?php echo $config->A22INFORMATIVO->FldCaption() ?></span></td>
		<td<?php echo $config->A22INFORMATIVO->CellAttributes() ?>>
<span id="el_config_A22INFORMATIVO">
<span<?php echo $config->A22INFORMATIVO->ViewAttributes() ?>>
<?php echo $config->A22INFORMATIVO->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A23ENQUETE->Visible) { // A23ENQUETE ?>
	<tr id="r_A23ENQUETE">
		<td><span id="elh_config_A23ENQUETE"><?php echo $config->A23ENQUETE->FldCaption() ?></span></td>
		<td<?php echo $config->A23ENQUETE->CellAttributes() ?>>
<span id="el_config_A23ENQUETE">
<span<?php echo $config->A23ENQUETE->ViewAttributes() ?>>
<?php echo $config->A23ENQUETE->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->A24DATA->Visible) { // A24DATA ?>
	<tr id="r_A24DATA">
		<td><span id="elh_config_A24DATA"><?php echo $config->A24DATA->FldCaption() ?></span></td>
		<td<?php echo $config->A24DATA->CellAttributes() ?>>
<span id="el_config_A24DATA">
<span<?php echo $config->A24DATA->ViewAttributes() ?>>
<?php echo $config->A24DATA->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->valor_mensal->Visible) { // valor_mensal ?>
	<tr id="r_valor_mensal">
		<td><span id="elh_config_valor_mensal"><?php echo $config->valor_mensal->FldCaption() ?></span></td>
		<td<?php echo $config->valor_mensal->CellAttributes() ?>>
<span id="el_config_valor_mensal">
<span<?php echo $config->valor_mensal->ViewAttributes() ?>>
<?php echo $config->valor_mensal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->valor_extenso->Visible) { // valor_extenso ?>
	<tr id="r_valor_extenso">
		<td><span id="elh_config_valor_extenso"><?php echo $config->valor_extenso->FldCaption() ?></span></td>
		<td<?php echo $config->valor_extenso->CellAttributes() ?>>
<span id="el_config_valor_extenso">
<span<?php echo $config->valor_extenso->ViewAttributes() ?>>
<?php echo $config->valor_extenso->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($config->referente_ao_mes->Visible) { // referente_ao_mes ?>
	<tr id="r_referente_ao_mes">
		<td><span id="elh_config_referente_ao_mes"><?php echo $config->referente_ao_mes->FldCaption() ?></span></td>
		<td<?php echo $config->referente_ao_mes->CellAttributes() ?>>
<span id="el_config_referente_ao_mes">
<span<?php echo $config->referente_ao_mes->ViewAttributes() ?>>
<?php echo $config->referente_ao_mes->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($config->Export == "") { ?>
<?php if (!isset($config_view->Pager)) $config_view->Pager = new cPrevNextPager($config_view->StartRec, $config_view->DisplayRecs, $config_view->TotalRecs) ?>
<?php if ($config_view->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($config_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $config_view->PageUrl() ?>start=<?php echo $config_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($config_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $config_view->PageUrl() ?>start=<?php echo $config_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $config_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($config_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $config_view->PageUrl() ?>start=<?php echo $config_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($config_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $config_view->PageUrl() ?>start=<?php echo $config_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $config_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
fconfigview.Init();
</script>
<?php
$config_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($config->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$config_view->Page_Terminate();
?>
