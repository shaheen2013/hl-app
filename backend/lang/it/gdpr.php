<?php

$gdprTreatments = [
    'informal' => [
        "intro_title"                      => "La tua privacy è importante per noi",
        "intro_question"                   => getIntroQuestion($hotelInfo['hotelName'], false),
        "intro_answer_client"              => "Sì",
        "intro_answer_not_client"          => "No",
        "checkbox_notifications"           => "Accetto il trasferimento dei miei dati a $1 per l’invio di comunicazioni commerciali",
        "commercial_profile"               => "Accetto che i miei dati vengano inseriti in un profilo commerciale per ricevere offerte personalizzate da $1",
        "go_back"                          => "Indietro",
        "confirm"                          => "Confermare",
        'PMS validator require msg'        => "Per confermare che sei un cliente di questo stabilimento, ti preghiamo di inserire le seguenti informazioni: ",
        'PMS validator require name'       => 'Inserisci il tuo nome completo:',
        'PMS validator require surname'    => 'Inserisci il tuo cognome:',
        'PMS validator require room'       => 'Inserisci il numero della tua camera:',
        'PMS validator require first name' => 'Inserisci il tuo nome:',
        'PMS validator require document id'=> 'Inserisci il tuo numero di documento personale:',
        'PMS validator title list users'   => 'Seleziona il tuo profilo:',
        'OR'                               => 'O',
        'PMS validator access code'        => "Digita qui sotto la password fornita:",
        'PMS validator radius ticket'      => 'Inserisci il tuo ticket:',
        'PMS validator radiusTicketMsg'    => "Se non possiedi un ticket rivolgiti al nostro personale.",
        "intro_accept_conditions"          => "Accettare e continuare ",
        "intro_accept_restrictive"         => "Continuare",
        "accommodated tab"                 => "Sono un ospite",
        "code tab"                         => "Ho un codice"
    ],

    'formal' => [
        "intro_title"                      => "La tua privacy è importante per noi",
        "intro_question"                   => getIntroQuestion($hotelInfo['hotelName'], true),
        "intro_answer_client"              => "Sì",
        "intro_answer_not_client"          => "No",
        "checkbox_notifications"           => "Accetto il trasferimento dei miei dati a $1 per l’invio di comunicazioni commerciali",
        "commercial_profile"               => "Accetto che i miei dati vengano inseriti in un profilo commerciale per ricevere offerte personalizzate da $1",
        "go_back"                          => "Indietro",
        "confirm"                          => "Confermare",
        'PMS validator require msg'        => "Per poter confermare che Lei sia un cliente di questo stabilimento, inserisca per cortesia i seguenti dati: ",
        'PMS validator require name'       => 'Inserisca il suo nome completo:',
        'PMS validator require surname'    => 'Inserisca il suo cognome:',
        'PMS validator require room'       => 'Inserisca il numero della sua camera:',
        'PMS validator require first name' => 'Inserisca il suo nome:',
        'PMS validator require document id'=> 'Inserisca il suo numero di documento personale:',
        'PMS validator title list users'   => 'Seleziona il tuo profilo:',
        'OR'                               => 'O',
        'PMS validator access code'        => "Digita qui sotto la password fornita:",
        'PMS validator radius ticket'      => 'Inserisca il suo ticket:',
        'PMS validator radiusTicketMsg'    => "Se non possiede un ticket si rivolga al nostro personale.",
        "intro_accept_conditions"          => "Accettare e continuare ",
        "intro_accept_restrictive"         => "Continuare",
        "accommodated tab"                 => "Sono un ospite",
        "code tab"                         => "Ho un codice"
    ]
];

$gdprLang = $gdprTreatments[$_SESSION['hotel']['treatment']];

function getIntroQuestion($hotelName, $isFormal){

    $charExceptions = ['h', 'H', 'a', 'A', 'e','E', 'i', 'I', 'o', 'O', 'u', 'U'];

    if(in_array($hotelName[0], $charExceptions) ){
        if($isFormal){
            return "Alloggia nell’" . $hotelName . "?";
        }else{
            return "Stai soggiornando nell " . $hotelName . "?";
        }
    }else{
        if($isFormal){
            return "Alloggia nell’" . $hotelName . "?";
        }else{
            return "Stai soggiornando nel " . $hotelName . "?";
        }
    }
}




