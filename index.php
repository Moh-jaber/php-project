<?php

session_start();
include 'conn.php';
$b = $crud->getuser();

$getcurrencyfromres = $crud->getcurrencyfromres();


$getcurrency = $crud->getcurrency();

while ($a = $getcurrency->fetch(PDO::FETCH_ASSOC)) {
    if ($getcurrencyfromres['currency_id'] == $a['currency_id']) {
        $_SESSION['currency'] = $a['currency_name'];
        $_SESSION['currency-rate'] = $a['currency_rate'];
        $_SESSION['currency-sym'] = $a['currency_symbol'];
        break;
    }
}


$f = 0;
//-------------------------------------------------------------------Login check-------------------------------------------------
if (isset($_POST['login-submit'])) { //if submit was clicked
    $emailLogin = $_POST['email'];
    $passwordLogin = $_POST['pass'];
    while ($a = $b->fetch(PDO::FETCH_ASSOC)) {
        if ($a['user_email'] == $emailLogin && $a['user_pass'] == $passwordLogin) {
            $_SESSION['user_name'] = $a['user_name'];
            $_SESSION['user_id'] = $a['user_id'];
            $_SESSION['user_image'] = $a['user_image'];
            $_SESSION['user_type'] = $a['user_type'];
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
} //----------------------------------------------------------end of login------------------------------------------------------------



//---------------------------------------------------------------sign up check-----------------------------------------------------------
if (isset($_POST['signout-submit'])) {
    session_unset();
    session_destroy();
}

if (isset($_POST['signup-submit'])) {

    $fileNameNew = false;

    $file = $_FILES['file'];

    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            $fileNameNew = uniqid('', true) . "." . $fileActualExt;
            $fileDestination = 'images/userImages/' . $fileNameNew;
            move_uploaded_file($fileTmpName, $fileDestination);
        ?>
            <script>
                alert("image uploaded successfully !");
            </script>
        <?php
        } else {
        ?>
            <script>
                alert("There was an error uploading the image !");
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            alert("you cannot upload files of this type !");
        </script>
        <?php
    }

    if ($fileNameNew) {


        $name = $_POST['signup-name'];
        $email = $_POST['signup-email'];
        $password = $_POST['signup-pass'];
        $nb = $_POST['signup-nb'];
        $image = $fileNameNew;

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
}
//-----------------------------------------------------------------end of sign up-------------------------------------------------


//------------------------------------------------------------reservation---------------------------------------------------------
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
} //-------------------------------------------------------------end of reservation-------------------------------------------------


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
    <script>
        console.log(' <?php echo $_SESSION['currency']; ?> ');
        console.log(' <?php echo $_SESSION['currency-rate']; ?> ');
    </script>

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
            <?php
            if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === "superAdmin") {
            ?>
                <div class="btn" onclick="myFunction3()" style="padding: 10px;">Add Admin
                    <span class="popuptext" id="myPopup"></span>
                </div>

                <div class="PopupScreen3">
                    <form class="Design" action="index.php" method="POST" enctype="multipart/form-data">
                        <div class="c-logo">
                            <a href="#" class="logo"><i class="fas fa-utensils"></i>
                                <span style="font-size: 20px ; font-weight: bolder; color:#666;"> Panadora</span>
                            </a>
                        </div>

                        <div class="inputBox content">

                            <label for="adminImage">Image:</label>
                            <input type="file" id="adminImage" name="adminImage" class="input">

                            <label for="adminName">Name:</label>
                            <input type="text" id="adminName" name="adminName" placeholder="Name" class="input">

                            <label for="adminNb">Phone:</label>
                            <input type="number" id="adminNb" name="adminNb" placeholder="Number" class="input">

                            <label for="email">Email:</label>
                            <input type="email" id="adminEmail" name="adminEmail" placeholder="email" class="input">

                            <label for="adminPass">Password:</label>
                            <input type="password" id="adminPass" name="adminPass" placeholder="Password" class="input">

                            <label for="adminConfirmPass">Confirm Password:</label>
                            <input type="password" id="adminConfirmPass" name="adminConfirmPass" placeholder="Confirm Password" class="input">

                        </div>
                        <div class="btns">
                            <input type="submit" value="Add" name="addAdmin-submit" class="btn2" style="margin-right:auto; margin-left:1rem">
                            <input type="button" value="Close" class="btn2" style="margin-left:auto; margin-right:1rem" onclick="myFunction3()">
                        </div>


                    </form>
                </div>
                <?php
            }

            // -----------------------------------------------------adding an admin-----------------------------------------------
            if (isset($_POST['addAdmin-submit'])) {

                $file = $_FILES['adminImage'];

                $fileName = $_FILES['adminImage']['name'];
                $fileTmpName = $_FILES['adminImage']['tmp_name'];
                $fileSize = $_FILES['adminImage']['size'];
                $fileError = $_FILES['adminImage']['error'];
                $fileType = $_FILES['adminImage']['type'];

                $fileExt = explode('.', $fileName);
                $fileActualExt = strtolower(end($fileExt));

                $allowed = array('jpg', 'jpeg', 'png');

                if (in_array($fileActualExt, $allowed)) {
                    if ($fileError === 0) {

                        $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                        $fileDestination = 'images/userImages/' . $fileNameNew;
                        move_uploaded_file($fileTmpName, $fileDestination);
                ?>
                        <script>
                            alert("image uploaded successfully !");
                        </script>
                    <?php
                    } else {
                    ?>
                        <script>
                            alert("There was an error uploading the image !");
                        </script>
                    <?php
                    }
                } else {
                    ?>
                    <script>
                        alert("you cannot upload files of this type !");
                    </script>
                    <?php
                }

                if ($fileNameNew) {

                    $admin_name = $_POST['adminName'];
                    $admin_nb = $_POST['adminNb'];
                    $admin_email = $_POST['adminEmail'];
                    $admin_image = $fileNameNew;
                    $admin_password = $_POST['adminPass'];

                    $addAdmin = $crud->insertuser($admin_name, $admin_nb, $admin_email, $admin_password, 'admin', $admin_image);

                    if (!$addAdmin) {
                    ?>
                        <script>
                            alert("admin not added");
                        </script>
                    <?php
                    } else {
                    ?>
                        <script>
                            alert("Done!");
                            location = "index.php";
                        </script>
            <?php
                    }
                }
            }
            ?>

        </div>

        <div class="image">
            <img src="images/home-img.png" alt="">
        </div>

    </section>

    <!-- home section ends -->

    <!-- offers section starts -->

    <section class="offers-events">
        <h1 class="heading"> our <span>Offers</span></h1>

        <?php
        if (!empty($_SESSION['user_type']) && ($_SESSION['user_type'] === "admin" || $_SESSION['user_type'] === "superAdmin")) {
        ?>
            <div class="btn1" onclick="myFunction()">Add Offer
                <span class="popuptext" id="myPopup"></span>
            </div><br><br>

        <?php
        }
        ?>

        <?php

        //---------------------------------------------------add offer----------------------------------------------
        if (isset($_POST['offer-submit'])) {

            $file = $_FILES['offer-image'];

            $fileName = $_FILES['offer-image']['name'];
            $fileTmpName = $_FILES['offer-image']['tmp_name'];
            $fileSize = $_FILES['offer-image']['size'];
            $fileError = $_FILES['offer-image']['error'];
            $fileType = $_FILES['offer-image']['type'];

            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));

            $allowed = array('jpg', 'jpeg', 'png');

            if (in_array($fileActualExt, $allowed)) {
                if ($fileError === 0) {

                    $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                    $fileDestination = 'images/offerImages/' . $fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
        ?>
                    <script>
                        alert("image uploaded successfully !");
                    </script>
                <?php
                } else {
                ?>
                    <script>
                        alert("There was an error uploading the image !");
                    </script>
                <?php
                }
            } else {
                ?>
                <script>
                    alert("you cannot upload files of this type !");
                </script>
                <?php
            }

            if ($fileNameNew) {

                $off_name = $_POST['offer-name'];
                $off_desc = $_POST['offer-description'];
                $off_image = $fileNameNew;
                $off_endtime = $_POST['offer-endtime'];
                $off_percent = $_POST['offer-percentage'];

                $insert_offer = $crud->insertoffer($off_name, $off_desc, $off_percent, $off_endtime, $off_image);

                if (!$insert_offer) {
                ?>
                    <script>
                        alert("Offer not added");
                    </script>
                <?php
                } else {
                ?>
                    <script>
                        alert("Done!");
                        location = "index.php";
                    </script>
        <?php
                }
            }
        }
        ?>
        <div class="item-container">
            <?php

            $of = $crud->getoffer();
            while ($fda = $of->fetch(PDO::FETCH_ASSOC)) {
                $databasename = $fda['offer_name'];
                $databasedesc = $fda['offer_desc'];
                $databaseimage = $fda['offer_image'];
                $databasepercent = $fda['offer_percentage'];
                $databaseendtime = $fda['offer_end_time'];

            ?>
                <div class="box">
                    <span class="price"> <?php echo "$databasepercent %" ?> </span>
                    <img <?php echo '<img alt=error" src="images/offerImages/' . $databaseimage . '"' ?>>
                    <h3><?php echo $databasename ?></h3>
                    <h4><?php echo $databasedesc ?></h4>
                    <h5><?php echo " ENDS AT: $databaseendtime " ?></h5>
                </div>
            <?php
            } // ------------------------------------------------end of add offer----------------------------------------------
            ?>

        </div>

        <div class="PopupScreen ">
            <form class="Design" action="index.php" method="POST" enctype="multipart/form-data">
                <div class="c-logo">
                    <a href="#" class="logo"><i class="fas fa-utensils"></i>
                        <span style="font-size: 20px ; font-weight: bolder;
                    color:#666;"> Panadora</span>
                    </a>
                </div>

                <div class="inputBox content">

                    <label for="offer-name"> Name:</label>
                    <input type="text" id="offername" name="offer-name" placeholder="Offer name" class="input">

                    <label for="offer-percentage">Percentage:</label>
                    <input type="number" min="1" max="100" id="offerpercentage" name="offer-percentage" placeholder="Offer percentage " class="input">

                    <label for="offer-endtime">End time:</label>
                    <input type="date" id="offerendtime" name="offer-endtime" placeholder="Offer end time " class="input">

                    <label for="offer-description">Description:</label>
                    <textarea placeholder="Write your description here:" style="resize: none;" name="offer-description" id="offerdescription" cols="30" rows="5"></textarea>

                    <label for="offer-image"></label>
                    <input type="file" name="offer-image" id="offerimage" />

                </div>

                <div class="btns">
                    <input type="submit" value="Add" name="offer-submit" class="btn2" style="margin-right: 1rem;margin-left: 0.5rem;">
                    <input type="button" value="Close" class="btn2" style="margin-left: 10rem;" onclick="myFunction()">
                </div>


            </form>
        </div><br><br>

        <h1 class="heading"> our <span>Events</span></h1>

        <?php
        if (!empty($_SESSION['user_type']) && ($_SESSION['user_type'] === "admin" || $_SESSION['user_type'] === "superAdmin")) {
        ?>

            <div class="btn1" onclick="myFunction2()">Add Event
                <span class="popuptext" id="myPopup"></span>
            </div><br><br>
        <?php
        }
        ?>

        <div class="PopupScreen2 ">
            <form class="Design" action="index.php" method="POST" enctype="multipart/form-data">
                <div class="c-logo">
                    <a href="#" class="logo"><i class="fas fa-utensils"></i>
                        <span style="font-size: 20px ; font-weight: bolder;
                color:#666;"> Panadora</span>
                    </a>
                </div>


                <div class="inputBox content">

                    <label for="event-name"> Name:</label>
                    <input type="text" id="eventname" name="event-name" placeholder="Event name" class="input">

                    <label for="event-description">Description:</label>
                    <textarea placeholder="Write your description here:" style="resize: none;" name="event-description" id="eventdescription" cols="30" rows="5"></textarea>

                    <label for="image"></label>
                    <input type="file" name="event-image" id="eventimage" />

                </div>

                <div class="btns">
                    <input type="submit" value="Add" name="event-submit" class="btn2" style="margin-right: 1rem;margin-left: 1rem;">
                    <input type="button" value="Close" class="btn2" style="margin-left: 10rem;" onclick="myFunction2()">
                </div>


            </form>
        </div>
        <?php
        //---------------------------------------------------------add event----------------------------------------------
        if (isset($_POST['event-submit'])) {

            $fileNameNew = false;

            $file = $_FILES['event-image'];

            $fileName = $_FILES['event-image']['name'];
            $fileTmpName = $_FILES['event-image']['tmp_name'];
            $fileSize = $_FILES['event-image']['size'];
            $fileError = $_FILES['event-image']['error'];
            $fileType = $_FILES['event-image']['type'];

            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));

            $allowed = array('jpg', 'jpeg', 'png');

            if (in_array($fileActualExt, $allowed)) {
                if ($fileError === 0) {

                    $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                    $fileDestination = 'images/eventImages/' . $fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
        ?>
                    <script>
                        alert("image uploaded successfully !");
                    </script>
                <?php
                } else {
                ?>
                    <script>
                        alert("There was an error uploading the image !");
                    </script>
                <?php
                }
            } else {
                ?>
                <script>
                    alert("you cannot upload files of this type !");
                </script>
                <?php
            }

            if ($fileNameNew) {

                $ev_name = $_POST['event-name'];
                $ev_desc = $_POST['event-description'];
                $ev_image = $fileNameNew;

                $insert_event = $crud->insertevent($ev_name, $ev_desc, $ev_image);

                if (!$insert_event) {
                ?>
                    <script>
                        alert("event not added");
                    </script>
                <?php
                } else {
                ?>
                    <script>
                        alert("Done!");
                        location = "index.php";
                    </script>
        <?php
                }
            }
        }
        ?>
        <div class="item-container">
            <?php

            $e = $crud->getevent();
            while ($fda = $e->fetch(PDO::FETCH_ASSOC)) {
                $databasename = $fda['event_name'];
                $databasedesc = $fda['event_desc'];
                $databaseimage = $fda['event_image'];

            ?>
                <div class="box">
                    <img <?php echo '<img alt="Error" src="images/eventImages/' . $databaseimage . '"' ?>>
                    <h3><?php echo $databasename ?></h3>
                    <h4><?php echo $databasedesc ?></h4>
                </div>
            <?php
            } //--------------------------------------------end of add event---------------------------------------------------------
            ?>

        </div>

    </section>
    <!-- offers section ends -->

    <!-- speciality section starts  -->

    <section class="speciality" id="speciality">

        <h1 class="heading"> our <span>Categories</span> </h1>

        <div class="item-container">
            <div class="box">
                <img src="images/breakfast.jpg" style="box-shadow:0px 0px 10px #666666" alt="">
                <h3>Breakfast</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <a href="Menu.php" class="btn">Check it out !</a>
            </div>

            <div class="box">
                <img src="images/burger.jpg" alt="">
                <h3>Lunch</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <a href="Menu.php" class="btn">Check it out !</a>
            </div>

            <div class="box">
                <img src="images/g-1.jpg" alt="">
                <h3>Dinner</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <a href="Menu.php" class="btn">Check it out !</a>
            </div>


            <div class="box">
                <img src="images/g-9.jpg" alt="">
                <h3>Dessert</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <a href="Menu.php" class="btn">Check it out !</a>
            </div>

            <div class="box">
                <img src="images/s-img-4.jpg" alt="">
                <h3>Drinks</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <a href="Menu.php" class="btn">Check it out !</a>
            </div>

            <div class="box">
                <img src="images/s-img-3.jpg" alt="">
                <h3>Ice-Cream</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <a href="Menu.php" class="btn">Check it out !</a>
            </div>

        </div>
        <br><br>
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
                <a href="Menu.php" class="btn">Check it out !</a>
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
                <a href="Menu.php" class="btn">Check it out !</a>
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
                <a href="Menu.php" class="btn">Check it out !</a>
            </div>
        </div><br><br><br><br><br>




        <h1 class="heading"> popular <span>clients</span> </h1>
        <div class="box-container">
            <div class="box">
                <img src="images/pic1.png" alt="">
                <h3>Lara White</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <p> This cozy restaurant has left the best impressions! Hospitable hosts, delicious dishes, beautiful
                    presentation, and wonderful dessert. I recommend to everyone! I would like to come back here again
                    and again.</p>
            </div>

            <div class="box">
                <img src="images/pic2.png" alt="">
                <h3>Sami Faour</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <p> It’s a great experience. The ambiance is very welcoming and charming. Amazing wines, food and
                    service. Staff are extremely knowledgeable and make great recommendations.</p>
            </div>

            <div class="box">
                <img src="images/pic3.png" alt="">
                <h3>Aya Darwich</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <p>This place is great!You can tell making the customers happy is their main priority. Food is pretty
                    good, some italian classics and some twists, and for their prices it’s 100% worth it.</p>
            </div>


        </div><br><br>
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
            </p><br><br>
            <a href="about.php" class="btn1">Read more</a>
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
        <div>
            <h1 class="credit"> Contact Us : <span> 71 271 156 </span></h1>
            <h1 class="credit"> Email : <span> Panadora.rest@gmail.com </span></h1>
            <h1 class="credit"> All rights reserved! </h1>

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