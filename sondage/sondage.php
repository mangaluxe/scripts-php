<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sondage</title>
    <style>
        body {
            font: 1em Verdana, sans-serif;
        }
        .a {
            /* text-decoration: underline; */
            color : #4487be;
            border-bottom: 1px solid #4487be; /* Meilleur soulignement que underline */
            cursor: pointer;
            display: inline;
        }
        .a:hover {
            color: #375c86;
            border-bottom: 1px solid #375c86;
        }

        .block-change {
            min-width: 300px;
            display: inline-block;
        }

        .block-change > .box {
            display: none;
        }
        .block-change > .box.active {
            display: block;
        }

        .sondage label {
            display: block;
            margin-bottom: 5px;
        }

        .h3-bg {
            background: linear-gradient(#c5daf5, #eef6fd);
            padding: 12px 15px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 0;
            font: 700 1.1em Arial, sans-serif;
        }
        .box {
            border: 1px solid #d4d4d4;
            border-radius: 8px;
            box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }
        .box-padding {
            padding: 15px;
        }
        .choix {
            margin-bottom: 10px;
        }
        .choix > div {
            display: inline-block;
        }

        input[type="radio"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            -ms-appearance: none;
            appearance: none;

            border: 3px solid #82b5ec;
            border-radius: 50px;
            height: 20px;
            width: 20px;
            margin: 2px 10px 2px 0;

            vertical-align: -6px;
        }
        input[type="radio"]:checked {
            background-color: #4487be;
            box-shadow: inset 0px 0px 0px 2px white;
            outline: none; /* Pour Chrome */
        }
        input[type="radio"]:checked + span {
            background: linear-gradient(to right, #c5daf5, #eef6fd);
        }
        .btn {
            display: block;
            margin: 30px auto;
            background-color: #4487be;
            color: #fcfcfc;
            border-radius: 10px;
            padding: 10px 18px;
            border: none;
            cursor: pointer;
            font: 1em sans-serif;
            transition: .2s ease;
        }
        .btn:hover {
            background-color: #375c86;
        }
    </style>
</head>
<body>

    <h1>Sondage</h1>

    <?php
    function sondage($question, $choix) {

        $page_actuelle = basename($_SERVER['SCRIPT_FILENAME']);
        $page_actuelle_sans_ext = strstr($page_actuelle, '.', true); // strstr() trouve la première occurrence dans une chaîne. Avec le paramère true, il prend ce qu'il y a avant (au lieu de après)

        $fichierIp = $page_actuelle_sans_ext.'-ip.php';
        $fichierResultats = $page_actuelle_sans_ext.'-resultats.php';

        // Si les fichiers IP et Resultats n'existent pas, on les crée. Attention: touch() ne sait pas créer de dossier
		if (!file_exists($fichierIp) or !file_exists($fichierResultats)) {
			touch($fichierIp); // touch() crée ce fichier s'il n'existe pas, sinon modifie juste la date de dernier accès au fichier

			touch($fichierResultats);
		}


        // ==================== Ajouter Vote ====================

        if (isset($_POST['vote'])) { /* Attention : ici avec !empty, impossible de voter pour le premier, car envoi la valeur de 0 */

            $vote = intval($_POST['vote']);

            // $dejaVote = true; // Ne garde pas en mémoire contrairement à l'intérieur d'une class

            echo '
            <div class="block-change">
                <div class="box">';

                echo '
                </div>
                <div class="box active">
                    <div class="h3-bg">'.$question.'</div>

                    <div class="box-padding">

                        <div>';

                            // ----- Vérifier IP et Ajouter IP dans un fichier -----

                            $fichier = fopen($fichierIp, "a+");
                            if ($fichier) {
                                while (! feof($fichier)) { /* feof() vérifie si la fin du fichier est atteinte. On boucle avec while() tant que la fin du fichier n'est pas atteinte. Cela permet de lire chaque ligne d'un fichier */
                                    $adr_ip = fgets($fichier, 4096); // fgets(): Récupère la ligne courante à partir de l'emplacement du pointeur sur fichier
                                    $adr_ip = trim($adr_ip); // trim(): Supprime les éventuels espaces ou saut de ligne avant et après
                                    if ($adr_ip === $_SERVER['REMOTE_ADDR']) {
                                        echo 'Vous avez déjà voté !';
                                        fclose ($fichier);
                                        echo '
                                        <script>
                                            setTimeout(function(){
                                                window.location = "'.$_SERVER['PHP_SELF'].'";
                                            }, 3000); // Redirection après 3 sec
                                        </script>';
                                        exit;
                                    }
                                }
                                fwrite($fichier, $_SERVER['REMOTE_ADDR']."\n"); // On inscrit l'IP dans le fichier (un IP par ligne)
                            }
                            fclose ($fichier);

                            // ----- Récupérer anciens résultats de vote dans un fichier -----

                            $result = explode(";", file_get_contents($fichierResultats, FALSE, NULL, 16)); // Les résultats sont stockés de cette façon : 59;21;16;18. On utilise explode() pour extraire les données. explode() coupe une chaine en segments, ici entre chaque point-virgule ; On obtient un tableau comme $result = [59, 21, 16, 18]; file_get_contents($filename, FALSE, NULL, 16) lit à partir de la position 16

                            // Si premier vote : initialisation du tableau $result avec 0 vote pour chaque choix. On obtient ce tableau : $result = [0, 0, 0, 0];
                            if (empty($result[0])) { /* Ne pas utiliser !isset, cela ne marchera pas */
                                $nb = count($choix);
                                for ($n=0; $n<$nb; $n++) {
                                    $result[$n] = 0;
                                }
                            }

                            // ----- Ajouter vote dans un fichier -----

                            $result[$vote]++; // Incrémentation du choix pour lequel le visiteur a voté. Exemple : Si on a voté pour le premier, on incrémente la valeur à l'index 0

                            // print_r($result); // Exemple si on a voté pour le premier : $result = [1, 0, 0, 0];

                            // Ajouter vote (sur une même ligne) :
                            if ( !file_put_contents($fichierResultats, '<?php exit(); ?>'.implode(";", $result)) ) {
                                return false;
                            }

                            // Si on veut aussi ajouter les IP sur une même ligne :
                            /*
                            $listeIp[] = $_SERVER["REMOTE_ADDR"]; // Ajouter IP du votant dans le tableau à la suite. On obtient par exemple : $listeIp = ['127.0.0.80', '127.0.0.81', '127.0.0.82', '127.0.0.1'];
                            if ( !file_put_contents($fichierResultats, '<?php exit(); ?>'.implode(";", $result)) or !file_put_contents($fichierIp, '<?php exit(); ?>'.implode(";", $listeIp)) ) {
                                return false;
                            }
                            */

                            // implode() rassemble les éléments d'un tableau en une chaîne. Exemple : [1, 0, 0, 0] devient 1;0;0;0


                            // ----- Afficher résultat -----

                            $totalVotes = array_sum($result); // array_sum() calcule la somme des valeurs d'un tableau. Fait la somme de toutes les valeurs de $result pour avoir le nombre total de votant
                    
                            // Parcourt le tableau $hoix afin d'afficher les résultats en utilisant $i pour obtenir le résultat correspondant au choix
                            foreach ($choix as $i => $c) {

                                if ($totalVotes > 0) {
                                    $pourcentage = round($result[$i]*100/$totalVotes, 2);
                                }
                                else {
                                    $pourcentage = 0;
                                }

                                echo '
                                <div class="choix">
                                    <div class="nom">'.$c.' : </div>
                                    <div class="bar">
                                        <div style="background:linear-gradient(0.5turn, #4caee2, #cbe1f1, #4487be); height:14px; width:'.(1.5*$pourcentage).'px"></div>
                                    </div>
                                    <div>'.$pourcentage.'% ('.$result[$i].')</div>
                                </div>';
                            }
            
                        echo '
                        </div>
                    
                    </div>
                 
                </div>
            </div>';

        }

        else {

            // ==================== Affiche le formulaire du vote ====================

            // var_dump($dejaVote); // Ne garde pas en mémoire contrairement à l'intérieur d'une class

            echo '
            <div class="block-change">
                <div class="box active">
                    <form class="sondage" method="post">

                        <div class="h3-bg">'.$question.'</div>

                        <div class="box-padding">
                            <div>';

                            $disable = '';
                            // if ($dejaVote === true) { /* Ne marche pas, car sans class, ne garde pas en mémoire */
                            //     $disable = ' disabled="disabled"'; // Si déjà voté
                            //     echo '<p>Merci pour votre vote !</p>';
                            // }

                            foreach ($choix as $i => $c) {
                                echo '<label for="vote'.$i.'"><input type="radio" name="vote" id="vote'.$i.'" value="'.$i.'"'.$disable.'>'.$c.'</label>';
                            }

                            echo '
                            </div>

                            <button class="btn"'.$disable.'>Voter</button>
                            <div class="a change">Voir résultats</div>
                        </div>

                    </form>
                </div>
                <div class="box">
                    <div class="h3-bg">'.$question.'</div>
                    <div class="box-padding">

                        <div>';

                        // ----- Afficher résultats -----

                        $result = explode(";", file_get_contents($fichierResultats, FALSE, NULL, 16)); // Les résultats sont stockés de cette façon : 59;21;16;18. On utilise explode() pour extraire les données. explode() coupe une chaine en segments, ici entre chaque point-virgule ; On obtient un tableau comme $result = [59, 21, 16, 18]; file_get_contents($filename, FALSE, NULL, 16) lit à partir de la position 16

                        // Si premier vote : initialisation du tableau $result avec 0 vote pour chaque choix. On obtient ce tableau : $result = [0, 0, 0, 0];
                        if (empty($result[0])) { /* Ne pas utiliser !isset, cela ne marchera pas */
                            $nb = count($choix);
                            for ($n=0; $n<$nb; $n++) {
                                $result[$n] = 0;
                            }
                        }

                        $totalVotes = array_sum($result); // array_sum() calcule la somme des valeurs d'un tableau. Fait la somme de tous les valeurs de $result pour avoir le nombre total de votant

                        // Parcourt le tableau $choix afin d'afficher les résultats en utilisant $i pour obtenir le résultat correspondant au choix
                        foreach ($choix as $i => $c) {
                            if ($totalVotes > 0) {
                                $pourcentage = round($result[$i]*100/$totalVotes, 2);
                            }
                            else {
                                $pourcentage = 0;
                            }
                            echo '
                            <div class="choix">
                                <div class="nom">'.$c.' : </div>
                                <div class="bar">
                                    <div style="background:linear-gradient(0.5turn, #4caee2, #cbe1f1, #4487be); height:14px; width:'.(1.5*$pourcentage).'px"></div>
                                </div>
                                <div>'.$pourcentage.'% ('.$result[$i].')</div>
                            </div>';
                        }

                        echo '
                        </div>

                        <div class="a change">Retour</div>

                    </div>
                </div>
            </div>';

        }


    }


    sondage("Votre chanteur préféré :", ["Ellana", "Nyaa", "Konzeil", "SDKirito"]); // Appel à la fonction utilisateur avec 2 paramètres : "Question", ["Liste de réponses dans un tableau"]
    ?>


    <script>

        let i = 0;
        const blockChangeItem = document.querySelectorAll(".block-change > div");
        const change = document.querySelectorAll(".change");

        if (blockChangeItem) {
            for (i = 0; i < change.length; i++) {
                change[i].addEventListener("click", function () {
                    blockChangeItem[0].classList.toggle("active");
                    blockChangeItem[1].classList.toggle("active");
                });
            }
        }

    </script>
    
</body>
</html>