<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function validarLoginUsaurio($email, $pass){
	$array_login = array();
	$sql="SELECT id, pass, verificado FROM users WHERE email = '".$email."'";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	
	if ($n_resultados == 0 ){
		// Email incorrecto
		$array_login[0] = '4029';
	}else{
		//Email correcto 
		$row = mysqli_fetch_array($rs);
		if (sha1($pass) == $row['pass']){
			if ($row['verificado']=='1'){
				//Todo OK -> podemos loguear
				$array_login[0] = '200';
			}else{
				// Cuenta no activada
				$array_login[0] = '4030'; 
			}
		}else{
			// Contraseña incorrecta
			$array_login[0] = '4029'; 
		}
	}
	return $array_login;
}

function obtenerIdUsuario($email){
	$sql="SELECT id FROM users WHERE email = '".$email."'";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_array($rs);
	liberar($rs);
	return $row['id'];
}

function comprobarUsuarioDB($email){
	
	$sql = "SELECT * FROM users WHERE email = '".$email."'";
	$row = mysqli_query (conectar(), $sql) or die("ERROR Select usuario login".mysqli_error());
	
	$n_resultados = mysqli_num_rows($row);
	
	if ($n_resultados == 0 ){
		return false;
	}else{
		return true;
	}
	liberar($row);
}

function guardarIntentoLogin($email){
	include_once LIB.'fecha.php';
	$fechaHora = dateTimeHoy();
	
	$sql = "INSERT INTO user_login_attempts (email, attempts, time) 
	VALUES ('".$email."', '1', '".$fechaHora."')
	ON DUPLICATE KEY UPDATE attempts=attempts+1, time='".$fechaHora."'";
	mysqli_query (conectar(), $sql);
}

function maximoIntentos($email){
	include_once LIB.'fecha.php';
	$fechaHora = dateTimeHoy();
	
	$sql = "SELECT attempts, time FROM user_login_attempts WHERE email='".$email."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);

	// segundos desde el último intento
	$segundosUltimoIntento = strtotime($fechaHora) - strtotime($row['time']);

	if($row['attempts']>='10' && $segundosUltimoIntento<='60'){
		//maximo intentos 10 y debe esperar 60s
		//cuenta bloqueada
		return true;
	}else if($row['attempts']>='10' && $segundosUltimoIntento>'60'){
		//maximo intentos 10 pero ya ha esperado los 60s
		//desbloquear cuenta
		$sql2 = "DELETE FROM user_login_attempts WHERE email='".$email."' ";
		mysqli_query (conectar(), $sql2);
		return false;
	}else{
		return false;
	}
}

function recoveryPass ($email){
	include_once LIB.'generarPass.php';
	
	$sql = "SELECT id, email, nombre AS name FROM users WHERE email='".$email."'";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	// Generar nuevo pass
	$row['nuevoPass'] = generaPass();
	// Hashear nuevo pass
	$passHash = sha1($row['nuevoPass']);
	// Update BD con el nuevo pass hasheado
	$sql2 = "UPDATE users SET pass='".$passHash."' WHERE email ='".$row['email']."'";
	mysqli_query (conectar(), $sql2);
	return $row;
}
?>