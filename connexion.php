

<?php
	
try
        {
            $connexion = new PDO('mysql:host=localhost:3306;dbname=arthurbe_snake', 'arthurbe_artesburger', 'Harry513!');
            $connexion ->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $connexion ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
catch(Exception $e)
        {
            die('Erreur : '.$e->getMessage());
        }
?>    