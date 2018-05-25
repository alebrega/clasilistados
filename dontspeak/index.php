<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<title>clasilistados - don't speak spanish?</title>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/metas/noindex.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header.inc.php");
?>
</head>
<?php flush(); ?>
<body>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/headerayuda.inc.php");
?>
<p style="margin-top: 0.49cm; margin-bottom: 0.49cm;"><font
 face="Bookman Old Style, serif">dear
user, <br>
<br>
we apologize but at the moment we are not offering an
english or any other language version for clasilistados because our
website was designed to assist the spanish speaking community in the
usa.&nbsp; </font>
</p>
<p style="margin-top: 0.49cm; margin-bottom: 0.49cm;"><font
 face="Bookman Old Style, serif">however,
if you would like to post an ad on clasilistados, we suggest you
compose it in your native language and have it translated into
spanish by a translation website or a software fit for that purpose.
&nbsp;</font></p>
<p style="margin-top: 0.49cm; margin-bottom: 0.49cm;"><font
 face="Bookman Old Style, serif">thanks
to your feedback, clasilistados has been growing at an unprecedented
pace; so if you have any comments or suggestions, please fill in the
form below and we promise to get back to you quickly. <br>
<br>
gracias!
</font></p>
<p style="margin-top: 0.49cm; margin-bottom: 0.49cm;"><font
 face="Bookman Old Style, serif"><br>
the
clasilistados team </font>
</p>
<p style="color: red;"><?php 
echo $respuesta;
?></p>
<p>your message to the clasilistados team: </p>
<br />
<form action="<?=$h->getHost().'/dontspeak/index.html'?>" method="post">
  <table border="0">
    <tbody>
      <tr>
        <td align="right">name:</td>
        <td><input name="name"
 value="please enter it here" onclick="this.value='';"
 class="btnGris" type="text"></td>
      </tr>
      <tr>
        <td align="right">email:</td>
        <td><input name="email"
 value="please enter it here" onclick="this.value='';"
 class="btnGris" type="text"></td>
      </tr>
      <tr>
        <td align="right" valign="top">message:</td>
        <td><textarea cols="49" rows="12"
 name="message" onclick="this.value='';" class="btnGris">please
enter it here</textarea></td>
      </tr>
      <tr>
        <td align="right" valign="top"></td>
      </tr>
      <tr>
        <td align="right" valign="top"></td>
        <td align="right"><input name="submit" value="Submit" type="submit"><input name="submit" value="Reset" type="reset"></td>
      </tr>
    </tbody>
  </table>
</form>

<br />
<a href="terms-and-conditions.html" rel="nofollow">read our terms and conditions</a>
<br />
<br />
<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>

</body>
</html>