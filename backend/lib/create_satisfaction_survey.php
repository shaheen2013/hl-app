<?php

/**
 * @param $user
 * @param $id_hotel
 * @param $id_chain
 * @param $ignoreRating
 * @return bool|int|string
 */
function createSatisfactionSurveyOnHotelinking($user_id, $id_hotel, $id_chain, $stayTime, $brandID, $brandSurvey, $customizedActive, $sendDate, $customizedSendDate, $ignoreRating = 0, $room_number = 'null')
{
    global $log;
    $con = conectar();
    // $id_user = $user['id'];
    $date = false;
    //Check if this user has a satisfaction from this hotel 30 days in past
    $checkDate = "SELECT fecha_creado FROM user_satisfaction WHERE id_usuario = $user_id AND id_hotel = $id_hotel ORDER BY fecha_creado DESC LIMIT 1";
    $checkDateRow = lectura($checkDate, $con, false);

    if (!empty($checkDateRow['fecha_creado'])) {
        $date = date('Y-m-d', strtotime('+' . $stayTime . ' days', strtotime(str_replace('/', '-', $checkDateRow['fecha_creado']))));
    }

    if (!$date || date('Y-m-d') > $date) {
        $log->debug('satisfaction survey can be created, dates match');
        $customizedSendDate = $customizedSendDate ? "'$customizedSendDate'" : 'NULL';

        $sql = "INSERT INTO user_satisfaction (id_usuario,id_hotel,id_cadena, send_date, fecha_creado, done, review_send, id_room, customized_send_date)
                VALUES ('$user_id', '$id_hotel', '$id_chain', '$sendDate', NOW(),0,$ignoreRating, '$room_number', $customizedSendDate)";

        $log->debug($sql);
        $result = escritura($sql, $con, false);

        $selectSurvey = "SELECT id FROM survey WHERE brand_id=$brandSurvey";
        $survey = lectura($selectSurvey, $con, false);

        if ($survey) {
            $insertUserSurvey = "INSERT INTO user_survey (survey_id, brand_id, user_id, access_code, send_date, customized_send_date, user_satisfaction_id, created_at, updated_at)
            VALUES (" . $survey['id'] . ", $brandID, $user_id, '$room_number', '$sendDate', $customizedSendDate, $result, NOW(), NOW())";
            
            escritura($insertUserSurvey, $con, false);
        }
       

        return $result;
    }

    return false;
}

/**
 * @param $user
 * @param $id_hotel
 * @param $sendDate
 * @param $url
 * @return bool|int|string
 */
function createSatisfactionSurveyOnEmailPlatform($user, $id_hotel, $sendDate, $url, $survey_id)
{
    $id_user = $user['id'];
    $con = conectar(2);
    $sql = "INSERT INTO satisfactions (user_id, hotel_id, send_date, satisfaction_url, created_at, updated_at, user_satisfaction_id) 
	VALUES ($id_user, '$id_hotel', '$sendDate', '$url', NOW(), NOW(), $survey_id)";
    return escritura($sql, $con);
}

/**
 * @param $user
 * @param $id_hotel
 * @param $guid_hotel
 * @param $id_chain
 * @param $ignoreRating
 * @return bool
 */
function createSatisfactionSurvey($user, $id_hotel, $guid_hotel, $id_chain, $stayTime, $brandID, $brandSurvey, $customizedActive, $checkoutDate, $ignoreRating = 0, $room_number = null)
{
    global $log, $urlTree;

    $log->debug('Creating satisfaction survey with : ' . json_encode(array($user, $id_hotel, $guid_hotel, $id_chain, $stayTime)));

    $sendDate = getSendDate($id_hotel, 'satisfaction');
    $selectTimeToSendSatisfaction = "SELECT customized_active, customized_type, customized_send_days, customized_send_hours FROM hotel_satisfaction WHERE id_hotel=$id_hotel";
    $timeToSendMail = lectura($selectTimeToSendSatisfaction);

    $customizedSendDate = null;
    if (array_get($timeToSendMail, 'customized_active') && $customizedActive) {
        $customDaysToSend = array_get($timeToSendMail, 'customized_send_days');
        $customHoursToSend = array_get($timeToSendMail, 'customized_send_hours') + ($customDaysToSend * 24);
        $customizedSendDate = $sendDate;

        if (array_get($timeToSendMail, 'customized_type') == 'In a later email') {
            $customizedSendDate = Date('Y-m-d H:i:s', strtotime('+' . $customHoursToSend .' hours'));
        }
        
        if (array_get($timeToSendMail, 'customized_type') == 'After PMS checkout date') {
            
            $stayTimeHoursToSend = $customHoursToSend + $stayTime * 24;

            $customizedSendDate = $checkoutDate 
                ? Date('Y-m-d H:i:s', strtotime($checkoutDate . ' + ' . $customHoursToSend .'hours')) 
                : Date('Y-m-d H:i:s', strtotime('+' . $stayTimeHoursToSend .' hours'));

            // If the date of sending the customized survey is before the normal date, we change 
            // the date so that they are sent at the same time.
            $sendDate = $customizedSendDate < $sendDate ? $customizedSendDate : $sendDate;
        }
    }

    $survey_id = createSatisfactionSurveyOnHotelinking($user['id'], $id_hotel, $id_chain, $stayTime, $brandID, $brandSurvey, $customizedActive, $sendDate, $customizedSendDate, $ignoreRating, $room_number);

    if (boolval($survey_id)) {
        $log->info('Satisfaction survey created in Hotelinking', array('id_user' => $user['id'], 'id_hotel' => $id_hotel, 'sendDate' => $survey_id));
    } else {
        $log->debug('Satisfaction survey not created in Hotelinking, another exists already, exiting', array('id_user' => $user['id'], 'id_hotel' => $id_hotel));
        return false;
    }

    //Create url to send in the email
    include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
    $brand_id = array_get(getHotelBrand($id_hotel), 'id');
    $surveyInfo = '{"brand_id" : '.$brand_id.', "survey_id" : '.$survey_id.'}';
    $surveyInfo = base64_encode($surveyInfo);
    $url = SURVEYS_URL . '/survey/'.$surveyInfo;

    $log->debug('creating url for satisfaction survery : ' . $url);

    $email_survey = createSatisfactionSurveyOnEmailPlatform($user, $id_hotel, $sendDate, $url, $survey_id);
    if ($email_survey) {
        $log->debug('Satisfaction survey created in email Platform');
    }

    return $survey_id;
}
