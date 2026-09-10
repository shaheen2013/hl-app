<?php
$gdprTreatments = [
    'informal' => [
        "intro_title"                      => "Твоята поверителност е важна за нас",
        "intro_question"                   => "В " . $hotelInfo['hotelName'] . " ли си отседнал(а)?",
        "intro_answer_client"              => "Да, отседнал(а) съм",
        "intro_answer_not_client"          => "Не, не съм отседнал(а)",
        "intro_accept_restrictive"         => "Продължи",
        "intro_accept_conditions"          => "Приеми и продължи",
        "checkbox_notifications"           => "Приемам прехвърлянето на моите данни срещу $1 за изпращане на търговски съобщения",
        "commercial_profile"               => "Приемам данните ми да бъдат включени в търговски профил, за да получавам персонализирани оферти от $1",
        "go_back"                          => "Назад",
        "confirm"                          => "Потвърди",
        'PMS validator require msg'        => "За да потвърдиш, че си клиент на този хотел, моля, въведи следната информация:",
        'PMS validator require name'       => 'Посочи трите си имена:',
        'PMS validator require first name' => 'Посочи малкото си име:',
        'PMS validator require surname'    => 'Посочи фамилията си:',
        'PMS validator require room'       => 'Посочи номера на стаята си:',
        'PMS validator require document id'=> 'Посочи твоя ЕГН:',
        'PMS validator title list users'   => 'Избери профил:',
        'OR'                               => 'Или',
        'PMS validator access code'        => 'Посочи твоя код за достъп:',
        'PMS validator radius ticket'      => 'Избери своя талон:',
        'PMS validator radiusTicketMsg'    => "Ако не разполагаш с талон, попитай нашите служители.",
        "accommodated tab"                 => "Отседнал(а) съм",
        "code tab"                         => "Имам код"
    ],

    'formal' => [
        "intro_title"                      => "Вашата поверителност е важна за нас",
        "intro_question"                   => "В " . $hotelInfo['hotelName'] . " ли сте отседнали?",
        "intro_answer_client"              => "Да, отседнал(а) съм",
        "intro_answer_not_client"          => "Не, не съм отседнал(а)",
        "intro_accept_restrictive"         => "Продължи",
        "intro_accept_conditions"          => "Приеми и продължи",
        "checkbox_notifications"           => "Приемам прехвърлянето на моите данни срещу $1 за изпращане на търговски съобщения.",
        "commercial_profile"               => "Приемам данните ми да бъдат включени в търговски профил, за да получавам персонализирани оферти от $1",
        "go_back"                          => "Назад",
        "confirm"                          => "Потвърди",
        'PMS validator require msg'        => "За да потвърдите, че сте клиент на този хотел, моля, въведете следната информация:",
        'PMS validator require name'       => 'Посочете трите си имена:',
        'PMS validator require first name' => 'Посочете малкото си име:',
        'PMS validator require surname'    => 'Посочете фамилията си:',
        'PMS validator require room'       => 'Посочете номера на Вашата стая:',
        'PMS validator require document id'=> 'Посочете Вашето ЕГН:',
        'PMS validator title list users'   => 'Изберете профил:',
        'OR'                               => 'O',
        'PMS validator access code'        => 'Посочете Вашия код за достъп:',
        'PMS validator radius ticket'      => 'Изберете Вашия талон:',
        'PMS validator radiusTicketMsg'    => "Ако не разполагате с талон, попитайте нашите служители.",
        "accommodated tab"                 => "Отседнал(а) съм",
        "code tab"                         => "Имам код"
    ]
];

$gdprLang = $gdprTreatments[$_SESSION['hotel']['treatment']];
