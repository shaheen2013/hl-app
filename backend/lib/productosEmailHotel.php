<?php
/*
*   Clase abstracta productoHotelEnvioEmail para productos de hotel con envio de email a usuarios.
*   
*   @producto (string) nombre del producto del hotel
*   @id_hotel_hl (int) id del hotel en hotelinking 
*   @id_hotel_emails (int) id del hotel en la plataforma de emails
*   @id_usuario_hl (int) id del usuario en hotelinking 
*   @id_usuario_emails (int) id del usuario en la plataforma de emails
*   
*/
abstract class productoHotelEnvioEmail
{
	protected $producto; 
	protected $id_hotel_hl;
	protected $id_hotel_emails;
	protected $id_usuario_hl;
	protected $id_usuario_emails;

	public function __construct($producto, $id_hotel_hl, $id_hotel_emails, $id_usuario_hl, $id_usuario_emails)
	{
		$this->producto         	= $producto;
		$this->id_hotel_hl      	= $id_hotel_hl;
		$this->id_hotel_emails  	= $id_hotel_emails;
		$this->id_usuario_hl    	= $id_usuario_hl; 
		$this->id_usuario_emails 	= $id_usuario_emails;
	}
}

/*
*   Clase abstracta productoHotelOpinionUsuarioEnvioEmail 
*/
abstract class productoHotelOpinionUsuarioEnvioEmail extends productoHotelEnvioEmail
{
protected $diasEnvio;   // días (int) que debemos esperar para mandar el email del producto al usuario.
protected $sendDate;    // fecha (date) en la que se mandará el email

public function __construct($producto, $id_hotel_hl, $id_hotel_emails, $id_usuario_hl, $id_usuario_emails)
{
	parent::__construct($producto, $id_hotel_hl, $id_hotel_emails, $id_usuario_hl, $id_usuario_emails);
}

/*
*   FX para obtener los días que debemos esperar para mandar el email del producto al usuario. 
*/
public function getDiasEnvioEmailProducto()
{
	$producto = $this->producto;
	$id_hotel = $this->id_hotel_hl;

//Get from cache diasEnvioReview / diasEnvioSatisfaction
	$cacheName = 'diasEnvio'.ucfirst($producto).'Hotel_' . $id_hotel;
	$cache = getFromCache($cacheName);

	if(!$cache) 
	{   
		$sql2 = "SELECT diasEnvio FROM hotel_$producto WHERE id_hotel=$id_hotel ";
		$row2 = lectura($sql2);

    //Si no existe la tabla hotel_$producto de ese hotel, devolvemos 0 como default
		$row2['diasEnvio'] = (empty($row2['diasEnvio'])? 0 : $row2['diasEnvio']);

		if($row2)
		{
			$tags = array('hotel', 'hotel_'.$producto.'_'.$id_hotel);
			setToCache($cacheName, $row2, 31536000, $tags);
		}
	}else{
		$row2 = $cache->get();
	}

	$diasEnvio = (empty($row2['diasEnvio'])? 0 : $row2['diasEnvio']);

	$this->diasEnvio = $diasEnvio;
}

/*
*   FX para poner la fecha de envio del email del producto
*/
public function setSendDateEmailProducto()
{
$this->sendDate = date('Y-m-d', strtotime("+".$this->diasEnvio." days")); //hoy + $diasEnvio
}
}

/*
*   Class satisfaction
*/
class satisfaction extends productoHotelOpinionUsuarioEnvioEmail
{
	private $guidHotel;
	private $guidUsuario;
public $id_satisfaction_hl;// id satisfaction en HL
private $urlSatisfaction;
public $id_satisfaction_emails;// id satisfaction en la plataforma emails

public function __construct($id_hotel_hl, $id_hotel_emails, $id_usuario_hl, $id_usuario_emails, $guidHotel='')
{
	static $producto='satisfaction';
	parent::__construct($producto, $id_hotel_hl, $id_hotel_emails, $id_usuario_hl, $id_usuario_emails);
	$this->guidHotel = $guidHotel;
} 

/*
* FX para crear la URL de satisfaction
*/
public function setUrlSatisfaction()
{
	include_once RUTA_DIR . LIB . 'obtenerDatosUsuario.php';
	$this->guidUsuario = (empty($this->guidUsuario)? obtenerGUIDUsuarioId($this->id_usuario_hl) : $this->guidUsuario );
	include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
	$this->guidHotel = (empty($this->guidHotel)? obtenerGUIDHotel($this->id_hotel_hl) : $this->guidHotel );
	global $urlTree;
	$this->urlSatisfaction = SECURE_BASE_PATH.$urlTree['satisfaction-survey'].'/?tk='.str_replace('-','',$this->guidUsuario).str_replace('-','',$this->guidHotel).'-'.$this->id_satisfaction_hl;
}

/*
* FX para generar el satisfaction en HL.
* Cuando se crear el satisfaction en la plataforma de emails, primero se debe crear el satisfaction en HL para asociar los satisfactions en ambas plataformas
*/
public function setSatisfactionHL()
{
	$id_cadena = hotelIdCadena($this->id_hotel_hl);
	$sql2 = "INSERT INTO user_satisfaction(id_usuario, id_hotel, id_cadena) 
	VALUES ('".$this->id_usuario_hl."', '".$this->id_hotel_hl."', '$id_cadena') ";
	$this->id_satisfaction_hl = escritura($sql2);
}

/*
* FX para enviar satisfaction a la plataforma de emails
*/
public function sendSatisfactionToEmailPlatform()
{
	$sql = "INSERT INTO satisfactions (user_id, hotel_id, send_date, satisfaction_url) 
	VALUES ('".$this->id_usuario_emails."', '".$this->id_hotel_emails."', '".$this->sendDate."', '".$this->urlSatisfaction."')";
	$con = conectar(2);
	$this->id_satisfaction_emails = escritura($sql, $con); 
}

/*
* FX para borrar satisfaction de Hotelinking. 
* Si no se puede crear satisfaction en la plataforma de email, debemos borrar el satisfaction de HL creado previamente para evitar tener satisfactions obsoletos
*/
public function borrarSatisfactionHL()
{
	$sql = "DELETE FROM user_satisfaction WHERE id='".$this->id_satisfaction_hl."' ";
	escritura($sql);
}
}

/* 
*   Class review.
*/
class review extends productoHotelOpinionUsuarioEnvioEmail
{
	public function __construct($id_hotel_hl, $id_hotel_emails, $id_usuario_hl, $id_usuario_emails)
	{
		static $producto='review';
		parent::__construct($producto, $id_hotel_hl, $id_hotel_emails, $id_usuario_hl, $id_usuario_emails);
	}

	public function sendReviewToEmailPlatform()
	{
		$sql = "INSERT INTO reviews (user_id, hotel_id, send_date, created_at)
		VALUES ('".$this->id_usuario_emails."', '".$this->id_hotel_emails."', '".$this->sendDate."', NOW())";
		$con = conectar(2);
		escritura($sql, $con);
	}
}

/* 
*   Class ofertaHotelCreacionPorToken.
*/
class ofertaHotelCreacionPorToken extends productoHotelEnvioEmail
{
	protected $id_oferta;
	protected $tokenOfertaCanjeo;
	protected $tipo_oferta;
	protected $id_origen_oferta;
	protected $urlTokenCreateOffer;
	public $id_tokenCanjeoOfertaHL;

	public function __construct($producto, $id_hotel_hl, $id_hotel_emails, $id_usuario_hl, $id_usuario_emails, $id_oferta, $tipo_oferta, $id_origen_oferta)
	{
		static $producto = 'ofertaWifiHotel';
		parent::__construct($producto, $id_hotel_hl, $id_hotel_emails, $id_usuario_hl, $id_usuario_emails);
		$this->id_oferta    = $id_oferta;
		$this->tipo_oferta  = $tipo_oferta;
		$this->id_origen_oferta  = $id_origen_oferta;
	}

/*
* FX para crear un token para crear una oferta  
* Este token se crea al momento sin mirar la base de datos. Se debe hacer limpieza de los tokens antiguos (cron)
*/
public function crearTokenCanjeoOfertaWifi()
{
	// Creamos un token a partir de los ids de usuario/hotel HL/email + time + salt
	$salt = '·$%&-*';
	return hash('sha256', $this->id_hotel_hl.$this->id_hotel_emails.$this->id_usuario_hl.$this->id_usuario_emails.time().$salt);
}

/*
*  FX para guardar en HL los datos necesarios para crear la oferta a partir del token
*/
public function setTokenCanjeoOfertaHL()
{
	$fecha = date("Y-m-d H:i:s");
	$sql = "INSERT INTO pre_oferta_token
	(id_usuario, id_hotel, id_oferta, token, tipo_oferta, id_origen_oferta, fecha) 
	VALUES 
	('".$this->id_usuario_hl."', '".$this->id_hotel_hl."', '".$this->id_oferta."', '".$this->tokenOfertaCanjeo."', '".$this->tipo_oferta."', 
	$this->id_origen_oferta, '".$fecha."') ";
	$this->id_tokenCanjeoOfertaHL = escritura($sql);
}

/*
* FX para actualizar el token pre oferta
*/
public function updateTokenCreateOfferHL()
{
	$sql = "UPDATE pre_oferta_token SET id_oferta='".$this->id_oferta."', fecha='".date("Y-m-d H:i:s")."' WHERE token='".$this->tokenOfertaCanjeo."' ";
	$con = conectar();
	escritura($sql, $con, false);
	$this->id_tokenCanjeoOfertaHL = mysqli_affected_rows($con);
	desconectar($con);

}

/*
* FX para dar un valor al token pre oferta
*/
public function setTokenCanjeoOfera($tokenOfertaCanjeo)
{
	$this->tokenOfertaCanjeo = $tokenOfertaCanjeo;
}

/*
* FX para crear la URL a la pagina que crea la oferta a partir del token
*/
public function setUrlTokenCreateOffer()
{
	global $urlTree;
    $this->urlTokenCreateOffer = SECURE_BASE_PATH . $urlTree['create-offer-from-token'] . '/?tk=' . $this->tokenOfertaCanjeo;
}

/*
* FX para obtener el token de canjeo previo a la oferta de un usuario y un hotel
*/
public function getTokenCanjeoOferta()
{
	$sql = "SELECT token FROM pre_oferta_token WHERE id_hotel='".$this->id_hotel_hl."' AND id_usuario='".$this->id_usuario_hl."' ";
	$row = lectura($sql);
	return $row['token'];
}
}

/*
*   Class ofertaWifiHotel
*/
class ofertaWifiHotel extends ofertaHotelCreacionPorToken
{
	private $img;
	private $lang;
	private $state;

	public function __construct($id_hotel_hl, $id_hotel_emails, $id_usuario_hl, $id_usuario_emails, $id_oferta, $tipo_oferta, $img, $lang, $id_origen_oferta, $state)
	{
		static $producto = 'ofertaWifiHotel';
		parent::__construct($producto, $id_hotel_hl, $id_hotel_emails, $id_usuario_hl, $id_usuario_emails, $id_oferta, $tipo_oferta, $id_origen_oferta);
		$this->img  = $img;
		$this->lang = $lang;
		$this->state = $state;
	}

	/*
	* @param : string, new state
	* @return : void
	*/
	public function setState($newState)
	{
		$this->state = $newState;
	}

	/*
	*   FX para mirar si un usuario tiene una oferta de wifi (id_origen_oferta='1') en alguno de estos 3 casos:
	*       - token previo oferta. Tabla pre_oferta_token
	*       - oferta no canjeada. Tabla oferta_referral_token 
	*       - oferta canjeada. Tabla used_promocode
	*/
	public function getOfertaWifiUsuario()
	{
		$id_hotel   = $this->id_hotel_hl;
		$id_usuario = $this->id_usuario_hl;

		$sql1 = " SELECT id AS id_preOfertaToken, id_oferta AS id_ofertaPreOfertaToken, DATE(fecha) AS fechaPreOfertaToken FROM pre_oferta_token 
		WHERE id_hotel=$id_hotel AND id_usuario=$id_usuario AND id_origen_oferta='1' ORDER BY fecha DESC LIMIT 1";
		$sql2 = " SELECT id AS id_cuponNoCanjeado, id_oferta AS id_ofertaCuponNoCanjeado, DATE(fecha) AS fechaCuponNoCanjeado FROM oferta_referral_token 
		WHERE id_hotel=$id_hotel AND id_usuario=$id_usuario AND id_origen_oferta='1' ORDER BY fecha DESC LIMIT 1";
		$sql3 = " SELECT id AS id_cuponCanjeado, id_oferta AS id_ofertaCuponCanjeado, DATE(fecha) AS fechaCuponCanjeado FROM used_promocode 
		WHERE id_hotel=$id_hotel AND id_usuario=$id_usuario AND id_origen_oferta='1' ORDER BY fecha DESC LIMIT 1";
		$con = conectar();
		$row1 = lectura($sql1, $con, false);
		$row2 = lectura($sql2, $con, false);
		$row3 = lectura($sql3, $con);

		$row1 = (empty($row1)? array('id_ofertaPreOfertaToken'=>'0', 'fechaPreOfertaToken'=>'0', 'id_preOfertaToken'=>'0'): $row1);
		$row2 = (empty($row2)? array('id_ofertaCuponNoCanjeado'=>'0', 'fechaCuponNoCanjeado'=>'0', 'id_cuponNoCanjeado'=>'0'): $row2);
		$row3 = (empty($row3)? array('id_ofertaCuponCanjeado'=>'0', 'fechaCuponCanjeado'=>'0', 'id_cuponCanjeado'=>'0'): $row3);

		return array_merge($row1, $row2, $row3);
	}

	/*
	* FX para obtener la fecha en la que podrá ser adquirida nuevamente la oferta del wifi. 
	* No damos a oferta wifi por cada conexión la wifi, hay un tiempo especificado por el hotel en su panel para establecer cuando la damos nuevamente.
	*  
	*   @days (int) numero de dias que deben pasar hasta que el usuario pueda obtener nuevamente la oferta
	*   @date (date) fecha el la que el usuario obtuvo su oferta (token pre oferta, oferta no cajeada, oferta cajeada)
	*/
	public function getNewAdquisitionOfferWifiDate($days, $date)
	{
		return date('Y-m-d', strtotime($date."+".$days." days")); 
	}

	/*
	*   FX para enviar email oferta con: token, user_id, hotel_id, send_date, token_type (inmediate web), offer_name y offer_image
	*/
	public function sendWifiOfferToEmailPlatform()
	{
		$sql = "INSERT INTO stay_offers (user_id, hotel_id, offer_name, offer_image, send_date, token, token_type, state) 
		VALUES ('".$this->id_usuario_emails."', '".$this->id_hotel_emails."', '".$this->lang."', '".$this->img."', 
		'".date('Y-m-d')."', '".$this->urlTokenCreateOffer."', '".$this->tipo_oferta."','".$this->state."')";
		escritura($sql, conectar(2));
	}
}

/*
*   FX para borrar cupon no canjeado, lo borra de las tablas oferta_referral_token y user_cupones
*
*   Params:
*       @$idCuponNoCanjeado (int) 
*/
function borrarCuponNoCanjeado($idCuponNoCanjeado)
{
	$sql = "DELETE `ort`, uc FROM oferta_referral_token ort
	LEFT JOIN user_cupones uc ON (ort.id_cupon = uc.id) 
	WHERE ort.id = $idCuponNoCanjeado ";
	escritura($sql);
}
?>