<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulaire avec demande de confirmation</title>
    <style>
        body {
            font: 1em Verdana, sans-serif;
            background-color: #c5daf5;
            margin: 15px;
        }
        a {text-decoration: none;}

		.form-block {margin-top: 12px;}

        .form input[type="text"] {
            border: 1px solid #487bc7;
            border-radius: 10px;
            padding: 9px 12px;
            font: 1em Verdana, sans-serif;
            outline: none;
        }

        .form button,
        .form input[type="submit"],
        .form input[type="button"] {
            background-color: #487bc7;
            color: white;
            border-radius: 10px;
            padding: 10px 18px;
            border: none;
            font: 1em sans-serif;
            transition: .2s ease;
            cursor: pointer;
            outline: none; /* Eviter bordure sur chrome */
        }
        .form button:hover,
        .form input[type="submit"]:hover,
        .form input[type="button"]:hover {
            background-color: #2463ac;
        }

        input[type="checkbox"] {
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
        input[type="checkbox"]:checked {
            background-color: #1b48b4;
            box-shadow: inset 0px 0px 0px 2px white;
            outline: none; /* Pour Chrome */
        }

    </style>
</head>
<body>

    <?php
    // ---------- 3. Exécution ----------

    if ( !empty($_SESSION['message']) and !empty($_SESSION['passion']) and !empty($_POST['confirmer']) ) {

        $message = $_SESSION['message']; // On récup les données stockées dans SESSION

        echo '<h2>Merci pour votre message !</h2>
        <p>Votre message : '.$message.'</p>

        <p>Vous aimez :';
        foreach ($_SESSION['passion'] as $p) { /* Quand c'est un tableau, on fait une boucle foreach pour parcourir les données */
            echo '<br>- '.$p;
        }
        echo '</p>';

    }


    // ---------- 2. Demande de confirmation ----------

    elseif (!empty($_POST['message']) and !empty($_POST['passion'])) {

        $_SESSION['message'] = $_POST['message']; // On garde les données pour la page suivante en les stockant dans SESSION
        $_SESSION['passion'] = $_POST['passion'];

        echo '
        <form class="form" method="post">
            <p>Confirmez l\'envoi du message : '.$_SESSION['message'].'</p>
            <p>Vos passions :';
            foreach ($_SESSION['passion'] as $p) { /* Quand c'est un tableau, on fait une boucle foreach pour parcourir les données */
                echo '<br>- '.$p;
            }
            echo '</p>
            <input type="submit" name="confirmer" value="Confirmer">
            <a href="'.$_SERVER['PHP_SELF'].'"><input type="button" value="Annuler"></a>
        </form>';

    }


    // ---------- 1. Formulaire ----------

    else {

        echo '
        <form class="form" method="post">

            <div class="form-block">
                <label for="message">Message : <input type="text" id="message" name="message"></label>
            </div>

            <div class="form-block">
                Vous aimez : <label for="musique"><input type="checkbox" name="passion[]" id="musique" value="Musique">Musique</label>
                <label for="cinema"><input type="checkbox" name="passion[]" id="cinema" value="Cinema">Cinema</label>
                <label for="lecture"><input type="checkbox" name="passion[]" id="lecture" value="Lecture">Lecture</label>
            </div>
            
            <div class="form-block">
                <button type="submit">Envoyer</button>
            </div>

        </form>';

    }

    ?>
    
</body>
</html>