<?php
class Locacion{

	private $esCiudad=false;
	private $esEstado=false;
	private $esPais=false;
	private $ciudad=null;
	private $ciudad_id=null;
	private $estado=null;
	private $estado_id=null;
	private $estado_url=null;
	private $estados=array ();
	private $location=null;
	private $estadoName=null;
	private $abreviacion=null;
	private $esEstadoPocoPoblado=false;
	
	public function __construct ($ciudad=null,$estado=null){
		global $cookie,$h;
		if (!is_null ($estado)){
			if ($this->checkExisteEstado($estado)){
				if ($this->esEstadoPocoPoblado()){
					$cookie->delete("ciudad");
					$cookie->set("estado",$estado);
				}
				$this->setLocation($this->getEstado());
				$this->esEstado=true;
			}
		}elseif (!is_null($ciudad)){
			if ($this->checkExisteCiudad($ciudad)){
				$this->setLocation($this->getCiudad());
				$this->esCiudad=true;
				$cookie->delete("estado");
				$cookie->set("ciudad",$ciudad);
			}
		}else{
			$this->esPais=true;
			$this->setLocation(PAIS);
		}
	}
	public function getAbreviacion(){
		return $this->abreviacion;
	}
	public function setAbreviacion($abbrev){
		$this->abreviacion=$abbrev;
	}
	public function setEsCiudad($bool){
		$this->esCiudad=$bool;
	}
	
	public function getSitioPorCityId_StateId($cityid,$stateid){
		global $db, $t_cities,$t_states,$h;
		$sitio='ninguno';
		if (!empty($cityid)){
			$q="SELECT $t_cities.city_name,$t_states.abbreviation FROM $t_cities INNER JOIN $t_states ON $t_cities.stateid=$t_states.stateid WHERE $t_cities.cityid=".$cityid."; ";    	
	    	$result=$db->query($q);
	    	$row = $result->fetch_assoc();
	    	$sitio=strtolower(trim($row['city_name']))." "."(".trim(strtoupper($row['abbreviation'])).")";
	    	//$ciudad=array("url_estado"=>$this->sacarDominio($row['url_estado']),"url_ciudad"=>$this->sacarDominio($row['url_ciudad']));
	    	//$url=$h->getLinkCiudadHref($ciudad);	
		}elseif(!empty($stateid)){
			$q="SELECT $t_states.name FROM $t_states WHERE stateid=".$stateid."; ";    	
	    	$result=$db->query($q);
	    	$row = $result->fetch_assoc();
	    	$sitio=strtolower($row['name']);
	    	//$url=$h->getLinkEstadoHref($this->sacarDominio($row['url_estado']));	
		}    	
    	return $sitio;
	}
	public  function esEstadoPocoPoblado(){
		return $this->esEstadoPocoPoblado;
	}
	public function esPais(){
		return $this->esPais;
	}
	
	public function esCiudad(){
		return $this->esCiudad;
	}
	public function esEstado(){
		return $this->esEstado;
	}
	public function cargarCiudad(){
		global $db, $t_cities,$t_states;
		$key='CiudadData-Id-'.$this->ciudad_id;
		$row=Cache::get($key);
		if (!$row){
			$q="SELECT $t_cities.city_name,$t_cities.cityid,$t_cities.stateid,$t_cities.cl2_url as url_ciudad,$t_states.cl2_url as url_estado, $t_states.name as nombre_estado FROM $t_cities INNER JOIN $t_states ON $t_cities.stateid=$t_states.stateid WHERE $t_cities.cityid=".$this->ciudad_id."; ";    	
			$result=$db->query($q);
			if (!$result){
				return false;
			}
    		$row = $result->fetch_assoc();
		}
    	if (!empty($row["city_name"])){
    		$this->setCiudadURL($this->sacarDominio($row['url_ciudad']));
    		$this->setEstadoURL($this->sacarDominio($row['url_estado']));
    		$this->setCiudad(strtolower($row["city_name"]));
    		$this->setEstado(strtolower($row["nombre_estado"]));
    		$this->setEstadoId($row["stateid"]);
    		$this->setCiudadId($row['cityid']);
    		$this->setLocation($this->getCiudad());
    		$this->esCiudad=true;
    		Cache::set($key,$row,true,86400);
    		return true;
    	}else{
    		return false;
    	}
	}
	public function checkExisteCiudad($ciudad){
    	global $db, $t_cities,$t_states;
    	$ciudad= $this->agregarDominio($ciudad);
    	$row=Cache::get('CiudadData-Url-'.$ciudad);
    	if (!$row){
    		$q="SELECT $t_cities.city_name,$t_cities.cityid,$t_cities.stateid,$t_cities.cl2_url as url_ciudad,$t_states.cl2_url as url_estado, $t_states.name as nombre_estado FROM $t_cities INNER JOIN $t_states ON $t_cities.stateid=$t_states.stateid WHERE $t_cities.cl2_url='$ciudad'; ";    	
    		$result=$db->query($q);	
    		$row = $result->fetch_assoc();
    		Cache::set('CiudadData-Url-'.$ciudad,$row,true,86400);
    	}
    	if (!empty($row["city_name"])){
    		$this->setCiudadURL($this->sacarDominio($row['url_ciudad']));
    		$this->setEstadoURL($this->sacarDominio($row['url_estado']));
    		$this->setCiudad(strtolower($row["city_name"]));
    		$this->setEstado(strtolower($row["nombre_estado"]));
    		$this->setEstadoId($row["stateid"]);
    		$this->setCiudadId($row['cityid']);
    		return true;
    	}
    	else{
    		return false;
    	}
    }
    public function setLocation($location){
    	$this->location=$location;
    }
    public function getLocation() {
    	return $this->location;
    }
	public function cargarEstado(){
    	global $db, $t_states;
    	$key='EstadoData-Id-'.$this->estado_id;
    	$row=Cache::get($key);
    	if (!$row){
	    	$q="SELECT name, stateid,cl2_url,poco_poblado FROM $t_states WHERE stateid=".$this->estado_id."; ";
	    	$result=$db->query($q);
	    	if (!$result){
	    		return false;
	    	}
	    	$row = $result->fetch_assoc();	
    	}
    	if (!empty($row['name'])){
    		$this->setEstadoURL($this->sacarDominio($row['cl2_url']));
    		$this->setEstado(strtolower($row['name']));
    		$this->setEstadoId($row["stateid"]);
    		$this->setLocation($this->getEstado());
    		$this->esEstado=true;
    		if ($row['poco_poblado']==1){
    			$this->esEstadoPocoPoblado=true;
    		}
    		Cache::set($key,$row,true,86400);
    		return true;
    	}
    	else{
    		return false;
    	}
    }
    public function checkExisteEstado($estado){
    	global $db, $t_states;
    	$estado= $this->agregarDominio($estado);
    	$estadoData=Cache::get('estadoData-'.$estado);
    	if (!$estadoData){
    		$q="SELECT name, stateid,cl2_url,poco_poblado FROM $t_states WHERE cl2_url='$estado'; ";
	    	$result=$db->query($q);
	    	if (!$result){
	    		return false;
	    	}
	    	if ($result->num_rows!=1){
	    		return false;
	    	}
	    	$estadoData = $result->fetch_assoc();
	    	Cache::set('estadoData-'.$estado,$estadoData,true);
    	}
    	$this->setEstadoURL($this->sacarDominio($estadoData['cl2_url']));
    	$this->setEstado(strtolower($estadoData['name']));
    	$this->setEstadoId($estadoData["stateid"]);
    	if ($estadoData['poco_poblado']==1){
    		$this->esEstadoPocoPoblado=true;
    	}
    	return true;
    }
    public function getCiudadesAutoComplete($caractCiudad,$stateid){
    	global $db, $t_states,$t_cities;
    	$estado= $this->agregarDominio($estado);
    	$q="SELECT $t_cities.city_name,$t_cities.cityid, $t_states.abbreviation FROM $t_cities INNER JOIN $t_states ON $t_cities.stateid=$t_states.stateid WHERE $t_cities.stateid=$stateid AND $t_cities.city_name LIKE '$caractCiudad%' LIMIT 10; ";    	
    	$result=$db->query($q);
    	if (!$result){
    		return false;
    	}
    	echo "<ul>";
    	while ($row = $result->fetch_array()){
    		echo '<li onClick="fill(\''.$row['cityid'].'\');">'.$row['city_name'].', '.$row['abbreviation'].'</li>';
    	}
    	echo "</ul>";
    }
    private function limpiar($valor)
	{
		//permitimos solo letras(a-Z), numeros y guion del medio
		return preg_replace('/[^a-zA-Z0-9-_]/', '', $valor);
	}
    public function getEstadoPorId($id) {
    	return $this->estados[$id];
    }
	public function getEstados(){
    	global $db, $t_states;
    	$this->estados=Cache::get("todosEstadosUSA");
    	if (!$this->estados){
	    	$q="SELECT name,cl2_url,poco_poblado,stateid,abbreviation from ".$t_states." where enabled='1' order by name asc; ";
	    	$result=$db->query($q);
	    	while ($row = $result->fetch_array()){
	    		$this->estados[]= array ("abbreviation"=>$row['abbreviation'],"estado"=>strtolower($row['name']), "url_estado"=>$this->sacarDominio($row['cl2_url']),"poco_poblado"=>$row["poco_poblado"],"stateid"=>$row["stateid"]);
	    	}
	    	Cache::set("todosEstadosUSA",$this->estados,true);	
    	}
    	return $this->estados;
    }
    public function validarAbreviacionEstado($abbrev){
   		$valido=false;
	   	foreach ($this->estados as $estado){
	   		if (trim($estado['abbreviation'])==trim($abbrev)){
	   			$valido=true;
	   		}
	   	}
	   	return $valido;
    }
	public function sacarDominio ($locacion){
    	return str_replace(".clasilistados.org", "", $locacion);//le saca el dominio
		
    }
    public function agregarDominio ($locacion){
    	return $locacion.".clasilistados.org";
    }
    public function getFiltroLocation(){
    	if ($this->esCiudad){
			$filtro_zona=$this->getEstadoURL().'/'.$this->getCiudadURL();
		}elseif ($this->esEstado){
			$filtro_zona=$this->getEstadoURL();
		}
		return $filtro_zona;
	}
	public function getEstadoIdPorAbbreviacion($abbrev){
		foreach ($this->estados as $estado){
			if (trim($estado['abbreviation'])==trim($abbrev)){
				return $estado['stateid'];
			}
		}
	}
	public function getCiudadesdelEstado(){
    	global $db, $t_cities,$t_states;
    	$ciudades=Cache::get('ciudadesImportantesDelEstado-'.$this->getEstadoId());
    	if (!$ciudades){
	    	$q="SELECT $t_cities.cityid,$t_cities.city_name as ciudad, $t_cities.is_bold as negrita, $t_cities.cl2_url as url_ciudad FROM $t_cities WHERE $t_cities.stateid=".$this->getEstadoId()." AND rel_of_state=1 ORDER BY show_city DESC;";
	    	$result=$db->query($q);
	    	if ($result->num_rows<=0){
	    		return $ciudades;
	    	}
	    	while ($row = $result->fetch_assoc()){
	    		$ciudades[]= array ("cityid"=>$row['cityid'],"ciudad"=> strtolower($row['ciudad']),"negrita"=> strtolower($row['negrita']),"url_ciudad" => $this->sacarDominio($row["url_ciudad"]), "url_estado"=>$this->getEstadoURL());
	    	}
	    	Cache::set('ciudadesImportantesDelEstado-'.$this->getEstadoId(),$ciudades,true,86400);
    	}
    	return $ciudades;
    }
    public function getTodasLasCiudadesdelEstado(){
    	global $db, $t_cities,$t_states;
    	$ciudades=array();
    	$ciudades=Cache::get("ciudadesDelEstado-".$this->getEstadoId());
    	if (!$ciudades){
    		//california,ny,texas,florida,colorado,north carolina,idaho,illinois,indiana
    		$q="SELECT city_name as ciudad, cl2_url as url_ciudad, is_bold as negrita FROM $t_cities WHERE stateid=".$this->getEstadoId()." and rel_of_state=1 ORDER BY city_name ASC;";
    		$result=db::getInstance()->query($q);
    		while ($row = $result->fetch_assoc()){
    			$ciudades[]= array ("ciudad"=> strtolower($row['ciudad']),"url_ciudad" => $this->sacarDominio($row["url_ciudad"]), "url_estado"=>$this->getEstadoURL(), "negrita"=>$row['negrita']);
    		}
    		Cache::set("ciudadesDelEstado-".$this->getEstadoId(),$ciudades,true,86400);
    	}
    	return $ciudades;
    }
    public function getCiudadURL(){
    	return $this->ciudad_url;
    }
    public function getIdEstado($estado_url){
    	
    }
    public function setCiudadURL($url){
    	$this->ciudad_url=$url;
    }
 	public function setEstadoURL($url){
    	$this->estado_url=$url;
    }
    public function getEstadoURL(){
    	return $this->estado_url;
    }
    
 	public function getCiudad (){
    	return $this->ciudad;
    }
    public function getEstadoId(){
    	return $this->estado_id;
    }
	public function getEstado(){
    	return $this->estado;
    }
    public function setEstadoId($estado_id){
    	$this->estado_id=$estado_id;
    }
    
	public function setCiudad($ciudad){
    	$this->ciudad=$ciudad;
    }
    public function getCiudadId(){
    	return $this->ciudad_id;
    }
    
    public function setCiudadId($ciudad_id){
    	$this->ciudad_id=$ciudad_id;
    }
    public function setEstado($estado){
    	$this->estado=$estado;
    }
    public function setEsEstado($bool){
    	$this->esEstado=$bool;
    }
 	public function getCiudadPorNombreAbreviacionEstado($ciudadBuscada,$abbrev){
    	global $db, $t_cities;
    	$q="SELECT  $t_cities.cityid,$t_cities.abbreviation FROM $t_cities WHERE $t_cities.city_name LIKE '%".$ciudadBuscada."%' AND abbreviation='".$abbrev."' AND $t_cities.rel_of_state=1 LIMIT 1; ";
    	$result=$db->query($q);
    	if(!$result){
    		return false;
    	}
    	if ($result->num_rows==1){
    		$row = $result->fetch_assoc();
    		$this->ciudad_id=$row['cityid'];
    		$this->abreviacion=$row['abbreviation'];
    		$this->esCiudad=true;
    		return true;
    	}else{
    		return false;
    	}
    }
	public function getEstadosPocoPoblados(){
    	global $t_states;
    	$estadosPocoPoblados=Cache::get("estadosPocoPoblados");
    	if (!$estadosPocoPoblados){
	    	$q="SELECT $t_states.cl2_url as url_estado, $t_states.name as estado,$t_states.stateid FROM $t_states WHERE $t_states.poco_poblado=1 ORDER BY $t_states.name DESC;";
	    	$result=db::getInstance()->query($q);
	    	while ($row = $result->fetch_assoc()){
	    		$estadosPocoPoblados[]= array ("nombre"=>$row['estado'],"url" => $this->sacarDominio($row["url_estado"]),"id"=>$row['stateid'],"es_estado"=>true);
	    	}
	    	Cache::set("estadosPocoPoblados",$estadosPocoPoblados,true,86400);
    	}
    	return $estadosPocoPoblados;
    }
	public function getCiudadesRelevantesEstado(){
    	global $t_cities;
    	$ciudadesRelevantesEstado=Cache::get("ciudadesRelevantesEstado");
    	if (!$ciudadesRelevantesEstado){
	    	$q="SELECT cl2_url as url,city_name as nombre,cityid as id FROM $t_cities WHERE rel_of_state=1 ORDER BY show_city DESC,city_name ASC;";
	    	$result=db::getInstance()->query($q);
    		while ($row = $result->fetch_assoc()){
	    		$ciudadesRelevantesEstado[]= array ("nombre"=>$row['nombre'],"url" => $this->sacarDominio($row["url"]),"id"=>$row['id'],"es_estado"=>false);
	    	}
	    	Cache::set("ciudadesRelevantesEstado",$ciudadesRelevantesEstado,true,86400);
    	}
    	return $ciudadesRelevantesEstado;
    }
    public function getCiudadesImpPorAbreviacionEstado(){
    	global $db, $t_cities;
    	$q="SELECT cityid,is_bold,rel_of_state,show_city FROM $t_cities WHERE abbreviation='".$this->abreviacion."'; ";
    	$result=$db->query($q);
    	if(!$result){
    		return false;
    	}
    	$ciudades=array();
    	while ($row = $result->fetch_assoc()){
    		$ciudades[]=array("cityid"=>$row['cityid'],"is_bold"=>$row['is_bold'],"rel_of_state"=>$row['rel_of_state'],"show_city"=>$row['show_city']);
    	}
    	return $ciudades;
    }
    public function getCiudadURLPorNombreEstadoId($ciudadBuscada,$estadoId){
    	global $db, $t_cities,$t_states;
    	$q="SELECT  $t_cities.city_name,$t_cities.cl2_url as url_ciudad, $t_states.cl2_url as url_estado FROM $t_cities INNER JOIN $t_states ON $t_cities.stateid=$t_states.stateid WHERE $t_cities.city_name LIKE '%".$ciudadBuscada."%' AND $t_states.stateid=$estadoId LIMIT ".MAX_CIUDADES_ELEGIR_BUSCADAS."; ";
    	$result=$db->query($q);
    	if(!$result){
    		return false;
    	}
    	while ($row = $result->fetch_assoc()){
    		$ciudades[]= array ("ciudad"=>strtolower($row['city_name']),"url_ciudad" => $this->sacarDominio($row["url_ciudad"]), "url_estado" => $this->sacarDominio($row["url_estado"]));
    	}
    	return $ciudades;
    }
    public function getCiudadURLPorNombre($ciudadBuscada){
    	global $db, $t_cities,$t_states;
    	$q="SELECT  $t_cities.cityid FROM $t_cities WHERE $t_cities.city_name LIKE '%".$ciudadBuscada."%'  LIMIT 1; ";
    	$result=$db->query($q);
    	if(!$result){
    		return false;
    	}
    	$row = $result->fetch_array();
    	$this->ciudad_id=$row['cityid'];
    	return $this->ciudad_id;
    }
	public function getMasCiudadesDestacadasdelPais(){
    	global $db, $t_cities,$t_states;
    	$ciudades=Cache::get("masCiudadesDestacadas");
    	if (!$ciudades){
	    	$q="SELECT $t_cities.city_name as ciudad, $t_cities.is_bold, $t_cities.cl2_url as url_ciudad, $t_states.cl2_url as url_estado, $t_states.name as estado, $t_cities.is_bold FROM $t_cities INNER JOIN $t_states ON $t_cities.stateid=$t_states.stateid WHERE $t_cities.show_city=1 OR ( $t_cities.rel_of_state=1 AND ($t_cities.abbreviation in('CA','FL','NY','CH','OR','MO','TX','IA','GA','IL','ID','MD')) ) ORDER BY $t_cities.city_name ASC,$t_states.name ASC;";
	    	$result=$db->query($q);
	    	while ($row = $result->fetch_assoc()){
	    		$ciudades[]= array ("estado"=>$row['estado'],"ciudad"=>strtolower($row['ciudad']), "url_ciudad" => $this->sacarDominio($row["url_ciudad"]), "url_estado" => $this->sacarDominio($row["url_estado"]),"is_bold"=>$row["is_bold"]);
	    	}
	    	Cache::set("masCiudadesDestacadas",$ciudades,true,86400);
    	}
    	return $ciudades;
    }
	public function getCiudadesDestacadasdelPais(){
    	global $db, $t_cities,$t_states;
    	$ciudades=Cache::get("ciudadesDestacadas");
    	if (!$ciudades){
	    	$q="SELECT $t_cities.city_name as ciudad, $t_cities.cityid, $t_cities.is_bold, $t_cities.cl2_url as url_ciudad, $t_states.cl2_url as url_estado,$t_states.poco_poblado as poco_poblado, $t_cities.abbreviation FROM $t_cities INNER JOIN $t_states ON $t_cities.stateid=$t_states.stateid WHERE $t_cities.show_city=1 ORDER BY city_name ASC limit ".MAX_CIUDADES_DESTACADAS."; ";
	    	$result=$db->query($q);
	    	while ($row = $result->fetch_assoc()){
	    		$ciudades[]= array ("cityid"=>$row['cityid'],"ciudad"=>strtolower($row['ciudad']), "url_ciudad" => $this->sacarDominio($row["url_ciudad"]), "url_estado" => $this->sacarDominio($row["url_estado"]),"poco_poblado"=>$row["poco_poblado"], "is_bold"=>$row["is_bold"],"abreviacion"=>$row['abbreviation']);
	    	}
	    	Cache::set("ciudadesDestacadas",$ciudades,true,86400);
    	}
    	return $ciudades;
    }
   
	
	
}
?>