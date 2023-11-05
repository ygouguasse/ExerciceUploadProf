<?php $titre = 'Accueil'; ?>

<?php ob_start(); ?>

<h1>Exemple upload</h1>

<?php $contenu = ob_get_clean(); ?>

<?php require 'vue/Gabarit.php'; ?>