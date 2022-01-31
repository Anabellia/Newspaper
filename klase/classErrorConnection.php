<?php

class errorConnection extends Exception{

    public function message() {
        $str= "MySQL Connection error: " . date("d.m.Y H:i:s", time())." - [{$this->getCode()}] {$this->getMessage()} - {$this->getFile()} ({$this->getLine()})\r\n";
        $exit="";
        if(file_exists("errors.txt")) 
            $exit=file_get_contents("errors.txt");
        file_put_contents("errors.txt", $str.$exit);
    }
    
};

?>