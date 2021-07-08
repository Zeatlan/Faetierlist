<?php
    include('skeleton.php');
?>
<script src="js/imgviewer.js"></script>

<div class="wrapper">
    <div class="container">
        <h1>Liste des genres</h1>
        
        <table>
            <thead><th class="banner">Bannière</th><th>Nom</th><th class="tool">Action</th></thead>
            <tbody>
                <?php
                    $genders = $db->query("SELECT * FROM gender ORDER BY g_id");
                    while($g = $genders->fetch()){
                        echo '<tr gid="'. $g['g_id'] .'"><td class="banner"><img src="../'. $g['g_banner'] .'" width="200px" height="100px"></td><td>'. $g['g_name'] .'</td><td><a href="editgender.php?gid='. $g['g_id'] .'"><div>Editer</div></a><div class="delete">Supprimer</div></td></tr>'; 
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="wrapper">
    <div class="container">
        <h1>Ajouter une catégorie</h1>
        <div id="view" class="view_cat_img"></div>
        <form method="POST" class="cat_form">
            <input type="text" name="name" class="cat_name" placeholder="Nom de la catégorie" required>
            <label>Bannière</label>
            <input type="file" name="banner" id="cat_banner" class="cat_banner" accept="image/png, image/jpeg" required>
            
            <input type="submit" value="Valider">
        </form>
    </div>
</div>


<script>
    function handleFiles(callback){
        var canvas = document.createElement("canvas");
        var filesToUpload = document.getElementById("cat_banner").files;
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
        $(".delete").click(function(){
            var that = $(this);
            $.ajax({
               type:"POST",
                url:"delete.php?gid="+ $(this).parents("tr").attr("gid"),
                data:{'gid': $(this).parents("tr").attr("gid")},
                success: function(data){
                    $(that).parents("tr").children('td').children().slideUp(function(){$(that).closest('tr').remove();});
                }
            });
        });
        
        $(".cat_form").submit(function(e){
            e.preventDefault(); 
            
            var formdata = new FormData();
            var that = this;
            
            formdata.append("name", $(this).children(".cat_name").val());
            
            handleFiles(function(blob){
            
                formdata.append("banner", blob, 'redim.png');
                
                $.ajax({
                   type:"POST",
                    url:"addgender.php",
                    data:formdata,
                    contentType: false, 
                    processData: false,
                    success: function(data){
                       $(that).append("<p>"+ data +"</p>");


                        setTimeout(function(){
                            location.reload();
                        }, 2000);
                    }
                });    
            });

        });
        
    });

</script>