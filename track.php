<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");

if (!empty($_GET['fid'])){
	Tracking::getInstance()->trackFeedUrl($_GET['fid'],$_GET['url']);	
}
if ($h->validarURL($_GET['url'])){
	$url=$_GET['url'];	
}else{
	$url=$_SERVER["HTTP_REFERER"];
}
header("Location: ".$url);
exit();
?>