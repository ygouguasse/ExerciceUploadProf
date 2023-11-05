window.addEventListener('load', Initialiser);

function Initialiser() {
	document.getElementById('form_upload')
		.addEventListener('submit', GereSoumissionFormulaire);
}

async function GereSoumissionFormulaire(event) {
	// On remplace la soumission par défaut
	event.preventDefault();
	const formulaire = event.currentTarget;
	const imageInput = formulaire.querySelector("#image");

	RetirerMessage();

	if (!formulaire.checkValidity()) { return; }

	// Remarquez qu'on place les données dans un FormData, pour avoir
	// pour avoir la même structure qu'une soumission standard d'un form.
	// Ainsi, PHP sera en mesure d'extraire les données dans $_GET, $_POST et $_FILE
	// tout dépendamment de ce qui est utilisé.
	const data = new FormData();
	// On récupère le fichier de l'image sélectionné.
	data.append('image', imageInput.files[0]);
	// Si on avait d'autres champs, on pourrait les ajouter.
	// data.append('utilisateur', formulaire.querySelector('#utilisateur').value);
	// data.append('mot_de_passe', formulaire.querySelector('#mot_de_passe').value);

	// Le navigateur va automatiquement ajouter le headers "Content-Type: multipart/form-data"
	try {
		const reponse = await fetch('index.php?action=AjoutImageAjax', {
			method: 'POST',
			body: data
		});

		const resultat = await reponse.json();

		if (!reponse.ok) {
			if (resultat["erreur"]) {
				throw new Error(resultat["erreur"]);
			}
			throw new Error('erreur');
		}

		AfficherMessageSucces();
		// Vide les champs du formulaire
		formulaire.reset();
		// Il faut retirer la classe "was-validated" du formulaire, sinon
		// les champs seront en erreur, car on vient de les vider.
		formulaire.classList.remove('was-validated');
	} catch (erreur) {
		AfficherMessageErreur(erreur);
	}
}

function RetirerMessage() {
	document.getElementById("message").innerHTML = "";
}

function AfficherMessageErreur(erreur) {
	const erreurs = {
		'PasDeFichier': 'Aucune image reçue.',
		'FichierVide': 'Image vide.',
		'FichierTropGros': 'Image trop volumineuse.',
		'TypeFichierNonAuthorise': 'Type de fichier non authorisé. Veuillez choisir une image de type PNG ou JPEG/JPG.',
	};
	let message = "Une erreur s'est produite lors du téléversement de l'image.";

	if (erreurs[erreur.message]) {
		message = erreurs[erreur.message];
	}

	document.getElementById("message").innerHTML = `
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			${message}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	`;
}

function AfficherMessageSucces() {
	document.getElementById("message").innerHTML = `
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			Image téléversée avec succès!
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	`;
}