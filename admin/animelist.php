<?php
    include('skeleton.php');
    $limitmax = 10;

    $c = $db->query("SELECT * FROM anime");
    $totalpage = ceil($c->rowCount() / $limitmax);
    if(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $totalpage){
        $_GET['page'] = intval($_GET['page']);
        $page = $_GET['page'];
    }else{
        $page = 1;
        $_GET['page'] = 1;
    }

   
    $limitmin = ($page-1)*$limitmax;

?>

<script src="js/animelist.js"></script>
<script src="js/imgviewer.js"></script>
<div class="wrapper">
    <div class="container">
        <h1>Liste des animes</h1>
        
        <table>
            <thead><th class="banner">Image</th><th>Nom</th><th>Genres</th><th class="tool">Action</th></thead>
            <tbody>
                <?php
                    $a = $anime->getListLimit($limitmin, $limitmax);
                    foreach($a as $key => $value){
                        if($value->valid() == 1)
                        echo '<tr aid="'. $value->id() .'" class="anime"><td class="banner"><img src="../'. $value->banner() .'" width="55px" height="75px"></td><td>'. $value->name() .'</td><td>'. findGenders($db, $value->id()) .'</td><td class="tool"><div class="edit">Editer</div><div class="delete">Supprimer</div></td></tr>'; 
                    }
                ?>
            </tbody>
        </table>
        
            <?php 
                if(isset($_GET['page']) && $totalpage > 1){
                    echo '<div class="pagination">';
                    for($i = 1; $i <= $totalpage; $i++){
                        if($i%($totalpage+1) == 0) break;
                        if($i == $_GET['page']){
                            echo '<i>'. $i .'</i> ';
                        }else{
                            echo '<a href="animelist.php?page='. $i .'">'. $i .'</a> ';
                        }
                    }
                    echo '</div>';
                }
            ?>
    </div>
</div>


<div class="wrapper">
    <div class="container">
        <h1>Ajouter un anime</h1>
        <div class="anime_cover_view" id="view"></div>
        <form class="addanime">
            <input type="text" class="name" name="name" placeholder="Nom de l'anime" required>
            <input type="text" class="shortname" name="shortname" placeholder="Nom alternatif">
            <input type="file" class="image" id="image" name="image" accept="image/png, image/jpeg">
            <br>
            <?php
                $allGender = $db->query("SELECT * FROM gender ORDER BY g_name");
                while($ag = $allGender->fetch()){
                    echo "<input type='checkbox' name='gender[]' class='gender' value='". $ag['g_id'] ."'> <label for='gender[]'>". $ag['g_name'] ."</label>";
                }
            ?>
            <input type="submit" value="Ajouter">
        </form>
    </div>
</div>

<div class="wrapper">
    <div class="container">
        <h1>Valider les animés</h1>
        <table>
            <thead><th class="banner">Image</th><th>Nom</th><th>Alternatif</th><th>Genres</th><th>Propositeur</th><th class="tool">Action</th></thead>
            <tbody>
                <?php
                    $a = $anime->getUnvalidList();
                    $propositeur = $db->prepare("SELECT * FROM log_anime WHERE l_aid = :aid");
                    foreach($a as $key => $value){
                        $propositeur->bindValue(":aid", $value->id());
                        $propositeur->execute();
                        $p = $propositeur->fetch();
                        
                        $m = $membre->getById($p['l_uid']);
                        
                            echo '<tr aid="'. $value->id() .'" class="anime"><td class="banner"><img src="../'. $value->banner() .'" width="55px" height="75px"></td><td>'. $value->name() .'</td><td>'. $value->shortName() .'</td><td>'. findGenders($db, $value->id()) .'</td><td class="prop">'. $m->pseudo() .'</td><td class="tool"><div class="approve">Approuver</div><div class="edit">Editer</div><div class="disapprove">Désapprouver</div></td></tr>'; 
                        
                    }
                ?>
            </tbody>
        </table>
        
    </div>
</div>