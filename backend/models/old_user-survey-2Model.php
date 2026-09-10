<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'permisosUsuario.php';
//include LIB.'/fecha.php';

function actualizarRatingHotel($id_hotel, $rating){
	$sql = "SELECT ROUND(SUM(rating),1) AS sumaRatings, COUNT(rating) AS nRatings
	FROM user_encuestas WHERE id_hotel='".$id_hotel."' AND DONE='1'";
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$rating_total = 0;
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	$rating_total = $row['sumaRatings']+$rating;
	$n_total_ratings = $row['nRatings']+1;
	if ($n_total_ratings !=0){
		$mediaRatings = round($rating_total / $n_total_ratings, 1);
	}else{
		$mediaRatings = 0;
	}
	$sql2 = "UPDATE hoteles SET rating='".$mediaRatings."' 
	WHERE id='".$id_hotel."' ";
	//echo '-------------'.$sql2;
	mysqli_query (conectar(), $sql2);
}

function guardarEncuesta ($id_encuesta,$id_usuario,$rating,$positiveComment,$negativeComment,$sexo,$edad){	
	$sql2 = "SELECT id FROM user_encuestas
	WHERE id='".$id_encuesta."' AND id_usuario='".$id_usuario."' AND done='0'";
	$rs2 = mysqli_query (conectar(), $sql2);
	$n_resultados = mysqli_num_rows($rs2);
	liberar ($rs2);

	if ($n_resultados == 1){ 
		$fecha = dateTimeHoy();
		$sql = "UPDATE user_encuestas SET rating='".$rating."', 
		positiveComment='".$positiveComment."', negativeComment='".$negativeComment."',
		sexo='".$sexo."', edad='".$edad."', fecha='".$fecha."', done='1'
		WHERE id='".$id_encuesta."' AND id_usuario='".$id_usuario."' AND done='0'";
		mysqli_query (conectar(), $sql);
		return true;
	}else{ // UPDATE no OK (No hay filas afectadas) encuesta ya hecha o de otro usuario
		return 0;
	}
}

function obtenerDatosUsuario($id_usuario){
	$sql = "SELECT fecha_nacimiento, sexo FROM users WHERE id='".$_SESSION['u_logueado']."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return ($row);
}

function obtenerNombreHotel($id_encuesta){
	$sql = "SELECT hotelName AS nombre_hotel FROM hoteles 
	INNER JOIN user_encuestas ON user_encuestas.id_hotel=hoteles.id
	WHERE user_encuestas.id='".$id_encuesta."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return ($row);
}

function obtenerIdHotel($id_encuesta){
	$sql = "SELECT id_hotel FROM user_encuestas WHERE id='".$id_encuesta."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return ($row['id_hotel']);
}

function encuestaHecha($id_encuesta){
	$sql = "SELECT done FROM user_encuestas WHERE id=".$id_encuesta;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	if($row['done']==1){
		return true;
	}else{
		return false;
	}
}

function encuestaSinShare($id_encuesta){
	$sql = "SELECT COUNT(user_shares.id) AS n 
	FROM user_encuestas 
	LEFT JOIN user_shares ON user_shares.id_encuesta=user_encuestas.id
	WHERE user_encuestas.id=".$id_encuesta;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	if($row['n']>1){
		return false;
	}else{
		return true;
	}
}
?>