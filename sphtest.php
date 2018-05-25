<?php
  include('classes/sphinxapi.php');

  $cl = new SphinxClient();
  $cl->SetServer( "10.0.0.197", 3312 );
  $cl->SetMatchMode( SPH_MATCH_ANY  );
//  $cl->SetFilter( 'model', array( 3 ) );

  $result = $cl->Query( 'campo', 'anuncios_4' );

  if ( $result === false ) {
      echo "Query failed: " . $cl->GetLastError() . ".\n";
  }
  else {
      if ( $cl->GetLastWarning() ) {
          echo "WARNING: " . $cl->GetLastWarning() . "
";
      }

      if ( ! empty($result["matches"]) ) {
          foreach ( $result["matches"] as $doc => $docinfo ) {
                echo "$doc\n";
          }
          
          print_r( $result );
      }
  }

  exit;
?>

