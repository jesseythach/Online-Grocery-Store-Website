<?php
session_start();
require "./UserHandlingAPI.php";
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

if (isset($_POST['uid']) && isset($_POST['email']) && isset($_POST['username'])) {

    $message = null;
    if (isset($_POST['password'])) {
        $message = UserHandlingAPI::editUser($_POST['uid'], $_POST['email'], $_POST['username'], $_POST['password']);
    } else {
        $message = UserHandlingAPI::editUser($_POST['uid'], $_POST['email'], $_POST['username'], null);


    }


    if ($message['success']) {
        header('location:userlist.php');
    } else {
        echo $message['message'] . "</br>Please go back and try again!";
    }

} else if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['username'])) {
    $userCreateResponse = UserHandlingAPI::createUser($_POST['username'], $_POST['email'], $_POST['password']);
    if ($userCreateResponse['success']) {
        header("location:userlist.php");
    } else {
        unset($userCreateResponse['success']);
        foreach ($userCreateResponse as $key=>$message){
            echo $message['message']."<br>";
        }
        echo "</br>Please press the back button and try again!";

    }
}
