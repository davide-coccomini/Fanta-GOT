<?php
include("config.php");
include("utility.php");
	if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}
	controllaRank(3);
	if(!isset($_GET["action"])){
		header('location: ../panel-index.php?p=panel-countdown&m=Azione invalida');
		nuovoMsg();
		$mysqli->close();
		die();
	}
	if($_GET["action"]==0){ //genera countdown
		if(!isset($_POST["testo"])||!isset($_POST["dataInizio"])||!isset($_POST["dataFine"])){
			header('location: ../panel-index.php?p=panel-countdown&m=Dati non validi');
			nuovoMsg();
			$mysqli->close();
			die();
		}

		
		$testo=filtra($_POST["testo"],$mysqli);
		$dataInizio=filtra($_POST["dataInizio"],$mysqli);
		$dataFine=filtra($_POST["dataFine"],$mysqli);
		$query="INSERT INTO countdown(testo,dataInizio,dataFine) VALUES('".$testo."','".$dataInizio."','".$dataFine."')";
		esegui($query,$mysqli);
		inviaLog("aggiunge countdown",$codice,$mysqli);
		header('location: ../panel-index.php?p=panel-countdown&m=Countdown generato');
		nuovoMsg();
		$mysqli->close();
		die();
	}else if($_GET["action"]==1){ //rimuovi countdown
		if(!isset($_GET["id"])){
			header('location: ../panel-index.php?p=panel-countdown&m=Countdown non trovato');
			nuovoMsg();
			$mysqli->close();
			die();
		}else{
			$id=filtra($_GET["id"],$mysqli);
			$query="SELECT testo FROM countdown WHERE id=".$id;
			$row=estrai($query,$mysqli);
			inviaLog("rimozione countdown",$row["testo"],$mysqli);
			$query="DELETE FROM countdown WHERE id=".$id;
			esegui($query,$mysqli);
			header('location: ../panel-index.php?p=panel-countdown&m=Countdown rimosso');
			nuovoMsg();
			$mysqli->close();
			die();
		}

	}
	

?>