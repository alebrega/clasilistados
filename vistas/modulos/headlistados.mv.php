<title><?=$this->getVar('categoriaSeo')?> - <?=$this->getVar('location')?></title>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/metas/listados.inc.php");
?>
<link rel=alternate type="application/rss+xml" href="<?=$this->getVar('urlRss')?>" title="clasilistados rss feed | <?=$this->getVar('categoriaSeo')?> en <?=$this->getVar('location')?> ">
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header.inc.php");
?>