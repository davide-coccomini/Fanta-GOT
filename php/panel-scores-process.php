<?php
include("config.php");
include("utility.php");

if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}



if(!isset($_GET["action"])){
	header('location: ../panel-index.php?p=panel-scores&m=Azione imprevista');
	nuovoMsg();
	$mysqli->close();
	die();
}
if($_GET["action"]<2){
	if(!isset($_POST["pg"]) || !isset($_POST["ep"]) || !isset($_POST["punteggio"])){
		forzaLogout("");
	}
$pg=filtra($_POST["pg"],$mysqli);
$punteggio=$_POST["punteggio"];
$ep=filtra($_POST["ep"],$mysqli);
	if(!is_numeric($punteggio)){
		header('location: ../panel-index.php?p=panel-scores&ep='.$ep.'&m=Punteggio inserito non numerico');
		nuovoMsg();
		$mysqli->close();
		die();
	}
	
}else if($_GET["action"]<5){
	if(!isset($_GET["ep"])){
		forzaLogout("");
	}
	$ep=$_GET["ep"];
}

if($_GET["action"]==4 || $_GET["action"]==6){ //genera e concatena documento
	if(!isset($_GET["ep"])){
		forzaLogout("");
	}
	$ep=filtra($_GET["ep"],$mysqli);
	$doc="";
  foreach($lingue as $l){
    if($l == "IT"){
       $doc="<h2>Punteggi episodio ".$ep."</h2><hr style='width:100%'><br>";
       $tabella="<div class='table-responsive'><table id='tablerank' class='table sortable text-center tableranking'><thead><tr><th>Personaggio</th><th>Dettagli</th><th>Punteggio</th></tr></thead>";
    }else{
       $doc="<h2>Episode ".$ep." scores</h2><hr style='width:100%'><br>";
    	 $tabella="<div class='table-responsive'><table id='tablerank' class='table sortable text-center tableranking'><thead><tr><th>Character</th><th>Details</th><th>Score</th></tr></thead>";
		}
    $doc=$doc.$tabella;
    $query="SELECT *,PP.punteggio as pt FROM (punteggipersonaggi PP INNER JOIN dettaglipunteggi DP ON DP.idPunteggio=PP.id) INNER JOIN personaggi P ON P.id=PP.personaggio WHERE DP.lingua='".$l."' AND PP.episodio=".$ep." ORDER BY P.id";
		$result=$mysqli->query($query);
      	while($row=$result->fetch_assoc()){
      		$pg="<tr><td>".$row['nome']."</td><td>".$row['dettagli']."</td><td>".$row['pt']."</td></tr>";
      		$doc=$doc.$pg;
      	}
      	$doc=$doc."</table></div>";
      	if($_GET["action"]==6){
      		$query="SELECT contenuto FROM docs WHERE tipo='punteggio' AND lingua='".$l."' ORDER BY timestamp DESC LIMIT 1";
					$row=estrai($query,$mysqli);
					$doc=$doc."<br>".$row['contenuto'];
      	}

    $doc=filtra($doc,$mysqli);
    $query="INSERT INTO docs(contenuto,username,tipo,lingua) VALUES('".$doc."','".$_COOKIE['username']."','punteggio','".$l."')";
    esegui($query,$mysqli);
  }
header('location: ../panel-index.php?p=panel-scores&ep='.$ep.'&m=Documento generato con successo');
nuovoMsg();
$mysqli->close();
die();
}

if($_GET["action"]==5){ //Rimozione punti fine stagione
  controllaRank(3);
  if(!isset($_POST["punti"])){
  	header('location: ../panel-index.php?p=panel-general&m=Punteggio assente');
	nuovoMsg();
	$mysqli->close();
	die();
  }
  $punti=filtra($_POST["punti"],$mysqli);
  if(!is_numeric($punti)){
  	header('location: ../panel-index.php?p=panel-general&m=Punteggio non valido');
	nuovoMsg();
	$mysqli->close();
	die();
  }

  $query="SELECT username FROM users WHERE personaggiacquistati=8"; //considero i soli utenti che hanno completato la squadra
  $result=$mysqli->query($query);
  while($ri=$result->fetch_assoc()){	//per ogni utente
    $query="SELECT username,COUNT(*) as numero FROM personaggiacquistati WHERE schierato=0 AND username='".$ri['username']."'";
    $row=estrai($query,$mysqli); //seleziono tutti i personaggi che non sono stati schierati
  	$query="SELECT COUNT(*) as personaggiNS FROM personagginonschierati WHERE username='".$ri['username']."'";
  	$riga=estrai($query,$mysqli);//seleziono i personaggi che ha venduto prima di schierare
  	$puntiDaSottrarre=($riga["personaggiNS"]+$row["numero"])*$punti; //calcolo i punti da sottrarre
  	$query="SELECT * FROM users WHERE username='".$ri['username']."'";
  	$r=estrai($query,$mysqli);
  	$punteggioNetto=$r["punteggio"]-$puntiDaSottrarre+$r["punteggioScommesse"];
  	$query="UPDATE users SET penalita=".$puntiDaSottrarre.", punteggioNetto=".$punteggioNetto." WHERE username='".$ri['username']."'";
  	esegui($query,$mysqli);//inserisco la penalità 
  	if(isset($r["gruppo"])){
  		aggiornaGruppo($r["gruppo"],$mysqli);
  	}
  }
aggiornaPosizione($mysqli,$episodioCorrente);
header('location: ../panel-index.php?p=panel-general&m=Penalità inserita');
nuovoMsg();
$mysqli->close();
die();
}
if($_GET["action"]==0){ //inserisci
	controllaRank(2);
  $numeroRegole = filtra($_POST["numeroRegole"],$mysqli);
    for($i=1; $i<=$numeroRegole; $i++){ // inserisco le regole nel database
      $voce = filtra($_POST["voce".$i],$mysqli);
      $score = filtra($_POST["score".$i],$mysqli);
      $query = "SELECT id FROM vociregolamento WHERE nome ='".$voce."'";
      $row = estrai($query,$mysqli);
      $idRegola = $row["id"];
      $query = "INSERT INTO regolepersonaggi (voce,punteggio,episodio,personaggio) VALUES(".$idRegola.",".$score.",".$ep.",".$pg.")";
      esegui($query,$mysqli);
    }
    $query="INSERT INTO punteggipersonaggi(punteggio,personaggio,episodio) VALUES(".$punteggio.",".$pg.",".$ep.")";
    esegui($query,$mysqli);
    $query = "SELECT id FROM punteggipersonaggi WHERE personaggio=".$pg." AND episodio=".$ep;
    
    $row = estrai($query,$mysqli);
    $idPunteggio = $row["id"];
    foreach($lingue as $l){  //creazione dei dettagli
      $dettagli = "";
      $query = "SELECT * FROM regolepersonaggi RP INNER JOIN vociregolamento VR ON VR.associazione=RP.voce  WHERE RP.personaggio=".$pg." AND RP.episodio =".$ep." AND VR.lingua='".$l."'";
      $result=$mysqli->query($query);
     
      while($row=$result->fetch_assoc()){
        $dettagli = $dettagli."- ".$row['nome'].": ".$row['punteggio']."<br>";
			}
			
      $query = "INSERT INTO dettaglipunteggi(idPunteggio,dettagli,lingua) VALUES(".$idPunteggio.",'".$dettagli."','".$l."')";
			esegui($query,$mysqli);
    }

}else if($_GET["action"]==1){//aggiorna
	controllaRank(2);
  $query = "DELETE FROM regolepersonaggi WHERE personaggio = ".$pg." AND episodio = ".$ep;
  esegui($query,$mysqli);
  $numeroRegole = filtra($_POST["numeroRegole"],$mysqli);
  $dettagli = "";
  for($i=1; $i<=$numeroRegole; $i++){ // inserisco le regole nel database
    $voce = filtra($_POST["voce".$i],$mysqli);
    $score = filtra($_POST["score".$i],$mysqli);
    $query = "SELECT id FROM vociregolamento WHERE nome ='".$voce."'";
    $row = estrai($query,$mysqli);
    $idRegola = $row["id"];
    $query = "INSERT INTO regolepersonaggi (voce,punteggio,episodio,personaggio) VALUES(".$idRegola.",".$score.",".$ep.",".$pg.")";
    esegui($query,$mysqli);
  }

  $query="UPDATE punteggipersonaggi SET punteggio=".$punteggio." WHERE episodio=".$ep." AND personaggio=".$pg;
  esegui($query,$mysqli);

  $query = "SELECT id FROM punteggipersonaggi WHERE personaggio=".$pg." AND episodio=".$ep;  
  $row = estrai($query,$mysqli);
  $idPunteggio = $row["id"];
    foreach($lingue as $l){  //creazione dei dettagli
      $dettagli = "";
      $query = "SELECT * FROM regolepersonaggi RP INNER JOIN vociregolamento VR ON VR.associazione=RP.voce  WHERE RP.personaggio=".$pg." AND RP.episodio =".$ep." AND VR.lingua='".$l."'";
      $result=$mysqli->query($query);
		
      while($row=$result->fetch_assoc()){
        $dettagli = $dettagli."- ".$row['nome'].": ".$row['punteggio']."<br>";
			}
      $query = "UPDATE dettaglipunteggi SET dettagli='".$dettagli."' WHERE idPunteggio=".$idPunteggio." AND lingua='".$l."'";
      esegui($query,$mysqli);
    }
	
}else if($_GET["action"]==2){//resetta
	$query="SELECT COUNT(*) as numero FROM punteggi WHERE tipologia='mercato' AND episodio=".$ep;
	$row=estrai($query,$mysqli);
	if($row["numero"]!=0){
		header('location: ../panel-index.php?p=panel-scores&ep='.$ep.'&m=Non puoi effettuare un reset perché i punteggi sono stati già applicati da un amministratore');
		nuovoMsg();
		$mysqli->close();
		die();
	}
	
	$query = "DELETE FROM dettaglipunteggi WHERE idPunteggio IN (SELECT id FROM punteggipersonaggi WHERE episodio=".$ep.")";
	esegui($query,$mysqli);
	$query="DELETE FROM punteggipersonaggi WHERE episodio=".$ep;
	esegui($query,$mysqli);
  $query="DELETE FROM regolepersonaggi WHERE episodio=".$ep;
  esegui($query,$mysqli);
	inviaLog("[ADMIN]Reset punteggi","ep:".$ep,$mysqli);
	header('location: ../panel-index.php?p=panel-scores&ep='.$ep.'&m=Reset effettuato');
	nuovoMsg();
	$mysqli->close();
	die();
}else if($_GET["action"]==3){ //applica
  controllaRank(3);
  $query="SELECT COUNT(*) as numero FROM punteggipersonaggi WHERE episodio=".$ep;
  $row=estrai($query,$mysqli);
  if($row["numero"]<$personaggiDisponibili){
  	header('location: ../panel-index.php?p=panel-scores&ep='.$ep.'&m=Non hai ancora assegnato un punteggio a tutti i personaggi di questa puntata');
	  nuovoMsg();
	  $mysqli->close();
	  die();
	}
	
	// ARENA
	$query = "SELECT * FROM sfide WHERE status = 3"; // PER OGNI SFIDA IN CUI ENTRAMBI I PARTECIPANTI HANNO SCELTO LA REGOLA
	$res=$mysqli->query($query);
	while($riga=$res->fetch_assoc()){	
		// Controllo sfidante
		$query = "SELECT VR.punteggioMassimo, COUNT(*) as numero FROM regolepersonaggi RP INNER JOIN vociregolamento VR ON VR.associazione = RP.voce WHERE RP.episodio = ".$ep." AND RP.voce = ".$riga['regolaSfidante'];
		$r = estrai($query,$mysqli);
	
		if($r["numero"] > 0){
			$punteggio = round($r["punteggioMassimo"]/2);

			$query = "UPDATE users SET punteggio = punteggio + ".$punteggio.", punteggioNetto = punteggioNetto + ".$punteggio.", punteggioArena = punteggioArena + ".$punteggio." WHERE username = '".$riga['sfidante']."'";
			esegui($query,$mysqli); 	// Aggiungo i punti all'utente che ha azzeccato la regola
			notifica($riga["sfidante"], 9, "/index/arena", "arena", $mysqli, $punteggio); // Notifico allo sfidante che ha ottenuto dei punti dall'arena
		
			$query = "UPDATE users SET punteggio = punteggio - ".$punteggio.", punteggioNetto = punteggioNetto - ".$punteggio.", punteggioArena = punteggioArena - ".$punteggio." WHERE username = '".$riga['sfidato']."'";
			esegui($query,$mysqli); 	 // Tolgo il punteggio all'utente che ha perso
			notifica($riga["sfidato"], 11, "/index/arena", "arena", $mysqli, (0 - $punteggio)); // Notifico allo sfidato che ha perso dei punti dall'arena
		}else{
			notifica($riga["sfidato"], 12, "/index/arena", "arena", $mysqli);
			notifica($riga["sfidante"], 10, "/index/arena", "arena", $mysqli);
		}
		// Controllo sfidato
		$query = "SELECT VR.punteggioMassimo, COUNT(*) as numero FROM regolepersonaggi RP INNER JOIN vociregolamento VR ON VR.associazione = RP.voce WHERE RP.episodio = ".$ep." AND RP.voce = ".$riga['regolaSfidato'];
		$r = estrai($query,$mysqli);
		if($r["numero"] > 0){

			$punteggio = round($r["punteggioMassimo"]/2);

			$query = "UPDATE users SET punteggio = punteggio + ".$punteggio.", punteggioNetto = punteggioNetto + ".$punteggio.", punteggioArena = punteggioArena + ".$punteggio." WHERE username = '".$riga['sfidato']."'";
			esegui($query,$mysqli); 	// Aggiungo i punti all'utente che ha azzeccato la regola
			notifica($riga["sfidato"], 9, "/index/arena", "arena", $mysqli, $punteggio); // Notifico allo sfidato che ha ottenuto dei punti dall'arena

			$query = "UPDATE users SET punteggio = punteggio - ".$punteggio.", punteggioNetto = punteggioNetto - ".$punteggio.", punteggioArena = punteggioArena - ".$punteggio." WHERE username = '".$riga['sfidante']."'";
			esegui($query,$mysqli); 	 // Tolgo il punteggio all'utente che ha perso
			notifica($riga["sfidante"], 11, "/index/arena", "arena", $mysqli, (0 - $punteggio)); // Notifico allo sfidante che ha perso dei punti dall'arena
		}else{
			notifica($riga["sfidato"], 10, "/index/arena", "arena", $mysqli);
			notifica($riga["sfidante"], 12, "/index/arena", "arena", $mysqli);
		}
		$query = "UPDATE sfide SET status = 4 WHERE id=".$riga['id'];
		esegui($query,$mysqli);
	}
	$query = "DELETE FROM sfide WHERE status <> 4 OR status IS NULL";
	esegui($query,$mysqli);
  $query="SELECT * FROM users WHERE personaggiSchierati=".$maxPersonaggiSchierabili; //SELEZIONO TUTTI GLI UTENTI CHE HANNO SCHIERATO IL GIUSTO NUMERO DI PERSONAGGI
  $result=$mysqli->query($query);

  while($row=$result->fetch_assoc()){	 //PER OGNI UTENTE SELEZIONATO
  	$query="DELETE FROM punteggi WHERE username='".$row['username']."' AND tipologia='mercato' AND episodio=".$ep; //ELIMINO DOPPIONI
  	esegui($query,$mysqli);
  	$query="UPDATE personaggiacquistati SET schierato=1 WHERE username='".$row['username']."' AND id IN(SELECT acquisto FROM schieramenti WHERE username='".$row['username']."') ";
  	esegui($query,$mysqli);
  	$query="SELECT SUM(PP.punteggio) as somma FROM (schieramenti S INNER JOIN personaggiacquistati PA ON S.acquisto=PA.id)INNER JOIN punteggipersonaggi PP ON PP.personaggio=PA.personaggio WHERE S.username='".$row["username"]."' AND PP.episodio=".$ep." GROUP BY S.username"; //OTTENGO LA SOMMA DEI PUNTEGGI DEI PERSONAGGI SCHIERATI IN QUESTA PUNTATA
  	$riga=estrai($query,$mysqli);
  	$query="INSERT INTO punteggi(episodio,punteggio,username,tipologia) VALUES(".$ep.",".$riga['somma'].",'".$row['username']."','mercato')"; //LO INSERISCO NEL DATABASE
    esegui($query,$mysqli);
  	$query="SELECT SUM(punteggio) as totale FROM punteggi WHERE tipologia='mercato' AND username='".$row['username']."'"; //SELEZIONO LA SOMMA DI TUTTI I PUNTEGGI CHE HA FATTO L'UTENTE NELLE VARIE PUNTATE
  	$ris=estrai($query,$mysqli);
  	$punteggioNetto=$ris["totale"]+$row["punteggioScommesse"]-$row["penalita"];
  	$query="UPDATE users SET punteggio=".$ris['totale'].", punteggioNetto=".$punteggioNetto." WHERE username='".$row['username']."'";
		esegui($query,$mysqli); //LO AGGIORNO
		notifica($row["username"], 13, "/index/formation", "ranking", $mysqli, $riga['somma']);
 
		
	if(isset($row["gruppo"])){
  		aggiornaGruppo($row["gruppo"],$mysqli);		
  	}
  }	
	$query="SELECT personaggio,SUM(punteggio) as somma FROM punteggipersonaggi GROUP BY personaggio";
	$res=$mysqli->query($query);
	while($riga=$res->fetch_assoc()){	
		$query="UPDATE personaggi SET punteggio=".$riga['somma']."  WHERE id=".$riga['personaggio'];
		esegui($query,$mysqli);
	}
  aggiornaPosizione($mysqli,$episodioCorrente);
	inviaLog("[ADMIN]Applica punteggi","ep:".$ep,$mysqli);
	header('location: ../panel-index.php?p=panel-scores&ep='.$ep.'&m=Punteggi applicati');
	nuovoMsg();
	$mysqli->close();
	die();
}else if($_GET["action"] == 7){ // Metti 0 a tutti quelli che non hanno settato niente
	$ep=filtra($_GET["ep"],$mysqli);
	$query = "SELECT * FROM personaggi";
	$result=$mysqli->query($query);
	while($row=$result->fetch_assoc()){	
		$query = "SELECT COUNT(*) as numero FROM punteggipersonaggi WHERE personaggio = ".$row["id"]." AND episodio =".$ep;
		$riga = estrai($query,$mysqli);
		if($riga["numero"]==0){	
			$query = "INSERT INTO punteggipersonaggi(punteggio,personaggio,episodio) VALUES(0,".$row['id'].",".$ep.")";
			esegui($query,$mysqli);	
		}
	}
	header('location: ../panel-index.php?p=panel-scores&ep='.$ep.'&m=Personaggi autocompletati');
	nuovoMsg();
	$mysqli->close();
	die();
}

inviaLog("[ADMIN]Aggiorna punteggio","pg:".$pg."; punteggio:".$punteggio." ep:".$ep,$mysqli);
header('location: ../panel-index.php?p=panel-scores&pg='.$pg.'&ep='.$ep.'&m=Personaggio aggiornato');
nuovoMsg();
$mysqli->close();
die();
?>