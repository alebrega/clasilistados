<?php
function parse( $szFilename ) {
    //assert( file_exists( $szFilename ) );
    $oParser = xml_parser_create("ISO-8859-1");
    $szData = implode( "",file($szFilename) );
    xml_parse_into_struct( $oParser, $szData, $aValues );
    foreach( $aValues as $aElement ) {
      if( $aElement["tag"] === "OUTLINE" ) {
        $szGUID = md5($aElement["attributes"]["XMLURL"]);
        $that->m_aData[$szGUID] = $aElement["attributes"];
      }
    }
    xml_parser_free( $oParser );
	$feeds_array= $that->m_aData;
		
    return $feeds_array;
} 

?>