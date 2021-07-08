<?php
    include 'skeleton.php';

    if(empty($_SESSION) || !isset($_SESSION['id'])){
?>

<style>
#pageloader
{
  background: rgba( 255, 255, 255, 0.8 );
  display: none;
  height: 100%;
  position: fixed;
  width: 100%;
  z-index: 9999;
}

#pageloader img
{
  left: 50%;
  margin-left: -32px;
  margin-top: -32px;
  position: absolute;
  top: 50%;
    }
</style>

        <title>Connexion</title>
        <div class="connexion_wrapper">
        <h1>Connexion</h1>
        
        <form method="post" id="connexion">
        <div class="connexion"> 
        <div class="field"><div class="field_img"><i class="fas fa-user"></i></div><input type="text" name="pseudo" placeholder="Pseudonyme" required></div>
        <div class="field"><div class="field_img"><i class="fas fa-unlock-alt"></i></div><input type="password" name="password" placeholder="Mot de passe" required></div>
        <?php if(isset($_SERVER['HTTP_REFERER'])){ ?>
        <input type="hidden" name="referer" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
            <?php } ?>
        </div>
           
            <input class="btn_connexion" type="submit" value="Connexion">
        </form>
    </div>
<?php
       // echo "<script>alert('". $_SESSION['referer'] ."');</script>";
        if(isset($_POST) && !empty($_POST)){

            
            if($_POST['pseudo'] != '' && $_POST['password'] != ''){
                $pseudo = htmlspecialchars($_POST['pseudo']);
                $password = md5($_POST['password']);
                $member = $membre->getByPseudo($pseudo);

                // Verification de l'existence du membre
                if($member->id() != null && $member->password() == $password){
                    if($member->password() == $password){
                        
                        $_SESSION['username'] = $pseudo;

                        // Jaugement des permissions
                        if($member->canVote() == 1){
                            if($member->admin() == 1)
                                $_SESSION['permission'] = 2;
                            else
                                $_SESSION['permission'] = 1;
                        }else {
                            $_SESSION['permission'] = 0;
                        }

                        $_SESSION['id'] = $member->id();
                        $_SESSION['welcomed'] = false;
                        
                        
                        
                        if(isset($_SERVER['HTTP_REFERER']) && isset($_POST['referer']) && $_POST['referer'] != ""){
                            if(basename($_SERVER['HTTP_REFERER']) == "connexion.php")
                                echo "<script>document.location.href='index.php?success=". $pseudo ."'; </script>";


                            if(substr($_POST['referer'], -1) == "p")
                                echo "<script>document.location.href='". $_POST['referer'] ."?success=". $pseudo ."'; </script>";
                            else if(substr($_POST['referer'], -1) == "/")
                                echo "<script>document.location.href='". $_POST['referer'] ."index.php?success=". $pseudo ."'; </script>";
                            else
                                echo "<script>document.location.href ='". $_POST['referer'] ."&success=". $pseudo ."'; </script>";
                        }else{
                            echo "<script>document.location.href='index.php?success=". $pseudo ."'; </script>";
                        }
                    }
                }else {
                    notif("error", "Nous n'avons pas pu vous retrouver...");
                }

            }
        }
    }else {
        ?>

        <div class="connexion_wrapper">
            <h1>ERREUR</h1>
            <div class="connexion"> 
                <div class="field">Vous êtes déjà connecté.</div>
            </div>
        </div>
<?php
        
    }
?>