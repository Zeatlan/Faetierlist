<?php
    include('db.php');

    if(isset($_SESSION['validation']) && $_SESSION['validation'] != ""){
        if(isset($_GET['id']) && $_GET['id'] != ""){
            $id = intval($_GET['id']);

            if($_SESSION['validation'] == $id){
                $m = $membre->getById($_SESSION['id']);
                $m->setCanVote(1);
                $membre->update($m);

                $_SESSION['validate'] = true;
                header('location:index.php?success=1');
            }
        }
    }
?>