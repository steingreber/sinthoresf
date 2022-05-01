<?php

// regiao
?>
<?php if ($regioes->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $regioes->TableCaption() ?></h4> -->
<table id="tbl_regioesmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($regioes->regiao->Visible) { // regiao ?>
		<tr id="r_regiao">
			<td><?php echo $regioes->regiao->FldCaption() ?></td>
			<td<?php echo $regioes->regiao->CellAttributes() ?>>
<span id="el_regioes_regiao">
<span<?php echo $regioes->regiao->ViewAttributes() ?>>
<?php echo $regioes->regiao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
