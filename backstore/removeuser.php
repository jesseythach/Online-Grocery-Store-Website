<?php
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

if($_SESSION['login']->checkUserExistence($_GET["id"])){

$_SESSION['login']->deleteUser($_GET["id"]);

header("location:userlist.php");

}else{
    echo "USER NOT FOUND";
}
