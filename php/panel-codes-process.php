<?php
include("config.php");
include("utility.php");
	if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}
	controllaRank(3);
	if(!isset($_GET["action"])){
		header('location: ../panel-index.php?p=panel-codes&m=Azione invalida');
		nuovoMsg();
		$mysqli->close();
		die();
	}
	if($_GET["action"]==0){ //genera codice
		if(!isset($_POST["codice"])||!isset($_POST["maxUtilizzi"])){
			header('location: ../panel-index.php?p=panel-codes&m=Dati non validi');
			nuovoMsg();
			$mysqli->close();
			die();
		}
		
		$codice=filtra($_POST["codice"],$mysqli);
		$utilizzi=filtra($_POST["maxUtilizzi"],$mysqli);
		if($utilizzi<1){
			header('location: ../panel-index.php?p=panel-codes&m=Utilizzi massimi invalidi');
			nuovoMsg();
			$mysqli->close();
			die();
		}
		$query="SELECT COUNT(*) as numero FROM codicipromo WHERE codice='".$codice."'";
		$row=estrai($query,$mysqli);
		if($row["numero"]!=0){
			header('location: ../panel-index.php?p=panel-codes&m=Codice già utilizzato');
			nuovoMsg();
			$mysqli->close();
			die();
		}
		$query="INSERT INTO codicipromo(codice,maxUtilizzi) VALUES('".$codice."',".$utilizzi.")";
		esegui($query,$mysqli);
		inviaLog("genera promocode",$codice,$mysqli);
		header('location: ../panel-index.php?p=panel-codes&m=Codice generato');
		nuovoMsg();
		$mysqli->close();
		die();
	}else if($_GET["action"]==1){ //revoca codice
		if(!isset($_GET["id"])){
			header('location: ../panel-index.php?p=panel-codes&m=Codice non trovato');
			nuovoMsg();
			$mysqli->close();
			die();
		}else{
			$id=filtra($_GET["id"],$mysqli);
			$query="SELECT codice FROM codicipromo WHERE id=".$id;
			$row=estrai($query,$mysqli);
			inviaLog("revoca promocode",$row["codice"],$mysqli);
			$query="DELETE FROM codicipromo WHERE id=".$id;
			esegui($query,$mysqli);
			header('location: ../panel-index.php?p=panel-codes&m=Codice revocato');
			nuovoMsg();
			$mysqli->close();
			die();
		}

	}
	

?>