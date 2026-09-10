<?php
// Pantalla: CE (chain-email)

// FX para obtener los datos del hotel de esta pantalla
// email: email del login de la cadena
// email_envio: email desde el que se realizan todos los envios de los hoteles de la cadena
// password: pass de login de la cadena. Esta en SHA-1.
function obtenerDatosCadenaCE($chain_id)
{
	$con = conectar(1);
	$chain_id = mysqli_real_escape_string($con, $chain_id);
	
	$sql = "SELECT email, email_envio, password FROM cadena WHERE id='$chain_id' ";
	return lectura($sql, $con);
}

function guardarPassCadena($id_cadena, $pass)
{
	$id_cadena = mysqli_real_escape_string(conectar(), $id_cadena);
	$pass = mysqli_real_escape_string(conectar(), $pass);
	
	//Insert
	$sql = "UPDATE cadena SET password='".$pass."' WHERE id='".$id_cadena."' ";
	mysqli_query (conectar(), $sql) or die(mysqli_error());
}

// Fx para mirar si el email de cadena ya existe (tampoco puede ser staff)
// email: email a comprobar su existencia
// tipo_email: email (de cuenta (login)), email_envio (email para envio de emails)
function emailCadenaExiste($email, $tipo_email)
{
	$con = conectar(1);
	$email = mysqli_real_escape_string($con, $email);
	$tipo_email = mysqli_real_escape_string($con, $tipo_email);
	
	//$sql = "SELECT COUNT(id) AS n FROM cadena WHERE ".$tipo_email."='".$email."' ";
	
	$sql = "SELECT
	(SELECT COUNT(cadena.id) AS n FROM cadena WHERE ".$tipo_email."='".$email."') AS cadena,
	(SELECT COUNT(hotel_staff.id) AS n FROM hotel_staff WHERE email='".$email."') AS staff ";
	
	$row = lectura($sql, $con);
	if( $row['cadena']=='1' || $row['staff']=='1' ){
		return true;
	}else{
		return false;
	}
}

/**
 * Update chain email for all hotels in chain
 * @param $hotelsArray
 * @return boolean
 */
function updateChainSendingEmail($chain_id, $email)
{
    $con = conectar();
	$email = mysqli_real_escape_string($con, $email);
	
	//update chain communication email
	$sql = "UPDATE cadena SET email_envio='$email' WHERE id = '$chain_id'";
	escritura($sql, $con);
	//TODO: delete hotel cache in HL_mail_platform
}
?>