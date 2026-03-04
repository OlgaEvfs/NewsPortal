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
            $errorString .= "Неправильный email<br />";
        }
        if (!$password || !$confirm || mb_strlen($password) < 6) {
            $errorString .= "Пароль должен быть больше 6 символов <br />";
        }
        if ($password != $confirm) {
            $errorString .= "Пароли не совпадают<br />";
        }

        if (mb_strlen($errorString) == 0) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $date = date("Y-m-d");

            $sql = "INSERT INTO `users` (`id`, `username`, `email`, `password`, `status`, `registration_date`, `pass`) 
                    VALUES (NULL, '$name', '$email', '$passwordHash', 'user', '$date', '$password')";
            
            $item = $db->executeRun($sql);
            
            if ($item) {
                return array(0 => true);
            } else {
                return array(0 => false, 1 => 'Ошибка базы данных');
            }
        } else {
            return array(0 => false, 1 => $errorString);
        }
    }
}
?>