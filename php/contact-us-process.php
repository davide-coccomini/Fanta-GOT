<?php
include("config.php");
include("utility.php");


if($_GET["action"]==0){ //Invio contatto
	if(!isset($_POST["email"]) || !isset($_POST["message"]) || !isset($_POST["subject"])){
		header('location: index/market');
		nuovoMsg($generalAlert20);
		$mysqli->close();
		die();
	}
	if($_POST["subject"]=="na")
	{
		header('location: /index/market');
		nuovoMsg($generalAlert21);
		$mysqli->close();
		die();
	}
	$email=filtra($_POST["email"],$mysqli);
	$message=filtra($_POST["message"],$mysqli);
	$subject=filtra($_POST["subject"],$mysqli);

	$query="SELECT COUNT(*) as numero FROM contatti WHERE username='".$_COOKIE['username']."'";

	$row=estrai($query,$mysqli);

	if($row["numero"]>15){
		header('location: /index/market');
		nuovoMsg($generalAlert22);
		$mysqli->close();
		die();
	}

	$query="INSERT INTO contatti(username,oggetto,contenuto,email) VALUES('".$_COOKIE['username']."','".$subject."','".$message."','".$email."')";

	esegui($query,$mysqli);
	nuovoMsg($generalAlert23);
	inviaLog("Invia una richiesta di contatto","",$mysqli);
	header('location: /index/market');
	$mysqli->close();
	die();
}
if($_GET["action"]==1){ //Rimozione di una richiesta di contatto
	controllaRank(3);
	if(!isset($_GET["id"])){
		header('location: /panel-index/panel-contacts');
		nuovoMsg($generalAlert24);
		$mysqli->close();
		die();
	}
 $id=filtra($_GET["id"],$mysqli);

 $query="DELETE FROM contatti WHERE id=".$id;
 esegui($query,$mysqli);
 inviaLog("[ADMIN] Rimuove una richiesta di contatto",$id,$mysqli);
 header('location: /panel-index/panel-contacts');
 nuovoMsg($generalAlert25);
 $mysqli->close();
 die();
}
?>