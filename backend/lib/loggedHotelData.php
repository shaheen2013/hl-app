<?php
// Librería que contiene los métodos necesario para un hotel/cadena conectado (logged)

//Verificar si el hotel logueado tiene contratado un producto y puede ver la pantalla
//Una pantalla puede pertenecer a varios productos, si el usuario tiene acceso a uno de ellos, ya puede ver la pantalla
function permisosHotel($id_hotel, $controlador)
{
    // $con 			= conectar(1);
    // $id_hotel 		= mysqli_real_escape_string($con, $id_hotel);
    // $controlador 	= mysqli_real_escape_string($con, $controlador);
    // $productos = array();

    // if ($controlador == 'no-permission') {
    //     //A no-permission siempre tenemos acceso aunque LY,MK,RF=0
    //     $access['code'] = '200';
    // } else {
    //     //Obtenemos todos los productos de HL
    //     $sql2 = "SELECT producto FROM products";
    //     $rs2 = mysqli_query(conectar(), $sql2);
    //     $sql = "SELECT ";
    //     while ($row = mysqli_fetch_assoc($rs2)) {
    //         $productos[]=$row['producto'];
    //         //Generamos dinamicamente la select con todos los productos de HL
    //         $sql .= "(SELECT ".$row['producto']." FROM controladores WHERE controlador='".$controlador."' ) 
	// 		AS ".$row['producto']."Contr , ";
    //     }
    //     //echo $sql;
    //     liberar($rs2);
    //     //Quitamos última coma
    //     $sql = substr($sql, 0, -2);

    //     global $log; $log->debug("SQL", [$sql]);
    //     $rs = mysqli_query($con, $sql);
    //     if ($rs) {
    //         $row = mysqli_fetch_assoc($rs);
    //     }
    //     liberar($rs);
    //     desconectar($con);
    //     $access['code'] = '404';//Inicializamos sin acceso
    //     foreach ($productos as $producto) {
    //         if (($row[$producto.'Contr']==1 && $_SESSION['permisos'][$producto]==1)) {
    //             $access['code'] = '200';//Tiene acceso a la pantalla
    //             break;
    //         }
    //     }
    // }
    $access['code'] = '200';
    return $access;
}
