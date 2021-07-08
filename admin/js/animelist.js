   function handleFiles(callback){
        var canvas = document.createElement("canvas");
        var filesToUpload = document.getElementById("image").files;
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


$(document).ready(function(){
        $("tr").hover(function(){
            if($(this).children("td").is(':first-child') == true){
                $(this).css("background-color", '#'+(Math.random()*0xFFFFFF<<0).toString(16));
                $(this).children("td.tool").fadeIn("slow");
            }
        }, function(){
                $(this).css("background-color", "transparent");
                $(this).find('td.tool').hide();
        });
        
        $(".edit").click(function(){
           document.location.href = "editanime.php?aid=" + $(this).parents("tr").attr("aid"); 
        });
        
        $(".delete").click(function(){
            var that = $(this);
            $.ajax({
               type:"POST",
                url:"delete.php?aid="+ $(this).parents("tr").attr("aid"),
                data:{'aid': $(this).parents("tr").attr("aid")},
                success: function(data){
                    $(that).parents("tr").children('td').children().slideUp(function(){$(that).closest('tr').remove();});
                }
            });
        });
        
        $(".approve").click(function(){
            var that = this;
           $.ajax({
               type:'GET',
               url:'approve.php?aid='+ $(this).parents("tr").attr("aid"),
               data:{
                   'approve':'true',
                    'prop':$(this).parents("tr").children("td.prop").html()
                },
               success: function(data){
                   $(that).parents("tr").css("background-color", "green");
                    $(that).parents("tr").children('td').children().slideUp(function(){$(that).closest('tr').remove();});
               }
           }) 
        });
        
        $(".disapprove").click(function(){
            var that = this;
           $.ajax({
               type:'GET',
               url:'approve.php?aid='+ $(this).parents("tr").attr("aid"),
               data:{'approve':'false'},
               success: function(data){
                   $(that).parents("tr").css("background-color", "red");
                    $(that).parents("tr").children('td').children().slideUp(function(){$(that).closest('tr').remove();});
               }
           }) 
        });
        
        
        $("form.addanime").submit(function(e){
               e.preventDefault();

               var c = new Array();

                var formdata = new FormData();
               $(this).children("input:checkbox:checked").each(function(){
                  c.push($(this).val()); 
               });
                formdata.append('name', $(this).children("input.name").val());
                formdata.append('shortname', $(this).children("input.shortname").val());
                formdata.append('gender', c);
            
                handleFiles(function(blob){
                    formdata.append('image', blob, 'redim.png');
           
                  $.ajax({
                        type:"POST",
                        url:"addanime.php",
                        contentType: false, 
                        processData: false,
                        data:formdata,
                        success: function(data){
                            if(data === "exist"){
                                $("form").parent().append("<div class='error' style='display:none'>Cet anime est déjà enregistré dans notre base de donnée.</div>");
                                $("form").parent().find(".error").slideDown("fast");

                                setTimeout(function(){
                                    $("form").parent().find(".error").fadeOut("slow").remove(); 
                                }, 2000);
                            }

                            if(data === "size"){
                                $("form").parent().append("<div class='error' style='display:none'>Taille ou format invalide.</div>");
                                $("form").parent().find(".error").slideDown("fast");

                                setTimeout(function(){
                                    $("form").parent().find(".error").fadeOut("slow").remove(); 
                                }, 2000);
                            }

                            if(data === "success"){
                                $("form").parent().append("<div class='success' style='display:none'>L'anime a bien été ajouté.</div>");
                                $("form").parent().find(".success").slideDown("fast");

                                $("form").children("input[type='text']").val('');


                            }
                        }
                  });
           });
        });
        
        
    });