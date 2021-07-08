 $(document).ready(function(){
        $(".card").children(".content").children(".info").children("div").hide();
        
        var doingSomething = 0;
        var timer;
        
        $(".card").hover(function(){
            var that = this;
            timer = setTimeout(function(){
               $(that).find(".choice").addClass("hoverChoice");
               $(that).addClass("hoverCard");
                $(that).children(".content").children(".info").children("div").show(500);
            }, 100);
        }, function(){
            if(doingSomething == 0){
               $(this).find(".choice").removeClass("hoverChoice");
               $(this).removeClass("hoverCard");    
            $(this).children(".content").children(".info").children("div").hide(500);
                clearTimeout(timer);
            }
        });
        
        $(".edit").click(function(){
            if($(this).parents(".card").attr("adm") == 0)
                document.location.href = "editmember.php?uid="+ $(this).parents(".card").attr("uid"); 
            else
                alert("Vous ne pouvez pas éditer un admin !");
        });
        
        $(".delete").click(function(){
            doingSomething = 1;
            
            var thisdelete = $(this);
            var parent = $(this).closest(".card").attr("uid");
            
            if($(this).parents(".card").attr("adm") == 0){
               $.ajax({
                  type:"post",
                   url:"delete.php?uid="+ $(this).parents(".card").attr("uid"),
                   data:{"user":$(this).parents(".card").attr("uid")},
                   success: function(data){
                       $(thisdelete).css("display", "none");
                       $(thisdelete).parents(".choice").children(".edit").css("display", "none");$

                       var deleted = $("<p>Supprimé !</p>").hide();
                       $(deleted).css("text-align", "center");
                       $(thisdelete).parents(".choice").append(deleted);
                       deleted.show("slow");
                       
                       setTimeout(function(){
                           $(thisdelete).parents(".card[uid="+ parent +"]").fadeOut("slow");
            
                            doingSomething = 0;
                       }, 2000);
                       
                   }
               });
            }else{
                alert("Vous ne pouvez pas supprimer un admin !");
            
            doingSomething = 0;
            }
        });
    });