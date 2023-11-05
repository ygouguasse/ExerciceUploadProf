<?php

require_once "modele/BD.php";

class ModeleFichier {
    public static function ObtenirFichier($nom) {
        $sql = 'SELECT * FROM fichiers WHERE nom = :nom';

        $requete = BD::ObtenirConnexion()->prepare($sql);
        $requete->bindparam('nom', $nom, pdo::PARAM_STR);
        $requete->execute();

        return $requete;
    }

    public static function AjouterFichier($nom, $extension, $description) {
        $sql = 'INSERT INTO
                    fichiers (nom, extension, description)
                VALUES (:nom, :extension, :description)';
        
        $requete = BD::ObtenirConnexion()->prepare($sql);
        $requete->bindparam('nom', $nom, PDO::PARAM_STR);
        $requete->bindparam('extension', $extension, PDO::PARAM_STR);
        $requete->bindparam('description', $description, PDO::PARAM_STR);
        $requete->execute();

        return $requete;
    }

    public static function ObtenirFichiers() {
        $sql = 'SELECT * FROM fichiers';

        $requete = BD::ObtenirConnexion()->prepare($sql);
        $requete->execute();

        return $requete;
    }
}
?>