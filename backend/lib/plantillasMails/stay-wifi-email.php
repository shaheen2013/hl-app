<?php
//Variables que se pueden usar en esta plantilla
// $name: nombre del usuario
// $hotel['hotelName'], nombre del hotel

$asunto = $name.', '.$stayWifiEmailLang['asunto'];
$cuerpo = $stayWifiEmailLang['accessWifi'].' <b>'.$hotel['hotelName'].'</b>'.$stayWifiEmailLang['clickButton'].' 
<br><br>
<a href="'.$urlWifi['url'].'" title="'.$stayWifiEmailLang['button'].'">
  <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonBlock" width="100%" align="center">
      <tbody class="mcnButtonBlockOuter">
          <tr>
              <td align="center" class="mcnButtonBlockInner" style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top">
              <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-top-left-radius: 5px;border-top-right-radius: 5px;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;background-color: #65c3df;">
                  <tbody>
                      <tr>
                      <td align="center" class="mcnButtonContent" style="font-family: Arial; font-size: 16px; padding: 16px;" valign="middle"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="color:rgb(255, 255, 255)"><strong><span style="font-size:14px">'.$stayWifiEmailLang['button'].'</span></strong></span></span></span>
                      </td>
                      </tr>
                  </tbody>
              </table>
              </td>
          </tr>
      </tbody>
  </table>
</a>
<br>'.$stayWifiEmailLang['linkBelow'].'<br>
<br><a href="'.$urlWifi['url'].'">LINK</a>
<br><br>';

//Formato de la plantilla para "mandarEmailMandrillPlantillaSoloContenido"
$template_content = array(
		array(
		'name' => 'body_content00',
		'content' => $cuerpo
		),
		array(
		'name' => 'cta_content00',
		'content' => ''
		),
		// Preview area
		array(
		'name' => 'preheader_content00',
		'content' => $stayWifiEmailLang['preview']
		),
		array(
		'name' => 'logo_content00',
		'content' => '<img src="' . $hotelLogo . '" alt="hotel logo">'
		),
		array(
		'name' => 'name_content00',
		'content' => '<h2>' . $hotel['hotelName'] . '</h2>'
		),
		array(
		'name' => 'unsuscribe_content',
		'content' => ''
		)
	);
?>