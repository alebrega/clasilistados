<?php
$dev_env=false;
if ($dev_env){
	require_once ($_SERVER["DOCUMENT_ROOT"]."/config/dev_bd.php");
	require_once ($_SERVER["DOCUMENT_ROOT"]."/config/dev_memcache.php");
}else{
	require_once ($_SERVER["DOCUMENT_ROOT"]."/config/live_bd.php");
	require_once ($_SERVER["DOCUMENT_ROOT"]."/config/live_memcache.php");
}


$smtp_host='mail.clasilistados.net';
$smtp_auth=true;
$smtp_username='noresponder@clasilistados.net';
$smtp_password='Malena67';

define('TITULO', 'clasilistados');
//comienzo del titulo
define('NOMBRE_SITIO', 'clasilistados');

define('EMAIL_ERROR', 'it@clasilistados.net');
//email para errores

define('LANGUAGE', 'es');
//idioma del sitio

define('MAXIMO_DIAS_CALENDARIO', 28);
//dias del calendario a mostrar

define ('PAIS', 'estados unidos de américa');
define('HOME_PAIS', 'ee.uu.');
//la home por default, sino esta seteada la cookie va al pais.


define('PRECIO_MINIMO', 0);
define('PRECIO_MAXIMO', 99999999999);
define('MONEDA', '$');

define('MALA_PALABRA_REEMPLAZO', '****');

define('DURACION_COOKIE', 31536000);
//24hs = 86400 segundos
//1 año = 31536000 segundos
//la home por default, sino esta seteada la cookie va al pais.

define('MAX_CIUDADES_HOME_ESTADO', 6);
//cantidad maxima de ciudades a mostrar por cada estado
define('MAX_CIUDADES_DESTACADAS', 52);
//cantidad maxima de ciudades destacadas
define('MAX_CIUDADES_ELEGIR_BUSCADAS', 25);
//la cantidad maxima de ciudades buscadas a mostrar en selecc ciudad de cada estado.

define('IMAGENES_ANUNCIO_RECOMENDADO', 2);

define('MAX_INTENTOS_CAPTCHA', 2);

define('CANTIDAD_CARACTERES_MAXIMO_ENLACE_AUTOMATICO', 1200);


//FEEDS
define('FEED_HABILITADO', 1);
define('FEED_INHABILITADO', 0);
define('FEED_ENVIO_HABILITADO', 1);
define('FEED_ENVIO_INHABILITADO', 0);

define('LIMITE_ANUNCIOS_FEED', 50);


define('IMAGEN_CLAVE', 'img');
define('IMAGENES_ANUNCIO', 4);
define('IMAGENES_CALIDAD', 65);
define('IMAGENES_TAMANO', 300);
define('IMAGENES_DIR', '/imagenes/');

//ESTADOS DEL USUARIO
define('USUARIO_SIN_PASSWORD', 1);
define('USUARIO_CON_PASSWORD', 2);
define('USUARIO_EMAIL_INEXISTENTE', 3);
define('USUARIO_HABILITADO', 1);
define('USUARIO_INHABILITADO', 0);

define('FALTA_POCO_ENVIADO',1);
define('FALTA_POCO_NO_ENVIADO',0);

define('LIMITE_URL_SITEMAP',25000);


define('USUARIO_CAMBIO_CORREO', "clasilistados: cambio de tu cuenta de correo electrónico");
define('USUARIO_NUEVA_CUENTA_ASUNTO', "clasilistados: nueva cuenta");

// Table names
$tprefix			= "cl2_";
$t_users			= $tprefix . "users";
$t_sesiones			= $tprefix . "sesiones";

$t_countries		= $tprefix . "countries";
$t_country_state	= $tprefix.  "country_state";
$t_states			= $tprefix . "states";
$t_cities			= $tprefix . "cities";
$t_envios			= $tprefix . "envios";
$t_imagenes			= $tprefix . "imagenes";
$t_faltapoco = $tprefix. "faltapoco";
$t_feeds = $tprefix. "feeds";
$t_feeds_anuncios = $tprefix. "feeds_anuncios";
$t_feeds_tracking = $tprefix. "tracking_feeds";
$t_busquedas 	= $tprefix."busquedas";
$t_traducciones = $tprefix. "traducciones";
$t_mailing_republicar = $tprefix. "mailing_republicar";

$t_cats				= $tprefix . "cats";
$t_subcats			= $tprefix . "subcats";

$t_envia_amigo	= $tprefix . "envia_amigo";

$t_ads_personales	= $tprefix . "ads_personales";
$t_adpics_personales = $tprefix. "adpics_personales";

$t_flags = $tprefix. "flags";
$t_flags_anuncios = $tprefix. "flags_anuncios";
$t_reclamos = $tprefix. "reclamos";
$t_respuestas_anuncios= $tprefix. "respuestas_anuncios";
$t_usuarios_anuncios = $tprefix. "usuarios_anuncios";

$t_anuncios = $tprefix. "anuncios_";

$t_compra_venta = $tprefix. "anuncios_4";
$t_personales = $tprefix. "anuncios_2";
$t_inmuebles = $tprefix. "anuncios_3";
$t_servicios = $tprefix. "anuncios_5";
$t_empleos = $tprefix. "anuncios_6";
$t_trabajo_temporario = $tprefix. "anuncios_7";
$t_curriculum = $tprefix. "anuncios_8"; 
$t_comunidad = $tprefix. "anuncios_9";
$t_eventos = $tprefix. "anuncios_11";
$t_sociales = $tprefix. "anuncios_14";
$t_vehiculos = $tprefix. "anuncios_13";
$t_mascotas = $tprefix. "anuncios_12";
//iba usar ste array para mejorar la performance, por lo q analize no sirve de tanto
//$estados =array("alabama"=>163, "alaska"=>162, "arizona"=>161, "arkansas"=>160, "california"=>159, "colorado"=>158, "connecticut"=>157, "delaware"=>156, "districtofcolumbia"=>155, "florida"=>154, "georgia"=>153, "hawaii"=>152, "idaho"=>151, "illinois"=>150, "indiana"=>149, "iowa"=>148, "kansas"=>147, "kentucky"=>146, "louisiana"=>145, "maine"=>144, "maryland"=>143, "massachusetts"=>142, "michigan"=>141, "minnesota"=>140, "mississippi"=>139, "missouri"=>138, "montana"=>137, "nebraska"=>136, "nevada"=>135, "newhampshire"=>134, "newjersey"=>133, "newmexico"=>132, "newyorkstate"=>131, "northcarolina"=>130, "northdakota"=>129, "ohio"=>128, "oklahoma"=>127, "oregon"=>164, "pennsylvania"=>125, "puertorico"=>169, "rhodeisland"=>124, "southcarolina"=>123, "southdakota"=>122, "tennessee"=>121, "texas"=>120, "utah"=>119, "vermont"=>118, "virginia"=>117, "washington"=>116, "westvirginia"=>165, "wisconsin"=>166, "wyoming"=>113);
//estados con su correspondiente id

$tags_permitidos='<b><br><strong><center><b><u><i><s><big><small><font><p><br><blockquote><hr><h1><h2><h3><h4><h5><h6><ul><ol><li><dl><dd><dt><a><img><pre><table><tr><th><td><span><div>';

//categorias del sitio con su correspondiente id
//"foros de debate"=>10,
$cats=array ("empleos"=>6,"vehículos"=>13,"personales"=>2,"inmuebles"=>3,"compra-venta"=>4,"servicios"=>5,"trabajo temporal"=>7,"currículum"=>8,"comunidad"=>9,"eventos"=>11,"mascotas"=>12,"sociales"=>14);

$nombre_campos_categoria=array(
	2=>array("nombre"=>"edad","campo"=>"edad"),
	
	
);

define ('HOME_PAGINA', "/index.php"); 
define ('SELECCIUDAD_PAGINA', "/selecciudad.php"); 
define ('MASCIUDADES_PAGINA', "/masciudades.php"); 
define ('PUBLICARANUNCIO_PAGINA', "/publicar.php"); 
define ('SITIOS_PAGINA', "/sitios.php"); 
define ('PUBLICACIONES_PAGAS', "/publicacionespagas.php"); 
define ('FORMPOSTING_PAGINA', "/formposting.php"); 
define ('ANUNCIOCONFIRMACIONES', "/anuncio_confirmaciones.php"); 
define ('LISTADOS', "/listados.php");
define ('ADMIN_ANUNCIO', "/admin_anuncio.php");
define ('ANUNCIO_PAGINA', "/item.php");
define ('FLAG', "/flag.php");
define ('ENVIAAMIGO', "/envia_amigo.php");
define ('CONTACTANOS', "/contactanos.php");
define ('DONTSPEAKSPANISH', "/dontspeak/index.php");
define ('LOMEJOR', "/lomejor.php");
define ('MICUENTA', "/micuenta.php");
define ('CREARCUENTA', "/crearcuenta.php");
define ('REINICIARCONTRASENA', "/reiniciar_contrasena.php");
define ('ASOCIARSE', "/asociarse.php");
define ('ESTAMOS_CONTRATANDO', "/estamos-contratando.php");
define ('PRIVACIDAD', "/privacidad.php");
define ('SEGURIDAD', "/seguridad.php");
define ('PROHIBIDO_ANUNCIAR', "/prohibido_anunciar.php");
define ('URGDEST', "/urgdest.php");
define ('CONDICIONES','/condiciones.php');
define ('PAGANDOCONTARJETA','/pagandocontarjeta.php');
define ('NUMEROSDEVERIFICACION','/numerosdeverificacion.php');
define ('FUENTES','/fuentes.php');
define ('RSS','/rss.php');
define ('CODIGOPROMOCION','/promotioncode.php');


define ('COLOR_ADMIN_ACTIVO','#66FF66');
define ('COLOR_ADMIN_PENDIENTE','yellow');
define ('COLOR_ADMIN_BORRADO_POR_MI','red');
define ('COLOR_ADMIN_VENCIDO','#FA8072');
define ('COLOR_ADMIN_BORRADO_MARCADO','violet');



//emailing republicacion
define(MAXIMO_MAILING_REPUBLICACION,4);

//lo mejor de clasilistados
define(LO_MEJOR_LIMITE_PAGINA,20);

//listados
define (LIMITE_CANTIDAD_PAGINAS,10);
define (LIMITE_POR_PAGINA,100);
define (LISTADOS_CACHE_SEGUNDOS_EXPIRE,900); //15 MINUTOS

//sphinx
define (SPHINX_MAX_MATCHES,250000); 
define (SPHINX_MAX_RESULTADOS,100000); 

//flags
define(FLAG_LOMEJOR,4);
define(FLAG_OFENSIVO,3);
define(FLAG_PROHIBIDO,2);
define(FLAG_MALCATEGORIZADO,1);

define(MAX_FLAGS_PROHIBIDO_OFENSIVO,10);
define(MAX_FLAGS_MARCADO,4);

define('USUARIO_ANUNCIO_BORRADO', 0);
define('USUARIO_ANUNCIO_ACTIVO', 1);
define('USUARIO_ANUNCIO_PENDIENTE', 2);
define('USUARIO_ANUNCIO_MARCADO', 3);
define('USUARIO_ANUNCIO_VENCIDO', 4);

define('USUARIO_ANUNCIO_GRATIS', 'gratis');
define('USUARIO_ANUNCIO_SUSCRIP', 'suscrip.');

define('CANT_DIAS_MENOS_LISTADO_USUARIOS', 30);

define('CANT_DIAS_ANUNCIO_ACTIVO', 30);

//anuncio
define(ANUNCIO_INHABILITADO,0);
define(ANUNCIO_HABILITADO,1);
/*
//precio urgente y destacado
define(PRECIO_DESTACADO,6.99);
define(PRECIO_URGENTE,2.99);
*/
define ('ANUNCIO_DESTACADO',1);
define ('ANUNCIO_URGENTE',1);

//como nos conocio

define(NOSCONOCIO_BUSTOS,1);
define(NOSCONOCIO_PUBLICIDAD,2);
define(NOSCONOCIO_BUSCADOR,3);
define(NOSCONOCIO_RECOMENDACION,4);
define(NOSCONOCIO_OTRO,5);

//secciones

define(PUBLICAR_ANUNCIO,1);
define(ERROR_PUBLICAR_ANUNCIO,4);
define(RECOMENDAR_AMIGO_ANUNCIO,2);
define(RESPONDER_ANUNCIO,3);
define(RECLAMOS,5);
define(DONTSPEAKSPANISH,6);
define(USUARIO_ACTIVACION,7);
define(USUARIO_REINICIO_CONTRASENA,8);
define(USUARIO_CAMBIO_CORREO_ACTIVACION,9);
define(ANUNCIO_MARCADO_BORRADO,10);
define(CUENTAS_AUTOMATICAS,11);
define(CONTACTANOS_FAQ,12);
define(PAGO_CONFIRMACION,13);
define(REPUBLICACIONES,14);
define(COMISION_VENDEDOR,15);
/*
$configData=array(

"publicacion"=>array(

"unidad"=>array(
6=>array("precio"=>50,"subcategoria"=>array(0),"cityid"=>array(0),"stateid"=>array(0)),
13=>array("precio"=>50,"subcategoria"=>array(0),"cityid"=>array(0),"stateid"=>array(0)),
14=>array("precio"=>10,"subcategoria"=>array(293),"cityid"=>array(0),"stateid"=>array(0))),
"suscripcion"=>array(
6=>array("precio"=>150,"subcategoria"=>array(0),"cityid"=>array(0),"stateid"=>array(0)),
13=>array("precio"=>150,"subcategoria"=>array(0),"cityid"=>array(0),"stateid"=>array(0)))
),

"destacados"=>array(

"negrita"=>array(
6=>array("precio"=>15,"cityid"=>array(0),"stateid"=>array(0)),
4=>array("precio"=>15,"cityid"=>array(0),"stateid"=>array(0))),
"fondo"=>array(
6=>array("precio"=>25,"cityid"=>array(0),"stateid"=>array(0)),
4=>array("precio"=>25,"cityid"=>array(45066),"stateid"=>array(0)))

)

);
*/
$tarjetas_aceptadas=array("Visa", "MasterCard", "Discover","Amex");

if(!defined('CONFIG_LOADED'))
{
	// Constant to indicate if the config has been loaded
	define('CONFIG_LOADED', TRUE);
	require_once ($_SERVER["DOCUMENT_ROOT"]."/lib/class.db.php");
	require_once ($_SERVER["DOCUMENT_ROOT"]."/classes/Crypt.php");
	
	$db = db::getInstance();
 	$db->conectarse($db_host, $db_user, $db_pass, $db_name);
 	
}
	/*
	Funciones para el uso general de la aplicacion
	*/
	
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/functions.inc.php");
?>
