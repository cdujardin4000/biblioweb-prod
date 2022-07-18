<?php
include 'includes/header.php';
include 'dbaccess.php';
include 'functions.php';

if (isset($_GET['succes']) &&  $_GET['succes'] == 'changeStatus')
{
    $succes = "Statut correctement modifié";
}

if (isset($_GET['error']) &&  $_GET['error'] == 'tooMuchChange')
{
    $error = "Vous avez deja effectué 2 changements";
} else if (isset($_GET['error']) &&  $_GET['error'] == 'db')
{
    $error = "Problème avec la base de donnée, veuillez contacter votre administrateur réseau...";
}
//récupere les users
$query = "SELECT * FROM users";
$getUsers = dbAccess($query);
$users= $getUsers['data'];
$message = $getUsers['message'];

function changeStatus($id)
{
    $queryUser = "SELECT * FROM users WHERE id=$id";
    $response = dbAccess($queryUser);
    $user = $response['data'][0];

    if ($_SESSION['statusChanged'] < 2){
        if (isset($_POST['promote']))
        {
            if ($user['statut'] == 'novice')
            {
                $query = "UPDATE users SET statut='membre' WHERE id =$id";
            } else if ($user['statut'] == 'membre')
            {
                $query = "UPDATE users SET statut='admin' WHERE id =$id";
            }
        } else if (isset($_POST['retrograde']))
        {
            if ($user['statut'] == 'admin')
            {
                $query = "UPDATE users SET statut='membre' WHERE id =$id";
            } else if ($user['statut'] == 'membre')
            {
                $query = "UPDATE users SET statut='novice' WHERE id =$id";
            }
        }

        $_SESSION['statusChanged']++;
        // Create connection
        $mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        if ($mysqli->query($query))
        {
            $mysqli->close();
            header("location: promote.php?succes=changeStatus");

        } else
        {
            header("location: promote.php?error=db");
        }
    }
    else
    {
        header("location: promote.php?error=tooMuchChange");
    }
}

if(isset($_POST['promote']))
{
    $id = $_POST['promote'];
    changeStatus($id);
}
else if(isset($_POST['retrograde']))
{
    $id = $_POST['retrograde'];
    changeStatus($id);
}
?>
<div class="container">
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
    <form action="<?= $_SERVER['PHP_SELF']?>" method="post" class="row g-3">
        <table class="table table-striped table-bordered">
            <tr>
                <td>USERNAME</td>
                <td>STATUS</td>
                <td>PROMOUVOIR</td>
                <td>RETROGRADER</td>
            </tr>
            <?php foreach ($users as $user) { ?>
            <tr>
                <td><?= $user['login']?></td>
                <td><?= $user['statut']?></td>
                <td>
                    <?php if ($user['statut'] == 'novice' || $user['statut'] == 'membre') { ?>
                    <button type="submit" name="promote" value="<?= $user['id']?>" class="btn btn-primary">Promouvoir</button>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($user['statut'] == 'membre' || $user['statut'] == 'admin') { ?>
                    <button type="submit" name="retrograde" value="<?= $user['id']?>" class="btn btn-primary">Retrograder</button>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </table>
    </form>
</div>
<?php
include 'includes/footer.php';
?>