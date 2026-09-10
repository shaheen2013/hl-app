<?php
$goalEmailLangTreatment = [
    "formal"   => [
        "asunto"                  => "" . $nombre . ", " . $goal['nombre_oferta'] . " raggiunto!",
        "Thanks for choosing us!" => "GRACIAS POR COMPARTIR SU ESTANCIA CON SUS AMIGOS",
        "Dear"                    => "Caro",
        "Share"                   => "Grazie per aver condiviso con i suoi amici il suo prossimo soggiorno presso ",
        "Appreciate"              => ", per ringraziarla vogliamo offrirle un regalo.",
        "Reedem"                  => "Cambi il suo regalo",
        "Thanks"                  => " Grazie mille e ci auguriamo che si goda il suo soggiorno.",
        "Unsubscribe"             => "Non voglio ricevere altre informazioni.",
    ],
    "informal" => [
        "asunto"                  => "" . $nombre . ", " . $goal['nombre_oferta'] . " raggiunto!",
        "Thanks for choosing us!" => "Grazie per aver condiviso il tuo soggiorno con i tuoi amici",
        "Dear"                    => "Caro/a",
        "Share"                   => "Grazie per aver condiviso con i tuoi amici il tuo prossimo soggiorno presso",
        "Appreciate"              => ", per ringraziarti vogliamo farti un omaggio con:",
        "Reedem"                  => "Cambia il tuo regalo",
        "Thanks"                  => "Grazie mille e ci auguriamo che si goda il suo soggiorno",
        "Unsubscribe"             => "Non voglio ricevere altre informazioni.",
    ]
];

$goalEmailLang = $goalEmailLangTreatment[$treatment];
