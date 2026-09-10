<?php

use Respect\Validation\Validator as v;



// VALIDATORS
//check https://github.com/Respect/Validation/blob/1.1/docs/VALIDATORS.md

//examples
$usernameValidator = v::alnum()->noWhitespace()->length(1, 10);
$ageValidator = v::intVal()->positive()->between(1, 20);

$emailValidator = v::email()->notEmpty()->setName('Email');;
$passwordValidator = v::notEmpty()->noWhitespace()->length(1,18)->setName('Password');;

$loginValidators = array(
    'email' => $emailValidator
);

$guidValidator = v::regex('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/')->setTemplate('{{name}} is not valid UUID');
$hotelGuidValidator = v::regex('/^([0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f] |[]){12}$/')->setTemplate('{{name}} is not valid UUID');

$unsubscribeValidators = array(
    'hotel_guid' => $guidValidator
);

$oldUnsubscribeValidators = array(
    'user_guid' => $guidValidator
);



