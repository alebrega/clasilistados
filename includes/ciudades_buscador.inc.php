		<?php
			if (!empty($_GET['ciudad'])){
				$ciudades=$locacion->getCiudadURLPorNombreEstadoId($_GET['ciudad'],$_GET['estadoId']);
				if (!$ciudades){
					$mensaje='En el estado de '.$_GET['estado'].' no se han encontrado ciudades con el nombre <font style="color:red;">"'.$_GET['ciudad'].'"</font>';
				}else{
					$locacion->setEsCiudad(true);
					$locacion->setEsEstado(false);
					if(count($ciudades)==1){
						$h->ir($h->getLinkHomeCiudadHref($ciudades[0]));
					}else{
						$mensaje='Se han encontrado ciudades con el nombre <font style="color:red;">"'.$_GET['ciudad'].'"</font>';
					}
				}
			}
		if (!empty($mensaje)){
			echo '<b>'.$mensaje.'</b>';
		}
		?>
			<br />
			<hr/>
			<h5>no encuentras tu ciudad en <?=$locacion->getEstado()?>? búscala aquí.</h5>
			<form id="searchciudad" action="<?=$_SERVER['REQUEST_URI']?>" method="get">
					<input name="estadoId" value="<?=$locacion->getEstadoId() ?>" type="hidden">
					<input id="query" name="ciudad" value="<?=empty($_GET['ciudad'])?'tu ciudad aqui...':$_GET['ciudad'];?>" onClick="this.value=''" class="btnGris"><input id="go" value="&gt;" type="submit">
				</form>
				
				