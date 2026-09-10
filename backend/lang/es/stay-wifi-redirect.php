<?php

$stayWifiRedirectTreatment = [
    'informal' => [

        //first message
        'Let your friends know about us!'                                          => '¡Ayúdanos a ser más conocidos! Comparte en Facebook.',
        "Let your friends know about us with share gift"                           => "Recomienda a tus amigos de Facebook y recibe tu",
        "Click and edit your post before it is shared on Facebook with share gift" => "Puedes editar el post antes de compartirlo en Facebook.",
        'Click and edit your post before it is shared on Facebook.'                => 'Puedes editar el post antes de compartirlo en Facebook.',

        //User cancelled
        'You\'ve just canceled. Please try again.'                                 => 'Has cancelado. Por favor, prueba de nuevo para ayudarnos.',
        'Click and edit your post before it is shared on Facebook.'                => 'Podrás editar tu post antes de que lo compartas en Facebook.',

        //All OK
        'Welcome!'                                                                 => 'Te damos la bienvenida',
        'To'                                                                       => ' al ',
        'Thank you!'                                                               => '¡Gracias!',
        'Now you can access free wifi by clickings on the button below'            => 'Ya puedes navegar.',

        //User treatment
        'Mr.'                                                                      => '',
        'Ms.'                                                                      => '',

        //Button
        'Share on Facebook'                                                        => 'Compartir en Facebook',
        'Give me wifi now'                                                         => 'WiFi ahora',

        //Room Number
        'Room Number'                                                              => 'Número de habitación',
        'Room Number Text'                                                         => 'Escribe debajo el número de tu habitación para continuar:',
        'Room Number Not Hotel Text'                                               => 'Escribe debajo la contraseña proporcionada para continuar:',
        'Room Placeholder'                                                         => 'Número de habitación',
        'Room Not Hotel Placeholder'                                               => 'Contraseña',
        'Room Success Text'                                                        => 'Muchas gracias!',
        'Room Success Not Hotel Text'                                              => 'Muchas gracias!',
        'Room Error Text'                                                          => 'Por favor indica un número de habitación valido',
        'Room Error Not Hotel Text'                                                => 'Por favor indica una contraseña válida',
        'Room Wifi Now'                                                            => 'WiFi ahora',

        //skip
        "I want to skip this step and get wifi now"                                => "No quiero ayudar realmente. Continuar y saltar este paso.",
        "I want to skip this step and get wifi now gift"                           => "Gracias, pero no me interesa recibir mi regalo.",

        //Connecting to wifi
        "Connecting to wifi."                                                      => "Cargando...",
        "This can take a bit, please be patient."                                  => "Por favor espera unos segundos.",

        //Error Curl Unifi
        "Unifi Error Title"                                                        => "Acceso a la WiFi no disponible en este momento",
        "Unifi Error Message"                                                      => "Por favor, inténtalo de nuevo o contacta con recepción si el problema persiste",
        'device_blacklisted'                                                       => 'Si en unos minutos no tuvieras acceso a Internet, por favor, contacta con nuestro personal. Disculpa las molestias.',
        "try again"                                                                => "Intentar de nuevo"
    ],

    'formal' => [
        //first message
        'Let your friends know about us!'                                          => '¡Ayúdenos a ser más conocidos! Comparta en Facebook.',
        "Let your friends know about us with share gift"                           => "Recomiende a sus amigos de Facebook y reciba su",
        "Click and edit your post before it is shared on Facebook with share gift" => "Puede editar el post antes de compartirlo en Facebook.",
        'Click and edit your post before it is shared on Facebook.'                => 'Puede editar el post antes de compartirlo en Facebook.',

        //User cancelled
        'You\'ve just canceled. Please try again.'                                 => 'Ha cancelado. Por favor, pruebe de nuevo para ayudarnos.',
        'Click and edit your post before it is shared on Facebook.'                => 'Podrá editar su post antes de que lo comparta en Facebook.',

        //All OK
        'Welcome!'                                                                 => 'Le damos la bienvenida',
        'To'                                                                       => ' al ',
        'Thank you!'                                                               => '¡Gracias!',
        'Now you can access free wifi by clickings on the button below'            => 'Ya puede navegar.',

        //User treatment
        'Mr.'                                                                      => 'Sr.',
        'Ms.'                                                                      => 'Sra.',

        //Button
        'Share on Facebook'                                                        => 'Compartir en Facebook',
        'Give me wifi now'                                                         => 'WiFi ahora',

        //Room Number
        'Room Number'                                                              => 'Número de habitación',
        'Room Number Text'                                                         => 'Escriba debajo el número de su habitación para continuar:',
        'Room Number Not Hotel Text'                                               => 'Escriba debajo la contraseña proporcionada para continuar:',
        'Room Placeholder'                                                         => 'Número de habitación',
        'Room Not Hotel Placeholder'                                               => 'Contraseña',
        'Room Success Text'                                                        => '¡Muchas gracias!',
        'Room Success Not Hotel Text'                                              => '¡Muchas gracias!',
        'Room Error Text'                                                          => 'Por favor indique un número de habitación valido',
        'Room Error Not Hotel Text'                                                => 'Por favor indique una contraseña válida',
        'Room Wifi Now'                                                            => 'WiFi ahora',

        //skip
        "I want to skip this step and get wifi now"                                => "No quiero ayudar realmente. Continuar y saltar este paso.",
        "I want to skip this step and get wifi now gift"                           => "Gracias, pero no me interesa recibir mi regalo.",

        //Connecting to wifi
        "Connecting to wifi."                                                      => "Cargando...",
        "This can take a bit, please be patient."                                  => "Por favor espere unos segundos.",

        //Error Curl Unifi
        "Unifi Error Title"                                                        => "Acceso a la WiFi no disponible en este momento",
        "Unifi Error Message"                                                      => "Por favor, inténtelo de nuevo o contacte con recepción si el problema persiste",
        'device_blacklisted'                                                       => 'Si en unos minutos no tuviera acceso a Internet, por favor, contacte con nuestro personal. Disculpe las molestias.',
        "try again"                                                                => "Intentar de nuevo"
    ]
];

$stayWifiRedirect = $stayWifiRedirectTreatment[$treatment];
