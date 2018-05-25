<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
        
<script type='text/javascript'>
var googletag = googletag || {};
googletag.cmd = googletag.cmd || [];
(function() {
var gads = document.createElement('script');
gads.async = true;
gads.type = 'text/javascript';
var useSSL = 'https:' == document.location.protocol;
gads.src = (useSSL ? 'https:' : 'http:') + 
'//www.googletagservices.com/tag/js/gpt.js';
var node = document.getElementsByTagName('script')[0];
node.parentNode.insertBefore(gads, node);
})();
</script>
        
<script type='text/javascript'>
googletag.cmd.push(function() {
googletag.defineSlot('/1089488/clasi_728x90_anuncios', [728, 90], 'div-gpt-ad-1388086537590-0').addService(googletag.pubads());
googletag.defineSlot('/1089488/clasi_728x90_listados_abajo', [728, 90], 'div-gpt-ad-1388086537590-1').addService(googletag.pubads());
googletag.defineSlot('/1089488/clasi_728x90_listados_arriba', [728, 90], 'div-gpt-ad-1388086537590-2').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();
});
</script>

<?php 
if (!empty($_GET['ordenar'])){
	$ordenar="por ".str_replace("_"," ",$_GET['ordenar']);
}else{
	$ordenar="";
}
if ($pagina<2){
	$paginaTitulo="";
}else{
	$paginaTitulo=' - '.$pagina;
}
if ($locacion->esCiudad()){
?>
<title><?='clasificados en español de '.$categoriaSeo.' en '.$location.' '.$locacion->getEstado().$paginaTitulo." ".$ordenar ?></title>
<?php 
}else{?>
<title><?='clasificados en español de '.$categoriaSeo.' en '.$location.$paginaTitulo." ".$ordenar ?></title>
<?php 
}?>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/metas/listados.inc.php");
if ((!$anuncios) || (!buscador::getInstance()->getFiltrarPorLocacion())){?>
<META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">
<?php 
}else{
	?>
<META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
<?php 
}
?>
<link rel=alternate type="application/rss+xml" href="<?=$h->getCategoriaLinkHref($_GET['cat']).'/rss'?>" title="clasilistados rss feed | <?=$categoriaSeo ?> en <?=$location?> ">
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header.inc.php");
?>
<script type="text/javascript" src="<?php echo version('http://'.$_SERVER['SERVER_NAME'].'/js/jquery.js');?>"></script>
<script type="text/javascript" src="<?php echo version('http://'.$_SERVER['SERVER_NAME'].'/js/listados.js');?>"></script>
</head>
<?php flush(); ?>
<body class="toc">

<a name="top"></a>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/headerlistados.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/buscador.inc.php");
?>


<blockquote>
<?php 
if (!buscador::getInstance()->estaBuscando()){
?>
<table summary="" width="100%" style="margin: 5px;">
        <tbody>
        <tr>
        	<td valign="top">[ <?=$fechaHoraBusqueda?> ]</td>
			<td id="messages" valign="top">
		<font size="3">[<a href="<?=$h->getHost()?>/legal-abusos-ayuda/mejorar-visibilidad-anuncio" target="_blank" rel="nofollow">¿Como destaco mi anuncio?</a>]</font>
		<font size="3">[<a href="<?=$h->getHost()?>/legal-abusos-ayuda/mejorar-visibilidad-anuncio" target="_blank" rel="nofollow">¿Que es <span class="urgente">urgente!</span>?</a>]</font>
		[<?=$h->getMensajeItemsProhibidosListados();?>]
			</td>
        </tr>
</tbody></table>
<?php
}
if (empty($subcategoria_id) && $categoria->esEvento()){
?>
<table width="95%" style="margin: 5px;">
	<tbody><tr>
		<td align="left" width="33%"><a href="<?=$listados->getDiaLink($listados->getFechaEvento(),-1)?>">&lt;&lt;dia anterior</a></td>
		<td width="33%" style="text-align: center;"><b><?=$h->getFormatoFechaEvento($listados->getFechaEvento())?></b></td>
		<td align="right" width="33%"><a href="<?=$listados->getDiaLink($listados->getFechaEvento(),1)?>">dia siguiente&gt;&gt;</a></td>
	</tr>
</tbody></table>
<?php
}
if (!$anuncios){
	echo '<hr><br /><strong>no se han encontrado anuncios para esta búsqueda (todas las palabras deben coincidir). </strong><br /><br />';
}else{
	if (!buscador::getInstance()->getFiltrarPorLocacion()){
		echo '<br /><br /><span>aquí tienes algunos resultados similares a la búsqueda realizada con '.$categoria->getCategoriaNombre().':</span><br /><br />';
		echo $anuncios;
	}else{
		require_once($_SERVER["DOCUMENT_ROOT"].'/includes/submenu.inc.php');
		echo $anuncios;
	}
	
}
?>
<hr>

<div id="footer">
	<span id="copy">
		<br><?=$h->getCopyright()?><br>
		<a href="#top">Volver arriba</a>
	</span>
	<br>
	

<?php
if ($mostrarRss){
?>
<span class="rss">
		<a href="<?=$h->getCategoriaLinkHref($_GET['cat']).'/rss'?>" class="l">RSS</a>
		<a href="<?=$h->getLegalAbuAyudaLinkHref()?>">(?)</a><br/>
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
