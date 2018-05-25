<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/classes/gtranslate/GTranslate.php');

class Feed{
	
	private $tabla='cl2_feeds';
	private $nombre=null;
	private $empresa=null;
	private $email=null;
	private $url_feed=null;
	private $api=null;
	private $enabled=null;
	private $envio=null;
	private $catid=null;
	private $subcatid=null;
	private $actualizar=null;
	private $ult_actualizacion=null;
	private $limite_dia=null;
	private $traductor=null;
	private $anuncios=array();
	private $ult_traduccion=null;
	private $t_feeds_locacion='cl2_feeds_locacion';
	private $feeds_anuncios='cl2_feeds_anuncios';
	private $t_anuncios='cl2_anuncios_';
	private $feed_id=null;
	private $cantidad_traducciones=0;
	const LIMITE_TRADUCCION_CARACTERES_GOOGLE=5000;
	const LIMITE_TRADUCCIONES_GOOGLE=500000;
	const MAX_CARAC_FRASE_TRAD_GUARDAR=70;
	private $rssItemTags= array('title', 'link', 'description', 'author', 'category', 'comments', 'enclosure', 'guid', 'pubDate', 'source');
	private $ftp_url;
	private $ftp_user;
	private $ftp_pass;
	
	public function __construct(Registro $registro=null){
		$this->registro=$registro;
	}
	public function esUnTagRss($tag){
		if (in_array ($tag,$this->rssItemTags)){
			return true;
		}
		return false;
	}
	public function existeURL($url){
		$q="SELECT url_feed,feed_id FROM ".$this->tabla." WHERE url_feed='".$url."' AND enabled='".FEED_HABILITADO."';";
		$result=$this->registro->get("db")->query($q);
		if ($result->num_rows==1){
			$row=$result->fetch_array();
			$this->feed_id=$row['feed_id'];
			return true;
		}else{
			return false;
		}
	}
	public function getCantidadTraducciones(){
		return $this->cantidad_traducciones;
	}
	public function nuevo($nombre,$empresa,$email,$url,$api,$catid,$subcatid,$actualizar){
		$this->nombre=$this->registro->get("db")->real_escape_string($nombre);
		$this->empresa=$this->registro->get("db")->real_escape_string($empresa);
		$this->url_feed=$this->registro->get("db")->real_escape_string($url);
		$this->email=$this->registro->get("db")->real_escape_string($email);
		$this->ult_actualizacion=0;
		$this->actualizar=$actualizar;
		$q="INSERT INTO ".$this->tabla." (nombre,empresa,email,url_feed,api,enabled,envio,catid,subcatid,actualizar) VALUES ('".$this->nombre."','".$this->empresa."','".$this->email."','".$this->url_feed."','".$api."','".FEED_HABILITADO."','".FEED_ENVIO_INHABILITADO."',$catid,$subcatid,$actualizar);";
		$result=$this->registro->get("db")->query($q);
		$this->feed_id=$this->registro->get("db")->insert_id();
		if ($result){
			return $this->feed_id;
		}else{
			return false;
		}
	}
	public function getAnunciosFeed($feed_id){
		$q="SELECT adid,catid FROM ".$this->feeds_anuncios." WHERE feed_id=".$feed_id." AND enabled='1';";
		$result=$this->registro->get("db")->query($q);
		if ($result){
			while ($row = $result->fetch_array()){
				$this->anuncios[]=array("adid"=>$row['adid'],"catid"=>$row['catid']);
			}
			return true;
		}else{
			return false;
		}
	}
	public function borrarAnunciosTodos($feed_id){
		if (count($this->anuncios)==0){
			return false;
		}
		foreach ($this->anuncios as $anuncio){
			$q="DELETE FROM ".$this->t_anuncios.$anuncio['catid']." WHERE adid=".$anuncio['adid']." AND feed_id=".$feed_id.";";	
			$result=$this->registro->get("db")->query($q);
			$q="UPDATE ".$this->feeds_anuncios." SET enabled='0' WHERE adid=".$anuncio['adid']." AND catid=".$anuncio['catid']." AND feed_id=".$feed_id.";";
			$result=$this->registro->get("db")->query($q);
		}		
	}	
	public function getNombre(){
		return $this->nombre;
	}
	public function getEmpresa(){
		return $this->empresa;
	}
	public function getEmail(){
		return $this->email;
	}
	public function getUrlFeed(){
		return $this->url_feed;
	}
	public function getApi(){
		return $this->api;
	}
	public function getEnabled(){
		return $this->enabled;
	}
	public function getEnvio(){
		return $this->envio;
	}
	public function getLimiteDia(){
		return $this->limite_dia;
	}
	public function getUltActualizacion(){
		return $this->ult_actualizacion;
	}
	public function getActualizar(){
		return $this->actualizar;
	}
	public function getFeedId(){
		return $this->feed_id;
	}
	public function setFeedId($id){
		$this->feed_id=$id;
	}
	public function actualizarTiempoInicio($time,$inicio){
		$q="UPDATE ".$this->tabla." SET ult_actualizacion='".$time."',limite_dia='".$inicio."' WHERE feed_id=".$this->feed_id.";";
		$result=$this->registro->get("db")->query($q);
		return $result;
	}
	public function actualizar($time){
		$q="UPDATE ".$this->tabla." SET ult_actualizacion='".$time."' WHERE feed_id=".$this->feed_id.";";
		$result=$this->registro->get("db")->query($q);
		return $result;
	}
	public function validarNuevo($email,$nombre,$url_feed){
		$errores=array();
		if (!$this->registro->get('email')->validar($email)){
			$errores[]='El correo electrónico que has ingresado no es valido.';
		}
		if (empty($nombre)){
			$errores[]='Debes ingresar tu nombre.';
		}
		if (empty($url_feed)){
			$errores[]='Debes ingresar una URL valida para tu archivo.';
		}
		if (count($errores)==0){
			return true;
		}
		return $errores;
	}
	public function getCiudadIdDondeSubeFeed(){		
		$abreviacion=$this->registro->get("locacion")->getAbreviacion();
		if (empty($abreviacion)){
			return false;
		}
		$ciudadesDestPais=$this->registro->get("locacion")->getCiudadesDestacadasdelPais();
		$ciudadesCandidatas=array();
		foreach ($ciudadesDestPais as $ciudad){
			if ($ciudad['abreviacion']==$abreviacion){
				$ciudadesCandidatas[]=$ciudad;
			}
		}
		if (count($ciudadesCandidatas)==1){
			//si una sola ciudad es destacada (show_city=1) del estado que seleccione devuelvo esa
			return $ciudadesCandidatas[0]['cityid'];
		}elseif (count($ciudadesCandidatas)>1){
			$posAleatoria=rand(0, count($ciudadesCandidatas)-1);
			//devuelve una cuidad aleatoria de las ciudades candidatas
			return $ciudadesCandidatas[$posAleatoria]['cityid'];
		}else{
			$ciudadesImpAbrrev=$this->registro->get("locacion")->getCiudadesImpPorAbreviacionEstado();
			if (empty($ciudadesImpAbrrev)){
				return false;
			}
			foreach ($ciudadesImpAbrrev as $ciudadImpAbr){
				if ($ciudadImpAbr['rel_of_state']==1){
					//la primera rel_of_state que encuentra de las ciudades del estado
					return $ciudadImpAbr['cityid'];
				}
			}
			// no se donde ponerlo
			return false;
		}
	}
	public function cargar($id){
		$q="SELECT nombre,empresa,email,url_feed,api,enabled,envio,catid,subcatid,actualizar,ult_actualizacion,limite_dia FROM ".$this->tabla." WHERE feed_id=".$id.";";
		$result=$this->registro->get("db")->query($q);
		if ($result){
			$row=$result->fetch_array();
			$this->nombre=$row['nombre'];
			$this->empresa=$row['empresa'];
			$this->email=$row['email'];
			$this->url_feed=$row['url_feed'];
			$this->api=$row['api'];
			$this->enabled=$row['enabled'];
			$this->envio=$row['envio'];
			//$this->registro->get("categoria")->getSubCategoriaData($row['subcatid']);
			$this->actualizar=$row['actualizar'];
			$this->ult_actualizacion=$row['ult_actualizacion'];
			$this->limite_dia=$row['limite_dia'];
			$this->feed_id=$id;
			return true;
		}else{
			return false;
		}
	}
	public function traducirEnAEs($str){
		if ($this->buscarTraduccion($str)){
			return $this->ult_traduccion;
		}
		if ($this->cantidad_traducciones>self::LIMITE_TRADUCCIONES_GOOGLE){
			
			die('paso el limite de las traducciones');	
		}
		$traducido=$this->traductor->en_to_es($str);
		$this->cantidad_traducciones++;
		$this->guardarTraduccion($str,$traducido);
		if (is_null($traducido)){
			return $str;
		}
		return $traducido;
	}
	public function traducir($str){
		if (is_null($this->traductor)){
			$this->traductor=new Gtranslate;
		}
		$str=trim($str);
		if (!$this->verficarLimiteTraduccion($str)){
			$resultado="";
			$pos=0;
			while ($pos <= strlen($str)) {
				$partialQuery = substr($str,$pos,$pos+self::LIMITE_TRADUCCION_CARACTERES_GOOGLE); 
				$posEnd = max(strrpos($partialQuery,"."), strrpos($partialQuery,"!"), strrpos($partialQuery,"?"));
				 if ($posEnd == 0 || $posEnd == false) {
				     $posEnd = self::LIMITE_TRADUCCION_CARACTERES_GOOGLE;
				 } else {
				     $posEnd = $posEnd + 1; // since we got result as index which starts at 0, we need to increase it by 1
				 }
				 $partialQuery = substr($str,$pos,$posEnd);
				 if (strlen(trim($partialQuery)) > 0) {
				 	$resultado.=$this->traducirEnAEs($partialQuery);
				 	
				 }
				 $pos = $pos + $posEnd;
			}
			$str=$resultado;
		}else{
			$str=$this->traducirEnAEs($str);
		}
		return $str;
		
	}
	public function guardarTraduccion($ingles,$espanol){
		global $t_traducciones;
		//si es nula la traduccion a espanol es porque no se tradujo nada, guardo este valor asi no vuelvo a pedir su traduccion
		if (is_null($espanol)){
			$q="INSERT INTO ".$t_traducciones." (text_ingles,text_espanol) VALUES ('".$ingles."','".$ingles."');";
			$this->registro->get("db")->query($q);	
			return true;
		}
		if ((strlen($espanol)<=self::MAX_CARAC_FRASE_TRAD_GUARDAR) && (strlen($ingles)<=self::MAX_CARAC_FRASE_TRAD_GUARDAR)){
			$q="INSERT INTO ".$t_traducciones." (text_ingles,text_espanol) VALUES ('".$ingles."','".$espanol."');";
			$this->registro->get("db")->query($q);	
			return true;
		}else{
			return false;
		}
		
	}
	public function cantidadAnunciosPorFeedId($feed_id){
		if (empty($feed_id)){
			return false;
		}
		$q="SELECT count(*) as cantidad FROM ".$this->feeds_anuncios." WHERE feed_id=".$feed_id.";";
		$r=db::getInstance()->query($q);
		if (!$r){
			return false;
		}
		$row=$r->fetch_array();
		return $row['cantidad'];
	}
	public function buscarTraduccion($ingles){
		global $t_traducciones;
		if ((strlen($ingles)<=self::MAX_CARAC_FRASE_TRAD_GUARDAR)){
			$q="SELECT text_espanol FROM ".$t_traducciones." WHERE text_ingles='".$ingles."';";
			$result=$this->registro->get("db")->query($q);	
			if (($result) && ($result->num_rows==1)){
				$row=$result->fetch_array();
				$this->ult_traduccion=$row['text_espanol'];
				return true;
			}
			return false;
		}else{
			return false;
		}
		
	}
	public function camposOblTraduccion($item){
		$item['description']=$this->traducir($item['description']);
		$item['title']=$this->traducir($item['title']);
		return $item;
	}
	public function verficarLimiteTraduccion($str){
		$caracteresATraducir=strlen($str);
		if ($caracteresATraducir>=self::LIMITE_TRADUCCION_CARACTERES_GOOGLE){
			return false;
		}
		return true;
	}
	public function conectarFtp($user,$pass,$url){
		$this->ftp_user=$user;
		$this->ftp_pass=$pass;
		$this->ftp_url=$url;
	}
	public function bajarArchivo($dest,$arc_ftp){
		passthru("wget -P ".$dest." ftp://$this->ftp_user:$this->ftp_pass@$this->ftp_url".$arc_ftp);
	}
	private function eliminarCantidadFeedsPorCiudad($ciudad_id,$estado_id){
		if (!empty($ciudad_id)){
			$q="DELETE FROM ".$this->t_feeds_locacion." WHERE cityid=$ciudad_id;";
		}else{
			$q="DELETE FROM ".$this->t_feeds_locacion." WHERE stateid=$estado_id;";
		}
		$result=db::getInstance()->query($q);
		if ($result) {
			return true;
		}else{
			return false;			
		}
	}
	public function guardarCantidadFeedsPorCiudad($cantidad){
		$locacion=$this->registro->get("locacion");
		$ciudad_id=$locacion->getCiudadId();
		$estado_id=$locacion->getEstadoId();
		if (!$this->eliminarCantidadFeedsPorCiudad($ciudad_id,$estado_id)){
			echo "no elimino ".$ciudad_id." ".$estado_id;
		}
		if (!empty($ciudad_id)){
			$id=$ciudad_id;
			$q="INSERT INTO ".$this->t_feeds_locacion." (cityid,cantidad) VALUES (?,?) ";
		}else{
			$id=$estado_id;
			$q="INSERT INTO ".$this->t_feeds_locacion." (stateid,cantidad) VALUES (?,?) ";
		}
		if ($stmt = db::getInstance()->prepare($q)) {
		   	$stmt->bind_param("ii", $id,$cantidad);
		   	$stmt->execute();
		   	$stmt->close();
			return true;
		}else{
			return false;			
		}
	}
	public function cantidadCiudadesFeedLocaion(){
		$locacion=$this->registro->get("locacion");
		$q="SELECT count(*) as contador FROM ".$this->t_feeds_locacion;
		if ($stmt=db::getInstance()->prepare($q)) {
			  $stmt->execute();
			  $stmt->bind_result($contador);
			  $stmt->fetch();
			  $stmt->close();
			  return $contador;
		}else{
			return false;
		}
	}
	public function getLocacionConMenosCantidadyMasViejo(){
		$cityid=null;
		$statetid=null;
		$q="SELECT cityid,stateid FROM ".$this->t_feeds_locacion." ORDER BY tiempo ASC LIMIT 1;";
		$result=db::getInstance()->query($q);
		if ($result) {
			$row=$result->fetch_array();  
			return array("ciudad_id"=>$row['cityid'],"estado_id"=>$row['stateid']);
		}else{
			return false;
		}
	}
}
?>