<?php
include("config.php");
include("utility.php");
	if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}
	controllaRank(2);
	if(!isset($_GET["action"])){
		header('location: ../panel-index.php?p=panel-verification&m=Azione invalida');
		nuovoMsg();
		$mysqli->close();
		die();
	}
	if($_GET["action"]==0){ //attiva utente
		if(!isset($_GET["email"])){
			header('location: ../panel-index.php?p=panel-verification&m=Email non trovata');
			nuovoMsg();
			$mysqli->close();
			die();
		}else{
			$email=filtra($_GET["email"],$mysqli);
			$query="UPDATE users_verification SET status=1 WHERE email='".$email."'";
			esegui($query,$mysqli);
			inviaLog("attivazione utente",$email." ".$username,$mysqli);
			header('location: ../panel-index.php?p=panel-verification&m=Utente attivato');
			nuovoMsg();
			$mysqli->close();
			die();
		}

	}
	

?>