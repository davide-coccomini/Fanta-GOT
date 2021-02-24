<?php
include("config.php");
include("utility.php");
if(!verificaAdmin($mysqli)){
		forzaLogout("");
}

controllaRank(2);

if(!isset($_GET["action"]))
{
	header('location: ../panel-index.php?p=panel-rules-composer&m=Errore generico&l='.$language);
	nuovoMsg();
	$mysqli->close();
	die();
}
if(!isset($_GET["l"])){
	$language = $lingue[0];
}else{
	$language = filtra($_GET["l"],$mysqli);
}
if($_GET["action"]==0){
	if(!isset($_POST["nome"]) || !isset($_POST["min"]) || !isset($_POST["max"])){
		header('location: ../panel-index.php?p=panel-rules-composer&m=Form invalido&l='.$language);
		nuovoMsg();
		$mysqli->close();
		die();
	}

 $nome=filtra($_POST["nome"],$mysqli);
 if(isset($_POST["dettagli"]))
 	$dettagli=filtra($_POST["dettagli"],$mysqli);

 $min=filtra($_POST["min"],$mysqli);
 $max=filtra($_POST["max"],$mysqli);
 $associazione=filtra($_POST["associazione"],$mysqli);
 if($min>$max){
 	header('location: ../panel-index.php?p=panel-rules-composer&m=Il valore minimo non può essere maggiore del valore massimo&l='.$language);
	nuovoMsg();
	$mysqli->close();
	die();
 }
 if(isset($_POST["dettagli"]))
 	$query="INSERT INTO vociregolamento(nome,dettagli,punteggioMinimo,punteggioMassimo,lingua) VALUES('".$nome."','".$dettagli."','".$min."','".$max."','".$language."')";
 else
	$query="INSERT INTO vociregolamento(nome,punteggioMinimo,punteggioMassimo,lingua) VALUES('".$nome."','".$min."','".$max."','".$language."')";	
echo $query;
 esegui($query,$mysqli);

$query = "SELECT MAX(id) as massimo FROM vociregolamento";
$row = estrai($query,$mysqli);
$associazione = (!is_numeric($associazione))?$row["massimo"]:$associazione;
$id = $row["massimo"];
$query = "UPDATE vociregolamento SET associazione = ".$associazione." WHERE id=".$id;
esegui($query,$mysqli);


 header('location: ../panel-index.php?p=panel-rules-composer&m=Voce aggiunta&l='.$language);
 nuovoMsg();
 $mysqli->close();
 die();
}else if($_GET["action"]==1){
	if(!isset($_GET["id"])){
		header('location: ../panel-index.php?p=panel-rules-composer&m=Voce invalida&l='.$language);
		nuovoMsg();
		$mysqli->close();
		die();
	}
$id=filtra($_GET["id"],$mysqli);
$query="DELETE FROM vociregolamento WHERE id=".$id;
esegui($query,$mysqli);
header('location: ../panel-index.php?p=panel-rules-composer&m=Voce rimossa&l='.$language);
nuovoMsg();
$mysqli->close();
die();
}else if($_GET["action"]==2 || $_GET["action"]==3){ //aggiorna reg o concatena
	controllaRank(2);
	$regolamento="<center><h2>Regolamento punteggi</h2></center><hr style='width:95%'><br><br>";
	if($language == "EN"){
	   $tabella="<div class='table-responsive'><table id='tablerank' class='table sortable text-center tableranking'><thead><tr><th>Voice</th><th>Details</th><th>Minimum score</th><th>Maximum score</th></tr></thead>";
	}else if($language == "IT"){
	   $tabella="<div class='table-responsive'><table id='tablerank' class='table sortable text-center tableranking'><thead><tr><th>Voce</th><th>Dettagli</th><th>Punteggio minimo</th><th>Punteggio massimo</th></tr></thead>";
	}
	$regolamento=$regolamento.$tabella;
	$query="SELECT * FROM vociregolamento  WHERE lingua = '".$language."' ORDER BY punteggioMassimo DESC, punteggioMinimo ASC";
    $result=$mysqli->query($query);
	    while($row=$result->fetch_assoc())
	    {
	    	$voce="<tr class='rowtableranking2'><td>".$row['nome']."</td><td>".$row['dettagli']."</td><td>".$row['punteggioMinimo']."</td><td>".$row['punteggioMassimo']."</td></tr>";
	    	$regolamento=$regolamento.$voce;
	    
      	/*VERSIONE TESTUALE
	    	$voce="<b>".$row['nome']."</b>(MIN:".$row['punteggioMinimo']."; MAX:".$row['punteggioMassimo'].")<br>".$row['dettagli']."<br><br>";
	    	$regolamento=$regolamento.$voce;
	   */
	    }
	$regolamento=$regolamento."</table></div>";
	if($_GET["action"]==3){ //concatena
		$query="SELECT contenuto FROM docs WHERE tipo='regolamento' AND lingua ='".$language."' ORDER BY timestamp DESC LIMIT 1";
		$row=estrai($query,$mysqli);
		$regolamento=$row["contenuto"]."<br>".$regolamento;
	}
	
	$regolamento=filtra($regolamento,$mysqli);
	$query="INSERT INTO docs(username,tipo,contenuto,lingua) VALUES('".$_COOKIE['username']."','regolamento','".$regolamento."','".$language."')";
	inviaLog("genera regolamento","tramite rules composer",$mysqli);
	esegui($query,$mysqli);
	header('location: ../panel-index.php?p=panel-rules-composer&m=Regolamento generato, visita la pagina del regolamento per vederlo&l='.$language);
	$mysqli->close();
	die();
}else if($_GET["action"]==4){ // Imposta arena
	controllaRank(2);
	$id=filtra($_GET["id"],$mysqli);
	$query = "SELECT associazione FROM vociregolamento WHERE id=".$id;
	$row = estrai($query,$mysqli);
	$query = "UPDATE vociregolamento SET arena = !arena WHERE associazione=".$row['associazione'];
	esegui($query,$mysqli);
	header('location: ../panel-index.php?p=panel-rules-composer&m=Regola aggiornata&l='.$language);
	$mysqli->close();
	die();
}

?>