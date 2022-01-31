<?php
class Message{
    public static function error_message($str){
        return "<div style='font-weight: 600; color: red;display: inline-block; border-radius: 5px;'>$str</div>";
    }
    public static function success($str){
        return "<div style='font-weight: 600; color: green;display: inline-block; border-radius: 5px;'>$str</div>";
    }
    public static function info($str, $create=''){
        return "<div style='font-weight: 600; color: blue;display: inline-block; border-radius: 5px;'>$str $create</div>";
    }
}
?>