<?php
include("config.php");
include("utility.php");
	if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}
	controllaRank(2);
	if(!isset($_POST["user"])){
		header('location: ../panel-index.php?p=panel-ban&m=Username non inserito');
		nuovoMsg();
		$mysqli->close();
		die();
	}
	$user=filtra($_POST["user"],$mysqli);
	$query="SELECT admin,COUNT(*) as numero FROM users WHERE username='".$user."'";
	$row=estrai($query,$mysqli);
	if($row["numero"]==0){
		header('location: ../panel-index.php?p=panel-ban&m=Username non trovato');
		nuovoMsg();
		$mysqli->close();
		die();
	}
	if($row["admin"]>$_COOKIE["admin"]){
		inviaLog("[!]Tentativo di bannare un superiore",$user,$mysqli);
		header('location: ../panel-index.php?p=panel-ban&m=Non puoi bannare qualcuno di grado più alto');
		nuovoMsg();
		$mysqli->close();
		die();
	}
	$query="UPDATE users SET ban= NOT ban WHERE username='".$user."'";
	esegui($query,$mysqli);
	inviaLog("[ADMIN]Ban/Sban",$user,$mysqli);
	header('location: ../panel-index.php?p=panel-ban&m=Operazione completata');
	nuovoMsg();
	$mysqli->close();
	die();

?>