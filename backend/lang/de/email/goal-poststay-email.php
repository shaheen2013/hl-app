<?php
$goalEmailLangTreatment = [
    "formal"   => [
        "asunto"                  => "" . $nombre . ", " . $goal['nombre_oferta'] . " verdient!",
        "Thanks for choosing us!" => "Vielen Dank, dass Sie Ihren Aufenthalt mit Ihren Freunden teilen",
        "Dear"                    => "Sehr geehrter Herr. / Sehr geehrte Frau.",
        "Share"                   => "Vielen Dank, dass Sie Ihren nächsten Aufenthalt im ",
        "Appreciate"              => "mit Ihren Freunden teilen; zum Dank möchten wir Ihnen folgendes Geschenk machen:",
        "Reedem"                  => "Lösen Sie Ihr Geschenk ein",
        "Thanks"                  => "Vielen Dank und genießen Sie Ihren Aufenthalt.",
        "Unsubscribe"             => "Ich möchte keine weiteren Mitteilungen mehr erhalten",
    ],
    "informal" => [
        "asunto"                  => "" . $nombre . ", " . $goal['nombre_oferta'] . " verdient!",
        "Thanks for choosing us!" => "Vielen Dank, dass Sie Ihren Aufenthalt mit Ihren Freunden teilen",
        "Dear"                    => "Sehr geehrter Herr. / Sehr geehrte Frau.",
        "Share"                   => "Vielen Dank, dass Sie Ihren nächsten Aufenthalt im ",
        "Appreciate"              => "mit Ihren Freunden teilen; zum Dank möchten wir Ihnen folgendes Geschenk machen:",
        "Reedem"                  => "Lösen Sie Ihr Geschenk ein",
        "Thanks"                  => "Vielen Dank und genießen Sie Ihren Aufenthalt.",
        "Unsubscribe"             => "Ich möchte keine weiteren Mitteilungen mehr erhalten",
    ]
];

$goalEmailLang = $goalEmailLangTreatment[$treatment];
