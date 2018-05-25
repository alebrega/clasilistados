<?php
class Afiliados{
	
	protected $cantidad_anuncios=null; 
	protected $width=336;
	protected $height=280;
	protected $cant_vinculos=0;
	protected $cant_contenido=0;
	const MAXIMO_ANUNCIOS_CONTENIDO=3;
	const MAXIMO_ANUNCIOS_VINCULOS=3;
	protected $registro=null;
	
	public function __construct(Registro $registro){
		$this->registro=$registro;
	}
	public function setMedidas($medidas){
		$medidas=explode("x",$medidas);
		$this->width=$medidas[0];
		$this->height=$medidas[1];
	}

	public function checkCantidadAnuncios()
	{
		if(MAXIMO_ANUNCIOS_CONTENIDO<$this->cant_contenido){
			$this->cant_contenido=MAXIMO_ANUNCIOS_CONTENIDO;
		}
		if(MAXIMO_ANUNCIOS_VINCULOS<$this->cant_vinculos){
			$this->cant_vinculos=MAXIMO_ANUNCIOS_VINCULOS;
		}
	}
	
	
}
?>