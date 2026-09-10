<?php
//OK
//general
$msg2003 = "Bien fait! S'il vous plaît, vérifiez votre email"; // contraseña mandada al email
$msg2004 = 'Alert! the position of the widget has changed, this will require you to update the tag on your web page ';
$msg2007 = "vos données ont été sauvegardées avec succès";
//hotel check in
$msg2005 = "Check-in ok!";
$msg2006 = "validé avec succès";
//tienda
$msg2008 = "Offre de récompense racheté avec succès";
$msg2010 = "Offre de récompense ajouté à la liste de souhaits!";
//wishlist
$msg2009 = "Offre de récompense supprimée de la liste de souhaits";
//
$msg2011 = "Offre de récompense partagée avec succès";
$msg2012 = (!empty($points) ? $points . ' ' : '') . '100 points de fidélité ajoutés à votre compte avec succès';
$msg2013 = "Vous suivez maintenant cet hôtel / chaîne";
$msg2014 = "vous ne suivez plus cet hôtel / chaîne";
// Add staff
$msg2015 = "Personnel créé avec succès!";
// staff-managemen
$msg2016 = "Personnel supprimé";
//chain management
$msg2017 = "Nouvel hôtel créé avec succès!";
// chain-management
$msg2018 = 'Connecté comme ' . (!empty($_SESSION['hotelName']) ? '<strong>' . $_SESSION['hotelName'] . '</strong>' : ' nouvel hôtel');
// invitar-usuarios-2
$msg2019 = "Liste enregistré avec succès!";
// invite-user-drafts
$msg2020 = "Liste supprimé avec succès";
// cupon (regalar)
$msg2021 = "Bon donné avec succès";
//hotel profile 2
$msg2022 = "Photo supprimée avec succès";
$msg2023 = "Priorité donnée à la photo avec succès";
// User-points (regalar puntos)
$msg2024 = "Points donnés avec succès!";
$msg2025 = "Cartes de fidélité activés avec succès";
$msg2026 = "Cartes de fidélité désactivés avec succès";
//landing page
$msg2027 = "Récompense attribuée à la page d'atterrissage";
$msg2028 = "Récompensez non assignée de la page d'atterrissage";
$msg2029 = "Email changé, s'il vous plaît vérifiez votre email et validez-le";
$msg2030 = 'Adresse email valide';
$msg2031 = "Row supprimé avec succès";
$msg2032 = "E-mails invalides supprimés avec succès";
$msg2033 = "Successfully shared on social media";
$msg2034 = "Email modifié avec succès";
$msg2035 = "Invitation envoyée avec succès à nouveau";

//offer
$msg2036 = "Offre publiée correctement";

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
$msg4001 = 'Invité non trouvé';
$msg4002 = "Cette action n'est pas autorisée";
$msg4003 = "Vous devez être connecté";
$msg4005 = 'cet utilisateur a déjà été réceptionné';
$msg4006 = 'Désolé, vous ne pouvez pas profiter de cette offre de récompense maintenant';
$msg4007 = "Désolé, cette offre de récompense n'a pas de places restantes";
$msg4008 = "Malheureusement, vous n'avez pas suffisamment de points fidélité pour échanger cette offre de récompense";
$msg4009 = "Cette offre de récompense est disponible uniquement que pour les nouveaux clients. Vous étiez déjà identifié comme un client de l'hôtel, donc s'il vous plaît utilisez (rubis rouges) pour échanger des offres de récompense de cet hôtel";
$msg4010 = "Vous devez être réceptionné à l'hôtel pour profiter de cette offre de récompense";
$msg4011 = "Cette offre de récompense n'est pas active en ce moment";
$msg4012 = 'Vous ne pouvez échanger une de cette offres de récompense. Puisque vous en avez déjà échangé une, malheureusement, vous ne pouvez pas racheter plus de ces derniers';
$msg4013 = "Mot de passe incorrect, s'il vous plaît essayez à nouveau";
$msg4014 = "Désolé, les offres de récompense ne peuvent être partagés que deux fois";
$msg4015 = "Vous devez être connecté en tant qu'invité pour prendre cette mesure";
$msg4016 = "cet utilisateur existe déjà en tant qu'administrateur, il ne peut pas être déplacé à un niveau inférieur. Essayez avec une adresse e-mail différente";
$msg4017 = "Cet utilisateur existe déjà";
$msg4018 = "Il ya quelques emails incorrects. Nous vous prions de les corriger ou un email d'invitation ne sera pas envoyé. Le système enverra seulement une invitation à des adresses e-mail valides";
$msg4019 = "= Il ya quelques champs dupliqués, la liste ne peut être sauvegardée";
$msg4020 = "Vous ne disposez pas suffisamment de points de récompenses";
$msg4021 = "Vous pouvez donner seulement que vos bons";
$msg4022 = "Ce bon a déjà été validé. Il ne peut pas être utilisé à nouveau!";
$msg4023 = "Vous ne pouvez donner que des offres de récompense, si vous avez assez de points de récompense";
$msg4024 = "S'il vous plaît, remplissez tous les champs obligatoires";
$msg4025 = "Email déjà utilisé";
$msg4026 = "Compte bloqué, attendez un moment jusqu'au prochaine essai";
// Hotel login errors
$msg4027 = "Mauvais email, s'il vous plaît essayez à nouveau"; //email mal formado (FILTER_VALIDATE_EMAIL)
$msg4028 = "Le mot de passe doit être plus long";
$msg4029 = "Wrong password"; // email o pass incorrectos
$msg4030 = "Le compte invité n’est pas actif";
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
$msg4032 = "Le Logo doit être en jpg/gif/png";
$msg4033 = "Image trop grande";

$msg4034 = "Les mots de passe ne correspondent pas";
$msg4035 = "Hôtel fermé dans cette période";
$msg4036 = "La date 'Jusqu'à' doit être postérieure à la date 'De'"; //Fecha inicio superior fecha fin
$msg4037 = "Vous devez être invité à créer un compte";
$msg4038 = "token incorrecte";
$msg4039 = "Vous ne pouvez pas partager sans médias sociaux liés";
$msg4040 = "Offre déjà donné à cette adresse e-mail. S'il vous plaît utilisez un différent mail ";
$msg4041 = "La quantité du niveau actuel ne peut pas être plus inférieure à la quantité du niveau antérieur ou plus élevé que le suivant";
$msg4042 = "Cette récompense n’est pas admissible pour une utilisation dans la page d'atterrissage. Il doit être avant tout publié";
$msg4043 = "E-mails invalides ou jetables non autorisés";
$msg4044 = "Mot de passe incorrect";
$msg4045 = "Le compte invité n’est pas actif";
$msg4046 = "Format de liste incorrecte";
$msg4047 = "adresse courriel incorrecte";
$msg4048 = "You can't referrer yourself";
$msg4049 = "'points jusqu'à ' doit être supérieure à des 'points de'";
$msg4050 = "'nuits jusqu'à ce que ' doit être supérieure à 'nuits du'";
$msg4051 = " 'passé jusqu'à ce que devrait être supérieur à ' passé de'";
$msg4052 = "Vous ne pouvez pas importer liste vide";
$msg4053 = "S'il vous plaît , remplissez tous les champs obligatoires dans toutes vos langues";
$msg4054 = "Vous ne l'avez pas autorisé Facebook à nous donner accès à votre adresse e-mail . Par conséquent, nous ne serons pas en mesure de vous envoyer votre chèque par courrier électronique . Aller à Facebook, supprimer Hotelinking application et recommencer à nouveau.";
$msg4055 = "Vous devez échanger votre bon de gagner un nouveau.";
$msg4056 = "Tweet n'a pas été posté . Il semble que vous avez déjà posté , ou si vous avez dépassé la limite .";
// Usuario no ha verificado su email de la cuenta de twitter
$msg4057 = "Il semble que votre compte de messagerie n'a pas été vérifié par Twitter. S'il vous plaît assurez -vous qu'il est verfied et essayez à nouveau.";
$msg4058 = 'Désolé, WiFi ne peut être consulté dès maintenant. Nous travaillons pour donner accès le plus tôt possible. Merci pour votre patience.';
$msg4059 = 'URL est plus de 1.000 caractères. La limite a été dépassée. S\'il vous plaît essayez avec une autre URL.';
$msg4060 = "Vous avez entré un mot de passe différent. S'il vous plaît prendre que les deux sont le même mot de passe.";
$msg4061 = 'S\'il vous plaît activer les cookies sur votre navigateur actuel de procéder.';
$msg4062 = 'Il semble que votre compte n\'a pas été vérifié par Facebook . S\'il vous plaît assurez-vous qu\'il est correctement vérifié et testé à nouveau.';
$msg4063 = 'Une erreur attendue ocurred avec votre compte Facebook. Veuillez réessayer plus tard.';
$msg4064 = 'Une erreur inattendue est survenue pendant que vous avez essayé de vous connecter. Veuillez réessayer.';
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
$msg4071 = "L'accès au Wi-Fi n'est pas disponible pour le moment";
$msg4072 = "Veuillez réessayer ou contacter la réception si le problème persiste";

//ERROR PMS VALIDATOR
$msg4073 = "Les informations saisies ne coïncident pas avec nos registres. Si vous êtes client, veuillez réessayer ultérieurement, ou merci de bien vouloir contacter la réception.";
$msg4074 = "Tous les champs sont obligatoires";

//GTM Errors
$msg4075 = "Google Tag Manager variables could not be recovered. Please make sure that the container_id and workspace_id are correct.";

//Referral Hotel
$msg5001 = 'The room list you provided does not seem to be correctly formatted, remember it should be separated by commas (ex: 1001, 1002, 1003';


//WARNING
$msg3005 = "Invité réceptionné avec succès. Cependant, nous n’avons pas envoyé l'email d’invitation, parce que ce client existe déjà dans votre base de données.";
$msg3006 = "Les données vierges seront envoyés par défaut '0' si elles ne sont pas modifiées";
$msg3007 = "Vous possédez déjà cette offre, s'il vous plaît connectez-vous en ligne pour vérifier tous vos bons";
$msg3008 = "Vous déjà demandé cette offre. Nous vous avons envoyé un email, s'il vous plaît contrôlez votre boîte de réception.";
$msg3009 = "Certaines langues sont incomplètes. Vous pouvez les modifier ultérieurement";
$msg3010 = "Nous n'avons pas pu publier vôtre offre correctement";


$msg4083 = 'Last action was cancelled, your session was recently closed or changed';

//Email from pms not validated
$msg4090 = "L'e-mail obtenu à partir du système n'est pas valide, veuillez en utiliser un autre s'il vous plaît";
$msg4091 = "Customized survey should be sent always at the same time o after satisfaction survey. Please, check filled data.";
$msg4092 = "In order to save the offers, they must be marked by default or active dates specified.";
$msg4094 = "An offer(s) already exists for the specified dates / users, please modify/delete it before creating a new one.";
$msg4095 = "There can only be one default offer by accommodated or non accommodated type.";

// NEW OFFERS MESSAGES
$msg4096 = "The offer is linked to a reward. You must work it out before continue.";
$msg4097 = "Offer removed successfully.";

// UNSUBSCRIBE FAILED
$msg4098 = "Error trying to unsubscribe user";

// Bad fields on captive portal
$msg4099 = "Certains des champs du formulaire sont incorrects. Veuillez le vérifier";
$msg4100 = "Veuillez entrer le numéro correcte de la chambre";
