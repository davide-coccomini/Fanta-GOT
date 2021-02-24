<?php
include("config.php");
include("utility.php");
	if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}
	if(!isset($_GET["action"])){
		forzaLogout("");
	}
	if(!isset($_GET["l"])){
		$language = $lingue[0];
	}else{
		$language = filtra($_GET["l"],$mysqli);
	}
	if($language == "EN"){
		$etichetta1 = "Episode's bets";
		$etichetta2 = "VERIFIED";
		$etichetta3 = "UNVERIFIED";
	}else if($language == "IT"){
		$etichetta1 = "Scommesse episodio";
		$etichetta2 = "VERIFICATO";
		$etichetta3 = "NON VERIFICATO";
	}
if($_GET["action"]==0){ //inserisci una nuova scommessa
	if(!isset($_POST["descrizione"]) || !isset($_POST["pv"]) || !isset($_POST["pnv"])){
		forzaLogout("");
	}
	if(!isset($_GET["ep"])){
		forzaLogout("");
	}
 $ep=filtra($_GET["ep"],$mysqli);
 $descrizione=filtra($_POST["descrizione"],$mysqli);
 $pv=filtra($_POST["pv"],$mysqli);
 $pnv=filtra($_POST["pnv"],$mysqli);

 if($pv > 50 || $pv<0){
 	header('location: ../panel-index.php?p=panel-bets&ep='.$ep.'&l='.$language.'&m=Punteggio verificato non valido (da 0 a +50)');
	nuovoMsg("");
	$mysqli->close();
	die();
 }
 if($pnv < -50 || $pnv>0){
 	header('location: ../panel-index.php?p=panel-bets&ep='.$ep.'&l='.$language.'&m=Punteggio non verificato non valido (da -50 a 0)');
	nuovoMsg("");
	$mysqli->close();
	die();
 }
 $query="INSERT INTO scommesse(descrizione,punteggioVerificato,punteggioNonVerificato,episodio,lingua) VALUES('".$descrizione."',".$pv.",".$pnv.",".$ep.",'".$language."')";

 esegui($query,$mysqli);
 $query = "SELECT MAX(id) as id FROM scommesse";
 $row = estrai($query,$mysqli);
 $id = $row["id"];
 $associazione = $id;

 if(!is_numeric($_POST["associazione"])) { // E' associata a se stessa
 	$associazione = $row["id"];
 }else{
 	$associazione = filtra($_POST["associazione"],$mysqli);
 }

 $query = "UPDATE scommesse SET associazione=".$associazione." WHERE id=".$id;
 esegui($query,$mysqli);
 header('location: ../panel-index.php?p=panel-bets&ep='.$ep.'&l='.$language.'&m=Scommessa inserita');
 nuovoMsg("");
 $mysqli->close();
 die();
}else if($_GET["action"]==1){ //rimuovi una scommessa
	if(!isset($_GET["ep"]) || !isset($_GET["id"])){
		forzaLogout("");
	}
$ep=filtra($_GET["ep"],$mysqli);
$id=filtra($_GET["id"],$mysqli);
$query="DELETE FROM scommesse WHERE id=".$id;
esegui($query,$mysqli);
$query="DELETE FROM scommesseeffettuate WHERE scommessa=".$id;
esegui($query,$mysqli);
header('location: ../panel-index.php?p=panel-bets&ep='.$ep.'&l='.$language.'&m=Scommessa eliminata');
nuovoMsg("");
$mysqli->close();
die();
}else if($_GET["action"]==2){ // verifica/non verifica una scommessa
	if(!isset($_GET["ep"]) || !isset($_GET["id"]) || !isset($_GET["s"])){
		forzaLogout("");
	}
$ep=filtra($_GET["ep"],$mysqli);
$id=filtra($_GET["id"],$mysqli);
$status=filtra($_GET["s"],$mysqli);
$query="SELECT associazione FROM scommesse WHERE id=".$id;
$row=estrai($query,$mysqli);
$query="UPDATE scommesse SET verificato=".$status." WHERE associazione=".$row['associazione'];
esegui($query,$mysqli);
header('location: ../panel-index.php?p=panel-bets&ep='.$ep.'&l='.$language.'&m=Operazione effettuata');
nuovoMsg("");
$mysqli->close();
die();
}else if($_GET["action"]==3){ //applica punteggi
	controllaRank(3);
	if(!isset($_GET["ep"])){
		forzaLogout("");
	}
	$ep=filtra($_GET["ep"],$mysqli);
	$query="SELECT * FROM scommesseeffettuate SE INNER JOIN scommesse S ON S.id=SE.scommessa AND S.verificato>=0 GROUP BY SE.username, S.lingua";
	$result=$mysqli->query($query);
  	while($row=$result->fetch_assoc()){ //per ogni utente selezionato
  		$query="DELETE FROM punteggi WHERE tipologia='scommessa' AND username='".$row['username']."' AND episodio=".$ep; //ELIMINO DOPPIONI
	  	esegui($query,$mysqli);
	  	$query="SELECT S.verificato,S.punteggioVerificato,S.punteggioNonVerificato FROM scommesseeffettuate SE INNER JOIN scommesse S ON S.id=SE.scommessa WHERE S.verificato IS NOT NULL AND SE.username='".$row['username']."' AND SE.episodio=".$ep; //Seleziono le informazioni relative ai punteggi
	  	$r=estrai($query,$mysqli);

	  	if($r["verificato"]==0){
	  		$punteggio=$r["punteggioNonVerificato"];
	  	}else{
	  		$punteggio=$r["punteggioVerificato"];
	  	}
	  	$query="INSERT INTO punteggi(episodio,punteggio,username,tipologia) VALUES(".$ep.",".$punteggio.",'".$row['username']."','scommessa')"; //LO INSERISCO NEL DATABASE
	  	esegui($query,$mysqli);

  		$query="SELECT SE.username,SUM(S.punteggioVerificato) as punteggiPositivi FROM scommesseeffettuate SE INNER JOIN scommesse S ON S.id=SE.scommessa WHERE S.verificato=1 AND SE.username='".$row['username']."'";	
		$rowP=estrai($query,$mysqli);

  		$query="SELECT  SE.username,SUM(S.punteggioNonVerificato) as punteggiNegativi FROM scommesseeffettuate SE INNER JOIN scommesse S ON S.id=SE.scommessa WHERE S.verificato=0 AND SE.username='".$row['username']."'";
		$rowN=estrai($query,$mysqli);
		  
  		$punteggioScommesse=$rowP["punteggiPositivi"]+$rowN["punteggiNegativi"]; //+ perché il secondo è negativo
  		$query="SELECT * FROM users WHERE username='".$row['username']."'";
  		$r=estrai($query,$mysqli); 
  		$punteggioNetto=$r["punteggio"]-$r["penalita"]+$punteggioScommesse;
  		$query="UPDATE users SET punteggioScommesse=".$punteggioScommesse.", punteggioNetto=".$punteggioNetto." WHERE username='".$row['username']."'";
		esegui($query,$mysqli);
  		aggiornaPosizione($mysqli,$episodioCorrente);
  		if(isset($r["gruppo"])){
  			aggiornaGruppo($r["gruppo"],$mysqli);
  		}
  	}
  header('location: ../panel-index.php?p=panel-bets&ep=1&l='.$language.'&m=Punteggi applicati');
  nuovoMsg("");
  $mysqli->close();
  die();
}else if($_GET["action"]==4){ // Concatena documento
	if(!isset($_GET["ep"])){
		forzaLogout("");
	}
	$ep=filtra($_GET["ep"],$mysqli);
	$doc="<br><h2>".$etichetta1." ".$ep."</h2><hr style='width:100%'><br>";

	$query="SELECT * FROM scommesse WHERE episodio=".$ep." AND lingua='".$language."' AND verificato IS NOT NULL ORDER BY id";
	$result=$mysqli->query($query);


  	while($row=$result->fetch_assoc()){	
		if($row["verificato"]==0){
			$status = $etichetta3;
		}else{
			$status = $etichetta2;
		}
  	  $scommessa = "- ".$row['descrizione'].": <b>".$status."</b><br>";
  	  $doc=$doc.$scommessa;
  	}

	$query="SELECT contenuto FROM docs WHERE tipo='punteggio' AND lingua ='".$language."'ORDER BY timestamp DESC LIMIT 1";
  	$row=estrai($query,$mysqli);
  	$doc=$doc."<br>".$row['contenuto'];
  $doc = $doc."<hr id='bets".$ep.".'>";
  $doc=filtra($doc,$mysqli);
  $query="INSERT INTO docs(contenuto,username,tipo,lingua) VALUES('".$doc."','".$_COOKIE['username']."','punteggio','".$language."')";
  esegui($query,$mysqli);
  header('location: ../panel-index.php?p=panel-bets&ep='.$ep.'&l='.$language.'&m=Documento generato con successo');
  nuovoMsg("");
  $mysqli->close();
  die();
}else if($_GET["action"]==5){ // inserisci o aggiorna scommessa stagionale
	
	if(!isset($_GET["ep"])){
		forzaLogout("");
	}
	$ep=filtra($_GET["ep"],$mysqli);

	if(!isset($_POST["descrizione"]) || !isset($_POST["punteggio"]) || !isset($_POST["opzioni"])){
		header('location: ../panel-index.php?p=panel-bets&ep='.$ep.'&l='.$language.'&m=Form invalido');
	    nuovoMsg("");
	    $mysqli->close();
	    die();
	}
	$id = 0;
	$descrizione = filtra($_POST["descrizione"],$mysqli);
	$punteggio = filtra($_POST["punteggio"],$mysqli);
	$opzioni = filtra($_POST["opzioni"],$mysqli);
	if($opzioni<2){
		header('location: ../panel-index.php?p=panel-bets&ep='.$ep.'&l='.$language.'&m=Devono esserci almeno 2 opzioni');
	    nuovoMsg("");
	    $mysqli->close();
	    die();
	}
	$query = "SELECT id,COUNT(*) as numero FROM scommessestagionali WHERE lingua='".$language."'";
	$row = estrai($query,$mysqli);
    
	if($row["numero"]>0){ // aggiorna
		$idScommessa = $row["id"];
		$query = "UPDATE scommessestagionali SET descrizione='".$descrizione."',punteggio=".$punteggio." WHERE id=".$idScommessa;
		esegui($query,$mysqli);
		echo $query;
	}else{ // inserisci
		$query = "INSERT INTO scommessestagionali(descrizione,punteggio,lingua) VALUES('".$descrizione."',".$punteggio.",'".$language."')";
		esegui($query,$mysqli);
		$query = "SELECT MAX(id) as id FROM scommessestagionali WHERE lingua = '".$language."'";
		$row = estrai($query,$mysqli);
		$idScommessa = $row["id"];
		echo "a";
	}
 	// la gestione delle opzioni rimane inviarata
	
	for($i=1; $i<=$opzioni; $i++){
		$opzione = filtra($_POST["opzione".$i],$mysqli);
		$associazione = filtra($_POST["associazione".$i],$mysqli);
		if(isset($_POST["id".$i])){
		    $id = filtra($_POST["id".$i],$mysqli);
			$query = "SELECT COUNT(*) as numero FROM opzioniscommessestagionali WHERE id=".$id;
			$row = estrai($query,$mysqli);
			$numero = $row["numero"];
		}else{
		 	$numero = 0;
		}
		
		if($opzione != ""){ // Opzione da inserire o da aggiornare
			if($numero>0){ // Opzione già esistente
				$query = "UPDATE opzioniscommessestagionali SET opzione='".$opzione."', associazione='".$associazione."' WHERE id=".$id;
				esegui($query,$mysqli);
			}else{ // Opzione non esistente
				if($associazione != ""){ // Associazione già fornita dall'utente
					$query = "INSERT INTO opzioniscommessestagionali(opzione,scommessa,associazione) VALUES('".$opzione."',".$idScommessa.",".$associazione.")";
					esegui($query,$mysqli);
				}else{ // Associazione mancante, sarà generata dopo l'inserimento
					$query = "INSERT INTO opzioniscommessestagionali(opzione,scommessa) VALUES('".$opzione."',".$idScommessa.")";
					esegui($query,$mysqli);	
					$associazione = mysqli_insert_id($mysqli);
					$query = "UPDATE opzioniscommessestagionali SET associazione=".$associazione." WHERE id=".$associazione;
					esegui($query,$mysqli);
				}
			}
		}else{ // Opzione esistente e da eliminare
			$query = "DELETE FROM opzioniscommessestagionali WHERE id=".$id;
			esegui($query,$mysqli);
		}
	}
	
	header('location: ../panel-index.php?p=panel-bets&ep='.$ep.'&l='.$language.'&m=Operazione effettuata');
	nuovoMsg("");
	$mysqli->close();
	die();
}else if($_GET["action"]==6){ // elimina scommessa stagionale
	if(!isset($_GET["ep"])){
		forzaLogout("");
	}

	$ep=filtra($_GET["ep"],$mysqli);
	if(!isset($_GET["id"])){
		header('location: ../panel-index.php?p=panel-bets&ep='.$ep.'&l='.$language.'&m=Scommessa invalida');
		nuovoMsg("");
		$mysqli->close();
		die();
	}
	$id = $_GET["id"];
	$query = "DELETE FROM scommessestagionali WHERE id=".$id;
	esegui($query,$mysqli);
	$query = "DELETE FROM opzioniscommessestagionali WHERE scommessa=".$id;
	esegui($query,$mysqli);
	$query = "UPDATE users SET opzioneScommessaStagionale WHERE opzioneScommessaStagionale IN (SELECT id FROM opzioniscommessestagionali WHERE scommessa = ".$id.")";
	esegui($query,$mysqli);
	
	header('location: ../panel-index.php?p=panel-bets&ep='.$ep.'&l='.$language.'&m=Operazione effettuata');
	nuovoMsg("");
	$mysqli->close();
	die();
}else if($_GET["action"]==7){ // Aggiorna opzione corretta/scorretta
	if(!isset($_GET["ep"])){
		forzaLogout("");
	}
	$ep=filtra($_GET["ep"],$mysqli);
	if(!isset($_POST["opzioni"])){
		header('location: ../panel-index.php?p=panel-bets&ep='.$ep.'&l='.$language.'&m=Form invalido');
	    nuovoMsg("");
	    $mysqli->close();
	    die();
	}
	$opzioni = filtra($_POST["opzioni"],$mysqli);

	for($i=1; $i<=$opzioni; $i++){
		$corretta = filtra($_POST["corretta".$i],$mysqli);
		$id = filtra($_POST["id".$i],$mysqli);
		if($corretta!=1 && $corretta!=0){
			$corretta = "NULL";
		}

		$query = "UPDATE opzioniscommessestagionali SET corretta=".$corretta." WHERE id=".$id;
		esegui($query,$mysqli);
	}
	header('location: ../panel-index.php?p=panel-bets&ep='.$ep.'&l='.$language.'&m=Operazione effettuata');
	nuovoMsg("");
	$mysqli->close();
	die();
}else if($_GET["action"]==8){ // applica scommessa stagionale
	controllaRank(3);
	if(!isset($_GET["ep"])){
		forzaLogout("");
	}
	$ep=filtra($_GET["ep"],$mysqli);
	if($episodioCorrente<$totEpisodi){
		header('location: ../panel-index.php?p=panel-bets&ep='.$ep.'&l='.$language.'&m=Non puoi compiere questa azione in questo momento');
		nuovoMsg("");
		$mysqli->close();
		die();
	}

	$query = "SELECT *,S.punteggio as punteggioScommessa FROM (opzioniscommessestagionali O INNER JOIN scommessestagionali S ON S.id=O.scommessa) INNER JOIN users U ON U.opzioneScommessaStagionale=O.id";
	$result=$mysqli->query($query);
	while($row=$result->fetch_assoc()){
		if($row["corretta"]==1){
			$query = "UPDATE users SET punteggioNetto = punteggioNetto + ".$row['punteggioScommessa']." WHERE username='".$row['username']."'";
			esegui($query,$mysqli);
		}
	}
	header('location: ../panel-index.php?p=panel-bets&ep='.$ep.'&l='.$language.'&m=Operazione effettuata');
	nuovoMsg("");
	$mysqli->close();
	die();
}
