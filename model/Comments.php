<?php
class Comments {
    // Insert comment (NOW WITH VALIDATION)
    public static function insertComment($text, $news_id, $db = null)
    {
        $db = $db ?? new Database();
        
        // Clean text from tags (XSS Protection)
        $cleanText = self::validateComment($text);
        
        if ($cleanText) {
            $news_id = (int)$news_id;
            $query ="INSERT INTO `comments` (`id`, `news_id`, `text`, `date`) 
                     VALUES (NULL, '$news_id', '$cleanText', CURRENT_TIMESTAMP)";
            return $db->executeRun($query);
        }
        return false;
    }

    // Pure validation logic (FOR TESTS)
    public static function validateComment($text) {
        $text = trim($text);
        if (empty($text)) {
            return false;
        }
        // Remove HTML tags for security
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
        return $c['count'] ?? 0; // Return the number directly
    }
}
?>