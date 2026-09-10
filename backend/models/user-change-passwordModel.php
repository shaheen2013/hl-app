<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function oldPassOK($id_usuario, $oldPass){
	$sql = "SELECT pass FROM users WHERE id='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if(sha1($oldPass) == $row['pass'] || ($row['pass']=='' && $oldPass=='')){
		return true;	
	}else{
		return false;
	}
}

//Guardamos el nuevo pass
function cambiarPassUsuario($id_usuario, $pass){
	$passHash = sha1($pass);
	$sql = "UPDATE users SET pass='".$passHash."' WHERE id='".$id_usuario."' ";
	mysqli_query (conectar(), $sql);
}

//Miramos si tiene pass anterior 
function tienePassAnterior($id_usuario){
	$sql = "SELECT pass FROM users WHERE id='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if($row['pass']!=''){
		return true;
	}else{
		return false;
	}
}
?>