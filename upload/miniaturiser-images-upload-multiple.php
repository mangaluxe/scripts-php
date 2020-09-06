<?php
// Fonction : Transformer une chaîne en slug
function slugify($param) {
    $replace = ['à'=>'a', 'â'=>'a', 'ä'=>'a', 'é'=>'e', 'è'=>'e', 'ê'=>'e', 'É'=>'e', 'ï'=>'i', 'ô'=>'o', 'ō'=>'o', 'ö'=>'o'];
    $param = strtr($param, $replace); // Remplacer accents
    
    $param = preg_replace('~[^\pL\d]+~u', '-', $param); // Remplace caractères non alphanumérique par -
    $param = trim($param, '-'); // trim supprime les espaces (ou d'autres caractères) en début et fin de chaîne. Ici, supprime aussi les - en début et fin de chaîne
    $param = preg_replace('~-+~', '-', $param); // Supprime les doubles -
    $param = strtolower($param); // Tout en minuscules

    return $param;
}



if (!empty($_FILES['images'])) {
    $images = $_FILES['images']; // Tableau avec toutes les infos sur l'image uploadée
    // var_dump($image);

    $total = count($images['name']); // Nb total fichiers uploadés

    // ===== Une boucle pour uploader plusieurs fichiers =====

    for ($i=0; $i < $total; $i++) {

        $original_name = strtolower($images['name'][$i]);
        $extension = pathinfo($original_name)['extension'];

        // $random_name = md5($original_name.uniqid()); // Génère un nom aléatoire (sur le nom original et un nb aléatoire)
        $random_name = uniqid(); // Génère un nb aléatoire

        $original_name_no_ext = basename($original_name, '.'.$extension); // Retire l'extension (le point compris)

        $tmp = $images['tmp_name'][$i]; // Emplacement du fichier image temp

        $new_name = slugify($original_name_no_ext).'-'.$random_name.'.'.$extension;


        if ($tmp != "") {

            $num_upload = $i + 1;

            // ===== Limiter les abus : =====

            $finfo = finfo_open(FILEINFO_MIME_TYPE); // Permet d'ouvrir un fichier
            $mimeType = finfo_file($finfo, $tmp); // Ouvre le fichier et renvoie image/jpg
            $allowedExtensions = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($mimeType, $allowedExtensions)) {
                echo "Extension du fichier ".$num_upload." interdite !<br><br>";
                goto lbl;
            }
            if ($images['size'][$i] / 1024 > 800) {
                echo "Le fichier ".$num_upload." est trop lourde (max 800 ko) !<br><br>";
                goto lbl;
            }

            // ===== Créer une miniature : =====

            if ($extension == "png") {
                $mini = imagecreatefrompng($tmp);
            }
            else if ($extension == "gif") {
                $mini = imagecreatefromgif($tmp);
            }
            else {
                $mini = imagecreatefromjpeg($tmp); // imagecreatefromjpeg() crée une nouvelle image depuis un fichier ou une URL
            }

            // Obtenir largeur et hauteur d'origine (méthode 1) :
            // list($old_width, $old_height) = getimagesize($tmp); // getimagesize() retourne la taille d'une image. list() assigne des variables comme si elles étaient un tableau

            // Obtenir largeur et hauteur d'origine (méthode 2) :
            $old_width = imagesx($mini); // imagesx() retourne la largeur d'une image
            $old_height = imagesy($mini); // imagesy() retourne la hauteur d'une image

            $new_width = 200;
            $new_height = floor($new_width * ($old_height / $old_width)); // Calcul permettant d'obtenir la nouvelle hauteur de la miniature en gardant les proportions. floor() arrondit au nombre inférieur


            $minitmp = imagecreatetruecolor($new_width, $new_height); // imagecreatetruecolor() crée une nouvelle image en couleurs vraies

            // imagecopyresized($minitmp, $mini, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height); // imagecopyresized() copie et redimensionne l'image. Mauvaise qualité, utilisez imagecopyresampled()

            imagecopyresampled($minitmp, $mini, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height); // imagecopyresampled() copie, redimensionne, rééchantillonne l'image

            // ===== Placer la miniature créé : =====

            if ($extension == "png") {
                imagepng($minitmp, "up/min-".$new_name); // imagejpeg() crée un fichier PNG
            }
            else if ($extension == "gif") {
                imagegif($minitmp, "up/min-".$new_name); // imagejpeg() crée un fichier GIF
            }
            else {
                imagejpeg($minitmp, "up/min-".$new_name, 85); // imagejpeg() crée un fichier JPEG depuis l'image fournie. Dernier paramètre c'est la qualité jpeg
            }

            // ===== Placer le fichier original uploadé : =====

            move_uploaded_file($tmp, "up/".$new_name); // On déplace le fichier uploadé où on le souhaite
            echo 'Upload '.$num_upload.' réussi !<br>';

            echo 'Voir : <a href="up/'.$new_name.'" target="_blank">Image originale</a>, <a href="up/min-'.$new_name.'" target="_blank">Miniature</a><br><br>';

            lbl: // goto ici

        }

    }

}

?>

<h1>Miniaturiser images uploadées</h1>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="images[]" multiple><br>
    <button type="submit">Valider</button>
</form>