<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include 'skeleton.php';


        if(isset($_GET) && !empty($_GET['gid'])){
            $gid = intval($_GET['gid']);
            $select = $db->prepare("SELECT * FROM gender WHERE g_id = :gid");
            $select->bindParam(':gid', $gid);
            $select->execute();
            $s = $select->fetch();
            
            $GLOBALS['prohibited'] = array();
            
            function generateList($db, $min, $max){
                $folder = "http://". $_SERVER['SERVER_NAME'] . substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')) . "/";
                $animeArray = array();

                $anime = new AnimeManager($db);
                $gid = intval($_GET['gid']);

                $a = $db->prepare("SELECT * FROM anime_gender WHERE g_id = :gid");
                $a->bindParam(":gid", $gid);
                $a->execute();
                while($gender = $a->fetch()){
                    $anim = $anime->getById($gender['a_id']);
                    $note = $db->prepare("SELECT * FROM note WHERE n_aid = :aid AND n_gid = :gid");
                    $note->bindValue(":gid", $gid);
                    $note->bindValue(":aid", $anim->id());
                    $note->execute();
                    $n = $note->fetch();
                    
                    if($n && floor($anime->moyenne($anim, $gid)) >= $min && floor($anime->moyenne($anim, $gid)) <= $max){
                        $id = $anim->id();
                        array_push($GLOBALS['prohibited'], $id);

                        $msg = "<a href=\"{$folder}anime/". $anim->id() ."\">
                        <div class=\"v_content\">
                        <div class=\"anime_name\">";
                        
                        $msg .= (strlen($anim->name()) < 20? $anim->name() : ($anim->shortName() != ""? $anim->shortName() : substr($anim->name(), 0, 20)."..."));
                        
                        $msg .= "</div><div class=\"anime_moy\">". number_format($anime->moyenne($anim, $gid), 2) ."</div>
                            <div class=\"v_img\" style=\"background-image:url('". $folder . $anim->banner()."');background-size: cover;\"></div>
                        </div>
                        </a>";

                        array_push($animeArray, array($anime->moyenne($anim, $gid), $msg));
                    }
                }
                if(sizeof($animeArray) > 0){
                    usort($animeArray, 'sortByFirst');
                    foreach($animeArray as $k => $v){
                        echo $v[1];
                    }
                }
            }

            function sortByFirst($a, $b) {
                if($a[0]==$b[0]) return 0;
                return $a[0] < $b[0]?1:-1;
            }
        ?>

<script src="<?php echo $folder; ?>js/tierlist.js"></script>



<!--BANNIERE TIERLIST -->
<div class="banniere_tierlist" style="background:url('<?php echo $folder . $s['g_banner']; ?>') center;background-size:cover;">
    <div class="filter_bg">
        <div class="content_titre_btn">

            <div class="categorie_titre">
                Tierlist <?php echo ($s['g_name'] == '' ? 'non trouvée':$s['g_name']); ?>

            </div>

            <?php
            if(isset($_SESSION) && isset($_SESSION['id'])){
            $user = $membre->getById($_SESSION['id']);
                if(!empty($_SESSION['permission']) && $_SESSION['permission'] >= 1 || $user->canVote() == 1){?>
            <div class="vote_btn" onclick="document.location.href='<?php echo $folder; ?>vote/<?php echo $s['g_id']; ?>'">Voter</div>
            <?php }else { ?>

            <!-- TODO: <div class="vote_btn_impossible"><i class="fas fa-lock"></i>Vote impossible</div> -->
            <?php } ?>
            <?php } ?>
        </div>



    </div>


</div>
<!-- FIN TIERLIST -->


<div class="content_tierlists">


<?php } 
 ?>

    <!-- SS 
                    17 - 20-->
    <div class="tierlist_wrapper" id="ss">
        <div class="tierlist_rank" id="ss">
            <div class="info_rank"><i class="fas fa-info-circle"></i>
                <div class="rank_titre">SS Tier</div>
                <div class="box_info">
                    <div class="info_box_title">Note Moy</div>
                    <div class="interval_note">17 - 20</div>
                </div>
            </div>
            <div class="nb_anime_rank">
                <div class="content">
                    <?php echo $note->calculatePerTier($_GET['gid'], 17, 20, $GLOBALS['prohibited']); ?> animes</div>
            </div>
        </div>
        <div class="vignettes">
            <div class="v_arrow_left"><i class="fas fa-angle-left"></i></div>
            <div class="all_vignettes">
                <div class="vcontent_slide">

                    <?php
                     generateList($db, 17, 20);
                ?>
                </div>
            </div>
            <div class="v_arrow_right"><i class="fas fa-angle-right"></i></div>
        </div>
    </div>

    <!-- S 
                    14 - 16-->
    <div class="tierlist_wrapper" id="s">
        <div class="tierlist_rank" id="s">
            <div class="info_rank"><i class="fas fa-info-circle"></i>
                <div class="rank_titre">S Tier</div>
                <div class="box_info">
                    <div class="info_box_title">Note Moy</div>
                    <div class="interval_note">14 - 16</div>
                </div>
            </div>
            <div class="nb_anime_rank">
                <div class="content">
                    <?php echo $note->calculatePerTier($_GET['gid'], 14, 16, $GLOBALS['prohibited']); ?> animes</div>
            </div>
        </div>
        <div class="vignettes">
            <div class="v_arrow_left"><i class="fas fa-angle-left"></i></div>
            <div class="all_vignettes">
                <div class="vcontent_slide">
                    <?php
                     generateList($db, 14, 16);
                ?>
                </div>
            </div>
            <div class="v_arrow_right"><i class="fas fa-angle-right"></i></div>
        </div>
    </div>

    <!-- A 
                    11 - 13 -->
    <div class="tierlist_wrapper" id="a">
        <div class="tierlist_rank" id="a">
            <div class="info_rank"><i class="fas fa-info-circle"></i>
                <div class="rank_titre">A Tier</div>
                <div class="box_info">
                    <div class="info_box_title">Note Moy</div>
                    <div class="interval_note">11 - 13</div>
                </div>
            </div>
            <div class="nb_anime_rank">
                <div class="content">
                    <?php echo $note->calculatePerTier($_GET['gid'], 11, 13, $GLOBALS['prohibited']); ?> animes</div>
            </div>
        </div>
        <div class="vignettes">
            <div class="v_arrow_left"><i class="fas fa-angle-left"></i></div>
            <div class="all_vignettes">
                <div class="vcontent_slide">
                    <?php
                     generateList($db, 11, 13);
                ?>
                </div>
            </div>
            <div class="v_arrow_right"><i class="fas fa-angle-right"></i></div>
        </div>
    </div>

    <!-- B 
                    8 - 10 -->
    <div class="tierlist_wrapper" id="b">
        <div class="tierlist_rank" id="b">
            <div class="info_rank"><i class="fas fa-info-circle"></i>
                <div class="rank_titre">B Tier</div>
                <div class="box_info">
                    <div class="info_box_title">Note Moy</div>
                    <div class="interval_note">8 - 10</div>
                </div>
            </div>
            <div class="nb_anime_rank">
                <div class="content">
                    <?php echo $note->calculatePerTier($_GET['gid'], 8, 10, $GLOBALS['prohibited']); ?> animes</div>
            </div>
        </div>
        <div class="vignettes">
            <div class="v_arrow_left"><i class="fas fa-angle-left"></i></div>
            <div class="all_vignettes">
                <div class="vcontent_slide">
                    <?php
                     generateList($db, 8, 10);
                ?>
                </div>
            </div>
            <div class="v_arrow_right"><i class="fas fa-angle-right"></i></div>
        </div>
    </div>

    <!-- C 
                   5 - 7 -->
    <div class="tierlist_wrapper" id="c">
        <div class="tierlist_rank" id="c">
            <div class="info_rank"><i class="fas fa-info-circle"></i>
                <div class="rank_titre">C Tier</div>
                <div class="box_info">
                    <div class="info_box_title">Note Moy</div>
                    <div class="interval_note">5 - 7</div>
                </div>
            </div>
            <div class="nb_anime_rank">
                <div class="content"> <?php echo $note->calculatePerTier($_GET['gid'], 5, 7, $GLOBALS['prohibited']); ?>
                    animes</div>
            </div>
        </div>
        <div class="vignettes">
            <div class="v_arrow_left"><i class="fas fa-angle-left"></i></div>
            <div class="all_vignettes">
                <div class="vcontent_slide">
                    <?php
                     generateList($db, 5, 7);
                ?>
                </div>
            </div>
            <div class="v_arrow_right"><i class="fas fa-angle-right"></i></div>
        </div>
    </div>

    <!-- D 
                    1 - 4-->
    <div class="tierlist_wrapper" id="d">
        <div class="tierlist_rank" id="d">
            <div class="info_rank"><i class="fas fa-info-circle"></i>
                <div class="rank_titre">D Tier</div>
                <div class="box_info">
                    <div class="info_box_title">Note Moy</div>
                    <div class="interval_note">0 - 4</div>
                </div>
            </div>
            <div class="nb_anime_rank">
                <div class="content"> <?php echo $note->calculatePerTier($_GET['gid'], 1, 4, $GLOBALS['prohibited']); ?>
                    animes</div>
            </div>
        </div>
        <div class="vignettes">
            <div class="v_arrow_left"><i class="fas fa-angle-left"></i></div>
            <div class="all_vignettes">
                <div class="vcontent_slide">
                    <?php
                     generateList($db, 1, 4);
                ?>
                </div>
            </div>
            <div class="v_arrow_right"><i class="fas fa-angle-right"></i></div>
        </div>
    </div>