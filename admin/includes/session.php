<?php
/**
 * Created by PhpStorm.
 * User: maciek
 * Date: 29.12.17
 * Time: 19:54
 */

class Session {

    private $signed_in = false;
    public  $user_id;
    public  $message;
    public  $count;

    function __construct() {
        session_start();
        $this->visitor_count();
        $this->check_the_login();
        $this->check_message();
    }

    public function visitor_count() {
        if(isset($_SESSION['count'])) {
            return $this->count = $_SESSION['count']++;
        } else {
            return $_SESSION['count'] = 1;
        }
    }


    public function message($msg = "") {
        if(!empty($msg)) {
            $_SESSION['message'] = $msg;
        } else {
            return $this->message;
        }
    }

    public function check_message() {
        if(isset($_SESSION['message'])) {
            $this->message = $_SESSION['message'];
            unset($_SESSION['message']);                        //unset to make sure that same message is not there after refresh
        } else {
            $this->message = "";                                //if session is not set, set property to empty to prevent errors
        }
    }


    public function is_signed_in() {                        //Getter return value of private property $signed_in
        return $this->signed_in;
    }

    public function login($user) {
        if($user) {                                             //if such user exist
            $this->user_id = $_SESSION['user_id'] = $user->id;  //property
            $this->signed_in = true;
        }
    }

    public function logout() {
        unset($_SESSION['user_id']);                          //usuwa id usera z tablicy SESSION
        unset($this->user_id);                                //usuwa id usera z sesji
        $this->signed_in = false;                             //user zalogowany ustawia na false
    }

    private function check_the_login() {                      //check if user is logged

        if(isset($_SESSION['user_id'])) {                     //if there is user id in session
            $this->user_id = $_SESSION['user_id'];
            $this->signed_in = true;
        } else {
            unset($this->user_id);
            $this->signed_in = false;
        }
    }

    public function getUserId() {
        return $this->user_id;
    }
}



$session = new Session();