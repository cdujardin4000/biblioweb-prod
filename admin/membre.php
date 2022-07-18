<?php
include '../includes/header.php';
// verifier si l'user est connecté en tant que membre
// sinon on renvoie vers index
if( ($_SESSION['status'] !== 'membre' && $_SESSION['status'] !== 'admin'))
{
    header("location: ..\index.php?error=membre"); // redirection
    exit;
}


?>
<div class = "container">
    <p>Bonjour, <?= $_SESSION['username'] ?></p>
    <p>Bienvenue dans la zone membre</p>
</div>


<?php
include '../includes/footer.php';
?>

