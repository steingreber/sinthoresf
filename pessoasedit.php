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

$pessoas_edit = NULL; // Initialize page object first

class cpessoas_edit extends cpessoas {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'pessoas';

	// Page object name
	var $PageObjName = 'pessoas_edit';

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

		// Table object (pessoas)
		if (!isset($GLOBALS["pessoas"]) || get_class($GLOBALS["pessoas"]) == "cpessoas") {
			$GLOBALS["pessoas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pessoas"];
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
			define("EW_TABLE_NAME", 'pessoas', TRUE);

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
		if (@$_GET["cod_pessoa"] <> "") {
			$this->cod_pessoa->setQueryStringValue($_GET["cod_pessoa"]);
			$this->RecKey["cod_pessoa"] = $this->cod_pessoa->QueryStringValue;
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

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
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
					$this->Page_Terminate("pessoaslist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->GetEditUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
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
		if (!$this->cod_pessoa->FldIsDetailKey)
			$this->cod_pessoa->setFormValue($objForm->GetValue("x_cod_pessoa"));
		if (!$this->datacadastro->FldIsDetailKey) {
			$this->datacadastro->setFormValue($objForm->GetValue("x_datacadastro"));
		}
		if (!$this->nome->FldIsDetailKey) {
			$this->nome->setFormValue($objForm->GetValue("x_nome"));
		}
		if (!$this->endereco->FldIsDetailKey) {
			$this->endereco->setFormValue($objForm->GetValue("x_endereco"));
		}
		if (!$this->numero->FldIsDetailKey) {
			$this->numero->setFormValue($objForm->GetValue("x_numero"));
		}
		if (!$this->complemento->FldIsDetailKey) {
			$this->complemento->setFormValue($objForm->GetValue("x_complemento"));
		}
		if (!$this->bairro->FldIsDetailKey) {
			$this->bairro->setFormValue($objForm->GetValue("x_bairro"));
		}
		if (!$this->cidade->FldIsDetailKey) {
			$this->cidade->setFormValue($objForm->GetValue("x_cidade"));
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
		if (!$this->CEP->FldIsDetailKey) {
			$this->CEP->setFormValue($objForm->GetValue("x_CEP"));
		}
		if (!$this->telefone->FldIsDetailKey) {
			$this->telefone->setFormValue($objForm->GetValue("x_telefone"));
		}
		if (!$this->sexo->FldIsDetailKey) {
			$this->sexo->setFormValue($objForm->GetValue("x_sexo"));
		}
		if (!$this->datanasc->FldIsDetailKey) {
			$this->datanasc->setFormValue($objForm->GetValue("x_datanasc"));
		}
		if (!$this->estado_civil->FldIsDetailKey) {
			$this->estado_civil->setFormValue($objForm->GetValue("x_estado_civil"));
		}
		if (!$this->rg->FldIsDetailKey) {
			$this->rg->setFormValue($objForm->GetValue("x_rg"));
		}
		if (!$this->cpf->FldIsDetailKey) {
			$this->cpf->setFormValue($objForm->GetValue("x_cpf"));
		}
		if (!$this->carteira_trabalho->FldIsDetailKey) {
			$this->carteira_trabalho->setFormValue($objForm->GetValue("x_carteira_trabalho"));
		}
		if (!$this->nacionalidade->FldIsDetailKey) {
			$this->nacionalidade->setFormValue($objForm->GetValue("x_nacionalidade"));
		}
		if (!$this->naturalidade->FldIsDetailKey) {
			$this->naturalidade->setFormValue($objForm->GetValue("x_naturalidade"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->cod_pessoa->CurrentValue = $this->cod_pessoa->FormValue;
		$this->datacadastro->CurrentValue = $this->datacadastro->FormValue;
		$this->nome->CurrentValue = $this->nome->FormValue;
		$this->endereco->CurrentValue = $this->endereco->FormValue;
		$this->numero->CurrentValue = $this->numero->FormValue;
		$this->complemento->CurrentValue = $this->complemento->FormValue;
		$this->bairro->CurrentValue = $this->bairro->FormValue;
		$this->cidade->CurrentValue = $this->cidade->FormValue;
		$this->estado->CurrentValue = $this->estado->FormValue;
		$this->CEP->CurrentValue = $this->CEP->FormValue;
		$this->telefone->CurrentValue = $this->telefone->FormValue;
		$this->sexo->CurrentValue = $this->sexo->FormValue;
		$this->datanasc->CurrentValue = $this->datanasc->FormValue;
		$this->estado_civil->CurrentValue = $this->estado_civil->FormValue;
		$this->rg->CurrentValue = $this->rg->FormValue;
		$this->cpf->CurrentValue = $this->cpf->FormValue;
		$this->carteira_trabalho->CurrentValue = $this->carteira_trabalho->FormValue;
		$this->nacionalidade->CurrentValue = $this->nacionalidade->FormValue;
		$this->naturalidade->CurrentValue = $this->naturalidade->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// cod_pessoa
			$this->cod_pessoa->EditAttrs["class"] = "form-control";
			$this->cod_pessoa->EditCustomAttributes = "";
			$this->cod_pessoa->EditValue = $this->cod_pessoa->CurrentValue;
			$this->cod_pessoa->ViewCustomAttributes = "";

			// datacadastro
			$this->datacadastro->EditAttrs["class"] = "form-control";
			$this->datacadastro->EditCustomAttributes = "";
			$this->datacadastro->EditValue = ew_HtmlEncode($this->datacadastro->CurrentValue);
			$this->datacadastro->PlaceHolder = ew_RemoveHtml($this->datacadastro->FldCaption());

			// nome
			$this->nome->EditAttrs["class"] = "form-control";
			$this->nome->EditCustomAttributes = "";
			$this->nome->EditValue = ew_HtmlEncode($this->nome->CurrentValue);
			$this->nome->PlaceHolder = ew_RemoveHtml($this->nome->FldCaption());

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

			// complemento
			$this->complemento->EditAttrs["class"] = "form-control";
			$this->complemento->EditCustomAttributes = "";
			$this->complemento->EditValue = ew_HtmlEncode($this->complemento->CurrentValue);
			$this->complemento->PlaceHolder = ew_RemoveHtml($this->complemento->FldCaption());

			// bairro
			$this->bairro->EditAttrs["class"] = "form-control";
			$this->bairro->EditCustomAttributes = "";
			$this->bairro->EditValue = ew_HtmlEncode($this->bairro->CurrentValue);
			$this->bairro->PlaceHolder = ew_RemoveHtml($this->bairro->FldCaption());

			// cidade
			$this->cidade->EditAttrs["class"] = "form-control";
			$this->cidade->EditCustomAttributes = "";
			$this->cidade->EditValue = ew_HtmlEncode($this->cidade->CurrentValue);
			$this->cidade->PlaceHolder = ew_RemoveHtml($this->cidade->FldCaption());

			// estado
			$this->estado->EditAttrs["class"] = "form-control";
			$this->estado->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `sigla`, `estado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `estados`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->estado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->estado->EditValue = $arwrk;

			// CEP
			$this->CEP->EditAttrs["class"] = "form-control";
			$this->CEP->EditCustomAttributes = "";
			$this->CEP->EditValue = ew_HtmlEncode($this->CEP->CurrentValue);
			$this->CEP->PlaceHolder = ew_RemoveHtml($this->CEP->FldCaption());

			// telefone
			$this->telefone->EditAttrs["class"] = "form-control";
			$this->telefone->EditCustomAttributes = "";
			$this->telefone->EditValue = ew_HtmlEncode($this->telefone->CurrentValue);
			$this->telefone->PlaceHolder = ew_RemoveHtml($this->telefone->FldCaption());

			// sexo
			$this->sexo->EditAttrs["class"] = "form-control";
			$this->sexo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->sexo->FldTagValue(1), $this->sexo->FldTagCaption(1) <> "" ? $this->sexo->FldTagCaption(1) : $this->sexo->FldTagValue(1));
			$arwrk[] = array($this->sexo->FldTagValue(2), $this->sexo->FldTagCaption(2) <> "" ? $this->sexo->FldTagCaption(2) : $this->sexo->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->sexo->EditValue = $arwrk;

			// datanasc
			$this->datanasc->EditAttrs["class"] = "form-control";
			$this->datanasc->EditCustomAttributes = "";
			$this->datanasc->EditValue = ew_HtmlEncode($this->datanasc->CurrentValue);
			$this->datanasc->PlaceHolder = ew_RemoveHtml($this->datanasc->FldCaption());

			// estado_civil
			$this->estado_civil->EditAttrs["class"] = "form-control";
			$this->estado_civil->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->estado_civil->FldTagValue(1), $this->estado_civil->FldTagCaption(1) <> "" ? $this->estado_civil->FldTagCaption(1) : $this->estado_civil->FldTagValue(1));
			$arwrk[] = array($this->estado_civil->FldTagValue(2), $this->estado_civil->FldTagCaption(2) <> "" ? $this->estado_civil->FldTagCaption(2) : $this->estado_civil->FldTagValue(2));
			$arwrk[] = array($this->estado_civil->FldTagValue(3), $this->estado_civil->FldTagCaption(3) <> "" ? $this->estado_civil->FldTagCaption(3) : $this->estado_civil->FldTagValue(3));
			$arwrk[] = array($this->estado_civil->FldTagValue(4), $this->estado_civil->FldTagCaption(4) <> "" ? $this->estado_civil->FldTagCaption(4) : $this->estado_civil->FldTagValue(4));
			$arwrk[] = array($this->estado_civil->FldTagValue(5), $this->estado_civil->FldTagCaption(5) <> "" ? $this->estado_civil->FldTagCaption(5) : $this->estado_civil->FldTagValue(5));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->estado_civil->EditValue = $arwrk;

			// rg
			$this->rg->EditAttrs["class"] = "form-control";
			$this->rg->EditCustomAttributes = "";
			$this->rg->EditValue = ew_HtmlEncode($this->rg->CurrentValue);
			$this->rg->PlaceHolder = ew_RemoveHtml($this->rg->FldCaption());

			// cpf
			$this->cpf->EditAttrs["class"] = "form-control";
			$this->cpf->EditCustomAttributes = "";
			$this->cpf->EditValue = ew_HtmlEncode($this->cpf->CurrentValue);
			$this->cpf->PlaceHolder = ew_RemoveHtml($this->cpf->FldCaption());

			// carteira_trabalho
			$this->carteira_trabalho->EditAttrs["class"] = "form-control";
			$this->carteira_trabalho->EditCustomAttributes = "";
			$this->carteira_trabalho->EditValue = ew_HtmlEncode($this->carteira_trabalho->CurrentValue);
			$this->carteira_trabalho->PlaceHolder = ew_RemoveHtml($this->carteira_trabalho->FldCaption());

			// nacionalidade
			$this->nacionalidade->EditAttrs["class"] = "form-control";
			$this->nacionalidade->EditCustomAttributes = "";
			$this->nacionalidade->EditValue = ew_HtmlEncode($this->nacionalidade->CurrentValue);
			$this->nacionalidade->PlaceHolder = ew_RemoveHtml($this->nacionalidade->FldCaption());

			// naturalidade
			$this->naturalidade->EditAttrs["class"] = "form-control";
			$this->naturalidade->EditCustomAttributes = "";
			$this->naturalidade->EditValue = ew_HtmlEncode($this->naturalidade->CurrentValue);
			$this->naturalidade->PlaceHolder = ew_RemoveHtml($this->naturalidade->FldCaption());

			// Edit refer script
			// cod_pessoa

			$this->cod_pessoa->HrefValue = "";

			// datacadastro
			$this->datacadastro->HrefValue = "";

			// nome
			if (!ew_Empty($this->nome->CurrentValue)) {
				$this->nome->HrefValue = "socioslist.php?x_socio=" . ((!empty($this->nome->EditValue)) ? $this->nome->EditValue : $this->nome->CurrentValue) . "&z_socio=LIKE&cmd=search"; // Add prefix/suffix
				$this->nome->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->nome->HrefValue = ew_ConvertFullUrl($this->nome->HrefValue);
			} else {
				$this->nome->HrefValue = "";
			}

			// endereco
			$this->endereco->HrefValue = "";

			// numero
			$this->numero->HrefValue = "";

			// complemento
			$this->complemento->HrefValue = "";

			// bairro
			$this->bairro->HrefValue = "";

			// cidade
			$this->cidade->HrefValue = "";

			// estado
			$this->estado->HrefValue = "";

			// CEP
			$this->CEP->HrefValue = "";

			// telefone
			$this->telefone->HrefValue = "";

			// sexo
			$this->sexo->HrefValue = "";

			// datanasc
			$this->datanasc->HrefValue = "";

			// estado_civil
			$this->estado_civil->HrefValue = "";

			// rg
			$this->rg->HrefValue = "";

			// cpf
			$this->cpf->HrefValue = "";

			// carteira_trabalho
			$this->carteira_trabalho->HrefValue = "";

			// nacionalidade
			$this->nacionalidade->HrefValue = "";

			// naturalidade
			$this->naturalidade->HrefValue = "";
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
		if (!$this->datacadastro->FldIsDetailKey && !is_null($this->datacadastro->FormValue) && $this->datacadastro->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->datacadastro->FldCaption(), $this->datacadastro->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->datacadastro->FormValue)) {
			ew_AddMessage($gsFormError, $this->datacadastro->FldErrMsg());
		}
		if (!$this->nome->FldIsDetailKey && !is_null($this->nome->FormValue) && $this->nome->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nome->FldCaption(), $this->nome->ReqErrMsg));
		}
		if (!$this->endereco->FldIsDetailKey && !is_null($this->endereco->FormValue) && $this->endereco->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->endereco->FldCaption(), $this->endereco->ReqErrMsg));
		}
		if (!$this->numero->FldIsDetailKey && !is_null($this->numero->FormValue) && $this->numero->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->numero->FldCaption(), $this->numero->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->numero->FormValue)) {
			ew_AddMessage($gsFormError, $this->numero->FldErrMsg());
		}
		if (!$this->cidade->FldIsDetailKey && !is_null($this->cidade->FormValue) && $this->cidade->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cidade->FldCaption(), $this->cidade->ReqErrMsg));
		}
		if (!$this->estado->FldIsDetailKey && !is_null($this->estado->FormValue) && $this->estado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estado->FldCaption(), $this->estado->ReqErrMsg));
		}
		if (!$this->CEP->FldIsDetailKey && !is_null($this->CEP->FormValue) && $this->CEP->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->CEP->FldCaption(), $this->CEP->ReqErrMsg));
		}
		if (!$this->telefone->FldIsDetailKey && !is_null($this->telefone->FormValue) && $this->telefone->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->telefone->FldCaption(), $this->telefone->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->telefone->FormValue)) {
			ew_AddMessage($gsFormError, $this->telefone->FldErrMsg());
		}
		if (!$this->sexo->FldIsDetailKey && !is_null($this->sexo->FormValue) && $this->sexo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sexo->FldCaption(), $this->sexo->ReqErrMsg));
		}
		if (!$this->datanasc->FldIsDetailKey && !is_null($this->datanasc->FormValue) && $this->datanasc->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->datanasc->FldCaption(), $this->datanasc->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->datanasc->FormValue)) {
			ew_AddMessage($gsFormError, $this->datanasc->FldErrMsg());
		}
		if (!$this->estado_civil->FldIsDetailKey && !is_null($this->estado_civil->FormValue) && $this->estado_civil->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estado_civil->FldCaption(), $this->estado_civil->ReqErrMsg));
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

			// datacadastro
			$this->datacadastro->SetDbValueDef($rsnew, $this->datacadastro->CurrentValue, NULL, $this->datacadastro->ReadOnly);

			// nome
			$this->nome->SetDbValueDef($rsnew, $this->nome->CurrentValue, NULL, $this->nome->ReadOnly);

			// endereco
			$this->endereco->SetDbValueDef($rsnew, $this->endereco->CurrentValue, NULL, $this->endereco->ReadOnly);

			// numero
			$this->numero->SetDbValueDef($rsnew, $this->numero->CurrentValue, NULL, $this->numero->ReadOnly);

			// complemento
			$this->complemento->SetDbValueDef($rsnew, $this->complemento->CurrentValue, NULL, $this->complemento->ReadOnly);

			// bairro
			$this->bairro->SetDbValueDef($rsnew, $this->bairro->CurrentValue, NULL, $this->bairro->ReadOnly);

			// cidade
			$this->cidade->SetDbValueDef($rsnew, $this->cidade->CurrentValue, NULL, $this->cidade->ReadOnly);

			// estado
			$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, "", $this->estado->ReadOnly);

			// CEP
			$this->CEP->SetDbValueDef($rsnew, $this->CEP->CurrentValue, "", $this->CEP->ReadOnly);

			// telefone
			$this->telefone->SetDbValueDef($rsnew, $this->telefone->CurrentValue, "", $this->telefone->ReadOnly);

			// sexo
			$this->sexo->SetDbValueDef($rsnew, $this->sexo->CurrentValue, NULL, $this->sexo->ReadOnly);

			// datanasc
			$this->datanasc->SetDbValueDef($rsnew, $this->datanasc->CurrentValue, NULL, $this->datanasc->ReadOnly);

			// estado_civil
			$this->estado_civil->SetDbValueDef($rsnew, $this->estado_civil->CurrentValue, NULL, $this->estado_civil->ReadOnly);

			// rg
			$this->rg->SetDbValueDef($rsnew, $this->rg->CurrentValue, NULL, $this->rg->ReadOnly);

			// cpf
			$this->cpf->SetDbValueDef($rsnew, $this->cpf->CurrentValue, NULL, $this->cpf->ReadOnly);

			// carteira_trabalho
			$this->carteira_trabalho->SetDbValueDef($rsnew, $this->carteira_trabalho->CurrentValue, NULL, $this->carteira_trabalho->ReadOnly);

			// nacionalidade
			$this->nacionalidade->SetDbValueDef($rsnew, $this->nacionalidade->CurrentValue, NULL, $this->nacionalidade->ReadOnly);

			// naturalidade
			$this->naturalidade->SetDbValueDef($rsnew, $this->naturalidade->CurrentValue, NULL, $this->naturalidade->ReadOnly);

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "pessoaslist.php", "", $this->TableVar, TRUE);
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
if (!isset($pessoas_edit)) $pessoas_edit = new cpessoas_edit();

// Page init
$pessoas_edit->Page_Init();

// Page main
$pessoas_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pessoas_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var pessoas_edit = new ew_Page("pessoas_edit");
pessoas_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = pessoas_edit.PageID; // For backward compatibility

// Form object
var fpessoasedit = new ew_Form("fpessoasedit");

// Validate form
fpessoasedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_datacadastro");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pessoas->datacadastro->FldCaption(), $pessoas->datacadastro->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_datacadastro");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pessoas->datacadastro->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nome");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pessoas->nome->FldCaption(), $pessoas->nome->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_endereco");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pessoas->endereco->FldCaption(), $pessoas->endereco->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_numero");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pessoas->numero->FldCaption(), $pessoas->numero->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_numero");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pessoas->numero->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cidade");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pessoas->cidade->FldCaption(), $pessoas->cidade->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pessoas->estado->FldCaption(), $pessoas->estado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_CEP");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pessoas->CEP->FldCaption(), $pessoas->CEP->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_telefone");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pessoas->telefone->FldCaption(), $pessoas->telefone->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_telefone");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pessoas->telefone->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sexo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pessoas->sexo->FldCaption(), $pessoas->sexo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_datanasc");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pessoas->datanasc->FldCaption(), $pessoas->datanasc->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_datanasc");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pessoas->datanasc->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_estado_civil");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pessoas->estado_civil->FldCaption(), $pessoas->estado_civil->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cpf");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pessoas->cpf->FldErrMsg()) ?>");

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
fpessoasedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpessoasedit.ValidateRequired = true;
<?php } else { ?>
fpessoasedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpessoasedit.Lists["x_estado"] = {"LinkField":"x_sigla","Ajax":null,"AutoFill":false,"DisplayFields":["x_estado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $pessoas_edit->ShowPageHeader(); ?>
<?php
$pessoas_edit->ShowMessage();
?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pessoas_edit->Pager)) $pessoas_edit->Pager = new cPrevNextPager($pessoas_edit->StartRec, $pessoas_edit->DisplayRecs, $pessoas_edit->TotalRecs) ?>
<?php if ($pessoas_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pessoas_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pessoas_edit->PageUrl() ?>start=<?php echo $pessoas_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pessoas_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pessoas_edit->PageUrl() ?>start=<?php echo $pessoas_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pessoas_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pessoas_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pessoas_edit->PageUrl() ?>start=<?php echo $pessoas_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pessoas_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pessoas_edit->PageUrl() ?>start=<?php echo $pessoas_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pessoas_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<form name="fpessoasedit" id="fpessoasedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pessoas_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pessoas_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pessoas">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($pessoas->cod_pessoa->Visible) { // cod_pessoa ?>
	<div id="r_cod_pessoa" class="form-group">
		<label id="elh_pessoas_cod_pessoa" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->cod_pessoa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->cod_pessoa->CellAttributes() ?>>
<span id="el_pessoas_cod_pessoa">
<span<?php echo $pessoas->cod_pessoa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pessoas->cod_pessoa->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_cod_pessoa" name="x_cod_pessoa" id="x_cod_pessoa" value="<?php echo ew_HtmlEncode($pessoas->cod_pessoa->CurrentValue) ?>">
<?php echo $pessoas->cod_pessoa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->datacadastro->Visible) { // datacadastro ?>
	<div id="r_datacadastro" class="form-group">
		<label id="elh_pessoas_datacadastro" for="x_datacadastro" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->datacadastro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->datacadastro->CellAttributes() ?>>
<span id="el_pessoas_datacadastro">
<input type="text" data-field="x_datacadastro" name="x_datacadastro" id="x_datacadastro" size="10" maxlength="10" placeholder="<?php echo ew_HtmlEncode($pessoas->datacadastro->PlaceHolder) ?>" value="<?php echo $pessoas->datacadastro->EditValue ?>"<?php echo $pessoas->datacadastro->EditAttributes() ?>>
<?php if (!$pessoas->datacadastro->ReadOnly && !$pessoas->datacadastro->Disabled && !isset($pessoas->datacadastro->EditAttrs["readonly"]) && !isset($pessoas->datacadastro->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fpessoasedit", "x_datacadastro", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $pessoas->datacadastro->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->nome->Visible) { // nome ?>
	<div id="r_nome" class="form-group">
		<label id="elh_pessoas_nome" for="x_nome" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->nome->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->nome->CellAttributes() ?>>
<span id="el_pessoas_nome">
<input type="text" data-field="x_nome" name="x_nome" id="x_nome" size="50" maxlength="60" placeholder="<?php echo ew_HtmlEncode($pessoas->nome->PlaceHolder) ?>" value="<?php echo $pessoas->nome->EditValue ?>"<?php echo $pessoas->nome->EditAttributes() ?>>
</span>
<?php echo $pessoas->nome->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->endereco->Visible) { // endereco ?>
	<div id="r_endereco" class="form-group">
		<label id="elh_pessoas_endereco" for="x_endereco" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->endereco->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->endereco->CellAttributes() ?>>
<span id="el_pessoas_endereco">
<input type="text" data-field="x_endereco" name="x_endereco" id="x_endereco" size="50" maxlength="60" placeholder="<?php echo ew_HtmlEncode($pessoas->endereco->PlaceHolder) ?>" value="<?php echo $pessoas->endereco->EditValue ?>"<?php echo $pessoas->endereco->EditAttributes() ?>>
</span>
<?php echo $pessoas->endereco->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->numero->Visible) { // numero ?>
	<div id="r_numero" class="form-group">
		<label id="elh_pessoas_numero" for="x_numero" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->numero->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->numero->CellAttributes() ?>>
<span id="el_pessoas_numero">
<input type="text" data-field="x_numero" name="x_numero" id="x_numero" size="8" maxlength="8" placeholder="<?php echo ew_HtmlEncode($pessoas->numero->PlaceHolder) ?>" value="<?php echo $pessoas->numero->EditValue ?>"<?php echo $pessoas->numero->EditAttributes() ?>>
</span>
<?php echo $pessoas->numero->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->complemento->Visible) { // complemento ?>
	<div id="r_complemento" class="form-group">
		<label id="elh_pessoas_complemento" for="x_complemento" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->complemento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->complemento->CellAttributes() ?>>
<span id="el_pessoas_complemento">
<input type="text" data-field="x_complemento" name="x_complemento" id="x_complemento" size="30" maxlength="60" placeholder="<?php echo ew_HtmlEncode($pessoas->complemento->PlaceHolder) ?>" value="<?php echo $pessoas->complemento->EditValue ?>"<?php echo $pessoas->complemento->EditAttributes() ?>>
</span>
<?php echo $pessoas->complemento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->bairro->Visible) { // bairro ?>
	<div id="r_bairro" class="form-group">
		<label id="elh_pessoas_bairro" for="x_bairro" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->bairro->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->bairro->CellAttributes() ?>>
<span id="el_pessoas_bairro">
<input type="text" data-field="x_bairro" name="x_bairro" id="x_bairro" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pessoas->bairro->PlaceHolder) ?>" value="<?php echo $pessoas->bairro->EditValue ?>"<?php echo $pessoas->bairro->EditAttributes() ?>>
</span>
<?php echo $pessoas->bairro->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->cidade->Visible) { // cidade ?>
	<div id="r_cidade" class="form-group">
		<label id="elh_pessoas_cidade" for="x_cidade" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->cidade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->cidade->CellAttributes() ?>>
<span id="el_pessoas_cidade">
<input type="text" data-field="x_cidade" name="x_cidade" id="x_cidade" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pessoas->cidade->PlaceHolder) ?>" value="<?php echo $pessoas->cidade->EditValue ?>"<?php echo $pessoas->cidade->EditAttributes() ?>>
</span>
<?php echo $pessoas->cidade->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->estado->Visible) { // estado ?>
	<div id="r_estado" class="form-group">
		<label id="elh_pessoas_estado" for="x_estado" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->estado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->estado->CellAttributes() ?>>
<span id="el_pessoas_estado">
<select data-field="x_estado" id="x_estado" name="x_estado"<?php echo $pessoas->estado->EditAttributes() ?>>
<?php
if (is_array($pessoas->estado->EditValue)) {
	$arwrk = $pessoas->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pessoas->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<script type="text/javascript">
fpessoasedit.Lists["x_estado"].Options = <?php echo (is_array($pessoas->estado->EditValue)) ? ew_ArrayToJson($pessoas->estado->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $pessoas->estado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->CEP->Visible) { // CEP ?>
	<div id="r_CEP" class="form-group">
		<label id="elh_pessoas_CEP" for="x_CEP" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->CEP->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->CEP->CellAttributes() ?>>
<span id="el_pessoas_CEP">
<input type="text" data-field="x_CEP" name="x_CEP" id="x_CEP" size="8" maxlength="8" placeholder="<?php echo ew_HtmlEncode($pessoas->CEP->PlaceHolder) ?>" value="<?php echo $pessoas->CEP->EditValue ?>"<?php echo $pessoas->CEP->EditAttributes() ?>>
</span>
<?php echo $pessoas->CEP->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->telefone->Visible) { // telefone ?>
	<div id="r_telefone" class="form-group">
		<label id="elh_pessoas_telefone" for="x_telefone" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->telefone->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->telefone->CellAttributes() ?>>
<span id="el_pessoas_telefone">
<input type="text" data-field="x_telefone" name="x_telefone" id="x_telefone" size="15" maxlength="11" placeholder="<?php echo ew_HtmlEncode($pessoas->telefone->PlaceHolder) ?>" value="<?php echo $pessoas->telefone->EditValue ?>"<?php echo $pessoas->telefone->EditAttributes() ?>>
</span>
<?php echo $pessoas->telefone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->sexo->Visible) { // sexo ?>
	<div id="r_sexo" class="form-group">
		<label id="elh_pessoas_sexo" for="x_sexo" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->sexo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->sexo->CellAttributes() ?>>
<span id="el_pessoas_sexo">
<select data-field="x_sexo" id="x_sexo" name="x_sexo"<?php echo $pessoas->sexo->EditAttributes() ?>>
<?php
if (is_array($pessoas->sexo->EditValue)) {
	$arwrk = $pessoas->sexo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pessoas->sexo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</span>
<?php echo $pessoas->sexo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->datanasc->Visible) { // datanasc ?>
	<div id="r_datanasc" class="form-group">
		<label id="elh_pessoas_datanasc" for="x_datanasc" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->datanasc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->datanasc->CellAttributes() ?>>
<span id="el_pessoas_datanasc">
<input type="text" data-field="x_datanasc" name="x_datanasc" id="x_datanasc" size="10" maxlength="10" placeholder="<?php echo ew_HtmlEncode($pessoas->datanasc->PlaceHolder) ?>" value="<?php echo $pessoas->datanasc->EditValue ?>"<?php echo $pessoas->datanasc->EditAttributes() ?>>
<?php if (!$pessoas->datanasc->ReadOnly && !$pessoas->datanasc->Disabled && !isset($pessoas->datanasc->EditAttrs["readonly"]) && !isset($pessoas->datanasc->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fpessoasedit", "x_datanasc", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $pessoas->datanasc->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->estado_civil->Visible) { // estado_civil ?>
	<div id="r_estado_civil" class="form-group">
		<label id="elh_pessoas_estado_civil" for="x_estado_civil" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->estado_civil->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->estado_civil->CellAttributes() ?>>
<span id="el_pessoas_estado_civil">
<select data-field="x_estado_civil" id="x_estado_civil" name="x_estado_civil"<?php echo $pessoas->estado_civil->EditAttributes() ?>>
<?php
if (is_array($pessoas->estado_civil->EditValue)) {
	$arwrk = $pessoas->estado_civil->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pessoas->estado_civil->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</span>
<?php echo $pessoas->estado_civil->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->rg->Visible) { // rg ?>
	<div id="r_rg" class="form-group">
		<label id="elh_pessoas_rg" for="x_rg" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->rg->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->rg->CellAttributes() ?>>
<span id="el_pessoas_rg">
<input type="text" data-field="x_rg" name="x_rg" id="x_rg" size="18" maxlength="18" placeholder="<?php echo ew_HtmlEncode($pessoas->rg->PlaceHolder) ?>" value="<?php echo $pessoas->rg->EditValue ?>"<?php echo $pessoas->rg->EditAttributes() ?>>
</span>
<?php echo $pessoas->rg->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->cpf->Visible) { // cpf ?>
	<div id="r_cpf" class="form-group">
		<label id="elh_pessoas_cpf" for="x_cpf" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->cpf->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->cpf->CellAttributes() ?>>
<span id="el_pessoas_cpf">
<input type="text" data-field="x_cpf" name="x_cpf" id="x_cpf" size="15" maxlength="15" placeholder="<?php echo ew_HtmlEncode($pessoas->cpf->PlaceHolder) ?>" value="<?php echo $pessoas->cpf->EditValue ?>"<?php echo $pessoas->cpf->EditAttributes() ?>>
</span>
<?php echo $pessoas->cpf->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->carteira_trabalho->Visible) { // carteira_trabalho ?>
	<div id="r_carteira_trabalho" class="form-group">
		<label id="elh_pessoas_carteira_trabalho" for="x_carteira_trabalho" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->carteira_trabalho->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->carteira_trabalho->CellAttributes() ?>>
<span id="el_pessoas_carteira_trabalho">
<input type="text" data-field="x_carteira_trabalho" name="x_carteira_trabalho" id="x_carteira_trabalho" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($pessoas->carteira_trabalho->PlaceHolder) ?>" value="<?php echo $pessoas->carteira_trabalho->EditValue ?>"<?php echo $pessoas->carteira_trabalho->EditAttributes() ?>>
</span>
<?php echo $pessoas->carteira_trabalho->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->nacionalidade->Visible) { // nacionalidade ?>
	<div id="r_nacionalidade" class="form-group">
		<label id="elh_pessoas_nacionalidade" for="x_nacionalidade" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->nacionalidade->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->nacionalidade->CellAttributes() ?>>
<span id="el_pessoas_nacionalidade">
<input type="text" data-field="x_nacionalidade" name="x_nacionalidade" id="x_nacionalidade" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pessoas->nacionalidade->PlaceHolder) ?>" value="<?php echo $pessoas->nacionalidade->EditValue ?>"<?php echo $pessoas->nacionalidade->EditAttributes() ?>>
</span>
<?php echo $pessoas->nacionalidade->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoas->naturalidade->Visible) { // naturalidade ?>
	<div id="r_naturalidade" class="form-group">
		<label id="elh_pessoas_naturalidade" for="x_naturalidade" class="col-sm-2 control-label ewLabel"><?php echo $pessoas->naturalidade->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pessoas->naturalidade->CellAttributes() ?>>
<span id="el_pessoas_naturalidade">
<input type="text" data-field="x_naturalidade" name="x_naturalidade" id="x_naturalidade" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pessoas->naturalidade->PlaceHolder) ?>" value="<?php echo $pessoas->naturalidade->EditValue ?>"<?php echo $pessoas->naturalidade->EditAttributes() ?>>
</span>
<?php echo $pessoas->naturalidade->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
<?php if (!isset($pessoas_edit->Pager)) $pessoas_edit->Pager = new cPrevNextPager($pessoas_edit->StartRec, $pessoas_edit->DisplayRecs, $pessoas_edit->TotalRecs) ?>
<?php if ($pessoas_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pessoas_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pessoas_edit->PageUrl() ?>start=<?php echo $pessoas_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pessoas_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pessoas_edit->PageUrl() ?>start=<?php echo $pessoas_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pessoas_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pessoas_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pessoas_edit->PageUrl() ?>start=<?php echo $pessoas_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pessoas_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pessoas_edit->PageUrl() ?>start=<?php echo $pessoas_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pessoas_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<script type="text/javascript">
fpessoasedit.Init();
</script>
<?php
$pessoas_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pessoas_edit->Page_Terminate();
?>
