<?php
/**
 * Created by PhpStorm.
 * User: maciek
 * Date: 10.01.18
 * Time: 21:27
 */
class Db_object {

    protected static $db_table = "users";

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

    public function set_file($file) {                   //$file === $_FILES['uploaded_file']

        if(empty($file) || !$file || !is_array($file)) {
            $this->errors[] = "There was no file uploaded here";
            return false;
        } elseif($file['error'] != 0) {                 //0 = UPLOAD_ERR_OK
            $this->errors[] = $this->uploads_errors[$file['error']];
            return false;
        } else {
            $this->user_image = basename($file['name']);      //basename - removes path to file and returns user_image
            $this->tmp_path = $file['tmp_name'];
            $this->type = $file['type'];
            $this->size = $file['size'];
        }


    }

    public static function find_all() {

        return static::find_by_query("SELECT * FROM " . static::$db_table . " ");
    }

    public static function find_by_id($id) {
        global $database;
        $the_result_array = static::find_by_query("SELECT * FROM " . static::$db_table . " WHERE id=$id LIMIT 1"); //returns row which contains given id as an array
        return !empty($the_result_array) ? array_shift($the_result_array) : false;                                     //if array not empty, return first element
    }

    public static function find_by_query($sql) {                        //executes a query and returns an array
        global $database;
        $result_set = $database->query($sql);                           //performs a query and returns data
        $the_object_array = array();                                    //utworzenie tablicy
        while($row = mysqli_fetch_array($result_set)) {                 //fetchuje (robi array z) result_set i przypisuje jako wartość do row, tworzy się tablica z jednego wiersza z bazy na zasadzie klucz=>wartość
//            var_dump($row);
//            echo "<br>";
            $the_object_array[] = static::instantiation($row);            //row leci do instantiation i wraca jako obiekt z kluczami i wartościami
        }
        return $the_object_array;                                       //zwraca tablicę z obiektami(userami), key i value nadawane w metodzie instantiation
    }

    public static function instantiation($the_record) {

        $calling_class = get_called_class();                            //Gets the name of the class the static method is called in.

        $the_object = new $calling_class;                               //tworzy nowy obiekt klasy, któremu zostaną nadane przekazane wartości

        foreach ($the_record as $the_attribute => $value) {             //dla każdego klucza => wartości z tablicy $the_record
            if($the_object->has_the_attribute($the_attribute)) {        //dostaje true or false
                $the_object->$the_attribute = $value;                   //przypisuje w obiekcie $the_object wartość $value do zmiennej o nazwie będącą bieżącą wartością $the_attribute
            }
        }

        return $the_object;
    }

    private function has_the_attribute($the_attribute) {                //czy jest w klasie zmienna o nazwie $zmienna($the_attribute)
        $object_properties = get_object_vars($this);                   //class variables - properties - $this oznacza tę klasę

        return array_key_exists($the_attribute, $object_properties);    //zwraca true or false zależnie czy attribute jest w obj properties

    }

    protected function properties() {
        //return get_object_vars($this);                                  //zwraca tablicę z properties tej klasy wraz z wartosciami

        $properties = array();                                            //array w którym będzie klucz => wartosc
        foreach (static::$db_table_fields as $db_field) {                   //z tablicy fields pobiera klucze

            if(property_exists($this, $db_field)) {                       //jeżeli w tej klasie istnieje property o nazwie pod $db_field
                $properties[$db_field] = $this->$db_field;                //w tabllicy properties, dla klucza pod db_field dodaj wartość danej zmiennej w tej klasie
            }

        }

        return $properties;                                               //zwraca tablicę klucz => wartość
    }

    protected function cleanProperties() {
        global $database;                                                 //$database będzie dostępne w całym skrypcie

        $clean_properties = array();                                      //utwoerzenie docelowej tablicy

        foreach ($this->properties() as $key => $value) {                 //pobiera aktualne properties i leci po nich pętlą
            $clean_properties[$key] = $database->escape_string($value);   //pary property => value (clean) dla danego obiektu
        }

        return $clean_properties;                                         //zwraca tablicę property => value gotową do wykonania zapytania z użyciem iteracji
    }

    public function save() {
        return isset($this->id) ? $this->update() : $this->create();      //jesli istnieje id uzyj metody update, jesli nie uzyj create
    }


    public function create() {
        global $database;

        $properties = $this->cleanProperties();

        $sql = "INSERT INTO " .static::$db_table. "(" . implode(",", array_keys($properties)) . ")";     //insert do tabeli $db_name do danych kolumn
        $sql .= " VALUES ('". implode("','", array_values($properties)) ."')";                         //podstawia wartości z tablicy


        if($database->query($sql)) {

            $this->id = $database->the_insert_id();                 //generuje i zwraca id

            return true;
        } else {
            return false;
        }


    }

    public function update() {
        global $database;

        $properties = $this->cleanProperties();                     //przygotowuje propierties do zapytania
        $property_pairs = array();                                  //tworzenie tablicy pod zapytanie

        foreach ($properties as $key => $value) {
            $property_pairs[] = "{$key}='{$value}'";
        }

        $sql = "UPDATE " .static::$db_table. " SET ";
        $sql .= implode(", ", $property_pairs);                     //"SET klucz='wartosc', klucz2=wartosc2...
        $sql .= " WHERE id= "    . $database->escape_string($this->id);  //gdzie id to id obiektu

        $database->query($sql);                                          //zapytanie

        return (mysqli_affected_rows($database->connection) == 1) ? true : false;   //jezeli zapytanie dotyczylo jednego rzedu zwraca true

    }

    public function  delete() {
        global $database;
        $sql= "DELETE FROM " .static::$db_table. " WHERE id= " . $database->escape_string($this->id) . " LIMIT 1";

        $database->query($sql);

        return (mysqli_affected_rows($database->connection) == 1) ? true : false;
    }
}