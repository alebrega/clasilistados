<?php
if ($locacion->esCiudad()){
	?>
	<h5 class="ctry"><?=$locacion->getEstado() ?></h5>
	<ul>
	<?php
			
		 foreach ($locacion->getCiudadesdelEstado() as $ciudad){
		 	if ($ciudad['negrita']==1){
		 		$ciudadImpLink='<strong>'.$h->getLinkCiudad($ciudad).'</strong>';
		 	}else{
		 		$ciudadImpLink=$h->getLinkCiudad($ciudad);
		 	}
		 	?>
			<li><?=$ciudadImpLink?></li>
			
		 	<?php
		 }
		?>
	</ul>
	<?php
}
?>