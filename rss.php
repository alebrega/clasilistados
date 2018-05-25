<?php
ob_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
$newtext = ob_get_clean();
header("Content-type: text/xml");
$rss= "<?xml version='1.0' encoding='UTF-8'?><rss version='2.0'>
<channel>
<title>".NOMBRE_SITIO." | ".$categoriaSeo." </title>
<link>".$h->getHost().str_replace('/rss','',$_SERVER['REQUEST_URI'])."</link>
<description>".str_replace("-"," ",$categoriaSeo)." en ".$location.". Anuncios clasificados de ".str_replace("-"," ",$categoriaSeo)." en ".$location."</description>
<language>es-us</language>";

foreach ($anuncios as $anuncio){
	$rss.= "<item>
	<pubDate>".date('D, d M Y H:i:s O',$anuncio['fechahora'])."</pubDate>
	<title><![CDATA[".$anuncio['titulo']."]]></title>
	<link>".$anuncio['link']."</link>";
	$descripcion=substr($anuncio['descripcion'],0,50);
	$descripcion.='...';
	$rss.= "<description><![CDATA[".$descripcion."]]></description>
	</item>";
	
}
$rss.= "</channel></rss>";
echo $rss;
?>