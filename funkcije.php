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

function validanString($str)
{
    if(strpos($str, ' ')!==false) return false;
    if(strpos($str, '=')!==false) return false;
    return true;
}

function statistika($db, $tekst=''){
    $upit="INSERT INTO statistika (ipadresa, stranica, parametri, tekst) VALUES ('{$_SERVER['REMOTE_ADDR']}', '{$_SERVER['SCRIPT_NAME']}', '{$_SERVER['QUERY_STRING']}', '{$tekst}')";
    mysqli_query($db, $upit);
}
?>