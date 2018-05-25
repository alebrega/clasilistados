<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>

<title><?=$h->getGenericTitle()?></title>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header_colores.inc.php");
?>
</head>
<?php flush(); ?>
<body class="hp">

<table class="www">

<tbody><tr>

<td> <font size="6" color="blue">clasilistados</font><span class="beta_logo">&nbsp;beta</span></td>

<td rowspan="3" width="40"><font color="#f4f4f4">.</font></td>

<td><b>estados <?=HOME_PAIS?></b></td><td colspan="2"></td><td><b>ciudades <?=HOME_PAIS?></b></td><td></td><td><b style="font-size: 12px;"><?=$h->getLinkMasciudades()?></b></td><td></td></tr>

<tr>
<td rowspan="2" align="left">

					<?=$h->getIngresarMiCuentaLink(true)?>
					<br />
					<?=$h->getLegalAbuAyudaLink()?>
				

				<br />

				


				<br />
				
							
				<?php
				echo (strip_tags(file_get_contents($_SERVER["DOCUMENT_ROOT"]."/includes/menu.inc.php"),'<a>'));
				?>
				
				<br />
				<br />
				<?$h->getCopyright()?>			

</td>



      
		
		<?php
		$estados=$locacion->getEstados();
		$mitad=ceil (count($estados)/2);
		$i=1;
		foreach ($estados as $estado){
			if (($i%$mitad==1) || ($i==1))
				echo '<td valign="top">';
			echo '<a href="'.$h->getLinkEstadoHref($estado['url_estado']).'" style="background: white; padding: 2px 2px 2px 5px;" title="'.$estado["estado"].'">'.$estado['estado'].'</a>';
			if ($i%$mitad==0)
				echo '</td>';
			$i++;
		}
		?>
		


<td valign="top" width="20">


</td>
<?php
		$mitad=21;
		$mitad=ceil (count($locacion->getCiudadesDestacadasdelPais())/2);
		$i=1;
		foreach ($locacion->getCiudadesDestacadasdelPais() as $ciudad){
			if (($i%$mitad==1) || ($i==1))
				echo '<td valign="top">';
			echo '<a href="'.$h->getLinkCiudadHref($ciudad).'" style="background: white; padding: 2px 2px 2px 5px;" title="'.$ciudad["ciudad"].'">'.$ciudad["ciudad"].'</a>';
			if ($i%$mitad==0)
				echo '</td>';
			$i++;
		 	
		}
?>
</tr>
</tbody></table>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body></html>