<?php
include("config.php");
include("utility.php");

	if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}
	controllaRank(2);
	if(!isset($_GET["l"])){
		$language = $lingue[0];
	}else{
		$language = filtra($_GET["l"],$mysqli);
	}
	if(!isset($_POST["newrules"])){
		header('location: panel-index.php?p=panel-rules&m=Non puoi non inserire un regolamento&l='.$language);
		$mysqli->close();
		die();
	}
	
	$newrules=filtra($_POST["newrules"],$mysqli);
	$query="INSERT INTO docs(contenuto,username,tipo,lingua) VALUES('".$newrules."','".$_COOKIE['username']."','regolamento','".$language."')";
	inviaLog("[ADMIN]Aggiorna regolamento","",$mysqli);
	esegui($query,$mysqli);
	
	header('location: ../panel-index.php?p=panel-rules&m=Regolamento aggiornato&l='.$language);
	$mysqli->close();
	die();
?>