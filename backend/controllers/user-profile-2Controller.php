<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
userLanding ();// Si no esta logueado lo manda a la landing

if (!empty($_POST['save'])){
	//Estrellas
	$minEstrellas = mysqli_real_escape_string(conectar(), $_POST['minEstrellas']);
	$maxEstrellas = mysqli_real_escape_string(conectar(), $_POST['maxEstrellas']);
	//Rango precios
	$minRango = mysqli_real_escape_string(conectar(), $_POST['minRango']);
	$maxRango = mysqli_real_escape_string(conectar(), $_POST['maxRango']);
	//Tipos Hotel
	if (isset ($_POST['hotelType'])){
		insertarTiposHotelUsuario($_SESSION['u_logueado'], $_POST['hotelType']);
	}else{
		borrarTiposHotelUsuario($_SESSION['u_logueado']);
	}
	
	//Hotel decoraciones 
	if (isset ($_POST['hotelDecoration'])){
		insertarDecoracionesUsuario($_SESSION['u_logueado'], $_POST['hotelDecoration']);
	}else{
		borrarDecoracionesUsuario($_SESSION['u_logueado']);
	}
	
	//Extras en habitaciones
	if (isset ($_POST['roomExtra'])){
		insertarExtrasUsuario($_SESSION['u_logueado'], $_POST['roomExtra']);
	}else{
		eliminarExtrasUsuario($_SESSION['u_logueado']);
	}
	
	//Tipos de habitaciones
	if (isset ($_POST['roomType'])){
		insertarTiposHabUsuario($_SESSION['u_logueado'], $_POST['roomType']);
	}else{
		borrarTiposHabUsuario($_SESSION['u_logueado']);
	}

	//Servicios hotel
	if (isset ($_POST['hotelServices'])){
		insertarServiciosUsuario($_SESSION['u_logueado'], $_POST['hotelServices']);
	}else{
		borrarServiciosUsuario($_SESSION['u_logueado']);
	}

	insertarDatosDB2 (
	//estrellas
	$minEstrellas, $maxEstrellas, 
	//rango precios
	$minRango, $maxRango 
	);
	$ok = array (true, '2007');
}

$arrayUserProfile2 = obtenerUserProfile2($_SESSION['u_logueado']);
$arrayUserExtras = obtenerDatosUsuarioExtras($_SESSION['u_logueado']);
$arrayUserTiposHab = obtenerDatosUsuarioTiposHab($_SESSION['u_logueado']);
$arrayUserServicios = obtenerDatosUsuarioServicios($_SESSION['u_logueado']);
$arrayUserTiposHotel = obtenerDatosUsuarioTiposHotel($_SESSION['u_logueado']);
$arrayUserDecoraciones = obtenerDatosUsuarioDecoraciones($_SESSION['u_logueado']);

//Total servicios / tipos hab. / extras de BD
$arrayTiposHotel = obtenerTiposHotel();
$arrayTiposDecoracion = obtenerTiposDecoracion();
$arrayExtrasHotel = obtenerExtrasHotel();
$arrayTiposHabHotel = obtenerTiposHabHotel();
$arrayServiciosHotel = obtenerServiciosHotel();

//rellenamos el array con 1 y 0 para marcar los checkbox de extras habitacion hotel
$nUserExtras = count($arrayUserExtras);//extras que tiene el usuario
$nExtrasHotel = count($arrayExtrasHotel);//total extras que puede tener un hotel
$i=0;
while($i<$nExtrasHotel){
	$t=0;
	if($nUserExtras==0){
		$arrayUserExtras10[$arrayExtrasHotel[$i]['id_extra']]=0;
	}else{
		while($t<$nUserExtras){
			if ($arrayExtrasHotel[$i]['id_extra']==$arrayUserExtras[$t]['id_extra']){
				$arrayUserExtras10[$arrayExtrasHotel[$i]['id_extra']]=1;
				break;
			}else{
				$arrayUserExtras10[$arrayExtrasHotel[$i]['id_extra']]=0;
			}
			$t++;
		}
	}
	$i++;
}

//rellenamos el array con 1 y 0 para marcar los checkbox de tipos de habitacion hotel
$nUserTiposHab = count($arrayUserTiposHab);//extras que tiene el usuario 
$nTiposHabHotel = count($arrayTiposHabHotel);//total extras que puede tener un hotel
$i=0;
while($i<$nTiposHabHotel){
	$t=0;
	if($nUserTiposHab==0){
		$arrayUserTiposHab10[$arrayTiposHabHotel[$i]['id_tipo_hab']]=0;
	}else{
		while($t<$nUserTiposHab){
			if ($arrayTiposHabHotel[$i]['id_tipo_hab']==$arrayUserTiposHab[$t]['id_tipo_hab']){
				$arrayUserTiposHab10[$arrayTiposHabHotel[$i]['id_tipo_hab']]=1;
				break;
			}else{
				$arrayUserTiposHab10[$arrayTiposHabHotel[$i]['id_tipo_hab']]=0;
			}
			$t++;
		}
	}
	$i++;
}

//rellenamos el array con 1 y 0 para marcar los checkbox de servicios hotel
$nUserServicios = count($arrayUserServicios);//extras que tiene el hotel
$nServiciosHotel = count($arrayServiciosHotel);//total extras que puede tener un hotel
$i=0;
while($i<$nServiciosHotel){
	$t=0;
	if($nUserServicios==0){
		$arrayUserServicios10[$arrayServiciosHotel[$i]['id_servicio']]=0;
	}else{
		while($t<$nUserServicios){
			if ($arrayServiciosHotel[$i]['id_servicio']==$arrayUserServicios[$t]['id_servicio']){
				$arrayUserServicios10[$arrayServiciosHotel[$i]['id_servicio']]=1;
				break;
			}else{
				$arrayUserServicios10[$arrayServiciosHotel[$i]['id_servicio']]=0;
			}
			$t++;
		}
	}
	$i++;
}

//rellenamos el array con 1 y 0 para marcar los checkbox de tipos hotel
$nUserTiposHotel = count($arrayUserTiposHotel);//extras que tiene el hotel
$nTiposHotel = count($arrayTiposHotel);//total tipos de hotel
$i=0;
while($i<$nTiposHotel){
	$t=0;
	if($nUserTiposHotel == 0){
		$arrayUserTiposHotel10[$arrayTiposHotel[$i]['id_tipo_hotel']]=0;
	}else{
		while($t<$nUserTiposHotel){
			if ($arrayTiposHotel[$i]['id_tipo_hotel']==$arrayUserTiposHotel[$t]['id_tipo_hotel']){
				$arrayUserTiposHotel10[$arrayTiposHotel[$i]['id_tipo_hotel']]=1;
				break;
			}else{
				$arrayUserTiposHotel10[$arrayTiposHotel[$i]['id_tipo_hotel']]=0;
			}
			$t++;
		}
	}
	$i++;
}

//rellenamos el array con 1 y 0 para marcar los checkbox de decoraciones hotel
$nUserDecoraciones = count($arrayUserDecoraciones);//extras que tiene el hotel
$nTiposDecoracion = count($arrayTiposDecoracion);//total decoracionese hotel
$i=0;
while($i<$nTiposDecoracion){
	$t=0;
	if ($nUserDecoraciones == 0){
		$arrayUserDecoraciones10[$arrayTiposDecoracion[$i]['id_decoracion']]=0;
	}else{
		while($t<$nUserDecoraciones){
			if ($arrayTiposDecoracion[$i]['id_decoracion']==$arrayUserDecoraciones[$t]['id_decoracion']){
				$arrayUserDecoraciones10[$arrayTiposDecoracion[$i]['id_decoracion']]=1;
				break;
			}else{
				$arrayUserDecoraciones10[$arrayTiposDecoracion[$i]['id_decoracion']]=0;
			}
			$t++;
		}
	}
	$i++;
}

/*echo '<pre>';
print_r($arrayUserProfile2);
echo '</pre>';

echo '<pre>';
print_r($arrayUserExtras);
echo '</pre>';

echo '<pre>';
print_r($arrayUserTiposHab);
echo '</pre>';

echo '<pre>';
print_r($arrayUserServicios);
echo '</pre>';

echo '<pre>';
print_r($arrayUserTiposHotel);
echo '</pre>';

echo '<pre>';
print_r($arrayUserDecoraciones);
echo '</pre>';

echo '<pre>';
print_r($arrayUserExtras10);
echo '</pre>';
/*
echo '<pre>';
print_r($arrayUserTiposHab10);
echo '</pre>';

echo '<pre>';
print_r($arrayUserServicios10);
echo '</pre>';

echo '<pre>';
print_r($arrayUserTiposHotel10);
echo '</pre>';

echo '<pre>';
print_r($arrayUserDecoraciones10);
echo '</pre>';*/

/*echo '------------------';
echo '<pre>';
print_r($arrayTiposHotel);
echo '</pre>';
echo '<pre>';
print_r($arrayTiposDecoracion);
echo '</pre>';*/
/*echo '<pre>';
echo '--arrayExtrasHotel--';
print_r($arrayExtrasHotel);
echo '</pre>';
echo '<pre>';
print_r($arrayTiposHabHotel);
echo '</pre>';
echo '<pre>';
print_r($arrayServiciosHotel);
echo '</pre>';*/

?>