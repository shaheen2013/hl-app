<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 17/04/2018
 * Time: 16:43
 */


class clientsWsTest extends MainHotelinkingTestConfiguration
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

    /*In this test we will create two users by sending two request to connection-history-ws, once they are completed
    we will proceed to evaluate the users-ws*/
    public function testUserWs()
    {
        $sql_user = "SELECT id FROM users";
        $users = lecturaArray($sql_user);
        $sql_hotel = "SELECT id FROM hoteles";
        $hotel = lectura($sql_hotel);
        $today = date("Y-m-d");
        $this->client->request('POST', SECURE_BASE_PATH . LIB . 'webservices/connection-history.php', ['form_params' =>[
            'testing'=> true,
            'user_id'=>$users[0]['id'],
            'hotel_id'=>$hotel['id'],
            'room_id'=>'101',
            'mac'=>'24:24:24:24',
            'source'=>'form',
            'hotelProducts'=>["id"=>$hotel['id'],"id_hotel"=>$hotel['id'],"LY"=>"0","RF"=>"1","MK"=>"0","review"=>"0","satisfaction"=>"1","wifi_offers"=>"1","birthday_emails"=>"1","pushtech"=>"0","require_room_num"=>"1","display_require_room"=>"1","wifi_days"=>"15","user_enrichment"=>"1","birthday_alarm"=>"1","loyalty"=>"0"],
            'user'=> ["id"=>$users[0]['id'],"email"=>"garridorosichricardo@gmail.com","name"=>"Ricardo Garrido Rosich","lang"=>"es","gender"=>"male","birthday"=>$today,"locale"=>"es","isNew"=>false,"regularUser"=>false,"stayTimeReconnection"=>false,"source"=>"form","sendex"=>0,"result"=>"risky"]
        ]]);

        $this->client->request('POST', SECURE_BASE_PATH . LIB . 'webservices/connection-history.php', ['form_params' =>[
            'testing'=> true,
            'user_id'=>$users[1]['id'],
            'hotel_id'=>$hotel['id'],
            'room_id'=>'101',
            'mac'=>'24:24:24:24',
            'source'=>'form',
            'hotelProducts'=>["id"=>$hotel['id'],"id_hotel"=>$hotel['id'],"LY"=>"0","RF"=>"1","MK"=>"0","review"=>"0","satisfaction"=>"1","wifi_offers"=>"1","birthday_emails"=>"1","pushtech"=>"0","require_room_num"=>"1","display_require_room"=>"1","wifi_days"=>"15","user_enrichment"=>"1","birthday_alarm"=>"1","loyalty"=>"0"],
            'user'=> ["id"=>$users[1]['id'],"email"=>"garridorosichricardo@gmail.com","name"=>"Ricardo Garrido Rosich","lang"=>"es","gender"=>"male","birthday"=>$today,"locale"=>"es","isNew"=>false,"regularUser"=>false,"stayTimeReconnection"=>false,"source"=>"form","sendex"=>0,"result"=>"risky"]
        ]]);

        $sql = "UPDATE  `connection_history` SET `last_login`='2017-08-31 15:50:30' WHERE `id_user`= ". $users[0]['id'];
        escritura($sql);

        $answer = $this->client->request('POST', SECURE_BASE_PATH . LIB . 'webservices/clients-ws.php', ['form_params' =>[
            'testing'=> true,
            'id_hotel'=>$hotel['id'],
        ]]);
        $ansStr = $answer->getBody()->getContents();
        $answer = (array) json_decode ($ansStr, true);

        $this->assertEquals($users[0]['id'],$answer['data'][1]['user_id'], "The user_id was not registered properly or the order of the list is no't right");
        $this->assertEquals($users[1]['id'],$answer['data'][0]['user_id'], "The user_id was not registered properly or the order of the list is no't right");
    }
}
