<?php
class Register {
    // Основной метод для вызова из контроллера
    public static function registerUser() {
        if(isset($_POST['save'])) {
            $name = $_POST['name'] ?? '';
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm'] ?? '';
            
            $db = new Database();
            return self::processRegistration($name, $email, $password, $confirm, $db);
        }
        return array(0 => false, 1 => 'error');
    }

    // Логика, которую мы будем тестировать
    public static function processRegistration($name, $email, $password, $confirm, $db) {
        $errorString = "";

        if (!$email) {
            $errorString .= "Incorrect email<br />";
        }
        if (!$password || !$confirm || mb_strlen($password) < 6) {
            $errorString .= "Password must be more than 6 characters<br />";
        }
        if ($password != $confirm) {
            $errorString .= "Passwords do not match<br />";
        }

        if (mb_strlen($errorString) == 0) {
            // Check if email already exists
            $checkSql = "SELECT * FROM `users` WHERE `email` = '$email'";
            $existingUser = $db->getOne($checkSql);

            if ($existingUser) {
                return array(0 => false, 1 => "This email is already registered.<br />");
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $date = date("Y-m-d");

            $sql = "INSERT INTO `users` (`id`, `username`, `email`, `password`, `status`, `registration_date`, `pass`) 
                    VALUES (NULL, '$name', '$email', '$passwordHash', 'user', '$date', '$password')";
            
            $item = $db->executeRun($sql);
            
            if ($item) {
                return array(0 => true);
            } else {
                return array(0 => false, 1 => 'Database error');
            }
        } else {
            return array(0 => false, 1 => $errorString);
        }
    }
}
?>