<?php
    include('../db.php');

    if(isset($_POST)){
        if(isset($_POST['name']) && $_POST['name'] != ""){
            $r = $db->query("SHOW TABLE STATUS LIKE 'gender'");
            $row = $r->fetch();
            $id = $row['Auto_increment'];
            
            $exist = $db->prepare("SELECT * FROM gender WHERE g_name = :name");
            $exist->bindValue(":name", $_POST['name']);
            $exist->execute();
            $e = $exist->fetch();
            
            if(!$e){
                if(isset($_FILES['banner'])){
                    $upload = sendFile($db, $_FILES['banner'], "../img/gender/", getFirstId($db, "gender"));
                    
                    $insert = $db->prepare("INSERT INTO gender(g_name, g_banner) VALUES(:name, :banner)");
                    $insert->bindValue(":name", $_POST['name']);
                    $insert->bindValue(":banner", $upload);
                    $insert->execute();
                    
                    echo "Genre ajouté avec succès !";
                }
            }else{
                echo "Un genre de ce même nom existe déjà !";
            }
        }
    }
?>