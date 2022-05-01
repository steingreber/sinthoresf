<?php

// cod_socio
// socio
// cod_empresa
// dt_cadastro
// validade
// ativo

?>
<?php if ($socios->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $socios->TableCaption() ?></h4> -->
<table id="tbl_sociosmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($socios->cod_socio->Visible) { // cod_socio ?>
		<tr id="r_cod_socio">
			<td><?php echo $socios->cod_socio->FldCaption() ?></td>
			<td<?php echo $socios->cod_socio->CellAttributes() ?>>
<span id="el_socios_cod_socio">
<span<?php echo $socios->cod_socio->ViewAttributes() ?>>
<?php echo $socios->cod_socio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($socios->socio->Visible) { // socio ?>
		<tr id="r_socio">
			<td><?php echo $socios->socio->FldCaption() ?></td>
			<td<?php echo $socios->socio->CellAttributes() ?>>
<span id="el_socios_socio">
<span<?php echo $socios->socio->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($socios->socio->ListViewValue())) && $socios->socio->LinkAttributes() <> "") { ?>
<a<?php echo $socios->socio->LinkAttributes() ?>><?php echo $socios->socio->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $socios->socio->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($socios->cod_empresa->Visible) { // cod_empresa ?>
		<tr id="r_cod_empresa">
			<td><?php echo $socios->cod_empresa->FldCaption() ?></td>
			<td<?php echo $socios->cod_empresa->CellAttributes() ?>>
<span id="el_socios_cod_empresa">
<span<?php echo $socios->cod_empresa->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($socios->cod_empresa->ListViewValue())) && $socios->cod_empresa->LinkAttributes() <> "") { ?>
<a<?php echo $socios->cod_empresa->LinkAttributes() ?>><?php echo $socios->cod_empresa->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $socios->cod_empresa->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($socios->dt_cadastro->Visible) { // dt_cadastro ?>
		<tr id="r_dt_cadastro">
			<td><?php echo $socios->dt_cadastro->FldCaption() ?></td>
			<td<?php echo $socios->dt_cadastro->CellAttributes() ?>>
<span id="el_socios_dt_cadastro">
<span<?php echo $socios->dt_cadastro->ViewAttributes() ?>>
<?php echo $socios->dt_cadastro->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($socios->validade->Visible) { // validade ?>
		<tr id="r_validade">
			<td><?php echo $socios->validade->FldCaption() ?></td>
			<td<?php echo $socios->validade->CellAttributes() ?>>
<span id="el_socios_validade">
<span<?php echo $socios->validade->ViewAttributes() ?>>
<?php echo $socios->validade->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($socios->ativo->Visible) { // ativo ?>
		<tr id="r_ativo">
			<td><?php echo $socios->ativo->FldCaption() ?></td>
			<td<?php echo $socios->ativo->CellAttributes() ?>>
<span id="el_socios_ativo">
<span<?php echo $socios->ativo->ViewAttributes() ?>>
<?php echo $socios->ativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
