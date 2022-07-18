<?php
/**
 * @param $authors
 * @return array
 */
function getAuthorIds($authors)
{
    $tab = [];
    foreach($authors as $author)
    {
        $tab[$author['id']] = $author;
    }

    return $tab;
}

/**
 * @param $needle
 * @return array
 */
function filterAuthors($needle)
{
    //reset les auteurs
    $query = "SELECT * FROM authors" ;
    $getAuthors = dbAccess($query);
    $authors = $getAuthors['data'];
    $filtered = [];
    foreach ($authors as $author)
    {
        if (str_contains(strtolower($author['firstname']), strtolower($needle)) || str_contains(strtolower($author['lastname']), strtolower($needle)))
        {
            $filtered[] = $author;
            foreach ($filtered as $findAuthor)
            {
                $_SESSION['lastAuthorsFind'][] = $findAuthor['lastname'];
                if (isset($_SESSION['lastAuthorsFind']) && count($_SESSION['lastAuthorsFind']) > 3)
                {
                    array_shift($_SESSION['lastAuthorsFind']);
                }
            }
        }
    }

    return $filtered;
}

/**
 * @param $authors
 * @return array
 */
function filterBooks($authors)
{
    //reset les livres
    $query = "SELECT * FROM books";
    $getBooks = dbAccess($query);
    $filtered = [];
    $books = $getBooks['data'];

    foreach ($authors as $author)
    {
        foreach ($books as $book)
        {
            if ($book['author_id'] == $author['id']) {
                $filtered[] = $book;
            }
        }
    }

    return $filtered;
}

/**
 * @param $username
 * @param $password
 * @return array|false|string[]|void
 */
function pwVerif($username, $password)
{
    // Connexion au serveur MySQL et sélection de la base de données
    if ($mysqli = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE)) {

        // Nettoyage des données externes
        $username = mysqli_real_escape_string($mysqli, $username);

        // Exécution de la requête SQL
        $result = mysqli_query($mysqli, "SELECT * FROM users WHERE login='$username'");
        if ($result) {
            // Extraction des données
            $user = mysqli_fetch_assoc($result);
            mysqli_free_result($result); // Libérer la mémoire

            if ($user && password_verify($password, $user['password'])) {

                mysqli_close($mysqli); // Fermer la connexion au serveur
                return $user;
            }
        }
        mysqli_close($mysqli); // Fermer la connexion au serveur

        return false;
    }
}

/**
 * @param $username
 * @param $password
 * @param $mail
 */
function addUser($username, $password, $mail)
{
// Create connection
    $mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
// Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    // Nettoyage des données externes
    $username = mysqli_real_escape_string($mysqli, $username);
    $mail = mysqli_real_escape_string($mysqli, $mail);
    $password = password_hash($password, PASSWORD_BCRYPT);


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

/**
 * @param $title
 * @param $author_id
 * @param $description
 * @param $cover_url
 * @return bool|void
 */
/**
 * @param $id
 * @param $loandBook
 * @return string|void
 */
function insertRatings($id, $loandBook)
{

    // Create connection
    $mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $query = "INSERT INTO ratings (`user_id`,`book_id`,`rating`) VALUES ('$id','$loandBook',NULL)";

    if ($mysqli->query($query)) {
        $mysqli->close();
    } else {

        return $mysqli->error;
    }
}
function addBook($title, $author_id, $description, $cover_url)
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

    $query = "INSERT INTO books (title, author_id, description, cover_url) VALUES ('$title', '$author_id', '$description', '$cover_url')";

    if ($mysqli->query($query)) {
        //var_dump($mysqli->connect_error);die;
        $mysqli->close();
        return  true;

    } else {

        return false;
    }
}

/**
 * @param $lastname
 * @param $firstname
 * @param $nationality
 * @return bool|void
 */
function addAuthor($lastname, $firstname, $nationality)
{
// Create connection
    $mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
// Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    // Nettoyage des données externes
    $lastname = mysqli_real_escape_string($mysqli, $lastname);
    $firstname = mysqli_real_escape_string($mysqli, $firstname);
    $nationality = mysqli_real_escape_string($mysqli, $nationality);


    $query = "INSERT INTO authors (lastname, firstname, nationality) VALUES ('$lastname', '$firstname', '$nationality')";

    if ($mysqli->query($query)) {
        $mysqli->close();
        return true;

    } else {

        return false;
    }
}




