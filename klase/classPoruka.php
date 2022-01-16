<?php
class Poruka{
    public static function error_message($str){
        return "<div style='background-color: red; color: white;width: 100%; margin: 2px; padding:2px'>$str</div>";
    }
    public static function success($str){
        return "<div style='background-color: green; color: white;width: 100%; margin: 2px; padding:2px'>$str</div>";
    }
    public static function info($str){
        return "<div style='background-color: blue; color: white;width: 100%; margin: 2px; padding:2px'>$str</div>";
    }
}
?>