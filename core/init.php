<?php
    session_start();
    include_once ('database/connection.php');
    include_once ('classes/user.php');
    include_once ('classes/tweet.php');
    include_once ('classes/follow.php');

    global $pdo;


    $getFromU = new User($pdo);
    $getFromT = new Tweet($pdo);
    $getFromF = new Follow($pdo);

    define("BASE_URL","http://localhost/twitter/");
?>