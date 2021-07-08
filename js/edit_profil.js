    function handleFiles(){
        var canvas = document.createElement("canvas");
        var filesToUpload = document.getElementById("avatar").files;
        var fileup = filesToUpload[0];
        
        var img = document.createElement("img");
        var reader = new FileReader();
        
        reader.onload = function(e){ img.src = e.target.result; }
        
            
        img.onload = function() {
            var ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0);

            var MAX_WIDTH = 200;
            var MAX_HEIGHT = 200;
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


            var formData = new FormData();
            formData.append("avatar", file);
            
            $.ajax({
               type:"post",
                url:"editmember.php",
                data:formData,
                contentType: false, 
                processData: false,
                success: function(data){
                    if(data == "avatar"){
                        $.getScript('js/notify.js', function(){
                            notif('<div class="success"><b>Succès</b> Votre image de profil a été modifiée avec succès !');
                        });
                    }

                    if(data == "fat"){
                        $.getScript('js/notify.js', function(){
                            notif('<div class="error"><b>Erreur</b> Fichier trop volumineux.');
                        });
                    }
                }
            });
        
        };
        
        reader.readAsDataURL(fileup);

    }

$( document ).ready(function() {
    function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();

          reader.onload = function(e) {
            $('.show_pic').css('background', 'url('+e.target.result+') center no-repeat');
            $('.show_pic').css('background-size', 'cover');
          }

          reader.readAsDataURL(input.files[0]);
        }
      }

      $("#avatar").change(function() {
        readURL(this);
      });
    
    
    $(".info").click(function(){
       $.ajax({
           type:"post",
           url:"editmember.php",
           data:{
               'password_old':$(".edit_pass:nth-of-type(1)").val(),
               'password_new':$(".edit_pass:nth-of-type(2)").val(),
               'password_confirm':$(".edit_pass:nth-of-type(3)").val()
           },
           success: function(data){
                if(data == "ok"){
                    $.getScript('js/notify.js', function(){
                        notif('<div class="success"><b>Succès</b> Votre mot de passe a été modifié avec succès !');
                        $(".edit_pass:nth-of-type(1)").val("");
                        $(".edit_pass:nth-of-type(2)").val("");
                        $(".edit_pass:nth-of-type(3)").val("");
                    });
                }
                if(data == "noot"){
                    $.getScript('js/notify.js', function(){
                        notif('<div class="error"><b>Erreur</b> Les mots de passes ne correspondent pas !');
                    });
                }
                if(data == "old"){
                    $.getScript('js/notify.js', function(){
                        notif('<div class="error"><b>Erreur</b> L\'ancien mot de passe est faux !');
                    });
                }
           }
       }) 
    });
});