<?php
include_once 'class/Sondage.php';

$sondage = new Sondage('Votre chanteur(se) préféré(e) :', ['Tom', 'Terry', 'Joe', 'Andy'], 'fichiers/sondage-ip.php', 'fichiers/sondage-resultats.php');

if (isset($_POST['choix'])) {
    $sondage->ajoutVote(intval($_POST['choix'])); // À utiliser avant les balises <html lang="fr"><head>..., car utilisation de la fonction setcookie(). Sinon message d'erreur
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sondage</title>
    <style>
        body {
            font: 1em Verdana, sans-serif;
        }
        .a {
            /* text-decoration: underline; */
            color : #4487be;
            border-bottom: 1px solid #4487be;
            cursor: pointer;
            display: inline;
        }
        .a:hover {
            color: #375c86;
            border-bottom: 1px solid #375c86;
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

        .sondage label {
            display: block;
            margin-bottom: 5px;
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
            transition: 0.2s ease;
        }
        .btn:hover {
            background-color: #375c86;
        }

        .block-change {
            max-width: 350px;
        }

        .block-change > div {
            display: none;
        }

        .block-change > div.active {
            display: block;
        }

    </style>
</head>
<body>
    <main>

        <h1>Sondage</h1>

        <div class="block-change">
            <div class="box active">
                <?php
                if ($sondage->dejaVote === true) {
                    $sondage->afficherResultats();
                }
                else {
                    $sondage->afficherFormulaire();
                }
                ?>
            </div>
            <div class="box">
                <?php
                $sondage->afficherResultats();
                ?>
            </div>
        </div>

    </main>


    <script>
        let i = 0;
        const blockChangeItem = document.querySelectorAll(".block-change > div");
        const change = document.querySelectorAll(".change");

        for (i = 0; i < change.length; i++) {
            change[i].addEventListener("click", function() {

                blockChangeItem[0].classList.toggle("active");
                blockChangeItem[1].classList.toggle("active");

            });

        }
    </script>
</body>
</html>