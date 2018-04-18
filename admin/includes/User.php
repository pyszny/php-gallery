<?php
/**
 * Created by PhpStorm.
 * User: maciek
 * Date: 27.12.17
 * Time: 21:39
 */

class User extends  Db_object {

    protected static $db_table = "users";                               //contains table name
    protected static $db_table_fields = array('username', 'password', 'first_name', 'last_name', 'user_image');
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;
    public $user_image;
    public $upload_directory = "images";
    public $images_placeholder = "http://placehold.it/400x400&text=image";


    public function upload_photo() {


            if(!empty($this->errors)) { //if any errors return false
                return false;
            }

            if(empty($this->user_image) || empty($this->tmp_path)) {
                $this->errors[] = "The file was not available";
                return false;
            }

            $target_path = SITE_ROOT . DS . 'admin' . DS . $this->upload_directory . DS . $this->user_image;  //full file path

            if(file_exists($target_path)) {
                $this->errors[] = "The file {$this->user_image} already exists";
                return false;
            }

            if(move_uploaded_file($this->tmp_path, $target_path)) {         //move from path a to b, continue if succeeded
                                                     //use create() method, continue if succeeded
                    unset($this->tmp_path);                                 //remove temporary path
                    return true;

            } else {
                $this->errors[] = "The file directory probably does not have permission";
                return false;
            }



    }


    public function image_path_and_placeholder() {
        return empty($this->user_image) ? $this->images_placeholder : $this->upload_directory.DS.$this->user_image;
    }

    public static function verify_user($username, $password) {
        global $database;

        $username = $database->escape_string($username);                //remove special characters
        $password = $database->escape_string($password);                //and prepare for query

        $sql = "SELECT * FROM " . self::$db_table . " WHERE ";
        $sql .= "username = '$username' ";
        $sql .= "AND password = '$password' ";
        $sql .= "LIMIT 1";

        $the_result_array = self::find_by_query($sql);                              //return row as an array
        return !empty($the_result_array) ? array_shift($the_result_array) : false;  //if true, return user id


    }











}