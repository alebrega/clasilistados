<?php
require("GTranslate.php");

 try{
       $gt = new Gtranslate;
       echo "Translating [Hello World] from English to German => ".$gt->english_to_german("hello world")."<br/>";
	echo "Translating [Ciao mondo] Italian to English => ".$gt->it_to_en("Ciao mondo")."<br/>";
echo "Translating [Hello world] English to spanish => ".$gt->en_to_es("hello world")."<br/>";
 } catch (GTranslateException $ge)
 {
       echo $ge->getMessage();
 }

?>
