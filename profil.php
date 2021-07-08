<?php 
include 'skeleton.php'; 

    if(isset($_GET) && isset($_GET['uid']) && intval($_GET['uid']) > 0){
        $m = $membre->getById(intval($_GET['uid']));
        $classement = $membre->classementContribution($m);

        function moyenneVoteSite($db){
            $q = $db->query("SELECT AVG(n_note) AS moyenne FROM note");
            $data = $q->fetch();
            return number_format($data['moyenne'], 2);
        }

        function favoriteAnime($db, $member) {
            $id = intval($member->id());
            // Trouver la meilleure moyenne
            $q = $db->prepare("select n_aid, avg_note as moyenne from (
              select n_aid , avg(n_note) as avg_note 
              from note
              where n_uid = ?
              group by n_aid
            ) sal1
            where avg_note=(select  max(avg_note) 
                          from (select n_aid , avg(n_note) as avg_note 
                                from note where n_uid = ? group by n_aid) sal2)");
            $q->bindParam("1", $id);
            $q->bindParam("2", $id);
            $q->execute();
            $d = $q->fetch();
            $data = [];
            array_push($data, $d['moyenne']);

            // Trouver l'anime en question
            $qq = $db->prepare("SELECT * FROM anime WHERE a_id = :id");
            $qq->bindParam(":id", $d['n_aid']);
            $qq->execute();
            $dd = $qq->fetch();
            array_push($data, $dd);

            return $data;
        }

        function bestGender($db, $member) {
            $id = intval($member->id());
            $q = $db->prepare("SELECT n_gid, COUNT(n_note) as total FROM note WHERE n_uid = :id GROUP BY n_gid ORDER BY total desc");
            $q->bindParam(':id', $id);
            $q->execute();
            $d = $q->fetch();
            $data = [];
            array_push($data, $d['total']);

            // get gender
            $qq = $db->prepare("SELECT * FROM gender WHERE g_id = :gid");
            $qq->bindParam(':gid', $d['n_gid']);
            $qq->execute();
            $dd = $qq->fetch();
            array_push($data, $dd);

            return $data;
        }
?>

<div class="profil_content">
    <div class="profil_head">
    <!-- onegai faudrait garder cette image elle rend trop bien pour le moment :3 -->
        <div class="profil_banner" style=" background: url('https://cache.desktopnexus.com/cropped-wallpapers/1510/1510949-1920x1080-[DesktopNexus.com].jpg') center;
            background-size: cover;">

        </div>
        <div class="profil_infos_content">
            <div class="img_profil"
                style="background:url('<?php echo $folder . $m->avatar(); ?>') center;background-size:cover;">
            </div>
            <div class="profil_infos_content_content">
                <div class="profil_pseudo"><span class="pseudo"><?php echo $m->pseudo(); ?></span>
                    <!--Uniquement 10 premiers
                    Couleur premier : #E3BE4A Title : "Classé premier"
                    Couleur deuxième : #8E8E92 Title : "Classé second"
                    Couleur troisième : #BC7E4E Title : "Classé trosième"
                    Couleur reste : #A8B8D4 Title : "Classé 4ème" chiffre+ème
                    -->
                    <?php
                        $title = "";
                        $color = "";
                        if($classement == 1){
                            $title = "Classé premier";
                            $color = "#E3BE4A";
                        }else if($classement == 2){
                            $title = "Classé second";
                            $color = "#8E8E92";
                        }else if($classement == 3){
                            $title = "Classé troisième";
                            $color = "#BC7E4E";
                        }else {
                            $title = "Classé {$classement}ème";
                            $color = "#A8B8D4";
                        }
                    ?>
                    <span class="classement" style="background:<?php echo $color; ?>;"><i class="fas fa-trophy"></i> <?php echo $title; ?></span></div>
                <!-- Futurs titres, pour le moment on met membre ou admin 
                style="background:#FC435F;" à rajouter pour les admins -->
                <div class="titre" <?php echo ($m->admin() == 1 ? "style='background:#FC435F;' > Admin" : null ." > Membre" ); ?></div> 
                <div class="profil_stat_content">
                    <div class="stat"><span><?php echo $membre->totalVote($m); ?></span> votes</div>
                    <div class="stat"><span><?php echo ($m->contribution() == 0 ? "0" : $m->contribution()); ?></span> points</div>
                </div>

            </div>
        </div>
        <?php if(isset($_SESSION) && isset($_SESSION['id']) && $m->id() == $_SESSION['id']){ ?>
        <a href="<?php echo $folder; ?>edit_profil.php"><div class="edit_profil">Editer le profil</div></a>
        <?php } ?>
    </div>

    <div class="profil_box">
        <div class="box_item">
            <div class="sub_item">
                <div class="title_item">Note Moy.</div>
                <div class="note_value"><?php echo $membre->moyenneVote($m); ?></div>
            </div>
            <div class="sub_item">
            <!-- Calculer la moyenne de toutes les notes du site -->

                <div class="title_item">Moy. du site</div>
                <div class="note_value_moy"><?php echo moyenneVoteSite($db); ?></div>
            </div>
            <div class="sub_item">
                <div class="title_item">Notation</div>
                <!-- Pour le moment on va établir un truc simple (qu'on pourra améliorer dans le turfu)5AC657
                 [ 0 ; {MOYENNE DU SITE} - 2 ] = "Sévère" -- couleur : #FA5151  
                 [{MOYENNE DU SITE} - 2 ; {MOYENNE DU SITE} + 2] = "Normale" --couleur : #77E0F5 
                 [{MOYENNE DU SITE} + 2 ; 20] = "Indulgente" -- couleur : #5AC657 
                 -->
                 <?php
                    $trait = "";
                    $color = "";
                    $moyMembre = $membre->moyenneVote($m);
                    $moySite = moyenneVoteSite($db);
                    if($moyMembre >= 0 && $moyMembre <= $moySite-2){
                        $trait = "Sévère";
                        $color = "#FA5151";
                    }else if($moyMembre >= $moySite-2 && $moyMembre <= $moySite+2){
                        $trait = "Normale";
                        $color = "#77E0F5";
                    }else if($moyMembre <= 20 && $moyMembre >= $moySite+2){
                        $trait = "Indulgente";
                        $color = "#5AC657";
                    }
                    
                 ?>
    
                <div class="trait_value" style=" background:<?php echo $color; ?>;"><?php echo $trait; ?></div>
            </div>

        </div>
        <div class="box_item_c">
            <div class="title_item ">Anime favori</div>

            <?php
                $favorite = favoriteAnime($db, $m);
                $anim = $anime->getById($favorite[1][0]);
            ?>

            <div class="sub_item">
                <a href="<?php echo $folder ."anime/". $anim->id(); ?>" class="anime_cover"
                    style="background:url('<?php echo $folder . $anim->banner(); ?>') center;background-size:cover;">
                </a>
                <div class="stat">
                <a href="<?php echo $folder ."anime/". $anim->id(); ?>">
                    <div class="name"><?php echo (strlen($anim->name()) < 30? $anim->name() : ($anim->shortName() != "" ? (strlen($anim->shortName()) < 30 ? $anim->shortName() : substr($anim->shortName(), 0, 30)."..."): substr($anim->name(), 0, 30)."...")); ?></div>
                    </a>
                    <div class="moy">Note moyenne
                    <!--  L'anime qui à la meilleure moyenne 
                    exple : je note  Re:ZERO  :  DRAME : 18   -  ROMANCE : 15  -  ISEKAI : 17  ==> Moyenne 
                    donc l'anime qui à la meilleure moyenne sera ici ! -->
                        <span class="note"><?php echo number_format($favorite[0], 2); ?></span>
                    </div>
                </div>
                </a>

            </div>
        </div>

        <?php
            $bestGender = bestGender($db, $m);
        ?>
        <div class="box_item_c">
            <div class="title_item ">Genre favori</div>
            <div class="sub_item">
            
                <a href="<?php echo $folder ."tierlist/". $bestGender[1]['g_id']; ?>" class="genre_cover"
                    style="background:url('<?php echo $folder . $bestGender[1]['g_banner']; ?>') center;background-size:cover;">
                </a>
                <div class="stat">
                <a href="<?php echo $folder ."tierlist/". $bestGender[1]['g_id']; ?>">
                    <div class="name"><?php echo $bestGender[1]['g_name']; ?></div> </a>
                    <div class="moy">
                     <!-- Bon là c'est le genre qui a le plus de votes qui est favoris logiquement -->
                        <span class="note"><?php echo $bestGender[0]; ?></span>
                        votes
                    </div>
                </div>
                </a>
            </div>
        </div>
    </div>

</div>
<?php 
    }
    ?>
