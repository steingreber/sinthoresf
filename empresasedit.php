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

$empresas_edit = NULL; // Initialize page object first

class cempresas_edit extends cempresas {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'empresas';

	// Page object name
	var $PageObjName = 'empresas_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		$this->cod_empresa->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $DisplayRecs = 1;
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
		global $objForm, $Language, $gsFormError;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Load key from QueryString
		if (@$_GET["cod_empresa"] <> "") {
			$this->cod_empresa->setQueryStringValue($_GET["cod_empresa"]);
			$this->RecKey["cod_empresa"] = $this->cod_empresa->QueryStringValue;
		} else {
			$bLoadCurrentRecord = TRUE;
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load recordset
		$this->StartRec = 1; // Initialize start position
		if ($this->Recordset = $this->LoadRecordset()) // Load records
			$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
		if ($this->TotalRecs <= 0) { // No record found
			if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$this->Page_Terminate("empresaslist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->cod_empresa->CurrentValue) == strval($this->Recordset->fields('cod_empresa'))) {
					$this->setStartRecordNumber($this->StartRec); // Save record position
					$bMatchRecord = TRUE;
					break;
				} else {
					$this->StartRec++;
					$this->Recordset->MoveNext();
				}
			}
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

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
				if (!$bMatchRecord) {
					if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
					$this->Page_Terminate("empresaslist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					if ($this->getCurrentDetailTable() <> "") // Master/detail edit
						$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
					else
						$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		if (!$this->cod_empresa->FldIsDetailKey)
			$this->cod_empresa->setFormValue($objForm->GetValue("x_cod_empresa"));
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
		if (!$this->regiao->FldIsDetailKey) {
			$this->regiao->setFormValue($objForm->GetValue("x_regiao"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->cod_empresa->CurrentValue = $this->cod_empresa->FormValue;
		$this->nome_empresa->CurrentValue = $this->nome_empresa->FormValue;
		$this->endereco->CurrentValue = $this->endereco->FormValue;
		$this->numero->CurrentValue = $this->numero->FormValue;
		$this->bairro->CurrentValue = $this->bairro->FormValue;
		$this->telefone->CurrentValue = $this->telefone->FormValue;
		$this->cidade->CurrentValue = $this->cidade->FormValue;
		$this->cgc->CurrentValue = $this->cgc->FormValue;
		$this->regiao->CurrentValue = $this->regiao->FormValue;
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
		$this->cod_empresa->setDbValue($rs->fields('cod_empresa'));
		$this->nome_empresa->setDbValue($rs->fields('nome_empresa'));
		$this->endereco->setDbValue($rs->fields('endereco'));
		$this->numero->setDbValue($rs->fields('numero'));
		$this->bairro->setDbValue($rs->fields('bairro'));
		$this->telefone->setDbValue($rs->fields('telefone'));
		$this->cidade->setDbValue($rs->fields('cidade'));
		$this->cgc->setDbValue($rs->fields('cgc'));
		$this->regiao->setDbValue($rs->fields('regiao'));
		if (array_key_exists('EV__regiao', $rs->fields)) {
			$this->regiao->VirtualValue = $rs->fields('EV__regiao'); // Set up virtual field value
		} else {
			$this->regiao->VirtualValue = ""; // Clear value
		}
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
		$this->regiao->DbValue = $row['regiao'];
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
		// regiao

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
			$this->telefone->ViewValue = ew_FormatNumber($this->telefone->ViewValue, 0, -2, -2, -2);
			$this->telefone->ViewCustomAttributes = "";

			// cidade
			$this->cidade->ViewValue = $this->cidade->CurrentValue;
			$this->cidade->ViewCustomAttributes = "";

			// cgc
			$this->cgc->ViewValue = $this->cgc->CurrentValue;
			$this->cgc->ViewCustomAttributes = "";

			// regiao
			if ($this->regiao->VirtualValue <> "") {
				$this->regiao->ViewValue = $this->regiao->VirtualValue;
			} else {
			if (strval($this->regiao->CurrentValue) <> "") {
				$sFilterWrk = "`id_regiao`" . ew_SearchString("=", $this->regiao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_regiao`, `regiao` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `regioes`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->regiao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `regiao` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->regiao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->regiao->ViewValue = $this->regiao->CurrentValue;
				}
			} else {
				$this->regiao->ViewValue = NULL;
			}
			}
			$this->regiao->ViewCustomAttributes = "";

			// cod_empresa
			$this->cod_empresa->LinkCustomAttributes = "";
			$this->cod_empresa->HrefValue = "";
			$this->cod_empresa->TooltipValue = "";

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

			// regiao
			$this->regiao->LinkCustomAttributes = "";
			$this->regiao->HrefValue = "";
			$this->regiao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// cod_empresa
			$this->cod_empresa->EditAttrs["class"] = "form-control";
			$this->cod_empresa->EditCustomAttributes = "";
			$this->cod_empresa->EditValue = $this->cod_empresa->CurrentValue;
			$this->cod_empresa->ViewCustomAttributes = "";

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

			// regiao
			$this->regiao->EditAttrs["class"] = "form-control";
			$this->regiao->EditCustomAttributes = "";
			if (trim(strval($this->regiao->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_regiao`" . ew_SearchString("=", $this->regiao->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `id_regiao`, `regiao` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `regioes`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->regiao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `regiao` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->regiao->EditValue = $arwrk;

			// Edit refer script
			// cod_empresa

			$this->cod_empresa->HrefValue = "";

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

			// regiao
			$this->regiao->HrefValue = "";
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
		if (!ew_CheckInteger($this->telefone->FormValue)) {
			ew_AddMessage($gsFormError, $this->telefone->FldErrMsg());
		}
		if (!$this->regiao->FldIsDetailKey && !is_null($this->regiao->FormValue) && $this->regiao->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->regiao->FldCaption(), $this->regiao->ReqErrMsg));
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// nome_empresa
			$this->nome_empresa->SetDbValueDef($rsnew, $this->nome_empresa->CurrentValue, NULL, $this->nome_empresa->ReadOnly);

			// endereco
			$this->endereco->SetDbValueDef($rsnew, $this->endereco->CurrentValue, NULL, $this->endereco->ReadOnly);

			// numero
			$this->numero->SetDbValueDef($rsnew, $this->numero->CurrentValue, NULL, $this->numero->ReadOnly);

			// bairro
			$this->bairro->SetDbValueDef($rsnew, $this->bairro->CurrentValue, NULL, $this->bairro->ReadOnly);

			// telefone
			$this->telefone->SetDbValueDef($rsnew, $this->telefone->CurrentValue, NULL, $this->telefone->ReadOnly);

			// cidade
			$this->cidade->SetDbValueDef($rsnew, $this->cidade->CurrentValue, NULL, $this->cidade->ReadOnly);

			// cgc
			$this->cgc->SetDbValueDef($rsnew, $this->cgc->CurrentValue, NULL, $this->cgc->ReadOnly);

			// regiao
			$this->regiao->SetDbValueDef($rsnew, $this->regiao->CurrentValue, 0, $this->regiao->ReadOnly);

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

				// Update detail records
				if ($EditRow) {
					$DetailTblVar = explode(",", $this->getCurrentDetailTable());
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
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
if (!isset($empresas_edit)) $empresas_edit = new cempresas_edit();

// Page init
$empresas_edit->Page_Init();

// Page main
$empresas_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empresas_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var empresas_edit = new ew_Page("empresas_edit");
empresas_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = empresas_edit.PageID; // For backward compatibility

// Form object
var fempresasedit = new ew_Form("fempresasedit");

// Validate form
fempresasedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_telefone");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($empresas->telefone->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_regiao");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empresas->regiao->FldCaption(), $empresas->regiao->ReqErrMsg)) ?>");

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
fempresasedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempresasedit.ValidateRequired = true;
<?php } else { ?>
fempresasedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fempresasedit.Lists["x_regiao"] = {"LinkField":"x_id_regiao","Ajax":true,"AutoFill":false,"DisplayFields":["x_regiao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $empresas_edit->ShowPageHeader(); ?>
<?php
$empresas_edit->ShowMessage();
?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($empresas_edit->Pager)) $empresas_edit->Pager = new cPrevNextPager($empresas_edit->StartRec, $empresas_edit->DisplayRecs, $empresas_edit->TotalRecs) ?>
<?php if ($empresas_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($empresas_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $empresas_edit->PageUrl() ?>start=<?php echo $empresas_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($empresas_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $empresas_edit->PageUrl() ?>start=<?php echo $empresas_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $empresas_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($empresas_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $empresas_edit->PageUrl() ?>start=<?php echo $empresas_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($empresas_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $empresas_edit->PageUrl() ?>start=<?php echo $empresas_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $empresas_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<form name="fempresasedit" id="fempresasedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($empresas_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $empresas_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="empresas">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($empresas->cod_empresa->Visible) { // cod_empresa ?>
	<div id="r_cod_empresa" class="form-group">
		<label id="elh_empresas_cod_empresa" class="col-sm-2 control-label ewLabel"><?php echo $empresas->cod_empresa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->cod_empresa->CellAttributes() ?>>
<span id="el_empresas_cod_empresa">
<span<?php echo $empresas->cod_empresa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empresas->cod_empresa->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_cod_empresa" name="x_cod_empresa" id="x_cod_empresa" value="<?php echo ew_HtmlEncode($empresas->cod_empresa->CurrentValue) ?>">
<?php echo $empresas->cod_empresa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->nome_empresa->Visible) { // nome_empresa ?>
	<div id="r_nome_empresa" class="form-group">
		<label id="elh_empresas_nome_empresa" for="x_nome_empresa" class="col-sm-2 control-label ewLabel"><?php echo $empresas->nome_empresa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->nome_empresa->CellAttributes() ?>>
<span id="el_empresas_nome_empresa">
<input type="text" data-field="x_nome_empresa" name="x_nome_empresa" id="x_nome_empresa" size="60" maxlength="50" placeholder="<?php echo ew_HtmlEncode($empresas->nome_empresa->PlaceHolder) ?>" value="<?php echo $empresas->nome_empresa->EditValue ?>"<?php echo $empresas->nome_empresa->EditAttributes() ?>>
</span>
<?php echo $empresas->nome_empresa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->endereco->Visible) { // endereco ?>
	<div id="r_endereco" class="form-group">
		<label id="elh_empresas_endereco" for="x_endereco" class="col-sm-2 control-label ewLabel"><?php echo $empresas->endereco->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->endereco->CellAttributes() ?>>
<span id="el_empresas_endereco">
<input type="text" data-field="x_endereco" name="x_endereco" id="x_endereco" size="60" maxlength="50" placeholder="<?php echo ew_HtmlEncode($empresas->endereco->PlaceHolder) ?>" value="<?php echo $empresas->endereco->EditValue ?>"<?php echo $empresas->endereco->EditAttributes() ?>>
</span>
<?php echo $empresas->endereco->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->numero->Visible) { // numero ?>
	<div id="r_numero" class="form-group">
		<label id="elh_empresas_numero" for="x_numero" class="col-sm-2 control-label ewLabel"><?php echo $empresas->numero->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->numero->CellAttributes() ?>>
<span id="el_empresas_numero">
<input type="text" data-field="x_numero" name="x_numero" id="x_numero" size="10" placeholder="<?php echo ew_HtmlEncode($empresas->numero->PlaceHolder) ?>" value="<?php echo $empresas->numero->EditValue ?>"<?php echo $empresas->numero->EditAttributes() ?>>
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
<input type="text" data-field="x_telefone" name="x_telefone" id="x_telefone" size="15" maxlength="20" placeholder="<?php echo ew_HtmlEncode($empresas->telefone->PlaceHolder) ?>" value="<?php echo $empresas->telefone->EditValue ?>"<?php echo $empresas->telefone->EditAttributes() ?>>
</span>
<?php echo $empresas->telefone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->cidade->Visible) { // cidade ?>
	<div id="r_cidade" class="form-group">
		<label id="elh_empresas_cidade" for="x_cidade" class="col-sm-2 control-label ewLabel"><?php echo $empresas->cidade->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->cidade->CellAttributes() ?>>
<span id="el_empresas_cidade">
<input type="text" data-field="x_cidade" name="x_cidade" id="x_cidade" size="40" maxlength="40" placeholder="<?php echo ew_HtmlEncode($empresas->cidade->PlaceHolder) ?>" value="<?php echo $empresas->cidade->EditValue ?>"<?php echo $empresas->cidade->EditAttributes() ?>>
</span>
<?php echo $empresas->cidade->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->cgc->Visible) { // cgc ?>
	<div id="r_cgc" class="form-group">
		<label id="elh_empresas_cgc" for="x_cgc" class="col-sm-2 control-label ewLabel"><?php echo $empresas->cgc->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->cgc->CellAttributes() ?>>
<span id="el_empresas_cgc">
<input type="text" data-field="x_cgc" name="x_cgc" id="x_cgc" size="20" maxlength="20" placeholder="<?php echo ew_HtmlEncode($empresas->cgc->PlaceHolder) ?>" value="<?php echo $empresas->cgc->EditValue ?>"<?php echo $empresas->cgc->EditAttributes() ?>>
</span>
<?php echo $empresas->cgc->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->regiao->Visible) { // regiao ?>
	<div id="r_regiao" class="form-group">
		<label id="elh_empresas_regiao" for="x_regiao" class="col-sm-2 control-label ewLabel"><?php echo $empresas->regiao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->regiao->CellAttributes() ?>>
<span id="el_empresas_regiao">
<select data-field="x_regiao" id="x_regiao" name="x_regiao"<?php echo $empresas->regiao->EditAttributes() ?>>
<?php
if (is_array($empresas->regiao->EditValue)) {
	$arwrk = $empresas->regiao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($empresas->regiao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $empresas->regiao->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_regiao',url:'regioesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_regiao"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $empresas->regiao->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `id_regiao`, `regiao` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `regioes`";
$sWhereWrk = "";

// Call Lookup selecting
$empresas->Lookup_Selecting($empresas->regiao, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `regiao` ASC";
?>
<input type="hidden" name="s_x_regiao" id="s_x_regiao" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id_regiao` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $empresas->regiao->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
<?php if (!isset($empresas_edit->Pager)) $empresas_edit->Pager = new cPrevNextPager($empresas_edit->StartRec, $empresas_edit->DisplayRecs, $empresas_edit->TotalRecs) ?>
<?php if ($empresas_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($empresas_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $empresas_edit->PageUrl() ?>start=<?php echo $empresas_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($empresas_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $empresas_edit->PageUrl() ?>start=<?php echo $empresas_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $empresas_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($empresas_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $empresas_edit->PageUrl() ?>start=<?php echo $empresas_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($empresas_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $empresas_edit->PageUrl() ?>start=<?php echo $empresas_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $empresas_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<script type="text/javascript">
fempresasedit.Init();
</script>
<?php
$empresas_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empresas_edit->Page_Terminate();
?>
