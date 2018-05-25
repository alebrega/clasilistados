<script type="text/javascript" src="<?php echo version('http://'.$_SERVER['SERVER_NAME'].'/js/jquery-1.11.1.min.js');?>"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#flagChooser a.fl").click(function(){
                var parametros = {
                    "flag_tipo" : this.id,
                    "categoria_id" : <?=$categoria->getCategoriaId()?>,
                    "id" : <?=$anuncio->getId()?>
                    
                };
                alert(parametros.flag_tipo);
                $.ajax({
	   		type: "POST",
	   		url: "http://clasilistados.org/flag",
	   		dataType: "text",
	   		//data: "flag_tipo="+this.id+"&categoria_id="+<?=$categoria->getCategoriaId()?>+"&id="+<?=$anuncio->getId()?>,
	   		data: parametros,
                        success: function(msg){
	   			$("#flagChooser").slideUp("fast",function(){
	   				$("#flagMsg").html(msg);
	   			});
	   		}
	 	});
	});
});
function validarRespuesta(form, min_content) {
	if (form.comentarios.value.length < min_content) {
		alert("La descripcion debe tener como minimo" + " " + min_content + " caracteres.");
		form.comentarios.focus();
		return false;
	}
 	if (form.email.value.length == 0) {
		alert("Debes escribir un e-mail valido");
 		form.email.focus();
		return false;
	}
 	return true;
}
</script>