<?php
$goalEmailLangTreatment = [
    "formal"   => [
        "asunto"                  => "" . $nombre . ", " . $goal['nombre_oferta'] . " earned!",
        "Thanks for choosing us!" => "THANKS FOR SHARING YOUR STAY WITH YOUR FRIENDS",
        "Dear"                    => "Dear",
        "Share"                   => "On behalf of the ",
        "Appreciate"              => "team, we would like to thank you for choosing us for your stay with this gift",
        "Reedem"                  => "Redeem your gift",
        "Thanks"                  => "Thank you very much and we hope you will enjoy your stay.",
        "Unsubscribe"             => "I do not want to receive more notifications.",
    ],
    "informal" => [
        "asunto"                  => "" . $nombre . ", " . $goal['nombre_oferta'] . " earned!",
        "Thanks for choosing us!" => "THANKS FOR SHARING YOUR STAY WITH YOUR FRIENDS",
        "Dear"                    => "Dear",
        "Share"                   => "On behalf of the ",
        "Appreciate"              => "team, we would like to thank you for choosing us for your stay with this gift",
        "Reedem"                  => "Redeem your gift",
        "Thanks"                  => "Thank you very much and we hope you’ll enjoy your stay.",
        "Unsubscribe"             => "I don´t want to receive more notifications.",
    ]
];

$goalEmailLang = $goalEmailLangTreatment[$treatment];

