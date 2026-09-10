<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 16/04/2018
 * Time: 10:54
 */

require(__DIR__.'/../lib/composer/vendor/autoload.php');
require_once __DIR__.'/../app/configTest.php';
require_once(__DIR__.'/../models/_indexModel.php');
require_once(__DIR__.'/../lib/cache.php');

class MainHotelinkingTestConfiguration extends PHPUnit_Framework_TestCase
{
    protected $client;

    protected function setUp()
    {
        $this->client = new GuzzleHttp\Client();

        $this->tearDown();
    }
}
