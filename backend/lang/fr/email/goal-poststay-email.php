<?php
$goalEmailLangTreatment = [
    "formal"   => [
        "asunto"                  => "" . $nombre . ", " . $goal['nombre_oferta'] . " gagné!",
        "Thanks for choosing us!" => "Merci de partager votre séjour avec vos amis",
        "Dear"                    => "Cher / Chère",
        "Share"                   => "Merci de partager votre prochain séjour à l’hôtel ",
        "Appreciate"              => "avec vos amis ; pour vous remercier nous souhaitons vous offrir",
        "Reedem"                  => "Utilisez votre cadeau",
        "Thanks"                  => "Un grand merci et bon séjour !",
        "Unsubscribe"             => "Je ne souhaite pas recevoir d’autres communications.",
    ],
    "informal" => [
        "asunto"                  => "" . $nombre . ", " . $goal['nombre_oferta'] . " gagné!",
        "Thanks for choosing us!" => "Merci de partager votre séjour avec vos amis",
        "Dear"                    => "Cher / Chère",
        "Share"                   => "Merci de partager votre prochain séjour à l’hôtel ",
        "Appreciate"              => "avec vos amis ; pour vous remercier nous souhaitons vous offrir",
        "Reedem"                  => "Utilisez votre cadeau",
        "Thanks"                  => "Un grand merci et bon séjour !",
        "Unsubscribe"             => "Je ne souhaite pas recevoir d’autres communications.",
    ]
];

$goalEmailLang = $goalEmailLangTreatment[$treatment];
