<?php
include("config.php");
include("utility.php");
	if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}
	controllaRank(3);
	
	if(!isset($_GET["r"])){
		header('location: ../panel-index.php?p=panel-ban&m=Errore generico');
		nuovoMsg();
		$mysqli->close();
		die();
	}
	if($_GET["r"]==0){ //mercato

		$query="SELECT PA.username FROM personaggi P INNER JOIN personaggiacquistati PA ON PA.personaggio=P.id GROUP BY PA.username";
		$result=$mysqli->query($query);
	    while($row=$result->fetch_assoc())
	    {
	    	$query="UPDATE users SET personaggiAcquistati=-1, personaggiSchierati=0, crediti=".$creditiIniziali." WHERE username='".$row['username']."'";
	    	esegui($query,$mysqli);
	    }
	   $query="DELETE FROM schieramenti";
	   esegui($query,$mysqli);
	   $query="DELETE FROM personaggiacquistati";
	   esegui($query,$mysqli);
	   	header('location: ../panel-index.php?p=panel-general&m=Acquisti resettati');
	   	inviaLog("[ADMIN]Reset","Acquisti",$mysqli);
		nuovoMsg();
		$mysqli->close();
		die();
	}else if($_GET["r"]==1){ //schieramento
		$query="UPDATE users SET personaggiSchierati=-1";
		esegui($query,$mysqli);
		inviaLog("[ADMIN]Reset","Schieramenti",$mysqli);
		$query="DELETE FROM schieramenti";
        esegui($query,$mysqli);
		header('location: ../panel-index.php?p=panel-general&m=Schieramenti resettati');
		nuovoMsg();
		$mysqli->close();
		die();

	}else if($_GET["r"]==2){ //scommesse effettuate
		$query="DELETE FROM scommesseeffettuate";
		esegui($query,$mysqli);
		$query="UPDATE users SET scommessaEffettuata=0";
		esegui($query,$mysqli);
		inviaLog("[ADMIN]Reset","Scommesse",$mysqli);
		header('location: ../panel-index.php?p=panel-general&m=Scommesse resettate');
		nuovoMsg();
		$mysqli->close();
		die();
	}else if($_GET["r"]==3){ //season
		$query = "UPDATE config SET episodioCorrente=1";
		esegui($query,$mysqli);

		$query = "UPDATE users SET titolo = NULL, gruppo = NULL, personaggiAcquistati = -1, personaggiSchierati = 0, punteggio = 0, punteggioNetto = 0, penalita = 0, punteggioSettimanale = 0, punteggioArena=0, punteggioScommesse=0, scommessaEffettuata = 0, opzioneScommessaStagionale=NULL, crediti=".$creditiIniziali;
		esegui($query,$mysqli);
		
		$query = "DELETE FROM titoliassegnati";
		esegui($query,$mysqli);

		$query = "UPDATE users SET donatore=0 WHERE donatore=2";
		esegui($query,$mysqli);

		$query = "DELETE FROM gruppi";
		esegui($query,$mysqli);

		$query = "DELETE FROM scommesseeffettuate";
		esegui($query,$mysqli);

		$query = "DELETE FROM scommesse";
		esegui($query,$mysqli);

		$query = "DELETE FROM scommessestagionali";
		esegui($query,$mysqli);

		$query = "DELETE FROM opzioniScommesseStagionali";
		esegui($query,$mysqli);

		$query = "DELETE FROM punteggipersonaggi";
		esegui($query,$mysqli);

		$query = "DELETE FROM regolepersonaggi";
		esegui($query,$mysqli);

		$query = "DELETE FROM sfide";
		esegui($query,$mysqli);

		$query = "DELETE FROM dettaglipunteggi";
		esegui($query,$mysqli);

		$query = "DELETE FROM punteggi";
		esegui($query,$mysqli);

		$query = "DELETE FROM personaggiacquistati";
		esegui($query,$mysqli);

		$query = "DELETE FROM personagginonschierati";
		esegui($query,$mysqli);

		$query = "DELETE FROM personaggivendutischierati";
		esegui($query,$mysqli);

		$query = "DELETE FROM docs WHERE tipo='punteggio'";
		esegui($query,$mysqli);

		$query = "UPDATE personaggi SET punteggio=0";
		esegui($query,$mysqli);


		
		aggiornaPosizione($mysqli,$episodioCorrente);
		inviaLog("[ADMIN]Reset","Season",$mysqli);
		header('location: ../panel-index.php?p=panel-general&m=Season resettata');
		nuovoMsg();
		$mysqli->close();
		die();
	}
?>