<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'fecha.php';

function existe($email, $tipo)
{
	$email = mysqli_real_escape_string(conectar(), $email);
	$tipo = mysqli_real_escape_string(conectar(), $tipo);
	
	// Miramos si ese email ya existe en la tabla hotel/cadena/staff/usuario
	if($tipo=='hot'){
		//hotel
		$sql = "SELECT
		(SELECT COUNT(id) FROM hoteles WHERE email='".$email."' ) AS nHot, 
		(SELECT COUNT(id) FROM cadena WHERE email='".$email."'  ) AS nCad,
		(SELECT COUNT(id) FROM hotel_staff WHERE email='".$email."' ) AS nStaff 
		";
		$rs = mysqli_query (conectar(), $sql);
		$row = mysqli_fetch_assoc($rs);
		liberar($rs);
		if ($row['nHot']==0 && $row['nCad']==0 && $row['nStaff']==0){
			return false;
		}else{
			return true;
		}

	}else if($tipo=='us'){
		$sql = "SELECT id FROM users WHERE email='".$email."' AND verificado=1";
		$rs = mysqli_query (conectar(), $sql);
		$row = mysqli_fetch_assoc($rs);
		liberar($rs);
		if($row['id']==''){
			return false;
		}else{
			return true;
		}
	}
}

function borrarVerifAnteriores($email, $tipo)
{
	$email = mysqli_real_escape_string(conectar(), $email);
	$tipo = mysqli_real_escape_string(conectar(), $tipo);
	
	$sql = "DELETE FROM verificar_email WHERE email='".$email."' AND tipo='".$tipo."' ";
	mysqli_query (conectar(), $sql);
}



function borrarVerifAnteriores2($id, $tipo, $tipo_email)
{
	$id = mysqli_real_escape_string(conectar(), $id);
	$tipo = mysqli_real_escape_string(conectar(), $tipo);
	
	$sql = "DELETE FROM verificar_email 
	WHERE id_tipo='".$id."' AND tipo='".$tipo."' AND tipo_email='".$tipo_email."' ";
	mysqli_query (conectar(), $sql);
}

//tipo = hot/us. Hotel o usuario
function registarEmailVerificar($email, $token, $tipo, $id_tipo=0, $tipo_email=0)
{
	$email = mysqli_real_escape_string(conectar(), $email);
	$token = mysqli_real_escape_string(conectar(), $token);
	$tipo = mysqli_real_escape_string(conectar(), $tipo);
	
	if(!existe($email, $tipo)){
		borrarVerifAnteriores2($id_tipo, $tipo, $tipo_email);
		$fecha = dateTimeHoy();
		$sql = "INSERT INTO verificar_email (email, token, fecha, tipo, id_tipo, tipo_email) 
		VALUES 
		('".$email."', '".$token."', '".$fecha."', '".$tipo."', '".$id_tipo."', '".$tipo_email."')";
		mysqli_query (conectar(), $sql);
		return true;
	}else{
		return false;
	}
}

//Verificar codigo de verificacion para usuario
function verifCodVerifUs($codVerif, $token)
{
	$codVerif = mysqli_real_escape_string(conectar(), $codVerif);
	$token = mysqli_real_escape_string(conectar(), $token);
	
	$sql =  "SELECT COUNT(invitaciones_users.id) AS n
	FROM invitaciones_users
	INNER JOIN verificar_email ON verificar_email.email=invitaciones_users.email
	WHERE invitaciones_users.token='".$token."' 
	AND verificar_email.token='".$codVerif."' 
	AND verificar_email.tipo='us'";
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if ($row['n'] == '0'){
		return false;
	}else{
		return true;
	}
}
?>