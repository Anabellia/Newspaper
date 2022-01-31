<header class="header">
    <a href="index.php">
    <img class="header__logo"src="img/logo.png" alt="logo">

    </a>
            <nav>
                <ul class="header__nav">
                <?php
                    if(login()) {
                        echo "<li class='nav__item'>
                            <a class='nav__link' href='index.php'>Home</a>
                        </li>";
                        echo "<li class='nav__item'>
                            <a class='nav__link' href='news.php'>News</a>
                        </li>";
                        echo "<li class='nav__item'>
                            <a class='nav__link' href='logout.php'>Logout</a>
                        </li>";

                        $name = $_SESSION['full_name'];
                        $firstname = explode(" ",$name);
                        echo "<li class='nav__item nav__item--border'>
                            {$firstname[0]}({$_SESSION['status']})
                            </li>";
                    } else {
                        echo "<li class='nav__item'>
                            <a class='nav__link' href='login.php'>Sign in</a>
                        </li>";
                        echo "<li class='nav__item'>
                            <a class='nav__link' href='register.php'>Register</a>
                        </li>";
                    }
                    ?>
                    
                </ul>
            </nav>
        </header>