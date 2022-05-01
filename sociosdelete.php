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

$socios_delete = NULL; // Initialize page object first

class csocios_delete extends csocios {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'socios';

	// Page object name
	var $PageObjName = 'socios_delete';

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

		// Table object (socios)
		if (!isset($GLOBALS["socios"]) || get_class($GLOBALS["socios"]) == "csocios") {
			$GLOBALS["socios"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["socios"];
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
			define("EW_TABLE_NAME", 'socios', TRUE);

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
			$this->Page_Terminate("socioslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in socios class, sociosinfo.php

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

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
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
		$Breadcrumb->Add("list", $this->TableVar, "socioslist.php", "", $this->TableVar, TRUE);
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
if (!isset($socios_delete)) $socios_delete = new csocios_delete();

// Page init
$socios_delete->Page_Init();

// Page main
$socios_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$socios_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var socios_delete = new ew_Page("socios_delete");
socios_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = socios_delete.PageID; // For backward compatibility

// Form object
var fsociosdelete = new ew_Form("fsociosdelete");

// Form_CustomValidate event
fsociosdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsociosdelete.ValidateRequired = true;
<?php } else { ?>
fsociosdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsociosdelete.Lists["x_socio"] = {"LinkField":"x_cod_pessoa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsociosdelete.Lists["x_cod_empresa"] = {"LinkField":"x_cod_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_empresa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($socios_delete->Recordset = $socios_delete->LoadRecordset())
	$socios_deleteTotalRecs = $socios_delete->Recordset->RecordCount(); // Get record count
if ($socios_deleteTotalRecs <= 0) { // No record found, exit
	if ($socios_delete->Recordset)
		$socios_delete->Recordset->Close();
	$socios_delete->Page_Terminate("socioslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $socios_delete->ShowPageHeader(); ?>
<?php
$socios_delete->ShowMessage();
?>
<form name="fsociosdelete" id="fsociosdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($socios_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $socios_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="socios">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($socios_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $socios->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($socios->cod_socio->Visible) { // cod_socio ?>
		<th><span id="elh_socios_cod_socio" class="socios_cod_socio"><?php echo $socios->cod_socio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($socios->socio->Visible) { // socio ?>
		<th><span id="elh_socios_socio" class="socios_socio"><?php echo $socios->socio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($socios->cod_empresa->Visible) { // cod_empresa ?>
		<th><span id="elh_socios_cod_empresa" class="socios_cod_empresa"><?php echo $socios->cod_empresa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($socios->dt_cadastro->Visible) { // dt_cadastro ?>
		<th><span id="elh_socios_dt_cadastro" class="socios_dt_cadastro"><?php echo $socios->dt_cadastro->FldCaption() ?></span></th>
<?php } ?>
<?php if ($socios->validade->Visible) { // validade ?>
		<th><span id="elh_socios_validade" class="socios_validade"><?php echo $socios->validade->FldCaption() ?></span></th>
<?php } ?>
<?php if ($socios->ativo->Visible) { // ativo ?>
		<th><span id="elh_socios_ativo" class="socios_ativo"><?php echo $socios->ativo->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$socios_delete->RecCnt = 0;
$i = 0;
while (!$socios_delete->Recordset->EOF) {
	$socios_delete->RecCnt++;
	$socios_delete->RowCnt++;

	// Set row properties
	$socios->ResetAttrs();
	$socios->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$socios_delete->LoadRowValues($socios_delete->Recordset);

	// Render row
	$socios_delete->RenderRow();
?>
	<tr<?php echo $socios->RowAttributes() ?>>
<?php if ($socios->cod_socio->Visible) { // cod_socio ?>
		<td<?php echo $socios->cod_socio->CellAttributes() ?>>
<span id="el<?php echo $socios_delete->RowCnt ?>_socios_cod_socio" class="socios_cod_socio">
<span<?php echo $socios->cod_socio->ViewAttributes() ?>>
<?php echo $socios->cod_socio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($socios->socio->Visible) { // socio ?>
		<td<?php echo $socios->socio->CellAttributes() ?>>
<span id="el<?php echo $socios_delete->RowCnt ?>_socios_socio" class="socios_socio">
<span<?php echo $socios->socio->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($socios->socio->ListViewValue())) && $socios->socio->LinkAttributes() <> "") { ?>
<a<?php echo $socios->socio->LinkAttributes() ?>><?php echo $socios->socio->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $socios->socio->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($socios->cod_empresa->Visible) { // cod_empresa ?>
		<td<?php echo $socios->cod_empresa->CellAttributes() ?>>
<span id="el<?php echo $socios_delete->RowCnt ?>_socios_cod_empresa" class="socios_cod_empresa">
<span<?php echo $socios->cod_empresa->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($socios->cod_empresa->ListViewValue())) && $socios->cod_empresa->LinkAttributes() <> "") { ?>
<a<?php echo $socios->cod_empresa->LinkAttributes() ?>><?php echo $socios->cod_empresa->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $socios->cod_empresa->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($socios->dt_cadastro->Visible) { // dt_cadastro ?>
		<td<?php echo $socios->dt_cadastro->CellAttributes() ?>>
<span id="el<?php echo $socios_delete->RowCnt ?>_socios_dt_cadastro" class="socios_dt_cadastro">
<span<?php echo $socios->dt_cadastro->ViewAttributes() ?>>
<?php echo $socios->dt_cadastro->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($socios->validade->Visible) { // validade ?>
		<td<?php echo $socios->validade->CellAttributes() ?>>
<span id="el<?php echo $socios_delete->RowCnt ?>_socios_validade" class="socios_validade">
<span<?php echo $socios->validade->ViewAttributes() ?>>
<?php echo $socios->validade->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($socios->ativo->Visible) { // ativo ?>
		<td<?php echo $socios->ativo->CellAttributes() ?>>
<span id="el<?php echo $socios_delete->RowCnt ?>_socios_ativo" class="socios_ativo">
<span<?php echo $socios->ativo->ViewAttributes() ?>>
<?php echo $socios->ativo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$socios_delete->Recordset->MoveNext();
}
$socios_delete->Recordset->Close();
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
fsociosdelete.Init();
</script>
<?php
$socios_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$socios_delete->Page_Terminate();
?>
