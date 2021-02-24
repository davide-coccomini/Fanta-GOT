<?php
include("config.php");
include("utility.php");

	if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}

controllaRank(2);

	if(!isset($_GET["action"])){
			header('location: ../panel-index.php?p=panel-scheduled-closers&m=Azione imprevista');
			nuovoMsg();
			$mysqli->close();
			die();
	}

	if($_GET["action"]==0){ //inserisci programmazione
		if(!isset($_POST["data"]) || !isset($_POST["soggetto"]) || !isset($_POST["status"])){
			header('location: ../panel-index.php?p=panel-scheduled-closers&m=Dati non pervenuti');
			nuovoMsg();
			$mysqli->close();
			die();
		}
		$data = filtra($_POST["data"],$mysqli);
		$soggetto = filtra($_POST["soggetto"],$mysqli);
		$status = filtra($_POST["status"],$mysqli);

		$query = "INSERT INTO aperture(soggetto,status,programmato,username) VALUES('".$soggetto."',".$status.",'".$data."','".$_COOKIE["username"]."')";
		esegui($query,$mysqli);
		header('location: ../panel-index.php?p=panel-scheduled-closers&m=Azione programmata con successo');
		nuovoMsg();
		$mysqli->close();
		die();
	}else if($_GET["action"]==1){
		if(!isset($_GET["id"])){
			header('location: ../panel-index.php?p=panel-scheduled-closers&m=Dati non pervenuti');
			nuovoMsg();
			$mysqli->close();
			die();
		}
		$id = filtra($_GET["id"],$mysqli);
		$query = "DELETE FROM aperture WHERE id = ".$id." AND programmato IS NOT NULL";
		esegui($query,$mysqli);
		header('location: ../panel-index.php?p=panel-scheduled-closers&m=Programmazione annullata');
		nuovoMsg();
		$mysqli->close();
		die();
	}

?>