<?php

// cod_empresa
// nome_empresa
// telefone
// cidade

?>
<?php if ($empresas->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $empresas->TableCaption() ?></h4> -->
<table id="tbl_empresasmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($empresas->cod_empresa->Visible) { // cod_empresa ?>
		<tr id="r_cod_empresa">
			<td><?php echo $empresas->cod_empresa->FldCaption() ?></td>
			<td<?php echo $empresas->cod_empresa->CellAttributes() ?>>
<span id="el_empresas_cod_empresa">
<span<?php echo $empresas->cod_empresa->ViewAttributes() ?>>
<?php echo $empresas->cod_empresa->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($empresas->nome_empresa->Visible) { // nome_empresa ?>
		<tr id="r_nome_empresa">
			<td><?php echo $empresas->nome_empresa->FldCaption() ?></td>
			<td<?php echo $empresas->nome_empresa->CellAttributes() ?>>
<span id="el_empresas_nome_empresa">
<span<?php echo $empresas->nome_empresa->ViewAttributes() ?>>
<?php echo $empresas->nome_empresa->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($empresas->telefone->Visible) { // telefone ?>
		<tr id="r_telefone">
			<td><?php echo $empresas->telefone->FldCaption() ?></td>
			<td<?php echo $empresas->telefone->CellAttributes() ?>>
<span id="el_empresas_telefone">
<span<?php echo $empresas->telefone->ViewAttributes() ?>>
<?php echo $empresas->telefone->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($empresas->cidade->Visible) { // cidade ?>
		<tr id="r_cidade">
			<td><?php echo $empresas->cidade->FldCaption() ?></td>
			<td<?php echo $empresas->cidade->CellAttributes() ?>>
<span id="el_empresas_cidade">
<span<?php echo $empresas->cidade->ViewAttributes() ?>>
<?php echo $empresas->cidade->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
