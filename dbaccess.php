<?php

use JetBrains\PhpStorm\ArrayShape;

include('config.php');

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
    $mysqli = @mysqli_connect(HOSTNAME, USERNAME, PASSWORD); //@pour ne rien afficher si erreur

    // selectionner une base de donnée
    if ($mysqli)
    {

        if (mysqli_select_db($mysqli, DATABASE))
        {
            // Nettoyage des données externes
            $query = mysqli_real_escape_string($mysqli, $query);
            //préparer une requète
            $result = mysqli_query($mysqli, $query);

            if($result){
                //extraire les résultats
                while (($line = mysqli_fetch_assoc($result)) != null)
                {
                    $data[] = $line;
                }
                //libérer la mémoire
                mysqli_free_result($result);
                //fermer la connection
                mysqli_close($mysqli);
            } else
            {
                $message = $mysqli->error;
            }
        } else
        {
            $message = $mysqli->error;
        }
    } else
    {
        $message = "Erreur de connexion, verifier votre fichier config";
    }

    $response = [
        'data' => $data,
        'message' => $message,
    ];

    return $response;

}


