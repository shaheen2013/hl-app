<?php
$gdprTreatments = [
    'informal' => [
        "intro_title"                      => "Tu privacidad es importante para nosotros",
        "intro_question"                   => "¿Estás alojado en " . $hotelInfo['hotelName'] . "?",
        "intro_answer_client"              => "Sí, estoy alojado",
        "intro_answer_not_client"          => "No, no estoy alojado",
        "intro_accept_restrictive"         => "Continuar",
        "intro_accept_conditions"          => "Aceptar y continuar",
        "checkbox_notifications"           => "Acepto la cesión de mis datos a $1 para el envío de comunicaciones comerciales",
        "commercial_profile"               => "Acepto que mis datos sean incluidos en un perfil comercial para recibir ofertas personalizadas de $1",
        "go_back"                          => "Volver",
        "confirm"                          => "Confirmar",
        'PMS validator require msg'        => "Para poder validar que eres cliente de este establecimiento, por favor introduce los siguientes datos:",
        'PMS validator require name'       => 'Introduce tu nombre completo:',
        'PMS validator require first name' => 'Introduce tu nombre:',
        'PMS validator require surname'    => 'Introduce tu primer apellido:',
        'PMS validator require room'       => 'Introduce tu número de habitación:',
        'PMS validator require document id'=> 'Introduce tu DNI:',
        'PMS validator title list users'   => 'Selecciona tu perfil:',
        'OR'                               => 'O',
        'PMS validator access code'        => "Introduce tu código de acceso:",
        'PMS validator radius ticket'      => "Introduce tu ticket:",
        'PMS validator radiusTicketMsg'    => "Si no dispones de un ticket, pregunta a nuestro personal.",
        "accommodated tab"                 => "Estoy alojado",
        "code tab"                         => "Tengo un código"
    ],

    'formal' => [
        "intro_title"                      => "Su privacidad es importante para nosotros",
        "intro_question"                   => "¿Está alojado en " . $hotelInfo['hotelName'] . "?",
        "intro_answer_client"              => "Sí, estoy alojado",
        "intro_answer_not_client"          => "No, no estoy alojado",
        "intro_accept_restrictive"         => "Continuar",
        "intro_accept_conditions"          => "Aceptar y continuar",
        "checkbox_notifications"           => "Acepto la cesión de mis datos a $1 para el envío de comunicaciones comerciales",
        "commercial_profile"               => "Acepto que mis datos sean incluidos en un perfil comercial para recibir ofertas personalizadas de $1",
        "go_back"                          => "Volver",
        "confirm"                          => "Confirmar",
        'PMS validator require msg'        => "Para poder validar que es usted un cliente de este establecimiento, por favor introduzca los siguientes datos:",
        'PMS validator require name'       => 'Introduzca su nombre completo:',
        'PMS validator require first name' => 'Introduzca su nombre:',
        'PMS validator require surname'    => 'Introduzca su primer apellido:',
        'PMS validator require room'       => 'Introduzca su número de habitación:',
        'PMS validator require document id'=> 'Introduzca su DNI:',
        'PMS validator title list users'   => 'Selecione su perfil:',
        'OR'                               => 'O',
        'PMS validator access code'        => "Introduzca su código de acceso:",
        'PMS validator radius ticket'      => "Introduzca su ticket:",
        'PMS validator radiusTicketMsg'    => "Si no dispone de un ticket, pregunte a nuestro personal.",
        "accommodated tab"                 => "Estoy alojado",
        "code tab"                         => "Tengo un código"
    ]
];

$gdprLang = $gdprTreatments[$_SESSION['hotel']['treatment']];
