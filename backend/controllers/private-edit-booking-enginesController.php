

<?php 
/********************************************************************/
/*                          ¡IMPORTANT!                             */
/* In localhost, this only works with path http://127.0.0.1/..      */
/********************************************************************/

//Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

$booking_engines = getBookingEngines ();

$booking_engine_id = array_get($_POST, 'booking_engine_id');
$booking_engine_container_id = array_get($_POST, 'gtm_booking_engine_container_id');
$booking_engine_workspace_id = array_get($_POST, 'gtm_booking_engine_workspace_id');

if (array_get($_POST, 'editContainerId')) {
    updateContainerId($booking_engine_container_id, $booking_engine_id);
    header('Location: ' . filter_var(GOOGLE_OAUTH_REDIRECT_URL, FILTER_SANITIZE_URL));
}

if (array_get($_POST, 'editWorkspaceId')) {
    updateWorkspaceId($booking_engine_workspace_id, $booking_engine_id);
    header('Location: ' . filter_var(GOOGLE_OAUTH_REDIRECT_URL, FILTER_SANITIZE_URL));
}

if (array_get($_POST, 'addNewBookingEngine')) {
    $booking_engine_name = array_get($_POST, 'booking_engine_name');
    
    insertNewBookingEngine($booking_engine_name, $booking_engine_container_id, $booking_engine_workspace_id);
    header('Location: ' . filter_var(GOOGLE_OAUTH_REDIRECT_URL, FILTER_SANITIZE_URL));
}


if (array_get($_POST, 'startMappingVariables')) {
    $log->debug("Let's map variables", []);

    global $log;

    $client = new Google_Client();
    $client->setAuthConfig($_ENV['GTM_AUTH']);
    $client->setScopes(array('https://www.googleapis.com/auth/tagmanager.readonly', 'https://www.googleapis.com/auth/tagmanager.edit.containers'));
    
    if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
        $log->debug("Have access tookkeeen", []);

        $client->setAccessToken($_SESSION['access_token']);

        try {
            $gtm = new Google_Service_TagManager($client);
            $gtm_response = $gtm->accounts_containers_workspaces_variables->listAccountsContainersWorkspacesVariables("accounts/".GTM_ACCOUNT_ID."/containers/".$booking_engine_container_id."/workspaces/".$booking_engine_workspace_id);
            $gtm_mapped_variables = map_variables($gtm_response);
            $log->debug("i'm going to update variables maaaaaaaap", []);

            updateGTMVariablesMap($gtm_mapped_variables, $booking_engine_id);
            $log->debug("Done, bitch!", []);

            header('Location: ' . filter_var(GOOGLE_OAUTH_REDIRECT_URL, FILTER_SANITIZE_URL));
        } catch (Google_Service_Exception $e) {
            global $log;
            $ok = array(false, '4075');
            $log->debug("Error getting variable list", ["ERROR" => $e]);
        }
        
    } else {

        $log->debug("Nothing man", []);


        $client = new Google_Client();
        $client->setAuthConfig($_ENV['GTM_AUTH']);
        $client->setRedirectUri(GOOGLE_OAUTH_REDIRECT_URL);
        $client->setScopes(array('https://www.googleapis.com/auth/tagmanager.readonly', 'https://www.googleapis.com/auth/tagmanager.edit.containers'));

        if (! isset($_GET['code'])) {
            $log->debug("No tenemos el codigo tio, que quieres que hagamos", []);
            $auth_url = $client->createAuthUrl();
            header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        } else {
            $log->debug("Tenemos el codigo!", []);

            if ($client->isAccessTokenExpired()) {
                $log->debug("Session Token Expired Niggi", []);
                $client->revokeToken();
                header('Location: '.GOOGLE_OAUTH_REDIRECT_URL);
            }
            $log->debug("All Right, let's LOGIN", []);

            $client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $client->getAccessToken();
            
            header('Location: ' . filter_var(GOOGLE_OAUTH_REDIRECT_URL, FILTER_SANITIZE_URL));
        }
    }
}

function map_variables($gtm_response) {
    $map_vars = array();
    foreach (array_get($gtm_response,'variable') as $gtm_variable) {
        $parameters = array_get($gtm_variable, 'parameter');
        
        if(is_array($parameters)) {
          foreach ($parameters as $parameter) {

            if (array_get($parameter, 'key') == 'name' && array_get($gtm_variable, 'type') == 'v') {
                $map_vars[array_get($gtm_variable, 'name')] = array_get($parameter, 'value');   
            }

            if (array_get($parameter, 'key') == 'value' && array_get($gtm_variable, 'type') == 'c') {
                $map_vars[array_get($gtm_variable, 'name')] = array_get($parameter, 'value');   
            }
          }
        }
    }

    return $map_vars;
}

?>