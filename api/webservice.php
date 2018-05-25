<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
class clasilistados
{
	private $anuncio=null;
	public function __construct(Anuncio $anuncio){
		$this->anuncio=$anuncio;
	}
	public function validarAnuncio(){
		$this->anuncio->validarPublicacion();
	}
	public function getErrores(){
		$this->anuncio->getErrores();
	}
}
$anuncio=new Anuncio();
$clasilistados=new clasilistados($anuncio);
$clasilistados->validarAnuncio();

$server = new SoapServer(
              null, // No utilizar WSDL
                array('uri' => 'urn:webservices') // Se debe especificar el URI
        ); 
$server->setObject($clasilistados);
// Atender los llamados al webservice
$server->handle();
?>