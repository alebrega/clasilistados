<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>

<title>clasilistados: anuncios en <?php echo $location ?>, <?=$h->getCategoriasTitulo()?></title>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header.inc.php");
?>
</head>
<?php flush(); ?>
<body>
<blockquote>

	<h3><a href="/" class="sin_decoracion_texto">clasilistados</a>&nbsp;&gt; <b><?=$location ?></b>	</h3>
	<blockquote>&nbsp;
		<blockquote>
			<h4>escoge el lugar mas cercano adonde te encuentres (<?=$h->getSugerenciasLink()?>):</h4>
			<blockquote>&nbsp;
							
		<?php
		if ($locacion->esEstado()){
			$ciudades=$locacion->getTodasLasCiudadesdelEstado();
			if (count($ciudades)<14){
				echo '<div id="list2">';
			}else{
				echo '<div id="list">';
			}
			foreach ($ciudades as $ciudad){
				echo $h->getLinkHomeLocacion($ciudad)
			?>
				<br />
			 	<?php
			 	
			}
			echo '</div>';
		}
		
			?>
			
				
			</blockquote>
		</blockquote>
	</blockquote>
</blockquote>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body></html>