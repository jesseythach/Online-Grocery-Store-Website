<?php
require "./UserHandlingAPI.php";
session_start();
UserHandlingAPI::authAdmin();
$userList = UserHandlingAPI::getUserList();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <script src='../backstore_menu.js'></script>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<link rel="stylesheet" href="../navbar.css"/>
    <link rel="stylesheet" href="backtemplate.css"/>-->
    <link rel="stylesheet" type="text/css" href="../css/main.minified.css" />
    <script src="../js/scroll-hiding.js"></script>
    <script src="../js/mobile-menu.js"></script>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">

    <title>Backstore - User Management</title>
</head>

<body id="backstore">
    <header id="head">
    </header>
    <div class="content">
        <div class="section">

            <a class="editor-button fake-button" href="edituser.php">Add user</a>

            <h2>User Management</h2>

            <div class="vertical-list">
                <?php
                foreach ($userList as $index => $userInfo) {

                ?>
                    <div class="backstore-user">
                        <p> <?= $userInfo['admin'] ? "Yes" : "No"; ?></p>
                        <div class="separator"></div>
                        <p><?= $userInfo['email'] ?></p>
                        <div class="separator"></div>
                        <div class="vertical-list">
                            <p class="little-margin"> <?= $userInfo['username'] ?></p>
                            <p class="italic little-margin"> <?= $userInfo['uid'] ?></p>
                        </div>
                        <div class="separator"></div>
                        <div class="vertical-list">
                            <a class="fake-button" href="edituser.php?id=<?= $userInfo['uid'] ?>">Edit</a>
                            <a class="fake-button" href="removeuser.php?id=<?= $userInfo['uid'] ?>">Remove</a>
                        </div>

                    </div>
                <?php

                }
                ?>


            </div>
        </div>
    </div>
    <footer id="foot">
        <a href="../about-us.php">About us</a>
        <a href="../legal-notice.php">Legal notice</a>
    </footer>
</body>

</html>