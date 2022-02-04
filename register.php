<?php

session_start();
require_once("_require.php");

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

    <title>Register</title>
</head>
<body>
<?php include_once("_menu.php")?>
    <hr>
    <main>
        <section class="login login--reg"> 
        <div class="signin__wrapp signin__wrapp--reg">
            <h2 class="signin__title">Register</h2>
            <form action="register.php" method="post">
                <input type="text" name="rfirstName" placeholder="First Name" class="input">
                <br>
                <input type="text" name="rlastName" placeholder="Last Name" class="input">
                <br>
                <input type="text" name="rusername" placeholder="Username" class="input">
                <br>
                <input type="email" name="remail" placeholder="Email" class="input">
                <br>
                <input type="password" name="rpassword" placeholder="Password" class="input">
                <br>
                <input type="password" name="rpasswordConf" placeholder="Confirm Password" class="input">
                <br>
                <select name="rstatus" id="rstatus">
                <?php
                $query = "SELECT * FROM status ORDER BY id DESC";
                $result = $db->query($query);
                while ($row = $db->fetch_object($result)){
                    echo "<option value='$row->id'>$row->status_name</option>";
                }

                ?>
                </select>
                <br>
                <br>
                <button type="submit" name="rsubmit">Register</button>
            </form>
        </div>
        <?php

        try {
            if(isset($_POST['rsubmit'])) {

                if(isset($_POST['rfirstName']) && isset($_POST['rlastName']) && isset($_POST['rusername']) && isset($_POST['remail']) && isset($_POST['rpassword']) && isset($_POST['rpasswordConf']) && isset($_POST['rstatus'])) {

                    if($_POST['rfirstName'] != "" or $_POST['rlastName'] != "" or $_POST['rusername'] != "" or $_POST['remail'] != "" or $_POST['rpassword'] != "" or $_POST['rpasswordConf'] != "") {

                        if(strlen($_POST['rfirstName']) < 15) {
                            $rfirstName = sanitize_str($_POST['rfirstName']);

                        } else {
                            $message = Message::error_message("First name is too long!");
                            exit();
                        }

                        if(strlen($_POST['rlastName']) < 15) {
                            $rlastName = sanitize_str($_POST['rlastName']);

                        }
                        else {
                            $message = Message::error_message("Last name is too long!");
                            exit();
                        }

                        if(strlen($_POST['rusername']) < 15) {
                            $rusername = sanitize_str($_POST['rusername']);

                        }
                        else {
                            $message = Message::error_message("Username name is too long!");
                            exit();
                        }
                        if(filter_email($_POST['remail'])) {
                            $remail = sanitize_email($_POST['remail']);
                        } else {
                            $message = Message::error_message("Email or password are invalid");
                        }
                        


                        if($_POST['rpassword'] === $_POST['rpasswordConf']) {
                            if(strlen($_POST['rpassword']) < 30) {
                                $rpassword = $_POST['rpassword'];
                                $rpasswordConf = $_POST['rpasswordConf'];

                            } else {
                                $message = Message::error_message("Password is too long!");
                            }
                        } else {
                            $message = Message::error_message("Passwords don't match!");
                        }
                        if(filter_int($_POST['rstatus']))
                        $rstatus = $_POST['rstatus'];

                        $query = "INSERT INTO users(`u_name`, `u_lastname`, `username`, `email`, `password`, `status`) VALUES ('$rfirstName', '$rlastName', '$rusername', '$remail', '$rpassword', '$rstatus')";
                        $result = $db->query($query);

                        if(!$result) throw new errorQuery; 
                        else {
                            $message = Message::success("Your regestration is successful!");
                        }
                    }     
                }
            }
        } catch (errorConnection $e) {
            $e->message();
        } catch (errorQuery $e) {
            $e->message();
        }
                
        ?>
        <p><?= $message?></p>
        </section> 
    </main>
<?php include_once("_footer.php")?> 
</body>
</html>