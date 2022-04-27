<?php

class UserHandlingAPI
{
    static function loadUserFile()
    {
        return simplexml_load_file($_SERVER['DOCUMENT_ROOT'] . "/users.xml");
    }

    private static string $usernameKey = "fiugefRERgvrfrveGNEtv549324372gTYJ$53";
    private static string $emailKey = "efuheriuhdhfe79$?inWEFGERgwaufdheiFGERufhw";
    private static string $passwordKey = "flvkjh#$%?%*#$%$%&*%&ebdflkjhcblkfbFGEFRG,HNBEFasdkjqlwhdfsDFGslkdfgweraskjedweDFREg";
    private static string $algorithm = "AES-256-CTR";
    private static string $emailCookieKey = ";wodefgihuwl;krgWDFGwefgbqkhglurg%#$352t24qEH$2";
    private static string $passwordCookieKey = "rtyelrtihgWRYUGHHHGNYgfjheEDwh4525RWH4326TREWq";
    private bool $isAuthed;

    function __construct()
    {
        $this->checkToken();
    }

    private static function encryptUsername(string $username): string|null
    {
        {
            try {
                $iv = random_bytes(16);
                $encryptedUsername = base64_encode(openssl_encrypt($username, self::$algorithm, self::$usernameKey, OPENSSL_RAW_DATA, $iv) . $iv);
                return $encryptedUsername;

            } catch (Exception $e) {
                error_log('UNABLE TO CREATE PROPER ENCRYPTION, ABORTING.');
                return null;
            }
        }
    }

    /**
     * @return bool
     */
    public function isAuthed(): bool
    {
        return $this->checkToken();
    }

    private static function decryptUsername($encryptedUsername)
    {
        $base64decodedEncryptedUsername = base64_decode($encryptedUsername);
        $iv = substr($base64decodedEncryptedUsername, strlen($base64decodedEncryptedUsername) - 16);
        $properEncryptedUsername = substr($base64decodedEncryptedUsername, 0, strlen($base64decodedEncryptedUsername) - 16);

        return openssl_decrypt($properEncryptedUsername, self::$algorithm, self::$usernameKey, OPENSSL_RAW_DATA, $iv);
    }

    private static function encryptPassword(string $password): string|null
    {
        {
            try {
                $iv = random_bytes(16);
                $encryptedPassword = base64_encode(openssl_encrypt($password, self::$algorithm, self::$passwordKey, OPENSSL_RAW_DATA, $iv) . $iv);
                return $encryptedPassword;

            } catch (Exception $e) {
                error_log('UNABLE TO CREATE PROPER ENCRYPTION, ABORTING.');
                return null;
            }
        }
    }

    private static function encryptPasswordCookie(string $password): string|null
    {
        {
            try {
                $iv = random_bytes(16);
                $encryptedPassword = base64_encode(openssl_encrypt($password, self::$algorithm, self::$passwordCookieKey, OPENSSL_RAW_DATA, $iv) . $iv);
                return $encryptedPassword;

            } catch (Exception $e) {
                error_log('UNABLE TO CREATE PROPER ENCRYPTION, ABORTING.');
                return null;
            }
        }
    }

    private static function encryptEmailCookie(string $email): string|null
    {
        {
            try {
                $iv = random_bytes(16);
                $encryptedEmail = base64_encode(openssl_encrypt($email, self::$algorithm, self::$emailCookieKey, OPENSSL_RAW_DATA, $iv) . $iv);
                return $encryptedEmail;

            } catch (Exception $e) {
                error_log('UNABLE TO CREATE PROPER ENCRYPTION, ABORTING.');
                return null;
            }
        }
    }

    private static function decryptPasswordCookie($encryptedPassword)
    {
        $base64decodedEncryptedPassword = base64_decode($encryptedPassword);
        $iv = substr($base64decodedEncryptedPassword, strlen($base64decodedEncryptedPassword) - 16);
        $properEncryptedPassword = substr($base64decodedEncryptedPassword, 0, strlen($base64decodedEncryptedPassword) - 16);

        return openssl_decrypt($properEncryptedPassword, self::$algorithm, self::$passwordCookieKey, OPENSSL_RAW_DATA, $iv);
    }

    private static function decryptEmailCookie($encryptedEmail)
    {
        $base64decodedEncryptedEmail = base64_decode($encryptedEmail);
        $iv = substr($base64decodedEncryptedEmail, strlen($base64decodedEncryptedEmail) - 16);
        $properEncryptedPassword = substr($base64decodedEncryptedEmail, 0, strlen($base64decodedEncryptedEmail) - 16);

        return openssl_decrypt($properEncryptedPassword, self::$algorithm, self::$emailCookieKey, OPENSSL_RAW_DATA, $iv);
    }

    private static function decryptPassword($encryptedPassword)
    {
        $base64decodedEncryptedPassword = base64_decode($encryptedPassword);
        $iv = substr($base64decodedEncryptedPassword, strlen($base64decodedEncryptedPassword) - 16);
        $properEncryptedPassword = substr($base64decodedEncryptedPassword, 0, strlen($base64decodedEncryptedPassword) - 16);

        return openssl_decrypt($properEncryptedPassword, self::$algorithm, self::$passwordKey, OPENSSL_RAW_DATA, $iv);
    }

    private
    static function encryptEmail(string $email): null|string
    {
        try {
            $iv = random_bytes(16);
            $encryptedEmail = base64_encode(openssl_encrypt($email, self::$algorithm, self::$emailKey, OPENSSL_RAW_DATA, $iv) . $iv);
            return $encryptedEmail;

        } catch (Exception $e) {
            error_log('UNABLE TO CREATE PROPER ENCRYPTION, ABORTING.');
            return null;
        }
    }

    private
    static function decryptEmail(string $encrypted): string|null
    {
        $encrypted = base64_decode($encrypted);
        $iv = substr($encrypted, strlen($encrypted) - 16);
        $properEncryptedEmail = substr($encrypted, 0, strlen($encrypted) - 16);

        return openssl_decrypt($properEncryptedEmail, self::$algorithm, self::$emailKey, OPENSSL_RAW_DATA, $iv);
    }

    public static function authAdmin()
    {
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
    }

    public
    static function createUser(string $username, string $email, string $password): array
    {
        $usersXML = self::loadUserFile();
        $userArr = $usersXML->xpath("user");
        $errorArr = [];
        $errorCount = 0;
        foreach ($userArr as $user => $userValues) {

            $userValues = (array)$userValues;

            if (!isset($userValues['email']) || $userValues["email"] == '' || !isset($userValues['username']) || $userValues["username"] == '') return ["success" => false, "message" => "Server error."];
            if (trim(self::decryptUsername($userValues['username'])) === trim($username)) {

                $errorArr['success'] = false;
                $errorArr[$errorCount] = ["message" => "Error, username already exists!", "scope" => "username"];
                $errorCount++;
            }
            if (trim($username) === "") {
                $errorArr['success'] = false;

                $errorArr[$errorCount] = ["message" => "Error, Invalid username.", "scope" => "username"];
                $errorCount++;
            }
            if (self::decryptEmail($userValues['email']) === $email) {

                $errorArr['success'] = false;
                $errorArr[$errorCount] = ["message" => "Error, email already exists!", "scope" => "email"];
                $errorCount++;
            }
            if (trim($email) === "") {

                $errorArr['success'] = false;
                $errorArr[$errorCount] = ["message" => "Error, Email is empty!", "scope" => "email"];
                $errorCount++;
            }
            if ($errorCount > 0) return $errorArr;

        }
        $newID = random_int(0, 1000000);
        $sameIDDetected = false;
        do {
            $sameIDDetected = false;
            foreach ($userArr as $user => $userValues) {
                $userValues = (array)$userValues;
                if ($userValues['uid'] === "$newID") {
                    $sameIDDetected = true;
                    $newID = random_int(0, 1000000);
                    break;
                }
            }

        } while ($sameIDDetected);

        //  }
        $newUser = $usersXML->addChild('user');

        $encryptedEmail = self::encryptEmail($email);
        $newUser->addChild('uid', $newID);
        $newUser->addChild('email', $encryptedEmail);
        $encryptedPassword = self::encryptPassword($password);
        $newUser->addChild('password', $encryptedPassword);
        //todo change this so we can add more admins!
        $newUser->addChild('admin', "false");
        $encryptedUsername = self::encryptUsername($username);
        $newUser->addChild("username", $encryptedUsername);

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/users.xml', $usersXML->asXML());

        return ["success" => true, "email" => $encryptedEmail, "password" => $encryptedPassword];

    }

    static function editUser(string $uid, string|null $email, string|null $username, string|null $password): array
    {
        $userXML = self::loadUserFile();
        $usersArr = $userXML->xpath("user");

        foreach ($usersArr as $user => $userValues) {
            if (self::decryptUsername($userValues->username) === $username && trim($userValues->uid) !== trim($uid)) {
                return ["success" => false, "message" => "Error, username already exists!", "scope" => "username"];
            }
            if (self::decryptEmail($userValues->email) === $email && trim($userValues->uid) !== trim($uid)) {
                return ["success" => false, "message" => "Error, email already exists!", "scope" => "email"];
            }
            if (trim($userValues->uid) === trim($uid)) {
                if ($email !== null) {
                    $userValues->email = self::encryptEmail($email);
                }
                if ($username !== null) {
                    $userValues->username = self::encryptUsername($username);
                }

                if ($password !== null) {
                    $userValues->password = self::encryptPassword($password);
                }
                $userXML->asXML($_SERVER['DOCUMENT_ROOT'] . "/users.xml");
                return ["success" => true];
            }
        }
        return ["success" => false, "message" => "Could not find UID!", "scope" => "uid"];
    }

    public function checkToken(bool $checkAdmin = false): bool
    {
        if (isset($_COOKIE['email']) && isset($_COOKIE['password'])) {

            $email = self::decryptEmailCookie($_COOKIE['email']);
            $password = self::decryptPasswordCookie($_COOKIE['password']);
            $loginInfo = self::checkLogin($email, $password);
            if ($loginInfo === false) {
                unset($_SESSION['user']);
                return false;
            } elseif (!isset($_SESSION['user']) || $_SESSION['user'] !== $loginInfo) {
                $_SESSION['user'] = $loginInfo;
            }
            if (!$checkAdmin)
                return true; elseif ($checkAdmin && $loginInfo['admin']) {
                return true;
            }
        }

        unset($_SESSION['user']);
        return false;
    }

    function deleteUser($id)
    {
        $isDelete = false;
        $userXML = self::loadUserFile();
        foreach ($userXML->xpath("user") as $userValues) {
            if (trim($userValues->uid) === trim($id)) {
                unset($userValues[0][0]);
                $isDelete = true;
            }
        }

        if ($isDelete) {
            $userXML->asXML($_SERVER['DOCUMENT_ROOT'] . "/users.xml");
        }
    }

    function checkUserExistence($id): array
    {
        if ($id == null) return ["valid" => false];
        $userList = self::getUserList();

        if (isset($_GET["id"])) {
            foreach ($userList as $user => $userInfo) {

                if (trim($userInfo['uid']) === trim($id)) {
                    return ["valid" => true, "user" => $userInfo];
                    $currentUser = $userInfo;
                    break;
                }
            }

        }
        return ["valid" => false];
    }

    private function checkLogin($login, $password, $isCookie = false): array|bool
    {
        $usersArr = self::loadUserFile()->xpath("user");

        foreach ($usersArr as $user => $userValues) {

            $userValues = (array)$userValues;


            if ($password != $this->decryptPassword($userValues['password'])) continue;
            if ((isset($userValues['email']) && $this->decryptEmail($userValues['email']) == $login) || $login == $this->decryptUsername($userValues['username'])) {

                //todo add get the cart of the user as well
                //todo add ability to save a user's cart to the xml file

                if ($userValues['admin'] === "true") {
                    $userValues['admin'] = true;
                } else {
                    $userValues['admin'] = false;
                }
                if (!$isCookie) {
                    return ['id' => $userValues['uid'], 'admin' => $userValues['admin'], "email" => self::encryptEmail(self::decryptEmail($userValues['email'])), "password" => self::encryptPassword($password)];

                } else {
                    return ['id' => $userValues['uid'], 'admin' => $userValues['admin'], "email" => self::encryptEmailCookie(self::decryptEmail($userValues['email'])), "password" => self::encryptPasswordCookie($password)];

                }
            }
        }
        return false;
    }


    static function getUserList(): array
    {
        $xmlFile = self::loadUserFile();
        $xmlArr = $xmlFile->xpath("user");
        $parsedUserArr = [];
        $counter = 0;
        foreach ($xmlArr as $unused => $content) {

            $content = (array)$content;
            $content['username'] = self::decryptUsername($content['username']);
            $content['email'] = self::decryptEmail($content['email']);
            unset($content['password']);

            if ($content['admin'] === "true") {
                $content['admin'] = true;
            } else {
                $content['admin'] = false;
            }

            $parsedUserArr[$counter] = $content;

            $counter++;
        }

        return $parsedUserArr;
    }

    public function login($login, $password): array|bool
    {
        $loginInfo = $this->checkLogin($login, $password, true);

        if ($loginInfo === false) {
            unset($_SESSION['user']);
            return false;
        }
        setcookie("email", self::encryptEmailCookie($login), time() + 10000);
        setcookie("password", self::encryptPasswordCookie($password), time() + 10000);
        return [true, $loginInfo['admin']];

    }
}