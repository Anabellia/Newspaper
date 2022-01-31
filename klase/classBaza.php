<?php

class Database {
    protected $location;
    protected $username;
    protected $password;
    protected $database;
    protected $db;

    public function __construct() {
        $this->location = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->database = "news"; 
    }

  

    public function connect() {
        try{
            $this->db = @mysqli_connect($this->location, $this->username, $this->password, $this->database);
            if(!$this->db) throw new Exception(mysqli_connect_error()); //throw exception so mysqli can execute anything that's in catch block because my sqli doesn't throw exceptions he was made before there were exceptions at all
        } catch (Exception $e){
            echo $e->getMessage();

        }
        return $this->db;
    }

    public function query($query) {
        return @mysqli_query($this->db, $query);
    }

    public function fetch_assoc($result) {
        return mysqli_fetch_assoc($result);
    }

    public function fetch_object($result) {
        return @mysqli_fetch_object($result);
    }

    public function error() {
        return mysqli_error($this->db);
    }

    public function errno() {
        return mysqli_errno($this->db);
    }

    public function num_rows($result) {
        return mysqli_num_rows($result);
    }


}

?>