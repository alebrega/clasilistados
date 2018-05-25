<?php
class Adsense {
	protected $cantidad_anuncios=null; 
	protected $width=336;
	protected $height=280;
	protected $cant_vinculos=0;
	protected $cant_contenido=0;
	const MAXIMO_ANUNCIOS_CONTENIDO=3;
	const MAXIMO_ANUNCIOS_VINCULOS=3;
	protected $registro=null;
	
	private function deboMostrarme(){
		global $registro;
		if ($registro->get('categoria')->esPersonales()){
			return false;
		}else{
			return true;
		}
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
	public function getHTML($key){	
		$adsense='';
		if ($this->deboMostrarme()){
			/*require_once($_SERVER["DOCUMENT_ROOT"]."/includes/adsense/".$key."_".$this->width."_".$this->height.".inc.php");
			$adsense='<script type="text/javascript"><!--
				google_ad_client = "pub-3302531499919903";
				google_ad_slot = "'.$google_ad_slot.'";
				google_ad_width = '.$this->width.';
				google_ad_height = '.$this->height.';
				//-->
				</script>
				<script type="text/javascript"
				src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
				</script>';*/
                                switch ($key){
                                    
                                    case "arriba":
                                        $adsense="<!-- clasi_728x90_listados_arriba -->
                                        <div id='div-gpt-ad-1388086537590-2' style='width:728px; height:90px;'>
                                        <script type='text/javascript'>
                                        googletag.cmd.push(function() { googletag.display('div-gpt-ad-1388086537590-2'); });
                                        </script>
                                        </div>";
                                    break;
                                    case "abajo":
                                            $adsense="<!-- clasi_728x90_listados_abajo -->
                                            <div id='div-gpt-ad-1388086537590-1' style='width:728px; height:90px;'>
                                            <script type='text/javascript'>
                                            googletag.cmd.push(function() { googletag.display('div-gpt-ad-1388086537590-1'); });
                                            </script>
                                            </div>";
                                    break;
                                    default:
                                            $adsense="<!-- clasi_728x90_anuncios -->
                                                      <div id='div-gpt-ad-1388086537590-0' style='width:728px; height:90px;'>
                                                        <script type='text/javascript'>
                                                        googletag.cmd.push(function() { googletag.display('div-gpt-ad-1388086537590-0'); });
                                                        </script>
                                                        </div>";
                                    break;
                                            
                                }
                }
		return $adsense;		
	}
	
}
