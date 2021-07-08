<?php
    include 'skeleton.php';
    if(isset($_GET['aid']) && $_GET['aid'] != "" && is_numeric($_GET['aid'])){
        $aid = intval($_GET['aid']);
        $a = $anime->getById($aid);

        if($a->valid() == 1){
?>
<div class="banner_anime">
            <div class="anime_title">
                <?php echo $a->name(); ?>
            </div>
            <div class="bg_banner_anime" style="background:url('<?php echo $folder . $a->banner(); ?>');background-size:cover;background-position:50%;">
            </div>
    <div class="anime_cover" style="background:url('<?php echo $folder . $a->banner(); ?>');background-size:cover;"></div>
        </div>
        <div class="moy_gen_wrapper">
            <div class="moy_gen_title">Moyenne générale de l'anime</div> 
    <div class="separator"></div>
    <div class="moy_anime"><?php echo (number_format($anime->moyenneGlobal($a), 2) > 0)? number_format($anime->moyenneGlobal($a), 2) : '-'; ?><sub style="font-size:10pt;">/20</sub></div>
</div>

<div class="content_anime">
<div class="wrapper_content_tl">

    <div class="tl_content">

        <?php
            $genders = $db->prepare("SELECT * FROM anime_gender WHERE a_id = :aid");
            $genders->bindParam(":aid", $aid);
            $genders->execute();
            $gender = $db->prepare("SELECT * FROM gender WHERE g_id = :gid");
            while($gg = $genders->fetch()){  
                $gender->bindValue(':gid', $gg['g_id']);
                $gender->execute();
                $g = $gender->fetch();
        ?>
        <div class="item" onClick="window.location.href = '<?php echo $folder; ?>tierlist/<?php echo $g['g_id']; ?>';">
            <div class="bg_item" style="background:url('<?php echo $folder . $g['g_banner']; ?>')center;background-size:cover;"></div>
            <div class="title_item"><?php echo $g['g_name']; ?></div>
            <div class="rank_item"><?php echo ($anime->getTier($a, $g['g_id']) != null? $anime->getTier($a, $g['g_id']) : "-"); ?></div>
        </div>
        <?php } ?>


    </div>

    
</div>
<div class="wrapper_content_last_vote"><div class="title_last_vote">Derniers votes</div>

    <div class="lv_content">
        
        <?php 
            $tab = $note->lastVote($a);
            $g = $db->prepare("SELECT * FROM gender WHERE g_id = :id");
        
            foreach($tab as $key => $value){
                $m = $membre->getById($value->uid());
                $g->bindValue(':id', $value->gid());
                $g->execute();
                $gg = $g->fetch();
        ?>

        <div class="lv_item">
            <div class="lv_avatar" style="background: url('<?php echo $folder . $m->avatar(); ?>') center; background-size:cover;"></div>
            <div class="lv_pseudo"><?php echo $m->pseudo() ." <i>[". $gg['g_name'] ."]</i>"; ?></div>
            <div class="lv_note"><?php echo $value->note(); ?></div>
        </div>
        <?php
            }
        ?>


    </div>



</div>

</div>

<?php
        }
    }
?>
