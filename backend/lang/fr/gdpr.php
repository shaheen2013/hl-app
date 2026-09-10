<?php
$gdprTreatments = [
    'informal' => [
        "intro_title"                      => "Le respect de votre confidentialité est très important.",
        "intro_question"                   => "Êtes-vous logé à" . $hotelInfo['hotelName'] . "?",
        "intro_answer_client"              => "Oui, je suis logé(e)",
        "intro_answer_not_client"          => "Non, je ne suis pas logé(e)",
        "intro_accept_restrictive"         => "Continuer",
        "intro_accept_conditions"          => "Accepter et continuer",
        "checkbox_notifications"           => "J’accepte que mes données soient cédées à $1 pour l’envoi de communications commerciales sur ses services",
        "commercial_profile"               => "J'accepte que mes données soient incluses dans un profil commercial afin de recevoir des offres personnalisées de la part de $1",
        "go_back"                          => "arrière",
        "confirm"                          => "Confirmer",
        'PMS validator require msg'        => "Pour pouvoir confirmer que vous êtes bien un client de cet établissement, veuillez introduire les
renseignements suivants :",
        'PMS validator require name'       => 'Introduire votre nom complet',
        'PMS validator require surname'    => 'Introduire votre nom de famille :',
        'PMS validator require room'       => 'Introduire votre numéro de chambre :',
        'PMS validator require first name' => 'Introduire votre prénom :',
        'PMS validator require document id'=> 'Introduisez votre carte d’identité :',
        'PMS validator title list users'   => 'Sélectionnez votre profil :',
        'OR'                               => 'Ou',
        'PMS validator access code'        => "Saisissez le mot de passe fourni:",
        'PMS validator radius ticket'      => 'Saisissez votre ticket:',
        'PMS validator radiusTicketMsg'    => "Si vous ne disposez pas d’un ticket, veuillez vous adresser à notre personnel.",
        "accommodated tab"                 => "Je suis logé(e)",
        "code tab"                         => "Je dispose d'un code"
    ],

    'formal' => [
        "intro_title"                      => "Le respect de votre confidentialité est très important.",
        "intro_question"                   => "Êtes-vous logé à" . $hotelInfo['hotelName'] . "?",
        "intro_answer_client"              => "Oui, je suis logé(e)",
        "intro_answer_not_client"          => "Non, je ne suis pas logé(e)",
        "intro_accept_restrictive"         => "Continuer",
        "intro_accept_conditions"          => "Accepter et continuer",
        "checkbox_notifications"           => "J’accepte que mes données soient cédées à $1 pour l’envoi de communications commerciales sur ses services",
        "commercial_profile"               => "J'accepte que mes données soient incluses dans un profil commercial afin de recevoir des offres personnalisées de $1",
        "go_back"                          => "arrière",
        "confirm"                          => "Confirmer",
        'PMS validator require msg'        => "Pour pouvoir confirmer que vous êtes bien un client de cet établissement, veuillez introduire les
renseignements suivants :",
        'PMS validator require name'       => 'Introduire votre nom complet',
        'PMS validator require surname'    => 'Introduire votre nom de famille :',
        'PMS validator require room'       => 'Introduire votre numéro de chambre :',
        'PMS validator require first name' => 'Introduire votre prénom :',
        'PMS validator require document id'=> 'Introduisez votre carte d’identité :',
        'PMS validator title list users'   => 'Sélectionnez votre profil :',
        'OR'                               => 'Ou',
        'PMS validator access code'        => "Saisissez le mot de passe fourni:",
        'PMS validator radius ticket'      => 'Saisissez votre ticket:',
        'PMS validator radiusTicketMsg'    => "Si vous ne disposez pas d’un ticket, veuillez vous adresser à notre personnel.",
        "accommodated tab"                 => "Je suis logé(e)",
        "code tab"                         => "Je dispose d'un code"
    ]
];

$gdprLang = $gdprTreatments[$_SESSION['hotel']['treatment']];
