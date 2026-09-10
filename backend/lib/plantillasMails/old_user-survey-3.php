<?php
global $urlTree;
// Plantilla
$link_puntos = BASE_PATH.$urlTree['user-points'];
$link_tienda_adq = BASE_PATH.$urlTree['tienda'].'/?adre=adq';

$asunto = $arrayDatosEmail['nombre'].' '.$asun.'!';
$cuerpo = '<h2>'.$txt1.' '.$arrayDatosEmail['nombre'].',</h2>
<br>'.$txt2.':
<br><br>
<ul>
<li><strong>'.$puntos_survey.'</strong> '.$txt3.' <strong>'.$arrayDatosHotel['hotelName'].'</strong></li>';
if($tweetOK==200){// si ha compartido
	$cuerpo .= '<li><strong>'.$puntos_shr_survey.'</strong> '.$txt4.'.</li>';
}
$cuerpo .= '</ul>
'.$txt5.'.
<br><br>
'.$txt6.':<br>
<ul>
<li>'.$txt7.'</li>
<li>'.$txt8.'</li>
<li>'.$txt9.'</li>
</ul>
'.$txt10.' <a href="'.$link_puntos.'"><strong>'.$puntos_hl.'</strong></a> 
<br><br>
<a href="'.$link_tienda_adq.'" title="'.$txt11.'">
  <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonBlock" width="100%" align="center">
      <tbody class="mcnButtonBlockOuter">
          <tr>
              <td align="center" class="mcnButtonBlockInner" style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top">
              <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-top-left-radius: 5px;border-top-right-radius: 5px;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;background-color: #65c3df;">
                  <tbody>
                      <tr>
                      <td align="center" class="mcnButtonContent" style="font-family: Arial; font-size: 16px; padding: 16px;" valign="middle"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="color:rgb(255, 255, 255)"><strong><span style="font-size:14px">'.$txt11.'</span></strong></span></span></span>
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
<br><a href="'.$link_tienda_adq.'">'.$link_tienda_adq.'</a>
<br><br>
'.$txt12.','
?>