<?php
class usuarioEmails
{
    private $user_id; // id HL
    private $name;
    private $email;
    private $lang;
    private $birthday;
    private $user_id_emails; // id plataforma emails

    public function __construct($user_id, $name, $email, $lang, $birthday)
    {
        $this->user_id  = $user_id;
        $this->name     = $name;
        $this->email    = $email;
        $this->lang     = $lang;
        $this->birthday = $birthday;
    }

    public function saveUserEmails()
    {
//        $user_id = mysqli_real_escape_string(conectar(2), $this->user_id);
//        $name = mysqli_real_escape_string(conectar(2), $this->name);
//        $email = mysqli_real_escape_string(conectar(2), $this->email);
//        $lang = mysqli_real_escape_string(conectar(2), $this->lang);
//        $birthday = mysqli_real_escape_string(conectar(2), $this->birthday);
//
//        $sql2 = "INSERT users (id_hotelinking, name, email, lang, birthday) VALUES ('".$user_id."', '".$name."', '".$email."', '".$lang."', '".$birthday."')
//        ON DUPLICATE KEY UPDATE name='".$name."', email='".$email."', lang='".$lang."', birthday='".$birthday."', id=LAST_INSERT_ID(id) ";
//        $con = conectar(2);
//        $this->user_id_emails = escritura($sql2, $con, false);
//        $affected_rows = mysqli_affected_rows($con);
//        desconectar($con);
//        // "INSERT ... ON DUPLICATE KEY UPDATE" devuelve 1 en caso de INSERT, 2 en caso UPDATE, 0 ni INSERT ni UPDATE
//        if($affected_rows==1)
//        {
//            $action = 'insert';
//        }else if($affected_rows==2){
//            $action = 'update';
//        }else{
//            $action = 'none';
//        }
//
//        $arrayReturn['action']=$action;
//        return $arrayReturn;
    }

    public function getIdUsuarioEmails()
    {
        return $this->user_id_emails;
    }
}

class unsubscribePlataformaEmails
{
    private $user_id; // id HL
    private $email;
    private $user_id_emails; // id plataforma emails
    private $hash_unsubscribe;

    public function __construct($user_id, $email, $user_id_emails)
    {
        $this->user_id          = $user_id;
        $this->email            = $email;
        $this->user_id_emails   = $user_id_emails;
    }

    public function createUnsubscribeHashPlataformaEmails()
    {
        include_once RUTA_DIR . LIB . 'obtenerDatosUsuario.php';
        $guidUsuario = obtenerGUIDUsuarioId($this->user_id);
        include_once RUTA_DIR . LIB . 'make_unsuscribe_hash.php';
        $this->hash_unsubscribe = (generate_hash($this->email, $guidUsuario));
    }

    public function guardarUnsubscribePlataformaEmails()
    {
//        $sql = "INSERT unsuscribes (user_id, status, token) VALUES ('".$this->user_id_emails."', 'suscribed', '".$this->hash_unsubscribe."') ";
//        $con = conectar(2);
//        escritura($sql, $con);
    }
}

// FX que agrupa los procedimientos para crear un usuario (+ unsubscribe) en la plataforma de emails
function crearUsuarioEmails($id_usuario, $name, $email, $lang, $birth)
{
    $usuarioEmails = NEW usuarioEmails($id_usuario, $name, $email, $lang, $birth);
    $resultUsuarioEmails = $usuarioEmails->saveUserEmails();
    $user_id = $usuarioEmails->getIdUsuarioEmails(); // id del usuario en la plataforma de emails

    if ($resultUsuarioEmails['action'] == 'insert')
    {
        //usuario insertado nuevo, crear unsubscribe
        $unsubscribePlataformaEmails = NEW unsubscribePlataformaEmails($id_usuario, $email, $user_id);
        $unsubscribePlataformaEmails->createUnsubscribeHashPlataformaEmails();
        // $unsubscribePlataformaEmails->guardarUnsubscribePlataformaEmails();
    }

    $result['user_id'] = $user_id;

    return $result;
}
?>