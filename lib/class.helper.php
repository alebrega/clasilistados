<?php
class Helper{
	
	private $host=null;
	private $crossPostingenabled=false;
	private $registro=null;
	
	public function __construct() {
		$this->host='http://'.$_SERVER['SERVER_NAME'];
		$this->host=str_replace("www.","",$this->host);
	}
	public function setZonaSegura(){
		if ($_SERVER["SERVER_PORT"] == 80)
		{
			$this->host='http://'.$_SERVER['SERVER_NAME'];
		}
		elseif($_SERVER["SERVER_PORT"] == 443)
		{
			$this->host='http://'.$_SERVER['SERVER_NAME'];
		}
	}
	public function getMensajeDestacado(){
		return '<span style="font-weight:bold;">destacado</span>: Primeras posiciones del listado de anuncios y resultados de búsqueda';
	}
	public function getMensajeUrgente(){
		return '<span style="color:red;">urgente!</span>: Obtén más respuestas dándole carácter de urgente a tu anuncio';
	}
	function getSmallLink($longurl){  
	   	$url = "http://api.bit.ly/shorten?version=2.0.1&longUrl=$longurl&login=clasilistados&apiKey=R_edcbe84215dea08e9a2ff7d86debfdd9&format=json&history=1"; 
	   	$s = curl_init();  
	    curl_setopt($s,CURLOPT_URL, $url);  
	  	curl_setopt($s,CURLOPT_HEADER,false);  
	   	curl_setopt($s,CURLOPT_RETURNTRANSFER,1);  
	   	$result = curl_exec($s);  
	   	curl_close( $s );  
	   	$obj = json_decode($result, true);  
	   	return $obj["results"]["$longurl"]["shortUrl"];  
   }  
	public function getHttpsHost(){
		return 'http://'.$_SERVER['SERVER_NAME'];
	}
	public function getTrackingUrl(){
		return $this->host.'/track';
	}
	public function limpiar($valor)
	{
		//permitimos solo letras(a-Z), numeros y guion del medio
		return preg_replace('/[^a-zA-Z0-9-]/', '', $valor);
	}
	public function pasarMinuscTrim($str){
		return trim(strtolower($str));
	}
	public function validarURL($url){
		if (@fopen($url, "r")) {
		 return true;
		} else {
		 return false;
		}
	}
	public function getPublicacionLink($catid,$subcats=array()){
		if (empty($subcats)){
			return $this->getPublicacionesPagasLink().'cat-'.$catid;
		}else{
			return $this->getPublicacionesPagasLink().'cat-'.$catid.'_'.implode('_',$subcats);
		}
	}
	function validarDominio($url) {
	 //fsockopen -> Abrir una conexión de sockets de dominio de Internet o Unix
	 //resource fsockopen ( string destino, int puerto [, int errno [, string errstr [, float tiempo_espera]]])
		 $validar = @fsockopen($url, 80, $errno, $errstr, 15);
		 if ($validar) {
		  fclose($validar);
		  return true;
		 }else
		  return false;
	}
	
	public function getHost($seguro=false){
		global $dev_env;
		if ($seguro && !$dev_env){
			return $this->getHttpsHost();
		}
		return $this->host;
	}
	public function link_asociarseClasi(){
		return $this->host.'/asociarse-con-clasilistados';
	}
	public function getMaketime($datetime){
		$fechaHora = explode(' ', $datetime);
		$arrayfecha = explode('-', $fechaHora[0]);
		$hora = explode(':', $fechaHora[1]);
		$fechaint = mktime($hora[0],$hora[1],$hora[2], $arrayfecha[1], $arrayfecha[2], $arrayfecha[0]);
		return $fechaint;
	}
	public function getFechaInsertarAnuncio($maketime){
		return date("Y",$maketime)."-".date("m",$maketime)."-".date("d",$maketime)." ".date("h:i:s",$maketime);
	}
	public function getFecha($maketime){
		$mesLetras=date("F",$maketime);
		$hora=date("H:i:s A",$maketime);
		$mes=calendario::getMesEspanol($mesLetras);
		$mes=substr($mes,0,3);
		return date("j",$maketime)."-".$mes."-".date("Y",$maketime).", ".$hora;
		
	}
	public function getDatetimePorMaketime($maketime){
		return date("Y",$maketime)."-".date("m",$maketime)."-".date("d",$maketime)." ".date("H:i:s",$maketime);
	}
	public function getFechaSolo($maketime){
		$mesLetras=date("F",$maketime);
		$mes=calendario::getMesEspanol($mesLetras);
		$mes=substr($mes,0,3);
		return date("j",$maketime)."-".$mes."-".date("Y",$maketime);
		
	}
	public function getFechaDiaMesAnioLetras($maketime){
		$mesLetras=date("F",$maketime);
		$mes=calendario::getMesEspanol($mesLetras);
		return date("j",$maketime)." de ".$mes." del ".date("Y",$maketime);
		
	}
	public function validarLongitudMayorCero($valor){
		if (!empty($valor)){
			return true;
		}else{
			return false;
		}
	}
	public function getCaptchaLink ($c1,$c2){
		return $this->host.'/captchaimg/'.$c2.'/'.$c1;
	}
	public function getFechaSoloMesyDia($fecha,$fechaint=null){
		if (is_null($fechaint)){
			$arrayfecha = explode('-', $fecha);
			$fechaint = mktime(0,0,0, $arrayfecha[1], $arrayfecha[2], $arrayfecha[0]);
		}
		return date('j', $fechaint).'/'.date('m', $fechaint);  
	}
	public function getFechaDateTime($fecha){
		$arrayfecha = explode('-', $fecha);
		$dia = explode(' ', $arrayfecha[2]);
		$fechaint = mktime(0,0,0, $arrayfecha[1], $dia[0], $arrayfecha[0]);
		$mes=strtolower(substr(calendario::getMesEspanol(date("F",$fechaint)),0,3));
		$dia= date('j', $fechaint);  
		$anio= date('Y', $fechaint);  
		return $dia.' '.$mes.' '.$anio;
	}
	public function getFechaEventoLink($dia,$mes,$ano){
		global $locacion;
		//return $this->getHost().'/'.$locacion->getFiltroLocation().'/eventos/'.$dia.'-'.$mes.'-'.$ano;
		return $this->getHost().'/'.$locacion->getFiltroLocation().'/eventos/'.$ano.'-'.$mes.'-'.$dia;
	}
	public function getFechaAnuncios($fecha,$conDiaSemana=true){
		$arrayfecha = explode('-', $fecha);
		$fechaint = mktime(0,0,0, $arrayfecha[1], $arrayfecha[2], $arrayfecha[0]);
		$dia_semana=strtolower(substr(calendario::dia_semana($fechaint),0,3));
		$mes=strtolower(substr(calendario::getMesEspanol(date("F",$fechaint)),0,3));
		$dia= date('j', $fechaint);  
		if ($conDiaSemana){
			$this->fechaAnuncio=$dia_semana.' '.$dia.' '.$mes;
		}else{
			$this->fechaAnuncio=$dia.' '.$mes;
		}
		return $this->fechaAnuncio;
	}
	public function getFormatoFechaEvento ($arrayfecha){
		$fechaint = mktime(0,0,0, $arrayfecha[1], $arrayfecha[2], $arrayfecha[0]);
		$dia_semana=strtolower(calendario::dia_semana($fechaint));
		$mes=strtolower(calendario::getMesEspanol(date("F",$fechaint)));
		$dia= date('j', $fechaint);  
		return $dia_semana.', '.$dia.' de '.$mes;
	}
	public function getNoHablasEspanol(){
		return '<a href="'.$this->host.'/dontspeak">don\'t speak spanish?</a>';
	}
	public function getMejorasLink (){
		return '<a href="'.$this->host.'/sugerencias">vota en clasilistados</a>';
	}
	public function getIngresarMiCuentaLink ($seguro=true){
		return '<a href="'.$this->getMiCuentaLink($seguro).'" rel="nofollow">ingresar a mi cuenta</a>';
	}
	public function getVolverMiCuentaLink (){
		return '<a href="'.$this->getMiCuentaLink().'">volver a mi cuenta</a>';
	}
	public function getMiCuentaLink ($seguro=true){
		return $this->getHost($seguro).'/mi-cuenta';
	}
	public function getCrearCuentaHref (){
		return $this->getMiCuentaLink().'/crear';
	}
	public function getCrearCuentaLink (){
		return '<a href="'.$this->getMiCuentaLink().'/crear" rel="nofollow"><small>(Crear mi cuenta)</small></a>';
	}
	public function getCrearCuentaAqui (){
		return '<a href="'.$this->getMiCuentaLink().'/crear" target="_blank">aquí</a>';
	}
	public function getLegalAbuAyudaLinkHref(){
		return $this->host.'/legal-abusos-ayuda';
	}
	public function getLegalAbuAyudaLink(){
		return '<a href="'.$this->host.'/legal-abusos-ayuda" rel="nofollow">legal, abusos, ayuda</a>';
	}
	public function getLogoLink(){
		return '<a href="'.$this->host.'/sitios">clasilistados</a>';
	}
	public function getHomeCiudadLink(){
		global $locacion;
		return $this->host.'/'.$locacion->getFiltroLocation();
	}
	function caracteres_html($texto){
      $texto = htmlentities($texto, ENT_NOQUOTES, 'UTF-8'); // Convertir caracteres especiales a entidades
      $texto = htmlspecialchars_decode($texto, ENT_NOQUOTES); // Dejar <, & y > como estaban
      return $texto;
  	}
	function br2nl($str) {
    	$str = preg_replace("/(\r\n|\n|\r)/", "", $str);
    	return preg_replace("=<br */?>=i", "\n", $str);
	}
	
	public function getDescripcionValida($descripcion){
		global $tags_permitidos;
		//inserto enlaces
		$descripcion=$this->insertarEnlaces($descripcion);
   		$descripcion = str_replace("\r", '', $descripcion);
    	$descripcion = preg_replace('/(?<!>)\n/', "<br />\n", $descripcion);
    	$descripcion = Filter::getInstance()->filter_html(strip_tags($descripcion,$tags_permitidos));
		return $descripcion;
	}
	public function insertarNoFollowLinks ($str) {
		$str = stripslashes($str);
		$preg = "/<[\s]*a[\s]*href=[\s]*[\"\']?([\w.-]*)[\"\']?[^>]*>(.*?)<\/a>/i";
		preg_match_all($preg, $str, $match);
		foreach ($match[1] as $key=>$val) {
			$pattern[] = '/'.preg_quote($match[0][$key],'/').'/';
			if ($val=="http"){
				$url=$val.'://'.$match[2][$key];
				$replace[] = "<a href='$url' >$url</a>";
			}else{
				$replace[] = "<a href='$val'>{$match[2][$key]}</a>";
			}
		}
		return preg_replace($pattern, $replace, $str);
		
	}
	public function convertUrlsToLinks($text_with_raw_URLs){
		$pattern = "@\b(https?://)?(([0-9a-zA-Z_!~*'().&=+$%-]+:)?[0-9a-zA-Z_!~*'().&=+$%-]+\@)?(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-zA-Z_!~*'()-]+\.)*([0-9a-zA-Z][0-9a-zA-Z-]{0,61})?[0-9a-zA-Z]\.[a-zA-Z]{2,6})(:[0-9]{1,4})?((/[0-9a-zA-Z_!~*'().;?:\@&=+$,%#-]+)*/?)@";
		$text_with_hyperlink = preg_replace($pattern, '<a href="\0">\0</a>', $text_with_raw_URLs);
		return $text_with_hyperlink;
	}
	function createlinks($strtoconvert) {
		$strtoconvert = preg_replace("/((http(s?):\/\/)¦(www\.))([\w\.]+)/i", "<a href=\"http$3://$4$5\">$2$4$5</a>", $strtoconvert);
		$strtoconvert = preg_replace("/([\w\.]+)(@)([\w\.]+)/i", "<a href=\"mailto:$0\">$0</a>", $strtoconvert);
		echo $strtoconvert;
	}	 
	public function insertarEnlaces($texto){
		if (strlen($texto)<CANTIDAD_CARACTERES_MAXIMO_ENLACE_AUTOMATICO){
			$texto= ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\" >\\0</a>", $texto);
		}
		return $texto;
		
	}
	public function eliminoHTMLEnlaces($texto){
		global $tags_permitidos;
		if (strlen($texto)<CANTIDAD_CARACTERES_MAXIMO_ENLACE_AUTOMATICO){
			$permitidos=str_replace('<a>','',$tags_permitidos);
			$texto=strip_tags($texto, $permitidos);
		}
		return $texto;
	}
	public function getHomeLink(){
		global $locacion;
		return '<a href="'.$this->host.'/'.$locacion->getFiltroLocation().'">clasilistados</a>';
	}
	public function getEliminaAnuncioAnuncioURL($id,$hashCode,$titulo){
		global $locacion,$categoria,$crypt;
		return $this->host.'/'.$locacion->getFiltroLocation().'/'.$this->getSeoUrl($categoria->getSubCategoriaNombre()).'--cat-'.$categoria->getSubCategoriaId().'/'.$this->getSeoUrl($titulo).'--cl-'.$id.'/eliminar-'.$hashCode;
	}
	public function getEditaAnuncioURL($id,$hashCode,$titulo){
		global $locacion,$categoria;
		return $this->host.'/'.$locacion->getFiltroLocation().'/'.$this->getSeoUrl($categoria->getSubCategoriaNombre()).'--cat-'.$categoria->getSubCategoriaId().'/'.$this->getSeoUrl($titulo).'--cl-'.$id.'/editar-'.$hashCode;
	}
	public function getAnuncioLink($id,$titulo,$catid,$subcatid,$subcat_nombre){
		global $locacion,$categoria,$cats,$crypt;
		$categoriaNombre=array_search($catid, $cats);
		if(is_null($subcatid)){
			$subcatid=$categoria->getSubCategoriaId();
			$subcat_nombre=$categoria->getSubCategoriaNombre();
		}
		$url_locacion=$locacion->getFiltroLocation();
		if (empty($url_locacion)){
			$url_locacion=$url_locacion_set;
		}
		return $this->host.'/'.$url_locacion.'/'.$this->urls_amigables($categoriaNombre).'/'.$this->urls_amigables($subcat_nombre).'/'.$this->urls_amigables($titulo).'/categoria-'.$catid.'-subcategoria-'.$subcatid.'-id-'.$id;
	}
	public function linkAnuncio($id,$catid){
		return $this->host.'/id-'.$id.'-cat-'.$catid;
	}
	public function getAdminAnuncioLinkUsuario($id,$catid){
		return $this->getMiCuentaLink().'/admin/anuncio/id-'.$id.'/cat-'.$catid;
	}
	public function getAdminRepublicarAnuncioLink($id,$cod_seguridad,$catid){
		return $this->host.'/admin/anuncio/republicar/id-'.$id.'/cat-'.$catid.'/c-'.$cod_seguridad;
	}
	public function getAdminAnuncioLink($id,$cod_seguridad,$catid){
		return $this->host.'/admin/anuncio/id-'.$id.'/cat-'.$catid.'/c-'.$cod_seguridad;
	}
	public function getActivacionCuentaLinkHref($email,$id,$cod_seguridad){
		return $this->getMiCuentaLink().'/usuario/id-'.$id.'/c-'.$cod_seguridad.'/email-'.$email;
	}
	public function getActivacionCuentaLink($email,$id,$cod_seguridad){
		return '<a href="'.$this->getActivacionCuentaLinkHref($email,$id,$cod_seguridad).'">'.$this->getActivacionCuentaLinkHref($email,$id,$cod_seguridad).'</a>';
	}
	public function getHomeLinkLocation(){
		global $location,$locacion;
		return '<a href="'.$this->host.'/'.$locacion->getFiltroLocation().'" title="clasilistados '.$location.'">clasilistados '.$location.'</a>';
	}
	public function getTitleLocacion(){
		global $location,$locacion;
		if ($locacion->esCiudad()){
			return 'clasificados en '.$location.' '.$locacion->getEstado().', '.$this->getCategoriasTitulo();
		}else{
			return 'clasificados en '.$location.', '.$this->getCategoriasTitulo();
		}
		
	}
	
	public function getGenericTitle(){
		$titulo=NOMBRE_SITIO.': '.$this->getCategoriasTitulo();
		return $titulo;
		//return 'clasilistados: anuncios clasificados de empleos, inmuebles, personales, compra-venta, servicios, vehiculos, mascotas, sociales, curriculum, comunidad y eventos';
	}
	public function getCategoriasTitulo(){
		global $cats;
		$categorias=array_flip($cats);
		$ultima = array_pop($categorias);
		$categorias_titulo='clasificados de '.implode(', ',$categorias).' y '.$ultima;
		return $categorias_titulo;
	}
	public function getAdvertenciaCrossPosting(){
		if (!$this->crossPostingenabled){
		return '
			<div class="noticecrossposting">
			<i>Por favor publica tu anuncio en no m&aacutes de una categoría – esta prohibido publicar el mismo anuncio en diferentes categorías -</i>
			</div>';
		}
		//<i>Por favor publica tu anuncio en una sola ciudad y en no mas de una categoría – esta prohibido publicar el mismo anuncio en diferentes ciudades y categorías -</i>
		return '';
	}
	
	public function getSearchAction(){
		global $locacion;
		return $this->host.'/buscar/';
	}
	public function getValidarPostingAction(){
		global $categoria,$locacion,$crypt;
		$id=$categoria->getSubCategoriaId();
		return $this->getHost(true).'/'.$locacion->getFiltroLocation().'/publicar-anuncios/'.$this->getSeoUrl($categoria->getSubCategoriaNombre()).'--subcat-'.$id.'/val';
	}
	public function getPostingAction(){
		global $categoria;
		return $this->getPostingSubCategoriaLinkHref($categoria->getCategoriaId(),$categoria->getSubCategoriaId());
	}
	public function getAnuncioConfirmacion($codseg,$id){
		return $this->host.'/anuncio_confirmacion_pendiente?codseg='.$codseg.'&id='.$id;
	}
	public function getValorReq($key){
		$valor=empty($_POST[$key])?$_GET[$key]:$_POST[$key];
		return $valor;
	}
	public function getSugerenciasLink(){
		global $locacion;
		return '<a href="'.$this->host.'/contacto" class="sin_decoracion_texto">o sugiere uno nuevo aquí</a>';
	}
	public function getSugeriCategoriasLink(){
		global $locacion;
		return '<p>¿Te gustaría sugerir categorías nuevas? <a href="'.$this->host.'/contacto" rel="nofollow" target="_blank"> Haz clic aquí</a></p>';
	}
	public function getFaqLink(){
		return '<a href="'.$this->host.'/preguntas-frecuentes">preguntas frecuentes</a>';
	}
	public function getSeguridadLink(){
		return '<a href="'.$this->host.'/seguridad">consejos de seguridad</a>';
	}
	public function getAcercaDeLink(){
		return '<a href="'.$this->host.'/acerca-de-clasilistados">acerca de clasilistados</a>';
	}
	public function getLoMejorLink(){
		return '<a href="'.$this->host.'/lo-mejor-de-clasilistados">lo mejor de clasilistados</a>';
	}
	public function getPrivacidadLink(){
		return '<a href="'.$this->host.'/privacidad" rel="nofollow">privacidad</a>';
	}
	public function getEstamosContratandoLink(){
		return '<a href="'.$this->host.'/estamos-contratando">¡estamos contratando!</a>';
	}
	public function getQuienesLink(){
		return '<a href="'.$this->host.'/quienes">quienes somos</a>';
	}
	public function getTermCondicionesLinkHref(){
		return $this->host.'/condiciones';
	}
	public function getTermCondicionesLink(){
		return '<a href="'.$this->host.'/condiciones" rel="nofollow">términos y condiciones</a>';
	}
	public function getAyudaLinkHref(){
		return $this->host.'/legal-abusos-ayuda';
	}
	public function getReiniciarPasswordLink(){
		return $this->getMiCuentaLink().'/reiniciar_contrasena';
	}
	public function getContactanosLink(){
		return '<a href="'.$this->host.'/contacto" rel="nofollow">contáctanos</a>';
	}
	public function getContactanosLinkHref(){
		return $this->host.'/contacto';
	}
	public function getAyudaLink(){
		return '<a href="'.$this->host.'/contacto" rel="nofollow" target="_blank">ayuda</a>';
	}
	public function getEnvioAmigoLink($id,$catid){
		return $this->host.'/envia-anuncio-a-un-amigo/'.$id.'/'.$catid;
	}
	public function getCategoriaLinkHref($id){
		global $registro,$crypt,$cats;
		$categoria=array_search($id, $cats);
		return $this->host.'/'.$registro->get('locacion')->getFiltroLocation().'/'.$this->urls_amigables($categoria).'/categoria-'.$id;
	}
	public function getUrgenteCatLink($id,$nombre){
		global $locacion;
		return $this->host.'/'.$locacion->getFiltroLocation().'/urgente-'.$this->urls_amigables($nombre).'/categoria-'.$id;
	}
	public function getCategoriaLink($id){
		global $locacion,$crypt,$cats;
		$categoria=array_search($id, $cats);
		return '<a href="'.$this->host.'/'.$locacion->getFiltroLocation().'/'.$this->urls_amigables($categoria).'/categoria-'.$id.'" title="'.$categoria.'">'.$categoria.'</a>';
	}
	public function getPostingCategoriaLink($id,$categoria,$texto){
		global $locacion,$crypt;
		return '<a href="'.$this->getPublicacionLink($id).'" rel="nofollow">'.$texto.'</a>';
	}
	public function getPublicacionesPagasLink(){
		global $locacion;
		return $this->getHost(true).'/'.$locacion->getFiltroLocation().'/publicar-anuncios/public_';
	}
	public function getPostingSubCategoriaLinkHref($catid,$id){
		if (empty($id)){
			return $this->getPublicacionLink($catid);
		}
		return $this->getPublicacionLink($catid,array('s-'.$id));
	}
	public function getPostingSubCategoriaLink($catid,$id,$texto){
		return '<a href="'.$this->getPostingSubCategoriaLinkHref($catid,$id).'" rel="nofollow">'.$texto.'</a>';
	}
	public function getPostingFormAction($catid,$id){
		return $this->getPostingSubCategoriaLinkHref($catid,$id);
	}
	public function getElijaTipoAnuncio (){
		return '<h4>Elige el tipo de anuncio que vas a publicar:</h4>';
	}
	public function getElijaCategoria(){
		return '<h4>Por favor, elija una categorí­a:</h4>';
	}	
	
	public function getLinkCiudad($ciudad){
		return '<a href="'.$this->host.'/'.$ciudad["url_estado"].'/'.$ciudad["url_ciudad"].'" title="'.$ciudad["ciudad"].'">'.$ciudad["ciudad"].'</a>';
	}
	public function getLinkCiudadHref($ciudad){
		return $this->host.'/'.$ciudad["url_estado"].'/'.$ciudad["url_ciudad"];
	}
	public function getLinkCiudadEstadoPocoPoblado($ciudad){
		return '<a href="'.$this->host.'/'.$ciudad["url_estado"].'" title="'.$ciudad["ciudad"].'">'.$ciudad["ciudad"].'</a>';
	}
	public function getLinkEstado($estado){
		return '<a href="'.$this->host.'/'.$estado["url_estado"].'" title="'.$estado["estado"].'">'.$estado["estado"].'</a>';
	}
	public function getLinkEstadoHref($url_estado){
		return $this->host.'/'.$url_estado;
	}
	public function getLinkMasciudades(){
		global $locacion;
		return '<a href="'.$this->host.'/masciudades">mas ciudades...</a>';
	}
	public function renderBold ($text){
		return '<b>'.$text.'</b>';
	}
	public function getLinkHomeCiudadHref($sitio){
		return $this->host.'/'.$sitio["url_estado"].'/'.$sitio["url_ciudad"];
	}
	public function getLinkHomeLocacion($sitio){
		$link='<a href="'.$this->host.'/'.$sitio["url_estado"].'/'.$sitio["url_ciudad"].'" title="'.$sitio["ciudad"].'">'.$sitio["ciudad"].'</a>';	
		if ($sitio['negrita']==1){
			$link=$this->renderBold($link);
		}
		return $link;

	}
	public function getSeoUrl($link){
		$link=strtolower($link);
		$link=str_replace("/", "-", $link);
		$link = str_replace("¡", "", $link);
		$link = str_replace("!", "", $link);
		$link = str_replace(".", "-", $link);
		$link = str_replace(" ", "-", $link);
		$link = str_replace("ñ", "n", $link);
		$link = str_replace("á", "a", $link);
		$link = str_replace("é", "e", $link);
		$link = str_replace("í", "i", $link);
		$link = str_replace("ó", "o", $link);
		$link = str_replace("ú", "u", $link);
		$link = urlencode($link);
		return $link;
	}
	function urls_amigables($url) {
	
		// Tranformamos todo a minusculas
		
		$url = strtolower($url);
		
		//Rememplazamos caracteres especiales latinos
		
		$find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
		
		$repl = array('a', 'e', 'i', 'o', 'u', 'n');
		
		$url = str_replace ($find, $repl, $url);
		
		// Añaadimos los guiones
		
		$find = array(' ', '&', '\r\n', '\n', '+','/');
		$url = str_replace ($find, '-', $url);
		
		// Eliminamos y Reemplazamos demás caracteres especiales
		
		$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
		
		$repl = array('', '-', '');
		
		$url = preg_replace ($find, $repl, $url);
	
		return $url;
	
	}
	public function makeLink($accion){
		global $locacion;
		$link=getSeoUrl($accion);	
		return '<a href="'.$this->host.'/'.$locacion->getFiltroLocation().'/'.$link.'">'.$accion.'</a>'; 
	}
	public function getLinkSubcat($subcat){
		global $locacion,$crypt,$cats;
		$categoria=$this->urls_amigables(array_search($subcat['catid'],$cats));
		$link=$this->urls_amigables($subcat["nombre"]);		
		$id = $subcat["id"];
		$catid= $subcat['catid'];
		$nombre=$subcat["nombre"];
		$subcat["nombre"]=$this->abbrevNombreCategoria($subcat["nombre"],$catid);
		return '<a href="'.$this->host.'/'.$locacion->getFiltroLocation().'/'.$categoria.'/'.$link.'/categoria-'.$catid.'-subcategoria-'.$id.'" title="'.$nombre.'">'.$subcat["nombre"].'</a>';
	}
	public function abbrevNombreCategoria($nombre,$catid){
		if ($_SERVER["PHP_SELF"]==HOME_PAGINA){
			switch ($catid){
				case 9:
				case 13:
				case 12:
				case 14:
				case 3:
				case 4:
					$nombre=substr($nombre, 0, 11);
					break;
				default:
					break;
			}
		}
		return $nombre;
	}
	public function getLinkSubcatHref($subcat){
		global $locacion,$crypt,$cats;
		$categoria=$this->urls_amigables(array_search($subcat['catid'],$cats));
		$link=$this->urls_amigables($subcat["nombre"]);		
		$id = $subcat["id"];
		$catid= $subcat['catid'];
		return $this->host.'/'.$locacion->getFiltroLocation().'/'.$categoria.'/'.$link.'/categoria-'.$catid.'-subcategoria-'.$id;
	}
	public function getPostingLink($seguro){
		global $locacion;
		return '<a href="'.$this->getHost($seguro).'/'.$locacion->getFiltroLocation().'/publicar-anuncios" rel="nofollow">publicar anuncios</a>'; 
	}
	public function getPostingHref(){
		global $locacion;
		return $this->getHost(true).'/'.$locacion->getFiltroLocation().'/publicar-anuncios'; 
	}
	public function getPublicarAnunciosLink(){
		return $this->getHost(true).'/publicar-anuncios'; 
	}
	public function NotFound(){
		header("HTTP/1.0 404 Not Found");
		header("Location: ".$this->host."/error/notfound.html"); 
		exit;
	}
	public function irSeleccionaCiudad($estado){
		header("Location: ".$this->host."/".$estado);
		exit();
	}
	public function ir($locacion){
		header("Location: ".$locacion);
		exit();
	}
	public function irMicuenta(){
		header("Location: ".$this->getMiCuentaLink());
		exit();
	}
	public function irSitios(){
		header("Location: ".$this->host."/sitios");
	}
	public function irHome(){
		header("Location: ".$this->host);
	}
	public function getMensEscojaCiudad(){
		return '<h4>escoge la ciudad mas cercana adonde te encuentres (<a href="'.$this->host.'/contacto" class="sin_decoracion_texto">o sugiere una nueva</a>):</h4>';
	}
	public function renderCampoPrecioCompraVenta()
	{
		global $anuncio;
		echo '<td valign="top" align="center"><span class="std">Precio:</span><br>$<input tabindex="1" size="5" maxlength="7" name="precio" value="'.$anuncio->getPrecio().'"></td>';
	}
	public function renderPrecio(){
		global $anuncio;
		echo '<td valign="top" align="left"><span class="std">&nbsp;&nbsp;&nbsp;Precio:</span><br/>$ <input name="precio" maxlength="10" size="10" tabindex="1" value="'.$anuncio->getPrecio().'" /></td>';
	}
	public function getMensajeItemsProhibidosListados(){
		echo '<a href="'.$this->host.'/prohibido-anunciar" rel="nofollow" target="_blank">lo prohibido en clasilistados</a>';
	}
	public function getFechaHora($dia,$mes,$anio,$hora){
		global $listados;
		$mes=calendario::getMesEspanol($mes);
		return $dia."-".$mes."-".$anio." ".$hora;
	}
	public function getMensajeItemsProhibidos(){
		echo '<p>Antes de publicar un anuncio por favor lee atentamente <a href="'.$this->host.'/prohibido-anunciar" rel="nofollow" target="_blank">la lista de aquello que no esta permitido publicar en clasilistados </a></p>';
	}
	public function getBusquedaEstaticaCategoriaLink($busqueda,$catid){
		global $locacion;
		return $this->host.'/q/'.$locacion->getFiltroLocation().'/'.urlencode($busqueda).'/categoria-'.$catid;
	}
	public function getBusquedaEstaticaSubCategoriaLink($busqueda,$catid,$subcatid){
		global $locacion;
		return $this->host.'/q/'.$locacion->getFiltroLocation().'/'.urlencode($busqueda).'/categoria-'.$catid.'-subcategoria-'.$subcatid;
	}
	public function renderCampoAlquilerVivienda(){
		global $anuncio;
		echo '<td valign="top" align="left"><span class="std">&nbsp;&nbsp;&nbsp;Alquiler:</span><br/>$ <input value="'.$anuncio->getAlquiler().'" name="alquiler" maxlength="11" size="11" tabindex="1"/></td>';
	}
	public function getCopyright(){
		echo '<span class="copyright">© Clasilistados '.date("Y").'</span>';
	}
	public function getAdvPosting(){
	 	echo '¡Por favor <b>no</b> ingreses números de teléfono, correos electrónicos ni enlaces en los anuncios personales!';
	}
	public function renderCampoEdad(){
		global $anuncio;
		echo '<td align="center" valign="top">&nbsp;<span>Edad:</span><br/>&nbsp;<input value="'.$anuncio->getEdad().'" name="edad" maxlength="3" size="5" tabindex="1"/></td>';
	}
	public function renderCampoRetribucion(){
		global $anuncio;
		$class=($anuncio->enRojo("retribucion")) ? 'class="err"':'class="req"';
		echo '	<br /><span '.$class.' >Retribución:   </span>
				<span style="font-size: smaller;" id="compdet">[intenta ser lo más preciso que puedas]</span>
				<br />
				<input value="'.$anuncio->getRetribucion().'" name="retribucion" id="retribucion" size="80" tabindex="1"/>';
	}
	public function renderMasDetallesEmpleo(){
		$checkedTeletrabajo=(!empty($_POST['teletrabajo'])) ? 'checked' : '';
		$checkedTiempoparcial=(!empty($_POST['tiempo_parcial'])) ? 'checked' : '';
		$checkedContrato=(!empty($_POST['contrato'])) ? 'checked' : '';
		$checkedSinlucro=(!empty($_POST['org_sinlucro'])) ? 'checked' : '';
		$checkedPasantia=(!empty($_POST['pasantia'])) ? 'checked' : '';
		echo '	<fieldset>
	<legend><span class="std">Mas detalles:</span><br/></legend>
		<label>
		<input type="checkbox" name="teletrabajo" id="teletrabajo" tabindex="1" '.$checkedTeletrabajo.'/>
			posibilidad de teletrabajo</label>  
		<label>
		<input type="checkbox" name="tiempo_parcial" id="tiempo_parcial" tabindex="1" '.$checkedTiempoparcial.' />
			tiempo parcial</label>  
		<label>
		<input type="checkbox" name="contrato" id="contrato" tabindex="1" '.$checkedContrato.' />
			contrato</label>  
		<label>
		<input type="checkbox" name="org_sinlucro" id="org_sinlucro" tabindex="1" '.$checkedSinlucro.' />
			organización sin ánimo de lucro</label>  
		<label>
		<input type="checkbox" name="pasantia" id="pasantia" tabindex="1"  '.$checkedPasantia.' />
			pasantía</label>
	</fieldset>';
	}
	public function renderPermisosEmpleo(){
		$checkedReclutadores=(!empty($_POST['agencia_busquedas'])) ? 'checked' : '';
		$checkedLlamadas=(!empty($_POST['recibir_llamados'])) ? 'checked' : '';
		echo '<label><input type="checkbox" name="agencia_busquedas" id="ro" tabindex="1" '.$checkedReclutadores.' /> acepto contacto directo por agencias de búsqueda de personal</label>
				<br />
				<label><input type="checkbox" name="recibir_llamados" id="pc" tabindex="1" '.$checkedLlamadas.' /> permito recibir llamadas de telefono sobre este puesto</label>
				<br />';
	}
	public function renderCampoPago(){
		global $anuncio;
		$disabledPago='';
		if ((is_null($anuncio->getPago()) || ($anuncio->getPago()==0))) { 
			$checkedNopago='CHECKED';
			$disabledPago='disabled';
		}else{
			$checkedNopago=	'';
		}
		if ($anuncio->getPago()==1) 
			$checkedPago='CHECKED';
		else
			$checkedPago=''; 
		$html='<br /><span class="std">';
		$html.='<label><input type="radio" id="nopago" value="0" name="pago" onchange="sacarPago();"  '.$checkedNopago.'  /><b>no pago</b></label>';
		$html.='<label><input type="radio" id="pago" value="1" name="pago" onchange="agregarPago();" '.$checkedPago.'  /><b>pago</b>:</label></span>';
		$html.='<span style="font-size: smaller;" id="compdet">  [intenta ser lo más preciso que puedas]</span><br />';
		$html.='<input type="text" value="'.$anuncio->getPago().'" name="pagocampo" id="pagocampo" size="80" tabindex="1" '.$disabledPago.' />';
		return $html;
	}
}
?>