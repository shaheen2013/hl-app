<?php

$stayWifiRedirectTreatment = [
    'informal' => [
       //first message
       'Let your friends know about us!'                                          => 'Help us to spread the word! Share on facebook',
       "Let your friends know about us with share gift"                           => "Recommend to your Facebook friends and receive your",//texto especifico de share facebook
       "Click and edit your post before it is shared on Facebook with share gift" => "You can edit the post before you share it on Facebook",//texto especifico de share facebook segunda línea
       'Click and edit your post before it is shared on Facebook.'                => 'You can edit the post before you share it on Facebook',

       //User cancelled
       'You\'ve just canceled. Please try again.'                                 => 'You have cancelled. Please, try again to help us',
       'Click and edit your post before it is shared on Facebook.'                => 'You will be able to edit your post before you post it on Facebook',

       //All OK
       'Welcome!'                                                                 => 'We welcome you',
       'To'                                                                       => ' to ',
       'Thank you!'                                                               => 'Thank you!',
       'Now you can access free wifi by clickings on the button below'            => 'You can now browse.',

       //User treatment
       'Mr.'                                                                      => 'Mr.',
       'Ms.'                                                                      => 'Ms.',

       //Button
       'Share on Facebook'                                                        => 'Share on Facebook',
       'Give me wifi now'                                                         => 'WiFi Now',

       //Room Number
       'Room Number'                                                              => 'Room Number',
       'Room Number Text'                                                         => 'Type in below your room number to continue:',
       'Room Number Not Hotel Text'                                               => 'Type the password provided below to continue:',
       'Room Placeholder'                                                         => 'Enter your room number here',
       'Room Not Hotel Placeholder'                                               => 'Password',
       'Room Success Text'                                                        => 'The room number is correct!',
       'Room Success Not Hotel Text'                                              => 'The password is correct!',
       'Room Error Text'                                                          => 'Please, indicate a valid room number',
       'Room Error Not Hotel Text'                                                => 'Please, indicate a valid password',
       'Room Wifi Now'                                                            => 'Get WiFi',

       //skip
       'I want to skip this step and get wifi now'                                => 'I really don\'t want to help. Skip this step please.',
       "I want to skip this step and get wifi now gift"                           => "Thanks, but I don´t want to get rewarded.",

       //Connecting to wifi
       "Connecting to wifi."                                                      => "Loading...",
       "This can take a bit, please be patient."                                  => "Please hold on for a few seconds.",

       //Error Curl Unifi
       "Unifi Error Title"                                                        => "Access to WiFi not available at this time",
       "Unifi Error Message"                                                      => "Please, try again or contact with reception if the problem persists",
       'device_blacklisted'                                                       => "Sorry for the inconvenience. If in a few minutes you don't have access to Internet, please contact our staff.",
       "try again"                                                                => "Try again"
    ],

    'formal' => [
        //first message
        'Let your friends know about us!'                                          => 'Help us to spread the word! Share on facebook',
        "Let your friends know about us with share gift"                           => "Recommend to your Facebook friends and receive your",//texto especifico de share facebook
        "Click and edit your post before it is shared on Facebook with share gift" => "You can edit the post before you share it on Facebook",//texto especifico de share facebook segunda línea
        'Click and edit your post before it is shared on Facebook.'                => 'You can edit the post before you share it on Facebook',

        //User cancelled
        'You\'ve just canceled. Please try again.'                                 => 'You have cancelled. Please, try again to help us',
        'Click and edit your post before it is shared on Facebook.'                => 'You will be able to edit your post before you post it on Facebook',

        //All OK
        'Welcome!'                                                                 => 'We welcome you',
        'To'                                                                       => ' to ',
        'Thank you!'                                                               => 'Thank you!',
        'Now you can access free wifi by clickings on the button below'            => 'You can now browse.',

        //User treatment
        'Mr.'                                                                      => 'Mr.',
        'Ms.'                                                                      => 'Ms.',

        //Button
        'Share on Facebook'                                                        => 'Share on Facebook',
        'Give me wifi now'                                                         => 'WiFi Now',

        //Room Number
        'Room Number'                                                              => 'Room Number',
        'Room Number Text'                                                         => 'Type in below your room number to continue:',
        'Room Number Not Hotel Text'                                               => 'Type the password provided below to continue:',
        'Room Placeholder'                                                         => 'Enter your room number here',
        'Room Not Hotel Placeholder'                                               => 'Password',
        'Room Success Text'                                                        => 'The room number is correct!',
        'Room Success Not Hotel Text'                                              => 'The password is correct!',
        'Room Error Text'                                                          => 'Please, indicate a valid room number',
        'Room Error Not Hotel Text'                                                => 'Please, indicate a valid password',
        'Room Wifi Now'                                                            => 'Get WiFi',

        //skip
        'I want to skip this step and get wifi now'                                => 'I really don\'t want to help. Skip this step please.',
        "I want to skip this step and get wifi now gift"                           => "Thanks, but I don´t want to get rewarded.",

        //Connecting to wifi
        "Connecting to wifi."                                                      => "Loading...",
        "This can take a bit, please be patient."                                  => "Please hold on for a few seconds.",

        //Error Curl Unifi
        "Unifi Error Title"                                                        => "Access to WiFi not available at this time",
        "Unifi Error Message"                                                      => "Please, try again or contact with reception if the problem persists",
        'device_blacklisted'                                                       => "Sorry for the inconvenience. If in a few minutes you don't have access to Internet, please contact our staff.",
        "try again"                                                                => "Try again"
    ]
];

$stayWifiRedirect = $stayWifiRedirectTreatment[$treatment];

?>
