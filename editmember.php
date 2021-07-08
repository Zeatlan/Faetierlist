<?php

    include('db.php');


    if(isset($_FILES) && !empty($_FILES)){
        $m = $membre->getById($_SESSION['id']);
        
        $uploaddir = 'img/avatar/';
        $uploadfile = $uploaddir . basename($_SESSION['id'] .".". pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));

        if(file_exists('img/avatar/'. basename($_SESSION['id']) .".png")){
            unlink('img/avatar/'. basename($_SESSION['id']) .".png");
        }
        
        if(file_exists('img/avatar/'. basename($_SESSION['id']) .".jpg")){
            unlink('img/avatar/'. basename($_SESSION['id']) .".jpg");
        }
        
        move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadfile);
        $m->setAvatar($uploadfile);
        $membre->update($m);
        
        if($_FILES['avatar']['size'] < 2097152){
            echo "avatar";
        }else{
            echo "fat";
        }
    }

    if(isset($_POST) && !empty($_POST)){
        if(isset($_POST['password_old']) && $_POST['password_old'] != "" && isset($_POST['password_new']) && $_POST['password_new'] != "" && isset($_POST['password_confirm']) && $_POST['password_confirm'] != ""){
            $m = $membre->getById($_SESSION['id']);
            
            if($m->password() == md5($_POST['password_old'])){
                if($_POST['password_new'] == $_POST['password_confirm']){
                    $m->setPassword(md5($_POST['password_new']));
                    $membre->update($m);
                    echo "ok";
                }else{
                    echo "noot";
                }
            }else{
                echo "old";
            }
        }
    }
?>