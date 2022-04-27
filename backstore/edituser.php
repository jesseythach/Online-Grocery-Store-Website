<?php
require "./UserHandlingAPI.php";
session_start();
$sessionLoginHandler = null;
if (isset($_SERVER["login"])) {
    $sessionLoginHandler = &$_SESSION['login'];
    $sessionLoginHandler->checkToken();
} else {
    $_SESSION['login'] = new UserHandlingAPI();
    $sessionLoginHandler = &$_SESSION['login'];
}
if (!$_SESSION['login']->checkToken(true)) {
    echo ("<pre>401 UNAUTHORIZED</pre>");
    http_response_code(401);
    exit();
}

$userList = UserHandlingAPI::getUserList();

$emailName = "";
$usernameName = "";
$passwordName = "";
$title = "User creator";
$currentUser = [];
$isValid = false;
if (isset($_GET["id"])) {
    foreach ($userList as $user => $userInfo) {
        if (trim($userInfo['uid']) === trim($_GET['id'])) {
            $isValid = true;
            $emailName = $userInfo['email'];
            $usernameName = $userInfo['username'];
            $currentUser = $userInfo;
            $title = "User editor";
            break;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">


<head>
    <script src='../backstore_menu.js'></script>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<link rel="stylesheet" href="backtemplate.css">
    <link rel="stylesheet" type="text/css" href="../navbar.css"/>-->
    <link rel="stylesheet" type="text/css" href="../css/main.minified.css" />
    <script src="../js/scroll-hiding.js"></script>
    <script src="../js/mobile-menu.js"></script>
    <title><?= $title ?></title>
    <?php
    if (isset($_GET["id"])) {
    ?>
        <script>
            let passwordForm = "<div class=\"field\" id=\"togglePass\"> <label for=\"new-password\"><b>New password:</b></label><input type=\"password\" name=\"password\" class=\"fontsize1\" id=\"new-password\"/></div>"

            function togglePassword(value, parent) {

                if (value.checked) {
                    parent.insertAdjacentHTML('afterend', passwordForm);
                } else {
                    document.getElementById("togglePass").remove();
                }

            }
        </script>
    <?php
    }
    ?>
</head>

<body id="editor">
    <header id="head">
    </header>
    <div class="content">
        <div class="section">

            <h2><?= $title ?></h2>

            <form id="editorform" method="post" action="adduser.php">
                <div class="vertical-list">
                    <div class="field">

                        <label for="email"><b>E-mail:</b></label>
                        <input type="text" name="email" value="<?= $emailName ?>" class="fontsize1" id="email" />
                    </div>

                    <div class="field">
                        <label for="username"><b>username:</b></label>
                        <input type="text" name="username" value="<?= $usernameName ?>" class="fontsize1" id="username" />

                    </div>
                    <?php
                    if (isset($_GET["id"])) {
                    ?>
                        <div class="field">
                            <label for="toggle-password"><b>Toggle password:</b></label>

                            <input type="checkbox" name="password" class="fontsize1" onclick="togglePassword(this,this.parentNode)" id="toggle-password" />
                        </div>


                    <?php
                    } else echo "<div id=\"togglePass\"<label for=\"new-password\"><b>New password:</b></label></td> <td class=\"product-edit-cell\"><input type=\"password\" name=\"password\" class=\"fontsize1\" id=\"new-password\"/></div>";
                    ?>

                    <?php
                    if (isset($_GET["id"]) && $isValid) {

                    ?>
                        <input type="text" name="uid" value="<?= $currentUser['uid'] ?>" style="display:none">
                    <?php
                    }
                    ?>
                    <button class="save hoverButton">Save</button>
                </div>
            </form>
        </div>
    </div>
    <footer id="foot">
        <a href="../about-us.php">About us</a>
        <a href="../legal-notice.php">Legal notice</a>
    </footer>
</body>

</html>