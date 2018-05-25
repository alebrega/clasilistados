<?php
class Categoria {
	
	private $subcats=array ();
	private $categoria_id=null;
	private $subcategoria_id=null;
	private $subcategoriaNombre=null;
	private $tieneImagenes;
	private $categorias=null;
	private $registro=null;
	private $subcatHabilitada=null;
	
	public function __construct (){
		global $cats;
		$this->categorias=$cats;
    }
    public function getSubCategoria($id){
    	return $this->subcats[$id];
    }    
    public function cargarTodaslasCategorias(){
    	global $t_subcats;
    	$i=0;
    	$this->subcats=Cache::get("todasSubcategorias");
    	if (!$this->subcats){
	    	$q="SELECT subcatname,subcatid,catid from ".$t_subcats." WHERE enabled='1' AND (";
	    	foreach ($this->categorias as $catname=>$id){
	    		if ($i==0){
	    			$q.=" catid = ".$id." ";
	    		}
	    		else{
	    			$q.=" OR catid = ".$id."  ";
	    		}
	    		$i++;
	    		
	    	}
	    	$q.=") ORDER BY subcatname asc; ";
	    	$result=db::getInstance()->query($q);
	    	while ($row = $result->fetch_array()){
	    		$this->subcats[$row['catid']][]= array ("nombre"=>$row['subcatname'], "id"=>$row['subcatid'], "catid"=>$row['catid']);
	    	}
	    	Cache::set("todasSubcategorias",$this->subcats,true,86400);
    	}
    	
    }
	public function getSubCategoriasEspecialesPosting(){
		global $t_subcats;
		$especialCats=Cache::get('subCategoriasEspecialesPosting');
		if (!$especialCats){
			$especialCats=array();
			$q="SELECT subcatid,catid,subcatname,se_ofrece FROM ".$t_subcats." WHERE enabled='1' and publiDirecto='1' ORDER BY pos asc;";
			$result=db::getInstance()->query($q);
			while ($row = $result->fetch_array()){
				$especialCats[]=array ("catid"=>$row['catid'],"subcatid"=>$row['subcatid'],"subcatname"=>$row["subcatname"],"se_ofrece"=>$row["se_ofrece"]);
			}
			Cache::set('subCategoriasEspecialesPosting',$especialCats,true,86400);
		}
		return $especialCats;
	}
	public function mostrarCategorias(){
		$categorias=$this->getCategorias();
		var_dump($categorias);
	}
	public function getCategorias(){
		global $t_cats;
		$categorias=Cache::get('categoriasDelSitio');
		if (!$categorias){
			$q="SELECT catid,catname,se_ofrece FROM ".$t_cats." WHERE enabled='1' ORDER BY pos asc;";
			$result=db::getInstance()->query($q);
	    	$categorias=array();
			while ($row = $result->fetch_array()){
				$categorias[]=array ("catid"=>$row['catid'],"nombre"=>$row["catname"],"se_ofrece"=>$row["se_ofrece"]);
	    	}
	    	Cache::set('categoriasDelSitio',$categorias,true,86400);
		}
		return $categorias;
		
	}
	public function getSubCategorias($catid){
		global $t_subcats;
		$subcategorias=Cache::get('subCategoriasDelSitio-'.$catid);
		if (!$subcategorias){
			$q="SELECT subcatid,subcatname FROM ".$t_subcats." WHERE enabled='1' and catid=".$catid." ORDER BY subcatname asc;";
			$result=db::getInstance()->query($q);
	    	while ($row = $result->fetch_array()){
	    		$subcategorias[]=array("subcatid"=>$row['subcatid'],"nombre"=>$row["subcatname"]);
	    	}
	    	Cache::set('subCategoriasDelSitio-'.$catid,$subcategorias,true,86400);
		}
		return $subcategorias;
	}
	/**
	 * Devuelve si una categoria esta habilitada en el sitio
	 *
	 * @return bool
	 */
	public function esCategoriaHabilitada($catid){
		if (in_array($catid, $this->categorias)){
			return true;
		}
		return false;
	}
	public function esSubcategoriaHabilitada(){
		if ($this->subcatHabilitada==1){
			return true;
		}else{
			return false;
		}
	}
	
	public function setSubCategoriaHabilitada($habilitada){
		$this->subcatHabilitada=$habilitada;
	}
	public function getCategoriaNombre(){
		return array_search($this->getCategoriaId(), $this->categorias);
	}
	public function getSubCategoriaData($subcatid){
		global $t_subcats;
		$sub=Cache::get('subcategoriaDataId-'.$subcatid);
		if (!$sub){
			$q="SELECT catid,subcatname,send_images,enabled FROM ".$t_subcats." WHERE subcatid=".$subcatid.";";
			$result=db::getInstance()->query($q);
			if (!$result){
				return false;
			}
	    	$row = $result->fetch_array();
	    	$sub=array("nombre"=>$row['subcatname'],"subcatname"=>$row['subcatname'],"catid"=>$row['catid'],"send_images"=>$row['send_images'],"enabled"=>$row['enabled']);
	    	Cache::set('subcategoriaDataId-'.$subcatid,$sub,true,86400);
		}
    	$this->setSubCategoriaNombre($sub['subcatname']);
    	$this->setCategoriaId($sub['catid']);
    	$this->setSubCategoriaId($subcatid);
    	$this->setTieneImagenes(intval($sub['send_images']));
    	$this->setSubCategoriaHabilitada($sub['enabled']);
	}
	public function setTieneImagenes($val){
		if ($val==1){
			$this->tieneImagenes=true;
		}else{
			$this->tieneImagenes=false;
		}
	}
	public function getTieneImagenes(){
		return $this->tieneImagenes;
	}
	private function setSubCategoriaNombre($nombre){
		$this->subcategoriaNombre=$nombre;
	}
	public function setCategoriaId($id){
		$this->categoria_id=$id;
	}
	public function setSubCategoriaId($id){
		$this->subcategoria_id=$id;
	}
	public function getSubCategoriaNombre(){
		return $this->subcategoriaNombre;
	}
	public function getCategoriaId(){
		return $this->categoria_id;
	}
	public function getSubCategoriaId(){
		return $this->subcategoria_id;
	}
	public function esCompraVenta(){
		return ($this->getCategoriaId()==4)?true:false;
	}
	public function esVivienda(){
		return ($this->getCategoriaId()==3)?true:false;
	}
	public function esEmpleo(){
		return ($this->getCategoriaId()==6)?true:false;
	}
	public function esPersonales(){
		return ($this->getCategoriaId()==2)?true:false;
	}
	public function esTTemporal(){
		return ($this->getCategoriaId()==7)?true:false;
	}
	public function esEvento(){
		return ($this->getCategoriaId()==11)?true:false;
	}
	public function esComunidad(){
		return ($this->getCategoriaId()==9)?true:false;
	}
	public function esServicios(){
		return ($this->getCategoriaId()==5)?true:false;
	}
	public function esCurriculum(){
		return ($this->getCategoriaId()==8)?true:false;
	}
	public function esMascotas(){
		return ($this->getCategoriaId()==12)?true:false;
	}
	
	public function esSociales(){
		return ($this->getCategoriaId()==14)?true:false;
	}
	
	public function esVehiculos(){
		return ($this->getCategoriaId()==13)?true:false;
	}
	
	
	
}
?>