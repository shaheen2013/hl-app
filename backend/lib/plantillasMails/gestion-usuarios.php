<?php
//Plantilla. Hotel regala puntos a usuario
$link_puntos = BASE_PATH.$urlTree['user-points'];
$asunto = $asun1.' '.$datosUsuario['nombre'].' '.$asun2.'!';
$cuerpo = '<h2>'.$txt1.' '.$datosUsuario['nombre'].'!</h2>
<br><br>
<strong>'.$datosHotel['hotelName'].'</strong> '.$txt2.' '.$puntos.' '.$txt3.'.
<br><br>
<a href="'.$link_puntos.'" title="'.$txt4.'">
  <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonBlock" width="100%" align="center">
      <tbody class="mcnButtonBlockOuter">
          <tr>
              <td align="center" class="mcnButtonBlockInner" style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top">
              <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-top-left-radius: 5px;border-top-right-radius: 5px;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;background-color: #65c3df;">
                  <tbody>
                      <tr>
                      <td align="center" class="mcnButtonContent" style="font-family: Arial; font-size: 16px; padding: 16px;" valign="middle"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="color:rgb(255, 255, 255)"><strong><span style="font-size:14px">'.$txt4.'</span></strong></span></span></span>
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
<br><a href="'.$link_puntos.'">'.$link_puntos.'</a>
<br><br>
'.$txt5.' <strong>'.$datosHotel['hotelName'].'</strong>'.$txt6.'.
<br><br>
'.$txt7.',';
?>