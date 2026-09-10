<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Define si a la librería de emails se la va a llamar como webservice
$ws = 0;
// Acciones para adquirir oferta

include_once RUTA_DIR.LIB.'agregarPuntos.php';
include_once RUTA_DIR.LIB.'fecha.php';
include_once RUTA_DIR.LIB.'sanitize.php';
include_once RUTA_DIR.LIB.'wishlist.php';
include_once RUTA_DIR.LIB.'enviarEmail.php';
include_once RUTA_DIR.LIB.'obtenerdatosHotel.php';

function obtenerCupon($id_Oferta){
	// Obtenemos el último cupón
	$sql = "SELECT voucher FROM user_cupones WHERE id_oferta='".$id_Oferta."' ORDER BY fecha DESC LIMIT 1";
	$row = lectura($sql);
	return $row['voucher'];
}

function generarVoucherId(){
	$cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	$longitudCadena=strlen($cadena);
	$voucherID = "";
	$longitudVoucher=12; // Longitud del voucher
	for($i=1 ; $i<=$longitudVoucher ; $i++){
		$pos=rand(0,$longitudCadena-1);
		$voucherID .= substr($cadena,$pos,1);
	}
	//Comprobamos si existe el voucherID
	$sql = "SELECT COUNT(id) AS n FROM user_cupones WHERE voucher = '".$voucherID."' ";
	$row = lectura($sql);
	if ($row['n']==0){
		return $voucherID; // El voucherID no existe en la BD
	}else{
		generarVoucherId(); // VoucherID repetido, lo volvemos a calcular
	}
}

function quedaCupo($id_oferta){
	$sql = "SELECT cupo, adquiridas FROM hotel_oferta WHERE id='".$id_oferta."' ";
	////echo '<br />------------------------------'.$sql;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if($row['cupo'] == 0){ // No tiene cupo
		return true;
	}else{
		$quedan = $row['cupo'] - $row['adquiridas'];
			if ($quedan > 0){
			////echo '----------Queda cupo';
			return true;
		}else{
			////echo '----------No queda cupo';
			return false;
		}
	}
}

// Si es oferta de Adquisicion y el usuario ya ha ha h//echo check-in
// no puede canjear esta oferta !!! (si solo ha sido invitado por el hotel si).
// Si ha h//echo ckeck-in en un hotel de cadena no puede canjear ofertas de adq. de 
// los hoteles de la cadena
function checkinAnteriormente ($id_oferta, $userId){// iteración futura
	$sql3 = "SELECT adq_ret, id_hotel FROM hotel_oferta
	WHERE id='".$id_oferta."' ";
	$rs = mysqli_query (conectar(), $sql3);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	////echo '--------------'.$sql3;
	if($row['adq_ret']=='adq'){
		if(hotelDeCadena($row['id_hotel'])){
			$id_cadena = hotelIdCadena($row['id_hotel']);
			$sql4 = "SELECT COUNT(user_checkin.id) AS n
			FROM cadena_hotel
			INNER JOIN user_checkin ON user_checkin.id_hotel=cadena_hotel.id_hotel
			WHERE id_cadena='".$id_cadena."' 
			AND id_usuario='".$userId."'";
		}else{
			$sql4 = "SELECT COUNT(id) AS n FROM user_checkin
			WHERE id_usuario='".$userId."'
			AND id_hotel='".$row['id_hotel']."' ";
		}
		////echo '<br />'.$sql4;
		$rs4 = mysqli_query (conectar(), $sql4);
		$row4 = mysqli_fetch_assoc($rs4);
		liberar($rs4);
		if ($row4['n']==0){
			return true;
		}else{ //Ya ha h//echo ckeck-in en este hotel anteriormente, no puede adquirir of.
			return false;
		}
	}else{
		return true;
	}
}

// Las ofertas de Check-in requieren que el usuario esté en el hotel
function requiereChk($id_oferta, $userId){
	//echo '----->estoy en requiereChk<br>';
	$sql = "SELECT id_hotel, id_tipo_oferta FROM hotel_oferta WHERE id='".$id_oferta."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if ($row['id_tipo_oferta'] != 'chk'){ 
		// No es oferta de check-in -> puede adquirirla
		//echo 'puede adquirirla <br>';
		return 1;
	}else{
		//echo 'Miramos si el usuario está en checkin<br>';
		$id_hotel = $row['id_hotel'];

		$sql2 = "SELECT id, chkout_date
		FROM user_checkin
		WHERE id_usuario='".$userId."' AND id_hotel='".$id_hotel."'
		ORDER BY id DESC LIMIT 1";
		//echo $sql2 . '<br>';
		$rs2 = mysqli_query (conectar(), $sql2);
		$row2 = mysqli_fetch_assoc($rs2);
		liberar($rs2);
		if ($row2['chkout_date']=='0000-00-00'){
			//echo 'El usuario está en checkin<br>';
			return 1; // Es oferta de check-in y esta alojado en el hotel
		}else{
			// No pudede adquirir esta oferta -> No esta alojado en el hotel
			return 0;
		}
	}
}

function tienePuntosSuficientes ($id_oferta, $userId){
	$id_hotel = idHotelDeIdOferta($id_oferta);
	$sql3 = "SELECT hotel_oferta.puntos AS puntos_oferta, hotel_oferta.adq_ret
	FROM hotel_oferta WHERE id='".$id_oferta."'";
	$rs3 = mysqli_query (conectar(), $sql3);
	$row3 = mysqli_fetch_assoc($rs3);
	liberar ($rs3);
	if(hotelDeCadena($id_hotel)){
		/*$sql = "SELECT hotel_oferta.puntos AS puntos_oferta, hotel_oferta.adq_ret,
		user_points_cadena.puntos AS puntos_usuario
		FROM hotel_oferta
		INNER JOIN cadena_hotel ON cadena_hotel.id_hotel=hotel_oferta.id_hotel
		INNER JOIN user_points_cadena ON user_points_cadena.id_cadena=cadena_hotel.id_cadena
		WHERE user_points_cadena.id_usuario = '".$userId."' AND
		hotel_oferta.id='".$id_oferta."' ";*/
		$sql = "SELECT puntos AS puntos_usuario
		FROM user_points_cadena
		WHERE id_cadena='".hotelIdCadena($id_hotel)."' 
		AND id_usuario = '".$userId."' ";
	}else{
		/*$sql = "SELECT hotel_oferta.puntos AS puntos_oferta, hotel_oferta.adq_ret,
		user_points.puntos AS puntos_usuario
		FROM hotel_oferta
		LEFT JOIN user_points ON user_points.id_emisor=hotel_oferta.id_hotel
		WHERE user_points.id_usuario = '".$userId."' AND
		hotel_oferta.id='".$id_oferta."' ";*/
		$sql = "SELECT puntos AS puntos_usuario
		FROM user_points WHERE id_emisor='".$id_hotel."' 
		AND id_usuario = '".$userId."' ";
	}
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	if ($row3['adq_ret']=='ret'){
		// La oferta es de retención, se paga con los puntos del hotel / cadena
		if ($row['puntos_usuario'] >= $row3['puntos_oferta']){
			return true; //tiene puntos
		}else{
			return false; //No tiene suf. puntos
		}
	}else if ($row3['adq_ret']=='adq'){
		// La oferta es de adquisición, se paga con los puntos de hotelinking
		$sql2= "SELECT puntos AS  puntos_hl
		FROM user_points_hl WHERE id_usuario ='".$userId."' ";
		$rs2 = mysqli_query (conectar(), $sql2);
		$row2 = mysqli_fetch_assoc($rs2);
		liberar ($rs2);
		if ($row2['puntos_hl'] >= $row3['puntos_oferta']){
			return true; //tiene puntos
		}else{
			return false; //No tiene suf. puntos
		}
	}else{
		//Las ofertas que no son de adq/ret no se pueden canjear en la tienda
		return false;
	}
}

function ofertaPublicada($id_oferta){
	// Miramos si el estado de la oferta es 1 (publicada)
	// 0 en edición, 2 pausada no editable, 3 pausada editable (no tiene canjeadas pendientes)
	$sql = "SELECT estado FROM hotel_oferta WHERE id='".$id_oferta."' ";
	////echo '--------------'.$sql;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	if ($row['estado']==1){
		return true;
	}else{
		////echo '<br>----------------Oferta no publicada ';
		return false;
	}
}

// Las ofertas de Aquisisión solo se pueden aquirir una por usuario
function ofertaAdqYaAdquirida($id_oferta, $userId){
	$sql = "SELECT adq_ret FROM hotel_oferta WHERE id='".$id_oferta."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	if ($row['adq_ret'] == 'adq'){
		$sql2 = "SELECT id FROM user_cupones WHERE id_oferta='".$id_oferta."'
		AND id_usuario='".$userId."' ";
		$rs2 = mysqli_query (conectar(), $sql2);
		$n_resultados = mysqli_num_rows($rs2);
		if ($n_resultados==0){
			return true;
		}else{
			////echo '<br>---------------Oferta de Adq ya adquirida ';
			return false;
		}
	}else{
		return true;
	}
}

function puedeAdquirirOferta ($id_oferta, $userId){
	if (quedaCupo($id_oferta) &&
	checkinAnteriormente ($id_oferta,$userId) &&
	requiereChk($id_oferta,$userId) &&
	tienePuntosSuficientes ($id_oferta,$userId) &&
	ofertaPublicada($id_oferta) &&
	ofertaAdqYaAdquirida($id_oferta,$userId)
	){
		return true;
	}else{
		return false;
	}
}

function borrarPuntosOfertaUsuario($id_oferta,$userId){
	$id_hotel = idHotelDeIdOferta($id_oferta);
	if(hotelDeCadena($id_hotel)){
		$sql = "SELECT hotel_oferta.id_hotel, hotel_oferta.puntos AS puntos_oferta,
		hotel_oferta.adq_ret,
		user_points_cadena.puntos AS puntos_usuario
		FROM hotel_oferta
		INNER JOIN cadena_hotel ON cadena_hotel.id_hotel=hotel_oferta.id_hotel
		INNER JOIN user_points_cadena ON user_points_cadena.id_cadena=cadena_hotel.id_cadena
		WHERE user_points_cadena.id_usuario = '".$userId."' AND
		hotel_oferta.id='".$id_oferta."' ";
		////echo '---------------------'.$sql;
	}else{
		$sql = "SELECT hotel_oferta.puntos AS puntos_oferta, hotel_oferta.adq_ret,
		hotel_oferta.id_hotel,
		user_points.puntos AS puntos_usuario
		FROM user_points
		INNER JOIN hotel_oferta ON hotel_oferta.id_hotel=user_points.id_emisor
		WHERE user_points.id_usuario = '".$userId."' AND
		hotel_oferta.id='".$id_oferta."' ";
	}
	////echo '<br />------------------------------'.$sql;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);

	if ($row['adq_ret']=='ret'){
		// La oferta es de retención
		// Se paga con los puntos del hotel
		$puntos = $row['puntos_usuario'] - $row['puntos_oferta'];
		if(hotelDeCadena($id_hotel)){
			$sql3 = "UPDATE user_points_cadena SET puntos='".$puntos."'
			WHERE id_usuario='".$userId."'
			AND id_cadena='".hotelIdCadena($row['id_hotel'])."' ";
			////echo '---------------------'.$sql3;
		}else{
			$sql3 = "UPDATE user_points SET puntos='".$puntos."'
			WHERE id_usuario='".$userId."'
			AND id_emisor='".$row['id_hotel']."' ";
		}
		////echo '<br />------------------------------'.$sql3;
		mysqli_query (conectar(), $sql3);
		$emisor = $row['id_hotel'];
	}else if($row['adq_ret']=='adq'){
		// La oferta es de adquisición
		// Se paga con los puntos de hotelinking
		$sql2 = "SELECT puntos AS puntos_hl
		FROM user_points_hl WHERE id_usuario='".$userId."'  ";
		////echo '<br />------------------------------'.$sql2;
		$rs2 = mysqli_query (conectar(), $sql2);
		$row2 = mysqli_fetch_assoc($rs2);
		liberar($rs2);
		$puntos = $row2['puntos_hl'] - $row['puntos_oferta'];
		$sql3 = "UPDATE user_points_hl SET puntos='".$puntos."'
		WHERE id_usuario='".$userId."'  ";
		////echo '<br />------------------------------'.$sql3;
		mysqli_query (conectar(), $sql3);
		$emisor = 'hl';
	}
	// Registramos los puntos que se quitan
	quitarPuntosReg($userId,$row['puntos_oferta'],10,$id_oferta,$emisor);
}

function adquirirOferta($id_oferta,$userId){
	////echo '---------------AO------';
	if (puedeAdquirirOferta($id_oferta,$userId)){
		$sql = "SELECT id_tipo_oferta FROM hotel_oferta WHERE id='".$id_oferta."' ";
		////echo '---------------------'.$sql;
		$rs = mysqli_query (conectar(), $sql);
		$row = mysqli_fetch_assoc($rs);
		liberar ($rs);
		// Borrar de wishlist
		//borrarDeWishlist ($id_oferta, $userId);
		// Generar voucher id
		$voucherID = generarVoucherId();
		$fecha = dateTimeHoy();
		// Insert tabla cupones
		$sql2 = "INSERT INTO user_cupones
		(voucher, id_usuario, id_oferta, fecha, canjeado, fecha_canj) VALUES
		('".$voucherID."', '".$userId."', '".$id_oferta."', '".$fecha."', ";
		if ($row['id_tipo_oferta']=='chk'){
			// Si la oferta es de checkin, al adquirirla tb se canjea
			$fecha = dateTimeHoy();
			$sql2 .= "1, '".$fecha."')";
			// Incrementar las canjeadas
			$sql3="UPDATE hotel_oferta SET canjeadas=canjeadas+1 WHERE id='".$id_oferta."'";
			////echo '-------------------------'.$sql3;
			mysqli_query (conectar(), $sql3);

		}else{
			$sql2 .= "0, '0000-00-00 00:00:00')";
		}
		mysqli_query (conectar(), $sql2);
		////echo '<br />------------------------------'.$sql2;
		// borrar puntos de la oferta
		borrarPuntosOfertaUsuario($id_oferta, $userId);

		// Marcar oferta como canjeada
		$sql3="UPDATE hotel_oferta SET adquiridas=adquiridas+1 WHERE id='".$id_oferta."' ";
		mysqli_query (conectar(), $sql3);
		// Oferta adquirida correctamente!!
		return true;
	}else{
		// No puede adquirir esta oferta
		return false;
	}
}

function obtenerDatosOfertaEmail($id_Oferta){
	$sql = "SELECT hotel_oferta.id AS id_oferta, id_tipo_oferta, 
	case when oferta_lang.nombre is null 
    then   oferta_en.nombre 
    else oferta_lang.nombre end AS nombre_oferta, puntos, inicio, fin,
	telefonoReservas, emailReserva,
	hoteles.hotelName AS nombre_hotel, hoteles.id AS id_hotel
	FROM hotel_oferta
	LEFT JOIN hoteles ON hoteles.id=hotel_oferta.id_hotel
	LEFT JOIN hotel_oferta_lang as oferta_en   on hotel_oferta.id = oferta_en.id_oferta   and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='". $_SESSION['userLang'] . "'
	WHERE hotel_oferta.id='".$id_Oferta."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return ($row);
}

function obtenerPuntosUsuario($userId, $id_oferta){
	$sql = "SELECT id_hotel, adq_ret FROM hotel_oferta WHERE id='".$id_oferta."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if ($row['adq_ret']=='adq'){ // Oferta de ADQ
	 	$arrayPuntos['tipo']='adq';
		$sql2 = "SELECT puntos FROM user_points_hl WHERE id_usuario='".$userId."' ";
	}else{ // Oferta de RET
		$arrayPuntos['tipo']='ret';
		if(hotelDeCadena($row['id_hotel'])){ // Hotel de cadena
			$id_cadena = hotelIdCadena($row['id_hotel']);
			$sql2 = "SELECT puntos FROM user_points_cadena
			WHERE id_cadena='".$id_cadena."' AND id_usuario='".$userId."' ";
		}else{
			$sql2 = "SELECT puntos FROM user_points
			WHERE id_emisor='".$row['id_hotel']."' AND id_usuario='".$userId."' ";
		}
	}
	$rs2 = mysqli_query (conectar(), $sql2);
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	if($row2['puntos']!=''){
		$arrayPuntos['puntos']=$row2['puntos'];
	}else{
		$arrayPuntos['puntos']=0;
	}
	return $arrayPuntos;
}

if(!empty($_GET['acq']) && (!empty($_SESSION['u_logueado']) || !empty($_GET['user_id'])) ){
	if(!empty($_SESSION['u_logueado'])){
		$userId = $_SESSION['u_logueado'];
	}else{
		$userId = $_GET['user_id'];
	}
	if(!empty($_SESSION['userlang'])){
		$userLang = $_SESSION['userlang'];
	}else{
		$userLang = 'en';
	}
	//echo 'compra desde la APP <br>';
	//$id_oferta = mysqli_real_escape_string(conectar(), $_GET['acq']);
	$id_oferta = $_GET['acq'];
	//echo 'el id de la oferta es ' . $id_oferta . '<br>';
	//echo 'el id del usuario es ' . $userId . '<br>';
	if (adquirirOferta($id_oferta, $userId)){
		//$_SESSION['id_oferta'] = $id_oferta;
		// enviamos mail de confirmación  ---------------
		$arrayDatosUsuarioMail = obtenerDatosUsuarioMail($userId);
		$datosOferta = obtenerDatosOfertaEmail($id_oferta);

		if(!empty($_SESSION['hotel']['brand_id']) && $userLang){
			$websiteUrlReserva = getHotelWebsiteUrl(!empty($_SESSION['hotel']['brand_id']), $userLang);
		}
		
		//$asunto='Oferta adquirida';
		//$cuerpo='Has adquirido';
		//if ($datosOferta['id_tipo_oferta']=='chk'){
			// Si la oferta es de check-in al ser adquirida tambien es canjeada
			//$cuerpo.=' i <strong>canjeado</strong>';
		//}
		//$cuerpo.=' por <strong>'.$datosOferta['puntos'].'</strong> puntos la oferta: <strong>'.$datosOferta['nombre_oferta'].'</strong> en el hotel:<strong>'.$datosOferta['nombre_hotel'].'</strong>';
		$cod_cupon = obtenerCupon($id_oferta);
		$urlHotel = obtenerUrlGUIDHotel($datosOferta['id_hotel']);
		//$cuerpo.=' tu código de cupón es: <strong>'.$cod_cupon.'</strong>';
		// Email
		include_once RUTA_DIR.LANG.$userLang.'/email/acqOffer.php';
		include_once RUTA_DIR.LIB.'plantillasMails/acqOffer.php';
		mandarEmailMandrillPlantilla($arrayDatosUsuarioMail['email'],
		$arrayDatosUsuarioMail['nombre'], $asunto, $cuerpo, 'standard-template');

		$ok = array (true, '2008');
		//Retorno si es del api
		if($ws == 1){
			$ok = 'ok';
			echo $ok;
		}
		//echo 'Si se ha podido comprar';
		// redireccionar a thanks ---------------
		header ('Location: /'.$urlTree['oferta-gracias'].'/?id='.$id_oferta);
	}else{
		// No se puede adquirir oferta
		$ok = array (false, '4006');
		if (!quedaCupo($id_oferta)){
			$ok[] = '4007';
		}
		if (!tienePuntosSuficientes ($id_oferta, $userId)){
			$ok[] = '4008';
		}
		if (!checkinAnteriormente ($id_oferta, $userId)){
			$ok[] = '4009';
		}
		if (!requiereChk($id_oferta, $userId)){
			$ok[] = '4010';
		}
		if (!ofertaPublicada($id_oferta)){
			$ok[] = '4011';
		}
		if (!ofertaAdqYaAdquirida($id_oferta, $userId)){
			$ok[] = '4012';
		}
		//Retorno si es del api
		if($ws == 1){
			$ok = 'ERROR';
			echo $ok;
		}
	}
}else if(!empty($_GET['acq']) && empty($userId) ){
	// Usuario no logueado
	$ok = array (false, '4015');
	// Retorno si es del api
		if($ws == 1){
			$ok = 'Not logged in';
			echo $ok;
		}
}

function idHotelDeIdOferta($id_oferta){
	$sql = "SELECT id_hotel FROM hotel_oferta WHERE id='".$id_oferta."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	return $row['id_hotel'];
}
?>