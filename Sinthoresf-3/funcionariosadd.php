<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "funcionariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$funcionarios_add = NULL; // Initialize page object first

class cfuncionarios_add extends cfuncionarios {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'funcionarios';

	// Page object name
	var $PageObjName = 'funcionarios_add';

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

		// Table object (funcionarios)
		if (!isset($GLOBALS["funcionarios"]) || get_class($GLOBALS["funcionarios"]) == "cfuncionarios") {
			$GLOBALS["funcionarios"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["funcionarios"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'funcionarios', TRUE);

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
			if (@$_GET["cod_func"] != "") {
				$this->cod_func->setQueryStringValue($_GET["cod_func"]);
				$this->setKey("cod_func", $this->cod_func->CurrentValue); // Set up key
			} else {
				$this->setKey("cod_func", ""); // Clear key
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

		// Set up detail parameters
		$this->SetUpDetailParms();

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
					$this->Page_Terminate("funcionarioslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "funcionariosview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		$this->foto->Upload->Index = $objForm->Index;
		$this->foto->Upload->UploadFile();
		$this->foto->CurrentValue = $this->foto->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->nome->CurrentValue = NULL;
		$this->nome->OldValue = $this->nome->CurrentValue;
		$this->endereco->CurrentValue = NULL;
		$this->endereco->OldValue = $this->endereco->CurrentValue;
		$this->numero->CurrentValue = NULL;
		$this->numero->OldValue = $this->numero->CurrentValue;
		$this->bairro->CurrentValue = NULL;
		$this->bairro->OldValue = $this->bairro->CurrentValue;
		$this->cidade->CurrentValue = NULL;
		$this->cidade->OldValue = $this->cidade->CurrentValue;
		$this->sexo->CurrentValue = NULL;
		$this->sexo->OldValue = $this->sexo->CurrentValue;
		$this->estado_civil->CurrentValue = NULL;
		$this->estado_civil->OldValue = $this->estado_civil->CurrentValue;
		$this->rg->CurrentValue = NULL;
		$this->rg->OldValue = $this->rg->CurrentValue;
		$this->cpf->CurrentValue = NULL;
		$this->cpf->OldValue = $this->cpf->CurrentValue;
		$this->carteira_trabalho->CurrentValue = NULL;
		$this->carteira_trabalho->OldValue = $this->carteira_trabalho->CurrentValue;
		$this->nacionalidade->CurrentValue = NULL;
		$this->nacionalidade->OldValue = $this->nacionalidade->CurrentValue;
		$this->naturalidade->CurrentValue = NULL;
		$this->naturalidade->OldValue = $this->naturalidade->CurrentValue;
		$this->datanasc->CurrentValue = NULL;
		$this->datanasc->OldValue = $this->datanasc->CurrentValue;
		$this->funcao->CurrentValue = NULL;
		$this->funcao->OldValue = $this->funcao->CurrentValue;
		$this->cod_empresa->CurrentValue = NULL;
		$this->cod_empresa->OldValue = $this->cod_empresa->CurrentValue;
		$this->dt_entrou_empresa->CurrentValue = NULL;
		$this->dt_entrou_empresa->OldValue = $this->dt_entrou_empresa->CurrentValue;
		$this->dt_entrou_categoria->CurrentValue = NULL;
		$this->dt_entrou_categoria->OldValue = $this->dt_entrou_categoria->CurrentValue;
		$this->foto->Upload->DbValue = NULL;
		$this->foto->OldValue = $this->foto->Upload->DbValue;
		$this->foto->CurrentValue = NULL; // Clear file related field
		$this->ativo->CurrentValue = 1;
		$this->dependentes->CurrentValue = NULL;
		$this->dependentes->OldValue = $this->dependentes->CurrentValue;
		$this->dtcad->CurrentValue = NULL;
		$this->dtcad->OldValue = $this->dtcad->CurrentValue;
		$this->dtcarteira->CurrentValue = NULL;
		$this->dtcarteira->OldValue = $this->dtcarteira->CurrentValue;
		$this->telefone->CurrentValue = NULL;
		$this->telefone->OldValue = $this->telefone->CurrentValue;
		$this->acompanhante->CurrentValue = NULL;
		$this->acompanhante->OldValue = $this->acompanhante->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->nome->FldIsDetailKey) {
			$this->nome->setFormValue($objForm->GetValue("x_nome"));
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
		if (!$this->cidade->FldIsDetailKey) {
			$this->cidade->setFormValue($objForm->GetValue("x_cidade"));
		}
		if (!$this->sexo->FldIsDetailKey) {
			$this->sexo->setFormValue($objForm->GetValue("x_sexo"));
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
		if (!$this->datanasc->FldIsDetailKey) {
			$this->datanasc->setFormValue($objForm->GetValue("x_datanasc"));
		}
		if (!$this->funcao->FldIsDetailKey) {
			$this->funcao->setFormValue($objForm->GetValue("x_funcao"));
		}
		if (!$this->cod_empresa->FldIsDetailKey) {
			$this->cod_empresa->setFormValue($objForm->GetValue("x_cod_empresa"));
		}
		if (!$this->dt_entrou_empresa->FldIsDetailKey) {
			$this->dt_entrou_empresa->setFormValue($objForm->GetValue("x_dt_entrou_empresa"));
		}
		if (!$this->dt_entrou_categoria->FldIsDetailKey) {
			$this->dt_entrou_categoria->setFormValue($objForm->GetValue("x_dt_entrou_categoria"));
		}
		if (!$this->ativo->FldIsDetailKey) {
			$this->ativo->setFormValue($objForm->GetValue("x_ativo"));
		}
		if (!$this->dependentes->FldIsDetailKey) {
			$this->dependentes->setFormValue($objForm->GetValue("x_dependentes"));
		}
		if (!$this->dtcad->FldIsDetailKey) {
			$this->dtcad->setFormValue($objForm->GetValue("x_dtcad"));
		}
		if (!$this->dtcarteira->FldIsDetailKey) {
			$this->dtcarteira->setFormValue($objForm->GetValue("x_dtcarteira"));
		}
		if (!$this->telefone->FldIsDetailKey) {
			$this->telefone->setFormValue($objForm->GetValue("x_telefone"));
		}
		if (!$this->acompanhante->FldIsDetailKey) {
			$this->acompanhante->setFormValue($objForm->GetValue("x_acompanhante"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nome->CurrentValue = $this->nome->FormValue;
		$this->endereco->CurrentValue = $this->endereco->FormValue;
		$this->numero->CurrentValue = $this->numero->FormValue;
		$this->bairro->CurrentValue = $this->bairro->FormValue;
		$this->cidade->CurrentValue = $this->cidade->FormValue;
		$this->sexo->CurrentValue = $this->sexo->FormValue;
		$this->estado_civil->CurrentValue = $this->estado_civil->FormValue;
		$this->rg->CurrentValue = $this->rg->FormValue;
		$this->cpf->CurrentValue = $this->cpf->FormValue;
		$this->carteira_trabalho->CurrentValue = $this->carteira_trabalho->FormValue;
		$this->nacionalidade->CurrentValue = $this->nacionalidade->FormValue;
		$this->naturalidade->CurrentValue = $this->naturalidade->FormValue;
		$this->datanasc->CurrentValue = $this->datanasc->FormValue;
		$this->funcao->CurrentValue = $this->funcao->FormValue;
		$this->cod_empresa->CurrentValue = $this->cod_empresa->FormValue;
		$this->dt_entrou_empresa->CurrentValue = $this->dt_entrou_empresa->FormValue;
		$this->dt_entrou_categoria->CurrentValue = $this->dt_entrou_categoria->FormValue;
		$this->ativo->CurrentValue = $this->ativo->FormValue;
		$this->dependentes->CurrentValue = $this->dependentes->FormValue;
		$this->dtcad->CurrentValue = $this->dtcad->FormValue;
		$this->dtcarteira->CurrentValue = $this->dtcarteira->FormValue;
		$this->telefone->CurrentValue = $this->telefone->FormValue;
		$this->acompanhante->CurrentValue = $this->acompanhante->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("cod_func")) <> "")
			$this->cod_func->CurrentValue = $this->getKey("cod_func"); // cod_func
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
			switch (@$gsLanguage) {
				case "br":
					$sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
					$sWhereWrk = "";
					break;
			}
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// sexo
			$this->sexo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->sexo->FldTagValue(1), $this->sexo->FldTagCaption(1) <> "" ? $this->sexo->FldTagCaption(1) : $this->sexo->FldTagValue(1));
			$arwrk[] = array($this->sexo->FldTagValue(2), $this->sexo->FldTagCaption(2) <> "" ? $this->sexo->FldTagCaption(2) : $this->sexo->FldTagValue(2));
			$this->sexo->EditValue = $arwrk;

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

			// datanasc
			$this->datanasc->EditAttrs["class"] = "form-control";
			$this->datanasc->EditCustomAttributes = "";
			$this->datanasc->EditValue = ew_HtmlEncode($this->datanasc->CurrentValue);
			$this->datanasc->PlaceHolder = ew_RemoveHtml($this->datanasc->FldCaption());

			// funcao
			$this->funcao->EditAttrs["class"] = "form-control";
			$this->funcao->EditCustomAttributes = "";
			$this->funcao->EditValue = ew_HtmlEncode($this->funcao->CurrentValue);
			$this->funcao->PlaceHolder = ew_RemoveHtml($this->funcao->FldCaption());

			// cod_empresa
			$this->cod_empresa->EditAttrs["class"] = "form-control";
			$this->cod_empresa->EditCustomAttributes = "";
			if (trim(strval($this->cod_empresa->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`cod_empresa`" . ew_SearchString("=", $this->cod_empresa->CurrentValue, EW_DATATYPE_NUMBER);
			}
			switch (@$gsLanguage) {
				case "br":
					$sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `empresas`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `empresas`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->cod_empresa, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->cod_empresa->EditValue = $arwrk;

			// dt_entrou_empresa
			$this->dt_entrou_empresa->EditAttrs["class"] = "form-control";
			$this->dt_entrou_empresa->EditCustomAttributes = "";
			$this->dt_entrou_empresa->EditValue = ew_HtmlEncode($this->dt_entrou_empresa->CurrentValue);
			$this->dt_entrou_empresa->PlaceHolder = ew_RemoveHtml($this->dt_entrou_empresa->FldCaption());

			// dt_entrou_categoria
			$this->dt_entrou_categoria->EditAttrs["class"] = "form-control";
			$this->dt_entrou_categoria->EditCustomAttributes = "";
			$this->dt_entrou_categoria->EditValue = ew_HtmlEncode($this->dt_entrou_categoria->CurrentValue);
			$this->dt_entrou_categoria->PlaceHolder = ew_RemoveHtml($this->dt_entrou_categoria->FldCaption());

			// foto
			$this->foto->EditAttrs["class"] = "form-control";
			$this->foto->EditCustomAttributes = "";
			$this->foto->UploadPath = sistema;
			if (!ew_Empty($this->foto->Upload->DbValue)) {
				$this->foto->ImageAlt = $this->foto->FldAlt();
				$this->foto->EditValue = ew_UploadPathEx(FALSE, $this->foto->UploadPath) . $this->foto->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->foto->EditValue = ew_UploadPathEx(TRUE, $this->foto->UploadPath) . $this->foto->Upload->DbValue;
				}
			} else {
				$this->foto->EditValue = "";
			}
			if (!ew_Empty($this->foto->CurrentValue))
				$this->foto->Upload->FileName = $this->foto->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->foto);

			// ativo
			$this->ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ativo->FldTagValue(1), $this->ativo->FldTagCaption(1) <> "" ? $this->ativo->FldTagCaption(1) : $this->ativo->FldTagValue(1));
			$arwrk[] = array($this->ativo->FldTagValue(2), $this->ativo->FldTagCaption(2) <> "" ? $this->ativo->FldTagCaption(2) : $this->ativo->FldTagValue(2));
			$this->ativo->EditValue = $arwrk;

			// dependentes
			$this->dependentes->EditAttrs["class"] = "form-control";
			$this->dependentes->EditCustomAttributes = "";
			$this->dependentes->EditValue = ew_HtmlEncode($this->dependentes->CurrentValue);
			$this->dependentes->PlaceHolder = ew_RemoveHtml($this->dependentes->FldCaption());

			// dtcad
			$this->dtcad->EditAttrs["class"] = "form-control";
			$this->dtcad->EditCustomAttributes = "";
			$this->dtcad->EditValue = ew_HtmlEncode($this->dtcad->CurrentValue);
			$this->dtcad->PlaceHolder = ew_RemoveHtml($this->dtcad->FldCaption());

			// dtcarteira
			$this->dtcarteira->EditAttrs["class"] = "form-control";
			$this->dtcarteira->EditCustomAttributes = "";
			$this->dtcarteira->EditValue = ew_HtmlEncode($this->dtcarteira->CurrentValue);
			$this->dtcarteira->PlaceHolder = ew_RemoveHtml($this->dtcarteira->FldCaption());

			// telefone
			$this->telefone->EditAttrs["class"] = "form-control";
			$this->telefone->EditCustomAttributes = "";
			$this->telefone->EditValue = ew_HtmlEncode($this->telefone->CurrentValue);
			$this->telefone->PlaceHolder = ew_RemoveHtml($this->telefone->FldCaption());

			// acompanhante
			$this->acompanhante->EditAttrs["class"] = "form-control";
			$this->acompanhante->EditCustomAttributes = "";
			$this->acompanhante->EditValue = ew_HtmlEncode($this->acompanhante->CurrentValue);
			$this->acompanhante->PlaceHolder = ew_RemoveHtml($this->acompanhante->FldCaption());

			// Edit refer script
			// nome

			$this->nome->HrefValue = "";

			// endereco
			$this->endereco->HrefValue = "";

			// numero
			$this->numero->HrefValue = "";

			// bairro
			$this->bairro->HrefValue = "";

			// cidade
			$this->cidade->HrefValue = "";

			// sexo
			$this->sexo->HrefValue = "";

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

			// datanasc
			$this->datanasc->HrefValue = "";

			// funcao
			$this->funcao->HrefValue = "";

			// cod_empresa
			$this->cod_empresa->HrefValue = "";

			// dt_entrou_empresa
			$this->dt_entrou_empresa->HrefValue = "";

			// dt_entrou_categoria
			$this->dt_entrou_categoria->HrefValue = "";

			// foto
			$this->foto->UploadPath = sistema;
			if (!ew_Empty($this->foto->Upload->DbValue)) {
				$this->foto->HrefValue = ew_UploadPathEx(FALSE, $this->foto->UploadPath) . $this->foto->Upload->DbValue; // Add prefix/suffix
				$this->foto->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->foto->HrefValue = ew_ConvertFullUrl($this->foto->HrefValue);
			} else {
				$this->foto->HrefValue = "";
			}
			$this->foto->HrefValue2 = $this->foto->UploadPath . $this->foto->Upload->DbValue;

			// ativo
			$this->ativo->HrefValue = "";

			// dependentes
			$this->dependentes->HrefValue = "";

			// dtcad
			$this->dtcad->HrefValue = "";

			// dtcarteira
			$this->dtcarteira->HrefValue = "";

			// telefone
			$this->telefone->HrefValue = "";

			// acompanhante
			$this->acompanhante->HrefValue = "";
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
		if ($this->sexo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sexo->FldCaption(), $this->sexo->ReqErrMsg));
		}
		if (!$this->estado_civil->FldIsDetailKey && !is_null($this->estado_civil->FormValue) && $this->estado_civil->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estado_civil->FldCaption(), $this->estado_civil->ReqErrMsg));
		}
		if (!$this->rg->FldIsDetailKey && !is_null($this->rg->FormValue) && $this->rg->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->rg->FldCaption(), $this->rg->ReqErrMsg));
		}
		if (!$this->cpf->FldIsDetailKey && !is_null($this->cpf->FormValue) && $this->cpf->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cpf->FldCaption(), $this->cpf->ReqErrMsg));
		}
		if (!$this->carteira_trabalho->FldIsDetailKey && !is_null($this->carteira_trabalho->FormValue) && $this->carteira_trabalho->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->carteira_trabalho->FldCaption(), $this->carteira_trabalho->ReqErrMsg));
		}
		if (!$this->nacionalidade->FldIsDetailKey && !is_null($this->nacionalidade->FormValue) && $this->nacionalidade->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nacionalidade->FldCaption(), $this->nacionalidade->ReqErrMsg));
		}
		if (!$this->naturalidade->FldIsDetailKey && !is_null($this->naturalidade->FormValue) && $this->naturalidade->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->naturalidade->FldCaption(), $this->naturalidade->ReqErrMsg));
		}
		if (!$this->datanasc->FldIsDetailKey && !is_null($this->datanasc->FormValue) && $this->datanasc->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->datanasc->FldCaption(), $this->datanasc->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->datanasc->FormValue)) {
			ew_AddMessage($gsFormError, $this->datanasc->FldErrMsg());
		}
		if (!$this->funcao->FldIsDetailKey && !is_null($this->funcao->FormValue) && $this->funcao->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->funcao->FldCaption(), $this->funcao->ReqErrMsg));
		}
		if (!$this->dt_entrou_empresa->FldIsDetailKey && !is_null($this->dt_entrou_empresa->FormValue) && $this->dt_entrou_empresa->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dt_entrou_empresa->FldCaption(), $this->dt_entrou_empresa->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->dt_entrou_empresa->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_entrou_empresa->FldErrMsg());
		}
		if (!$this->dt_entrou_categoria->FldIsDetailKey && !is_null($this->dt_entrou_categoria->FormValue) && $this->dt_entrou_categoria->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dt_entrou_categoria->FldCaption(), $this->dt_entrou_categoria->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->dt_entrou_categoria->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_entrou_categoria->FldErrMsg());
		}
		if ($this->ativo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ativo->FldCaption(), $this->ativo->ReqErrMsg));
		}
		if (!$this->dtcad->FldIsDetailKey && !is_null($this->dtcad->FormValue) && $this->dtcad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dtcad->FldCaption(), $this->dtcad->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->dtcad->FormValue)) {
			ew_AddMessage($gsFormError, $this->dtcad->FldErrMsg());
		}
		if (!$this->dtcarteira->FldIsDetailKey && !is_null($this->dtcarteira->FormValue) && $this->dtcarteira->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dtcarteira->FldCaption(), $this->dtcarteira->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->dtcarteira->FormValue)) {
			ew_AddMessage($gsFormError, $this->dtcarteira->FldErrMsg());
		}
		if (!$this->telefone->FldIsDetailKey && !is_null($this->telefone->FormValue) && $this->telefone->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->telefone->FldCaption(), $this->telefone->ReqErrMsg));
		}
		if (!$this->acompanhante->FldIsDetailKey && !is_null($this->acompanhante->FormValue) && $this->acompanhante->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->acompanhante->FldCaption(), $this->acompanhante->ReqErrMsg));
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
			$this->foto->OldUploadPath = sistema;
			$this->foto->UploadPath = $this->foto->OldUploadPath;
		}
		$rsnew = array();

		// nome
		$this->nome->SetDbValueDef($rsnew, $this->nome->CurrentValue, NULL, FALSE);

		// endereco
		$this->endereco->SetDbValueDef($rsnew, $this->endereco->CurrentValue, NULL, FALSE);

		// numero
		$this->numero->SetDbValueDef($rsnew, $this->numero->CurrentValue, NULL, FALSE);

		// bairro
		$this->bairro->SetDbValueDef($rsnew, $this->bairro->CurrentValue, NULL, FALSE);

		// cidade
		$this->cidade->SetDbValueDef($rsnew, $this->cidade->CurrentValue, NULL, FALSE);

		// sexo
		$this->sexo->SetDbValueDef($rsnew, $this->sexo->CurrentValue, NULL, FALSE);

		// estado_civil
		$this->estado_civil->SetDbValueDef($rsnew, $this->estado_civil->CurrentValue, NULL, FALSE);

		// rg
		$this->rg->SetDbValueDef($rsnew, $this->rg->CurrentValue, NULL, FALSE);

		// cpf
		$this->cpf->SetDbValueDef($rsnew, $this->cpf->CurrentValue, NULL, FALSE);

		// carteira_trabalho
		$this->carteira_trabalho->SetDbValueDef($rsnew, $this->carteira_trabalho->CurrentValue, NULL, FALSE);

		// nacionalidade
		$this->nacionalidade->SetDbValueDef($rsnew, $this->nacionalidade->CurrentValue, NULL, FALSE);

		// naturalidade
		$this->naturalidade->SetDbValueDef($rsnew, $this->naturalidade->CurrentValue, NULL, FALSE);

		// datanasc
		$this->datanasc->SetDbValueDef($rsnew, $this->datanasc->CurrentValue, NULL, FALSE);

		// funcao
		$this->funcao->SetDbValueDef($rsnew, $this->funcao->CurrentValue, NULL, FALSE);

		// cod_empresa
		$this->cod_empresa->SetDbValueDef($rsnew, $this->cod_empresa->CurrentValue, NULL, FALSE);

		// dt_entrou_empresa
		$this->dt_entrou_empresa->SetDbValueDef($rsnew, $this->dt_entrou_empresa->CurrentValue, NULL, FALSE);

		// dt_entrou_categoria
		$this->dt_entrou_categoria->SetDbValueDef($rsnew, $this->dt_entrou_categoria->CurrentValue, NULL, FALSE);

		// foto
		if (!$this->foto->Upload->KeepFile) {
			$this->foto->Upload->DbValue = ""; // No need to delete old file
			if ($this->foto->Upload->FileName == "") {
				$rsnew['foto'] = NULL;
			} else {
				$rsnew['foto'] = $this->foto->Upload->FileName;
			}
			$this->foto->ImageWidth = 130; // Resize width
			$this->foto->ImageHeight = 141; // Resize height
		}

		// ativo
		$this->ativo->SetDbValueDef($rsnew, $this->ativo->CurrentValue, 0, strval($this->ativo->CurrentValue) == "");

		// dependentes
		$this->dependentes->SetDbValueDef($rsnew, $this->dependentes->CurrentValue, NULL, FALSE);

		// dtcad
		$this->dtcad->SetDbValueDef($rsnew, $this->dtcad->CurrentValue, NULL, FALSE);

		// dtcarteira
		$this->dtcarteira->SetDbValueDef($rsnew, $this->dtcarteira->CurrentValue, NULL, FALSE);

		// telefone
		$this->telefone->SetDbValueDef($rsnew, $this->telefone->CurrentValue, "", FALSE);

		// acompanhante
		$this->acompanhante->SetDbValueDef($rsnew, $this->acompanhante->CurrentValue, "", FALSE);
		if (!$this->foto->Upload->KeepFile) {
			$this->foto->UploadPath = sistema;
			if (!ew_Empty($this->foto->Upload->Value)) {
				$rsnew['foto'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->foto->UploadPath), $rsnew['foto']); // Get new file name
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if (!$this->foto->Upload->KeepFile) {
					if (!ew_Empty($this->foto->Upload->Value)) {
						$this->foto->Upload->Resize($this->foto->ImageWidth, $this->foto->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY);
						$this->foto->Upload->SaveToFile($this->foto->UploadPath, $rsnew['foto'], TRUE);
					}
				}
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
			$this->cod_func->setDbValue($conn->Insert_ID());
			$rsnew['cod_func'] = $this->cod_func->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// foto
		ew_CleanUploadTempPath($this->foto, $this->foto->Upload->Index);
		return $AddRow;
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
if (!isset($funcionarios_add)) $funcionarios_add = new cfuncionarios_add();

// Page init
$funcionarios_add->Page_Init();

// Page main
$funcionarios_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$funcionarios_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var funcionarios_add = new ew_Page("funcionarios_add");
funcionarios_add.PageID = "add"; // Page ID
var EW_PAGE_ID = funcionarios_add.PageID; // For backward compatibility

// Form object
var ffuncionariosadd = new ew_Form("ffuncionariosadd");

// Validate form
ffuncionariosadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nome");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->nome->FldCaption(), $funcionarios->nome->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_endereco");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->endereco->FldCaption(), $funcionarios->endereco->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_numero");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->numero->FldCaption(), $funcionarios->numero->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_numero");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($funcionarios->numero->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cidade");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->cidade->FldCaption(), $funcionarios->cidade->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sexo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->sexo->FldCaption(), $funcionarios->sexo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_estado_civil");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->estado_civil->FldCaption(), $funcionarios->estado_civil->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_rg");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->rg->FldCaption(), $funcionarios->rg->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cpf");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->cpf->FldCaption(), $funcionarios->cpf->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_carteira_trabalho");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->carteira_trabalho->FldCaption(), $funcionarios->carteira_trabalho->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nacionalidade");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->nacionalidade->FldCaption(), $funcionarios->nacionalidade->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_naturalidade");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->naturalidade->FldCaption(), $funcionarios->naturalidade->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_datanasc");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->datanasc->FldCaption(), $funcionarios->datanasc->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_datanasc");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($funcionarios->datanasc->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_funcao");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->funcao->FldCaption(), $funcionarios->funcao->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dt_entrou_empresa");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->dt_entrou_empresa->FldCaption(), $funcionarios->dt_entrou_empresa->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dt_entrou_empresa");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($funcionarios->dt_entrou_empresa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dt_entrou_categoria");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->dt_entrou_categoria->FldCaption(), $funcionarios->dt_entrou_categoria->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dt_entrou_categoria");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($funcionarios->dt_entrou_categoria->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ativo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->ativo->FldCaption(), $funcionarios->ativo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dtcad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->dtcad->FldCaption(), $funcionarios->dtcad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dtcad");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($funcionarios->dtcad->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dtcarteira");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->dtcarteira->FldCaption(), $funcionarios->dtcarteira->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dtcarteira");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($funcionarios->dtcarteira->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_telefone");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->telefone->FldCaption(), $funcionarios->telefone->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_acompanhante");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $funcionarios->acompanhante->FldCaption(), $funcionarios->acompanhante->ReqErrMsg)) ?>");

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
ffuncionariosadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffuncionariosadd.ValidateRequired = true;
<?php } else { ?>
ffuncionariosadd.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
ffuncionariosadd.MultiPage = new ew_MultiPage("ffuncionariosadd",
	[["x_nome",0],["x_endereco",2],["x_numero",2],["x_bairro",2],["x_cidade",2],["x_sexo",1],["x_estado_civil",1],["x_rg",3],["x_cpf",3],["x_carteira_trabalho",3],["x_nacionalidade",3],["x_naturalidade",3],["x_datanasc",1],["x_funcao",4],["x_cod_empresa",0],["x_dt_entrou_empresa",4],["x_dt_entrou_categoria",4],["x_foto",4],["x_ativo",1],["x_dependentes",4],["x_dtcad",4],["x_dtcarteira",4],["x_telefone",1],["x_acompanhante",1]]
);

// Dynamic selection lists
ffuncionariosadd.Lists["x_cod_empresa"] = {"LinkField":"x_cod_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_empresa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $funcionarios_add->ShowPageHeader(); ?>
<?php
$funcionarios_add->ShowMessage();
?>
<form name="ffuncionariosadd" id="ffuncionariosadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($funcionarios_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $funcionarios_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="funcionarios">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($funcionarios->nome->Visible) { // nome ?>
	<div id="r_nome" class="form-group">
		<label id="elh_funcionarios_nome" for="x_nome" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->nome->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->nome->CellAttributes() ?>>
<span id="el_funcionarios_nome">
<input type="text" data-field="x_nome" name="x_nome" id="x_nome" size="30" maxlength="60" placeholder="<?php echo ew_HtmlEncode($funcionarios->nome->PlaceHolder) ?>" value="<?php echo $funcionarios->nome->EditValue ?>"<?php echo $funcionarios->nome->EditAttributes() ?>>
</span>
<?php echo $funcionarios->nome->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->cod_empresa->Visible) { // cod_empresa ?>
	<div id="r_cod_empresa" class="form-group">
		<label id="elh_funcionarios_cod_empresa" for="x_cod_empresa" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->cod_empresa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->cod_empresa->CellAttributes() ?>>
<span id="el_funcionarios_cod_empresa">
<select data-field="x_cod_empresa" id="x_cod_empresa" name="x_cod_empresa"<?php echo $funcionarios->cod_empresa->EditAttributes() ?>>
<?php
if (is_array($funcionarios->cod_empresa->EditValue)) {
	$arwrk = $funcionarios->cod_empresa->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($funcionarios->cod_empresa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $funcionarios->cod_empresa->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_cod_empresa',url:'empresasaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_cod_empresa"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $funcionarios->cod_empresa->FldCaption() ?></span></button>
<?php
switch (@$gsLanguage) {
	case "br":
		$sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
		$sWhereWrk = "";
		break;
	default:
		$sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
		$sWhereWrk = "";
		break;
}

// Call Lookup selecting
$funcionarios->Lookup_Selecting($funcionarios->cod_empresa, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_cod_empresa" id="s_x_cod_empresa" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`cod_empresa` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $funcionarios->cod_empresa->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div>
<div class="tabbable" id="funcionarios_add">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_funcionarios1" data-toggle="tab"><?php echo $funcionarios->PageCaption(1) ?></a></li>
		<li><a href="#tab_funcionarios2" data-toggle="tab"><?php echo $funcionarios->PageCaption(2) ?></a></li>
		<li><a href="#tab_funcionarios3" data-toggle="tab"><?php echo $funcionarios->PageCaption(3) ?></a></li>
		<li><a href="#tab_funcionarios4" data-toggle="tab"><?php echo $funcionarios->PageCaption(4) ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_funcionarios1">
<div>
<?php if ($funcionarios->sexo->Visible) { // sexo ?>
	<div id="r_sexo" class="form-group">
		<label id="elh_funcionarios_sexo" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->sexo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->sexo->CellAttributes() ?>>
<span id="el_funcionarios_sexo">
<div id="tp_x_sexo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_sexo" id="x_sexo" value="{value}"<?php echo $funcionarios->sexo->EditAttributes() ?>></div>
<div id="dsl_x_sexo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $funcionarios->sexo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($funcionarios->sexo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_sexo" name="x_sexo" id="x_sexo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $funcionarios->sexo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $funcionarios->sexo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->estado_civil->Visible) { // estado_civil ?>
	<div id="r_estado_civil" class="form-group">
		<label id="elh_funcionarios_estado_civil" for="x_estado_civil" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->estado_civil->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->estado_civil->CellAttributes() ?>>
<span id="el_funcionarios_estado_civil">
<select data-field="x_estado_civil" id="x_estado_civil" name="x_estado_civil"<?php echo $funcionarios->estado_civil->EditAttributes() ?>>
<?php
if (is_array($funcionarios->estado_civil->EditValue)) {
	$arwrk = $funcionarios->estado_civil->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($funcionarios->estado_civil->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $funcionarios->estado_civil->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->datanasc->Visible) { // datanasc ?>
	<div id="r_datanasc" class="form-group">
		<label id="elh_funcionarios_datanasc" for="x_datanasc" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->datanasc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->datanasc->CellAttributes() ?>>
<span id="el_funcionarios_datanasc">
<input type="text" data-field="x_datanasc" name="x_datanasc" id="x_datanasc" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($funcionarios->datanasc->PlaceHolder) ?>" value="<?php echo $funcionarios->datanasc->EditValue ?>"<?php echo $funcionarios->datanasc->EditAttributes() ?>>
<?php if (!$funcionarios->datanasc->ReadOnly && !$funcionarios->datanasc->Disabled && !isset($funcionarios->datanasc->EditAttrs["readonly"]) && !isset($funcionarios->datanasc->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ffuncionariosadd", "x_datanasc", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $funcionarios->datanasc->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->ativo->Visible) { // ativo ?>
	<div id="r_ativo" class="form-group">
		<label id="elh_funcionarios_ativo" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->ativo->CellAttributes() ?>>
<span id="el_funcionarios_ativo">
<div id="tp_x_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ativo" id="x_ativo" value="{value}"<?php echo $funcionarios->ativo->EditAttributes() ?>></div>
<div id="dsl_x_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $funcionarios->ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($funcionarios->ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_ativo" name="x_ativo" id="x_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $funcionarios->ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $funcionarios->ativo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->telefone->Visible) { // telefone ?>
	<div id="r_telefone" class="form-group">
		<label id="elh_funcionarios_telefone" for="x_telefone" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->telefone->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->telefone->CellAttributes() ?>>
<span id="el_funcionarios_telefone">
<input type="text" data-field="x_telefone" name="x_telefone" id="x_telefone" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($funcionarios->telefone->PlaceHolder) ?>" value="<?php echo $funcionarios->telefone->EditValue ?>"<?php echo $funcionarios->telefone->EditAttributes() ?>>
</span>
<?php echo $funcionarios->telefone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->acompanhante->Visible) { // acompanhante ?>
	<div id="r_acompanhante" class="form-group">
		<label id="elh_funcionarios_acompanhante" for="x_acompanhante" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->acompanhante->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->acompanhante->CellAttributes() ?>>
<span id="el_funcionarios_acompanhante">
<textarea data-field="x_acompanhante" name="x_acompanhante" id="x_acompanhante" cols="35" rows="3" placeholder="<?php echo ew_HtmlEncode($funcionarios->acompanhante->PlaceHolder) ?>"<?php echo $funcionarios->acompanhante->EditAttributes() ?>><?php echo $funcionarios->acompanhante->EditValue ?></textarea>
</span>
<?php echo $funcionarios->acompanhante->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
		</div>
		<div class="tab-pane" id="tab_funcionarios2">
<div>
<?php if ($funcionarios->endereco->Visible) { // endereco ?>
	<div id="r_endereco" class="form-group">
		<label id="elh_funcionarios_endereco" for="x_endereco" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->endereco->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->endereco->CellAttributes() ?>>
<span id="el_funcionarios_endereco">
<input type="text" data-field="x_endereco" name="x_endereco" id="x_endereco" size="30" maxlength="60" placeholder="<?php echo ew_HtmlEncode($funcionarios->endereco->PlaceHolder) ?>" value="<?php echo $funcionarios->endereco->EditValue ?>"<?php echo $funcionarios->endereco->EditAttributes() ?>>
</span>
<?php echo $funcionarios->endereco->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->numero->Visible) { // numero ?>
	<div id="r_numero" class="form-group">
		<label id="elh_funcionarios_numero" for="x_numero" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->numero->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->numero->CellAttributes() ?>>
<span id="el_funcionarios_numero">
<input type="text" data-field="x_numero" name="x_numero" id="x_numero" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($funcionarios->numero->PlaceHolder) ?>" value="<?php echo $funcionarios->numero->EditValue ?>"<?php echo $funcionarios->numero->EditAttributes() ?>>
</span>
<?php echo $funcionarios->numero->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->bairro->Visible) { // bairro ?>
	<div id="r_bairro" class="form-group">
		<label id="elh_funcionarios_bairro" for="x_bairro" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->bairro->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->bairro->CellAttributes() ?>>
<span id="el_funcionarios_bairro">
<input type="text" data-field="x_bairro" name="x_bairro" id="x_bairro" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($funcionarios->bairro->PlaceHolder) ?>" value="<?php echo $funcionarios->bairro->EditValue ?>"<?php echo $funcionarios->bairro->EditAttributes() ?>>
</span>
<?php echo $funcionarios->bairro->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->cidade->Visible) { // cidade ?>
	<div id="r_cidade" class="form-group">
		<label id="elh_funcionarios_cidade" for="x_cidade" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->cidade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->cidade->CellAttributes() ?>>
<span id="el_funcionarios_cidade">
<input type="text" data-field="x_cidade" name="x_cidade" id="x_cidade" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($funcionarios->cidade->PlaceHolder) ?>" value="<?php echo $funcionarios->cidade->EditValue ?>"<?php echo $funcionarios->cidade->EditAttributes() ?>>
</span>
<?php echo $funcionarios->cidade->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
		</div>
		<div class="tab-pane" id="tab_funcionarios3">
<div>
<?php if ($funcionarios->rg->Visible) { // rg ?>
	<div id="r_rg" class="form-group">
		<label id="elh_funcionarios_rg" for="x_rg" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->rg->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->rg->CellAttributes() ?>>
<span id="el_funcionarios_rg">
<input type="text" data-field="x_rg" name="x_rg" id="x_rg" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($funcionarios->rg->PlaceHolder) ?>" value="<?php echo $funcionarios->rg->EditValue ?>"<?php echo $funcionarios->rg->EditAttributes() ?>>
</span>
<?php echo $funcionarios->rg->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->cpf->Visible) { // cpf ?>
	<div id="r_cpf" class="form-group">
		<label id="elh_funcionarios_cpf" for="x_cpf" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->cpf->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->cpf->CellAttributes() ?>>
<span id="el_funcionarios_cpf">
<input type="text" data-field="x_cpf" name="x_cpf" id="x_cpf" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($funcionarios->cpf->PlaceHolder) ?>" value="<?php echo $funcionarios->cpf->EditValue ?>"<?php echo $funcionarios->cpf->EditAttributes() ?>>
</span>
<?php echo $funcionarios->cpf->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->carteira_trabalho->Visible) { // carteira_trabalho ?>
	<div id="r_carteira_trabalho" class="form-group">
		<label id="elh_funcionarios_carteira_trabalho" for="x_carteira_trabalho" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->carteira_trabalho->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->carteira_trabalho->CellAttributes() ?>>
<span id="el_funcionarios_carteira_trabalho">
<input type="text" data-field="x_carteira_trabalho" name="x_carteira_trabalho" id="x_carteira_trabalho" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($funcionarios->carteira_trabalho->PlaceHolder) ?>" value="<?php echo $funcionarios->carteira_trabalho->EditValue ?>"<?php echo $funcionarios->carteira_trabalho->EditAttributes() ?>>
</span>
<?php echo $funcionarios->carteira_trabalho->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->nacionalidade->Visible) { // nacionalidade ?>
	<div id="r_nacionalidade" class="form-group">
		<label id="elh_funcionarios_nacionalidade" for="x_nacionalidade" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->nacionalidade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->nacionalidade->CellAttributes() ?>>
<span id="el_funcionarios_nacionalidade">
<input type="text" data-field="x_nacionalidade" name="x_nacionalidade" id="x_nacionalidade" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($funcionarios->nacionalidade->PlaceHolder) ?>" value="<?php echo $funcionarios->nacionalidade->EditValue ?>"<?php echo $funcionarios->nacionalidade->EditAttributes() ?>>
</span>
<?php echo $funcionarios->nacionalidade->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->naturalidade->Visible) { // naturalidade ?>
	<div id="r_naturalidade" class="form-group">
		<label id="elh_funcionarios_naturalidade" for="x_naturalidade" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->naturalidade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->naturalidade->CellAttributes() ?>>
<span id="el_funcionarios_naturalidade">
<input type="text" data-field="x_naturalidade" name="x_naturalidade" id="x_naturalidade" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($funcionarios->naturalidade->PlaceHolder) ?>" value="<?php echo $funcionarios->naturalidade->EditValue ?>"<?php echo $funcionarios->naturalidade->EditAttributes() ?>>
</span>
<?php echo $funcionarios->naturalidade->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
		</div>
		<div class="tab-pane" id="tab_funcionarios4">
<div>
<?php if ($funcionarios->funcao->Visible) { // funcao ?>
	<div id="r_funcao" class="form-group">
		<label id="elh_funcionarios_funcao" for="x_funcao" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->funcao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->funcao->CellAttributes() ?>>
<span id="el_funcionarios_funcao">
<input type="text" data-field="x_funcao" name="x_funcao" id="x_funcao" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($funcionarios->funcao->PlaceHolder) ?>" value="<?php echo $funcionarios->funcao->EditValue ?>"<?php echo $funcionarios->funcao->EditAttributes() ?>>
</span>
<?php echo $funcionarios->funcao->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->dt_entrou_empresa->Visible) { // dt_entrou_empresa ?>
	<div id="r_dt_entrou_empresa" class="form-group">
		<label id="elh_funcionarios_dt_entrou_empresa" for="x_dt_entrou_empresa" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->dt_entrou_empresa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->dt_entrou_empresa->CellAttributes() ?>>
<span id="el_funcionarios_dt_entrou_empresa">
<input type="text" data-field="x_dt_entrou_empresa" name="x_dt_entrou_empresa" id="x_dt_entrou_empresa" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($funcionarios->dt_entrou_empresa->PlaceHolder) ?>" value="<?php echo $funcionarios->dt_entrou_empresa->EditValue ?>"<?php echo $funcionarios->dt_entrou_empresa->EditAttributes() ?>>
<?php if (!$funcionarios->dt_entrou_empresa->ReadOnly && !$funcionarios->dt_entrou_empresa->Disabled && !isset($funcionarios->dt_entrou_empresa->EditAttrs["readonly"]) && !isset($funcionarios->dt_entrou_empresa->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ffuncionariosadd", "x_dt_entrou_empresa", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $funcionarios->dt_entrou_empresa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->dt_entrou_categoria->Visible) { // dt_entrou_categoria ?>
	<div id="r_dt_entrou_categoria" class="form-group">
		<label id="elh_funcionarios_dt_entrou_categoria" for="x_dt_entrou_categoria" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->dt_entrou_categoria->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->dt_entrou_categoria->CellAttributes() ?>>
<span id="el_funcionarios_dt_entrou_categoria">
<input type="text" data-field="x_dt_entrou_categoria" name="x_dt_entrou_categoria" id="x_dt_entrou_categoria" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($funcionarios->dt_entrou_categoria->PlaceHolder) ?>" value="<?php echo $funcionarios->dt_entrou_categoria->EditValue ?>"<?php echo $funcionarios->dt_entrou_categoria->EditAttributes() ?>>
<?php if (!$funcionarios->dt_entrou_categoria->ReadOnly && !$funcionarios->dt_entrou_categoria->Disabled && !isset($funcionarios->dt_entrou_categoria->EditAttrs["readonly"]) && !isset($funcionarios->dt_entrou_categoria->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ffuncionariosadd", "x_dt_entrou_categoria", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $funcionarios->dt_entrou_categoria->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->foto->Visible) { // foto ?>
	<div id="r_foto" class="form-group">
		<label id="elh_funcionarios_foto" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->foto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->foto->CellAttributes() ?>>
<span id="el_funcionarios_foto">
<div id="fd_x_foto">
<span title="<?php echo $funcionarios->foto->FldTitle() ? $funcionarios->foto->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($funcionarios->foto->ReadOnly || $funcionarios->foto->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_foto" name="x_foto" id="x_foto">
</span>
<input type="hidden" name="fn_x_foto" id= "fn_x_foto" value="<?php echo $funcionarios->foto->Upload->FileName ?>">
<input type="hidden" name="fa_x_foto" id= "fa_x_foto" value="0">
<input type="hidden" name="fs_x_foto" id= "fs_x_foto" value="50">
<input type="hidden" name="fx_x_foto" id= "fx_x_foto" value="<?php echo $funcionarios->foto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_foto" id= "fm_x_foto" value="<?php echo $funcionarios->foto->UploadMaxFileSize ?>">
</div>
<table id="ft_x_foto" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $funcionarios->foto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->dependentes->Visible) { // dependentes ?>
	<div id="r_dependentes" class="form-group">
		<label id="elh_funcionarios_dependentes" for="x_dependentes" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->dependentes->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->dependentes->CellAttributes() ?>>
<span id="el_funcionarios_dependentes">
<textarea data-field="x_dependentes" name="x_dependentes" id="x_dependentes" cols="35" rows="3" placeholder="<?php echo ew_HtmlEncode($funcionarios->dependentes->PlaceHolder) ?>"<?php echo $funcionarios->dependentes->EditAttributes() ?>><?php echo $funcionarios->dependentes->EditValue ?></textarea>
</span>
<?php echo $funcionarios->dependentes->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->dtcad->Visible) { // dtcad ?>
	<div id="r_dtcad" class="form-group">
		<label id="elh_funcionarios_dtcad" for="x_dtcad" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->dtcad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->dtcad->CellAttributes() ?>>
<span id="el_funcionarios_dtcad">
<input type="text" data-field="x_dtcad" name="x_dtcad" id="x_dtcad" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($funcionarios->dtcad->PlaceHolder) ?>" value="<?php echo $funcionarios->dtcad->EditValue ?>"<?php echo $funcionarios->dtcad->EditAttributes() ?>>
<?php if (!$funcionarios->dtcad->ReadOnly && !$funcionarios->dtcad->Disabled && !isset($funcionarios->dtcad->EditAttrs["readonly"]) && !isset($funcionarios->dtcad->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ffuncionariosadd", "x_dtcad", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $funcionarios->dtcad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($funcionarios->dtcarteira->Visible) { // dtcarteira ?>
	<div id="r_dtcarteira" class="form-group">
		<label id="elh_funcionarios_dtcarteira" for="x_dtcarteira" class="col-sm-2 control-label ewLabel"><?php echo $funcionarios->dtcarteira->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $funcionarios->dtcarteira->CellAttributes() ?>>
<span id="el_funcionarios_dtcarteira">
<input type="text" data-field="x_dtcarteira" name="x_dtcarteira" id="x_dtcarteira" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($funcionarios->dtcarteira->PlaceHolder) ?>" value="<?php echo $funcionarios->dtcarteira->EditValue ?>"<?php echo $funcionarios->dtcarteira->EditAttributes() ?>>
<?php if (!$funcionarios->dtcarteira->ReadOnly && !$funcionarios->dtcarteira->Disabled && !isset($funcionarios->dtcarteira->EditAttrs["readonly"]) && !isset($funcionarios->dtcarteira->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ffuncionariosadd", "x_dtcarteira", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $funcionarios->dtcarteira->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
		</div>
	</div>
</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
ffuncionariosadd.Init();
</script>
<?php
$funcionarios_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$funcionarios_add->Page_Terminate();
?>
