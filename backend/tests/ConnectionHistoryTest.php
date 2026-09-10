<?php


/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 09/04/2018
 * Time: 15:42
 */

require_once 'MainHotelinkingTestConfiguration.php';

class ConnectionHistoryTest extends MainHotelinkingTestConfiguration
{

    protected function tearDown(){
        $sql = "TRUNCATE TABLE connection_history";
        escritura($sql);

        $sql2 = "TRUNCATE TABLE users_visits";
        escritura($sql2);

        $sql3 = "TRUNCATE TABLE `birthday_alarms`";
        escritura($sql3, null,true,2);

        $sql3 = "TRUNCATE TABLE `regular_customer`";
        escritura($sql3, null,true,2);
    }

    public function testUserTriggerBirthdayAlarm()
    {
        $sql_user = "SELECT id FROM users";
        $user = lectura($sql_user);
        $sql_hotel = "SELECT id FROM hoteles";
        $hotel = lectura($sql_hotel);
        $today = date("Y-m-d");
        $this->client->request('POST', SECURE_BASE_PATH . LIB . 'webservices/connection-history.php', ['form_params' =>[
            'testing'=> true,
            'user_id'=>$user['id'],
            'hotel_id'=>$hotel['id'],
            'room_id'=>'101',
            'mac'=>'24:24:24:24',
            'source'=>'form',
            'hotelProducts'=>["id"=>$hotel['id'],"id_hotel"=>$hotel['id'],"LY"=>"0","RF"=>"1","MK"=>"0","review"=>"0","satisfaction"=>"1","wifi_offers"=>"1","birthday_emails"=>"1","pushtech"=>"0","require_room_num"=>"1","display_require_room"=>"1","wifi_days"=>"15","user_enrichment"=>"1","birthday_alarm"=>"1","loyalty"=>"0"],
            'user'=> ["id"=>$user['id'],"email"=>"garridorosichricardo@gmail.com","name"=>"Ricardo Garrido Rosich","lang"=>"es","gender"=>"male","birthday"=>$today,"locale"=>"es","isNew"=>false,"regularUser"=>false,"stayTimeReconnection"=>false,"source"=>"form","sendex"=>0,"result"=>"risky"]
        ]]);
        $sql = "SELECT id_user, times_login FROM connection_history";
        $answer_connection = lectura($sql);
        $this->assertEquals($user['id'],$answer_connection['id_user'], "The user was not registered properly");
        $this->assertEquals(1,$answer_connection['times_login'], "The times_login was not registered properly");

        $sql = "SELECT user_id, num_visits FROM users_visits where hotel_id = ".$hotel['id'];
        $answer_visits = lectura($sql);
        $this->assertEquals($user['id'],$answer_visits['user_id'], "The user_id was not registered properly");
        $this->assertEquals(1,$answer_visits['num_visits'], "The num_visits was not registered properly");

        $sql = "SELECT * FROM birthday_alarms where user_id = ".$user['id'];
        $answer_birthday_notification = lectura($sql, null,true,2);
        $this->assertEquals($user['id'],$answer_birthday_notification['user_id'], "The validation for send birthday_notifications is failing or the DB was not cleaned properly");

        $sql = "DELETE FROM `birthday_alarms` WHERE `id`= ". $answer_birthday_notification['id'];
        escritura($sql, null,true,2);

        $this->client->request('POST', SECURE_BASE_PATH . LIB . 'webservices/connection-history.php', ['form_params' =>[
            'testing'=> true,
            'user_id'=>$user['id'],
            'hotel_id'=>$hotel['id'],
            'room_id'=>'101',
            'mac'=>'24:24:24:24',
            'source'=>'form',
            'hotelProducts'=>["id"=>$hotel['id'],"id_hotel"=>$hotel['id'],"LY"=>"0","RF"=>"1","MK"=>"0","review"=>"0","satisfaction"=>"1","wifi_offers"=>"1","birthday_emails"=>"1","pushtech"=>"0","require_room_num"=>"1","display_require_room"=>"1","wifi_days"=>"15","user_enrichment"=>"1","birthday_alarm"=>"1","loyalty"=>"0"],
            'user'=> ["id"=>$user['id'],"email"=>"garridorosichricardo@gmail.com","name"=>"Ricardo Garrido Rosich","lang"=>"es","gender"=>"male","birthday"=>$today,"locale"=>"es","isNew"=>false,"regularUser"=>false,"stayTimeReconnection"=>false,"source"=>"form","sendex"=>0,"result"=>"risky"]
        ]]);

        $sql = "SELECT id_user, times_login FROM connection_history";
        $answer_conneciton = lectura($sql);
        $this->assertEquals($user['id'],$answer_conneciton['id_user'], "The user was not registered properly");
        $this->assertEquals(2,$answer_conneciton['times_login'], "The times_login was not registered properly");

        $sql = "SELECT user_id, num_visits FROM users_visits where hotel_id = ".$hotel['id'];
        $answer_visits = lectura($sql);
        $this->assertEquals($user['id'],$answer_visits['user_id'], "The user_id was not registered properly");
        $this->assertEquals(1,$answer_visits['num_visits'], "The num_visits was not registered properly");

        $sql = "SELECT * FROM birthday_alarms where user_id = ".$user['id'];
        $answer_birthday_notification = lectura($sql, null,true,2);
        $this->assertEquals(null,$answer_birthday_notification, "The validation for send birthday_notifications is failing or the DB was not cleaned properly");
    }

    public function testUserDoesntTriggerBirthdayAlarm()
    {
        $sql_user = "SELECT id FROM users";
        $user = lectura($sql_user);
        $sql_hotel = "SELECT id FROM hoteles";
        $hotel = lectura($sql_hotel);
        $today = date("Y-m-d");
        $date = date("Y-m-d",strtotime("+20 day", strtotime($today)));
        $this->client->request('POST', SECURE_BASE_PATH . LIB . 'webservices/connection-history.php', ['form_params' =>[
            'testing'=> true,
            'user_id'=>$user['id'],
            'hotel_id'=>$hotel['id'],
            'room_id'=>'101',
            'mac'=>'24:24:24:24',
            'source'=>'form',
            'hotelProducts'=>["id"=>$hotel['id'],"id_hotel"=>$hotel['id'],"LY"=>"0","RF"=>"1","MK"=>"0","review"=>"0","satisfaction"=>"1","wifi_offers"=>"1","birthday_emails"=>"1","pushtech"=>"0","require_room_num"=>"1","display_require_room"=>"1","wifi_days"=>"15","user_enrichment"=>"1","birthday_alarm"=>"1","loyalty"=>"0"],
            'user'=> ["id"=>$user['id'],"email"=>"garridorosichricardo@gmail.com","name"=>"Ricardo Garrido Rosich","lang"=>"es","gender"=>"male","birthday"=>$date,"locale"=>"es","isNew"=>false,"regularUser"=>false,"stayTimeReconnection"=>false,"source"=>"form","sendex"=>0,"result"=>"risky"]
        ]]);
        $sql = "SELECT id_user, times_login FROM connection_history";
        $answer_conneciton = lectura($sql);
        $this->assertEquals($user['id'],$answer_conneciton['id_user'], "The user was not registered properly");
        $this->assertEquals(1,$answer_conneciton['times_login'], "The times_login was not registered properly");

        $sql = "SELECT user_id, num_visits FROM users_visits where hotel_id = ".$hotel['id'];
        $answer_visits = lectura($sql);
        $this->assertEquals($user['id'],$answer_visits['user_id'], "The user_id was not registered properly");
        $this->assertEquals(1,$answer_visits['num_visits'], "The num_visits was not registered properly");

        $sql = "SELECT * FROM birthday_alarms where user_id = ".$user['id'];
        $answer_birthday_notification = lectura($sql, null,true,2);
        print_r($answer_birthday_notification );
        $this->assertEquals(null,$answer_birthday_notification, "The validation for send birthday_notifications is failing or the DB was not cleaned properly");

        $this->client->request('POST', SECURE_BASE_PATH . LIB . 'webservices/connection-history.php', ['form_params' =>[
            'testing'=> true,
            'user_id'=>$user['id'],
            'hotel_id'=>$hotel['id'],
            'room_id'=>'101',
            'mac'=>'24:24:24:24',
            'source'=>'form',
            'hotelProducts'=>["id"=>$hotel['id'],"id_hotel"=>$hotel['id'],"LY"=>"0","RF"=>"1","MK"=>"0","review"=>"0","satisfaction"=>"1","wifi_offers"=>"1","birthday_emails"=>"1","pushtech"=>"0","require_room_num"=>"1","display_require_room"=>"1","wifi_days"=>"15","user_enrichment"=>"1","birthday_alarm"=>"1","loyalty"=>"0"],
            'user'=> ["id"=>$user['id'],"email"=>"garridorosichricardo@gmail.com","name"=>"Ricardo Garrido Rosich","lang"=>"es","gender"=>"male","birthday"=>$date,"locale"=>"es","isNew"=>false,"regularUser"=>false,"stayTimeReconnection"=>false,"source"=>"form","sendex"=>0,"result"=>"risky"]
        ]]);

        $sql = "SELECT id_user, times_login FROM connection_history";
        $answer_conneciton = lectura($sql);
        $this->assertEquals($user['id'],$answer_conneciton['id_user'], "The user was not registered properly");
        $this->assertEquals(2,$answer_conneciton['times_login'], "The times_login was not registered properly");

        $sql = "SELECT user_id, num_visits FROM users_visits where hotel_id = ".$hotel['id'];
        $answer_visits = lectura($sql);
        $this->assertEquals($user['id'],$answer_visits['user_id'], "The user_id was not registered properly");
        $this->assertEquals(1,$answer_visits['num_visits'], "The num_visits was not registered properly");

        $sql = "SELECT * FROM birthday_alarms where user_id = ".$user['id'];
        $answer_birthday_notification = lectura($sql, null,true,2);
        $this->assertEquals(null,$answer_birthday_notification, "The validation for send birthday_notifications is failing or the DB was not cleaned properly");
    }

    public function testBirthdayAlarmDisabled()
    {
        $sql_user = "SELECT id FROM users";
        $user = lectura($sql_user);
        $sql_hotel = "SELECT id FROM hoteles";
        $hotel = lectura($sql_hotel);
        $today = date("Y-m-d");
        $this->client->request('POST', SECURE_BASE_PATH . LIB . 'webservices/connection-history.php', ['form_params' =>[
            'testing'=> true,
            'user_id'=>$user['id'],
            'hotel_id'=>$hotel['id'],
            'room_id'=>'101',
            'mac'=>'24:24:24:24',
            'source'=>'form',
            'hotelProducts'=>["id"=>$hotel['id'],"id_hotel"=>$hotel['id'],"LY"=>"0","RF"=>"1","MK"=>"0","review"=>"0","satisfaction"=>"1","wifi_offers"=>"1","birthday_emails"=>"1","pushtech"=>"0","require_room_num"=>"1","display_require_room"=>"1","wifi_days"=>"15","user_enrichment"=>"1","birthday_alarm"=>"0","loyalty"=>"0"],
            'user'=> ["id"=>$user['id'],"email"=>"garridorosichricardo@gmail.com","name"=>"Ricardo Garrido Rosich","lang"=>"es","gender"=>"male","birthday"=>$today,"locale"=>"es","isNew"=>false,"regularUser"=>false,"stayTimeReconnection"=>false,"source"=>"form","sendex"=>0,"result"=>"risky"]
        ]]);

        $sql = "SELECT id_user, times_login FROM connection_history";
        $answer_conneciton = lectura($sql);
        $this->assertEquals($user['id'],$answer_conneciton['id_user'], "The user was not registered properly");
        $this->assertEquals(1,$answer_conneciton['times_login'], "The times_login was not registered properly");

        $sql = "SELECT user_id, num_visits FROM users_visits where hotel_id = ".$hotel['id'];
        $answer_visits = lectura($sql);
        $this->assertEquals($user['id'],$answer_visits['user_id'], "The user_id was not registered properly");
        $this->assertEquals(1,$answer_visits['num_visits'], "The num_visits was not registered properly");

        $sql = "SELECT * FROM birthday_alarms where user_id = ".$user['id'];
        $answer_birthday_notification = lectura($sql, null,true,2);
        $this->assertEquals(null,$answer_birthday_notification, "The validation for send birthday_notifications is failing or the DB was not cleaned properly");
    }

//    public function testLoyaltyUserCallWebservice()
//    {
//        $sql_user = "SELECT id FROM users";
//        $user = lectura($sql_user);
//        $sql_hotel = "SELECT id FROM hoteles";
//        $hotel = lectura($sql_hotel);
//        $today = date("Y-m-d");
//        $date = date("Y-m-d",strtotime("+20 day", strtotime($today)));
//        $sql = "UPDATE  `hoteles` SET `loyalty_emails`='kingofthemoondance@hotmail.com', `loyalty_alerts`='1' WHERE `id`= ". $hotel['id'];
//        escritura($sql);
//        $this->client->request('POST', SECURE_BASE_PATH . LIB . 'webservices/connection-history.php', ['form_params' =>[
//            'testing'=> true,
//            'user_id'=>$user['id'],
//            'hotel_id'=>$hotel['id'],
//            'room_id'=>'101',
//            'mac'=>'24:24:24:24',
//            'source'=>'form',
//            'hotelProducts'=>["id"=>$hotel['id'],"id_hotel"=>$hotel['id'],"LY"=>"0","RF"=>"1","MK"=>"0","review"=>"0","satisfaction"=>"1","wifi_offers"=>"1","birthday_emails"=>"1","pushtech"=>"0","require_room_num"=>"1","display_require_room"=>"1","wifi_days"=>"15","user_enrichment"=>"1","birthday_alarm"=>"0","loyalty"=>"1"],
//            'user'=> ["id"=>$user['id'],"email"=>"garridorosichricardo@gmail.com","name"=>"Ricardo Garrido Rosich","lang"=>"es","gender"=>"male","birthday"=>$date,"locale"=>"es","isNew"=>false,"regularUser"=>false,"stayTimeReconnection"=>false,"source"=>"form","sendex"=>0,"result"=>"risky"]
//        ]]);
//        $sql = "SELECT id_user, times_login FROM connection_history";
//        $answer_conneciton = lectura($sql);
//        $this->assertEquals($user['id'],$answer_conneciton['id_user'], "The user was not registered properly");
//        $this->assertEquals(1,$answer_conneciton['times_login'], "The times_login was not registered properly");
//
//        $sql = "SELECT id, user_id, num_visits FROM users_visits where hotel_id = ".$hotel['id'];
//        $answer_visits = lectura($sql);
//        $this->assertEquals($user['id'],$answer_visits['user_id']);
//        $this->assertEquals(1,$answer_visits['num_visits']);
//
//        $sql = "SELECT * FROM regular_customer where user_id = ".$user['id'];
//        $answer_regular_customer = lectura($sql, null,true,2);
//        $this->assertEquals(null,$answer_regular_customer, "The validation for send regular_customer is failing or the DB was not cleaned properly");
//
//        $sql = "UPDATE  `users_visits` SET `last_login`='2011-04-11 08:28:34' WHERE `id`= ". $answer_visits['id'];
//        escritura($sql);
//
//        $this->client->request('POST', SECURE_BASE_PATH . LIB . 'webservices/connection-history.php', ['form_params' =>[
//            'testing'=> true,
//            'user_id'=>$user['id'],
//            'hotel_id'=>$hotel['id'],
//            'room_id'=>'101',
//            'mac'=>'24:24:24:24',
//            'source'=>'form',
//            'hotelProducts'=>["id"=>$hotel['id'],"id_hotel"=>$hotel['id'],"LY"=>"0","RF"=>"1","MK"=>"0","review"=>"0","satisfaction"=>"1","wifi_offers"=>"1","birthday_emails"=>"1","pushtech"=>"0","require_room_num"=>"1","display_require_room"=>"1","wifi_days"=>"15","user_enrichment"=>"1","birthday_alarm"=>"0","loyalty"=>"1"],
//            'user'=> ["id"=>$user['id'],"email"=>"garridorosichricardo@gmail.com","name"=>"Ricardo Garrido Rosich","lang"=>"es","gender"=>"male","birthday"=>$date,"locale"=>"es","isNew"=>false,"regularUser"=>false,"stayTimeReconnection"=>false,"source"=>"form","sendex"=>0,"result"=>"risky"]
//        ]]);
//
//        $sql = "SELECT id_user, times_login FROM connection_history";
//        $answer_conneciton = lectura($sql);
//        $this->assertEquals($user['id'],$answer_conneciton['id_user'], "The user was not registered properly");
//        $this->assertEquals(2,$answer_conneciton['times_login'], "The times_login was not registered properly");
//
//        $sql = "SELECT user_id, num_visits FROM users_visits where hotel_id = ".$hotel['id'];
//        $answer_visits = lectura($sql);
//        $this->assertEquals($user['id'],$answer_visits['user_id']);
//        $this->assertEquals(2,$answer_visits['num_visits']);
//
//        $sql = "SELECT * FROM regular_customer where user_id = ".$user['id'];
//        $answer_regular_customer = lectura($sql, null,true,2);
//        $this->assertEquals($user['id'],$answer_regular_customer['user_id'], "The validation for send regular_customer is failing or the DB was not cleaned properly");
//
//        $sql = "UPDATE  `hoteles` SET `loyalty_emails`='', `loyalty_alerts`='0' WHERE `id`= ". $hotel['id'];
//        escritura($sql);
//    }
}
