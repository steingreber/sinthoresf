<?php include_once "permissoesinfo.php" ?>
<?php

// Create page object
if (!isset($socios_grid)) $socios_grid = new csocios_grid();

// Page init
$socios_grid->Page_Init();

// Page main
$socios_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$socios_grid->Page_Render();
?>
<?php if ($socios->Export == "") { ?>
<script type="text/javascript">

// Page object
var socios_grid = new ew_Page("socios_grid");
socios_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = socios_grid.PageID; // For backward compatibility

// Form object
var fsociosgrid = new ew_Form("fsociosgrid");
fsociosgrid.FormKeyCountName = '<?php echo $socios_grid->FormKeyCountName ?>';

// Validate form
fsociosgrid.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_socio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->socio->FldCaption(), $socios->socio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cod_empresa");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->cod_empresa->FldCaption(), $socios->cod_empresa->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dt_cadastro");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->dt_cadastro->FldCaption(), $socios->dt_cadastro->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dt_cadastro");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios->dt_cadastro->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_validade");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->validade->FldCaption(), $socios->validade->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_validade");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios->validade->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ativo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->ativo->FldCaption(), $socios->ativo->ReqErrMsg)) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fsociosgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "socio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "cod_empresa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "dt_cadastro", false)) return false;
	if (ew_ValueChanged(fobj, infix, "validade", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ativo", false)) return false;
	return true;
}

// Form_CustomValidate event
fsociosgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsociosgrid.ValidateRequired = true;
<?php } else { ?>
fsociosgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsociosgrid.Lists["x_socio"] = {"LinkField":"x_cod_pessoa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsociosgrid.Lists["x_cod_empresa"] = {"LinkField":"x_cod_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_empresa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($socios->CurrentAction == "gridadd") {
	if ($socios->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$socios_grid->TotalRecs = $socios->SelectRecordCount();
			$socios_grid->Recordset = $socios_grid->LoadRecordset($socios_grid->StartRec-1, $socios_grid->DisplayRecs);
		} else {
			if ($socios_grid->Recordset = $socios_grid->LoadRecordset())
				$socios_grid->TotalRecs = $socios_grid->Recordset->RecordCount();
		}
		$socios_grid->StartRec = 1;
		$socios_grid->DisplayRecs = $socios_grid->TotalRecs;
	} else {
		$socios->CurrentFilter = "0=1";
		$socios_grid->StartRec = 1;
		$socios_grid->DisplayRecs = $socios->GridAddRowCount;
	}
	$socios_grid->TotalRecs = $socios_grid->DisplayRecs;
	$socios_grid->StopRec = $socios_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($socios_grid->TotalRecs <= 0)
			$socios_grid->TotalRecs = $socios->SelectRecordCount();
	} else {
		if (!$socios_grid->Recordset && ($socios_grid->Recordset = $socios_grid->LoadRecordset()))
			$socios_grid->TotalRecs = $socios_grid->Recordset->RecordCount();
	}
	$socios_grid->StartRec = 1;
	$socios_grid->DisplayRecs = $socios_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$socios_grid->Recordset = $socios_grid->LoadRecordset($socios_grid->StartRec-1, $socios_grid->DisplayRecs);

	// Set no record found message
	if ($socios->CurrentAction == "" && $socios_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$socios_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($socios_grid->SearchWhere == "0=101")
			$socios_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$socios_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$socios_grid->RenderOtherOptions();
?>
<?php $socios_grid->ShowPageHeader(); ?>
<?php
$socios_grid->ShowMessage();
?>
<?php if ($socios_grid->TotalRecs > 0 || $socios->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fsociosgrid" class="ewForm form-inline">
<?php if ($socios_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($socios_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_socios" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_sociosgrid" class="table ewTable">
<?php echo $socios->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$socios->RowType = EW_ROWTYPE_HEADER;

// Render list options
$socios_grid->RenderListOptions();

// Render list options (header, left)
$socios_grid->ListOptions->Render("header", "left");
?>
<?php if ($socios->cod_socio->Visible) { // cod_socio ?>
	<?php if ($socios->SortUrl($socios->cod_socio) == "") { ?>
		<th data-name="cod_socio"><div id="elh_socios_cod_socio" class="socios_cod_socio"><div class="ewTableHeaderCaption"><?php echo $socios->cod_socio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cod_socio"><div><div id="elh_socios_cod_socio" class="socios_cod_socio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->cod_socio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->cod_socio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->cod_socio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->socio->Visible) { // socio ?>
	<?php if ($socios->SortUrl($socios->socio) == "") { ?>
		<th data-name="socio"><div id="elh_socios_socio" class="socios_socio"><div class="ewTableHeaderCaption"><?php echo $socios->socio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="socio"><div><div id="elh_socios_socio" class="socios_socio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->socio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->socio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->socio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->cod_empresa->Visible) { // cod_empresa ?>
	<?php if ($socios->SortUrl($socios->cod_empresa) == "") { ?>
		<th data-name="cod_empresa"><div id="elh_socios_cod_empresa" class="socios_cod_empresa"><div class="ewTableHeaderCaption"><?php echo $socios->cod_empresa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cod_empresa"><div><div id="elh_socios_cod_empresa" class="socios_cod_empresa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->cod_empresa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->cod_empresa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->cod_empresa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->dt_cadastro->Visible) { // dt_cadastro ?>
	<?php if ($socios->SortUrl($socios->dt_cadastro) == "") { ?>
		<th data-name="dt_cadastro"><div id="elh_socios_dt_cadastro" class="socios_dt_cadastro"><div class="ewTableHeaderCaption"><?php echo $socios->dt_cadastro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dt_cadastro"><div><div id="elh_socios_dt_cadastro" class="socios_dt_cadastro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->dt_cadastro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->dt_cadastro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->dt_cadastro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->validade->Visible) { // validade ?>
	<?php if ($socios->SortUrl($socios->validade) == "") { ?>
		<th data-name="validade"><div id="elh_socios_validade" class="socios_validade"><div class="ewTableHeaderCaption"><?php echo $socios->validade->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="validade"><div><div id="elh_socios_validade" class="socios_validade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->validade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->validade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->validade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->ativo->Visible) { // ativo ?>
	<?php if ($socios->SortUrl($socios->ativo) == "") { ?>
		<th data-name="ativo"><div id="elh_socios_ativo" class="socios_ativo"><div class="ewTableHeaderCaption"><?php echo $socios->ativo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ativo"><div><div id="elh_socios_ativo" class="socios_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$socios_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$socios_grid->StartRec = 1;
$socios_grid->StopRec = $socios_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($socios_grid->FormKeyCountName) && ($socios->CurrentAction == "gridadd" || $socios->CurrentAction == "gridedit" || $socios->CurrentAction == "F")) {
		$socios_grid->KeyCount = $objForm->GetValue($socios_grid->FormKeyCountName);
		$socios_grid->StopRec = $socios_grid->StartRec + $socios_grid->KeyCount - 1;
	}
}
$socios_grid->RecCnt = $socios_grid->StartRec - 1;
if ($socios_grid->Recordset && !$socios_grid->Recordset->EOF) {
	$socios_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $socios_grid->StartRec > 1)
		$socios_grid->Recordset->Move($socios_grid->StartRec - 1);
} elseif (!$socios->AllowAddDeleteRow && $socios_grid->StopRec == 0) {
	$socios_grid->StopRec = $socios->GridAddRowCount;
}

// Initialize aggregate
$socios->RowType = EW_ROWTYPE_AGGREGATEINIT;
$socios->ResetAttrs();
$socios_grid->RenderRow();
if ($socios->CurrentAction == "gridadd")
	$socios_grid->RowIndex = 0;
if ($socios->CurrentAction == "gridedit")
	$socios_grid->RowIndex = 0;
while ($socios_grid->RecCnt < $socios_grid->StopRec) {
	$socios_grid->RecCnt++;
	if (intval($socios_grid->RecCnt) >= intval($socios_grid->StartRec)) {
		$socios_grid->RowCnt++;
		if ($socios->CurrentAction == "gridadd" || $socios->CurrentAction == "gridedit" || $socios->CurrentAction == "F") {
			$socios_grid->RowIndex++;
			$objForm->Index = $socios_grid->RowIndex;
			if ($objForm->HasValue($socios_grid->FormActionName))
				$socios_grid->RowAction = strval($objForm->GetValue($socios_grid->FormActionName));
			elseif ($socios->CurrentAction == "gridadd")
				$socios_grid->RowAction = "insert";
			else
				$socios_grid->RowAction = "";
		}

		// Set up key count
		$socios_grid->KeyCount = $socios_grid->RowIndex;

		// Init row class and style
		$socios->ResetAttrs();
		$socios->CssClass = "";
		if ($socios->CurrentAction == "gridadd") {
			if ($socios->CurrentMode == "copy") {
				$socios_grid->LoadRowValues($socios_grid->Recordset); // Load row values
				$socios_grid->SetRecordKey($socios_grid->RowOldKey, $socios_grid->Recordset); // Set old record key
			} else {
				$socios_grid->LoadDefaultValues(); // Load default values
				$socios_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$socios_grid->LoadRowValues($socios_grid->Recordset); // Load row values
		}
		$socios->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($socios->CurrentAction == "gridadd") // Grid add
			$socios->RowType = EW_ROWTYPE_ADD; // Render add
		if ($socios->CurrentAction == "gridadd" && $socios->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$socios_grid->RestoreCurrentRowFormValues($socios_grid->RowIndex); // Restore form values
		if ($socios->CurrentAction == "gridedit") { // Grid edit
			if ($socios->EventCancelled) {
				$socios_grid->RestoreCurrentRowFormValues($socios_grid->RowIndex); // Restore form values
			}
			if ($socios_grid->RowAction == "insert")
				$socios->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$socios->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($socios->CurrentAction == "gridedit" && ($socios->RowType == EW_ROWTYPE_EDIT || $socios->RowType == EW_ROWTYPE_ADD) && $socios->EventCancelled) // Update failed
			$socios_grid->RestoreCurrentRowFormValues($socios_grid->RowIndex); // Restore form values
		if ($socios->RowType == EW_ROWTYPE_EDIT) // Edit row
			$socios_grid->EditRowCnt++;
		if ($socios->CurrentAction == "F") // Confirm row
			$socios_grid->RestoreCurrentRowFormValues($socios_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$socios->RowAttrs = array_merge($socios->RowAttrs, array('data-rowindex'=>$socios_grid->RowCnt, 'id'=>'r' . $socios_grid->RowCnt . '_socios', 'data-rowtype'=>$socios->RowType));

		// Render row
		$socios_grid->RenderRow();

		// Render list options
		$socios_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($socios_grid->RowAction <> "delete" && $socios_grid->RowAction <> "insertdelete" && !($socios_grid->RowAction == "insert" && $socios->CurrentAction == "F" && $socios_grid->EmptyRow())) {
?>
	<tr<?php echo $socios->RowAttributes() ?>>
<?php

// Render list options (body, left)
$socios_grid->ListOptions->Render("body", "left", $socios_grid->RowCnt);
?>
	<?php if ($socios->cod_socio->Visible) { // cod_socio ?>
		<td data-name="cod_socio"<?php echo $socios->cod_socio->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_cod_socio" name="o<?php echo $socios_grid->RowIndex ?>_cod_socio" id="o<?php echo $socios_grid->RowIndex ?>_cod_socio" value="<?php echo ew_HtmlEncode($socios->cod_socio->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_cod_socio" class="form-group socios_cod_socio">
<span<?php echo $socios->cod_socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->cod_socio->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_cod_socio" name="x<?php echo $socios_grid->RowIndex ?>_cod_socio" id="x<?php echo $socios_grid->RowIndex ?>_cod_socio" value="<?php echo ew_HtmlEncode($socios->cod_socio->CurrentValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->cod_socio->ViewAttributes() ?>>
<?php echo $socios->cod_socio->ListViewValue() ?></span>
<input type="hidden" data-field="x_cod_socio" name="x<?php echo $socios_grid->RowIndex ?>_cod_socio" id="x<?php echo $socios_grid->RowIndex ?>_cod_socio" value="<?php echo ew_HtmlEncode($socios->cod_socio->FormValue) ?>">
<input type="hidden" data-field="x_cod_socio" name="o<?php echo $socios_grid->RowIndex ?>_cod_socio" id="o<?php echo $socios_grid->RowIndex ?>_cod_socio" value="<?php echo ew_HtmlEncode($socios->cod_socio->OldValue) ?>">
<?php } ?>
<a id="<?php echo $socios_grid->PageObjName . "_row_" . $socios_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($socios->socio->Visible) { // socio ?>
		<td data-name="socio"<?php echo $socios->socio->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($socios->socio->getSessionValue() <> "") { ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_socio" class="form-group socios_socio">
<span<?php echo $socios->socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->socio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $socios_grid->RowIndex ?>_socio" name="x<?php echo $socios_grid->RowIndex ?>_socio" value="<?php echo ew_HtmlEncode($socios->socio->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_socio" class="form-group socios_socio">
<select data-field="x_socio" id="x<?php echo $socios_grid->RowIndex ?>_socio" name="x<?php echo $socios_grid->RowIndex ?>_socio"<?php echo $socios->socio->EditAttributes() ?>>
<?php
if (is_array($socios->socio->EditValue)) {
	$arwrk = $socios->socio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->socio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios->socio->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `cod_pessoa`, `nome` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pessoas`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $socios->Lookup_Selecting($socios->socio, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `nome` ASC";
?>
<input type="hidden" name="s_x<?php echo $socios_grid->RowIndex ?>_socio" id="s_x<?php echo $socios_grid->RowIndex ?>_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`cod_pessoa` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_socio" name="o<?php echo $socios_grid->RowIndex ?>_socio" id="o<?php echo $socios_grid->RowIndex ?>_socio" value="<?php echo ew_HtmlEncode($socios->socio->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($socios->socio->getSessionValue() <> "") { ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_socio" class="form-group socios_socio">
<span<?php echo $socios->socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->socio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $socios_grid->RowIndex ?>_socio" name="x<?php echo $socios_grid->RowIndex ?>_socio" value="<?php echo ew_HtmlEncode($socios->socio->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_socio" class="form-group socios_socio">
<select data-field="x_socio" id="x<?php echo $socios_grid->RowIndex ?>_socio" name="x<?php echo $socios_grid->RowIndex ?>_socio"<?php echo $socios->socio->EditAttributes() ?>>
<?php
if (is_array($socios->socio->EditValue)) {
	$arwrk = $socios->socio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->socio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios->socio->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `cod_pessoa`, `nome` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pessoas`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $socios->Lookup_Selecting($socios->socio, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `nome` ASC";
?>
<input type="hidden" name="s_x<?php echo $socios_grid->RowIndex ?>_socio" id="s_x<?php echo $socios_grid->RowIndex ?>_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`cod_pessoa` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->socio->ViewAttributes() ?>>
<?php echo $socios->socio->ListViewValue() ?></span>
<input type="hidden" data-field="x_socio" name="x<?php echo $socios_grid->RowIndex ?>_socio" id="x<?php echo $socios_grid->RowIndex ?>_socio" value="<?php echo ew_HtmlEncode($socios->socio->FormValue) ?>">
<input type="hidden" data-field="x_socio" name="o<?php echo $socios_grid->RowIndex ?>_socio" id="o<?php echo $socios_grid->RowIndex ?>_socio" value="<?php echo ew_HtmlEncode($socios->socio->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->cod_empresa->Visible) { // cod_empresa ?>
		<td data-name="cod_empresa"<?php echo $socios->cod_empresa->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_cod_empresa" class="form-group socios_cod_empresa">
<select data-field="x_cod_empresa" id="x<?php echo $socios_grid->RowIndex ?>_cod_empresa" name="x<?php echo $socios_grid->RowIndex ?>_cod_empresa"<?php echo $socios->cod_empresa->EditAttributes() ?>>
<?php
if (is_array($socios->cod_empresa->EditValue)) {
	$arwrk = $socios->cod_empresa->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->cod_empresa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios->cod_empresa->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $socios->Lookup_Selecting($socios->cod_empresa, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `nome_empresa` ASC";
?>
<input type="hidden" name="s_x<?php echo $socios_grid->RowIndex ?>_cod_empresa" id="s_x<?php echo $socios_grid->RowIndex ?>_cod_empresa" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`cod_empresa` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_cod_empresa" name="o<?php echo $socios_grid->RowIndex ?>_cod_empresa" id="o<?php echo $socios_grid->RowIndex ?>_cod_empresa" value="<?php echo ew_HtmlEncode($socios->cod_empresa->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_cod_empresa" class="form-group socios_cod_empresa">
<select data-field="x_cod_empresa" id="x<?php echo $socios_grid->RowIndex ?>_cod_empresa" name="x<?php echo $socios_grid->RowIndex ?>_cod_empresa"<?php echo $socios->cod_empresa->EditAttributes() ?>>
<?php
if (is_array($socios->cod_empresa->EditValue)) {
	$arwrk = $socios->cod_empresa->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->cod_empresa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios->cod_empresa->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $socios->Lookup_Selecting($socios->cod_empresa, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `nome_empresa` ASC";
?>
<input type="hidden" name="s_x<?php echo $socios_grid->RowIndex ?>_cod_empresa" id="s_x<?php echo $socios_grid->RowIndex ?>_cod_empresa" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`cod_empresa` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->cod_empresa->ViewAttributes() ?>>
<?php echo $socios->cod_empresa->ListViewValue() ?></span>
<input type="hidden" data-field="x_cod_empresa" name="x<?php echo $socios_grid->RowIndex ?>_cod_empresa" id="x<?php echo $socios_grid->RowIndex ?>_cod_empresa" value="<?php echo ew_HtmlEncode($socios->cod_empresa->FormValue) ?>">
<input type="hidden" data-field="x_cod_empresa" name="o<?php echo $socios_grid->RowIndex ?>_cod_empresa" id="o<?php echo $socios_grid->RowIndex ?>_cod_empresa" value="<?php echo ew_HtmlEncode($socios->cod_empresa->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->dt_cadastro->Visible) { // dt_cadastro ?>
		<td data-name="dt_cadastro"<?php echo $socios->dt_cadastro->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_dt_cadastro" class="form-group socios_dt_cadastro">
<input type="text" data-field="x_dt_cadastro" name="x<?php echo $socios_grid->RowIndex ?>_dt_cadastro" id="x<?php echo $socios_grid->RowIndex ?>_dt_cadastro" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->dt_cadastro->PlaceHolder) ?>" value="<?php echo $socios->dt_cadastro->EditValue ?>"<?php echo $socios->dt_cadastro->EditAttributes() ?>>
<?php if (!$socios->dt_cadastro->ReadOnly && !$socios->dt_cadastro->Disabled && !isset($socios->dt_cadastro->EditAttrs["readonly"]) && !isset($socios->dt_cadastro->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsociosgrid", "x<?php echo $socios_grid->RowIndex ?>_dt_cadastro", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_dt_cadastro" name="o<?php echo $socios_grid->RowIndex ?>_dt_cadastro" id="o<?php echo $socios_grid->RowIndex ?>_dt_cadastro" value="<?php echo ew_HtmlEncode($socios->dt_cadastro->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_dt_cadastro" class="form-group socios_dt_cadastro">
<input type="text" data-field="x_dt_cadastro" name="x<?php echo $socios_grid->RowIndex ?>_dt_cadastro" id="x<?php echo $socios_grid->RowIndex ?>_dt_cadastro" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->dt_cadastro->PlaceHolder) ?>" value="<?php echo $socios->dt_cadastro->EditValue ?>"<?php echo $socios->dt_cadastro->EditAttributes() ?>>
<?php if (!$socios->dt_cadastro->ReadOnly && !$socios->dt_cadastro->Disabled && !isset($socios->dt_cadastro->EditAttrs["readonly"]) && !isset($socios->dt_cadastro->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsociosgrid", "x<?php echo $socios_grid->RowIndex ?>_dt_cadastro", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->dt_cadastro->ViewAttributes() ?>>
<?php echo $socios->dt_cadastro->ListViewValue() ?></span>
<input type="hidden" data-field="x_dt_cadastro" name="x<?php echo $socios_grid->RowIndex ?>_dt_cadastro" id="x<?php echo $socios_grid->RowIndex ?>_dt_cadastro" value="<?php echo ew_HtmlEncode($socios->dt_cadastro->FormValue) ?>">
<input type="hidden" data-field="x_dt_cadastro" name="o<?php echo $socios_grid->RowIndex ?>_dt_cadastro" id="o<?php echo $socios_grid->RowIndex ?>_dt_cadastro" value="<?php echo ew_HtmlEncode($socios->dt_cadastro->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->validade->Visible) { // validade ?>
		<td data-name="validade"<?php echo $socios->validade->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_validade" class="form-group socios_validade">
<input type="text" data-field="x_validade" name="x<?php echo $socios_grid->RowIndex ?>_validade" id="x<?php echo $socios_grid->RowIndex ?>_validade" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->validade->PlaceHolder) ?>" value="<?php echo $socios->validade->EditValue ?>"<?php echo $socios->validade->EditAttributes() ?>>
<?php if (!$socios->validade->ReadOnly && !$socios->validade->Disabled && !isset($socios->validade->EditAttrs["readonly"]) && !isset($socios->validade->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsociosgrid", "x<?php echo $socios_grid->RowIndex ?>_validade", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_validade" name="o<?php echo $socios_grid->RowIndex ?>_validade" id="o<?php echo $socios_grid->RowIndex ?>_validade" value="<?php echo ew_HtmlEncode($socios->validade->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_validade" class="form-group socios_validade">
<input type="text" data-field="x_validade" name="x<?php echo $socios_grid->RowIndex ?>_validade" id="x<?php echo $socios_grid->RowIndex ?>_validade" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->validade->PlaceHolder) ?>" value="<?php echo $socios->validade->EditValue ?>"<?php echo $socios->validade->EditAttributes() ?>>
<?php if (!$socios->validade->ReadOnly && !$socios->validade->Disabled && !isset($socios->validade->EditAttrs["readonly"]) && !isset($socios->validade->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsociosgrid", "x<?php echo $socios_grid->RowIndex ?>_validade", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->validade->ViewAttributes() ?>>
<?php echo $socios->validade->ListViewValue() ?></span>
<input type="hidden" data-field="x_validade" name="x<?php echo $socios_grid->RowIndex ?>_validade" id="x<?php echo $socios_grid->RowIndex ?>_validade" value="<?php echo ew_HtmlEncode($socios->validade->FormValue) ?>">
<input type="hidden" data-field="x_validade" name="o<?php echo $socios_grid->RowIndex ?>_validade" id="o<?php echo $socios_grid->RowIndex ?>_validade" value="<?php echo ew_HtmlEncode($socios->validade->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->ativo->Visible) { // ativo ?>
		<td data-name="ativo"<?php echo $socios->ativo->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_ativo" class="form-group socios_ativo">
<div id="tp_x<?php echo $socios_grid->RowIndex ?>_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $socios_grid->RowIndex ?>_ativo" id="x<?php echo $socios_grid->RowIndex ?>_ativo" value="{value}"<?php echo $socios->ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $socios_grid->RowIndex ?>_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $socios->ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_ativo" name="x<?php echo $socios_grid->RowIndex ?>_ativo" id="x<?php echo $socios_grid->RowIndex ?>_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $socios->ativo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ativo" name="o<?php echo $socios_grid->RowIndex ?>_ativo" id="o<?php echo $socios_grid->RowIndex ?>_ativo" value="<?php echo ew_HtmlEncode($socios->ativo->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_ativo" class="form-group socios_ativo">
<div id="tp_x<?php echo $socios_grid->RowIndex ?>_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $socios_grid->RowIndex ?>_ativo" id="x<?php echo $socios_grid->RowIndex ?>_ativo" value="{value}"<?php echo $socios->ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $socios_grid->RowIndex ?>_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $socios->ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_ativo" name="x<?php echo $socios_grid->RowIndex ?>_ativo" id="x<?php echo $socios_grid->RowIndex ?>_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $socios->ativo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->ativo->ViewAttributes() ?>>
<?php echo $socios->ativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ativo" name="x<?php echo $socios_grid->RowIndex ?>_ativo" id="x<?php echo $socios_grid->RowIndex ?>_ativo" value="<?php echo ew_HtmlEncode($socios->ativo->FormValue) ?>">
<input type="hidden" data-field="x_ativo" name="o<?php echo $socios_grid->RowIndex ?>_ativo" id="o<?php echo $socios_grid->RowIndex ?>_ativo" value="<?php echo ew_HtmlEncode($socios->ativo->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$socios_grid->ListOptions->Render("body", "right", $socios_grid->RowCnt);
?>
	</tr>
<?php if ($socios->RowType == EW_ROWTYPE_ADD || $socios->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fsociosgrid.UpdateOpts(<?php echo $socios_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($socios->CurrentAction <> "gridadd" || $socios->CurrentMode == "copy")
		if (!$socios_grid->Recordset->EOF) $socios_grid->Recordset->MoveNext();
}
?>
<?php
	if ($socios->CurrentMode == "add" || $socios->CurrentMode == "copy" || $socios->CurrentMode == "edit") {
		$socios_grid->RowIndex = '$rowindex$';
		$socios_grid->LoadDefaultValues();

		// Set row properties
		$socios->ResetAttrs();
		$socios->RowAttrs = array_merge($socios->RowAttrs, array('data-rowindex'=>$socios_grid->RowIndex, 'id'=>'r0_socios', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($socios->RowAttrs["class"], "ewTemplate");
		$socios->RowType = EW_ROWTYPE_ADD;

		// Render row
		$socios_grid->RenderRow();

		// Render list options
		$socios_grid->RenderListOptions();
		$socios_grid->StartRowCnt = 0;
?>
	<tr<?php echo $socios->RowAttributes() ?>>
<?php

// Render list options (body, left)
$socios_grid->ListOptions->Render("body", "left", $socios_grid->RowIndex);
?>
	<?php if ($socios->cod_socio->Visible) { // cod_socio ?>
		<td data-name="cod_socio">
<?php if ($socios->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_socios_cod_socio" class="form-group socios_cod_socio">
<span<?php echo $socios->cod_socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->cod_socio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_cod_socio" name="x<?php echo $socios_grid->RowIndex ?>_cod_socio" id="x<?php echo $socios_grid->RowIndex ?>_cod_socio" value="<?php echo ew_HtmlEncode($socios->cod_socio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_cod_socio" name="o<?php echo $socios_grid->RowIndex ?>_cod_socio" id="o<?php echo $socios_grid->RowIndex ?>_cod_socio" value="<?php echo ew_HtmlEncode($socios->cod_socio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->socio->Visible) { // socio ?>
		<td data-name="socio">
<?php if ($socios->CurrentAction <> "F") { ?>
<?php if ($socios->socio->getSessionValue() <> "") { ?>
<span id="el$rowindex$_socios_socio" class="form-group socios_socio">
<span<?php echo $socios->socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->socio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $socios_grid->RowIndex ?>_socio" name="x<?php echo $socios_grid->RowIndex ?>_socio" value="<?php echo ew_HtmlEncode($socios->socio->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_socios_socio" class="form-group socios_socio">
<select data-field="x_socio" id="x<?php echo $socios_grid->RowIndex ?>_socio" name="x<?php echo $socios_grid->RowIndex ?>_socio"<?php echo $socios->socio->EditAttributes() ?>>
<?php
if (is_array($socios->socio->EditValue)) {
	$arwrk = $socios->socio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->socio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios->socio->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `cod_pessoa`, `nome` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pessoas`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $socios->Lookup_Selecting($socios->socio, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `nome` ASC";
?>
<input type="hidden" name="s_x<?php echo $socios_grid->RowIndex ?>_socio" id="s_x<?php echo $socios_grid->RowIndex ?>_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`cod_pessoa` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_socios_socio" class="form-group socios_socio">
<span<?php echo $socios->socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->socio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_socio" name="x<?php echo $socios_grid->RowIndex ?>_socio" id="x<?php echo $socios_grid->RowIndex ?>_socio" value="<?php echo ew_HtmlEncode($socios->socio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_socio" name="o<?php echo $socios_grid->RowIndex ?>_socio" id="o<?php echo $socios_grid->RowIndex ?>_socio" value="<?php echo ew_HtmlEncode($socios->socio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->cod_empresa->Visible) { // cod_empresa ?>
		<td data-name="cod_empresa">
<?php if ($socios->CurrentAction <> "F") { ?>
<span id="el$rowindex$_socios_cod_empresa" class="form-group socios_cod_empresa">
<select data-field="x_cod_empresa" id="x<?php echo $socios_grid->RowIndex ?>_cod_empresa" name="x<?php echo $socios_grid->RowIndex ?>_cod_empresa"<?php echo $socios->cod_empresa->EditAttributes() ?>>
<?php
if (is_array($socios->cod_empresa->EditValue)) {
	$arwrk = $socios->cod_empresa->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->cod_empresa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios->cod_empresa->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `cod_empresa`, `nome_empresa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $socios->Lookup_Selecting($socios->cod_empresa, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `nome_empresa` ASC";
?>
<input type="hidden" name="s_x<?php echo $socios_grid->RowIndex ?>_cod_empresa" id="s_x<?php echo $socios_grid->RowIndex ?>_cod_empresa" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`cod_empresa` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } else { ?>
<span id="el$rowindex$_socios_cod_empresa" class="form-group socios_cod_empresa">
<span<?php echo $socios->cod_empresa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->cod_empresa->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_cod_empresa" name="x<?php echo $socios_grid->RowIndex ?>_cod_empresa" id="x<?php echo $socios_grid->RowIndex ?>_cod_empresa" value="<?php echo ew_HtmlEncode($socios->cod_empresa->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_cod_empresa" name="o<?php echo $socios_grid->RowIndex ?>_cod_empresa" id="o<?php echo $socios_grid->RowIndex ?>_cod_empresa" value="<?php echo ew_HtmlEncode($socios->cod_empresa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->dt_cadastro->Visible) { // dt_cadastro ?>
		<td data-name="dt_cadastro">
<?php if ($socios->CurrentAction <> "F") { ?>
<span id="el$rowindex$_socios_dt_cadastro" class="form-group socios_dt_cadastro">
<input type="text" data-field="x_dt_cadastro" name="x<?php echo $socios_grid->RowIndex ?>_dt_cadastro" id="x<?php echo $socios_grid->RowIndex ?>_dt_cadastro" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->dt_cadastro->PlaceHolder) ?>" value="<?php echo $socios->dt_cadastro->EditValue ?>"<?php echo $socios->dt_cadastro->EditAttributes() ?>>
<?php if (!$socios->dt_cadastro->ReadOnly && !$socios->dt_cadastro->Disabled && !isset($socios->dt_cadastro->EditAttrs["readonly"]) && !isset($socios->dt_cadastro->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsociosgrid", "x<?php echo $socios_grid->RowIndex ?>_dt_cadastro", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_socios_dt_cadastro" class="form-group socios_dt_cadastro">
<span<?php echo $socios->dt_cadastro->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->dt_cadastro->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_dt_cadastro" name="x<?php echo $socios_grid->RowIndex ?>_dt_cadastro" id="x<?php echo $socios_grid->RowIndex ?>_dt_cadastro" value="<?php echo ew_HtmlEncode($socios->dt_cadastro->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_dt_cadastro" name="o<?php echo $socios_grid->RowIndex ?>_dt_cadastro" id="o<?php echo $socios_grid->RowIndex ?>_dt_cadastro" value="<?php echo ew_HtmlEncode($socios->dt_cadastro->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->validade->Visible) { // validade ?>
		<td data-name="validade">
<?php if ($socios->CurrentAction <> "F") { ?>
<span id="el$rowindex$_socios_validade" class="form-group socios_validade">
<input type="text" data-field="x_validade" name="x<?php echo $socios_grid->RowIndex ?>_validade" id="x<?php echo $socios_grid->RowIndex ?>_validade" size="12" maxlength="10" placeholder="<?php echo ew_HtmlEncode($socios->validade->PlaceHolder) ?>" value="<?php echo $socios->validade->EditValue ?>"<?php echo $socios->validade->EditAttributes() ?>>
<?php if (!$socios->validade->ReadOnly && !$socios->validade->Disabled && !isset($socios->validade->EditAttrs["readonly"]) && !isset($socios->validade->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsociosgrid", "x<?php echo $socios_grid->RowIndex ?>_validade", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_socios_validade" class="form-group socios_validade">
<span<?php echo $socios->validade->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->validade->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_validade" name="x<?php echo $socios_grid->RowIndex ?>_validade" id="x<?php echo $socios_grid->RowIndex ?>_validade" value="<?php echo ew_HtmlEncode($socios->validade->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_validade" name="o<?php echo $socios_grid->RowIndex ?>_validade" id="o<?php echo $socios_grid->RowIndex ?>_validade" value="<?php echo ew_HtmlEncode($socios->validade->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->ativo->Visible) { // ativo ?>
		<td data-name="ativo">
<?php if ($socios->CurrentAction <> "F") { ?>
<span id="el$rowindex$_socios_ativo" class="form-group socios_ativo">
<div id="tp_x<?php echo $socios_grid->RowIndex ?>_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $socios_grid->RowIndex ?>_ativo" id="x<?php echo $socios_grid->RowIndex ?>_ativo" value="{value}"<?php echo $socios->ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $socios_grid->RowIndex ?>_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $socios->ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_ativo" name="x<?php echo $socios_grid->RowIndex ?>_ativo" id="x<?php echo $socios_grid->RowIndex ?>_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $socios->ativo->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_socios_ativo" class="form-group socios_ativo">
<span<?php echo $socios->ativo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->ativo->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_ativo" name="x<?php echo $socios_grid->RowIndex ?>_ativo" id="x<?php echo $socios_grid->RowIndex ?>_ativo" value="<?php echo ew_HtmlEncode($socios->ativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ativo" name="o<?php echo $socios_grid->RowIndex ?>_ativo" id="o<?php echo $socios_grid->RowIndex ?>_ativo" value="<?php echo ew_HtmlEncode($socios->ativo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$socios_grid->ListOptions->Render("body", "right", $socios_grid->RowCnt);
?>
<script type="text/javascript">
fsociosgrid.UpdateOpts(<?php echo $socios_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($socios->CurrentMode == "add" || $socios->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $socios_grid->FormKeyCountName ?>" id="<?php echo $socios_grid->FormKeyCountName ?>" value="<?php echo $socios_grid->KeyCount ?>">
<?php echo $socios_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($socios->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $socios_grid->FormKeyCountName ?>" id="<?php echo $socios_grid->FormKeyCountName ?>" value="<?php echo $socios_grid->KeyCount ?>">
<?php echo $socios_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($socios->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fsociosgrid">
</div>
<?php

// Close recordset
if ($socios_grid->Recordset)
	$socios_grid->Recordset->Close();
?>
<?php if ($socios_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($socios_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($socios_grid->TotalRecs == 0 && $socios->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($socios_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($socios->Export == "") { ?>
<script type="text/javascript">
fsociosgrid.Init();
</script>
<?php } ?>
<?php
$socios_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$socios_grid->Page_Terminate();
?>
