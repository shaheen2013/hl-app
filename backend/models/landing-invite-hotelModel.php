<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'fecha.php';
function insertaEmail($email){
	$fecha = dateTimeHoy();
	$email = mysqli_real_escape_string(conectar(), $email);

	//Vemos si el usuario ya existe
	$query = "SELECT id, email, invite_completed FROM invitaciones_hotel_pendientes WHERE email ='".$email."'";
	$rs = mysqli_query(conectar(), $query);
	$results = mysqli_fetch_array($rs,MYSQLI_ASSOC);
	$rows = mysqli_num_rows($rs);
	liberar($rs);

	//si existe, vamos a ver si tiene toda la información rellenada
	//De lo contrario, le dejamos que siga el proceso de registro
	if($rows > 0){
		if($results['invite_completed'] === "0"){
			//Existe, pero incompleto
			//guardamos el ID para que acabe de rellenar el formulario
			$id = $results['id'];
			$_SESSION['id-invite'] = $id;
			return true;
		}else{
			//Existe y tiene todos los campos rellenados, lo redirigimos a la home y le avisamos
			return false;
		}
	//No existe, introducimos el email, guardamos el id para el resto de campos
	}else{
		$sql = "INSERT INTO invitaciones_hotel_pendientes (email,  fecha) VALUES ('".$email."', '".$fecha."')";
		$conexion = conectar();
		mysqli_query ($conexion, $sql);
		$id = mysqli_insert_id($conexion);
		$_SESSION['id-invite'] = $id;
	}
	return true;
	desconectar();
}

?>