<?php
$pdo = new PDO('mysql:host=localhost;dbname=mon_projet', 'root', '', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
// print_r($_POST);
$tab = array(); // on prépare la réponse
$tab['connexion'] = '';

// on ouvre la session
session_start(); 
// ^met à disposition l'outil superglobale $_SESSION

//
if(isset($_POST['pseudo']) && isset($_POST['mdp'])){
    $pseudo = $_POST['pseudo'];
    $mdp = $_POST['mdp'];

    // on déclenche une requête en BDD sur la base du pseudo récupéré
    $verif_connexion =  $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $verif_connexion->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $verif_connexion->execute();

    //$tab['connexion'] = $verif_connexion->rowCount();

    // on vérifie si on a une  ligne dans la réponse(si le pseudo est bon)
    if($verif_connexion->rowCount() > 0){
        //pseudo ok

        //on vérifie le mot de passe
        $infos = $verif_connexion->fetch(PDO::FETCH_ASSOC);
        if(password_verify($mdp,$infos['mdp'])){
            //pseudo ok, mdp ok
            //on met les infos de l'user dans la $_SESSION pour la conserver et simuler un user connecté
            $_SESSION['membre']['pseudo'] = $infos['id_membre'];
            $_SESSION['membre']['pseudo'] = $infos['pseudo'];
            $_SESSION['membre']['nom'] = $infos['nom'];
            $_SESSION['membre']['prenom'] = $infos['prenom'];
            $_SESSION['membre']['email'] = $infos['email'];
            $_SESSION['membre']['date_inscription'] = $infos['date_inscription'];

            /*
            $_SESSION['membre'] = $infos;
            unset($_SESSION['membre']['mdp]);
            */

            $tab['connexion'] .= 'ok';

        } else {
        //erreur sur le mdp
        $tab['connexion'] .= '<div class="alert alert-danger">Erreur sur le pseudo et/ou le mot de passe</div>';
        }
        
    }else{
        //erreur sur le pseudo
        $tab['connexion'] .= '<div class="alert alert-danger">Erreur sur le pseudo et/ou le mot de passe</div>';
    }

}



echo json_encode($tab); // on affiche la réponse au format json.