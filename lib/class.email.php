<?php

require_once($_SERVER['DOCUMENT_ROOT']."/classes/class.phpmailer.php");

class Email{
	
	private $correo=null;
	private $registro=null;
	private $t_envios=null;
	
	public function __construct(Registro $registro, $t_envios,  $smtpHost,  $smtpAuth,  $username,  $password){
		if (is_null($this->correo)){
			$this->correo = new PHPMailer();
			$this->correo->IsSMTP();                                      // set mailer to use SMTP
			$this->correo->Host = $smtpHost;  // specify main and backup server
			$this->correo->SMTPAuth = $smtpAuth;     // turn on SMTP authentication
			$this->correo->Username = $username;  // SMTP username
			$this->correo->Password = $password; // SMTP password
			$this->registro=$registro;
			$this->setFrom(); // setea el from por default
			$this->t_envios=$t_envios;
		}
	}
	function myCheckDNSRR($hostName, $recType = '') 
	{ 
	 if(!empty($hostName)) { 
	   if( $recType == '' ) $recType = "MX"; 
	   exec("nslookup -type=$recType $hostName", $result); 
	   // check each line to find the one that starts with the host 
	   // name. If it exists then the function succeeded. 
	   foreach ($result as $line) { 
	     if(eregi("^$hostName",$line)) { 
	       return true; 
	     } 
	   } 
	   // otherwise there was no mail handler for the domain 
	   return false; 
	 } 
	 return false; 
	}
	public function validar ($email) {
        if(preg_match('/^\w[-.\w]*@(\w[-._\w]*\.[a-zA-Z]{2,}.*)$/', $email, $matches))
        {
        	if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') return true;
            if(function_exists('checkdnsrr'))
            {
                if(checkdnsrr($matches[1] . '.', 'MX')) return true;
                if(checkdnsrr($matches[1] . '.', 'A')) return true;
            }else{
                if(!empty($hostName))
                {
                    if( $recType == '' ) $recType = "MX";
                    exec("nslookup -type=$recType $hostName", $result);
                    foreach ($result as $line)
                    {
                        if(eregi("^$hostName",$line))
                        {
                            return true;
                        }
                    }
                    return false;
                }
                return false;
            }
        }
        return false;
    }
    public function getCorreo(){
    	return $this->correo;
    }
    public function setFrom($from="noresponder@clasilistados.org",$fromName="clasilistados.org"){
    	$this->correo->From = $from;
    	$this->correo->FromName = $fromName;
    }
    public function configurar(){
    	$this->correo->AddCustomHeader("X-Originating-IP: [".$_SERVER['REMOTE_ADDR']."]");
		$this->correo->WordWrap = 64;  
		$this->correo->CharSet = "utf-8";
		$this->correo->IsHTML(true);                                  // set email format to HTML
    }
    public function setContenido($contenido){
    	$this->correo->Body    = $contenido;
        $Altmailbody    =str_replace("<br>","\n\r",$contenido);
        $Altmailbody    =str_replace("<br />","\n\r",$contenido);
        $Altmailbody    =str_replace("&nbsp;","",$contenido);
        $Altmailbody    =str_replace("&copy;","",$contenido);
        $this->correo->AltBody  =strip_tags($Altmailbody);
    }
    /**
     * Enviar un correo electronico
     *
     * @param string $asunto
     * @param string $email
     * @param string $contenido
     * @param string $remitente
     * @param string $nombre
     * @param integer $seccion
     * @return boolean
     */
    public function enviar( $asunto, $email, $contenido, $seccion){
    	$this->correo->AddAddress($email);                  // name is optional
		//$mail->AddReplyTo("sac@clasilistados.org", "clasilistados.org");}
		$this->configurar();
		$this->correo->Subject = $asunto;
		$this->setContenido($contenido);		
		
		if(!$this->correo->Send())
		{
		  	$sql="INSERT INTO ".$this->t_envios." (email,enviado,error,seccion) VALUES ('$email','0','".$this->correo->ErrorInfo."',".$seccion.");";
		  	$result=$this->registro->get("db")->query($sql);
		  	$this->correo->ClearAddresses();
		  	return false;
		}else{
			$sql="INSERT INTO ".$this->t_envios." (email,enviado,error,seccion) VALUES ('$email','1','".$this->correo->ErrorInfo."',".$seccion.");";
		  	$result=$this->registro->get("db")->query($sql);
			$this->correo->ClearAddresses();
		  	return true;
		}
	}
	
	public function validarCompararEmail($email,$emailconf){
		$errores=array();
		if (trim($email)==trim($emailconf)){
			if (!$this->validar($email)){
				$errores[]="El correo electrónico que has escrito es incorrecto.";
			}
		}else{
			$errores[]="El correo electrónico de confirmacion que has escrito no coincide.";
		}
		if(count($errores)>0){
			return $errores;
		}else{
			return true;
		}
		
	}
	
	
}
?>