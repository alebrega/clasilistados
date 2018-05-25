<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<title>clasilistados - publica tu anuncio en <?php echo $location ?></title>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header.inc.php");
?>
</head>
<?php flush(); ?>
<body id="pp">
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/headerposting.inc.php");
?>

<blockquote>
	<?php
		if ($esElegirTipoAnuncio){
			echo $h->getAdvertenciaCrossPosting();
			echo $h->getElijaTipoAnuncio();
			echo $categoria->getCategoriasPosting();
		}
		else{
			echo $h->getElijaCategoria();
			echo $categoria->getSubCategoriasPosting($_GET['cat']);
		}
		
	require_once($_SERVER["DOCUMENT_ROOT"]."/includes/footerposting.inc.php");
?>
</blockquote>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body></html>