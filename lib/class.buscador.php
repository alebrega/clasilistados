<?php
class Buscador {

	static private $instance = null;
	private $registro = null;
	private $atributosOrd=array();
	private $estaBuscando=false;
	private $query='';
	private $filtros=array();
	private $filtrosRango=array();
	private $anuncios_encontrados=0;
	private $total_anuncios=null;
	private $limitMatches=true;
	private $filtrar_por_estado=false;
	private $filtrar_por_ciudad=true;
	private $buscoAnunciosRelacionados=false;
	private $debeBuscarPorCampo=false;
	private $buscarPorCampo=null;
	private $ordenarPor=null;
	private $urgente=false;
	private $campos=array('adid','titulo','fechaHora','subcatid','lugar', 'destacado','urgente');
	private $pagina=1;
	private $soloAnunciosConImagenes=false;
	private $encontradosTotal=0;
	private $filtrarPorLocacion=true;
	private $fechaEvento=null;
	
	public function conectarse(Registro $registro){
  		$this->registro=$registro;
  		$categoria=$this->registro->get('categoria');
		if ($this->registro->get('locacion')->esEstado()){
			$this->filtrar_por_estado=true;
		}
		if ($categoria->esCompraVenta() || $categoria->esVehiculos() || $categoria->esVivienda()){
			array_push($this->campos,'precio');
		}
		if ($categoria->esEvento()){
			array_push($this->campos,'fechaInicio');
			array_push($this->campos,'fechaFin');
		}
	}
	public function setFechaEvento($f){
		$this->fechaEvento=$f;
	}
	public function setFiltrarPorLocacion($b){
		$this->filtrarPorLocacion=$b;
	}
	public function getFiltrarPorLocacion(){
		return $this->filtrarPorLocacion;
	}
	public function setOrdenarPor($od){
		$this->ordenarPor=$od;
	}
	public function setUrgente($u){
		$this->urgente=$u;
	}
	public function getOrdenarPor(){
		return $this->ordenarPor;
	}
	public function setQuery($q){
		$this->query=$q;
	}
	public function getQuery(){
		return $this->query;
	}
	public function getAnunciosEncontrados(){
		return $this->anuncios_encontrados;
	}
	public function SetRangoFiltro($f){
		array_push($this->filtrosRango, $f);
	}
	
	public function estaBuscando(){
		$this->estaBuscando=(!is_null($_GET['busqueda']) || !is_null($_GET['ordenar']));
		return $this->estaBuscando;
	}
	private function __construct(){
	}
	/*
	public function generarKeyListados(){
		$categoria=$this->registro->get('categoria');
		$subcatid=$_GET['subcat'];
		$ciudad_id=$this->registro->get('locacion')->getCiudadId();
		$estado_id=$this->registro->get('locacion')->getEstadoId();
		$keyListados='listados-Catid-'.$categoria->getCategoriaId();
		if (!empty($subcatid)){
			$keyListados.='-Subcatid-'.$categoria->getSubCategoriaId();
		}
		if ($categoria->esEvento()){
			$keyListados.='-eventos-'.$_GET['fecha'];
		}
		if (!empty($ciudad_id)){
			$keyListados.='-CityId-'.$ciudad_id;
		}elseif (!empty($estado_id)){
			$keyListados.='-EstadoId-'.$estado_id;
		}
		if (!empty($this->ordenarPor)){
			$keyListados.='-ordenarPor-'.$this->ordenarPor;	
		}
		if ($this->urgente){
			$keyListados.='-urgente-1';	
		}
		return $keyListados;
	}
	public function getHtmlCache(){
		$html='';
		if($this->seGuardaEnCache()){
			$this->total=Cache::get('total_encontrados');
			$html=Cache::get($this->generarKeyListados());	
		}
		return $html;
	}
	*/
	public function buscarPorCampo($campo){
		$this->buscarPorCampo=$campo;
		$this->debeBuscarPorCampo=true;
	}
	
	public function getAnuncios($campos){
		global $t_anuncios;
		foreach ($campos as $campo){
			$camposBd[]='a.'.$campo;
		}
		$camposBd=implode(",", $camposBd);
		$condiciones=$this->filtrar();
		$orden=$this->ordenar();
		$cant_anuncios= (($this->pagina - 1) * LIMITE_POR_PAGINA);
		//sumo los destacados asi solo traigo 100 por listado
		$limit = 'LIMIT ' .$cant_anuncios.',' .(LIMITE_POR_PAGINA - $this->anuncios_encontrados);
		$tabla=$t_anuncios.$this->registro->get('categoria')->getCategoriaId();
		if (!empty($_GET['busqueda'])){
			$sql="SELECT * FROM (SELECT
					$camposBd,c.imgid, MATCH (titulo,descripcion) AGAINST ('".$this->getQuery()."') as Score		
				FROM $tabla a LEFT JOIN cl2_imagenes c ON a.adid=c.adid AND a.catid=c.catid 
				 WHERE MATCH (titulo,descripcion) AGAINST ('".$this->getQuery()."') AND 
					a.habilitado='".ANUNCIO_HABILITADO."' $condiciones ORDER BY Score DESC) k 
				GROUP BY k.adid $orden $limit;";
		}else{
			//$sql="SELECT ".$camposBd." FROM ".$tabla." a WHERE a.habilitado='".ANUNCIO_HABILITADO."' $condiciones $orden $limit; ";
			$sql="SELECT * FROM (SELECT $camposBd,c.imgid FROM $tabla a LEFT JOIN cl2_imagenes c 
				ON a.adid=c.adid AND a.catid=c.catid WHERE a.habilitado='".ANUNCIO_HABILITADO."' $condiciones) k 
				GROUP BY k.adid $orden $limit; ";
		}
		//echo $sql;
		$result=db::getInstance()->query($sql);
		$this->anuncios_encontrados=$result->num_rows;
		$this->encontradosTotal=$this->anuncios_encontrados+$this->encontradosTotal; // destacados mas anuncios normales
		if ($result->num_rows<=0){
			return false;
		}
		return $result;
	}
	public function ordenar(){
		$orden="";
		$categoria=$this->registro->get('categoria');
		if ((!empty($_GET['busqueda'])) && ($_GET['ordenar']!='fecha')){
			$orden='ORDER BY Score DESC';
		}else{
			$orden='ORDER BY k.fechaHora DESC';
		}
		if ($categoria->esCompraVenta() || $categoria->esVehiculos() || $categoria->esVivienda()){
			if (($this->ordenarPor=='menor_precio')){
				$orden='ORDER BY k.precio ASC';
			}
			if (($this->ordenarPor=='mayor_precio')){
				$orden='ORDER BY k.precio DESC';
			}
		}
		return $orden;
	}
	public function filtrar($count=false){
		$condiciones="";
		foreach ($this->filtros as $campo){
			foreach ($campo as $c=>$v){
				if (is_integer($v)){
					$condiciones.=" AND a.".$c."=".$v." ";
				}else{
					$condiciones.=" AND a.".$c."='".$v."' ";
				}
				
			}	
		}
		if ($this->filtrarPorLocacion){
			$stateid=$this->registro->get('locacion')->getEstadoId();
			if ( ($this->filtrar_por_estado) && !empty($stateid)){
				$condiciones.=" AND a.stateid=$stateid ";
			}
			$ciudad_id=$this->registro->get('locacion')->getCiudadId();
			if ( ($this->filtrar_por_ciudad) && !empty($ciudad_id) && $this->registro->get('locacion')->esCiudad() ){
				$condiciones.=" AND a.cityid=$ciudad_id ";
			}
		}
		
		if ($this->soloAnunciosConImagenes){
			$condiciones.=" AND c.imgid IS NOT NULL ";
		}
		if ($this->debeBuscarPorCampo){
			$condiciones.=" AND a.".$this->buscarPorCampo." LIKE '%".$this->getQuery()."%' ";
		}
		for ($i=0;$i<count($this->filtrosRango);$i++){
			$filtroRango=$this->filtrosRango[$i];
			if (!empty($filtroRango[0])){
				//si el campo a filtrar no es vacio
				$condiciones.=" AND a.".$filtroRango[0]." BETWEEN ".$filtroRango[1]." AND ".$filtroRango[2]." ";
			}
		}
		if ($this->registro->get('categoria')->esEvento()){
			$condiciones.=" AND a.fechaInicio<='".$this->fechaEvento."' AND a.fechaFin>='".$this->fechaEvento."' ";
					
		}
		if (!$count){
			if (empty($_GET['busqueda']) && ($this->ordenarPor=='rel')){
				$condiciones.=" AND a.destacado=1 ";
			}
			if(($this->anuncios_encontrados>0) && ($this->ordenarPor=='fecha')){
				$condiciones.=" AND a.destacado=0 ";
			}
		}
		return $condiciones;
	}
	public function getTotalAnuncios($habilitado=1){
		if (empty($this->total_anuncios)){
			global $t_anuncios;
			$condiciones=$this->filtrar(true);
			if (!empty($_GET['busqueda'])){
				$sql="SELECT COUNT(*)  as count
					FROM ".$t_anuncios.$this->registro->get('categoria')->getCategoriaId()." a WHERE MATCH (titulo,descripcion) AGAINST ('".$this->getQuery()."') AND 
					a.habilitado='".$habilitado."' $condiciones;";
			}else{
				$sql="SELECT COUNT(*) AS count FROM ".$t_anuncios.$this->registro->get('categoria')->getCategoriaId()." a WHERE a.habilitado='".$habilitado."' $condiciones; ";
			}
			$result=db::getInstance()->query($sql);
			if (!$result){
				return 0;
			}
			$row=$result->fetch_array();
			$this->total_anuncios=$row["count"];
		}
		return $this->total_anuncios;
	}
	public function soloAnunciosConImagenes($bool=false){
		$this->soloAnunciosConImagenes=$bool;
	}
	public function getTitulos(){
		$fechaAnuncioAnterior='';
		$fechaAnuncioDiaMes='';
		$subcatActual='';
		$subcatMostradas=array();
		$locacion=$this->registro->get('locacion');
		$categoria=$this->registro->get('categoria');
		$subcatid=$categoria->getSubcategoriaId();
		$result=$this->getAnuncios($this->campos);
		if (!$result){
			return false;
		}
		$this->getTotalAnuncios(); //cargo total_anuncios
		if (($this->encontradosTotal==LIMITE_POR_PAGINA) && ((LIMITE_POR_PAGINA*$this->pagina)<intval($this->total_anuncios)) && $this->getFiltrarPorLocacion()){
			if (!is_null($subcatid)){
				$subcat=array("catid"=>$categoria->getCategoriaId(),"nombre"=>$categoria->getSubCategoriaNombre(),"id"=>$subcatid);
				$linkSig=$this->registro->get('helper')->getLinkSubcatHref($subcat).'/'.($this->pagina+1);
			}
			else{
				$linkSig=$this->registro->get('helper')->getCategoriaLinkHref($categoria->getCategoriaId()).'/'.($this->pagina+1);
			}
			if (!empty($_SERVER['QUERY_STRING']) && (!empty($_GET['busqueda']))){
				$linkSig=$linkSig.'?'.$_SERVER['QUERY_STRING'];
			}
			$paginacion='<p align="center"><font size="4"><a href="'.$linkSig.'">siguientes '.LIMITE_POR_PAGINA.' anuncios</a></font></p>';
		}
		if ($categoria->esCompraVenta() || $categoria->esVehiculos()){
			$formatoTitulo='<<TITULO>> - $<<PRECIO>>';
		}
		if ($this->registro->get('categoria')->esVivienda()){
			$formatoTitulo='$<<PRECIO>> <<TITULO>>';
		}
		
		$cityid=$locacion->getCiudadId();
		$stateid=$locacion->getEstadoId();
		$i=0;
		while ($row = $result->fetch_array()){
			$categoria=new Categoria();
			$extrasAnuncio='';
			//$fechaAnuncio=$this->registro->get('helper')->getFechaAnuncios($row['fechaHora'],!$this->estaBuscando());			
			$categoria->getSubCategoriaData($row['subcatid']);
			if (!$categoria->esEvento() && empty($subcatid)){
				$subcat=array ("nombre"=>$categoria->getSubcategoriaNombre(),"catid"=>$categoria->getCategoriaId(),"id"=>$categoria->getSubcategoriaId());
				$categoriaLink='<small class="gc">'.$this->registro->get('helper')->getLinkSubcat($subcat).'</small>';
			}else{
				$categoriaLink='';	
			}
			if ($categoria->esEvento()){
				if (empty($subcatid) && ($subcatActual!=$categoria->getSubcategoriaNombre()) && (!in_array($categoria->getSubcategoriaId(),$subcatMostradas))){
					//solo se muestra para la categoria eventos
					$subcat=array ("nombre"=>$categoria->getSubcategoriaNombre(),"catid"=>$categoria->getCategoriaId(),"id"=>$categoria->getSubcategoriaId());
					$html.= '<h4>'.$categoria->getSubcategoriaNombre().' > (<a href="'.$this->registro->get('helper')->getLinkSubcatHref($subcat).'">ver todo '.$categoria->getSubcategoriaNombre().'</a>)</h4>';
					array_push($subcatMostradas,$categoria->getSubcategoriaId());
				}
				$subcatActual=$categoria->getSubcategoriaNombre();
				$fechaAnuncioTitulo=$this->registro->get('helper')->getFechaSoloMesyDia($row['fechaInicio'],null).'-'.$this->registro->get('helper')->getFechaSoloMesyDia($row['fechaFin'],null).': ';
			}
			/*elseif($fechaAnuncio!=$fechaAnuncioAnterior){
				$fechaAnuncioAnterior=$fechaAnuncio;
				if (!$this->estaBuscando()){
					 if(!$this->buscoAnunciosRelacionados()){
					 	$html.= '<h4>'.$fechaAnuncio.'</h4>';
					 }
				}else{
					$fechaAnuncioTitulo=$fechaAnuncio.' - ';
				}
			}*/
			$extrasAnuncio.=!empty($row['lugar'])? ' (<font size="-1">'.strtolower($row['lugar']).'</font>) ': '';
			if (!empty($row['imgid'])){
				$extrasAnuncio.='<span class="p"> foto </span> ';
			}
			//var_dump($row['destacado']); ANUNCIO_DESTACADO
			$titulo=strtolower($row['titulo']);
			if (!empty($row['precio'])){
				$titulo=str_replace('<<TITULO>>',$titulo,$formatoTitulo);
				$titulo=str_replace('<<PRECIO>>',intval($row['precio']),$titulo);
			}
			$url_locacion=$locacion->getFiltroLocation();
			if ($row['destacado']==ANUNCIO_DESTACADO){
				$html.='<p style="font-weight: bold;">';
			}else{
				$html.='<p>';
			}
			if ((strlen($extrasAnuncio)>0) || (strlen($categoriaLink)>0)){
				$extras=' - '.$extrasAnuncio.' '.$categoriaLink	;
			}else{
				$extras=$extrasAnuncio.' '.$categoriaLink;
			}
			$html.='<p>'.$fechaAnuncioTitulo.$this->generarLink($row,$titulo,$categoria,$row['destacado'],$row['urgente']).$extras.'</p>';
			$i++;
		}
		$adsense=new Adsense();
		$adsense->setMedidas('728x90');
                $htmlAdsensearriba='<div style="margin-left: -7px;">'.$adsense->getHTML('arriba').'</div>';
                
		$htmlAdsenseabajo='<div style="margin-left: -7px;">'.$adsense->getHTML('abajo').'</div>';	
		
		//$htmlAdsense='<div style="margin-left: -7px;">'.$adsense->getHTML("texto_arriba_contenido").'</div>';
		//$htmlAdsense2='<div style="margin-left: -7px;">'.$adsense->getHTML("texto_abajo_contenido").'</div>';
		//$html=$htmlAdsense.$html.$htmlAdsense2.$paginacion;
		$html=$htmlAdsensearriba.$html.$htmlAdsenseabajo.$paginacion;
		return $html;
	}
	private function generarLink($row,$titulo,$categoria,$destacado=0,$urgente=0){
		$urg='';
		if ($urgente==ANUNCIO_URGENTE){
			$urg='<span class="urgente">urgente!</span> - ';
		}
		if ($destacado==ANUNCIO_DESTACADO){
			$link='<a href="'.$this->registro->get('helper')->getAnuncioLink($row["adid"],$row["titulo"],$categoria->getCategoriaId(),$categoria->getSubCategoriaId(),$categoria->getSubCategoriaNombre()).'" title="'.$titulo.' - '.str_replace('/',' ',$this->registro->get('locacion')->getFiltroLocation()).'" class="destacadoLink">'.$titulo.'</a>';	
		}else{
			$link='<a href="'.$this->registro->get('helper')->getAnuncioLink($row["adid"],$row["titulo"],$categoria->getCategoriaId(),$categoria->getSubCategoriaId(),$categoria->getSubCategoriaNombre()).'" title="'.$titulo.' - '.str_replace('/',' ',$this->registro->get('locacion')->getFiltroLocation()).'">'.$titulo.'</a>';
		}
		return $urg.' '.$link;
	}
	/*
	public function seGuardaEnCache(){
		if ( !$this->buscoAnunciosRelacionados && empty($_GET['no_cache']) && (!$this->estaBuscando()) && (($this->limite['inicio']-1)==0) ) {
			//solo guarda la primera pagina de un listado
			return true;
		}else{
			return false;
		}
	}*/
	public function getTotal(){
		return $this->total;
	}
	public function getTotalFound(){
		return $this->total_found;
	}
	public function buscar(){
	 	$categoria_id=$this->registro->get('categoria')->getCategoriaId();
		if (!empty($categoria_id)){
			$this->setFiltros(array('catid'=>$categoria_id));
		}
		$stateid=$this->registro->get('locacion')->getEstadoId();
		if ( ($this->filtrar_por_estado) && !empty($stateid)){
			$this->setFiltros(array('stateid'=>$stateid));
		}
		$ciudad_id=$this->registro->get('locacion')->getCiudadId();
		if ( ($this->filtrar_por_ciudad) && !empty($ciudad_id) && $this->registro->get('locacion')->esCiudad() ){
			$this->setFiltros(array('cityid'=>$ciudad_id));	
		}
		$this->filtrar();
		$this->ordenar();
		
		if ($this->limitMatches){
			$this->sphinx->setLimits($this->limite['inicio']-1,LIMITE_POR_PAGINA,SPHINX_MAX_RESULTADOS);
		}
		$this->sphinx->SetMatchMode($this->matchMode);
		$query=$this->query;
		if ($this->debeBuscarPorCampo){
			$query='@'.$this->buscarPorCampo.' "'.$this->query.'"';
		}
		$this->anuncios = $this->sphinx->Query($query,'anuncios_'.$categoria_id);
		$this->total=$this->anuncios['total'];		
		$this->total_found=$this->anuncios['total_found'];
		$encontrados=count($this->anuncios['matches']);
		$this->enviarAlertaTecnica();
		if ($encontrados<=0){
			$this->anunciosRelacionados(rand(20, 35));
		}else{
			$this->guardarTerminoBusqueda($this->query);
		}
		return $this->anuncios;
	}
	/*
	public function guardarTerminoBusqueda($query){
		global $t_busquedas;
		if (!empty($query)){
			if (strlen($query)>3000){
				return false;
			}
			if ($stmt = db::getInstance()->prepare("INSERT INTO $t_busquedas (busqueda,catid,subcatid,cityid,stateid) VALUES (?,?,?,?,?)")){
			    $subcatid=$this->registro->get('categoria')->getSubCategoriaId();
				if (empty($subcatid)){
					$subcatid=0;
				}
				$locacion=$this->registro->get('locacion');
			    $stmt->bind_param("siiii", $query,$this->registro->get('categoria')->getCategoriaId(),$subcatid,intval($locacion->getCiudadId()),$locacion->getEstadoId());
				if ($stmt->execute()){
					$stmt->close();
					return true;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
				
	}
	
	
	public function getBusquedasPopulares(){
		global $t_busquedas;
		$categoria=$this->registro->get('categoria');
		$locacion=$this->registro->get('locacion');
		$catid=$categoria->getCategoriaId();
		$subcatid=$categoria->getSubCategoriaId();
		$cityid=intval($locacion->getCiudadId());
		$stateid=$locacion->getEstadoId();
		
		if (empty($subcatid)){
			$subcatid=0;
		}
		$key='busquedas_populares-catid-'.$catid.'-subcatid-'.$subcatid.'-cityid-'.$cityid.'-stateid-'.$stateid;
		$busquedas_pop=Cache::get($key);
		if (!$busquedas_pop){
			$sql="SELECT count(*) as contador,busqueda FROM $t_busquedas WHERE catid=? AND subcatid=? AND cityid=? AND stateid=? GROUP BY busqueda,subcatid ORDER BY contador DESC,tiempo DESC LIMIT 15;";
			if ($stmt=db::getInstance()->prepare($sql)) {
			  $busquedas_pop = array();
			  $stmt->bind_param('iiii', $catid, $subcatid,$cityid,$stateid);
			  $stmt->execute();
			  $stmt->bind_result($contador,$busq);
			  while ($stmt->fetch()) {
			      $busquedas_pop[] = $busq;
			   }
			   $stmt->close();
			   Cache::set($key,$busquedas_pop,true,86400); //una vez por dia genera las nuevas busquedas populares
			}
		}
		
		return $busquedas_pop;
	}
	*/
	public function getEncontrados(){
		return $this->encontrados;
	}
	/*
	public function anunciosRelacionados($cantidad){
		if (!$this->buscoAnunciosRelacionados && !$this->estaBuscando()){
			$this->sphinx->ResetFilters();
			
			$this->buscoAnunciosRelacionados=true;
			$this->setFiltros(array());
			$this->setFiltrarPorCiudad(false);
			$this->setFiltrarPorEstado(false);
			$this->setLimite(array('inicio'=>1,'fin'=>$cantidad));
			$this->buscar();
		}
	}*/
	public function setFiltros(array $filtros){
		array_push($this->filtros, $filtros);
	}
	public function setFiltrarPorCiudad($filtrar){
		$this->filtrar_por_ciudad=$filtrar;
	}
	public function setFiltrarPorEstado($filtrar){
		$this->filtrar_por_estado=$filtrar;
	}
	public function setPagina($p){
		$this->pagina=$p;
	}
	public function getPagina(){
		return $this->pagina;
	}
	static public function getInstance(){
		if (!self::$instance) {
			self::$instance = new Buscador();
		}
		return self::$instance;
	}
	
}
?>