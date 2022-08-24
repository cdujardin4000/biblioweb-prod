<?php
include('config.php');
use JetBrains\PhpStorm\ArrayShape;


/**
 * @param $query
 * @return array
 */
#[ArrayShape(['data' => "array", 'message' => "string"])]
function dbAccess($query)
{
    $data = [];
    $message = "";
    // se connecter au serveur Mysql

try {
    $mysqli = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);
    $query = mysqli_real_escape_string($mysqli, $query);
    //préparer une requète
    $result = mysqli_query($mysqli, $query);
    if($result){
       while (($line = mysqli_fetch_assoc($result)) != null){
           $data[] = $line;
       }
    }
    //libérer la mémoire
    mysqli_free_result($result);
    //fermer la connection
    mysqli_close($mysqli);
  
 
    //var_dump($data[0]);
    

    }
    catch (Throwable $e) {
        echo "Captured Throwable for connection : " . $e->getMessage() . PHP_EOL;
    }

    $response = [
        'data' => $data,
        'message' => $message,
    ];
    return $response;



}


