<?php
include("config.php");
include("utility.php");

	if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}
	controllaRank(2);


	if($_GET["action"]==0){ //TODO: Rimozione di tutti i membri inattivi dai gruppi

	}else if($_GET["action"]==1){ //rimozione di un singolo membro inattivo dai gruppi
		if(!isset($_GET["user"])){
			nuovoMsg();
			header('location: ../panel-index.php?p=panel-inactive&m=Utente invalido');
			$mysqli->close();
			die();
		}
		$username=filtra($_GET['user'],$mysqli);
		$query = "SELECT *,COUNT(*) as numero FROM users U WHERE U.username='".$username."' AND U.gruppo IS NOT NULL AND U.username NOT IN (SELECT P.username FROM punteggi P)";
		$row = estrai($query,$mysqli);

		if($row["numero"]==0){
			nuovoMsg();
			header('location: ../panel-index.php?p=panel-inactive&m=Utente inesistente o non inattivo');
			$mysqli->close();
			die();
		}
	 $query = "SELECT membri,nome FROM gruppi WHERE nome ='".$row['gruppo']."'";
	 $row=estrai($query,$mysqli);
	 if($row["membri"]==1){
	 		nuovoMsg();
			header('location: ../panel-index.php?p=panel-inactive&m=Non puoi cacciare il membro in quanto non ce ne sono altri nel gruppo');
			$mysqli->close();
			die();
	 }
	 echo $query;
	 $query = "UPDATE users SET gruppo = NULL, segnalato=0 WHERE username='".$username."'";
	 esegui($query,$mysqli); //tolgo il gruppo all'utente

	 $query = "UPDATE gruppi SET membri=membri-1 WHERE nome='".$row['nome']."'";
	 esegui($query,$mysqli); //decremento il numero di membri

	 aggiornaGruppo($row["nome"],$mysqli);
	 nuovoMsg();
	 header('location: ../panel-index.php?p=panel-inactive&m=Utente cacciato');
	 $mysqli->close();
	 die();
	}
?>