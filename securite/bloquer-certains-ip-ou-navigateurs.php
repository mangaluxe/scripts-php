<?php
// -------------------- Bannir avec Regex --------------------

$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
if (preg_match("(194.5.48.|cloud4search|contabo.net|contextweb|cssgroup.lv|dsabuse.com|e-commercepark|ecatel|free-internet-media|frozenway|helvawebhost|host-stage-dns|hostgator.com|hostkey.ru|idealhosting.net.tr|internetserviceteam|justquaconnect|keymachine|nmservers|ntc.or.th|pool.com|redstation|scoutjet|serverspace|softlayer|startdedicated|SteepHost|sistrix|techentrance.com|tralex.se|vhoster|vpn99|voxility|webazilla|xsserver.eu)", $hostname)) { /* Bannir les serveurs hôtes contenant ces mots */
    echo 'Ban';
    exit;
}

$navigateur = $_SERVER["HTTP_USER_AGENT"];
if (preg_match("(atraxbot|betaBot|bnf.fr_bot|DigExt|discobot|EC2LinkFinder|FileDownloader|findfiles|FrontPage|Gaisbot|Grabber|HTTrack|HTTPClient|Indy Library|Internet Ninja|larbin|LeechFTP|LexiBot|LexxeBot|libwww-perl|lwp-trivial|MegaIndex.ru|MJ12bot|Offline|PageGrabber|Plukkie|Purebot|PycURL|Python-urllib|Seekport Crawler|SiteBot|Snapbot|Sosospider|spbot|startdedicated|swish-e|Tagoobot|TCGfetch|unspecified.mail|WebCapture|webcollage|Webster|Wget|wikiwix|Xenu)", $navigateur)) { /* Bannir les user-agents contenant ces mots */
    echo 'Spambot Ban';
    exit;
}


// -------------------- Bannir avec l'ancienne méthode : tableau et boucle --------------------

$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$navigateur = $_SERVER["HTTP_USER_AGENT"];

$banhost = ['194.5.48.','cloud4search','contabo.net','contextweb','cssgroup.lv','dsabuse.com','e-commercepark','ecatel','free-internet-media','frozenway','helvawebhost','host-stage-dns','hostgator.com','hostkey.ru','idealhosting.net.tr','internetserviceteam','justquaconnect','keymachine','nmservers','ntc.or.th','pool.com','redstation','scoutjet','serverspace','softlayer','startdedicated','SteepHost','sistrix','techentrance.com','tralex.se','vhoster','vpn99','voxility','webazilla','xsserver.eu'];
foreach ($banhost as $ban) {
    $comparaison = strstr($hostname, $ban); // strstr(): trouve la 1re occurence dans une chaine
    if ($comparaison == true) {
        echo 'Ban';
        exit;
    }
}

$bannav = ['atraxbot','betaBot','bnf.fr_bot','DigExt','discobot','EC2LinkFinder','FileDownloader','findfiles','FrontPage','Gaisbot','Grabber','HTTrack','HTTPClient','Indy Library','Internet Ninja','larbin','LeechFTP','LexiBot','LexxeBot','libwww-perl','lwp-trivial','MegaIndex.ru','MJ12bot','Offline','PageGrabber','Plukkie','Purebot','PycURL','Python-urllib','Seekport Crawler','SiteBot','Snapbot','Sosospider','spbot','startdedicated','swish-e','Tagoobot','TCGfetch','unspecified.mail','WebCapture','webcollage','Webster','Wget','wikiwix','Xenu'];
foreach ($bannav as $ban) {
    $comparaison = strstr($navigateur, $ban);
    if ($comparaison == true) {
        echo 'Spambot';
        exit;
    }
}