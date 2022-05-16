<?php
session_start();
include "conn.php";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>complete responsive food website design tutorial </title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/menu.css">

    <style>
        .rating_stars {
            margin-top: 15px;
            display: inline-block;
            font-size: 20px;
            font-weight: 200;
            color: #918f8f;
            position: relative;
        }

        .rating_stars span .fa,
        .rating_stars span.active-low .fa-star-o,
        .rating_stars span.active-high .fa-star-o {
            display: inline-block;
        }

        .rating_stars span .fa-star-o {
            display: inline-block;
        }

        .rating_stars span.s.active-high .fa-star {
            display: inline-block;
            color: #feb645;
        }

        .rating_stars span.s.active-low .fa-star-half-o {
            display: inline-block;
            color: #feb645;
        }

        .rating_stars span.r {
            position: absolute;
            top: 0;
            height: 20px;
            width: 10px;
            left: 0;
        }

        .rating_stars span.r.r0_5 {
            left: 0px;
        }

        .rating_stars span.r.r1 {
            left: 10px;
            width: 11px;
        }

        .rating_stars span.r.r1_5 {
            left: 21px;
            width: 13px;
        }

        .rating_stars span.r.r2 {
            left: 34px;
            width: 12px;
        }

        .rating_stars span.r.r2_5 {
            left: 46px;
            width: 12px;
        }

        .rating_stars span.r.r3 {
            left: 58px;
            width: 11px;
        }

        .rating_stars span.r.r3_5 {
            left: 69px;
            width: 12px;
        }

        .rating_stars span.r.r4 {
            left: 81px;
            width: 12px;
        }

        .rating_stars span.r.r4_5 {
            left: 93px;
            width: 12px;
        }

        .rating_stars span.r.r5 {
            left: 105px;
            width: 12px;
        }

        /* Just to make things look pretty ;) */
    </style>


</head>

<body>

    <!-- header section starts  -->

    <header>

        <a href="#" class="logo"><i class="fas fa-utensils"></i>Panadora</a>

        <div id="menu-bar" class="fas fa-bars"></div>

        <nav class="navbar">
            <a href="index.php">home</a>
            <a href="Menu.php">Menu</a>
            <a href="Resrvation.php">Reservation</a>
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


    <section>
        <div class="wrapper">
            <div class="title">
                <h4><span>Fresh food for good health </span>Our Menu</h4>
            </div>
        </div>
    </section>

    <section class="review" id="review">

        <h1 class="heading"> popular <span>items</span> </h1>

        <div class="item-container">
            <?php
            $items = $crud->getitem();
            if (isset($_POST['breakfast'])) {
                $_SESSION['categoryid'] = 1;
                while ($a = $items->fetch(PDO::FETCH_ASSOC)) {
                    if ($a['category_id'] == 1) {
                        $newPrice = floatval($a['item_price']) * floatval($_SESSION['currency-rate']);
            ?>
                        <div class="box">
                            <span class="price"> <?php echo $newPrice . ' ' . $_SESSION['currency-sym'] ?></span>
                            <img <?php echo '<img alt="Error" src="images/itemImages/' . $a['item_image'] . '"' ?>>
                            <h3><?php echo $a['item_name'] ?></h3>
                            <div class="stars">
                                <?php
                                for ($i = $a['item_rating']; $i > 0; $i--) {
                                    if ($i > 0 && $i < 1)
                                        echo "<i class='fa fa-star-half-o' aria-hidden='true'></i>";
                                    else {
                                        echo "<i class='fa fa-star' aria-hidden='true'></i>";
                                    }
                                }
                                for ($i = intval(5 - $a['item_rating']); $i > 0; $i--) {
                                    echo "<i class='fa fa-star-o' aria-hidden='true'></i>";
                                }
                                ?>
                            </div>
                            <h4><?php echo $a['item_desc'] ?></h4>
                        </div>

                <?php
                    }
                }
                ?>
                <div class="wrapper2">
                    <div class="addItem">
                        <section class="offers-events" style="padding: 1rem;" id="review">
                            <?php
                            if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === "admin") {
                            ?>
                                <div class="btn" onclick="myFunction()">Add Item
                                    <span class="popuptext" id="myPopup"></span>
                                </div>
                            <?php
                            }
                            ?>

                            <div class="PopupScreen">
                                <form class="Design" action="Menu.php" method="POST" enctype="multipart/form-data">
                                    <div class="c-logo">
                                        <a href="#" class="logo"><i class="fas fa-utensils"></i>
                                            <span style="font-size: 20px ; font-weight: bolder;color:#666;"> Panadora</span>
                                        </a>
                                    </div>


                                    <div class="inputBox content">

                                        <label for="item-name">Name:</label>
                                        <input type="text" id="item-name" name="item-name" placeholder="name" class="input">

                                        <label for="item-price">Price:</label>
                                        <input type="number" id="item-price" name="item-price" placeholder="price" class="input">

                                        <label for="feedback">Description:</label>
                                        <textarea placeholder="Write your description here:" style="resize: none;" name="item-desc" id="item-desc" cols="30" rows="5"></textarea>

                                        <label for="item-image">Image: </label>
                                        <input type="file" name="item-image" id="itemimage" />

                                    </div>
                                    <div>
                                        <span class="rating_stars rating_0" style="margin-left:0%; margin-top:-5px">
                                            <span class='s' data-low='0.5' data-high='1'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                            <span class='s' data-low='1.5' data-high='2'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                            <span class='s' data-low='2.5' data-high='3'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                            <span class='s' data-low='3.5' data-high='4'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                            <span class='s' data-low='4.5' data-high='5'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>

                                            <span class='r r0_5' data-rating='1' data-value='0.5'></span>
                                            <span class='r r1' data-rating='1' data-value='1'></span>
                                            <span class='r r1_5' data-rating='15' data-value='1.5'></span>
                                            <span class='r r2' data-rating='2' data-value='2'></span>
                                            <span class='r r2_5' data-rating='25' data-value='2.5'></span>
                                            <span class='r r3' data-rating='3' data-value='3'></span>
                                            <span class='r r3_5' data-rating='35' data-value='3.5'></span>
                                            <span class='r r4' data-rating='4' data-value='4'></span>
                                            <span class='r r4_5' data-rating='45' data-value='4.5'></span>
                                            <span class='r r5' data-rating='5' data-value='5'></span>
                                        </span>
                                        <input type="hidden" name="rating" id="rating_val" value="0" />
                                    </div>
                                    <div class="btns">
                                        <input type="submit" value="Submit" name="item-submit" class="btn2" style="margin-right: 1rem;margin-left: 1rem;">
                                        <input type="button" value="Close" class="btn2" style="margin-left: 7rem;" onclick="myFunction()">
                                    </div>


                                </form>
                            </div>


                    </div>


                    <?php
                }
                if (isset($_POST['lunch'])) {
                    $_SESSION['categoryid'] = 2;
                    while ($a = $items->fetch(PDO::FETCH_ASSOC)) {
                        if ($a['category_id'] == 2) {
                            $newPrice = floatval($a['item_price']) * floatval($_SESSION['currency-rate']);
                    ?>
                            <div class="box">
                                <span class="price"> <?php echo $newPrice . ' ' . $_SESSION['currency-sym'] ?></span>
                                <img <?php echo '<img alt="Error" src="images/itemImages/' . $a['item_image'] . '"' ?>>
                                <h3><?php echo $a['item_name'] ?></h3>
                                <div class="stars">
                                    <?php
                                    for ($i = $a['item_rating']; $i > 0; $i--) {
                                        if ($i > 0 && $i < 1)
                                            echo "<i class='fa fa-star-half-o' aria-hidden='true'></i>";
                                        else {
                                            echo "<i class='fa fa-star' aria-hidden='true'></i>";
                                        }
                                    }
                                    for ($i = intval(5 - $a['item_rating']); $i > 0; $i--) {
                                        echo "<i class='fa fa-star-o' aria-hidden='true'></i>";
                                    }
                                    ?>
                                </div>
                                <h4><?php echo $a['item_desc'] ?></h4>
                            </div>

                    <?php
                        }
                    }
                    ?>
                    <div class="wrapper2">
                        <div class="addItem">
                            <section class="offers-events" style="padding: 1rem;" id="review">
                                <?php
                                if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === "admin") {
                                ?>
                                    <div class="btn" onclick="myFunction()">Add Item
                                        <span class="popuptext" id="myPopup"></span>
                                    </div>
                                <?php
                                }
                                ?>

                                <div class="PopupScreen">
                                    <form class="Design" action="Menu.php" method="POST" enctype="multipart/form-data">
                                        <div class="c-logo">
                                            <a href="#" class="logo"><i class="fas fa-utensils"></i>
                                                <span style="font-size: 20px ; font-weight: bolder;color:#666;">
                                                    Panadora</span>
                                            </a>
                                        </div>


                                        <div class="inputBox content">

                                            <label for="item-name">Name:</label>
                                            <input type="text" id="item-name" name="item-name" placeholder="name" class="input">

                                            <label for="item-price">Price:</label>
                                            <input type="number" id="item-price" name="item-price" placeholder="price" class="input">

                                            <label for="feedback">Description:</label>
                                            <textarea placeholder="Write your description here:" style="resize: none;" name="item-desc" id="item-desc" cols="30" rows="5"></textarea>

                                            <label for="item-image">Image: </label>
                                            <input type="file" name="item-image" id="itemimage" />

                                        </div>
                                        <div>
                                            <span class="rating_stars rating_0" style="margin-left:0%; margin-top:-5px">
                                                <span class='s' data-low='0.5' data-high='1'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                                <span class='s' data-low='1.5' data-high='2'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                                <span class='s' data-low='2.5' data-high='3'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                                <span class='s' data-low='3.5' data-high='4'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                                <span class='s' data-low='4.5' data-high='5'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>

                                                <span class='r r0_5' data-rating='1' data-value='0.5'></span>
                                                <span class='r r1' data-rating='1' data-value='1'></span>
                                                <span class='r r1_5' data-rating='15' data-value='1.5'></span>
                                                <span class='r r2' data-rating='2' data-value='2'></span>
                                                <span class='r r2_5' data-rating='25' data-value='2.5'></span>
                                                <span class='r r3' data-rating='3' data-value='3'></span>
                                                <span class='r r3_5' data-rating='35' data-value='3.5'></span>
                                                <span class='r r4' data-rating='4' data-value='4'></span>
                                                <span class='r r4_5' data-rating='45' data-value='4.5'></span>
                                                <span class='r r5' data-rating='5' data-value='5'></span>
                                            </span>
                                            <input type="hidden" name="rating" id="rating_val" value="0" />
                                        </div>
                                        <div class="btns">
                                            <input type="submit" value="Submit" name="item-submit" class="btn2" style="margin-right: 1rem;margin-left: 1rem;">
                                            <input type="button" value="Close" class="btn2" style="margin-left: 7rem;" onclick="myFunction()">
                                        </div>


                                    </form>
                                </div>


                            </section>
                        </div>
                    </div>
                    <?php
                }
                if (isset($_POST['dinner'])) {
                    $_SESSION['categoryid'] = 3;
                    while ($a = $items->fetch(PDO::FETCH_ASSOC)) {
                        if ($a['category_id'] == 3) {
                            $newPrice = floatval($a['item_price']) * floatval($_SESSION['currency-rate']);

                    ?>
                            <div class="box">
                                <span class="price"> <?php echo $newPrice . ' ' . $_SESSION['currency-sym'] ?></span>
                                <img <?php echo '<img alt="Error" src="images/itemImages/' . $a['item_image'] . '"' ?>>
                                <h3><?php echo $a['item_name'] ?></h3>
                                <div class="stars">
                                    <?php
                                    for ($i = $a['item_rating']; $i > 0; $i--) {
                                        if ($i > 0 && $i < 1)
                                            echo "<i class='fa fa-star-half-o' aria-hidden='true'></i>";
                                        else {
                                            echo "<i class='fa fa-star' aria-hidden='true'></i>";
                                        }
                                    }
                                    for ($i = intval(5 - $a['item_rating']); $i > 0; $i--) {
                                        echo "<i class='fa fa-star-o' aria-hidden='true'></i>";
                                    }
                                    ?>
                                </div>
                                <h4><?php echo $a['item_desc'] ?></h4>
                            </div>

                    <?php
                        }
                    }
                    ?>
                    <div class="wrapper2">
                        <div class="addItem">
                            <section class="offers-events" style="padding: 1rem;" id="review">
                                <?php
                                if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === "admin") {
                                ?>
                                    <div class="btn" onclick="myFunction()">Add Item
                                        <span class="popuptext" id="myPopup"></span>
                                    </div>
                                <?php
                                }
                                ?>

                                <div class="PopupScreen">
                                    <form class="Design" action="Menu.php" method="POST" enctype="multipart/form-data">
                                        <div class="c-logo">
                                            <a href="#" class="logo"><i class="fas fa-utensils"></i>
                                                <span style="font-size: 20px ; font-weight: bolder;color:#666;">
                                                    Panadora</span>
                                            </a>
                                        </div>


                                        <div class="inputBox content">

                                            <label for="item-name">Name:</label>
                                            <input type="text" id="item-name" name="item-name" placeholder="name" class="input">

                                            <label for="item-price">Price:</label>
                                            <input type="number" id="item-price" name="item-price" placeholder="price" class="input">

                                            <label for="feedback">Description:</label>
                                            <textarea placeholder="Write your description here:" style="resize: none;" name="item-desc" id="item-desc" cols="30" rows="5"></textarea>

                                            <label for="item-image">Image: </label>
                                            <input type="file" name="item-image" id="itemimage" />

                                        </div>
                                        <div>
                                            <span class="rating_stars rating_0" style="margin-left:0%; margin-top:-5px">
                                                <span class='s' data-low='0.5' data-high='1'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                                <span class='s' data-low='1.5' data-high='2'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                                <span class='s' data-low='2.5' data-high='3'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                                <span class='s' data-low='3.5' data-high='4'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                                <span class='s' data-low='4.5' data-high='5'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>

                                                <span class='r r0_5' data-rating='1' data-value='0.5'></span>
                                                <span class='r r1' data-rating='1' data-value='1'></span>
                                                <span class='r r1_5' data-rating='15' data-value='1.5'></span>
                                                <span class='r r2' data-rating='2' data-value='2'></span>
                                                <span class='r r2_5' data-rating='25' data-value='2.5'></span>
                                                <span class='r r3' data-rating='3' data-value='3'></span>
                                                <span class='r r3_5' data-rating='35' data-value='3.5'></span>
                                                <span class='r r4' data-rating='4' data-value='4'></span>
                                                <span class='r r4_5' data-rating='45' data-value='4.5'></span>
                                                <span class='r r5' data-rating='5' data-value='5'></span>
                                            </span>
                                            <input type="hidden" name="rating" id="rating_val" value="0" />
                                        </div>
                                        <div class="btns">
                                            <input type="submit" value="Submit" name="item-submit" class="btn2" style="margin-right: 1rem;margin-left: 1rem;">
                                            <input type="button" value="Close" class="btn2" style="margin-left: 7rem;" onclick="myFunction()">
                                        </div>


                                    </form>
                                </div>


                        </div>
    </section>
    </div>
    </div>
    <?php
                }
                if (isset($_POST['dessert'])) {
                    $_SESSION['categoryid'] = 4;
                    while ($a = $items->fetch(PDO::FETCH_ASSOC)) {
                        if ($a['category_id'] == 4) {
                            $newPrice = floatval($a['item_price']) * floatval($_SESSION['currency-rate']);
    ?>
            <div class="box">
                <span class="price"> <?php echo $newPrice . ' ' . $_SESSION['currency-sym'] ?></span>
                <img <?php echo '<img alt="Error" src="images/itemImages/' . $a['item_image'] . '"' ?>>
                <h3><?php echo $a['item_name'] ?></h3>
                <div class="stars">
                    <?php
                            for ($i = $a['item_rating']; $i > 0; $i--) {
                                if ($i > 0 && $i < 1)
                                    echo "<i class='fa fa-star-half-o' aria-hidden='true'></i>";
                                else {
                                    echo "<i class='fa fa-star' aria-hidden='true'></i>";
                                }
                            }
                            for ($i = intval(5 - $a['item_rating']); $i > 0; $i--) {
                                echo "<i class='fa fa-star-o' aria-hidden='true'></i>";
                            }
                    ?>
                </div>
                <h4><?php echo $a['item_desc'] ?></h4>
            </div>

    <?php
                        }
                    }
    ?>
    <div class="wrapper2">
        <div class="addItem">
            <section class="offers-events" style="padding: 1rem;" id="review">
                <?php
                    if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === "admin") {
                ?>
                    <div class="btn" onclick="myFunction()">Add Item
                        <span class="popuptext" id="myPopup"></span>
                    </div>
                <?php
                    }
                ?>

                <div class="PopupScreen">
                    <form class="Design" action="Menu.php" method="POST" enctype="multipart/form-data">
                        <div class="c-logo">
                            <a href="#" class="logo"><i class="fas fa-utensils"></i>
                                <span style="font-size: 20px ; font-weight: bolder;color:#666;"> Panadora</span>
                            </a>
                        </div>


                        <div class="inputBox content">

                            <label for="item-name">Name:</label>
                            <input type="text" id="item-name" name="item-name" placeholder="name" class="input">

                            <label for="item-price">Price:</label>
                            <input type="number" id="item-price" name="item-price" placeholder="price" class="input">

                            <label for="feedback">Description:</label>
                            <textarea placeholder="Write your description here:" style="resize: none;" name="item-desc" id="item-desc" cols="30" rows="5"></textarea>

                            <label for="item-image">Image: </label>
                            <input type="file" name="item-image" id="itemimage" />

                        </div>
                        <div>
                            <span class="rating_stars rating_0" style="margin-left:0%; margin-top:-5px">
                                <span class='s' data-low='0.5' data-high='1'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                <span class='s' data-low='1.5' data-high='2'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                <span class='s' data-low='2.5' data-high='3'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                <span class='s' data-low='3.5' data-high='4'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                <span class='s' data-low='4.5' data-high='5'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>

                                <span class='r r0_5' data-rating='1' data-value='0.5'></span>
                                <span class='r r1' data-rating='1' data-value='1'></span>
                                <span class='r r1_5' data-rating='15' data-value='1.5'></span>
                                <span class='r r2' data-rating='2' data-value='2'></span>
                                <span class='r r2_5' data-rating='25' data-value='2.5'></span>
                                <span class='r r3' data-rating='3' data-value='3'></span>
                                <span class='r r3_5' data-rating='35' data-value='3.5'></span>
                                <span class='r r4' data-rating='4' data-value='4'></span>
                                <span class='r r4_5' data-rating='45' data-value='4.5'></span>
                                <span class='r r5' data-rating='5' data-value='5'></span>
                            </span>
                            <input type="hidden" name="rating" id="rating_val" value="0" />
                        </div>
                        <div class="btns">
                            <input type="submit" value="Submit" name="item-submit" class="btn2" style="margin-right: 1rem;margin-left: 1rem;">
                            <input type="button" value="Close" class="btn2" style="margin-left: 7rem;" onclick="myFunction()">
                        </div>

                    </form>
                </div>


        </div>
        </section>
    </div>
    </div>
    <?php
                }
                if (isset($_POST['drinks'])) {
                    $_SESSION['categoryid'] = 5;
                    while ($a = $items->fetch(PDO::FETCH_ASSOC)) {
                        if ($a['category_id'] == 5) {
                            $newPrice = floatval($a['item_price']) * floatval($_SESSION['currency-rate']);
    ?>
            <div class="box">
                <span class="price"> <?php echo $newPrice . ' ' . $_SESSION['currency-sym'] ?></span>
                <img <?php echo '<img alt="Error" src="images/itemImages/' . $a['item_image'] . '"' ?>>
                <h3><?php echo $a['item_name'] ?></h3>
                <div class="stars">
                    <?php
                            for ($i = $a['item_rating']; $i > 0; $i--) {
                                if ($i > 0 && $i < 1)
                                    echo "<i class='fa fa-star-half-o' aria-hidden='true'></i>";
                                else {
                                    echo "<i class='fa fa-star' aria-hidden='true'></i>";
                                }
                            }
                            for ($i = intval(5 - $a['item_rating']); $i > 0; $i--) {
                                echo "<i class='fa fa-star-o' aria-hidden='true'></i>";
                            }
                    ?>
                </div>
                <h4><?php echo $a['item_desc'] ?></h4>
            </div>

    <?php
                        }
                    }
    ?>
    <div class="wrapper2">
        <div class="addItem">
            <section class="offers-events" style="padding: 1rem;" id="review">
                <?php
                    if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === "admin") {
                ?>
                    <div class="btn" onclick="myFunction()">Add Item
                        <span class="popuptext" id="myPopup"></span>
                    </div>
                <?php
                    }
                ?>

                <div class="PopupScreen">
                    <form class="Design" action="Menu.php" method="POST" enctype="multipart/form-data">
                        <div class="c-logo">
                            <a href="#" class="logo"><i class="fas fa-utensils"></i>
                                <span style="font-size: 20px ; font-weight: bolder;color:#666;"> Panadora</span>
                            </a>
                        </div>


                        <div class="inputBox content">

                            <label for="item-name">Name:</label>
                            <input type="text" id="item-name" name="item-name" placeholder="name" class="input">

                            <label for="item-price">Price:</label>
                            <input type="number" id="item-price" name="item-price" placeholder="price" class="input">

                            <label for="feedback">Description:</label>
                            <textarea placeholder="Write your description here:" style="resize: none;" name="item-desc" id="item-desc" cols="30" rows="5"></textarea>

                            <label for="item-image">Image: </label>
                            <input type="file" name="item-image" id="itemimage" />

                        </div>
                        <div>
                            <span class="rating_stars rating_0" style="margin-left:0%; margin-top:-5px">
                                <span class='s' data-low='0.5' data-high='1'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                <span class='s' data-low='1.5' data-high='2'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                <span class='s' data-low='2.5' data-high='3'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                <span class='s' data-low='3.5' data-high='4'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                <span class='s' data-low='4.5' data-high='5'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>

                                <span class='r r0_5' data-rating='1' data-value='0.5'></span>
                                <span class='r r1' data-rating='1' data-value='1'></span>
                                <span class='r r1_5' data-rating='15' data-value='1.5'></span>
                                <span class='r r2' data-rating='2' data-value='2'></span>
                                <span class='r r2_5' data-rating='25' data-value='2.5'></span>
                                <span class='r r3' data-rating='3' data-value='3'></span>
                                <span class='r r3_5' data-rating='35' data-value='3.5'></span>
                                <span class='r r4' data-rating='4' data-value='4'></span>
                                <span class='r r4_5' data-rating='45' data-value='4.5'></span>
                                <span class='r r5' data-rating='5' data-value='5'></span>
                            </span>
                            <input type="hidden" name="rating" id="rating_val" value="0" />
                        </div>
                        <div class="btns">
                            <input type="submit" value="Submit" name="item-submit" class="btn2" style="margin-right: 1rem;margin-left: 1rem;">
                            <input type="button" value="Close" class="btn2" style="margin-left: 7rem;" onclick="myFunction()">
                        </div>


                    </form>
                </div>


        </div>
        </section>
    </div>
    </div>
    <?php
                }
                if (isset($_POST['ice-cream'])) {
                    $_SESSION['categoryid'] = 6;
                    while ($a = $items->fetch(PDO::FETCH_ASSOC)) {
                        if ($a['category_id'] == 6) {
                            $newPrice = floatval($a['item_price']) * floatval($_SESSION['currency-rate']);
    ?>
            <div class="box">
                <span class="price"> <?php echo $newPrice . ' ' . $_SESSION['currency-sym'] ?></span>
                <img <?php echo '<img alt="Error" src="images/itemImages/' . $a['item_image'] . '"' ?>>
                <h3><?php echo $a['item_name'] ?></h3>
                <div class="stars">
                    <?php
                            for ($i = $a['item_rating']; $i > 0; $i--) {
                                if ($i > 0 && $i < 1)
                                    echo "<i class='fa fa-star-half-o' aria-hidden='true'></i>";
                                else {
                                    echo "<i class='fa fa-star' aria-hidden='true'></i>";
                                }
                            }
                            for ($i = intval(5 - $a['item_rating']); $i > 0; $i--) {
                                echo "<i class='fa fa-star-o' aria-hidden='true'></i>";
                            }
                    ?>
                </div>
                <h4><?php echo $a['item_desc'] ?></h4>
            </div>

    <?php
                        }
                    }
    ?>
    <div class="wrapper2">
        <div class="addItem">
            <section class="offers-events" style="padding: 1rem;" id="review">
                <?php
                    if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === "admin") {
                ?>
                    <div class="btn" onclick="myFunction()">Add Item
                        <span class="popuptext" id="myPopup"></span>
                    </div>
                <?php
                    }
                ?>

                <div class="PopupScreen">
                    <form class="Design" action="Menu.php" method="POST" enctype="multipart/form-data">
                        <div class="c-logo">
                            <a href="#" class="logo"><i class="fas fa-utensils"></i>
                                <span style="font-size: 20px ; font-weight: bolder;color:#666;"> Panadora</span>
                            </a>
                        </div>


                        <div class="inputBox content">

                            <label for="item-name">Name:</label>
                            <input type="text" id="item-name" name="item-name" placeholder="name" class="input">

                            <label for="item-price">Price:</label>
                            <input type="number" id="item-price" name="item-price" placeholder="price" class="input">

                            <label for="feedback">Description:</label>
                            <textarea placeholder="Write your description here:" style="resize: none;" name="item-desc" id="item-desc" cols="30" rows="5"></textarea>

                            <label for="item-image">Image: </label>
                            <input type="file" name="item-image" id="itemimage" />

                        </div>
                        <div>
                            <span class="rating_stars rating_0" style="margin-left:0%; margin-top:-5px">
                                <span class='s' data-low='0.5' data-high='1'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                <span class='s' data-low='1.5' data-high='2'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                <span class='s' data-low='2.5' data-high='3'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                <span class='s' data-low='3.5' data-high='4'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                                <span class='s' data-low='4.5' data-high='5'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>

                                <span class='r r0_5' data-rating='1' data-value='0.5'></span>
                                <span class='r r1' data-rating='1' data-value='1'></span>
                                <span class='r r1_5' data-rating='15' data-value='1.5'></span>
                                <span class='r r2' data-rating='2' data-value='2'></span>
                                <span class='r r2_5' data-rating='25' data-value='2.5'></span>
                                <span class='r r3' data-rating='3' data-value='3'></span>
                                <span class='r r3_5' data-rating='35' data-value='3.5'></span>
                                <span class='r r4' data-rating='4' data-value='4'></span>
                                <span class='r r4_5' data-rating='45' data-value='4.5'></span>
                                <span class='r r5' data-rating='5' data-value='5'></span>
                            </span>
                            <input type="hidden" name="rating" id="rating_val" value="0" />
                        </div>
                        <div class="btns">
                            <input type="submit" value="Submit" name="item-submit" class="btn2" style="margin-right: 1rem;margin-left: 1rem;">
                            <input type="button" value="Close" class="btn2" style="margin-left: 7rem;" onclick="myFunction()">
                        </div>


                    </form>
                </div>
            </section>

        </div>
    </div>
<?php
                }
?>
</section>
<div style="display:flex ;align-items:center; justify-content:center; margin-bottom:1rem">

    <a href="Menu.php" class="btn">Back to Menu</a>
    <?php
    if (!empty($_SESSION['user_type']) &&  $_SESSION['user_type'] === "superAdmin") {
    ?>
        <form action="item.php" method="POST">
            <button class="btn" type="submit" name="changeCurrency" style="margin-left: 1rem;">Change currency</button>
        </form>
    <?php
    }
    ?>
</div>

<?php
//---------------------------------------------------------------changing currency--------------------------------------------------
if (isset($_POST['changeCurrency'])) {
    if ($_SESSION['currency'] == 'dollar') {
        $uc = $crud->updatecurrencytoeuro();
        if ($uc) {
?>
            <script>
                alert('currency changed to euro');
                location = "index.php";
            </script>
        <?php
        } else {
        ?>
            <script>
                alert('error changing currency to euro');
            </script>
        <?php
        }
    } else {
        $uc = $crud->updatecurrencytodollar();
        if ($uc) {
        ?>
            <script>
                alert('currency changed to dollar');
                location = "index.php";
            </script>
        <?php
        } else {
        ?>
            <script>
                alert('error changing currency to dollar');
            </script>
<?php
        }
    }
} //--------------------------------------------------end of change currency-----------------------------------------------

?>






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
<script>
    jQuery(document).ready(function($) {

        $('.rating_stars span.r').hover(function() {
            // get hovered value
            var rating = $(this).data('rating');
            var value = $(this).data('value');
            $(this).parent().attr('class', '').addClass('rating_stars').addClass('rating_' + rating);
            highlight_star(value);
        }, function() {
            // get hidden field value
            var rating = $("#rating").val();
            var value = $("#rating_val").val();
            $(this).parent().attr('class', '').addClass('rating_stars').addClass('rating_' + rating);
            highlight_star(value);
        }).click(function() {
            // Set hidden field value
            var value = $(this).data('value');
            $("#rating_val").val(value);

            var rating = $(this).data('rating');
            $("#rating").val(rating);

            highlight_star(value);
        });

        var highlight_star = function(rating) {
            $('.rating_stars span.s').each(function() {
                var low = $(this).data('low');
                var high = $(this).data('high');
                $(this).removeClass('active-high').removeClass('active-low');
                if (rating >= high) $(this).addClass('active-high');
                else if (rating == low) $(this).addClass('active-low');
            });
        }
    });
</script>
<script src="script.js"></script>
</body>

</html>