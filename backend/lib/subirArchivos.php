<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function reducirImagen($ImagenTmp, $extension){
	// Reducir IMG
	if($extension=='jpg' || $extension == 'JPG'){
		$im = imagecreatefromjpeg($ImagenTmp);
		imagejpeg($im, $ImagenTmp, 50);
	}else if($extension=='gif' || $extension == 'GIF'){
		$im = imagecreatefromgif($ImagenTmp);
		imagejpeg($im, $ImagenTmp, 50);
	}else if($extension=='png' || $extension == 'PNG'){
		$im = imagecreatefrompng($ImagenTmp);
		
		/*$background = imagecolorallocate($im, 0, 0, 0);
		imagecolortransparent($im, $background);
		imagealphablending($im, false);
		imagesavealpha($im, true);*/
		
		//imagepng($im, $ImagenTmp, 0);
		//imagealphablending($im, true);
		//imagesavealpha($im, true);
	}
	
	// Liberar memoria
	imagedestroy($im);
}

function crearThumbnails($ImagenTmp){
	// Crea thumbnails de 400x400 y 150x150 en el mismo directorio
	$arrayRutaImg = explode('/', $ImagenTmp);// Sacamos la ruta
	$n = count($arrayRutaImg);
	
	/*$arrayImg = explode('.', $rutaImg[$n-1]);// Sacamos la extension
	echo '<pre>';
	print_r($arrayImg);
	echo '</pre>';
	echo $n;*/
	
	$i=0;$rutaImg='';
	while($i<$n-1){
		$rutaImg .= $arrayRutaImg[$i].'/';
		$i++;
	}
	
	$img150 = $rutaImg.'small_'.$arrayRutaImg[$n-1];
	$img400 = $rutaImg.'med_'.$arrayRutaImg[$n-1];
	$img600 = $rutaImg.'big_'.$arrayRutaImg[$n-1];
	//echo $img150.'<br>'.$img400.'<br>'.$img600;

	redim ($ImagenTmp,$img150,'150','150');
	redim ($ImagenTmp,$img400,'400','400');
	redim ($ImagenTmp,$img600,'600','600');
}

function redim($ruta1,$ruta2,$ancho,$alto){
	// Reduce el tamaño de una imagen ya subida al disco
    # se obtene la dimension y tipo de imagen
    $datos=getimagesize ($ruta1);
    
    $ancho_orig = $datos[0]; # Anchura de la imagen original
    $alto_orig = $datos[1];  # Altura de la imagen original
    $tipo = $datos[2];
    
    if ($tipo==1){ # GIF
        if (function_exists("imagecreatefromgif"))
            $img = imagecreatefromgif($ruta1);
        else
            return false;
    }
    else if ($tipo==2){ # JPG
        if (function_exists("imagecreatefromjpeg"))
            $img = imagecreatefromjpeg($ruta1);
        else
            return false;
    }
    else if ($tipo==3){ # PNG
        if (function_exists("imagecreatefrompng")){
            $img = imagecreatefrompng($ruta1);
		}else{
            return false;
		}
    }
    
    # Se calculan las nuevas dimensiones de la imagen
    if ($ancho_orig>$alto_orig){
        $ancho_dest=$ancho;
        $alto_dest=($ancho_dest/$ancho_orig)*$alto_orig;
    }else{
        $alto_dest=$alto;
        $ancho_dest=($alto_dest/$alto_orig)*$ancho_orig;
    }

    // imagecreatetruecolor, solo estan en G.D. 2.0.1 con PHP 4.0.6+
    $img2=@imagecreatetruecolor($ancho_dest,$alto_dest) or $img2=imagecreate($ancho_dest,$alto_dest);
	imagealphablending( $img2, false );
	imagesavealpha( $img2, true );
	
    // Redimensionar
    // imagecopyresampled, solo estan en G.D. 2.0.1 con PHP 4.0.6+
    @imagecopyresampled($img2,$img,0,0,0,0,$ancho_dest,$alto_dest,$ancho_orig,$alto_orig) or imagecopyresized($img2,$img,0,0,0,0,$ancho_dest,$alto_dest,$ancho_orig,$alto_orig);

    // Crear fichero nuevo, según extensión.
    if ($tipo==1) // GIF
        if (function_exists("imagegif"))
            imagegif($img2, $ruta2);
        else
            return false;

    if ($tipo==2) // JPG
        if (function_exists("imagejpeg"))
            imagejpeg($img2, $ruta2);
        else
            return false;

    if ($tipo==3)  // PNG
        if (function_exists("imagepng"))
			imagepng($img2, $ruta2, 0);
		else
            return false;
    
	return true;
}

//Sube varias imagenes
function subirImagenes ($uploadDir, $arrayImagenes, $maxSize){
	$nImagenes = count ($arrayImagenes['name']);
	$i=0;$totalSize=0;
	while($i<$nImagenes){
		$totalSize += $arrayImagenes['size'][$i];
		$i++;
	}
	if ($totalSize>$maxSize){
		return false;
	}
	
	$i=0;
	while($i<$nImagenes){	
		$filesize = $arrayImagenes['size'][$i];
		$filename = trim($arrayImagenes['name'][$i]);
		$filename = str_replace(" ", "", $filename);
		//$filename[$i] = time().$filename;
		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		reducirImagen($arrayImagenes['tmp_name'][$i], $extension);
		
		if($filesize > 0){
			
			if(($extension == 'jpg') || ($extension == 'JPG') || ($extension == 'gif') || ($extension == 'GIF') || ($extension == 'png') || ($extension == 'PNG')){
				$uploadFile = $uploadDir . $filename;
				if (!file_exists($uploadDir)) {//Si no existe el directorio lo creamos
					mkdir($uploadDir, 0777, true); 
				}
				move_uploaded_file($arrayImagenes['tmp_name'][$i], $uploadFile);
				crearThumbnails($uploadFile);
			}else{
				//return false;
			}
		}else{
			//return false;
		}
		$i++;
	}
}

//Sube una imagen
function subirImagen ($uploadDir, $Imagen, $maxSize){
	$filesize = $Imagen['size'];
	$filename = trim($Imagen['name']);
	$filename = str_replace(" ", "", $filename);
	//$filename[$i] = time().$filename;
	
	if(($filesize > 0) && ($filesize < $maxSize)){
		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		if(($extension == 'jpg') || ($extension == 'JPG') || ($extension == 'gif') || ($extension == 'GIF') || ($extension == 'png') || ($extension == 'PNG') ){
			reducirImagen($Imagen['tmp_name'], $extension);
			$uploadFile = $uploadDir . $filename;
			if (!file_exists($uploadDir)) {//Si no existe el directorio lo creamos
				mkdir($uploadDir, 0777, true); 
			}
			move_uploaded_file($Imagen['tmp_name'], $uploadFile);
			crearThumbnails($uploadFile);
			$error = array('200');
		}else{
			//Extension incorrecta
			$error = array('400', '2');
			//return false;
		}
	}else{
		// Tamaño maximo sobrepasado
		$error = array('400', '3');
		//return false;
	}
	return $error;
}

// img con la ruta completa
function borrarThumbnail($ruta, $img){
	$arrayThumbs = array ('big', 'med', 'small');
	foreach ($arrayThumbs as $tipo){
		$thumb = $ruta.$tipo.'_'.$img;
		if(file_exists($thumb)){
			//echo '<br />Borrar: '.$thumb;
			unlink ($thumb);
		}
	}
}

function subirArchivo($archivo, $uploadDir, $filename){
	// size max: 20971520bytes -> 20Mb
	// type: text/plain -> txt, text/csv -> csv
	if($archivo['size']<20971520 && ($archivo['type']=='text/plain' || $archivo['type']=='text/csv' )){
		$uploadFile = $uploadDir .'/'. $filename;
		move_uploaded_file($archivo['tmp_name'], $uploadFile);
	}else{
		//archivo demasiado grande o de formato no permitido
	}
}
?>