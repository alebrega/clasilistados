<?php

require_once("/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<title><?=$h->getTitleLocacion()?></title>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/metas/index.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header_colores.inc.php");
?>
</head>
<?php flush(); ?>
<body class="hp">
	<table summary="page" cellpadding="0" cellspacing="0">
		<tbody><tr>
			<td id="leftbar">
			
				<div id="logo"><?=$h->getLogoLink()?><span class="beta_logo">&nbsp;beta</span></div>
				<div id="menucl">
				<ul id="postlks">
					<li class="destacado"><?=$h->getIngresarMiCuentaLink(true)?></li>
					<li><?=$h->getPostingLink(true)?></li>
					<li><?=$h->getLegalAbuAyudaLink()?></li>
				</ul>
				
				<div>busca en clasilistados</div>
				
				<form class="formFuente" method="get" action="<?=$h->getSearchAction()?>">
				<?php
				$search_form = new Form($h->getSearchAction(),"&gt;","search");
				if ($locacion->esCiudad()){
					?>
					<input type="hidden" value="<?=$locacion->getCiudadURL()?>" name="ciudad"/>
					<?php
				}else{
					?>
					<input type="hidden" value="<?=$locacion->getEstadoURL()?>" name="estado"/>
					<?php
				}
				?>
				<input type="hidden" value="<?=1?>" name="princ"/>
				<input type="hidden" value="<?='c'?>" name="c"/>
				<input type="text" value="<?='tu busqueda aquí...'?>" name="busqueda" onClick="this.value=''" class="btnGris" style="width: 84%;"/>
				<br />
				<select id="cat" name="cat" style="font-family: Bookman Old Style, sans-serif;">
			
				<?php
				foreach ($cats as $name=>$id){
					$selected=false;
					if($name=="compra-venta"){
						$selected=true;
					}
					$selected=($selected)?'selected="selected"':'';
					?>
					<option value=<?=$id?> <?=$selected?> ><?=$name?></option>
					<?php
					
				}
				?>
				
				</select>
				<input type="submit" value=">" id="go"/>
				</form>
				
				<br />

				
		
			<?php
			echo Calendario::renderCalendar();
			?>	


				<br />

				<?php
				require_once($_SERVER["DOCUMENT_ROOT"]."/includes/menu.inc.php");
				?>
				
				<br /><br />
					<?$h->getCopyright()?>	
			
			</div>
			</td>

			<td>&nbsp;&nbsp;</td>

			<td>
				<table summary="main">
					<tbody><tr>
						<td colspan="5" id="topban" align="center">
							<div>
								<h2><?php echo $location ?></h2>
								
							</div>
						</td>
					</tr>

					<tr>
						<td>

					
							
			<div class="ban"><?=$h->getCategoriaLink(9)?></div>
			<table class="w2" summary="comunidad" width="100%" cellspacing="1">
				<tbody>
				<tr>
				<?php
		$mitad=ceil (count($categoria->getSubCategoria(9))/2);
        $i=1;
		foreach ($categoria->getSubCategoria(9) as $subcat){
			if (($i%$mitad==1) || ($i==1))
				echo '<td valign="top">';
			echo $h->getLinkSubcat($subcat);
			if ($i%$mitad==0)
				echo '</td>';
			$i++;
		}
		?>
		</tr>
<tr><td style="background: #F5FFFA;">&nbsp;</td></tr>

</tbody></table>
		
							
			<div class="ban"><?=$h->getCategoriaLink(2)?></div>
			<table class="w2" summary="personales" width="100%" cellspacing="1">
				<tbody><tr>
				<td>
					
					<?php	
					foreach ($categoria->getSubCategoria(2) as $subcat){
						echo $h->getLinkSubcat($subcat);
					}
					 ?>
</td>			
</tr>
<tr><td style="background: #F5FFFA;">&nbsp;</td></tr></tbody></table>

						
						<div class="ban"><span class="urgentetit">urgente!</span></div>
			<table class="w2" summary="vehiculos" width="100%" cellspacing="1">
				<tbody>
				<tr>
		
		
		<?php
		$mitad=ceil (count($cats)/2);
        $i=1;
       
		foreach ($cats as $cat=>$catid){
			if (($i%$mitad==1) || ($i==1))
				echo '<td valign="top">';
			echo '<a href="'.$h->getUrgenteCatLink($catid,$cat).'" title="urgente! '.$cat.'">'.substr($cat,0,10).'</a>';
			if ($i%$mitad==0)
				echo '</td>';
			$i++;
		}
		?>
		</tr>
<tr><td style="background: #F5FFFA;">&nbsp;</td></tr></tbody></table>
						
						<div class="ban"><?=$h->getCategoriaLink(13)?></div>
			<table class="w2" summary="vehiculos" width="100%" cellspacing="1">
				<tbody>
				<tr>
		
		
		<?php
		$mitad=ceil (count($categoria->getSubCategoria(13))/2);
        $i=1;
		foreach ($categoria->getSubCategoria(13) as $subcat){
			if (($i%$mitad==1) || ($i==1))
				echo '<td valign="top">';
			echo $h->getLinkSubcat($subcat);
			if ($i%$mitad==0)
				echo '</td>';
			$i++;
		}
		?>
		</tr>
<tr><td style="background: #F5FFFA;">&nbsp;</td></tr></tbody></table>

							
						<div class="ban"><?=$h->getCategoriaLink(12)?></div>
			<table class="w2" summary="mascotas" width="100%" cellspacing="1">
				<tbody>
				<tr>
		
		
		<?php
		$mitad=ceil (count($categoria->getSubCategoria(12))/2);
        $i=1;
		foreach ($categoria->getSubCategoria(12) as $subcat){
			if (($i%$mitad==1) || ($i==1))
				echo '<td valign="top">';
			echo $h->getLinkSubcat($subcat);
			if ($i%$mitad==0)
				echo '</td>';
			$i++;
		}
		?>
		</tr>
<tr><td style="background: #F5FFFA;">&nbsp;</td></tr></tbody></table>


	<div class="ban"><?=$h->getCategoriaLink(14)?></div>

						</td>

						<td>&nbsp;</td>

						<td>
							
			<div class="ban"><?=$h->getCategoriaLink(3)?></div>
			<table class="w2" summary="inmuebles" width="100%" cellspacing="1">
				<tbody>
				<tr>
		
		
		<?php
		$mitad=ceil (count($categoria->getSubCategoria(3))/2);
        $i=1;
		foreach ($categoria->getSubCategoria(3) as $subcat){
			if (($i%$mitad==1) || ($i==1))
				echo '<td valign="top">';
			echo $h->getLinkSubcat($subcat);
			if ($i%$mitad==0)
				echo '</td>';
			$i++;
		}
		?>
		</tr>
<tr><td style="background: #F5FFFA;">&nbsp;</td></tr></tbody></table>

							
			<div class="ban"><?=$h->getCategoriaLink(4)?></div>
			<table class="w2" summary="compra-venta" width="100%" cellspacing="1">
				<tbody>
				<tr>
	<?php
		
		$mitad=ceil (count($categoria->getSubCategoria(4))/2);
        $i=1;
		foreach ($categoria->getSubCategoria(4) as $subcat){
			if (($i%$mitad==1) || ($i==1))
				echo '<td valign="top">';
			echo $h->getLinkSubcat($subcat);
			if ($i%$mitad==0)
				echo '</td>';
			$i++;
		}
		?>
</tr>
<tr><td style="background: #F5FFFA;">&nbsp;</td></tr>
</tbody></table>

							
			<div class="ban"><?=$h->getCategoriaLink(5)?></div>
			<table class="w2" summary="servicios" width="100%" cellspacing="1">
				<tbody>
				<tr>
				

<?php
		$mitad=ceil (count($categoria->getSubCategoria(5))/2);
        $i=1;
		foreach ($categoria->getSubCategoria(5) as $subcat){
			if (($i%$mitad==1) || ($i==1))
				echo '<td valign="top">';
			echo $h->getLinkSubcat($subcat);
			if ($i%$mitad==0)
				echo '</td>';
			$i++;
		}
		?>
		</tr>
<tr><td style="background: #F5FFFA;">&nbsp;</td></tr></tbody></table>

						</td>

						<td>&nbsp;</td>

						<td>

							
			<div class="ban"><?=$h->getCategoriaLink(6)?></div>
			<table class="w2" summary="trabajo" width="100%" cellspacing="1">
				<tbody>
				<tr><td>
<?php
		foreach ($categoria->getSubCategoria(6) as $subcat){
			echo $h->getLinkSubcat($subcat);
		}
		?>
		</td></tr>
<tr><td style="background: #F5FFFA;">&nbsp;</td></tr></tbody></table>
					

							
			<div class="ban"><?=$h->getCategoriaLink(7)?></div>
			<table class="w2" summary="trabajo temporal" width="100%" cellspacing="1">
				<tbody>
				<tr>
				<?php
				
		$mitad=ceil (count($categoria->getSubCategoria(7))/2);
        $i=1;
		foreach ($categoria->getSubCategoria(7) as $subcat){
			if (($i%$mitad==1) || ($i==1))
				echo '<td valign="top">';
			echo $h->getLinkSubcat($subcat);
			if ($i%$mitad==0)
				echo '</td>';
			$i++;
		}
		?>
</tr>
<tr><td style="background: #F5FFFA;">&nbsp;</td></tr></tbody></table>

							
			<div class="ban"><?=$h->getCategoriaLink(8)?></div>
			<table class="w2" summary="currí­culums" width="100%" cellspacing="1">
				<tbody><tr>
					<td>
		</td></tr>
<tr><td style="background: #F5FFFA;">&nbsp;</td></tr></tbody></table>

						</td>
					</tr>
				</tbody></table>
			</td>

			<td>


	
		
<table class="city" summary="city list"><tbody><tr><td>


<h5>ciudades</h5>

<ul>
<?php
			
		 foreach ($locacion->getCiudadesDestacadasdelPais() as $ciudad){
			?>
			<li><?=$h->getLinkCiudad($ciudad)?></li>
			
		 	<?php
		 }
		?>
</ul>
<h5><?=$h->getLinkMasciudades()?></h5>

		</td><td valign="top">

		<h5>estados</h5>
		<ul>
		
		<?php
		foreach ($locacion->getEstados() as $estado){
			?>
			<li><?=$h->getLinkEstado($estado)?></li>
			<?php
		} ?>
	</ul>

		</td></tr></tbody></table>

			</td>

		</tr>
	</tbody></table>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body></html>
