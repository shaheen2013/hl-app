<?php

$stayWifiRedirectTreatment = [
    'informal' => [
        //first message
        'Let your friends know about us!'                                          => 'Partagez l´hôtel sur Facebook',
        "Let your friends know about us with share gift"                           => "Recommandez-nous à vos amis sur Facebook et obtenez",
        "Click and edit your post before it is shared on Facebook with share gift" => "Vous pouvez modifier le message avant de le partager sur Facebook.",
        'Click and edit your post before it is shared on Facebook.'                => 'Vous pouvez modifier le message avant de le partager sur Facebook.',

        //User cancelled
        'You\'ve just canceled. Please try again.'                                 => 'Vous avez interrompu, veuillez réessayer.',
        'Click and edit your post before it is shared on Facebook.'                => 'Vous pouvez éditer votre message avant de le partager sur Facebook.',

        //All OK
        'Welcome!'                                                                 => 'Nous vous souhaitons la bienvenue',
        'To'                                                                       => ' à ',
        'Thank you!'                                                               => 'Merci!',
        'Now you can access free wifi by clickings on the button below'            => 'Connectez-vous maintenant au WiFi',

        //User treatment
        'Mr.'                                                                      => 'Mr.',
        'Ms.'                                                                      => 'Mme.',

        //Button
        'Share on Facebook'                                                        => 'Partager sur Facebook',
        'Give me wifi now'                                                         => 'Obtenez maintenant le WiFi',

        //Room Number
        'Room Number'                                                              => 'Numéro de chambre',
        'Room Number Text'                                                         => "Écrivez ci-dessous le numéro de votre chambre pour continuer:",
        'Room Number Not Hotel Text'                                               => "Saisissez ci-après le mot de passe fourni pour pouvoir continuer",
        'Room Placeholder'                                                         => 'Entrer le numéro de chambre ici ',
        'Room Not Hotel Placeholder'                                               => 'Mot de passe',
        'Room Success Text'                                                        => 'Merci beaucoup!',
        'Room Success Not Hotel Text'                                              => 'Merci beaucoup!',
        'Room Error Text'                                                          => "Veuillez entrer le numéro correcte de la chambre",
        'Room Error Not Hotel Text'                                                => 'Veuillez indiquer un mot de passe valable',
        'Room Wifi Now'                                                            => 'Accéder au wifi',

        //skip
        "I want to skip this step and get wifi now"                                => "Non merci, passer cette étape.",
        "I want to skip this step and get wifi now gift"                           => "Merci, mais je ne veux pas recevoir mon cadeau.",

        //Connecting to wifi
        "Connecting to wifi."                                                      => "Chargement...",
        "This can take a bit, please be patient."                                  => "Veuillez patienter quelques secondes.",

        //Error Curl Unifi
        "Unifi Error Title"                                                        => "L'accès au Wi-Fi n'est pas disponible pour le moment",
        "Unifi Error Message"                                                      => "Veuillez réessayer ou contacter la réception si le problème persiste",
        'device_blacklisted'                                                       => "Si vous n'avez pas accès à Internet dans quelques minutes, veuillez contacter notre personnel. Désolé pour le dérangement.",
        "try again"                                                                => "Réessayer"
    ],

    'formal' => [
        //first message
        'Let your friends know about us!'                                          => 'Partagez l´hôtel sur Facebook',
        "Let your friends know about us with share gift"                           => "Recommandez-nous à vos amis sur Facebook et obtenez",
        "Click and edit your post before it is shared on Facebook with share gift" => "Vous pouvez modifier le message avant de le partager sur Facebook.",
        'Click and edit your post before it is shared on Facebook.'                => 'Vous pouvez modifier le message avant de le partager sur Facebook.',

        //User cancelled
        'You\'ve just canceled. Please try again.'                                 => 'Vous avez interrompu, veuillez réessayer.',
        'Click and edit your post before it is shared on Facebook.'                => 'Vous pouvez éditer votre message avant de le partager sur Facebook.',

        //All OK
        'Welcome!'                                                                 => 'Nous vous souhaitons la bienvenue',
        'To'                                                                       => ' à ',
        'Thank you!'                                                               => 'Merci!',
        'Now you can access free wifi by clickings on the button below'            => 'Connectez-vous maintenant au WiFi',

        //User treatment
        'Mr.'                                                                      => 'Mr.',
        'Ms.'                                                                      => 'Mme.',

        //Button
        'Share on Facebook'                                                        => 'Partager sur Facebook',
        'Give me wifi now'                                                         => 'Obtenez maintenant le WiFi',

        //Room Number
        'Room Number'                                                              => 'Numéro de chambre',
        'Room Number Text'                                                         => "Écrivez ci-dessous le numéro de votre chambre pour continuer:",
        'Room Number Not Hotel Text'                                               => "Saisissez ci-après le mot de passe fourni pour pouvoir continuer",
        'Room Placeholder'                                                         => 'Entrer le numéro de chambre ici ',
        'Room Not Hotel Placeholder'                                               => 'Mot de passe',
        'Room Success Text'                                                        => 'Merci beaucoup!',
        'Room Success Not Hotel Text'                                              => 'Merci beaucoup!',
        'Room Error Text'                                                          => "Veuillez entrer le numéro correcte de la chambre",
        'Room Error Not Hotel Text'                                                => 'Veuillez indiquer un mot de passe valable',
        'Room Wifi Now'                                                            => 'Accéder au wifi',

        //skip
        "I want to skip this step and get wifi now"                                => "Non merci, passer cette étape.",
        "I want to skip this step and get wifi now gift"                           => "Merci, mais je ne veux pas recevoir mon cadeau.",

        //Connecting to wifi
        "Connecting to wifi."                                                      => "Chargement...",
        "This can take a bit, please be patient."                                  => "Veuillez patienter quelques secondes.",

        //Error Curl Unifi
        "Unifi Error Title"                                                        => "L'accès au Wi-Fi n'est pas disponible pour le moment",
        "Unifi Error Message"                                                      => "Veuillez réessayer ou contacter la réception si le problème persiste",
        'device_blacklisted'                                                       => "Si vous n'avez pas accès à Internet dans quelques minutes, veuillez contacter notre personnel. Désolé pour le dérangement.",
        "try again"                                                                => "Réessayer"
    ]
];

$stayWifiRedirect = $stayWifiRedirectTreatment[$treatment];

?>
