<?php
require "../utilities.php";
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

if (isset($_GET["nonValid"])) echo ('<script>alert("Product already exists! Please change the name.")</script>');

if (isset($_GET["id"])) {
    $item = Utilities::findItem((int)$_GET["id"], '..');
    $isNew = $item == null;
} else $isNew = true;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script src='../backstore_menu.js'></script>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="title">Product Editor</title>
    <!--<link rel="stylesheet" type="text/css" href="backtemplate.css" />
    <link rel="stylesheet" type="text/css" href="../navbar.css" />-->
    <link rel="stylesheet" type="text/css" href="../css/main.minified.css" />
    <script src="../js/scroll-hiding.js"></script>
    <script src="../js/mobile-menu.js"></script>
</head>

<body id="editor">

    <header id="head">

    </header>
    <div class="content">

        <div class="section">
            <h2>Product editor</h2>
            <form id="editorform" method="post" enctype="multipart/form-data" action="addproduct.php">
                <div class="vertical-list">
                    <div class="field">
                        <label for="image"><b>Image:</br><sub>Please upload a square image</sub></b></label>
                        <div>
                            <img id="image" width="100" src="..<?= (!$isNew) ? $item->Image_Link : "/Assets/upload.png" ?>" />
                            <input type="file" accept=".png" name="fileToUpload" id="fileToUpload" <?= ($isNew) ? 'required' : "" ?>>
                        </div>
                    </div>

                    <hr>

                    <div class="field">
                        <label for="name"><b>Name:</b></label>
                        <input type="text" name="name" value="<?= (!$isNew) ? str_replace("_", " ", $item->Name) : "" ?>" class="fontsize1" id="name" required />
                    </div>

                    <div class="field">
                        <label for="price"><b>Price:</b></label>
                        <input type="number" name="price" min="0" step="0.01" class="fontsize1" id="price" value="<?= (!$isNew) ? $item->Price : "" ?>" required />
                    </div>

                    <div class="field">
                        <label for="amount"><b>Amount:</b></label>
                        <input type="number" name="amount" min="0" class="fontsize1" id="amount" value="<?= (!$isNew) ? $item->Amount : "" ?>" required />
                    </div>

                    <hr>

                    <div class="field">

                        <label for="desc1"><b>Primary description:</b></label>

                        <textarea class="fontsize1 font" name="desc1" id="desc1" required><?=
                                                                                            (!$isNew) ? ltrim($item->Big_Description) : ""
                                                                                            ?></textarea>
                    </div>
                    <div class="field">

                        <label for="desc2"><b>Secondary description:</b></label>

                        <textarea class="fontsize1 font" name="desc2" id="desc2" required><?=
                                                                                            (!$isNew) ? ltrim($item->Small_Description) : ""
                                                                                            ?></textarea>
                    </div>

                    <hr>

                    <div class="field">
                        <label><b>Aisle:</b></label>

                        <?php
                        $aisles = Utilities::getAisles('..');
                        foreach ($aisles as $aisle) {
                            echo ("<div><label for=\"" . $aisle . "\">" . $aisle . "</label>");
                            if (isset($item) && trim($item->Aisle) == $aisle)
                                echo ("<input type=\"radio\" name=\"aisle\" class=\"fontsize1\" id=\"" . $aisle . "\" value=\"" . $aisle . "\" checked required/></div>");
                            else
                                echo ("<input type=\"radio\" name=\"aisle\" class=\"fontsize1\" id=\"" . $aisle . "\" value=\"" . $aisle . "\" required/></div>");
                        }
                        ?>
                    </div>

                    <input type='hidden' name="id" value="<?= $isNew ? null : $item->Id ?>" />
                    <button type="submit" class="submit">Submit</button>
            </form>
        </div>
    </div>
    </div>
    <footer id="foot">
        <a href="../about-us.php">About us</a>
        <a href="../legal-notice.php">Legal notice</a>
    </footer>
</body>

</html>