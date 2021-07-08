<?php
    include('skeleton.php');
?>

<div class="wrapper">
    <div class="container">
        <h1>Restreindre un membre</h1>
    
    
        <form class="restrict">
            <input type="text" name="pseudo" placeholder="Pseudonyme" autocomplete="off" required> <input type="submit" value="Restreindre">
        </form>
        
        <div class="msgstate">
        
        </div>
    </div>
</div>

<div class="wrapper">
    <div class="container">
        <h1>Liste des membres restreints</h1>
        
        <?php
            $m = $membre->getList();
            foreach($m as $key => $value){
                if($m[$key]->canVote() == -1)
                    echo "<p>". $m[$key]->pseudo() ." <button class='unrestrict' uid='". $m[$key]->id() ."'>Unrestrict</button></p>";
            }
        ?>
    </div>
</div>


<div class="wrapper">
    <div class="container">
        <h1>Membres suspects</h1>
        <div class="suspicious">
            <?php
                $getMinimal = $db->query("SELECT COUNT(*), n_uid FROM note WHERE n_note < 8 GROUP BY n_uid ORDER BY COUNT(*) DESC");
                while($gm = $getMinimal->fetch()){
                    $m = $membre->getById($gm['n_uid']);
                    echo "<p>[". $m->pseudo() ." a <strong>". $gm['COUNT(*)'];
                    echo ($gm['COUNT(*)'] > 1)? " notes ":" note ";
                    echo "</strong> en dessous de 8]</p>";
                }
            ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("form").submit(function(e){
            e.preventDefault();
            
            $("form.restrict input").attr('disabled', true);
            $.ajax({
               type:"post",
                url:"fetchmember.php",
                data:{'username':$(this).children("input[type='text']").val()},
                success: function(data){
                    $("form.restrict input").attr('disabled', false);
                    $("form.restrict input[type='text']").val("");
                    $(".msgstate").append(data);
                    $(".msgstate").find(".msg").slideDown("fast");
                    $(".msgstate").parent().find(".msg").delay(2000).hide('slow', function(){$(this).remove(); });
                }
            });
            
        }); 
        
        $("button.unrestrict").click(function(){
            var that = $(this);
            $.ajax({
               type:"post",
                url:"fetchmember.php",
                data:{'unrestrict':$(this).attr("uid")},
                success: function(data){
                    that.parent().hide('slow', function(){$(this).remove();});
                    $(".msgstate").append(data);
                    $(".msgstate").find(".msg").slideDown("fast");
                    $(".msgstate").parent().find(".msg").delay(2000).hide('slow', function(){$(this).remove(); });
                }
            });
        });
    });
</script>