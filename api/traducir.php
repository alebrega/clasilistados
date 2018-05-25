<?php
//require_once("nusoap/lib/nusoap.php");
$servicio="http://clasilistados.org/api/webservice.php";
   // Utilizar el uri
        $client = new SoapClient(null,
                array(
                        'location' => $servicio,
                        'uri' => 'urn:webservices',
                ));
 
        // Llamar el metodo como si fuera del cliente
         $client->traducir();
        var_dump( $client->getErrores());


?> 