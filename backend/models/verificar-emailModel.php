<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB . 'hotelinking_emails.php';

function tokenOK($token, $email)
{	
	$token = mysqli_real_escape_string(conectar(), $token);
	$email = mysqli_real_escape_string(conectar(), $email);
	
	$sql="SELECT COUNT(id) AS n FROM verificar_email 
	WHERE token='".$token."' AND email='".$email."' ";
	$rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if($row['n']==1){
		return true;
	}else{
		return false;
	}
}

function verificarCuentaHotel($email, $token)
{
	$token = mysqli_real_escape_string(conectar(), $token);
	$email = mysqli_real_escape_string(conectar(), $email);
	
	$sql = "UPDATE hoteles SET verificado='1' WHERE email='".$email."' ";
	mysqli_query (conectar(), $sql) or die(mysqli_error());
	borrarEmailVerificar($token);


}

function borrarEmailVerificar($token)
{
	$token = mysqli_real_escape_string(conectar(), $token);
	
	$sql = "DELETE FROM verificar_email WHERE token='".$token."' ";
	mysqli_query (conectar(), $sql) or die(mysqli_error());
}

// FX para modificar el email ya verificado
// id: id del hotel/cadena
// email: email nuevo que sustituye al que habia
// tipo: hoteles (hotel) o cadena(cadena)
// tipo_email: email (email de la cuenta (login) ), email_envio (email para envio de email)
function modificarEmailVerificado($id, $email,  $tipo, $tipo_email='')
{
	$id = mysqli_real_escape_string(conectar(), $id);
	$email = mysqli_real_escape_string(conectar(), $email);
	$tipo = mysqli_real_escape_string(conectar(), $tipo);
	$tipo_email = mysqli_real_escape_string(conectar(), $tipo_email);
	
	// Inicializamos el array de resultado
	$result = array('code' => '0');
	
	if( $tipo_email != '' )
	{
		$sql = "UPDATE ".$tipo." SET ".$tipo_email."='".$email."' ";
		if( $tipo == 'hoteles' )
		{
			$sql .= " , verificado='1' ";
		}
		$sql .= " WHERE id='".$id."'; ";
		mysqli_query (conectar(), $sql) or die(mysqli_error());	
		$result['code']='200';

		//Borrar cache
		if($tipo == 'hoteles')
    		deleteCacheByTag('hotel_profile_' . $id);//Borramos de cache al verificar @

		if($tipo == 'cadena' && $tipo_email == 'email_envio' )
    		deleteCacheByTag('cadena_email_' . $id);//Borramos de cache al verificar @

	}else{
		$result['code']='404';
	}
	return $result;
}

function obtenerIdStaff($email)
{
	$email = mysqli_real_escape_string(conectar(), $email);
	
	$sql = "SELECT id FROM hotel_staff WHERE email='".$email."' AND deleted=0";
	$rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	
	return $row['id'];
}

function verificarStaff($id)
{
	$sql = "UPDATE hotel_staff SET activo=1 WHERE id='".$id."' ";
	mysqli_query (conectar(), $sql) or die(mysqli_error());
	$result['code']='200';
	return $result;
}
?>