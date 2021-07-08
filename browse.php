<?php
    include 'skeleton.php';
?>



<div class="browse_wrapper"> 
<h1><i class="fas fa-th"></i> Tierlists populaires</h1>
<div class="browse_content">
    
    <?php
        $popular = $db->query("SELECT * FROM gender ORDER BY g_nbvote DESC");
        while($p = $popular->fetch()){
            $count = $db->prepare("SELECT count(DISTINCT n_aid) as compter, count(*) as compterdist FROM note WHERE n_gid = :gid AND n_note >= 1");
            $count->bindParam(":gid", $p['g_id']);
            $count->execute();
            $c = $count->fetch();
    ?>
        <div class="item" onclick="document.location.href='<?php echo $folder; ?>tierlist/<?php echo $p['g_id']; ?>'">
            <div class="img_item" style="background:url('<?php echo $p['g_banner']; ?>') center;background-size:cover;"></div>
            <div class="item_titre"><?php echo $p['g_name']; ?></div>
            <div class="stat_wrapper">
            <div class="item_stat"><?php echo $c['compter']; ?> anime<?php echo $c['compter']>1 ? "s": null;?></div>
            <div class="item_stat_nb_vote"><?php echo $c['compterdist']; ?> vote<?php echo $c['compterdist']>1 ? "s": null;?></div>
            </div>
            
        </div>
    
    <?php
        }
    ?>
</div>
</div>

