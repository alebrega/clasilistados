<?php
function __autoload($class_name) {
	$file=$_SERVER['DOCUMENT_ROOT'] . "/lib/class.".strtolower($class_name).".php";
	if (strpos($class_name,"_")){
		$paquete=split("_",$class_name);
		$file=$_SERVER['DOCUMENT_ROOT'] . "/lib/".strtolower($paquete[0])."/".strtolower($paquete[1]).".php";
	}
	require_once($file);
}
?>