

<div class="popup_body">

    <div class="popup_content">
        <div class="popup_live_search_content">
            <input class="popup_input_search "type="text" id="popup_search" name="popup_search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Rechercher un anime taggé <?php echo $s['g_name']; ?>...">
        </div>
        
        <div class="popup_result_content">
            <?php
                $used = [];
            
                $genderanime = $anime->getByGender($s['g_id']);
                foreach($genderanime as $key => $value){
                    $n = $note->getByAid($value->id());
                    foreach($n as $key => $valuee){
                        if($valuee->uid() == $_SESSION['id'] && $valuee->gid() == $_GET['gid'])
                            array_push($used, $valuee->aid());
                    }
                    if(!in_array($value->id(), $used) && $value->valid() == 1){
                        echo "<div class='popup_anime_item' title=\"";
                        echo (strlen($value->name()) < 20? $value->name() : ($value->shortName() != ""? (strlen($value->shortName())<20? $value->shortName() : substr($value->shortName(), 0, 20)."...") : substr($value->name(), 0, 20)."..."));
                        echo "\" style='background:url(\"". $folder . $value->banner() ."\") center; background-size:cover;' aid='". $value->id() ."'> </div>";
                    }
                }
        
            ?>
        </div>
    </div>

</div> 
    
    <script>
    $(document).ready(function() {
       $("#popup_search").keyup(function(){
           
           var txt = $(this).val();
           var folder = '<?php echo $folder; ?>';
           if(txt != ''){
               $.ajax({
                   url: folder +'fetch.php',
                   method:'post',
                   data:{search_popup:txt},
                   dataType:'json',
                   success:function(response, statusText){
 
                            if(response != null){
                                for(var j = 0; j < response[1].length; j++){
                                    $('.popup_anime_item[aid='+ response[1][j] +']').slideUp("fast", function(){
                                        $(this).hide();
                                    });
                                }
                                for(var i = 0; i < response[0].length; i++){
                                    $('.popup_anime_item[aid='+ response[0][i] +']').fadeIn("100");
                                }
                            }
                    }
               });
               
           }else{
                $('.popup_anime_item').fadeIn("100");
           }
       });
    });
    </script>