<?php
// Cron para envio de email asincronos
//include_once 'libreriasCron.php';// Librerias básicas
include_once RUTA_DIR.LIB.'enviarEmail.php';

function enviarEmailsEnCola()
{
	$sql = "SELECT * FROM email_cue
	WHERE no_enviable=0 ORDER BY id ASC LIMIT 100";
	$rows = lecturaArray($sql);

	/*echo '<pre>';
	print_r($rows);
	echo '</pre>';*/

	foreach($rows AS $row)
	{
		/*echo '<pre>';
		print_r($row);
		echo '</pre>';*/
		if($row['fx']==2)
		{
			echo 'mandarEmailMandrillPlantilla';
			//$rs = mandarEmailMandrillPlantilla($row['email_dest'], $row['nombre_dest'], $row['asunto'], $row['cuerpo'], $row['plantilla'], $row['email_orig'], $row['nombre_orig'], $row['bcc']);
		}else if($row['fx']==3){
			echo 'mandarEmailMandrillPlantillaSoloContenido';
			$rs = mandarEmailMandrillPlantillaSoloContenido($row['email_dest'], $row['nombre_dest'], $row['asunto'], unserialize ($row['cuerpo']), $row['plantilla'], $row['email_orig'], $row['nombre_orig']);
		}else{
			echo 'mandarEmailMandrill<br>';
			//$rs = mandarEmailMandrill($row['email_dest'], $row['nombre_dest'], $row['asunto'], unserialize ($row['cuerpo']), $row['email_orig'], $row['nombre_orig'], $row['cc']);
		}
		echo '<pre>';
		print_r($rs);
		echo '</pre>';
		if(isset($rs[0]['status']) && ($rs[0]['status']=='sent' || $rs[0]['status']=='queued') )
		{
			//Borrarr email enviado de la tabla de email en cola
			echo 'borrar email: '. $row['id'];
			//borrarEmailCola($row['id']);
		}else{
			//email no enviado. Lo marcamos como no enviable
			echo 'email no enviable: '. $row['id'];
			//marcarNoEnviable($row['id']);
		}
	}
}

function borrarEmailCola($id)
{
	$sql = "DELETE FROM email_cue WHERE id=$id ";
	escritura($sql);
	return true;
}

function marcarNoEnviable($id)
{
	$sql = "UPDATE email_cue SET no_enviable=1 WHERE id=$id ";
	escritura($sql);
	return true;
}
?>