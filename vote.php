
<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include 'skeleton.php';
    if(isset($_GET) && !empty($_GET['gid']) && intval($_GET['gid']) > 0){
        $select = $db->query("SELECT * FROM gender WHERE g_id =". $_GET['gid']);
        $s = $select->fetch();
        ?>

<script>
    var gid = <?php echo $_GET['gid']; ?>;
    var folder = '<?php echo $folder; ?>';
</script>
<script src="<?php echo $folder ?>js/vote.js"></script>

<div class="banniere_vote" style="background: url('<?php echo $folder . $s['g_banner']; ?>') center;background-size:cover;">
<div class="filter_bg">
    <div class="vote_titre"><?php echo $s['g_name']; ?></div>
    <a href="<?php echo $folder; ?>tierlist.php?gid=<?php echo $_GET['gid']; ?>"><div class="back_to_tl"><i class="fas fa-chevron-left"></i></div></a>
</div>
</div>

<?php
    if(!empty($_SESSION)){
        $m = $membre->getById($_SESSION['id']);
        if($_SESSION['permission'] >= 1 || $m->canVote() == 1){
?>
<div class="vote_wrapper">
    <div class="vote_content">
    <div class="anime_cover noanime"><i class="fas fa-plus"></i></div>
        <?php
            $gender = $db->prepare("SELECT * FROM anime_gender WHERE g_id = :gid");
            $gender->bindparam(":gid", $_GET['gid']);
            $gender->execute();
            
            while($g = $gender->fetch()){
                $noted = $db->prepare("SELECT * FROM note WHERE n_uid = :uid AND n_aid = :aid AND n_gid = :gid");
                $noted->bindParam(":uid", $_SESSION['id']);
                $noted->bindParam(":aid", $g['a_id']);
                $noted->bindParam(":gid", $_GET['gid']);
                $noted->execute();
                
                $n = $noted->fetch();
                if($n){
                    $a = $anime->getById($n['n_aid']);
                    
                    echo "<div class='anime_cover' style='background:url(\"". $folder . $a->banner()."\");background-size:cover;'><div class='note_anime_content'><div class='delete_anime_vote'><i class=\"fas fa-times\"></i></div><div class='name_anime_vote'>{$a->name()}</div><div class='vote_input'><input type='hidden' class='aid' name='aid' value='". $a->id() ."'> <input type='input' class='vote_note' id='note' value='". $n['n_note'] ."' name='note'>/20</div></div></div>";
                }
            }
        ?>
        
    </div>
    <div class="btn_voter">Soumettre le vote</div> 
</div>
      
<?php
        }else{
            echo "<p> Vous n'êtes pas un compte validé, confirmez votre compte grâce au lien envoyé sur discord. Si vous n'avez rien reçu contactez un admin</p>";
        }
    }else {
        echo "<p> Vous devez vous connecter pour voter.</p>";
    }
    }
?>