<?php


include '../includes/header.php';
include '../functions.php';
include '../config.php';

$message="";
$connected = false;


function checkUser($email)
{
    // Connexion au serveur MySQL et sélection de la base de données
    if ($mysqli = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE)) {

        // Nettoyage des données externes
        $email = mysqli_real_escape_string($mysqli, $email);

        // Exécution de la requête SQL
        $result = mysqli_query($mysqli, "SELECT * FROM users WHERE email='$email'");
        if ($result) {
            // Extraction des données
            $user = mysqli_fetch_assoc($result);
            //var_dump($user);
            mysqli_free_result($result); // Libérer la mémoire
            return $user;

        }
        mysqli_close($mysqli); // Fermer la connexion au serveur

        return false;
    }
}
if(isset($_GET['action']) && $_GET['action'] == 'logout')
{
    /**
     * ICI AJOUTER LE CODE POUR RECUPERER LES DONNEES UTILISATEUR ANONYMES
     */
    session_unset(); // suppression des variables de sessions
    session_destroy(); // destruction de la session
    header("location: ../index.php?succes=deco");

}
if (isset($_GET['error']) &&  $_GET['error'] == 'pass') {
    $error = "Les Mots de passe ne correspondent pas. Veuillez recommencer.";
} else if (isset($_GET['error']) &&  $_GET['error'] == 'mail'){
    $error = "Les adresses mail ne correspondent pas. Veuillez recommencer.";
} else if (isset($_GET['error']) &&  $_GET['error'] == 'db') {
    $error = "Nous rencontrons des problèmes actuellement. Si cela se prolonge, veuillez contacter l'administration";
} else if (isset($_GET['error']) &&  $_GET['error'] == 'login'){
    $error = "Il y à une erreur dans votre username ou dans votre password";
} else if (isset($_GET['error']) &&  $_GET['error'] == 'notmail') {
    $error = "Veuillez entrer un email valide";
}

if (isset($_POST['username'], $_POST['password1'], $_POST['password2'], $_POST['mail1'], $_POST['mail2']) && !empty($_POST['username']) && !empty($_POST['password1']) && !empty($_POST['password2'])
&& !empty($_POST['mail1'])  && !empty($_POST['mail2'])){

    $username = $_POST['username'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
    $mail1 = $_POST['mail1'];
    $mail2 = $_POST['mail2'];
    if ($password1 != $password2){
        header("location: ".$_SERVER['PHP_SELF']."?action=signUp&path=admin&error=pass");
    } else if ($mail1 != $mail2){
        header("location: ".$_SERVER['PHP_SELF']."?action=signUp&path=admin&error=mail");
    } else if (!filter_var($mail1, FILTER_VALIDATE_EMAIL)){
        header("location: ".$_SERVER['PHP_SELF']."?action=signUp&path=admin&error=notmail");
    }
    addUser($username, $password2,  $mail2);
}
if(isset($_GET['action']) && $_GET['action'] == 'check')
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($user = pwVerif($username, $password)){
        $connected = true;

        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['login'];
        $_SESSION['status'] = $user['statut'];
        $_SESSION['statusChanged'] = 0;
        header("location: ../index.php?succes=connect");
    } else {
        header("location: ".$_SERVER['PHP_SELF']."?error=true&path=admin&action=login");
    }
}
if(isset($_GET['action']) && $_GET['action'] == 'resetPw') {
    $mail1 = $_POST['mail1'];
    if ($user = checkUser($mail1)) {

        $to      = $mail1;
        $subject = 'Reset Password';
        $message = "<p>Bonjour veuillez cliquer sur ce lien pour réinitialiser votre mot de passe</p></br><a href='http://localhost/biblioweb/admin/log.php?action=pwReset&mail=$mail1&path=admin'>Reset password</a>";

        if (mail($to, $subject, $message)){
            var_dump("success");
        }
    }
    else {
        header("location: ".$_SERVER['PHP_SELF']."?error=true&path=admin&mail=$mail1&action=resetPw");
    }
}
if(isset($_GET['action']) && $_GET['action'] == 'pwReset') {
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    if ($password1 != $password2){
        header("location: ".$_SERVER['PHP_SELF']."?action=pwReset&path=admin&error=pass");
    }

    else {
        // Create connection
        $mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }
        $mail = $_GET['mail'];
        $password = password_hash($password1, PASSWORD_BCRYPT);


        $query = "INSERT INTO users (login, password, email, statut) VALUES ('$username', '$password', '$mail', 'membre')";

        if ($mysqli->query($query) === TRUE) {

            $mysqli->close();
            $_SESSION['username'] = $username;
            $_SESSION['status'] = 'novice';
            header("location: ../index.php?succes=userCreated");

        } else {

            header("location: signUp.php?path=admin&error=db");
        }
    }
}

?>
<div class="container">
<?php if (isset($_GET['error']) && $_GET['error'] == 'true') { ?>
    <!-- On affiche les erreurs -->
    <div class="alert alert-danger" role="alert">
        <p class="error"><?= $error ?></p>
    </div>
<?php } ?>

<?php if (isset($_GET['action']) && $_GET['action'] == 'login') { ?>
    <form method="post" action="<?=$_SERVER['PHP_SELF']?>?action=check" class="row g-3">
        <div class="col-md-6">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Username">
        </div>
        <div class="col-md-6">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
        </div>
        <div class="col-md-12">
            <input class="btn btn-primary" type="submit">
            <a class="btn btn-primary" href="<?=$_SERVER['PHP_SELF']?>?action=lostPw&path=admin">J'ai oublié mon mot de passe</a>
        </div>
    </form>

<?php } else if (isset($_GET['action']) && $_GET['action'] == 'lostPw') { ?>
    <form method="post" action="<?=$_SERVER['PHP_SELF']?>?action=resetPw" class="row g-3">
        <div class="col-md-6">
            <label for="mail1" class="form-label">Mail</label>
            <input type="email" class="form-control" id="inputAddress" placeholder="Entrez votre adresse mail" name = "mail1" required>
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Réinitializez le mdp</button>
        </div>
    </form>
<?php }  else if (isset($_GET['action']) && $_GET['action'] == 'signUp') { ?>
    <form class="row g-3"  method="post" action="<?=$_SERVER['PHP_SELF']?>?path=admin" >
        <div class="col-md-4">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="inputUsername" name = "username" required>
        </div>
        <div class="col-md-4">
            <label for="password1" class="form-label">Password</label>
            <input type="password" class="form-control" id="InputPassword1" name = "password1" required>
        </div>
        <div class="col-md-4">
            <label for="password2" class="form-label">Confirmez password</label>
            <input type="password" class="form-control" id="InputPassword2" name = "password2" required>
        </div>
        <div class="col-md-6">
            <label for="mail1" class="form-label">Mail</label>
            <input type="email" class="form-control" id="inputAddress" placeholder="Entrez votre adresse mail" name = "mail1" required>
        </div>
        <div class="col-md-6">
            <label for="mail2" class="form-label">Confirmez mail</label>
            <input type="email" class="form-control" id="inputAddress2" placeholder="confirmez votre adresse mail" name = "mail2" required>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Sign in</button>
        </div>
    </form>
<?php }  else if (isset($_GET['action']) && $_GET['action'] == 'pwReset') { ?>
    <form class="row g-3"  method="post" action="<?=$_SERVER['PHP_SELF']?>?path=admin&action=pwReset">
        <div class="col-md-4">
            <label for="password1" class="form-label">Password</label>
            <input type="password" class="form-control" id="InputPassword1" name = "password1" required>
        </div>
        <div class="col-md-4">
            <label for="password2" class="form-label">Confirmez password</label>
            <input type="password" class="form-control" id="InputPassword2" name = "password2" required>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Reset password</button>
        </div>
    </form>
<?php } ?>
</div>
<?php
include '../includes/footer.php';
?>