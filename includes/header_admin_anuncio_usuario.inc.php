
<?php
if (!empty($mensaje)){
	?>
	<table width="70%"  cellpadding="7" style="background: <?=$color?> none repeat scroll 0% 0%;"><tbody><tr><td><?=$mensaje?></td></tr></tbody></table>
	<?php
}
?>
<table width="70%" cellpadding="7" style="background: <?=$color?> none repeat scroll 0% 0%;"><tbody>
<tr>
	<td colspan="2"><b>Enviado a <i><?=$categoria->getSubCategoriaNombre()?></i> en clasilistados 
	<a href="<?=$h->getHomeCiudadLink()?>"><?=$locacion->getCiudad()?></a></b></td>
</tr>

<?php
if ($anuncio->estaHabilitado()){
?>				
<tr><td><form action="<?=$h->getAdminAnuncioLinkUsuario($adid,$catid)?>" method="post"><input type="hidden" value="eliminar" name="accion"/><input type="submit" value="Elimina este anuncio"/></form></td><td>esto eliminara el anuncio del listado.</td></tr>
<tr><td><form action="<?=$h->getPostingFormAction($categoria->getCategoriaId(),$categoria->getSubCategoriaId())?>" method="post"><input type="hidden" value="editar" name="accion"/><input type="hidden" value="<?=$adid?>" name="id"/><input type="hidden" value="<?=$catid ?>" name="cat"/><input type="submit" value="Edita este anuncio"/></form></td><td>los cambios que realices al anuncio deben cumplir los <?=$h->getTermCondicionesLink()?>.</td></tr>
<?php
}else{
	?>
	<tr><td><form action="<?=$h->getPostingFormAction($categoria->getCategoriaId(),$categoria->getSubCategoriaId())?>"><input type="hidden" value="reenviar" name="accion"/><input type="hidden" value="<?=$adid?>" name="id"/><input type="hidden" value="<?=$catid ?>" name="cat"/><input type="submit" value="Reenvia este anuncio"/></form></td><td>Esto creará una nueva copia del anuncio en el sitio. Podrás cambiar lo que necesites antes de publicarlo.</td></tr>
	<?php
}
 ?>
</tbody></table>
<p><a href="<?=$h->getHost()?>">volver a clasilistados</a> | <?=$h->getVolverMiCuentaLink()?></p>