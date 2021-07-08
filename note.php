<?php
    include('db.php'); 

    if(isset($_POST) && !isset($_GET['delete'])){
        $chang = false;
        $success = array(
            "add" => false,
            "update" => false,
            "maxvote" => false
        );
        
        if(isset($_POST['note'])){
            for($i = 0; $i < count($_POST['note']); $i++){
                if($_POST['note'][$i] != ""){
                    if(is_numeric($_POST['note'][$i])){
                        if($_POST['note'][$i] >= 0 && $_POST['note'][$i] <= 20){

                            $exist = $db->prepare("SELECT * FROM note WHERE n_aid = :aid AND n_gid = :gid AND n_uid = :uid");
                            $exist->bindParam(":aid", $_POST['aid'][$i]);
                            $exist->bindParam(":gid", $_GET['gid']);
                            $exist->bindParam(":uid", $_SESSION['id']);
                            $exist->execute();
                            $e = $exist->fetch();

                            $n = new Note(array("", $_POST['aid'][$i], $_GET['gid'], $_SESSION['id'], $_POST['note'][$i], time()));

                            $n->setAid($_POST['aid'][$i]);
                            $n->setNote($_POST['note'][$i]);
                            $n->setUid($_SESSION['id']);
                            $n->setGid($_GET['gid']);
                            $n->setDate(time());
                            if(!$e){
                                $note->add($n);
                                $m = $membre->getById($_SESSION['id']);
                                $select = $db->query("SELECT g_nbvote FROM gender WHERE g_id=". $_GET['gid']);

                                $g = $db->prepare("UPDATE gender SET g_nbvote = g_nbvote + 1 WHERE g_id = :gid");
                                $g->bindParam(':gid', $_GET['gid']);
                                $g->execute();
                            
                                $success['add'] = true;
                                if($m->maxVote() <= 0){
                                    $success['maxvote'] = true;
                                }else{
                                    $membre->decreaseMaxVote($m);
                                }
                                $chang = true;

                                    if(count($_POST['note']) == 1) break;
                            }else {
                                if($e['n_note'] != $_POST['note'][$i]){
                                    $n->setId($e['n_id']);
                                    $note->update($n);
                                    
                                    $success['update'] = true;
                                    $chang = true;
                                        if(count($_POST['note']) == 1) break;
                                }
                            }
                        }else{
                            echo "number";
                            break;
                        }
                    }else{
                        echo "numeric";
                        break;
                    }
                }else{
                    echo "manquant";
                    break;
                }
            }
            if($chang)
                echo json_encode($success);
        }else{
            echo "manquant";
        }
        
    }

if(isset($_GET['delete']) && isset($_GET['gid'])){
    $aid = intval($_GET['delete']);
    $gid = intval($_GET['gid']);
    $uid = intval($_SESSION['id']);
    
    $exist = $db->query("SELECT * FROM note WHERE n_uid = {$uid} AND n_aid = {$aid} AND n_gid = {$gid}");
    $e = $exist->fetch();
    if($e){
        $n = $db->prepare("DELETE FROM note WHERE n_uid = :uid AND n_aid = :aid AND n_gid = :gid");
        $n->bindParam(':uid', $uid);
        $n->bindParam(':aid', $aid);
        $n->bindParam(':gid', $gid);
        $n->execute();
        echo "success";
    }
}
?>