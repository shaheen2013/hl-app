<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//------------------------------------------------------------------------------------//
// Publicar/Guardar oferta
// $publicar: 1 o 6 (publicar), 0 (borrador)
// Devuelve:
//	id_oferta: id de la oferta
//  obligarorios: 200 -> todos los idiomas insertados estan completos
//				  400 -> si algÃºn idioma le falta algun campo
//				  (Si algÃºn idioma no ha sido rellenado en su totalidad no da error)
//------------------------------------------------------------------------------------//
function guardarOferta($publicar)
{
    if($publicar=='1' || $publicar=='6'){
        $fecha_publicada = datetimeHoy();
        if($_SESSION['offerMethod']=='ref')
        {
            // Si la oferta es de Referral y la publicamos la fecha 'Valid From' es hoy
            $_SESSION['inicio'] = girarFecha(dateHoy());
        }
    }else{
        $fecha_publicada = '0000-00-00 00:00:00';// Se le asignara una fecha cuando se publique
    }
    $fecha_creacion = datetimeHoy();

    //Mirar si es cadena / hotel
    if(!empty($_SESSION['c_logueado'])){
        $id_cadena = $_SESSION['c_logueado'];
        $tipo = 'c';
    }else{
        $id_cadena = '0';
        $tipo = 'h';
    }
    if(isset($_SESSION['puntos']) && $_SESSION['puntos']=='XXX'){
        $_SESSION['puntos']='0';
    }

    global $datos;//Array datos $_SESSION para guardar
    $sql = "INSERT INTO hotel_oferta (
                id_hotel,
                id_cadena,
                id_tipo_oferta,
                id_categoria,
                id_subcategoria,
                adq_ret,
                inicio,
                fin,
                requerimientos,
                cupo,
                descuento,
                coste,
                puntos,
                img,
                moneda,
                booking_engine_code,
                estado,
                fecha_publicada,
                fecha_creacion)
            VALUES ";
    //Si es una cadena y la oferta es de referral la guardamos como oferta de Cadena
    if($tipo == 'c' && $_SESSION['offerMethod']=='ref'){
        $sql .="('', '".$id_cadena."', ";
    }else{
        $sql .="('".$_SESSION['h_logueado']."', '', ";
    }
    $n = count ($datos);
    $i=0;
    while ($i < $n){
        if(!empty ($_SESSION[$datos[$i]])){
            if ($datos[$i] == 'inicio' || $datos[$i] == 'fin'){
                $sql .=" '".girarFecha(mysqli_real_escape_string(conectar() ,$_SESSION[$datos[$i]]))."',";
            }else{
                $sql .=" '".mysqli_real_escape_string(conectar() ,$_SESSION[$datos[$i]])."',";
            }
        }else{
            $sql .=" '',";
        }
        $i++;
    }
    $sql .="'".$publicar."', '".$fecha_publicada."', '".$fecha_creacion."' )";
    //echo $sql;
    $link = conectar();
    mysqli_query ($link, $sql) or die (mysqli_error());
    $ultimoID = mysqli_insert_id($link);
    //mysqli_close($link);

    //--------------Guardamos Lang en tabla aparte--------------
    if(!empty($ultimoID))
    {
        $obligatorios = insertLangs($ultimoID);
    }


    $result['id_oferta'] = $ultimoID;
    $result['obligatorios'] = $obligatorios;

    return ($result);
}

// Update oferta
// $estado: 1 (Publicado), 0 (Borrador) para ofertas de adq/ret
// $estado: 6 (no asignada), 0 (Borrador) para ofertas de ref
function updateOferta($id_oferta, $estado)
{
    if($estado=='1' || $estado=='6'){
        $fecha_publicada = datetimeHoy();
    }else{
        $fecha_publicada = '0000-00-00 00:00:00';// Se le asignara una fecha cuando se publique
    }

    global $datos;//Array datos $_SESSION para guardar

    //Mirar si es cadena / hotel
    if(!empty($_SESSION['c_logueado'])){
        $id_cadena = $_SESSION['c_logueado'];
        $tipo = 'c';
    }else{
        $id_cadena = '0';
        $tipo = 'h';
    }
    if(isset($_SESSION['puntos']) && $_SESSION['puntos']=='XXX'){
        $_SESSION['puntos']='0';
    }

    //Datos para el update
    $datos2 = array ('id_tipo_oferta', 'id_categoria', 'id_subcategoria', 'adq_ret', 'inicio', 'fin', 'requerimientos', 'cupo', 'descuento', 'coste', 'puntos', 'img', 'moneda', 'booking_engine_code');

    $sql = "UPDATE hotel_oferta SET ";
    $n = count ($datos);
    $i=0;
    while ($i < $n){
        if(!empty ($_SESSION[$datos[$i]])){
            if ($datos[$i] == 'inicio' || $datos[$i] == 'fin'){
                $sql .= " $datos2[$i]='".girarFecha(mysqli_real_escape_string(conectar() ,$_SESSION[$datos[$i]]))."', ";
            }else{
                $sql .= " $datos2[$i]='".mysqli_real_escape_string(conectar() ,$_SESSION[$datos[$i]])."', ";
            }
        }
        $i++;
    }
    $sql .=" estado='".$estado."', fecha_publicada='".$fecha_publicada."' "; //
    $sql .=" WHERE id='".$id_oferta."' ";
    if($tipo == 'c' && $_SESSION['offerMethod']=='ref'){
        $sql .=" AND id_cadena='".$id_cadena."' ";
    }else{
        $sql .=" AND id_hotel='".$_SESSION['h_logueado']."' ";
    }
    //echo $sql;
    mysqli_query (conectar(), $sql) or die(mysqli_error());

    //--------------Lang--------------
    //Datos para el update / insert lang

    $langsHotel = obtenerIdStringLangsHotel($_SESSION['h_logueado']);
    foreach($langsHotel as $lang)
    {
        //Update (si existe) o insert (si no existe)
        if(existeLang($id_oferta, $lang))
        {
            $result = updateLang($id_oferta, $lang);
        }else{
            $result = insertLang($id_oferta, $lang);
        }
    }
    return $result;
}

//Insert de oferta para todos los langs del hotel
function insertLangs($ultimoID)
{
    global $datosLang;//Array datos $_SESSION para guardar

    $n = count ($datosLang);
    $sql2 = "INSERT INTO hotel_oferta_lang
	(id_oferta, descripcion, condiciones, nombre, lang, lang_ok) 
	VALUES ";
    $langsHotel = obtenerIdStringLangsHotel($_SESSION['h_logueado']);
    $sql3 = '';
    $result = '200';
    foreach($langsHotel as $lang)
    {

        !empty($_SESSION[$lang]['nombre'])?$nombre=$_SESSION[$lang]['nombre']: $nombre='';
        !empty($_SESSION[$lang]['descripcion'])?$descripcion=$_SESSION[$lang]['descripcion']: $descripcion='';
        !empty($_SESSION[$lang]['condiciones'])?$condiciones=$_SESSION[$lang]['condiciones']: $condiciones='';

        if( !empty($nombre) || !empty($descripcion) || !empty($condiciones) )
        {
            //Si no tiene todos los campos de lang en blanco procedemos
            $sql3 .="( '".$ultimoID."', ";
            $i=0;
            while ($i < $n){
                if(!empty ($_SESSION[$lang][$datosLang[$i]])){
                    $sql3 .=" '".mysqli_real_escape_string(conectar() ,$_SESSION[$lang][$datosLang[$i]])."',";
                }else{
                    $sql3 .=" '',";
                }
                $i++;
            }
            $sql3 .=" '".$lang."', ";
            //Si los campos obligatorios del lang estan todos marcamos lang_ok=1 sino 0
            if(camposObligatoriosLangOk($nombre,$descripcion, $condiciones) ){
                $sql3 .=" '1'),";
            }else{
                $result = '400';
                $sql3 .=" '0'),";
            }
        }else{
            //echo ' - NO tiene ningÃºn campo <br>';
        }

    }
    //Quitamos Ãºltima coma
    $sql2 .= substr($sql3,0, -1);
    //echo $sql2.'<br>';
    //mysqli_query (conectar(), $sql2) or die (mysqli_error());
    escritura($sql2);

    return $result;
}

//Inserta 1 lang de oferta, para la FX updateOferta
//Si esta haciendo update de una oferta y ha aÃ±adido un lang nuevo
function insertLang($id_oferta, $lang)
{
    global $datosLang;//Array datos $_SESSION para guardar
    $n = count ($datosLang);

    !empty($_SESSION[$lang]['nombre'])?$nombre=$_SESSION[$lang]['nombre']: $nombre='';
    !empty($_SESSION[$lang]['descripcion'])?$descripcion=$_SESSION[$lang]['descripcion']: $descripcion='';
    !empty($_SESSION[$lang]['condiciones'])?$condiciones=$_SESSION[$lang]['condiciones']: $condiciones='';

    $result['obligatorios'] = '200';

    if( !empty($nombre) || !empty($descripcion) || !empty($condiciones) )
    {
        //Si no tiene todos los campos de lang en blanco procedemos
        $sql2 = "INSERT INTO hotel_oferta_lang
		(id_oferta, descripcion, condiciones, nombre, lang, lang_ok) 
		VALUES ( '".$id_oferta."', ";
        $i=0;
        while ($i < $n){
            if(!empty ($_SESSION[$lang][$datosLang[$i]])){
                $sql2 .=" '".mysqli_real_escape_string(conectar() ,$_SESSION[$lang][$datosLang[$i]])."',";
            }else{
                $sql2 .=" '',";
            }
            $i++;
        }
        $sql2 .=" '".$lang."', ";
        //Si los campos obligatorios del lang estan todos marcamos lang_ok=1 sino 0
        if(camposObligatoriosLangOk($nombre,$descripcion, $condiciones) ){
            $sql2 .=" '1'),";
        }else{
            $result['obligatorios'] = '400';
            $sql2 .=" '0'),";
        }
        //Quitamos Ãºltima coma
        $sql = substr($sql2,0, -1);
        //echo $sql.'<br>';
        //mysqli_query (conectar(), $sql) or die (mysqli_error());
        escritura($sql);
    }
    return $result;
}

function updateLang($id_oferta, $lang)
{
    global $datosLang;//Array datos $_SESSION para guardar
    $n = count ($datosLang);
    $i=0;
    $result['obligatorios'] = '200';
    $sql2 = "UPDATE hotel_oferta_lang SET ";
    while ($i < $n){
        if(!empty ($_SESSION[$lang][$datosLang[$i]])){
            $sql2 .= " ".$datosLang[$i]."='".mysqli_real_escape_string(conectar() ,$_SESSION[$lang][$datosLang[$i]])."', ";
        }
        $i++;
    }
    //Si los campos obligatorios del lang estan todos marcamo lang_ok=1 sino 0
    !empty($_SESSION[$lang]['nombre'])?$nombre=$_SESSION[$lang]['nombre']: $nombre='';
    !empty($_SESSION[$lang]['descripcion'])?$descripcion=$_SESSION[$lang]['descripcion']: $descripcion='';
    !empty($_SESSION[$lang]['condiciones'])?$condiciones=$_SESSION[$lang]['condiciones']: $condiciones='';
    if(camposObligatoriosLangOk($nombre,$descripcion, $condiciones) )
    {
        $sql2 .= " lang_ok='1' ";
    }else{
        $result['obligatorios'] = '400';
        $sql2 .= " lang_ok='0' ";
    }
    $sql2 .=" WHERE id_oferta='".$id_oferta."' AND lang='".$lang."' ";
    //echo $sql2.'<br>';
    //mysqli_query (conectar(), $sql2) or die (mysqli_error());
    escritura($sql2);
    return $result;
}

function existeLang($id_oferta, $lang)
{
    $sql = "SELECT COUNT(id) AS n FROM hotel_oferta_lang 
	WHERE id_oferta='".$id_oferta."' AND lang='".$lang."' ";
    $rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
    $row = mysqli_fetch_assoc($rs);
    liberar($rs);
    if($row['n']=='0')
    {
        return false;
    }else{
        return true;
    }
}
?>