<?php
include("config.php");
include("utility.php");

	if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}
	controllaRank(2);

if(!isset($_GET["action"])){ //inserisci un annuncio
	if(!isset($_POST["titolo"]) || !isset($_POST["titolo"])){
		header('location: ../panel-index.php?p=panel-announcements&m=Dati non pervenuti');
		$mysqli->close();
		die();
	}
	if(strlen($_POST["titolo"])<3 || strlen($_POST["titolo"])>30){
		header('location: ../panel-index.php?p=panel-announcements&m=Titolo invalido (3-30 caratteri)');
		$mysqli->close();
		die();
	}
	$lingua = filtra($_POST["lingua"],$mysqli);
	$query="UPDATE users SET annunciDaLeggere=1 WHERE lingua='".$lingua."'";
	esegui($query,$mysqli);
	$titolo=filtra($_POST["titolo"],$mysqli);
	$contenuto=filtra($_POST["contenuto"],$mysqli);
	$query="INSERT INTO annunci(titolo,contenuto,username,lingua) VALUES('".$titolo."','".$contenuto."','".$_COOKIE['username']."','".$lingua."')";
	inviaLog("[ADMIN]Pubblica un annuncio",$titolo,$mysqli);
	esegui($query,$mysqli);

	nuovoMsg("");
	header('location: ../panel-index.php?p=panel-announcements&m=Annuncio pubblicato&l='.$lingua);
	$mysqli->close();
	die();
}else if($_GET["action"]==0){ // elimina un annuncio
	if(!isset($_GET["id"])){
		nuovoMsg("");
		header('location: ../panel-index.php?p=panel-announcements&m=Questo annuncio non esiste');
		$mysqli->close();
		die();
	}
	$id=filtra($_GET["id"],$mysqli);
	$query="SELECT username FROM annunci WHERE id=".$id;
	$row=estrai($query,$mysqli);
	if($row["username"]!=$_COOKIE["username"] && $_COOKIE["admin"]<3){
		nuovoMsg("");
		header('location: ../panel-index.php?p=panel-announcements&m=Non hai i permessi necessari per rimuovere gli annunci degli altri amministratori');
		$mysqli->close();
		die();
	}
	$id=filtra($_GET["id"],$mysqli);
	$query="DELETE FROM annunci WHERE id=".$id;
	esegui($query,$mysqli);
	nuovoMsg("");
	header('location: ../panel-index.php?p=panel-announcements&m=Annuncio rimosso');
	$mysqli->close();
	die();
}else if($_GET["action"]==1){ //elimina tutti gli annunci
	controllaRank(3);
	$query="DELETE FROM annunci";
	esegui($query,$mysqli);
	nuovoMsg("");
	header('location: ../panel-index.php?p=panel-announcements&m=Tutti gli annunci sono stati rimossi');
	$mysqli->close();
	die();
}else if($_GET["action"]==2){ // Inserimento annunci automatico
	$annuncio = filtra($_GET["a"], $mysqli);
	$it = "";
	$en = "";
	switch ($annuncio){
		case 0: // Mercato chiuso
			$it = ["Chiusura mercato", "Il mercato è ufficialmente chiuso e riaprirà tra il terzo e il quarto episodio per il mercato di metà stagione. Quando sarà disponibile una data di riapertura, potrete visionarla cliccando sulla corrispondente etichetta nella sidebar. Ricordiamo che se durante il mercato si vende un personaggio mai schierato, si incorrerà in penalità."];
			$en = ["Market closing", "The market is officially closed and will re-open between the third and fourth episode for the mid-season market."];
			break;
		case 1: // Mercato di metà stagione aperto
			$it = ["Apertura mercato di metà stagione", "Il mercato di metà stagione è ufficialmente aperto. Quando sarà disponibile una data di riapertura, potrete visionarla cliccando sulla corrispondente etichetta nella sidebar. Ricordiamo che se durante il mercato si vende un personaggio mai schierato, si incorrerà in penalità."];
			$en = ["Mid-season market opening", "The mid-season market is officially open. When a re-opening date will be available, you can view it by clicking on the corresponding label in the sidebar. Remember, if you will sell a character never deployed, you will incur penalty. "];
			break;
		case 2: // Schieramento chiuso 
			$it = ["Chiusura schieramento", "Lo schieramento è ufficialmente chiuso."];
			$en = ["Deployment closing", "The deployment is officially closed."];
			break;
		case 3: // Schieramento aperto
			$it = ["Apertura schieramento", "Lo schieramento è nuovamente aperto e resterà tale fino alla data consultabile cliccando sulla corrispondente etichetta nella sidebar."];
			$en = ["Deployment opening", "The deployment is open again and will remain open until the date available by clicking on the corresponding label in the sidebar."];
			break;
		case 4: // Punteggi provvisori
			$it = ["Pubblicazione punteggi provvisori", "I punteggi provvisori sono stati pubblicati. Lasciamo alcune ore a disposizione per segnalare eventuali errori prima che vengano definitivamente applicati."];
			$en = ["Provisional scores publication", "Provisional scores have been published. We leave a few hours available to report any errors before they are finally applied."];
			break;
		case 5: // Applicazione punteggi
			$it = ["Applicazione punteggi", "I punteggi sono stati applicati, il titolo assegnato e a breve riapriremo lo schieramento."];
			$en = ["Scoring application", "The scores have been applied, the title assigned and we will shortly reopen the deployment."];
			break;
		default:
			header('location: ../panel-index.php?p=panel-announcements&m=Annuncio non disponibile');
			$mysqli->close();
			die();
	}

	$query="UPDATE users SET annunciDaLeggere=1";
	esegui($query,$mysqli);

	$query="INSERT INTO annunci(titolo,contenuto,lingua) VALUES('".$it[0]."','".$it[1]."','IT')";
	esegui($query,$mysqli);
	$query="INSERT INTO annunci(titolo,contenuto,lingua) VALUES('".$en[0]."','".$en[1]."','EN')";
	esegui($query,$mysqli);

	inviaLog("[ADMIN]Pubblica un annuncio globale","",$mysqli);
	header('location: ../panel-index.php?p=panel-announcements&m=Annuncio globale inviato');
	$mysqli->close();
	die();
}
?>