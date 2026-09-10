<?php
/**
 * @param $user
 * @param $id_hotel
 * @return bool
 */
function createReviewSurvey($user, $hotel_id, $sending_days = null)
{
    global $log;
    $sendDate = $sending_days == null ? getSendDate($hotel_id, 'review') : $sending_days;
    //if after recover sendDate it still not defined we set the sendDate for 15 days after the current date
    if (!$sendDate) {
        $date = date('Y-m-d');
        $sendDate = date('Y-m-d', strtotime($date . ' + 15 day'));
    }
    $reviewCreated = createReviewSurveyOnEmailPlatform($user, $hotel_id, $sendDate);
    if (boolval($reviewCreated)) {
        $log->debug('Review survey created in Email Platform', array('id_user' => $user['id'], 'id_hotel' => $hotel_id, 'sendDate' => $sendDate));
    } else {
        return false;
    }
    return true;
}

function obtenerDatosHotelSatisfactionAndReview($id_hotel)
{
    //Get from cache
    $cacheName = 'hotel_satisfaction_' . $id_hotel;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $id_hotel = mysqli_real_escape_string($con, $id_hotel);

        $sql = "SELECT 
					hotel_satisfaction.puntMin,
					hotel_satisfaction.sendThanksMail,
					hotel_satisfaction.customized_chain_activated,
					hotel_review.diasEnvio as send_date,
					fotoBg,
					logo,
					hotelName,
					hotel_review.ignoreRating
				FROM hoteles 
				LEFT JOIN hotel_review ON hotel_review.id_hotel=hoteles.id
				LEFT JOIN hotel_satisfaction ON hotel_satisfaction.id_hotel=hoteles.id
				WHERE hoteles.id = $id_hotel 
		";
        $row = lectura($sql, $con);

        if ($row) {
            $tags = array('hotel', 'hotel_profile', 'hotel_profile_' . $id_hotel, 'hotel_review_' . $id_hotel, 'hotel_satisfaction_' . $id_hotel);
            setToCache($cacheName, $row, 31536000, $tags);
        }

    } else {
        $row = $cache->get();
    }

    return $row;
}

/**
 * @param $user
 * @param $id_hotel
 * @param $sendDate
 * @return bool|int|string
 */
function createReviewSurveyOnEmailPlatform($user, $id_hotel, $sendDate)
{
    global $log;
    $con = conectar(2);
    $id_user = $user['id'];
    $date = false;
    //Check if this user has a satisfaction from this hotel 30 days in past
    $checkDate = "SELECT created_at FROM reviews WHERE user_id = $id_user ORDER BY created_at DESC LIMIT 1";
    $checkDateRow = lectura($checkDate, $con, false);

    if (!empty($checkDateRow['created_at'])) {
        $date = date('Y-m-d', strtotime('+30 days', strtotime(str_replace('/', '-', $checkDateRow['created_at']))));
    }

    if (!$date || date('Y-m-d H:i:s') > $date) {
        $sql = "INSERT INTO reviews (user_id, hotel_id, send_date, created_at, updated_at)
		        VALUES ('$id_user', '$id_hotel', '$sendDate', NOW(), NOW())";
        $result = escritura($sql, $con);

        return $result;
    }

    return false;
}
