<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// Generar Token Alfanumerico / Mayus-Minus Unico
function generarTokenAN($long){
	$token = '';
	$cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";//62
	$longitudCadena = strlen($cadena);
	$longitudToken = $long;
	for($i=1 ; $i<=$longitudToken ; $i++){
		$pos=rand(0,$longitudCadena-1);
		$token .= substr($cadena,$pos,1);
	}
	return $token;
}

// Generar Token Alfanumerico (Mayúsculas)
function generarTokenANMayus($long){
	$token = '';
    $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    for ($i = 0; $i < $long; $i++) {
        $token .= $chars[mt_rand(0, strlen($chars)-1)];
    }
	return $token;
}

function tokenRepetido($token, $tabla){
	$sql = "SELECT COUNT(id) AS n FROM ".$tabla." WHERE token='".$token."' ";
	//echo $sql;
	$row = lectura($sql);
	if($row['n']=='0'){
		return false;
	}else{
		return true;
	}
}

function tokenPromoNoRepetido($token){
	//El promo code debe ser unico ademas no puede ser igual a ningún voucher de cupón
	$sql = "SELECT
	(SELECT COUNT(id_oferta) AS n FROM oferta_referral_token WHERE token='".$token."') AS promo,
	(SELECT COUNT(id) AS n FROM user_cupones WHERE voucher='".$token."') AS voucher	 ";
	$row = lectura($sql);
	//Ambos deben ser 0 para que el promo code sea válido
	if($row['promo']=='0' && $row['voucher']=='0'){
		return false;
	}else{
		return true;
	}
}

//FX para crear un GUID
function guidv4()
{
	$data = openssl_random_pseudo_bytes(16);
    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function guidRepetido($guid, $tabla)
{
	$sql = "SELECT COUNT(id) AS n FROM ".$tabla." WHERE guid='".$guid."' ";
	$row = lectura($sql);
	if($row['n']=='0'){
		return false;
	}else{
		return true;
	}
}
?>