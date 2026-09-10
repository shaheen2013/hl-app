<?php
// Plantilla
$link_codVerif = BASE_PATH.$urlTree['create-account'].'/?tokenVerif='.$token.'&codVerif='.$codVerif;

$asunto = 'email verification';
$cuerpo = 'Please follow the link below: 
<br><br>
<a href="'.$link_codVerif.'">'.$link_codVerif.'</a>';
?>