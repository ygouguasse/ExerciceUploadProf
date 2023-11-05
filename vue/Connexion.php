<?php $titre = 'Connexion'; ?>

<?php ob_start(); ?>

<?php
	function Color($user) {
		if (!empty($_SESSION['user']) && $_SESSION['user']['nom'] === $user) {
			echo 'secondary';
		}else {
			echo 'primary';
		}
	}
?>

<div class="row gap-3 mb-3">
	<form class="col-sm d-flex justify-content-center" action="index.php?action=Connecter" method="POST">
		<input type="hidden" name="user" value="admin">
		<button type="submit" class="btn btn-<?php Color("admin") ?>">
			Connexion admin
		</button>
	</form>

    <form class="col-sm d-flex justify-content-center" action="index.php?action=Deconnecter" method="POST">
        <button type="submit" class="btn btn-danger">Déconnexion</button>
    </form>

	<form class="col-sm d-flex justify-content-center" action="index.php?action=Connecter" method="POST">
		<input type="hidden" name="user" value="user">
		<button type="submit" class="btn btn-<?php Color("user") ?>">
			Connexion user
		</button>
	</form>
</div>

<?php $contenu = ob_get_clean(); ?>

<?php require 'vue/Gabarit.php'; ?>