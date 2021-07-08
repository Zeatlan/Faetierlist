<?php

    include('db.php');

    if(isset($_POST)){
        $a = new Anime(array("", "", "", "", ""));
        
        if(isset($_POST['name']) && $_POST['name'] != ""){
            $r = $db->query("SHOW TABLE STATUS LIKE 'anime'");
            $row = $r->fetch();
            $id = $row['Auto_increment'];
            
            $exist = $anime->getByName($_POST['name']);
            
            if($exist->id() == null){
                
                $a->setName(htmlspecialchars($_POST['name']));
        
               if(isset($_POST['nameshort']) && $_POST['nameshort'] != ""){
                    $a->setShortName(htmlspecialchars($_POST['nameshort']));
                }
                            
                if(isset($_FILES['banner'])){
                        $uploaddir = 'img/anime/';
                        $uploadfile = $uploaddir . basename($id .".". pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION));

                        if(file_exists('img/anime/'. basename($id) .".png")){
                            unlink('img/anime/'. basename($id) .".png");
                        }

                        if(file_exists('img/anime/'. basename($id) .".jpg")){
                            unlink('img/anime/'. basename($id) .".jpg");
                        }

                        move_uploaded_file($_FILES['banner']['tmp_name'], $uploadfile);

                        if($_FILES['banner']['size'] < 2097152){
                            $a->setBanner($uploadfile);
                        }else{
                        }
                    
                    //$a->setBanner(sendFile($db, $_FILES['banner'], "img/anime/", getFirstId($db, "anime")));
                }

                if(isset($_POST['categories']) && $_POST['categories'] != ""){
                    $categories = explode(",", $_POST['categories']);

                    $gender = $db->prepare("INSERT INTO anime_gender(a_id, g_id) VALUES(:aid, :gid)");
                    for($i = 0; $i < sizeof($categories)-1; $i++){
                        $gender->bindValue(':aid', $id);
                        $gender->bindValue(':gid', $categories[$i]);
                        $gender->execute();

                    }
                }
                $log = $db->prepare("INSERT INTO log_anime(l_uid, l_aid) VALUES(:uid, :aid)");
                $log->bindValue(':uid', $_SESSION['id']);
                $log->bindValue(':aid', $id);
                $log->execute();
                
                $anime->add($a);
                echo "success";
            }else{
                echo "error";
            }

        }
        
        
    }
?>