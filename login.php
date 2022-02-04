<?php

session_start();
require_once("_require.php");

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

        <title>Sign in</title>
        

    </head>
    <body>
        <?php include_once("_menu.php")?>
        <hr>
        <main>
        
            <section class="login">
                <div class="signin__wrapp">
                    <h2 class="signin__title">Sign in </h2>
                    <form action="login.php" method="post">
                        <input class="email" type="text" name="username" placeholder="enter E-mail or Username" required><br><br>
                        <input class="pass" type="password" name="password" placeholder="enter Password" required><br><br>
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember me</label><br><br>
                        <button type="submit" name="submit">Sign in</button>
                    </form>
                </div>
                
                
                <?php

                try {
                    $message="";

                    $db = new Database();
                    if(!$db->connect()) throw new errorConnection();

                    if(isset($_POST['submit'])) {
                        if(isset($_POST['username']) and isset($_POST['password'])) {
                                if(strpos($_POST['username'], "@")) {
                                    if(filter_email($_POST['username'])) {
                                        $username = sanitize_email($_POST['username']);
                                        } else {
                                        echo $message = Message::error_message("Email is invalid") ;
                                    }
                                } else {
                                    $username = sanitize_str($_POST['username']);
                                }
                            
                    
                            
                            if(strlen($_POST['password']) < 30) {
                                $password = $_POST['password'];
    
                            }
                            else {
                                echo $message = Message::error_message("Password name is too long!");
                                exit();
                            }
    
                            if($username != "" or $password != "") {
                                
                                    $query = "SELECT * FROM vwusers WHERE email='$username' OR username='$username'";
                                    
                                    $result = $db->query($query);
                                    
                                    

                                    if(!$result) throw new errorQuery;

                                    if($db->num_rows($result) == 1) {
                                        $row = $db->fetch_object($result);
                                            if($row->password == $password) {
                                                $_SESSION['id'] = $row->id;
                                                $_SESSION['full_name'] = $row->u_name . " " . $row->u_lastname;
                                                $_SESSION['status'] = $row->status_name;
    
                                                if(isset($_POST['remember'])) {
                                                    setcookie("id", $_SESSION['id'], time()+86400, "/");
                                                    setcookie("id", $_SESSION['full_name'], time()+86400, "/");
                                                    setcookie("id", $_SESSION['status'], time()+86400, "/");
                                                }
    
                                                header("location: index.php");
                                            } else {
                                                $message = Message::error_message("Wrong password for user '{$username}'");
    
                                            }
                                        
                                    } else {
                                        $message = Message::info("Your email or password is not correct.");
                                    }
                            } else $message = Message::info("All fields are required");
                        } 
                    } 
                } catch (errorConnection $e){
                    $e->message();
                } catch (errorQuery $e) {
                    $e->message();
                }
                
                ?>
                <p><?= $message?></p>
                <p>If you don't have an account yet-> <br><a href='register.php'>Create Account</a></p>

            </section>

          
        </main>
        <?php include_once("_footer.php")?>

    </body>
</html>

