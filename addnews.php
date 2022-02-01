<?php

session_start();
require_once("_require.php");

if(!admin_check()) {
    header("location: index.php");
}

$db = new Database();
if(!$db->connect()) throw new errorConnection();

$message="";
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

    <title>Add news</title>
    
</head>
<body>
    <?php include_once("_menu.php")?>
    
    <hr>
    <main>
        <section class="add__news">
            <h2 class="add__news-heading">Add news</h2>
            <form action="addnews.php" method="post">
                
                <select name="writer" id="writer">
                    <option value="0">Choose author</option>
                    <?php
                    try {
                        $query = "SELECT * FROM author_name";
                        $result = $db->query($query);
                        if(!$result) throw new errorQuery;
                        while($row = $db->fetch_object($result)) {
                            echo "<option value='{$row->id}'>{$row->fullname} </option>";
                        }
                    } catch (errorQuery $e) {
                        $e->message();
                    }
                    
                    ?>
                </select>
                <br>
                <br>
                <select name="category" id="category">
                    <option value="0">Choose category</option>
                    <?php
                    try {
                        $query = "SELECT * FROM categories";
                        $result = $db->query($query);
                        if(!$result) throw new errorQuery;
                        while($row = $db->fetch_object($result)) {
                            echo "<option value='{$row->id}'>{$row->c_name} </option>";
                        }
                    } catch (errorQuery $e) {
                        $e->message();
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
                    <textarea name="content" id="content" cols="40" rows="9"></textarea>
                </div>
                <button type="submit">Add article</button>

            </form>

        <?php
        try {

            if(isset($_POST['writer']) && isset($_POST['category']) && isset($_POST['headline']) && isset($_POST['content'])) {
                if($_POST['writer'] != "0" && $_POST['category'] != "0" && $_POST['headline'] != "" && $_POST['content'] != "") {
                    $writer = $_POST['writer'];
                    $category = $_POST['category'];
                    $headline = sanitize_str($_POST['headline']);
                    $headline = mysqli_real_escape_string($db->connect(), $headline);
                    $content = mysqli_real_escape_string($db->connect(), sanitize_str($_POST['content']));
    
                    $query = "INSERT INTO all_news (title, text, author, category) VALUES ('{$headline}', '{$content}', '{$writer}', '{$category}')";
                    $result = $db->query($query);
                    if(!$result) throw new errorQuery;
                    
                    $message = Message::success("Article is added successfully!");
                    echo "<br>" . $message;   
                    
                } else {
                    $message = Message::info("All fields are required!");
                    echo "<br>" . $message;   
                }
            }

        } catch (errorConnection $e){
            $e->message();
        } catch (errorQuery $e) {
            $e->message();
        }
        
        ?>
        </section>
    </main>
    <?php include_once("_footer.php")?>
</body>
</html>