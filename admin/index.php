<?php include("skeleton.php"); ?>
        
        <div class="wrapper">
            <div class="container stats">
                <h1>Administration</h1>
                <h2>Statistiques</h2>
                
                <?php
                
                    function countData($db, $from, $where = ""){
                        if($where != "" || $where != null){
                            $count = $db->query("SELECT COUNT(*) as count FROM ". $from ." WHERE ". $where);
                        }else{
                            $count = $db->query("SELECT COUNT(*) as count FROM ". $from);
                        }
                        
                        $c = $count->fetch();
                        
                        return $c['count'];
                    }
                ?>
                
                <ul>
                    <div class="member list"><?php echo "<span>". countData($db, "membre", "u_canVote = 1 OR u_admin = 1") ."</span> membres (dont ". countData($db, "membre", "u_canVote = 0") ." non-validés)"; ?></div>
                    <div class="animes list"><?php echo "<span>". countData($db, "anime") ."</span> animes enregistrés"; ?></div>
                    <div class="gender list"><?php echo "<span>". countData($db, "gender") ."</span> genres enregistrés"; ?></div>
                </ul>
                
                <div class="tender">
                    <?php
                        $popular = $db->query("SELECT * FROM gender ORDER BY g_nbvote DESC");
                        $i = 0;
                        while($p = $popular->fetch()){
                            if($i == 0)
                                $best = $p['g_nbvote'];
                            
                            $i++;
                            
                            $count = $db->prepare("SELECT count(n_aid) as compter FROM note WHERE n_gid = :gid AND n_note >= 5");
                            $count->bindParam(":gid", $p['g_id']);
                            $count->execute();
                            $c = $count->fetch();
                            echo "<div class='gender_pro'><div class='progress'>". $p['g_name'] ." [". $c['compter'] ." votes]</div>
                                <div class='bar-wrap'><span class='bar-fill' gender='". $p['g_id'] ."' style='width:100%;'></span></div></div>";
                            echo "<script>fill(". $p['g_nbvote'] .", ". $p['g_id'] .", ". $best .");</script>";
                        }
                    ?>
                </div>
                
                
            </div>
        </div>    
        
        <div class="wrapper">
            <div class="container member">
                <h1>Valider un membre</h1>
                <?php 
                $invalid = $db->query("SELECT * FROM membre WHERE u_canVote = 0 AND u_admin = 0 ORDER BY u_joinedtime DESC");
                while($i = $invalid->fetch()){
                    ?>
                <div class="validate_member" uid="<?php echo $i['u_id']; ?>">
                    <div class="user">
                        <div class="avatar" style="background-image:url('<?php echo $i['u_avatar']; ?>');"></div>
                        <div class="infouser">
                            <div class="pseudo"><?php echo $i['u_pseudo']; ?></div>
                            <div class="discord"><?php echo $i['u_discord']; ?></div>
                        </div>
                    </div>
                    
                    <div class="choice">
                        <button class="validate">Valider</button>
                        <button class="delete">Supprimer</button>
                    </div>
                </div>
                
                <?php } ?>
            </div>
        </div>

        <script>
            $(document).ready(function(){
               $(".validate").click(function(){
                  $.ajax({
                     url:"member_verif.php",
                     type: "POST",
                     data: {"user": $(this).parents(".validate_member").attr("uid"),
                            "type": "validate"
                           },
                      success: function(data){
                            $(".validate").parents(".validate_member[uid="+ jQuery.parseJSON(data)[1] +"]").css("background-color", jQuery.parseJSON(data)[0]).fadeOut("slow");
                      }
                  });
               });
                
                $(".delete").click(function(){
                  $.ajax({
                     url:"member_verif.php",
                     type: "POST",
                     data: {"user": $(this).parents(".validate_member").attr("uid"),
                            "type": "delete"
                           },
                      success: function(data){
                            $(".delete").parents(".validate_member[uid="+ jQuery.parseJSON(data)[1] +"]").css("background-color", jQuery.parseJSON(data)[0]).fadeOut("slow");
                      }
                  });
               });
            });
        </script>