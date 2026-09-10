<?php
$goalEmailLangTreatment = [
    "formal"   => [
        "asunto"                  => "" . $nombre . ", " . $goal['nombre_oferta'] . " conseguida!",
        "Thanks for choosing us!" => "Gràcies per compartir la vostra estada amb els amics",
        "Dear"                    => "Benvolgut",
        "Share"                   => "Gràcies per compartir amb els amics la vostra pròxima estada a ",
        "Appreciate"              => ", per agrair-vos-ho us volem obsequiar amb",
        "Reedem"                  => "Bescanvieu el regal",
        "Thanks"                  => "Moltes gràcies i esperem que gaudiu de l'estada.",
        "Unsubscribe"             => "No vull rebre més comunicacions.",
    ],
    "informal" => [
        "asunto"                  => "" . $nombre . ", " . $goal['nombre_oferta'] . " conseguida!",
        "Thanks for choosing us!" => "Gràcies per compartir la teva estada amb els amics",
        "Dear"                    => "Benvolgut/uda",
        "Share"                   => "Gràcies per compartir amb els amics la teva pròxima estada a",
        "Appreciate"              => ", per agrair-t’ho et volem obsequiar amb:",
        "Reedem"                  => "Bescanvia el regal",
        "Thanks"                  => "Moltes gràcies i esperem que gaudiu de l'estada",
        "Unsubscribe"             => "No vull rebre més comunicacions.",
    ]
];

$goalEmailLang = $goalEmailLangTreatment[$treatment];


