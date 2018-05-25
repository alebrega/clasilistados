<?php

class Suscripcion{
	
	private $diasValidez=365;
	private $registro=null;
	private $t_suscripciones='cl2_suscripciones';
	private $t_suscripciones_pagos='cl2_pagos_suscripciones';
	private $usuarioTieneSuscripcion=false;
	private $suscripciones=array();
	private $mensajes=array();
	private $esSuscripcionUnica=null;
	private $vencioSuscrip=null;
	
	public function __construct(Registro $registro){
		$this->registro=$registro;
		$this->cargarSuscripciones($registro->get('usuario')->getUsuarioId());
	}
	public function getSuscripciones(){
		return $this->suscripciones;
	}
	public function setdiasValidez($diasValidez){
		$this->diasValidez=$diasValidez;
	}
	public function getdiasValidez(){
		return $this->diasValidez;
	}
	private function generarTimestampVenc(){
		return mktime(date("H"), date("i"),date("s"),date("n"),date("j")+$this->diasValidez,date("Y"));
	}
	public function esSuscripcionUnicaValida($catid,$usuario_id){
		if (is_null($this->esSuscripcionUnica)){
			if(empty($this->suscripciones[$catid])){
				$this->cargarSuscripciones($usuario_id);
			}
			if (!$this->vencioSuscrip($this->suscripciones[$catid]['vencimiento'])){
	    		$this->esSuscripcionUnica=false;
	    	}else{
	    		$this->esSuscripcionUnica=true;
	    	}
		}
		return $this->esSuscripcionUnica;
	}
	private function getKeyVencimientoSesion(){
		return $this->registro->get("crypt")->encrypt('vencimiento');
	}
	public function nueva($catid,$usuario_id){
		$this->registro->get("session")->remove($this->getKeyVencimientoSesion());
		if (!$this->esSuscripcionUnicaValida($catid,$usuario_id)){
			return false;
		}
		$q="INSERT INTO ".$this->t_suscripciones." (catid,usuario_id,vencimiento) VALUES (?,?,?)";
		if ($stmt = db::getInstance()->prepare($q)) {
			$vencimiento=$this->generarTimestampVenc();
		   	$stmt->bind_param("iii", $catid,$usuario_id,$vencimiento);
		   	$stmt->execute();
		   	$stmt->close();
		   	$this->suscripciones[$catid]=array('id'=>db::getInstance()->insert_id(),'vencimiento'=>$vencimiento);
		   	return true;
		}else{
			return false;
		}
	}
	public function getVencimiento(){
		$categoria=$this->registro->get('categoria');
		return $this->suscripciones[$categoria->getCategoriaId()]['vencimiento'];
	}
	private function guardarVencimientoSesion($vencimiento){
		//para que solo valide el vencimiento la primeza vez. Valido por 6 horas la sesion
		if (!$this->vencioSuscrip($vencimiento)){
			$this->registro->get("session")->set($this->getKeyVencimientoSesion(),$vencimiento);
		}
	}
	private function cargarSuscripciones($usuario_id){
		$categoria=$this->registro->get('categoria');
		if (empty($this->suscripciones[$categoria->getCategoriaId()]['id'])){
			if ($stmt = db::getInstance()->prepare("SELECT suscripcion_id,catid,vencimiento FROM ".$this->t_suscripciones." WHERE usuario_id=?")) {
			    $stmt->bind_param("i", $usuario_id);
			    $stmt->execute();
			    $stmt->bind_result($id,$categoriaId,$vencimiento);
			    while ($stmt->fetch()){
			    	$this->suscripciones[$categoriaId]=array("id"=>$id,"vencimiento"=>$vencimiento);
			    }
			    $stmt->close();
			    if (!empty($this->suscripciones[$categoria->getCategoriaId()]['id'])){
			    	$this->guardarVencimientoSesion($vencimiento);
			    	return true;
			    }
			    return false;
			}else{
				return false;
			}
		}else{
			return true;
		}
	}
	private function esCategoriaValidaSuscrip($categoria_id){
		if (!empty($this->suscripciones[$categoria_id])){
			return true;
		}else{
			return false;
		}
	}
	private function vencioSuscrip($vencimiento){
		$vencimiento_sesion=$this->registro->get("session")->get($this->getKeyVencimientoSesion());
		$ahora=time();
		if (!empty($vencimiento_sesion) && ($vencimiento_sesion<$vencimiento)){
			$vencimiento=$vencimiento_sesion;
		}
		if (is_null($this->vencioSuscrip)){
			if ($ahora<$vencimiento){
				$this->vencioSuscrip=false;
			}else{
				$this->vencioSuscrip=true;
			}	
		}
		return $this->vencioSuscrip;
	}
	public function usuarioTieneSuscripcion(){
		$usuario=$this->registro->get('usuario');
		$categoria=$this->registro->get('categoria');
		if (!$this->usuarioTieneSuscripcion){
			if (!$usuario->estaLogueado()){
				$this->usuarioTieneSuscripcion=false;
				return false;
			}
			if (!$this->cargarSuscripciones($usuario->getUsuarioId())){
				$this->usuarioTieneSuscripcion=false;
				return false;
			}
			if (!$this->esCategoriaValidaSuscrip($categoria->getCategoriaId())){
				$this->usuarioTieneSuscripcion=false;
				return false;
			}
			if ($this->vencioSuscrip($this->suscripciones[$categoria->getCategoriaId()]['vencimiento'])){
				$this->usuarioTieneSuscripcion=false;
				return false;
			}
			$this->usuarioTieneSuscripcion=true;
			return true;
		}else{
			return $this->usuarioTieneSuscripcion;
		}
	}
	public function registrarPago($data){
		$q="INSERT INTO $this->t_suscripciones_pagos (importe,nombre,telefono,correo,suscripcion_id,promocod,nosconocio) VALUES (?,?,?,?,?,?,?)";
		if ($stmt = db::getInstance()->prepare($q)) {
			$id=$this->suscripciones[$this->registro->get('categoria')->getCategoriaId()]['id'];
		   	$stmt->bind_param("isssisi", $data['chargetotal'], $data['nombre'],$data['telefono'],$data['email_cc'],$id,strtoupper($data['codigo_prom']),$data['nosconocio']);
		   	$stmt->execute();
		   	$stmt->close();
		   	return true;
		}else{
			return false;
		}
	}

}