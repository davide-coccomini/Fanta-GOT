<?php
include("config.php");
include("utility.php");
if(!isset($_GET["action"]) || !isset($_GET["id"]))
{
	header('location: /index/formation');
    nuovoMsg($generalAlert133);
	$mysqli->close();
	die();
}
$id=filtra($_GET["id"],$mysqli);
$query = "SELECT COUNT(*) as numero FROM personaggiacquistati WHERE username='".$_COOKIE['username']."'";
$row=estrai($query,$mysqli);
if($row["numero"]!=$maxPersonaggiAcquistabili){
	header('location: /index/formation');
    nuovoMsg($generalAlert131);
	$mysqli->close();
	die();
}

$query="SELECT status FROM aperture WHERE soggetto='schieramento' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
$row=estrai($query,$mysqli);
if($row["status"]==1){

	inviaLog("[!]Tentativo di schieramento durante la chiusura","",$mysqli);
	header('location: /index/formation');
    nuovoMsg($generalAlert132);
	$mysqli->close();
	die();
}
if($_GET["action"]==0){ // schieramento
	$query="SELECT COUNT(*) as numero FROM schieramenti S INNER JOIN personaggiacquistati PA ON S.acquisto=PA.id WHERE PA.username='".$_COOKIE['username']."'";
	$row=estrai($query,$mysqli);
	if($row["numero"]==$maxPersonaggiSchierabili){
		header('location: /index/formation');
	    nuovoMsg($generalAlert134);
		$mysqli->close();
		die();
	}
	$query="SELECT *,COUNT(*) as numero FROM schieramenti S INNER JOIN (personaggiacquistati PA INNER JOIN personaggi P on P.id=PA.personaggio) ON S.acquisto=PA.id WHERE S.acquisto=".$id;
	$row=estrai($query,$mysqli);
	$nome=$row["nome"];
	if($row["numero"]!=0){
		header('location: /index/formation');
	    nuovoMsg($generalAlert135);
		$mysqli->close();
		die();
	}
	if($_COOKIE["personaggiSchierati"]+1 == $maxPersonaggiSchierabili && isset($_COOKIE["invitedBy"])){
		if($_COOKIE["punteggioInvitante"]!=-1){
			$query = "UPDATE users SET punteggioInvitante = 1 WHERE username='".$_COOKIE['username']."'";
			esegui($query,$mysqli);
		}
	}
	$query="INSERT INTO schieramenti(acquisto,username) VALUES(".$id.",'".$_COOKIE['username']."')";
	esegui($query,$mysqli);
	$query="UPDATE users SET personaggiSchierati=personaggiSchierati+1 WHERE username='".$_COOKIE['username']."'";
	esegui($query,$mysqli);
    setcookie("schierato".$id, 1 , time() + (86400 * 30), "/");
    setcookie("personaggiSchierati",$_COOKIE["personaggiSchierati"]+1, time() + (86400 * 30), "/"); 
	header('location: /index/formation');
	if($_COOKIE["personaggiSchierati"]+1 == $maxPersonaggiSchierabili)
		nuovoMsg($generalAlert148);
	else
		nuovoMsg($generalAlert136);
	inviaLog("schieramento",$nome,$mysqli);
	$mysqli->close();
	die();
}
if($_GET["action"]==1){ //togli da schieramento
	$query="SELECT COUNT(*) as numero FROM schieramenti S INNER JOIN personaggiacquistati PA ON S.acquisto=PA.id WHERE PA.username='".$_COOKIE['username']."' AND acquisto=".$id;
	$row=estrai($query,$mysqli);
	if($row["numero"]==0){
		header('location: /index/formation');
		nuovoMsg($generalAlert137);
		$mysqli->close();
		die();
	}
	$query="DELETE FROM schieramenti WHERE acquisto=".$id;
	esegui($query,$mysqli);
	$query="UPDATE users SET personaggiSchierati=personaggiSchierati-1 WHERE username='".$_COOKIE['username']."'";
	esegui($query,$mysqli);
	setcookie("schierato".$id, 1 , time() -1, "/");
	setcookie("schierato".$id, 1 , time() -1);
	setcookie("personaggiSchierati",$_COOKIE["personaggiSchierati"]-1, time() + (86400 * 30), "/"); 

	header('location: /index/formation');
	nuovoMsg($generalAlert138);
	$mysqli->close();
	die();
}
?>