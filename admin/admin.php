<?php
include '../includes/header.php';

// verifier si l'user est connecté en tant que admin
// sinon on renvoie vers index
if($_SESSION['status'] !== 'admin')
{
    header("location: ../index.php?error=admin"); // redirection
    exit;
}

if (isset($_GET['error']))
{
    $error = match ($_GET['error']) {
        'logo' => "Erreur : fichier invalide (max-size: 300ko, format: png/jpg/jpeg)",
    };
}

if (isset($_GET['succes']))
{
    $succes = match ($_GET['succes']) {
        'logo' => "Le fichier est valide, nous l'affichons tout de suite,  merci",
    };
}
$logoMessage = "Veuillez choisir le nouveau logo";

if(isset($_POST['btn-change-logo'] ))
{
    //var_dump($_FILES);
    if($_FILES['logo']['error'] == 0){

        if($_FILES['logo']['size'] < 300000 && ($_FILES['logo']['type'] == 'image/jpeg' || $_FILES['logo']['type'] == 'image/jpg' || $_FILES['logo']['type'] == 'image/png')){
            //var_dump($_FILES);
            //Renomme le nouveau fichier
            $_FILES['name'] = "logo-biblioweb.jpeg";
            $source = $_FILES['logo']['tmp_name'];
            //Renomme l'ancien fichier en old
            rename('../img/logo-biblioweb.jpeg', '../img/old-logo-biblioweb.jpeg');

            if(move_uploaded_file($_FILES['logo']['tmp_name'], '../img/'.basename($_FILES['name']))){
                header( "location: admin.php?path=admin&succes=logo" );
            } else {
                header( "location: admin.php?path=admin&error=logo" );
            }

        }
    }
}

?>
<div class = "container">
    <?php if (isset($_GET['succes'])) { ?>
        <!-- On affiche les succes -->
        <div class="alert alert-success" role="alert">
            <p class="succes"><?= $succes ?></p>
        </div>
    <?php } else if (isset($_GET['error'])) { ?>
        <!-- On affiche les erreurs -->
        <div class="alert alert-danger" role="alert">
            <p class="error"><?= $error ?></p>
        </div>
    <?php } ?>
    <p>Bonjour, </p>
    <p>Bienvenue dans la zone admin</p>
    <a class="btn btn-primary" href="../addAuthor.php" >Add author</a>
    <a class="btn btn-primary" href="../add.php" >Add book</a>
    <a class="btn btn-primary" href="../addCover.php" >Add cover</a>
</div>
<div class="container form mb-3 changeLogo">
    <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="300000"/>
        <label class="form-label statusMessage"><?= $logoMessage ?></label>
        <input type="file" class="form-control form-control-dark" name="logo">
        <button type="submit" class="btn btn-primary change-logo" name="btn-change-logo">Valider</button>
    </form>
</div>
<?php
include '../includes/footer.php';
?>