<?php
$goalEmailLangTreatment = [
    "formal"   => [
        "asunto"                  => "" . $nombre . ", your voucher confirmation.",
        "Thanks for choosing us!" => "Thanks for booking with us!",
        "Dear"                    => "Dear",
        "In name"                 => "On behalf of the",
        "Appreciate"              => " team, we would like to thank you for choosing us for your stay with this gift",
        "Show"                    => "Show this email at the reception desk with the following promotional code to receive your gift.",
        "Thanks"                  => "Thank you very much and we hope you will enjoy your stay.",
        "Unsubscribe"             => "I do not want to receive more notifications.",
    ],
    "informal" => [
        "asunto"                  => "" . $nombre . ", your voucher confirmation.",
        "Thanks for choosing us!" => "Thanks for booking with us!",
        "Dear"                    => "Dear",
        "In name"                 => "On behalf of the",
        "Appreciate"              => " team, we would like to thank you for choosing us for your stay with this gift",
        "Show"                    => "Show this email at the reception desk with the following promotional code to receive your gift.",
        "Thanks"                  => "Thank you very much and we hope you’ll enjoy your stay.",
        "Unsubscribe"             => "I don´t want to receive more notifications.",
    ]
];

$goalEmailLang = $goalEmailLangTreatment[$treatment];
