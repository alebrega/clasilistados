<h2><b><a target="_top" href="/">clasilistados</a></b> &gt;
<?php
switch ($_SERVER["PHP_SELF"]){
	case URGDEST:
		echo "Mejorar la visibilidad de su anuncio";
		break;
	case PROHIBIDO_ANUNCIAR:
		echo "objetos prohibidos y restringidos";
		break;
	case SEGURIDAD:
		echo "consejos de seguridad";
		break;
	case ASOCIARSE:
	case FUENTES:
		echo "como asociarse con clasilistados";
		break;
	case CONTACTANOS:
		echo "contáctanos";
		break;
	case LOMEJOR:
		echo "lo mejor de clasilistados";
		break;
	case DONTSPEAKSPANISH:
		echo "don't speak spanish?";
		break;
	case ESTAMOS_CONTRATANDO:
		echo "estamos contratando";
		break;
	case PRIVACIDAD:
		echo "politica de privacidad";
		break;
	case CONDICIONES:
		echo "términos y condiciones de uso";
		break;
	case PAGANDOCONTARJETA:
		echo "pagando mediante tarjeta de crédito";
		break;
	case NUMEROSDEVERIFICACION:
		echo "numeros de verificación de tarjeta de crédito";
		break;
	case CODIGOPROMOCION:
		echo "código de promoción de ventas";
		break;
	default:
		break;
			
}
?>
</h2>