<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

	//Código en función del email
	function getEmailCode($email){
		$email = mysqli_real_escape_string(conectar(), $email);

		//Vemos si el usuario ya existe
		$query = "SELECT ref_code FROM invitaciones_hotel_pendientes WHERE email ='".$email."'";
		$rs = mysqli_query(conectar(), $query);
		$results = mysqli_fetch_array($rs,MYSQLI_ASSOC);
		liberar($rs);
		return $results;
	}

	//Unsub un usuario
	function unsubUser($email, $code){
		$email = mysqli_real_escape_string(conectar(), $email);
		$code = mysqli_real_escape_string(conectar(), $code);

		//revisamos el usuario y vemos si coincide
		$query = "SELECT id FROM invitaciones_hotel_pendientes WHERE email ='".$email."' AND ref_code ='".$code."'";
		$rs = mysqli_query(conectar(), $query);
		$rows = mysqli_num_rows($rs);

		if($rows > 0){
			$results = mysqli_fetch_array($rs,MYSQLI_ASSOC);
			$query2 = ("UPDATE invitaciones_hotel_pendientes SET unsub = '1' WHERE id ='".$results['id']."'");
			$rs2 = mysqli_query(conectar(), $query2);
			liberar($rs);
			return true;
		}else{
			return false;
		}	
	}

 ?>