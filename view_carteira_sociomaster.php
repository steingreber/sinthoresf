<?php

// cod_socio
// nome
// nome_empresa
// validade

?>
<?php if ($view_carteira_socio->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $view_carteira_socio->TableCaption() ?></h4> -->
<table id="tbl_view_carteira_sociomaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($view_carteira_socio->cod_socio->Visible) { // cod_socio ?>
		<tr id="r_cod_socio">
			<td><?php echo $view_carteira_socio->cod_socio->FldCaption() ?></td>
			<td<?php echo $view_carteira_socio->cod_socio->CellAttributes() ?>>
<span id="el_view_carteira_socio_cod_socio">
<span<?php echo $view_carteira_socio->cod_socio->ViewAttributes() ?>>
<?php echo $view_carteira_socio->cod_socio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($view_carteira_socio->nome->Visible) { // nome ?>
		<tr id="r_nome">
			<td><?php echo $view_carteira_socio->nome->FldCaption() ?></td>
			<td<?php echo $view_carteira_socio->nome->CellAttributes() ?>>
<span id="el_view_carteira_socio_nome">
<span<?php echo $view_carteira_socio->nome->ViewAttributes() ?>>
<?php echo $view_carteira_socio->nome->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($view_carteira_socio->nome_empresa->Visible) { // nome_empresa ?>
		<tr id="r_nome_empresa">
			<td><?php echo $view_carteira_socio->nome_empresa->FldCaption() ?></td>
			<td<?php echo $view_carteira_socio->nome_empresa->CellAttributes() ?>>
<span id="el_view_carteira_socio_nome_empresa">
<span<?php echo $view_carteira_socio->nome_empresa->ViewAttributes() ?>>
<?php echo $view_carteira_socio->nome_empresa->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($view_carteira_socio->validade->Visible) { // validade ?>
		<tr id="r_validade">
			<td><?php echo $view_carteira_socio->validade->FldCaption() ?></td>
			<td<?php echo $view_carteira_socio->validade->CellAttributes() ?>>
<span id="el_view_carteira_socio_validade">
<span<?php echo $view_carteira_socio->validade->ViewAttributes() ?>>
<?php echo $view_carteira_socio->validade->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
