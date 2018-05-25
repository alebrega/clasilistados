<div class="bchead">
<span id="ef">
[ <?=$h->getAyudaLink()?>]
[ <?=$h->getIngresarMiCuentaLink(true)?> ]
<?php
if (empty($_GET['subcat'])){
	echo '[ '.$h->getPostingCategoriaLink($categoria_id,$categoria_nombre,"publica tu anuncio").' ]';
}elseif($haySuscripcionCategoria){
	echo '[ <a href="'.$h->getPublicacionLink($categoria_id).'?subcategoriaCheck='.$subcategoria_id.'" rel="nofollow">publica tu anuncio</a>'.' ]';
}else{
	echo '[ '.$h->getPostingSubCategoriaLink($categoria_id,$subcategoria_id,"publica tu anuncio").' ]';
}
?>
</span>

<?=$h->getHomeLinkLocation()?> <?=($urgente)?' &gt; <span class="urgentetit">urgente!</span>':'';?> &gt; <?=$h->getCategoriaLink($categoria_id)?>
	<?php
	if (!empty($_GET['subcat'])){
		echo ' &gt; '.$h->getLinkSubcat(array ("nombre"=>$categoria->getSubCategoriaNombre(), "id"=>$categoria->getSubCategoriaId(), "catid"=>$categoria->getCategoriaId()));
	}
	?>
</div>