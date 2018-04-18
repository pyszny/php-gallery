<?php
/**
 * Created by PhpStorm.
 * User: maciek
 * Date: 14.01.18
 * Time: 15:50
 */
class Photo extends Db_object {

    protected static $db_table = "photos";
    protected static $db_table_fields = array('title', 'caption', 'description', 'filename', 'alternate_text', 'type', 'size');
    public $id;
    public $title;
    public $caption;
    public $description;
    public $filename;
    public $alternate_text;
    public $type;
    public $size;

    public $tmp_path;
    public $upload_directory = "images";
    public $errors = array();
    public $uploads_errors = array(

        UPLOAD_ERR_OK => "There is no error.",
        UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload_max_filesize.",
        UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the MAX_FIME_SIZE directive.",
        UPLOAD_ERR_NO_FILE  => "No file was uploaded.",
        UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded.",
        UPLOAD_ERR_CANT_WRITE => "Filed to write file to disc.",
        UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload.",
        UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder."

    );

    //This is passing $_FILES['uploaded_file'] as an argument

    public function set_file($file) {                   //$file === $_FILES['uploaded_file']

        if(empty($file) || !$file || !is_array($file)) {
            $this->errors[] = "There was no file uploaded here";
            return false;
        } elseif($file['error'] != 0) {                 //0 = UPLOAD_ERR_OK
            $this->errors[] = $this->uploads_errors[$file['error']];
            return false;
        } else {
            $this->filename = basename($file['name']);      //basename - removes path to file and returns filename
            $this->tmp_path = $file['tmp_name'];
            $this->type = $file['type'];
            $this->size = $file['size'];
        }


    }

    public function picture_path() {
        return $this->upload_directory.DS.$this->filename;      //returns directory/image.jpg
    }

    public function save() {

        if($this->id) {                 //id id exists
            $this->update();            //overwrite
        } else {

            if(!empty($this->errors)) { //if any errors return false
                return false;
            }

            if(empty($this->filename) || empty($this->tmp_path)) {
                $this->errors[] = "The file was not available";
                return false;
            }

            $target_path = SITE_ROOT . DS . 'admin' . DS . $this->upload_directory . DS . $this->filename;  //full file path

            if(file_exists($target_path)) {
                $this->errors[] = "The file {$this->filename} already exists";
                return false;
            }

            if(move_uploaded_file($this->tmp_path, $target_path)) {         //move from path a to b, continue if succeeded
                if($this->create()) {                                       //use create() method, continue if succeeded
                    unset($this->tmp_path);                                 //remove temporary path
                    return true;
                }
            } else {
                $this->errors[] = "The file directory probably does not have permission";
                return false;
            }

        }

    }

    public function delete_photo()
    {
        if($this->delete()) {

            $target_path = SITE_ROOT.DS. 'admin' . DS . $this->picture_path();

            return unlink($target_path) ? true : false;

        } else {

            return false;

        }
    }

}