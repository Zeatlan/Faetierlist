<?php
    include('db.php');

    if(isset($_SESSION)){
        session_destroy();
        if(isset($_SERVER['HTTP_REFERER']))
            header("location:". $_SERVER['HTTP_REFERER']);
        else
            header("location: index.php");
    }
?>

