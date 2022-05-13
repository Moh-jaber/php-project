<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>complete responsive food website design tutorial </title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">

</head>

<body>

    <header>

        <a href="#" class="logo"><i class="fas fa-utensils"></i>Panadora</a>

        <div id="menu-bar" class="fas fa-bars"></div>

        <nav class="navbar">
            <a href="index.php">home</a>
            <a href="Menu.php">Menu</a>
            <!--<a href="#popular">popular</a>        <a href="#order">order</a>-->
            <a href="index.php">About</a>
            <a href="index.php">review</a>
        </nav>


    </header>


    <div class="main">

        <!-- Sign up form -->
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <div class="signup-form">
                        <h2 class="logo">Sign up</h2>
                        <form action="index.php" method="POST" class="register-form" id="register-form">
                            <div class="form-group">
                                <label for="image"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="file" name="signup-image" id="image" />
                            </div>
                            <div class="form-group">
                                <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="signup-name" id="name" placeholder="Your Name" />
                            </div>
                            <div class="form-group">
                                <label for="number"><i class="zmdi zmdi-email"></i></label>
                                <input type="text" name="signup-nb" id="number" placeholder="Your Number" />
                            </div>
                            <div class="form-group">
                                <label for="email"><i class="zmdi zmdi-email"></i></label>
                                <input type="email" name="signup-email" id="email" placeholder="Your Email" />
                            </div>
                            <div class="form-group">
                                <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="signup-pass" id="pass" placeholder="Password" />
                            </div>
                            <div class="form-group">
                                <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>
                                <input type="password" name="re_pass" id="re_pass"
                                    placeholder="Confirm your password" />
                            </div>

                            <div class="form-group">
                                <input type="submit" value="register" name="signup-submit" class="btn">
                            </div>
                        </form>
                    </div>
                    <div class="signup-image">
                        <figure><img src="images/p-2.jpg" alt="sing up image"></figure>
                        <a href="index.php" class="btn">I am already member</a>
                    </div>
                </div>
            </div>
        </section>

    </div>
    <section class="footer">

        <div class="share">
            <a href="#" class="btn">facebook</a>
            <a href="#" class="btn">twitter</a>
            <a href="#" class="btn">instagram</a>
            <a href="#" class="btn">pinterest</a>
            <a href="#" class="btn">linkedin</a>
        </div>

        <h1 class="credit"> created by <span> mr. web designer </span> | all rights reserved! </h1>

    </section>

    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>