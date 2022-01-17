<?php
    session_start();
    session_unset();
    session_destroy();

    setcookie("id", " ", time()-1, "/");

    header("location: index.php")
?>