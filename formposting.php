<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<?php 
if ($esCategoriaPaga){
	?>
	<title>clasilistados <?php echo $location ?> >
	 anuncio en
	 <?php 
	 $cant_subcategorias=count($subcategoriasElegidas);
     $i=0;
     while ($i<$cant_subcategorias){
     	echo $subcategoriasElegidas[$i]['nombre'];
        $i++;
        if ($i<$cant_subcategorias){
        	echo ' / ';
        } 
     }
    ?>
   </title>
	<?php 
}else{
?>
<title>clasilistados <?php echo $location ?> > anuncio en <?=$categoria->getSubCategoriaNombre()?></title>
<?php
}
?>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/metas/noindex.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/headerssl.inc.php");
?>
</head>
<?php flush(); ?>
<body id="pp">
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/headerposting.inc.php");
if ( (!$anuncio->pulsoEditar()) && ($anuncio->pulsoContinuar()) && (($anuncio->validarPublicacion()) ) || ($anuncio->pulsoAceptar()) || ($anuncio->pulsoRechazo())){

	if ((strlen($_POST['val'])>0) || ($hayDestacado &&  $_REQUEST['accion']=="editar")){
		if (($esCategoriaPaga || $esSubCategoriaPaga || $hayDestacado) && !$suscripcion->usuarioTieneSuscripcion()){
			$titulo=$anuncio->getTitulo();
			$items=$subcategoriasElegidas;
			$total_a_pagar=$publicacion->getTotalPrecio($subcategoriasElegidas);
			if($_POST['destacado_1']==ANUNCIO_DESTACADO){
				$total_a_pagar=$total_a_pagar+PRECIO_DESTACADO;
			}
			if ($_POST['destacado_2']==ANUNCIO_URGENTE){
				$total_a_pagar=$total_a_pagar+PRECIO_URGENTE;
			}
			if ($hayDestacado &&  ($_REQUEST['accion']=="editar") && empty($_POST['credit_card_form'])){
				require_once($_SERVER["DOCUMENT_ROOT"]."/descripcion_formpago.php");
			}elseif (!$validoCaptchaCondiciones && empty($_POST['credit_card_form'])){
				$form_action=$h->getPublicacionesPagasLink().'cat-'.$categoria->getCategoriaId().'_'.implode('_',$parametrosSubcategoria);
				require_once($_SERVER["DOCUMENT_ROOT"]."/validar_anuncio.php");
			}elseif($anuncio->pulsoAceptar() && empty($_POST['credit_card_form'])){
				require_once($_SERVER["DOCUMENT_ROOT"]."/descripcion_formpago.php");
			}elseif(!empty($_POST['credit_card_form'])){
					if (($resultpago===true) && ($resultpago==true)) {?>
						<?php
						require_once($_SERVER["DOCUMENT_ROOT"]."/anuncio_confirmaciones.php");
					}else{
						$titulo=$anuncio->getTitulo();
						$items=$subcategoriasElegidas;
						$total_a_pagar=$publicacion->getTotalPrecio($subcategoriasElegidas);
						require_once($_SERVER["DOCUMENT_ROOT"]."/descripcion_formpago.php");
					}
				}
			
		}else{
			$form_action=$h->getPublicacionLink($categoria->getCategoriaId(),$parametrosSubcategoria);
			if (is_array($idCodigoSeg)){
				require_once($_SERVER["DOCUMENT_ROOT"]."/anuncio_confirmaciones.php");
			}
			else
			{
				require_once($_SERVER["DOCUMENT_ROOT"]."/validar_anuncio.php");
			}			
		}
	}else{
		$anuncio->getTiempoPublicacion(); 
		?>
		
		<?php
		if (($esCategoriaPaga || $esSubCategoriaPaga || $hayDestacado) && !$suscripcion->usuarioTieneSuscripcion()){
			$titulo=$anuncio->getTitulo();
			$items=$subcategoriasElegidas;
			require_once($_SERVER["DOCUMENT_ROOT"]."/includes/descripcion_precio.inc.php");
			echo '<br />';
		}
		?>
		<div class="remarcar">Tu anuncio será publicado en clasilistados <em><?=$location?></em> para <em><?=$anuncio->getEmail()?></em></div>
		<div class="fondoPrevisAnuncio">
		<?php
		require_once($_SERVER["DOCUMENT_ROOT"]."/anuncio.php");
		?>
		<hr />
		</div>		
		<br />
		<!-- continuar -->
		<?php 
		if (($esCategoriaPaga || $esSubCategoriaPaga)){
			?>
			<form action="<?=$h->getPublicacionLink($categoria->getCategoriaId(),$parametrosSubcategoria)?>" method="post" style="display: inline;">
			<?php 
		}else{
			?>
			<form action="<?=$h->getPostingAction()?>" method="post" style="display: inline;">
			<?php 
		}
		$anuncio->traerCamposOcultosaInsertar();
		?>
		<input type="hidden" name="fechaHora" value="<?=$crypt->encrypt($anuncio->getFechaInsertarAnuncio())?>" />
		<input type="hidden" name="val" value="1" />
		<input tabindex="1" name="continuar" value="Continuar" type="submit" id="submit">&nbsp;&nbsp;
		</form>
		
		<!-- editar -->
		<?php 
		if (($esCategoriaPaga || $esSubCategoriaPaga)){
			?>
			<form action="<?=$h->getPublicacionLink($categoria->getCategoriaId(),$parametrosSubcategoria)?>" method="post" style="display: inline;">
			<?php 
		}else{
			?>
			<form action="<?=$h->getPostingAction()?>" method="post" style="display: inline;">
			<?php 
		}
		if($anuncio->pulsoContinuar()){
			?>
			<input type="hidden" name="descripcion2" value="<?=$crypt->encrypt($descripcion2)?>" />
			<?php 	
		}
		$anuncio->traerCamposOcultosaInsertar();
		?>
		<input tabindex="1" name="editar" value="Editar" type="submit" id="submit">&nbsp;&nbsp;
		</form>
		
		<br />
		<p><b>Las respuestas a tu anuncio llegaran a tu correo electronico.</b></p>
		<p><b>Recuerda que no editamos ni leemos tu anuncio y que la responsabilidad de lo que pongas es enteramente tuya.</b></p>
		<?php
	}
}elseif(!empty($_REQUEST['subcat']) && empty($_POST['suscribirme'])){
	if($anuncio->pulsoContinuar()){
		$anuncio->setDescripcion($descripcion2);
	}
	?>
	<script type="text/javascript" src="<?php echo version($h->getHost(true).'/js/jquery.js');?>"></script>
	<script type="text/javascript" src="<?php echo version($h->getHost(true).'/js/publicacion.js');?>"></script>
	<?php
	if (($esCategoriaPaga || $esSubCategoriaPaga)){
		$posting_form = new Form($h->getPublicacionesPagasLink().'cat-'.$categoria->getCategoriaId().'_'.implode('_',$parametrosSubcategoria),"Continuar","publicacion","post","multipart/form-data");	
	}else{
		
		$posting_form = new Form($h->getPostingAction(),"Continuar","publicacion","post","multipart/form-data");
	
	}
	echo $posting_form->getFormOpenTag();
	require_once($_SERVER["DOCUMENT_ROOT"]."/formulario.php");
}else{
		$titulo='Suscripción - Publicaciones ilimitadas hasta el '.date('d').'-'.date('m').'-'.(date('Y')+1);
		$items=array(0=>array('nombre'=>$categoria->getCategoriaNombre(),'precio'=>$publicacion->getPrecio($categoria->getCategoriaId(),'suscripcion')));
		$total_a_pagar=$publicacion->getPrecio($categoria->getCategoriaId(),'suscripcion');
		if (($hayDestacado || (!empty($_POST['suscribirme']) && $haySuscripcionCategoria)) && !$resultpago && empty($_POST['credit_card_form'])){
			  if ($usuario->estaLogueado()){
			  	$_POST['email']=$usuario->getEmail();
			  	require_once($_SERVER["DOCUMENT_ROOT"]."/descripcion_formpago.php");
			  }else{
			  	echo '<blockquote>
			  	<table border="0" width="100%">
			  	<tr>
			  	<td width="45%" align="left" valign="top">
			  	<form name="registrado" action="'.$h->getMiCuentaLink().'">
			  	<input type="hidden" name="ir" value="'.$h->getPublicacionLink($categoria->getCategoriaId()).'?suscribirme=1" />
			  	<br /><b>Debes tener una cuenta de usuario para suscribirte:</b>
			  	<br /><br /><span class="highlight"><label><input type="radio" name="registrado" value="1" onClick="document.registrado.submit()"> Tengo una cuenta</label></span>';
			  	echo '<br /><br /><div class="highlight"><label><input type="radio" name="registrado" value="0" checked="checked"> No tengo una cuenta</label>
			  	</form>
			  	<br /> <br />';
			  	require_once($_SERVER["DOCUMENT_ROOT"]."/includes/crearcuenta.inc.php");
			  	echo'</div>
			  	</td>
			  	<td valign="top"></td>
			  	</tr>
			  	</table></blockquote>';
			  }
		}elseif((!empty($_POST['credit_card_form']) && !empty($_POST['suscribirme'])) || $hayDestacado){
			if (($resultpago===true) && ($resultpago==true)) {
				$titulo='Suscripción - Publicaciones ilimitadas hasta el '.date('d',$suscripcion->getVencimiento()).'-'.date('m',$suscripcion->getVencimiento()).'-'.(date('Y',$suscripcion->getVencimiento()));
				require_once($_SERVER["DOCUMENT_ROOT"]."/anuncio_confirmaciones.php");
			}else{
				require_once($_SERVER["DOCUMENT_ROOT"]."/descripcion_formpago.php");
			}
		}else{
			require_once($_SERVER["DOCUMENT_ROOT"]."/seleccsubcategoria.php");	
		}		
}
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body></html>