<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once RUTA_DIR.LIB.'fecha.php';
include_once RUTA_DIR.LIB.'enviarEmail.php';
include_once RUTA_DIR.LIB.'obtenerDatosUsuario.php';
include_once RUTA_DIR.LIB.'idiomas.php';

function obtenerDatosInvitador($tipo, $invitador){
	if($tipo=='hot'){//Hotel
		$sql = "SELECT id, hotelName AS nombre
		FROM hoteles WHERE id='".$invitador."' ";
	}else if($tipo=='us'){//Usuario
		$sql = "SELECT id, nombre
		FROM users WHERE id='".$invitador."' ";
	}
	$row = lectura($sql);
	return $row;
}

function comprobarSiUsuarioNuevo($email){
	$email = mysqli_real_escape_string(conectar(), $email);
	$sql = "SELECT id FROM users WHERE email='".$email."' " ;
	$row = lectura($sql);
	if (empty($row)){
		return 0;
	}else{
		return $row['id'];
	}
}

//---------------crearInvitacionUsuario---------------------------
//email: email del usuario invitado
//Tipo: tipo de invitador, hot/us (hotel/usuario)
//Invitador: id_usuario del invitador
//Id encuesta: id encuesta compartida en digital-loyalty-program
//txtExtra: texto extra que se le añade a la invitación (cupón regalado,...)
//$noMail:0 manda email, 1 no manda email
//----------------------------------------------------------------
function crearInvitacionUsuario ($email,$tipo,$invitador,$puntos=0,$id_encuesta=0,$txtExtra=0,$ptSumados=0, $nombre_destino='', $noMail='')
{
	$email = mysqli_real_escape_string(conectar(), $email);
	$invitador = mysqli_real_escape_string(conectar(), $invitador);
	$id_encuesta = mysqli_real_escape_string(conectar(), $id_encuesta);
	$nombre_destino = mysqli_real_escape_string(conectar(), $nombre_destino);
	
	$sql2 = "SELECT id, token FROM invitaciones_users WHERE email='".$email."' ";
	$sql2 .= " AND invitador=".$invitador." AND tipo_invitador='".$tipo."'";
	//echo $sql2.'<br>';
	$row = lectura($sql2);
	
	if (empty($row))
	{
		$fecha = dateTimeHoy();
		//Invitación nueva
		$token = generarTokenInvitacion();//Generamos nuevo token
		$sql = "INSERT INTO invitaciones_users
		(email, created, token, invitador, id_encuesta,tipo_invitador, puntos, puntos_sumados) VALUES
		('".$email."', '".$fecha."', '".$token."', '".$invitador."'
		, '".$id_encuesta."', '".$tipo."', '".$puntos."', '".$ptSumados."')";
	}else{
		//Actualizamos invitación
		$sql = "UPDATE invitaciones_users SET ";
		if($puntos==0){
			$sql .= " puntos=puntos ";//No modificamos los puntos
		}else{
			$sql .= " puntos='".$puntos."' ";
		}
		$sql .= ", id_encuesta='".$id_encuesta."' WHERE id='".$row['id']."' ";
		$token = $row['token'];
	}
	global $urlTree; 
	$urlInvitacion = BASE_PATH . $urlTree['login'].'/?token='.$token;
	$result['urlInvitacion']=$urlInvitacion;
	escritura($sql);

	if($noMail=='' || $noMail==0){//Mandamos email
		// Actualizamos invitación
		$datosInvitador = obtenerDatosInvitador($tipo, $invitador);
		
		//Enviar email
		if ($nombre_destino == ''){
			$nombre_destino = 'guest';
		}
		// Obtenemos el lang del usuario y lo filtramos por los langs de la plataforma
		$lang = mirarIdiomaPlataforma(obtenerLangUsuarioEmail($email));
		//include_once $base2.LANG.'email/invitacion'.ucfirst($tipo).'-'.$_SESSION['userLang'].'.php';
		include RUTA_DIR.LANG.$lang.'/email/invitacion'.ucfirst($tipo).'.php';
		include RUTA_DIR.LIB.'plantillasMails/invitacion'.ucfirst($tipo).'.php';
		mandarEmailMandrillPlantilla($email, $nombre_destino, $asunto, $cuerpo);
	}
	return $result;
}

function crearInvitacionStaff($email, $name, $pass, $datosHotel, $id_staff, $token)
{
	include_once LIB . 'obtenerDatosStaff.php';
	$staff = obtenerDatosStaffEmail($id_staff);
	// Enviar email
	include_once LANG.$_SESSION['userLang'].'/email/add-staff.php';
	include_once LIB.'plantillasMails/add-staff.php';
	mandarEmailMandrillPlantilla($email, $name, $asunto, $cuerpo, 'standard-template');
}

function generarTokenInvitacion(){
	$token='';
	$cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	$longitudCadena=strlen($cadena);
	$longitudToken=30;
	for($i=1 ; $i<=$longitudToken ; $i++){
		$pos=rand(0,$longitudCadena-1);
		$token .= substr($cadena,$pos,1);
	}
	return $token;
}

/*function comprobarInvitacionStaff($email, $token){
	$sql = "SELECT email FROM invitaciones_hotel_staff WHERE token = '".$token."'";
	$rs = mysqli_query (conectar(), $sql) or die("ERROR BD ".mysqli_error());
	$n_resultados = mysqli_num_rows($rs);
	if ($n_resultados == 0 ){
		return '';
	}else{
		$row = mysqli_fetch_assoc($rs);
		return $row['email'];
	}
	liberar($rs);
}*/

function comprobarInvitacionUsuario($token){
	$token = mysqli_real_escape_string(conectar(), $token);
	$sql = "SELECT email FROM invitaciones_users WHERE token = '".$token."'";
	$row = lectura($sql);
	if (empty($row) ){
		return false;
	}else{
		return true;
	}
}

function sumarPuntosInvitacionesUsuario($email){
	/*$email = mysqli_real_escape_string(conectar(), $email);
	//Sumar puntos de invitaciones
	$sql = "SELECT users.id, puntos, invitador, tipo_invitador, id_encuesta
	FROM invitaciones_users
	INNER JOIN users ON users.email=invitaciones_users.email
	WHERE invitaciones_users.email='".$email."'  AND puntos_sumados=0";
	$rs = mysqli_query (conectar(), $sql);
	$puntosRegistro = obtenerPuntos('register');
	while($row = mysqli_fetch_assoc($rs)){
		//echo '<!--'.$row['id'].' '.$row['invitador'].' '.$row['puntos'].'--><br>';
		if($row['tipo_invitador']=='hot'){
			agregarPuntos($row['id'], $row['invitador'], $row['puntos'], '8');//agregar puntos invitacion
		}else if($row['tipo_invitador']=='us' && $row['invitador']!='' && $row['id_encuesta']!=0){
			//Agregar puntos
			agregarPuntosHl($row['id'],$puntosRegistro,4, 0, 0, $row['invitador']);
		}
	}
	liberar($rs);*/
}

function obtenerEmailInvitacionUS($token){
	$token = mysqli_real_escape_string(conectar(), $token);
	$sql = "SELECT email FROM invitaciones_users WHERE token = '".$token."'";
	$row = lectura($sql);
	return $row['email'];
}

//Borra todas las invitaciones por email
function borrarInvitacionUsuario($email){
	$email = mysqli_real_escape_string(conectar(), $email);
	$sql = "DELETE FROM invitaciones_users WHERE email='".$email."' ";
	escritura($sql);
}

function borrarInvitacionHotel($email){
	$email = mysqli_real_escape_string(conectar(), $email);
	$sql = "DELETE FROM invitaciones_hotel WHERE email = '".$email."'";
	escritura($sql);	
}

//Miramos (por email) que la cuenta de usuario no esté activada
function userNoActivo($email){
	$email = mysqli_real_escape_string(conectar(), $email);
	$sql = "SELECT created FROM users WHERE email='".$email."' ";
	$row = lectura($sql);
	if($row['created']=='0000-00-00 00:00:00'){
		return true;
	}else{
		return false;
	}
}

//Miramos por id de usuario) que la cuenta de usuario no esté activada
function userNoActivoId($id_usuario){
	$id_usuario = mysqli_real_escape_string(conectar(), $id_usuario);
	$sql = "SELECT created FROM users WHERE id='".$id_usuario."' ";
	$row = lectura($sql);
	if($row['created']=='0000-00-00 00:00:00'){
		return true;
	}else{
		return false;
	}
}
?>