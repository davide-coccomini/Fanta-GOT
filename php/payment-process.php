<?php
  SESSION_start();
  include("utility.php");
  include("config.php");

  $query = 'UPDATE users SET donatore = 1 WHERE username = "'.$_COOKIE['username'].'"';
  esegui($query, $mysqli);

  $response = array(
    'p' => 'about-donation',
    'm' => 'Donazione effettuata, visita il menu extra per accedere alle nuove funzionalità'
  );

  echo json_encode($response);

?>
