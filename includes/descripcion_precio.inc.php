
<table width="50%" border="0">
        <tbody><tr bgcolor="#eeeeee">
                <th align="left" colspan="2"><b style="font-size: 16px;">Descripción</b></th>
                <th align="right"><b style="font-size: 16px;">Precio</b></th>
        </tr>
        <tr>
                <td colspan="2"><?=$titulo?></td>
                <td> </td>
        </tr>

<?php
$descuento=$publicacion->getDescuentoCodigoPromocion($_POST);
$total=0;
foreach ($items as $item){
	if (!empty($item['precio'])){
		$precio_unidad=$item['precio'];
		?>
		<tr>
		<td> </td><td><i><b style="color: #555555;"><?=$item['nombre']?></b></i></td>
		<td align="right"><?=formatMoney($precio_unidad,2)?></td>
		</tr>
	<?php 
	}
	if ($_POST['destacado_1']==ANUNCIO_DESTACADO){
		?>
		<tr>
	<td> </td><td><i><?=$h->getMensajeDestacado()?></i></td>
	<td align="right"><?=formatMoney(PRECIO_DESTACADO,2)?></td>
	</tr>
		<?php 
		$total=$total+PRECIO_DESTACADO;
	}
	if ($_POST['destacado_2']==ANUNCIO_URGENTE){
	?>
	<tr>
	<td> </td><td><i><?='<span style="color: red;">urgente! </span>'?></i></td>
	<td align="right"><?=formatMoney(PRECIO_URGENTE,2)?></td>
	</tr>
	<?php
		$total=$total+PRECIO_URGENTE;
	}
	$total=$total+$precio_unidad;
}

if (!empty($descuento) && (intval($descuento)!=0)){
	?>
	<tr>
	<td> </td><td><i><?='descuento (código: '.$_POST['codigo_prom'].')'?></i></td>
	<td align="right"><span style="color: red;"><?='-'.$descuento.'%'?></span></td>
	</tr>
	<?php
	if (!empty($_POST['chargetotal'])){
			$total=$_POST['chargetotal'];
	}
}
?>
<tr>
        <tr>
                <td nowrap="" align="right" colspan="2"> <b><?=($pagado)?'Total pagado:':'Total a pagar:';?></b>   </td>
                <td align="right"><b><?=formatMoney($total,2)?></b></td>
        </tr>
</tbody></table>