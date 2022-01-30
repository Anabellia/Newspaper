
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

        <title>Home</title>
        

    </head>
    <body>
        <?php include_once("_menu.php")?>
        
        <main>
        
         
            
            <?php

            /***********select from db */

            if(isset($_GET['id'])) {
                $query = "SELECT * FROM vwall_news WHERE deleted = 0 AND id=" . $_GET['id'];
            }

            $result = $db->query($query);
            $row=$db->fetch_object($result);
            if($db->error()) {
                echo "Unsuccessful connection to database!";
                echo $db->error().":(". $db->errno() .")";
                exit();
            }

       

            /***********back and delete */

            echo "<div class='addnews' style='margin-bottom: 25px';>";
            echo "<a class='news__link news__link--left' href='news.php'>Back to all news</a>";
            echo "<a class='news__link' href='article.php?id=" . $row->id . "&delete=" . $row->id ."'>Delete this article</a>";
            echo "</div>";
           
            if(isset($_GET['delete'])) {
                echo "<section style='padding-bottom: 25px;'>";
                echo "<p>Are you sure you want to delete this article?</p>";
                echo "<form action='article.php?id=" . $row->id . "' method='post'>";
                echo "<button class='delete__btn'type='submit' name='del'>Delete</button>";
                echo "<button class='cancel__btn'><a href='article.php?id=" . $row->id . "'>Cancel</a></button>";
                echo "</form>";
                echo "</section>";
            }

            if(isset($_POST['del'])) {
                $query = "UPDATE all_news SET deleted = 1 WHERE id=" . $_GET['id'];
                $result = $db->query($query);
                header('location: news.php');

            }
            
           echo "<section>";
            echo "<div style='border-bottom: 1px solid black; width: 740px; padding-bottom: 20px; padding-left:20px;'>";
            echo "<div class='cat__wrapp'>";
            echo "<h3><a class='category' href='news.php?category=".$row->category."'>".$row->c_name."</a></h3>";
            echo "</div>";
            echo "<h2><a class='title' href='article.php?id=".$row->id."'>".$row->title."</a></h2>";
    
            echo "<p class='text'>$row->text</p><br>";

            echo "<b><a class='author' href='news.php?autor=".$row->author."'>".$row->fullname."</a></b> <i>".$row->time."</i><br>";

            echo "</div>";
            echo "</section>";

            unset($db);
            ?>
            
           

           
            </section>

            <section class="comments">
                <h3>Comments</h3>
                <form action="article.php" method="post">
                    <textarea name="comment" id="comment" cols="70" rows="4" placeholder="Enter your comment"></textarea><br>
                    <button>Save comment</button>
                </form>
            </section>
        </main>
        <?php include_once("_footer.php")?>

    </body>
</html>

