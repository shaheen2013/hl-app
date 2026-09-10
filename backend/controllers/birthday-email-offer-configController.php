<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//contenido solo disponible si logueado
include LIB . 'logueado.php';
include LIB . 'curateEmailsString.php';
hotelStaffLanding();// Si no esta logueado lo manda a la landing

$newBirthdayOffer = false;
$birthdayAlert = isset($_POST['birthdayAlert']) ? 1 : 0;
$birthdayAlertEmails = isset($_POST['birthdayAlertEmails']) ? curateEmailsString($_POST['birthdayAlertEmails']) : "";

$birthdayAlertEmails = (!empty($birthdayAlertEmails) ? implode( ",", $birthdayAlertEmails ) : "");
$birthdayAlertRange = $_POST['birthdayAlertRange'] ?? 3;

// For front purposes
$currentSubPage = 'birthday-email';

if ($_POST && !array_has($_POST, 'relogin_hotel_id') && isset($birthdayAlertEmails) && $birthdayAlertRange ) {
    $birthdayAlarm = setBirthdayAlarm($_SESSION['loggedBrandID'], $birthdayAlert);
    $birthdayAlarmEmail = setMailsFromBirthdayAlarm($_SESSION['h_logueado'], $birthdayAlertEmails, $birthdayAlertRange);

    if ($birthdayAlarm || $birthdayAlarmEmail) {
        $ok = array(true, '2007');
    } else {
        $ok = array(false, '4065');
    }
}
if ($_POST && !array_has($_POST, 'relogin_hotel_id') && (!empty($_POST['birthdayoffer']) || !empty($_POST['SendBirthdayWarningOfNonUsers']))) {
    $newBirthdayOffer = false;
    $sendWarningFromNonUsers = !empty($_POST['SendBirthdayWarningOfNonUsers']) ? 1 : 0;
    if (array_get($_POST, 'birthdayoffer')) {
        $newBirthdayOffer = setBirthdayOffer($_SESSION['h_logueado'], $_POST['birthdayoffer'], $sendWarningFromNonUsers);
    }
    if ($newBirthdayOffer) {
        $ok = array(true, '2007');
    } else {
        $ok = array(false, '4065');
    }
}

$hotelOfferList = getHotelOfferList($_SESSION['h_logueado'], $_SESSION['userLang']);
$birthdayData = getBirthdayOffer($_SESSION['h_logueado']);
$birthdayAlarm = getBirthdayAlarm($_SESSION['loggedBrandID']);
$birthdayAlarmParams = getMailsFromBirthdayAlarm($_SESSION['h_logueado']);
$birthdayAlertEmails = $birthdayAlarmParams['birthdayAlertEmails'];
$birthdayAlertRange = $birthdayAlarmParams['birthday_alarm_days_range'];

