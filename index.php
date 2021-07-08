    <?php include 'skeleton.php';


            $totalTierlist = $db->query("SELECT * FROM gender");
            $tt = $totalTierlist->rowCount();

            $totalAnime = $db->query("SELECT * FROM anime WHERE a_valid = 1");
            $ta = $totalAnime->rowCount();

            $totalNote = $db->query("SELECT * FROM note");
            $tn = $totalNote->rowCount();
        ?>
    <div class="presentation_wrapper">
    <div class="presentation">
        <div class="img_wrapper"><div class="img"></div></div>
        <div class="content_pres"><h2>FAE Tierlist</h2>
            <p>Découvrez une multitude de tierlists et d'animes.
            Ici vous avez la possibilité de noter chaque anime par catégorie et ainsi établir un classement une tierlist. Mais aussi de vous
            donner des idées pour quel anime se doit d'être regardé ou bien d'être évité.
            <p>Voici la liste exhaustive des paliers :</p>
            </p>
            <div class="minilist"><div class="mlbox">SS</div> <div class="mlbox">S</div> <div class="mlbox">A</div> <div class="mlbox">B</div> 
            <div class="mlbox">C</div> <div class="mlbox">D</div></div>
        </div>
        </div>

    </div>
    <div class="stat_wrapper">
        <div class="stat">
            <div class="stat_img"><i class="fas fa-search"></i></div>
            <div class="stat_content">Au total <b><?php echo $tt; ?></b> tierlists répertoriées.
            </div> 
        </div>
        <div class="stat">
            <div class="stat_img"><i class="fas fa-list"></i></div>
            <div class="stat_content">Pour <b><?php echo $ta; ?></b> animes classés.
            </div> 

        </div>
        <div class="stat">
            <div class="stat_img"><i class="fas fa-star-half-alt"></i></div>
            <div class="stat_content">Avec en tout <b><?php echo $tn; ?></b> votes.
            </div> 
        </div>
   </div>
   
   <div class="banner_wrapper">
    <div class="banner">
        <div class="bannerimg"><i class="fas fa-flask"></i></div>
        <div class="bannercontent">La version du site est actuellement en <b>Beta</b>.<br>
        Vous êtes témoin d'un bug ou bien vous avez des suggestions d'amélioration ?<br>
        Contactez-nous sur Discord.
        </div>
    </div>
    </div>