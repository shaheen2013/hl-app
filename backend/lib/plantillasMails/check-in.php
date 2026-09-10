<?php
global $urlTree;
// Plantilla

// Links
$link_tienda_chkn = BASE_PATH.$urlTree['tienda'].'/?hot='.$id_hotel;
$link_a_puntos = BASE_PATH.$urlTree['user-points'];
// Email check-in
$asunto= $arrayDatosEmail['nombre'].' '.$asun1.' '.$hotelName;
$cuerpo = '<h2>'.$txt1.' '.$arrayDatosEmail['nombre'].',</h2>
<br><br>
'.$txt2.' <strong>'.$hotelName.'</strong><br> 
'.$txt3.' <strong>'.$hotelName.'</strong>
<br><br>
<a href="'.$link_tienda_chkn.'" title="'.$txt4.'">
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
<br><a href="'.$link_tienda_chkn.'">'.$link_tienda_chkn.'</a>
<br><br>
'.$txt5.' <strong>'.$hotelName.'</strong> '.$txt6.' <strong>'.$hotelName.'</strong> <a href="'.$link_a_puntos.'">'.$txt7.'</a>.
<br><br>
'.$txt8.',
';
?>