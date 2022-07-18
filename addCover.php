<?php
include 'includes/header.php';
include 'dbaccess.php';

//Recuperer un livre au hazard
$query = "SELECT ref, title, lastname, firstname, author_id FROM books JOIN authors ON books.author_id=authors.id WHERE cover_url IS NULL ORDER BY RAND() LIMIT 1";
$getRandomBook = dbAccess($query);
$randomBook = $getRandomBook['data'][0];
$message = $getRandomBook['message'];
var_dump($randomBook);

/**
 * @param $cover_url
 * @param $ref
 * @return bool|string|void
 */
function addCover($cover_url, $ref)
{

    // Create connection
    $mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $cover_url = mysqli_real_escape_string($mysqli, $cover_url);

    $query = "UPDATE books SET cover_url='$cover_url' WHERE ref ='$ref'";

    if ($mysqli->query($query)) {

        $mysqli->close();
        return  true;

    } else {

        return $mysqli->error;
    }
}

if(isset($_POST['btn-add-cover'] ))
{

    //var_dump($_FILES);
    if($_FILES['cover']['error'] == 0){

        if($_FILES['cover']['size'] < 300000 && ($_FILES['cover']['type'] == 'image/jpeg' || $_FILES['cover']['type'] == 'image/jpg' || $_FILES['cover']['type'] == 'image/png')){
            //REcupere l'id et autId
            $id = $_POST['id'];
            $authId = $_POST['authId'];
            //Recupere l'extension
            $ext = ".";
            $format = substr($_FILES['cover']['type'],6);
            $ext .= $format;
            //Renomme le nouveau fichier (ref.ext)
            $fileName =  $randomBook['ref'] .= $ext;
            $_FILES['name'] = $fileName;
            $source = $_FILES['cover']['tmp_name'];

            if(move_uploaded_file($_FILES['cover']['tmp_name'], 'img/covers/'.basename($_FILES['name']))){
                addCover($fileName, $id);
                header("location: view.php?id=".$id."&authId=".$authId."&succes=cover");

            } else {
                header( "location: index.php?error=db" );
            }
        }
    }
}
?>
<div class = "container form mb-3 addCover">

<?php if (!empty($randomBook)) { ?>
    <h2><?= $randomBook['title'] ?></h2>
    <p>de <?= "{$randomBook['firstname']} {$randomBook['lastname']}" ?></p>
    <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="300000"/>
        <input type="hidden" name="id" value="<?=$randomBook['ref']?>"/>
        <input type="hidden" name="authId" value="<?=$randomBook['author_id']?>"/>
        <input type="file" class="form-control form-control-dark" name="cover">
        <button type="submit" class="btn btn-primary change-logo" name="btn-add-cover">Valider</button>
    </form>
<?php } ?>


</div>

<?php
include 'includes/footer.php';
?>