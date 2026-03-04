<?php
class modelAdmin {
    // АВТОРИЗАЦИЯ АДМИНА
    public static function userAuthentication()
    {
        if (isset($_SESSION['sessionId'])) {
            return true;
        }

        if(isset($_POST['btnLogin'])) {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';
            
            if ($email && $password) {
                $db = new database();
                $user = self::verifyUserCredentials($email, $password, $db);
                
                if ($user) {
                    $_SESSION['sessionId'] = session_id();
                    $_SESSION['userId'] = $user['id'];
                    $_SESSION['name'] = $user['username'];
                    $_SESSION['status'] = $user['status'];
                    return true;
                }
            }
        }
        return false;
    }

    // Чистая логика проверки учетных данных (ДЛЯ ТЕСТОВ)
    public static function verifyUserCredentials($email, $password, $db) {
        $email = strtolower($email);
        $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
        $user = $db->getOne($sql);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

// ВЫХОД ИЗ АДМИНКИ
    public static function userLogout()
    {
        unset($_SESSION['sessionId']);
        unset($_SESSION['userId']);
        unset($_SESSION['name']);
        unset($_SESSION['status']);
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}
?>