<?php $titre = 'Images'; ?>

<?php ob_start(); ?>

<?php
foreach ($images as $image) { ?>
	<article class="row pb-2">
		<div class="col-md-6 d-flex align-items-center bg-light rounded">
			<img class="w-100 h-auto" src="index.php?action=ObtenirImage&image=<?php echo $image['nom']; ?>" alt="<?php echo $image['description']; ?>">
		</div>
		<div class="col-md-6 d-flex align-items-center rounded bg-info p-4">
			<p class="text-center w-100">
				<?php echo $image['description']; ?>
			</p>
		</div>
	</article>
	<div>
		
	</div>
<?php }
?>

<?php $contenu = ob_get_clean(); ?>

<?php require 'vue/Gabarit.php'; ?>