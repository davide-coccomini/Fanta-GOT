<?php
include("config.php");
include("utility.php");



if(!isset($_GET["action"])){ //invia una segnalazione
	if(!isset($_POST["titolo"]) || !isset($_POST["descrizione"])){
		nuovoMsg($generalAlert9);
		header('location: /index/market');
		$mysqli->close();
		die();
	}
	if(strlen($_POST["titolo"])<7 || strlen($_POST["password"])>20){
		nuovoMsg($generalAlert10);
		header('location: /index/market');
		$mysqli->close();
		die();
	}

	$titolo=filtra($_POST["titolo"],$mysqli);
	$descrizione=filtra($_POST["descrizione"],$mysqli);
	$query="INSERT INTO bugs(titolo,descrizione,username) VALUES('".$titolo."','".$descrizione."','".$_COOKIE['username']."')";
	inviaLog("Segnala un bug",$titolo,$mysqli);
	esegui($query,$mysqli);
	nuovoMsg($generalAlert11);
	header('location: /index/market');
	$mysqli->close();
	die();
}else if($_GET["action"]==0){
	controllaRank(3);
	if(!isset($_GET["id"])){
		nuovoMsg($generalAlert12);
		header('location: /panel-index/panel-bugs');
		$mysqli->close();
		die();
	}
	$id=filtra($_GET["id"],$mysqli);
	$query="DELETE FROM bugs WHERE id=".$id;
	esegui($query,$mysqli);
	nuovoMsg($generalAlert13);
	header('location: /panel-index/panel-bugs');
	$mysqli->close();
	die();
}else if($_GET["action"]==1){
	controllaRank(3);
	$query="DELETE FROM bugs";
	esegui($query,$mysqli);
	nuovoMsg($generalAlert14);
	header('location: /panel-index/panel-bugs');
	$mysqli->close();
	die();

}else if($_GET["action"]==2){
	controllaRank(3);

	if(!isset($_POST["username"]) || !isset($_POST["testo"])){
		header('location: /index/market');
		$mysqli->close();
		die();
	}
	$username = filtra($_POST["username"],$mysqli);
	$testo = filtra($_POST["testo"],$mysqli);
	
            
	nuovoMsg($generalAlert14);
	header('location: /panel-index/panel-bugs');
	$mysqli->close();
	die();
}