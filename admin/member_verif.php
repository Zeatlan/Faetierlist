<?php
    include('../db.php');


    if(isset($_POST) && $_POST['user'] != ""){
        if(isset($_POST['type']) && $_POST['type'] == "validate"){
            $update = $db->prepare("UPDATE membre SET u_canVote = 1 WHERE u_id = :id");
            $update->bindParam(":id", $_POST['user']);
            $update->execute();
            $a = array();
            array_push($a, "#7ed461");
            array_push($a, $_POST['user']);
            
            echo json_encode($a);
        }else if($_POST['type'] == "delete"){
            $update = $db->prepare("DELETE FROM membre WHERE u_id = :id");
            $update->bindParam(":id", $_POST['user']);
            $update->execute();
            $a = array();
            array_push($a, "#d46176");
            array_push($a, $_POST['user']);    
            
            echo json_encode($a);
        }
    }
?>