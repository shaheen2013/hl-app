<?php
$goalEmailLangTreatment = [
    "formal"   => [
        "asunto"                  => "" . $nombre . ", confirmación de su cupón.",
        "Thanks for choosing us!" => "¡Gracias por reservar con nosotros!",
        "Dear"                    => "Estimado",
        "In name"                 => "En nombre del equipo de",
        "Appreciate"              => ", queremos agradecerle que nos haya elegido para su estancia con este regalo.",
        "Show"                    => "Muestre este email en recepción, con el siguiente código promocional para recibir su regalo.",
        "Thanks"                  => "Muchas gracias y esperamos que disfrute de su estancia.",
        "Unsubscribe"             => "No quiero recibir más comunicaciones.",
    ],
    "informal" => [
        "asunto"                  => "" . $nombre . ", confirmación de tu cupón.",
        "Thanks for choosing us!" => "¡Gracias por reservar con nosotros!",
        "Dear"                    => "Querido",
        "In name"                 => "En nombre del equipo de",
        "Appreciate"              => ", queremos agradecerte que nos hayas elegido para tu estancia con este regalo.",
        "Show"                    => "Muestra este email en recepción, con el siguiente código promocional para recibir tu regalo.",
        "Thanks"                  => "Muchas gracias y esperamos que disfrutes de tu estancia.",
        "Unsubscribe"             => "No quiero recibir más comunicaciones.",
    ]
];

$goalEmailLang = $goalEmailLangTreatment[$treatment];
