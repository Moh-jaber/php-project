<?php
class crud
{
    private $db;

    function __construct($conn)
    {
        $this->db = $conn;
    }


    public function insertfeedback($user_id, $f_desc, $rating_id)
    {
        try {

            // define sql statement to be executed
            $sql = " INSERT INTO feedback (f_desc,user_id,rating_id) VALUES (:fdesc,:userid,:ratingid) "; // hude l parameters henne l placeholders 

            // prepare the sql statement for execution
            $stmt = $this->db->prepare($sql);

            // bind all placeholders to the actual values
            $stmt->bindparam(':fdesc', $f_desc);
            $stmt->bindparam(':userid', $user_id);
            $stmt->bindparam(':ratingid', $rating_id);

            // execute statement
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function insertreservation($user_id, $res_date, $nb_of_seats, $res_sugg)
    {
        try {

            // define sql statement to be executed
            $sql = " INSERT INTO reservations (user_id, reservation_date, nb_of_seats, reservation_suggestion) VALUES (:user_id, :reservation_date, :nb_of_seats, :reservation_suggestion) "; // hude l parameters henne l placeholders 

            // prepare the sql statement for execution
            $stmt = $this->db->prepare($sql);

            // bind all placeholders to the actual values
            $stmt->bindparam(':user_id', $user_id);
            $stmt->bindparam(':reservation_date', $res_date);
            $stmt->bindparam(':nb_of_seats', $nb_of_seats);
            $stmt->bindparam(':reservation_suggestion', $res_sugg);

            // execute statement
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function insertrating($user_id, $user_rating) //insert user rating to database
    {
        try {

            // define sql statement to be executed
            $sql = " INSERT INTO rating (user_id,user_rating) VALUES (:userid,:userrating) "; // hude l parameters henne l placeholders 

            // prepare the sql statement for execution
            $stmt = $this->db->prepare($sql);

            // bind all placeholders to the actual values
            $stmt->bindparam(':userid', $user_id);
            $stmt->bindparam(':userrating', $user_rating);

            // execute statement
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function insertuser($user_name, $user_nb, $user_email, $user_pass, $user_type, $user_image)
    {
        try {

            // define sql statement to be executed
            $sql = " INSERT INTO user (user_name, user_nb, user_email, user_pass, user_type, user_image) VALUES (:username, :usernb,:useremail,:userpass,:usertype,:userimage) "; // hude l parameters henne l placeholders 

            // prepare the sql statement for execution
            $stmt = $this->db->prepare($sql);

            // bind all placeholders to the actual values
            $stmt->bindparam(':username', $user_name);
            $stmt->bindparam(':usernb', $user_nb);
            $stmt->bindparam(':useremail', $user_email);
            $stmt->bindparam(':userpass', $user_pass);
            $stmt->bindparam(':usertype', $user_type);
            $stmt->bindparam(':userimage', $user_image);

            // execute statement
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    //inserting an offer to the database
    public function insertoffer($offer_name, $offer_desc, $offer_percentage, $offer_endtime, $offer_image)
    {
        try {

            // define sql statement to be executed
            $sql = " INSERT INTO offer (offer_name, offer_desc, offer_percentage, offer_end_time, offer_image) VALUES (:offer_name, :offer_desc,:offer_percentage,:offer_end_time,:offer_image) "; // hude l parameters henne l placeholders 

            // prepare the sql statement for execution
            $stmt = $this->db->prepare($sql);

            // bind all placeholders to the actual values
            $stmt->bindparam(':offer_name', $offer_name);
            $stmt->bindparam(':offer_desc', $offer_desc);
            $stmt->bindparam(':offer_percentage', $offer_percentage);
            $stmt->bindparam(':offer_end_time', $offer_endtime);
            $stmt->bindparam(':offer_image', $offer_image);

            // execute statement
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    //inserting a new event to the database
    public function insertevent($event_name, $event_desc, $event_image)
    {
        try {

            // define sql statement to be executed
            $sql = " INSERT INTO events (event_name, event_desc, event_image) VALUES (:event_name, :event_desc, :event_image) "; // hude l parameters henne l placeholders 

            // prepare the sql statement for execution
            $stmt = $this->db->prepare($sql);

            // bind all placeholders to the actual values
            $stmt->bindparam(':event_name', $event_name);
            $stmt->bindparam(':event_desc', $event_desc);
            $stmt->bindparam(':event_image', $event_image);

            // execute statement
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    //inserting a new item to the database
    public function insertitem($item_name, $item_price, $item_currency, $item_desc, $item_rating, $item_image, $category_id)
    {
        try {

            // define sql statement to be executed
            $sql = " INSERT INTO item (item_name, item_price, item_currency, item_desc, item_rating, item_image, category_id) VALUES (:itemname, :itemprice, :itemcurrency, :itemdesc, :itemrating, :itemimage, :categoryid) "; // hude l parameters henne l placeholders 

            // prepare the sql statement for execution
            $stmt = $this->db->prepare($sql);

            // bind all placeholders to the actual values
            $stmt->bindparam(':itemname', $item_name);
            $stmt->bindparam(':itemprice', $item_price);
            $stmt->bindparam(':itemcurrency', $item_currency);
            $stmt->bindparam(':itemdesc', $item_desc);
            $stmt->bindparam(':itemrating', $item_rating);
            $stmt->bindparam(':itemimage', $item_image);
            $stmt->bindparam(':categoryid', $category_id);

            // execute statement
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updatecurrencytodollar()
    {
        try {

            // define sql statement to be executed
            $sql = " UPDATE `restaurant` SET `currency_id` = 1 WHERE `res_id` = 1;";

            // prepare the sql statement for execution
            $stmt = $this->db->prepare($sql);

            // execute statement
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updatecurrencytoeuro()
    {
        try {

            // define sql statement to be executed
            $sql = " UPDATE `restaurant` SET `currency_id` = 2 WHERE `res_id` = 1;";

            // prepare the sql statement for execution
            $stmt = $this->db->prepare($sql);

            // execute statement
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }



    //get all feedbacks from database
    public function getfeedback()
    {
        $sql = "SELECT * FROM `feedback`;";
        $result = $this->db->query($sql);
        return $result;
    }

    // get all users from table user
    public function getuser()
    {
        $sql = "SELECT * FROM `user`;";
        $result = $this->db->query($sql);
        return $result;
    }

    // to get the max number of reservations in the restaurant
    public function getlimit()
    {
        $sql = "SELECT * FROM `restaurant`;";
        $result = $this->db->query($sql);
        return $result;
    }

    // to get number of reserved seats
    public function getReservationTotal()
    {
        $sql = "SELECT * FROM `reservations`;";
        $result = $this->db->query($sql);
        return $result;
    }


    // to get all the offers
    public function getoffer()
    {
        $sql = "SELECT * FROM `offer`;";
        $result = $this->db->query($sql);
        return $result;
    }


    // to get all the events
    public function getevent()
    {
        $sql = "SELECT * FROM `events`;";
        $result = $this->db->query($sql);
        return $result;
    }

    //get all items
    public function getitem()
    {
        $sql = "SELECT * FROM `item`;";
        $result = $this->db->query($sql);
        return $result;
    }
    //get all ratings
    public function getrating()
    {
        $sql = "SELECT * FROM `rating`;";
        $result = $this->db->query($sql);
        return $result;
    }

    //get currency id from restaurant
    public function getcurrencyfromres()
    {
        $sql = "SELECT * FROM `restaurant`;";
        $result = $this->db->query($sql);
        return $result->fetch();
    }

    //get all currency table info
    public function getcurrency()
    {
        $sql = "SELECT * FROM `currency`;";
        $result = $this->db->query($sql);
        return $result;
    }
}