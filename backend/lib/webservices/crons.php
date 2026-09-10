<?php
// session_start();
// require 'libreriasCron.php';// Librerias básicas
// include_once RUTA_DIR.LIB.'enviarEmail.php';
// include_once RUTA_DIR.LIB.'make_unsuscribe_hash.php';
// include_once RUTA_DIR.LIB.'idiomas.php';

// function runCron($crons)
// {
//     $i = 0;
// 	$result['code']=404;
//     foreach($crons as $cron)
// 	{
// 	   	$time = time();
// 		if(is_time_cron($time , $cron['cron_time']))
// 		{
// 			$result['code']=200;
// 			include_once RUTA_DIR . LIB . 'webservices/' . $cron['cron_name'].'.php';
// 			$result[] = call_user_func($cron['cron_name']);//Llamamos a la FX del cron a ejecutar
// 		}
//     }
// 	return $result;
// }

// function is_time_cron($time, $cron) {
//     $cron_parts = explode(' ', $cron);
//     if (count($cron_parts) != 5) {
//         return false;
//     }    
//     list($min, $hour, $day, $mon, $week) = explode(' ', $cron);    
//     $to_check = array('min' => 'i', 'hour' => 'G', 'day' => 'j', 'mon' => 'n', 'week' => 'w');    
//     $ranges = array('min' => '0-59', 'hour' => '0-23', 'day' => '1-31', 'mon' => '1-12', 'week' => '0-6',);    
//     foreach ($to_check as $part => $c) {
//         $val = $$part;
//         $values = array();       
//         if (strpos($val, '/') !== false) {            
//             //Get the range and step
//             list($range, $steps) = explode('/', $val); 	           
//             //Now get the start and stop
//             if ($range == '*') {
//                 $range = $ranges[$part];
//             }
//             list($start, $stop) = explode('-', $range);            
//             for ($i = $start; $i <= $stop; $i = $i + $steps) {
//                 $values[] = $i;
//             }
//         } else {
//             $k = explode(',', $val);            
//             foreach ($k as $v) {
//                 if (strpos($v, '-') !== false) {
//                     list($start, $stop) = explode('-', $v);
                    
//                     for ($i = $start; $i <= $stop; $i++) {
//                         $values[] = $i;
//                     }
//                 } else {
//                     $values[] = $v;
//                 }
//             }
//         }
//         if (!in_array(date($c, $time), $values) and (strval($val) != '*')) {
//             return false;
//         }
//     }    
//     return true;
// }

// // Miramos si tiene el parametro $_GET exec=1
// in_array('exec=1', $argv) || (!empty($_GET['exec']) && $_GET['exec']=='1')? $access=true : $access=false;

// // Allowed ips
// $localIp = '192.168.32.174';
// $developIp = '92.222.145.165';
// $productionIp = '172.30.0.115'; // joopbox aws
// $allowedIps = array($localIp, $developIp, $productionIp);

// !empty($_SERVER['REMOTE_ADDR'])? $ip=$_SERVER['REMOTE_ADDR'] : $ip=gethostbyname($_SERVER['SERVER_NAME']);

// // Email al que enviamos el resumen de la ejecución
// $emailResumen = 'jaumehotelinking@gmail.com';

// // El cron solo se ejecuta si el .bat tiene el parametro exec=1 y la IP esa dentro de $allowedIps
// // Ejemplo: C:\xampp\php\php.exe -f C:\xampp\htdocs\hotelinking\lib\webservices\crons.php -- exec=1
// if ($access && in_array($ip, $allowedIps) )
// {
// 	//Obtenemos todos los crons activos
// 	$conn = conectar();
// 	$data = $conn->query("SELECT cron_id, cron_time, cron_name FROM crons WHERE active=1");
// 	while($row = mysqli_fetch_assoc($data)){
// 	   $crons[$row['cron_id']] = $row;
// 	}
	
// 	$result = runCron($crons);
	
// 	//enviamos un email con el result del cron si se ha ejecutado alguno
// 	if ($result['code'] == '200')
// 	{
// 		$content = 'Resultado de la ejecución del cron: <pre>'.print_r($result, true).'</pre>';
// 		$content .= '<br>Ip de ejecución: '.$ip.'
// 		<br>Hora fin ejecución: '.date("Y-m-d H:i:s");
// 		mandarEmailMandrill($emailResumen, '','cron result',$content);
// 	}else{
// 		//No se ha enviado nada (no hay ningún cron en esta hora, no hay results de ningún cron, etc...) 
// 	}
// }else{
// 	// Cron no ejecutado por: ip no valida o parametro get incorrecto
// 	$content = 'Cron no ejecutado por: ip no válida ('.$ip.') o parametro get incorrecto:'.print_r($argv, true);
// 	mandarEmailMandrill($emailResumen, '','cron result KO',$content);
// }
// session_destroy();
?>