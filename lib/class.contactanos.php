<?php
class Contactanos{
	
	private $correo=null;
	private $registro=null;
	
	public function __construct(Email $email,Registro $registro){
		$this->correo=$email;
		$this->registro=$registro;
	}
	public function generarReclamo($tipo_reclamo,$enviarA,$t_reclamos){
		$email=$_POST['email'];
		$nombre=$_POST['nombre'];
		$ubicacion=$_POST['ubicacion'];
		$asunto=$_POST['asunto'];
		$reclamo=$_POST['reclamo'];
		$q="INSERT INTO ".$t_reclamos." (nombre,email,tipo,ubicacion,asunto,descrip) VALUES ('$nombre','$email','$enviarA','$ubicacion','$asunto','$reclamo'); ";
		$result=$this->registro->get("db")->query($q);
		$ticket_numero=$this->registro->get("db")->insert_id();
		if ($result){
			$this->enviarReclamo($tipo_reclamo,$ticket_numero);
			return true;
		}else{
			return false;
		}
	}
	public function enviarMailFaqContactanos($email,$asunto){
		$contenido=file_get_contents($_SERVER['DOCUMENT_ROOT']."/plantillas/email_contactanos_faq.html");
		$contenido=str_replace("<<CONTACTARNOS>>",'<a href="'.$this->registro->get("helper")->getContactanosLinkHref().'" rel="nofollow">contactarnos</a>',$contenido);
		$this->correo->enviar('Re: '.$asunto, $email, $contenido, CONTACTANOS_FAQ);
	}
	public function enviarReclamo($tipo_reclamo,$ticket_numero){
		$email=$_POST['email'];
		$nombre=$_POST['nombre'];
		$ubicacion=$_POST['ubicacion'];
		$asunto=$_POST['asunto'];
		$reclamo=$_POST['reclamo'];
		$contenido=file_get_contents($_SERVER['DOCUMENT_ROOT']."/plantillas/reclamos.html");
		$contenido=str_replace("<<TIPO>>",$tipo_reclamo,$contenido);
		$contenido=str_replace("<<NOMBRE>>",$nombre,$contenido);
		$contenido=str_replace("<<CORREO>>",$email,$contenido);
		$contenido=str_replace("<<UBICACION>>",$ubicacion,$contenido);
		$contenido=str_replace("<<ASUNTO>>",$asunto,$contenido);
		$contenido=str_replace("<<RECLAMO>>",$reclamo,$contenido);
		$contenido=str_replace("<<TICKET>>",$ticket_numero,$contenido);
		$this->correo->enviar("clasilistados: consulta", "3@clasilistados.org", $contenido, RECLAMOS);
	}
}
?>