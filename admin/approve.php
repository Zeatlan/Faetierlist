<?php
    include('../db.php');


    $m = $membre->getById($_SESSION['id']);
    if($_SESSION['permission'] == 2 || $m->admin() == 1){
        if(isset($_GET['aid']) && isset($_GET['approve'])){
            $aid = intval($_GET['aid']);

            $a = $anime->getById($aid);
            if($_GET['approve'] == 'true'){
                $anime->approve($a);
                $m = $membre->getByPseudo($_GET['prop']);
                $membre->animeApprovedGain($m);
            }
            
            if($_GET['approve'] == 'false'){
                $anime->delete($a);
                $delete = $db->prepare("DELETE FROM anime_gender WHERE a_id = :aid");
                $delete->bindValue(':aid', intval($_GET['aid']));
                $delete->execute();
                
                if(file_exists('../img/anime/'. basename($_GET['aid']) .".png")){
                    unlink('../img/anime/'. basename($_GET['aid']) .".png");
                }

                if(file_exists('../img/anime/'. basename($_GET['aid']) .".jpg")){
                    unlink('../img/anime/'. basename($_GET['aid']) .".jpg");
                }
        
            }
        }
    }
?>