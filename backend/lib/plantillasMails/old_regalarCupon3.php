<?php
global $urlTree;
include_once LIB.'sanitize.php';
// Plantilla. Email a amigo al que regalan cupón
$urlVouchers = BASE_PATH.$urlTree['user-ofertas'];
$linkOffer = BASE_PATH.$urlTree['cupon'].'/'.string_sanitize($datosCupon['nombre_oferta']).'/'.$datosCupon['id_oferta'];

$asunto = $asun.' '.$regalador['nombre'].'!';
$cuerpo = '<div align="center"><h2>'.$txt1.'!</h2>
<br><br>
'.$txt2.' '.$regalador['nombre'].'. '.$txt3.' <a href="'.BASE_PATH.'">Hotelinking</a>, '.$txt4.' '.$datosCupon['hotelName'].'
<br><br>
'.$txt7.' <strong>Hotelinking</strong>.
<br><br>
<a href="'.$urlVouchers.'" title="'.$txt8.'">
  <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonBlock" width="100%" align="center">
      <tbody class="mcnButtonBlockOuter">
          <tr>
              <td align="center" class="mcnButtonBlockInner" style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top">
              <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-top-left-radius: 5px;border-top-right-radius: 5px;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;background-color: #65c3df;">
                  <tbody>
                      <tr>
                      <td align="center" class="mcnButtonContent" style="font-family: Arial; font-size: 16px; padding: 16px;" valign="middle"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="color:rgb(255, 255, 255)"><strong><span style="font-size:14px">'.$txt8.'</span></strong></span></span></span>
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
<br><a href="'.$urlVouchers.'">'.$urlVouchers.'</a>
<br><br>
'.$txt9.': <strong>'.$datosCupon['voucher'].'</strong>
<a href="'.$linkOffer.'" >'.$txt10.'</a>
<br><br>
'.$txt11.',</div>';
?>