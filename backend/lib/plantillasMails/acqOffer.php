<?php
// Links
$link_oferta = BASE_PATH.$urlTree['oferta'].'/'.string_sanitize($datosOferta['nombre_oferta']).'/'.$datosOferta['id_oferta'];
$link_tienda_hotel = BASE_PATH.$urlTree['tienda'].'/?hot='.$datosOferta['id_hotel'];
$link_hotel = $urlHotel;
$link_voucher = BASE_PATH.$urlTree['cupon'].'/'.string_sanitize($datosOferta['nombre_oferta']).'/'.$datosOferta['id_oferta'];
// Plantilla
$asunto= $arrayDatosUsuarioMail['nombre'].' '.$asun;
$cuerpo = $txt1.' '.$arrayDatosUsuarioMail['nombre'].',
<br><br>
'.$txt2.': <a href="'.$link_oferta.'">'.$datosOferta['nombre_oferta'].'</a> '.$txt3.' <a href="'.$link_hotel.'"><strong> '.$datosOferta['nombre_hotel'].'</strong></a> '.$txt4.'. '.$datosOferta['puntos'].' '.$txt5.'. <br><br>
'.$txt6.' <a href="'.$link_voucher.'"><strong>'.$cod_cupon.'</strong></a><br><br>';

if ($datosOferta['id_tipo_oferta']!='chk'){// Si la oferta NO es de checkin
	$cuerpo .= $txt7.' '.$datosOferta['inicio'];
	if($datosOferta['fin']!='0000-00-00'){
		$cuerpo .= ' '.$txt8.' '.$datosOferta['fin'].'. '.$txt9.'.';
	}	
	$cuerpo .= '<br><br>
'.$txt10.'.
<br><br>
'.$txt11.':<br>
'.$txt12.': '.$datosOferta['telefonoReservas'].'<br>
Email: '.$datosOferta['telefonoReservas'].'<br>
Web: <a href="' . $websiteReservaUrl . '">WEB</a><br>';
} else {
	$cuerpo .= $txt13.' <strong>'.$datosOferta['nombre_hotel'].'</strong>';
}

$cuerpo .= '<br>
<a href="'.$link_tienda_hotel.'" title="'.$txt14.' '.$datosOferta['nombre_hotel'].' '.$txt15.'">
  <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonBlock" width="100%" align="center">
      <tbody class="mcnButtonBlockOuter">
          <tr>
              <td align="center" class="mcnButtonBlockInner" style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top">
              <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-top-left-radius: 5px;border-top-right-radius: 5px;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;background-color: #65c3df;">
                  <tbody>
                      <tr>
                      <td align="center" class="mcnButtonContent" style="font-family: Arial; font-size: 16px; padding: 16px;" valign="middle"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="color:rgb(255, 255, 255)"><strong><span style="font-size:14px">'.$txt14.' '.$datosOferta['nombre_hotel'].' '.$txt15.'</span></strong></span></span></span>
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
<br><a href="'.$link_tienda_hotel.'">'.$link_tienda_hotel.'</a>
<br><br>
'.$txt16.',<br><br>'
?>