<?php
//OK
//general
$msg2003 = 'Well done! Please, check your email'; // contraseña mandada al email
$msg2004 = 'Alert! the position of the widget has changed, this will require you to update the tag on your web page ';
$msg2007 = 'Your data has been successfully saved';
//hotel check in
$msg2005 = 'Check-in ok!';
$msg2006 = 'Successfully validated';
//tienda
$msg2008 = 'Reward offer successfully redeemed';
$msg2010 = 'reward Offer added to wish list!';
//wishlist
$msg2009 = 'Reward offer deleted from wish list';
//
$msg2011 = 'Reward offer successfully shared';
$msg2012 = (!empty($points) ? $points . ' ' : '') . 'reward points added to your account successfully';
$msg2013 = 'You are now following this hotel/chain';
$msg2014 = 'You have unfollowed this hotel/chain';
// Add staff
$msg2015 = 'Staff created successfully!';
// staff-management
$msg2016 = 'Staff deleted';
//chain management
$msg2017 = 'New hotel created successfully!';
// chain-management
$msg2018 = 'Logged in as ' . (!empty($_SESSION['hotelName']) ? '<strong>' . $_SESSION['hotelName'] . '</strong>' : ' new hotel');
// invitar-usuarios-2
$msg2019 = 'List successfully saved!';
// invite-user-drafts
$msg2020 = 'List successfully deleted';
// cupon (regalar)
$msg2021 = 'Voucher successfully given';
//hotel profile 2
$msg2022 = 'Picture successfully deleted';
$msg2023 = 'Picture successfully prioritized';
// User-points (regalar puntos)
$msg2024 = 'Points successfully given!';
$msg2025 = 'Loyalty cards successfully activated';
$msg2026 = 'Loyalty cards successfully deactivated';
//landing page
$msg2027 = 'Reward assigned to landing page';
$msg2028 = 'Reward unassigned from landing page';
$msg2029 = 'Email changed, please check your email and confirm it'; // contraseña mandada al email
$msg2030 = 'Great! Valid email address';
$msg2031 = "Row successfully deleted";
$msg2032 = "Invalid emails successfully deleted";
$msg2033 = "Successfully shared on social media!";
$msg2034 = "Email successfully changed";
$msg2035 = "Invitation successfully sent again";

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
$msg4001 = 'Guest not found';
$msg4002 = 'This action is not permitted';
$msg4003 = "You must be logged in";
$msg4005 = 'This user has already been checked-in';
$msg4006 = 'Sorry, you can´t redeem this reward offer right now';
$msg4007 = 'Sorry, this reward offer has no seats left';
$msg4008 = 'Unfortunately, you don´t have enough reward points to redeem this rewards offer';
$msg4009 = 'This reward offer is only available for new guests (use blue rubies <i class="rubies rubix2 rubiesHL">rubies</i> ). You have been identified as a guest of this hotel already, so please use (red rubies <i class="rubies rubix2">rubies</i>) to redeem reward offers from this hotel';
$msg4010 = 'You need to be checked-in at the hotel to redeem this reward offer';
$msg4011 = 'This reward offer is not active at this moment';
$msg4012 = 'You only can redeem one of this reward offers. Since you have already redeem one, unfortunately you cannot redeem more of these. <br> Why? All reward offers that use the <i class="rubies rubix2 rubiesHL">rubies</i> rubie, can only be redeemed once. Blue rubies are meant to be used as discovery reward points (only to redeem reward offers, from hotels you have never been before)';
$msg4013 = 'Incorrect password, try again please';
$msg4014 = "Sorry, reward offers can't be shared two times";
$msg4015 = "You must be logged-in as a guest to take this action";
$msg4016 = "this user already exists as admin, can't be moved to a lower level. Try with a different email address instead";
$msg4017 = "This user already exists";
$msg4018 = "There are some invalid emails. Please correct them or an invite email will not be sent. The system will only send an invite to valid e-mail addresses";
$msg4019 = "There are some duplicated fields, the list can't be saved";
$msg4020 = "You don't have enough rewards points";
$msg4021 = "You can only give your vouchers";
$msg4022 = "This voucher has been vailidated already. Cannot be used again!";
$msg4023 = "You can only give reward offers, if you have enough rewards points";
$msg4024 = "Please, fill all the required fields";
$msg4025 = "Email already in use";
$msg4026 = "Blocked account, wait a while until next try";
// Hotel login errors
$msg4027 = "Wrong email, please try again"; //email mal formado (FILTER_VALIDATE_EMAIL)
$msg4028 = "Password must be longer";
$msg4029 = "Wrong password"; // email o pass incorrectos
$msg4030 = "Guest account it´s not active";
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
$msg4032 = "Logo must be jpg/gif/png";
$msg4033 = "Picture too big";
$msg4034 = "Passwords doesn't match";
$msg4035 = "Hotel closed this period";
$msg4036 = "'Until' date should be later than 'From' date"; //Fecha inicio superior fecha fin
$msg4037 = "You must be invited to create an account";
$msg4038 = "Incorrect token";
$msg4039 = "Can't share without social medias linked to Hotelinking";
$msg4040 = "Offer already given to this email address. Please use a different one.";
$msg4041 = "Actual level quantity can't be minor to prior level quantity or higher than next one";
$msg4042 = "This reward is in draft mode an cannot be activated. It needs to be published first";
$msg4043 = "无效的电子邮件，请您使用真实的电子邮件";
$msg4044 = "Incorrect password";
$msg4045 = "Guest account it´s not active";
$msg4046 = "Incorrect list format. Please make sure semicolon (;) is used to separate data.";
$msg4047 = "Invalid email address. Please make sure the email is a valid one.";
$msg4048 = "You can't referrer yourself";
$msg4049 = "'points until' should be greater than 'points from'";
$msg4050 = "'nights until' should be greater than 'nights from'";
$msg4051 = "'spent until' should be greater than 'spent from'";
$msg4052 = "Can't import empty list";
$msg4053 = "Please, fill all the required fields in all your languages";
$msg4054 = "You did not authorized Facebook to give us access to your email address. Consequently, we will not be able to send you your voucher via email. Go to Facebook, delete Hotelinking app and start over again.";
$msg4055 = 'You must redeem your existing voucher before you can earn a new one';
$msg4056 = 'Tweet has not been posted. It looks you have already posted, or you have exceeded the limit.';
// Usuario no ha verificado su email de la cuenta de twitter
$msg4057 = 'It appears that your email account has not been verfied by Twitter. Please make sure it is verfied and try again.';
$msg4058 = 'Sorry, WiFi cannot be accessed now. We are working to give access as soon as possible. Thanks for your patience.';
$msg4059 = 'URL is over 1.000 characters. The limit has been exceeded. Please try with another URL.';
$msg4060 = "You have entered different password. Please make sure both are the same password.";
$msg4061 = 'Please enable cookies on your current browser to proceed.';
$msg4062 = 'It seems that your account has not been verified by Facebook yet. Please, verify your account and try again.';
$msg4063 = 'An expected error ocurred with your Facebook account. Please try again later.';
$msg4064 = '当您试图登录时，发生了一个意外错误。请再试一次。';
//Error de base de datos
$msg4065 = "We had a problem with the database, please contact support for help";
$msg4067 = "Error al recibir datos, por favor contacta con soporte si persiste el problema";
$msg4101 = 'The product ' . (isset($productName) ? $productName : null) . ' has not been ' . (isset($active) ? ($active === '1' ? 'deactivated' : 'activated') : null) . ' correctly on brand id ' . (isset($brandIdsString) ? $brandIdsString: null);
$msg4102 = 'The product ' . (isset($productName) ? $productName : null) . ' has not been ' . ' edited correctly on brand id ' . (isset($brandIdsString) ? $brandIdsString: null);
//API errors
$msg4066 = 'API unreachable with those credentials';
$msg4068 = 'Esta campaña y oferta ya están mapeadas';
$msg4069 = 'Error al mapear esta campaña, faltan datos';
$msg4070 = 'We could not delete this item, please contact support if the problem persists';
$msg4077 = 'We cannot process the request with this data. Try other data or contact support if the problem persists';
//Error Curl Unifi
$msg4071 = "Wifi连接失败";
$msg4072 = "Please, try again or contact with reception if the problem persists";
//ERROR PMS VALIDATOR
$msg4073 = '输入的信息与我们的登记不符，如果您是入住的客人，请稍后再试 或请您与前台联系。';
$msg4074 = "All fields are required";

//GTM Errors
$msg4075 = "Google Tag Manager variables could not be recovered. Please make sure that the container_id and workspace_id are correct.";


//Referral Hotel
$msg5001 = 'The room list you provided does not seem to be correctly formatted, remember it should be separated by commas (ex: 1001, 1002, 1003';

//WARNING
$msg3005 = 'Guest successfully checked-in.<br> However, we have not send the invite email, because this guest already exists on your data base.';
$msg3006 = 'Blank data will be sent as default "0" if not modified';
$msg3007 = 'You already own this offer, please login online to check all your vouchers';
$msg3008 = 'You already requested this offer. We have sent you an email, please check your inbox.';
$msg3009 = 'Activated language found incomplete. You can edit later';
$msg3010 = 'We could not publish your offer';

//Privacy Policy Errors
//OnlyData
$msg4080 = 'If you want to use this option name, address, NIF, and email must be filled';
//hotel
$msg4081 = 'You need to fill the privacy conditions';
$msg4082 = 'You cannot activate the page since some traductions are missing';

//SESSION

$msg4083 = 'Last action was cancelled, your session was recently closed or changed';

//Email from pms not validated
$msg4090 = "系统获得的电子邮件无效，请您使用另一个电子邮件。";
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
$msg4099 = " 某些表单字段不正确，请检查。";
$msg4100 = "请您提供有效的房间号码。";