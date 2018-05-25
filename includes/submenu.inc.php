<?php
$pagina=buscador::getInstance()->getPagina();
$total=buscador::getInstance()->getTotalAnuncios();
if ($pagina<2){
	$inicio=1;
}else{
	$inicio=LIMITE_POR_PAGINA*($pagina-1);
}
if ($encontrados < LIMITE_POR_PAGINA){
	if ($total<LIMITE_POR_PAGINA){
		$fin=$encontrados;
	}else{
		$fin=$inicio+$encontrados;
	}
}else{
	$fin=LIMITE_POR_PAGINA*$pagina;
}
if ($encontrados>0){
	$bloqueOrden='<div class="submenu">';
	if (empty($_GET['ordenar'])){
		$total=buscador::getInstance()->getTotalAnuncios();
		$bloqueOrden.='<span>Mostrando '.$inicio.'-'.$fin.' de <b>'.$total.'</b>. </span>';
	}
	$_SERVER['QUERY_STRING']=str_replace('ordenar','',$_SERVER['QUERY_STRING']);
	if ($_GET['urg']!=1){
		$bloqueOrden.=' <span>Ordenar por: ';
	}
	$subcatid=$categoria->getSubCategoriaId();
	
	if (empty($subcatid)){
		$bloqueOrden.='<input type="hidden" name="cat" value="'.$categoria->getCategoriaId().'" />';
	}else{
		$bloqueOrden.='<input type="hidden" name="cat" value="'.$categoria->getCategoriaId().'---'.$categoria->getSubCategoriaId().'" />';
	}
	if (!empty($_GET['busqueda'])){
		$bloqueOrden.='<input type="hidden" name="busqueda" value="'.$_REQUEST['busqueda'].'" />';
	}
	/*
	if ($_GET['buscarPorPais']=='1'){
		$bloqueOrden.='<input type="hidden" name="buscarPorPais" value="1" />';
	}*/
	if ($_GET['busq_img']=='1'){
		$bloqueOrden.='<input type="hidden" name="busq_img" value="1" />';
	}
	if ($_GET['busqTipo']=='busq_tit'){
		$bloqueOrden.='<input type="hidden" name="busqTipo" value="busq_tit" />';
	}
	if ($categoria->esEvento()){
		if (!empty($_GET['fecha'])){
			$bloqueOrden.='<input type="hidden" name="fecha" value="'.$_GET['fecha'].'" />';
		}else{
			$bloqueOrden.='<input type="hidden" name="fecha" value="'.date("d").'-'.date("n").'-'.date("Y").'" />';
		}	
	}
	
	if ($locacion->esCiudad()){
		$ubicacion='ciudad-'.$locacion->getCiudadURL();
	}else{
		$ubicacion='estado-'.$locacion->getEstadoURL();
	}
	if (empty($subcatid)){
		$cat=$categoria->getCategoriaId();
	}else{
		$cat=$categoria->getCategoriaId().'---'.$categoria->getSubCategoriaId();
	}
	$query="";
	if (!empty($_GET['busqueda'])){
		//$query="&busqueda=".$_GET['busqueda'];
		$query="&".$_SERVER['QUERY_STRING'];
	}
	
	if ($_GET['urg']!=1){
		if (empty($_GET['ordenar']) || ($_GET['ordenar']=='rel')){
			$bloqueOrden.='<b>Más Relevantes</b>';
		}else{
			$bloqueOrden.='<a href="'.$h->getHost().'/listado/'.$ubicacion.'/'.$categoria->getCategoriaNombre().'/categoria-'.$cat.'?ordenar=rel'.$query.'">Más Relevantes</a>';
		}
		$bloqueOrden.=' | ';
		if (($_GET['ordenar']=='fecha')){
			$bloqueOrden.='<b>Más Recientes</b>';
		}else{
			$bloqueOrden.='<a href="'.$h->getHost().'/listado/'.$ubicacion.'/'.$categoria->getCategoriaNombre().'/categoria-'.$cat.'?ordenar=fecha'.$query.'">Más Recientes</a>';
		}
		
		if ($categoria->esCompraVenta() || $categoria->esVehiculos() || $categoria->esVivienda()){
			$bloqueOrden.=' | ';
			if (($_GET['ordenar']=='menor_precio')){
				$bloqueOrden.='<b>Menor Precio</b>';
			}else{
				$bloqueOrden.='<a href="'.$h->getHost().'/listado/'.$ubicacion.'/'.$categoria->getCategoriaNombre().'/categoria-'.$cat.'?ordenar=menor_precio'.$query.'">Menor Precio</a>';
			}
			$bloqueOrden.=' | ';
			if (($_GET['ordenar']=='mayor_precio')){
				$bloqueOrden.='<b>Mayor Precio</b>';
			}else{
				$bloqueOrden.='<a href="'.$h->getHost().'/listado/'.$ubicacion.'/'.$categoria->getCategoriaNombre().'/categoria-'.$cat.'?ordenar=mayor_precio'.$query.'">Mayor Precio</a>';
			}
		}
	}
	echo $bloqueOrden.'</span></div>';

	if (!empty($_GET['busqueda'])){
		require_once($_SERVER["DOCUMENT_ROOT"].'/includes/paginacion.inc.php');
	}
}
?>