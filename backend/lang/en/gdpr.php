<?php
$gdprTreatments = [
    'informal' => [
        "intro_title"                      => "Your privacy is important to us",
        "intro_question"                   => "Are you staying at " . $hotelInfo['hotelName'] . "?",
        "intro_answer_client"              => "Yes",
        "intro_answer_not_client"          => "No",
        "intro_accept_restrictive"         => "Continue",
        "intro_accept_conditions"          => "I agree.  Continue",
        "checkbox_notifications"           => "I agree to the assignment of my data to $1 to send commercial communications on their services.",
        "commercial_profile"               => "I agree to have my data included in a commercial profile in order to receive personalized offers of $1",
        "go_back"                          => "Go back",
        "confirm"                          => "Confirm",
        'PMS validator require msg'        => "In order to prove that you are staying at this hotel, please enter the following data:",
        'PMS validator require name'       => 'Enter your full name:',
        'PMS validator require first name' => 'Enter your first name:',
        'PMS validator require surname'    => 'Enter your surname:',
        'PMS validator require room'       => 'Enter your room number:',
        'PMS validator require document id'=> 'Enter your ID number:',
        'PMS validator title list users'   => 'Select your profile:',
        'OR'                               => 'OR',
        'PMS validator access code'        => 'Please enter your access code:',
        'PMS validator radius ticket'      => 'Please enter your ticket:',
        'PMS validator radiusTicketMsg'    => "If you don’t have a ticket code, please ask our staff.",
        "accommodated tab"                 => "I am a guest",
        "code tab"                         => "I have a code"
    ],

    'formal' => [
        "intro_title"                      => "Your privacy is important to us",
        "intro_question"                   => "Are you staying at " . $hotelInfo['hotelName'] . "?",
        "intro_answer_client"              => "Yes",
        "intro_answer_not_client"          => "No",
        "intro_accept_restrictive"         => "Continue",
        "intro_accept_conditions"          => "I agree.  Continue",
        "checkbox_notifications"           => "I agree to the assignment of my data to $1 to send commercial communications on their services.",
        "commercial_profile"               => "I agree to have my data included in a commercial profile in order to receive personalized offers of $1",
        "go_back"                          => "Go back",
        "confirm"                          => "Confirm",
        'PMS validator require msg'        => "In order to prove that you are staying at this hotel, please enter the following data:",
        'PMS validator require name'       => 'Enter your full name:',
        'PMS validator require first name' => 'Enter your first name:',
        'PMS validator require surname'    => 'Enter your surname:',
        'PMS validator require room'       => 'Enter your room number:',
        'PMS validator require document id'=> 'Enter your ID number:',
        'PMS validator title list users'   => 'Select your profile:',
        'OR'                               => 'OR',
        'PMS validator access code'        => 'Please enter your access code:',
        'PMS validator radius ticket'      => 'Please enter your ticket:',
        'PMS validator radiusTicketMsg'    => "If you don’t have a ticket code, please ask our staff.",
        "accommodated tab"                 => "I am a guest",
        "code tab"                         => "I have a code"
    ]
];

$gdprLang = $gdprTreatments[$_SESSION['hotel']['treatment']];
