<?php
require '../utilities.php';
require "./UserHandlingAPI.php";
session_start();
$sessionLoginHandler = null;
if (isset($_SERVER["login"])) {
    $sessionLoginHandler =& $_SESSION['login'];
    $sessionLoginHandler->checkToken();

} else {
    $_SESSION['login'] = new UserHandlingAPI();
    $sessionLoginHandler =& $_SESSION['login'];
}
if (!$_SESSION['login']->checkToken(true)) {
    echo("<pre>401 UNAUTHORIZED</pre>");
    http_response_code(401);
    exit();
}

$hasDeleted = Utilities::removeOrder((int)$_GET['id'], '..');

if (!$hasDeleted) {
    exit("404 Can't remove, order doesn't exist.");
}

header("location:orderlist.php");

