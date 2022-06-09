<?php
    include_once "../core/init.php";
    $getFromU -> logout();

    if($getFromU->loggedIn() ===false)
    {
    header("Location: ../index.php");
    }
?>