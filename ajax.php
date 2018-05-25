<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/init.php");

	switch ($_POST['accion']){
		case 'mostrarCiudades':
			$stateid = $db->real_escape_string($_POST['stateid']);
			break;
		default:
			break;
	}
?>