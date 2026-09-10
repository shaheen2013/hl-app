<?php
$urlTmp = $_SERVER["REQUEST_URI"];
$urlTmp = str_replace(BASE_PATH, '', $urlTmp);
$urlTmp = filter_var($urlTmp, FILTER_SANITIZE_URL);
$urlTmp = explode('/', $urlTmp);
$urlTmp = array_filter($urlTmp); // limpia los elementos vacias

$url['dir1'] = $urlTmp ? strtolower(array_shift($urlTmp)) : null; // saco el primer elemento
$url['dir2'] = $urlTmp ? strtolower(array_shift($urlTmp)) : null; // saco el que sigue
$url['dir3'] = $urlTmp ? strtolower(array_shift($urlTmp)) : null; // saco el que sigue
$url['args'] = $urlTmp; // lo demas son argumentos*/
if (empty($url['dir1'])) {
    $url['dir1'] = DEFAULT_DIR1;
} else if (0 === strpos($url['dir1'], '?')) {
    $url['dir1'] = DEFAULT_DIR1;
}
if (empty($url['dir2'])) {
    $url['dir2'] = DEFAULT_DIR2;
}
if (empty($url['dir3'])) {
    $url['dir3'] = DEFAULT_DIR3;
}
if (empty($url['args'])) {
    $url['args'] = array();
}
unset($urlTmp);
