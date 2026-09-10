<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'rmdir.php';
include_once LIB.'sanitize.php';
include_once LIB.'borrarSession.php';
include_once LIB.'subirArchivos.php';// Para crear thumbnails
include_once LIB.'guardarOferta.php';

function generarURLOferta(){
    global $urlTree;
    $urlOferta = BASE_PATH;
    $urlOferta .= $urlTree['oferta'].'/';
    if(!empty($_SESSION[$_SESSION['lang']]['nombre']))
    {
        $urlOferta .= string_sanitize($_SESSION[$_SESSION['lang']]['nombre']);
    }else{
        $urlOferta .= 'offer';
    }
    $urlOferta .= '/'.$_SESSION['id_oferta'];
    return $urlOferta;
}
$offermethod = $_SESSION['offerMethod'];

if (!empty($_SESSION['guardada']) && $_SESSION['guardada']==1 && $_SESSION['publicable']==1){

    $_SESSION['guardada'] = 0;
    // Publicamos la oferta
    if($_SESSION['offerMethod']=='ref'){
        //El status no asignada borrable/editable/activable de las ofertas de ref es 6
        $publicar = '6';
    }else{
        //El status publicada de las ofertas de adq/ret es 1
        $publicar = '1';
    }

    $result = guardarOferta($publicar);
    $id_oferta = $_SESSION['id_oferta'] = $result['id_oferta'];

    if($result['obligatorios']=='400')
    {
        $ok = array(false, '3009');//Warning: algÃºn idioma tiene algÃºn campo en blanco
    }

    //Movemos la IMG de TMP al directorio definitivo
    mkdir ( $_SESSION['ruta'].$_SESSION['id_oferta'], 0777);
    copy ($_SESSION['ruta_tmp'].$_SESSION['foto'], $_SESSION['ruta'].$_SESSION['id_oferta'].'/'.$_SESSION['foto']);
    crearThumbnails($_SESSION['ruta'].$_SESSION['id_oferta'].'/'.$_SESSION['foto']);

    //Borrar carpeta temporal de imagenes
    rm_dir($_SESSION['ruta_tmp']);
    $_SESSION['publicada'] = 1;//La oferta ha sido publicada (para info de onboarding)

    //Si estaba creando una oferta de referral para un goal devolvemos el control a esa pantalla
    //para que realice sus acciones
    if(isset ($_COOKIE['goal'])){
        header('Location: /'.$urlTree['referral-goals'].'/?offer='.$id_oferta);
    }
    //Esta creando una oferta nueva de post/pre stay
    if(isset ($_COOKIE['stay'])){
        header('Location: /'.$urlTree['referral-goals'].'/?offer='.$id_oferta);
    }

}else if (!empty($_SESSION['editada']) && $_SESSION['editada']==1){

    $_SESSION['editada'] = 0;
    // Publicamos la oferta
    if($_SESSION['offerMethod']=='ref'){
        //El status no asignada borrable/editable/activable de las ofertas de ref es 6
        $publicar = '6';
    }else{
        //El status publicada de las ofertas de adq/ret es 1
        $publicar = '1';
    }
    // Guardamos la oferta
    $result = updateOferta($_SESSION['id_oferta'], $publicar);

    //Borrar cache
    deleteCacheByTag('oferta_'. $_SESSION['id_oferta']);

    if($result['obligatorios']=='400')
    {
        $ok = array(false, '3009');//Warning: algÃºn idioma tiene algÃºn campo en blanco
    }
    if(!empty($_SESSION['foto_nueva']) && $_SESSION['foto_nueva']==1){// Ha cambiado la foto
        //Miramos si existe la ruta de la img
        if (!is_dir($_SESSION['ruta'].$_SESSION['id_oferta'])) {
            //No existe, lo creamos
            mkdir ( $_SESSION['ruta'].$_SESSION['id_oferta'], 0777);
        }else{
            //Existe, lo vaciamos
            array_map('unlink', glob($_SESSION['ruta'].$_SESSION['id_oferta']."/*"));
        }
        copy ($_SESSION['ruta_tmp'].$_SESSION['foto'], $_SESSION['ruta'].$_SESSION['id_oferta'].'/'.$_SESSION['foto']);
        crearThumbnails($_SESSION['ruta'].$_SESSION['id_oferta'].'/'.$_SESSION['foto']);
        //Borrar carpeta temporal de imagenes
        rm_dir($_SESSION['ruta_tmp']);
    }
}else{
    //echo '-------------------oferta no publicada';
}

// Generar URL de la oferta
$urlOferta = generarURLOferta(/*lang*/);
// Borramos los datos de session
borrarSessionCrearOferta($_SESSION['h_logueado']);
//vaciarCarpeta();
?>