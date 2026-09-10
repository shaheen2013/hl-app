<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// Funcion para borrar directorio con y sin contenido
function rm_dir($dir) {
    foreach(glob($dir . '/' . '*') as $file) {
        if(is_dir($file)){
            rm_dir($file);
        }else{
            unlink($file);
        }
    }
    rmdir($dir);
}
?>