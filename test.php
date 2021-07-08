<?php
    include('db.php');
    session_destroy();
    session_unset();
    var_dump($_SESSION);
?>