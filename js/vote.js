$( document ).ready(function() {
    var selection = [];
    var idselection = [];
    var animename = [];
    var note = [];
    var aid = [];
    var indice = 0;

    
    $( ".anime_cover.noanime" ).click(function() {
        $(".popup_body").addClass("visible_popup_body");
        $(".popup_content").addClass("visible_popup_content");
        $(".popup_result_content").addClass("visible_search_content");
        
      });

      $( ".popup_body" ).click(function(e) {
        if (e.target == this) {
            $(".popup_body").removeClass("visible_popup_body");
            $(".popup_content").removeClass("visible_popup_content");
            $(".popup_result_content").removeClass("visible_search_content");
        }
      });
    
    $( ".popup_result_content" ).on("click", ".popup_anime_item", function() {

        $(".popup_body").removeClass("visible_popup_body");
        $(".popup_content").removeClass("visible_popup_content");
        $(".popup_result_content").removeClass("visible_search_content");
        selection.push($(this).attr('style'));
        idselection.push($(this).attr('aid'));
        animename.push($(this).attr('title'));
        indice++;
        
        $( ".noanime" ).after( " <div class='anime_cover' style='"+selection[indice-1]+"'><div class='note_anime_content'><div class='delete_anime_vote'><i class=\"fas fa-times\"></i></div><div class='name_anime_vote'>"+ animename[indice-1] +"</div><div class='vote_input'><input type='hidden' class='aid' name='aid' value='"+ idselection[indice-1] +"'> <input type='input' class='vote_note' id='note' name='note'>/20</div></div></div>" );
        
        $( ".popup_anime_item[aid="+ idselection[indice-1] +"]").remove();
      });
    
    
        
    $(document).on("click", ".btn_voter", function(){

        $(".note_anime_content").each(function(){
            note.push($(this).children('.vote_input').children('.vote_note').val());
            aid.push($(this).children('.vote_input').children('.aid').val());
        });
        
        var that = this;
        
        $.ajax({
            type: "post",
            url: folder+'note.php?gid='+ gid,
            data :  {
                "note" : note,
                "aid" : aid
            },
            success : function(data){
                try {
                    var d = JSON.parse(data);
                    
                    if(d.add == true  && d.maxvote == false){
                        $.getScript(folder+'js/notify.js', function(){
                        notif('<div class="success"><b>Succès</b> Votre note a été ajoutée, votre contribution a augmenté d\'un point !');
                        });
                    }

                    if(d.add == true  && d.maxvote == true){
                        $.getScript(folder+'js/notify.js', function(){
                        notif('<div class="success"><b>Succès</b> Votre note a été ajoutée, vous avez atteint le maximum de vote journalier, vous ne gagnerez plus de contributions avant demain.');
                        });
                    }

                    if(d.update == true){
                        $.getScript(folder+'js/notify.js', function(){
                        notif('<div class="success"><b>Succès</b> Votre note a été modifiée avec succès !');
                        });
                    }
                }catch(e){}

                if(data == "manquant"){
                    $.getScript(folder+'js/notify.js', function(){
                       notif('<div class="error"><b>Erreur</b> Vous n\'avez pas rempli des champs, vérifiez vos votes !');
                    }); 
                }
                
                if(data == "number"){
                    $.getScript(folder+'js/notify.js', function(){
                       notif('<div class="error"><b>Erreur</b> Soyez raisonnable sur la note.');
                    });
                    
                }

                if(data == "numeric"){
                    $.getScript(folder+'js/notify.js', function(){
                        notif('<div class="error"><b>Erreur</b> Veillez entrer une valeur numérique !');
                     }); 
                }
                
                note = [];
                aid = [];
                
            }
        });
    });
    
    $( ".vote_content" ).on("click",".delete_anime_vote", function() {
        $(this).parents(".anime_cover").remove();
        var aid = $(this).parent().children('.vote_input').children('.aid').val();
        var title = $(this).parent().children('.name_anime_vote').text();
        var style = $(this).parents(".anime_cover").attr("style");
        
        
        $(".popup_result_content").prepend("<div class='popup_anime_item' title='"+ title +"' style='"+ style +"' aid='"+ aid +"'> </div>");
     
        $.ajax({
            type: "get",
            url: folder+'note.php?delete='+ aid +'&gid='+ gid,
            cache:false,
            success : function(data){
                if(data == "success"){
                    $.getScript(folder+'js/notify.js', function(){
                       notif('<div class="success"><b>Succès</b> Votre note a été supprimée !'); 
                    }); 
                }
            }
        });
    });

});