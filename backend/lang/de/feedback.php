<?php
//OK
//general
$msg2003 = 'Gut gemacht! Bitte überprüfe Deine E-Mail'; // contraseña mandada al email
$msg2004 = 'Alert! the position of the widget has changed, this will require you to update the tag on your web page ';
$msg2007 = 'Deine Daten wurden erfolgreich gespeichert';
//hotel check in
$msg2005 = 'Check-In OK!';
$msg2006 = 'rfolgreich bestätigt';
//tienda
$msg2008 = 'Belohnungsangebot erfolgreich eingelöst';
$msg2010 = 'Belohnungsangebot zur Wunschliste hinzugefügt!';
//wishlist
$msg2009 = 'Belohnungsangebot von Merkliste gelöscht';
//
$msg2011 = 'Belohnungsangebot erfolgreich geteilt';
$msg2012 = (!empty($points) ? $points . ' ' : '') . ' Bonuspunkte erfolgreich Deinem Konto hinzugefügt';
$msg2013 = 'Du folgst nun diesem Hotel / dieser Kette';
$msg2014 = 'Du folgst diesem Hotel / dieser Ketten nicht mehr';
// Add staff
$msg2015 = 'Mitarbeiter erfolgreich erstellt!';
// staff-managemen
$msg2016 = 'Mitarbeiter gelöscht';
//chain management
$msg2017 = 'Neues Hotel erfolgreich erstellt!';
// chain-management
$msg2018 = 'Als ' . (!empty($_SESSION['hotelName']) ? '<strong>' . $_SESSION['hotelName'] . '</strong>' : ' new hotel') . ' angemeldet';
// invitar-usuarios-2
$msg2019 = 'Liste erfolgreich gespeichert!';
// invite-user-drafts
$msg2020 = 'Liste erfolgreich gelöscht';
// cupon (regalar)
$msg2021 = 'Gutschein erfolgreich gegeben';
//hotel profile 2
$msg2022 = 'Bild erfolgreich gelöscht';
$msg2023 = 'Bild erfolgreich priorisiert';
// User-points (regalar puntos)
$msg2024 = 'Punkte erfolgreich gegeben!';
$msg2025 = 'Treuekarten erfolgreich aktiviert';
$msg2026 = 'Treuekarten erfolgreich deaktiviert';
//landing page
$msg2027 = 'Belohnung der Zielseite zugewiesen';
$msg2028 = 'Belohnung der Zielseite nicht mehr zugewiesen';
$msg2029 = 'Email geändert, überprüfen Sie bitte Ihre E-Mail und bestätigen Sie';
$msg2030 = 'Gültige E-Mail-Adresse';
$msg2031 = "Row erfolgreich gelöscht";
$msg2032 = "Ungültige E-Mails erfolgreich gelöscht";
$msg2033 = "Social Media erfolgreich freigegeben";
$msg2034 = "E-Mail erfolgreich geändert";
$msg2035 = "Einladung erfolgreich gesendet wieder";

//offer
$msg2036 = "Offer successfully published";

//Edit staff
$msg2037 = "Staff successfully updated";

//UNSUBSCRIBE
$msg2038 = "User succesfully unsubscribed";

//Clients
$msg2039 = "Request successfully processed. Depending on the file size, this process may take a while.";

//products
$msg2040 = 'The product ' . (isset($productName) ? $productName : null) . ' has been ' . (isset($active) ? ($active === '1' ? 'deactivated' : 'activated') : null) . ' correctly on brand id ' . (isset($brandIdsString) ? $brandIdsString: null);
$msg2041 = 'The product ' . (isset($productName) ? $productName : null) . ' has been ' . 'edited correctly on brand id ' . (isset($brandIdsString) ? $brandIdsString: null);


$msg2042 = 'Password changed correctly.';
$msg2043 = 'Your preferences to protect your account have been successfully saved. You can log in now';
//===================================

//KO
$msg4001 = 'Gast nicht gefunden';
$msg4002 = 'Diese Aktion ist nicht erlaubt';
$msg4003 = "Du musst eingeloggt sein";
$msg4005 = 'Dieser Benutzer wurde bereits eingecheckt';
$msg4006 = 'Es tut uns Leid, Du kannst diese Belohnung jetzt nicht einlösen';
$msg4007 = 'Es tut uns Leid, das Belohnungsangebot hat keine Plätze mehr frei';
$msg4008 = 'Leider hast Du nicht genug Bonuspunkte zum Einlösen dieses Angebots';
$msg4009 = 'Dieses Belohnungsangebot gilt nur für neue Gäste';
$msg4010 = 'Du musst im Hotel eingecheckt sein, um dieses Belohnungsangebot einlösen zu können';
$msg4011 = 'Diese Belohnung ist in diesem Moment nicht aktiv';
$msg4012 = 'Du kannst nur eins dieser Belohnungsangebote einlösen.';
$msg4013 = 'Falsches Passwort, bitte versuche es erneut';
$msg4014 = "Es tut uns Leid, Bonusangebote können nicht zwei Mal geteilt werden";
$msg4015 = "Sie müssen als Gast angemeldet sein, um diese Aktion durchzuführen";
$msg4016 = "Der Benutzer existiert bereits als Admin, kann nicht auf ein niedrigeres Level bewegt werden. Versuche es mit einer anderen E-Mail-Adresse statt";
$msg4017 = "Dieser Benutzer ist bereits vorhanden";
$msg4018 = "Es gibt einige ungültige E-Mails. Bitte korrigiere sie oder eine Einladung per E-Mail wird nicht gesendet. Das System sendet nur an gültige E-Mail-Adressen eine Einladung";
$msg4019 = "Es gibt einige gleiche Felder, die Liste kann nicht gespeichert werden";
$msg4020 = "Du hast nicht genug Bonuspunkte";
$msg4021 = "Du kannst nur Deine Gutscheine geben";
$msg4022 = "Dieser Gutschein wurde bereits bestätigt. Kann nicht wieder verwendet werden!";
$msg4023 = "Du kannst nur Belohnungsangebote geben, wenn Du genug Bonuspunkte hast";
$msg4024 = "Bitte fülle alle erforderlichen Felder aus";
$msg4025 = "E-Mail bereits verwendet";
$msg4026 = "Gesperrtes Konto, etwas bis zum nächsten Versuch warten";
// Hotel login errors
$msg4027 = "Falsche E-Mail, versuche es erneut"; //email mal formado (FILTER_VALIDATE_EMAIL)
$msg4028 = "Passwort muss länger sein";
$msg4029 = "Wrong password"; // email o pass incorrectos
$msg4030 = "Gastkonto ist nicht aktiv";
$msg4031 = "The password must have at least 8 characters, a lower case, an upper case, a number and a special character.";
$msg4076 = "The account you are trying to connect to is disabled";
$msg4103 = "An unknown error occurred. Please contact support.";
$msg4104 = "User not found. Check that the email entered is correct.";
$msg4105 = "Invalid verification code provided, please try again";
$msg4106 = "You have reached your request limit. Please try again later.";
$msg4107 = "The account status does not allow password recovery. Please contact support.";
$msg4108 = "The code you entered has expired. Please try to recover the account again to receive a new code.";
$msg4109 = "The phone number entered is not in the requested format.";
$msg4110 = "The code you entered has expired. Please try again with a new code.";
$msg4111 = "All information could not be obtained for this user. Please contact support.";
$msg4112 = "User information could not be updated. Please contact support.";
$msg4113 = "User could not be created. Please contact support.";
$msg4114 = "Invalid verification code provided. Please rescan the QR and enter the correct code.";
//Subir imagenes
$msg4032 = "Logo muss jpg / gif / png sein";
$msg4033 = "Bild zu groß";

$msg4034 = "Passwörter stimmen nicht überein";
$msg4035 = "Das Hotel ist diese Saison geschlossen";
$msg4036 = "'Bis' Datum sollte später als 'Von' Datum sein"; //Fecha inicio superior fecha fin
$msg4037 = "Du musst eingeladen werden, um ein Konto zu erstellen";
$msg4038 = "Falscher Token";
$msg4039 = "Kann nicht teilen ohne soziale Medien zu Hotelinking hinzuzufügen";
$msg4040 = "Angebot bereits an diese E-Mail Adresse gemacht. Bitte benutze eine andere";
$msg4041 = "Aktuelle Level-Menge kann nicht weniger als die vorige Menge bzw. höher als die nächste sein";
$msg4042 = "Diese Belohnung ist nicht für den Einsatz auf der Zielseite zugelassen. Es muss zuerst veröffentlicht werden";
$msg4043 = "Ungültige oder Wegwerf-E-Mails nicht erlaubt";
$msg4044 = "Falsches Passwort";
$msg4045 = "Gastkonto ist nicht aktiv";
$msg4046 = "Falsche Listenformat";
$msg4047 = "ungültige Email Adresse";
$msg4048 = "Sie können nicht selbst referrer";
$msg4049 = "'Punkte bis' größer als 'Punkte zu sein'";
$msg4050 = "'Nächte bis' größer als 'Nächte von sein'";
$msg4051 = "'bis verbracht' sollte größer 'aus verbrauchten'";
$msg4052 = "Kann eine leere Liste nicht importiert werden";
$msg4053 = "Bitte füllen Sie alle erforderlichen Felder in allen Sprachen";
$msg4054 = "Sie haben keine Berechtigung Facebook, uns den Zugriff auf Ihre E-Mail Adresse zu geben. Folglich werden wir nicht in der Lage Sie Ihren Gutschein per E-Mail zu schicken. Gehen Sie zu Facebook, Hotelinking App zu löschen und neu zu starten.";
$msg4055 = "Sie müssen Ihren Gutschein einlösen , einen neuen zu verdienen";
$msg4056 = "Tweet nicht gebucht wurde. Es sieht aus, die Sie bereits gebucht haben , oder Sie haben die Grenze überschritten haben .";
// Usuario no ha verificado su email de la cuenta de twitter
$msg4057 = 'Es scheint, dass Ihre E-Mail -Konto wurde nicht von Twitter verfied worden. Bitte stellen Sie sicher, dass es überprüft wird, und versuchen Sie es erneut.';
$msg4058 = 'Our apologies, WiFi cannot be accessed now. We are working to give access as soon as possible. Thanks for your patience.';
$msg4059 = 'URL ist über 1.000 Zeichen. Der Grenzwert wurde überschritten. Bitte versuchen Sie es mit einer anderen URL.';
$msg4060 = "Sie haben verschiedene Passwort eingegeben. Bitte stellen Sie sicher, beide sind das gleiche Passwort";
$msg4061 = 'Bitte aktivieren Sie die Cookies auf Ihrem aktuellen Browser, um fortzufahren';
$msg4062 = 'Es scheint, dass Ihr Konto nicht von Facebook überprüft  wird. Bitte stellen Sie sicher, dass es richtig überprüft  und erneut getestet';
$msg4063 = 'Ein erwarteter Fehler aufgetreten mit Ihrem Facebook-Konto. Bitte versuchen Sie es später noch einmal.';
$msg4064 = 'Ein unerwarteter Fehler ist aufgetreten, während Sie sich anmelden wollten. Bitte versuche es erneut.';
//Error de base de datos
$msg4065 = "We had a problem with the database , please contact support for help";
$msg4067 = "Error al recibir datos, por favor contacta con soporte si persiste el problema";
$msg4101 = 'The product ' . (isset($productName) ? $productName : null) . ' has not been ' . (isset($active) ? ($active === '1' ? 'deactivated' : 'activated') : null) . ' correctly on brand id ' . (isset($brandIdsString) ? $brandIdsString: null);
$msg4102 = 'The product ' . (isset($productName) ? $productName : null) . ' has not been ' . ' edited correctly on brand id ' . (isset($brandIdsString) ? $brandIdsString: null);
//API errors
$msg4066 = 'No ha sido posible conectar con el API con estas credenciales';
$msg4068 = 'Esta campaña y oferta ya están mapeadas';
$msg4069 = 'Error al mapear esta campaña, faltan datos';
$msg4070 = 'We could not delete this item, please contact support if the problem persists';
$msg4077 = 'We cannot process the request with this data. Try other data or contact support if the problem persists';

//Error Curl Unifi
$msg4071 = "Der Zugriff auf WLAN ist derzeit nicht möglich";
$msg4072 = "Bitte versuchen Sie es erneut oder wenden Sie sich an die Rezeption, wenn das Problem weiterhin besteht";

//ERROR PMS VALIDATOR
$msg4073 = "Die eingegebenen Daten stimmen nicht mit unseren Einträgen überein; wenn Sie ein Hotelgast sind, dann versuchen Sie es bitte später noch einmal oder wenden Sie sich an die Rezeption.";
$msg4074 = "Alle Felder sind erforderlich";


//GTM Errors
$msg4075 = "Google Tag Manager variables could not be recovered. Please make sure that the container_id and workspace_id are correct.";


//Referral Hotel
$msg5001 = 'The room list you provided does not seem to be correctly formatted, remember it should be separated by commas (ex: 1001, 1002, 1003';

//WARNING
$msg3005 = 'Gast erfolgreich eingecheckt. Wir haben jedoch die Einladung per E-Mail nicht gesendet, da dieser Gast bereits in Deiner Datenbank existiert.';
$msg3006 = 'Leere Daten werden als Standard "0" gesendet, wenn Du sie nicht änderst';
$msg3007 = 'Du besitzt dieses Angebot bereits, bitte online anmelden, um alle Deine Gutscheine zu überprüfen';
$msg3008 = 'Du hast dieses Angebot bereits angefordert. Wir haben Dir eine E-Mail gesendet, überprüfe bitte Deinen Posteingang';
$msg3009 = 'Eine Sprache ist unvollständig. Du kannst ihn später bearbeiten';
$msg3010 = 'We could not publish your offer';


$msg4083 = 'Last action was cancelled, your session was recently closed or changed';

//Email from pms not validated
$msg4090 = "Die dem System angegebene E-Mail-Adresse ist ungültig. Geben Sie bitte eine andere an!";
$msg4091 = "Customized survey should be sent always at the same time o after satisfaction survey. Please, check filled data.";

$msg4092 = "In order to save the offers, they must be marked by default or active dates specified.";
$msg4093 = "At least one of the accommodated/non accommodated options must be checked.";
$msg4094 = "An offer(s) already exists for the specified dates / users, please modify/delete it before creating a new one.";
$msg4095 = "There can only be one default offer by accommodated or non accommodated type.";

// NEW OFFERS MESSAGES
$msg4096 = "The offer is linked to a reward. You must work it out before continue.";
$msg4097 = "Offer removed successfully.";

// UNSUBSCRIBE FAILED
$msg4098 = "Error trying to unsubscribe user";

// Bad fields on captive portal
$msg4099 = "Einige der Formularfelder sind nicht korrekt ausgefüllt. Überprüfen Sie sie bitte!";
$msg4100 = "Geben Sie bitte eine gültige Zimmernummer an";
