<?php

// cod_func
// nome
// ativo
// dtcad
// telefone

?>
<?php if ($funcionarios->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $funcionarios->TableCaption() ?></h4> -->
<table id="tbl_funcionariosmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($funcionarios->cod_func->Visible) { // cod_func ?>
		<tr id="r_cod_func">
			<td><?php echo $funcionarios->cod_func->FldCaption() ?></td>
			<td<?php echo $funcionarios->cod_func->CellAttributes() ?>>
<span id="el_funcionarios_cod_func">
<span<?php echo $funcionarios->cod_func->ViewAttributes() ?>>
<?php echo $funcionarios->cod_func->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($funcionarios->nome->Visible) { // nome ?>
		<tr id="r_nome">
			<td><?php echo $funcionarios->nome->FldCaption() ?></td>
			<td<?php echo $funcionarios->nome->CellAttributes() ?>>
<span id="el_funcionarios_nome">
<span<?php echo $funcionarios->nome->ViewAttributes() ?>>
<?php echo $funcionarios->nome->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($funcionarios->ativo->Visible) { // ativo ?>
		<tr id="r_ativo">
			<td><?php echo $funcionarios->ativo->FldCaption() ?></td>
			<td<?php echo $funcionarios->ativo->CellAttributes() ?>>
<span id="el_funcionarios_ativo">
<span<?php echo $funcionarios->ativo->ViewAttributes() ?>>
<?php echo $funcionarios->ativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($funcionarios->dtcad->Visible) { // dtcad ?>
		<tr id="r_dtcad">
			<td><?php echo $funcionarios->dtcad->FldCaption() ?></td>
			<td<?php echo $funcionarios->dtcad->CellAttributes() ?>>
<span id="el_funcionarios_dtcad">
<span<?php echo $funcionarios->dtcad->ViewAttributes() ?>>
<?php echo $funcionarios->dtcad->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($funcionarios->telefone->Visible) { // telefone ?>
		<tr id="r_telefone">
			<td><?php echo $funcionarios->telefone->FldCaption() ?></td>
			<td<?php echo $funcionarios->telefone->CellAttributes() ?>>
<span id="el_funcionarios_telefone">
<span<?php echo $funcionarios->telefone->ViewAttributes() ?>>
<?php echo $funcionarios->telefone->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
