<?php
include("config.php");
include("utility.php");



if(!isset($_GET["action"]))
{
	header('location: /index/bets');
	nuovoMsg($generalAlert1);
	$mysqli->close();
	die();
}

$query="SELECT status FROM aperture WHERE soggetto='schieramento' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
$riga=estrai($query,$mysqli);
$status=$riga["status"];

  if($status==1){
		header('location: /index/bets');
		nuovoMsg($generalAlert2);
	    $mysqli->close();
		die();
   }


if($_GET["action"]<2){ // controlli per le scommesse per episodio
	
	   if(!isset($_GET["id"])){
			header('location: /index/bets');
			nuovoMsg($generalAlert3);
			$mysqli->close();
			die();
		}

	$id=filtra($_GET["id"],$mysqli);

	$query="SELECT *,COUNT(*) as numero FROM scommesse WHERE id='".$id."'";
	$row=estrai($query,$mysqli);

		if($row["numero"]==0 || $row["episodio"]!=$episodioCorrente){
			header('location: /index/bets');
			nuovoMsg($generalAlert3);
			$mysqli->close();
			die();
		}
}
if($_GET["action"]==0){ //scommetti

$query="SELECT COUNT(*) as numero FROM scommesseeffettuate WHERE episodio=".$episodioCorrente." AND username='".$_COOKIE['username']."'";
$riga=estrai($query,$mysqli);
	if($riga["numero"]>0){
		header('location: /index/bets');
		nuovoMsg($generalAlert4);
		$mysqli->close();
		die();
	}

$query="SELECT COUNT(*) as numero FROM scommesse WHERE episodio=".$episodioCorrente." AND id=".$id." AND  verificato is null";
$rig=estrai($query,$mysqli);

	if($rig["numero"]==0){
		header('location: /index/bets');
		nuovoMsg($generalAlert5);
		$mysqli->close();
		die();
	}

$query="INSERT INTO scommesseeffettuate(scommessa,episodio,username) VALUES(".$row['associazione'].",".$episodioCorrente.",'".$_COOKIE['username']."')";
esegui($query,$mysqli);
$query="UPDATE users SET scommessaEffettuata=1 WHERE username='".$_COOKIE['username']."'";
esegui($query,$mysqli);
inviaLog("scommette",$row["descrizione"],$mysqli);

header('location: /index/bets');
nuovoMsg($generalAlert6);
$mysqli->close();
die();
}else if($_GET["action"]==1){ //annulla scommessa
$query="SELECT COUNT(*) as numero FROM scommesse WHERE episodio=".$episodioCorrente." AND id=".$id." AND  verificato is null";
$row=estrai($query,$mysqli);

	if($row["numero"]==0){
		header('location: /index/bets');
		nuovoMsg($generalAlert7);
		$mysqli->close();
		die();
	}

$query="DELETE FROM scommesseeffettuate WHERE episodio=".$episodioCorrente." AND username='".$_COOKIE['username']."'";
esegui($query,$mysqli);
inviaLog("annulla scommessa",$id,$mysqli);
$query="UPDATE users SET scommessaEffettuata=0 WHERE username='".$_COOKIE['username']."'";
esegui($query,$mysqli);
header('location: /index/bets');
nuovoMsg($generalAlert8);
$mysqli->close();
die();
}else if($_GET["action"]==2){ // scommetti su scommessa stagionale

	if(!isset($_POST["opzione"]) || $_POST["opzione"]=="NULL"){
		header('location: /index/bets');
		nuovoMsg($generalAlert3);
		$mysqli->close();
		die();
	}
	if($episodioCorrente>1){
		header('location: /index/bets');
		nuovoMsg($generalAlert156);
		$mysqli->close();
		die();
	}

	$opzione = filtra($_POST["opzione"],$mysqli);
	if($opzione!="NULL"){
		$query = "SELECT associazione FROM opzioniscommessestagionali WHERE id=".$opzione;
		$row = estrai($query,$mysqli);
		$associazione = $row["associazione"];
		$query = "UPDATE users SET opzioneScommessaStagionale=".$associazione." WHERE username='".$_COOKIE['username']."'";
		esegui($query,$mysqli);
		setcookie("opzioneScommessaStagionale", $associazione, time() + (86400 * 30), "/");
	}
	header('location: /index/bets');
	nuovoMsg($generalAlert6);
	$mysqli->close();
	die();
}else if($_GET["action"]==3){ // annulla scommessa stagionale
	$query = "UPDATE users SET opzioneScommessaStagionale=NULL WHERE username='".$_COOKIE['username']."'";
	esegui($query,$mysqli);
	aggiornaInformazioni($mysqli,$personaggiDisponibili,$utentiDaInvitare);
	header('location: /index/bets');
	nuovoMsg($generalAlert8);	
	$mysqli->close();
	die();
}
