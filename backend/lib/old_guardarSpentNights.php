<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//totalSpent y totalNights inicial de las invitaciones, 
//puede modificarse hasta que el usuario acepta la invitación
function guardarSpentNights($id_hotel, $id_usuario, $totalSpent, $totalNights)
{
	$id_hotel = mysqli_real_escape_string(conectar(), $id_hotel);
	$id_usuario = mysqli_real_escape_string(conectar(), $id_usuario);
	$totalSpent = mysqli_real_escape_string(conectar(), $totalSpent);
	$totalNights = mysqli_real_escape_string(conectar(), $totalNights);
	
	$sql2 = "SELECT COUNT(id) AS n FROM user_hotel_gasto_noches_inicial
	WHERE id_hotel='".$id_hotel."' AND id_usuario='".$id_usuario."' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	if($row2['n']=='0')
	{
		$sql = "INSERT INTO user_hotel_gasto_noches_inicial 
		(id_hotel, id_usuario, gasto_total, total_noches) VALUES
		('".$id_hotel."', '".$id_usuario."', '".$totalSpent."', '".$totalNights."') ";
	}else{
		$sql = "UPDATE user_hotel_gasto_noches_inicial SET gasto_total='".$totalSpent."', 
		total_noches='".$totalNights."'
		WHERE id_hotel='".$id_hotel."' AND id_usuario='".$id_usuario."' ";
	}
	mysqli_query (conectar(), $sql);
}

function agregarNochesYSpent($id_usuario)
{
	//Agregar total_noches y total_spent 
	$sql = "SELECT id_hotel, total_noches, gasto_total
	FROM user_hotel_gasto_noches_inicial
	WHERE id_usuario='".$id_usuario."'";
	//echo '<br>(2)'.$sql.'<br>';
	$rs = mysqli_query (conectar(), $sql);
	while($row = mysqli_fetch_assoc($rs)){
		//echo $row['id_hotel'].' '.$row['total_noches'].' '.$row['gasto_total'];
		activarSpentNights($row['id_hotel'], $id_usuario, $row['gasto_total'], $row['total_noches']);
	}
	liberar($rs);
}

//Cuando el usuario activa su cuenta se le activan estos datos
function activarSpentNights($id_hotel, $id_usuario, $gasto_total, $total_noches)
{
	$sql = "UPDATE user_hotels SET total_noches=total_noches+'".$total_noches."', 
	gasto_total=gasto_total+'".$gasto_total."'
	WHERE id_usuario='".$id_usuario."' AND id_hotel='".$id_hotel."' ";
	//echo '<br>'.$sql;
	mysqli_query (conectar(), $sql);
}
?>