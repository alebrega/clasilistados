<?php
class cListados_pc extends cPageController_fw{
	
	public function init(){
		$ciudad=null;
		$estado=null;
		if (!is_null($_GET["ciudad"])){
			$ciudad=$_GET["ciudad"];
		}
		if (!is_null($_GET["estado"])){
			$estado=$_GET["estado"];
		}
		
		if ( is_null($ciudad) && is_null($estado) && (is_null($_GET['busqueda'])) ){
			$h->irSitios();
			//sino esta seteado el estado y la ciudad en cookies o en la url que vaya a elegir su sitio
		}
		if (!is_null($ciudad)){
			$estado=null;
		}
		$locacion=new Locacion($ciudad,$estado);
		
		$categoria=new Categoria();
		//$anuncio=new Anuncio($registro,$t_anuncios);
		$imagenes=new Imagenes($this->getRegistro());
		$this->registro->set('imagenes',$imagenes);
		$this->registro->set('locacion',$locacion);
		$categoria_id=$_GET['cat'];
		$categoria->setCategoriaId($categoria_id);
		$subcategoria_id=null;
		$mostrarRss=true;
		if (!is_null($_GET['busqueda'])){
			$busqueda=new Busqueda($_GET['catid'],$_GET['busqueda'],$_GET['princ'],$_GET['c'],$db,$h,$locacion,$_GET['cat']);
			$mostrarRss=false;
		}
		//$categoria_nombre=array_search ($categoria_id,$cats);
		$listados=new Listados($this->registro);
		$listados->getMaketimeBusqueda();//genera el fecha y hora de la busqueda del listado
		$this->setVar('fechaHoraBusqueda',$listados->getFechaHoraBusqueda());
		$pagina=$_GET['p'];
		if (empty($pagina)){
			$pagina=LIMITE_POR_PAGINA;
		}else{
			$pagina=$pagina+LIMITE_POR_PAGINA;
		}
		if ($_GET['cat']==$cats["eventos"]){
			$listados->setEsEvento(true);
			if (empty($_GET['fecha'])){
				$fecha=date("d")."-".date("n")."-".date("Y");
			}else{
				$fecha=$_GET['fecha'];
			}
			$listados->setFechaEvento($fecha);
		}
		$this->registro->set('categoria',$categoria);
		//$registro->set('anuncio',$anuncio);
		$this->setVar('listados',$listados);
		$this->setVar('busqueda',$busqueda);
	}

}