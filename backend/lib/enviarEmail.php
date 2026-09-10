<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

function mandarEmailMandrill($email_destino, $nombre_destino, $asunto, $cuerpo, $emailHotel = 'helpdesk@hotelinking.com', $nombreHotel = 'hotelinking.com', $cc = '')
{
    require RUTA_DIR . LIB . 'composer/vendor/autoload.php';
    global $log;
    try {
        $mandrill = new Mandrill(defined('MANDRILL_API_KEY') ? MANDRILL_API_KEY : '');
        $message = array(
            'html' => null,
            'text' => $cuerpo,
            'subject' => $asunto,
            'from_email' => $emailHotel,
            'from_name' => $nombreHotel,
            'to' => array(
                array(
                    'email' => $email_destino,
                    'name' => 'IT',
                    'type' => 'to',
                ),
            ),
            'headers' => array(),
            'important' => false,
            'track_opens' => null,
            'track_clicks' => null,
            'auto_text' => null,
            'auto_html' => null,
            'inline_css' => null,
            'url_strip_qs' => null,
            'preserve_recipients' => null,
            'view_content_link' => null,
            'bcc_address' => null,
            'tracking_domain' => null,
            'signing_domain' => null,
            'return_path_domain' => null,
            'merge' => true,
            'merge_language' => 'mailchimp',
            'global_merge_vars' => array(),
            'merge_vars' => array(),
            'tags' => array('internal_warning'),
            'subaccount' => null,
            'google_analytics_domains' => array('example.com'),
            'google_analytics_campaign' => 'message.from_email@example.com',
            'metadata' => array('website' => 'www.example.com'),
            'recipient_metadata' => array(),
            'attachments' => array(),
            'images' => array(),
        );
        $async = true;
        $ip_pool = 'Main Pool';
        $mandrill->messages->send($message, $async, $ip_pool);
    } catch (Mandrill_Error $e) {
        // Mandrill errors are thrown as exceptions
        $log->error('A mandrill error occurred:' . get_class($e) . ' - ' . $e->getMessage());
    }
}

// Plantillas: 'envelope', 'standard-template'
function mandarEmailMandrillPlantilla($email_destino, $nombre_destino, $asunto, $cuerpo, $plantilla = 'envelope', $emailHotel = 'helpdesk@hotelinking.com', $nombreHotel = 'hotelinking.com', $bcc = '')
{
    if (!filter_var($email_destino, FILTER_VALIDATE_EMAIL)) {
        //Email incorrecto
        $response[] = 'Incorrect email';
    } else {
        require_once RUTA_DIR . LIB . 'composer/vendor/mandrill/mandrill/src/Mandrill.php';
        $mandrill = new Mandrill('mm0pT3_vlLe7qerj8dhGTQ');
        $message = array(
            'subject' => $asunto,
            'from_email' => $emailHotel,
            'from_name' => $nombreHotel,
            'to' => array(array('email' => $email_destino, 'name' => $nombre_destino)),
        );
        // Si hay BCC lo añadimos
        if ($bcc != '') {
            $message['to'][] = array('email' => $bcc, 'type' => 'bcc');
        }
        $template_name = $plantilla; // Nombre de la plantilla
        $template_content = array(
            array(
                'name' => 'main',
                'content' => $cuerpo,
            ),
        );
        $async = true;
        $response = $mandrill->messages->sendTemplate($template_name, $template_content, $message, $async);
    }
    global $log;
    $log->debug("respuesta mandrill", [$response]);
    return $response;
}

//FX para mandar email sin HTML. Le pasamos $template_content que contiene un array con el contenido
// Plantillas: 'beaty-template' (share), 'beauty-promo-template' (landing), 'beauty-promo-template' (goal)
// 'standard' (generica vacia), 'standard-hotel-template' (generica de hotel vacia)
function mandarEmailMandrillPlantillaSoloContenido($email_destino, $nombre_destino, $asunto, $content, $plantilla = 'envelope', $emailHotel = 'helpdesk@hotelinking.com', $nombreHotel = 'hotelinking.com')
{
    if (!filter_var($email_destino, FILTER_VALIDATE_EMAIL)) {
        //Email incorrecto
        $response[] = 'Incorrect email';
    } else {
        require_once RUTA_DIR . LIB . 'composer/vendor/mandrill/mandrill/src/Mandrill.php';
        $mandrill = new Mandrill('mm0pT3_vlLe7qerj8dhGTQ');
        $message = array(
            'subject' => $asunto,
            'from_email' => $emailHotel,
            'from_name' => $nombreHotel,
            'to' => array(array('email' => $email_destino, 'name' => $nombre_destino)),
        );
        $async = true;
        $response = $mandrill->messages->sendTemplate($plantilla, $content, $message, $async);
    }
    return $response;
}

// Función para mandar mail de prueba con madrill
//http://help.mandrill.com/entries/24486133-Does-Mandrill-have-a-test-mode-or-sandbox-
// Free Mandrill accounts can generate up to 1,000 test emails per day
// paid accounts can generate up to 10,000.
function mandarEmailMandrillSandbox($email_destino, $nombre_destino, $asunto, $cuerpo, $emailHotel = 'helpdesk@hotelinking.com', $nombreHotel = 'hotelinking.com')
{
    echo 'mandarEmailMandrillSandbox';
    //require 'lib/PHPMailer/PHPMailerAutoload.php';
    require_once 'lib/PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer;

    $mail->isSMTP(); // Set mailer to use SMTP
    // Enable encryption, 'ssl' also accepted
    $mail->Host = 'smtp.mandrillapp.com'; // Specify main and backup server
    $mail->Port = 587;
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'd.alzina@winhotelsolution.com'; // SMTP username
    $mail->Password = defined('MANDRILL_API_KEY') ? MANDRILL_API_KEY : '';
    $mail->SMTPSecure = "tls"; // SMTP password
    $mail->From = $emailHotel;
    $mail->FromName = $nombreHotel;
    //$mail->addAddress('josh@example.net', 'Josh Adams');  // Add a recipient
    $mail->addAddress("" . $email_destino . ""); // Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');
    $mail->WordWrap = 50; // Set word wrap to 50 characters
    //$mail->addAttachment('/var/tmp/file.tar.gz');  // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');  // Optional name
    $mail->isHTML(true); // Set email format to HTML
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $asunto;
    $mail->Body = $cuerpo;

    if (!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        exit;
    }
}
