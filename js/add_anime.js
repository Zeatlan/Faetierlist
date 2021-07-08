   function handleFiles(callback){
        var canvas = document.createElement("canvas");
        var filesToUpload = document.getElementById("cover").files;
        var fileup = filesToUpload[0];
       

        var img = document.createElement("img");
        var reader = new FileReader();
        
        reader.onload = function(e){ img.src = e.target.result; }
        
            
        var x = img.onload = function() {
            var ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0);

            var MAX_WIDTH = 200;
            var MAX_HEIGHT = 300;
            var width = img.width;
            var height = img.height;

            if (width > height) {
              if (width > MAX_WIDTH) {
                height *= MAX_WIDTH / width;
                width = MAX_WIDTH;
              }
            } else {
              if (height > MAX_HEIGHT) {
                width *= MAX_HEIGHT / height;
                height = MAX_HEIGHT;
              }
            }

            canvas.width = width;
            canvas.height = height;
            var ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0, width, height);


            var dataurl = canvas.toDataURL("image/png");                    
            
            var blobBin = atob(dataurl.split(',')[1]);
            var array = [];
            for(var i = 0; i < blobBin.length; i++) {
                array.push(blobBin.charCodeAt(i));
            }
            var file=new Blob([new Uint8Array(array)], {type: 'image/png'});
            
            callback(file); 
        };
        
        reader.readAsDataURL(fileup);
    }


$( document ).ready(function() {
    
    function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          
          reader.onload = function(e) {
            $('.ad_anime_cover').css('background', 'url('+e.target.result+') center no-repeat');
            $('.ad_anime_cover').css('background-size', 'cover');
            $('.ad_bg_banner_anime').css('background', 'url('+e.target.result+') center no-repeat');
            $('.ad_bg_banner_anime').css('background-size', 'cover');
            $('.ad_bg_banner_anime').css('background-position', '50%');


            
          }
          
          reader.readAsDataURL(input.files[0]);
        }
      }
      
      $("#cover").change(function() {
        readURL(this);
      });

      var selection = [];
      var idselection = [];
        var name = [];
      var note = [];
      var aid = [];
      var indice = 0;
  
      
      $( ".ad_cat_item.nocat" ).click(function() {
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
  
      $( ".popup_result_content" ).on('click', '.ad_cat_item_pop', function() {
  
          $(".popup_body").removeClass("visible_popup_body");
          $(".popup_content").removeClass("visible_popup_content");
          $(".popup_result_content").removeClass("visible_search_content");
          selection.push($(this).attr('style'));
          idselection.push($(this).attr('gid'));
          name.push($(this).text());
          indice++;
          $( ".ad_cat_content" ).prepend( " <div class='ad_cat_item' gid='"+idselection[indice-1]+"' style='"+selection[indice-1]+"\'><div class=\"cat_name_item\"><div class='delete_cat_item'><i class=\"fas fa-times\"></i></div><div class='name cat_name_item'>"+name[indice-1]+"</div></div></div>" );
          $( ".ad_cat_item_pop[gid="+ idselection[indice-1] +"]").remove();
        });
  
      
          
           $(".btn_submit").click(function(){
                var name = $("#name_c").val();
                var nameshort = $("#name_short").val();
                var categories = [];
               
                handleFiles(function(blob){
                       var formData = new FormData();

                        $(".ad_cat_item").each(function(){
                            categories.push($(this).attr("gid"));
                        });
                       formData.append('name', name);
                       formData.append('nameshort', nameshort);
                       formData.append('banner', blob);
                       formData.append('categories', categories);
                    

                       $.ajax({
                           type:"post",
                           url:"send_anime.php",
                           data:formData,
                            contentType: false, 
                            processData: false,
                           success: function(data){ 
                               if(data == "success")
                                $.getScript('js/notify.js', function(){
                                   notif('<div class="success"><b>Succès</b> Nous avons bien reçu votre anime, si il est conforme aux règles, il sera validé sous peu !');
                                });
                               else
                                 $.getScript('js/notify.js', function(){
                                    notif('<div class="error"><b>Erreur</b> L\'anime proposé a déjà été proposé !');
                                });

                              setTimeout(function(){
                                   location.reload();
                               }, 2000);
                           }
                       });
                });
               

           });

           $( ".ad_cat_content" ).on('click', '.delete_cat_item', function() {
            $(this).parents(".ad_cat_item").remove();
            var gid = $(this).parents(".ad_cat_item").attr("gid");
            console.log($(this).parent().children(".name").text());
            var title = $(this).parent().children(".name").text();
            var style = $(this).parents(".ad_cat_item").attr("style");
            
            
            $(".popup_result_content").prepend("<div class='ad_cat_item_pop' title='"+ title +"' style='"+ style +"' gid='"+ gid +"'><div class='cat_name_item'>"+ title +"</div></div>");
           
        });


    });