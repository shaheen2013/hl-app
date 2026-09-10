<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}


  include_once LIB . 'make_unsuscribe_hash.php';
  //test GUID = 6d773c68-08dc-49c9-927f-3c7acba1bd99 mail = xiscoo@gmail.com hash = $2y$11$4CuceaxIxdjGnwJmjOIFUOZLefTVS4wou9TjrIHYAu6e9ZfisM91C
  //Unsuscribe a los usuarios de recibir emails de la plataforma
  //Para identificar al usuario hacemos un hash del mail del usuario, y su GUID.

  if(!empty($_GET['h']) && !empty($_GET['m'])){
    //has hash
    $hash = $_GET['h'];
    $email = $_GET['m'];
    $guid = getUserGUID($email);
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    if(!empty($_GET['p'])){
      ($_GET['p'] == 't' ? $preunsuscribe = true : $preunsuscribe = false);
    }

    if(!empty($preunsuscribe)){
      if(!empty($guid)){
        //User exists
        $digest = $email.$guid;
        $valid = validate_hash($digest, $hash);
        //If valid unsuscribe
        if($valid){
          $unsuscribe = unsuscribeUser($email);
        }else{
          $unsuscribe = false;
        }
      }else{
        $unsuscribe = false;
      }
    }else{
      $preunsuscribe = false;
    }
  }else{
    $unsuscribe = false;
  }
?>