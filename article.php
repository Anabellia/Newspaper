
<?php

session_start();
require_once("_require.php");

$db = new Database();
if(!$db->connect()) exit();

$message = "";
$messageSelect = "";

if(!login_check()) {
    if($_SESSION['status'] != "Administrator" || $_SESSION['status'] != "User") {
        header("location: index.php");

    }
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

        <title>Article</title>
        

    </head>
    <body>
        <?php include_once("_menu.php")?>
        
        <main>
        
         
            
            <?php

/*************** select from db */

                $query = "SELECT * FROM vwall_news where deleted=0";

                $result = $db->query($query);
                $row=$db->fetch_object($result);

                if($db->error()) {
                    echo "Unsuccessful connection to database!";
                    echo $db->error().":(". $db->errno() .")";
                    exit();
                }

/*************** back and delete links under header */

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

/***************  showing the article  */

                if(isset($_GET['id'])) {
                    $query = "SELECT * FROM vwall_news WHERE deleted = 0 AND id=" . $_GET['id'];

                    $result = $db->query($query);
                    $row=$db->fetch_object($result);

                    if($db->error()) {
                        echo "Unsuccessful connection to database!";
                        echo $db->error().":(". $db->errno() .")";
                        exit();
                    }

                    echo "<section>";
                    echo "<div class='article__story'>";
                    echo "<div class='cat__wrapp'>";
                    echo "<h3><a class='category' href='news.php?category=".$row->category."'>".$row->c_name."</a></h3>";
                    echo "</div>";
                    echo "<h2><a class='title' href='article.php?id=".$row->id."'>".$row->title."</a></h2>";
            
                    echo "<p class='text'>$row->text</p><br>";

                    echo "<b><a class='author' href='news.php?autor=".$row->author."'>".$row->fullname."</a></b> <i>".$row->time."</i><br>";

                    echo "</div>";
                    echo "</section>";

                }
                
                echo "<section>";

/*************** writting comments */

                if(isset($_POST['comment']) && isset($_SESSION['full_name'])) {
                    $comment = mysqli_real_escape_string($db->connect(), $_POST['comment']);
                    $username = $_SESSION['full_name'];
                    $status = "";
                    if($_SESSION['status'] == "Administrator") {
                        $status = 1;
                    } else {
                        $status = 2;
                    }

                    if($comment != "") {
                        
                        $query = "SELECT * FROM vwcomments WHERE (username ='{$_SESSION['full_name']}' AND comment = '$comment')";
                        $result = $db->query($query);
                        if(mysqli_num_rows($result) == 0) {
                            $query = "INSERT INTO comments (username,status,article,comment) VALUE ('$username','$status','{$_GET['id']}','$comment')";
                            $result = $db->query($query);
        
                            $message = Message::success("Your comment is saved!");
                        }

                    } else {
                        $message = Message::info("Type your comment first!");     
                    }
                }

            ?>

<!------------ writting comments  HTML-->

            <div class="comments">
                <h3>Comments</h3>
                <form action="article.php?id=<?= $_GET['id']?>" method="post">
                    <textarea name="comment" id="comment"  rows="4" placeholder="Enter your comment"></textarea><br>
                    
                    <button>Save comment</button>
                    <p><?= $message?></p>
                </form>
            </div>

            <?php

/*************** show comments to ADMINISTRATOR */

                if ($_SESSION['status']  == 'Administrator') {

                    /**delete comments */
                
                    if(isset($_POST['delete_comment'])) {
                        if(!empty($_REQUEST['checkboxstatus'])) {
                            $checked_values = $_REQUEST['checkboxstatus'];
                            foreach($checked_values as $val) {
                                $querydel = "DELETE from comments WHERE id = '$val'";
                                $result = $db->query($querydel);
                            }
                        } else {
                            $messageSelect = Message::info("You didn't select anything");
                            
                        }
                    } 
            
/******************* read and check comments */

                    echo "<form class='comments__show-form' action='article.php?id=" . $_GET['id'] . "' method='post'>";
                        
                        $query = "SELECT * FROM vwcomments WHERE deleted=0 AND article= {$_GET['id']} ORDER BY id DESC";
                        $result = $db->query($query);
                        $i=1;
                        while($row = $db->fetch_object($result)) {
                            echo "<div class='comments__show--admin'>";
                            echo "<input type='checkbox' name='checkboxstatus[".$i."]' value=" . $row->id . ">";
                            echo "<div>";
                            echo "<p class='comment__text'>$row->comment</p>";
                            echo "<p class='comment__user'><b>$row->username</b> , <i><small>$row->time_add</small></i></p>";
                            echo "</div>";
                            echo "</div>";
                            $i++;
                        }

                        if($i > 1)
                        echo "<button type='submit' name='delete_comment'>Delete selected</button><br>";

                        if(isset($_POST['delete_comment']))
                        echo $messageSelect;
                    
                    echo "</form>";   
            
/*************** show comments to USER */

                } else if ($_SESSION['status']  == 'User') {

                    /** comment delete */

                    if(isset($_POST['user-comment-delete'])) {
                        $queryCommDel = "UPDATE comments SET deleted = 1 WHERE id=" . $_GET['comment'];
                        $resultCommDel = $db->query($queryCommDel);
                    }

                    /** comments show */

                    $query = "SELECT * FROM vwcomments WHERE deleted=0 AND article= {$_GET['id']} ORDER BY id DESC";
                    $result = $db->query($query);

                    while($row = $db->fetch_object($result)) {
                        echo "<div class='comments__show'>";
                        echo "<p class='comment__text'>$row->comment</p>";
                        echo "<p class='comment__user'><b>$row->username</b> , <i><small>$row->time_add</small></i></p>";

                        /** delete button */

                        if($row->username == $_SESSION['full_name']) {

                            echo "<form action='article.php?id=" . $_GET['id'] ."&comment=" . $row->id ."' method='post'>";
                            echo "<button class='delete__btn' type='submit' name='user-comment-delete'>Delete</button>";
                            echo "</form>";
        
                        }

                        echo "</div>";
 
                    }

                   

                   

                    

                }

                unset($db);
            ?>
            </section>
        </main>
        <?php include_once("_footer.php")?>
    </body>
</html>

