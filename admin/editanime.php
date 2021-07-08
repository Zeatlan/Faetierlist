<?php
    include('skeleton.php');

    if(isset($_GET['aid']) && $_GET['aid'] != ""){
        $a = $anime->getById($_GET['aid']);
?>
<script src="js/imgviewer.js"></script>
<div class="wrapper">
    <div class="container">
        <h1>Edition de <?php echo $a->name(); ?></h1>
        <form class="editmember">
            <div id="view" class="banner" style="background-image:url('<?php echo "../".$a->banner(); ?>')"></div>
            <input type="text" class="name" name="name" placeholder="Nom" value="<?php echo $a->name(); ?>">
            <input type="text" class="shortname" name="shortname" placeholder="Nom alternatif" value="<?php echo $a->shortname(); ?>">
            <input type="file" class="avatar" id="avatar" name="avatar" accept="image/png, image/jpeg">
            
            <div class="genders">
                <?php
                    $allGender = $db->query("SELECT * FROM gender");
                        $animegender = $db->prepare("SELECT * FROM anime_gender WHERE a_id = :aid AND g_id = :gid");
                    $aid = $a->id();
                    $i = 1;
                    while($ag = $allGender->fetch()){
                        $i++;
                        $gid = $ag['g_id'];
                        $animegender->bindParam(":aid", $aid);
                        $animegender->bindParam(":gid", $gid);
                        $animegender->execute();
                        $ang = $animegender->fetch();
                        echo "<div class='gender_cat'>";
                            if($ang)
                                echo "<label for='gender[]'>". $ag['g_name'] ."</label><input type='checkbox' name='gender[]' class='gender' value='". $ag['g_id'] ."' checked=checked>";
                            else
                                echo "<label for='gender[]'>". $ag['g_name'] ."</label><input type='checkbox' name='gender[]' class='gender' value='". $ag['g_id'] ."'>";
                        
                        echo  "</div>";
                    }
                ?>
            </div>
            <input type="submit" value="Editer">
        </form>
    </div>
</div>

<script>
     function handleFiles(callback){
        var canvas = document.createElement("canvas");
        var filesToUpload = document.getElementById("avatar").files;
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
        
        var aid = <?php echo $_GET['aid']; ?>;
        
        
       $("form.editmember").submit(function(e){
        var form = $("form.editmember");
        e.preventDefault();
           
           var c = new Array();
           
            $(this).children(".genders").children(".gender_cat").children("input:checkbox:checked").each(function(){
              c.push($(this).val()); 
           });
           
           var formdata = new FormData();
           formdata.append("name", $(this).children(".name").val());
           formdata.append("shortname", $(this).children(".shortname").val());
           formdata.append("gender", c);
           
           if(document.getElementById("avatar").files.length > 0){
                formdata.append("banner", $(this).children(".avatar").prop('files')[0]);
           
                $("input[type=file]").change(function(){
                   handleFiles(function(blob){
                        formdata.set("banner", blob, "rename.png");
                    });
                });
                   
           }                 
           
              $.ajax({
                  url: "editm.php?aid="+ aid,
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