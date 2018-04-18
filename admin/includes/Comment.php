<?php
/**
 * Created by PhpStorm.
 * User: maciek
 * Date: 27.12.17
 * Time: 21:39
 */

class Comment extends  Db_object {

    protected static $db_table = "comments";                               //contains table name
    protected static $db_table_fields = array('photo_id', 'author', 'body', 'comment_date');
    public $id;
    public $photo_id;
    public $author;
    public $body;
    public $comment_date;


    public static function create_comment($photo_id, $author="John",$body="", $comment_date) {
        if(!empty($photo_id) && !empty($author) && !empty($body)) {

            $comment = new Comment();

            $comment->photo_id = (int)$photo_id;
            $comment->author   = $author;
            $comment->body     = $body;
            $comment->comment_date = time();
            return $comment;

        } else {
            return false;
        }
    }


    public static function find_the_comments($photo_id = 0) {

        global $database;

        $sql = "SELECT * FROM " . self::$db_table;
        $sql.= " WHERE photo_id = " . $database->escape_string($photo_id);
        $sql.= " ORDER BY photo_id ASC";

        return self::find_by_query($sql);
    }


    public function change_date_format($comment_date) {
        $comment_date = date('F j, Y, g:i a', $comment_date);
        return $comment_date;
    }




} //End of Comment class