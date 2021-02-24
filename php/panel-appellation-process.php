<?php
include("config.php");
include("utility.php");
if(!verificaAdmin($mysqli)){
		forzaLogout("");
}

controllaRank(3);

if(!isset($_GET["action"]))
{
	header('location: ../panel-index.php?p=panel-appellation&m=Errore generico');
	nuovoMsg("");
	$mysqli->close();
	die();
}
if($_GET["action"]==0){ //inserisci titolo
	if(!isset($_POST["nome"]) || !isset($_POST["descrizione"])){
		header('location: ../panel-index.php?p=panel-appellation&m=Form invalido');
		nuovoMsg("");
		$mysqli->close();
		die();
	}
$nome=filtra($_POST["nome"],$mysqli);
$descrizione=filtra($_POST["descrizione"],$mysqli);
$associazione = filtra($_POST["associazione"],$mysqli);
$lingua = filtra($_POST["lingua"],$mysqli);
if(isset($associazione) && $associazione != ""){
	$query='INSERT INTO titoli(nome,descrizione,associazione,lingua) VALUES("'.$nome.'","'.$descrizione.'",'.$associazione.',"'.$lingua.'")';
	esegui($query,$mysqli);
}else{
	$query='INSERT INTO titoli(nome,descrizione,lingua) VALUES("'.$nome.'","'.$descrizione.'","'.$lingua.'")';
	esegui($query,$mysqli);
}
if(!isset($associazione) || $associazione == ""){
	$query = "SELECT MAX(id) as massimo FROM titoli";
	$row = estrai($query,$mysqli);
	$query = "UPDATE titoli SET associazione = ".$row['massimo']." WHERE id=".$row['massimo'];
	esegui($query,$mysqli);
}
	header('location: ../panel-index.php?p=panel-appellation&m=Titolo inserito&l='.$lingua);
	nuovoMsg("");
	$mysqli->close();
	die();
}else if($_GET["action"]==1){ //rimuovi titolo
	if(!isset($_GET["id"])){
		header('location: ../panel-index.php?p=panel-appellation&m=Titolo non valido');
		nuovoMsg("");
		$mysqli->close();
		die();
	}
$id=filtra($_GET["id"],$mysqli);

	$query="DELETE FROM titoli WHERE id=".$id;
	esegui($query,$mysqli);
	header('location: ../panel-index.php?p=panel-appellation&m=Titolo eliminato');
	nuovoMsg("");
	$mysqli->close();
	die();
}else if($_GET["action"]==2){ //assegna titolo
	if(!isset($_GET["g"])){ //assegna ad utente
		if(!isset($_POST["username"]) || !isset($_POST["tipo"])){
			header('location: ../panel-index.php?p=panel-appellation&m=Form incompleto');
			nuovoMsg("");
			$mysqli->close();
			die();
		}
	$username=filtra($_POST["username"],$mysqli);
	$id=filtra($_POST["tipo"],$mysqli);
	$query="SELECT COUNT(*) as numero FROM users WHERE username='".$username."'";

	$row=estrai($query,$mysqli);
	if($row["numero"]==0){
		header('location: ../panel-index.php?p=panel-appellation&m=Utente non trovato');
		nuovoMsg("");
		$mysqli->close();
		die();
	}
	$query="SELECT nome,associazione,COUNT(*) as numero FROM titoli WHERE id=".$id;
	$result=$mysqli->query($query);
	$row=$result->fetch_assoc();
	if($row["numero"]==0){
			header('location: ../panel-index.php?p=panel-appellation&m=Titolo non trovato');
			nuovoMsg("");
			$mysqli->close();
			die();
		}
		$titolo=$row["nome"];
		$associazione=$row["associazione"];
		$query="SELECT COUNT(*) as numero FROM titoliassegnati WHERE assegnato='".$username."' AND tipo='utente' AND titolo=".$associazione;
		$result=$mysqli->query($query);
		$row=$result->fetch_assoc();
		if($row["numero"]>0){
			header('location: ../panel-index.php?p=panel-appellation&m=Il giocatore possiede già questo titolo');
			nuovoMsg("");
			$mysqli->close();
			die();
		}
		$query="INSERT INTO titoliassegnati(titolo,assegnato,tipo) VALUES(".$associazione.",'".$username."','utente')";
		esegui($query,$mysqli);
		notifica($username, 14, "/index/ranking", "ranking", $mysqli);
		header('location: ../panel-index.php?p=panel-appellation&m=Titolo assegnato');
		nuovoMsg("");
		inviaLog("Assegna titolo","titolo:".$titolo."; user:".$username.";",$mysqli);
		$mysqli->close();
		die();
	}else{ // assegna a gruppo
		if(!isset($_POST["gruppo"]) || !isset($_POST["tipo"])){
			header('location: ../panel-index.php?p=panel-appellation&m=Form incompleto');
			nuovoMsg("");
			$mysqli->close();
			die();
		}
	$gruppo=filtra($_POST["gruppo"],$mysqli);
	$id=filtra($_POST["tipo"],$mysqli);
	$query="SELECT COUNT(*) as numero FROM gruppi WHERE nome='".$gruppo."'";
	$row=estrai($query,$mysqli);
	if($row["numero"]==0){
		header('location: ../panel-index.php?p=panel-appellation&m=Gruppo non trovato');
		nuovoMsg("");
		$mysqli->close();
		die();
	}
	$query="SELECT nome,associazione,COUNT(*) as numero FROM titoli WHERE id=".$id;
	$row=estrai($query,$mysqli);
	if($row["numero"]==0){
		header('location: ../panel-index.php?p=panel-appellation&m=Titolo non trovato');
		nuovoMsg("");
		$mysqli->close();
		die();
	}

	$titolo=$row["nome"];
	$associazione=$row["associazione"];
	$query="SELECT COUNT(*) as numero FROM titoliassegnati WHERE assegnato='".$gruppo."' AND tipo='gruppo' AND titolo=".$associazione;
	$row = estrai($query,$mysqli);

	if($row["numero"]>0){
		header('location: ../panel-index.php?p=panel-appellation&m=Il gruppo possiede già questo titolo');
		nuovoMsg("");
		$mysqli->close();
		die();
	}
	$query="INSERT INTO titoliassegnati(titolo,assegnato,tipo) VALUES(".$associazione.",'".$gruppo."','gruppo')";
	esegui($query,$mysqli);

	$query = "SELECT * FROM users WHERE gruppo = '".$gruppo."'";
	$result=$mysqli->query($query);
		while($row=$result->fetch_assoc()){ // Notifica ai membri che qualcuno ha scritto in bacheca
			notifica($row["username"], 15, "/index/ranking/g", $_COOKIE["clan"], $mysqli);
		}

	header('location: ../panel-index.php?p=panel-appellation&m=Titolo assegnato');
	nuovoMsg("");
	inviaLog("Assegna titolo","titolo:".$titolo."; gruppo:".$gruppo.";",$mysqli);
	$mysqli->close();
	die();
 }
}else if($_GET["action"]==3){//resetta titoli assegnati
	$query="UPDATE users SET titolo=NULL";
	esegui($query,$mysqli);
	$query="UPDATE gruppi SET titolo=NULL";
	esegui($query,$mysqli);
	$query = "DELETE FROM titoliassegnati";
	esegui($query,$mysqli);
	inviaLog("Resetta tutti i titoli assegnati","",$mysqli);
	header('location: ../panel-index.php?p=panel-appellation&m=Titoli assegnati resettati');
	nuovoMsg("");
	$mysqli->close();
	die();
}
?>