<?php 
    include 'skeleton.php'; 
            if(isset($_SESSION))
                $m = $membre->getById($_SESSION['id']);
            if(!empty($_SESSION['permission']) && $_SESSION['permission'] >= 1 || $m->canVote() == 1){
        $m = $membre->getById($_SESSION['id']);
?>
    <script src="js/edit_profil.js"></script>

    <div class="edit_profil_content">
        <div class="show_pic_wrapper"><div class="show_pic" style="background-image:url('<?php echo $m->avatar(); ?>') center;background-size:cover;"></div></div>
        <div class="input_file">
            <div class="title_file">Changer votre image de profil :</div>
            <label class="file_img">
            <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg" onchange="handleFiles()">
            Charger une image
            </label>
          </div>
    </div>

    <div class="edit_profil_content">
        <div class="edit_pass_wrapper">
            <div class="title_pass_edit">Modification de votre mot de passe :</div>
            <input class="edit_pass" type="password" name="password_old" placeholder="Ancien mot de passe" required>
            <input class="edit_pass" type="password" name="password_new" placeholder="Nouveau mot de passe" required>
            <input class="edit_pass" type="password" name="password_new2" placeholder="Nouveau mot de passe 2" required>
        </div>

    <div class="btn_send info">Enregistrer les modifications</div>
    </div>


<?php
    }
?>