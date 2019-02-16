<?php
/**
 * Created by PhpStorm.
 * User: gomab
 * Date: 2/16/19
 * Time: 5:07 AM
 */

session_start();

$con = mysqli_connect("localhost", "gomab", "root", "social"); //Connection variable

if (mysqli_connect_errno()){
    echo "Failed to connect" .mysqli_connect_errno();
}

// declaring variable to prevents errors

$fname = "";
$lname = "";
$em = "";
$em2 = "";
$password = "";
$password2 = "";
$date = ""; //Sign up date
$error_array = []; //Holds error msg



if (isset($_POST['register_button'])){
    //registration form values

    //First Name
    $fname = strip_tags($_POST['reg_fname']); //Remove html tags
    $fname = str_replace(' ', '', $fname); // Remove space
    $fname = ucfirst(strtolower($fname)); // Uppercase first letter
    $_SESSION['reg_fname'] = $fname; //Stores first name into name session variable

    //Last Name
    $lname = strip_tags($_POST['reg_lname']); //Remove html tags
    $lname = str_replace(' ', '', $lname); // Remove space
    $lname = ucfirst(strtolower($lname)); // Uppercase first letter
    $_SESSION['reg_lname'] = $lname; //Stores last name into name session variable

    //Email
    $em = strip_tags($_POST['reg_email']); //Remove html tags
    $em = str_replace(' ', '', $em); // Remove space
    $em = ucfirst(strtolower($em)); // Uppercase first letter
    $_SESSION['reg_email'] = $em; //Stores email into name session variable

    //Email2
    $em2 = strip_tags($_POST['reg_email2']); //Remove html tags
    $em2 = str_replace(' ', '', $em2); // Remove space
    $em2 = ucfirst(strtolower($em2)); // Uppercase first letter
    $_SESSION['reg_email2'] = $em2; //Stores email2 into name session variable

    //Password
    $password = strip_tags($_POST['reg_password']); //Remove html tags

    //Password2
    $password2 = strip_tags($_POST['reg_password2']); //Remove html tags

    $date = date("Y-m-d");


    //Email setup
    if ($em == $em2 ){
        //Check if email is in valid format
        if (filter_var($em, FILTER_VALIDATE_EMAIL)){
            $em = filter_var($em, FILTER_VALIDATE_EMAIL);

            // Check if email already exists
            $e_check = mysqli_query($con,"SELECT email FROM users WHERE email = '$em' ");

            //Count the numbers of rows returned
            $num_rows = mysqli_num_rows($e_check);

            if ($num_rows > 0){
                array_push($error_array, "Email already in use <br>");
            }
        }else{
            array_push($error_array, "Invalid email format <br>");
        }
    }else{
        array_push($error_array, "Emails don't match <br>");
    }


    //First name control
    if (strlen($fname) > 25 || strlen($fname) < 2){
        //echo "Your first name must be between 2 and 25 characters";
        array_push($error_array, "Your first name must be between 2 and 25 characters <br>");
    }

    //Last name control
    if (strlen($lname) > 25 || strlen($lname) < 2){
        array_push($error_array, "Your last name must be between 2 and 25 characters <br>");
    }

    //Password Control
    if ($password != $password2){
        array_push($error_array, "Your password do not match <br>");
    }else{
        if (preg_match('/[^A-Za-z0-9]/', $password)){
            array_push($error_array, "Your password can only contain english characters or numbers <br>");
        }
    }

    //Password control
    if (strlen($password) > 30 || strlen($password) < 5){
        //echo "Your password must be between 5 and 30 characters";
        array_push($error_array, "Your password must be between 5 and 30 characters <br>");
    }


    if (empty($error_array)){
        $password = md5($password); //Encrypt password before sending to database

        //Generate username by concatenating first name and last name
        $username = strtolower($fname) . "_" . $lname;
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username' ");

        $i = 0;
        //If username exists add number to username
        while(mysqli_num_rows($check_username_query) != 0){
            $i++; //Add 1 to 1
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username' ");
        }
    }
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hope</title>
</head>
<body>
    <form action="register.php" method="post">
        <input type="text" name="reg_fname" placeholder="First Name" value="
            <?
                if (isset($_SESSION['reg_fname'])){
                    echo $_SESSION['reg_fname'];
                }
            ?>
        " required>
        <br>
        <? if (in_array("Your first name must be between 2 and 25 characters <br>", $error_array)) echo "Your first name must be between 2 and 25 characters <br>"?>

        <input type="text" name="reg_lname" placeholder="Last Name" value="
            <?
                if (isset($_SESSION['reg_lname'])){
                    echo $_SESSION['reg_lname'];
                }
            ?>
        " required>
        <br>
        <? if (in_array("Your last name must be between 2 and 25 characters <br>", $error_array)) echo "Your last name must be between 2 and 25 characters <br>"?>

        <input type="email" name="reg_email" placeholder="Email" value="
            <?
                if (isset($_SESSION['reg_email'])){
                    echo $_SESSION['reg_email'];
                }
            ?>
        " required>
        <br>

        <input type="email" name="reg_email2" placeholder="Confirm email" value="
            <?
                if (isset($_SESSION['reg_email2'])){
                    echo $_SESSION['reg_email2'];
                }
            ?>
        " required>
        <br>
        <? if (in_array("Email already in use <br>", $error_array)) echo "Email already in use <br>" ;
         elseif (in_array("Invalid email format <br>", $error_array)) echo "Invalid email format <br>" ;
         elseif (in_array("Emails don't match <br>", $error_array)) echo "Emails don't match <br>" ;
        ?>


        <input type="password" name="reg_password" placeholder="Password">
        <br>
        <input type="password" name="reg_password2" placeholder="Confirm password">
        <br>
        <? if (in_array("Your password do not match <br>", $error_array)) echo "Your password do not match <br>" ;
        elseif (in_array("Your password can only contain english characters or numbers <br>", $error_array)) echo "Your password can only contain english characters or numbers <br>" ;
        elseif (in_array("Your password must be between 5 and 30 characters <br>", $error_array)) echo "Your password must be between 5 and 30 characters <br>" ;
        ?>

        <input type="submit" name="register_button" value="Register">
    </form>
</body>
</html>
