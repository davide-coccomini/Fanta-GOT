<?php

include("config.php");
include("utility.php");
	if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}

	if(!isset($_POST["user"]) || !isset($_POST["rank"])){
		header('location: ../panel-index.php?p=panel-staff&m=Modulo invalido');
		nuovoMsg();
		$mysqli->close();
		die();
	}

	controllaRank(3);

	$user=filtra($_POST["user"],$mysqli);
	$rank=filtra($_POST["rank"],$mysqli);
	$query="UPDATE users SET admin=".$rank." WHERE username='".$user."'";
	esegui($query,$mysqli);
	inviaLog("[ADMIN]Modifica staff","user:".$user."; rank:".$rank,$mysqli);
	header('location: ../panel-index.php?p=panel-staff&m=Operazione effettuata');
	nuovoMsg();
	$mysqli->close();
	die();


?>