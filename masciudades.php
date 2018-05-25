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
<body id="ciudades">
<blockquote>

	<h3><a href="/" class="sin_decoracion_texto">clasilistados</a>&nbsp;&gt; <b><?=HOME_PAIS ?></b>	</h3>
	<blockquote>&nbsp;
		<blockquote>
		<?=$h->getMensEscojaCiudad()?>
			

			<blockquote>&nbsp;
					<div id="list">
	
		<?php
			foreach ($locacion->getMasCiudadesDestacadasdelPais() as $ciudad){
				?>
				<?php
				if ($ciudad["is_bold"]==1){
					echo '<b>'.$h->getLinkCiudad($ciudad).'</b>';
				}elseif(!empty($ciudad["ciudad"])){
					echo $h->getLinkCiudad($ciudad);
				}elseif(!empty($ciudad["estado"])){
					echo $h->getLinkEstado($ciudad);
				}
				?>
				<br />
			 	<?php
			 	
			}
		?>
		
</div>
			</blockquote>
		</blockquote>
	</blockquote>
</blockquote>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body></html>