<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/lib/interfaces/pagable.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/lib/mediosdepago/class.paypal.php');

class Publicacion implements Pagable{
	
	private $registro=null;
	private $errores=array();
	private $transaccion_id=null;
	private $pago_id=null;
	private $t_pagos='cl2_pagos_publicaciones';
	private $t_codigo_descuento='cl2_promo_descuento';
	private $emailPromo=null;
	private $descuento=0;
	
	public function __construct(Registro $registro){
		$this->registro=$registro;
	}
	public function esCategoriaPaga($catid,$tipo='unidad'){
		$config=$this->registro->get('configuracion');
		$info=$config->get('publicacion',$tipo);
		if(!empty($info[$catid])){
			if ($this->esSubCategoriaPaga($catid,0,$tipo)){
				return true;
			}
		}
		return false;
	}
	public function esSubCategoriaPaga($catid,$subcatid,$tipo='unidad'){
		$config=$this->registro->get('configuracion');
		$info=$config->get('publicacion',$tipo);
		if (!empty($info[$catid]["subcategoria"])){
			foreach ($info[$catid]["subcategoria"] as $subcategoriaId){
				if ($subcategoriaId==$subcatid){
					return true;
				}
			}
		}
		
		return false;
	}
	public function deboCobrarEstaUbicacion($catid,$subcatid=null,$tipo='unidad'){
		$locacion=$this->registro->get('locacion');
		$config=$this->registro->get('configuracion')->get('publicacion',$tipo);				
		if (!empty($config[$catid])){
			foreach ($config[$catid]['cityid'] as $ubicacion){
				if ($ubicacion==$locacion->getCiudadId()){
					return true;
				}
				if ($ubicacion==0){
					return true;
				}
			}
			foreach ($config[$catid]['stateid'] as $ubicacion){
				if ($ubicacion==$locacion->getEstadoId()){
					return true;
				}
				if ($ubicacion==0){
					return true;
				}
			}
		}
		return false;
		
	}
	public function getErrores(){
		return $this->errores;
	}
	public function getDescuentoCodigoPromocion($data){
		if ($this->descuento==0){
			$q="SELECT email,descuento FROM $this->t_codigo_descuento WHERE promocod=?";
			if ($stmt = db::getInstance()->prepare($q)) {
			    $stmt->bind_param("s", $data['codigo_prom']);
			    $stmt->execute();
			    $stmt->bind_result($email,$descuento);
			   	$stmt->fetch();
			    $stmt->close();
			    $this->emailPromo=$email;
			    $this->descuento=$descuento;
			}else{
				return false;
			}
		}
		return $this->descuento;
	}
	public function validar ($data,Paypal $ccpayment){
		if (!$ccpayment->validarDatos($data)){
			$this->errores=$ccpayment->getErrores();
			return false;
		}
		if (empty($data['nombre'])){
			$this->errores[]="Introduzca el nombre de quien deberíamos contactar si tenemos preguntas sobre tu anuncio.";
			return false;
		}
		if (empty($data['telefono'])){
			$this->errores[]="Introduzca el telefono de quien deberíamos contactar si tenemos preguntas sobre tu anuncio.";
			return false;
		}
		/*if ( (empty($data['nosconocio'])) || ($data['nosconocio']=='0') ){
			$this->errores[]="Debe seleccionar como nos conoció.";
			return false;
		}*/
		$email=$this->registro->get('email');
		if (empty($data['email_cc']) || (!$email->validar($data['email_cc']))){
			$this->errores[]="Introduzca un correo electrónico válido de quien deberíamos contactar si tenemos preguntas sobre tu anuncio.";
			return false;
		}
		return true;
	}
	public function pagar($data){
		$CCPayment = new Paypal();
		if (!$this->validar($data,$CCPayment)){
			return false;
		}
		if (!empty($data['codigo_prom'])){
			if (strlen($data['codigo_prom'])!=6){
				$this->errores[]="Introduzca un código de promoción valido.";
				return false;
			}
			$this->getDescuentoCodigoPromocion($data);
			if(!empty($this->descuento) && is_numeric($this->descuento)){
				$valor=$data['chargetotal'];
				$data['chargetotal']=$data['chargetotal'] - (($data['chargetotal']*$this->descuento)/100);
				$data['chargetotal']=round($data['chargetotal'],2);
				if($data['chargetotal']<=0){
					$data['chargetotal']=$valor;
				}
				$_POST['chargetotal']=$data['chargetotal'];
			}
		}
		//$result=true;
		$result=$CCPayment->process($data);
		if ($result===true && $result==true){
			$this->transaccion_id=$CCPayment->getTransaccionId();
			$this->enviarMailComision($data);
		}
		return $result;
	}
	public function enviarMailComision($data){
		$asunto="Tienes una comisión por cobrar";
	    $contenido=file_get_contents($_SERVER['DOCUMENT_ROOT']."/plantillas/email_vendedor_comision.html");
		$contenido=str_replace("<<VENDEDOR_EMAIL>>",$this->emailPromo,$contenido);
		$contenido=str_replace("<<NOMBRE>>",$data['nombre'],$contenido);
		$contenido=str_replace("<<CODIGO>>",strtoupper($data['codigo_prom']),$contenido);
		$contenido=str_replace("<<MONTO>>",formatMoney($data['chargetotal'],2),$contenido);
		$contenido=str_replace("<<FECHA>>",date('m/d/Y'),$contenido);
		$contenido=str_replace("<<TELEFONO>>",$data['telefono'],$contenido);
		$contenido=str_replace("<<COMISION>>",formatMoney((($data['chargetotal']*40)/100),2),$contenido);
		$contenido=str_replace("<<EMAIL>>",$data['email_cc'],$contenido);
		
		$contenido=$this->registro->get("helper")->caracteres_html($contenido);
		$this->registro->get("email")->enviar($asunto,$this->emailPromo,$contenido,COMISION_VENDEDOR);
	}
	public function registrarPago($data,$adid){
		$q="INSERT INTO ".$this->t_pagos." (importe,nombre,telefono,correo,adid,catid,promocod,nosconocio) VALUES (?,?,?,?,?,?,?,?)";
		if ($stmt = db::getInstance()->prepare($q)) {
			$precio=$this->getPrecio($this->registro->get('categoria')->getCategoriaId());
		   	$stmt->bind_param("isssiisi", $precio, $data['nombre'],$data['telefono'],$data['email_cc'],$adid,$this->registro->get('categoria')->getCategoriaId(),strtoupper($data['codigo_prom']),$data['nosconocio']);
		   	$stmt->execute();
		   	$stmt->close();
		   	return true;
		}else{
			return false;
		}
	}
	public function getTransaccionId(){
		return $this->transaccion_id;
	}
	
	public function getPrecio($catid,$tipo='unidad'){
		$config=$this->registro->get('configuracion');
		$info=$config->get('publicacion',$tipo);
		$precio=$info[$catid]["precio"];
		if (!empty($precio)){
			return $precio;
		}
		return false;
		
	}
	public function getTotalPrecio($subcategorias){
		$total=0;
		if (is_array($subcategorias)) {
			foreach ($subcategorias as $subcategoriaElegida){
				$precio_unidad=$this->getPrecio($subcategoriaElegida['catid']);
				$total=$total+$precio_unidad;
			}
		}
		return $total;
	}
	
}