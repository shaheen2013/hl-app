<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function loginAdmin($user, $password){
	//Escape de strings
	$user = mysqli_real_escape_string(conectar() ,$user);
	$password = mysqli_real_escape_string(conectar() ,$password);
	//hash the password
	$secretKey = 'winhotelsolution';
	$passwordFromPost = (sha1($secretKey.$password));
	//Get password from database and compare
	//Get
	$sql = "SELECT * FROM private_login WHERE username = '$user'";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	$hashedPasswordFromDB = $row['password'];
	//Compare
	if ($passwordFromPost === $hashedPasswordFromDB) {
	    return true;
	} else {
	    return false;
	}
	}
 ?>
