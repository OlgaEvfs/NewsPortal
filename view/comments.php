<?php
class ViewComments {

    public static function CommentsForm() {
        echo '<form action="insertcomment">
        <input type="hidden" name="id" value="'.$_GET['id'].'">
            Teie kommentaar: <input type="text" name="comment">
        <input type="submit" value="Saada"> </form>';
    }

    public static function CommentsByNews($arr)
    {
        if($arr!=null) {
            echo '<table id="ctable"><th>Kommentaar</th><th>Kuupäev</th>';
            foreach($arr as $value) {
                echo '<tr><td>'.$value['text']."</td><td>".$value['date']."</td></tr>";
            }
            echo '</table>';
        }
    }

    public static function CommentsCountWithAncor($value) {
        if ($value>0)
        echo '<b><a href="#ctable"/> ('.$value.') </a></b>';
    }

    public static function CommentsCount($value) {
        if ($value>0) {
            echo '<b><font color="red">('.$value.') </font></b>';
        }
    }

}//end class