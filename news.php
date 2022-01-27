
<?php

session_start();
require_once("_require.php");

$db = new Database();
if(!$db->connect()) exit();

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
        <link href="css/news.css" rel="stylesheet">

        <title>Home</title>
        

    </head>
    <body>
        <?php include_once("_menu.php")?>
        <hr>
        <main>
            <section>
            <?php
                    if(login()) {
                        if($_SESSION['status'] == "Administrator") {
                            echo "<a href='addnews.php'>Add news</a>";
                            echo "<a href='deletenews.php'>Delete news</a>";

                        }
                    }
                ?>
            </section>
            <hr>
            <section>
                
                <?php
                $query = "SELECT * FROM categories";
                $result = $db->query($query);
                while($row = $db->fetch_object($result)) {
                    echo "<a href='news.php?category={$row->id}'>{$row->c_name} </a>";
                }
                


                ?>
                <form action="news.php" method="post">
                <input type="text" name="keyword" placeholder="Search">
                <button>Search</button>

                </form>
               
            <?php
            $query = "SELECT * FROM vwall_news WHERE deleted=0";

            if(isset($_GET['id'])) $query = "SELECT * FROM vwall_news WHERE deleted = 0 AND id=" . $_GET['id'];
            if(isset($_GET['category'])) $query="SELECT * FROM vwall_news WHERE deleted = 0 AND category=" . $_GET['category'];
            if(isset($_GET['author'])) $query = "SELECT * FROM vwall_news WHERE deleted = 0 AND author=" . $_GET['author'];
            if(isset($_POST['keyword'])) $query = "SELECT * FROM vwall_news WHERE deleted = 0 AND (title LIKE ('%".$_POST['keyword']."%') OR text LIKE ('%".$_POST['keyword']."%'))";

            $result = $db->query($query);

            if($db->error()) {
                echo "Unsuccessful connection to database!";
                echo $db->error().":(". $db->errno() .")";
                exit();
            }


            echo "<br>Number of news: ".$db->num_rows($result);

         

            while($row=$db->fetch_object($result)) {
                echo "<div style='border: 1px solid black; width: 100%; margin:2px; padding: 2px'>";
                echo "<a href='news.php?category=".$row->category."'>".$row->c_name."</a><br>";
                echo "<h3><a href='article.php?id=".$row->id."'>".$row->title."</a></h3>";
                if(isset($_GET['id']))
                    echo $row->text;
                else
                {
                    //$a=$vest->deoTeksta();
                    $tmp=explode(" ", $row->text);
                    $new=array_slice($tmp, 0, 20);
                    $a=implode(" ", $new).".....<br>";
                    if(isset($_POST['keyword']))
                        echo str_replace(strtolower($_POST['keyword']), "<span style='background-color:yellow'>".$_POST['keyword']."</span>", strtolower($a));
                    else
                        echo $a;
                }
                    
                
                
                echo "<b><a href='news.php?autor=".$row->author."'>".$row->fullname."</a></b> <i>".$row->time."</i><br>";

                echo "</div>";
            }
            
            unset($db);
            ?>
            </section>

          
        </main>
        <?php include_once("_footer.php")?>

    </body>
</html>

