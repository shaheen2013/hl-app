<?php
/**
 * @param $user
 * @return string
 */
function createToken($user_id)
{
    $salt = 'HoRop(Gr^+7QaJ lc7:Mj*VqO +Fqj?S8dS%>~R*{)06!QXE8)rZ)rh@kxi#F?6U';
    return hash('sha256', time() . $salt . $user_id);
}

/**
 * @param $id
 */
function deleteActiveOffer($id)
{
    $sql = "DELETE ort, uc FROM oferta_referral_token ort LEFT JOIN user_cupones uc ON ort.id_cupon = uc.id WHERE ort.id_oferta = $id ";
    escritura($sql);
}

/**
 * @param $token
 */
function deleteToken($token)
{
    $sql = "DELETE FROM pre_oferta_token WHERE token = '$token'";
    escritura($sql);
}

/**
 * @param $user
 * @param $id_hotel
 * @param $offer
 * @param $token
 */
function saveOfferToken($user_id, $id_hotel, $offer, $token, $origen_cupon_id)
{
    $offerID   = array_get($offer, 'offer.id');
    $offerType = array_get($offer, 'offer.type');
    $date      = date("Y-m-d H:i:s");
    $sql       = "INSERT INTO pre_oferta_token
                (id_usuario, id_hotel, id_oferta, token, tipo_oferta, id_origen_oferta, fecha)
            VALUES ('$user_id', '$id_hotel', $offerID, '$token', '$offerType', $origen_cupon_id, '$date') ";
    escritura($sql);
}

/**
 * @param $user
 * @param $id_hotel
 * @param $status
 * @param $offer
 * @return boolean
 */
function createWifiOfferOnEmailPlatform($user, $id_hotel, $status, $offer)
{
    global $log;
    global $urlTree;

    $con          = conectar(2);
    $offer_name   = mysqli_real_escape_string($con, $offer['description']['name']);
    $img = $offer['img'];
    $url = SECURE_BASE_PATH . $urlTree['create-offer-from-token'] . '/?tk=' . $status['token'];
    $sql = "INSERT INTO stay_offers (user_id, hotel_id, offer_name, offer_image, send_date, token, token_type, state, created_at) 
            VALUES ('{$user['id']}', $id_hotel, '$offer_name', '$img', '" . date('Y-m-d') . "', '$url', '{$status['type']}','{$status['status']}', NOW())
            ON DUPLICATE KEY UPDATE state = VALUES(state)";
    return escritura($sql, $con);
}

/**
 * @param $user
 * @param $id_hotel
 * @param $offer
 * @return array
 */
function setOffer($user_id, $id_hotel, $offer, $origen_cupon_id, $loyalty = false)
{
    global $log;

    // Check on DDBB user vouchers and offers
    $extraLoyaltySql = '';
    if ($loyalty) {
        $extraLoyaltySql = 'AND id_oferta = ' . array_get($offer, 'offer.id');
    }
    $sql1 = " SELECT tipo_oferta, token, id_oferta AS id_offer_from_token, fecha AS token_created_at FROM pre_oferta_token WHERE id_hotel = $id_hotel AND id_usuario = '$user_id' AND id_origen_oferta = $origen_cupon_id $extraLoyaltySql ORDER BY fecha DESC LIMIT 1";
    $sql2 = " SELECT id_cupon AS id_voucher, id_oferta AS id_offer, fecha AS offer_created_at FROM oferta_referral_token WHERE id_hotel = $id_hotel AND id_usuario = '$user_id' AND id_origen_oferta = $origen_cupon_id ORDER BY fecha DESC LIMIT 1";
    $sql3 = " SELECT id_oferta AS id_offer_used, fecha AS offer_used_at FROM used_promocode WHERE id_hotel = $id_hotel AND id_usuario = '$user_id' ORDER BY fecha DESC LIMIT 1";
    $con  = conectar(1);
    $log->debug($sql1);
    $token          = lectura($sql1, $con, false);
    $active_offer   = lectura($sql2, $con, false);
    $redeemed_offer = lectura($sql3, $con);
    $log->debug('user offer', array('token' => $token, 'active_offer' => $active_offer, 'redeemed_offer' => $redeemed_offer));

    // 1ST check
    // If user redeemed an offer and this offer is inside limits inform user is not elegible
    if (!empty($redeemed_offer)) {
        $date = date('Y-m-d', strtotime('+' . $offer['days_to_expire'] . ' days', strtotime(str_replace('/', '-', $redeemed_offer['offer_used_at']))));
        $log->debug('offer can not be redeemed in ' . $offer['days_to_expire'] . ' days again');
        // If 30 days has past since last redeem
        if (date('Y-m-d') > $date) {
            $new_token = createToken($user_id);
            saveOfferToken($user_id, $id_hotel, $offer, $new_token, $origen_cupon_id);
            $log->debug('Trying to send an offer to user');
            return array(
                'status' => 'first_send',
                'token'  => $new_token,
                'type'   => $offer['offer']['type']
            );
        } else {
            $log->debug('User has redeemed an offer already, trying to send message to user');
            return array(
                'status' => 'already_redeemed'
            );
        }
    }

    //2ND check
    // If user has a token from offer and this offer is active offer, give offer, if not create new token
    if (!empty($token)) {
        $log->debug('User has a token already, trying to send token again');
        if ($token['id_offer_from_token'] == array_get($offer, 'offer.id')) {
            // Create array, return with data to send to email platform
            return array(
                'status' => 'token_not_used',
                'token'  => $token['token'],
                'type'   => $offer['offer']['type']
            );
        } else {
            // Delete old token, create a new token and return
            deleteToken($token['token']);
            $new_token = createToken($user_id);
            saveOfferToken($user_id, $id_hotel, $offer, $new_token, $origen_cupon_id);
            return array(
                'status' => 'token_not_used',
                'token'  => $new_token,
                'type'   => $offer['offer']['type']
            );
        }
    }

    //3RD check
    // If user has an active offer and this offer is inside date limits, delete this offer and create a new one
    if (!empty($active_offer)) {
        $log->debug('User has an active offer already, delete and create a new offer');
        deleteActiveOffer($active_offer['id_offer']);
        $new_token = createToken($user_id);
        saveOfferToken($user_id, $id_hotel, $offer, $new_token, $origen_cupon_id);
        return array(
            'status' => 'voucher_not_used',
            'token'  => $new_token,
            'type'   => $offer['offer']['type']
        );
    }

    // Return a new offer if none of the above happens
    $log->debug('Giving wifi offer to user');
    $new_token = createToken($user_id);
    saveOfferToken($user_id, $id_hotel, $offer, $new_token, $origen_cupon_id);
    return array(
        'status' => 'first_send',
        'token'  => $new_token,
        'type'   => $offer['offer']['type']
    );
}

/**
 * @param $user
 * @param $id_hotel
 * @param $id_hotel_email
 * @param $action
 */
function sendWifiOffer($user, $id_hotel, $action, $customerType)
{
    global $log;
    // Get offer to give to the user
    $brand_id = getHotelBrand($id_hotel)['id'] ?? 0;
    $wifi_offer = getActiveWifiOffer($brand_id, $customerType, $user['lang']);
    $log->debug('active wifi offer at this moment is', ['wifi_offer' => $wifi_offer]);
    // If no offer exit!
    if (empty($wifi_offer['id'])) {
        $log->warning('Hotel has not selected any offer for captive portal, but gift is active, exiting');
        exit;
    }

    //Send offer email always if there is offer and user is elegible
    if (!empty($wifi_offer['id'])) {
        // Check if user is elegible for an offer
        $condition = array_get($wifi_offer, 'condition');
        if (
            ($condition === 'always' && $action === 'login') ||
            ($condition === 'always' && $action === 'facebook_login') ||
            ($condition === 'always' && $action === 'facebook_share') ||
            ($condition === 'facebook_share' && $action === 'facebook_share') ||
            ($condition === 'facebook_login' && $action === 'facebook_login')
        ) {
            $wifi_offer['offer']['id'] = $wifi_offer['offer_id'];
            $wifi_offer['days_to_expire'] = $wifi_offer['period'];
            $wifi_offer['offer']['type'] = $wifi_offer['offer_type'];

            $status = setOffer($user['id'], $id_hotel, $wifi_offer, 1);
            createWifiOfferOnEmailPlatform($user, $id_hotel, $status, $wifi_offer);
        } else {
            $log->debug('Not elegible, conditions do not match', ['condition' => $condition, 'action' => $action]);
        }
    }
}
