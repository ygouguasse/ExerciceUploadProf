<?php

require_once "modele/BD.php";

class ModelePermissionFichierUtilisateur {
    public static function AjouterPermission($utilisateurId, $fichierId) {
        $sql = 'INSERT INTO `permissions_fichiers_utilisateurs`
                    (`utilisateur`, `fichier`)
                VALUES
                    (:utilisateurId, :fichierId);
        ';
        
        $requete = BD::ObtenirConnexion()->prepare($sql);
        $requete->bindparam('utilisateurId', $utilisateurId, PDO::PARAM_INT);
        $requete->bindparam('fichierId', $fichierId, PDO::PARAM_INT);
        $requete->execute();

        return $requete;
    }
}
?>