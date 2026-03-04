<?php
class News {

    public static function getLast10News($db = null) {
        $db = $db ?? new Database();
        $query = "SELECT * FROM news ORDER BY id DESC LIMIT 3";
        return $db->getAll($query);
    }

    public static function getAllNews($db = null) {
        $db = $db ?? new Database();
        $query = "SELECT * FROM news ORDER BY id DESC";
        return $db->getAll($query);
    }

    public static function getNewsByCategoryID($id, $db = null) {
        $db = $db ?? new Database();
        $id = (int)$id; // Безопасность: приведение к числу
        $query = "SELECT * FROM news where category_id=$id ORDER BY id DESC";
        return $db->getAll($query);
    }

    public static function getNewsByID($id, $db = null) {
        $db = $db ?? new Database();
        $id = (int)$id; // Безопасность: приведение к числу
        $query = "SELECT * FROM news where id=$id";
        return $db->getOne($query);
    }
}
?>