<?php

    include('../db.php');

    if(isset($_POST['username']) && $_POST['username'] != ""){
        $m = $membre->getByPseudo($_POST['username']);
        if($m->pseudo() != ""){
            if($m->admin() == 0){
                if($m->canVote() == 1){
                    $m->setCanVote(-1);
                    $membre->update($m);
                    
                    $id = intval($m->id());
                    
                    $deleteVotes = $db->prepare("DELETE FROM note WHERE n_uid = :id");
                    $deleteVotes->bindParam(":id", $id);
                    $deleteVotes->execute();
                    echo "<div class='msg'>". $_POST['username'] ." a été restreint de tout vote. (Tous ses votes ont étaient supprimés.)</div>";
                }else{
                    echo "<div class='msg'>". $_POST['username'] ." ne peut pas être restreint, il est soit déjà restreint soit n'est pas encore validé.</div>";
                    
                }
            }else{
                echo "<div class='msg'> Action impossible sur un admin.</div>";
            }
        }else{
            echo "<div class='msg'> Nous n'avons pas réussit à trouver l'utilisateur.</div>";
        }
    }

    if(isset($_POST['unrestrict']) && $_POST['unrestrict'] > 0){
        $m = $membre->getById($_POST['unrestrict']);
        if($m->canVote() == -1){
            $m->setCanVote(1);
            $membre->update($m);
            echo "<div class='msg'>". $m->pseudo() ." peut de nouveau voter.</div>";
        }else{
            echo "<div class='msg'>". $m->pseudo() ." n'est pas un utilisateur restreint.</div>";
            
        }
    }
?>