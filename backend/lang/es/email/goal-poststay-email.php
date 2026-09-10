<?php
$goalEmailLangTreatment = [
    "formal"   => [
        "asunto"                  => "" . $nombre . ", " . $goal['nombre_oferta'] . " conseguida!",
        "Thanks for choosing us!" => "GRACIAS POR COMPARTIR SU ESTANCIA CON SUS AMIGOS",
        "Dear"                    => "Estimado/a",
        "Share"                   => "Gracias por compartir con sus amigos su próxima estancia en",
        "Appreciate"              => ", para agradecérselo queremos obsequiarle con:",
        "Reedem"                  => "Canjee su regalo",
        "Thanks"                  => "Muchas gracias y esperamos que disfrute de su estancia.",
        "Unsubscribe"             => "No quiero recibir más comunicaciones.",
    ],
    "informal" => [
        "Thanks for choosing us!" => "GRACIAS POR COMPARTIR TU ESTANCIA CON TUS AMIGOS",
        "Dear"                    => "Querido/a",
        "Share"                   => "Gracias por compartir con tus amigos tu próxima estancia en",
        "Appreciate"              => ", para agradecértelo queremos obsequiarte con:",
        "Reedem"                  => "Canjea tu regalo",
        "Thanks"                  => "Muchas gracias y esperamos que disfrutes de tu estancia.",
        "Unsubscribe"             => "No quiero recibir más comunicaciones.",
    ]
];

$goalEmailLang = $goalEmailLangTreatment[$treatment];
