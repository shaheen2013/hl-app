<?php
// Plantilla. Usuario regala puntos a otro usuario ya existente en HL
$link_user_points = BASE_PATH.$urlTree['user-points'];
$asunto = $arrayDatosEmail['nombre'].' '.$asun.' '.$regalador['nombre'];
$cuerpo = '<h2>'.$txt1.' '.$arrayDatosEmail['nombre'].'!</h2>
<br>
'.$txt2.' <strong>'.$regalador['nombre'].'</strong> '.$txt3.' <strong>'.$puntos.'</strong> '.$txt4.' <strong>'.$datosHotelCadena['name'].'</strong>.
<br><br>
<a href="'.$link_user_points.'" title="'.$txt5.'">
  <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonBlock" width="100%" align="center">
      <tbody class="mcnButtonBlockOuter">
          <tr>
              <td align="center" class="mcnButtonBlockInner" style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top">
              <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-top-left-radius: 5px;border-top-right-radius: 5px;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;background-color: #65c3df;">
                  <tbody>
                      <tr>
                      <td align="center" class="mcnButtonContent" style="font-family: Arial; font-size: 16px; padding: 16px;" valign="middle"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="color:rgb(255, 255, 255)"><strong><span style="font-size:14px">'.$txt5.'</span></strong></span></span></span>
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
<br><a href="'.$link_user_points.'">'.$link_user_points.'</a>
<br><br>
'.$txt6.' <strong>'.$datosHotelCadena['name'].'</strong>'.$txt7.'.
<br><br>
'.$txt8.',';
?>