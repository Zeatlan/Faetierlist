<?php 
include 'skeleton.php';
    
    if(isset($_GET) && !empty($_GET['uid'])){
        $uid = intval($_GET['uid']);
        if($uid > 0){
        $m = $membre->getById($uid);
        $countNote = $db->prepare("SELECT COUNT(*) FROM note WHERE n_uid = :uid");
        $countNote->bindParam(':uid', $uid);
        $countNote->execute();
        $c = $countNote->fetch();
        
        $moy = $db->prepare("SELECT FORMAT(AVG(n_note),2) AS moyenne FROM note WHERE n_uid = :uid");
        $moy->bindParam(':uid', $uid);
        $moy->execute();
        $mo = $moy->fetch();
?>



<div class="profil_wrapper">
<div class="pic_profil" style="background:url('<?php echo $folder . $m->avatar(); ?>') center; background-size:cover; <?php echo ($m->admin() == 1)? "border-color:#F74E60" : null; ?>"></div>
    <div class="banniere_membre" style="background:url('<?php echo $m->banner(); ?>') center;background-size:cover;">
            <?php if(isset($_SESSION['id']) && $_SESSION['id'] == $uid) { ?>
    <a href="edit_profil.php"><div class="btn_profil_m"><i class="fas fa-pen"></i></div></a>
            <?php } ?>
        <div class="profil_title_content">
            <div class="profil_pseudo"><?php echo $m->pseudo(); ?></div>
            <div class="profil_mini_info_tierlist"><?php echo $c['COUNT(*)']; echo ($c['COUNT(*)'] > 1)? " votes" : " vote";?> </div>
            <?php if(isset($_SESSION['id']) && $_SESSION['id'] == $uid) { ?>
            <a href="<?php echo $folder; ?>edit_profil.php"><div class="btn_edit_profil"><i class="fas fa-pen"></i></div></a>
            <?php } ?>
        </div>
    </div>
   
    <div class="profil_content">
    <div class="item_stat"> <div class="title_stat">Moyenne globale de ses votes</div>
        <div class="content_item_stat">
            
               <div class="stat_moyenne"><?php echo ($mo['moyenne'] > 0)? $mo['moyenne'] : "0"; ?><sub style="font-size:9pt;">/20</sub></div>

        </div>
    </div>

    <div class="item_stat">
    <div class="title_stat">Top 5 animes</div>
        <div class="content_item_stat">
            <div class="overflow_y">
                <?php
                    $notes = $db->prepare("SELECT * FROM note WHERE n_uid = :uid ORDER BY n_note DESC LIMIT 5");
                    $notes->bindParam(":uid", $uid);
                    $notes->execute();
        
                    while($no = $notes->fetch()){
                        $a = $anime->getById($no['n_aid']);
                        $gender = $db->prepare("SELECT g_name FROM gender WHERE g_id = :id");
                        $gender->bindValue(":id", $no['n_gid']);
                        $gender->execute();
                        $g = $gender->fetch();
                        echo "<div class=\"stat_anime_item\">
                                
                                <a href=\"anime.php?aid={$a->id()}\" class=\"stat_mini_cover\" style=\"background:url('". $folder . $a->banner() ."');background-size:cover;\"></a>
                                
                                <div class=\"stat_anime_info\">
                                <span class=\"stat_name_anime\">";
                                
                        echo (strlen($a->name()) < 20? $a->name() : ($a->shortName() != ""? $a->shortName() : substr($a->name(), 0, 20)."..."));
                        
                        echo "</span>
                                <span class=\"stat_gender_anime\">". $g['g_name'] ."</span>
                                <span class=\"stat_note_anime\">A donné la note de <b>". $no['n_note'] ."/20</b></span>
                                </div>
                            </div>";
                    }
        
                    if($notes->rowCount() == 0){
                        echo "<p style='padding:10px;font-size:10pt;color:rgba(0,0,0,0.5);'>Aucun anime trouvé.</p>";
                    }
                ?>

            </div>
        </div>
    </div>
        
        <?php
            $bestGender = $db->prepare("SELECT *, COUNT(*) FROM note WHERE n_uid = :uid GROUP BY n_gid LIMIT 3");
            $bestGender->bindParam(":uid", $uid);
            $bestGender->execute();
        ?>
    
    <div class="item_stat"> <div class="title_stat">Top 3 catégories</div>
        <div class="content_item_stat">
            
              <div class="content_item_stat_flex">
                  
                  <?php
                        while($bg = $bestGender->fetch()){
                            $banner = $db->query("SELECT * FROM gender WHERE g_id = ". $bg['n_gid']);
                            $b = $banner->fetch();
                            echo "<div class=\"item_stat_cat\" style=\"background:url('". $folder . $b['g_banner']."') center;background-size:cover;\" onclick='document.location.href=\"tierlist.php?gid=". $b['g_id'] ."\"'>
                                    <div class=\"item_stat_cat_name\">". $b['g_name'] ."</div>
                                  </div>";
                        }  if($bestGender->rowCount() == 0){
                            echo "<p style='padding:10px;font-size:10pt;color:rgba(0,0,0,0.5);'>Aucune catégorie trouvée.</p>";
                        }
                    ?>
                  
              </div>

        </div>
    </div>

    </div>
</div>

<?php
        }
    }
?>
