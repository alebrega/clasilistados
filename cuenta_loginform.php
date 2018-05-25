<h3>clasilistados: ingresa a tu cuenta</h3>

<p><font color=red size=4> &nbsp;<?=$mensajesHTML?></font></p>
  <form action="<?=$h->getMiCuentaLink()?>" method="post" name="login">
  <input type="hidden" name="ir" value="<?=$_REQUEST['ir']?>">
  <blockquote>
  <table border="0" cellpadding="6" class="bloque_borde_gris">
        <tr>

                <td colspan="2" bgcolor="#dddddd"><b>Accede a tu cuenta en clasilistados</b></td>
        </tr>
    <tr>
      <td colspan="2" style="border-bottom: 4px solid #dddddd">
<B>NOTA: no todos los que publican anuncios en clasilistados tienen una cuenta.</B><BR><font size=-1>
Si no estas seguro si tienes una cuenta con clasilistados pídenos <a href="<?=$h->getReiniciarPasswordLink()?>">reiniciar tu contraseña</a></font>
</td>
    </tr>
    <tr>

      <td align="right"><b>Correo:</b></td>
      <td><input type="text" name="email" size="40" maxlength="64" tabindex="1" value="<?=$_POST['email']?>"></td>
    </tr>
    <tr>
      <td align="right"><b>Contraseña:</b></td>
      <td><input type="password" name="password" size="10" maxlength="40" tabindex="1" value="<?=$_POST['password']?>"></td>
    </tr>
    
    <tr>

      <td>&nbsp;</td>
      <td><input type="submit" value="Entrar" tabindex="1" name="login"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><font size="-1"><a href="<?=$h->getReiniciarPasswordLink()?>">¿olvidaste tu contraseña?</a></font></td>

    </tr>
  </table>
  </blockquote>
  </form>
<blockquote>
<p>
Si no tienes una cuenta haz clic <a href="<?=$h->getCrearCuentaHref()?>">aquí</a> para crear una.
</p>
<p>
Si necesitas ayuda, haz clic <a href="<?=$h->getAyudaLinkHref()?>">aquí</a> para más información

</p>
</blockquote>
<script type="text/javascript">
document.login.email.focus();
</script>