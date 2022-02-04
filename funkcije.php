<?php

function sanitize_str($str) {
    $str = filter_var($str, FILTER_SANITIZE_STRING);
    return $str;
}

function sanitize_email($email) {
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    return $email;
}

function filter_int($int) {
    if(!filter_var($int, FILTER_VALIDATE_INT)) {
        echo Message::error_message("Data is not an integer!");
        exit();
        return false;
    }
    else return true;
}

function filter_email($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo Message::error_message("Email is not valid!");
        return false;
    }
    else return true;
}



function login() {
    if(isset($_SESSION['id']) and isset($_SESSION['full_name']) and isset($_SESSION['status'])) 
    {
        return true;
    } else if (isset($_COOKIE['id']) and isset($_COOKIE['full_name']) and isset($_COOKIE['status'])) //if there are cookies when user opens the site again then make a session;
    {
        $_SESSION['id'] = $_COOKIE['id'];
        $_SESSION['full_name'] = $_COOKIE['full_name'];
        $_SESSION['status'] = $_COOKIE['status'];
    } else
    {
        return false;
    }
}

function admin_check() {
    if($_SESSION['status'] == "Administrator") return true;
    else return false;
                
}

function login_check() {
    if($_SESSION['status'] == "Administrator" || $_SESSION['status'] == "User") return true;
    else return false;
                
}
?>