<?php
class Sondage {

	public $question = ""; // Question du sondage
	public $choix = []; // Choix du sondage
	public $fichierIp; // Fichier contenant IP des visiteurs ayant déjà votés (séparées par des;)
	public $fichierResultats; // Fichier contenant résultats du sondage (séparés par des; et dans l'ordre du tableau $this->choix)

	public $dejaVote = false; // True si le visiteur a déjà  voté, false (par défaut) sinon
	public $listeIp = []; // Contient l'IP des visiteurs ayant déjà  voté (extrait de $this->fichierIp)
	public $resultats = []; // Contient les résultats des votes (extrait de $this->fichierResultats)


	// ---------- Constructeur de la classe ----------

	public function __construct($question, $choix, $fichierIp, $fichierResultats) {
		$this->question = $question;
		$this->choix = $choix;
		$this->fichierIp = $fichierIp;
		$this->fichierResultats = $fichierResultats;
		
		// Si les fichiers IP et Resultats n'existent pas : les crée (possibilité de supprimer cette partie si vous créez les fichiers manuellement)
		if (!file_exists($fichierIp) or !file_exists($fichierResultats)) {
			touch($fichierIp); // touch() modifie la date de modification et de dernier accès d'un fichier
			touch($fichierResultats);
		}
		
		// Vérifie si le visiteur a déjà voté (Vérification Cookie + IP)
		if (isset($_COOKIE['vote']) or $this->verifierIp($_SERVER["REMOTE_ADDR"]) === false) {
			$this->dejaVote = true;
		}
	}


	// ---------- Vérifie si l'IP du visiteur est déjà présente dans le fichier $fichierResultats ----------

	private function verifierIp($ip) {
		$this->listeIp = explode(";", file_get_contents($this->fichierIp, NULL, NULL, 16));
		if (!empty($this->listeIp) and in_array($ip, $this->listeIp)) {
			return false;
		}
		return true;
	}


	// ---------- Ajouter Vote ----------

	// A UTILISER AVANT toute sortie (avant les balises <html lang="fr"><head>...), car utilisation de la fonction setcookie(). Sinon message d'erreur
	public function ajoutVote($NumVote) {
		if ($this->dejaVote === true) {
			return false;
		}
		$this->resultats = explode(";", file_get_contents($this->fichierResultats, NULL, NULL, 16));

		// Si premier vote : initialisation de $this->resultats avec 0 votes pour chaque choix
		if (!isset($this->resultats[0]{0})) {
			$nb = sizeof($this->choix);
			for ($n=0; $n<$nb; $n++) {
				$this->resultats[$n] = '0';
			}
		}
		$this->resultats[$NumVote]++; // Incrémentation du choix pour lequel le visiteur a voté
		
		$this->listeIp[] = $_SERVER["REMOTE_ADDR"]; // Ajout de l'IP du votant à  la liste
		
		// Ecriture des Résultats et des IP dans les fichiers
		if ( !file_put_contents($this->fichierResultats, '<?php exit(); ?>'.implode(";", $this->resultats) ) 
		or !file_put_contents($this->fichierIp, '<?php exit(); ?>'.implode(";", $this->listeIp) ) ) {
			return false;
		}
		$this->dejaVote = true;
		setcookie('vote', true, time()+3*30*24*60*60); // Mise en place d'un cookie valide 3 mois
		return true;
	}


	// ---------- Affiche les résultats du vote ----------

	public function afficherResultats() {
		// Si $this->resultats est vide : il n'a pas encore été recherché dans le fichier $this->fichierResultats (ou 0 votes : voir ci-dessous)
		if (empty($this->resultats)) {

			// var_dump(file_get_contents($this->fichierResultats)); // Affiche par exemple: "59;21;16;18"

			$this->resultats = explode(";", file_get_contents($this->fichierResultats, NULL, NULL, 16)); // Les résultats sont stockés de cette façon : 59;21;16;18. On utilise explode pour extraire les données

			// Si $this->resultats est toujours vide : 0 vote : initialisation de $this->resultats
			if (empty($this->resultats)) {
				$nb = sizeof($this->choix);
				for ($n=0; $n<$nb; $n++) {
					$this->resultats[] = 0;
				}
			}
		}

		// var_dump($this->resultats); // Affiche par exemple : [59, 21, 16, 18]

		$totalVotes = array_sum($this->resultats); // array_sum() calcule la somme des valeurs d'un tableau. Fait la somme de tous les valeurs de $this->resultats pour avoir le nombre total de votant
		
		echo '
		<div class="h3-bg">'.$this->question.'</div>

		<div class="box-padding">
			<div>';
			
			// Parcourt le tableau $this->choix afin d'afficher les résultats en utilisant $i pour obtenir le résultat correspondant au choix
			foreach ($this->choix as $i => $choix) {
				@$pourcentage = $this->resultats[$i]*100/$totalVotes; // @ : Opérateur de contrôle d'erreur
				echo '
				<div class="choix">
					<div class="nom">'.$choix.' : </div>
					<div class="bar">
						<div style="background:linear-gradient(0.5turn, #4caee2, #cbe1f1, #4487be); height:14px; width:'.(2*$pourcentage).'px"></div>
					</div>
					<div>'.number_format($pourcentage, 2, ',', '').'% ('.$this->resultats[$i].')</div>
				</div>'; // number_format(): Formate un nombre pour l'affichage
			}

			echo '
			</div>

			<div>
				<p class="mt-2"><span class="b">Total des votes : </span> '.$totalVotes.'</p>';	
				if ($this->dejaVote === true) {
					echo '<p class="mt-2">Merci pour votre vote !</p>';
				}
				else {
					echo '<div class="a change">Retour</div>';
				}
			echo '
			</div>
		</div>';
	}


	// ---------- Affiche le formulaire du vote ----------

	public function afficherFormulaire() {
		echo '
		<form class="sondage" method="post">

			<div class="h3-bg">'.$this->question.'</div>

			<div class="box-padding">
				<div>';

				$disable = '';

				if ($this->dejaVote === true) {
					$disable = ' disabled="disabled"'; // Si déjà voté
					echo '<p>Merci pour votre vote !</p>';
				}

				foreach ($this->choix as $i => $choix) {
					echo '<label for="chanteur'.$i.'"><input type="radio" name="choix" id="chanteur'.$i.'" value="'.$i.'"'.$disable.'>'.$choix.'</label>';
				}

				echo '
				</div>

				<button class="btn"'.$disable.'>Voter</button>
				<div class="a change">Voir résultats</div>
			</div>

		</form>';	
	}

}
?>