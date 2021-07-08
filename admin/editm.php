<?php
    include('../db.php');



    if(isset($_POST)){
        
        $message = array();
        
        if(isset($_GET['uid'])){
            $m = $membre->getById($_GET['uid']);
            if($_POST['pseudo'] != '' && $_POST['pseudo'] != $m->pseudo()){
                $m->setPseudo($_POST['pseudo']);
                array_push($message,"<div class='success' style='display:none'>Le pseudo a été modifié.</div>");
            }

            if($_POST['password'] != '' && md5($_POST['password']) != $m->password()){
                $m->setPassword(md5($_POST['password']));
                array_push($message, "<div class='success' style='display:none'>Le mot de passe a été modifié.</div>");
            }

            if($_POST['canVote'] != '' && $_POST['canVote'] != $m->canVote()){
                $m->setCanVote($_POST['canVote']);
                array_push($message, "<div class='success' style='display:none'>Le droit de vote a été modifié.</div>");
            }

            if($_POST['discord'] != '' && $_POST['discord'] != $m->discord()){
                $m->setDiscord($_POST['discord']);
                array_push($message, "<div class='success' style='display:none'>Le tag discord a été modifié.</div>");
            }

            if($_POST['admin'] != '' && $_POST['admin'] != $m->admin()){
                $m->setAdmin($_POST['admin']);
                if($_POST['admin'] == 1)
                    array_push($message, "<div class='success' style='display:none'>L'utilisateur est désormais un administrateur.</div>");
                else
                    array_push($message, "<div class='success' style='display:none'>L'utilisateur est désormais un membre.</div>");
            }

            if(isset($_FILES) && isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0){
                $m->setAvatar(sendFile($db, $_FILES['avatar'], "../img/avatar/", $_GET['uid']));
                array_push($message, "<div class='success' style='display:none'>L'avatar a été modifié.</div>");
            }

            
            if($_POST['banner'] != '' && $_POST['banner'] != $m->banner()){
                $m->setBanner($_POST['banner']);
                array_push($message, "<div class='success' style='display:none'>La bannière a été modifiée.</div>");
            }
            
            $membre->update($m);

            echo json_encode($message);
        }else if(isset($_GET['aid'])){
            $a = $anime->getById($_GET['aid']);
            
            if($_POST['name'] != '' && $_POST['name'] != $a->name()){
                $a->setName($_POST['name']);
                array_push($message, "<div class='success' style='display:none'>Le nom a été modifié.</div>");
            }
            
            if($_POST['shortname'] != '' && $_POST['shortname'] != $a->shortname()){
                $a->setShortName($_POST['shortname']);
                array_push($message, "<div class='success' style='display:none'>Le nom alternatif a été modifié.</div>");
            }
            
            if(isset($_FILES['banner'])){
                $a->setBanner(sendFile($db, $_FILES['banner'], "../img/anime/", $_GET['aid']));
                array_push($message, "<div class='success' style='display:none'>La bannière a été modifiée.</div>");
            }
                
            $delete = $db->prepare("DELETE FROM anime_gender WHERE a_id = :aid");
            $deletee = $db->prepare("DELETE FROM anime_gender WHERE a_id = :aid AND g_id = :gid");
            
            $aid = $a->id();
            if(isset($_POST['gender'])){
                $exist = $db->prepare("SELECT * FROM anime_gender WHERE a_id = :aid AND g_id = :gid");
                $list = $db->prepare("SELECT * FROM anime_gender WHERE a_id = :aid");
                $list->bindParam(":aid", $aid);
                $list->execute();
                $lgen = array();
                while($l = $list->fetch()){
                    array_unshift($lgen, $l['g_id']);
                }

                $add = $db->prepare("INSERT INTO anime_gender(a_id, g_id) VALUES(:aid, :gid)");

                $del = array();
                
                $genders = explode(",", $_POST['gender']);

                
                foreach($genders as $key => $value){
                    $gid = $value;
                    $exist->bindParam(":aid", $aid);
                    $exist->bindParam(":gid", $gid);
                    $exist->execute();

                    $e = $exist->fetch();
                    if(!$e && $gid){
                        $add->bindParam(":aid", $aid);
                        $add->bindParam(":gid", $gid);
                        $add->execute();
                        array_push($message, "<div class='success' style='display:none'>Un genre a été ajouté.</div>");
                    }else{

                        foreach($lgen as $keyy => $valuee){
                            if(!in_array($valuee, $genders)) array_push($del, $valuee);
                        }
                    }
                }
                
                if(sizeof($del) > 0){
                    foreach($del as $key => $value){
                        $gid = $del[$key];
                        $deletee->bindParam(":aid", $aid);
                        $deletee->bindParam(":gid", $gid);
                        $deletee->execute();
                        array_push($message, "<div class='success' style='display:none'>Un genre a été effacé.</div>");
                    }
                }
            }else{
                
                $delete->bindParam(":aid", $aid);
                $delete->execute();
                array_push($message, "<div class='success' style='display:none'>Les genres ont étaient reset.</div>");
            }
            
            
                
            
            
            echo json_encode($message);
            
            $anime->update($a);
        }else if(isset($_GET['gid'])){
            $gid = intval($_GET['gid']);
            $exist = $db->prepare("SELECT * FROM gender WHERE g_id = :gid");
            $exist->bindParam(":gid", $gid);
            $exist->execute();
            
            $e = $exist->fetch();
            
            if($_POST['name'] != '' && $_POST['name'] != $e['g_name']){
                $name = htmlspecialchars($_POST['name']);
                
                $update = $db->prepare("UPDATE gender SET g_name = :name WHERE g_id = :gid");
                $update->bindParam(":gid", $gid);
                $update->bindParam(":name", $name);
                $update->execute();
                array_push($message, "<div class='success' style='display:none'>Le nom a été modifié.</div>");
            }
            
            if(isset($_FILES['banner'])){
                $upload = sendFile($db, $_FILES['banner'], "../img/gender/", $_GET['gid']);
                
                $update = $db->prepare("UPDATE gender SET g_banner = :banner WHERE g_id = :gid");
                $update->bindParam(":gid", $gid);
                $update->bindParam(":banner", $upload);
                $update->execute();
                array_push($message, "<div class='success' style='display:none'>La bannière a été modifiée.</div>");
            }
            
            echo json_encode($message);
        }
    }
?>