<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'permisosUsuario.php';	

include_once LIB.'sanitize.php';
include_once LIB.'acqOffer.php';

function obtenerArrayWishlist($id_usuario){
	$arrayWishlist = array();
	$sql = "SELECT user_wishlist.id_oferta, user_wishlist.fecha, 
	user_wishlist.id AS id_wishlist,
	case when oferta_lang.nombre is null 
    then   oferta_en.nombre 
    else oferta_lang.nombre end AS nombre,
	hotel_oferta.puntos , hotel_oferta.inicio, hotel_oferta.fin,
	hotel_oferta.img, hotel_oferta.descuento, hotel_oferta.requerimientos,
	hotel_oferta.adq_ret, hotel_oferta.id, hotel_oferta.id_tipo_oferta,
	categoria_oferta.categoria_es AS categoria, categoria_oferta.id_categoria_oferta,
	hoteles.hotelName, hoteles.estrellas, hoteles.rating, hoteles.city AS city_name, 
	hoteles.id AS id_hotel,
	IFNULL (hoteles.logo,0) AS logo
	FROM user_wishlist 
	LEFT JOIN hotel_oferta ON user_wishlist.id_oferta=hotel_oferta.id
	LEFT JOIN hotel_oferta_lang as oferta_en   on hotel_oferta.id = oferta_en.id_oferta   and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='". $_SESSION['userNavLang'] . "'
	LEFT JOIN categoria_oferta ON hotel_oferta.id_categoria=categoria_oferta.id_categoria_oferta
	LEFT JOIN hoteles ON hotel_oferta.id_hotel=hoteles.id
	WHERE user_wishlist.id_usuario='".$id_usuario."' ";
	//echo '----------------------'.$sql;
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if($key == 'inicio' || $key == 'fin'){
				$arrayWishlist[$i][$key] = girarFecha($valor);
			}else{
				$arrayWishlist[$i][$key] = $valor;
			}
		}
		if(puedeAdquirirOferta($row['id_oferta'], $_SESSION['u_logueado'])){
			$arrayWishlist[$i]['puedeAdquirir']=1;
		}else{
			$arrayWishlist[$i]['puedeAdquirir']=0;
		}
		$nombre_sanitizado = string_sanitize($row['nombre']);
		$arrayWishlist[$i]['url']= $nombre_sanitizado;
		$i++;
	}
	liberar($rs);
	return ($arrayWishlist);
}

// Borra un elemento de wishlist 
function borrarWishlist($id_oferta){
	$sql = "DELETE FROM user_wishlist WHERE id='".$id_oferta."' 
	AND id_usuario='".$_SESSION['u_logueado']."' ";
	mysqli_query (conectar(), $sql);
}
?>