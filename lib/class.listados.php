<?php
class Listados{
	
	private $fechaHoraBusqueda;
	private $maketime;
	private $registro=null;
	private $fechaAnuncio=null;
	private $esEvento=false;
	private $fechaEvento=null;
	private $maketimeEvento=null;
	private $anunciosEncontrados=null;
	
	public function __construct(Registro $registro){
		$this->registro=$registro;
	}
	public function setEsEvento($bool){
		$this->esEvento=$bool;
	}
	public function getEsEvento(){
		return $this->esEvento;
	}
	public function setFechaEvento($fecha){
		$arrayfecha = explode('-', $fecha);
		$this->fechaEvento=$arrayfecha;
	}
	public function getDiaLink($arrayfecha,$variacion){
		$this->maketimeEvento = mktime(0,0,0, $arrayfecha[1], $arrayfecha[2]+$variacion, $arrayfecha[0]);
		return $this->registro->get("helper")->getFechaEventoLink(date("j",$this->maketimeEvento),date("n",$this->maketimeEvento),date("Y",$this->maketimeEvento));
	}
	public function getMkTimeBusquedaEvento(){
		$mktime=mktime(0,0,0, $this->fechaEvento[1], $this->fechaEvento[0], $this->fechaEvento[2]);
		return $mktime;
	}
	public function getFechaBusquedaEvento(){
		$mktime=mktime(0,0,0, $this->fechaEvento[1], $this->fechaEvento[0], $this->fechaEvento[2]);
		return date("Y-m-d", $mktime);
	}
	public function getMakeTimeEvento(){
		return $this->maketimeEvento;
	}
	public function getFechaEvento(){
		return $this->fechaEvento;
	}
	public function getDiaEvento(){
		return $this->fechaEvento[0];
	}
	public function getMesEvento(){
		return $this->fechaEvento[1];
	}
	public function getAnoEvento(){
		return $this->fechaEvento[2];
	}
	public function getMaketime(){
		return $this->maketime;
	}
	public function getMaketimeBusqueda(){
		$this->maketime = mktime(date("H"),date("i"),date("s"), date("m"), date("d"),date("Y"));
		return $this->maketime;
		
	}
	
	public function getFechaHoraBusqueda() {
		$mes=calendario::getMesEspanol(date("F",$this->maketime));
		$dia_semana=strtolower(calendario::dia_semana($this->maketime));
		$this->fechaHoraBusqueda=$dia_semana.", ".date("d",$this->maketime)." ".strtolower($mes)." ".date("H:i:s",$this->maketime);
		return $this->fechaHoraBusqueda;
	}
	public function getFechaHora(){
		return date("Y-m-d H:i:s", $this->maketime);
	}
	public function getAnunciosVencidos($catid,$cuantos){
		global $t_anuncios;
		$q="SELECT a.adid,a.cod_seguridad,a.email,a.titulo,a.fechaHora FROM ".$t_anuncios.$catid." a WHERE a.habilitado='1' and ISNULL(a.feed_id) and (NOW()>a.vencimiento OR ISNULL(a.vencimiento)) ORDER BY a.vencimiento ASC LIMIT $cuantos,100;";
		$result=db::getInstance()->query($q);
		if (!$result){
			return false;
		}
		if ($result->num_rows==0){
			return false;
		}
		while ($row = $result->fetch_array()){
			$anuncios[]=array('adid'=>$row['adid'],'fechaHora'=>$row['fechaHora'],'cod_seguridad'=>$row['cod_seguridad'],'email'=>$row['email'],'titulo'=>$row['titulo']);
		}
		return $anuncios;
			
	}
	public function getAnunciosPorLimite($catid,$limite,$order){
		global $t_anuncios,$t_imagenes,$t_subcats,$registro;
		$q="SELECT a.adid FROM ".$t_anuncios.$catid." a WHERE a.habilitado='1' ORDER BY a.fechaHora ".$order." LIMIT ".$limite.";";
		$result=db::getInstance()->query($q);
		if (!$result){
			return false;
		}
		if ($result->num_rows==0){
			return false;
		}
		while ($row = $result->fetch_array()){
			$anuncios[]=array('adid'=>$row['adid']);
		}
		return $anuncios;
			
	}
	public function getUltimosAnunciosPublicados($catid,$subcatid,$limite){
		global $t_anuncios,$t_imagenes,$t_subcats,$registro;
		$q="SELECT a.titulo,a.adid,a.cityid,a.stateid,a.subcatid FROM  ".$t_anuncios.$catid." a WHERE a.habilitado='1' AND a.subcatid=".$subcatid." ORDER BY a.fechaHora DESC LIMIT ".$limite.";";
		$result=db::getInstance()->query($q);
		if (!$result){
			return false;
		}
		if ($result->num_rows==0){
			return false;
		}
		$anuncios=array();
		while ($row = $result->fetch_array()){
			$anuncios[]=array('titulo'=>$row['titulo'],'adid'=>$row['adid'],'cityid'=>$row['cityid'],'stateid'=>$row['stateid'],'subcatid'=>$row['subcatid']);
		}
		return $anuncios;
			
	}/*
	public function getAnuncios($pagina,$catid,$subcatid=null){
		global $t_anuncios,$t_imagenes,$t_subcats;
		
		if ($this->registro->get("locacion")->esCiudad()){
			$zona_geografica="a.cityid=".$this->registro->get("locacion")->getCiudadId();
		}else{
			$zona_geografica="a.stateid=".$this->registro->get("locacion")->getEstadoId();
		}
		$anuncios=array();
		$eventoCond="";
		$subcatCond="";
		$subCatInfoCond="";
		$orderSubcat="";
		$camposSubcat="";
		$camposEvento="";
		if ($this->getEsEvento()){
			$eventoCond=" AND '".$this->getFechaBusquedaEvento()."' BETWEEN fechaInicio AND fechaFin";
			$camposEvento=",a.fechaInicio,a.fechaFin";
		}
		if (!is_null($subcatid) && ($this->registro->get("categoria")->esSubcategoriaHabilitada())){
			$subcatCond="AND a.subcatid=".$subcatid;
		}
		if(is_null($subcatid) || ($this->getEsEvento()))
		{
			$camposSubcat=", s.subcatid, s.subcatname";
			$subCatInfoCond="INNER JOIN ".$t_subcats." s ON a.subcatid=s.subcatid";
			$subcatCond=" AND s.enabled='1'";
		}
		$limite=LIMITE_POR_PAGINA;
		$q="SELECT a.titulo, a.adid $camposSubcat $camposEvento FROM  ".$t_anuncios.$catid." a $subCatInfoCond WHERE  $zona_geografica AND a.habilitado='1' $subcatCond $eventoCond ORDER BY a.fechaHora DESC LIMIT ".$limite.";";
		$fechaAnuncioAnterior="";
		$subcatActual="";
		$fechaEvento="";
		$subcatMostradas=array();
		$result=db::getInstance()->query($q);
		if (!$result){
			return false;
		}
		while ($row = $result->fetch_array()){
			$q_img="SELECT img1,img2,img3,img4 FROM ".$t_imagenes." WHERE adid=".$row['adid']." and catid=".$catid." ;";
			$imagenes_result=db::getInstance()->query($q_img);
			$imagenesHTML="";
			if ($imagenes_result){
				$imagenes_result = $imagenes_result->fetch_array();
				$this->registro->get("imagenes")->cargar($row['adid'],$catid);
				$imagenesHTML=$this->registro->get("imagenes")->getHTML(IMAGENES_ANUNCIO);
			}
			$link=$this->registro->get("helper")->getAnuncioLink($row["adid"],$row["titulo"],$catid,$row["subcatid"],$row['subcatname']);
			$anuncios[]=array("link"=>$link,"titulo"=>strtolower($row['titulo']),"imagenesHTML"=>$imagenesHTML);
    	}
    	return $anuncios;
	}*/
	public function getListadoCategoria($pagina,$catid,$subcatid=null){
		global $t_anuncios,$t_imagenes,$t_subcats;
		if ($this->registro->get("locacion")->esCiudad()){
			$zona_geografica="a.cityid=".$this->registro->get("locacion")->getCiudadId();
		}else{
			$zona_geografica="a.stateid=".$this->registro->get("locacion")->getEstadoId();
		}
		$eventoCond="";
		$subcatCond="";
		$subCatInfoCond="";
		$orderSubcat="";
		$camposSubcat="";
		$camposEvento="";
		$precio='';
		if ($this->getEsEvento()){
			$eventoCond=" AND '".$this->getFechaBusquedaEvento()."' BETWEEN fechaInicio AND fechaFin";
			$camposEvento=",a.fechaInicio,a.fechaFin";
		}
		if (!is_null($subcatid) && ($this->registro->get('categoria')->esSubcategoriaHabilitada())){
			$subcatCond="AND a.subcatid=".$subcatid;
		}
		if(is_null($subcatid) || ($this->getEsEvento()))
		{
			$camposSubcat=", s.subcatid, s.subcatname";
			$subCatInfoCond="INNER JOIN ".$t_subcats." s ON a.subcatid=s.subcatid";
			$subcatCond=" AND s.enabled='1'";
		}
		
		$limite=$this->getLimiteListados($pagina);
		$q="SELECT a.titulo, a.adid, a.lugar, DATE(a.fechaHora) as fechaHora $precio $camposSubcat $camposEvento FROM  ".$t_anuncios.$catid." a $subCatInfoCond WHERE  $zona_geografica AND a.habilitado='1' $subcatCond $eventoCond ORDER BY a.fechaHora DESC LIMIT ".$limite.";";
		$fechaAnuncioAnterior="";
		$subcatActual="";
		$fechaEvento="";
		$subcatMostradas=array();
		$result=db::getInstance()->query($q);
		if (!$result){
			return false;
		}
		$this->anunciosEncontrados=$result->num_rows;
		$i=0;
		$html='';
		while ($row = $result->fetch_array()){
			$imagenes=new Imagenes($this->registro);
			$this->registro->set('imagenes',$imagenes);
			if ($i==LIMITE_POR_PAGINA){
				break;
			}
			$extrasAnuncio="";
			$precio="";
			$fechaAnuncio=$this->registro->get('helper')->getFechaAnuncios($row['fechaHora']);
			if(($fechaAnuncio!=$fechaAnuncioAnterior) && (!$this->getEsEvento())){
				$fechaAnuncioAnterior=$fechaAnuncio;
				$html.= '<h4>'.$fechaAnuncio.'</h4>';
			}
			if ($this->getEsEvento()){
				$subcat=array ("nombre"=>$row['subcatname'],"catid"=>$catid,"id"=>$row['subcatid']);
				$categoriaLink=$this->registro->get('helper')->getLinkSubcat($subcat);
				if (($subcatActual!=$row['subcatname']) && (!in_array($row['subcatid'],$subcatMostradas))){
					$html.= '<h4>'.$categoriaLink.'</h4>';
					array_push($subcatMostradas,$row['subcatid']);
				}
				$subcatActual=$row['subcatname'];
				$fechaEvento=$this->registro->get('helper')->getFechaSoloMesyDia($row['fechaInicio']).'-'.$this->registro->get('helper')->getFechaSoloMesyDia($row['fechaFin']).': ';
			}
			$extrasAnuncio.=$this->registro->get('helper')->validarLongitudMayorCero($row['lugar'])? ' - (<font size="-1">'.strtolower($row['lugar']).'</font>) ': '';
			$this->registro->get('imagenes')->cargar($row['adid'],$catid);			
			if ($this->registro->get('imagenes')->tengo()){
				$extrasAnuncio.=' <span class="p"> foto </span> ';
			}
			$categoriaLink='';			
			if (is_null($subcatid) && !$this->getEsEvento()){
				$subcat=array ("nombre"=>$row['subcatname'],"catid"=>$catid,"id"=>$row['subcatid']);
				$categoriaLink=' <<<i>'.$this->registro->get('helper')->getLinkSubcat($subcat).'</i>';
			}
			$titulo=strtolower($row['titulo']);
			if (!empty($row['precio'])){
				$titulo=str_replace('<<TITULO>>',$titulo,$formatoTitulo);
				$titulo=str_replace('<<PRECIO>>',$row["precio"],$titulo);
			}
			$html.='<p>'.$fechaEvento.'<a href="'.$this->registro->get('helper')->getAnuncioLink($row["adid"],$row["titulo"],$catid,$row["subcatid"],$row['subcatname']).'">'.$titulo.'</a>'.$extrasAnuncio.$categoriaLink.'</p>';
    		$i++;
		}
		return $html;
	}
	public function getLimiteListados($pagina){
		$pagina=$pagina/LIMITE_POR_PAGINA;
		$limite=intval(($pagina-1)*LIMITE_POR_PAGINA).','.intval($pagina*(LIMITE_POR_PAGINA+1));
		//siempre traer 101 items para ver si tengo que imprimir el cartel de siguientes anuncios
		return $limite;
	}
	public function getAnunciosEncontrados(){
		return $this->anunciosEncontrados;
		
	}
	public function getLoMejor($t_flags,$h){
		global $t_anuncios,$t_subcats;
		$q="SELECT adid,catid,count(*) as cantidad FROM ".$t_flags." WHERE flag=".FLAG_LOMEJOR." GROUP BY adid,catid ORDER BY cantidad desc LIMIT ".LO_MEJOR_LIMITE_PAGINA.";";
		$result=db::getInstance()->query($q);
		$html='';
		while ($row = $result->fetch_array()){
			$sql="SELECT titulo,fechaHora FROM ".$t_anuncios.$row["catid"]." WHERE adid=".$row["adid"]." and habilitado='".ANUNCIO_HABILITADO."';";
			$result2=db::getInstance()->query($sql);
			$anuncio_data=$result2->fetch_array();
			if (!empty($anuncio_data)){
				//para evitar que muestre anuncios eliminados
				$fecha=$h->getFechaDateTime($anuncio_data['fechaHora']);
				$html.='<p>'.$fecha.' - <a href="'.$this->registro->get('helper')->linkAnuncio($row["adid"],$row["catid"]).'">'.$anuncio_data['titulo'].'</a></p>';	
			}			
		}
		return $html;
		
	}
	
}
?>