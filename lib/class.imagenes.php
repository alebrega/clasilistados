<?php
class Imagenes
{
	// Variables
    private $img_input;
    private $img_output;
    private $img_src;
    private $format;
    private $quality = IMAGENES_CALIDAD;
    private $x_input;
    private $y_input;
    private $x_output;
    private $y_output;
    private $resize;
    private $ext;
    private $imagenes=array();
    
    private $registro=null;
    private $key=IMAGEN_CLAVE;
    private $maximo=IMAGENES_ANUNCIO; 
    private $tamano=IMAGENES_TAMANO;
    private $directorio=IMAGENES_DIR;
    private $t_imagenes=null;
	
	public function __construct(Registro $registro){
		global $t_imagenes;
		if (is_null($this->registro)){
			$this->registro=$registro;
		}
		$this->t_imagenes=$t_imagenes;
	}
	public function setKey($key){
		$this->key=$key;
	}
	public function setMaximo($maximo){
		$this->maximo=$maximo;
	}
	public function setTamano($tamano){
		$this->tamano=$tamano;
	}
	public function setDirectorio($dir){
		$this->directorio=$dir;
	}
	public function validarMaximo($cantidad){
		if ($cantidad>$this->maximo){
			return $this->maximo;
		}else{
			return $cantidad;
		}
	}
	public function existeUrlImagen($url){
		$url=@getimagesize($url);
		if(!is_array($url))
		{
			return false;
		
		}else{
			return true;
		}
	}
	public function getAll(){
		return $this->imagenes;
	}
	public function tengo(){
		if (count($this->imagenes)>0){
			return true;
		}else{
			return false;
		}
	}
	public function estaSeteada($num){
		if (strlen($this->imagenes[$num])>0){
			return true;
		}else {
			return false;
		}
	}
	public function set($key,$value){
		$this->imagenes[$key]=$value;
	}
	public function get($key){
		return $this->imagenes[$key];
	}
	public function subir($cantidad){
		$imagenes=array();
		$cantidad=$this->validarMaximo($cantidad)+1; //empieza en key1
		for ($i=1;$i<$cantidad;$i++){
			if (strlen($_FILES[$this->key.$i]["name"])==0){
				if (strlen($_POST[$this->key.$i])>0){
					$imagenes[$i]=$_POST[$this->key.$i];
				}
				continue;
			}
			$src = $_FILES[$this->key.$i];
			$this->set_img($src);
			$this->set_size($this->tamano);
			$nombreImagen=$this->resolvNombre($_FILES[$this->key.$i]['name']);
			$ubicacionImagen=$_SERVER['DOCUMENT_ROOT'].$this->directorio.$nombreImagen;
			$this->save_img($ubicacionImagen);
			$this->clear_cache();
			$imagenes[$i]=$this->registro->get("helper")->getHost().$this->directorio.$nombreImagen.$this->getExt();
		}
		$this->imagenes=$imagenes;
	}
	public function resolvNombre($nombreImagen){
		return md5(rand().time().$nombreImagen);
	}
    public function getExt(){
    	return $this->ext;
    }
    public function cargar($id,$catid){
		$q="SELECT img1,img2,img3,img4 FROM ".$this->t_imagenes." WHERE catid=".$catid." AND adid=".$id.";";
		$result=db::getInstance()->query($q);
		$row=$result->fetch_array();
		if ($result->num_rows!=0){
			if (strlen($row['img1'])>0){
				$this->imagenes[1]=$row['img1'];	
			}
			if (strlen($row['img2'])>0){
				$this->imagenes[2]=$row['img2'];	
			}
			if (strlen($row['img3'])>0){
				$this->imagenes[3]=$row['img3'];	
			}
			if (strlen($row['img4'])>0){
				$this->imagenes[4]=$row['img4'];	
			}
			return true;
		}else{
			return false;
		}
	}
	public function insertarmeEnElAnuncio($id){
		$imagen1=(isset($this->imagenes[1]) ? $this->imagenes[1] : '');
		$imagen2=(isset($this->imagenes[2]) ? $this->imagenes[2] : '');
		$imagen3=(isset($this->imagenes[3]) ? $this->imagenes[3] : '');
		$imagen4=(isset($this->imagenes[4]) ? $this->imagenes[4] : '');
		if ((strlen($imagen1)>0) || (strlen($imagen2)>0) || (strlen($imagen3)>0) || (strlen($imagen4)>0)){
			$sql="INSERT INTO ".$this->t_imagenes." (img1,img2,img3,img4,adid,catid) VALUES ('$imagen1','$imagen2','$imagen3','$imagen4',$id,".$this->registro->get("categoria")->getCategoriaId().");";
			$result=$this->registro->get("db")->query($sql);
			$imgid=db::getInstance()->insert_id();
			if($result){
				$this->registro->get("anuncio")->actualizarImagen($id,$this->registro->get("categoria")->getCategoriaId(),$imgid);
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
		
	}
	public function actualizarmeEnElAnuncio($id,$catid){
		$imagen1=(isset($this->imagenes[1]) ? $this->imagenes[1] : '');
		$imagen2=(isset($this->imagenes[2]) ? $this->imagenes[2] : '');
		$imagen3=(isset($this->imagenes[3]) ? $this->imagenes[3] : '');
		$imagen4=(isset($this->imagenes[4]) ? $this->imagenes[4] : '');
		$sql="UPDATE ".$this->t_imagenes." SET img1='$imagen1',img2='$imagen2',img3='$imagen3',img4='$imagen4' WHERE adid=$id AND catid=$catid ; ";
		$result=$this->registro->get("db")->query($sql);
		if($result){
			return true;
		}else{
			return false;
		}
	}
	public function validarX($width){
		if ($width>IMAGENES_TAMANO){
			return IMAGENES_TAMANO;
		}else{
			return $width;
		}
	}
	public function getHTML($cantidad){
		$html='';
		if ($this->tengo()){
			$html.='<table summary=""><tbody>';
			$mitad=ceil ($cantidad/2);
			$cantidad=$this->validarMaximo($cantidad)+1; //empieza en key1
			for ($i=1;$i<$cantidad;$i++){
				if (($i%$mitad==1) || ($i==1)){
					$html.='<tr>';
				}
				$imagen=$this->get($i);
				if (!empty($imagen)){
					$anuncio=$this->registro->get('anuncio');
					if(!empty($anuncio)){
						$titulo=$anuncio->getTitulo();
					}else{
						$titulo='';
					}
					$html.='<td align="center"><img src="'.$imagen.'" alt="'.$titulo.'" /></td>';
				}
				/*else{
					$html.='<td align="center">&nbsp;</td>';
				
				}*/
				if ($i%$mitad==0){
					$html.='</tr>';
				}
			}
			$html.='</tbody></table>';
		}
		return $html;
	}
    // Set image
    public function set_img($img)
    {	
    	global $anuncio;

        // Find format
        $ext = strtoupper(pathinfo($img['name'], PATHINFO_EXTENSION));
        $this->ext=".".pathinfo($img['name'], PATHINFO_EXTENSION);
        $img=$img['tmp_name'];
        // JPEG image
        if(is_file($img) && ($ext == "JPG" OR $ext == "JPEG"))
        {

            $this->format = $ext;
            $this->img_input = ImageCreateFromJPEG($img);
            $this->img_src = $img;
           

        }

        // PNG image
        elseif(is_file($img) && $ext == "PNG")
        {

            $this->format = $ext;
            $this->img_input = ImageCreateFromPNG($img);
            $this->img_src = $img;

        }

        // GIF image
        elseif(is_file($img) && $ext == "GIF")
        {

            $this->format = $ext;
            $this->img_input = ImageCreateFromGIF($img);
            $this->img_src = $img;

        }elseif(is_file($img) && $ext == "BMP"){
        	$this->format = $ext;
            $this->img_input = $this->ImageCreateFromBMP($img);
            $this->img_src = $img;
        }
        // Get dimensions
        $this->x_input = @imagesx($this->img_input);
        $this->y_input = @imagesy($this->img_input);

    }

    // Set maximum image size (pixels)
    public function set_size($size = 100)
    {

        // Resize
        if($this->x_input > $size && $this->y_input > $size)
        {

            // Wide
            if($this->x_input >= $this->y_input)
            {

                $this->x_output = $size;
                $this->y_output = ($this->x_output / $this->x_input) * $this->y_input;

            }

            // Tall
            else
            {

                $this->y_output = $size;
                $this->x_output = ($this->y_output / $this->y_input) * $this->x_input;

            }

            // Ready
            $this->resize = TRUE;

        }

        // Don't resize
        else { $this->resize = FALSE; }

    }

    // Set image quality (JPEG only)
    public function set_quality($quality)
    {

        if(is_int($quality))
        {

            $this->quality = $quality;

        }

    }

    // Save image
    public function save_img($path)
    {
		$path=$path.$this->ext;
        // Resize
        
        if($this->resize)
        {

            $this->img_output = ImageCreateTrueColor($this->x_output, $this->y_output);
            ImageCopyResampled($this->img_output, $this->img_input, 0, 0, 0, 0, $this->x_output, $this->y_output, $this->x_input, $this->y_input);

        }
        // Save JPEG
        if($this->format == "JPG" OR $this->format == "JPEG")
        {
		if($this->resize) { 
			imageJPEG($this->img_output, $path, $this->quality);
		}else {
			copy($this->img_src, $path);
		}

       } // Save PNG
       elseif($this->format == "PNG")
        {

            if($this->resize) { imagePNG($this->img_output, $path); }
            else { copy($this->img_src, $path); }

        }
        // Save GIF
        elseif($this->format == "GIF")
        {

            if($this->resize) { imageGIF($this->img_output, $path); }
            else { copy($this->img_src, $path); }

        }elseif($this->format == "BMP"){
	        if($this->resize) { 
				imageJPEG($this->img_output, $path, $this->quality);
			}else {
				copy($this->img_src, $path);
			}
        }
    }

    // Get width
    public function get_width()
    {

        return $this->x_input;

    }

    // Get height
    public function get_height()
    {

        return $this->y_input;

    }

    // Clear image cache
    public function clear_cache()
    {

        @ImageDestroy($this->img_input);
        @ImageDestroy($this->img_output);

    }
	function ImageCreateFromBMP($filename)
	{
	 //Ouverture du fichier en mode binaire
	   if (! $f1 = fopen($filename,"rb")) return FALSE;
	
	 //1 : Chargement des ent�tes FICHIER
	   $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1,14));
	   if ($FILE['file_type'] != 19778) return FALSE;
	
	 //2 : Chargement des ent�tes BMP
	   $BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
	                 '/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
	                 '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1,40));
	   $BMP['colors'] = pow(2,$BMP['bits_per_pixel']);
	   if ($BMP['size_bitmap'] == 0) $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
	   $BMP['bytes_per_pixel'] = $BMP['bits_per_pixel']/8;
	   $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
	   $BMP['decal'] = ($BMP['width']*$BMP['bytes_per_pixel']/4);
	   $BMP['decal'] -= floor($BMP['width']*$BMP['bytes_per_pixel']/4);
	   $BMP['decal'] = 4-(4*$BMP['decal']);
	   if ($BMP['decal'] == 4) $BMP['decal'] = 0;
	
	 //3 : Chargement des couleurs de la palette
	   $PALETTE = array();
	   if ($BMP['colors'] < 16777216)
	   {
	    $PALETTE = unpack('V'.$BMP['colors'], fread($f1,$BMP['colors']*4));
	   }
	
	 //4 : Cr�ation de l'image
	   $IMG = fread($f1,$BMP['size_bitmap']);
	   $VIDE = chr(0);
	
	   $res = imagecreatetruecolor($BMP['width'],$BMP['height']);
	   $P = 0;
	   $Y = $BMP['height']-1;
	   while ($Y >= 0)
	   {
	    $X=0;
	    while ($X < $BMP['width'])
	    {
	     if ($BMP['bits_per_pixel'] == 24)
	        $COLOR = unpack("V",substr($IMG,$P,3).$VIDE);
	     elseif ($BMP['bits_per_pixel'] == 16)
	     { 
	        $COLOR = unpack("n",substr($IMG,$P,2));
	        $COLOR[1] = $PALETTE[$COLOR[1]+1];
	     }
	     elseif ($BMP['bits_per_pixel'] == 8)
	     { 
	        $COLOR = unpack("n",$VIDE.substr($IMG,$P,1));
	        $COLOR[1] = $PALETTE[$COLOR[1]+1];
	     }
	     elseif ($BMP['bits_per_pixel'] == 4)
	     {
	        $COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
	        if (($P*2)%2 == 0) $COLOR[1] = ($COLOR[1] >> 4) ; else $COLOR[1] = ($COLOR[1] & 0x0F);
	        $COLOR[1] = $PALETTE[$COLOR[1]+1];
	     }
	     elseif ($BMP['bits_per_pixel'] == 1)
	     {
	        $COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
	        if     (($P*8)%8 == 0) $COLOR[1] =  $COLOR[1]        >>7;
	        elseif (($P*8)%8 == 1) $COLOR[1] = ($COLOR[1] & 0x40)>>6;
	        elseif (($P*8)%8 == 2) $COLOR[1] = ($COLOR[1] & 0x20)>>5;
	        elseif (($P*8)%8 == 3) $COLOR[1] = ($COLOR[1] & 0x10)>>4;
	        elseif (($P*8)%8 == 4) $COLOR[1] = ($COLOR[1] & 0x8)>>3;
	        elseif (($P*8)%8 == 5) $COLOR[1] = ($COLOR[1] & 0x4)>>2;
	        elseif (($P*8)%8 == 6) $COLOR[1] = ($COLOR[1] & 0x2)>>1;
	        elseif (($P*8)%8 == 7) $COLOR[1] = ($COLOR[1] & 0x1);
	        $COLOR[1] = $PALETTE[$COLOR[1]+1];
	     }
	     else
	        return FALSE;
	     imagesetpixel($res,$X,$Y,$COLOR[1]);
	     $X++;
	     $P += $BMP['bytes_per_pixel'];
	    }
	    $Y--;
	    $P+=$BMP['decal'];
	   }
	
	 //Fermeture du fichier
	   fclose($f1);
	
	 return $res;
	}
	
}
?>