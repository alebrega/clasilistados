<?php

require 'lphp.php';

class CCPayment {
	
	private $mylphp=null;
	private $myorder=array();
	private $errores=array();
	private $transaccion_id=null;
	
	public function __construct(){
		
		$this->mylphp=new lphp;
		# constants
		$this->myorder["host"]       = "secure.linkpt.net";
		$this->myorder["port"]       = "1129";
		$this->myorder["keyfile"]    = $_SERVER["DOCUMENT_ROOT"].'/firstdataapi/1001213417.pem'; # Change this to the name and location of your certificate file 
		$this->myorder["configfile"] = "1001213417";        # Change this to your store number 
	}
	public function getErrores(){
		return $this->errores;
	}
	public function validarDatos($data){
		if (empty($data['cardnumber'])){
			$this->errores[]="Introduzca el número de su tarjeta de crédito/débito.";
			return false;	
		}
		if (empty($data['cvmvalue'])){
			$this->errores[]="Introduzca el número de verificación de la tarjeta.";
			return false;
		}
		if ( (empty($data['cardexpmonth']) || ($data['cardexpmonth']=='Mes')) || ( empty($data['cardexpyear']) || ($data['cardexpyear']=='Ano')) ){
			$this->errores[]="La fecha de expiración de su tarjeta de crédito no es correcta.";	
			return false;
		}
		if (empty($data['name'])){
			$this->errores[]="Introduzca el nombre del titular de la tarjeta.";
			return false;
		}
		if (empty($data['address1'])){
			$this->errores[]="Introduzca la dirección de facturación de su tarjeta.";
			return false;
		}
		if (empty($data['city'])){
			$this->errores[]="Introduzca la ciudad de la dirección de facturación de su tarjeta.";
			return false;
		}
		if (empty($data['state'])){
			$this->errores[]="Introduzca el estado de la dirección de facturación de su tarjeta.";
			return false;
		}
		if (empty($data['zip'])){
			$this->errores[]="Introduzca el Zip/Código postal de la dirección de facturación de su tarjeta.";
			return false;
		}
		if (empty($data['country'])){
			$this->errores[]="Introduzca el país de la dirección de facturación de su tarjeta.";
			return false;
		}
		return true;
	}
	public function process($data){
		# form data
		
		$this->myorder["cardnumber"]    = $data["cardnumber"];
		$this->myorder["cardexpmonth"]  = $data["cardexpmonth"];
		$this->myorder["cardexpyear"]   = $data["cardexpyear"];
		$this->myorder["chargetotal"]   = $data["chargetotal"];
		$this->myorder["ordertype"]     = $data["ordertype"];
		$this->myorder["ip"]     = $data["ip"];
		$this->myorder["name"]     = $data["name"];
		$this->myorder["address1"]     = $data["address1"];
		$this->myorder["city"]     = $data["city"];
		$this->myorder["state"]     = $data["state"];
		$this->myorder["country"]     = $data["country"];
		$this->myorder["zip"]     = $data["zip"];
		$this->myorder["phone"]    = $data["telefono"];
		//$this->myorder["email"]    = $data["email_cc"]; asi no manda email
		$error=array();
		
	  	# Send transaction. Use one of two possible methods  #
		//	$result = $mylphp->process($myorder);       # use shared library model
		$result = $this->mylphp->curl_process($this->myorder);  # use curl methods
		
		if ($result["r_approved"] != "APPROVED")    // transaction failed, print the reason
		{
			return $result[r_error];
		}
		else	// success
		{		
			$this->transaccion_id=$result[r_code];
			return true;
			//print "Status: $result[r_approved]<br>\n";
			//print "Transaction Code: $result[r_code]<br><br>\n";
		}
	}
	public function getTransaccionId(){
		return $this->transaccion_id;
	}
}