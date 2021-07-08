<?php
    include('skeleton.php');


    if(isset($_GET['gid']) && $_GET['gid'] != ""){
    $gid = intval($_GET['gid']);
    $g = $db->prepare("SELECT * FROM gender WHERE g_id = :gid");
    $g->bindParam(":gid", $gid);
    $g->execute();
    $gg = $g->fetch();
?>
<script src="js/imgviewer.js"></script>
<div class="wrapper">
    <div class="container">
        <h1>Edition de <?php echo $gg['g_name']; ?></h1>
        <form class="editmember">
            <div id="view" class="banner_gender" style="background-image:url('../<?php echo $gg['g_banner']; ?>')"></div>
            <input type="text" class="name" name="name" placeholder="Nom" value="<?php echo $gg['g_name']; ?>">
            <input type="file" class="banner" id="banner" name="banner" accept="image/png, image/jpeg">
            <input type="submit" value="Editer">
        </form>
    </div>
</div>


<script>
       function handleFiles(callback){
        var canvas = document.createElement("canvas");
        var filesToUpload = document.getElementById("banner").files;
        var fileup = filesToUpload[0];
       

        var img = document.createElement("img");
        var reader = new FileReader();
        
        reader.onload = function(e){ img.src = e.target.result; }
        
            
        var x = img.onload = function() {
            var ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0);

            var MAX_WIDTH = 1200;
            var MAX_HEIGHT = 500;
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
        
        var gid = <?php echo $_GET['gid']; ?>;
        
       $("form.editmember").submit(function(e){
            e.preventDefault();
           
           console.log($(this).children("input.banner"));
           var formdata = new FormData();
           
           formdata.append("name", $(this).children("input.name").val());
           
           if(document.getElementById('banner').files.length > 0){
               formdata.append("banner", $(this).children('.banner').prop('files')[0]);
               
               handleFiles(function(blob){
                    formdata.set("banner", blob, "rename.png");
                });
           }
           
            $.ajax({
                  url: "editm.php?gid="+ gid,
                 type: "POST",
                  data:formdata,
                  dataType:'json',
                    contentType: false, 
                    processData: false,
                  success: function(data){
                      for(let i = 0; i < data.length; i++){
                        $("form.editmember").parent().append(data[i]);
                        $("form.editmember").parent().find(".success").slideDown("fast");
                        $("form.editmember").parent().find(".success").delay(2000).hide('slow', function(){$(this).remove(); });
                      }

                  }
              });  
           });
    });
</script>

<?php
    }
?>