<?php
include("config.php");
include("utility.php");
if(!isset($_GET["key"])){
	header('location: /index/home&m=Codice invalido');
	nuovoMsg();
	$mysqli->close();
	die();
}
$key=filtra($_GET["key"],$mysqli);

$query="SELECT email, COUNT(*) as numero FROM users_verification WHERE registration_key='".$key."'";

$row=estrai($query,$mysqli);
if($row["numero"]==0){
	header('location: /index/home');
	nuovoMsg($generalAlert27);
	$mysqli->close();
	die();
}

$query="UPDATE users_verification SET status=1 WHERE registration_key='".$key."'";
esegui($query,$mysqli);

include('./libs/mailchimp.php');
$mailchimp = new Mailchimp();
$mailchimp->addUserToList($row['email']);

if(!isset($_COOKIE["login"])){
 header('location: /index/home');
 $testoMsg = $generalAlert28;
}else{
 header('location: /index/market');
 $testoMsg = $generalAlert29;
}
nuovoMsg($testoMsg);
$mysqli->close();
die();
?>
