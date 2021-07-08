<?php
    include 'skeleton.php';
    if(isset($_SESSION) && isset($_SESSION['id']) && $_SESSION['id'] != ""){
?>

<div class="content_ranking">
    <div class="title_ranking">Classement des meilleurs contributeurs</div>
    <div class="ranking">
        <div class="ranking_box_you">
            <?php
                    $you = $membre->getById($_SESSION['id']);

                    $id = intval($you->id());
                    $youladder = $db->prepare("SELECT COUNT(*) FROM membre WHERE u_contribution > (SELECT u_contribution FROM membre WHERE u_id = :id)");
                    $youladder->bindParam(':id', $id);
                    $youladder->execute();
                    $yl = $youladder->fetch();
                ?>

            <div class="ranking_box_profil_img"
                style="background:url('<?php echo $folder . $you->avatar(); ?>') center;background-size:cover;">
            </div>
            <div class="ranking_box_infos">
                <div class="you_title_rb">Vous</div>
                <div class="you_pos_rank">
                    <i class="fas fa-trophy"></i> <?php echo $yl['COUNT(*)']+1; if (($yl['COUNT(*)']+1) == 1) { ?>er<?php } else { ?>ème <?php } ?>
                </div>
                <div class="you_score_contrib">Score de contribution :
                    <?php echo ($you->contribution() > 1 ? $you->contribution()." points" : ($you->contribution() != ""? $you->contribution() : "0") ." point"); ?>
                </div>

            </div>
        </div>

        <!--ranking box-->
        <?php
                $max = 15;
                $leaderboard = $db->prepare("SELECT * FROM membre WHERE u_canVote = 1 ORDER BY u_contribution DESC LIMIT 0, :max");
                $leaderboard->bindParam(':max', $max);
                $leaderboard->execute();
                $i = 0;
                while($l = $leaderboard->fetch()){
                    $i++;
            ?>
        <div class="ranking_box">
            <div class="rank_pos"><?php echo $i; ?></div>
            <div class="ranking_box_content">
                <div class="ranking_box_profil_img" 
                    style="background:url('<?php echo $l['u_avatar']; ?>') center;background-size:cover;" onclick="document.location.href='<?php echo $folder .'profil/'. $l['u_id']; ?>'"></div>
                <div class="ranking_box_infos">
                    <div class="ranking_box_pseudo" onclick="document.location.href='<?php echo $folder .'profil/'. $l['u_id']; ?>'" ><?php echo $l['u_pseudo']; ?></div>
                    <div class="score_contrib">
                        <?php echo ($l['u_contribution'] > 1 ? $l['u_contribution'] . " points" : $l['u_contribution'] ." point"); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
                }
            ?>
        <!--end ranking box-->


    </div>
    <?php if($i >= 15) {?>
    <div class="btn_more_ranks">Afficher plus</div>
    <?php } ?>
</div>
<?php
    }
?>

<script>
$(document).ready(function() {
    var maxx = <?php echo $max; ?>;
    $(".btn_more_ranks").click(function() {
        var that = this;
        $.ajax({
            cache: false,
            type: "post",
            url: "showmore.php",
            data: {
                max: maxx
            },
            success: function(data) {
                d = JSON.parse(data);
                $(".ranking").append(d.rank);

                maxx = d.max;

                if (d.delete === true) {
                    $(that).remove();
                }
            }
        });
    });
});
</script>