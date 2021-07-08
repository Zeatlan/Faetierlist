
<div class="popup_body">

    <div class="popup_content">
        <div class="popup_live_search_content">
            <input class="popup_input_search "type="text" id="popup_search" name="popup_search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Rechercher une catégorie...">
        </div>
        
        <div class="popup_result_content">
            <?php
                $genders = $db->query("SELECT * FROM gender ORDER BY g_id");
                while($g = $genders->fetch()){
                    echo "<div class=\"ad_cat_item_pop\" gid=\"{$g['g_id']}\" style='background:url({$g['g_banner']}) center;background-size:cover;'><div class=\"cat_name_item\">";
                    echo (strlen($g['g_name']) > 20 )? $g['g_shortname']: $g['g_name'];
                    echo "</div></div>";
                    
                }
            
            ?>
        </div>
    </div>

</div> 

<script>
    $(document).ready(function(){
        $("#popup_search").keyup(function(){
 
           var txt = $(this).val();
           if(txt != ''){
               $.ajax({
                   url:'fetch.php',
                   method:'post',
                   data:{search_popup_anime:txt},
                   dataType:'json',
                   success:function(response, statusText){
                        if(response != null){
                            for(var j = 0; j < response[1].length; j++){
                                $('.ad_cat_item_pop[gid='+ response[1][j] +']').slideUp("fast", function(){
                                    $(this).hide();
                                });
                            }
                            for(var i = 0; i < response[0].length; i++){
                                $('.ad_cat_item_pop[gid='+ response[0][i] +']').fadeIn("100");
                            }
                        }
                    }
               });
               
           }else{
                $('.ad_cat_item_pop').fadeIn("100");
           }
       }); 
    });
</script>