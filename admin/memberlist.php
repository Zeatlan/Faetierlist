<?php include("skeleton.php"); 

    $limitmax = 20;
    
    $total = $db->query("SELECT * FROM membre");
    $totalpage = $total->rowCount();
    if(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 0){
        $_GET['page'] = intval($_GET['page']);
        $page = $_GET['page'];
    }else{
        $page = 1;
    }

    $limitmin = ($page-1)*$limitmax;
?>

<script src="js/memberlist.js"></script>
    <div class="wrapper">
        <div class="container">
            <h1>Liste des membres</h1>
            
            
            <?php
                $memberlist = $db->query("SELECT * FROM membre WHERE u_canVote = 1 OR u_admin = 1 LIMIT ". $limitmin .",". $limitmax);
                while($m = $memberlist->fetch()){
            ?>
            <div class="card" uid="<?php echo $m['u_id']; ?>" adm="<?php echo $m['u_admin']; ?>">
                
                <div class="choice">
                    <div class="edit">Editer</div>
                    <div class="delete">Supprimer</div>
                </div>
                
                <div class="header">
                    <div class="banner" style="background-image: url('../<?php echo $m['u_banner']; ?>')"></div>
                    <div class="avatar" style="background-image: url('../<?php echo $m['u_avatar']; ?>')"></div>
                </div>
                <div class="content">
                    <div class="info">
                        <span class="pseudo"><?php echo $m['u_pseudo']; ?></span>
                        <div><span>Discord</span><span><?php echo $m['u_discord']; ?></span></div>
                        <div><span>Inscrit le</span><span><?php echo date("d/m/Y", $m['u_joinedtime']);?></span>  </div>
                        <div><span>Rank</span><span><?php echo ($m['u_admin']==1)? "Administrateur" : "Membre"; ?></span> </div> 
                    </div>
                </div>
            </div>
            <?php }
                echo '<div class="pagination">';
                for($i = 1; $i <= $totalpage/20; $i++){
                    if($i == $_GET['page']){
                        echo '<i>'. $i .'</i> ';
                    }else{
                        echo '<a href="memberlist.php?page='. $i .'">'. $i .'</a> ';
                    }
                }
                echo '</div>';
            ?>
        </div>
    </div>