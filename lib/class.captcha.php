<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/classes/recaptchalib.php");

class Captcha{

	const publickey="6LeNqAQAAAAAAAuDpgfx_37KTM-7XBSxGgkcgFra";
	const privatekey="6LeNqAQAAAAAABW-kGR2HbshBNhIx9tmyYNgnx3g";
	
	public function esValido(){
		$resp = recaptcha_check_answer (self::privatekey,
		                                $_SERVER["REMOTE_ADDR"],
		                                $_POST["recaptcha_challenge_field"],
		                                $_POST["recaptcha_response_field"]);
		if (!$resp->is_valid) {
		 return false;
		 
		}else{
			return true;
		}
				
	}
	public function imprimir($seguro=false){
		return recaptcha_get_html(self::publickey,null,$seguro);
	}
}
?>