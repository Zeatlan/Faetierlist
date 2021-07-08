<?php
    session_start();

    try {
        $db = new PDO('mysql:host=localhost;dbname=faetierlist', 'root', '', array(
           PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    }
    catch (PDOException $error) {
        die("Connexion erreur : ". $error->getMessage());
    }
    $folder = "http://". $_SERVER['SERVER_NAME'] . substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')) . "/";

    $class = "class/";
    if(is_dir($class)){
        if($dh = opendir($class)){
            while(($file = readdir($dh)) !== false){
                if($file != "." && $file != "..")
                    include $class . $file;
            }
            closedir($dh);
        }
    }else{
        $class = "../class/";
        if($dh = opendir($class)){
            while(($file = readdir($dh)) !== false){
                if($file != "." && $file != "..")
                    include $class . $file;

            }
            closedir($dh);
        }
    }
    $membre = new MemberManager($db);
    $tierlist = new TierlistManager($db);
    $anime = new AnimeManager($db);
    $note = new NoteManager($db);
    $log = new LogsManager($db);
    $restriction = new RestrictionManager($db);


    function notif($type, $msg){
            $final = str_replace("'", "\'", $msg);
            echo "<script>$.getScript(\"js/notify.js\", function(){
                        notif('<div class=\"". $type ."\"><b>";
            echo ($type=='success')? 'Succès' : 'Erreur';
            echo "</b> ". $final ." </div>');
                        });
                    </script>";   
    }

    function findGenders($db, $ai){
        $goutput = "";
                        
        $aid = $ai;
        $getgenders = $db->prepare("SELECT * FROM anime_gender WHERE a_id = :aid");
        $getgenders->bindParam(":aid", $aid);
        $getgenders->execute();
                        
        $count = $getgenders->rowCount();
        $i = 0;
                        
        while($gg = $getgenders->fetch()){
            $i++;
            $gid = $gg['g_id'];
            $g = $db->prepare("SELECT g_name FROM gender WHERE g_id = :gid");
                            $g->bindParam(":gid", $gid);
            $g->execute();
            $gender = $g->fetch();
            if($i < $count)
                $goutput .= $gender['g_name'] .", ";
            else
                $goutput .= $gender['g_name'];
        }
        
        return $goutput;
    }

    function sendFile($db, $name, $path, $auto){
            if ($name['error'] === UPLOAD_ERR_OK) {
                
                if(file_exists($path. basename($auto) .".png")){
                    unlink($path. basename($auto) .".png");
                }

                if(file_exists($path. basename($auto) .".jpg")){
                    unlink($path. basename($auto) .".jpg");
                }
                
                if($path == "../img/avatar" || $path == "../img/anime")
                    $size = 2097152;
                else
                    $size = 5242880;
                
                if(pathinfo($name['name'], PATHINFO_EXTENSION) == "png" || pathinfo($name['name'], PATHINFO_EXTENSION) == "jpg" && $name['size'] < $size){
                    $uploaddir = $path;
                    $uploadfile = $uploaddir . basename($auto .".". pathinfo($name['name'], PATHINFO_EXTENSION));

                    move_uploaded_file($name['tmp_name'], $uploadfile);
                    
                    $check = substr($uploadfile, 0, 3);
                    
                    if($check == "../"){
                        $result = substr($uploadfile, 3);
                        return $result;
                    }
                    return $uploadfile;
                }else{
                    echo "size";
                }
            }else{
                die("ERROR dans l'upload du fichier : ". $name['error']);
            }
    }

    function getFirstId($db, $table){
        $r = $db->query("SHOW TABLE STATUS LIKE '{$table}'");
        $row = $r->fetch();
        $id = $row['Auto_increment'];
        
        return $id;
    }
?>