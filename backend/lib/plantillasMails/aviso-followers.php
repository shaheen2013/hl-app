<?php
global $urlTree;
if(!empty($row['nombre'])){
	$userName = $row['nombre'];
}else{
	$userName = 'user-name';
}

$asunto = $asun;
$cuerpo = $txt1.' <strong>'.$aviso_followers['name'].'</strong>,<br><br>
'.$txt2.'.<br><br>
'.$txt3.' <strong>'.$userName.'</strong> '.$txt4.' '.$followers_usuario.' '.$txt5.' '.$social.'.<br><br>

<a href="'.$link_usuario.'" title="'.$txt6.' '.$userName.$txt7.'">
  <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonBlock" width="100%" align="center">
      <tbody class="mcnButtonBlockOuter">
          <tr>
              <td align="center" class="mcnButtonBlockInner" style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top">
              <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-top-left-radius: 5px;border-top-right-radius: 5px;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;background-color: #65c3df;">
                  <tbody>
                      <tr>
                      <td align="center" class="mcnButtonContent" style="font-family: Arial; font-size: 16px; padding: 16px;" valign="middle"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="font-family:arial,helvetica neue,helvetica,sans-serif"><span style="color:rgb(255, 255, 255)"><strong><span style="font-size:14px">'.$txt6.' '.$userName.$txt7.'</span></strong></span></span></span>
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
<br><a href="'.$link_usuario.'">'.$link_usuario.'</a>
<br><br>
'.$txt8.',';

?>