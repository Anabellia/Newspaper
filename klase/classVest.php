<?php
/*class Article {
    protected $id;
    protected $title;
    protected $text;
    protected $time;
    protected $author;
    protected $category;
    protected $deleted;
    protected $change;

    public function __construct($id, $title, $text, $time, $author, $category, $deleted, $change) {
        $this->id = $id;
        $this->title = $title;
        $this->text = $text;
        $this->time = $time;
        $this->category = $category;
        $this->deleted = $deleted;
        $this->change = $change;
    }

    public function category() {
        return "<a href='news.php?category=".$this->category."'>".$this->category. "</a><br>";
    }

    public function title() {
        return "<a href='news.php?title=".$this->title."'>".$this->category . "</a><br>";
    }

    public function author() {
        return "<a href='news.php?author=".$this->author."'>".$this->author."</a><br>";
    }

    public function text(){
        return $this->text."<br>";
    }

    public function deoTeksta(){
        $tmp=explode(" ", $this->tekst);
        $novi=array_slice($tmp, 0, 20);
        return implode(" ", $novi).".....<br>";
    }

    public function __get($name){
        return $this->$name;
    }
}*/

?>