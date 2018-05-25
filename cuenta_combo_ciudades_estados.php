<select name="ubicacion">
<?php
$cityId=$usuario->getCityId();
if (empty($cityId)){
	echo '<option value="" SELECTED>escoge una</option>';
}
$ciudadesMasImp=$locacion->getCiudadesDestacadasdelPais();
foreach ($ciudadesMasImp as $ciudad){
	$seleccionado=' ';
	if ($cityId==$ciudad['cityid']){
		$seleccionado='SELECTED';
	}
	echo '<option value="cit-'.$ciudad['cityid'].'" '.$seleccionado.' >'.$ciudad['ciudad'].'</option>';	
}
?>
</select>