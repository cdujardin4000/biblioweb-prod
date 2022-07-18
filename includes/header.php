<?php
session_start(); // on demarre une session
if (!isset($_SESSION['status'])){
    $_SESSION['status'] = "unknown";
}

// Verification du chemin pour injecter les scripts
$prefUrl = "";
if(isset($_GET['path']) && $_GET['path'] == "admin")
{
    $prefUrl = "../";
}

// Verification de la page pour afficher le double menu ou non
$doubleMenu = true;
if(isset($_GET['action']) && (($_GET['action'] == "login" || $_GET['action'] == "signUp" || $_GET['action'] == "lostPw"))) {
    $doubleMenu = false;
}

// Verification si l'utilisateur est connecté pour le message de bienvenue ou afficher son username
$connected = false;
$messageWelcome = "Bonjour cher inconu!!!";
if (isset($_SESSION['username'])){
    $connected = true;
    $messageWelcome = "Bonjour " . $_SESSION['username'] . ", vous etes connecté en tant que " . strtoupper($_SESSION['status']) .".";
}

?>
<!doctype html>
<html lang="fr">
<head>
    <title>Biblioweb</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href=<?=$prefUrl."css/bootstrap.min.css"?>>
    <!-- CSS -->
    <link rel="stylesheet" href=<?=$prefUrl."css/screen.css"?>>
</head>
<body>

<header>
    <div class="px-3 py-2 bg-dark text-white border-bottom">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a class="d-flex align-items-center my-2 my-lg-0 me-lg-auto text-white text-decoration-none" href=<?=$prefUrl."index.php"?>>
                    <img alt="logo du header" title="LOGO" class="logo-header" src=<?=$prefUrl."img/logo-Biblioweb.jpeg"?>>
                </a>

                <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
                    <li>
                        <a  class="nav-link text-white" href=<?=$prefUrl."index.php"?>>Home</a>
                    </li>
                    <li>
                        <a  class="nav-link text-white" href=<?=$prefUrl."admin/membre.php?path=admin"?>>Membre</a>
                    </li>
                    <li>
                        <a  class="nav-link text-white" href=<?=$prefUrl."admin/admin.php?path=admin"?>>Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <?php if($doubleMenu) { ?>
    <div class="px-3 py-2 bg-dark mb-3">
        <div class="container d-flex flex-wrap justify-content-between">
            <form class="text-start"  method="get" action=<?=$prefUrl."index.php"?>>
                <input type="text" class="form-control form-control-dark" id="input" placeholder="Rechercher un auteur..."  name="query">
                <input type="submit" class="btn btn-outline-light me-2" value="Rechercher">
            </form>
            <div class="text-end">
                <p class = "welcome"><?= $messageWelcome?></p>
                <?php if ($connected) { ?>
                    <a  class="btn btn-outline-light" href=<?=$prefUrl."admin/log.php?action=logout"?>>Logout</a>
                <?php } else { ?>
                    <a class="btn btn-outline-light" href=<?=$prefUrl."admin/log.php?action=login&path=admin"?>>Login</a>
                    <a class="btn btn-outline-light" href=<?=$prefUrl."admin/log.php?action=signUp&path=admin"?>>Sign-up</a>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>
</header>
