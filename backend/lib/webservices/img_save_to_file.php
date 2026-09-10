<?php
include_once 'librerias.php';// Librerias bÃ¡sicas

$imagePath = $_SERVER['DOCUMENT_ROOT'] . DS . DIR_IMG_FICHA_HOTEL . $_SESSION['h_logueado'] . '/tmp/';
$_SESSION['dir_tmp'] = $imagePath;

//delete old files
$files = glob($imagePath . '*'); // get all file names
foreach($files as $file){ // iterate files
    if(is_file($file))
        unlink($file); // delete file
}

$allowedExts = array("gif", "jpeg", "jpg", "png", "GIF", "JPEG", "JPG", "PNG");

if(!$_FILES){
    $response = array(
        "status" => 'error',
        "code" => 'imgNotUploaded',
    );
    echo json_encode($response);
    exit;
}

$temp = explode(".", $_FILES["uploadImg"]["name"]);
$extension = end($temp);

if ( in_array($extension, $allowedExts)){
    if ($_FILES["uploadImg"]["error"] > 0){
        $response = array(
            "status" => 'error',
            "message" => 'ERROR Return Code: '. $_FILES["uploadImg"]["error"],
        );
    }else{
        $filename = $_FILES["uploadImg"]["tmp_name"];
        list($width, $height) = getimagesize( $filename );
        if($width>=710 && $height>=500){

            $_FILES["uploadImg"]["name"] = str_replace(" ", "", $_FILES["uploadImg"]["name"]);
            $_SESSION['foto'] = $_FILES["uploadImg"]["name"];

            move_uploaded_file($filename,  $imagePath . $_FILES["uploadImg"]["name"]);

            //Imagen nueva ediciÃ³n
            $_SESSION['ruta_tmp'] = $imagePath = DIR_IMG_FICHA_HOTEL . $_SESSION['h_logueado'] . '/tmp/';
            $_SESSION['foto_nueva']=1;

            $response = array(
                "status" => 'success',
                "url" => $imagePath.$_SESSION['foto'],
                "width" => $width,
                "height" => $height
            );
        }else{
            $response = array(
                "status" => 'error',
                "code" => "imgSmall"
            );
        }
    }
}else{
    $response = array(
        "status" => 'error',
        "message" => 'imgNotAllowed',
    );
}

echo json_encode($response);

?>