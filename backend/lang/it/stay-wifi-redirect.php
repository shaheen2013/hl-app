<?php

$stayWifiRedirectTreatment = [
    'informal' => [
        //first message
        'Let your friends know about us!'                                          => 'Aiutaci a farci conoscere di più! Condividi su Facebook.',
        "Let your friends know about us with share gift"                           => "Raccomandaci a tuoi amici di Facebook e riceverai il tuo",
        "Click and edit your post before it is shared on Facebook with share gift" => "Puoi modificare il post prima di condividerlo su Facebook.",
        'Click and edit your post before it is shared on Facebook.'                => 'Puoi modificare il post prima di condividerlo su Facebook. ',

        //User cancelled
        'You\'ve just canceled. Please try again.'                                 => 'Hai cancellato. Per favore, prova di nuovo per aiutarci.',
        'Click and edit your post before it is shared on Facebook.'                => 'Potrai modificare il tuo post prima di condividerlo su Facebook.',

        //All OK
        'Welcome!'                                                                 => 'Ti diamo il benvenuto',
        'To'                                                                       => ' al ',
        'Thank you!'                                                               => 'Grazie!',
        'Now you can access free wifi by clickings on the button below'            => 'Adesso puoi navigare.',

        //User treatment
        'Mr.'                                                                      => 'Sig.',
        'Ms.'                                                                      => 'Sig.ra',

        //Button
        'Share on Facebook'                                                        => 'Condividi su Facebook',
        'Give me wifi now'                                                         => 'Wi-Fi adesso',

        //Room Number
        'Room Number'                                                              => 'Numero della camera',
        'Room Number Text'                                                         => 'Introduci qui sotto il numero della camera per continuare:',
        'Room Number Not Hotel Text'                                               => 'Digita qui sotto la password fornita per continuare',
        'Room Placeholder'                                                         => 'Numero della camera',
        'Room Not Hotel Placeholder'                                               => 'Password',
        'Room Success Text'                                                        => 'Grazie mille!',
        'Room Success Not Hotel Text'                                              => 'Grazie mille!',
        'Room Error Text'                                                          => 'Per cortesia indica un numero di camera corretto',
        'Room Error Not Hotel Text'                                                => 'Per cortesia, indica una password valida',
        'Room Wifi Now'                                                            => 'Wi-Fi adesso',

        //skip
        "I want to skip this step and get wifi now"                                => "In realtà non voglio aiutare. Continuare e saltare questo passaggio.",
        "I want to skip this step and get wifi now gift"                           => "Grazie, però non mi interessa ricevere il mio regalo.",

        //Connecting to wifi
        "Connecting to wifi."                                                      => "Sta caricando…",
        "This can take a bit, please be patient."                                  => "Per cortesia, aspetta un momento.",

        //Error Curl Unifi
        "Unifi Error Title"                                                        => "Accesso alla Wi-Fi non disponibile in questo momento",
        "Unifi Error Message"                                                      => "Per cortesia, prova di nuovo o contatta la recepcion se il problema persiste.",
        'device_blacklisted'                                                       => "Se non hai accesso a Internet in pochi minuti, ti preghiamo di contattare il nostro staff. Ci scusiamo per l'inconveniente.",
        "try again"                                                                => "Riprovare"
    ],

    'formal' => [
        //first message
        'Let your friends know about us!'                                          => 'Aiutaci ad essere più conosciuti! Condivida su Facebook',
        "Let your friends know about us with share gift"                           => "Raccomandi ai suoi amici di Facebook e riceva",
        "Click and edit your post before it is shared on Facebook with share gift" => "Può editare il post prima di condividerlo su Facebook",
        'Click and edit your post before it is shared on Facebook.'                => 'Può editare il post prima di condividerlo su Facebook',

        //User cancelled
        'You\'ve just canceled. Please try again.'                                 => 'Ha cancellato. Per cortesia, provi di nuovo per aiutarci.',
        'Click and edit your post before it is shared on Facebook.'                => 'Potrà editare il suo post prima di condividerlo su Facebook',

        //All OK
        'Welcome!'                                                                 => 'Ti diamo il benvenuto',
        'To'                                                                       => ' al ',
        'Thank you!'                                                               => 'Grazie!',
        'Now you can access free wifi by clickings on the button below'            => 'Ora può navigare',

        //User treatment
        'Mr.'                                                                      => 'Sig.',
        'Ms.'                                                                      => 'Sig.ra',

        //Button
        'Share on Facebook'                                                        => 'Condividi su Facebook',
        'Give me wifi now'                                                         => 'Wi-Fi adesso',

        //Room Number
        'Room Number'                                                              => 'Numero della camera',
        'Room Number Text'                                                         => 'Introduca qui sotto il numero della camera per continuare:',
        'Room Number Not Hotel Text'                                               => 'Scriva qui sotto la password che può richiedere in reception.',
        'Room Placeholder'                                                         => 'Numero della camera',
        'Room Not Hotel Placeholder'                                               => 'Password',
        'Room Success Text'                                                        => 'Grazie mille!',
        'Room Success Not Hotel Text'                                              => 'Grazie mille!',
        'Room Error Text'                                                          => 'Per cortesia, indichi un numero di camera valido',
        'Room Error Not Hotel Text'                                                => 'Per cortesia, indichi un password valido',
        'Room Wifi Now'                                                            => 'Wi-Fi adesso',

        //skip
        "I want to skip this step and get wifi now"                                => "In realtà non voglio aiutare. Continuare e saltare questo passaggio.",
        "I want to skip this step and get wifi now gift"                           => "Grazie, però non mi interessa ricevere il mio regalo.",

        //Connecting to wifi
        "Connecting to wifi."                                                      => "Sta caricando…",
        "This can take a bit, please be patient."                                  => "Per cortesia attenda qualche secondo. ",

        //Error Curl Unifi
        "Unifi Error Title"                                                        => "Accesso alla Wi-Fi non disponibile in questo momento",
        "Unifi Error Message"                                                      => "Per cortesia, riprova di nuovo o contatta la reception se il problema persiste",
        'device_blacklisted'                                                       => "Se non ha accesso a Internet in pochi minuti, la preghiamo di contattare il nostro staff. Ci scusiamo per l'inconveniente.",
        "try again"                                                                => "Riprovare"
    ]
];

$stayWifiRedirect = $stayWifiRedirectTreatment[$treatment];

?>
