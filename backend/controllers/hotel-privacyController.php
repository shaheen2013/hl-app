<?php if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
//contenido solo disponible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing
// For front purposes
$currentSubPage = "hotel-privacy";

if ($_POST) {
    $validData = checkPostData($_POST);
    if ($validData) {
        $_POST['use_as_chain'] = !empty($_POST['use_as_chain']) ? 1 : 0;
        $stored = storeData($_POST);
        if ($stored) {
            $ok = array(true, '2007', 3000);
            unset($_SESSION['old_data']);
            $log->info('privacy policy changed for hotel ' . $_SESSION['c_logueado']);
        }
    } else {
        $ok = showError($_POST['option']);
        $_SESSION['old_data'] = $_POST;
    }

    if (!empty($_SESSION['old_data'])){
        $privacyData = $_SESSION['old_data'];
    } else {
        $privacyData = getData();
    }

} else {
    $privacyData = getData();
}

// HELPERS
function checkPostData($post)
{
    global $log;
    switch ($post['option']) {
        case 'onlyData':
            if (array_get($post, 'company_name', NULL) && array_get($post, 'company_address', NULL) && array_get($post, 'company_nif', NULL) && array_get($post, 'company_email', NULL)) {
                return true;
            }
            break;
        case 'hotel':
            if (array_get($post, 'privacy_conditions', NULL)) {
                return true;
            }
            break;
        case 'hotelinking':
            return true;
    }
    return false;
}

function showError($policyOption)
{
    switch ($policyOption) {
        case 'onlyData':
            return array(false, '4080', 5000);
            break;
        case 'hotel':
            return array(false, '4081', 5000);
            break;
        default:
            return array(false, '4002', 5000);
            break;
    }
}