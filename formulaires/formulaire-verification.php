<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Formulaire vérification</title>
	<style>
        body {
            font: 1em Verdana, sans-serif;
            background-color: #c5daf5;
            margin: 15px;
		}

		.form-block {margin-top: 15px;}


		input[type="checkbox"],
        input[type="radio"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            -ms-appearance: none;
            appearance: none;

            border: 3px solid #487bc7;
            height: 20px;
            width: 20px;
            margin: 2px 5px 2px 0;

            vertical-align: -6px;
        }
        input[type="checkbox"]:checked,
        input[type="radio"]:checked {
            background-color: #1b48b4;
            box-shadow: inset 0px 0px 0px 2px white;
            outline: none; /* Pour Chrome */
        }
        input[type="radio"] {
            border-radius: 50%;
        }

		input[type="text"],
		input[type="number"],
		textarea,
		select {
            border: 1px solid #4caee2;
            border-radius: 10px;
            padding: 9px 12px;
            font: 1em Verdana, sans-serif;
			outline: none;
			margin-top: 8px;
		}

		input[type="number"] {
			-moz-appearance: textfield; /* Retirer les flèches des inputs de type number. Firefox */
		}
		input[type="number"]::-webkit-inner-spin-button,
		input[type="number"]::-webkit-outer-spin-button {
			-webkit-appearance: none; /* Chrome */
			margin: 0;
		}
		input[type="number"]::-o-inner-spin-button,
		input[type="number"]::-o-outer-spin-button {
			-o-appearance: none; /* Opera */
			margin: 0
		}

		.select-container {
			position: relative;
			display: inline-block;
		}

		select {
			padding: 6px 22px 6px 10px; /* On met plus de padding à droite pour y insérer la flèche */

			-webkit-appearance: none;
			-moz-appearance: none;
			-ms-appearance: none;
			appearance: none; /* Cacher la flèche de select */
		}
		.arrow-down:after {
			color: #4caee2;
			content: "\25BC"; /* ▼ */
			font-size: .8em;
			position: absolute;
			pointer-events: none; /* Sans cela, il est impossible de cliquer à cette endroit */
			right: 6px;
			top: 16px;
		}
	
		input[type="file"] {
			display: none; /* On le cache pour pouvoir mettre un autre style */
		}

		.input-file,
		input[type="submit"],
		button {
			background-color: #4487be;
			color: white;
			border-radius: 10px;
			font: 1em Verdana, sans-serif;
			display: inline-block;
			padding: 9px 12px;
			cursor: pointer;
		}

		button {
            border: none;
			margin-top: 50px;
			outline: none; /* Eviter bordure sur chrome */
		}

		.input-file:hover,
		input[type="submit"]:hover,
		button:hover {
			background-color: #375c86;
		}

		textarea {
			width: 30em;
            height: 4em;
			resize: none; /* Désactiver le redimensionnement de textarea */
		}


		.invalid {
			color: red;
		}
		.form-invalid {
			background-color: #feebf8;
		}
    </style>
</head>
<body>

	<h1>Formulaire avec vérification</h1>

	<?php
	$genre = $pseudo = $age = $talent = $message = null; // Par défaut. Pour éviter que les values affichent des erreurs quand le formulaire n'est pas encore envoyé

	$liste_talents = ["Absentéisme","Absorb Eau","Absorb Volt","Acharné","Adaptabilité","Agitation","Ailes Bourrasque","Air Lock","Alerte Neige","Ame vagabonde","Amour Filial","Analyste","Animacoeur","Annule Garde","Anti-Bruit","Anticipation","Aquabulle","Armumagma","Armurbaston","Armure Miroir","Armurouillée","Aroma-Voile","Attention","Aura Féerique","Aura Inversée","Aura Ténébreuse","Baigne Sable","Bajoues","Banc","Battant","Batterie","Benêt","Boom Final","Boost Acier","Boost Chimère","Bouclier-Carcan","Boule de Poils","Brasier","Brise Moule","Brise-Barrière","Calque","Cercle d'énergie","Chanceux","Chasse-Neige","Cherche Miel","Chlorophylle","Ciel Gris","Coeur de Coq","Coeur Noble","Coeur Soin","Coloforce","Colérique","Contestation","Coque Armure","Corps Ardent","Corps Coloré","Corps condamné","Corps Gel","Corps Maudit","Corps Sain","Corrosion","Crachin","Cran","Cruauté","Créa-Brume","Créa-Herbe","Créa-Psy","Créa-Élec","Cuvette","Cérébro-Force","Danseuse","Don Floral","Dracolère","Début Calme","Déclic Fringale","Déclic Tactique","Défaitiste","Dégobage","Déguisement","Délestage","Ecaille Spéciale","Ecran Fumée","Effilochage","Endurance","Engrais","Entêtement","Envelocape","Epine de fer","Escampette","Esprit Vital","Essaim","Expert Acier","Expul'sable","Expuls'Organes","Écailles Glacées","Échauffement","Écran Poudre","Égide Inflexible","Fantômasque","Farceur","Fermeté","Feuille Garde","Filature","Filtre","Flora-Voile","Force Pure","Force Sable","Force Soleil","Fouille","Frein","Fuite","Garde Amie","Garde Magik","Garde Mystik","Gaz Inhibiteur","Glissade","Gloutonnerie","Gluco-Voile","Glue","Griffe Dure","Heavy Metal","Herbivore","Hydrata-Son","Hydratation","Hyper Cutter","Hypersommeil","Ignifu-Voile","Ignifuge","Illusion","Impassible","Imposteur","Impudence","Inconscient","Infiltration","Insomnia","Intimidation","Isograisse","Joli Sourire","Lame Indomptable","Lavabo","Lentiteintée","Libéro","Light Metal","Longue Portée","Lumiattirance","Lunatique","Lévitation","Magicien","Magnépiège","Maladresse","Marque Ombre","Matinal","Mauvais Rêve","Mer Primaire","Mimétisme","Minus","Miroir Magik","Mode Transe","Moiteur","Momie","Motorisé","Mue","Multi-Coups","Multitype","Multiécaille","Mèche Rebelle","Médic Nature","Méga Blaster","Métallo-Garde","Météo","Mûrissement","Nerfs d'acier","Normalise","Oeil Composé","Osmose","Paratonnerre","Pare-Balles","Peau Céleste","Peau Dure","Peau Féerique","Peau Gelée","Peau Miracle","Peau Sèche","Peau Électrique","Phobique","Pickpocket","Pied Véloce","Pieds Confus","Piège","Plus","Poing de Fer","Point Poison","Poisseux","Pose Spore","Pression","Prestance Royale","Prioguérison","Prisme-Armure","Prognathe","Propulseur","Protéen","Prédiction","Puanteur","Punk Rock","Querelleur","Rage Brûlure","Rage Poison","Ramassage","Ramasse Ball","Rassemblement","Receveur","Regard Vif","Repli Tactique","Rideau Neige","Rivalité","Récolte","Régé-Force","Sable Humide","Sable Volant","Sans Limite","Simple","Sniper","Soin Poison","Solide Roc","Souffle Delta","Spectro-Bouclier","Statik","Suintement","Surf Caudal","Symbiose","Synchro","Synergie","Système Alpha","Sécheresse","Sérénité","Technicien","Tempo Perso","Tension","Terre Finale","Toison Epaisse","Toison Herbue","Torche","Torrent","Toxitouche","Turbine","Turbo","Turbobrasier","Télécharge","Télépathe","Téméraire","Téra-Voltage","Tête de gel","Tête de Roc","Vaccin","Ventouse","Victorieux","Voile Pastel","Voile Sable"];


	// ============================== Envoi du formulaire ==============================

	// if (!empty($_POST['message'])) { /* S'il y a plusieurs formulaires sur une même page, on précise un name utilisé dans le formulaire */
	if (!empty($_POST)) { /* Ici, on utilise uniquement !empty (pas isset), car avec isset, on peut envoyer des champs vides */

		// print_r($_POST);
		/* Tableau obtenu. Exemple :
		[
			'pseudo' => 'Test'
			'age' => '30'
		]
		*/

		// $genre = $_POST['genre']; // Genre posté
		// $pseudo = trim($_POST['pseudo']); // Pseudo posté. trim() efface les éventuels espaces avant et après
		// $age = $_POST['age']; // Age posté
		// $talent = $_POST['talent']; // Talent posté

		// Equivalent à ci-dessus pour éviter la répétition :
		foreach ($_POST as $variable => $value) {
			$$variable = trim($value); // Equivaut à : $genre = $_POST['genre']; $pseudo = $_POST['pseudo']; etc...
		}
		
		$erreurs = []; // Définir un tableau d'erreur vide qui va se remplir après chaque erreur
		if (empty($genre)) {
			$erreurs['genre'] = 'Mettez un genre !';
		}
		if (empty($pseudo)) {
			$erreurs['pseudo'] = 'Mettez un pseudo !';
		}
		if (empty($age)) {
			$erreurs['age'] = 'Mettez votre âge !';
		}
		if (empty($talent)) {
			$erreurs['talent'] = 'Mettez un talent !';
		}
		if (empty($message)) {
			$erreurs['message'] = 'Écrivez un message !';
		}

		$image = $_FILES['img']; // Tableau avec toutes les infos sur l'image uploadée
		// var_dump($image); // On constate qu'il y a toujours des infos, même si on n'ajoute pas d'image. "Code erreur 4" s’il n’y a pas d’image
		if ($image['error'] === 4) {
			$erreurs['img'] = 'Ajoutez une image !';
		}
		else {
			$filetmp = $image['tmp_name']; // Emplacement du fichier temp
			$finfo = finfo_open(FILEINFO_MIME_TYPE); // Permet d’ouvrir un fichier
			$mimeType = finfo_file($finfo, $filetmp); // Ouvre le fichier et renvoie image/jpg
			$allowedExtensions = ['image/jpg', 'image/jpeg', 'image/gif', 'image/png'];

			if (!in_array($mimeType, $allowedExtensions)) {
				$erreurs['img'] = 'Uniquement jpg, jpeg, gif et png !';
			}
			if ($image['size'] / 1024 > 1000) {
				$erreurs['img'] = 'Max 1 Mo !';
			}
		}


		// -------------------- S'il n'y a pas d'erreur dans le formulaire : --------------------

		if (empty($erreurs)) {

			// ----- Upload de l’image si une image est choisie -----

			if ($image['error'] !== 4) {

				$extension = pathinfo($image['name'])['extension']; // On récupère l'extension de l'image uploadé. On la met en minuscule éventuellement
				$img = strtolower($pseudo.'.'.$extension); // L'image prend le nom de votre pseudo

				// Déjà vérifié au-dessus :

				// $filetmp = $image['tmp_name']; // Emplacement du fichier temp
				// $finfo = finfo_open(FILEINFO_MIME_TYPE); // Permet d’ouvrir un fichier
				// $mimeType = finfo_file($finfo, $filetmp); // Ouvre le fichier et renvoie image/jpg
				// $allowedExtensions = ['image/jpg', 'image/jpeg', 'image/gif', 'image/png'];

				// if (!in_array($mimeType, $allowedExtensions)) {
				// 	echo '<p>Seuls les types jpg, gif et png sont autorisés</p>';
				// 	exit;
				// }
				// if ($image['size'] / 1024 > 1000) {
				// 	echo "<p>Image trop lourde</p>"; // Vérif taille fichier (ici max 1000 ko)
				// 	exit;
				// }

				move_uploaded_file($filetmp, $img); // On déplace le fichier uploadé où on le souhaite. Si on veut mettre dans dossier img: 'img/'.$img
			}

			
			// ----- Message de bienvenue -----

			$age = intval($age); // Mettre nb en entier

			echo '<p>Bonjour '.ucfirst($genre).' '.ucfirst($pseudo).', vous avez '.$age.' ans ! Votre talent est : '.$talent.'.</p>
			<p>Votre message : '.$message.'</p>
			<p>Votre photo :</p>
			<img src="'.$img.'" alt="Photo de '.$pseudo.'">'; // ucfirst() pour mettre 1re lettre en majuscule

		}

	}


	// ============================== Affichage du formulaire (Par défaut) ==============================
	?>

	<form method="post" enctype="multipart/form-data"> <!--  enctype="multipart/form-data" c'est pour l'upload de l'image -->

		<div class="form-block">
			Genre :
			<label for="homme"><input type="radio" id="homme" name="genre" value="homme" <?php if ($genre==="homme") echo "checked"; ?>>Monsieur</label>
            <label for="femme"><input type="radio" id="femme" name="genre" value="femme" <?php if ($genre==="femme") echo "checked"; ?>>Madame</label>
            <label for="autre"><input type="radio" id="autre" name="genre" value="autre" <?php if ($genre==="autre") echo "checked"; ?>>Autre</label>
			<?php
			if (isset($erreurs['genre'])) {
				echo '<span class="invalid">'.$erreurs['genre'].'</span>';
			}
			?>
		</div>

		<div class="form-block">
			<label for="pseudo">Pseudo :
				<input type="text" name="pseudo" id="pseudo" value="<?php echo $pseudo ?>" class="form-control <?php echo isset($erreurs['pseudo']) ? 'form-invalid' : null; ?>">
			</label>
			<?php
			if (isset($erreurs['pseudo'])) {
				echo '<span class="invalid">'.$erreurs['pseudo'].'</span>';
			}
			?>
		</div>

		<div class="form-block">
			<label for="age">Age :
				<input type="number" name="age" id="age" value="<?php echo $age ?>" min="0" max="150" class="form-control <?php echo isset($erreurs['age']) ? 'form-invalid' : null; ?>">
			</label>
			<?php
			if (isset($erreurs['age'])) {
				echo '<span class="invalid">'.$erreurs['age'].'</span>';
			}
			?>
		</div>

		<div class="form-block">
			<label for="talent">Talent :</label>
			<div class="select-container">
				<select name="talent" id="talent" class="form-control <?php echo isset($erreurs['talent']) ? 'form-invalid' : null; ?>">
					<option value="">-</option>
					<?php
					foreach ($liste_talents as $tal) { /* On met la liste dans un tableau, puis on fait une boucle sur le tableau pour éviter de répéter */
						echo '<option value="'.$tal.'"'; echo $tal == $talent ? 'selected' : null; echo '>'; echo $tal.'</option>';
					}
					?>
				</select>
				<div class="arrow-down"></div>
			</div>
			<?php
			if (isset($erreurs['talent'])) {
				echo '<span class="invalid">'.$erreurs['talent'].'</span>';
			}
			?>
		</div>

		<div class="form-block">
			<label for="img">Ajoutez votre photo :
				<span class="input-file" id="img_container">Ajouter<input type="file" name="img" id="img"></span>
			</label>
			<span id="uploadinfo">
				<?php
				if (isset($erreurs['img'])) {
					echo '<span class="invalid">'.$erreurs['img'].'</span>';
				}
				else {
					echo 'Aucun fichier selectionné';
				}
				?>
			</span>
		</div>

		<div class="form-block">
			<label for="message">Message :</label>
			<textarea name="message" id="message" class="form-control <?php echo isset($erreurs['message']) ? 'form-invalid' : null; ?>"><?php echo $message ?></textarea>
			<?php
			if (isset($erreurs['message'])) {
				echo '<span class="invalid">'.$erreurs['message'].'</span>';
			}
			?>
		</div>

		<button>Envoyer</button>

	</form>


	<script>

        // ========== Input type file personnalisé : ==========

		const inputFile = document.getElementById("img");
		const inputFileContainer = document.getElementById("img_container"); // Le glisser-déposer ne marche que sur un parent
		let file;

		if (inputFile) { /* On vérifie qu'il existe */

			// ---------- Ajout fichier ----------

			inputFile.addEventListener("change", function () {

				file = inputFile.files;
				console.log(file); // Dans console.log, on constate que c'est un objet qui est retourné. Pour accéder au nom du fichier, c'est : file[0].name
				document.getElementById("uploadinfo").innerText = file[0].name;

			});


			// ---------- Ajout fichier par glisser-déposer ----------

            // dragover : Quand l'élément glissé survole la cible de dépôt :
            inputFileContainer.addEventListener("dragover", function (e) {
                e.preventDefault(); // Annule l'interdiction de dépot (drop)
            });

            // drop : Quand l'élément glissé est déposé sur la cible de dépôt :
            inputFileContainer.addEventListener("drop", function (e) {

                e.preventDefault();
                file = e.dataTransfer.files; // dataTransfer contient les données glissées au cours d'un glisser-déposer. Avec eslint, utilisez ça : const { files } = e.dataTransfer;
                console.log(file);
                document.getElementById("uploadinfo").innerText = file[0].name;

                inputFile.files = file; // Pour que l'upload marche ensuite en php

            });
			
		}

	</script>
	
</body>
</html>