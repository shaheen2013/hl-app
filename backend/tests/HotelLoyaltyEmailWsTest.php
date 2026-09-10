<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 17/04/2018
 * Time: 11:23
 */


class HotleLoyaltyEmailWsTest extends MainHotelinkingTestConfiguration
{

    protected function tearDown(){
        $sql = "TRUNCATE TABLE connection_history";
        escritura($sql);

        $sql = "TRUNCATE TABLE offer_goals";
        escritura($sql);

        $sql = "DELETE FROM hotel_oferta WHERE id = 1";
        escritura($sql);

        $sql2 = "TRUNCATE TABLE users_visits";
        escritura($sql2);

        $sql3 = "TRUNCATE TABLE `birthday_alarms`";
        escritura($sql3, null,true,2);

        $sql3 = "TRUNCATE TABLE `regular_customer`";
        escritura($sql3, null,true,2);

        $sql3 = "TRUNCATE TABLE `chain_loyalty_offers`";
        escritura($sql3, null,true,2);
    }

    public function testLoyaltyUserCallWebservice()
    {
        $sql_user = "SELECT id FROM users";
        $user = lectura($sql_user);
        $sql_hotel = "SELECT id FROM hoteles";
        $hotel = lectura($sql_hotel);
        $sql_hotel = "SELECT id_cadena FROM cadena_hotel where id_hotel = ". $hotel['id'];
        $chain = lectura($sql_hotel);

        $today = date("Y-m-d");

        $sql = "UPDATE  `hoteles` SET `loyalty_emails`='test@gmail.com,test2@gmail.com', `loyalty_alerts`='1' WHERE `id`= ". $hotel['id'];
        escritura($sql);

        $sql = "INSERT INTO `users_visits` (hotel_id, chain_id, user_id, last_login, num_visits)
            VALUES ('".$hotel['id']."', null, ".$user['id'].", '$today', '1') ";
        escritura($sql);

        $sql = "INSERT INTO `users_visits` (hotel_id, chain_id, user_id, last_login, num_visits)
            VALUES (null, '".$chain['id_cadena']."', ".$user['id'].", '$today', '1') ";
        escritura($sql);

        $sql = "INSERT INTO hotel_oferta (id, id_hotel, id_cadena, id_tipo_oferta, id_categoria, id_subcategoria, adq_ret, inicio, fin, cupo, adquiridas, canjeadas, descuento, coste, moneda, requerimientos, puntos, img, estado, fecha_creacion, fecha_publicada, booking_engine_code) 
        VALUES ('1', '0', ".$chain['id_cadena'].", '', '', '', '', '2018-04-02', '0000-00-00', '0', '0', '0', '0', '0', '', '0', '0', '', '6', '2018-04-16 11:26:36', '2018-04-16 11:26:39', 'test')";
        escritura($sql);

        $sql = "INSERT INTO `offer_goals` (hotel_id, chain_id, offer_id,product_id, n_triggers, days_to_expire, offer_type)
            VALUES (null, '".$chain['id_cadena']."',1, 11, '2',15,'web') ";
        escritura($sql);

        $this->client->request('POST', SECURE_BASE_PATH . LIB . 'webservices/hotel-loyalty-email-ws.php', ['form_params' =>[
            'testing'=> true,
            'room_id' => 101,
            'emails' => 'test@gmail.com,test2@gmail.com',
            'chain_id' => $chain['id_cadena'],
            'user_id'=>$user['id'],
            'hotel_id'=>$hotel['id'],
        ]]);

        $this->client->request('POST', SECURE_BASE_PATH . LIB . 'webservices/chain-loyalty-email-ws.php', ['form_params' =>[
            'testing'=> true,
            'user'=> ["id"=>$user['id'],"email"=>"garridorosichricardo@gmail.com","name"=>"Ricardo Garrido Rosich","lang"=>"es","gender"=>"male","birthday"=>$today,"locale"=>"es","isNew"=>false,"regularUser"=>false,"stayTimeReconnection"=>false,"source"=>"form","sendex"=>0,"result"=>"risky", "num_visits"=>1],
            'hotel_id'=>$hotel['id'],
        ]]);


        $sql = "SELECT * FROM regular_customer where user_id = ".$user['id']." AND hotel_id = ".$hotel['id'];
        $answer_regular_customer = lectura($sql, null,true,2);
        $this->assertEquals(null,$answer_regular_customer, "The validation for send regular_customer is failing or the DB was not cleaned properly");

        $sql = "SELECT * FROM `chain_loyalty_offers` where user_id = ".$user['id']." AND hotel_id = ".$hotel['id'];
        $answer_regular_customer = lectura($sql, null,true,2);
        $this->assertEquals(null,$answer_regular_customer, "The validation for send loyalty_offer is failing or the DB was not cleaned properly");

        $sql = "UPDATE  `users_visits` SET `last_login`= '2011-04-11 08:28:34', num_visits = 2";
        escritura($sql);

        $this->client->request('POST', SECURE_BASE_PATH . LIB . 'webservices/hotel-loyalty-email-ws.php', ['form_params' =>[
            'testing'=> true,
            'room_id' => 101,
            'emails' => 'test@gmail.com,test2@gmail.com',
            'chain_id' => $chain['id_cadena'],
            'id_user'=>$user['id'],
            'id_hotel'=>$hotel['id'],
        ]]);

        $this->client->request('POST', SECURE_BASE_PATH . LIB . 'webservices/chain-loyalty-email-ws.php', ['form_params' =>[
            'testing'=> true,
            'user'=> ["id"=>$user['id'],"email"=>"garridorosichricardo@gmail.com","name"=>"Ricardo Garrido Rosich","lang"=>"es","gender"=>"male","birthday"=>$today,"locale"=>"es","isNew"=>false,"regularUser"=>false,"stayTimeReconnection"=>false,"source"=>"form","sendex"=>0,"result"=>"risky", "num_visits"=>2],
            'hotel_id'=>$hotel['id'],
        ]]);

        $sql = "SELECT * FROM regular_customer where user_id = ".$user['id']." AND hotel_id = ".$hotel['id'];
        $answer_regular_customer = lectura($sql, null,true,2);
        $this->assertNotEquals(null,$answer_regular_customer, "The validation for send regular_customer is failing or the DB was not cleaned properly");

        $sql = "SELECT * FROM `chain_loyalty_offers` where user_id = ".$user['id']." AND hotel_id = ".$hotel['id'];
        $answer_regular_customer = lectura($sql, null,true,2);
        $this->assertNotEquals(null,$answer_regular_customer, "The validation for send loyalty_offer is failing or the DB was not cleaned properly");


        $sql = "UPDATE  `hoteles` SET `loyalty_emails`='', `loyalty_alerts`='0' WHERE `id`= ". $hotel['id'];
        escritura($sql);
    }
}






