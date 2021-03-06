<?php

session_start();
require_once("_require.php");

try {
    $db = new Database();
    if(!$db->connect()) throw new errorConnection();
}
catch (errorConnection $e){
    $e->message();
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/style.css" rel="stylesheet">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300&display=swap" rel="stylesheet">

        <title>Home</title>
        

    </head>
    <body>
        <?php include_once("_menu.php")?>
        <main>
            <section class="land">

            </section>
        </main>
        <?php include_once("_footer.php")?>

    </body>
</html>
