<?php
if ($anuncio->tieneRespuestaViaEnlace()){
	preg_match('@^(?:http://)?([^/]+)@i',$anuncio->getLink(), $coincidencias);
	?>
	<br>
	Contestar a: <a href="<?=$anuncio->getLink()?>" rel="nofollow" target="_blank"><?=$coincidencias[1]?></a>
	<?php
}
?>