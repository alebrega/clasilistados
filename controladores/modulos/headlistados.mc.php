<?php
class cHeader_mc extends cModuleController_fw{
	public function init(){
		$this->setVar('urlRss',$this->registro->get('helper')->getHost().$_SERVER['REQUEST_URI'].'/rss');
		$this->setVar('location',$this->registro->get('locacion')->getLocation());
		$categoria=$this->registro->get('categoria');
		if (!is_null($_GET['busqueda'])){
			$categoria_id=$_GET['catid'];
			$categoria->setCategoriaId($categoria_id);
			$categoria_nombre=$categoria->getCategoriaNombre();
			$categoriaBuscada='todo '.$categoria_nombre;
			$categoriaSeo=$_GET['busqueda'].' - '.$categoria_nombre;
		}else{
			if (is_null($_GET['subcat'])){
				$categoria->setCategoriaId($categoria_id);
				$categoria_nombre=$categoria->getCategoriaNombre();
				$categoriaBuscada='todo '.$categoria_nombre;
				$categoriaSeo=$categoria_nombre;
				$linkSig=$this->registro->get('helper')->getCategoriaLinkHref($categoria_id);
			}else{
				$subcategoria_id=$_GET['subcat'];
				$categoria->getSubCategoriaData($subcategoria_id); //le pasa el id de la subcategoria
				$categoria_nombre=$categoria->getSubCategoriaNombre();
				$categoriaBuscada=$categoria_nombre;
				$categoria->setCategoriaId($categoria_id);
				$categoriaSeo=$categoria->getSubCategoriaNombre().' - '.$categoria->getCategoriaNombre();
				$subcat=array("catid"=>$categoria_id,"nombre"=>$categoria_nombre,"id"=>$subcategoria_id);
				$linkSig=$this->registro->get('helper')->getLinkSubcatHref($subcat);
			}	
		}
		$this->setVar('categoriaSeo',$categoriaSeo);
	}
}

?>