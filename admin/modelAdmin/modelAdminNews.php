<?php
class modelAdminNews {

    public static function getNewsList($db = null) {
        $db = $db ?? new Database();
        $query = "SELECT news.*, category.name, users.username FROM news,
        category, users WHERE news.category_id=category.id AND
        news.user_id=users.id ORDER BY `news`.`id` DESC";
        return $db->getAll($query);
    }

    // Основной метод для вызова из контроллера
    public static function getNewsAdd() {
        if (isset($_POST['save'])) {
            $title = $_POST['title'] ?? '';
            $text = $_POST['text'] ?? '';
            $idCategory = $_POST['idCategory'] ?? 0;
            
            $image = "";
            if (isset($_FILES['picture']['tmp_name']) && $_FILES['picture']['tmp_name'] != "") {
                $image = addslashes(file_get_contents($_FILES['picture']['tmp_name']));
            }

            $db = new Database();
            return self::processNewsAdd($title, $text, $idCategory, $image, $db);
        }
        return false;
    }

    // Чистая логика добавления (ДЛЯ ТЕСТОВ)
    public static function processNewsAdd($title, $text, $idCategory, $image, $db) {
        if (empty($title) || empty($text) || empty($idCategory)) {
            return false;
        }

        $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $idCategory = (int)$idCategory;
        
        $sql = "INSERT INTO `news` (`id`, `title`, `text`, `picture`, `category_id`, `user_id`) 
                VALUES (NULL, '$title', '$text', '$image', '$idCategory', '1')";
        
        return $db->executeRun($sql);
    }

    public static function getNewsDetail($id, $db = null) {
        $db = $db ?? new Database();
        $id = (int)$id;
        $query = "SELECT news.*, category.name, users.username FROM news, category, users 
                  WHERE news.category_id=category.id AND news.user_id=users.id AND news.id=$id";
        return $db->getOne($query);
    }

    // Основной метод для редактирования
    public static function getNewsEdit($id) {
        if (isset($_POST['save'])) {
            $title = $_POST['title'] ?? '';
            $text = $_POST['text'] ?? '';
            $idCategory = $_POST['idCategory'] ?? 0;
            
            $image = "";
            if (isset($_FILES['picture']['tmp_name']) && $_FILES['picture']['tmp_name'] != "") {
                $image = addslashes(file_get_contents($_FILES['picture']['tmp_name']));
            }

            $db = new Database();
            return self::processNewsEdit($id, $title, $text, $idCategory, $image, $db);
        }
        return false;
    }

    // Чистая логика редактирования (ДЛЯ ТЕСТОВ)
    public static function processNewsEdit($id, $title, $text, $idCategory, $image, $db) {
        if (empty($title) || empty($text) || empty($idCategory)) {
            return false;
        }

        $id = (int)$id;
        $idCategory = (int)$idCategory;
        $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

        if ($image == "") {
            $sql = "UPDATE `news` SET `title` = '$title', `text` = '$text', `category_id` = '$idCategory' 
                    WHERE `news`.`id` = $id";
        } else {
            $sql = "UPDATE `news` SET `title` = '$title', `text` = '$text', `picture` = '$image', `category_id` = '$idCategory' 
                    WHERE `news`.`id` = $id";
        }

        return $db->executeRun($sql);
    }

    // Удаление
    public static function getNewsDelete($id) {
        if (isset($_POST['save'])) {
            $db = new Database();
            return self::processNewsDelete($id, $db);
        }
        return false;
    }

    // Чистая логика удаления (ДЛЯ ТЕСТОВ)
    public static function processNewsDelete($id, $db) {
        $id = (int)$id;
        $sql = "DELETE FROM `news` WHERE `news`.`id` = $id";
        return $db->executeRun($sql);
    }
}// class