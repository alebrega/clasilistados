<?php
class Cookie{
	private $separator = '--';
	private $uniqueID = 'Ju?hG&F0yh9?=ale/6*GVfd-d8u6f86hp';
	private $data;
	private $timeExpire=DURACION_COOKIE; //un año
	private $path="/";
	private $dominio=null;
	
	public function __construct(){
		$this->dominio=".".$_SERVER['SERVER_NAME'];
		$this->dominio=str_replace("www.","",$this->dominio);
	}
	public function set($key,$value){
		$this->delete($key);
		$enCryptvalue=$value.$this->separator.md5($value.$this->uniqueID);
		@setcookie($key, $enCryptvalue, time() + $this->timeExpire,$this->path,$this->dominio);
	}
	public function get($key){
		if (isset($_COOKIE)){
			$cut = explode($this->separator, $_COOKIE[$key]);
		   	if (md5($cut[0].$this->uniqueID) === $cut[1]) {
		   		return $cut[0];
		   	} else {
		    	return null;
		   	}
		}
		else{
			return null;
		}
	}
	public function delete ($key){
		@setcookie ($key, null, time() - 3600, $this->path, $this->dominio); // cambia la hora de la expiracion una hora atras.
		//unset($_COOKIE[$key]); no funciona
	}
	
	
}
?>