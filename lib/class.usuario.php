<?php
class Usuario {
	
	private $tabla="cl2_users";
	private $tabla_usuarios_anuncios="cl2_usuarios_anuncios";
	private $tabla_anuncios="cl2_anuncios_";
	private $tabla_falta_poco="cl2_faltapoco";
	private $registro=null;
	private $usuario_id=null;
	private $email=null;
	private $cod_seguridad=null;
	private $estado=null;
	private $password=null;
	private $city_id=null;
	private $stateid=null;
	private $duracionSesion=21600; //6 horas
	private $cantidad_anuncios=0;
	private $esMiAnuncio=null;
	
	public function __construct(Registro $registro){
		$this->registro=$registro;	
		$this->usuario_id=$this->registro->get("session")->get("usuario_id");
		$this->email=$this->registro->get("session")->get("email");
	}
	public function cambiarHabilitado($habilitado){
		$q="UPDATE ".$this->tabla." SET enabled='".$habilitado."',cod_seguridad='".$this->getCodSeguridad()."' WHERE usuario_id=".$this->getUsuarioId().";";
		$r=$this->registro->get("db")->query($q);
		return $r;
	}
	public function cambiarEstadoUsuario($estado){
		$q="UPDATE ".$this->tabla." SET estado=".$estado." WHERE usuario_id=".$this->getUsuarioId().";";
		$r=$this->registro->get("db")->query($q);
		return $r;
	}
	public function getUsuariosDistEstado($estado,$habilitado,$limite){
		$sql="SELECT DISTINCT(email) FROM ".$this->tabla." WHERE  estado<>".$estado." AND enabled='".$habilitado."' LIMIT ".$limite.";";
		$result=$this->registro->get("db")->query($sql);
		if (!$result){
			return false;
		}
		$emails=array();
		while ($row=$result->fetch_array()){
			$emails[]=$row['email'];
		}
		return $emails;
	}
	public function getUsuariosFaltaPoco($enviado,$limite){
		$sql="SELECT DISTINCT(email) FROM ".$this->tabla_falta_poco." WHERE  enviado=".$enviado." LIMIT ".$limite.";";
		$result=$this->registro->get("db")->query($sql);
		if (!$result){
			return false;
		}
		$emails=array();
		while ($row=$result->fetch_array()){
			$emails[]=$row['email'];
		}
		return $emails;
	}
	public function crear($email,$cityid=null,$stateid=null){
		$cityid=(empty($cityid))?0:$cityid;
		$stateid=(empty($stateid))?0:$stateid;
		$this->crearCodSeguridad();
		$sql="INSERT INTO ".$this->tabla." (email,enabled,city_id,stateid,estado,cod_seguridad) VALUES ('$email','".USUARIO_HABILITADO."',".$cityid.",".$stateid.",".USUARIO_SIN_PASSWORD.",'".$this->getCodSeguridad()."');";
		$result=$this->registro->get("db")->query($sql);
		$id_usuario=$this->registro->get("db")->insert_id();
		if ($result){
			return $id_usuario;
		}else{
			return false;
		}
	}
	public function crearCodSeguridad(){
		global $salt;
		//TODO: Mover este salt de una global y transformarlo en una clase seguridad
		$this->setCodSeguridad(md5 ($email.$salt.time()));
	}
	/**
	 * Valida el email ingresado para la creacion del usuario
	 *
	 * @param String $email
	 * @return Array
	 */
	public function validarNuevo($email){
		$errores=array();
		if (!$this->registro->get("captcha")->esValido()){
			$errores[]="No has tecleado las palabras de verificación correctamente. Intentalo de nuevo.";	
			return $errores;
		}
		if (!$this->registro->get("email")->validar($email)){
			$errores[]="El correo electronico no es valido. Intenta de nuevo.";
			return $errores;
		}
		if ($this->cargar($email)){
			$errores[]='Ya existe una cuenta asociada al correo electrónico que has ingresado. Si es necesario, puedes <a href="'.$this->registro->get("helper")->getReiniciarPasswordLink().'">reiniciar tu contraseña</a> para esta cuenta.';
			return $errores;
		}
		return $errores;
	}
	public function eliminarAnuncio($adid,$catid){
		$q="UPDATE ".$this->tabla_usuarios_anuncios." SET estado=".USUARIO_ANUNCIO_BORRADO." WHERE catid=".$catid." AND adid=".$adid." AND usuario_id=".$this->getUsuarioId()." ;";
		$r=$this->registro->get("db")->query($q);
		return $r;
	}
	public function insertarMiAnuncio($adid,$catid,$usuario_id,$costo, $estado_id,$ciudad_id){
		$sitio=$this->registro->get("locacion")->getSitioPorCityId_StateId($ciudad_id,$estado_id);
		$suscripciones=$this->registro->get("suscripcion")->getSuscripciones();
		$suscripcion_id=intval($suscripciones[$catid]['id']);
		$sql_insert="INSERT INTO ".$this->tabla_usuarios_anuncios." (usuario_id, catid, adid, sitio, estado, costo, suscripcion_id) VALUES ($usuario_id, ".$catid.", ".$adid.", '".$sitio."', ".USUARIO_ANUNCIO_ACTIVO.", $costo, $suscripcion_id) ;";
		$result_insert=$this->registro->get("db")->query($sql_insert);
		if ($result_insert){
			return true;
		}
		return false;
	}
	
	public function salir(){
		$this->registro->get("session")->remove("usuario_id");
		$this->registro->get("session")->remove("email");
		$this->registro->get("cookie")->delete("usuario_id");
	}
	public function cargarUsuarioInactivo($email){
		$sql="SELECT usuario_id,password,cod_seguridad,city_id,stateid,estado,duracion_sesion FROM ".$this->tabla." WHERE email='".$email."' AND enabled='".USUARIO_INHABILITADO."' AND estado<>".USUARIO_EMAIL_INEXISTENTE." GROUP BY email;";
		$result=$this->registro->get("db")->query($sql);
		if (!$result){
			return false;
		}
		if ($result->num_rows>0){
			$row=$result->fetch_array();
			$this->setCityId($row['city_id']);
			$this->setStateId($row['stateid']);
			$this->setCodSeguridad($row['cod_seguridad']);
			$this->setPassword($row['password']);
			$this->setUsuarioId($row['usuario_id']);
			$this->setEstado($row['estado']);
			$this->setDuracionSesion($row['duracion_sesion']);
			$this->setEmail($email);
			return true;
		}else{
			return false;
		}
		
		
	}
	public function cargarPorUsuarioId($usuario_id){
		$sql="SELECT email,password,cod_seguridad,city_id,stateid,estado,duracion_sesion FROM ".$this->tabla." WHERE usuario_id='".$usuario_id."' and enabled='1' ;";
		$result=$this->registro->get("db")->query($sql);
		if (!$result){
			return false;
		}
		$row=$result->fetch_array();
		$this->setCityId($row['city_id']);
		$this->setStateId($row['stateid']);
		$this->setCodSeguridad($row['cod_seguridad']);
		$this->setPassword($row['password']);
		$this->setUsuarioId($usuario_id);
		$this->setEstado($row['estado']);
		$this->setDuracionSesion($row['duracion_sesion']);
		$this->setEmail($row['email']);
		
		
		if ($result->num_rows==1){
			return true;
		}
		return false;
	}
	public function cargar($email){
		$sql="SELECT usuario_id,password,cod_seguridad,city_id,stateid,estado,count(*) as registrados,duracion_sesion FROM ".$this->tabla." WHERE email='".$email."' and enabled='1' GROUP BY email;";
		$result=$this->registro->get("db")->query($sql);
		if (!$result){
			return false;
		}
		$row=$result->fetch_array();
		$this->setCityId($row['city_id']);
		$this->setStateId($row['stateid']);
		$this->setCodSeguridad($row['cod_seguridad']);
		$this->setPassword($row['password']);
		$this->setUsuarioId($row['usuario_id']);
		$this->setEstado($row['estado']);
		$this->setDuracionSesion($row['duracion_sesion']);
		$this->setEmail($email);
		
		
		if ($row['registrados']==1){
			return true;
		}
		return false;
	}
	public function cambiarEnvioFaltaPoco($enviado,$email){
		$q="UPDATE ".$this->tabla_falta_poco." SET enviado=".$enviado." WHERE email='".$email."'";
		$result=$this->registro->get("db")->query($q);
		return $result;
	}
	public function existeOtroHabilitado($email){
		$sql="SELECT count(*) as registrados FROM ".$this->tabla." WHERE email='".$email."' AND enabled='".USUARIO_HABILITADO."' GROUP BY email;";
		$result=$this->registro->get("db")->query($sql);
		$row=$result->fetch_array();
		if ($row['registrados']>=1){
			return true;
		}
		return false;
	}
	public function cambiarEstadoCiudad($ciudad_id,$estado_id){
		if (empty($ciudad_id)){
			$ciudad_id=0;
		}
		if (empty($estado_id)){
			$estado_id=0;
		}
		$q="UPDATE ".$this->tabla." SET city_id=".$ciudad_id.", stateid=".$estado_id." WHERE email='".$this->getEmail()."' AND usuario_id=".$this->getUsuarioId().";";
		$result=$this->registro->get("db")->query($q);
		if (!$result){
			return false;
		}else{
			$this->setCityId($ciudad_id);
			$this->setStateId($estado_id);
			return true;
		}		
	}
	public function getColorAnuncio($estado){
		switch ($estado){
			case USUARIO_ANUNCIO_BORRADO:
				return COLOR_ADMIN_BORRADO_POR_MI;
			case USUARIO_ANUNCIO_ACTIVO:
				return COLOR_ADMIN_ACTIVO;
			case USUARIO_ANUNCIO_PENDIENTE:
				return COLOR_ADMIN_PENDIENTE;
			case USUARIO_ANUNCIO_MARCADO:
				return COLOR_ADMIN_BORRADO_MARCADO;
			case USUARIO_ANUNCIO_VENCIDO:
				return COLOR_ADMIN_VENCIDO;
				break;
			default:
				return COLOR_ADMIN_ACTIVO;
				break;
		}
	}
	
	public function cargarMiAnuncio($adid,$catid){
		$sql_usuario="SELECT adid,catid,costo,sitio,estado FROM ".$this->tabla_usuarios_anuncios." WHERE usuario_id=".$this->getUsuarioId()." AND catid=".$catid." AND adid=".$adid.";";
		$result=$this->registro->get("db")->query($sql_usuario);
		if (!$result){
			return false;
		}
		$row=$result->fetch_array();
		return $row;
	}
	
	public function misAnuncios($usuario_id,$fechaMktimeDesde,$fechaMktimeHasta){
		global $t_subcats;
		$html='';
		$this->cantidad_anuncios=0;
	
		$sql_usuario="SELECT adid,catid,costo,sitio,estado,suscripcion_id FROM ".$this->tabla_usuarios_anuncios." WHERE usuario_id=".$usuario_id." ORDER BY id DESC;";
		$result=$this->registro->get("db")->query($sql_usuario);
		if (!$result){
			return false;
		}
		$fechaDesde=$this->registro->get("helper")->getDatetimePorMaketime($fechaMktimeDesde);
		$fechaHasta=$this->registro->get("helper")->getDatetimePorMaketime($fechaMktimeHasta);
		while ($row=$result->fetch_array()){
			$sql_anuncio="SELECT a.titulo,a.fechaHora,s.subcatname,a.subcatid FROM ".$this->tabla_anuncios.$row['catid']." a INNER JOIN ".$t_subcats." s ON a.subcatid=s.subcatid WHERE a.adid=".$row['adid']." AND a.fechaHora BETWEEN '".$fechaDesde."' AND '".$fechaHasta."'";
			$result_anuncio=$this->registro->get("db")->query($sql_anuncio);
			$anuncio=$result_anuncio->fetch_array();
			if ((!$result_anuncio) || (empty($anuncio))){
				continue;
			}
			$maketime=$this->registro->get("helper")->getMaketime($anuncio['fechaHora']);
			$html.='<tr bgcolor="'.$this->getColorAnuncio($row['estado']).'">
				<td nowrap="nowrap">'.$row['adid'].'</td>
				<td nowrap="nowrap">'.strtolower($this->registro->get("helper")->getFecha($maketime)).'</td>
				<td nowrap="nowrap">'.$row['sitio'].'</td>
				<td nowrap="nowrap">'.strtolower($anuncio['subcatname']).'</td>
				<td nowrap="nowrap"><a href="'.$this->registro->get("helper")->getAdminAnuncioLinkUsuario($row['adid'],$row['catid']).'">'.strtolower($anuncio['titulo']).'</a></td>
				<td nowrap="nowrap">'.$this->getPrecioAnuncio($row['costo'],$row['suscripcion_id']).'</td>
			</tr>';
			$this->cantidad_anuncios++;
		}
		return $html;
	}

	public function esMiAnuncio($adid,$catid,$usuario_id){
		if (!is_bool($this->esMiAnuncio)){
			$q="SELECT count(*) as encontrados FROM ".$this->tabla_usuarios_anuncios." WHERE usuario_id=".$usuario_id." AND catid=".$catid." AND adid=".$adid.";  ";
			$r=$this->registro->get("db")->query($q);
			$fila=$r->fetch_array();
			if ($fila['encontrados']==1){
				$this->esMiAnuncio=true;
			}else{
				$this->esMiAnuncio=false;
			}
		}
		return $this->esMiAnuncio;
	}
	public function getCantidadAnuncios(){
		return $this->cantidad_anuncios;
	}
	public function getPrecioAnuncio($precio,$suscripcion_id=0){
		if ($suscripcion_id!=0){
			return USUARIO_ANUNCIO_SUSCRIP;
		}
		switch ($precio){
			case 0:
				return USUARIO_ANUNCIO_GRATIS;
				break;
			default:		
				return formatMoney($precio,2);		
				break;
		}
	}
	public function cambiarDuracionSesion($segundos){
		$q="UPDATE ".$this->tabla." SET duracion_sesion=".$segundos." WHERE email='".$this->getEmail()."' AND usuario_id=".$this->getUsuarioId().";";
		$result=$this->registro->get("db")->query($q);
		if (!$result){
			return false;
		}else{
			$this->setDuracionSesion($segundos);
			return true;
		}
	}
	public function cambiarCorreo($correo,$correo2){
		if ($this->getEmail()==$correo){
			$mensaje='El correo electrónico que has introducido es el que esta asociado a la cuenta ahora mismo. Intenta con otro correo.';
			return $mensaje;
		}
		if ($correo!=$correo2){
			$mensaje='Las direcciones de correo electrónico que has puesto no coinciden.';
			return $mensaje;
		}
		if (!$this->registro->get("email")->validar($correo)){
			$mensaje='El correo electrónico que has ingresado es invalido. Intenta de nuevo.';
			return $mensaje;
		}
		if ($this->existeOtroHabilitado($correo)){
			$mensaje='Ya existe una cuenta asociada al correo electrónico que has ingresado';
			return $mensaje;
		}
		$q="UPDATE ".$this->tabla." SET email='".$correo."',password=NULL WHERE email='".$this->getEmail()."' AND usuario_id=".$this->getUsuarioId().";";
		$result=$this->registro->get("db")->query($q);
		if (!$result){
			$mensaje='No tienes los permisos necesarios para modificar el correo electrónico de esta cuenta.';
			return $mensaje;
		}else{
			$this->enviarActivacionCambioCorreo($correo);
			$this->setEmail($correo);
			$this->salir();
			return '';
		}
		
	}
	private function getContenidoActivacionCambioCorreo($email,$cod_seguridad,$id_usuario){
		$contenido=file_get_contents($_SERVER['DOCUMENT_ROOT']."/plantillas/cuenta_cambiarcorreo.html");
		$contenido=str_replace("<<CUENTA_CAMBIO_CORREO_LINK>>",$this->registro->get("helper")->getActivacionCuentaLink($email,$id_usuario,$cod_seguridad),$contenido);
		$contenido=str_replace("<<CONTACTANOS_LINK>>",$this->registro->get("helper")->getContactanosLink(),$contenido);
		$contenido=$this->registro->get("helper")->caracteres_html($contenido);
		return $contenido;
	}
	public function enviarActivacionCambioCorreo($correo){
		$asunto=USUARIO_CAMBIO_CORREO;
		$this->registro->get("email")->enviar($asunto,$correo,$this->getContenidoActivacionCambioCorreo($correo,$this->getCodSeguridad(),$this->getUsuarioId()),USUARIO_CAMBIO_CORREO_ACTIVACION);	
	}
	public function getDuracionSesion(){
		return $this->duracionSesion;
	}
	public function setDuracionSesion($segundos){
		$this->duracionSesion=$segundos;
	}
	public function login ($email,$password){
		$mensajes=array();
		if (!$this->registro->get("email")->validar($email)){
			$mensajes[]='El correo electrónico que has ingresado es invalido. Intenta de nuevo.';
			return $mensajes;
		}
		if (!$this->cargar($email)){
			$mensajes[]='No existe ninguna cuenta con el correo electrónico ingresado. Si lo deseas, puedes crearte una cuenta <a href="'.$this->registro->get("helper")->getCrearCuentaHref().'">aquí</a>.';
			return $mensajes;
		}
		
		if ($this->encriptarContrasena($password)!=$this->getPassword()){
			$mensajes[]='Tu contraseña es incorrecta. Intenta de nuevo.';
			return $mensajes;
		}
		$this->entrar();
		return $mensajes;
	}
	public function entrar (){
		$this->registro->get("session")->setSessionCookieDuration($this->getDuracionSesion());
		$this->registro->get("session")->set("email",$this->getEmail());
		$this->registro->get("session")->set("usuario_id",$this->getUsuarioId());
		$this->registro->get("cookie")->set("usuario_id",$this->registro->get('crypt')->encrypt($this->getUsuarioId()));
	}
	private function getContenidoActivacionCuenta($email,$cod_seguridad,$id_usuario){
		$contenido=file_get_contents($_SERVER['DOCUMENT_ROOT']."/plantillas/nuevacuenta.html");
		if (!empty($_POST['suscribirme'])){
			$linkNuevoUsuarioSuscrip=$this->registro->get("helper")->getActivacionCuentaLinkHref($email,$id_usuario,$cod_seguridad).'?ir='.$_POST['ir'].'?suscribirme=1';
			$contenido=str_replace("<<ACTIVE_CUENTA_LINK>>",'<a href="'.$linkNuevoUsuarioSuscrip.'">'.$linkNuevoUsuarioSuscrip.'</a>',$contenido);
		}else{
			$contenido=str_replace("<<ACTIVE_CUENTA_LINK>>",$this->registro->get("helper")->getActivacionCuentaLink($email,$id_usuario,$cod_seguridad),$contenido);
		}
		$contenido=str_replace("<<CONTACTANOS_LINK>>",$this->registro->get("helper")->getContactanosLink(),$contenido);
		$contenido=$this->registro->get("helper")->caracteres_html($contenido);
		return $contenido;
	}
	public function enviarActivacion($email,$cod_seguridad,$id_usuario){
		$asunto=USUARIO_NUEVA_CUENTA_ASUNTO;
		$this->registro->get("email")->enviar($asunto,$email,$this->getContenidoActivacionCuenta($email,$cod_seguridad,$id_usuario),USUARIO_ACTIVACION);	
	}
	public function enviarCuentasAutomaticasLink(){
		$contenido=file_get_contents($_SERVER['DOCUMENT_ROOT']."/plantillas/cuentas_automaticas.html");
		$contenido=str_replace("<<REINICIAR_CONTRASENA_LINK>>",$this->registro->get("helper")->getActivacionCuentaLink($this->getEmail(),$this->getUsuarioId(),$this->getCodSeguridad()),$contenido);
		$contenido=str_replace("<<CONTACTANOS_LINK>>",$this->registro->get("helper")->getContactanosLink(),$contenido);
		$contenido=$this->registro->get("helper")->caracteres_html($contenido);
		$asunto="crea tu cuenta y administra tus anuncios mas fácilmente";
		if ($this->registro->get("email")->enviar($asunto,$this->getEmail(),$contenido,CUENTAS_AUTOMATICAS)){
			return true;
		}else{
			return false;
		}
	}
	public function enviarReiniciarContrasenaLink(){
		$contenido=file_get_contents($_SERVER['DOCUMENT_ROOT']."/plantillas/reiniciocontrasena.html");
		$contenido=str_replace("<<REINICIAR_CONTRASENA_LINK>>",$this->registro->get("helper")->getActivacionCuentaLink($this->getEmail(),$this->getUsuarioId(),$this->getCodSeguridad()),$contenido);
		$contenido=str_replace("<<CONTACTANOS_LINK>>",$this->registro->get("helper")->getContactanosLink(),$contenido);
		$contenido=str_replace("<<EMAIL>>",$this->getEmail(),$contenido);
		$contenido=$this->registro->get("helper")->caracteres_html($contenido);
		$asunto="clasilistados: petición para reiniciar tu contraseña para la cuenta ".$this->getEmail();
		if ($this->registro->get("email")->enviar($asunto,$this->getEmail(),$contenido,USUARIO_REINICIO_CONTRASENA)){
			return true;
		}else{
			return false;
		}
	}
	private function encriptarContrasena($contrasena){
		global $salt;
		$hash = md5($salt.$contrasena);
		return $hash;
	}
	public function cambiarContrasena($password,$id_usuario,$email,$cod_seguridad){
		$hash=$this->encriptarContrasena($password);
		$sql="UPDATE ".$this->tabla." SET password='".$hash."',estado='".USUARIO_CON_PASSWORD."' WHERE cod_seguridad='".$cod_seguridad."' AND usuario_id=".$id_usuario." AND email='".$email."' ;";
		$result=$this->registro->get("db")->query($sql);
		if ($result){
			return true;
		}else{
			return false;
		}
	}
	public function intentarLogin(){
		$usuario_id=$this->registro->get("cookie")->get("usuario_id");
		if (!empty($usuario_id)){
			$usuario_id=$this->registro->get("crypt")->decrypt($usuario_id);
			if ($this->cargarPorUsuarioId($usuario_id)){
				$this->entrar();	
				return true;
			}
		}
		return false;
	}
	public function estaLogueado(){
		$logueado=($this->usuario_id>0);
		if (!$logueado){
			$logueado=$this->intentarLogin();	
		}
		return $logueado;
	}
	public function setCodSeguridad($cod_seguridad){
		$this->cod_seguridad=$cod_seguridad;
	}
	public function getCodSeguridad(){
		return $this->cod_seguridad;
	}
	public function setPassword($password){
		$this->password=$password;
	}
	public function getPassword(){
		return $this->password;
	}
	public function setUsuarioId($usuario_id){
		$this->usuario_id=$usuario_id;
	}
	public function getUsuarioId(){
		return $this->usuario_id;
	}
	public function setCityId($cityid){
		$this->city_id=$cityid;
	}
	public function getCityId(){
		return $this->city_id;
	}
	public function setStateId($stateId){
		$this->stateid=$stateId;
	}
	public function getStateId(){
		return $this->stateid;
	}
	public function setEstado($estado){
		$this->estado=$estado;
	}
	public function getEstado(){
		return $this->estado;
	}
	public function setEmail($email){
		$this->email=$email;
	}
	public function getEmail(){
		return $this->email;
	}
}
?>