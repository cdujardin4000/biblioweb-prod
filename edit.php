<?php
include 'includes/header.php';
include 'dbAccess.php';
include 'functions.php';
/**
 * @param $title
 * @param $author_id
 * @param $description
 * @param $cover_url
 * @param $ref
 * @return string|void
 */
function editBook($title, $author_id, $description, $cover_url, $ref)
{

    // Create connection
    $mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    $title = mysqli_real_escape_string($mysqli, $title);
    $description = mysqli_real_escape_string($mysqli, $description);
    $cover_url = mysqli_real_escape_string($mysqli, $cover_url);

    $query = "UPDATE books SET title='$title', author_id='$author_id', description='$description', cover_url='$cover_url' WHERE ref ='$ref'";

    if ($mysqli->query($query)) {

        $mysqli->close();
        return  true;

    } else {

        return $mysqli->error;
    }
}

if (isset($_GET['id']) && !empty($_GET['id']))
{
    $id = $_GET['id'];
    $query = "SELECT * FROM `books` WHERE `ref` = $id";
    $getBook = dbAccess($query);
    $book = $getBook['data'][0];
}
//recupére auteurs
$query = "SELECT * FROM authors" ;
$getAuthors = dbAccess($query);
$authors = $getAuthors['data'];
//recupére id
$authorsRealIds = getAuthorIds($authors);
//var_dump($authorsRealIds);die;
if (!empty($_POST['ref']) && !empty($_POST['title']) && !empty($_POST['author_id']) && !empty($_POST['description']) && !empty($_POST['cover_url']))
{
    // Nettoyage des données externes
    $title = htmlspecialchars( $_POST['title']);
    $author_id = htmlspecialchars( $_POST['author_id']);
    $description = htmlspecialchars($_POST['description']);
    $cover_url = htmlspecialchars($_POST['cover_url']);
    $ref = htmlspecialchars($_POST['ref']);

    if(editBook($title, $author_id, $description, $cover_url, $ref))
    {

        header("location: index.php?succes=editBook");
    }
    else
    {
        header("location: index.php?error=db");
    }
}
?>
<div class="container">
    <?php if (isset($_GET['error'])){ ?>
    <p class="error"></p>
    <?php } ?>
    <?php if (isset($_GET['id']) && !empty($_GET['id'])) { ?>
    <form class="row g-3"  method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
        <input type="hidden" name="ref" value="<?= $_GET['id']?>">
    <?php foreach($book as $param => $value) { ?>

    <?php if ($param == 'ref'){ ?>

    <?php } else if ($param == 'author_id'){ ?>
        <div class="col-12">
            <label for="<?=$param?>" class="form-label">Author</label>
            <select name ="<?=$param?>" class="form-select form-select" aria-label="form-select" id="select_<?=$param?>" required onchange="updateSelect(this.value);">
                <option selected>Sélectionnez auteur</option>
                <?php foreach($authorsRealIds as $authorRealId => $val) { ?>
                    <option value="<?=$authorRealId?>"><?=$authorsRealIds[$authorRealId]['lastname'] . " " . $authorsRealIds[$authorRealId]['firstname']?></option>
                <?php } ?>
            </select>
        </div>
    <?php } else if ($param == 'description') { ?>
        <div class="col-12">
            <label for="<?=$param?>" class="form-label"><?=$param?></label>
            <textarea type="text" class="form-control" name ="<?=$param?>" required><?=$value?></textarea>
        </div>
    <?php } else { ?>
        <div class="col-12">
            <label for="<?=$param?>" class="form-label"><?=$param?></label>
            <input type="text" class="form-control" placeholder="<?=$value?>" name ="<?=$param?>" required>
        </div>
    <?php } ?>
    <?php } ?>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Edit</button>
        </div>
    </form>
    <?php } ?>
</div>
<?php
include 'includes/footer.php';
?>
