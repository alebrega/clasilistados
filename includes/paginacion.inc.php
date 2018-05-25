<?php
$url=parse_url($_SERVER['REQUEST_URI']);
$url['query']=preg_replace('/&p=(.*)/', '', $url['query']);
$_SERVER['REQUEST_URI']=$url['path'].'?'.$url['query'];
$html.='<div class="sh" style="position: relative; text-align: center;">';
if (($inicio+1)>LIMITE_POR_PAGINA){
	$html.='<span style="position: absolute; left: 0;"><a href="'.$_SERVER['REQUEST_URI'].'&p='.($pagina-1).'"><b>&lt;&lt; anuncios anteriores</b></a></span>';
}
if ($total>(LIMITE_CANTIDAD_PAGINAS*LIMITE_POR_PAGINA)){
	$total=LIMITE_CANTIDAD_PAGINAS*LIMITE_POR_PAGINA;
}
if (($fin+1)<$total){
	$html.='<span style="position: absolute; right: 0;"><a href="'.$_SERVER['REQUEST_URI'].'&p='.($pagina+1).'"><b>anuncios siguientes&gt;&gt;</b></a></span>';
}
$html.='<b>Encontrados: '.$total.' Mostrando: '.$inicio.' - '.$fin.'</b>';
if ($total>LIMITE_POR_PAGINA){
	$html.='<br>[ ';
	$paginas=intval(floor($total/LIMITE_POR_PAGINA)+1);
	if ($paginas>LIMITE_CANTIDAD_PAGINAS){
		$paginas=LIMITE_CANTIDAD_PAGINAS;
	}
	for ($i=1;$i<=$paginas;$i++){
		if ($pagina==$i){
			$indice[]='<b>'.$i.'</b>';
		}else{
			$indice[]='<span><a href="'.$_SERVER['REQUEST_URI'].'&p='.$i.'">'.$i.'</a></span>';
		}
	}				
	$html.=implode(' | ',$indice);
	$html.=' ]';
}

$html.='</div>';
echo $html;
?>