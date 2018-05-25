<div class="bchead">
<?php
if (!$anunciosRelacionados){
?>
<span id="ef">
<a href="<?=$h->getEnvioAmigoLink($_GET['id'],$categoria->getCategoriaId())?>" rel="nofollow">enviar anuncio a un/a amigo/a</a>
</span>
<?php
}
?>
<?=$h->getHomeLinkLocation()?> &gt; <?=$h->getCategoriaLink($categoria_id)?>
<?php
if (!empty($_GET['subcat'])){
	echo ' &gt; '.$h->getLinkSubcat(array ("nombre"=>$categoria->getSubCategoriaNombre(), "id"=>$categoria->getSubCategoriaId(), "catid"=>$categoria->getCategoriaId()));
}
?>
</div>