<?php

session_start();
include 'conn.php';
$b = $crud->getuser();

$f = 0;
//Login check
if (isset($_POST['login-submit'])) { //if submit was clicked
    $emailLogin = $_POST['email'];
    $passwordLogin = $_POST['pass'];
    while ($a = $b->fetch(PDO::FETCH_ASSOC)) {
        if ($a['user_email'] == $emailLogin && $a['user_pass'] == $passwordLogin) {
            $_SESSION['user_name'] = $a['user_name'];
            $_SESSION['user_id'] = $a['user_id']; //if email and password exist we login and save the email and password in the session array
            $_SESSION['user_image'] = $a['user_image'];
            $_SESSION['status'] = 'on';
            $f++;
            break; //break with f>0
        } else {
            $f = 0;
        }
    }
    if ($f == 0) { //if wrong email or wrong password
?>
<script type="text/javascript">
alert("Wrong Username or Password");
location = "login.php";
</script>
<?php

    }
} //end of login



//sign up check
if (isset($_POST['signout-submit'])) {
    session_unset();
    session_destroy();
}

if (isset($_POST['signup-submit'])) {

    $name = $_POST['signup-name'];
    $email = $_POST['signup-email'];
    $password = $_POST['signup-pass'];
    $nb = $_POST['signup-nb'];
    $image = $_POST['signup-image'];

    $issuccess = true;
    while ($c = $b->fetch(PDO::FETCH_ASSOC)) {
        if ($c['user_email'] == $email) {
            $issuccess = false;
            break;
        } else {
            $issuccess = true;
        }
    }
    if (!$issuccess) {
    ?>
<script>
alert("Error: username or email already found");
location = "signup.php";
wait(1000);
</script>
<?php
    } else {
        $issuccess = $crud->insertuser($name, $nb, $email, $password, "user", $image);
        
        $target_dir = "images/";
        $im = $_FILES[$image]['tmp_name'];
        if(move_uploaded_file($im, $target_dir)){
            ?>
                <script>
                    alert("photo successfully uploaded");
                </script>
            <?php
        }
        
        if (!$issuccess) {
        ?>
<script>
alert("There was an error");
location = "signup.php";
</script>
<?php
        } else {
            while ($c = $b->fetch(PDO::FETCH_ASSOC)) {
                if ($c['user_name'] == $name && $c['user_email'] == $email && $c['user_pass'] == $password) {
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_image'] = $user_image;
                    $_SESSION['status'] = 'on';
                }
            }
        ?>
<?php
        }
    }
}
//end of sign up


//reservation
if (isset($_POST['reservation_submit'])) {
    $res_email = $_POST['reservation_email'];
    $res_nb = $_POST['reservation_phone'];
    $res_date = $_POST['reservation_date'];
    $res_suggestion = $_POST['reservation_suggestion'];
    $res_guest_nb = $_POST['nb_of_seats'];

    $flag = false;
    $sum = 0; //to calculate number of reserved places
    $insert_res = 0;


    $limit = $crud->getlimit();
    $reservations = $crud->getReservationTotal();
    $res_limit = $limit->fetch(PDO::FETCH_ASSOC); //$res_limit['res_limit'] contains the total number of reservation in the restaurant

    //checking if the reservation number > 8 (max allowed reservations is 8)
    if ($res_guest_nb > 8) {
        ?>
<script>
alert("Maximum number of reservations is 8!");
location = 'Reservation.php';
</script>
<?php
        $flag = true;
    }

    //loop to check if there is any places for the reservation
    while ($a = $reservations->fetch(PDO::FETCH_ASSOC)) {
        $sum += $a['nb_of_seats'];
    }

    //check if we can reserve a seat or not
    $available_seats = $res_limit['res_limit'] - $sum;
    if ($available_seats <  $res_guest_nb) {
        $flag = true;
    ?>
<script>
alert("Not enough places, please try changing the number of guests");
location = "Reservation.php";
</script>
<?php
    }

    // if flag stays false -->  user can reserve the seats he wants

    if ($flag == false) {
        $insert_res = $crud->insertreservation($_SESSION['user_id'], $res_date, $res_guest_nb, $res_suggestion);
        if (!$insert_res) {
        ?>
<script>
alert("There was an error");
location = "Reservation.php";
</script>
<?php
        } else {
        ?>
<script>
alert("Reservation success");
</script>
<?php
        }
    }
}


?>
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->
    <title> Panadora resturant </title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>

<body>

    <!-- header section starts  -->

    <header>

        <a href="#" class="logo"><i class="fas fa-utensils"></i>Panadora</a>

        <div id="menu-bar" class="fas fa-bars"></div>

        <nav class="navbar">
            <a href="#home">home</a>
            <a href="#speciality">Menu</a>
            <a href="Reservation.php">Reservation</a>
            <a href="#review">review</a>
            <a href="about.php">About</a>
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

    <!-- header section ends -->

    <!-- home section starts  -->

    <section class="home" id="home">

        <div class="content">
            <h3>Good food, Good mood</h3>
            <p>specializes in delicious food featuring fresh ingredients and masterful preparation by the
                <b>Panadora</b> culinary team. Whether you’re ordering a multi-course meal or grabbing a drink and pizza
                at home, <b>Panadora</b> lively, casual yet upscale atmosphere makes it perfect for dining with friends,
                family, clients and business associates!
            </p>
            <a href="signup.php" class="btn">Sign Up</a>

        </div>

        <div class="image">
            <img src="images/home-img.png" alt="">
        </div>

    </section>

    <!-- home section ends -->


    <!-- speciality section starts  -->

    <section class="speciality" id="speciality">

        <h1 class="heading"> our <span>domain</span> </h1>

        <div class="box-container">

            <div class="box">
                <a href="Menu.php"><img class="image" src="images/burger.jpg" alt=""></a>
                <div class="content">
                    <img src="images/s-1.png" alt="">
                    <h3>tasty burger</h3>
                    <p>
                        The best burgers offer a combination of tastes and textures – sweet, sour, salt – with a bit of
                        crunch. The patty is so juicy, the bun soft but sturdy. A quarter pound flattened piece of meat.
                    </p>
                </div>
            </div>
            <div class="box">
                <a href="Menu.php"><img class="image" src="images/pizza.jpg" alt=""></a>
                <div class="content">
                    <img src="images/s-2.png" alt="">
                    <h3>tasty pizza</h3>
                    <p>pizza, dish of Italian origin consisting of a flattened disk of bread dough topped with some
                        combination of olive oil, oregano, tomato, olives, mozzarella or other cheese, and many other
                        ingredients.</p>
                </div>
            </div>
            <div class="box">
                <a href="Menu.php"><img class="image" src="images/ice.jpg" alt=""></a>
                <div class="content">
                    <img src="images/s-3.png" alt="">
                    <h3>cold ice-cream</h3>
                    <p>a rich, sweet, creamy frozen food made from variously flavored cream and milk products churned or
                        stirred to a smooth consistency during the freezing process and often containing gelatin, etc..
                    </p>
                </div>
            </div>
            <div class="box">
                <a href="Menu.php"><img class="image" src="images/drinks.jpg" alt=""></a>
                <div class="content">
                    <img src="images/s-4.png" alt="">
                    <h3>cold drinks</h3>
                    <p>pizza, dish of Italian origin consisting of a flattened disk of bread dough topped with some
                        combination of olive oil, oregano, tomato, olives, mozzarella or other cheese, and many other
                        ingredients.</p>
                </div>
            </div>
            <div class="box">
                <a href="Menu.php"><img class="image" src="images/s-img-5.jpg" alt=""></a>
                <div class="content">
                    <img src="images/s-5.png" alt="">
                    <h3>tasty sweets</h3>
                    <p>Dissert ,such as biscuits, cakes, cookies, custards, gelatins, ice creams, pastries, pies,
                        puddings, macaroons, sweet soups, tarts, and fruit salad. contain cane sugar, palm sugar, brown
                        sugar,etc..</p>
                </div>
            </div>
            <div class="box">
                <a href="Menu.php"><img class="image" src="images/breakfast.jpg" alt=""></a>
                <div class="content">
                    <img src="images/s-6.png" alt="">
                    <h3>healty breakfast</h3>
                    <p>Breakfast brought a serene sense of comfort that started the day so well.Four eggs for breakfast,
                        fried in a brushing of olive oil, lightly salted... so perfect.Toast and peanut butter, a glass
                        of oat milk.</p>
                </div>
            </div>

        </div>
        <div>
            <a href="Menu.php" class="btn1">View Menu</a>
        </div>

    </section><br><br><br>

    <!-- speciality section ends -->


    <!-- steps section starts  -->

    <div class="step-container">

        <h1 class="heading">how it <span>works</span></h1>

        <section class="steps">

            <div class="box">
                <img src="images/step-1.jpg" alt="">
                <h3>choose your favorite food</h3>
            </div>
            <div class="box">
                <img src="images/step-2.jpg" alt="">
                <h3>free and fast delivery</h3>
            </div>
            <div class="box">
                <img src="images/step-3.jpg" alt="">
                <h3>easy payments methods</h3>
            </div>
            <div class="box">
                <img src="images/step-4.jpg" alt="">
                <h3>and finally, enjoy your food</h3>
            </div>

        </section>

    </div><br><br><br>

    <!-- steps section ends -->


    <!-- review section starts  -->

    <section class="review" id="review">

        <h1 class="heading"> popular <span>items</span> </h1>

        <div class="item-container">
            <div class="box">
                <span class="price"> $5 - $20 </span>
                <img src="images/p-1.jpg" alt="">
                <h3>tasty burger</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <a href="#" class="btn">order now</a>
            </div>

            <div class="box">
                <span class="price"> $5 - $20 </span>
                <img src="images/p-2.jpg" alt="">
                <h3>tasty cakes</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <a href="#" class="btn">order now</a>
            </div>

            <div class="box">
                <span class="price"> $5 - $20 </span>
                <img src="images/p-3.jpg" alt="">
                <h3>tasty sweets</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <a href="#" class="btn">order now</a>
            </div>
        </div><br><br><br><br><br>

        <h1 class="heading"> popular <span>clients</span> </h1>
        <div class="box-container">
            <div class="box">
                <img src="images/pic1.png" alt="">
                <h3>john deo</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Corrupti delectus, ducimus facere quod
                    ratione vel laboriosam? Est, maxime rem. Itaque.</p>
            </div>

            <div class="box">
                <img src="images/pic1.png" alt="">
                <h3>john deo</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Corrupti delectus, ducimus facere quod
                    ratione vel laboriosam? Est, maxime rem. Itaque.</p>
            </div>

            <div class="box">
                <img src="images/pic1.png" alt="">
                <h3>john deo</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Corrupti delectus, ducimus facere quod
                    ratione vel laboriosam? Est, maxime rem. Itaque.</p>
            </div>


        </div><br><br><br><br><br>
        <div>
            <a href="feedback.php" class="btn1">Your Feedback</a>
        </div>

    </section>

    <!-- review section ends -->

    <!-- order section starts  -->

    <!-- <section class="order" id="order">

    <h1 class="heading"> <span>order</span> now </h1>

    <div class="row">
        
        <div class="image">
            <img src="images/order-img.jpg" alt="">
        </div>

        <form action="">

            <div class="inputBox">
                <input type="text" placeholder="name">
                <input type="email" placeholder="email">
            </div>

            <div class="inputBox">
                <input type="number" placeholder="number">
                <input type="text" placeholder="food name">
            </div>

            <textarea placeholder="address" name="" id="" cols="30" rows="10"></textarea>

            <input type="submit" value="order now" class="btn">

        </form>

    </div>

</section> -->

    <!-- order section ends -->

    <!-- about section starts -->

    <section class="about" id="about">
        <h1 class="heading">Our <span>Story</span></h1>
        <div class="content">
            <p><b>Panadora</b> is your place to enjoy good food with the comfort and familiarity of home!
                <b>Panadora</b> was born out of a vision to provide you a simple yet warm and comfortable dining
                experience! We only strive to give you the warmest of welcome and service you truly deserve! We only
                serve the heartiest, healthiest dishes, lovingly prepared and generously served. Being at
                <b>Panadora</b> is being with family! <b>Panadora</b> is yours!
            </p>
        </div>

    </section>

    <!-- about section ends -->


    <!-- footer section  -->

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

    <!-- scroll top button  -->
    <a href="#home" class="fas fa-angle-up" id="scroll-top"></a>

    <!-- loader  -->
    <!-- <div class="loader-container">
    <img src="images/loader.gif" alt="">
</div> -->


    <!-- custom js file link  -->
    <script src="script.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->

</body>

</html>