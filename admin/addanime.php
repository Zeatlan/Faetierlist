<?php
    include('../db.php');

    if(isset($_POST) && isset($_POST['name']) && isset($_POST['shortname'])){
        $exist = $db->prepare("SELECT * FROM anime WHERE a_name = :name");
        $exist->bindParam(":name", $_POST['name']);
        $exist->execute();            
        
        $r = $db->query("SHOW TABLE STATUS LIKE 'anime'");
        $row = $r->fetch();
        $id = $row['Auto_increment'];
        
        $e = $exist->fetch();
        if(!$e){
            if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $add = new Anime(array());
                    $add->setName($_POST['name']);
                    $add->setShortName($_POST['shortname']);

                    $add->setBanner(sendFile($db, $_FILES['image'], "../img/anime/", getFirstId($db, "anime")));

                    $anime->add($add);

                    $name = $add->name();
                
                    $valid = $anime->getByName($name);
                    
                    $anime->approve($valid);

                    $agender = $db->prepare("INSERT INTO anime_gender(a_id, g_id) VALUES(:aid, :gid)");
                    $idanime = $db->prepare("SELECT a_id FROM anime WHERE a_name = :name");
                    $idanime->bindParam(":name", $name);
                    $idanime->execute();
                    $ia = $idanime->fetch();

                    $gender = explode(",", $_POST['gender']);
                    if(sizeof($gender) > 0){
                        foreach($gender as $key => $value){
                            $agender->bindParam(":aid", $ia['a_id']);
                            $agender->bindParam(":gid", $value);
                            $agender->execute();
                        }
                    }else {
                        $agender->bindParam(":aid", $ia['a_id']);
                        $agender->bindParam(":gid", $_POST['gender']);
                        $agender->execute();
                        
                    }
                    $m = $membre->getByPseudo($_SESSION['id']);
                    $membre->animeApprovedGain($m);

                    echo "success";
            }
        }else{
            echo "exist";
        }
    }
?>