<?php


function validString($str)
{
    if(strpos($str, ' ')!==false) return false;
    if(strpos($str, '=')!==false) return false;
    if(strpos($str, '(')!==false) return false;
    if(strpos($str, ')')!==false) return false;
    if(strpos($str, '*')!==false) return false;


    return true;
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