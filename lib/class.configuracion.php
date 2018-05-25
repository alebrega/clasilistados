<?php
class Configuracion {
	private $configData=array();
	
	public function get($objeto,$key)
	{
		return $this->configData[$objeto][$key];
	}
	public function set($objeto,$key,$valor)
	{
		$this->configData[$objeto][$key]=$valor;
	}
	public function llenar($configData){
		$this->configData=$configData;
	}
}