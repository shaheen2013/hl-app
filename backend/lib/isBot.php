<?php
/////DETECT FACEBOOK IN APP WEB BROWSER AND REDIRECT/////
use UAParser\Parser;

$ua = $_SERVER['HTTP_USER_AGENT'];
//if User agent is one bot, exit;
switch ($ua){
    case'crawl':
    case'DuckDuckBot':
    case'slurp':
    case'spider':
    case'archiv':
    case'spinn':
    case'sniff':
    case'seo':
    case'audit':
    case'survey':
    case'pingdom':
    case'worm':
    case'capture':
    case'analyz':
    case'index':
    case'thumb':
    case'check':
    case'facebook':
    case'facebot':
    case'YandexBot':
    case'Twitterbot':
    case'a_archiver':
    case'facebookexternalhit':
    case'Bingbot':
    case'Googlebot':
    case'Baiduspider':
    case'semalt':
        exit();
}

$parser = Parser::create();
$result = $parser->parse($ua);

//Miramos si es un bot
$isBot = $result->device->family;
if($isBot == 'Spider')
	exit();

$isFacebook =  $result->ua->family;     //Facebook
/////DETECT FACEBOOK IN APP WEB BROWSER AND REDIRECT/////
?>