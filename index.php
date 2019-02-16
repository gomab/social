<?php
/**
 * Created by PhpStorm.
 * User: gomab
 * Date: 2/15/19
 * Time: 9:47 PM
 */


//Database connexion


$con = mysqli_connect("localhost", "gomab", "root", "social");

if (mysqli_connect_errno()){
    echo "Failed to connect: " .mysqli_connect_errno();
}












?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    Hello word!!
</body>
</html>
