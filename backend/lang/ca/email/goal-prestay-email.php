<?php
$goalEmailLangTreatment = [
    "formal"   => [
        "asunto"                  => "" . $nombre . ", confirmació de la seva cupó",
        "Thanks for choosing us!" => "Gràcies per reservar amb nosaltres!",
        "Dear"                    => "Benvolgut",
        "In name"                 => "En nom de l’equip de",
        "Appreciate"              => ",volem agrair-vos que ens hàgiu triat per a la vostra estada amb aquest regal.",
        "Show"                    => "Mostreu el vostre correu electrònic a recepció, amb el codi promocional següent per rebre el regal.",
        "Thanks"                  => "Moltes gràcies i esperem que gaudiu de l'estada.",
        "Unsubscribe"             => "No vull rebre més comunicacions.",
    ],
    "informal" => [
        "asunto"                  => "" . $nombre . ", confirmació del teu cupó",
        "Thanks for choosing us!" => "Gràcies per reservar amb nosaltres!",
        "Dear"                    => "Benvolgut/uda",
        "In name"                 => "En nom de l’equip de",
        "Appreciate"              => ", volem agrair-te que ens hagis triat per a la teva estada amb aquest regal.",
        "Show"                    => "Mostra el teu correu electrònic a recepció, amb el codi promocional següent per rebre el regal.",
        "Thanks"                  => "Moltes gràcies i esperem que gaudiu de l'estada.",
        "Unsubscribe"             => "No vull rebre més comunicacions.",
    ]
];

$goalEmailLang = $goalEmailLangTreatment[$treatment];
