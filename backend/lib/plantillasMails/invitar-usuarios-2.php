<?php
// Plantilla
// Mail que se manda al hotelero cuando se ha acabado de importar un lista de usuarios
// Pantalla: invitar-usuarios-2

$link_boton = BASE_PATH.$urlTree['invitar-usuarios-2'].'/'.$datos_lista[0].'/';
$link_bd_usuarios = BASE_PATH.$urlTree['gestion-usuarios'];

$asunto = $asun1;
$cuerpo = '<h1>'.$txt1.' '.$datosHotel['name'].',</h1>

'.$txt2.' <strong>'.$nombre.'</strong> '.$txt3.'.
'.$txt4.' '.$datos_lista[1].' '.$txt5.'.
<br /><br />
<a href="'.$link_boton.'" title="'.$txt6.'">
  <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonBlock" width="100%" align="center">
      <tbody class="mcnButtonBlockOuter">
          <tr>
              <td align="center" class="mcnButtonBlockInner" style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top">
              <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-top-left-radius: 5px;border-top-right-radius: 5px;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;background-color: #65c3df;">
                  <tbody>
                      <tr>
                      <td align="center" class="mcnButtonContent" style="font-family: Arial; font-size: 16px; padding: 16px;" valign="middle"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="color:rgb(255, 255, 255)"><strong><span style="font-size:14px">'.$txt6.'</span></strong></span></span></span>
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
<br><a href="'.$link_boton.'">'.$link_boton.'</a>
<br /><br />
'.$txt7.'
<ul>
	<li>'.$txt8.'.</li>
	<li>'.$txt9.' <a href="'.$link_bd_usuarios.'">'.$txt10.'</a> '.$txt11.'.</li>
	<li>'.$txt12.'</li>
</ul>
<br />
<br />
'.$txt13.',';
?>