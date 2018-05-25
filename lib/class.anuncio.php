<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/classes/twitter/Twitter.class.php');

class Anuncio 
{
	private $esAlquiler=false;
	private $titulo=null;
	private $precio=null;
	private $lugar=null;
	private $descripcion=null;
	private $camposRojos=array();
	private $errores=array();
	private $alquiler=null;
	private $email=null;
	private $edad=null;
	private $eventoFechaFin=null;
	private $eventoFechaInicio=null;
	private $retribucion=null;
	private $pago=null;
	private $contact_comercial=null;
	private $fechaHora=null;
	private $debeValidarse=false;
	private $valores=array();
	private $teletrabajo;
	private $tiempo_parcial;
	private $contrato;
	private $org_sinlucro;
	private $pasantia;
	private $agencia_busquedas;
	private $recibir_llamados;
	private $id;
	private $maketime;
	private $habilitado;
	private $gatos_ok;
	private $perros_ok;
	private $registro=null;
	private $t_anuncios=null;
	private $usuario_id=null;
	private $cod_seguridad=null;
	private $puedoModificarAnuncio=null;
	private $vencimiento=null;
	private $feed_id=null;
	private $t_feeds_anuncios='cl2_feeds_anuncios';
	private $respuestaPorLink='0';
	private $link=null;
	private $maximoCaracteresTitulo=70;
	private $anuncioConImagenes=false;
	private $diasVencimiento=45;
	private $destacado=0;
	private $urgente=0;
	
	public function __construct(Registro $registro=null,$t_anuncios){
		if (is_null($this->registro)){
			$this->registro=$registro;
		}
		$this->t_anuncios=$t_anuncios;
	}
	public function setValores($v){
		$this->valores=$v;
	}
	public function setRegistro($r){
		$this->registro=$r;
	}
	
	public function getMaximoCaracteresTitulo(){
		return $this->maximoCaracteresTitulo;
	}
	public function getFeedId(){
		return $this->feed_id;
	}
	public function setFeedId($feed_id){
		$this->feed_id=$feed_id;
	}
	public function setId($id){
		$this->id=$id;
	}
	public function getLink(){
		return $this->link;
	}
	public function setVencimiento($venc){
		$this->vencimiento=$venc;
	}
	public function getVencimiento(){
		return $this->vencimiento;
	}
	public function enRojo($campo){
		return (in_array($campo,$this->camposRojos)) ? true : false;
	}
	public function setCod_seguridad($c){
		$this->cod_seguridad=$c;
	}
	public function getCod_seguridad(){
		return $this->cod_seguridad;
	}
	public function setTitulo($titulo){
		 $this->titulo=$titulo;
	}
	public function setLugar($lugar){
		 $this->lugar=$lugar;
	}
	public function setPrecio($precio){
		 $this->precio=$precio;
	}
	public function setAlquiler($alquiler){
		 $this->alquiler=$alquiler;
	}
	public function setEdad($edad){
		 $this->edad=$edad;
	}
	public function getTitulo(){
		return $this->titulo;
	}
	public function getDescripcion(){
		return $this->descripcion;
	}
	public function getPrecio(){
		return $this->precio;
	}
	public function estaHabilitado(){
		if ($this->habilitado==1){
			return true;
		}else{
			return false;
		}
	}
	public function marcarAnuncio ($tipo,$adid,$categoria_id,$t_flags){
		$sql="INSERT INTO ".$t_flags." (flag,adid,catid) VALUES ($tipo, $adid, $categoria_id);";
		$result=$this->registro->get("db")->query($sql);
		if($result){
			return true;
		}else{
			return false;
		}
		
	}
	public function generarCamposAnuncio()
	{
		$categoria=$this->registro->get("categoria");
		$campos=array ("titulo","descripcion","lugar","fechaHora","contact_comercial","subcatid","stateid","cityid","habilitado","email","cod_seguridad");
		if ($categoria->esCompraVenta()){
			array_push($campos,"precio");			
		}
		if ($categoria->esVehiculos()){
			array_push($campos,"precio");			
		}
		if ($categoria->esEmpleo()){
			array_push($campos,"retribucion");	
			array_push($campos,"teletrabajo");	
			array_push($campos,"tiempo_parcial");	
			array_push($campos,"contrato");	
			array_push($campos,"org_sinlucro");	
			array_push($campos,"pasantia");	
			array_push($campos,"agencia_busquedas");	
			array_push($campos,"recibir_llamados");	
		}
		if ($categoria->esEvento()){
			array_push($campos,"fechaInicio");	
			array_push($campos,"fechaFin");	
		}
		if ($categoria->esVivienda()){
			array_push($campos,"precio");	
			array_push($campos,"alquiler");	
			array_push($campos,"gatos_ok");	
			array_push($campos,"perros_ok");	
		}
		if ($categoria->esTTemporal()){
			array_push($campos,"pago");	
		}
		if ($categoria->esPersonales()){
			array_push($campos,"edad");	
		}
		return $campos;	
	}
	
	public function cargarAnuncioPorCampos($id,$catid,$campos){
		$q="SELECT ".implode(",", $campos)." FROM ".$this->t_anuncios.$catid." WHERE adid=$id;";
		$result=db::getInstance()->query($q);
		if (!$result){
			return false;
		}
		$row=$result->fetch_array();
		if ($result->num_rows<=0){
			return false;
		}
		$this->setId($id);
		foreach ($campos as $columna){
			eval("\$this->".addslashes($columna)."='".addslashes($row[$columna])."';");
		}
		return $row;
	}
		
	public function cargarAnuncio($id,$catid,$c=null,$sinLocacion=false,$activo=null){
		$campos=$this->generarCamposAnuncio();
		$habilitado='';
		if(!is_null($c)){
			$habilitado=" AND cod_seguridad='".$c."'";
		}
		if(!is_null($activo)){
			$habilitado.=" AND habilitado='".$activo."'";
		}
		$q="SELECT ".implode(",", $campos)." FROM ".$this->t_anuncios.$catid." WHERE adid=$id $habilitado ;";
		//echo $q;
		$result=$this->registro->get("db")->query($q);
		if (!$result){
			return false;
		}
		$row=$result->fetch_array();
		if (!is_array($row)){
			return false;
		}
		$this->setId($id);
		foreach ($campos as $columna){
			eval("\$this->".addslashes($columna)."='".addslashes($row[$columna])."';");
		}
		if (!$sinLocacion){
			if (!is_null($row['cityid'])){
				$this->registro->get("locacion")->setCiudadId($row['cityid']);
				$this->registro->get("locacion")->cargarCiudad();
			}elseif(!is_null($row['stateid'])){
				$this->registro->get("locacion")->setEstadoId($row['stateid']);
				$this->registro->get("locacion")->cargarEstado();
			}
		}
		$this->registro->get("categoria")->getSubCategoriaData($row['subcatid']);
		$arrayfecha = explode('-', $this->fechaHora);
		$dia=explode(' ', $arrayfecha[2]);
		$horario=explode(':', $dia[1]);
		$maketime = mktime($horario[0],$horario[1],$horario[2], $arrayfecha[1], $dia[0], $arrayfecha[0]);
		$this->setMaketime($maketime);
		if($this->registro->get("imagenes")->cargar($id,$catid)){
			$this->anuncioConImagenes=true;
		}
		return true;
	}
	public function esteAnuncioTieneImagenes(){
		return $this->anuncioConImagenes;
	}
	public function publicarAnuncio($id,$catid,$cod_seguridad=null){
		$validacion='';
		if (!is_null($cod_seguridad)){
			$validacion=" AND cod_seguridad='".$cod_seguridad."'";
		}
		$q="UPDATE ".$this->t_anuncios.$catid." SET habilitado='".ANUNCIO_HABILITADO."' WHERE adid=$id $validacion;";
		$result=$this->registro->get("db")->query($q);
		if ($result){
			$this->enviarTweet($id);
			return true;
		}else{
			return false;
		}
	}
	public function eliminarAnuncio($id,$catid,$cod_seguridad=null){
		$validacion='';
		if (!is_null($cod_seguridad)){
			$validacion=" AND cod_seguridad='".$cod_seguridad."'";
		}
		$q="UPDATE ".$this->t_anuncios.$catid." SET habilitado='0' WHERE adid=".$id." ".$validacion.";";
		$result=$this->registro->get("db")->query($q);
		if ($result){
			return true;
		}else{
			return false;
		}
		
	}
	public function setContactoComercial($cm){
		$this->contact_comercial=$cm;
	}
	public function setDescripcion($desc){
		$this->descripcion=$desc;
	}
	public function setFecha($fechaHora){
		$this->fechaHora=$fechaHora;
	}
	public function getEdad(){
		return $this->edad;
	}
	public function getLugar(){
		return $this->lugar;
	}
	public function getEmail(){
		return $this->email;
	}
	public function getConfirmacionEmail(){
		return $this->getCampo('confirmacion_email');
	}
	public function getCampo($campo){
		if (empty($_POST[$campo])){
			eval ("\$valor=\$this->".$campo.";");
		}else{
			$valor= $_POST[$campo];
		}
		return $valor;
	}
	function soloNumeros($string){
    	$string = preg_replace("/[^0-9]/", "", $string);
    	$num=@(int)$string;
		if ($num==0){
			return '';
		}
    	return $num;
	} 
	public function getTiempoPublicacion(){
		$this->maketime=mktime(date("H"),date("i"),date("s"), date("m"), date("d"),date("Y"));
	}
	public function getMaketime(){
		return $this->maketime;
	}
	public function setMaketime($maketime){
		$this->maketime=$maketime;
	}
	public function getFechaInsertarAnuncio(){
		return date( 'Y-m-d H:i:s', $this->maketime );
		//return date("Y",$this->maketime)."-".date("m",$this->maketime)."-".date("d",$this->maketime)." ".date("h:i:s",$this->maketime);
	}
	
	function excedeMaxDigitos($num,$max) {
		if (strlen(strval(intval($num)))>$max){
			return true;
		}else{
			return false;
		}
  		
	}
	public function getAlquiler(){
		return $this->alquiler;
	}
	public function validarPrecio(){
		$this->precio=$this->getCampo('precio');
		if (strlen($this->precio)>0){
			$this->precio=filter_var($this->precio, FILTER_VALIDATE_FLOAT, array("options" => array("min_range"=>PRECIO_MINIMO, "max_range"=>PRECIO_MAXIMO)));       
			if($this->precio==false){
				$this->errores[]="El precio debe ser un numero mayor a cero.";
			}
		}
		
	}
	public function validarCamposCompraVenta(){
		$this->validarPrecio();
	}
	public function validarCamposVehiculos(){
		$this->validarPrecio();
	}
	public function hayErroresPublicacion(){
		if (count($this->errores)>0){
			return true;
		}
		else{
			return false;
		}
	}
	public function tieneRespuestaViaEnlace(){
		if ($this->respuestaPorLink=='0'){
			return false;
		}else{
			return true;
		}
	}
	public function mostrarErrores(){
		if ($this->hayErroresPublicacion()){
			$html= 'Nos falta algo de información. ';
			$html.='Por favor, corrige los campos marcados en <b style="color:red;">rojo</b>: <br />';
			$html.='<ul>';
			foreach ($this->errores as $error){
				$html.='<li>'.$error.'</li>';
			}
			$html.='</ul>';
			return $html;
		
		}else{
			return '';
		}
	}
	public function validarEmail($email,$emailconf){
		if ($this->validarLongitudMayorCero($email)){
			if ($email==$emailconf){
				if (!$this->registro->get("email")->validar($email)){
					$this->errores[]="El email que has escrito es incorrecto.";
					$this->camposRojos[]='email';
				}
			}else{
				$this->errores[]="El email de confirmacion que has escrito no coincide.";
				$this->camposRojos[]='email';
			}
		}else{
			$this->errores[]="Debes escribir un email valido para que te puedan contactar por tu anuncio.";
			$this->camposRojos[]='email';
		}
	}
	public function validarTitulo($titulo){
		if (!$this->validarMaxLongitud($titulo,$this->maximoCaracteresTitulo)){
			$this->errores[]="Debes escribir un titulo para tu anuncio de no mas de 70 caracteres.";
			$this->camposRojos[]='titulo';
		}
		if ($this->validarMaxLongitud($titulo,3)){
			$this->errores[]="Debes escribir un titulo para tu anuncio de mas de 3 caracteres.";
			$this->camposRojos[]='titulo';
		}
	}
	public function setDebeValidarse($bool){
		$this->debeValidarse=$bool;
	}
	public function debeValidarse(){
		return $this->debeValidarse;
	}
	public function fueValidado(){
		if ((strlen($this->getCampo('val'))>0) && (!$this->debeValidarse())){
			return true;
			//ya esta validado, sino iria a validar los campos encriptados y daria error!
		}
		else{
			return false;
		}
	}
	public function validarCamposEmpleo(){
		$this->retribucion=$this->getCampo('retribucion');
		if (!$this->validarLongitudMayorCero($this->retribucion)){
			$this->errores[]="Debes escribir la retribucion del empleo.";
			$this->camposRojos[]='retribucion';
		}			
		$this->teletrabajo=$this->getCampo("teletrabajo");
		if (isset($this->teletrabajo)){
			$this->teletrabajo=1;
		}else{
			$this->teletrabajo=0;
		}
		$this->tiempo_parcial=$this->getCampo("tiempo_parcial");
		if (isset($this->tiempo_parcial)){
			$this->tiempo_parcial=1;
		}else{
			$this->tiempo_parcial=0;
		}
		$this->contrato=$this->getCampo("contrato");
		if (isset($this->contrato)){
			$this->contrato=1;
		}else{
			$this->contrato=0;
		}
		$this->org_sinlucro=$this->getCampo("org_sinlucro");
		if (isset($this->org_sinlucro)){
			$this->org_sinlucro=1;
		}else{
			$this->org_sinlucro=0;
		}
		$this->pasantia=$this->getCampo("pasantia");
		if (isset($this->pasantia)){
			$this->pasantia=1;
		}else{
			$this->pasantia=0;
		}
		$this->agencia_busquedas=$this->getCampo("agencia_busquedas");
		if (isset($this->agencia_busquedas)){
			$this->agencia_busquedas=1;
		}else{
			$this->agencia_busquedas=0;
		}
		$this->recibir_llamados=$this->getCampo("recibir_llamados");
		if (isset($this->recibir_llamados)){
			$this->recibir_llamados=1;
		}else{
			$this->recibir_llamados=0;
		}	
	}
	public function puedoModifAnuncio($adid,$catid){
		$puedoModif=false;
		if (is_null($this->puedoModificarAnuncio)){
			if ($this->registro->get("usuario")->estaLogueado()){
				if ($this->registro->get("usuario")->esMiAnuncio($adid,$catid,$this->registro->get("usuario")->getUsuarioId())){
			 		$puedoModif=true;
				}
			}
			if(empty($_REQUEST['c'])){
				$c=$_POST['c'];
			}else{
				$c=$_REQUEST['c'];
			}
			if(empty($puedoModif)){
				if (!empty($c) && ($this->esCodSegValido($c,$adid,$catid)) ) {
					$puedoModif=true;
				}
			}
			$this->puedoModificarAnuncio=$puedoModif;	
		}
		return $this->puedoModificarAnuncio;
	}
	public function esCodSegValido($codSeg,$adid,$catid){
		$q="SELECT count(*) as encontrados FROM ".$this->t_anuncios.$this->registro->get("categoria")->getCategoriaId()." WHERE cod_seguridad='".$codSeg."' AND catid=".$catid." AND adid=".$adid.";  ";
		$r=$this->registro->get("db")->query($q);
		$fila=$r->fetch_array();
		if ($fila['encontrados']==1){
			return true;
		}else{
			return false;
		}
	}
	public function validarLocacion(){
		$estado_id=$this->registro->get("locacion")->getEstadoId();
		$ciudad_id=$this->registro->get("locacion")->getCiudadId();
		if (empty($estado_id)){
			if ($this->registro->get("locacion")->esEstado()){
				$this->errores[]="Debes seleccionar un Estado o una Ciudad para publicar tu anuncio.";	
				return false;	
			}
		}
		if  (empty($ciudad_id)){
			if ($this->registro->get("locacion")->esCiudad()){
				$this->errores[]="Debes seleccionar una Ciudad para publicar tu anuncio.";	
				return false;	
			}	
		}
		return true;
		
	}
	public function setUrgente($u){
		if (!empty($u)){
			$this->urgente=$u;
		}
	}
	public function setDestacado($d){
		if (!empty($d)){
			$this->destacado=$d;
		}
	}
	public function validarPublicacion(){
			if ($this->fueValidado()){
			return true;
			//ya esta validado, sino iria a validar los campos encriptados y daria error!
		}		
		$this->validarLocacion();
		$this->titulo=$this->getCampo('titulo');
		$this->validarTitulo($this->titulo);
		if ($this->registro->get("categoria")->esCompraVenta()){
			$this->validarCamposCompraVenta();
		}
		if ($this->registro->get("categoria")->esVehiculos()){
			$this->validarCamposVehiculos();
		}
		$this->email=$this->getCampo('email');
		if (!$this->registro->get("usuario")->estaLogueado()){
			$this->validarEmail($this->email,$this->getConfirmacionEmail());
		}else{
			$this->usuario_id=$this->getCampo('usuario_id');
		}
		$this->lugar=$this->getCampo('lugar');
		
		$this->setDestacado($this->getCampo('destacado_1'));
		$this->setUrgente($this->getCampo('destacado_2'));
		
		if (isset($this->lugar) && (!$this->validarMaxLongitud($this->lugar,40))) {
			$this->errores[]="Debes escribir un lugar de no más de 40  caracteres.";
		}
		$this->descripcion=$this->getCampo('descripcion');
		if (!$this->validarLongitudMayorCero($this->descripcion)){
				$this->errores[]="Debes escribir una descripcion del anuncio.";
				$this->camposRojos[]='descripcion';
		}
		else{
			$this->descripcion=$this->registro->get("helper")->getDescripcionValida($this->descripcion);
		}
		
		if ($this->registro->get("categoria")->esEvento()){
			$this->validarCamposEventos();
		}
		if ($this->registro->get("categoria")->esVivienda()){
			if ($this->esCompraVivienda()) {
				$this->validarPrecio(11);
				
			}
			if ($this->esAlquilerVivienda()){
				$this->validarAlquiler(11);
			}
			$this->gatos_ok=$this->getCampo('gatosOK');
			$this->perros_ok=$this->getCampo('perrosOK');
			if (isset($this->perros_ok)){
				$this->perros_ok=1;
			}else{
				$this->perros_ok=0;
			}
			if (isset($this->gatos_ok)){
				$this->gatos_ok=1;
			}else{
				$this->gatos_ok=0;
			}
		}
		if ($this->registro->get("categoria")->esPersonales()){
			$this->validarEdad();
		}
		if ($this->registro->get("categoria")->esEmpleo()){
			$this->validarCamposEmpleo();
		}
		if ($this->registro->get("categoria")->esTTemporal()){
			$this->pago=$this->getCampo('pagocampo');
		}
		if ($this->registro->get("categoria")->getTieneImagenes()){
			if (!empty($_FILES) || !empty($_POST)){
				//si es un cron entra aca y borra las imagenes que cargue via el cron
				$this->registro->get("imagenes")->subir(IMAGENES_ANUNCIO);	
			}
		}
		
		$this->contact_comercial=$this->getCampo('contact_comercial');
		if (isset($this->contact_comercial)){
			$this->contact_comercial=1;
		}else{
			$this->contact_comercial=0;
		}
		$this->setFechaHoraPublicacion();
		if(!$this->hayErroresPublicacion()){
			return true;
		}
		else{
			return false;
		}				
	}
	public function pulsoAceptar (){
		if ($this->validarLongitudMayorCero($this->getCampo("acepto"))){
			return true;
		}else{
			return false;	
		}
	}
	public function pulsoRechazo(){
		if ($this->validarLongitudMayorCero($this->getCampo("rechazo"))){
			return true;
		}else{
			return false;	
		}
	}
	public function validarTermCondiciones(){
		if (!$this->pulsoAceptar()){
			$this->errores[]="Necesitamos que aceptes las condiciones de uso antes de continuar.";
			$this->camposRojos[]='condiciones';
			return false;
		}else{
			return true;
		}
	}
	public function validarCaptcha($intentos){		
		if ($intentos>MAX_INTENTOS_CAPTCHA){
			if ($_POST['suma']!=$_POST['captcha']){
				$this->errores[]="La suma de los numeros no es la correcta, vuelve a intentarlo.";
				$this->camposRojos[]='palabraverif';
				return false;
			}
		}else{
			if (!$this->registro->get("captcha")->esValido()){
				$this->errores[]="No has tecleado las palabras de verificación correctamente. Intentalo de nuevo.";
				$this->camposRojos[]='palabraverif';
				return false;
			}
		}
		return true;
	}
	
	public function aceptoTerminosyCond(){
		if ($this->pulsoAceptar()){
			return true;
		}else{
			return false;
		}
	}
	public function pulsoEditar(){
		if ($this->validarLongitudMayorCero($this->getCampo("editar"))){
			return true;
		}else{
			return false;	
		}
	}
	public function deCryptValores(array $campos,$noEncriptarCampos=null){
		foreach ($campos as $campo=>$valor){
			if (!is_null($noEncriptarCampos)){
				if (in_array($campo,$noEncriptarCampos)){
					continue;
				}	
			}
			if (is_array($campos[$campo])){
				$i=0;
				foreach ($campos[$campo] as $val){
					$campos[$campo][$i]=$this->registro->get("crypt")->decrypt($val);
					$i++;
				}
			}else{
				$campos[$campo]=$this->registro->get("crypt")->decrypt($valor);
			}
		}
		return $campos;
	}
	public function obtenerCamposDecrypt($noEncriptarCampos=null){
		$_GET=$this->deCryptValores($_GET,$noEncriptarCampos);
		$_REQUEST=$this->deCryptValores($_REQUEST,$noEncriptarCampos);
		$_POST=$this->deCryptValores($_POST,$noEncriptarCampos);
	}
	public function traerCamposOcultosaInsertarEncripNombre($noEncriptar=null){
		foreach ($_POST as $campo=>$valor){
			if (!is_null($noEncriptar)){
				if (in_array($campo,$noEncriptar)){
					continue;
				}	
			}
			if ((strlen($valor)>0)){
				echo '<input type="hidden" name="'.$this->registro->get("crypt")->encrypt($campo).'" value="'.$valor.'" />';				
			}
		}
		$max_imagenes=IMAGENES_ANUNCIO+1;
		for ($i=1;$i<$max_imagenes;$i++){
			$valor=$this->registro->get("imagenes")->get($i);
			if (strlen($valor)>0){
				echo '<input type="hidden" name="'.$this->registro->get("crypt")->encrypt('img'.$i).'" value="'.$valor.'" />';
			}
		}
	}
	public function traerCamposOcultosaInsertar($noEncriptar=null){
		foreach ($_POST as $campo=>$valor){
			if (!is_null($noEncriptar)){
				if (in_array($campo,$noEncriptar)){
					continue;
				}	
			}
			if ((strlen($valor)>0)){
				echo '<input type="hidden" name="'.$campo.'" value="'.$this->registro->get("crypt")->encrypt($valor).'" />';				
			}
		}
		$max_imagenes=IMAGENES_ANUNCIO+1;
		for ($i=1;$i<$max_imagenes;$i++){
			$valor=$this->registro->get("imagenes")->get($i);
			if (strlen($valor)>0){
				echo '<input type="hidden" name="img'.$i.'" value="'.$this->registro->get("crypt")->encrypt($valor).'" />';
			}
		}
	}
	public function getImagen($num){
		return $this->imagenes[$num];
	}
	
	public function setErrores($error){
		$this->errores[]=$error;
	}
	public function setCampoRojo($campo){
		$this->camposRojos[]=$campo;
	}
	public function gatosOk() {
		return $this->gatos_ok;
	}
	public function perrosOk() {
		return $this->perros_ok;
	}
	public function contactoComercialOK(){
		return @(bool)$this->contact_comercial;
	}
	public function getFechaHora(){
		$mes=calendario::getMesEspanol(date("F"));
		$mes=substr($mes,0,3);
		return date("d")."-".$mes."-".date("Y")." ".date("h:i:s A");
	}
	public function getImagenes(){
		return $this->imagenes;
	}
	public function pulsoContinuar(){
		if (isset($_POST['continuar'])){
			return true;
		}else{
			return false;
		}
	}
	public function renderTitulo(){
		$this->precio=doubleval($this->precio);
		$precio=(!empty($this->precio)) ? ' - '.'$'.$this->precio:'';
		$lugar=($this->validarLongitudMayorCero($this->lugar)) ? ' - '.'('.$this->lugar.')':'';
		$alquiler=($this->validarLongitudMayorCero($this->alquiler)) ? ' - '.'$'.$this->alquiler:'';
		$edad=($this->validarLongitudMayorCero ($this->edad)) ? ' - '.$this->edad:'';
		$titulo=$this->titulo.$precio.$alquiler.$edad.$lugar;
		return $titulo;
	}
	public function setFechaHoraPublicacion (){
		$this->fechaHora=$this->getCampo('fechaHora');
	}
	public function getFechaHoraPublicacion(){
		return $this->fechaHora;
	}
	public function agregarCampo($campo,$valor,$tipo){
		if ($this->validarLongitudMayorCero($valor) && (!array_key_exists($campo,$this->valores))){
				$this->valores[$campo]=array("valor"=>db::getInstance()->real_escape_string($valor),"tipo"=>$tipo);
		}else{
			return false;
		}
	}
	public function getCamposValoresActualizar(){
		$campos=array();
		foreach ($this->valores as $campo=>$valor){
			if ($valor['tipo']=='s'){
				$valor['valor']="'".$valor['valor']."'";
			}
			$campos[]=$campo." = ".$valor['valor'];
		}
		return implode(",", $campos);
	}
	public function getValoresInsertar(){
		$campos=array();
		foreach ($this->valores as $campo=>$valor){
			if ($valor['tipo']=='s'){
				$valor['valor']="'".$valor['valor']."'";
			}
			$campos[]=$valor['valor'];
		}
		return implode(",", $campos);
	}
	public function getCamposInsertar(){
		$campos=array();
		foreach ($this->valores as $campo=>$valor){
			$campos[]=$campo;
		}
		return implode(",", $campos);
	}
	public function republicar($adid,$catid){
		$this->setMakeTime(time());
		$this->fechaHora=$this->getFechaInsertarAnuncio();
		$this->vencimiento=$this->registro->get('helper')->getDatetimePorMaketime($this->generarVencimiento());
		if ($this->actualizarAnuncio($adid,$catid)){
			return true;
		}else{
			return false;
		}
	}
	public function actualizarAnuncio($adid,$catid){		
		$anuncioData=$this->agregarCamposValoresAnuncio();
		$sql="UPDATE ".$anuncioData['tabla']." SET ".$this->getCamposValoresActualizar()." WHERE adid=".$adid." AND catid=".$catid." ;";
		$result=db::getInstance()->query($sql);		
		if ($this->registro->get("categoria")->getTieneImagenes()){
			if ($this->getCampo('tieneImagenes')==1){
				$this->registro->get("imagenes")->actualizarmeEnElAnuncio($adid,$catid);
			}else{
				$this->registro->get("imagenes")->insertarmeEnElAnuncio($adid);
			}
			
		}
		if($result){
			return true;
		}else{
			$contenido="Error al ejecutar la consulta: <br />".$sql;
    		$this->registro->get("email")->enviar("Error Insertar Anuncio","cto@clasilistados.org",$contenido,ERROR_PUBLICAR_ANUNCIO);
    		return false;
		}
	}
	public function generarVencimiento(){
		return mktime(date("H"),date("i"),date("s"),date("n"),date("j")+$this->diasVencimiento, date("Y"));
	}
	public function setDiasVencimiento($dias){
		$this->diasVencimiento=$dias;	
	}
	public function getDiasVencimiento(){
		return $this->diasVencimiento;	
	}
	public function agregarCamposValoresAnuncio(){
		global $salt;
		if (is_null($this->vencimiento)){
			$mktime=$this->generarVencimiento();
			$this->vencimiento=$this->registro->get('helper')->getDatetimePorMaketime($mktime);
			//'2029-05-10 11:50:22';
		}
		$this->agregarCampo("titulo",$this->getTitulo(),"s");
		//$this->agregarCampo("datetime_alta",$this->fechaHora,"s");
		$this->agregarCampo("fechaHora",$this->fechaHora,"s");
		$this->agregarCampo("descripcion",$this->getDescripcion(),"s");
		$this->agregarCampo("catid",$this->registro->get("categoria")->getCategoriaId(),"i");
		$this->agregarCampo("subcatid",$this->registro->get("categoria")->getSubcategoriaId(),"i");
		$this->agregarCampo("email",$this->getEmail(),"s");
		$this->agregarCampo("ip",$_SERVER['REMOTE_ADDR'],"s");
		$this->agregarCampo("lugar",$this->getLugar(),"s");
		$this->agregarCampo("contact_comercial",$this->contact_comercial,"s");
		$this->agregarCampo("vencimiento",$this->vencimiento,"s");
		$this->agregarCampo("destacado",$this->destacado,"i");
		$this->agregarCampo("urgente",$this->urgente,"i");
		
		if (!is_null($this->feed_id)){
			$this->agregarCampo("feed_id",$this->feed_id,"i");
			$this->agregarCampo("link",$this->link,"s");
			$this->agregarCampo("respuestaPorLink",$this->respuestaPorLink,"s");
		}
		if ($this->registro->get("locacion")->esEstado()){
			$this->agregarCampo("stateid",$this->registro->get("locacion")->getEstadoId(),"i");
		}
		if ($this->registro->get("locacion")->esCiudad()){
			$this->agregarCampo("cityid",$this->registro->get("locacion")->getCiudadId(),"i");
		}
		if ($this->registro->get("categoria")->esCompraVenta()){
			$this->agregarCampo("precio",$this->getPrecio(),"s");
			
		}
		if ($this->registro->get("categoria")->esVehiculos()){
			$this->agregarCampo("precio",$this->getPrecio(),"s");
			
		}
		if ($this->registro->get("categoria")->esEmpleo()){
			$this->agregarCampo("retribucion",$this->retribucion,"s");
			$this->agregarCampo("teletrabajo",$this->teletrabajo,"s");
			$this->agregarCampo("tiempo_parcial",$this->tiempo_parcial,"s");
			$this->agregarCampo("contrato",$this->contrato,"s");
			$this->agregarCampo("org_sinlucro",$this->org_sinlucro,"s");
			$this->agregarCampo("pasantia",$this->pasantia,"s");
			$this->agregarCampo("agencia_busquedas",$this->agencia_busquedas,"s");
			$this->agregarCampo("recibir_llamados",$this->recibir_llamados,"s");
		}
		if ($this->registro->get("categoria")->esEvento()){
			$this->agregarCampo("fechaInicio",$this->eventoFechaInicio,"s");
			$this->agregarCampo("fechaFin",$this->eventoFechaFin,"s");
		}
		if ($this->registro->get("categoria")->esVivienda()){
			$this->agregarCampo("precio",$this->getPrecio(),"s");
			$this->agregarCampo("alquiler",$this->getAlquiler(),"s");
			$this->agregarCampo("gatos_ok",$this->gatos_ok,"s");
			$this->agregarCampo("perros_ok",$this->perros_ok,"s");
		}
		if ($this->registro->get("categoria")->esTTemporal()){
			$this->agregarCampo("pago",$this->getPago(),"s");
		}
		if ($this->registro->get("categoria")->esPersonales()){
			$this->agregarCampo("edad",$this->getEdad(),"i");
		}

		$tabla=$this->t_anuncios.$this->registro->get("categoria")->getCategoriaId();
		if(empty($_REQUEST['c'])){
			$c=$_POST['c'];
		}else{
			$c=$_REQUEST['c'];
		}
		if (empty($c)){
			$hashCode = md5 ($this->valores.$salt.time());
		}else{
			$hashCode = $c;
		}
		$this->agregarCampo("cod_seguridad",$hashCode,"s");
		//cierro los campos
		$anuncioData['tabla']=$tabla;
		$anuncioData['hashCode']=$hashCode;
		return $anuncioData;
	}
	public function guardarAnuncioRSSData($item){
		$this->titulo=$item['title'];
		$this->descripcion=$item['description'];
		$this->getTiempoPublicacion();
		$this->fechaHora=$this->getFechaInsertarAnuncio();
		$this->contact_comercial=0;
		//feeds sin contacto comercial
	}
	public function guardarAnuncioOpcionalesRSSData($item){
		$this->lugar=$item['location'];
		$this->email=$item['email'];
		$this->confirmacion_email=$item['confirmacion_email'];
		$this->link=$item['link'];
		$this->respuestaPorLink=$item['respuestaPorLink'];
		if (is_array($item['imagenes'])){
			foreach ($item['imagenes'] as $imagenNum=>$valor){
				$this->registro->get("imagenes")->set($imagenNum,$valor);				
			}
		}
		if ($this->esPrecioValido($item['precio'])){
			$this->precio=$item['precio'];
		}
	}
	public function esPrecioValido($precio){
		if (strlen($precio)>0){
			$precio=filter_var($precio, FILTER_VALIDATE_FLOAT, array("options" => array("min_range"=>PRECIO_MINIMO, "max_range"=>PRECIO_MAXIMO)));       
			if($precio==false){
				return false;
			}
		}else{
			return false;
		}
		return true;
	}
	public function actualizarImagen($id,$catid,$imgid){
		$q="UPDATE ".$this->t_anuncios.$catid." SET imagenes_id=$imgid WHERE adid=$id;";
		$r=db::getInstance()->query($q);
		if ($r) {
		   	return true;
		}else{
			return false;
		}
	}
	public function envioMailingRepublicar($email,$adid,$catid){
		global $t_mailing_republicar;
		$q="INSERT INTO $t_mailing_republicar (email,adid,catid) VALUES (?,?,?);";
		if ($stmt = db::getInstance()->prepare($q)) {
		   	$stmt->bind_param("sii", $email,$adid, $catid);
		   	$stmt->execute();
		   	$stmt->close();
		   	return true;
		}else{
			return false;
		}
		
	}
	public function cuantosMailingRepublicacionEnvie($email,$adid,$catid){
		global $t_mailing_republicar;
		$q='SELECT count(*) as contador FROM '.$t_mailing_republicar.' WHERE adid='.$adid.' and catid='.$catid.' GROUP BY email';
		$result=db::getInstance()->query($q);
		if(!$result){
			return false;
		}
		if($result->num_rows==0){
			return false;
		}
		$row=$result->fetch_array();
		return $row['contador'];
	}
	public function insertarEnElBuscador($id,$catid){
		$q="INSERT INTO cl2_sphinx_ids (adid,catid) VALUES (?,?);";
		if ($stmt = db::getInstance()->prepare($q)) {
		   	$stmt->bind_param("ii", $id, $catid);
		   	$stmt->execute();
		   	$stmt->close();
		   	return true;
		}else{
			return false;
		}
	}
	public function elLinkExiste(){
		$q="SELECT adid FROM ".$this->t_anuncios.$this->registro->get("categoria")->getCategoriaId()." WHERE link='".$this->link."' and habilitado='".ANUNCIO_HABILITADO."';";
		$result=db::getInstance()->query($q);
		if(!$result){
			return false;
		}
		if($result->num_rows==0){
			return false;
		}
		while ($row=$result->fetch_array()){
			//var_dump($row);
			$this->eliminarAnuncio($row['adid'],$this->registro->get("categoria")->getCategoriaId());
		}
		return true;
	}
	public function insertarAnuncioFeed(){
		$anuncioData=$this->agregarCamposValoresAnuncio();
		//los feeds salen habilitados
		//$this->elLinkExiste();  //busca links iguales y los elimina del listado
		
		if ($this->validarPublicacion()){
			$this->agregarCampo("habilitado",ANUNCIO_HABILITADO,"s"); 
		}else{
			var_dump($this->errores);
			return false;
		}
		$sql="INSERT INTO ".$anuncioData['tabla']." (".$this->getCamposInsertar().") VALUES (".$this->getValoresInsertar().") ;";
		$result=db::getInstance()->query($sql);
		$this->id=db::getInstance()->insert_id();
		
    	if($result){
    		$this->insertarEnElBuscador($this->id,$this->registro->get("categoria")->getCategoriaId());
    		if ( ($this->registro->get("categoria")->getTieneImagenes()) && ($this->registro->get("imagenes")->tengo()) ){
	    		$this->registro->get("imagenes")->insertarmeEnElAnuncio($this->id);
			}
    		$q="INSERT INTO ".$this->t_feeds_anuncios." (feed_id,adid,catid,subcatid) VALUES ($this->feed_id,$this->id,".$this->registro->get("categoria")->getCategoriaId().",".$this->registro->get("categoria")->getSubCategoriaId().");";
    		$result=$this->registro->get("db")->query($q);
    		if ($result){
    			return array("id"=>$this->id,"codigo_seguridad"=>$anuncioData['hashCode']);	
    		}else{
    			return false;
    		}
    	}else{
    		$contenido="Error al ejecutar la consulta: <br />".$sql;
    		$this->errores[]="No pudimos procesar su anuncio. Vuelva a intentarlo más tarde.";
    		$this->registro->get("email")->enviar("Error Insertar Anuncio","cto@clasilistados.org",$contenido,ERROR_PUBLICAR_ANUNCIO);
    		return false;
    	}
	}
	public function esPublicacionPaga(){
		$categoria=$this->registro->get("categoria");
		$publicacion=$this->registro->get('publicacion');
		$esCategoriaPaga=(($publicacion->esCategoriaPaga($categoria->getCategoriaId())) && ( $publicacion->deboCobrarEstaUbicacion($categoria->getCategoriaId())));
		$esSubCategoriaPaga=(($publicacion->esSubCategoriaPaga($categoria->getCategoriaId(),$_REQUEST['subcat'])) && ( $publicacion->deboCobrarEstaUbicacion($categoria->getCategoriaId(),$_REQUEST['subcat'])));
		if ($esCategoriaPaga || $esSubCategoriaPaga){
			return true;
		}
		return false;
	}
	public function enviarTweet($id){
		global $dev_env;
		$success=false;
		if (!$dev_env){
			$categoria=$this->registro->get("categoria");
			$helper=$this->registro->get("helper");
			$tweet = new Twitter("clasilistados", "CLasi9002");
			$urlCorta=$helper->getSmallLink($this->registro->get("helper")->getAnuncioLink($id,$this->titulo,$categoria->getCategoriaId(),$categoria->getSubCategoriaId(),$categoria->getSubCategoriaNombre()));
			$status=$this->titulo. ' '.$urlCorta;
			$success = $tweet->update($status);
		}
		return $success;
	}
	public function insertarAnuncio(){
		$anuncioData=$this->agregarCamposValoresAnuncio();
		if ($this->registro->get("usuario")->estaLogueado() || ($this->esPublicacionPaga())){
			$this->agregarCampo("habilitado",ANUNCIO_HABILITADO,"s");
		}
		$sql="INSERT INTO ".$anuncioData['tabla']." (".$this->getCamposInsertar().") VALUES (".$this->getValoresInsertar().") ;";
		$result=db::getInstance()->query($sql);
		$this->id=db::getInstance()->insert_id();
		
		if ($this->registro->get("usuario")->estaLogueado() || ($this->esPublicacionPaga())){
			$this->enviarTweet($this->id);
		}
		
		if($result){
    		$this->insertarEnElBuscador($this->id,$this->registro->get("categoria")->getCategoriaId());
	    	if ( ($this->registro->get("categoria")->getTieneImagenes()) && ($this->registro->get("imagenes")->tengo()) ){
				$this->registro->get("imagenes")->insertarmeEnElAnuncio($this->id);
			}
			$costo=0;
			if($this->esPublicacionPaga() && !$this->registro->get("suscripcion")->usuarioTieneSuscripcion()){
				$costo=$this->registro->get('publicacion')->getPrecio($this->registro->get("categoria")->getCategoriaId());
				$asunto='Tu anuncio de '.NOMBRE_SITIO.' "'.stripslashes($this->getTitulo()).'"';
	    		$contenido=file_get_contents($_SERVER['DOCUMENT_ROOT']."/plantillas/pago_conf.html");
				$contenido=str_replace("<<URL_ADMIN_ANUNCIO>>",$this->registro->get("helper")->getAdminAnuncioLink($this->id,$anuncioData['hashCode'],$this->registro->get("categoria")->getCategoriaId()),$contenido);
				$contenido=str_replace("<<ID_TRANSACCION>>",$this->registro->get("publicacion")->getTransaccionId(),$contenido);
				$contenido=str_replace("<<ID>>",$this->id,$contenido);
				$contenido=str_replace("<<FECHA>>",date('d-m-Y'),$contenido);
				$contenido=str_replace("<<CARD_NAME>>",$_POST['name'],$contenido);
				$contenido=str_replace("<<CARD_ADRESS1>>",$_POST['address1'],$contenido);
				$contenido=str_replace("<<CARD_CITY>>",$_POST['city'],$contenido);
				$contenido=str_replace("<<CARD_STATE>>",$_POST['state'],$contenido);
				$contenido=str_replace("<<CARD_ZIP>>",$_POST['zip'],$contenido);
				$contenido=str_replace("<<CATEGORIA_NOMBRE>>",$this->registro->get("categoria")->getCategoriaNombre(),$contenido);
				$contenido=str_replace("<<SUBCATEGORIA_NOMBRE>>",$this->registro->get("categoria")->getSubCategoriaNombre(),$contenido);
				$contenido=str_replace("<<IMPORTE>>",$_POST['chargetotal'],$contenido);
				$contenido=str_replace("<<TITULO>>",$this->getTitulo(),$contenido);
				$contenido=str_replace("<<CIUDAD>>",$this->registro->get("locacion")->getCiudad(),$contenido);
				$contenido=str_replace("<<URL_ANUNCIO>>",$this->registro->get("helper")->getAnuncioLink($this->id,$this->getTitulo(),$this->registro->get("categoria")->getCategoriaId(),$this->registro->get("categoria")->getSubCategoriaId(),$this->registro->get("categoria")->getSubCategoriaNombre()),$contenido);
				$contenido=$this->registro->get("helper")->caracteres_html($contenido);
				$this->registro->get("email")->enviar($asunto,$_POST['email_cc'],$contenido,PAGO_CONFIRMACION);
			}
    		if ($this->registro->get("usuario")->estaLogueado()){
    			$this->registro->get("usuario")->insertarMiAnuncio($this->id,$this->registro->get("categoria")->getCategoriaId(),$this->usuario_id,$costo,$this->registro->get("locacion")->getEstadoId(),$this->registro->get("locacion")->getCiudadId());	
    		}elseif(!$this->esPublicacionPaga()){
	    		$asunto="Publicar/Editar/Eliminar: ".stripslashes($this->getTitulo());
	    		$contenido=file_get_contents($_SERVER['DOCUMENT_ROOT']."/plantillas/email_anuncio_confirmacion.html");
				$contenido=str_replace("<<URL_ADMIN_ANUNCIO>>",$this->registro->get("helper")->getAdminAnuncioLink($this->id,$anuncioData['hashCode'],$this->registro->get("categoria")->getCategoriaId()),$contenido);
				$contenido=str_replace("<<CREAR_CUENTA>>",$this->registro->get("helper")->getCrearCuentaAqui(),$contenido);
				$contenido=$this->registro->get("helper")->caracteres_html($contenido);
				$this->registro->get("email")->enviar($asunto,$this->getEmail(),$contenido,PUBLICAR_ANUNCIO);
    		}
    		
			return array("id"=>$this->id,"codigo_seguridad"=>$anuncioData['hashCode']);
    	}else{
    		$contenido="Error al ejecutar la consulta: <br />".$sql;
    		$this->errores[]="No pudimos procesar su anuncio. Vuelva a intentarlo más tarde.";
    		$this->registro->get("email")->enviar("Error Insertar Anuncio","cto@clasilistados.org",$contenido,ERROR_PUBLICAR_ANUNCIO);
    		return false;
    	}
	}
	public function getId(){
		return $this->id;
	}
	public function setRetribucion($r){
		$this->retribucion=$r;
	}
	public function getRetribucion(){
		return $this->retribucion;
	}
	public function enviarAmigo($emailRemitente,$emailDestinatario,$urlAnuncio,$t_envia_amigo){
    	$contenido=file_get_contents($_SERVER['DOCUMENT_ROOT']."/plantillas/recomenda_amigo.html");
		$contenido=str_replace("<<EMAIL>>",$emailRemitente,$contenido);
		$contenido=str_replace("<<URL_ANUNCIO>>",$urlAnuncio,$contenido);
		$contenido=str_replace("<<TITULO>>",$this->getTitulo(),$contenido);
		$contenido=str_replace("<<FECHA>>",$this->registro->get("helper")->getFecha($this->getMaketime()),$contenido);
		$contenido=str_replace("<<ANUNCIO_BODY>>",$this->getDescripcion(),$contenido);
		$contenido=str_replace("<<ANUNCIO_IMAGENES>>",$this->registro->get("imagenes")->getHTML(IMAGENES_ANUNCIO),$contenido);
		$contenido=$this->registro->get("helper")->caracteres_html($contenido);
		$asunto=$this->getTitulo();
		
		$this->registro->get("email")->setFrom($emailRemitente,$emailRemitente);		
		if ($this->registro->get("email")->enviar($asunto,$emailDestinatario,$contenido,RECOMENDAR_AMIGO_ANUNCIO)){
			$q="INSERT INTO ".$t_envia_amigo." (adid,catid,remitemail,destemail) VALUES (".$this->getId().",".$this->registro->get("categoria")->getCategoriaId().",'".$emailRemitente."','".$emailDestinatario."');";
			$this->registro->get("db")->query($q);
			return true;
		}else{
			return false;
		}
	}
	public function responderAnuncio($email,$comentarios,$t_respuestas_anuncios){
		$asunto=stripslashes($this->getTitulo());
    	$contenido=file_get_contents($_SERVER['DOCUMENT_ROOT']."/plantillas/respuesta_anuncio.html");
		$contenido=str_replace("<<COMENTARIOS>>",$comentarios,$contenido);
		$contenido=str_replace("<<URL_ANUNCIO>>",$this->registro->get("helper")->getAnuncioLink($this->getId(),$this->getTitulo(),$this->registro->get("categoria")->getCategoriaId(),$this->registro->get("categoria")->getSubCategoriaId(),$this->registro->get("categoria")->getSubCategoriaNombre()),$contenido);
		$contenido=$this->registro->get("helper")->caracteres_html($contenido);
		$comentarios=$this->registro->get("db")->real_escape_string($comentarios);
		$this->registro->get("email")->setFrom($email,$email);	
		if ($this->registro->get("email")->enviar($asunto,$this->getEmail(),$contenido,RESPONDER_ANUNCIO)){
			$q="INSERT INTO ".$t_respuestas_anuncios." (adid,catid,destinatario,comentarios) VALUES (".$this->getId().",".$this->registro->get("categoria")->getCategoriaId().",'".$email."','".$comentarios."');";
			$this->registro->get("db")->query($q);
			return true;
		}else{
			$q="INSERT INTO ".$t_respuestas_anuncios." (adid,catid,destinatario,seEnvio,comentarios) VALUES (".$this->getId().",".$this->registro->get("categoria")->getCategoriaId().",'".$email."','0','".$comentarios."');";
			$this->registro->get("db")->query($q);
			return false;
		}
		
	}
	public function getPago(){
		return $this->pago;
	}
	public function validarEdad(){
		$this->edad=$this->getCampo('edad');
		if ( (isset($this->edad)) && ($this->validarLongitudMayorCero($this->edad))){
			if(!is_numeric($this->edad) || ($this->edad<0)){
				$this->edad=$this->soloNumeros($this->edad);
				$this->errores[]="Tu edad debe ser un numero mayor a cero.";
			}
			if ($this->excedeMaxDigitos($this->edad,3)){
				$this->errores[]="Tu edad debe tener 3 digitos o menos";
			}
		}
	}
	public function validarAlquiler($digitosMax){
		$this->alquiler=$this->getCampo('alquiler');
		if ((isset($this->alquiler)) && ($this->validarLongitudMayorCero($this->alquiler))){
			if(!is_numeric($this->alquiler) || ($this->alquiler<0)){
				$this->alquiler=$this->soloNumeros($this->alquiler);
				$this->errores[]="El alquiler debe ser un numero mayor a cero.";
			}
			if ($this->excedeMaxDigitos($this->alquiler,$digitosMax)){
				$this->errores[]="El alquiler del anuncio debe tener ".$digitosMax." digitos o menos";
			}
		}
		
	}
	public function ValidarFechasCompletas($etiqueta,$dia,$mes,$anio){
		if(!$this->validarLongitudMayorCero($dia)){
			$this->errores[]="Fecha de $etiqueta del evento: Falta el dia";
		}
		if(!$this->validarLongitudMayorCero($mes)){
			$this->errores[]="Fecha de $etiqueta del evento: Falta el mes";
		}  
		if(!$this->validarLongitudMayorCero($anio)){
			$this->errores[]="Fecha de $etiqueta del evento: Falta el año";
		}  
	}
	public function validarCamposEventos(){
		$diaInicio=$this->getCampo('eventoDiaInicio');
		$mesInicio=$this->getCampo('eventoMesInicio');
		$anoInicio=$this->getCampo('eventoAnoInicio');
		$diaFinal=$this->getCampo('eventoDiaFin');
		$mesFinal=$this->getCampo('eventoMesFin');
		$anoFinal=$this->getCampo('eventoAnoFin');
		if (!@checkdate($mesInicio, $diaInicio, $anoInicio)){
			$this->errores[]="Fecha de inicio del evento: Debes escribir una fecha valida.";
			$this->camposRojos[]='fechaInicio';
		}else{
			$this->eventoFechaInicio=$anoFinal.'-'.$mesInicio.'-'.$diaInicio;
		}
		$this->ValidarFechasCompletas('inicio',$diaInicio,$mesInicio,$anoInicio);
		
		if ($this->validarLongitudMayorCero($diaFinal) || $this->validarLongitudMayorCero($mesFinal)){
			$this->ValidarFechasCompletas('fin',$diaFinal,$mesFinal,$anoFinal);
			//valido la fecha de fin si esta seteado el dia o mes final
			if (!@checkdate($mesFinal, $diaFinal, $anoFinal)){
				$this->errores[]="Fecha de fin del evento: Debes escribir una fecha valida.";
				$this->camposRojos[]='fechaFinal';
			}else{
				$this->eventoFechaFin=$anoFinal.'-'.$mesFinal.'-'.$diaFinal; //año-mes-dia
			}
		}else{
			$this->eventoFechaFin=$this->eventoFechaInicio; //si no esta seteado el mes y el dia va con la misma fecha de inicio.
		}
	}
	public function validarLongitudMayorCero($valor){
		if (strlen($valor)>0){
			return true;
		}else{
			return false;
		}
	}
	public function validarMaxLongitud($valor,$max){
		if ((strlen($valor)>$max)){
			return false;
		}else{
			return true;
		}
	}
	public function getErrores(){
		return $this->errores;
	}
	
	public function esCompraVivienda(){
		if ($this->registro->get("categoria")->esVivienda()){
				switch($this->registro->get("categoria")->getSubCategoriaId()){
					case 12:
					case 10:
					case 248:
					case 246:
						//se compra la vivienda
						return true;
						break;
					case 9:
					case 14:
					case 238:
					case 257: 
						$this->esAlquiler=true;
						return false;
					default:
						return false;
						break;
				}
			}
			else{
				return false;
			}
	}
	public function esAlquilerVivienda(){
		return $this->esAlquiler;
	}		
}
?>