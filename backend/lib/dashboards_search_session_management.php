<?php
// Init session variables for SEARCH
$_SESSION['chainSearch'] = array_get($_SESSION, 'chainSearch', false);
$_SESSION['rangeStart'] = array_get($_SESSION, 'rangeStart', false);
$_SESSION['rangeEnd'] = array_get($_SESSION, 'rangeEnd', false);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['chainSearch'] = array_get($_POST, 'chainSearch', false);
    $_SESSION['rangeStart'] = array_get($_POST, 'rangeStart', array_get($_SESSION, 'rangeStart', false));
    $_SESSION['rangeEnd'] = array_get($_POST, 'rangeEnd', array_get($_SESSION, 'rangeEnd', false));
}
