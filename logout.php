<?php
session_start();
if (isset($_COOKIE['email'])) {
    unset($_COOKIE['email']);
    setcookie('email', "", -1);
}

if (isset($_COOKIE['password'])) {
    unset($_COOKIE['password']);
    setcookie('password', "", -1);
}


unset($_SESSION['login']);
unset($_SESSION['user']);

header('location:' . $_SERVER['HTTP_REFERER'] ?? "index.php");
