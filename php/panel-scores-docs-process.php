<?php
include("config.php");
include("utility.php");

	if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}
	if(!isset($_GET["l"])){
		$language = $lingue[0];
	}else{
		$language = filtra($_GET["l"],$mysqli);
	}
	if(!isset($_POST["newrules"])){
		header('location: panel-index.php?p=panel-scores-docs&m=Non puoi non inserire un regolamento&l='.$language);
		$mysqli->close();
		die();
	}
	
	$newrules=filtra($_POST["newrules"],$mysqli);
	$query="INSERT INTO docs(contenuto,username,tipo,lingua) VALUES('".$newrules."','".$_COOKIE['username']."','punteggio','".$language."')";
	esegui($query,$mysqli);
	inviaLog("[ADMIN]Aggiorna docs punteggi","",$mysqli);

	header('location: ../panel-index.php?p=panel-scores-docs&m=Documento punteggi aggiornato&l='.$language);
	$mysqli->close();
	die();
?>