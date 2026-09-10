<?php
// -- Asunto
$asunto = $rememberShare['hi'].' '.$row['nombre'].', '.$rememberShare['Share Your Experience at'].' '.$row['nombre_hotel'];
// -- Cuerpo
$cuerpo = $rememberShare['hi'].' '.$row['nombre'].', 
<br><br>
'.$rememberShare['Remember to share your positive experience at'].' <b>'.$row['nombre_hotel'].'</b>. '.$rememberShare['Sharing your experience can make you'].'.
<br><br>';

//Formato de la plantilla para "mandarEmailMandrillPlantillaSoloContenido"
$template_content = array(
		array(
		'name' => 'body_content00',
		'content' => $cuerpo
		),
		array(
		'name' => 'cta_content00',
		'content' => '<a href="'.$urlShare.'" title="'.$rememberShare['Share your experience and earn deals'].'">
  <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonBlock" width="100%" align="center">
      <tbody class="mcnButtonBlockOuter">
          <tr>
              <td align="center" class="mcnButtonBlockInner" style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top">
              <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-top-left-radius: 5px;border-top-right-radius: 5px;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;background-color: #65c3df;">
                  <tbody>
                      <tr>
                      <td align="center" class="mcnButtonContent" style="font-family: Arial; font-size: 16px; padding: 16px;" valign="middle"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="color:rgb(255, 255, 255)"><strong><span style="font-size:14px">'.$rememberShare['Share your experience and earn deals'].'</span></strong></span></span></span>
                      </td>
                      </tr>
                  </tbody>
              </table>
              </td>
          </tr>
      </tbody>
  </table>
</a>'
		),
		array(
		'name' => 'logo_content00',
		'content' => '<img src="' . $hotelLogo . '" alt="hotel logo">'
		),
		array(
		'name' => 'name_content00',
		'content' => '<h2>' . $row['nombre_hotel'] . '</h2>'
		),
		array(
		'name' => 'unsuscribe_content',
		'content' => '<small>'.$rememberShare['Don´t want to receive more notifications'].'?: <a href="'.$urlBaja.'" style="color:#818181">'.$rememberShare['unsubscribe from notifications'].'</a></small>'
		)
	);
?>