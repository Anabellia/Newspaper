<?php
function konekcija()
{
    $db=@mysqli_connect("localhost", "root", "", "g2");
    if(!$db)
    {
        echo "Neuspesna konekcija na bazu!!!<br>";
        echo mysqli_connect_errno()."<br>";
        echo mysqli_connect_error()."<br>";
        if(mysqli_connect_errno()==2002)
            echo "Navedeni MySQL server ne postoji<br>";
        return false;
    }
    mysqli_query($db, "SET NAMES utf8");
    return $db;
}

function meni($db)
{
    $upit="SELECT * FROM kategorije";
    $rez=mysqli_query($db, $upit);
    echo "<div><a href='index.php'>Pocetna</a> ";
    while($red=mysqli_fetch_assoc($rez))
        echo "<a href='index.php?kategorija=".$red['id']."'>".$red['naziv']."</a> ";
    echo "</div>";

}

function validString($str)
{
    if(strpos($str, ' ')!==false) return false;
    if(strpos($str, '=')!==false) return false;
    if(strpos($str, '(')!==false) return false;
    if(strpos($str, ')')!==false) return false;
    if(strpos($str, '*')!==false) return false;


    return true;
}

function statistika($db, $tekst=''){
    $upit="INSERT INTO statistika (ipadresa, stranica, parametri, tekst) VALUES ('{$_SERVER['REMOTE_ADDR']}', '{$_SERVER['SCRIPT_NAME']}', '{$_SERVER['QUERY_STRING']}', '{$tekst}')";
    mysqli_query($db, $upit);
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