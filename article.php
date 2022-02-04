<?php

session_start();
require_once("_require.php");

if(!login_check()) {
    header("location: index.php");
}

$message = "";
$messageSelect = "";
$messageDel = "";

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
                try { 

                $db = new Database();
                if(!$db->connect()) throw new errorConnection();

/*************** select from db */
                if(isset($_GET['id'])) {
                    $id = $_GET['id'];
                    filter_int($id);
                    $query = "SELECT * FROM vwall_news where deleted=0 and id=".$id;
                    
                }

                $result = $db->query($query);
                $rowsnum = $db->num_rows($result);
                
                if(!$result || !$rowsnum) {
                    throw new errorQuery;
                    exit();
                }
                
                $row=$db->fetch_object($result);

/*************** back and delete links under header */

                echo "<div class='addnews' style='margin-bottom: 25px';>";
                echo "<a class='news__link news__link--left' href='news.php'>Back to all news</a>";
                echo "<a class='news__link news__link--del' href='article.php?id=" . $row->id . "&delete=" . $row->id ."'>Delete this article</a>";
                echo "</div>";
            
                if(isset($_GET['delete'])) {
                    $delete = $_GET['delete'];
                    filter_int($delete);
                    if($delete != $id) {
                        exit();
                    }
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
                    if(!$result) throw new errorQuery;
                    header('location: news.php');

                }

/***************  showing the article  */

                if(isset($_GET['id'])) {
                    $query = "SELECT * FROM vwall_news WHERE deleted = 0 AND id=" . $_GET['id'];

                    $result = $db->query($query);
                    if(!$result) throw new errorQuery;
                    $row=$db->fetch_object($result);

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
                    $comment = sanitize_str($_POST['comment']);
                    $comment = mysqli_real_escape_string($db->connect(), $comment);
                    
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
                } catch (errorConnection $e){
                    $e->message();
                } catch (errorQuery $e) {
                    $e->message();
                }

            ?>

<!------------ writting comments  HTML-->

            <div class="comments">
                <h3>Comments</h3>
                <form action="article.php?id=<?= $_GET['id']?>" method="post">
                    <textarea name="comment" id="comment"  rows="4" placeholder="Enter your comment"></textarea><br>
                    
                    <button>Save comment</button>
                    <p><?php
                    
                    echo $message;
                    

                    ?></p>
                </form>
            </div>

            <?php
                try {
                    /*************** show comments to ADMINISTRATOR */

                if ($_SESSION['status']  == 'Administrator') {

                    /**delete comments */
                
                    if(isset($_POST['delete_comment'])) {
                        if(!empty($_REQUEST['checkboxstatus'])) {
                            $checked_values = $_REQUEST['checkboxstatus'];
                            foreach($checked_values as $val) {
                                $querydel = "DELETE from comments WHERE id = '$val'";
                                $result = $db->query($querydel);
                                if(!$result) throw new errorQuery;
                            }
                        } else {
                            $messageSelect = Message::info("You didn't select anything");
                            
                        }
                    } 
            
/******************* read and check comments */
                    
                    echo "<form class='comments__show-form' action='article.php?id=" . $id . "' method='post'>";
                        
                        $query = "SELECT * FROM vwcomments WHERE deleted=0 AND article=$id ORDER BY id DESC";
                        $result = $db->query($query);
                        filter_int($id);
                        if(!$result) throw new errorQuery;
                        $i=1;
                        while($row = $db->fetch_object($result)) {
                            echo "<div class='comments__show--admin'>";
                            echo "<input type='checkbox' name='checkboxstatus[".$i."]' value=" . $row->id . ">";
                            echo "<div class='comment__wrapp'>";
                            echo "<p class='comment__text comment__text--admin'>$row->comment</p>";
                            echo "<p class='comment__user comment__user--admin'><b>$row->username</b> , <i><small>$row->time_add</small></i></p>";
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
                        $messageDel = Message::info("Your comment is deleted");
                         echo $messageDel; 
                    }

                    /** comments show */

                    $query = "SELECT * FROM vwcomments WHERE deleted=0 AND article= {$_GET['id']} ORDER BY id DESC";
                    $result = $db->query($query);
                    if(!$result) throw new errorQuery;

                    while($row = $db->fetch_object($result)) {
                        echo "<div class='comments__show'>";
                        echo "<p class='comment__text'>$row->comment</p>";
                        echo "<div class='comm-del-wrapp'>";
                        echo "<p class='comment__user'><b>$row->username</b> , <i><small>$row->time_add</small></i></p>";

                        /** delete button */

                        if($row->username == $_SESSION['full_name']) {

                            echo "<form action='article.php?id=" . $_GET['id'] ."&comment=" . $row->id ."' method='post'>";
                            echo "<input type='submit' class='delete__btn' name='user-comment-delete' value='delete my comment'/>";
                            echo "</form>";
        
                        }
                        echo "</div>";
                        echo "</div>";
 
                    }

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

