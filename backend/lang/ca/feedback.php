<?php
//OK
//general
$msg2003 = 'Genial! Comprueba tu email ahora por favor'; // contraseña mandada al email
$msg2004 = 'Alert! the position of the widget has changed, this will require you to update the tag on your web page ';
$msg2007 = 'Los datos se han guardado correctamente';
//hotel check in
$msg2005 = 'Check-in ok!';
$msg2006 = 'Validado correctamente';
//tienda
$msg2008 = 'Oferta de fidelización canjeada!';
$msg2010 = 'Oferta añadida a tu lista de deseos!';
//wishlist
$msg2009 = 'Oferta borrada de tu lista de deseos';
//
$msg2011 = 'Oferta compartida en redes sociales!';
$msg2012 = (!empty($points) ? $points . ' ' : '') . 'Puntos añadidos a tu cuenta correctamente';
$msg2013 = 'Ahora estás siguiendo a este hotel';
$msg2014 = 'Has dejado de seguir a este hotel';
// Add staff
$msg2015 = 'Usuario creado correctamente';
// staff-managemen
$msg2016 = 'Usuario borrado';
//chain management
$msg2017 = 'Nuevo hotel creado correctamente!';
// chain-management
$msg2018 = 'Logeado como ' . (!empty($_SESSION['hotelName']) ? '<strong>' . $_SESSION['hotelName'] . '</strong>' : ' nuevo hotel');
// invitar-usuarios-2
$msg2019 = 'Lista guardada con éxito';
// invite-user-drafts
$msg2020 = 'Lista borrada';
// cupon (regalar)
$msg2021 = 'Cupón de oferta enviado!';
//hotel profile 2
$msg2022 = 'Imagen borrada';
$msg2023 = 'Imagen marcada como prioritaria';
// User-points (regalar puntos)
$msg2024 = 'Puntos transferidos con éxito';
$msg2025 = 'Tarjeta de puntos activadas correctamente';
$msg2026 = 'Tarjeta de puntos desactivada correctamente';
$msg2027 = 'Oferta asignada correctamente';
$msg2028 = 'Oferta desactivada correctamente';
$msg2029 = 'Email cambiado, por favor comprueba tu email y confírmalo';
$msg2030 = 'Perfecto, la dirección de correo electrónico es correcta';
$msg2031 = "Fila borrada con éxito";
$msg2032 = "Cuentas de correo electrónico inválidas borradas con éxito";
$msg2033 = "Compartido correctamente en red social";
$msg2034 = "Email cambiado correctamente";
$msg2035 = "Invitación reenviada de nuevo con éxito";

//offer
$msg2036 = "Oferta publicada correctamente";

//Edit staff
$msg2037 = "Staff actualizado correctamente";

//UNSUBSCRIBE
$msg2038 = "Usuario dado de baja correctamente";

//Clients
$msg2039 = "Solicitud procesada con éxito. El email con el fichero puede tardar un poco en llegar.";

//products
$msg2040 = 'Se ha ' . (isset($active) ? ($active === '1' ? 'desactivado' : 'activado') : null) . ' correctamente el producto ' . (isset($productName) ? $productName : null) . ' en el brand ' . (isset($brandIdsString) ? $brandIdsString: null);

$msg2041 = 'Se ha editado correctamente el producto ' . (isset($productName) ? $productName : null) . ' en el brand ' . (isset($brandIdsString) ? $brandIdsString: null);
$msg2042 = 'Contraseña cambiada correctamente.';
$msg2043 = 'Sus preferencias para proteger su cuenta se han guardado correctamente. Puede loguearse ahora';

//KO
$msg4001 = 'Cliente no encontrado';
$msg4002 = 'Esta acción no está permitida';
$msg4003 = "Debes estar logeado";
$msg4005 = 'A este cliente ya se le ha realizado el check-in';
$msg4006 = 'Lo sentimos, no puedes canjear esta oferta en este momento';
$msg4007 = 'Lo sentimos, esta oferta ya no se puede canjear porque se ha terminado';
$msg4008 = 'Parece que no te quedan puntos suficientes como para canjear esta oferta';
$msg4009 = 'Esta oferta solo está disponible para clientes nuevos (utiliza los rubies azules <i class="rubies rubix2 rubiesHL">rubies</i> ). Parece que ya eres cliente de este hotel, por tanto debes utilizar los otros puntos (rubies rojos <i class="rubies rubix2">rubies</i>) para canjear ofertas de este hotel';
$msg4010 = 'Las ofertas de servicios solo se pueden canjear cuando estás en check-in en el hotel';
$msg4011 = 'Esta oferta no está activa en este momento';
$msg4012 = 'Solo puedes canjear una única oferta de este tipo. Como ya has canjeado una, lamentamos comunicarte que ya no puedes canjear más. <br> Porqué? Todas las ofertas que con rubies azules <i class="rubies rubix2 rubiesHL">rubies</i> solo se pueden canjear una vez. Los rubies azules son para descubrir nuevos hoteles (para canjear ofertas de hoteles que nunca has estado)';
$msg4013 = 'Password incorrecto, prueba otra vez';
$msg4014 = "Lo sentimos, las ofertas solo se pueden compartir una vez";
$msg4015 = "Debes estar logeado como cliente para realizar esta acción";
$msg4016 = "Este usuario ya existe como admin, no se puede mover a un nivel inferior. Prueba con una cuenta de email diferente.";
$msg4017 = "Usuario existente";
$msg4018 = "Hay emails incorrectos. Deben substituirse por emails correctos, o de lo contrario no se enviara nada a dichos correos. El sistema solo envía invitaciones a emails correctos.";
$msg4019 = "Hay campos duplicados, la lista no se puede guardar.";
$msg4020 = "Parece que no tienes puntos suficientes";
$msg4021 = "Únicamente puedes regalar tus cupones";
$msg4022 = "Este cupón ya ha sido validado. No se puede utlizar de nuevo";
$msg4023 = "Puedes regalar ofertas si cuentas con los puntos suficientes como para canjearla";
$msg4024 = "Por favor, completa todos los campos requeridos";
$msg4025 = "Email ya en uso";
$msg4026 = "La cuenta ha sido bloqueada por demasiados intentos. Vuelve a intentarlo más tarde.";
// Hotel login errors
$msg4027 = "Email incorrecto, prueba de nuevo por favor"; //email mal formado (FILTER_VALIDATE_EMAIL)
$msg4028 = "El password debe ser más largo";
$msg4029 = "Password incorrecto"; // email o pass incorrectos
$msg4030 = "Esta cuenta no esta activa";
$msg4031 = "El password debe tener como mínimo 8 carácteres, una minuscula, una mayuscula, un número y un carácter especial";
$msg4076 = "The account you are trying to connect to is disabled";
$msg4103 = "Ocurrió un error desconocido. Por favor, póngase en contacto con soporte.";
$msg4104 = "Usuario no encontrado. Compruebe que el email introducido sea correcto.";
$msg4105 = "Código de verificación no válido, inténtelo de nuevo.";
$msg4106 = "Ha alcanzado el límite de peticiones. Por favor, inténtelo más tarde";
$msg4107 = "El estado de la cuenta no permite recuperar la contraseña. Por favor, póngase en contacto con soporte.";
$msg4108 = "El código que ha introducido ha caducado. Por favor, intente recuperar la cuenta de nuevo para recibir un código nuevo.";
$msg4109 = "El número de teléfono introducido no tiene el formato solicitado";
$msg4110 = "El código que ha introducido ha caducado. Por favor, inténtelo de nuevo con un nuevo código.";
$msg4111 = "No se ha podido obtener toda la información de este usuario. Por favor, póngase en contacto con soporte.";
$msg4112 = "No se ha podido actualizar la información del usuario. Por favor, póngase en contacto con soporte.";
$msg4113 = "No se ha podido crear usuario. Por favor, póngase en contacto con soporte.";
$msg4114 = "Código de verificación no válido. Por favor, vuelva a escanear el QR e introduzca el código correcto";
//Subir imagenes
$msg4032 = "La imágen del logo debe ser del formato jpg, gif, o png";
$msg4033 = "Imagen demasiado grande";

$msg4034 = "El password no es el correcto";
$msg4035 = "El Hotel está cerrado durante el periodo";
$msg4036 = "'Hasta' debe ser mayor que 'desde'"; //Fecha inicio superior fecha fin
$msg4037 = "Debes tener una invitación para crearte una cuenta";
$msg4038 = "Token incorrecto";
$msg4039 = "No se puede compartir sin tus cuentas de social media asociadas";
$msg4040 = "Ya eres un cliente de este hotel";
$msg4041 = "La cantidad no puede ser mayor a la del nivel superior, o menor a la del nivel inferior";
$msg4042 = "Esta oferta no se puede activar porque es un borrador. Por favor publícala primero";
$msg4043 = "Email no vàlid, si us plau utilitza un email real";
$msg4044 = "Password incorrecto";
$msg4045 = "Cuenta de cliente no activa";
$msg4046 = "Formato inválido. Asegúrate que incluyes punto y coma (;) para separar los datos";
$msg4047 = "La dirección de correo electrónico es inválido";
$msg4048 = "No puedes referirte a ti mismo";
$msg4049 = "'puntos desde' debería ser mayor que 'puntos hasta'";
$msg4050 = "'noches desde' debería ser mayor que 'noches hasta'";
$msg4051 = "'gasto desde' debería ser mayor que 'gastos hasta'";
$msg4052 = "No se puede importar una lista vacía";
$msg4053 = "Por favor, completa todos los campos requeridos en todos los idiomas seleccionados";
$msg4054 = "No has autorizdo a Facebook para que nos ceda tu email. Por tanto, no podemos enviarte el cupón. Para solucionarlo puedes ir Facebook, y borra la aplicación Hotelinking. Entonces pudes volver a realizar el proceso de nuevo.";
$msg4055 = 'Debes canjear tu cupón para conseguir uno nuevo';
$msg4056 = 'Tweet no enviado. Seguramente porque ya has posteado este tweet, o porque has alcanzado el límite permitido.';
// Usuario no ha verificado su email de la cuenta de twitter
$msg4057 = 'Parece que su cuenta de correo electrónico no ha sido verificado por Twitter. Por favor, asegúrese de que se verifique e inténtelo de nuevo.';
$msg4058 = 'Lo sentimos, no se puede acceder al WiFi ahora. Estamos trabajando para dar acceso tan pronto como sea posible. Gracias por su paciencia.';
$msg4059 = 'La URL contiene más de 1.000 caracteres. Se ha excedido el límite. Por favor, intente con otra URL.';
$msg4060 = 'Nuevo password y repetir nuevo password no coinciden';
$msg4061 = 'Por favor, habilite las cookies de su navegador para poder proceder.';
$msg4062 = 'Parece que tu cuenta no está verificada por Facebook. Por favor, asegúrate que está correctamente verificada y prueba de nuevo.';
$msg4063 = 'Ha ocurrido un error inesperado con tu cuenta de Facebook. Por favor, inténtalo mas tarde.';
$msg4064 = 'Ha ocorregut un error inesperat quan intentaves accedir. Si us plau, intenta-ho de nou.';
//Error de base de datos
$msg4065 = "Error al inserta datos, por favor contacta con soporte si persiste el problema";
$msg4067 = "Error al recibir datos, por favor contacta con soporte si persiste el problema";
$msg4101 = 'No se ha ' . (isset($active) ? ($active === '1' ? 'desactivado' : 'activado') : null) . ' correctamente el producto ' . (isset($productName) ? $productName : null) . ' en el brand ' . (isset($brandIdsString) ? $brandIdsString: null);
$msg4102 = 'No se ha ' . 'editado correctamente el producto ' . (isset($productName) ? $productName : null) . ' en el brand ' . (isset($brandIdsString) ? $brandIdsString: null);

//API errors
$msg4066 = 'No ha sido posible conectar con el API con estas credenciales';
$msg4068 = 'Esta campaña y oferta ya están mapeadas';
$msg4069 = 'Error al mapear esta campaña, faltan datos';
$msg4070 = 'We could not delete this item, please contact support if the problem persists';
$msg4077 = 'No podemos procesar la petición con estos datos. Pruebe con otros o contacta con soporte si persiste el problema';
//Error Curl Unifi
$msg4071 = "Accés a la Wifi no disponible en aquest moment";
$msg4072 = "Por favor, inténtelo de nuevo o contacte con recepción si el problema persiste";
$msg4073 = "Les dades introduïdes no coincideixen amb els nostres registres, si vostè és client intenti-ho més tard o posi's en contacte amb recepció";
$msg4074 = "tots els camps són requerits";

//GTM Errors
$msg4075 = "No s'han pogut recuperar les variables de Google Tag Manager. Per favor, assegura't que el container_id i el workspace_id son correctes";

//Referral Hotel
$msg5001 = 'The room list you provided does not seem to be correctly formatted, remember it should be separated by commas (ex: 1001, 1002, 1003';
//WARNING
$msg3005 = 'Cliente en check-in.<br> No le hemos enviado una invitación porque ya es cliente de tu Hotel.';
$msg3006 = 'Los campos vacíos se enviarán como "0" sino se modifican';
$msg3007 = 'Ya has adquirido esta oferta, logéate online para acceder a la oferta';
$msg3008 = 'Ya has recibido esta oferta. Comprueba tu email';
$msg3009 = 'Idiomas activos pendientes de completar. Puedes volver y editarlo luego';
$msg3010 = 'No podemos publicar su oferta';

$msg4083 = 'La última acción fue cancelada, su sesión ha sido cerrada o cambiada recientemente';

//Email from pms not validated
$msg4090 = "El correu electrònic obtingut del sistema no és vàlid, si us plau utilitzi un altre";
$msg4091 = "La encuesta de satisfacción será enviada siempre a la vez o tras la encuesta de satisfacción. Por favor, revisa los datos introducidos.";

$msg4092 = "Para que se guarden las ofertas, estas deben ser marcadas por defecto o tener una fecha de aplicación.";
$msg4093 = "Deben estar marcadas al menos una de las opciones de alojado/no alojado.";
$msg4094 = "Ya existe una o varias ofertas para esas mismas fechas y/o tipo de usuario. Modifique o borre ésta antes de añadir una oferta nueva.";
$msg4095 = "Solo puede haber una oferta por defecto por tipo alojado o no alojado.";

// NEW OFFERS MESSAGES
$msg4096 = "La oferta está asociada a un premio. Debes solucionarlo antes de poder eliminar la oferta.";
$msg4097 = "Oferta eliminada con éxito.";

// UNSUBSCRIBE FAILED
$msg4098 = "Error al dar de baja al usuario";

// Bad fields on captive portal
$msg4099 = "Algun dels camps del formulari són incorrectes. Si us plau, revisi-ho.";
$msg4100 = "Per favor, indiqui un número d'habitació vàlid";