<?php
class Comments {
    // Вставка комментария (ТЕПЕРЬ С ВАЛИДАЦИЕЙ)
    public static function insertComment($text, $news_id, $db = null)
    {
        $db = $db ?? new Database();
        
        // Очищаем текст от тегов (XSS Protection)
        $cleanText = self::validateComment($text);
        
        if ($cleanText) {
            $news_id = (int)$news_id;
            $query ="INSERT INTO `comments` (`id`, `news_id`, `text`, `date`) 
                     VALUES (NULL, '$news_id', '$cleanText', CURRENT_TIMESTAMP)";
            return $db->executeRun($query);
        }
        return false;
    }

    // Чистая логика валидации (ДЛЯ ТЕСТОВ)
    public static function validateComment($text) {
        $text = trim($text);
        if (empty($text)) {
            return false;
        }
        // Удаляем HTML теги для безопасности
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    public static function getCommentByNewsID($id, $db = null) {
        $db = $db ?? new Database();
        $id = (int)$id;
        $query = "SELECT * FROM comments WHERE news_id=$id ORDER BY id DESC";
        return $db->getAll($query);
    }

    public static function getCommentsCountByNewsID($id, $db = null) {
        $db = $db ?? new Database();
        $id = (int)$id;
        $query = "SELECT count(id) as 'count' FROM comments WHERE news_id=$id";
        $c = $db->getOne($query);
        return $c['count'] ?? 0; // Возвращаем сразу число
    }
}
?>