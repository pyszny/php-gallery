<?php
require_once ("config.php");
/**
 * Created by PhpStorm.
 * User: maciek
 * Date: 27.12.17
 * Time: 17:18
 */
class Database {

    public $connection;

    function __construct()
    {
        $this->open_db_connection();
    }

    public function open_db_connection() {
        //$this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if($this->connection->connect_errno) {
            die("Database connection failed badly" . $this->connection->connect_error);
        }
    }


    public function query($sql) {
        //$result = mysqli_query($this->connection, $sql);
        $result = $this->connection->query($sql);                       //mysqli_query - Performs a query on the database

        $this->confirm_query($result);

        return $result;

    }


    private function confirm_query($result) {
        if(!$result) {
            die("Query Failed!" . $this->connection->error);
        }
    }


    public function escape_string($string) {        //Escapes special characters in a string for use in an SQL statement
        //$escaped_string = mysqli_real_escape_string($this->connection, $string);
        $escaped_string = $this->connection->real_escape_string($string);       //dla połączenia $connection, usuwa znaki specjalne i przygotowuje zapytanie
        return $escaped_string;
    }

    public function the_insert_id() {
        return $this->connection->insert_id;            //mysqli_insert_id — Returns the auto generated id used in the latest query
    }

}

$database = new Database();

