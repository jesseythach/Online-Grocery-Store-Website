<?php
include "backstore/UserHandlingAPI.php";
session_start();

header("content-type: application/json");
$sessionLoginHandler = &$_SERVER["login"];
if (isset($sessionLoginHandler)) {
    $sessionLoginHandler->checkToken();
} else {
    $_SESSION['login'] = new UserHandlingAPI();
}


?>

{
"home": {
"name": "Home",
"linkUrl": "/index.php"
},
"aisles": {
"name": "Aisles",
"linkUrl": "/index.php#aisles"
},
"myCart": {
"name": "My Cart",
"linkUrl": "/cart.php"
},
<?php
if ($_SESSION['login']->checkToken(true)) {

?>
    "backstore": {
    "name": "Backstore",
    "linkUrl": "/backstore/productlist.php"
    },

<?php
}

if (!$_SESSION['login']->checkToken()) {


?>
    "login": {
    "name": "Login",
    "linkUrl": "/login.php"
    }
<?php
} else {
?>


    "login": {
    "name": "Logout",
    "linkUrl": "/logout.php"
    }

<?php
}
?>
}