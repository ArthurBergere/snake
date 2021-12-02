<?php
Include "connexion.php";




if ($connexion)
    {  
        if (!empty($_GET['lepseudo']) && !empty($_GET['lescore'])) {
            echo "Les scripts ne sont pas accepté";
            $requete = $connexion->prepare('INSERT INTO joueur (pseudo, score) VALUES (:pseudo, :score)');
            $requete-> bindValue(':pseudo', htmlentities($_GET['lepseudo']));
            $requete-> bindValue(':score',htmlentities($_GET['lescore']));
            $resultat = $requete->execute();
    
            if ($resultat){
                echo " bien recu chef , tout est enregistré ! ";
                header('Location: /snake/index.php');
                
            }
            else {
                echo "erreur ybad";
            }
        }
        else {
             echo "erreur un des champs était vide, vous devez faire un score puis ensuite entrer votre pseudo !";
        }
}
?>