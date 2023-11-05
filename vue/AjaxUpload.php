
<?php $titre = 'AJAX Upload'; ?>

<?php ob_start(); ?>

<div id="message"></div>

<form action="index.php?action=AjoutImageFormulaire" class="needs-validation" method="POST" enctype="multipart/form-data" id="form_upload" novalidate>
	<div class="mb-3">
		<label for="image" class="form-label">Choisissez une image</label>
		<input class="form-control" type="file" id="image" name="image" accept="image/png, image/jpeg" required>
		<div class="invalid-feedback">Veuillez choisir une image de type PNG ou JPEG/JPG</div>
	</div>

	<button type="submit" class="btn btn-primary">Soumettre</button>
</form>

<script src="js/validationFormulaire.js"></script>
<script src="js/ajaxUpload.js"></script>

<?php $contenu = ob_get_clean(); ?>

<?php require 'vue/Gabarit.php'; ?>