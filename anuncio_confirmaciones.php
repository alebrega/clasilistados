<blockquote id="thanks">
<?php
if (empty($_POST['suscribirme'])){
	if ($usuario->estaLogueado()){
?>
	<div>
	<p>Gracias por publicar un anuncio en clasilistados, te lo agradecemos enormemente.</p>
	<p style="color: green">Ya puedes administrar tu anuncio desde tu cuenta</p>
	<p>Lo que te permitirá:</p>
	<ul>
	        <li>ver el anuncio
	        </li><li>modificarlo
	        </li><li>borrarlo
	</li></ul>
	<b><a href="<?=$h->getMiCuentaLink() ?>">ver mis anuncios</a></b><br>
	</div>
	<?php
	}else{
	?>
	<div>
	
	<em>En breve recibirás un correo electrónico</em>, con un enlace que te permitirá: 
	<ul>
	        <li>ver tu anuncio
	        </li><li>modificarlo
	        </li><li>borrarlo
	</li></ul>
	<b>GUARDA ESTE CORREO ELECTRONICO</b> -- lo necesitarás si es que decides editar o borrar tu anuncio.<br>
	</div>
	
	<br>
	<div>
	        
	        Si no recibiste ningun correo electónico, por favor consulta nuestras <a href="<?=$h->getAyudaLinkHref()?>">páginas de ayuda</a>
	</div>
	
	<br><br>
	
	<div>
	        <i>¿El correo electronico confirmando tu anuncio esta tardando demasiado?</i><br>
	        te sugerimos <a href="<?=$h->getCrearCuentaHref()?>">crear una cuenta en clasilistados</a> para que puedas gestionar tus anuncios más rápido y con mayor facilidad.       
	</div>
<?php
	}
}else{
	?>
	<div>
	<p><span style="color:green;">Gracias por suscribirte a la categoria <b><?=$categoria->getCategoriaNombre()?></b></span>, te lo agradecemos enormemente.</p>
	<b>Para publicar anuncios con tu suscripción, previamente debes haber iniciado sesión con tu cuenta.</b>
	<br />
	</div>
	<?php 	
}
if (($esCategoriaPaga || $esSubCategoriaPaga || $haySuscripcionCategoria) && $resultpago){
	echo '<br><br><div>';
	echo 'Tu pago se ha realizado correctamente -- Su codigo de pago es: <b style="color: green">'.$publicacion->getTransaccionId().'</b><br /><br />';
	$pagado=true;
	require_once($_SERVER["DOCUMENT_ROOT"]."/includes/descripcion_precio.inc.php");
	echo '</div>';
	if (!empty($_POST['suscribirme'])){
	?>
	<!-- Google Code for Subscripcion Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1037651831;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "sc08CPPiwwEQ957l7gM";
var google_conversion_value = 0;
if (150) {
  google_conversion_value = 150;
}
/* ]]> */
</script>
<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/1037651831/?value=150&amp;label=sc08CPPiwwEQ957l7gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
	
<?php 
	}else{
	?>
	<!-- Google Code for Publicacion Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1037651831;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "mt9ECPvhwwEQ957l7gM";
var google_conversion_value = 0;
if (50) {
  google_conversion_value = 50;
}
/* ]]> */
</script>
<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/1037651831/?value=50&amp;label=mt9ECPvhwwEQ957l7gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
	<?php 
	}
}
?>
<br><br>
¡Gracias por usar clasilistados!
<br>
<ul>
        <li><?=$h->getPostingLink(true)?></li>
        <li><?=$h->getHomeLinkLocation()?></li>
        </ul>
</blockquote>