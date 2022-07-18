<?php
include 'includes/header.php';
include 'dbaccess.php';
include 'functions.php';

if (!empty($_POST['lastname']) && !empty($_POST['firstname']) && !empty($_POST['nationality']))
{
    // Nettoyage des données externes
    $lastname = htmlspecialchars( $_POST['lastname']);
    $firstname = htmlspecialchars($_POST['firstname']);
    $nationality = htmlspecialchars($_POST['nationality']);


    if(addAuthor($lastname, $firstname, $nationality))
    {
        header("location: index.php?succes=addAuthor");
    }
    else
    {
        header("location: index.php?error=db");
    }
}

$query = "SELECT DISTINCT nationality FROM authors";
$getCountrys = dbAccess($query);
$countrys = $getCountrys['data'];

//recupére auteurs
$query = "SELECT * FROM authors" ;
$getAuthors = dbAccess($query);
$authors = $getAuthors['data'];
//récupère les params
$author = $authors[0];


?>
<div class="container">
    <?php if (isset($_GET['error'])) { ?>
        <p class="error"></p>
    <?php } ?>
    <form class="row g-3"  method="post" action="addAuthor.php">
        <?php foreach($author as $param => $value) { ?>
            <?php if ($param == 'id'){ ?>

            <?php } else if ($param == 'nationality'){ ?>
                <div class="col-12">
                    <label for="<?=$param?>" class="form-label">Nationality</label>
                    <select name ="<?=$param?>" class="form-select form-select" aria-label="form-select" required onchange="updateSelect(this.value);">
                        <option selected>Sélectionnez un Pays</option>
                        <?php foreach ($countrys as $country) { ?>
                            <option value="<?=$country['nationality']?>"><?=$country['nationality']?></option>
                        <?php } ?>
                    </select>
                </div>
            <?php } else { ?>
                <div class="col-12">
                    <label for="<?=$param?>" class="form-label"><?=$param?></label>
                    <input type="text" class="form-control" placeholder="<?=$param?>" name ="<?=$param?>" required>
                </div>
            <?php } ?>
        <?php } ?>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
    </form>
</div>
<?php
include 'includes/footer.php';
?>
