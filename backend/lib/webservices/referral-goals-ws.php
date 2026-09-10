<?php
include 'librerias.php';// Librerias básicas
// Restringir ips que pueden acceder
include_once RUTA_DIR . LIB . 'check_access.php';
checkIpAccess('rego', $_SERVER['REMOTE_ADDR']);

include_once RUTA_DIR . LANG . $_SESSION['userLang'] . '.php';
include_once RUTA_DIR . LANG . $_SESSION['userLang'] . '/hotel-goals.php';
include_once RUTA_DIR . LIB . 'webservices/msgFeedback.php';
include_once RUTA_DIR . LIB . 'referral-goals-actions.php';

function obtenerGoalsReferral($hotel_id)
{
    $goals = [];
    $sql = "SELECT id, n_referrals, id_oferta FROM referral_goal
	WHERE id_hotel='$hotel_id' ORDER BY n_referrals ASC";
    $rs = mysqli_query(conectar(1), $sql);
    $i = 0;
    while ($row = mysqli_fetch_assoc($rs)) {
        foreach ($row as $key => $valor) {
            $goals[$i][$key] = $valor;
        }
        $i++;
    }
    liberar($rs);
    return $goals;
}

function obtenerSelectOfertasReferral($hotel_id, $goals, $id_oferta, $id_goal, $n_referrals)
{
    global $log;
    $sql = "SELECT hotel_oferta.id, 
        case when oferta_lang.nombre is null 
        then   oferta_en.nombre 
        else oferta_lang.nombre end AS nombre 
	    FROM hotel_oferta
	    LEFT JOIN hotel_oferta_lang as oferta_en   on hotel_oferta.id = oferta_en.id_oferta   and oferta_en.lang='en' 
        LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='". $_SESSION['userLang'] . "'
	    WHERE hotel_oferta.adq_ret='ref' AND estado!='0' AND";
    if (hotelDeCadena($hotel_id)) {
        $id_cadena = hotelIdCadena($hotel_id);
        $sql .= " (id_hotel='" . $hotel_id . "' OR id_cadena='" . $id_cadena . "')";
    } else {
        $sql .= " id_hotel='" . $hotel_id . "'";
    }
    $sql .= " ORDER BY nombre ASC";
    $rs = mysqli_query(conectar(1), $sql);
    $select = '<select id=goalSelect-' . $id_goal . ' class="goal-select form-control" onchange="saveGoalOffer(this)"';
    /*if($n_referrals=='' || $n_referrals=='0'){
        $select .= 'disabled';
    }*/
    $select .= '>';
    $select .= '<option>...</option>';
    while ($row = mysqli_fetch_assoc($rs)) {
        $select .= '<option value="' . $row['id'] . '"  ';
        if ($id_oferta == $row['id']) {
            $select .= ' selected ';
        }
        $select .= ' >' . $row['nombre'] . '</option>';
    }
    $select .= '</select>';
    liberar($rs);

    return $select;
}

function mostarLinea($hotel_id, $goalsHotel, $n_referrals = '', $id_oferta = 0, $id_goal = 0)
{
    global $urlTree;
    global $hotelGoalsLang;
    $select = obtenerSelectOfertasReferral($hotel_id, $goalsHotel, $id_oferta, $id_goal, $n_referrals);
    $linea = '<tr class="table-row" id="line-' . $id_goal . '">
					<td>
						<input type="number" name="goal-' . $id_goal . '" onblur="saveGoal(this, ' . $id_goal . ')" class=" goal form-control" id=goal-' . $id_goal . ' value="' . $n_referrals . '" placeholder="Insert here the number of referrals attracted...">
						<input id="goal-' . $id_goal . '-h" type="hidden" value="' . $n_referrals . '" >
					</td>
					<td>
						<div class="col-lg-8">
							<div class="row">
								' . $select . '
							</div>
						</div>
						<div class="col-lg-4">
							<a class="btn btn-primary" id="newOffer-' . $id_goal . '" href="' . $urlTree['referral-goals'] . '/?newOffer=' . $id_goal . '">' . $hotelGoalsLang['Create new'] . '</a>
						</div>
					</td>
					<td class="text-right">
						<div class="btn-group goal-action-group" role="group" aria-label="...">
						  <button class="removeGoal btn btn-danger" onclick="removeGoal(' . $id_goal . ')"><i class="fa fa-times"></i></button>
						</div>
					</td>
				</tr>';

    return $linea;
}

function addPlus($linea)
{
    $linea = substr($linea, 0, -4);
    $linea .= '<span class="plusSpan"><button class="addGoal" onclick="addBlankLine()"><i class="fa fa-plus"></i></button></span><br>';
    return $linea;
}

function mostrarGoals($hotel_id)
{
    $goalsHotel = obtenerGoalsReferral($hotel_id);
    $goals = '';
    if (empty($goalsHotel)) {
        $goals .= mostarLinea($hotel_id, $goalsHotel);
    } else {
        foreach (array_keys($goalsHotel) as $key) {
            $goals .= mostarLinea($hotel_id, $goalsHotel, $goalsHotel[$key]['n_referrals'], $goalsHotel[$key]['id_oferta'], $goalsHotel[$key]['id']);
        }
        $goals = addPlus($goals);
    }
    echo $goals;
}

function existeGoal($n)
{
    $sql = "SELECT n_referrals AS n FROM referral_goal 
	WHERE id_hotel='" . $_SESSION['h_logueado'] . "' AND n_referrals='" . $n . "' ";
    $rs = mysqli_query(conectar(1), $sql);
    $row = mysqli_fetch_assoc($rs);
    liberar($rs);
    if ($row['n'] == $n) {
        return true;
    } else {
        return false;
    }
}

function guardarGoal($id_goal, $n)
{
    if (existeGoal($n)) {
        $result['exists'] = '1';
        $result['code'] = msgFeedbackWs('4019', $_SESSION['userLang']);
    } else {
        $result['exists'] = '0';
        if ($id_goal != '0') {
            $sql = "UPDATE referral_goal SET n_referrals='" . $n . "' 
			WHERE id_hotel='" . $_SESSION['h_logueado'] . "' AND id='" . $id_goal . "' ";
        } else {//Es un goal nuevo
            $sql = "INSERT INTO referral_goal (id_hotel, n_referrals) 
			VALUES ('" . $_SESSION['h_logueado'] . "', '" . $n . "')";
        }
        $link = conectar();
        mysqli_query($link, $sql);
        $id = mysqli_insert_id($link);// id insert/update

        if ($id_goal == '0') {
            $result['id'] = $id;
        } else {
            $result['id'] = '0';
        }
        $result['code'] = msgFeedbackWs('2007', $_SESSION['userLang']);

        //Borrar cache
        deleteCacheByTag('hotel_goals_' . $_SESSION['h_logueado']);
    }
    echo json_encode($result);
}

function guardarGoalOffer($id_goal, $id_oferta)
{
    $array = explode('-', $id_goal);
    $id_goal = $array[1];

    if ($id_goal != '0') {
        $sql = "UPDATE referral_goal SET id_oferta='" . $id_oferta . "' 
		WHERE id_hotel='" . $_SESSION['h_logueado'] . "' AND id='" . $id_goal . "' ";
    } else {//Es un goal nuevo
        $sql = "INSERT INTO referral_goal (id_hotel, id_oferta) 
		VALUES ('" . $_SESSION['h_logueado'] . "', '" . $id_oferta . "')";
    }
    $link = conectar();
    mysqli_query($link, $sql);
    $id = mysqli_insert_id($link);// id insert/update
    if ($id_goal == '0') {
        $result['id'] = $id;
    } else {
        $result['id'] = 0;
    }
    $result['code'] = msgFeedbackWs('2007', $_SESSION['userLang']);

    //Borrar cache
    deleteCacheByTag('hotel_goals_' . $_SESSION['h_logueado']);

    echo json_encode($result);
}

function borrarGoal($id_goal)
{
    $sql = "DELETE FROM referral_goal WHERE id='" . $id_goal . "' AND id_hotel='" . $_SESSION['h_logueado'] . "' ";
    mysqli_query(conectar(), $sql);
    $result['code'] = msgFeedbackWs('2031', $_SESSION['userLang']);

    //Borrar cache
    deleteCacheByTag('hotel_goals_' . $_SESSION['h_logueado']);

    echo json_encode($result);
}

//Cambia el estado del Iframe de activado y descativado en el Pre stay $id = id hotel
//$iframeState = estado actual del iframe
function changeIframeState($id, $iframeState, $type)
{
    $id = mysqli_real_escape_string(conectar(), $id);
    $iframeState = mysqli_real_escape_string(conectar(), $iframeState);
    $type = mysqli_real_escape_string(conectar(), $type);
    $newIframeState = ($iframeState == 'enabled' ? 'enabled' : 'disabled');
    if ($type == 'pre-stay') {
        $sql = "UPDATE hoteles SET iframe='$newIframeState' WHERE id='$id'";
        //Borrar cache
        deleteCacheByTag('hotel_iframe_' . $id . '_' . false);
    } else {
        $sql = "UPDATE hoteles SET landing_iframe='$newIframeState' WHERE id='$id'";
        //Borrar cache
        deleteCacheByTag('hotel_iframe_' . $id . '_' . true);
    }
    $rs = mysqli_query(conectar(), $sql);
    $result['code'] = msgFeedbackWs('2007', $_SESSION['userLang']);
    $result['iframeState'] = $newIframeState;
    echo json_encode($result);
    return;
}

//Fx para guardar la información del wifi de stay
function guardarDatosStay($hotel_id, $url, $urlLogin, $username, $password, $secret, $time, $siteID, $guest_enabled)
{
    global $log;
    $con = conectar();

    $url = (!empty($url) ? mysqli_real_escape_string($con, $url) : null);
    $urlLogin = (!empty($urlLogin) ? mysqli_real_escape_string($con, $urlLogin) : null);
    $username = (!empty($username) ? mysqli_real_escape_string($con, $username) : null);
    $password = (!empty($password) ? mysqli_real_escape_string($con, $password) : null);
    $secret = (!empty($secret) ? mysqli_real_escape_string($con, $secret) : null);
    $time = (!empty($time) ? mysqli_real_escape_string($con, $time) : null);
    $siteID = (!empty($siteID) ? mysqli_real_escape_string($con, $siteID) : 'placeholder_' . $hotel_id);

    $sql = "INSERT INTO hotel_oferta_stay (id_hotel, url, form_url, username, password, secret, unifi_time, unifi_site_id, guest_enabled) 
              VALUES ($hotel_id, '$url', '$urlLogin', '$username', '$password', '$secret', '$time', '$siteID', $guest_enabled) 
              ON DUPLICATE KEY UPDATE url=VALUES(url), form_url=VALUES(form_url), username=VALUES(username), password=VALUES(password), secret=VALUES(secret), unifi_time=VALUES(unifi_time), unifi_site_id=VALUES(unifi_site_id), guest_enabled=VALUES(guest_enabled)";

    try {
        $query = escritura($sql, $con);
        if ($query === false) {
            throw new Exception(mysqli_error($con));
        }
    } catch (Exception $e) {
        throw new Exception($e);
    }

    //Borrar cache de hotel_wifi_integrations
    deleteCacheByKey('wifiStay_' . $hotel_id);
}

//Fx para borrar un oferta de stay
//$tipo: 'pre', 'post'
function borrarOfertaStay($hotel_id, $tipo)
{
    $sql = "DELETE FROM hotel_oferta_" . $tipo . " WHERE id_hotel='" . $hotel_id . "' ";
    mysqli_query(conectar(), $sql);

    //Borrar cache
    deleteCacheByTag('hotel_goals_' . $hotel_id);
}

if (!empty($_POST['hid']) && !empty($_POST['iframeState'])) {
    changeIframeState($_POST['hid'], $_POST['iframeState'], $_POST['type']);
}

if (!empty($_POST['showGoals']) && $_POST['showGoals'] == '1') {
    $goals = mostrarGoals($_SESSION['h_logueado']);
}

if (!empty($_POST['saveGoal']) && $_POST['saveGoal'] == '1') {
    $n = mysqli_real_escape_string(conectar(), $_POST['n']);
    $id_goal = mysqli_real_escape_string(conectar(), $_POST['id']);
    guardarGoal($id_goal, $n);
}

if (!empty($_POST['saveGoalOffer']) && $_POST['saveGoalOffer'] == '1') {
    $idGoal = mysqli_real_escape_string(conectar(), $_POST['idGoal']);
    $idOffer = mysqli_real_escape_string(conectar(), $_POST['idOffer']);
    guardarGoalOffer($idGoal, $idOffer);
}

if (!empty($_POST['addBlankLine']) && $_POST['addBlankLine'] == '1') {
    $goalsHotel = obtenerGoalsReferral($_SESSION['h_logueado']);
    $linea = mostarLinea($_SESSION['h_logueado'], $goalsHotel);
    $linea = addPlus($linea);
    echo $linea;
}

if (!empty($_POST['delGoal'])) {
    $id_goal = mysqli_real_escape_string(conectar(), $_POST['delGoal']);
    borrarGoal($id_goal);
}

if (!empty($_POST['prestay']) || !empty($_POST['stay']) || !empty($_POST['poststay']) || !empty($_POST['login'])) {
    if (!empty($_POST['prestay'])) {
        $oferta = $_POST['prestay'];
    } elseif (!empty($_POST['stay'])) {
        $oferta = $_POST['stay'];
    } elseif (!empty($_POST['poststay'])) {
        $oferta = $_POST['poststay'];
    } else {
        $oferta = '';
    }

    $action = $_POST['action'];

    if (empty($_SESSION['h_logueado'])) {
        $result['code'] = msgFeedbackWs('4065', $_SESSION['userLang'], null, null, null);
        echo json_encode($result);
        return;
    }

    if ($action == 'stay') {
        include_once RUTA_DIR . LIB . 'generarUrlCorrecta.php';
        $loginUrl= (empty($_POST['loginUrl']) ? null : generarURLCorecta($_POST['loginUrl']));
        $username = (empty($_POST['username']) ? null : $_POST['username']);
        $password= (empty($_POST['password']) ? null : $_POST['password']);
        $guest_enabled= (empty($_POST['guest_enabled']) ? 'false' : $_POST['guest_enabled']);
        $secret = (empty($_POST['secret']) ? null : $_POST['secret']);
        $time = (empty($_POST['time']) ? null : $_POST['time']);
        $siteID = (empty($_POST['siteID']) ? null : $_POST['siteID']);

        try {
            guardarDatosStay($_SESSION['h_logueado'], $oferta, $loginUrl, $username, $password, $secret, $time, $siteID, $guest_enabled);
        } catch (Exception $e) {
            $result['code'] = msgFeedbackWs('4065', $_SESSION['userLang'], null, null, null);
            echo json_encode($result);
            return;
        }
    } else {
        try {
            guardarOfertaStay($oferta, $_SESSION['h_logueado'], $action);
        } catch (Exception $e) {
            $result['code'] = msgFeedbackWs('4065', $_SESSION['userLang'], null, null, null);
            echo json_encode($result);
            return;
        }
    }

    $result['code'] = msgFeedbackWs('2007', $_SESSION['userLang'], null, null, null);
    echo json_encode($result);
}

//Esta borrando un oferta de stay (pre, post...)
if (!empty($_POST['delStay'])) {
    $delStay = mysqli_real_escape_string(conectar(), $_POST['delStay']);
    borrarOfertaStay($_SESSION['h_logueado'], $delStay);
    $result['msgError'] = msgFeedbackWs('2031', $_SESSION['userLang']);
    echo json_encode($result);
}

/// Refactor ///
if (!empty($_POST['action'])) {
    // It updates an offer by type
    if ($_POST['action'] === 'updateOfferByType') {
        echo $result = updateOfferByType($_POST['value'], $_POST['type']);
    }
    // It deletes an offer by type
    if ($_POST['action'] === 'removeOfferByType') {
        echo $result = removeOfferByType($_POST['type']);
    }
}

/// Methods ///

function updateOfferByType($offer_id, $offer_type)
{
    $con = conectar();
    $offer_id = mysqli_real_escape_string($con, $offer_id);
    $offer_type = mysqli_real_escape_string($con, $offer_type);
    $hotel_id = $_SESSION['h_logueado'];

    //Update Offer
    $sql = "INSERT INTO hotel_oferta_$offer_type (id_hotel, id_oferta) 
			VALUES ('$hotel_id', '$offer_id') 
	 		ON DUPLICATE KEY UPDATE id_oferta='$offer_id'";

    escritura($sql);

    //Borrar cache
    $tags = ['hotel_goals_' . $hotel_id, 'hotel_oferta_wifi_' . $hotel_id];
    deleteCacheByTags($tags);

    $result['code'] = msgFeedbackWs('2007', $_SESSION['userLang']);
    return json_encode($result);
}

function removeOfferByType($offer_type)
{
    $offer_type = mysqli_real_escape_string(conectar(), $offer_type);
    $hotel_id = $_SESSION['h_logueado'];

    $sql = "DELETE FROM hotel_oferta_" . $offer_type . " WHERE id_hotel='" . $hotel_id . "' ";
    mysqli_query(conectar(), $sql);

    //Borrar cache
    $tags = ['hotel_goals_' . $hotel_id, 'hotel_oferta_wifi_' . $hotel_id];
    deleteCacheByTags($tags);


    $result['msgError'] = msgFeedbackWs('2031', $_SESSION['userLang']);
    return json_encode($result);
}
