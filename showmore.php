<?php
    include('db.php');

    if(isset($_SESSION) && isset($_SESSION['id']) && $_SESSION['id'] != ""){
        if(isset($_POST) && $_POST['max'] != ""){
            $max = intval($_POST['max']);
            $maxnext = $max+3;
            $append = array(
                "rank" => array(),
                "max" => $maxnext,
                "delete" => false
            );

            $fetch = $db->prepare("SELECT * FROM membre WHERE u_canVote = 1 ORDER BY u_contribution DESC LIMIT :max, :maxnext");
            $fetch->bindParam(':max', $max);
            $fetch->bindParam(':maxnext', $max);
            $fetch->execute();
            $i = $max;
            while($f = $fetch->fetch()){
                $i++;
                array_push($append["rank"], "<div class='ranking_box'><div class='rank_pos'>". $i ."</div><div class='ranking_box_content'><div class='ranking_box_profil_img' style='background:url(". $f['u_avatar'] .") center;background-size:cover;' onclick='document.location.href=" echo $folder .'profil/'. $l['u_id']; "'></div><div class='ranking_box_infos'><div class='ranking_box_pseudo' onclick='document.location.href=" echo $folder .'profil/'. $l['u_id']; "' >". $f['u_pseudo'] ."</div><div class='score_contrib'>". $f['u_contribution'] . " points</div></div> </div></div>");
            }
            if($i != $max+15){
                $append["delete"] = true;
            }

            echo json_encode($append);
        }
    }
?>