<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<?php 
$this->renderModule('headlistados');
?>
</head>
<?php flush(); ?>
<body class="toc">

<a name="top"></a>
<?php
//$this->renderModule('headerlistados');
//$this->renderModule('buscador');
?>


<blockquote>
<table summary="" width="95%">
        <tbody>
        <tr>
        	<td valign="top">[ <?=$this->getVar('fechaHoraBusqueda')?> ]</td>
			<td id="messages" valign="top"><?=$this->helper->getMensajeItemsProhibidosListados();?></td>
        </tr>
</tbody></table>
<?php
$listados=$this->getVar('listados');
if ($listados->getEsEvento()){
?>
<table width="90%">
	<tbody><tr>
		<td align="left" width="33%"><a href="<?=$listados->getDiaLink(-1)?>">&lt;&lt;dia anterior</a></td>
		<td width="33%" style="text-align: center;"><b><?=$this->helper->getFormatoFechaEvento($listados->getFechaEvento())?></b></td>
		<td align="right" width="33%"><a href="<?=$listados->getDiaLink(1)?>">dia siguiente&gt;&gt;</a></td>
	</tr>
</tbody></table>
<?php
}
?>
<?php
$busqueda=$this->getVar('busqueda');
if (is_null($_GET['busqueda'])){
	$listados->getListadoCategoria($pagina,$categoria_id,$subcategoria_id);
	$encontrados=$listados->getAnunciosEncontrados();
}else{
	echo $busqueda->getResultados();
	$encontrados=$busqueda->getAnunciosEncontrados();
}
?>
<?php
if ($encontrados>LIMITE_POR_PAGINA){
?>
<p align="center"><font size="4"><a href="<?=$linkSig.'/'.$pagina?>">siguientes <?=LIMITE_POR_PAGINA?> anuncios</a></font>

</p>
<?php
} ?>
<div id="footer">
	<hr class="hrstyle">
	<span id="copy">
		<br><?=$this->helper->getCopyright()?><br>
		<a href="#top">Volver arriba</a>
	</span>
	<br>
	

<?php
if ($mostrarRss){

?>
<span class="rss">
		<a href="<?=$this->helper->getHost().$_SERVER['REQUEST_URI'].'/rss'?>" class="l">RSS</a>
		<a href="<?=$this->helper->getLegalAbuAyudaLinkHref()?>">(?)</a><br/>
	</span>
	<?php
}
?>

</div>
<br><br>
<div id="floater">&nbsp;</div>


</blockquote>

<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body></html>