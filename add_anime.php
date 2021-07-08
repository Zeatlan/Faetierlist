<?php
    include 'skeleton.php';
    include 'popup_add_anime.php';
            if(isset($_SESSION) && !empty($_SESSION)){
                $m = $membre->getById($_SESSION['id']);
            if(!empty($_SESSION['permission']) && $_SESSION['permission'] >= 1 || $m->canVote() == 1){
?>
<script src="js/add_anime.js"></script>
<div class="ad_banner_anime">
            <div class="ad_anime_title">
            <div class="long_name"> <input class="input_name" type="text" id="name_c" name="anime_name" placeholder="Nom complet"></div>
           <div class="short_name"> <input class="input_name" type="text" id="name_short" name="anime_name" placeholder="Nom cours ou alternatif ou vide"></div>
          
            </div>
            <div class="ad_bg_banner_anime">
            </div>
            <div class="ad_anime_cover" >
            <div class="ad_input_file">
            <label class="ad_file_img">
                <input type="file" id="cover" name="avatar" accept="image/png, image/jpeg">
                 Charger une affiche d'anime
                 <div class="info_cover"></div>
            </label>
            </div>
        </div>
        </div>

        <h1 class="ad_cat_title">Sélectionnez une ou plusieurs catégories correspondantes à l'anime</h1>
        <div class="ad_cat_content">
        <div class="ad_cat_item nocat">
        <i class="fas fa-plus"></i>
        </div>
        </div>
        <div class="btn_submit">Soumettre la proposition d'anime</div>
        <div class="information_add_anime">Veuillez noter que votre proposition sera soumise à une vérification. Elle est donc suceptible d'être modifiée ou bien supprimée si elle n'est pas conforme.</div>

<?php
            }
        }
?>