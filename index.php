<?php
include 'includes/header.php';
include 'functions.php';
include 'dbaccess.php';

if (isset($_GET['error']))
{
    $error = match ($_GET['error']) {
        'membre' => "Désolé, vous devez être membre pour aller sur cette page...",
        'admin' => "Désolé, vous devez être admin pour aller sur cette page...",
        'db' => "Problème avec la base de donnée, veuillez contacter votre administrateur réseau...",
        'noBooks' => "Pas de livre Trouvé...",
    };
}

if (isset($_GET['succes']))
{
    $succes = match ($_GET['succes']) {
        'deco' => "Vous êtes déconnecté, à bientôt",
        'connect' => "Bonjour " . $_SESSION['username'] . ". Vous êtes bin connecté en tant que " . $_SESSION['status'] . ", Heureux de vous revoir parmis nous",
        'userCreated' => "Bienvenue parmis nous " . $_SESSION['username'] . ". N'hésitez pas à contacter un admin en cas de problème.",
        'editBook' => "Livre correctement mis à jour.",
        'addBook' => "Livre correctement ajouté.",
        'deleteBook' => "Livre correctement supprimé.",
        'addAuthor' => "Auteur correctement ajouté.",
        'loanBook' => "Livre loué. Bonne lecture",
        'resetPw' => "Vérifiez vos emails afin de réintialiser votre mot de passe",
        'pwUpdated' => "Votre mot de passe à été mis à jour...",
    };
}

//récupere les livres
$query = "SELECT * FROM books";
$getBooks = dbAccess($query);
$books = $getBooks['data'];
$message = $getBooks['message'];

//recupére auteurs
$query = "SELECT * FROM authors" ;
$getAuthorsNames = dbAccess($query);
$authorsNames = $getAuthorsNames['data'];
$message = $getAuthorsNames['message'];
$authorsRealIds = getAuthorIds($authorsNames);

//recherche livre par auteur
if (isset($_GET['query']) && !empty($_GET['query']))
{
    $authors = filterAuthors(strtolower($_GET['query']));
    $books = filterBooks($authors);
}

//récupere les livres indisponibles
$query = "SELECT * FROM loans";
$getLoans = dbAccess($query);
$loans = $getLoans['data'];
$message = $getLoans['message'];

//récupere les ratings
$query = "SELECT * FROM ratings";
$getRatings = dbAccess($query);
$ratings = $getRatings['data'];
$message = $getRatings['message'];

//recuperer id deletebook
if (!empty($_POST['refDel']))
{
    $refDel = htmlspecialchars($_POST['refDel']);
    if(deleteBook($refDel))
    {
        header("location: index.php?succes=deleteBook");
    }
    else
    {
        header("location: index.php?error=db");
    }
}

/**
 * @param $refDel
 * @return bool|void
 */
function deleteBook($refDel)
{
    // Create connection
    $mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $query = "DELETE FROM books WHERE ref =$refDel";

    if ($mysqli->query($query)) {
        $mysqli->close();

        return true;

    } else {

        return $mysqli->error;
    }
}

//insertRatings(24, 17);
/**
 * @param $id
 * @param $loandBook
 * @param $returnDate
 * @return string|void
 */
function loanBook($id, $loandBook, $returnDate)
{
    // Create connection
    $mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $query = "INSERT INTO loans (user_id,book_id,return_date) VALUES ('$id','$loandBook','$returnDate')";

    if ($mysqli->query($query)) {
        $mysqli->close();
        header("location: index.php?succes=loanBook");
    } else {

        return $mysqli->error;
    }
}

/**
 * @param $id
 * @return array|false[]
 */
function getBookRating($id){
    $query = "SELECT * FROM ratings WHERE book_id=$id && rating is NOT NULL";
    $bookRatings = dbAccess($query);
    $votes= $bookRatings['data'];
    $nbVotes = count($votes);
    $sum =0;

    foreach($votes as $vote){
        $sum += $vote['rating'];
    }

    if($nbVotes > 0){
        $averageRating = round($sum / $nbVotes);
    }

    if($nbVotes == 0){
        $response = [
            'rated' => false,
        ];
    } else {
        $response = [
            'rated' => true,
            'nbVotes' => $nbVotes,
            'avRating' => $averageRating,
        ];
    }

    return $response;
}

/**
 * @param $book_id
 * @param $id
 * @param $rating_change
 * @return bool|string|void
 */
function changeRate($book_id, $id, $rating_change)
{

    // Create connection
    $mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    $book_id = mysqli_real_escape_string($mysqli, $book_id);
    $id = mysqli_real_escape_string($mysqli, $id);
    $rating_change = mysqli_real_escape_string($mysqli, $rating_change);

    $query = "UPDATE ratings SET rating='$rating_change' WHERE user_id='$id' && book_id='$book_id'";

    if ($mysqli->query($query)) {

        $mysqli->close();
        return  true;

    } else {

        return $mysqli->error;
    }
}
if (isset($_POST['btn-loan'])){
    $loandBook = $_POST['book_id'];
    $id = $_SESSION['id'];
    $returnDate = date('Y-m-d', strtotime('+7days'));
    // Ajouter une ligne dans la table ratings
    insertRatings($id, $loandBook);
    loanBook($id, $loandBook, $returnDate);
}
if (isset($_POST['btn-rate'])){
    $book_id = $_POST['book_id'];
    $id = $_SESSION['id'];
    $rating = $_POST['rating'];

    changeRate($book_id, $id, $rating);
}
if (isset($_POST['btn-change-rate'])){
    $book_id = $_POST['book_id'];
    $id = $_SESSION['id'];
    $rating_change = $_POST['rating-change'];

    changeRate($book_id, $id, $rating_change);
}

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
    <!--On affiche la photo pour les nouveau membre-->
    <?php if($_SESSION['status'] == 'novice') { ?>
        <img src="img/dock-1846008_1920.jpg" alt= "IMAGE DE BIENVENUE" class="img-welcome"/>
    <?php } ?>
    <section class="container">
        <h2>A la une</h2>
        <?php
        $row = 1;

        if ( ($handle = fopen("books_spotlight.csv", "r")) !== false )
        {
            while ( ($data = fgetcsv($handle, 1000, ";")) !== false )
            {
                if ($row != 1) {
                    echo "<p><a href='view.php?id={$data[0]}&authId={$data[2]}'>$data[1]</a></p>";
                }
                $row++;
                if ($row == 6) break;
            }
            fclose($handle);
        }
        ?>
    </section>
    <?php if(count($books) == 0) {
        header("location: index.php?error=noBooks");
    } else { ?>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>
                        <h2 class='list-text'>Référence</h2>
                    </th>
                    <th>
                        <h2 class='list-text'>Titre</h2>
                    </th>
                    <th>
                        <h2 class='list-text'>Auteur</h2>
                    </th>
                    <th>
                        <h2 class='list-text'>Note</h2>
                    </th>
                    <th>
                        <h2 class='list-text'>Couverture</h2>
                    </th>
                    <!--On affiche pas les boutons si on est ni admin ni membre-->
                    <?php if($_SESSION['status'] !== 'membre' && $_SESSION['status'] !== 'admin') { ?>
                        <!--Sinon on les affiche-->
                    <?php }  else { ?>
                        <th>
                            <h2 class='list-text'>Actions</h2>
                        </th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody id="content">
            <?php foreach($books as $book) { ?>
                <tr>
                    <td>
                        <p class='list-text'><?= $book['ref'] ?></p>
                    </td>
                    <td>
                        <p class='list-text'><a href="view.php?id=<?= $book['ref'] ?>&authId=<?= $authorsRealIds[$book['author_id']]['id'] ?>"><?= $book['title'] ?></a></p>
                    </td>
                    <td>
                        <p class='list-text'><?= $authorsRealIds[$book['author_id']]['firstname']. " " . $authorsRealIds[$book['author_id']]['lastname'] ?></p>
                    </td>
                    <td>
                        <?php
                        $bookratings = getBookRating($book['ref']);
                        if ($bookratings['rated'] == true){
                            echo "<p>{$bookratings['avRating']}</p>";
                            echo "<p>{$bookratings['nbVotes']}  Votes</p>";
                        } else {
                            echo "<p>Pas de votes</p>";
                        }

                        ?>
                    </td>
                    <td>
                        <?php if ($book['cover_url'] != null){ ?>
                        <div class="cover">
                            <img class="cover-img" alt="<?=$book['title']?>" src="img/covers/<?=$book['cover_url']?>">
                        </div>
                        <?php } ?>
                    </td>
                <?php if ($_SESSION['status'] == "unknown") { ?>
                <?php } else { ?>
                    <td>
                    <?php if($_SESSION['status'] == 'admin') { ?>
                        <!-- Delete trigger modal -->
                        <button type="button" class="btn btn-primary open-AddBookDialog" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-id="<?= $book['ref'] ?>">Delete</button>
                        <!-- Modal -->
                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Vous allez supprimer ce livre. Veuillez confirmer.</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Cette action est irréversible
                                    </div>
                                    <div class="modal-footer">
                                        <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
                                            <input type="hidden" name="refDel" id="bookId">
                                            <button class= "btn-delete" type="submit" class="btn btn-primary">Delete</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="btn btn-primary" href="edit.php?id=<?= $book['ref'] ?>&authId=<?= $authorsRealIds[$book['author_id']]['id'] ?>" >Edit</a>
                    <?php } else if($_SESSION['status'] == 'membre') { ?>
                        <?php
                        $loaned = false;
                        $rateable = false;
                        $available = true;
                        foreach ($loans as $loan) {
                            if ($loan['book_id'] == $book['ref']) {
                                $available = false;
                                if ($loan['return_date'] > date('Y-m-d') && $loan['user_id'] == $_SESSION['id']) {
                                    $return = $loan['return_date'];
                                    $rateable = false;
                                    $loaned = true;
                                    $available = false;
                                }
                                if ($loan['return_date'] < date('Y-m-d') && $loan['user_id'] == $_SESSION['id']) {
                                    $rateable = true;
                                    $loaned = false;
                                    $available = true;
                                }
                                if ($loan['return_date'] > date('Y-m-d')&& $loan['user_id'] != $_SESSION['id']) {
                                    $return = $loan['return_date'];
                                    $rateable = false;
                                    $loaned = true;
                                    $available = false;
                                }
                                if ($loan['return_date'] < date('Y-m-d') && $loan['user_id'] != $_SESSION['id']) {
                                    $rateable = false;
                                    $loaned = false;
                                    $available = true;
                                }
                            }
                        }
                        if ($available) { ?>
                            <form method="post" class="loan-rate-form" action="<?=$_SERVER['PHP_SELF']?>">
                                <input type="hidden" name="book_id" value="<?=$book['ref']?>">
                                <button name="btn-loan" type=submit class="btn btn-primary">Loan</button>
                            </form>
                        <?php } if ($loaned) { ?>
                            <p class="list-text">retour prévu: <?= $return ?></p>
                        <?php }
                        if($rateable) {
                            foreach ($ratings as $rating) {
                                if ($rating['user_id'] == $_SESSION['id'] && $rating['book_id'] == $book['ref'] && $rating['rating'] != NULL ) {
                                    $state = 'changeRate';
                                } else if ($rating['user_id'] == $_SESSION['id'] && $rating['book_id'] == $book['ref'] && $rating['rating'] == NULL) {
                                    $state = 'rate';
                                }
                            }
                            if ($state == 'rate'){ ?>
                                <form class="loan-rate-form" method="post" action="<?=$_SERVER['PHP_SELF']?>">
                                    <input type="hidden" name="book_id" value="<?=$book['ref']?>">
                                    <select name ="rating" class="form-select form-select loan-rate-form" aria-label="form-select" required onchange="updateSelect(this.value);">
                                        <option selected>rate book</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                    <button name="btn-rate" type=submit class="btn btn-primary loan-rate-form">rate</button>
                                </form>
                            <?php }
                            else if ($state == 'changeRate') { ?>
                                <form class="loan-rate-form" method="post" action="<?=$_SERVER['PHP_SELF']?>">
                                    <input type="hidden" name="book_id" value="<?=$book['ref']?>">
                                    <select name ="rating-change" class="loan-rate-form form-select form-select" aria-label="form-select" required onchange="updateSelect(this.value);">
                                        <option selected>Change rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                    <button name="btn-change-rate" type=submit class="btn btn-primary loan-rate-form">rate</button>
                                </form>
                            <?php }
                        }
                    }
                } ?>
                </td>
                <?php } ?>
                </tr>
            </tbody>
        </table>
    <?php }
    include 'includes/events.inc.php';
    ?>
</div>
<?php
include 'includes/footer.php';
?>