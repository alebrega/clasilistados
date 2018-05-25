<?php
class Busqueda
{
	private $esPrincipal=false;
	private $catABuscar=null;
	private $esCategoria=false;
	private $esSubcategoria=false;
	private $busqueda=null;
	private $db;
	private $helper;
	private $catPadre=null;
	private $locacion=null;
	private $anunciosEncontrados=0;
	
	public function __construct($catABuscar,$busqueda,$esPrincipal,$esCat, Db $db,Helper $h, Locacion $locacion ,$catPadre=null){
		$this->catABuscar=$catABuscar;
		$this->catPadre=$catPadre;
		$this->busqueda=$busqueda;
		
		if ($esPrincipal==1){
			$this->esPrincipal=true;
		}
		if ($esCat=="c"){
			$this->esCategoria=true;
		}elseif ($esCat=="s"){
			$this->esSubcategoria=true;
		}
		$this->locacion=$locacion;
		$this->db=$db;
		$this->helper=$h;
	}
	public function getResultados(){
		global $t_anuncios,$t_imagenes,$t_subcats;
		$html='';
		if ($this->locacion->esCiudad()){
			$zona_geografica="a.cityid=".$this->locacion->getCiudadId();
		}else{
			$zona_geografica="a.stateid=".$this->locacion->getEstadoId();
		}
		if ($this->esCategoria){
			$categoria_busqueda="AND a.catid=".$this->catABuscar;
		}else{
			$categoria_busqueda="AND a.subcatid=".$this->catABuscar;
		}
		$categoria_busqueda="AND a.catid=".$this->catABuscar;
		if (!is_null($this->catPadre)){
			$tabla=$t_anuncios.$this->catPadre; 
			$catid=$this->catPadre;
		}else{
			$tabla=$t_anuncios.$this->catABuscar; 
			$catid=$this->catABuscar;
		}
		$q_busqueda="SELECT count(*) as encontrados FROM $tabla a WHERE a.titulo like '%".$this->busqueda."%' AND a.habilitado='1' AND $zona_geografica ;";
		$resultado_busq=$this->db->query($q_busqueda);
		if (!$resultado_busq){
			$html.= '<br /><hr class="hrstyle"/><b><br/>La búsqueda no ha dado resultados ( todos los terminos tienen que coincidir )</b>';
			return $html;
		}
		$row=$resultado_busq->fetch_array();
		if ($row['encontrados']>0){
			$html.="<h4>Encontrados ".$row['encontrados']." mostrando 1 - ".$row['encontrados']."</h4>";
			$q="SELECT a.titulo,a.adid,a.lugar,s.subcatname,s.send_images, DATE(a.fechaHora) as fechaHora,a.subcatid FROM $tabla a INNER JOIN $t_subcats s 
			ON a.subcatid=s.subcatid WHERE  $zona_geografica AND s.enabled='1' AND a.habilitado='1' $categoria_busqueda AND  titulo like '%".$this->busqueda."%' ORDER BY a.fechaHora DESC;";
		}else{
			$html.= '<br /><hr class="hrstyle"/><b><br/>La búsqueda no ha dado resultados ( todos los terminos tienen que coincidir )</b>';
			return $html;
		}
		$fechaAnuncioAnterior="";
		$result=$this->db->query($q);
		$this->anunciosEncontrados=$result->num_rows;
		while ($row = $result->fetch_array()){
			$extrasAnuncio="";
			$fechaAnuncio=$this->helper->getFechaAnuncios($row['fechaHora']);
			if($fechaAnuncio!=$fechaAnuncioAnterior){
				$fechaAnuncioAnterior=$fechaAnuncio;
				$html.= '<h4>'.$fechaAnuncio.'</h4>';
			}
			//TODO: Mejorar los extras al titulo del anuncio!
			$extrasAnuncio.=(strlen($row['lugar'])>0)? ' - '.strtolower($row['lugar']): '';
			
			$q_img="SELECT count(*) as imagenes FROM ".$t_imagenes." WHERE adid=".$row['adid']." and catid=".$catid." ;";
			$imagenes_result=$this->db->query($q_img);
			$imagenes_result = $imagenes_result->fetch_array();
			
			if ($imagenes_result['imagenes']>0){
				$extrasAnuncio.=' <span class="p"> foto </span> ';
			}
			
			$html.= '<p><a href="'.$this->helper->getAnuncioLink($row["adid"],$row["titulo"],$catid,$row["subcatid"],$row['subcatname']).'">'.strtolower($row['titulo']).'</a>'.$extrasAnuncio.'</p>';
    	}
    	if (strlen($html)==0){
    		$html.= '<br /><hr class="hrstyle"/><b><br/>La búsqueda no ha dado resultados ( todos los terminos tienen que coincidir )</b>';
    	}
		return $html;
	}
	public function getAnunciosEncontrados(){
		return $this->anunciosEncontrados;
	}
}
?>