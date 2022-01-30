<?php

session_start();
require_once("_require.php");

$db = new Database();
if(!$db->connect()) exit();

$message="";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet">
    <title>Register</title>
</head>
<body>
<?php include_once("_menu.php")?>
        <hr>
</body>
</html>