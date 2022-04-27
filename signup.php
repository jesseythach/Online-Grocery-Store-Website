<?php
include "backstore/UserHandlingAPI.php";
session_start();
$emailError = "";
$usernameError = "";
$currentEmail = "";
$currentPassword = "";
$currentUsername = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $currentEmail = $_POST['email'];
    $currentPassword = $_POST['password'];
    $currentUsername = $_POST['username'];
    if (!isset($currentEmail)) {
        $emailError = "Is empty";
    } else {

        $currentEmail = trim($currentEmail);

        $userCreateResponse = UserHandlingAPI::createUser($currentUsername, $currentEmail, $currentPassword);


        if (!$userCreateResponse['success']) {
            unset($userCreateResponse['success']);

            foreach ($userCreateResponse as $counter => $value) {
                if ($value['scope'] === 'username') {
                    $usernameError = $value['message'];
                }
                if ($value['scope'] === 'email') {
                    $emailError = $value['message'];
                }
            }
        } else {
            header("location: login.php");
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<link rel="stylesheet" type="text/css" href="template.css"/>
    <link rel="stylesheet" type="text/css" href="navbar.css"/>-->
    <link rel="stylesheet" type="text/css" href="css/main.minified.css" />
    <script src="./menu.js"></script>
    <script src="./js/scroll-hiding.js"></script>
    <script src="js/mobile-menu.js"></script>
    <title>Sign Up page</title>


</head>

<body id="editor">
    <header id="head">
    </header>

    <div class="content">
        <div class="section">

            <h2>Sign Up</h2>

            <p class="error"><?= $usernameError ?></p>
            <p class="error"><?= $emailError ?></p>

            <form id="editorform" method="post">

                <div class="vertical-list">
                    <div class="field">
                        <label for="username"><b>Username:</b></label>
                        <input type="text" name="username" value="<?= $currentUsername ?>" id="username" />
                    </div>

                    <div class="field">
                        <label for="email"><b>E-mail:</b></label>
                        <input type="text" name="email" value="<?= $currentEmail ?>" class="fontsize1" id="email" />
                    </div>
                    <div class="field">
                        <label for="new-password"><b>Create Password:</b></label></td>
                        <input type="password" name="password" class="fontsize1" id="new-password" />
                    </div>

                    <div>
                        <button class="actionbutton">Sign Up</button>
                        <a class="actionbutton fake-button" href="login.php">Already have an account? Login!</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <footer id="foot">
        <a href="about-us.php">About us</a>
        <a href="legal-notice.php">Legal notice</a>
    </footer>
</body>

</html>