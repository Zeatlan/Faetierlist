<?php
    include('skeleton.php');


if(isset($_GET) && $_GET['uid'] != ""){
    $m = $membre->getById($_GET['uid']);
    if($m->admin() == 0 && $m->id() != null){
?>


<div class="wrapper">
    <div class="container editcont">
        <h1>Edition de <?php echo $m->pseudo(); ?></h1>
            <div class="avatar" style="background-image:url('<?php echo "../".$m->avatar(); ?>'); background-position: center; background-size: cover;"></div>
        
        <form class="editmember">
            
            <div>
                <label for="pseudo">Pseudonyme</label>       
                <input type="text" class="name_inp" name="pseudo" placeholder="Pseudonyme" value="<?php echo $m->pseudo(); ?>">   
            </div>
            
            <div>
                <label for="password">Mot de passe</label>
                <input type="password" class="pass_inp" name="password" placeholder="Mot de passe">
            </div>
            
            <div>
                <label for="discord">Discord</label>
                <input type="text" class="disc_inp" name="discord" placeholder="Discord" value="<?php echo $m->discord(); ?>">
            </div>
            
            <div>
                <label for="canVote">Peut voter ?</label>
                <input type="number" class="vote" name="canVote" placeholder="Peut-il voter ? (1 si oui, 0 sinon)" value="<?php echo $m->canVote(); ?>">
            </div>
    
            <div>
                <label for="admin">Est admin ?</label>
                <input type="number" class="adm" name="admin" placeholder="Est-il admin ? (1 si oui, 0 sinon)" value="<?php echo $m->admin(); ?>">
            </div>
        
            <div>
                <label for="avatar">Avatar</label>
                <input type="file" id="av_inp" class="av_inp" name="avatar" accept="image/png, image/jpeg">
            </div>
            
            <div>
                <label for="banner">Bannière</label>
                <input type="text" class="banner" name="banner" placeholder="Bannière de profil" value="<?php echo $m->banner(); ?>">
            </div>
            
            <input type="submit" value="Editer">
        </form>
    </div>
</div>


<script>
    function handleFiles(callback){
        var canvas = document.createElement("canvas");
        var filesToUpload = document.getElementById("av_inp").files;
        var fileup = filesToUpload[0];
       

        var img = document.createElement("img");
        var reader = new FileReader();
        
        reader.onload = function(e){ img.src = e.target.result; }
        
            
        var x = img.onload = function() {
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
            
            callback(file); 
        };
        
        reader.readAsDataURL(fileup);
    }
    
    $(document).ready(function(){   
            var uid = <?php echo $_GET['uid']; ?>; 
        
        
        
       $("form.editmember").submit(function(e){
            var form = $("form.editmember");
            e.preventDefault();
           
           var formdata = new FormData();
           formdata.append("pseudo", $(this).children("div").children(".name_inp").val());
           formdata.append("password", $(this).children("div").children(".pass_inp").val());
           formdata.append("discord", $(this).children("div").children(".disc_inp").val());
           formdata.append("canVote", $(this).children("div").children(".vote").val());
           formdata.append("admin", $(this).children("div").children(".adm").val());
            formdata.append("banner", $(this).children("div").children(".banner").val());
           
           if(document.getElementById("av_inp").files.length > 0){
               formdata.append("avatar", $(this).children("div").children(".av_inp").prop('files')[0]);
               
               handleFiles(function(blob){
                formdata.set("avatar", blob, "redim.png");
               });
           }
               
            $.ajax({
                url: "editm.php?uid="+ uid,
                type: "POST",
                data:formdata,
                contentType: false, 
                processData: false,
                dataType:'json',
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
}
    ?>