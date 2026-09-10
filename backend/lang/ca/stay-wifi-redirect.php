<?php

$stayWifiRedirectTreatment = [
    'informal' => [
        //first message
        'Let your friends know about us!'                                          => 'Ajudeu-nos a ser més coneguts! Compartiu amb Facebook.',
        "Let your friends know about us with share gift"                           => "Recomaneu els vostres amics de Facebook i rebeu el vostre",
        "Click and edit your post before it is shared on Facebook with share gift" => "Podeu editar el post abans de compartir-lo a Facebook.",
        'Click and edit your post before it is shared on Facebook.'                => 'Podeu editar el post abans de compartir-lo a Facebook.',

        //User cancelled
        'You\'ve just canceled. Please try again.'                                 => 'Heu cancel·lat. Si us plau, torneu-ho a provar per ajudar-nos.',
        'Click and edit your post before it is shared on Facebook.'                => 'Podreu editar el post abans que el compartiu a Facebook.',

        //All OK
        'Welcome!'                                                                 => 'Et donem la benvinguda',
        'To'                                                                       => ' al ',
        'Thank you!'                                                               => 'Gràcies!',
        'Now you can access free wifi by clickings on the button below'            => 'Ja podeu navegar.',

        //User treatment
        'Mr.'                                                                      => 'Sr.',
        'Ms.'                                                                      => 'Sra.',

        //Button
        'Share on Facebook'                                                        => 'Compartir a Facebook',
        'Give me wifi now'                                                         => 'WiFi ara',

        //Room Number
        'Room Number'                                                              => 'Número d’habitació',
        'Room Number Text'                                                         => 'Per continuar, escriviu sota el número de la vostra habitació:',
        'Room Number Not Hotel Text'                                               => 'Per continuar, introdueixi més avall la contrasenya proporcionada:',
        'Room Placeholder'                                                         => 'Número d’habitació',
        'Room Not Hotel Placeholder'                                               => 'Contrasenya',
        'Room Success Text'                                                        => 'Moltes gràcies!',
        'Room Success Not Hotel Text'                                              => 'Moltes gràcies!',
        'Room Error Text'                                                          => 'Us preguem que indiqueu un número d’habitació vàlid',
        'Room Error Not Hotel Text'                                                => 'Si us plau, introdueixi una contrasenya vàlida',
        'Room Wifi Now'                                                            => 'WiFi ara',

        //skip
        "I want to skip this step and get wifi now"                                => "Realment no vull ajudar. Continuar i saltar aquest pas.",
        "I want to skip this step and get wifi now gift"                           => "Gràcies, però no m’interessa rebre el regal.",

        //Connecting to wifi
        "Connecting to wifi."                                                      => "S’està carregant...",
        "This can take a bit, please be patient."                                  => "Per favor, espera uns segons.",

        //Error Curl Unifi
        "Unifi Error Title"                                                        => "Accés a la wifi no disponible en aquest moment",
        "Unifi Error Message"                                                      => "Per favor, intenta-ho de nou o contacta amb recepció si el problema persisteix",
        'device_blacklisted'                                                       => 'Si en uns minuts no tinguessis accés a Internet, si us plau, contacta amb el nostre personal. Disculpa les molèsties.',
        "try again"                                                                => "Intentar de nou"
    ],

    'formal' => [
        //first message
        'Let your friends know about us!'                                          => "Ajudi'ns a ser més coneguts! Comparteixi en Facebook.",
        "Let your friends know about us with share gift"                           => "Recomani als seus amics de Facebook i rebi el seu",
        "Click and edit your post before it is shared on Facebook with share gift" => "Pot editar el post abans de compartir-lo en Facebook.",
        'Click and edit your post before it is shared on Facebook.'                => 'Pot editar el post abans de compartir-lo en Facebook.',

        //User cancelled
        'You\'ve just canceled. Please try again.'                                 => 'Ha cancel·lat. Per favor, provi de nou per a ajudar-nos.',
        'Click and edit your post before it is shared on Facebook.'                => 'Podrà editar el seu post abans que el comparteixi en Facebook.',

        //All OK
        'Welcome!'                                                                 => 'Us donem la benvinguda',
        'To'                                                                       => ' al ',
        'Thank you!'                                                               => 'Gràcies!',
        'Now you can access free wifi by clickings on the button below'            => 'Ja pot navegar.',

        //User treatment
        'Mr.'                                                                      => 'Sr.',
        'Ms.'                                                                      => 'Sra.',

        //Button
        'Share on Facebook'                                                        => 'Compartir a Facebook',
        'Give me wifi now'                                                         => 'WiFi ara',

        //Room Number
        'Room Number'                                                              => 'Número d’habitació',
        'Room Number Text'                                                         => 'Per continuar, escrigui sota el número de la seva habitació:',
        'Room Number Not Hotel Text'                                               => 'Escrigui sota la contrasenya proporcionada per a continuar:',
        'Room Placeholder'                                                         => 'Número d’habitació',
        'Room Not Hotel Placeholder'                                               => 'Contrasenya',
        'Room Success Text'                                                        => 'Moltes gràcies!',
        'Room Success Not Hotel Text'                                              => 'Moltes gràcies!',
        'Room Error Text'                                                          => "Per favor, indiqui un número d'habitació vàlid",
        'Room Error Not Hotel Text'                                                => 'Per favor, indiqui una contrasenya vàlida',
        'Room Wifi Now'                                                            => 'WiFi ara',

        //skip
        "I want to skip this step and get wifi now"                                => "Realment no vull ajudar. Continuar i saltar aquest pas.",
        "I want to skip this step and get wifi now gift"                           => "Gràcies, però no m’interessa rebre el regal.",

        //Connecting to wifi
        "Connecting to wifi."                                                      => "S’està carregant...",
        "This can take a bit, please be patient."                                  => "Us preguem que espereu un moment.",

        //Error Curl Unifi
        "Unifi Error Title"                                                        => "Accés a la wifi no disponible en aquest moment",
        "Unifi Error Message"                                                      => "Us preguem que ho torneu a intentar o que contacteu amb recepció si el problema persisteix",
        'device_blacklisted'                                                       => 'Si en uns minuts no tingués accés a Internet, si us plau, contacti amb el nostre personal. Disculpi les molèsties.',
        "try again"                                                                => "Intentar de nou"
    ]
];

$stayWifiRedirect = $stayWifiRedirectTreatment[$treatment];

?>
