<?php

require 'modele/ModeleFichier.php';
require 'modele/ModeleUtilisateur.php';
require 'modele/ModelePermissionFichierUtilisateur.php';

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
	DemarrerSession();
	$images = [];
	if (!empty($_SESSION['user'])) {
		$requeteFichiers = ModeleFichier::ObtenirFichiers($_SESSION['user']['id']);
		$images = $requeteFichiers->fetchAll();
		$requeteFichiers->closeCursor();
	}
	require 'vue/Images.php';
}

function AjoutImageFormulaire()
{
	$resultatAjout = AjoutImage();
	
	$statusCode = 200; // OK
	$redirect = 'location: index.php?action=FormulaireUpload';

	if (!empty($resultatAjout['erreur'])) {
		$statusCode = 400; // Bad Request
		if ($resultatAjout['erreur'] === 'PasConnecte') {
			$statusCode = 401; // Unauthorized
		}
		$redirect .= '&erreur=' . $resultatAjout['erreur'];
	}

	if (!empty($resultatAjout['succes'])) {
		$redirect .= '&succes=' . $resultatAjout['succes'];
	}

	http_response_code($statusCode);
	header($redirect);
}

function AjoutImageAjax()
{
	header('Content-Type: application/json; charset=utf-8');
	$resultatAjout = AjoutImage();
	$statusCode = 200; // OK

	if (!empty($resultatAjout['erreur'])) {
		$statusCode = 400; // Bad Request
		if ($resultatAjout['erreur'] === 'PasConnecte') {
			$statusCode = 401; // Unauthorized
		}
	}

	http_response_code($statusCode);
	echo json_encode($resultatAjout);
}

function AjoutImage()
{
	$tailleMaximale = 1024 * 1024 * 3; // 3 MB
	$typesAuthorises = [
		'image/png' => 'png',
		'image/jpeg' => 'jpg'
	];

	$infosValidation = ValiderFichier('image', 'description', $tailleMaximale, $typesAuthorises);
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
	$fichierId = ModeleFichier::AjouterFichier($nom, $extension, $infosValidation['description']);
	ModelePermissionFichierUtilisateur::AjouterPermission($infosValidation['user']['id'], $fichierId);

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

function ValiderFichier($nomChampFichier, $nomChampDescription, $tailleMaximale, $typesAuthorises)
{
	// On doit être connecté pour pouvoir ajouter un fichier.
	DemarrerSession();
	if (empty($_SESSION['user'])) {
		return ['erreur' => 'PasConnecte'];
	}

	// On regarde si on a reçu un fichier.
	// $nomChampFichier est le name de l'input de type file.
	if (!isset($_FILES[$nomChampFichier])) {
		return ['erreur' => 'PasDeFichier'];
	}

	// On regarde si on a reçu une description.
	if (!isset($_POST[$nomChampDescription])) {
		return ['erreur' => 'PasDeDescription'];
	}

	// Lorsque PHP reçoit un fichier, il le place dans un dossier
	// temporaire avec un nom autogénéré.
	// $_FILES[$nomChampFichier]['tmp_name'] permet de récupérer le chemin d'accès à ce fichier.
	$cheminFichier = $_FILES[$nomChampFichier]['tmp_name'];
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
		'extension' => $typesAuthorises[$typeFichier],
		'description' => $_POST[$nomChampDescription],
		'user' => $_SESSION['user'],
	];
}

function ObtenirImage()
{
	if (empty($_GET['image'])) {
		http_response_code(400);
		return;
	}

	DemarrerSession();
	$imageInfos = [];
	if (!empty($_SESSION['user'])) {
		$requeteFichier = ModeleFichier::ObtenirFichier($_GET['image'], $_SESSION['user']['id']);
		$imageInfos = $requeteFichier->fetch();
		$requeteFichier->closeCursor();
	}

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
	DemarrerSession();
	require 'vue/Connexion.php';
}

function Connecter()
{
	header('location: index.php?action=Connexion');

	if (empty($_POST['user'])) {
		http_response_code(400);
		return;
	}

	DemarrerSession();
	$requeteUtilisateur = ModeleUtilisateur::ObtenirUtilisateur($_POST['user']);
	$utilisateur = $requeteUtilisateur->fetch();
	$requeteUtilisateur->closeCursor();
	if (!$utilisateur) {
		http_response_code(401);
		return;
	}

	$_SESSION['user'] = $utilisateur;
}

function Deconnecter()
{
	DemarrerSession();
	session_unset();
	session_destroy();

	header('location: index.php?action=Connexion');
}
