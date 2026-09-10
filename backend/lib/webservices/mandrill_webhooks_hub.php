<?php

require 'librerias.php';
require RUTA_DIR . LIB . 'composer/vendor/autoload.php';
require RUTA_DIR . LIB . 'stage_helpers.php';
include_once RUTA_DIR . LIB . 'stage_helpers.php';
//$_POST['mandrill_events'] = '[{"event":"click","ts":"1535025901","user_agent":"Mozilla/5.0 (iPhone; CPU iPhone OS 11_2_6 like Mac OS X) AppleWebKit/604.5.6 (KHTML, like Gecko) Mobile/15D100","user_agent_parsed":{"type":"Mobile Browser","ua_family":"Mobile Safari","ua_name":"Mobile Safari","ua_version":null,"ua_url":"http://en.wikipedia.org/wiki/Safari_%28web_browser%29","ua_company":"Apple Inc.","ua_company_url":"http://www.apple.com/","ua_icon":"http://cdn.mandrill.com/img/email-client-icons/safari.png","os_family":"iOS","os_name":"iOS","os_url":"http://en.wikipedia.org/wiki/IOS","os_company":"Apple Inc.","os_company_url":"http://www.apple.com/","os_icon":"http://cdn.mandrill.com/img/email-client-icons/iphone.png","mobile":true},"ip":"88.20.119.199","location":{"country_short":"ES","country":"Spain","region":"Madrid","city":"Madrid","latitude":40.4165000916,"longitude":-3.70255994797,"postal_code":"-","timezone":"+02:00"},"_id":"44b1f4613b74464191b506a250647a20","msg":{"ts":1559615565,"_id":"ef6375bc8ecc4586b6e73f3242e7620d","state":"sent","subject":"Amanda Ashley Oldfield, Rate your satisfaction and help us improve your stay ","email":"garridorosichricardo@gmail.com","opens":[{"ts":1522148439,"ip":"88.20.119.199","ua":"Mobile/iOS/iOS/Mobile Safari/Mobile Safari 11.0","location":"Madrid, ES"},{"ts":1522152149,"ip":"88.20.119.199","location":"Madrid, ES","ua":"Mobile/iOS/iOS/Mobile Safari/Mobile Safari"}],"clicks":[{"ts":1522148439,"url":"https://app.hotelinking.com/satisfaction-survey/?tk=d58c792d2dc445e2bf098542ef011f1b905dd6d507b2492fb2e16f4789b8c6b3-281816","ip":"88.20.119.199","location":"Madrid, ES","ua":"Mobile/iOS/iOS/Mobile Safari/Mobile Safari 11.0"},{"ts":1522153604,"url":"https://app.hotelinking.com/satisfaction-survey/?tk=d58c792d2dc445e2bf098542ef011f1b905dd6d507b2492fb2e16f4789b8c6b3-281816","ip":"88.20.119.199","location":"Madrid, ES","ua":"Mobile/iOS/iOS/Mobile Safari/Mobile Safari 11.0"}],"smtp_events":[{"ts":1534741505,"type":"sent","diag":"250 ok queued","source_ip":"198.2.135.28","destination_ip":"98.137.157.43","size":17386}],"subaccount":"hotelinking","resends":[],"_version":"llMzENElFAIILVt6eUe92w","sender":"garridorosichricardo@gmail.com","template":"satisfaction-email"}},{"event":"click","ts":"1535025901","user_agent":"Mozilla/5.0 (iPhone; CPU iPhone OS 11_2_6 like Mac OS X) AppleWebKit/604.5.6 (KHTML, like Gecko) Mobile/15D100","user_agent_parsed":{"type":"Mobile Browser","ua_family":"Mobile Safari","ua_name":"Mobile Safari","ua_version":null,"ua_url":"http://en.wikipedia.org/wiki/Safari_%28web_browser%29","ua_company":"Apple Inc.","ua_company_url":"http://www.apple.com/","ua_icon":"http://cdn.mandrill.com/img/email-client-icons/safari.png","os_family":"iOS","os_name":"iOS","os_url":"http://en.wikipedia.org/wiki/IOS","os_company":"Apple Inc.","os_company_url":"http://www.apple.com/","os_icon":"http://cdn.mandrill.com/img/email-client-icons/iphone.png","mobile":true},"ip":"88.20.119.199","location":{"country_short":"ES","country":"Spain","region":"Madrid","city":"Madrid","latitude":40.4165000916,"longitude":-3.70255994797,"postal_code":"-","timezone":"+02:00"},"_id":"44b1f4613b74464191b506a250647a20","msg":{"ts":1559615565,"_id":"ef6375bc8ecc4586b6e73f3242e7620d","state":"sent","subject":"Amanda Ashley Oldfield, Rate your satisfaction and help us improve your stay ","email":"garridorosichricardo@gmail.com","tags":["satisfaction_email"],"opens":[{"ts":1522148439,"ip":"88.20.119.199","ua":"Mobile/iOS/iOS/Mobile Safari/Mobile Safari 11.0","location":"Madrid, ES"},{"ts":1522152149,"ip":"88.20.119.199","location":"Madrid, ES","ua":"Mobile/iOS/iOS/Mobile Safari/Mobile Safari"}],"clicks":[{"ts":1522148439,"url":"https://app.hotelinking.com/satisfaction-survey/?tk=d58c792d2dc445e2bf098542ef011f1b905dd6d507b2492fb2e16f4789b8c6b3-281816","ip":"88.20.119.199","location":"Madrid, ES","ua":"Mobile/iOS/iOS/Mobile Safari/Mobile Safari 11.0"},{"ts":1522153604,"url":"https://app.hotelinking.com/satisfaction-survey/?tk=d58c792d2dc445e2bf098542ef011f1b905dd6d507b2492fb2e16f4789b8c6b3-281816","ip":"88.20.119.199","location":"Madrid, ES","ua":"Mobile/iOS/iOS/Mobile Safari/Mobile Safari 11.0"}],"smtp_events":[{"ts":1534741505,"type":"sent","diag":"250 ok queued","source_ip":"198.2.135.28","destination_ip":"98.137.157.43","size":17386}],"subaccount":"hotelinking","resends":[],"_version":"llMzENElFAIILVt6eUe92w","sender":"garridorosichricardo@gmail.com","template":"satisfaction-email"}}]';

if (empty($_POST)) {
//    $log->error('HUB STAGE HOOK: calling mandrill webhooks without POST, exiting');
    exit();
}

$hook = json_decode(stripslashes($_POST['mandrill_events']), true);
$email_types = getEmailTypesByTag();
$mandrill = new Mandrill(MANDRILL_API_KEY);
//TODO:This webhook has been fixed but needs a refactor, until that moment take care with continues

foreach ($hook as $single_hook) {
    $timeMsg = array_get($single_hook, 'msg.ts');
    $timeMsg = strtotime('+30 days', $timeMsg);

    if (time() <= $timeMsg && $timeMsg != null) {
        // Get relevant value from event json response
        $hook_data = [
            'event' => $single_hook['event'],
            'date' => $single_hook['ts'],
            'tag' => !empty($single_hook['msg']['tags'][0]) ? $single_hook['msg']['tags'][0] : null,
            'email_id' => !empty($single_hook['msg']['_id']) ? $single_hook['msg']['_id'] : null, // Only accessible for 30 days
            'brand_email' => $single_hook['msg']['sender'],
            'customer_email' => !empty($single_hook['msg']['email']) ? $single_hook['msg']['email'] : null,
            'open_from_country_short' => $single_hook['location']['country_short'],
            'open_from_country' => $single_hook['location']['country'],
            'open_from_region' => $single_hook['location']['region'],
            'open_from_latitude' => $single_hook['location']['latitude'],
            'open_from_longitude' => $single_hook['location']['longitude'],
            'open_from_mobile' => $single_hook['user_agent_parsed']['mobile'],
            'user_id' => null,
            'hotel_id' => null,
            'chain_id' => null
        ];

        $email_type = null;
        if (array_key_exists($hook_data['tag'], $email_types)) {
            $email_type = $email_types[$hook_data['tag']];
        }

        if (!is_null($email_type)) {

            if (empty($hook_data['tag'])) {
                $log->warning('HUB STAGE HOOK: calling mandrill webhooks without TAG, bypassing this event', $hook_data);
                continue;
            }

            // Try to get the original email from mandrill and retrieve metadata from it
            // Metadata is chain_id|hotel_id|user_id in head of email <meta name="metadata" content="XXX|XXX|XXXXXX">
            if (!empty($hook_data['customer_email'])) {
                // We added metadata to every email with user_id, hotel_id, and chain_id
                // <meta name="metadata" content="chain_id|hotel_id|user_id">
                // Try to get this metadata from original email
                $original_email = $mandrill->messages->content($single_hook['msg']['_id']);
                $metas = getMetaTags($original_email['html']);
            }


            if (isset($metas['metadata'])) {
                $metadata = explode('|', $metas['metadata']);

                if ($metadata[2] == '*') {
                    continue;
                }

                $hook_data['chain_id'] = $metadata[0] == 'null' ? null : $metadata[0];
                $hook_data['hotel_id'] = $metadata[1] == 'null' ? null : $metadata[1];
                $hook_data['user_id'] = $metadata[2] == 'null' ? null : $metadata[2];

            }
            // Try to change NULLS for IDS on metadatas
            $curated_hook_data = findAndFillWithIds($hook_data);
        } 
    }
}


///////////
//Helpers//
///////////

function getMetaTags($str)
{
    $pattern = '
  ~<\s*meta\s

  # using lookahead to capture type to $1
    (?=[^>]*?
    \b(?:name|property|http-equiv)\s*=\s*
    (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
    ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
  )

  # capture content to $2
  [^>]*?\bcontent\s*=\s*
    (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
    ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
  [^>]*>

  ~ix';

    if (preg_match_all($pattern, $str, $out)) {
        return array_combine($out[1], $out[2]);
    }
    return array();
}

function getUserIDByEmail($email)
{
//    global $log;
    $con = conectar(1);
    $email = mysqli_real_escape_string($con, $email);
    $cacheName = 'id_of_' . $email;
    $cache = getFromCache($cacheName);
    if (!$cache) {
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $result = lectura($sql, $con);
        if ($result) {
            setToCache($cacheName, $result, 3600);
        }
        return $result;
    }
    return $cache->get();
}

function getBrandByEmail($email, $brandType)
{
//    global $log;
    $con = conectar(1);
    $email = mysqli_real_escape_string($con, $email);
    $cacheName = $brandType . '_id_of_' . $email;
    $cache = getFromCache($cacheName);
    if (!$cache) {
        if ($brandType == 'chain') {
            $sql = "SELECT id FROM cadena WHERE email = '$email' OR email_envio = '$email'";
        } else {
            $sql = "SELECT id FROM hoteles WHERE sending_email = '$email'";
        }

        $result = lectura($sql, $con);
        if ($result) {
            setToCache($cacheName, $result, 3600);
        }
        return $result;
    }
    return $cache->get();
}

function getAllHotelIdsFromChain($chain_id)
{
//    global $log;
    $con = conectar(1);
    $cacheName = 'hotels_ids_from_chain_' . $chain_id;
    $cache = getFromCache($cacheName);
    if (!$cache) {
        $sql = "SELECT id_hotel FROM cadena_hotel WHERE id_cadena = $chain_id";
        $result = lecturaArray($sql, $con);
        if ($result) {
            setToCache($cacheName, $result, 3600);
            return $result;
        }
    }
    return $cache->get();
}

function getLastVisitedHotelFromList($hotels_ids, $user_id)
{
//    global $log;
    $con = conectar(1);
    $cacheName = 'last_visited_hotel_from_user_' . $user_id;
    $cache = getFromCache($cacheName);
    if (!$cache) {
        $sql = "SELECT hotel_id FROM users_visits WHERE user_id = $user_id AND hotel_id IN ($hotels_ids) ORDER BY last_login DESC LIMIT 1";
        $result = lectura($sql, $con);
        if ($result) {
            setToCache($cacheName, $result, 3600);
            return $result;
        }
    }
    return $cache->get();
}

function findAndFillWithIds($hook_data)
{
    if (is_null($hook_data['user_id'])) {
        // Try to find user id from email
        $user = getUserIDByEmail($hook_data['customer_email']);
        $hook_data['user_id'] = !empty($user['id']) ? $user['id'] : null;
    }


    if (is_null($hook_data['chain_id'])) {
        // Try to find chain id from email
        $chain = getBrandByEmail($hook_data['brand_email'], 'chain');
        $hook_data['chain_id'] = !empty($chain['id']) ? $chain['id'] : null;
    }

    // If hotel_id and chain_id is empty email can be from an independent hotel
    if (is_null($hook_data['hotel_id']) && is_null($hook_data['chain_id'])) {
        $hotel = getBrandByEmail($hook_data['brand_email'], 'hotel');
        $hook_data['hotel_id'] = !empty($hotel['id']) ? $hotel['id'] : null;
    }

    // Some hotels are from chain, but use its own email, check if this hotel is from chain
    if (!is_null($hook_data['hotel_id']) && is_null($hook_data['chain_id'])) {
        $chain = getChainIDByHotelID($hook_data['hotel_id']);
        $hook_data['chain_id'] = !empty($chain['id_cadena']) ? $chain['id_cadena'] : null;
    }

    // If user_id and chain_id is present but hotel_id is not present, try to find last hotel_id
    if (!is_null($hook_data['user_id']) && !is_null($hook_data['chain_id']) && is_null($hook_data['hotel_id'])) {
        $hotels_from_chain = getAllHotelIdsFromChain($hook_data['chain_id']);
        $hotels_from_chain = implode(',', array_flatten($hotels_from_chain));
        // Try to assign last visited hotel
        $hotel = getLastVisitedHotelFromList($hotels_from_chain, $hook_data['user_id']);
        $hook_data['hotel_id'] = !empty($hotel['hotel_id']) ? $hotel['hotel_id'] : null;
    }

    return $hook_data;
}
