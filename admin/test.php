<?php

include 'checkPw.php';
var_dump(password_verify('1234', '$2y$10$yg87ijlMw6POlXY2nPQg2ObpWjoh/Bake5UzpQOKWAm4YaQZyWG/C'));die;

/*
 *
 *
 *                 echo '<pre>';
                var_dump($_SESSION);
                echo '</pre>';
$nbParams = count($book);
var_dump($nbParams);

function getPost(){
    for ($i=1;$i<$nbParams;$i++){
        if ($i == $nbParams-1){
            !empty($_POST['$param']);
        } else {
            !empty($_POST['$param'])."&&";
        }
    }
}
*/
/*<?php   if ($book[$param] == $val){ ?>
                        <option value="<?=$authorRealId?>" selected><?=$authorsRealIds[$authorRealId]['lastname'] . " " . $authorsRealIds[$authorRealId]['firstname']?></option>
                    <?php } else { */?>

<?php /* }                     <?php } else if($_SESSION['status'] == 'membre') { ?>
                        <?php
                        $loaned = false;
                        $rateable = false;
                        $available = true;
                        foreach ($loans as $loan) {
                            if ($loan['user_id'] == $_SESSION['id']) {
                                $available = false;
                                if ($loan['return_date'] > date('Y-m-d')) {
                                    $rateable = false;
                                    $loaned = true;
                                    $available = false;
                                    $return = $loan['return_date'];
                                }
                                if ($loan['return_date'] < date('Y-m-d')) {
                                    $rateable = true;
                                    $loaned = false;
                                    $available = true;
                                }
                            }
                            else if ($loan['user_id'] != $_SESSION['id']){
                                if ($loan['return_date'] > date('Y-m-d')) {
                                    $return = $loan['return_date'];
                                    $rateable = false;
                                    $loaned = true;
                                    $available = false;
                                    $return = $loan['return_date'];
                                }
                                if ($loan['return_date'] < date('Y-m-d')) {
                                    $rateable = false;
                                    $loaned = false;
                                    $available = true;
                                }
                            }
                            if($rateable) {
                                foreach ($ratings as $rating) {
                                    if ($rating['user_id'] == $_SESSION['id'] && $rating['book_id'] == $book['ref'] && isset($rating['rating']) && $loan['user_id'] == $_SESSION['id']) {
                                        $rerate = true;
                                        $rate = false;
                                    } else if ($rating['user_id'] == $_SESSION['id'] && $rating['book_id'] == $book['ref'] && !isset($rating['rating']) && $loan['user_id'] == $_SESSION['id']) {
                                        $rate = true;
                                        $rerate = false;
                                    }
                                }
                            }
                        }
                        if ($rerate) { ?>
                            <form class="loan-rate-form" method="post" action="<?=$_SERVER['PHP_SELF']?>">
                                <input class="loan-rate-form" type="hidden" name="rating_id" value="<?=$rating['id']?>">
                                <select name ="rating-change" class="loan-rate-form form-select form-select" aria-label="form-select" required onchange="updateSelect(this.value);">
                                    <option selected>Change rate(<?= $rating['rating'] ?>)</option>
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
                                <button  name="btn-rate" type=submit class="loan-rate-form btn btn-primary">Rate</button>
                            </form>
                        <?php } else if ($rate){ ?>
                            <form class="loan-rate-form" method="post" action="<?=$_SERVER['PHP_SELF']?>">
                                <input class="loan-rate-form" type="hidden" name="book_id" value="<?=$book['ref']?>">
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
                                <button name="btn-rate" type=submit class="loan-rate-form btn btn-primary">Rate</button>
                            </form>
                        <?php }
                    }
                    if (isset($available)) { ?>
                        <form class="loan-rate-form" method="post" action="<?=$_SERVER['PHP_SELF']?>">
                            <input type="hidden" name="book_id" value="<?=$book['ref']?>">
                            <button name="btn-loan" type=submit class="btn btn-primary">Loan</button>
                        </form>
                    <?php } if (isset($loaned) && isset($return)) { ?>
                        <p class="list-text">retour prévu: <?= $return ?></p>
                    <?php }
                }
            } ?>*/?>