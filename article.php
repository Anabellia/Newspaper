
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
        <link href="css/article.css" rel="stylesheet">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300&display=swap" rel="stylesheet">

        <title>Home</title>
        

    </head>
    <body>
        <?php include_once("_menu.php")?>
        <main>
        <hr>
            <section>
            
            <?php

            $query = "SELECT * FROM vwall_news";
            $result = $db->query($query);
            $row = $db->fetch_object($result);
            
            if(login())
            {
                if(admin_check()) 
                {
                    echo "The article is seen:" . $row->visited . "times<br>";
                }
            }
            
    

            if(isset($_GET['id'])) $query = "SELECT * FROM vwall_news WHERE deleted = 0 AND id=" . $_GET['id'];
            

            $result = $db->query($query);

            if($db->error()) {
                echo "Unsuccessful connection to database!";
                echo $db->error().":(". $db->errno() .")";
                exit();
            }


            echo "<a href='news.php'>Back to all news</a>";


            while($row=$db->fetch_object($result)) {
                echo "<div style='border: 1px solid black; width: 100%; margin:2px; padding: 2px'>";
                echo "<a href='news.php?category=".$row->category."'>".$row->c_name."</a><br>";
                echo "<h3><a href='article.php?id=".$row->id."'>".$row->title."</a></h3>";
      
                    echo $row->text;
     
                
                
                echo "<b><a href='news.php?autor=".$row->author."'>".$row->fullname."</a></b> <i>".$row->time."</i><br>";

                echo "</div>";
            }

            $query = "UPDATE vwall_news SET visited=visited+1 WHERE id=".$_GET['id'];

            $db->query($query);

            unset($db);
            ?>
            
           

           
            </section>

            <section>
                <h3>Comments</h3>
                <form action="article.php" method="post">
                    <textarea name="comment" id="comment" cols="30" rows="10" placeholder="Enter your comment"></textarea><br>
                    <button>Save comment</button>
                </form>
            </section>
        </main>
        <?php include_once("_footer.php")?>

    </body>
</html>

