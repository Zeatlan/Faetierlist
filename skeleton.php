
<?php
    include('db.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?php echo $folder; ?>img/favicon_fae_tierlist.png" />
    <link rel="stylesheet" href="<?php echo $folder; ?>css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link href="<?php echo $folder; ?>fa/css/all.css" rel="stylesheet"> 
    <title>FaeTierlist</title>
</head>
<body>

 
<?php
    if(!empty($_GET)){
        if(isset($_GET["success"])){
            if(isset($_SESSION['welcomed']) && $_SESSION['welcomed'] == false){
                $_SESSION['welcomed'] = true;
                $m = $membre->getById($_SESSION['id']);
                notif("success", "Bienvenue chez vous, ". $m->pseudo());
            }else if(isset($_SESSION['new']) && $_SESSION['new'] == true){
                $_SESSION['new'] = false;
                notif("success", "Merci de votre inscription, vous allez recevoir un message privé par notre bot, si vous n'avez rien reçu, merci de contacter un administrateur.");
            }else if(isset($_SESSION['validate']) && $_SESSION['validate'] == true){
                $_SESSION['validate'] = false;
                notif("success", "Votre compte est désormais valide, vous pouvez désormais éditer votre profil et voter, bon séjour !");
            }
        }

        if(isset($_GET['gid']) && intval($_GET['gid']) > 0){
            $select = $db->query("SELECT * FROM gender WHERE g_id =". $_GET['gid']);
            $s = $select->fetch();
    ?>
    <?php include 'popup_vote.php' ?>

    <?php
        }
    }
    ?>


<?php include 'header.php' ?>
<div class="wrapper_content">
<div class="content">