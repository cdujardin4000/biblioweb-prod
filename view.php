<?php
include 'includes/header.php';
include 'dbaccess.php';

if (isset($_GET['error']))
{
    $error = match ($_GET['error']) {
        'cover' => "Erreur : fichier invalide (max-size: 300ko, format: png/jpg/jpeg)",
    };
}

if (isset($_GET['succes']))
{
    $succes = match ($_GET['succes']) {
        'cover' => "Le fichier est valide, nous l'affichons tout de suite,  merci",
    };
}
if (isset($_GET['id']) && !empty($_GET['id'] && $_GET['authId']) && !empty($_GET['authId']))
{
    $id = $_GET['id'];
    $authId = $_GET['authId'];
} else
{
    header("location: index.php?error=db"); // redirection
    exit; // arrêt du script
}
$query = "SELECT * FROM `books` WHERE `ref` = $id";
$getBook = dbAccess($query);
$book = $getBook['data'][0];
$message = $getBook['message'];

$query = "SELECT * FROM `authors` WHERE `id` = $authId";
$getAuth = dbAccess($query);
$author = $getAuth['data'][0];
$message = $getAuth['message'];

$query = "SELECT * FROM `books` WHERE `author_id` = $authId";
$getFromAuth = dbAccess($query);
$FromAuthor = $getFromAuth['data'];
$message = $getFromAuth['message'];
?>

<div class="container list">
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
    <!--On affiche pas le bouton si on est pas connecté || si on est ni admin ni membre-->
    <?php if(!isset($_SESSION['status']) || ($_SESSION['status'] !== 'membre' && $_SESSION['status'] !== 'admin')) { ?>
        <!--//Sinon on l'affiche-->
    <?php }  else { ?>
        <a class="btn btn-primary" href="edit.php?id=<?= $id ?>&authId=<?= $authId ?>" >Edit</a>
    <?php } ?>
    <h1 class="book-title"><?= $book['title']?></h1>
    <div class="container">
        <?php foreach($book as $key => $value) {
            if($key == 'cover_url') { ?>
                <h3 class='list-text'>Cover:</h3>
                <div class="cover">
                    <img class="cover-img" alt="<?=$book['title']?>" src="img/covers/<?=$book['cover_url']?>">
                </div>
            <?php } else { ?>
                <h3 class='list-text'><?=$key?>:</h3>
                <p class='list-text'><?=$value?></p>
        <?php } } ?>
    </div>
    <h2 class="book-title">sur l'auteur: </h2>
    <p><?= $author['firstname'] . " " . strtoupper($author['lastname']) . " - " . $author['nationality']?></p>
    <h2 class="book-title">Du même auteur:</h2>
    <ul>
        <?php foreach($FromAuthor as $bookFrom) {
            if ($bookFrom['ref'] == $id)
                continue; ?>
            <li><p class='list-text'><a href="view.php?id=<?= $bookFrom['ref'] ?>&authId=<?= $authId ?>"><?= $bookFrom['title'] ?></a></p></li>
        <?php } ?>
    </ul>
</div>
<?php
include 'includes/footer.php';
?>