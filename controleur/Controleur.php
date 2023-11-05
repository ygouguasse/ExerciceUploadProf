<?php

require 'modele/ModeleFichier.php';

function DemarrerSession()
{
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
}

function AfficherPageDefaut()
{
	AfficherPageAccueil();
}

function AfficherPageAccueil()
{
	require 'vue/Accueil.php';
}

function AfficherPageFormulaireUpload()
{
	require 'vue/FormulaireUpload.php';
}

function AfficherPageAjaxUpload()
{
	require 'vue/AjaxUpload.php';
}

function AfficherPageImages()
{
	$requeteFichiers = ModeleFichier::ObtenirFichiers();
	require 'vue/Images.php';
}

function AjoutImageFormulaire()
{
	$resultatAjout = AjoutImage();

	if (!empty($resultatAjout['erreur'])) {
		header('location: index.php?action=FormulaireUpload&erreur=' . $resultatAjout['erreur']);
		return;
	}

	header('location: index.php?action=FormulaireUpload&succes=' . $resultatAjout['succes']);
}

function AjoutImageAjax()
{
	header('Content-Type: application/json; charset=utf-8');
	$resultatAjout = AjoutImage();

	if (!empty($resultatAjout['erreur'])) {
		http_response_code(400);
	}

	echo json_encode($resultatAjout);
}

function AjoutImage()
{
	$tailleMaximale = 1024 * 1024 * 3; // 3 MB
	$typesAuthorises = [
		'image/png' => 'png',
		'image/jpeg' => 'jpg'
	];

	$infosValidation = ValiderFichier('image', $tailleMaximale, $typesAuthorises);
	if (!empty($infosValidation['erreur'])) {
		return $infosValidation;
	}

	// On place les images dans le dossier uploads/images/
	$dossierImages = 'uploads/images/';
	// On donne un nom aléatoire au fichier pour ne pas écraser un fichier existant.
	$infosCheminFichier = ObtenirCheminFichierUnique($dossierImages, $infosValidation['extension']);
	$nom = $infosCheminFichier['nom'];
	$chemin = $infosCheminFichier['chemin'];
	$extension = $infosValidation['extension'];

	if (!move_uploaded_file($_FILES['image']['tmp_name'], $chemin)) {
		// Impossible de transférer le fichier
		return ['erreur' => 'erreur'];
	}

	// On ajoute l'image à la base de données.
	ModeleFichier::AjouterFichier($nom, $extension, basename($_FILES['image']['name']));

	return [
		'succes' => 'true',
	];
}

function ObtenirCheminFichierUnique($dossier, $extension)
{
	// Dans un environnement de production, on utiliserait un GUID au lieu de uniqid
	$nouveauNomFichier = uniqid('', true);
	$nouveauCheminFichier = $dossier . $nouveauNomFichier . '.' . $extension;

	while (file_exists($nouveauCheminFichier)) {
		$nouveauNomFichier = uniqid('', true);
		$nouveauCheminFichier = $dossier . $nouveauNomFichier . '.' . $extension;
	}

	return [
		'nom' => $nouveauNomFichier,
		'chemin' => $nouveauCheminFichier,
	];
}

function ValiderFichier($name, $tailleMaximale, $typesAuthorises)
{
	// On regarde si on a reçu un fichier.
	// $name est le name de l'input de type file.
	if (!isset($_FILES[$name])) {
		return ['erreur' => 'PasDeFichier'];
	}

	// Lorsque PHP reçoit un fichier, il le place dans un dossier
	// temporaire avec un nom autogénéré.
	// $_FILES[$name]['tmp_name'] permet de récupérer le chemin d'accès à ce fichier.
	$cheminFichier = $_FILES[$name]['tmp_name'];
	// Pour des raisons de sécurité, nous ne pouvons pas obtenir
	// la taille ou le type du fichier en utilisant $_FILES.
	$tailleFichier = filesize($cheminFichier);
	$fileinfo = finfo_open(FILEINFO_MIME_TYPE);
	$typeFichier = finfo_file($fileinfo, $cheminFichier);

	if ($tailleFichier === 0) {
		return ['erreur' => 'FichierVide'];
	}

	if ($tailleFichier > $tailleMaximale) {
		return ['erreur' => 'FichierTropGros'];
	}

	if (!in_array($typeFichier, array_keys($typesAuthorises))) {
		return ['erreur' => 'TypeFichierNonAuthorise'];
	}

	return [
		'extension' => $typesAuthorises[$typeFichier]
	];
}

function ObtenirImage()
{
	if (empty($_GET['image'])) {
		http_response_code(400);
		return;
	}

	$requeteImage = ModeleFichier::ObtenirFichier($_GET['image']);
	$imageInfos = $requeteImage->fetch();
	$requeteImage->closeCursor();

	if (!$imageInfos) {
		http_response_code(404);
		return;
	}

	$nomFichier = $imageInfos['nom'] . '.' . $imageInfos['extension'];
	$cheminFichier = 'uploads/images/' . $nomFichier;

	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="' . $nomFichier . '"');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($cheminFichier));
	readfile($cheminFichier);
}

function AfficherPageConnexion()
{
	require 'vue/Connexion.php';
}

function Connecter()
{
	if (empty($_POST['user'])) {
		http_response_code(400);
		return;
	}

	DemarrerSession();
	$_SESSION['user'] = $_POST['user'];

	header('location: index.php?action=Connexion');
}

function Deconnecter()
{
	DemarrerSession();
	session_unset();
	session_destroy();

	header('location: index.php?action=Connexion');
}
