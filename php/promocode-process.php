<?php
include("config.php");
include("utility.php");
	if(!isset($_POST["codice"])){
		header('location: ../index/market');
		nuovoMsg($generalAlert93);
		$mysqli->close();
		die();
	}
	if($_COOKIE["donatore"]!=0){
		header('location: ../index/market');
		nuovoMsg($generalAlert94);
		$mysqli->close();
		die();
	}
$codice=filtra($_POST["codice"],$mysqli);
$query="SELECT COUNT(*) as numero FROM codicipromo WHERE codice='".$codice."' AND maxUtilizzi>utilizzi";
$row=estrai($query,$mysqli);
	if($row["numero"]==0){
		header('location: ../index/market');
		nuovoMsg($generalAlert95);
		$mysqli->close();
		die();
	}
$query="UPDATE codicipromo SET utilizzi=utilizzi+1 WHERE codice='".$codice."'";
esegui($query,$mysqli);
$query="UPDATE users SET donatore=2 WHERE username='".$_COOKIE['username']."'";
esegui($query,$mysqli);
setcookie("donatore",1, time() + (86400 * 30), "/"); 
inviaLog("utilizzo promocode",$codice,$mysqli);
header('location: ../index/market');
nuovoMsg($generalAlert96);
$mysqli->close();
die();

?>