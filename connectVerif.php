<?php
$host = "localhost";
$user = "biblioweb";
$password = "Iamalegend19321980";
$dbname = "biblioweb";
$port = "3306";
try {
  $db = mysqli_init();
  
  $link = mysqli_real_connect($db, $host.":".$port, $user, $password, $dbname);
  // Nettoyage des données externes
  $query = mysqli_real_escape_string($mysqli, $query);
  //préparer une requète
  $result = mysqli_query($mysqli, $query);

  while (($line = mysqli_fetch_assoc($result)) != null){
      $data[] = $line;
  }
  //libérer la mémoire
  mysqli_free_result($result);
  //fermer la connection
  mysqli_close($mysqli);
  
  $rows = $res->fetch_all();
  var_dump($rows[0]);
  $db->close();
  $response = [
      'data' => $data,
      'message' => $message,
  ];

  return $response;

}
catch (Throwable $e) {
  echo "Captured Throwable for connection : " . $e->getMessage() . PHP_EOL;
}
