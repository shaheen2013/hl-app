<?php
$gdprTreatments = [
    'informal' => [
        "intro_title"                      => "La vostra privacitat és important per a nosaltres",
        "intro_question"                   => "Esteu allotjat a " . $hotelInfo['hotelName'] . "?",
        "intro_answer_client"              => "Sí, hi estic allotjat",
        "intro_answer_not_client"          => "No, no hi estic allotjat",
        "checkbox_notifications"           => "Accepto la cessió de les meves dades a $1 per a la tramesa de comunicacions comercials",
        "commercial_profile"               => "Accepto que les meves dades siguin incloses en un perfil comercial per rebre ofertes personalitzades de $1",
        "go_back"                          => "Tornar",
        "confirm"                          => "Confirmar",
        'PMS validator require msg'        => "Per poder validar que ets un client d’aquest establiment, et preguem que introdueixis les dades següents:",
        'PMS validator require name'       => 'Introdueix el teu nom complet:',
        'PMS validator require surname'    => 'Introdueix el teu primer cognom:',
        'PMS validator require room'       => 'Introdueix el número de la teva habitació:',
        'PMS validator require first name' => 'Introdueix el teu nom:',
        'PMS validator require document id'=> 'Introdueix el teu DNI:',
        'PMS validator title list users'   => 'Selecciona el teu perfil: ',
        'OR'                               => 'O',
        'PMS validator access code'        => 'Introdueixi més avall la contrasenya proporcionada:',
        'PMS validator radius ticket'      => 'Introdueix el teu ticket:',
        'PMS validator radiusTicketMsg'    => "Si no disposes d’un ticket, pregunta al nostre personal.",
        "intro_accept_restrictive"         => "Continuar",
        "intro_accept_conditions"          => "Acceptar i continuar",
        "accommodated tab"                 => "Hi estic allotjat",
        "code tab"                         => "Tinc un codi"
    ],

    'formal' => [
        "intro_title"                      => "La seva privacitat és important per a nosaltres",
        "intro_question"                   => "Està allotjat a " . $hotelInfo['hotelName'] . "?",
        "intro_answer_client"              => "Sí, hi estic allotjat",
        "intro_answer_not_client"          => "No, no hi estic allotjat",
        "checkbox_notifications"           => "Accepto la cessió de les meves dades a $1 per a la tramesa de comunicacions comercials",
        "commercial_profile"               => "Accepto que les meves dades siguin incloses en un perfil comercial per rebre ofertes personalitzades de $1",
        "go_back"                          => "Tornar",
        "confirm"                          => "Confirmar",
        'PMS validator require msg'        => "Per poder validar que sou un client d’aquest establiment, us preguem que introduïu les dades següents:",
        'PMS validator require name'       => 'Introduïu el vostre nom complet:',
        'PMS validator require surname'    => 'Introduïu el vostre primer cognom:',
        'PMS validator require room'       => 'Introduïu el número de la vostra habitació:',
        'PMS validator require first name' => 'Introduïu el vostre nom:',
        'PMS validator require document id'=> 'Introdueixi el seu DNI:',
        'PMS validator title list users'   => 'Selecciona el seu perfil : ',
        'OR'                               => 'O',
        'PMS validator access code'        => 'Introdueixi més avall la contrasenya proporcionada:',
        'PMS validator radius ticket'      => 'Introdueixi el seu ticket:',
        'PMS validator radiusTicketMsg'    => "Si no disposa d'un ticket pregunti al nostre personal.",
        "intro_accept_restrictive"         => "Continuar",
        "intro_accept_conditions"          => "Acceptar i continuar",
        "accommodated tab"                 => "Hi estic allotjat",
        "code tab"                         => "Tinc un codi"
    ]
];

$gdprLang = $gdprTreatments[$_SESSION['hotel']['treatment']];
