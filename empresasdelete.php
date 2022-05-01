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

$empresas_delete = NULL; // Initialize page object first

class cempresas_delete extends cempresas {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'empresas';

	// Page object name
	var $PageObjName = 'empresas_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("empresaslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in empresas class, empresasinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "D"; // Delete record directly
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
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

			// telefone
			$this->telefone->LinkCustomAttributes = "";
			$this->telefone->HrefValue = "";
			$this->telefone->TooltipValue = "";

			// cidade
			$this->cidade->LinkCustomAttributes = "";
			$this->cidade->HrefValue = "";
			$this->cidade->TooltipValue = "";

			// regiao
			$this->regiao->LinkCustomAttributes = "";
			$this->regiao->HrefValue = "";
			$this->regiao->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
		$conn->BeginTrans();

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
				$sThisKey .= $row['cod_empresa'];
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
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "empresaslist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($empresas_delete)) $empresas_delete = new cempresas_delete();

// Page init
$empresas_delete->Page_Init();

// Page main
$empresas_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empresas_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var empresas_delete = new ew_Page("empresas_delete");
empresas_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = empresas_delete.PageID; // For backward compatibility

// Form object
var fempresasdelete = new ew_Form("fempresasdelete");

// Form_CustomValidate event
fempresasdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempresasdelete.ValidateRequired = true;
<?php } else { ?>
fempresasdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fempresasdelete.Lists["x_regiao"] = {"LinkField":"x_id_regiao","Ajax":true,"AutoFill":false,"DisplayFields":["x_regiao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($empresas_delete->Recordset = $empresas_delete->LoadRecordset())
	$empresas_deleteTotalRecs = $empresas_delete->Recordset->RecordCount(); // Get record count
if ($empresas_deleteTotalRecs <= 0) { // No record found, exit
	if ($empresas_delete->Recordset)
		$empresas_delete->Recordset->Close();
	$empresas_delete->Page_Terminate("empresaslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $empresas_delete->ShowPageHeader(); ?>
<?php
$empresas_delete->ShowMessage();
?>
<form name="fempresasdelete" id="fempresasdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($empresas_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $empresas_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="empresas">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($empresas_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $empresas->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($empresas->cod_empresa->Visible) { // cod_empresa ?>
		<th><span id="elh_empresas_cod_empresa" class="empresas_cod_empresa"><?php echo $empresas->cod_empresa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas->nome_empresa->Visible) { // nome_empresa ?>
		<th><span id="elh_empresas_nome_empresa" class="empresas_nome_empresa"><?php echo $empresas->nome_empresa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas->telefone->Visible) { // telefone ?>
		<th><span id="elh_empresas_telefone" class="empresas_telefone"><?php echo $empresas->telefone->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas->cidade->Visible) { // cidade ?>
		<th><span id="elh_empresas_cidade" class="empresas_cidade"><?php echo $empresas->cidade->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas->regiao->Visible) { // regiao ?>
		<th><span id="elh_empresas_regiao" class="empresas_regiao"><?php echo $empresas->regiao->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$empresas_delete->RecCnt = 0;
$i = 0;
while (!$empresas_delete->Recordset->EOF) {
	$empresas_delete->RecCnt++;
	$empresas_delete->RowCnt++;

	// Set row properties
	$empresas->ResetAttrs();
	$empresas->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$empresas_delete->LoadRowValues($empresas_delete->Recordset);

	// Render row
	$empresas_delete->RenderRow();
?>
	<tr<?php echo $empresas->RowAttributes() ?>>
<?php if ($empresas->cod_empresa->Visible) { // cod_empresa ?>
		<td<?php echo $empresas->cod_empresa->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas_cod_empresa" class="empresas_cod_empresa">
<span<?php echo $empresas->cod_empresa->ViewAttributes() ?>>
<?php echo $empresas->cod_empresa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas->nome_empresa->Visible) { // nome_empresa ?>
		<td<?php echo $empresas->nome_empresa->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas_nome_empresa" class="empresas_nome_empresa">
<span<?php echo $empresas->nome_empresa->ViewAttributes() ?>>
<?php echo $empresas->nome_empresa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas->telefone->Visible) { // telefone ?>
		<td<?php echo $empresas->telefone->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas_telefone" class="empresas_telefone">
<span<?php echo $empresas->telefone->ViewAttributes() ?>>
<?php echo $empresas->telefone->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas->cidade->Visible) { // cidade ?>
		<td<?php echo $empresas->cidade->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas_cidade" class="empresas_cidade">
<span<?php echo $empresas->cidade->ViewAttributes() ?>>
<?php echo $empresas->cidade->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas->regiao->Visible) { // regiao ?>
		<td<?php echo $empresas->regiao->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas_regiao" class="empresas_regiao">
<span<?php echo $empresas->regiao->ViewAttributes() ?>>
<?php echo $empresas->regiao->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$empresas_delete->Recordset->MoveNext();
}
$empresas_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fempresasdelete.Init();
</script>
<?php
$empresas_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empresas_delete->Page_Terminate();
?>
