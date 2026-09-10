<?php
include_once RUTA_DIR.LIB.'obtenerDatosUsuario.php';

// Alerta cuando un usuario con mas de x followers se ha creado una cuenta en HL
function alertaFollowers($id_hotel, $id_usuario, $followers_usuario, $social){
	//Si tiene mas de X followers, avisamos al hotel
	if(!empty($id_hotel)){
		$sql_followers="SELECT email, name, alert_friends FROM hoteles WHERE id='".$id_hotel."' ";
		//echo '<!--'.$sql_followers.'-->';
		$row_followers = mysqli_query (conectar(), $sql_followers);
		$aviso_followers = mysqli_fetch_assoc($row_followers);
		liberar($row_followers);
		if($followers_usuario>=$aviso_followers['alert_friends']){
			// Revisar el lunes !!!!
			if($social=='facebook'){
				$sql = "SELECT nombre FROM user_facebook WHERE id_usuario=".$id_usuario;
			}else if ($social=='twitter'){
				$sql = "SELECT twitter_user AS nombre FROM user_twitter WHERE id_usuario=".$id_usuario;
			}
			$rs = mysqli_query (conectar(), $sql);
			$row = mysqli_fetch_assoc($rs);
			liberar($rs);
			$link_usuario = obtenerUrlGUIDUsario($id_usuario);
			// Mandar email
			include_once LANG.'en/email/aviso-followers.php';
			include_once LIB.'plantillasMails/aviso-followers.php';
			mandarEmailMandrillPlantilla($aviso_followers['email'], $aviso_followers['name'], $asunto, $cuerpo, 'standard-template');
		}
	}
}
?>