<div class="header">
    <div class="h_l_m">
        <a href="index.php">
            <div class="logo">
                <div class="logop1">FAE</div>
                <div class="logop2">TIERLIST</div>
            </div>
        </a>

        <div class="menu"><a href="browse.php">
                
                <div class="mtitle">Parcourir</div>
                <div class="mtitle_m"><i class="far fa-compass"></i></div>
            </a>
            <?php 
            if(isset($_SESSION) && isset($_SESSION['id']) && $_SESSION['id'] != ""){
                $m = $membre->getById($_SESSION['id']);
                if(!empty($_SESSION['permission']) && $_SESSION['permission'] >= 1 || $m->canVote() == 1){ ?>
            <style>
            @media screen and (max-width: 1460px) {
                .menu .mtitle_m {
                    display: block;
                }

                .menu .mtitle {
                    display: none;
                } 

            }

            @media screen and (max-width: 1020px) {
                .search_m {
                    display: block;
                }

                .search {
                    display: none;
                }

            } 
            </style>
            
            <a href="add_anime.php">
                <div class="mtitle">Ajout</div>
                <div class="mtitle_m"><i class="fas fa-plus"></i></div>
            </a>
            <?php } 
            }?>
            <a href="infos.php">
                <div class="mtitle">Informations</div>
                <div class="mtitle_m"><i class="fas fa-info-circle"></i></div>
            </a>
            <div class="expand_m">…</div>
            <div class="search_m"><i class="fas fa-search"></i></div>
            <div class="search_m_content">

                <div class='search_res_m'>
                    <input type="text" id="search_m" name="search_m" autocomplete="off" autocorrect="off"
                        autocapitalize="off" spellcheck="false" placeholder="Rechercher">
                    <div class="search_res_content_m">

                    </div>

                </div>
            </div>

            <div class="menu_expanded">
            <a href="browse.php"><div class="title">Parcourir</div></a>
            <?php 
            if(isset($_SESSION) && isset($_SESSION['id']) && $_SESSION['id'] != ""){
                $m = $membre->getById($_SESSION['id']);
                if(!empty($_SESSION['permission']) && $_SESSION['permission'] >= 1 || $m->canVote() == 1){ ?>
            <a href="add_anime.php"><div class="title">Ajout</div></a>
            <?php } 
            }?>
             <a href="infos.php"><div class="title">Informations</div></a>
            </div>

        </div>
    </div>

    <div class="search">
        <div class="searchbox"><input type="text" id="search" name="search" autocomplete="off" autocorrect="off"
                autocapitalize="off" spellcheck="false" placeholder="Rechercher">
            <div class="searchbtn"><i class="fas fa-search"></i></div>
        </div>

        <div class='search_res'></div>


    </div>



    <?php 
        if(empty($_SESSION) || !isset($_SESSION['id'])){
    ?>

    <div class="login">
        <a href="connexion.php">
            <div class="loginbtn">Connexion</div>
            <div class="loginbtn_m"><i class="fas fa-sign-in-alt"></i></div>
        </a>
        <a href="inscription.php">
            <div class="loginbtn">Inscription</div>
            <div class="loginbtn_m"><i class="fas fa-user-plus"></i></div>
        </a>
    </div>

    <?php
        } else {
            
            $user = $membre->getById($_SESSION['id']);
            ?>
    <div class="connected">
        <div class="c_img_member" style="background:url('<?php echo $user->avatar(); ?>') center;background-size:cover;"
            onclick="document.location.href='profil.php?uid=<?php echo $user->id(); ?>'"></div>
        <?php if($user->admin() == 1) { ?>
        <div class="c_admin" onclick="location.href='admin/'"><i class="fas fa-cog"></i></div>
        <?php } ?>
        <div class="c_disconnect" onclick="document.location.href='logout.php'"><i class="fas fa-power-off"></i></div>
    </div>
    <?php
        }
    ?>

</div>

<script>
$(document).ready(function() {
   
    $('.search_res_m').click(function(e){
    e.stopPropagation();
    });
    $(".search_m").click(function(e) {
        e.preventDefault();
        $('.menu_expanded').hide();
        e.stopPropagation();
        $('.search_res_m').show();
        
    });
    $(document).click(function() {
        $('.search_res_m').hide();
    });
  

    $("#search_m").keyup(function() {
        var txt = $(this).val();
        if (txt != '') {
            $(".search_res_content_m").css("display", "flex");
            $('.search_res_content_m').html('');
            $.ajax({
                url: 'fetch.php',
                method: 'post',
                data: {
                    search: txt
                },
                dataType: 'text',
                success: function(data) {
                    $('.search_res_content_m').html(data);
                }
            });

        } else {}
    });

   

    $(".searchbtn").click(function() {
        $(".searchbtn i").replaceWith("<i class='fas fa-search'></i>");
        $('#search').val('');
        $('.search_res').html('');
    });

    $("#search").keyup(function() {
        var txt = $(this).val();
        if (txt != '') {
            $(".search_res").css("display", "flex");
            $(".searchbtn i").replaceWith("<i class='fas fa-times' style='padding-left:2px;'></i>");
            $('.search_res').html('');
            $.ajax({
                url: 'fetch.php',
                method: 'post',
                data: {
                    search: txt
                },
                dataType: 'text',
                success: function(data) {
                    $('.search_res').html(data);
                }
            });
        } else {
            $(".searchbtn i").replaceWith("<i class='fas fa-search'></i>");
            $(".search_res").css("display", "none");
        }
    });

    $("body").on('click', '.res_s', function() {
        var url = $(this).attr("href");
        if (url !== undefined) {
            document.location.href = url;
        } else {
            url = $(this).parent().attr("href");
            document.location.href = url;
        }
    });


    $("body").click(function(evt) {
        if ($(evt.target).closest("#search").length > 0 || $(evt.target).closest('.res_s').length > 0)
            return false;

        $(".searchbtn i").replaceWith("<i class='fas fa-search'></i>");
        $(".search_res").css("display", "none");
        $("#search").val("");
    });
   
    $('.menu_expanded').click(function(e){
    e.stopPropagation();
    });
    $(".expand_m").click(function(e) {
        e.preventDefault();
        
        $('.search_res_m').hide();
        e.stopPropagation();
        $('.menu_expanded').show();
        
    });
    $(document).click(function() {
        $('.menu_expanded').hide();
    });
   
    
 

});
</script>