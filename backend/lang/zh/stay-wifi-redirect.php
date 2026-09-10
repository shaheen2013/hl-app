<?php

$stayWifiRedirectTreatment = [
    'informal' => [
        //first message
        'Let your friends know about us!'                                          => '让您的朋友知道您在这里！',
        "Let your friends know about us with share gift"                           => "Refer your friends on Facebook and get your",//texto especifico de share facebook
        "Click and edit your post before it is shared on Facebook with share gift" => "Click the below button and edit your post before published",//texto especifico de share facebook segunda línea
        'Click and edit your post before it is shared on Facebook.'                => 'Edit your post before shared on Facebook.',

        //User cancelled
        'You\'ve just canceled. Please try again.'                                 => 'You\'ve just canceled. Please, try again to help us.',
        'Click and edit your post before it is shared on Facebook.'                => 'You can edit before your post is shared on Facebook.',

        //All OK
        'Welcome!'                                                                 => '不客气',
        'To'                                                                       => ' 先生光顾 ',
        'Thank you!'                                                               => '非常感谢！',
        'Now you can access free wifi by clickings on the button below'            => '现在连接无线网络',

        //User treatment
        'Mr.'                                                                      => '迎',
        'Ms.'                                                                      => '迎',

        //Button
        'Share on Facebook'                                                        => '在Facebook分享',
        'Give me wifi now'                                                         => '现在连接无线网络',

        //Room Number
        'Room Number'                                                              => '房间号',
        'Room Number Text'                                                         => '请在下面填写您的房间号码:',
        'Room Number Not Hotel Text'                                               => '请在下面填写为您提供的密码',
        'Room Placeholder'                                                         => '房间号',
        'Room Not Hotel Placeholder'                                               => '密码”',
        'Room Success Text'                                                        => '房间号码正确',
        'Room Success Not Hotel Text'                                              => '密码码正确',
        'Room Error Text'                                                          => '请您提供有效的房间号码。',
        'Room Error Not Hotel Text'                                                => '请您提供有效的密码。',
        'Room Wifi Now'                                                            => '现在连接无线网络',

        //skip
        'I want to skip this step and get wifi now'                                => '我不想分享。请跳过此步。',
        "I want to skip this step and get wifi now gift"                           => "Thanks, but I don´t want to get rewarded.",

        //Connecting to wifi
        "Connecting to wifi."                                                      => "正在加载......",
        "This can take a bit, please be patient."                                  => "请稍候。",

        //Error Curl Unifi
        "Unifi Error Title"                                                        => "当前无线网络不可用。",
        "Unifi Error Message"                                                      => "Please, try again or contact the front desk should the problem persist.",
        'device_blacklisted'                                                       => '如果您在几分钟后无法访问网络，请联系我们的工作人员。给您带来的不便请谅解。',
        "try again"                                                                => "再试一次"
    ],

    'formal' => [
        //first message
        'Let your friends know about us!'                                          => '让您的朋友知道您在这里！',
        "Let your friends know about us with share gift"                           => "Refer your friends on Facebook and get your",//texto especifico de share facebook
        "Click and edit your post before it is shared on Facebook with share gift" => "Click the below button and edit your post before published",//texto especifico de share facebook segunda línea
        'Click and edit your post before it is shared on Facebook.'                => 'Edit your post before shared on Facebook.',

        //User cancelled
        'You\'ve just canceled. Please try again.'                                 => 'You\'ve just canceled. Please, try again to help us.',
        'Click and edit your post before it is shared on Facebook.'                => 'You can edit before your post is shared on Facebook.',

        //All OK
        'Welcome!'                                                                 => '不客气',
        'To'                                                                       => ' 先生光顾 ',
        'Thank you!'                                                               => '非常感谢！',
        'Now you can access free wifi by clickings on the button below'            => '现在连接无线网络',

        //User treatment
        'Mr.'                                                                      => '迎',
        'Ms.'                                                                      => '迎',

        //Button
        'Share on Facebook'                                                        => '在Facebook分享',
        'Give me wifi now'                                                         => '现在连接无线网络',

        //Room Number
        'Room Number'                                                              => '房间号',
        'Room Number Text'                                                         => '请在下面填写您的房间号码:',
        'Room Number Not Hotel Text'                                               => '请在下面填写为您提供的密码',
        'Room Placeholder'                                                         => '房间号',
        'Room Not Hotel Placeholder'                                               => '密码',
        'Room Success Text'                                                        => '房间号码正确',
        'Room Success Not Hotel Text'                                              => '密码码正确',
        'Room Error Text'                                                          => '请您提供有效的房间号码。',
        'Room Error Not Hotel Text'                                                => '请您提供有效的密码。',
        'Room Wifi Now'                                                            => '现在连接无线网络',

        //skip
        'I want to skip this step and get wifi now'                                => '我不想分享。请跳过此步。',
        "I want to skip this step and get wifi now gift"                           => "Thanks, but I don´t want to get rewarded.",

        //Connecting to wifi
        "Connecting to wifi."                                                      => "正在加载......",
        "This can take a bit, please be patient."                                  => "请稍候。",

        //Error Curl Unifi
        "Unifi Error Title"                                                        => "当前无线网络不可用。",
        "Unifi Error Message"                                                      => "Please, try again or contact the front desk should the problem persist.",
        'device_blacklisted'                                                       => '如果您在几分钟后无法访问网络，请联系我们的工作人员。给您带来的不便请谅解。',
        "try again"                                                                => "再试一次"
    ]
];

$stayWifiRedirect = $stayWifiRedirectTreatment[$treatment];

?>
