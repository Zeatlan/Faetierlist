
    <?php include 'skeleton.php';
        ?>

        <title>Inscription</title>
    </head>
    <body>
<?php
    if(empty($_SESSION) || !isset($_SESSION['id'])){
?>
        <div class="inscription_wrapper">
        <h1>Inscription</h1>
        <form method="POST">
        <div class="info_inscription"><b>Veuillez activer vos messages privés Discord </b>afin de recevoir un lien d'activation pour valider votre inscription.</div>
        <div class="inscription">
            <div class="field"><div class="field_img"><i class="fas fa-user"></i></div><input class="field_input" type="text" name="pseudo" placeholder="Pseudonyme" required></div>
            <div class="field"><div class="field_img"><i class="fas fa-unlock-alt"></i></div><input class="field_input" type="password" name="password" placeholder="Mot de passe" required></div>
            <div class="field"><div class="field_img"><i class="fas fa-redo-alt"></i></div><input class="field_input" type="password" name="passwordconfirm" placeholder="Confirmer" required></div>
            </div>
            

            <div class="discord"> <div class="img_discord"><i class="fab fa-discord"></i></div>  <input class="discord_input" type="text" name="discord" placeholder="TagDiscord#Nombre" required></div>
            
            <input type="hidden" name="referer" value="<?php echo (isset($_SERVER['HTTP_REFERER']))? $_SERVER['HTTP_REFERER'] : "index.php"; ?>">
            
            <input class="btn_inscription" type="submit" value="S'inscrire">
        </form>


        </div>
        


<?php
        if(isset($_POST) && !empty($_POST)){
            $pseudo = htmlspecialchars($_POST['pseudo']);
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬]/', $pseudo))
            {
                notif("error", "Votre pseudo contient des caractères interdits. [". $pseudo ."]");
            }else{
                $pass = md5($_POST['password']);
                $confirm = md5($_POST['passwordconfirm']);
                $discord = htmlspecialchars($_POST['discord']);

                $member = $membre->getByPseudo($pseudo);
                if($member->id() != null){
                    notif("error", "Cet utilisateur existe déjà");
                }else {
                    if(strlen($pseudo) >= 3 && strlen($pseudo) < 20){
                        if($pass == $confirm){
                            if(strlen($_POST['password']) >= 8){
                                $verif = explode("#", $discord);
                                
                                $discordExist = $db->prepare("SELECT * FROM membre WHERE u_discord = :discord");
                                $discordExist->bindParam(':discord', $discord);
                                $discordExist->execute();
                                $de = $discordExist->fetch();

                                if(sizeof($verif) > 1 && sizeof($verif) < 3 && strlen($verif[1]) == 4 && $_POST['discord'] != "VerifyBot#0554"){
                                    if(!$de){
                                        $array = [];
                                        // On va pouvoir enfin l'ajouter
                                        $newMember = new Member($array);
                                        $newMember->setPseudo($pseudo);
                                        $newMember->setPassword($pass);
                                        $newMember->setJoinedtime(time());
                                        $newMember->setDiscord($discord);
                                        $newMember->setAdmin(0);
                                        $_SESSION['validation'] = rand();

                                        $data = array("discord" => $discord, "pseudo" => $pseudo, "id" => $_SESSION['validation']);                                                                    
                                        $data_string = json_encode($data);


                                        $ch = curl_init("http://vps642086.ovh.net:5665/sendMessage");

                                        curl_setopt($ch, CURLOPT_HEADER, FALSE);   
                                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                            
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                                            'Content-Type: application/json',                                                                                
                                            'Content-Length: ' . strlen($data_string))                                                                       
                                        ); 
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

                                        $result = json_decode(curl_exec($ch));

                                        curl_close($ch);
                                        
                                                
                                        
                                        if($result->message == 'error'){
                                            session_destroy();
                                            notif("error", "Nous n'avons pas réussit à vous trouver sur Discord, réessayez.");
                                        }else{
                                            $membre->add($newMember);

                                            $_SESSION['username'] = $pseudo;
                                            $_SESSION['permission'] = 0;
                                            $_SESSION['new'] = true;


                                            $memberr = $membre->getByPseudo($pseudo);
                                            $_SESSION['id'] = $memberr->id();

                                            echo '<script> document.location.href="index.php?success='.$pseudo .'";</script>';
                                        }
                                    }else{
                                        notif("error", "Compte discord déjà utilisé.");
                                    }
                                }else {
                                    notif("error", "Compte discord inexistant.");
                                }
                            }else {
                                notif("error", "8 caractères au minimum sont demandés pour votre mot de passe.");
                            }
                        }else {
                            notif("error", "Les mots de passes ne correspondent pas.");
                        }
                    }else {
                        notif("error", "Pseudo trop long.");
                    }
                }
            }
        }
    }else {
        ?>
    <body>
        <div class="inscription_wrapper">
        <h1>Erreur</h1>
        <div class="info_inscription">Vous êtes déjà inscrit.</div>

        </div>
        <?php
    }
?>
