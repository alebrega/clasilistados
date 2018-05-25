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
googletag.pubads().enableSingleRequest();
googletag.enableServices();
});
</script>
        
<title><?=stripslashes($anuncio->getTitulo())?> - <?=$location?></title>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/metas/item.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header.inc.php");
if (!$anunciosRelacionados){
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/anunciojs.inc.php");
?>
</head>
<?php flush();
 ?>
<body>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/headeranuncio.inc.php");
echo $mensajeResp;
require_once($_SERVER["DOCUMENT_ROOT"]."/anuncio_flags.php"); 
require_once($_SERVER["DOCUMENT_ROOT"]."/anuncio.php"); 
?>
<br /> 
<script type="text/javascript" src="http://w.sharethis.com/button/sharethis.js#publisher=4c72a627-d956-41cf-af63-b1ef069e5820&amp;type=website&amp;buttonText=compartir&amp;post_services=email%2Cfacebook%2Ctwitter%2Cgbuzz%2Cmyspace%2Cdigg%2Csms%2Cwindows_live%2Cdelicious%2Cstumbleupon%2Creddit%2Cgoogle_bmarks%2Clinkedin%2Cbebo%2Cybuzz%2Cblogger%2Cyahoo_bmarks%2Cmixx%2Ctechnorati%2Cfriendfeed%2Cpropeller%2Cwordpress%2Cnewsvine"></script>
<p class="bookman">ID del anuncio: <?=$anuncio->getId()?></p>
<hr>
<br /> 
<?php require_once($_SERVER["DOCUMENT_ROOT"]."/includes/footeranuncio.inc.php"); ?>

<?php
}else{
	?>
	</head>
	<?php flush();?>
	<body>
	<?php require_once($_SERVER["DOCUMENT_ROOT"]."/includes/headeranuncio.inc.php"); ?>
	<h2><?=stripslashes($anuncio->renderTitulo())?> </h2>
	<hr>
	<br /> 
	<p style="font-size: 30px;">este anuncio ya no está disponible</p>
	<br /> 
	<br /> 
	<?php
		$adsense=new Adsense($registro);
		$adsense->setMedidas('728x90');
		echo $adsense->getHTML('texto_contenido');
	?>
	<br /> 
	<hr>
	<br /> 
	<?php require_once($_SERVER["DOCUMENT_ROOT"]."/includes/footeranuncio.inc.php"); ?>
	<?php 
}
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body></html>