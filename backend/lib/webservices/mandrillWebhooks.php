<?php
include_once 'librerias.php';// Librerías básicas

!defined('INDEXCONTROLVAL')? define("INDEXCONTROLVAL", "1"):'';	

include_once RUTA_DIR.LIB.'enviarEmail.php';

//----------------------------------------------------------------------------------//
// FX para genera la signatura para comparar con la que nos manda mandrill en la 
// cabecera ($_SERVER['HTTP_X_MANDRILL_SIGNATURE']), si no son iguales, el post no viene 
// de Mandrill
// https://mandrill.zendesk.com/hc/en-us/articles/205583257-Authenticating-webhook-requests
//----------------------------------------------------------------------------------//
function generateSignature($webhook_key, $url, $params) 
{
	$signed_data = $url;
    ksort($params);
    foreach ($params as $key => $value) {
        $signed_data .= $key;
        $signed_data .= $value;
    }
    return base64_encode(hash_hmac('sha1', $signed_data, $webhook_key, true));
}

//----------------------------------------------------------------------------------//
// De momento, cuando se produzaca un Hard Bounce no borramos la cuenta de usuario,
// mandamos un email a una cuenta y verificaremos manualmente que sucede.
// Si en todas las ocasiones se puede borrar el usuario, descomentar el codigo de 
// borrado de usuario
//----------------------------------------------------------------------------------//
function hard_bounce($email, $msg, $entorno) 
{
	//Acciones hard bounce
	//borrarUsuarioEmail($email);<--Ojo
	//Mandamos email de que se ha producido un hard bounce
	mandarEmailMandrill('bounce@hotelinking.com', 'hotelinking', 'hard_bounce', 'Se ha producido un Hard Bounce de la cuenta con email: ', $email.' - MSG: '.$msg.' - entorno :'.$entorno.' (BETA, COM, DEV)<br>');
	
	//return '<br>hard bounce '.$email;//test
}

function send($email, $msg) 
{
	//Acciones send
}

function soft_bounce($email, $msg) 
{
	//Acciones soft_bounce
}

function open($email, $msg) 
{
	//Acciones event_open
}

function click($email, $msg) 
{
	//Acciones debug
}

function spam_report($email, $msg) 
{
	//Acciones spam_report
}

function unsubscribe($email, $msg) 
{
	//Acciones event_unsubscribe
}

function debug($email, $msg) 
{
	//Acciones debug
}

//----------------------------------------------------------------------------------//
// FX para obtener la signatura del webhook de Mandrill
//----------------------------------------------------------------------------------//
function obtenerSignaturaWebhook($PostParams, $entorno)
{
	// Keys webhooks + urls
	if($entorno == 'com'){
		$webhook_key = 'fqXGPiuPlMIpTWJWsw-x1g';
		$url = 'http://www.hotelinking.com/lib/webservices/mandrillWebhooks.php';
	}else if($entorno == 'beta'){
		$webhook_key = 'xFkXK_oQJJs7_CPM149QwQ';
		$url = 'http://beta.hotelinking.com/lib/webservices/mandrillWebhooks.php';
	}else if($entorno == 'dev'){
		$webhook_key = 'HzjQHU42lH47tnExS4eAjQ';
		$url = 'http://dev.hotelinking.com/lib/webservices/mandrillWebhooks.php';
	}
	return generateSignature($webhook_key, $url, $PostParams);
}

function obtenerEntorno($host)
{
	if ($host == 'beta.hotelinking.com'){
		$entorno = 'beta';
	}else if($host == 'app.hotelinking.com'){
		$entorno = 'com';
	}else if($host == 'dev.hotelinking.com'){
		$entorno = 'dev';
	}else{
		$entorno = 'local';
	}
	return $entorno;
}

if(!empty($_POST['mandrill_events']))
{	
	//$cuerpo = 'empty';//test
	$entorno = obtenerEntorno($_SERVER['HTTP_HOST']);//com, beta, dev
	//Para genera la signatura debemos pasar todo el $_POST
	$key = obtenerSignaturaWebhook($_POST, $entorno);
	
	$json = $_POST['mandrill_events'];
	$rs = json_decode(stripslashes($json),true);
	
	if(!empty($rs))
	{
		//$cuerpo = 'email test webhooks mandrill <br>'.$rs;//test
		//Si NO esta en COM o BETA saltamos
		if($entorno=='beta' || $entorno=='com' /*|| $entorno=='dev'*/)//test
		{
			//$cuerpo .= '<br>'.$_SERVER['HTTP_X_MANDRILL_SIGNATURE'].' == '.$key.'?<br>';//test
			if($_SERVER['HTTP_X_MANDRILL_SIGNATURE'] == $key)
			{
				//$cuerpo .= '<br>Signature OK ☺	';//test
				foreach($rs as $event)
				{
					switch($event["event"])
					{
						case "send": send($event); break;
						case "deferral": soft_bounce($event['msg']['email'], "deferral: " . $event['msg']['bounce_description']); break;
						case "hard_bounce":$cuerpo .= hard_bounce($event['msg']['email'], $event['msg']['bounce_description'], $entorno); break;
						case "soft-bounce": soft_bounce($event['msg']['email'], $event['msg']['bounce_description']); break;
						case "open": open($event); break;
						case "click": click($event); break;
						case "spam": spam_report($event['msg']['email']); break;
						case "spamreport": spam_report($event['msg']['email']); break;
						case "unsub": unsubscribe($event); break;
						case "reject": hard_bounce($event['msg']['email'], "reject: " . $event['msg']['bounce_description']); break;
						default: debug(" == Invalid category: '".$event["category"]."' for: ".$event["recipient"]." ==");
					}
				}
			}else{
				//$cuerpo .= '<br>Signature KO ';//test
			}
		}else{
			//El entorno es localhost o DEV 
			//$cuerpo .= '<br>Entorno: '.$entorno ;//test
		}
		
	}
	//test
	//mandarEmailMandrill('j.cabrer@hotelinking.com', 'jaume', 'webhooks mandrill', $cuerpo);
}else{
	//test
	//mandarEmailMandrill('j.cabrer@hotelinking.com', 'jaume', 'webhooks mandrill', 'nada');
}

// Borramos usuario por email solo si no esta verificado
// Si esta verificado asumimos que la cuenta es correcta
function borrarUsuarioEmail($email){
	$sql = "DELETE FROM users 
	WHERE email='".$email."' AND verificado=0 ";
	mysqli_query (conectar(), $sql) or die(mysqli_error());
}
?>