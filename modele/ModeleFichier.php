<?php

require_once "modele/BD.php";

class ModeleFichier {
    public static function ObtenirFichier($nom, $utilisateurId) {
        $sql = 'SELECT *
                FROM fichiers
                INNER JOIN permissions_fichiers_utilisateurs
                    ON fichiers.id = permissions_fichiers_utilisateurs.fichier
                WHERE nom = :nom
                    AND permissions_fichiers_utilisateurs.utilisateur = :utilisateurId
        ';

        $requete = BD::ObtenirConnexion()->prepare($sql);
        $requete->bindparam('nom', $nom, pdo::PARAM_STR);
        $requete->bindparam('utilisateurId', $utilisateurId, PDO::PARAM_INT);
        $requete->execute();

        return $requete;
    }

    public static function AjouterFichier($nom, $extension, $description) {
        $sql = 'INSERT INTO `fichiers`
                    (`nom`, `extension`, `description`)
                VALUES
                    (:nom, :extension, :description);
        ';
        
        // https://www.w3schools.com/php/php_mysql_insert_lastid.asp
        $connection = BD::ObtenirConnexion();
        $requete = $connection->prepare($sql);
        $requete->bindparam('nom', $nom, PDO::PARAM_STR);
        $requete->bindparam('extension', $extension, PDO::PARAM_STR);
        $requete->bindparam('description', $description, PDO::PARAM_STR);
        $requete->execute();

        return $connection->lastInsertId();
    }

    public static function ObtenirFichiers($utilisateurId) {
        $sql = 'SELECT *
                FROM fichiers
                INNER JOIN permissions_fichiers_utilisateurs
                    ON fichiers.id = permissions_fichiers_utilisateurs.fichier
                WHERE permissions_fichiers_utilisateurs.utilisateur = :utilisateurId
        ';

        $requete = BD::ObtenirConnexion()->prepare($sql);
        $requete->bindparam('utilisateurId', $utilisateurId, PDO::PARAM_INT);
        $requete->execute();

        return $requete;
    }
}
?>