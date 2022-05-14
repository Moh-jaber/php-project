<?php
    session_start();
    require_once 'conn.php';
?>
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
    <link rel="stylesheet" href="css/menu.css">

</head>

<body>

    <?php 
    //---------------------------------------------add item------------------------------------------------
        if(isset($_POST['item-submit'])){

            $item_name = $_POST['item-name'];
            $item_price = $_POST['item-price'];
            $item_desc = $_POST['item-desc'];
            $item_rating = $_POST['rating'];
            $item_image = $_POST['item-image'];
            $categroy_id = $_SESSION['categoryid'];


            $insertitem = $crud->insertitem($item_name, $item_price, $item_desc, $item_rating, $item_image, $categroy_id);

            ?>
                <script>
                    console.log("inserting!!");
                </script>
            <?php


            if($insertitem){
                ?>
                    <script>
                        alert("Item added !");
                    </script>
                <?php
            }
            else{
                ?>
                    <script>
                        alert("Error !");
                        location = "item.php";
                    </script>
                <?php
            }

        }
        // ------------------------------------------------------end of add item-----------------------------------------------------
    ?>

    <!-- header section starts  -->

    <header>

        <a href="#" class="logo"><i class="fas fa-utensils"></i>Panadora</a>

        <div id="menu-bar" class="fas fa-bars"></div>

        <nav class="navbar">
            <a href="index.php">home</a>
            <a href="#speciality">Menu</a>
            <a href="Reservation.php">Reservation</a>
            <a href="about.php">About</a>
            <a href="index.php">review</a>
        </nav>
        <?php
        if (empty($_SESSION['status'])) {

        ?>
            <div>
                <a href="login.php" class="btn">Log In</a>
                <a href="signup.php" class="btn">Sign Up</a>
            </div>
        <?php } else {
        ?>
            <form action="index.php" method="POST">
                <input type="submit" value="Log out" name="signout-submit" class="btn">
            </form>
        <?php
        }

        ?>

    </header>

    <section>
        <div class="wrapper">
            <div class="title">
                <h4><span>Fresh food for good health </span>Our Menu</h4>
            </div>
            <div class="menu">
                <div class="single-menu">
                    <img src="images/breakfast.jpg" alt="Breakfast" class="image">
                    <div class="menu-content">
                        <form action="item.php" method="POST">
                            <input type="submit" id="breakfast" name="breakfast" value="Breakfast" class="btn">
                        </form>
                        <p>Make mornings good with a delicious breakfast.</p>
                    </div>

                </div>
                <div class="single-menu">
                    <img src="images/burger.jpg" alt="lunch" class="image">
                    <div class="menu_content">
                        <form action="item.php" method="POST">
                            <input type="submit" id="lunch" name="lunch" value="Lunch" class="btn">
                        </form>
                        <p>Make you'r day good with a delicious Lunch.</p>

                    </div>

                </div>
                <div class="single-menu">
                    <img src="images/g-1.jpg" alt="dinner" class="image">
                    <div class="menu_content">
                        <form action="item.php" method="POST">
                            <input type="submit" id="dinner" name="dinner" value="Dinner" class="btn">
                        </form>
                        <p>Food tastes better when you eat it with your family.</p>
                    </div>
                </div>
                <div class="single-menu">
                    <img src="images/g-9.jpg" alt="sweet" class="image">
                    <div class="menu_content">
                        <form action="item.php" method="POST">
                            <input type="submit" id="dessert" name="dessert" value="Dessert" class="btn">
                        </form>
                        <p>You can't buy happiness, but you can buy dessert.</p>
                    </div>
                </div>
                <div class="single-menu">
                    <img src="images/s-img-4.jpg" alt="drinks" class="image">
                    <div class="menu_content">
                        <form action="item.php" method="POST">
                            <input type="submit" id="drinks" name="drinks" value="Drinks" class="btn">
                        </form>
                        <p>Keep Calm and drink a cocktail.</p>
                    </div>
                </div>
                <div class="single-menu">
                    <img src="images/s-img-3.jpg" alt="ice" class="image">
                    <div class="menu_content">
                        <form action="item.php" method="POST">
                            <input type="submit" id="ice-cream" name="ice-cream" value="Ice-Cream" class="btn">
                        </form>
                        <p>A balanced diet is an ice cream in each hand.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <section class="footer">

        <div class="share">
            <a href="#" class="btn">facebook</a>
            <a href="#" class="btn">twitter</a>
            <a href="#" class="btn">instagram</a>
            <a href="#" class="btn">pinterest</a>
            <a href="#" class="btn">linkedin</a>
        </div>
        <div>
            <h1 class="credit"> Contact Us : <span> 71 271 156 </span></h1>
            <h1 class="credit"> Email : <span> Panadora.rest@gmail.com </span></h1>
            <h1 class="credit"> Aall rights reserved! </h1>

    </section>

</body>

</html>