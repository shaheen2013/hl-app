<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'rmdir.php';
include_once LIB.'borrarSession.php';
include_once LIB.'subirArchivos.php';// Para crear thumbnails
include_once LIB.'guardarOferta.php';

if (!empty($_SESSION['guardada']) && $_SESSION['guardada']==1)
{
    //echo '--------guardada';
    $_SESSION['guardada'] = 0;
    // Guardamos la oferta
    $result = guardarOferta('0');

    $_SESSION['id_oferta'] = $result['id_oferta'];
    if($result['obligatorios']=='400')
    {
        $ok = array(false, '3009');//Warning: algÃºn idioma tiene algÃºn campo en blanco
    }
    if(!empty($_SESSION['foto']))
    {
        if(!empty($_SESSION['ruta'])){
            mkdir ( $_SESSION['ruta'].$_SESSION['id_oferta'], 0777);
        }
        copy ($_SESSION['ruta_tmp'].$_SESSION['foto'], $_SESSION['ruta'].$_SESSION['id_oferta'].'/'.$_SESSION['foto']);
        crearThumbnails($_SESSION['ruta'].$_SESSION['id_oferta'].'/'.$_SESSION['foto']);
    }
    //Borrar carpeta temporal de imagenes
    rm_dir($_SESSION['ruta_tmp']);
    // Borramos los datos de session
    borrarSessionCrearOferta($_SESSION['h_logueado']);

}else if (!empty($_SESSION['editada']) && $_SESSION['editada']==1){

    //echo '--------editada';
    $_SESSION['editada'] = 0;
    // Guardamos la oferta
    $result = updateOferta($_SESSION['id_oferta'], '0');

    if($result['obligatorios']=='400')
    {
        $ok = array(false, '3009');//Warning: algÃºn idioma tiene algÃºn campo en blanco
    }
    if(!empty($_SESSION['foto_nueva']) && $_SESSION['foto_nueva']==1)// Ha cambiado la foto
    {
        array_map('unlink', glob($_SESSION['ruta'].$_SESSION['id_oferta']."/*"));
        if(!is_dir($_SESSION['ruta'].$_SESSION['id_oferta']))//Si no existe el DIR lo creamos
        {
            //echo 'creamos DIR';
            mkdir ( $_SESSION['ruta'].$_SESSION['id_oferta'], 0777);
        }
        copy ($_SESSION['ruta_tmp'].$_SESSION['foto'], $_SESSION['ruta'].$_SESSION['id_oferta'].'/'.$_SESSION['foto']);
        crearThumbnails($_SESSION['ruta'].$_SESSION['id_oferta'].'/'.$_SESSION['foto']);
    }
    //Borrar carpeta temporal de imagenes
    rm_dir($_SESSION['ruta_tmp']);
    // Borramos los datos de session
    borrarSessionCrearOferta($_SESSION['h_logueado']);
}
?>