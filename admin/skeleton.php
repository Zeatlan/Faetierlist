<?php 
    include('../db.php'); 
    $current = $membre->getById($_SESSION['id']);

    if(isset($_SESSION) && !empty($_SESSION)){
        $m = $membre->getById($_SESSION['id']);
        if($m->admin() == 0){
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ../index.php');

            // Optional workaround for an IE bug (thanks Olav)
            header("Connection: close");
        }
    }else{
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ../connexion.php');
 
        // Optional workaround for an IE bug (thanks Olav)
        header("Connection: close");
    }




?>


<!DOCTYPE HTML>

<html>
    <head>
        <title>Administration</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/global.css">
        <link rel="stylesheet" href="css/index.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <link href="../fa/css/all.css" rel="stylesheet"> 
        <script type="text/javascript" charset="utf-8">		
            function fill(nbvote, gid, best){
                $('.bar-fill[gender='+ gid +']').css({
                    'width':(nbvote*100)/best +"%"
                });		
            }
        </script>
        
    </head>
    <body>
        
        <script>
            $(document).ready(function(){
               $(".input_search_bar").on('keypress', function(e){
                  if(e.which == 13){
                      $.ajax({
                         type:"post",
                          url:"fetch.php",
                          data:{"name":$(this).val()},
                          success:function(data){
                              document.location.href = data;
                          }
                      });
                  } 
               });
            });
        </script>

    
        <div id="header">
            <div class="title"><h1>FAE!TIERLAB</h1></div>
            
            <div class="search_bar"><input type="text" class="input_search_bar" name="search" placeholder="Rechercher quelque chose..."></div>
        
        </div>
        
        <div id="panel">
            <div class="account">
                <div class="avatar" style="background-image:url('<?php echo "../".$current->avatar(); ?> ');"></div>
                <div class="userinfo">
                    <div class="username"><?php echo $current->pseudo(); ?></div>
                    <div class="rank">Administrateur</div>
                    <div class="navigate"><a href="../index.php">Retour au site</a></div>
                </div>
            </div>
            
            <div class="menu">
                <ul>
                    <a href="index.php"><li><i class="fas fa-home"></i> Accueil</li></a>
                    <a href="memberlist.php"><li><i class="fa fa-user" aria-hidden="true"></i> Membres</li></a>
                    <a href="animelist.php"><li><i class="fas fa-star"></i> Animes</li></a>
                    <a href="gender.php"><li><i class="fas fa-book"></i> Genres</li></a>
                    <a href="restriction.php"><li><i class="fas fa-times"></i> Restriction</li></a>
                </ul>
            </div>
        </div>