<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/config.php");

if (!defined("INIT_DONE")) {
	
	define('SALT_LENGTH', 10);
	
	//TODO: BLACK LIST IP
	require_once($_SERVER['DOCUMENT_ROOT'] . "/autoload.inc.php");
	
	noCache();
	
	require_once($_SERVER['DOCUMENT_ROOT'] . "/objprincipales.inc.php");
	
    define("INIT_DONE", TRUE);
    
    if ($_GET["source"]==NOSCONOCIO_BUSTOS){
    	$registro->get("cookie")->set("source",NOSCONOCIO_BUSTOS);
    }
   	switch ($_SERVER["PHP_SELF"]){
		case HOME_PAGINA:
			$ciudad=null;
			$estado=null;
			
			if (!is_null($_GET["ciudad"])){
				$ciudad=$_GET["ciudad"];
			}else{
				if (!is_null ($cookie->get("ciudad"))){
					$ciudad=$cookie->get("ciudad");
				}
			}
			
			if (!is_null($_GET["estado"])){
				$estado=$_GET["estado"];
			}else{
				if (!is_null ($cookie->get("estado")) && is_null($ciudad)){
					$estado=$cookie->get("estado");
				}
			}
			if (is_null($ciudad) && is_null($estado)){
				$h->irSitios();
				//sino esta seteado el estado y la ciudad en cookies o en la url que vaya a elegir su sitio
			}
			
			$locacion=new Locacion($ciudad,$estado);
			//$locacion->getEstados();
			$categoria=new Categoria();
			$categoria->cargarTodaslasCategorias();
			$registro->set("categorias",$categorias);
			$location=$locacion->getLocation(); //titulo,meta
			if ($locacion->esCiudad())
				$id=$locacion->getCiudadId();
			else
				$id=$locacion->getEstadoId();
			break;
		case SELECCIUDAD_PAGINA:
			$locacion=new Locacion($_GET["ciudad"],$_GET["estado"]);
			$location=$locacion->getLocation(); //titulo,meta,www
			
			break;
		case MASCIUDADES_PAGINA:
			$locacion=new Locacion();
			$location=$locacion->getLocation();		
			break;
		case PUBLICARANUNCIO_PAGINA:
   			$ciudad=null;
			$estado=null;
			if (!is_null($_GET["ciudad"])){
				$ciudad=$_GET["ciudad"];
			}else{
				if (!is_null ($cookie->get("ciudad"))){
					$ciudad=$cookie->get("ciudad");
				}
			}
			
			if (!is_null($_GET["estado"])){
				$estado=$_GET["estado"];
			}else{
				if (!is_null ($cookie->get("estado")) && is_null($ciudad)){
					$estado=$cookie->get("estado");
				}
			}
			$categoria=new Categoria();
			$registro->set("categoria",$categoria);
			$categoria->setCategoriaId($_GET['cat']);
			$locacion=new Locacion($ciudad,$estado);
			$registro->set("locacion",$locacion);
			$usuario=new Usuario($registro);
			if (!empty($_POST['cuenta_admin_publicar'])){
				$ciudad_id=split ('cit-',$_POST['ubicacion']);
				$estado_id=split ('est-',$_POST['ubicacion']);
				if (!empty($ciudad_id[1])){
					$locacion->setCiudadId($ciudad_id[1]);
					$locacion->cargarCiudad();
				}elseif (!empty($estado_id[1])){
					$locacion->setEstadoId($estado_id[1]);
					$locacion->cargarEstado();	
					if(!$locacion->esEstadoPocoPoblado()){
						$h->irSeleccionaCiudad($locacion->getEstadoURL());
					}
				}else{
					//lo envio a elegir su sitio
					$h->ir($h->getMiCuentaLink().'?a=pref');
				}
			}
			$location=$locacion->getLocation();		
			
			$esElegirTipoAnuncio=isset($_GET['cat'])?false:true;
   			$publicacion=new Publicacion($registro);
			
			break;
		case SITIOS_PAGINA:
				$locacion=new Locacion();
				$location="clasificados";
			break;
		case ADMIN_ANUNCIO:
			$publico=false;
			$edito=false;
			$elimino=false;
			$anuncio=new Anuncio($registro,$t_anuncios);
			$imagenes=new Imagenes($registro);
			$categoria=new Categoria();
			$locacion= new Locacion();
			$usuario=new Usuario($registro);
			$categoria->setCategoriaId($_GET['catid']);
			$registro->set("imagenes",$imagenes);
			$registro->set("categoria",$categoria);
			$registro->set("anuncio",$anuncio);
			$registro->set("locacion",$locacion);
			$registro->set("usuario",$usuario);
			$tieneCodSeg=(!empty($_REQUEST['c']));
			$catid=$_REQUEST['catid'];
			$adid=$_REQUEST['id'];
			$borrado=false;
			if ($_GET['republicar']!=1){
				if ($tieneCodSeg){
					if ($_POST['boton']=="publicar"){
						if ($anuncio->publicarAnuncio($adid,$catid,$_POST['c'])){
							$color=COLOR_ADMIN_ACTIVO;
							$mensaje='<b>Tu anuncio ha sido publicado</b>. Si tu lo ves bien ¡ya has terminado!<br />
							Puedes ver el anuncio en:  <b><a href="<<LINK_ANUNCIO>>"><<LINK_ANUNCIO>></a></b>';
						}		
					}elseif($_POST['boton']=="eliminar"){
						$anuncio->eliminarAnuncio($adid,$catid,$_POST['c']);
						$color=COLOR_ADMIN_BORRADO_POR_MI;
						$mensaje='Tu anuncio <b>"<<ANUNCIO_TITULO>>"</b> ha sido eliminado <br />
						Gracias por usar clasilistados.';
						$borrado=true;
					}else{
						$color=COLOR_ADMIN_PENDIENTE;
					}
					$anuncio->cargarAnuncio($adid,$catid,$_REQUEST['c']);
					if ($anuncio->estaHabilitado()){
						$color=COLOR_ADMIN_ACTIVO;
						$linkAnuncio=$h->getAnuncioLink($adid,$anuncio->getTitulo(),$categoria->getCategoriaId(),$categoria->getSubCategoriaId(),$categoria->getSubCategoriaNombre());		
						$mensaje=str_replace('<<LINK_ANUNCIO>>',$linkAnuncio,$mensaje);
					}elseif(!$borrado){
						$mensaje="<b>Tu anuncio no esta publicado.</b>";
					}
					if($_POST['boton']=="eliminar"){
						$mensaje=str_replace('<<ANUNCIO_TITULO>>',$anuncio->getTitulo(),$mensaje);
					}
					
				}elseif($usuario->estaLogueado()){
		   			if (!empty($_REQUEST['accion'])){
						switch ($_REQUEST['accion']){
							case 'eliminar':
								if ($usuario->esMiAnuncio($adid,$catid,$usuario->getUsuarioId())){
										$usuario->eliminarAnuncio($adid,$catid);
								}
								$anuncio->eliminarAnuncio($adid,$catid);
								$mensaje='<b>Tu anuncio ha sido eliminado.</b><br />Gracias por usar clasilistados.';
								break;
							default:
								break;
						}
					}
					$anuncio->cargarAnuncio($adid,$catid);
					$miAnuncio=$usuario->cargarMiAnuncio($adid,$catid);
					$color=$usuario->getColorAnuncio($miAnuncio['estado']);
				}else{
					$h->ir($h->getMiCuentaLink().'?ir='.$h->getAdminAnuncioLinkUsuario($adid,$catid));
				}
   			}else{
   				$publicacion=new Publicacion($registro);
   				$haySuscripcionCategoria=(($publicacion->esCategoriaPaga($catid,'suscripcion')) && ( $publicacion->deboCobrarEstaUbicacion($catid,null,'suscripcion')));	
   				//se esta republicando....
   				$anuncio->cargarAnuncio($adid,$catid,$_REQUEST['c']);
   				if (!$haySuscripcionCategoria){
	   				if ($anuncio->republicar($adid,$catid)){
	   					$mensaje='<b>Tu anuncio ha sido re-publicado.</b><br />';
	   					$color=COLOR_ADMIN_ACTIVO;
	   				}else{
	   					//fue borrado o fue spam
	   					$mensaje='<b>Tu anuncio no puede ser re-publicado ya que fue eliminado.</b><br />';
	   					$color=COLOR_ADMIN_BORRADO_POR_MI;
	   				}
   				}else{
   					$mensaje='<b>Debes hacer clic en "re-publicar" para continuar.</b> <p>Si lo deseas, despues de re-publicar podras editar el anuncio.</p>';
	   				$color=COLOR_ADMIN_PENDIENTE;
   				}
   			}
			if ($_REQUEST['accion']=="editado"){
				$mensaje='<b>Tu anuncio ha sido modificado.</b><br />';
			}
			break;
		case FORMPOSTING_PAGINA:
			ob_start();
			$locacion=new Locacion($_GET["ciudad"],$_GET["estado"]);
			$location=$locacion->getLocation();	
			$categoria=new Categoria();
			$anuncio=new Anuncio($registro,$t_anuncios);
			$imagenes=new Imagenes($registro);
			$captcha=new Captcha();
			$usuario=new Usuario($registro);
			$publicacion=new Publicacion($registro);
			$registro->set("usuario",$usuario);
			$registro->set("captcha",$captcha);
			$registro->set("imagenes",$imagenes);
			$registro->set("categoria",$categoria);
			$registro->set("anuncio",$anuncio);
			$registro->set("locacion",$locacion);
			$registro->set("publicacion",$publicacion);
			if (strlen($_GET['opciones'])>0){
				$opcionesPubl=explode('_',$_GET['opciones']);
				foreach ($opcionesPubl as $optP){
					$oparams=explode('-',$optP);
					$i=0;
					foreach ($oparams as $oparam){
						if ($i%2==0){
							switch ($oparam){
								case 'cat':
									$_GET['cat']=$oparams[$i+1];
									$categoria->setCategoriaId($oparams[$i+1]);
									break;
								case 's':
								   	$categoria= new Categoria();
				         			$categoria->getSubCategoriaData($oparams[$i+1]);
				         			$registro->set("categoria",$categoria);
				         			$subcategoriasElegidas[]=array ("nombre"=>$categoria->getSubCategoriaNombre(), "id"=>$categoria->getSubCategoriaId(), "catid"=>$categoria->getCategoriaId(),"precio"=>$publicacion->getPrecio($categoria->getCategoriaId()));
									
				         			$parametrosSubcategoria[]='s-'.$oparams[$i+1];
									$_REQUEST['subcat']=$oparams[$i+1];
				         		default:
									break;
							}
						}
						$i++;
					}
				}
			}
			$esCategoriaPaga=(($publicacion->esCategoriaPaga($categoria->getCategoriaId())) && ( $publicacion->deboCobrarEstaUbicacion($categoria->getCategoriaId())));
			$esSubCategoriaPaga=(($publicacion->esSubCategoriaPaga($categoria->getCategoriaId(),$_REQUEST['subcat'])) && ( $publicacion->deboCobrarEstaUbicacion($categoria->getCategoriaId(),$_REQUEST['subcat'])));
			$hayDestacado=(!empty($_POST['destacado_1']) || (!empty($_POST['destacado_2'])));
			$haySuscripcionCategoria=(($publicacion->esCategoriaPaga($categoria->getCategoriaId(),'suscripcion')) && ( $publicacion->deboCobrarEstaUbicacion($categoria->getCategoriaId(),null,'suscripcion')));
			$haySuscripcionSubCategoria=(($publicacion->esSubCategoriaPaga($categoria->getCategoriaId(),$_REQUEST['subcat'],'suscripcion')) && ( $publicacion->deboCobrarEstaUbicacion($categoria->getCategoriaId(),$_REQUEST['subcat'],'suscripcion')));
			$suscripcion=new Suscripcion($registro);
			$registro->set("suscripcion",$suscripcion);
			if ($anuncio->pulsoEditar()){
				//sino a editar el anuncio, desincripto los valores.
				$anuncio->obtenerCamposDecrypt();
				//$categoria->getSubCategoriaData($_REQUEST['subcat']);
				//tengo que cargar asi sabe si hay imagenes habilitadas para esa categoria
				$anuncio->validarPublicacion();
				$anuncio->setDescripcion($_POST['descripcion2']);
			}
			if($anuncio->pulsoContinuar()){
				$descripcion2=$_POST['descripcion'];
				//porque validar publicacion modifica el post de descripcion
			}
			if (isset($_POST['val']) && !$esCategoriaPaga && !$esSubCategoriaPaga && !$hayDestacado){
				$captcha_1 = rand(0,9);
      			$captcha_2 = rand(0,9);
      			$noEncriptarCampos=array("captcha","recaptcha_response_field","recaptcha_challenge_field","subcat");
      			$anuncio->obtenerCamposDecrypt($noEncriptarCampos);			
      			array_walk($_POST, 'limpia');
      			$intentos=$_POST['intentos'];
      			if ( ( $anuncio->pulsoAceptar() || ($anuncio->pulsoRechazo() ) ) && ($anuncio->validarTermCondiciones())  && ($anuncio->validarCaptcha($intentos)) ){	
					$anuncio->setDebeValidarse(true);
					//la informacion de la categoria se tiene que cargar
					if ($anuncio->validarPublicacion()){
						$categoria->getSubCategoriaData($_REQUEST['subcat']);
						$registro->set('categoria',$categoria);
						$idCodigoSeg=$anuncio->insertarAnuncio();
						break;
					}
				}
				else{
					$_POST['intentos']=$intentos+1;
					//$_REQUEST['subcat']=$registro->get("crypt")->decrypt($_REQUEST['subcat']);
				}
			}
			
			if (isset($_POST['val']) && ($esCategoriaPaga || $esSubCategoriaPaga || $hayDestacado)){
				$captcha_1 = rand(0,9);
      			$captcha_2 = rand(0,9);
      			$validoCaptchaCondiciones=false;
      			$noEncriptarCampos=array("captcha","recaptcha_response_field","recaptcha_challenge_field","opciones");
      			if (empty($_POST['credit_card_form'])){
      				$anuncio->obtenerCamposDecrypt($noEncriptarCampos);
      			}
      			$anuncio->setTitulo($_POST['titulo']);
      			array_walk($_POST, 'limpia');
      			$intentos=$_POST['intentos'];
      			if ( ( $anuncio->pulsoAceptar() || ($anuncio->pulsoRechazo() ) ) && ($anuncio->validarTermCondiciones())  && ($anuncio->validarCaptcha($intentos)) ){	
					$validoCaptchaCondiciones=true;
				}
				else{
					$_POST['intentos']=$intentos+1;
					//$_REQUEST['subcat']=$registro->get("crypt")->decrypt($_REQUEST['subcat']);
				}
			}
   			$resultpago=false;
			//$categoria->getSubCategoriaData($_REQUEST['subcat']); //le pasa el id de la subcategoria
   			if (!empty($_GET['suscribirme'])){
   				$_POST['suscribirme']=$_GET['suscribirme'];
   			}
			if (!empty($_POST[$crypt->encrypt('credit_card_form')])){
   				$_POST=$crypt->decryptArrayKeys($_POST);
				//las claves estan encriptadas
			}
			if (($_POST['nosconocio']==NOSCONOCIO_BUSTOS) || ($registro->get("cookie")->get("source")==NOSCONOCIO_BUSTOS)){
				$_POST['codigo_prom']='BUSTOS';
			}
			if (($esCategoriaPaga || $esSubCategoriaPaga || $hayDestacado) && !empty($_POST['credit_card_form']) && empty($_POST['suscribirme'])){
				if(!empty($_POST['credit_card_form']) && isset($_POST['val'])){
					$resultpago=$publicacion->pagar($_POST);
					if($resultpago && ($_REQUEST['accion']!='editar')){
						foreach ($subcategoriasElegidas as $subcatData){
							$anuncio->setDebeValidarse(true);
							if ($anuncio->validarPublicacion()){
								$categoria->getSubCategoriaData($subcatData['id']);
								$registro->set('categoria',$categoria);
								$anuncio->setRegistro($registro);
								$anuncio->setValores(array());
								$idCodigoSeg=$anuncio->insertarAnuncio();
								$publicacion->registrarPago($_POST,$idCodigoSeg['id']);
							}
						}
					}
				}
			}elseif ($haySuscripcionCategoria && (!empty($_POST['suscribirme']) || $suscripcion->usuarioTieneSuscripcion())){
					if(!empty($_POST['credit_card_form']) && isset($_POST['val'])){
						$resultpago=$publicacion->pagar($_POST);
					}
					if ($resultpago){
						$suscripcionExitosa=$suscripcion->nueva($categoria->getCategoriaId(),$usuario->getUsuarioId());
						if ($suscripcionExitosa){
							$suscripcion->registrarPago($_POST);
						}	
					}
	         			
					if ($validoCaptchaCondiciones && is_array($subcategoriasElegidas)){
						foreach ($subcategoriasElegidas as $subcatData){
							$categoria=new Categoria();
							$registro->set('categoria',$categoria);
							$anuncio=new Anuncio($registro,$t_anuncios);
							$registro->set('anuncio',$anuncio);
							$anuncio->setDebeValidarse(true);
							if ($anuncio->validarPublicacion()){
								$categoria->getSubCategoriaData($subcatData['id']);
								$idCodigoSeg=$anuncio->insertarAnuncio();
							}
						}	
					}
	         			
			}
			if ($_REQUEST['accion']=="reenviar"){
				$adid=$_REQUEST['id'];
				//$catid=$_POST['categoria_id'];
				$catid=$categoria->getCategoriaId();
				if ( empty($_POST['continuar']) && (!$anuncio->pulsoEditar()) ){
					$anuncio->cargarAnuncio($adid,$catid);
					$anuncio->setDescripcion($h->br2nl($anuncio->getDescripcion()));
					$anuncio->setDescripcion($h->eliminoHTMLEnlaces($anuncio->getDescripcion()));
					$anuncio->setDescripcion(strip_tags($anuncio->getDescripcion()));
				}
				$subcat=$categoria->getSubCategoriaId();
				if (!isset($_POST['val']) && $subcat!=$_POST['subcat']){
					$subcat=$_POST['subcat'];
					$categoria->getSubcategoriaData($subcat);
					unset($subcategoriasElegidas);
					$subcategoriasElegidas[]=array ("nombre"=>$categoria->getSubCategoriaNombre(), "id"=>$categoria->getSubCategoriaId(), "catid"=>$categoria->getCategoriaId(),"precio"=>$publicacion->getPrecio($categoria->getCategoriaId()));
				}
			//$categoria->setSubCategoriaId($subcat);
			}elseif($_REQUEST['accion']=="editar"){
				$adid=$_REQUEST['id'];
				if (empty($adid)){
   					$adid=$_POST['id'];
				}
				$catid=$categoria->getCategoriaId();
				if ($anuncio->puedoModifAnuncio($adid,$catid)){
					if (empty($_POST['continuar'])){
						$anuncio->cargarAnuncio($adid,$catid);
						$anuncio->setDescripcion($h->br2nl($anuncio->getDescripcion()));
						$anuncio->setDescripcion($h->eliminoHTMLEnlaces($anuncio->getDescripcion()));
						$anuncio->setDescripcion(strip_tags($anuncio->getDescripcion()));
					}elseif ((!$hayDestacado && $anuncio->validarPublicacion()) || ($hayDestacado && $resultpago  && $anuncio->validarPublicacion())){
						$anuncio->setFecha($crypt->decrypt($anuncio->getFechaHoraPublicacion()));
						$categoria->SetSubcategoriaId($_POST['subcat']);
						$anuncio->setUrgente($_POST['destacado_2']);
						$anuncio->setDestacado($_POST['destacado_1']);
						if ($anuncio->actualizarAnuncio($adid,$catid)){
							if ($hayDestacado && $resultpago){
								$publicacion->registrarPago($_POST,$adid);
							}
							if(empty($_REQUEST['c'])){
								$c=$_POST['c'];
							}else{
								$c=$_REQUEST['c'];
							}
							if ($usuario->estaLogueado()){
								$h->ir($h->getAdminAnuncioLinkUsuario($adid,$catid).'?accion=editado');
							}else{
								$h->ir($h->getAdminAnuncioLink($adid,$c,$catid).'?accion=editado');
							}
						}
						else{
							$anuncio->setErrores("No es posible editar el anuncio. Por favor, intente de nuevo.");
						}
					}
					$fechaHora=(!empty($_POST['fechaHora']))?$_POST['fechaHora']:$crypt->encrypt($anuncio->getFechaHoraPublicacion());
					$codSeguridad=(!empty($_POST['c']))?$_POST['c']:$anuncio->getCod_seguridad();
   				}
			}
			if (($esCategoriaPaga || $esSubCategoriaPaga) && empty($_POST['suscribirme'])){
				if(!empty($_POST['subcategoriaPublicacion'])){ 
					$parametros='';
					foreach ($_POST['subcategoriaPublicacion'] as $subcategoriaId){
         				$categoria= new Categoria();
	         			$categoria->getSubCategoriaData($subcategoriaId);
	         			$registro->set("categoria",$categoria);
	         			$subcategoriasElegidas[]=array ("nombre"=>$categoria->getSubCategoriaNombre(), "id"=>$categoria->getSubCategoriaId(), "catid"=>$categoria->getCategoriaId(),"precio"=>$publicacion->getPrecio($categoria->getCategoriaId()));
						$parametrosSubcategoria[]='s-'.$subcategoriaId;
						$_REQUEST['subcat']=$subcategoriaId;
					}
				}		
			}
			ob_end_flush();
			break;	
		case LISTADOS:
				$ciudad=null;
				$estado=null;
				if (!is_null($_GET["ciudad"])){
					$ciudad=$_GET["ciudad"];
				}else{
					if (!is_null ($cookie->get("ciudad"))){
						$ciudad=$cookie->get("ciudad");
					}
				}
				if (!is_null($_GET["estado"])){
					$estado=$_GET["estado"];
				}else{
					if (!is_null ($cookie->get("estado")) && is_null($ciudad)){
						$estado=$cookie->get("estado");
					}
				}
				$urgente=false;
				if ($_GET['urg']=='1'){
					$urgente=true;
				}
				$locacion=new Locacion($ciudad,$estado);
				$location=$locacion->getLocation();					
				$categoria=new Categoria();
				$listados=new Listados($registro);
				$imagenes=new Imagenes($registro);
				$registro->set('categoria',$categoria);
				$registro->set('listados',$listados);
				$registro->set('imagenes',$imagenes);
				$registro->set('locacion',$locacion);
				$listados->getMaketimeBusqueda();
				$fechaHoraBusqueda=$listados->getFechaHoraBusqueda();
				
				if (strpos($_GET['cat'],'---')){
					$catData=split('---',$_GET['cat']);
					$categoria->setCategoriaId($catData[0]);
					$subcategoria_id=$catData[1];
					$categoria->setSubCategoriaId($subcategoria_id);
					$categoria->getSubCategoriaData($subcategoria_id);
				 	$_GET['cat']=$catData[0];
				 	$_GET['subcat']=$catData[1];
				}
				$categoria_id=$_GET['cat'];
   				$subcategoria_id=$_GET['subcat'];
				$categoria->setCategoriaId($categoria_id);
			   	if (!$registro->get('categoria')->esCategoriaHabilitada($categoria_id)){
						$registro->get('helper')->irHome();
				}
				$mostrarRss=true;
				buscador::getInstance()->conectarse($registro);
				
				if (empty($_GET['ordenar'])){
					buscador::getInstance()->setOrdenarPor('rel');
				}else{
					buscador::getInstance()->setOrdenarPor($_GET['ordenar']);
				}
   				if (!is_null($subcategoria_id)){
					$categoria->getSubCategoriaData($subcategoria_id); //le pasa el id de la subcategoria
					$categoria_nombre=$categoria->getSubCategoriaNombre();
					$categoriaBuscada=$categoria_nombre;
					$categoriaSeo=$categoria->getSubCategoriaNombre();
					//$categoriaSeo=$categoria->getSubCategoriaNombre().' ('.$categoria->getCategoriaNombre().')';
					buscador::getInstance()->setFiltros(array('subcatid'=>$subcategoria_id));
				}
				else
				{
					$categoria_nombre=$categoria->getCategoriaNombre();
					$categoriaBuscada='todo '.$categoria_nombre;
					//$categoriaSeo=$categoria_nombre;
					$categoriaSeo=$categoria->getCategoriaNombre();
					
				}
				if ($urgente){
					buscador::getInstance()->setUrgente($urgente);
					buscador::getInstance()->setFiltros(array('urgente'=>1));
				}
				if (buscador::getInstance()->estaBuscando()){
					//buscador::getInstance()->setMatchMode(SPH_MATCH_EXTENDED2);
					if ($_GET['busqTipo']=='busq_tit'){
						buscador::getInstance()->buscarPorCampo('titulo');
					}
					if ($_GET['buscarPorPais']=='1'){
						buscador::getInstance()->setFiltrarPorEstado(false);
						buscador::getInstance()->setFiltrarPorCiudad(false);
					}
					if ($categoria->esVivienda()){
						if (!empty($_GET['perros_ok'])){
							buscador::getInstance()->setFiltros(array('perros_ok'=>'1'));
						}
						if (!empty($_GET['gatos_ok'])){
							buscador::getInstance()->setFiltros(array('gatos_ok'=>'1'));
						}
					}
					if ($categoria->esEmpleo()){
						if (!empty($_GET['teletrabajo'])){
							buscador::getInstance()->setFiltros(array('teletrabajo'=>1));
						}
						if (!empty($_GET['contrato'])){
							buscador::getInstance()->setFiltros(array('contrato'=>1));
						}
						if (!empty($_GET['pasantia'])){
							buscador::getInstance()->setFiltros(array('pasantia'=>1));
						}
						if (!empty($_GET['tiempo_parcial'])){
							buscador::getInstance()->setFiltros(array('tiempo_parcial'=>1));
						}
						if (!empty($_GET['org_sinlucro'])){
							buscador::getInstance()->setFiltros(array('org_sinlucro'=>1));
						}
					}
					if (!empty($_GET['busqueda'])){
						$categoriaSeo=$_GET['busqueda'];
					}
					
					buscador::getInstance()->setQuery($_GET['busqueda']);
					$mostrarRss=false;
				}
				if ($categoria->esEvento()){
					$listados->setEsEvento(true);
					if (empty($_GET['fecha'])){
						$fecha=date("Y")."-".date("n")."-".date("d");
					}else{
						$fecha=$_GET['fecha'];
					}
					$listados->setFechaEvento($fecha);
					//$mktimeEventoBusq=$listados->getMkTimeBusquedaEvento();
					//buscador::getInstance()->SetRangoFiltro(array("fechaInicio",$fecha));
					//buscador::getInstance()->SetRangoFiltro(array("fechaFin",$fecha));
					buscador::getInstance()->setFechaEvento($fecha);
				  	//buscador::getInstance()->SetFilterRange ( "fechaInicio", (int)0, (int)$mktimeEventoBusq);
					//buscador::getInstance()->SetFilterRange ( "fechaFin", (int)$mktimeEventoBusq, 2000000000);
					$categoriaSeo=$fecha.' - '.$categoriaSeo;
					$anuncios=buscador::getInstance()->getTitulos();
					if (!$anuncios){
						buscador::getInstance()->setOrdenarPor('fecha');
						// si no hay anuncios , busco los anuncios en todo el pais en esa categoria
						buscador::getInstance()->setFiltrarPorLocacion(false);
						$anuncios=buscador::getInstance()->getTitulos();
					}
					
				}else{
					if ($categoria->esCompraVenta() || $categoria->esVehiculos() || $categoria->esVivienda()){
						$precio_min=$_GET['precio_min'];
						$precio_max=$_GET['precio_max'];
						if ($precio_min=='min'){
							unset($precio_min);
						}
						if ($precio_max=='max'){
							unset($precio_max);
						}
						if (!empty($precio_min) || (!empty($precio_max))){
							if (empty($precio_min)){
								$precio_min=0;
							}
							if (empty($precio_max)){
								$precio_max=99999999;
							}
							if (!empty($precio_min) && (!empty($precio_max))){
								if ($precio_min>$precio_max){
									$precio_min=$precio_max;
								}
							}
							buscador::getInstance()->SetRangoFiltro(array("precio",(int)$precio_min,(int)$precio_max));
						}
						if ($_GET['ordenar']=='menor_precio'){
							buscador::getInstance()->setOrdenarPor('menor_precio');
						}
						if ($_GET['ordenar']=='mayor_precio'){
							buscador::getInstance()->setOrdenarPor('mayor_precio');
						}
					}
					//buscador::getInstance()->SetRangoFiltro(array("vencimiento"=>array(time(),2000000000)));
					//que este entre ahora y el infinito.
					/*if (empty($_GET['ordenar']) || $_GET['ordenar']=='rel' || $_GET['ordenar']=='fecha'){
						buscador::getInstance()->setOrdenarPor('fechahora','desc');
					}*/	
					$pagina=$_GET['p'];
					if(!is_null($pagina)){
						buscador::getInstance()->setPagina($pagina);		
					}
					if ($_GET['busq_img']=='1'){
						buscador::getInstance()->soloAnunciosConImagenes(true);
					}else{
						buscador::getInstance()->soloAnunciosConImagenes(false);
					}
					$anuncios=buscador::getInstance()->getTitulos();
					$ordenarPor=buscador::getInstance()->getOrdenarPor();
					$encontrados=buscador::getInstance()->getAnunciosEncontrados();
					if (empty($_GET['busqueda']) && ($ordenarPor=='rel') && ($encontrados<LIMITE_POR_PAGINA)){
						buscador::getInstance()->setOrdenarPor('fecha');
						$anuncios=$anuncios.buscador::getInstance()->getTitulos();
						$encontrados=buscador::getInstance()->getAnunciosEncontrados()+$encontrados;
						if (!$anuncios){
							// si no hay anuncios , busco los anuncios en todo el pais en esa categoria
							buscador::getInstance()->setFiltrarPorLocacion(false);
							$anuncios=buscador::getInstance()->getTitulos();
						}
					}
				}
				$locacion=new Locacion($ciudad,$estado);
				$publicacion=new Publicacion($registro);
				$registro->set("publicacion",$publicacion);
				$haySuscripcionCategoria=(($publicacion->esCategoriaPaga($categoria->getCategoriaId(),'suscripcion')) && ( $publicacion->deboCobrarEstaUbicacion($categoria->getCategoriaId(),null,'suscripcion')));
				
			break;			
		case ANUNCIO_PAGINA:
				$ciudad=null;
				$estado=null;
   				if (!is_null($_GET["estado"])){
					$estado=$_GET["estado"];
				}
				if (!is_null($_GET["ciudad"])){
					$ciudad=$_GET["ciudad"];
					$estado=null;
				}
				$locacion=new Locacion($ciudad,$estado);
				$categoria_id=$_GET['cat'];
				$anuncio_id=$_GET['id'];
				$categoria=new Categoria();
				$categoria->setCategoriaId($categoria_id);
				$anuncio=new Anuncio($registro,$t_anuncios);
				$imagenes=new Imagenes($registro);
				$registro->set("imagenes",$imagenes);
				$registro->set("categoria",$categoria);
				$registro->set("anuncio",$anuncio);
				$registro->set("locacion",$locacion);
				$anunciosRelacionados=false;
				if (!$anuncio->cargarAnuncio($anuncio_id,$categoria_id,null,false,ANUNCIO_HABILITADO)){
					$anunciosRelacionados=true;
					$campos=array('titulo','subcatid','cityid','stateid');
					$row=$anuncio->cargarAnuncioPorCampos($anuncio_id,$categoria_id,$campos);
					if (!$row){
						$subcatid=$_GET['subcat'];
					}else{
						$subcatid=$row['subcatid'];
					}
					$categoria->getSubCategoriaData($subcatid);
					if (!is_null($row['cityid'])){
						$registro->get("locacion")->setCiudadId($row['cityid']);
						$registro->get("locacion")->cargarCiudad();
					}elseif(!is_null($row['stateid'])){
						$registro->get("locacion")->setEstadoId($row['stateid']);
						$registro->get("locacion")->cargarEstado();
					}
					$categoria_nombre=array_search ($categoria_id,$cats);
					$respuestaEnviada=false;
					$location=$locacion->getLocation();		
	   				$subcatNombre=$registro->get("categoria")->getSubCategoriaNombre();
	      			if (!empty($subcatNombre)){
	      				$categoriaSeo=$subcatNombre;
	      			}else{
	      				$categoriaSeo=array_search ($categoria_id,$cats);
	      			}
	      			break;
				}
				$categoria_nombre=array_search ($categoria_id,$cats);
				$respuestaEnviada=false;
				$location=$locacion->getLocation();		
   				$subcatNombre=$registro->get("categoria")->getSubCategoriaNombre();
      			if (!empty($subcatNombre)){
      				$categoriaSeo=$subcatNombre;
      			}else{
      				$categoriaSeo=array_search ($categoria_id,$cats);
      			}
				if (!empty($_POST['submit'])){
					$errores=array();
					$captchaSuma=intval($crypt->decrypt($_POST['c1']))+intval($crypt->decrypt($_POST['c2']));
					if ($captchaSuma==intval($_POST['captcha'])){
						if ($registro->get("email")->validar($_POST['email'])){
							$anuncio->responderAnuncio($_POST['email'],strip_tags($_POST['comentarios'],$tags_permitidos),$t_respuestas_anuncios);
						}
						else{
							$errores[]="Debes escribir un email valido";
						}
					}else{
						$errores[]="La suma de los numeros no es la correcta, vuelve a intentarlo.";
					}
					if (count($errores)==0){
						$respuestaEnviada=true;
					}
				}
				
				$comentariosAutoComplete='Hola, te escribo por el anuncio que publicaste el '.$h->getFechaDiaMesAnioLetras($anuncio->getMaketime()).' sobre "'.strtoupper($anuncio->getTitulo()).'"';
				$captcha_1 = rand(0,9);
      			$captcha_2 = rand(0,9);
      			
				$mensajeResp='';
				if (!empty($_POST['submit'])){
					if ($respuestaEnviada){
						$mensajeResp.= '<br /><div class="exitoRespuesta"><span style="font-size:12px;  font-weight:bold;"><b>Haz contestado exitosamente a este anuncio</b></span></div><br />';
					}elseif (isset($errores)){
							$mensajeResp.= '<br /><div class="errorRespuesta"><span style="font-weight:bold;">Por favor corrije lo siguiente: </span><br /><ul>';
						foreach ($errores as $error){
							$mensajeResp.= '<li><span>'.$error.'</span></li>';
						}
						$mensajeResp.= '</ul></div><br />';
					}else{
						$mensajeResp.= '<br />';
					}
				}
			break;
		case FLAG:
				$categoria_id=intval($_POST['categoria_id']);
				$tipo=intval($_POST['flag_tipo']);
				$adid=intval($_POST['id']);
				if ( !empty($categoria_id) && !empty($tipo) && !empty($adid) ){
					$anuncio=new Anuncio($registro,$t_anuncios);
					$anuncio->marcarAnuncio($tipo,$adid,$categoria_id,$t_flags);
				}
				echo "¡Gracias por marcar el anuncio!";
			break;
		case ENVIAAMIGO:
				$categoria_id=intval($_GET['catid']);
				$adid=intval($_GET['id']);
				$anuncio=new Anuncio($registro,$t_anuncios);
				$imagenes=new Imagenes($registro);
				$registro->set("imagenes",$imagenes);
				$registro->set("anuncio",$anuncio);;
				$fueEnviado=false;
				$urlAnuncio=$_SERVER['HTTP_REFERER'];
				if (!empty($_POST['submit'])){
					$categoria=new Categoria();
					$registro->set("categoria",$categoria);
					$categoria_id=intval($_POST['catid']);
					$adid=intval($_POST['adid']);
					$urlAnuncio=$_POST['urlAnuncio'];
					$anuncio->cargarAnuncio($adid,$categoria_id,null,true);
					if ( ($registro->get("email")->validar($_POST['email'])) && ($registro->get("email")->validar($_POST['destino'])) ){
						if ($anuncio->enviarAmigo($_POST['email'],$_POST['destino'],$urlAnuncio,$t_envia_amigo)){
							$fueEnviado=true;
						}
					}
				}
			break;
		case CONTACTANOS:
				$locacion=new Locacion();
				$enviarA=array('sop_tecnico_general'=>'soporte tecnico (general)','sop_tecnico_pagos'=>'soporte tecnico (empleos pagos e inmuebles)',
				'sop_tecnico_valtelefonica'=>'soporte tecnico (validación telefonica)','sop_tecnico_eroticos'=>'soporte tecnico (servicios eroticos)',
				'sop_tecnico_funebres'=>'soporte tecnico (avisos funebres)','spam'=>'reporta spam, fraude,  estafa','acoso'=>'acoso personal',
				'cobros'=>'cobros y facturación','prensa'=>'prensa/medios','legales'=>'legales','sugerencias'=>'sugerencias','otro'=>'otro...');
				$errores=array();
				$mensaje="";
				$captcha=new Captcha();
				if (!empty($_POST['submit'])){
					if (array_key_exists($_POST['enviarA'],$enviarA)){
						$tipo_reclamo=$enviarA[$_POST['enviarA']];	
					}else{
						$errores[]="Debes seleccionar un tipo de reclamo (escoge uno solo)";
					}
					$validacionEmail=$registro->get("email")->validarCompararEmail($_POST['email'],$_POST['email2']);
					if (is_array($validacionEmail)){
						$errores=$validacionEmail;
					}
					if (!$captcha->esValido()){
						$errores[]="No has tecleado las palabras de verificación correctamente. Intentalo de nuevo.";
					}
					if (count($errores)<=0){
						$contactanos=new Contactanos($registro->get("email"),$registro);
						$contactanos->generarReclamo($tipo_reclamo,$_POST['enviarA'],$t_reclamos);
						$contactanos->enviarMailFaqContactanos($_POST['email'],$_POST['asunto']);
					}else{
						$mensaje.='<br />Nos falta algo de información.
						<b>Por favor corrige los datos:</b><br />';
						$mensaje.='<ul>';
						foreach ($errores as $error){
							$mensaje.='<li>'.$error.'</li>';
						}
						$mensaje.='</ul>';
					}
					
				}
			break;
		case DONTSPEAKSPANISH:
			$respuesta="";
			  	if (!empty($_POST['submit'])){
			  		$mail=$_POST['email'];
					$nombre=$_POST['name'];
					$mensaje=$_POST['message'];
					$fecha=$h->getFecha(time());
					$contenido=file_get_contents($_SERVER['DOCUMENT_ROOT']."/plantillas/dontspeak.html");
			  		$contenido=str_replace("<<NAME>>",$nombre,$contenido);
			  		$contenido=str_replace("<<MESSAGE>>",$mensaje,$contenido);
			  		$contenido=str_replace("<<EMAIL>>",$mail,$contenido);
			  		$contenido=str_replace("<<FECHA>>",$fecha,$contenido);
			  		$asunto="don't speak Spanish? - mensaje generado el ".$fecha;
			  		if ($registro->get("email")->enviar($asunto,"3@clasilistados.org",$contenido,DONTSPEAKSPANISH)){
			  			$respuesta="The message has been sent";
			  		}else{
			  			$respuesta="The message cannot been sent, please try again";
			  		}
			  		
			  	}
				break;
		case LOMEJOR:
			$listados=new Listados($registro);
			

			break;
		case REINICIARCONTRASENA:
				$usuario=new Usuario($registro);
				$mensajesErrores="";
				$envioExitoso=false;
				if (!empty($_POST['reiniciarPassword'])){
					$errores=array();
					$email=$_POST['email'];
					if (!$registro->get("email")->validar($email)){
						$errores[]='El correo electrónico que has ingresado no es valido.';
					}elseif (!$usuario->cargar($email)){
						$errores[]='No hay ninguna cuenta asociada al correo electrónico que ingresaste. Puedes crear una cuenta  <a href="'.$h->getCrearCuentaHref().'">aquí</a>';
					}
					if (count($errores)==0){
						if (($usuario->cargar($email)) && ($usuario->enviarReiniciarContrasenaLink())){
							$envioExitoso=true;	
						}
					}else{
						foreach ($errores as $error){
							$mensajesErrores.=$error."<br />";
						}
					}
				}
			break;
		case MICUENTA:
				ob_start();
				$ciudad=null;
				$estado=null;
				$mensaje='<p>&nbsp;</p>';
				if (!is_null ($cookie->get("ciudad"))){
					$ciudad=$cookie->get("ciudad");
				}
				if (!is_null ($cookie->get("estado")) && is_null($ciudad)){
					$estado=$cookie->get("estado");
				}
				$locacion=new Locacion($ciudad,$estado);
				$location=$locacion->getLocation();	
				$usuario=new Usuario($registro);
				$pagina=$_SERVER["DOCUMENT_ROOT"]."/cuenta_loginform.php";
   				//login form
				$login = !empty($_POST['login']);
				if ($login){
					$errores=array();
					$email=$_POST['email'];
					$password=$_POST['password'];
					$mensajes=$usuario->login($email,$password);
					
					if (count($mensajes)>0){
						foreach ($mensajes as $mensajeLogin){
							$mensajesHTML.=$mensajeLogin."<br />";
						}
						break;
					}else{
						$titulo='cuenta de '.$email;
						if (!empty($_REQUEST['ir'])){
							$h->ir($_REQUEST['ir']);
						}
					}
				}
				
				//pagina de seteo de contraseña
				$cambiarContrasena= (!empty($_POST['cambiaContrasena'])) || (!empty($_GET['cambiaContrasena']));
				if ($cambiarContrasena){
					$masCamposhidden='';
					$titulo_cambioContrasena='ingresa una nueva contraseña de 6 o mas caracteres, haz clic en enviar y podrás acceder a tu cuenta.';
					$valorSubmitCambioContrasena='enviar';
					$pagina=$_SERVER["DOCUMENT_ROOT"]."/cuenta_cambiarcontrasena.php";
					$id_usuario=$_GET['id'];
					$cod_seguridad=$_GET['cod_seguridad'];
					$email=$_GET['email'];
					if (!empty($_POST['cambiaContrasena'])){
						$id_usuario=$_POST['id'];
						$cod_seguridad=$_POST['cod_seguridad'];
						$email=$_POST['email'];
						$password=$_POST['password'];
						if (strlen($password)<6){
							$errores[]="La contraseña debe tener 6 caracteres o más.";
						}elseif ($password!=$_POST['password2']){
							$errores[]="La contraseña de confirmacion que has escrito no coincide.";
						}elseif ($usuario->cambiarContrasena($password,$id_usuario,$email,$cod_seguridad)){
							$usuario->cargar($email);
							$usuario->entrar();
						}else{
							$errores[]="No tienes los permisos necesarios para modificar la contraseña de esta cuenta.";
						}
						if (count($errores)>0){
							foreach ($errores as $error){
								$mensajesErrores.=$error."<br />";
							}
							break;
						}else{
							if (!empty($_REQUEST['ir'])){
								$h->ir($_REQUEST['ir']);
							}
						}
					}
				}
				//dentro del admin
				if ($usuario->estaLogueado()){
					$titulo='cuenta de '.$usuario->getEmail();
					$pagina=$_SERVER["DOCUMENT_ROOT"]."/cuenta_admin.php";
					$usuario->cargar($usuario->getEmail());
					switch ($_REQUEST['a']){
						case 'salir':
							$usuario->salir();
							$url=(!empty($_GET['ir']))?$_GET['ir']:$h->getMiCuentaLink();
							$h->ir($url);
							break;
						case 'pref':
							$usuario->cargar($usuario->getEmail());
							//TODO: Se tendria que guardar en sesion toda la informacion del usuario
							$pagina=$_SERVER["DOCUMENT_ROOT"]."/cuenta_preferencias.php";
							$titulo='preferencias de '.$usuario->getEmail();
							switch ($_REQUEST['cambia']){
								case 'sesion':
									if ($usuario->cambiarDuracionSesion($_GET['duracionSesion'])){
										$mensaje='<p class="verde">Has cambiado la duración de la sesion</p>';
									}
									break;
								case 'sitio':
									$estado_id=split ('est-',$_GET['ubicacion']);
									$ciudad_id=split ('cit-',$_GET['ubicacion']);
									if ($usuario->cambiarEstadoCiudad($ciudad_id[1],$estado_id[1])){
										$mensaje='<p class="verde">Has cambiado el sitio por defecto</p>';
									}
									break;
								case 'correo':
									$pagina=$_SERVER["DOCUMENT_ROOT"]."/cuenta_cambiarcorreo.php";
									if (!empty($_POST['cambiarCorreo'])){
										$correoNuevo=$_POST['correoNuevo'];
										$correoNuevo2=$_POST['correoNuevo2'];
										$mensaje=$usuario->cambiarCorreo($correoNuevo,$correoNuevo2);
										if (strlen($mensaje)>0){
											$mensaje='<p class="rojo">'.$mensaje.'</p>';
										}else{
											$pagina=$_SERVER["DOCUMENT_ROOT"]."/cuenta_cambiarcorreoexitoso.php";
										}
									}
									break;	
								case 'cambiopassword':
								case 'password':
									if (($cambiarContrasena) && (count($errores)<=0)){
										$pagina=$_SERVER["DOCUMENT_ROOT"]."/cuenta_cambiarcontrasenaexitoso.php";
									}else{
										$cod_seguridad=$usuario->getCodSeguridad();
										$id_usuario=$usuario->getUsuarioId();
										$email=$usuario->getEmail();
										$titulo_cambioContrasena='Has pedido que se te cambie la contraseña. Por favor, introduce tu cambio y asegurate de que tenga al menos 6 caracteres.';
										$valorSubmitCambioContrasena='cambiar contraseña';
										$pagina=$_SERVER["DOCUMENT_ROOT"]."/cuenta_cambiarcontrasena.php";
										$masCamposhidden='<input type="hidden" name="a" value="pref"><input type="hidden" name="cambia" value="cambiopassword">';
									}
									break;
								case '':
									
									break;
								default:
									break;
							}
							break;
						default:
							$hora=date("H");
							$min=date("i");
							$seg=date("s");
							$mktime30diasMenos=calendario::getMkTimeMenosDias(CANT_DIAS_MENOS_LISTADO_USUARIOS);
							$mesDesde=date("n",$mktime30diasMenos);
							$diaDesde=date("j",$mktime30diasMenos);
							$anioDesde=date("Y",$mktime30diasMenos);
							$mesHasta=date("n");
							$diaHasta=date("j");
							$anioHasta=date("Y");
							if (!empty($_REQUEST['buscarAnuncios'])){
								$mesDesde=empty($_REQUEST['mesDesde'])?$mesDesde:$_REQUEST['mesDesde'];
								$diaDesde=empty($_REQUEST['diaDesde'])?$diaDesde:$_REQUEST['diaDesde'];
								$anioDesde=empty($_REQUEST['anioDesde'])?$anioDesde:$_REQUEST['anioDesde'];
								$mesHasta=empty($_REQUEST['mesHasta'])?$mesHasta:$_REQUEST['mesHasta'];
								$diaHasta=empty($_REQUEST['diaHasta'])?$diaHasta:$_REQUEST['diaHasta'];
								$anioHasta=empty($_REQUEST['anioHasta'])?$anioHasta:$_REQUEST['anioHasta'];
							}	
							$fechaMktimeDesde=mktime($hora,$min,$seg,$mesDesde, $diaDesde,$anioDesde);
							$fechaMktimeHasta=mktime($hora,$min,$seg, $mesHasta, $diaHasta,$anioHasta);		
							$html_anuncios_usuarios=$usuario->misAnuncios($registro->get("session")->get("usuario_id"),$fechaMktimeDesde,$fechaMktimeHasta);
						break;
					}
				}
			ob_end_flush();
			break;
		case CREARCUENTA:
   				$ciudad=null;
				$estado=null;
				if (!is_null ($cookie->get("ciudad"))){
					$ciudad=$cookie->get("ciudad");
				}
				if (!is_null ($cookie->get("estado")) && is_null($ciudad)){
					$estado=$cookie->get("estado");
				}
				$mensajesErrores="";
				$captcha=new Captcha();
				$locacion=new Locacion($ciudad,$estado);
				$location=$locacion->getLocation();	
				$registro->set("locacion",$locacion);
				$registro->set("captcha",$captcha);
				$usuario=new Usuario($registro);
				$ciudad_id=$locacion->getCiudadId();
				$state_id=$locacion->getEstadoId();
				$usuarioCreado=false;
				if (!empty($_POST['submit'])){
					$email=$_POST['email'];
					$errores=$usuario->validarNuevo($email);
					if (count($errores)<=0){
						$usuario_id=$usuario->crear($email,$_POST['city_id'],$_POST['state_id']);
						if ($usuario_id){
							$usuario->enviarActivacion($email,$usuario->getCodSeguridad(),$usuario_id);
							$usuarioCreado=true;
						}
					}else{
						foreach ($errores as $error){
							$mensajesErrores.=$error."<br />";
						}
					}
				}
			break;
		case ASOCIARSE:
			break;
		case FUENTES:
			$email=new Email($registro,$t_envios, $smtp_host,$smtp_auth,$smtp_username,$smtp_password);
			$registro->set("email",$email);
			$categoria=new Categoria();
			$registro->set("categoria",$categoria);
			$catid=empty($_GET['cat'])?$_POST['cat']:$_GET['cat'];
			$categoria->setCategoriaId($catid);
			if (!empty($_POST['submit'])){
				$feed=new Feed($t_feeds,$registro);
				$errores=array();
				if (!$email->validar($_POST['correo'])){
					$errores[]='El correo electrónico que has ingresado no es valido.';
				}
				if (empty($_POST['nombre'])){
					$errores[]='Debes ingresar tu nombre.';
				}
				$url='http://'.$_POST['url_archivo'];
				if (empty($_POST['url_archivo']) || (!$h->validarURL($url))){
					$errores[]='Debes ingresar una URL valida para tu archivo.';
				}
				
				if (count($errores)==0){
					$feed->nuevo($_POST['nombre'],$_POST['empresa'],$_POST['email'],$url,NULL,$_POST['cat'],$_POST['subcat']);
				}
			}
			break;
		case RSS:
			$ciudad=null;
			$estado=null;
			if (!is_null($_GET["ciudad"])){
				$ciudad=$_GET["ciudad"];
			}
			if (!is_null($_GET["estado"])){
				$estado=$_GET["estado"];
			}
			if (!is_null($ciudad)){
				$estado=null;
			}
			$locacion=new Locacion($ciudad,$estado);
			$location=$locacion->getLocation();	
			$categoria=new Categoria();
			$anuncio=new Anuncio($registro,$t_anuncios);
			$registro->set('categoria',$categoria);
			$registro->set('anuncio',$anuncio);
			$imagenes=new Imagenes($registro);
			$registro->set("imagenes",$imagenes);
			$registro->set("locacion",$locacion);
			
			buscador::getInstance()->conectarse($registro);
			
			$categoria_id=$_GET['cat'];
			$subcategoria_id=null;
			if (is_null($_GET['subcat'])){
				$categoria->setCategoriaId($categoria_id);
				$categoria_nombre=array_search ($categoria_id,$cats);
				$categoriaBuscada='todo '.$categoria_nombre;
				$categoriaSeo=$categoria_nombre;
			}else{
				//nunca entra porque solo hay rss de las categorias, no subcategorias
				$subcategoria_id=$_GET['subcat'];
				$categoria->getSubCategoriaData($subcategoria_id); //le pasa el id de la subcategoria
				$categoria_nombre=$categoria->getSubCategoriaNombre();
				$categoriaBuscada=$categoria_nombre;
				$categoriaSeo=$categoria->getSubCategoriaNombre().' - '.array_search ($categoria_id,$cats);
				buscador::getInstance()->setFilter('subcatid',$subcategoria_id);
			}
			
			//$categoria_nombre=array_search ($categoria_id,$cats);
			$listados=new Listados($registro);
			$listados->getMaketimeBusqueda();//genera el fecha y hora de la busqueda del listado
			$fechaHoraBusqueda=$listados->getFechaHoraBusqueda();
			/*
			if ($categoria->esEvento()){
				$mktimeEventoBusq=time();
			  	buscador::getInstance()->SetFilterRange ( "fechaInicio", (int)0, (int)$mktimeEventoBusq);
				buscador::getInstance()->SetFilterRange ( "fechaFin", (int)$mktimeEventoBusq, 2000000000);
			}*/
			buscador::getInstance()->setPagina(1);
			buscador::getInstance()->setOrdenarPor('fecha');
			//siempre muestro la primer pagina, osea los ultimos 100 anuncios en el RSS
			$campos=array('adid','titulo','descripcion','subcatid','fechaHora','lugar');
			$result=buscador::getInstance()->getAnuncios($campos);
			$i=0;
			while ($row = $result->fetch_array()){
				$anuncios[$i]['titulo']=$row['titulo'];
				$anuncios[$i]['descripcion']=$row['descripcion'];
				$anuncios[$i]['fechahora']=$resultado['anuncio'][$row['id']]['fechaHora'];
				$categoria->getSubCategoriaData($row['subcatid']);
				$anuncios[$i]['link']=$h->getAnuncioLink($row['adid'],$row['titulo'],$categoria_id,$categoria->getSubCategoriaId(),$categoria->getSubCategoriaNombre());
				$i++;
			}
			
			break;
	}
	
}
?>