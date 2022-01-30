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
    <title>Add news</title>
    <link href="css/news.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<?php include_once("_menu.php")?>
<hr>
    <?php
        if(login()) {
            if($_SESSION['status'] != "Administrator") {
                header("location: index.php");

            }
        }

        
    ?>
    <section class="section">
        <h2>Add news</h2>
        <form action="addnews.php" method="post">
            
            <select name="writer" id="writer">
                <option value="0">Choose author</option>
                <?php
                $query = "SELECT * FROM author_name";
                $result = $db->query($query);
                while($row = $db->fetch_object($result)) {
                    echo "<option value='{$row->id}'>{$row->fullname} </option>";
                }
                ?>
            </select>
            <br>
            <br>
            <select name="category" id="category">
                <option value="0">Choose category</option>
                <?php
                $query = "SELECT * FROM categories";
                $result = $db->query($query);
                while($row = $db->fetch_object($result)) {
                    echo "<option value='{$row->id}'>{$row->c_name} </option>";
                }
                ?>
            </select>
            <br>
            <br>
            <label for="headline">Enter headline:</label>
            <div>
                <textarea name="headline" id="headline" cols="40" rows="2"></textarea>
            </div>
            <br>
          
            <label for="content">Enter text:</label>
            <div>
                <textarea name="content" id="content" cols="40" rows="10"></textarea>
            </div>
            <button type="submit">Add article</button>
            

        </form>
    

    <?php
    if(isset($_POST['writer']) && isset($_POST['category']) && isset($_POST['headline']) && isset($_POST['content'])) {
        if($_POST['writer'] != "0" && $_POST['category'] != "0" && $_POST['headline'] != "" && $_POST['content'] != "") {
            $writer = $_POST['writer'];
            $category = $_POST['category'];
            $headline = mysqli_real_escape_string($db->connect(), $_POST['headline']);
            $content = mysqli_real_escape_string($db->connect(), $_POST['content']);
            echo $content;

            $query = "INSERT INTO all_news (title, text, author, category) VALUES ('{$headline}', '{$content}', '{$writer}', '{$category}')";
            $result = $db->query($query);
            
            $message = "Article is added successfully";
            echo "<br>" . $message;   
             
        } else {
            $message = "All fields are required!";
            echo "<br>" . $message;   
        }
    }
    ?>
    </section>
    <?php include_once("_footer.php")?>
    
</body>
</html>