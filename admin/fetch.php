<?php
    include('../db.php');

    if(isset($_GET) && empty($_POST)){
        $newContent = $db->query("SELECT * FROM anime WHERE a_id > ". $_GET['idanim']);
        while($n = $newContent->fetch()){
            echo '<tr aid="'. $n['a_id'] .'" class="anime"><td class="banner"><img src="'. $n['a_banner'] .'" width="75px" height="75px"></td><td>'. $n['a_name'] .'</td><td>'. findGenders($db, $n['a_id']) .'</td><td class="tool"><div class="edit">Editer</div><div class="delete">Supprimer</div></td></tr>'; 
        }
    }

    if(isset($_POST)){
        $a = $anime->getByName(htmlspecialchars($_POST['name']));
        
        if($a->id() != null){
            echo "editanime.php?aid={$a->id()}";
        }else{
            echo "error.php";
        }
    }
?>