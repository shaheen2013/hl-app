<?php
//Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

require_once (__DIR__.'/apiGateway.php');

/**
 * Validating email against lambda
 *
 * @param string $email
 *
 * @return array
 */
function validateEmail($email, $regularUser = false)
{
    global $log;
    if ((ENV == 'production' || VERIFY_EMAILS) && !$regularUser) {
        include_once RUTA_DIR . LIB . 'mailchecker/platform/php/MailChecker.php';

        $log->debug("validating email", [
            'email' => $email
        ]);

        //If emails is malformed or included in 2019 disposable email lists or is a spamtrap
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !MailChecker($email) || isSpamtrap($email)) {
            return [
                'code' => 400,
                "email_quality" => 'undeliverable',
                "email_rate" => 0.0,
            ];
        }

        // Instance of gateway
        $gateway = createApiGatewayConnection();
        $gateway->setResponseAll(true);

        $headers = [
            'Content-Type' => 'application/json',
        ];
        $data = [
            'email' => $email,
            'brand_id' => array_get($_SESSION, 'brandID') 
        ];
        try {
            $response = $gateway->sendRequest(
                $data, 
                "emails/validator", 
                "POST",
                $headers
            );
        } catch (Exception $e) {
            $response = $e->getResponse() ?? null;
        }

        // Get results from response
        $resultEmailValidation = json_decode($response->getBody(), true);
        $result = [
            'valid'     => $resultEmailValidation['valid'] ?? false,
            'code'      => $response->getStatusCode() ?? 400,
            'result'    => $resultEmailValidation['email_quality'] ?? 'risky',
            'sendex'    => $resultEmailValidation['email_rate'] ?? 0.0,
        ];

        // >> Setted as info to follow the validations on logs <<
        $log->info("result validation", [
            'email' => $email,
            'result' => $result
        ]);
        
        return $result;
    } else {
        // El environment NO es producción. No verificamos email, directamente permitimos envio.
        return [
            'code'      => 200,
            "valid"     => true,
            "result"    => 'deliverable',
            "sendex"    => SENDEX_SCORE + 0.1,
        ];
    }
}

function isSpamtrap($email)
{
    global $log;
    //Get from cache
    $cacheName = 'spamtraps';
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $rows = lecturaArray("select email from spamtraps");
        $spamtraps = array_pluck($rows, 'email');
        if ($spamtraps) {
            setToCache($cacheName, $spamtraps, 86400); // set spamtrap cache for 1 day
        }
    } else {
        $spamtraps = $cache->get();
    }
    $log->debug('spamtraps are', $spamtraps);
    return in_array($email, $spamtraps);
}
