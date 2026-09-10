<?php
//Plantilla (Invitación de Hotela a usuario)
if(!empty($puntos)){
	$txtBoton = $txt7.' '.$txt8.' '.$puntos.' '.$txt9;
}else{
	$txtBoton = $txt7;
}
if(!empty($txtExtra)){
	//El texto extra contiene frases delimitadas por # 
	$arrayTxtExtra = explode('#', $txtExtra);
}
$asunto = $datosInvitador['nombre'].' '.$asun;
$cuerpo = '<h2>'.$txt1.'!</h2>
<br><br>
<strong>'.$datosInvitador['nombre'].'</strong> '.$txt2.' <a href="'.BASE_PATH.'">Hotelinking</a>.<br><br>';
if ($txtExtra=='0'){
	$cuerpo .= $txt3.' '.$datosInvitador['nombre'].', '.$txt4.'.
<br><br>';
}

$cuerpo .= $txt5.'.<br><br>';
if($txtExtra!='0'){
	$cuerpo .= $arrayTxtExtra[0];
}
$cuerpo .= $txt6.'.
<br><br>
<a href="'.$urlInvitacion.'" title="'.$txtBoton.'">
  <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonBlock" width="100%" align="center">
      <tbody class="mcnButtonBlockOuter">
          <tr>
              <td align="center" class="mcnButtonBlockInner" style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top">
              <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-top-left-radius: 5px;border-top-right-radius: 5px;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;background-color: #65c3df;">
                  <tbody>
                      <tr>
                      <td align="center" class="mcnButtonContent" style="font-family: Arial; font-size: 16px; padding: 16px;" valign="middle"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="color:rgb(255, 255, 255)"><strong><span style="font-size:14px">'.$txtBoton.'</span></strong></span></span></span>
                      </td>
                      </tr>
                  </tbody>
              </table>
              </td>
          </tr>
      </tbody>
  </table>
</a>
<br>'.$linkBelow.'<br>
<br><a href="'.$urlInvitacion.'">'.$urlInvitacion.'</a><br><br>';
if($txtExtra!='0'){
	$cuerpo .= $arrayTxtExtra[1];
}
$cuerpo .= '<br><br>'.$txt10.',';
?>