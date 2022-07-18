<?php
include 'includes/header.php';
include 'dbaccess.php';

$loansFrom = [];
$date= date('Y-m-d');
if(isset($_GET['login'])){
    $login = htmlspecialchars($_GET['login']);
    try {
        $db = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DATABASE , USERNAME, PASSWORD);
    } catch (Exception $e) {
        die("Error : " . $e->getMessage());
    }
    $loginValid = $db->prepare('SELECT login FROM users WHERE login = ?');
    $loginValid->execute(array($login));

    $isLoginValid = $loginValid->fetch();
    if(isset($isLoginValid)){
        try {
            $db = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DATABASE , USERNAME, PASSWORD);
        } catch (Exception $e) {
            die("Error : " . $e->getMessage());
        }
        $stmt = $db->prepare("SELECT  l.id , title, return_date FROM books 
                                    INNER JOIN loans l ON books.ref=l.book_id
                                    INNER JOIN users  u ON u.id=l.user_id 
                                    WHERE login=?");

        $stmt->execute(array($login));
        $loansFrom = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}


if (isset($_POST['btn-restitution'])){
    $loan = $_POST['id'];
    $returnDate = date('Y-m-d', strtotime('-1days'));
    $query = "UPDATE loans SET return_date=$returnDate WHERE id='$loan'";
    $mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
    if ($result = $mysqli->query($query)){
        header("location: manageLoans.php?login=$login");
    }

}
?>
<div class="container">
    <?php if (!isset($_GET['login'])){ ?>
    <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" action="<?php $_SERVER['PHP_SELF'] ?>" method="get">
        <label class="form-label">Entrez le login du membre concerné</label>
        <input type="text" class="form-control form-control-dark" name="login">
        <button type="submit" class="btn btn-primary change-logo" name="btn-show-loans">Afficher les emprunts</button>
    </form>
    <?php } ?>
    <?php if (count($loansFrom) != 0) { ?>
    <table class="content-manage-loans table table-bordered table-striped">
        <caption>Emprunts en cours de <?= $login ?></caption>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Date retour</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $activeLoans = 0;
        foreach ($loansFrom as $loan){
            if ($loan['return_date'] > $date ) {
                $activeLoans++;?>
                <tr>
                    <td class="list-text"><?= $loan['title']?></td>
                    <td class="list-text"><?= $loan['return_date']?></td>
                    <td>
                        <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
                            <input type="hidden" name="id" value="<?=$loan['id']?>">
                            <button type="submit" class="btn btn-primary change-logo" name="btn-restitution">Restituer</button>
                        </form>
                    </td>
                </tr>
            <?php }
        } ?>
        </tbody>
        <tfoot>
            <tr >
                <th colspan="3">Locations en cours : <?= $activeLoans ?></th>
            </tr>
        </tfoot>
    </table>
    <?php } ?>
</div>

<?php
include 'includes/footer.php';
?>