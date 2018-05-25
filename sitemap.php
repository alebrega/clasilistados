<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
	header("Content-type: text/xml");
	echo '<?xml version="1.0" encoding="UTF-8"?>
	<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
	$path='sitemaps';
	$dir_handle = @opendir($path) or die("Unable to open $path");
  	while ($file = readdir($dir_handle))
  	{
	    if ($file!='.' AND $file!='..' AND strpos($file, '.xml'))
	    {
	    	echo '<sitemap><loc>http://clasilistados.org/sitemaps/'.$file.'</loc></sitemap>';	
	  	}
  	}	
	echo '</sitemapindex>';
?>