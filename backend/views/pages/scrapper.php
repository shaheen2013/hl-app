<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php
//tripadvisor
//$page = file_get_contents('http://www.tripadvisor.es/Hotel_Review-g187497-d190616-Reviews-Majestic_Hotel_Spa_Barcelona-Barcelona_Catalonia.html');
$hotelPage = new DOMDocument();
libxml_use_internal_errors(TRUE);
if(!empty($page)){
	$hotelPage->loadHTML($page);
	libxml_clear_errors();
	$xpath = new DOMXPath($hotelPage);
	$hotelName = $xpath->query(".//*[@id='HEADING']/text()[2]");
	$rating = $xpath->query(".//*[@id='HR_HACKATHON_CONTENT']/div[2]/div[3]/div[3]/div/span/img/@content");
	if($hotelName->length > 0){
		foreach($hotelName as $row){
			echo ($row->data);
		}
		foreach($rating as $ratingrow){
			echo($ratingrow->value);
		}
	}
}
?>