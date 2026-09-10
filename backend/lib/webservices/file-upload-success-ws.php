<?php

define("INDEXCONTROLVAL", "1");
include 'librerias.php';// Librerias básicas
require_once __DIR__ . '/../../src/Services/Connections/ApiGatewayConnection.php';

if($_POST && (array_has($_POST, 'hotel_id') || array_has($_POST, 'chain_id') || array_has($_POST, 'brand_id'))){
  header('Content-Type: application/json');
  $brand_id = array_get($_SESSION, 'loggedBrandID');
  $hotel_id = array_get($_POST, 'hotel_id');
  $chain_id = array_get($_POST, 'chain_id');
  $img_url = "https://" . array_get($_POST, 'bucket') . '/' . array_get($_POST, 'key');
  $file_type = array_get($_POST, 'file_type');

  // Cloudfront cache invalidation for images storaged in s3 bucket
  $offer_id = array_get($_POST, 'offer_id');
  $brand_guid = array_get($_SESSION,'guid_logueado');

  $path = $file_type == "offer" ? "offers/{$offer_id}/images/*" : "brands/{$brand_guid}/images/{$file_type}/*";
  invalidateCloudfrontCache($path);

  $log->info('updating image in db', ['hotel_id' => $hotel_id, 'type' => $file_type, 'url' => $img_url]);

  if($file_type === 'background') {
    return updateHotelBackgroundImage($hotel_id, $brand_id, $img_url);
  } else if ($file_type === 'logo') {
    return updateHotelLogo($hotel_id, $brand_id, $img_url);
  } else if ($file_type === 'chainLogo') {
    return updateChainLogo($chain_id, $img_url);
  } else if ($file_type === 'offer'){
    $offer_id = array_get($_POST, 'offer_id');
    return updateOfferImg($offer_id, $img_url);
  }

  exit();
}

use Aws\CloudFront\CloudFrontClient;

function invalidateCloudfrontCache($invalidationPath)
{
  $config = AWS;

  $client = CloudFrontClient::factory(array(
      'region' => $config['region'],
      'version' => 'latest',
      'credentials' => [
        'key' => $config['credentials']['key'],
        'secret' => $config['credentials']['secret']
      ]
  ));

  $date = time();
  $dId = IMAGES_CLOUDFRONT_DISTRIBUTION_ID;

  $payload = [
      "DistributionId" => $dId,
      "InvalidationBatch" => [
        "CallerReference" => $date,
        "Paths" => [
          "Items" => [
            "/{$invalidationPath}"
          ],
          "Quantity" => 1,
        ],
      ],
    ];

  $client->createInvalidation($payload);
}


function updateHotelBackgroundImage($hotel_id, $brand_id, $url) {
  $payload = [
		'brand' => [
			'id' => $brand_id,
			'hotel_id' => $hotel_id,
			'fotoBg' => $url
		]
	];

  global $log;

  $endPoint = HOTELINKING_ENDPOINT . "brands/{$payload['brand']['id']}/info";
  try {
      $gateway = new ApiGatewayConnection();
      $gateway->sendRequest($payload, $endPoint, 'PUT');
      $log->debug('Updating hotel background image', $payload);
      //Delete from cache
      deleteCacheByTag('hotel_profile_' . $payload['brand']['hotel_id']);
  }catch (Exception $e) {
      $log->error('Error updating hotel background image', ['message' => $e->getMessage(), 'error'=> $e]);
      return [];
  }
}

function updateHotelLogo($hotel_id, $brand_id, $url) {
  $url = str_replace("original", "small", $url); // Save the small version of the logo
  $_SESSION['logoHotel'] = $url;

  $payload = [
		'brand' => [
			'id' => $brand_id,
			'hotel_id' => $hotel_id,
			'logo' => $url
		]
	];

  global $log;

  $endPoint = HOTELINKING_ENDPOINT . "brands/{$payload['brand']['id']}/info";
  try {
      $gateway = new ApiGatewayConnection();
      $gateway->sendRequest($payload, $endPoint, 'PUT');
      $log->debug('Updating hotel logo', $payload);
      //Delete from cache
      deleteCacheByTag('hotel_profile_' . $payload['brand']['hotel_id']);
  }catch (Exception $e) {
      $log->error('Error updating hotel logo', ['message' => $e->getMessage(), 'error'=> $e]);
      return [];
  }
}

function updateChainLogo($chainId, $url)
{
  $url = str_replace("original", "small", $url); // Save the small version of the logo
  $sql = "UPDATE cadena SET logo='$url' WHERE id='$chainId' ";
  try{
    escritura($sql);
  } catch (Exception $e){
    global $log;
    $log->error("Error updating chain logo", ["error" => $e, "chain_id" => $chainId]);
  }
}

function updateOfferImg($offer_id, $img_url){
  $sql = "UPDATE hotel_oferta SET img='$img_url' WHERE id='$offer_id'";
  try{
    escritura($sql);
  } catch( Exception $e){

  }
}

?>
