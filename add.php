<?php
include 'includes/header.php';
include 'dbaccess.php';
include 'functions.php';



if (!empty($_POST['title']) && !empty($_POST['author_id']) && !empty($_POST['description']) && !empty($_POST['cover_url']))
{
    // Nettoyage des données externes
    $title = htmlspecialchars( $_POST['title']);
    $author_id = htmlspecialchars($_POST['author_id']);
    $description = htmlspecialchars($_POST['description']);
    $cover_url = htmlspecialchars($_POST['cover_url']);

    if(addBook($title, $author_id, $description, $cover_url))
    {
        header("location: index.php?succes=addBook");
    }
    else
    {
        header("location: index.php?error=db");
    }
}

//récupere les livres
$query = "SELECT * FROM books";
$getBooks = dbAccess($query);
$books = $getBooks['data'];
$message = htmlspecialchars($getBooks['message']);
//récupèrte les params
$book = $books[0];
//var_dump($book);die;
//recupére auteurs
$query = "SELECT * FROM authors" ;
$getAuthors = dbAccess($query);
$authors = $getAuthors['data'];
//recupére id
$authorsRealIds = getAuthorIds($authors);
//var_dump($authorsRealIds);die;

?>
<div class="container">
    <?php if (isset($_GET['error'])) { ?>
    <p class="error"></p>
    <?php } ?>
    <form class="row g-3"  method="post" action="add.php">
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
            <textarea type="text" class="form-control" name ="<?=$param?>"></textarea>
        </div>
        <?php } else { ?>
        <div class="col-12">
            <label for="<?=$param?>" class="form-label"><?=$param?></label>
            <input type="text" class="form-control" placeholder="<?=$param?>" name ="<?=$param?>">
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