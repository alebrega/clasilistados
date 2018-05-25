<div style="padding: 12px; background: <?=$color?> none repeat scroll 0% 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;">			
<?php
					if (!empty($mensaje)){
						echo $mensaje;
					}
					?>
					<p>Abajo puedes visualizar como se va a ver tu anuncio.
					</p>
					<?php
					if(!$haySuscripcionCategoria){?>
					<p>Para publicar, editar, o eliminar tu anuncio debes tu presionar los botones de abajo:<br/></p>	
					<?php 
					}
					?>
					<table border="0" cellpadding="2">
					
					<tr>
					<?php 
					if($haySuscripcionCategoria){
						?>
							<td>
							<?php
							$parametrosSubcategoria[]='s-'.$categoria->getSubcategoriaId();
							?>
							<form action="<?=$h->getPostingFormAction($categoria->getCategoriaId(),$categoria->getSubCategoriaId())?>" method="post">
						
							<input type="hidden" value="reenviar" name="accion"/>
						<input type="hidden" value="<?=$adid?>" name="id"/>
						<input type="hidden" value="<?=$catid ?>" name="cat"/>
						<input type="hidden" value="<?=$_GET['c'] ?>" name="c"/>
						<input type="submit" value="re-publicar"/>
							</form>
							</td>
						<?php
					}else{
					if (!$anuncio->estaHabilitado()){
							?>
							<td>
							<form method="post" action="<?=$h->getAdminAnuncioLink($_GET['id'],$_GET['c'],$_GET['catid'])?>">
							<input type="hidden" value="<?=$adid?>" name="id"/>
							<input type="hidden" value="<?=$_GET['c'] ?>" name="c"/>
							<input type="submit" value="publicar" name="boton"/>
							</form>
							</td>
							<?php
						}
						?>
						<td>
						<form action="<?=$h->getPostingFormAction($categoria->getCategoriaId(),$categoria->getSubCategoriaId())?>" method="post">
						<input type="hidden" value="editar" name="accion"/>
						<input type="hidden" value="<?=$adid?>" name="id"/>
						<input type="hidden" value="<?=$catid ?>" name="cat"/>
						<input type="hidden" value="<?=$_GET['c'] ?>" name="c"/>
						<input type="submit" value="editar"/>
						</form>
						</td>
						<?php
						if($anuncio->estaHabilitado()){
							?>
							<td>
							<form method="post" action="<?=$h->getAdminAnuncioLink($_GET['id'],$_GET['c'],$_GET['catid'])?>">
							<input type="hidden" value="<?=$adid?>" name="id"/>
							<input type="hidden" value="<?=$_GET['c'] ?>" name="c"/>
							<input type="submit" value="eliminar" name="boton"/>
							</form>
							</td>
					
					<?php 
						}
					}
						?>
						</tr>
						</table>
							
				</div>
				
<p><a href="<?=$h->getHost()?>">volver a clasilistados</a> | <?=($logueado)?$h->getVolverMiCuentaLink():$h->getIngresarMiCuentaLink(true)?></p>