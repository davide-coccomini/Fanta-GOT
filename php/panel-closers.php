<?php
include("config.php");
include("utility.php");

if(!verificaAdmin($mysqli)){
	forzaLogout("");
}
controllaRank(2);
if(!isset($_GET["s"])){
	header('location: ../panel-index.php?p=panel-general&m=Errore generico');
	nuovoMsg();
	$mysqli->close();
	die();
}

if(isset($_GET["r"])){
	if($_GET["r"]==1 && $_GET["s"]==0){ //ufficializza/deufficializza chiusure (per calcolo penalità)
		$query="UPDATE aperture SET ufficiale=NOT ufficiale WHERE soggetto='mercato'";
		esegui($query,$mysqli);
		header('location: ../panel-index.php?p=panel-general&m=Operazione effettuata');
		nuovoMsg();
		$mysqli->close();
		die();
	}	
 header('location: ../panel-index.php?p=panel-general&m=Errore generico');
 nuovoMsg();
 $mysqli->close();
 die();
}

if($_GET["s"]==0) $s='sito';
else if($_GET["s"]==1) $s='registrazioni';
else if($_GET["s"]==2) $s='mercato';
else if($_GET["s"]==3) $s='schieramento';
else if($_GET["s"]==4) $s='Lannister';
else if($_GET["s"]==5) $s='Brivski';
else if($_GET["s"]==6) $s="Targaryen";
else $s="scommesse";
$query="SELECT status FROM aperture WHERE soggetto='".$s."' ORDER BY timestamp DESC LIMIT 1";
$row=estrai($query,$mysqli);
if(!$row){
	$status=0;
}else if($row["status"]==0){
	$status=1;
}else $status=0;

$query="INSERT INTO aperture(soggetto,status,username) VALUES('".$s."',".$status.",'".$_COOKIE['username']."')";
inviaLog("[ADMIN]Modifica status","soggetto:".$s."; status:".$status,$mysqli);
esegui($query,$mysqli);
header('location: ../panel-index.php?p=panel-general&m=Operazione completata');
nuovoMsg();
$mysqli->close();
die();
?>

