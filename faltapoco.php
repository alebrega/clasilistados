<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<title>clasilistados - en construccion - dejanos tu correo</title>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/metas/noindex.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header.inc.php");
?>
</head>
<?php flush(); ?>
<body>
<?php
if (!isset ($_POST['submit'])){
echo '<form method="post" action="faltapoco.html"  name="registro">';
echo "Cada vez falta menos...";
echo "<br />";
echo "Dejanos tu email para que seas el primero en enterarte!  ".'<input type="text" name="email" size="40" onClick="this.value=\'\'" value="pone tu correo electrónico aquí"/><input type="submit" name="submit" value="OK" />';
echo "</form>";
}
else {
	
	$q="INSERT INTO ".$t_faltapoco." (email) VALUES ('".$_POST['email']."'); ";
	$db->query($q);
	echo "Gracias! ";
}
 
?>

<br />
<pre style="font-size: larger;">
 _____________
(  <a href="/">¿quieres </a>  ) 
(  <a href="/">volver a</a> )
(  <a href="/">clasilistados?</a>  )
 ------------- 
       o
        o            
         o        
	  ("`-''-/").___..--''"`-._
	   `6_ 6  )   `-.  (     ).`-.__.`)
	   (_Y_.)'  ._   )  `._ `. ``-..-'
	 _..`--'_..-_/  /--'_.' ,'
	(il),-''  (li),'  ((!.-'
	        



</pre>
<br />
<br />
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body></html>