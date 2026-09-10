<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
	header('Location: /');

	if(!empty($_POST) && !array_has($_POST, 'relogin_hotel_id')){
		if(!empty($_POST['username']) && !empty($_POST['password'])){
			$login = loginAdmin($_POST['username'],$_POST['password']);
			if($login == true){
				$_SESSION['private'] = true;
				header('Location: private-invitar-hotel');
			}
		}
	}
 ?>