<?php
include("config.php");
include("utility.php");
if(!isset($_COOKIE["login"])){
	header('location: /index/home');
      nuovoMsg($generalAlert155);
      $mysqli->close();
      die();
}
$query="UPDATE users_verification SET status=2 WHERE email='".$_COOKIE['email']."' AND status=1";
esegui($query,$mysqli);
header('location: /index/market');
nuovoMsg($generalAlert154);
$mysqli->close();
die();

?>