<?php if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include_once RUTA_DIR . LIB .  'obtenerDatosCadena.php';

function storeData($post)
{
    global $log;
    $con = conectar();
    $companyName = mysqli_real_escape_string($con, array_get($_POST,'company_name', NULL));
    $companyAddress = mysqli_real_escape_string($con, array_get($_POST,'company_address', NULL));
    $companyNIF = mysqli_real_escape_string($con, array_get($_POST,'company_nif', NULL));
    $companyEmail = mysqli_real_escape_string($con, array_get($_POST,'company_email', NULL));
    $privacyPolicyText = mysqli_real_escape_string($con, array_get($_POST,'privacy_conditions', NULL));
    $policyOption = mysqli_real_escape_string($con, array_get($_POST,'option', NULL));
    $chain = mysqli_real_escape_string($con, array_get($_POST,'use_as_chain', 0));
    // For localized privacy policies TODO
    $lang = mysqli_real_escape_string($con, array_get($_POST, 'lang', NULL));
    $chain_id = array_get($_SESSION, 'c_logueado', NULL);
    $hotel_id = array_get($_SESSION, 'h_logueado', NULL);

    if(!is_null($chain)){
        $hotel_ids = obtenerHotelesCadenaBasico($chain_id);
        $hotel_ids = array_column($hotel_ids, 'id');
    }

    $sql = "INSERT INTO hotels_privacy_policy (hotel_id, chain_id, company_name, company_address, company_nif, company_email, privacy_conditions, option, use_as_chain, created_at)
            VALUES ($hotel_id, $chain_id, '$companyName', '$companyAddress', '$companyNIF', '$companyEmail', '$privacyPolicyText', '$policyOption', $chain, NOW())
            ON DUPLICATE KEY UPDATE company_name = '$companyName', company_address = '$companyAddress', company_nif = '$companyNIF',
            company_email = '$companyEmail', privacy_conditions = '$privacyPolicyText', option = '$policyOption', use_as_chain = $chain, updated_at = NOW()";
    $result = escritura($sql, $con);
    if ($result){
        deleteCacheByKey("privacy_policy_" . $hotel_id);
    }
    return $result;
}
function getData()
{
    global $log;
    $con = conectar(1);
    $cache = getFromCache('privacy_policy_' . $_SESSION['h_logueado']);
    if (!$cache){
        $sql = "SELECT * FROM hotels_privacy_policy WHERE hotel_id = " . $_SESSION['h_logueado'];
        $result = lectura($sql, $con);
        if ($result){
            setToCache('privacy_policy_' . $_SESSION['h_logueado'], $result, 31536000);
        }
    } else {
        $result = $cache->get();
    }

    return $result;
}