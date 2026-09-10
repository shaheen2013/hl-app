<?php
header('Location: /' . $urlTree['404']);
exit;
$info = getEprivacyInfo($url['dir2']);
$userVisits = getUserVisits($info['user_id'], $info['hotel_id']);

