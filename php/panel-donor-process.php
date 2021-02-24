<?php
include("config.php");
include("utility.php");
	if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}
	controllaRank(3);
	if(!isset($_POST["user"])){
		header('location: ../panel-index.php?p=panel-donor&m=Username non inserito');
		nuovoMsg();
		$mysqli->close();
		die();
	}
	$user=filtra($_POST["user"],$mysqli);
	$query="SELECT COUNT(*) as numero FROM users WHERE username='".$user."'";
	$row=estrai($query,$mysqli);
	if($row["numero"]==0){
		header('location: ../panel-index.php?p=panel-ban&m=Username non trovato');
		nuovoMsg();
		$mysqli->close();
		die();
	}
	$query = "SELECT donatore FROM users WHERE username='".$user."'";
	$row = estrai($query,$mysqli);

	if($row["donatore"]>0){
		$query = "UPDATE users SET donatore = 0, scadenzaDonatore = NULL WHERE username='".$user."'";
		esegui($query,$mysqli);
	}else{
		if($_POST["days"]!="" && is_numeric($_POST["days"]) && $_POST["days"]>0){
			$days = filtra($_POST["days"],$mysqli);
			$query = "UPDATE users SET donatore = 1,scadenzaDonatore = CURRENT_TIMESTAMP + INTERVAL ".$days." DAY WHERE username='".$user."'";
		}else{
			$query="UPDATE users SET donatore = 1, scadenzaDonatore = NULL WHERE username='".$user."'";
		}
	 esegui($query,$mysqli);
	}

	inviaLog("[ADMIN]Setta/Desetta donatore",$user,$mysqli);
	header('location: ../panel-index.php?p=panel-donor&m=Operazione completata');
	nuovoMsg();
	$mysqli->close();
	die();

?>