<?php //Miramos si esta definida la variable de control de index.php
//if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
//Para WS
if(!empty($_POST['nError']) && !empty($_POST['lang']) )
{
	include_once 'librerias.php';

	$allowedLogTypes = ["debug", "info", "warning", "error"];
	if(!empty($_POST['logName']) && !empty($_POST['logType']) && in_array($_POST['logType'], $allowedLogTypes))
	{
		global $log;
		$log->{$_POST['logType']}($_POST['logName'], json_decode($_POST['logParams'] ?? "[]", true));
	}
	
    $nError = array_get($_POST, 'nError');
	$lang = array_get($_POST, 'lang');
	$active = array_get($_POST, 'active');
	$productName = array_get($_POST, 'product_name');
	$brandIds = array_get($_POST, 'brand_id'); 
	echo json_encode(msgFeedbackWs($nError, $lang, $active, $productName, $brandIds));
}

// FX para generar msg de feedback desde WS sin recargar la página
//$nError: numero de error del msg
// ${'msg' . $nError} equivale a $msg+nError
function msgFeedbackWs($nError, $lang, $active=null, $productName=null, $brandIds=null)
{
	$brandIdsString = is_array($brandIds) ? implode(', ', $brandIds) : $brandIds;
	
	include RUTA_DIR . LANG . $lang . '/feedback.php';
	
	//Extraemos el primer nº del $nError (2 success, 3 warning, 4 error)
	$tiposMsg = substr($nError, 0, 1);

	if($tiposMsg == '4'){
		//Msg error
		$icon = 'fa-ban';
	}else if($tiposMsg == '3'){
		//Msg alert
		$icon = 'fa-exclamation-triangle';
	}else{
		//Msg success
		$icon = 'fa-check';
	}

	$error = '<i class="fa '.$icon.' fa-2x blanco pull-left"></i>
	<ul><li >'.${'msg' . $nError}.'</li></ul>
	<a href="#" class="close-feedback blanco"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMS4xLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDE3NC4yMzkgMTc0LjIzOSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMTc0LjIzOSAxNzQuMjM5OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCI+CjxnPgoJPHBhdGggZD0iTTg3LjEyLDBDMzkuMDgyLDAsMCwzOS4wODIsMCw4Ny4xMnMzOS4wODIsODcuMTIsODcuMTIsODcuMTJzODcuMTItMzkuMDgyLDg3LjEyLTg3LjEyUzEzNS4xNTcsMCw4Ny4xMiwweiBNODcuMTIsMTU5LjMwNSAgIGMtMzkuODAyLDAtNzIuMTg1LTMyLjM4My03Mi4xODUtNzIuMTg1UzQ3LjMxOCwxNC45MzUsODcuMTIsMTQuOTM1czcyLjE4NSwzMi4zODMsNzIuMTg1LDcyLjE4NVMxMjYuOTIxLDE1OS4zMDUsODcuMTIsMTU5LjMwNXoiIGZpbGw9IiNGRkZGRkYiLz4KCTxwYXRoIGQ9Ik0xMjAuODMsNTMuNDE0Yy0yLjkxNy0yLjkxNy03LjY0Ny0yLjkxNy0xMC41NTksMEw4Ny4xMiw3Ni41NjhMNjMuOTY5LDUzLjQxNGMtMi45MTctMi45MTctNy42NDItMi45MTctMTAuNTU5LDAgICBzLTIuOTE3LDcuNjQyLDAsMTAuNTU5bDIzLjE1MSwyMy4xNTNMNTMuNDA5LDExMC4yOGMtMi45MTcsMi45MTctMi45MTcsNy42NDIsMCwxMC41NTljMS40NTgsMS40NTgsMy4zNjksMi4xODgsNS4yOCwyLjE4OCAgIGMxLjkxMSwwLDMuODI0LTAuNzI5LDUuMjgtMi4xODhMODcuMTIsOTcuNjg2bDIzLjE1MSwyMy4xNTNjMS40NTgsMS40NTgsMy4zNjksMi4xODgsNS4yOCwyLjE4OGMxLjkxMSwwLDMuODIxLTAuNzI5LDUuMjgtMi4xODggICBjMi45MTctMi45MTcsMi45MTctNy42NDIsMC0xMC41NTlMOTcuNjc5LDg3LjEyN2wyMy4xNTEtMjMuMTUzQzEyMy43NDcsNjEuMDU3LDEyMy43NDcsNTYuMzMxLDEyMC44Myw1My40MTR6IiBmaWxsPSIjRkZGRkZGIi8+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" /></i></a>';
	return $error;
}
?>