<?php
include 'librerias.php';// Librerias básicas

//Este WS no se llama desde ajax sino desde CURL. La seguridad se mira con la FX leerSeguridadWSHotel

// Restringir ips que pueden acceder
include_once RUTA_DIR . LIB . 'ips_acceso.php';
include_once RUTA_DIR . LIB . 'seguridadHotel.php';

if ( ips_acceso_webservice('in-us-rs', $_SERVER['SERVER_ADDR']) && !empty($_GET['idHotel']) && !empty($_GET['sc']) && leerSeguridadWSHotel($_GET['idHotel'], $_GET['sc']) )
{
	// Constante para poder cargar las librerias
	!defined('INDEXCONTROLVAL')? define("INDEXCONTROLVAL", "1"):'';	
	
	include_once RUTA_DIR . MODEL . 'invitar-usuarios-resultModel.php';
	include_once RUTA_DIR . LIB . 'enviarEmail.php';
	include_once RUTA_DIR . LIB . 'emailValidate.php';
	include_once RUTA_DIR . LIB . 'agregarPuntos.php';
	include_once RUTA_DIR . LIB . 'crearNuevoUsuario.php';
	include_once RUTA_DIR . LIB . 'invitaciones.php';
	include_once RUTA_DIR . LIB . 'guardarSpentNights.php';
	include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
	include_once RUTA_DIR . LIB . 'referrals-mailing.php';
	include_once RUTA_DIR . LIB . 'idiomas.php';
	
	//-------------------- invitar usuarios ---------------------------------
	// idList: id de la lista de usuarios
	// idHotel: id hotel al que pertenece la lista
	// type: tipo de acción (LY o RF...).
	// leerSeguridadWSHotel: miramos si el string de seguridad es correcto
	//------------------------------------------------------------------------
	if( isset($_GET['idList']) && isset($_GET['idHotel']) && isset($_GET['type']) )
	{
		$id_list = $_GET['idList'];
		$id_hotel = $_GET['idHotel'];
		$type = $_GET['type'];//LY, RF
		
		$guidHotel = obtenerGUIDHotel($id_hotel);
		
		// Lista usuarios
		$listaUsuarios = obtenerListaUsuarios($id_list, $id_hotel, $type);
		foreach($listaUsuarios as $usuario)
		{
			$email = $usuario['email'];
			$puntos = $usuario['puntos'];
			$idioma = mirarIdiomaPlataforma($usuario['idioma']);// Idioma en el que se le mandan los email
			$idiomaUsuario = strtolower($usuario['idioma']);// Idioma real del usuario
			$totalSpent = $usuario['total_spent'];
			$totalNights = $usuario['total_noches'];
			$nombre = $usuario['nombre'];
			$emailValido = $usuario['email_valido'];
			
			// Si el email es válido procedemos con las acciones (ya esta verificado, evitamos volver a verif.)
			/*if(ENV == 'production')
			{
				// El environment es producción, verificamos email
				$resultEmail = validateEmail($email);
			}else{
				// El environment NO es producción. No verificamos email, directamente permitimos envio.
				$resultEmail = array('code' => '200');
			}
			if( $resultEmail['code']=='200' )*/
			if( $emailValido=='1' )
			{
				//Devuelve el ID de usuario si existe en BD, en caso contrario devuelve ''
				$datosUsuario = obtenerUsuario($email);
				$id_usuario = $datosUsuario['id'];
				// Agregar puntos en el registro de puntos 
				$id_emisor = $id_hotel;

				if ($id_usuario=='')
				{
					// Usuario nuevo
					echo '<!--1-->';
					//Crear usuario
					$id_usuario = crearNuevoUsuario($email, $id_hotel, $nombre, $idiomaUsuario);
					vincularUsuarioHotelero($id_usuario, $id_hotel);
					
					if($type=='RF')
					{
						//Referral
						referralsMails($email, $guidHotel, '', $nombre, $idioma, '', true, 'h');
					}else if($type=='LY'){
						//Loyalty
						// Generar invitacion + puntos provisionales
						crearInvitacionUsuario($email,'hot',$id_hotel,$puntos);
						//Guardar total spent y total nights inicial
						guardarSpentNights($id_hotel, $id_usuario, $totalSpent, $totalNights);
					}
				}else{
					// Usuario existente
					// Mirar si ya esta vinculado con el hotel
					$usuarioYaVinculado = usuarioVinculadoHotel($id_usuario, $id_hotel);
					if ($usuarioYaVinculado==false)
					{
						echo '<!--2-->';
						//El usuario ya existe en la BD y no esta vinculado con el Hotel
						if($type=='RF')
						{
							//Referral
							referralsMails($email, $guidHotel, '', $nombre, $idioma, '', true, 'h');
						}else if($type=='LY'){
							//Loyalty
							$datosHotel = obtenerHotel($id_hotel);
							//enviar email com puntos (sin invitacion)
							include_once RUTA_DIR.LANG.$idioma.'/email/invitar-usuarios-result.php';
							include_once RUTA_DIR.LIB.'plantillasMails/invitar-usuarios-result.php';
							mandarEmailMandrillPlantilla($email, $datosUsuario['nombre'], $asunto, $cuerpo);
							//mandarEmailMandrillSandbox($email, $datosUsuario['nombre'], $asunto, $cuerpo);
							//agregarPuntos($id_usuario, $id_emisor, $puntos, 8);
							//Guardar total spent y total nights inicial
							guardarSpentNights($id_hotel, $id_usuario, $totalSpent, $totalNights);
						}					
						vincularUsuarioHotelero($id_usuario, $id_hotel);
						
						
					}else{
						// Ya es usuario del hotel, volvemos a invitar
						if($datosUsuario['created']=='0000-00-00')
						{
							//La cuenta del usuario no esta activada
							echo '<!--3-->';
							if($type=='RF')
							{
								//Referral
								referralsMails($email, $guidHotel, '', $nombre, $idioma, '', true, 'h');
							}else if($type=='LY'){
								//Loyalty
								// Generar invitacion
								crearInvitacionUsuario($email,'hot',$id_hotel,$puntos);
								//Guardar total spent y total nights inicial
								guardarSpentNights($id_hotel, $id_usuario, $totalSpent, $totalNights);
							}
						}else{
							if($type=='RF')
							{
								//Referral
								referralsMails($email, $guidHotel, '', $nombre, $idioma, '', true, 'h');
							}else if($type=='LY'){
								//Loyalty
								//Cuenta de usuario ya activada, ni invitamos ni damos puntos
							}
							echo '<!--4-->';
						}
					}	
				}
			}else{
				echo '<!--5-->';
			}
		}
		echo '<!--END-->';
		marcarEnviadosTipo($id_list, $type);
	}else{
		echo 'not allowed 1';
	}
}else{
	echo 'No direct access allowed.';
	exit;
}
?>