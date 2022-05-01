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

$socios_search = NULL; // Initialize page object first

class csocios_search extends csocios {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{31ECB1FF-BE34-4CC5-BF5C-0D3F1718C9D9}";

	// Table name
	var $TableName = 'socios';

	// Page object name
	var $PageObjName = 'socios_search';

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
			define("EW_PAGE_ID", 'search', TRUE);

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
	var $IsModal = FALSE;
	var $SearchLabelClass = "col-sm-3 control-label ewLabel";
	var $SearchRightColumnClass = "col-sm-9";

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;
		global $gbSkipHeaderFooter;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$sSrchStr = "socioslist.php" . "?" . $sSrchStr;
						if ($this->IsModal) {
							$row = array();
							$row["url"] = $sSrchStr;
							echo ew_ArrayToJson(array($row));
							$this->Page_Terminate();
							exit();
						} else {
							$this->Page_Terminate($sSrchStr); // Go to list page
						}
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->socio); // socio
		$this->BuildSearchUrl($sSrchUrl, $this->validade); // validade
		$this->BuildSearchUrl($sSrchUrl, $this->ativo); // ativo
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// socio

		$this->socio->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_socio"));
		$this->socio->AdvancedSearch->SearchOperator = $objForm->GetValue("z_socio");

		// validade
		$this->validade->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_validade"));
		$this->validade->AdvancedSearch->SearchOperator = $objForm->GetValue("z_validade");
		$this->validade->AdvancedSearch->SearchCondition = $objForm->GetValue("v_validade");
		$this->validade->AdvancedSearch->SearchValue2 = ew_StripSlashes($objForm->GetValue("y_validade"));
		$this->validade->AdvancedSearch->SearchOperator2 = $objForm->GetValue("w_validade");

		// ativo
		$this->ativo->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_ativo"));
		$this->ativo->AdvancedSearch->SearchOperator = $objForm->GetValue("z_ativo");
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

			// validade
			$this->validade->LinkCustomAttributes = "";
			$this->validade->HrefValue = "";
			$this->validade->TooltipValue = "";

			// ativo
			$this->ativo->LinkCustomAttributes = "";
			$this->ativo->HrefValue = "";
			$this->ativo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// socio
			$this->socio->EditAttrs["class"] = "form-control";
			$this->socio->EditCustomAttributes = "";
			$this->socio->EditValue = ew_HtmlEncode($this->socio->AdvancedSearch->SearchValue);
			$this->socio->PlaceHolder = ew_RemoveHtml($this->socio->FldCaption());

			// validade
			$this->validade->EditAttrs["class"] = "form-control";
			$this->validade->EditCustomAttributes = "";
			$this->validade->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->validade->AdvancedSearch->SearchValue, 7), 7));
			$this->validade->PlaceHolder = ew_RemoveHtml($this->validade->FldCaption());
			$this->validade->EditAttrs["class"] = "form-control";
			$this->validade->EditCustomAttributes = "";
			$this->validade->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->validade->AdvancedSearch->SearchValue2, 7), 7));
			$this->validade->PlaceHolder = ew_RemoveHtml($this->validade->FldCaption());

			// ativo
			$this->ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ativo->FldTagValue(1), $this->ativo->FldTagCaption(1) <> "" ? $this->ativo->FldTagCaption(1) : $this->ativo->FldTagValue(1));
			$arwrk[] = array($this->ativo->FldTagValue(2), $this->ativo->FldTagCaption(2) <> "" ? $this->ativo->FldTagCaption(2) : $this->ativo->FldTagValue(2));
			$this->ativo->EditValue = $arwrk;
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
		if (!ew_CheckEuroDate($this->validade->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->validade->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->validade->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->validade->FldErrMsg());
		}

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

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->socio->AdvancedSearch->Load();
		$this->validade->AdvancedSearch->Load();
		$this->ativo->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "socioslist.php", "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
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
if (!isset($socios_search)) $socios_search = new csocios_search();

// Page init
$socios_search->Page_Init();

// Page main
$socios_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$socios_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var socios_search = new ew_Page("socios_search");
socios_search.PageID = "search"; // Page ID
var EW_PAGE_ID = socios_search.PageID; // For backward compatibility

// Form object
var fsociossearch = new ew_Form("fsociossearch");

// Form_CustomValidate event
fsociossearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsociossearch.ValidateRequired = true;
<?php } else { ?>
fsociossearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsociossearch.Lists["x_socio"] = {"LinkField":"x_cod_pessoa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
// Validate function for search

fsociossearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_validade");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($socios->validade->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$socios_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $socios_search->ShowPageHeader(); ?>
<?php
$socios_search->ShowMessage();
?>
<form name="fsociossearch" id="fsociossearch" class="form-horizontal ewForm ewSearchForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($socios_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $socios_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="socios">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($socios_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($socios->socio->Visible) { // socio ?>
	<div id="r_socio" class="form-group">
		<label for="x_socio" class="<?php echo $socios_search->SearchLabelClass ?>"><span id="elh_socios_socio"><?php echo $socios->socio->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_socio" id="z_socio" value="LIKE"></p>
		</label>
		<div class="<?php echo $socios_search->SearchRightColumnClass ?>"><div<?php echo $socios->socio->CellAttributes() ?>>
			<span id="el_socios_socio">
<input type="text" data-field="x_socio" name="x_socio" id="x_socio" size="30" placeholder="<?php echo ew_HtmlEncode($socios->socio->PlaceHolder) ?>" value="<?php echo $socios->socio->EditValue ?>"<?php echo $socios->socio->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($socios->validade->Visible) { // validade ?>
	<div id="r_validade" class="form-group">
		<label for="x_validade" class="<?php echo $socios_search->SearchLabelClass ?>"><span id="elh_socios_validade"><?php echo $socios->validade->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_validade" id="z_validade" value="BETWEEN"></p>
		</label>
		<div class="<?php echo $socios_search->SearchRightColumnClass ?>"><div<?php echo $socios->validade->CellAttributes() ?>>
			<span id="el_socios_validade">
<input type="text" data-field="x_validade" name="x_validade" id="x_validade" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->validade->PlaceHolder) ?>" value="<?php echo $socios->validade->EditValue ?>"<?php echo $socios->validade->EditAttributes() ?>>
<?php if (!$socios->validade->ReadOnly && !$socios->validade->Disabled && !isset($socios->validade->EditAttrs["readonly"]) && !isset($socios->validade->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsociossearch", "x_validade", "%d/%m/%Y");
</script>
<?php } ?>
</span>
			<span class="ewSearchCond btw1_validade">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
			<span id="e2_socios_validade" class="btw1_validade">
<input type="text" data-field="x_validade" name="y_validade" id="y_validade" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->validade->PlaceHolder) ?>" value="<?php echo $socios->validade->EditValue2 ?>"<?php echo $socios->validade->EditAttributes() ?>>
<?php if (!$socios->validade->ReadOnly && !$socios->validade->Disabled && !isset($socios->validade->EditAttrs["readonly"]) && !isset($socios->validade->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsociossearch", "y_validade", "%d/%m/%Y");
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($socios->ativo->Visible) { // ativo ?>
	<div id="r_ativo" class="form-group">
		<label class="<?php echo $socios_search->SearchLabelClass ?>"><span id="elh_socios_ativo"><?php echo $socios->ativo->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_ativo" id="z_ativo" value="="></p>
		</label>
		<div class="<?php echo $socios_search->SearchRightColumnClass ?>"><div<?php echo $socios->ativo->CellAttributes() ?>>
			<span id="el_socios_ativo">
<div id="tp_x_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ativo" id="x_ativo" value="{value}"<?php echo $socios->ativo->EditAttributes() ?>></div>
<div id="dsl_x_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $socios->ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->ativo->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_ativo" name="x_ativo" id="x_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$socios_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fsociossearch.Init();
</script>
<?php
$socios_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$socios_search->Page_Terminate();
?>
