<?php
$goalEmailLangTreatment = [
    "formal"   => [
        "asunto"                  => "" . $nombre . ", conferma del tuo coupon.",
        "Thanks for choosing us!" => "Grazie per aver prenotato con noi!",
        "Dear"                    => "Caro",
        "In name"                 => "A nome del team di ",
        "Appreciate"              => ", vorremmo ringraziarla per averci scelto per il suo soggiorno facendole omaggio di questo regalo.",
        "Show"                    => "Deve mostrare questa email alla reception con il seguente codice promozionale per ricevere il tuo regalo.",
        "Thanks"                  => "Grazie mille e ci auguriamo che si goda il suo soggiorno.",
        "Unsubscribe"             => "Non voglio ricevere altre informazioni.",
    ],
    "informal" => [
        "asunto"                  => "" . $nombre . ", conferma del tuo coupon.",
        "Thanks for choosing us!" => "Grazie per aver prenotato con noi!",
        "Dear"                    => "Caro/a",
        "In name"                 => "A nome del team di",
        "Appreciate"              => ", vorremmo ringraziarti per averci scelto per il tuo soggiorno con questo regalo.",
        "Show"                    => "Mostra questa email alla reception, con il seguente codice promozionale per ricevere il tuo regalo.",
        "Thanks"                  => "Grazie mille e ci auguriamo che si goda il suo soggiorno.",
        "Unsubscribe"             => "Non voglio ricevere altre informazioni",
    ]
];

$goalEmailLang = $goalEmailLangTreatment[$treatment];
