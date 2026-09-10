<?php


/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 09/04/2018
 * Time: 15:42
 */

require_once 'MainHotelinkingTestConfiguration.php';
class SatisfactionSurveyThanksTest extends MainHotelinkingTestConfiguration
{

    protected function callToController($comment, $rating, $user_satisfaction){
        $sql_user_guid = "SELECT * FROM user_guid where id_usuario = " . $user_satisfaction['id_usuario'];
        $user_guid = lectura($sql_user_guid );
        $user_guid = str_replace('-','',$user_guid['guid']);
        $sql_hotel_guid = "SELECT * FROM hotel_guid where id_hotel = " . $user_satisfaction['id_hotel'];
        $hotel_guid = lectura($sql_hotel_guid );
        $hotel_guid = str_replace('-','',$hotel_guid['guid']);

        $this->client->request('POST', SECURE_BASE_PATH . 'satisfaction-survey/?tk=' . $user_guid . $hotel_guid . '-' .
            $user_satisfaction['id'],
            ['form_params' =>[
                'testing' => true,
                'comments' => $comment,
                'rating' => $rating,
                'sid' => $user_satisfaction['id'],
            ]]);
    }
    protected function tearDown(){
        $sql = "UPDATE  `user_satisfaction` SET `done`='0', `review_send`='0'";
        escritura($sql);

        $sql1 = "TRUNCATE TABLE `reviews`";
        escritura($sql1, null,true,2);

        $sql2 = "TRUNCATE TABLE `satisfaction_warnings`";
        escritura($sql2, null,true,2);

        $sql3 = "TRUNCATE TABLE `satisfaction_thanks`";
        escritura($sql3, null,true,2);

        $sql4 = "UPDATE  `hotel_satisfaction` SET `puntMin`='7', `ignoreRating`='0', `sendThanksMail`='1'";
        escritura($sql4);
    }

    public function testUserTriggerSatisfactionSurveyTHanks()
    {
        $comment = 'TESTTEST';
        $rating = 8;
        $sql_user_satisfaction = "SELECT * FROM user_satisfaction";
        $user_satisfaction = lectura($sql_user_satisfaction);
        $this->callToController($comment, $rating, $user_satisfaction);
        $sql_user_satisfaction = "SELECT * FROM user_satisfaction where id = " . $user_satisfaction['id'];
        $user_satisfaction = lectura($sql_user_satisfaction);
        $this->assertEquals($rating,$user_satisfaction['puntuacion'], "The user_satisfaction rating was not registered properly");
        $this->assertEquals($comment,$user_satisfaction['comentario'], "The user_satisfaction comment was not registered properly");
        $this->assertEquals('1',$user_satisfaction['done'], "The user_satisfaction was not registered properly");
    }

    public function testUserMailsSatisfactionSurveyTHanks()
    {
        $comment = 'TESTTEST2';
        $rating = 10;
        $sql_user_satisfaction = "SELECT * FROM user_satisfaction";
        $user_satisfaction = lectura($sql_user_satisfaction);
        $this->callToController($comment, $rating, $user_satisfaction);
        $sql_user_satisfaction = "SELECT * FROM user_satisfaction where id = " . $user_satisfaction['id'];
        $user_satisfaction = lectura($sql_user_satisfaction);
        $this->assertEquals($rating,$user_satisfaction['puntuacion'], "The user_satisfaction rating was not registered properly");
        $this->assertEquals($comment,$user_satisfaction['comentario'], "The user_satisfaction comment was not registered properly");
        $this->assertEquals('1',$user_satisfaction['done'], "The user_satisfaction was not registered properly");
        $this->assertEquals('1',$user_satisfaction['review_send'], "The review was not registered properly");

        $sql_mail_satisfaction_thanks = "SELECT * FROM satisfaction_thanks WHERE hotel_id = ". $user_satisfaction['id_hotel'] ." AND user_id =". $user_satisfaction['id_usuario'];
        $mail_satisfaction_thanks = lectura($sql_mail_satisfaction_thanks, '', true, 2);
        $this->assertNotEquals(null, $mail_satisfaction_thanks, "The satisfaction_thanks was not registered properly");

        $sql_mail_reviews = "SELECT * FROM reviews WHERE hotel_id = ". $user_satisfaction['id_hotel'] ." AND user_id =". $user_satisfaction['id_usuario'];
        $mail_reviews = lectura($sql_mail_reviews, '', true, 2);
        $this->assertNotEquals(null, $mail_reviews, "The review was not registered properly");
    }

    public function testUserMailsSatisfactionSurveyThanksAndWarning()
    {
        $comment = 'TESTTEST2';
        $rating = 5;
        $sql_user_satisfaction = "SELECT * FROM user_satisfaction";
        $user_satisfaction = lectura($sql_user_satisfaction);
        $this->callToController($comment, $rating, $user_satisfaction);
        $sql_user_satisfaction = "SELECT * FROM user_satisfaction where id = " . $user_satisfaction['id'];
        $user_satisfaction = lectura($sql_user_satisfaction);
        $this->assertEquals($rating,$user_satisfaction['puntuacion'], "The user_satisfaction rating was not registered properly");
        $this->assertEquals($comment,$user_satisfaction['comentario'], "The user_satisfaction comment was not registered properly");
        $this->assertEquals('1',$user_satisfaction['done'], "The user_satisfaction was not registered properly");
        $this->assertEquals('0',$user_satisfaction['review_send'], "The review was not registered properly");

        $sql_mail_satisfaction_thanks = "SELECT * FROM satisfaction_thanks WHERE hotel_id = ". $user_satisfaction['id_hotel'] ." AND user_id =". $user_satisfaction['id_usuario'];
        $mail_satisfaction_thanks = lectura($sql_mail_satisfaction_thanks, '', true, 2);
        $this->assertNotEquals(null, $mail_satisfaction_thanks, "The satisfaction_thanks was not registered properly");

        $sql_mail_reviews = "SELECT * FROM reviews WHERE hotel_id = ". $user_satisfaction['id_hotel'] ." AND user_id = " . $user_satisfaction['id_usuario'];
        $mail_reviews = lectura($sql_mail_reviews, '', true, 2);
        $this->assertEquals(null, $mail_reviews, "The review was not registered properly");

        $sql_mail_warning = "SELECT * FROM satisfaction_warnings WHERE hotel_id = ". $user_satisfaction['id_hotel'];
        $mail_warning = lectura($sql_mail_warning, '', true, 2);
        $this->assertNotEquals(null, $mail_warning, "The warning was not registered properly");
    }

    public function testUserMailsReviewRegardlessRatingAndThanksDisabled()
    {
        $sql = "UPDATE  `hotel_satisfaction` SET `ignoreRating`='1', `sendThanksMail`='0'";
        escritura($sql);

        $comment = 'TESTTEST2';
        $rating = 5;
        $sql_user_satisfaction = "SELECT * FROM user_satisfaction";
        $user_satisfaction = lectura($sql_user_satisfaction);
        $this->callToController($comment, $rating, $user_satisfaction);
        $sql_user_satisfaction = "SELECT * FROM user_satisfaction where id = " . $user_satisfaction['id'];
        $user_satisfaction = lectura($sql_user_satisfaction);
        $this->assertEquals($rating,$user_satisfaction['puntuacion'], "The user_satisfaction rating was not registered properly");
        $this->assertEquals($comment,$user_satisfaction['comentario'], "The user_satisfaction comment was not registered properly");
        $this->assertEquals('1',$user_satisfaction['done'], "The user_satisfaction was not registered properly");
        //ignoreRating sends the review in the stay-share process, so it will not be affected in this test
        $this->assertEquals('0',$user_satisfaction['review_send'], "The review was not registered properly");

        $sql_mail_satisfaction_thanks = "SELECT * FROM satisfaction_thanks WHERE hotel_id = ". $user_satisfaction['id_hotel'] ." AND user_id =". $user_satisfaction['id_usuario'];
        $mail_satisfaction_thanks = lectura($sql_mail_satisfaction_thanks, '', true, 2);
        $this->assertEquals(null, $mail_satisfaction_thanks, "The satisfaction_thanks was not registered properly");

        $sql_mail_reviews = "SELECT * FROM reviews WHERE hotel_id = ". $user_satisfaction['id_hotel'] ." AND user_id =". $user_satisfaction['id_usuario'];
        $mail_reviews = lectura($sql_mail_reviews, '', true, 2);
        $this->assertEquals(null, $mail_reviews, "The review was not registered properly");

        $sql_mail_warning = "SELECT * FROM satisfaction_warnings WHERE hotel_id = ". $user_satisfaction['id_hotel'];
        $mail_warning = lectura($sql_mail_warning, '', true, 2);
        $this->assertNotEquals(null, $mail_warning, "The warning was not registered properly");
    }
}