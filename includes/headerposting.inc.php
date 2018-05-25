<table id="header" summary="header" width="100%">
        <tbody>
        <tr valign="top">
        <td>             
          <b>
         <?=$h->getHomeLinkLocation()?>
          &gt; 
         
         <?php
         $categoria_id=$categoria->getCategoriaId();
         if (!empty($categoria_id)){
         	echo $categoria->getCategoriaNombre().' > ';
         }
         if (!empty($subcategoriasElegidas)){
         	 $cant_subcategorias=count($subcategoriasElegidas);
         	 $i=0;
         	 while ($i<$cant_subcategorias){
	         	echo $h->getLinkSubcat(array ("nombre"=>$subcategoriasElegidas[$i]['nombre'], "id"=>$subcategoriasElegidas[$i]['id'], "catid"=>$subcategoriasElegidas[$i]['catid']));
	         	$i++;
         	 	if ($i<$cant_subcategorias){
         	 		echo ' / ';
         	 	}
         	 }
         }else{
         	echo $h->getLinkSubcat(array ("nombre"=>$categoria->getSubCategoriaNombre(), "id"=>$categoria->getSubCategoriaId(), "catid"=>$categoria->getCategoriaId()));
       
         }
         ?> &gt; 
         <?php 
         if (!empty($_POST['suscribirme'])){
         	echo 'suscripción';
         }else{
         	echo 'publicar anuncio';
         }
         ?>
         </b>
          <br />
          <?php 
          if (empty($_REQUEST['suscribirme'])){
          	?> 
          	<i>Tu anuncio caducará en 45 días</i>
          <?php 
          }
          ?>
         
         </td> 
		
         <?php
         if ($usuario->estaLogueado()){
         ?>
		
		 <td width="10%" style="text-align: right; white-space: nowrap;" class="remarcar">
		<font color="#7a7a7a" size="2">
		[ sesión iniciada como <a href="<?=$h->getMiCuentaLink() ?>"><b><?=$usuario->getEmail()?></b></a> ] 
		[ <a href="<?=$h->getMiCuentaLink().'?a=salir&ir='.$h->getHost().$_SERVER['REQUEST_URI']?>">cerrar sesión</a> ]
		</font>
		</td>
		
	<?php
         }else{
         ?>
		
       <td width="10%" style="text-align: right; white-space: nowrap;" class="remarcar">
			<font face="sans-serif">
				<b>
				<a href="<?=$h->getMiCuentaLink().'?ir='.$h->getHost().$_SERVER['REQUEST_URI']?>" rel="nofollow">ingresar a mi cuenta</a>
				</b>
				<br/><?=$h->getCrearCuentaLink()?>
			</font>
		</td>
		
		<?php
         }
         ?>
        </tr> </tbody>
</table>
<?php
if ($_GET['cat']==$cats['inmuebles']){
	require_once($_SERVER["DOCUMENT_ROOT"]."/includes/inmuebles_advertencia.inc.php");
}
?>
<hr class="hrstyle" />