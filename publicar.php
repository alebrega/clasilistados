<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<title>clasilistados - publica tu anuncio en <?php echo $location ?></title>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/metas/noindex.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/headerssl.inc.php");
?>
</head>
<?php flush(); ?>
<body id="pp">
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/headerposting.inc.php");
echo '<blockquote>';
//echo $h->getAdvertenciaCrossPosting();
echo $h->getElijaTipoAnuncio();
$categorias=$categoria->getCategorias();
$html='<ul>';
$especialCatsPosting=$categoria->getSubCategoriasEspecialesPosting();
foreach ($categorias as $cat){
	if ($cat['catid']!=$cats['currículum']){
		$html.='<li class="listaEspaciada">'.$h->getPostingCategoriaLink($cat['catid'],$cat["nombre"],$cat["se_ofrece"]).'</li>';
	}
    foreach ($especialCatsPosting as $espCat){
    	if ($espCat["catid"]==$cat['catid']) {
    		$html.='<li class="listaEspaciada">'.$h->getPostingSubCategoriaLink($espCat["catid"],$espCat['subcatid'],$espCat["se_ofrece"]).'</li>';
    	}
    }
    //$html.= "<br />";
}
    $html.='</ul>';
echo $html;
echo '</blockquote>';
	
		
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/footerposting.inc.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body></html>