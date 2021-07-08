<?php
    include("../db.php");

    function delete($db, $table, $where, $post){
        $delete = $db->prepare("DELETE from ". $table ." WHERE ". $where);
        $delete->bindParam(":id", $post);
        $delete->execute();
        
        if($table == "membre"){
            $folder = "avatar";
            $deleteNote = $db->prepare("DELETE FROM note WHERE n_uid = :id");
        }
        if($table == "anime"){
            $folder = "anime";
            $deleteNote = $db->prepare("DELETE FROM note WHERE n_aid = :id");
        }
        if($table == "gender"){
            $folder = "gender";
            $deleteNote = $db->prepare("DELETE FROM note WHERE n_gid = :id");
        }
        
        $deleteNote->bindParam(":id", $post);
        $deleteNote->execute();
        
            
        if(file_exists('../img/'.$folder.'/'. basename($post) .".png")){
            unlink('../img/'.$folder.'/'. basename($post) .".png");
        }
        
        if(file_exists('../img/'.$folder.'/'. basename($post) .".jpg")){
            unlink('../img/'.$folder.'/'. basename($post) .".jpg");
        }   
    }


    if(isset($_POST) && isset($_POST['user']) && $_POST['user'] != ""){
        delete($db, "membre", "u_id = :id", $_POST['user']);
    }

    if(isset($_POST['aid']) && $_POST['aid'] != ""){
        delete($db, "anime", "a_id = :id", $_POST['aid']);
        delete($db, "anime_gender", "a_id = :id", $_POST['aid']);
    }

    if(isset($_POST['gid']) && $_POST['gid'] != ""){
        delete($db, "gender", "g_id = :id", $_POST['gid']);
        delete($db, "anime_gender", "g_id = :id", $_POST['gid']);
    }
?>