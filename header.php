<script src="<?php echo $folder; ?>js/header.js"></script>

<div class="header">
    <div class="left"> 

        <div class="menu_icon">
            <div class="hamburger hamburger--slider" id="menuicon">
                <div class="hamburger-box">
                    <div class="hamburger-inner"></div>
                </div>
            </div>

        </div>

        <a href="<?php echo $folder; ?>index.php">
            <div class="logo">
                <div class="logop1">FAE</div>
                <div class="logop2">TIERLIST</div>
            </div>
        </a>
        <div class="search_m"><i class="fas fa-search"></i></div>
        <div class="search_m_content">

            <div class='search_res_m'>
                <input type="text" id="search_m" name="search_m" autocomplete="off" autocorrect="off"
                    autocapitalize="off" spellcheck="false" placeholder="Rechercher">
                <div class="search_res_content_m">

                </div>

            </div>
        </div>
    </div>
    <div class="center">
        <div class="searchbox"><input type="text" id="search" name="search" autocomplete="off" autocorrect="off"
                autocapitalize="off" spellcheck="false" placeholder="Rechercher">
            <div class="searchbtn"><i class="fas fa-search"></i></div>
        </div>
        <div class='search_res'></div>
    </div>
    <div class="right">
        <?php 
        if(empty($_SESSION) || !isset($_SESSION['id'])){
        ?>
        <div class="not_logged">
            <a href="<?php echo $folder; ?>connexion.php">
                <div class="login_btn">Connexion</div>
                <div class="loginbtn_m"><i class="fas fa-sign-in-alt"></i></div>
            </a>
            <a href="<?php echo $folder; ?>inscription.php">
                <div class="login_btn">Inscription</div>
                <div class="loginbtn_m"><i class="fas fa-user-plus"></i></div>
            </a>
        </div>
        <?php
        } else {
            $user = $membre->getById($_SESSION['id']);
        ?>
        <div class="logged">
            <div class="img_profil"
                style="background:url('<?php echo $folder . $user->avatar(); ?>') center;background-size:cover;"
                onclick="document.location.href='<?php echo $folder; ?>profil/<?php echo $user->id(); ?>'"></div>
            <?php if($user->admin() == 1) { ?>
            <div class="admin" onclick="location.href='<?php echo $folder; ?>admin/'"><i class="fas fa-cog"></i></div>
            <?php } ?>
            <div class="disconnect" onclick="document.location.href='<?php echo $folder; ?>logout.php'"><i class="fas fa-power-off"></i>
            </div>
        </div>
        <?php
            }
        ?>
    </div>
</div>




<div class="menu">
    <div class="container_menu">
        <a href="<?php echo $folder; ?>browse.php">
            <div class="menu_select">
                <div class="icon_menu_select"><i class="fas fa-th"></i></div> Parcourir
            </div>
        </a>
        <?php 
            if(isset($_SESSION) && isset($_SESSION['id']) && $_SESSION['id'] != ""){ ?>
        <a href="<?php echo $folder; ?>ranking.php">
            <div class="menu_select">
                <div class="icon_menu_select"><i class="fas fa-trophy"></i></div> Classement
            </div>
        </a>
           <?php } ?>
        <?php 
            if(isset($_SESSION) && isset($_SESSION['id']) && $_SESSION['id'] != ""){
                $m = $membre->getById($_SESSION['id']);
                if(!empty($_SESSION['permission']) && $_SESSION['permission'] >= 1 || $m->canVote() == 1){ ?>
        <a href="<?php echo $folder; ?>add_anime.php">
            <div class="menu_select">
                <div class="icon_menu_select"><i class="fas fa-plus-circle"></i></div> Ajouter un anime
            </div>
        </a>
        <?php } 
            }?>
        <a href="<?php echo $folder; ?>infos.php">
            <div class="menu_select">
                <div class="icon_menu_select"><i class="fas fa-info"></i> </div> Mentions légales
            </div>
        </a>
    </div>
</div>

<div class="focus_menu"></div>

<script>
$(document).ready(function() {
    var folder = '<?php echo $folder; ?>';
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
                url: folder+'fetch.php',
                method: 'post',
                cache:false,
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

    $('.search_res_m').click(function(e) {
        e.stopPropagation();
    });
    $(".search_m").click(function(e) {
        e.preventDefault();
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
                url: folder+'fetch.php',
                method: 'post',
                cache:false,
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

});
</script>