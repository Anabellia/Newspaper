<?php

session_start();
require_once("_require.php");

if(!login_check()) {
    header("location: index.php");
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
       
        <title>News</title>

    </head>
    <body>
        <?php include_once("_menu.php")?>
        
        <main>
        <section class="addnews">
                
            <?php

            try {
                $db = new Database();
                if(!$db->connect()) throw new errorConnection();
    
                echo "<div class='news__category-wrapp'>";
                echo "<div>";
    
                if(login()) {
                    if(admin_check()) {
                        echo "<a class='news__link' href='addnews.php'>Add news</a>";
                    }
                }
    
                echo "<a class='news__category' href='news.php'>All news</a>";
    
                $query = "SELECT * FROM categories";
                $result = $db->query($query);

                if(!$result) throw new errorQuery;

                while($row = $db->fetch_object($result)) {
                    echo "<a class='news__category' href='news.php?category={$row->id}'>{$row->c_name} </a>";
                }

                echo "</div>";
            } catch (errorConnection $e){
                $e->message();
            } catch (errorQuery $e) {
                $e->message();
            }
            
            ?>
            
            <form class="search" action="news.php" method="post">
            <input type="text" name="keyword" placeholder="Search">
            <button>Search</button>
            </div>

            </form>
            </section>
            <section class="wrapp_allnews">
            <?php

            try {
                $query = "SELECT * FROM vwall_news WHERE deleted=0 ORDER BY id DESC";
            

                if(isset($_GET['id'])) $query = "SELECT * FROM vwall_news WHERE deleted = 0 AND id=" . $_GET['id'];
                if(isset($_GET['category'])) $query="SELECT * FROM vwall_news WHERE deleted = 0 AND category=" . $_GET['category'];
                if(isset($_GET['author'])) $query = "SELECT * FROM vwall_news WHERE deleted = 0 AND author=" . $_GET['author'];
                if(isset($_POST['keyword'])) $query = "SELECT * FROM vwall_news WHERE deleted = 0 AND (title LIKE ('%".$_POST['keyword']."%') OR text LIKE ('%".$_POST['keyword']."%'))";
    
    
                $result = $db->query($query);
                if(!$result) throw new errorQuery;
    
                while($row=$db->fetch_object($result)) {
                    echo "<div class='news__box'>";
                    echo "<div class='cat__wrapp'>";
                    echo "<h3><a class='category' href='news.php?category=".$row->category."'>".$row->c_name."</a></h3>";
                    echo "</div>";
                    echo "<h2><a class='title' href='article.php?id=".$row->id."'>".$row->title."</a></h2>";
    
                    if(isset($_GET['id']))
                        echo "<p class='text'>$row->text</p>";
                    else
                    {
                        $tmp=explode(" ", $row->text);
                        $new=array_slice($tmp, 0, 35);
                        $a=implode(" ", $new)."...";
                        if(isset($_POST['keyword'])) {
                            echo "<div style='padding: 0 30px;'>" . str_replace(strtolower($_POST['keyword']), "<span style='background-color:yellow'>".$_POST['keyword']."</span>", strtolower($a)) . "</div>";
                            echo "<br>";
                        }
                            
                        else 
                            echo "<p class='text'>$a</p>";
                    }
                        
    
                    echo "<b><a class='author' href='news.php?author=".$row->author."'>".$row->fullname."</a></b> <i>".$row->time."</i><br>";
    
                    echo "</div>";
                }
            } catch (errorQuery $e) {
                $e->message();
            }
            
            
            unset($db);
            ?>
            </section>
        </main>
        <?php include_once("_footer.php")?>

    </body>
</html>

