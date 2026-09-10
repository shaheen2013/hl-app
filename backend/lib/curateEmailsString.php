<?php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

/**
 * @param an string of emails separated by , or
 * @returns a curated array of emails
 */

function curateEmailsString($emailsString)
{
    //check and cure warning emails split string by ',' and ';'
    $emailsString = preg_split('/[,\s;]+/', $emailsString);

    //remove empty elements from array
    $emailsString = array_filter($emailsString);

    //Basic Check if element is an email and discard if not
    foreach ($emailsString as $keyEmail => $newWarningEmail) {
        if (!filter_var($newWarningEmail, FILTER_VALIDATE_EMAIL)) {
            //remove invalid email from array
            unset($emailsString[$keyEmail]);
        }
    }

    return $emailsString;
}
