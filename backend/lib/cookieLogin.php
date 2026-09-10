<?php
// Libreria para ver si tiene las cookies habilitas desde el login.
// Se debe ejecutar al cargar la pagina, para crear la cookie inicial !!!

// Cookie para ver en el login si tiene las cookies habilitadas
// La creamos al cargar la pagina, cuando realice el post de login miraremos si tiene valor
if( !isset($_COOKIE['lgTst']) )
{
	setcookie ('lgTst', 'cookiesOk', 0 ,'/', '', true, true);
}

function tieneCookiesLogin()
{
	// Primero miramos si tiene las cookie habilitadas
	if( empty($_COOKIE['lgTst']) )
	{
		return false;
	}else{
		return true;
	}
}

function borrarCookieLogin()
{
	if( !empty($_COOKIE['lgTst']) )
	{
		setcookie ('lgTst', "", time() - 3600, '/', '', true, true);
	}
}
?>