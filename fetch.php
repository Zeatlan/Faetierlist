<?php
    include('db.php');
    if(isset($_POST['search'])){
        if( $_POST['search'] != ""){
            $output = '';
            $output .= "<ul>";
            $search = "%".$_POST['search']."%";

            $select = $db->prepare("SELECT * FROM gender WHERE g_name LIKE ? LIMIT 4");

            $selectAnime = $db->prepare("SELECT * FROM anime WHERE a_name LIKE ? OR a_shortname LIKE '%:search%' LIMIT 4");

            $selectMember = $db->prepare("SELECT * FROM membre WHERE u_pseudo LIKE ? LIMIT 4");
            $select->bindParam('1', $search);
            $selectAnime->bindParam('1', $search);
            $selectMember->bindParam('1', $search);

            $select->execute();
            $selectAnime->execute();
            $selectMember->execute();

            $output .= "<div class='title_res_search'><h2> Tierlist </h2></div>";
            if($select->rowCount() == 0){
                $output .= "";

            }else{
                while($s = $select->fetch()){
                    $output .= "<a href='{$folder}tierlist/{$s['g_id']}'<div class='res_s'><div class='res_s_tierlist_img' style='background:url(\"". $folder . $s['g_banner'] ."\") center;background-size:cover;'></div><li>". $s['g_name'] ."</li></div></a>";
                }
            }


            $output .= "<div class='title_res_search'><h2> Anime </h2></div>";

            if( $selectAnime->rowCount() == 0){
                $output .= "";

            }else{
                while($a = $selectAnime->fetch()){
                    if($a['a_valid'] == 1){
                        $output .= "<a href='{$folder}anime/{$a['a_id']}'><div class='res_s'><div class='res_s_anime_img' style='background:url(\"". $folder . $a['a_banner'] ."\");background-size:cover;'></div><li>";
                        $output .= (strlen($a['a_name']) < 30? $a['a_name'] : ($a['a_shortname'] != ""? (strlen($a['a_shortname']) <30? $a['a_shortname'] : substr($a['a_shortname'], 0, 30)."...") : substr($a['a_name'], 0, 30)."..."));
                        $output .= "</li></div></a>";
                    }
                }
            }

            $output .= "<div class='title_res_search'><h2> Membre </h2></div>";
            if($selectMember->rowCount() == 0){
                $output .= "";
            }else{
                while($m = $selectMember->fetch()){
                    $output .= "<a href='{$folder}profil/{$m['u_id']}'><div class='res_s'><div class='res_s_member_img' style='background:url(". $folder . $m['u_avatar'] .") center;background-size:cover;'></div><li>". $m['u_pseudo'] ."</li></div></a>";
                }
            }
            $output .= "</ul>";
            echo $output;
        }
    }
                
            
    if(isset($_POST['search_popup'])){
        if($_POST['search_popup'] != ""){
            $output = array();
            $input = array();
            $res = array();

            $selectAnime = $db->query("SELECT * FROM anime WHERE a_name LIKE '%".$_POST['search_popup'] ."%'");
            $selectNotAnime = $db->query("SELECT * FROM anime WHERE a_name NOT LIKE '%".$_POST['search_popup'] ."%'");


            if( $selectAnime->rowCount() == 0){
                echo "null";
            }else{
                while($a = $selectAnime->fetch()){
                    array_push($output, $a['a_id']);
                }
                while($aa = $selectNotAnime->fetch()){
                    array_push($input, $aa['a_id']);
                }
                array_push($res, $output);
                array_push($res, $input);
                echo json_encode($res);
            }
        }
    }

    if(isset($_POST['search_popup_anime'])){
        if($_POST['search_popup_anime'] != ""){
            $output = array();
            $input = array();
            $res = array();

            $selectGender = $db->query("SELECT * FROM gender WHERE g_name LIKE '%".$_POST['search_popup_anime'] ."%'");
            $selectNotGender = $db->query("SELECT * FROM gender WHERE g_name NOT LIKE '%".$_POST['search_popup_anime'] ."%'");


            if($selectGender->rowCount() == 0){
                echo "null";
            }else{
                while($g = $selectGender->fetch()){
                    array_push($output, $g['g_id']);
                }
                while($gg = $selectNotGender->fetch()){
                    array_push($input, $gg['g_id']);
                }
                array_push($res, $output);
                array_push($res, $input);
                echo json_encode($res);
            }
        }
    }
?>