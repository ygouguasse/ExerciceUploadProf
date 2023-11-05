<?php

require_once "modele/BD.php";

class ModeleUtilisateur {
    public static function ObtenirUtilisateur($nom) {
        $sql = 'SELECT * FROM utilisateurs WHERE nom = :nom';

        $requete = BD::ObtenirConnexion()->prepare($sql);
        $requete->bindparam('nom', $nom, pdo::PARAM_STR);
        $requete->execute();

        return $requete;
    }
}
?>