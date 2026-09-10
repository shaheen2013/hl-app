<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {echo 'No direct access allowed.';exit;}

function consultaBDLoginHotel($email_login)
{
    $email_login = mysqli_real_escape_string(conectar(), $email_login);

    //-------------------------------------------------------------------------------
    // Función de consulta a BD de login de Hotelero, Staff Hotelero o Cadena
    // Recibe: $email_login
    // Devuelve: array '$result'
    //-------------------------------------------------------------------------------

    //---------------------
    // El email de la cadena es utilizado para el hotel al ser creado.
    // Primero debemos mirar si el email es de una cadena antes de mirar si es de un hotel

    // Mirar si es cadena ------------------------------CADENA--
    $sql3 = "SELECT cadena.id, cadena.password FROM cadena WHERE email='" . $email_login . "'";
    $rs3 = mysqli_query(conectar(), $sql3) or die(mysqli_error());
    $n_resultados3 = mysqli_num_rows($rs3);
    if ($n_resultados3 == 0) {
        //No es cadena
        // Mirar si es hotel ------------------------------HOTEL--

        $sql = "SELECT hoteles.id, hoteles.password, hoteles.verificado
		FROM hoteles
		WHERE email = '" . $email_login . "' AND hoteles.password!='' LIMIT 1";
        $rs = mysqli_query(conectar(), $sql) or die(mysqli_error());
        $n_resultados = mysqli_num_rows($rs);
        if ($n_resultados == 0) {
            //No es ni hotel ni cadena
            // Mirar si es un staff del Hotel--------------------------STAFF--
            $sql2 = "SELECT hotel_staff.id, hotel_staff.password, hotel_staff.activo
			FROM hotel_staff
			WHERE email='" . $email_login . "' AND deleted=0";
            $rs2 = mysqli_query(conectar(), $sql2) or die(mysqli_error());
            $n_resultados2 = mysqli_num_rows($rs2);
            if ($n_resultados2 == 0) {
                $result['error'] = '4029'; // Usuario inexistente ------------
                $result['defaultController'] = 'error';
            } else {
                // Es un Staff del Hotel
                $result['type'] = 'staff';
                $row2 = mysqli_fetch_array($rs2);
                $result['id'] = $row2['id'];
                if ($row2['activo'] == '1') {
                    $result['error'] = '200';
                } else {
                    $result['error'] = '4030'; // Cuenta no activada
                }
            }
            liberar($rs2);
        } else {
            //Es hotel
            $result['type'] = 'hotel';
            $row = mysqli_fetch_array($rs);
            $result['id'] = $row['id'];
            if ($row['verificado'] == 1) {
                //cuenta de usuario verificada
                $result['error'] = '200';
            } else {
                $result['error'] = '4045'; //Cuenta de usuario no activada
                $result['defaultController'] = 'error';
            }    
        }
        liberar($rs);
    } else {
        // Es una cadena
        $result['type'] = 'cadena';

        $row3 = mysqli_fetch_array($rs3);
        $result['id'] = $row3['id'];

        $result['error'] = '200';
    }
    liberar($rs3);
    return $result;
}

function guardarArrayResultRecovery($tipo, $id, $nombre, $error)
{
    $result['tipo'] = $tipo;
    $result['id'] = $id;
    $result['name'] = $nombre;
    $result['error'] = $error;

    return $result;
}

function recoveryPass($email)
{
    //-------------------------------------
    // Función de recovery del password del login de hotelero (hotel o cadena)
    // Recibe: $email
    // Devuelve: $error
    //-------------------------------------

    $email = mysqli_real_escape_string(conectar(), $email);

    include_once LIB . 'generarPass.php';

    //-----------------------
    // El email de la cadena es utilizado para el hotel al ser creado.
    // Primero debemos mirar si el email es de una cadena antes de mirar si es de un hotel

    // Mirar si es cadena
    $sql = "SELECT cadena.id, cadena.email, cadena.nombre AS name
	FROM cadena WHERE email='" . $email . "'";
    $rs = mysqli_query(conectar(), $sql) or die(mysqli_error());
    $n_resultados2 = mysqli_num_rows($rs);
    $row = mysqli_fetch_assoc($rs);
    liberar($rs);
    if ($n_resultados2 == 0) {
        //Mirar si es hotel
        $sql_recovery_email = "SELECT hoteles.id, hoteles.email, hoteles.name
		FROM hoteles WHERE email='" . $email . "'";
        $rs = mysqli_query(conectar(), $sql_recovery_email) or die(mysqli_error());
        $n_resultados = mysqli_num_rows($rs);
        $row = mysqli_fetch_assoc($rs);
        liberar($rs);
        if ($n_resultados != 0) {
            //Es hotel
            // Generamos el array de result
            $result = guardarArrayResultRecovery('hoteles', $row['id'], $row['name'], '200');
        } else {
            $sql = "SELECT hotel_staff.id, hotel_staff.nombre AS name
			FROM hotel_staff WHERE email='" . $email . "' ";
            $rs = mysqli_query(conectar(), $sql) or die(mysqli_error());
            $n_resultados = mysqli_num_rows($rs);
            $row = mysqli_fetch_assoc($rs);
            liberar($rs);
            if ($n_resultados != 0) {
                $result = guardarArrayResultRecovery('hotel_staff', $row['id'], $row['name'], '200');
            } else {
                // NO es ni hotel ni cadena ni staff
                $result = guardarArrayResultRecovery('none', '', '', '404');
            }
        }
    } else {
        // Es cadena
        // Generamos el array de result
        $result = guardarArrayResultRecovery('cadena', $row['id'], $row['name'], '200');
    }

    if ($result['error'] == '200') {
        // Generar nuevo pass
        $nuevoPass = generaPass();
        // Hashear nuevo pass
        $passHash = sha1($nuevoPass);
        // Update BD con el nuevo pass hasheado
        $sql = "UPDATE " . $result['tipo'] . " SET password='" . $passHash . "' WHERE email ='" . $email . "'";
        mysqli_query(conectar(), $sql) or die(mysqli_error());
        $result['nuevoPass'] = $nuevoPass;
    } else {
        // NO es ni hotel ni cadena ni staff. No generamos nuevo pass.
        $result['nuevoPass'] = '';
    }

    $result['email'] = $email;

    return $result;
}

function guardarIntentoLogin($email)
{
    $email = mysqli_real_escape_string(conectar(), $email);

    include_once LIB . 'fecha.php';
    $fechaHora = dateTimeHoy();

    $sql = "INSERT INTO hotel_login_attempts (email, attempts, time)
	VALUES ('" . $email . "', '1', '" . $fechaHora . "')
	ON DUPLICATE KEY UPDATE attempts=attempts+1, time='" . $fechaHora . "'";
    mysqli_query(conectar(), $sql) or die(mysqli_error());
}

function maximoIntentos($email)
{
    $email = mysqli_real_escape_string(conectar(), $email);

    include_once LIB . 'fecha.php';
    $fechaHora = dateTimeHoy();

    $sql = "SELECT attempts, time FROM hotel_login_attempts WHERE email='" . $email . "' ";
    $rs = mysqli_query(conectar(), $sql) or die(mysqli_error());
    $row = mysqli_fetch_assoc($rs);
    liberar($rs);

    // segundos desde el último intento
    $segundosUltimoIntento = strtotime($fechaHora) - strtotime($row['time']);

    if ($row['attempts'] >= '10' && $segundosUltimoIntento <= '60') {
        //maximo intentos 10 y debe esperar 60s
        //cuenta bloqueada
        return true;
    } else if ($row['attempts'] >= '10' && $segundosUltimoIntento > '60') {
        //maximo intentos 10 pero ya ha esperado los 60s
        //desbloquear cuenta
        borrarIntentosLogin($email);
        return false;
    } else {
        return false;
    }
}

// Reseteamos maximo de intentos de login
function borrarIntentosLogin($email)
{
    $email = mysqli_real_escape_string(conectar(), $email);

    $sql = "DELETE FROM hotel_login_attempts WHERE email='" . $email . "' ";
    mysqli_query(conectar(), $sql) or die(mysqli_error());
}
