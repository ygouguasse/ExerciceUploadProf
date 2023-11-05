<?php $titre = 'Fomrulaire Upload'; ?>

<?php ob_start(); ?>

<?php
function AfficherMessageSucces()
{
	if (!empty($_GET['succes'])) { ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			Image téléversée avec succès!
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php }
}

function AfficherErreurUpload()
{
	$erreurs = [
		'PasDeFichier' => 'Aucune image reçue.',
		'FichierVide' => 'Image vide.',
		'FichierTropGros' => 'Image trop volumineuse.',
		'TypeFichierNonAuthorise' => 'Type de fichier non authorisé. Veuillez choisir une image de type PNG ou JPEG/JPG.',
		'erreur' => 'Une erreur s\'est produite lors du téléversement de l\'image.'
	];


	if (!empty($_GET['erreur'])) { ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<?php
			if (!empty($erreurs[$_GET['erreur']])) {
				echo $erreurs[$_GET['erreur']];
			} else {
				echo $erreurs['erreur'];
			}
			?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
<?php }
}
?>

<?php AfficherMessageSucces(); ?>

<form action="index.php?action=AjoutImageFormulaire" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
	<div class="mb-3">
		<label for="image" class="form-label">Choisissez une image</label>
		<input class="form-control" type="file" id="image" name="image" accept="image/png, image/jpeg" required>
		<div class="invalid-feedback">Veuillez choisir une image de type PNG ou JPEG/JPG</div>
	</div>

	<button type="submit" class="btn btn-primary">Soumettre</button>
</form>

<script src="js/validationFormulaire.js"></script>

<?php $contenu = ob_get_clean(); ?>

<?php require 'vue/Gabarit.php'; ?>