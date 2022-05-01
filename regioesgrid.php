<?php include_once "permissoesinfo.php" ?>
<?php

// Create page object
if (!isset($regioes_grid)) $regioes_grid = new cregioes_grid();

// Page init
$regioes_grid->Page_Init();

// Page main
$regioes_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$regioes_grid->Page_Render();
?>
<?php if ($regioes->Export == "") { ?>
<script type="text/javascript">

// Page object
var regioes_grid = new ew_Page("regioes_grid");
regioes_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = regioes_grid.PageID; // For backward compatibility

// Form object
var fregioesgrid = new ew_Form("fregioesgrid");
fregioesgrid.FormKeyCountName = '<?php echo $regioes_grid->FormKeyCountName ?>';

// Validate form
fregioesgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_regiao");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $regioes->regiao->FldCaption(), $regioes->regiao->ReqErrMsg)) ?>");

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
fregioesgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "regiao", false)) return false;
	return true;
}

// Form_CustomValidate event
fregioesgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fregioesgrid.ValidateRequired = true;
<?php } else { ?>
fregioesgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($regioes->CurrentAction == "gridadd") {
	if ($regioes->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$regioes_grid->TotalRecs = $regioes->SelectRecordCount();
			$regioes_grid->Recordset = $regioes_grid->LoadRecordset($regioes_grid->StartRec-1, $regioes_grid->DisplayRecs);
		} else {
			if ($regioes_grid->Recordset = $regioes_grid->LoadRecordset())
				$regioes_grid->TotalRecs = $regioes_grid->Recordset->RecordCount();
		}
		$regioes_grid->StartRec = 1;
		$regioes_grid->DisplayRecs = $regioes_grid->TotalRecs;
	} else {
		$regioes->CurrentFilter = "0=1";
		$regioes_grid->StartRec = 1;
		$regioes_grid->DisplayRecs = $regioes->GridAddRowCount;
	}
	$regioes_grid->TotalRecs = $regioes_grid->DisplayRecs;
	$regioes_grid->StopRec = $regioes_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($regioes_grid->TotalRecs <= 0)
			$regioes_grid->TotalRecs = $regioes->SelectRecordCount();
	} else {
		if (!$regioes_grid->Recordset && ($regioes_grid->Recordset = $regioes_grid->LoadRecordset()))
			$regioes_grid->TotalRecs = $regioes_grid->Recordset->RecordCount();
	}
	$regioes_grid->StartRec = 1;
	$regioes_grid->DisplayRecs = $regioes_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$regioes_grid->Recordset = $regioes_grid->LoadRecordset($regioes_grid->StartRec-1, $regioes_grid->DisplayRecs);

	// Set no record found message
	if ($regioes->CurrentAction == "" && $regioes_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$regioes_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($regioes_grid->SearchWhere == "0=101")
			$regioes_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$regioes_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$regioes_grid->RenderOtherOptions();
?>
<?php $regioes_grid->ShowPageHeader(); ?>
<?php
$regioes_grid->ShowMessage();
?>
<?php if ($regioes_grid->TotalRecs > 0 || $regioes->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fregioesgrid" class="ewForm form-inline">
<?php if ($regioes_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($regioes_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_regioes" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_regioesgrid" class="table ewTable">
<?php echo $regioes->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$regioes->RowType = EW_ROWTYPE_HEADER;

// Render list options
$regioes_grid->RenderListOptions();

// Render list options (header, left)
$regioes_grid->ListOptions->Render("header", "left");
?>
<?php if ($regioes->regiao->Visible) { // regiao ?>
	<?php if ($regioes->SortUrl($regioes->regiao) == "") { ?>
		<th data-name="regiao"><div id="elh_regioes_regiao" class="regioes_regiao"><div class="ewTableHeaderCaption"><?php echo $regioes->regiao->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="regiao"><div><div id="elh_regioes_regiao" class="regioes_regiao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $regioes->regiao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($regioes->regiao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($regioes->regiao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$regioes_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$regioes_grid->StartRec = 1;
$regioes_grid->StopRec = $regioes_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($regioes_grid->FormKeyCountName) && ($regioes->CurrentAction == "gridadd" || $regioes->CurrentAction == "gridedit" || $regioes->CurrentAction == "F")) {
		$regioes_grid->KeyCount = $objForm->GetValue($regioes_grid->FormKeyCountName);
		$regioes_grid->StopRec = $regioes_grid->StartRec + $regioes_grid->KeyCount - 1;
	}
}
$regioes_grid->RecCnt = $regioes_grid->StartRec - 1;
if ($regioes_grid->Recordset && !$regioes_grid->Recordset->EOF) {
	$regioes_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $regioes_grid->StartRec > 1)
		$regioes_grid->Recordset->Move($regioes_grid->StartRec - 1);
} elseif (!$regioes->AllowAddDeleteRow && $regioes_grid->StopRec == 0) {
	$regioes_grid->StopRec = $regioes->GridAddRowCount;
}

// Initialize aggregate
$regioes->RowType = EW_ROWTYPE_AGGREGATEINIT;
$regioes->ResetAttrs();
$regioes_grid->RenderRow();
if ($regioes->CurrentAction == "gridadd")
	$regioes_grid->RowIndex = 0;
if ($regioes->CurrentAction == "gridedit")
	$regioes_grid->RowIndex = 0;
while ($regioes_grid->RecCnt < $regioes_grid->StopRec) {
	$regioes_grid->RecCnt++;
	if (intval($regioes_grid->RecCnt) >= intval($regioes_grid->StartRec)) {
		$regioes_grid->RowCnt++;
		if ($regioes->CurrentAction == "gridadd" || $regioes->CurrentAction == "gridedit" || $regioes->CurrentAction == "F") {
			$regioes_grid->RowIndex++;
			$objForm->Index = $regioes_grid->RowIndex;
			if ($objForm->HasValue($regioes_grid->FormActionName))
				$regioes_grid->RowAction = strval($objForm->GetValue($regioes_grid->FormActionName));
			elseif ($regioes->CurrentAction == "gridadd")
				$regioes_grid->RowAction = "insert";
			else
				$regioes_grid->RowAction = "";
		}

		// Set up key count
		$regioes_grid->KeyCount = $regioes_grid->RowIndex;

		// Init row class and style
		$regioes->ResetAttrs();
		$regioes->CssClass = "";
		if ($regioes->CurrentAction == "gridadd") {
			if ($regioes->CurrentMode == "copy") {
				$regioes_grid->LoadRowValues($regioes_grid->Recordset); // Load row values
				$regioes_grid->SetRecordKey($regioes_grid->RowOldKey, $regioes_grid->Recordset); // Set old record key
			} else {
				$regioes_grid->LoadDefaultValues(); // Load default values
				$regioes_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$regioes_grid->LoadRowValues($regioes_grid->Recordset); // Load row values
		}
		$regioes->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($regioes->CurrentAction == "gridadd") // Grid add
			$regioes->RowType = EW_ROWTYPE_ADD; // Render add
		if ($regioes->CurrentAction == "gridadd" && $regioes->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$regioes_grid->RestoreCurrentRowFormValues($regioes_grid->RowIndex); // Restore form values
		if ($regioes->CurrentAction == "gridedit") { // Grid edit
			if ($regioes->EventCancelled) {
				$regioes_grid->RestoreCurrentRowFormValues($regioes_grid->RowIndex); // Restore form values
			}
			if ($regioes_grid->RowAction == "insert")
				$regioes->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$regioes->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($regioes->CurrentAction == "gridedit" && ($regioes->RowType == EW_ROWTYPE_EDIT || $regioes->RowType == EW_ROWTYPE_ADD) && $regioes->EventCancelled) // Update failed
			$regioes_grid->RestoreCurrentRowFormValues($regioes_grid->RowIndex); // Restore form values
		if ($regioes->RowType == EW_ROWTYPE_EDIT) // Edit row
			$regioes_grid->EditRowCnt++;
		if ($regioes->CurrentAction == "F") // Confirm row
			$regioes_grid->RestoreCurrentRowFormValues($regioes_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$regioes->RowAttrs = array_merge($regioes->RowAttrs, array('data-rowindex'=>$regioes_grid->RowCnt, 'id'=>'r' . $regioes_grid->RowCnt . '_regioes', 'data-rowtype'=>$regioes->RowType));

		// Render row
		$regioes_grid->RenderRow();

		// Render list options
		$regioes_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($regioes_grid->RowAction <> "delete" && $regioes_grid->RowAction <> "insertdelete" && !($regioes_grid->RowAction == "insert" && $regioes->CurrentAction == "F" && $regioes_grid->EmptyRow())) {
?>
	<tr<?php echo $regioes->RowAttributes() ?>>
<?php

// Render list options (body, left)
$regioes_grid->ListOptions->Render("body", "left", $regioes_grid->RowCnt);
?>
	<?php if ($regioes->regiao->Visible) { // regiao ?>
		<td data-name="regiao"<?php echo $regioes->regiao->CellAttributes() ?>>
<?php if ($regioes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $regioes_grid->RowCnt ?>_regioes_regiao" class="form-group regioes_regiao">
<input type="text" data-field="x_regiao" name="x<?php echo $regioes_grid->RowIndex ?>_regiao" id="x<?php echo $regioes_grid->RowIndex ?>_regiao" size="40" maxlength="40" placeholder="<?php echo ew_HtmlEncode($regioes->regiao->PlaceHolder) ?>" value="<?php echo $regioes->regiao->EditValue ?>"<?php echo $regioes->regiao->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_regiao" name="o<?php echo $regioes_grid->RowIndex ?>_regiao" id="o<?php echo $regioes_grid->RowIndex ?>_regiao" value="<?php echo ew_HtmlEncode($regioes->regiao->OldValue) ?>">
<?php } ?>
<?php if ($regioes->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $regioes_grid->RowCnt ?>_regioes_regiao" class="form-group regioes_regiao">
<input type="text" data-field="x_regiao" name="x<?php echo $regioes_grid->RowIndex ?>_regiao" id="x<?php echo $regioes_grid->RowIndex ?>_regiao" size="40" maxlength="40" placeholder="<?php echo ew_HtmlEncode($regioes->regiao->PlaceHolder) ?>" value="<?php echo $regioes->regiao->EditValue ?>"<?php echo $regioes->regiao->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($regioes->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $regioes->regiao->ViewAttributes() ?>>
<?php echo $regioes->regiao->ListViewValue() ?></span>
<input type="hidden" data-field="x_regiao" name="x<?php echo $regioes_grid->RowIndex ?>_regiao" id="x<?php echo $regioes_grid->RowIndex ?>_regiao" value="<?php echo ew_HtmlEncode($regioes->regiao->FormValue) ?>">
<input type="hidden" data-field="x_regiao" name="o<?php echo $regioes_grid->RowIndex ?>_regiao" id="o<?php echo $regioes_grid->RowIndex ?>_regiao" value="<?php echo ew_HtmlEncode($regioes->regiao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $regioes_grid->PageObjName . "_row_" . $regioes_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($regioes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id_regiao" name="x<?php echo $regioes_grid->RowIndex ?>_id_regiao" id="x<?php echo $regioes_grid->RowIndex ?>_id_regiao" value="<?php echo ew_HtmlEncode($regioes->id_regiao->CurrentValue) ?>">
<input type="hidden" data-field="x_id_regiao" name="o<?php echo $regioes_grid->RowIndex ?>_id_regiao" id="o<?php echo $regioes_grid->RowIndex ?>_id_regiao" value="<?php echo ew_HtmlEncode($regioes->id_regiao->OldValue) ?>">
<?php } ?>
<?php if ($regioes->RowType == EW_ROWTYPE_EDIT || $regioes->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_id_regiao" name="x<?php echo $regioes_grid->RowIndex ?>_id_regiao" id="x<?php echo $regioes_grid->RowIndex ?>_id_regiao" value="<?php echo ew_HtmlEncode($regioes->id_regiao->CurrentValue) ?>">
<?php } ?>
<?php

// Render list options (body, right)
$regioes_grid->ListOptions->Render("body", "right", $regioes_grid->RowCnt);
?>
	</tr>
<?php if ($regioes->RowType == EW_ROWTYPE_ADD || $regioes->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fregioesgrid.UpdateOpts(<?php echo $regioes_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($regioes->CurrentAction <> "gridadd" || $regioes->CurrentMode == "copy")
		if (!$regioes_grid->Recordset->EOF) $regioes_grid->Recordset->MoveNext();
}
?>
<?php
	if ($regioes->CurrentMode == "add" || $regioes->CurrentMode == "copy" || $regioes->CurrentMode == "edit") {
		$regioes_grid->RowIndex = '$rowindex$';
		$regioes_grid->LoadDefaultValues();

		// Set row properties
		$regioes->ResetAttrs();
		$regioes->RowAttrs = array_merge($regioes->RowAttrs, array('data-rowindex'=>$regioes_grid->RowIndex, 'id'=>'r0_regioes', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($regioes->RowAttrs["class"], "ewTemplate");
		$regioes->RowType = EW_ROWTYPE_ADD;

		// Render row
		$regioes_grid->RenderRow();

		// Render list options
		$regioes_grid->RenderListOptions();
		$regioes_grid->StartRowCnt = 0;
?>
	<tr<?php echo $regioes->RowAttributes() ?>>
<?php

// Render list options (body, left)
$regioes_grid->ListOptions->Render("body", "left", $regioes_grid->RowIndex);
?>
	<?php if ($regioes->regiao->Visible) { // regiao ?>
		<td data-name="regiao">
<?php if ($regioes->CurrentAction <> "F") { ?>
<span id="el$rowindex$_regioes_regiao" class="form-group regioes_regiao">
<input type="text" data-field="x_regiao" name="x<?php echo $regioes_grid->RowIndex ?>_regiao" id="x<?php echo $regioes_grid->RowIndex ?>_regiao" size="40" maxlength="40" placeholder="<?php echo ew_HtmlEncode($regioes->regiao->PlaceHolder) ?>" value="<?php echo $regioes->regiao->EditValue ?>"<?php echo $regioes->regiao->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_regioes_regiao" class="form-group regioes_regiao">
<span<?php echo $regioes->regiao->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $regioes->regiao->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_regiao" name="x<?php echo $regioes_grid->RowIndex ?>_regiao" id="x<?php echo $regioes_grid->RowIndex ?>_regiao" value="<?php echo ew_HtmlEncode($regioes->regiao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_regiao" name="o<?php echo $regioes_grid->RowIndex ?>_regiao" id="o<?php echo $regioes_grid->RowIndex ?>_regiao" value="<?php echo ew_HtmlEncode($regioes->regiao->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$regioes_grid->ListOptions->Render("body", "right", $regioes_grid->RowCnt);
?>
<script type="text/javascript">
fregioesgrid.UpdateOpts(<?php echo $regioes_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($regioes->CurrentMode == "add" || $regioes->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $regioes_grid->FormKeyCountName ?>" id="<?php echo $regioes_grid->FormKeyCountName ?>" value="<?php echo $regioes_grid->KeyCount ?>">
<?php echo $regioes_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($regioes->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $regioes_grid->FormKeyCountName ?>" id="<?php echo $regioes_grid->FormKeyCountName ?>" value="<?php echo $regioes_grid->KeyCount ?>">
<?php echo $regioes_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($regioes->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fregioesgrid">
</div>
<?php

// Close recordset
if ($regioes_grid->Recordset)
	$regioes_grid->Recordset->Close();
?>
<?php if ($regioes_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($regioes_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($regioes_grid->TotalRecs == 0 && $regioes->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($regioes_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($regioes->Export == "") { ?>
<script type="text/javascript">
fregioesgrid.Init();
</script>
<?php } ?>
<?php
$regioes_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$regioes_grid->Page_Terminate();
?>
