<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "permissoesinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$permissoes_delete = NULL; // Initialize page object first

class cpermissoes_delete extends cpermissoes {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'permissoes';

	// Page object name
	var $PageObjName = 'permissoes_delete';

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

		// Table object (permissoes)
		if (!isset($GLOBALS["permissoes"]) || get_class($GLOBALS["permissoes"]) == "cpermissoes") {
			$GLOBALS["permissoes"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["permissoes"];
		}

		// User table object (permissoes)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cpermissoes();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'permissoes', TRUE);

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
		$this->a00id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $permissoes;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($permissoes);
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
			$this->Page_Terminate("permissoeslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in permissoes class, permissoesinfo.php

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
		$this->a00id->setDbValue($rs->fields('a00id'));
		$this->a01nome->setDbValue($rs->fields('a01nome'));
		$this->a02email->setDbValue($rs->fields('a02email'));
		$this->a03senha->setDbValue($rs->fields('a03senha'));
		$this->a04ativo->setDbValue($rs->fields('a04ativo'));
		$this->a05cadastro->setDbValue($rs->fields('a05cadastro'));
		$this->a06permissoes->setDbValue($rs->fields('a06permissoes'));
		$this->a07ultimo->setDbValue($rs->fields('a07ultimo'));
		$this->a08acessos->setDbValue($rs->fields('a08acessos'));
		$this->a09tipo->setDbValue($rs->fields('a09tipo'));
		$this->a10full->setDbValue($rs->fields('a10full'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->a00id->DbValue = $row['a00id'];
		$this->a01nome->DbValue = $row['a01nome'];
		$this->a02email->DbValue = $row['a02email'];
		$this->a03senha->DbValue = $row['a03senha'];
		$this->a04ativo->DbValue = $row['a04ativo'];
		$this->a05cadastro->DbValue = $row['a05cadastro'];
		$this->a06permissoes->DbValue = $row['a06permissoes'];
		$this->a07ultimo->DbValue = $row['a07ultimo'];
		$this->a08acessos->DbValue = $row['a08acessos'];
		$this->a09tipo->DbValue = $row['a09tipo'];
		$this->a10full->DbValue = $row['a10full'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// a00id
		// a01nome
		// a02email
		// a03senha
		// a04ativo

		$this->a04ativo->CellCssStyle = "white-space: nowrap;";

		// a05cadastro
		$this->a05cadastro->CellCssStyle = "white-space: nowrap;";

		// a06permissoes
		$this->a06permissoes->CellCssStyle = "white-space: nowrap;";

		// a07ultimo
		$this->a07ultimo->CellCssStyle = "white-space: nowrap;";

		// a08acessos
		$this->a08acessos->CellCssStyle = "white-space: nowrap;";

		// a09tipo
		$this->a09tipo->CellCssStyle = "white-space: nowrap;";

		// a10full
		$this->a10full->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// a00id
			$this->a00id->ViewValue = $this->a00id->CurrentValue;
			$this->a00id->ViewCustomAttributes = "";

			// a01nome
			$this->a01nome->ViewValue = $this->a01nome->CurrentValue;
			$this->a01nome->ViewCustomAttributes = "";

			// a02email
			$this->a02email->ViewValue = $this->a02email->CurrentValue;
			$this->a02email->ViewCustomAttributes = "";

			// a03senha
			$this->a03senha->ViewValue = "********";
			$this->a03senha->ViewCustomAttributes = "";

			// a00id
			$this->a00id->LinkCustomAttributes = "";
			$this->a00id->HrefValue = "";
			$this->a00id->TooltipValue = "";

			// a01nome
			$this->a01nome->LinkCustomAttributes = "";
			$this->a01nome->HrefValue = "";
			$this->a01nome->TooltipValue = "";

			// a02email
			$this->a02email->LinkCustomAttributes = "";
			$this->a02email->HrefValue = "";
			$this->a02email->TooltipValue = "";

			// a03senha
			$this->a03senha->LinkCustomAttributes = "";
			$this->a03senha->HrefValue = "";
			$this->a03senha->TooltipValue = "";
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
				$sThisKey .= $row['a00id'];
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
		$Breadcrumb->Add("list", $this->TableVar, "permissoeslist.php", "", $this->TableVar, TRUE);
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
if (!isset($permissoes_delete)) $permissoes_delete = new cpermissoes_delete();

// Page init
$permissoes_delete->Page_Init();

// Page main
$permissoes_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$permissoes_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var permissoes_delete = new ew_Page("permissoes_delete");
permissoes_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = permissoes_delete.PageID; // For backward compatibility

// Form object
var fpermissoesdelete = new ew_Form("fpermissoesdelete");

// Form_CustomValidate event
fpermissoesdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpermissoesdelete.ValidateRequired = true;
<?php } else { ?>
fpermissoesdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($permissoes_delete->Recordset = $permissoes_delete->LoadRecordset())
	$permissoes_deleteTotalRecs = $permissoes_delete->Recordset->RecordCount(); // Get record count
if ($permissoes_deleteTotalRecs <= 0) { // No record found, exit
	if ($permissoes_delete->Recordset)
		$permissoes_delete->Recordset->Close();
	$permissoes_delete->Page_Terminate("permissoeslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $permissoes_delete->ShowPageHeader(); ?>
<?php
$permissoes_delete->ShowMessage();
?>
<form name="fpermissoesdelete" id="fpermissoesdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($permissoes_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $permissoes_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="permissoes">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($permissoes_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $permissoes->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($permissoes->a00id->Visible) { // a00id ?>
		<th><span id="elh_permissoes_a00id" class="permissoes_a00id"><?php echo $permissoes->a00id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($permissoes->a01nome->Visible) { // a01nome ?>
		<th><span id="elh_permissoes_a01nome" class="permissoes_a01nome"><?php echo $permissoes->a01nome->FldCaption() ?></span></th>
<?php } ?>
<?php if ($permissoes->a02email->Visible) { // a02email ?>
		<th><span id="elh_permissoes_a02email" class="permissoes_a02email"><?php echo $permissoes->a02email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($permissoes->a03senha->Visible) { // a03senha ?>
		<th><span id="elh_permissoes_a03senha" class="permissoes_a03senha"><?php echo $permissoes->a03senha->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$permissoes_delete->RecCnt = 0;
$i = 0;
while (!$permissoes_delete->Recordset->EOF) {
	$permissoes_delete->RecCnt++;
	$permissoes_delete->RowCnt++;

	// Set row properties
	$permissoes->ResetAttrs();
	$permissoes->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$permissoes_delete->LoadRowValues($permissoes_delete->Recordset);

	// Render row
	$permissoes_delete->RenderRow();
?>
	<tr<?php echo $permissoes->RowAttributes() ?>>
<?php if ($permissoes->a00id->Visible) { // a00id ?>
		<td<?php echo $permissoes->a00id->CellAttributes() ?>>
<span id="el<?php echo $permissoes_delete->RowCnt ?>_permissoes_a00id" class="permissoes_a00id">
<span<?php echo $permissoes->a00id->ViewAttributes() ?>>
<?php echo $permissoes->a00id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($permissoes->a01nome->Visible) { // a01nome ?>
		<td<?php echo $permissoes->a01nome->CellAttributes() ?>>
<span id="el<?php echo $permissoes_delete->RowCnt ?>_permissoes_a01nome" class="permissoes_a01nome">
<span<?php echo $permissoes->a01nome->ViewAttributes() ?>>
<?php echo $permissoes->a01nome->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($permissoes->a02email->Visible) { // a02email ?>
		<td<?php echo $permissoes->a02email->CellAttributes() ?>>
<span id="el<?php echo $permissoes_delete->RowCnt ?>_permissoes_a02email" class="permissoes_a02email">
<span<?php echo $permissoes->a02email->ViewAttributes() ?>>
<?php echo $permissoes->a02email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($permissoes->a03senha->Visible) { // a03senha ?>
		<td<?php echo $permissoes->a03senha->CellAttributes() ?>>
<span id="el<?php echo $permissoes_delete->RowCnt ?>_permissoes_a03senha" class="permissoes_a03senha">
<span<?php echo $permissoes->a03senha->ViewAttributes() ?>>
<?php echo $permissoes->a03senha->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$permissoes_delete->Recordset->MoveNext();
}
$permissoes_delete->Recordset->Close();
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
fpermissoesdelete.Init();
</script>
<?php
$permissoes_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$permissoes_delete->Page_Terminate();
?>
