<?php

include("config.php");
include("utility.php");

if(!verificaAdmin($mysqli)){
    forzaLogout("");
}
controllaRank(3);


if(!isset($_GET["action"])){
		header('location: ../panel-index.php?p=panel-characters&m=Azione imprevista');
		nuovoMsg();
		$mysqli->close();
		die();
}
if($_GET["action"]!=1){ //controlli necessari per inserimento e aggiornamento
 if(!isset($_POST["nome"]) || !isset($_POST["prezzo"])){
	header('location: ../panel-index.php?p=panel-characters&m=Il nome e il prezzo non possono essere vuoti');
	nuovoMsg();
	$mysqli->close();
	die();
 }else{
	$nome = filtra($_POST["nome"],$mysqli);
	$prezzo = filtra($_POST["prezzo"],$mysqli);
	 if($prezzo<0 || $prezzo>300){
		header('location: ../panel-index.php?p=panel-characters&m=Prezzo invalido');
		nuovoMsg();
		$mysqli->close();
		die();
	 }
 }
}
if($_GET["action"]==0) //aggiornamento
{

	if(!isset($_GET["id"])){
		header('location: ../panel-index.php?p=panel-characters&m=Personaggio invalido');
		nuovoMsg();
		$mysqli->close();
		die();
	}

	$id = filtra($_GET["id"],$mysqli);

	if (isset($_FILES['userfile']) && is_uploaded_file($_FILES['userfile']['tmp_name'])) {
     $uploaddir = '../img/personaggi/';
     $userfile_tmp = $_FILES['userfile']['tmp_name'];
     $userfile_name = $_FILES['userfile']['name'];
     $exp=explode(".",$_FILES["userfile"]["name"]);
	 $extension = end($exp);
		if (($_FILES["userfile"]["size"] > 5242880)|| $extension !="jpg"){
			header('location: ../panel-index.php?p=panel-characters&m=Formato invalido (deve essere .jpg) o file troppo grande');
			nuovoMsg();
			$mysqli->close();
			die();
		}
	
	 $filename = strtolower($nome).".jpg";
	 $file = $_FILES['userfile'];
     if($file['error'] == UPLOAD_ERR_OK and is_uploaded_file($file['tmp_name']))
     {
        move_uploaded_file($file['tmp_name'], $uploaddir.$filename);
	 }else{
        header('location: ../panel-index.php?p=panel-characters&m=Errore nel caricamento del file');
		nuovoMsg();
		$mysqli->close();
		die();
	 }
	 
	}
	$query = "SELECT prezzo FROM personaggi WHERE id=".$id;
	$row=estrai($query,$mysqli);
	if($row["prezzo"]!=$prezzo){

		$diff = $row["prezzo"] - $prezzo;
		if($diff<0){
		 $query = "UPDATE users SET crediti = 500, personaggiAcquistati=-2, personaggiSchierati=0 WHERE username IN (SELECT username FROM personaggiacquistati WHERE personaggio=".$id.")";
		 esegui($query,$mysqli); // restituisco la differenza o la tolgo a chi ha acquistato il personaggio
		 $query="DELETE FROM schieramenti WHERE username IN (SELECT username FROM personaggiacquistati WHERE personaggio=".$id.")";
		 esegui($query,$mysqli);
		 $targetQuery = "SELECT username FROM personaggiacquistati WHERE personaggio=".$id;
		 $result=$mysqli->query($targetQuery);
		 while($row=$result->fetch_assoc()){
		 	$query="DELETE FROM personaggiacquistati WHERE username ='".$row['username']."'";
		 	esegui($query,$mysqli);
		 }
		}else{
		 $query = "UPDATE users SET crediti = crediti + ".$diff." WHERE username IN (SELECT username FROM personaggiacquistati WHERE personaggio=".$id.")";
		 esegui($query,$mysqli); // restituisco la differenza o la tolgo a chi ha acquistato il personaggio
		}

	}
	if(isset($filename))
	 $query = "UPDATE personaggi SET nome='".$nome."', prezzo=".$prezzo.", percorso='".$filename."' WHERE id=".$id;
	else
	 $query = "UPDATE personaggi SET nome='".$nome."', prezzo=".$prezzo." WHERE id=".$id;
	
	esegui($query,$mysqli);
	header('location: ../panel-index.php?p=panel-characters&m=Personaggio aggiornato');
	nuovoMsg();
	$mysqli->close();
	die();
}else if($_GET["action"]==1){ //eliminazione
	if(!isset($_GET["id"])){
		header('location: ../panel-index.php?p=panel-characters&m=Personaggio invalido');
		nuovoMsg();
		$mysqli->close();
		die();
	}

	$id = filtra($_GET["id"],$mysqli);
    $query = "SELECT status FROM aperture WHERE soggetto='mercato' ORDER BY timestamp DESC LIMIT 1";
    $row=estrai($query,$mysqli);
    $status=$row["status"];
	if($status==1){
		header('location: ../panel-index.php?p=panel-characters&m=Non è possibile cancellare un personaggio quando il mercato è chiuso');
		nuovoMsg();
		$mysqli->close();
		die();
	}
	$query = "SELECT prezzo FROM personaggi WHERE id=".$id;
	$row = estrai($query,$mysqli); // estraggo il prezzo del personagio
	
	$query = "UPDATE users SET crediti=crediti+".$row['prezzo'].",personaggiAcquistati=personaggiAcquistati-1 WHERE username IN (SELECT username FROM personaggiacquistati WHERE personaggio=".$id.")";
	esegui($query,$mysqli); //restituisco i crediti a chi lo ha comprato 

	$query = "UPDATE users SET personaggiSchierati = personaggiSchierati-1 WHERE username IN (SELECT S.username FROM schieramenti S INNER JOIN personaggiacquistati P ON P.id=S.acquisto AND P.personaggio=".$id.")";
	esegui($query,$mysqli); // decremento il contatore dei personaggi schierati per chi ha schierato il personaggio
	$query = "DELETE FROM schieramenti WHERE acquisto IN (SELECT id FROM personaggiacquistati WHERE personaggio=".$id.")";
	esegui($query,$mysqli); // rimuovo gli schieramenti relativi a quel personaggio

	$query = "DELETE FROM personaggiacquistati WHERE personaggio =".$id;
	esegui($query,$mysqli); // rimuovo gli acquisti relativi a quel personaggio

	$query = "DELETE FROM regolepersonaggio WHERE id=".$id;
	esegui($query,$mysqli);

	$query = "DELETE FROM personaggi WHERE id=".$id;
	esegui($query,$mysqli);
	header('location: ../panel-index.php?p=panel-characters&m=Personaggio eliminato con successo');
	nuovoMsg();
	$mysqli->close();
	die();

}else if($_GET["action"]==2){ //inserimento
	if (!isset($_FILES['userfile']) || !is_uploaded_file($_FILES['userfile']['tmp_name'])) {
		 header('location: ../panel-index.php?p=panel-characters&m=Per creare un nuovo personaggio è necessario assegnargli una immagine');
		 nuovoMsg();
		 $mysqli->close();
		 die();
	}
	 $uploaddir = '../img/personaggi/';
     $userfile_tmp = $_FILES['userfile']['tmp_name'];
     $userfile_name = $_FILES['userfile']['name'];
     $exp=explode(".",$_FILES["userfile"]["name"]);
	 $extension = end($exp);
		if (($_FILES["userfile"]["size"] > 5242880)|| $extension !="jpg"){
			header('location: ../panel-index.php?p=panel-characters&m=Formato invalido (deve essere .jpg) o file troppo grande');
			nuovoMsg();
			$mysqli->close();
			die();
		}
	
	 $filename = strtolower($nome).".jpg";
	 $file = $_FILES['userfile'];
     if($file['error'] == UPLOAD_ERR_OK and is_uploaded_file($file['tmp_name']))
     {
        move_uploaded_file($file['tmp_name'], $uploaddir.$filename);
	 }else{
        header('location: ../panel-index.php?p=panel-characters&m=Errore nel caricamento del file');
		nuovoMsg();
		$mysqli->close();
		die();
	 }
	 $query = "INSERT INTO personaggi (nome,prezzo,percorso) VALUES('".$nome."',".$prezzo.",'".$filename."')";
	 esegui($query,$mysqli);
	 header('location: ../panel-index.php?p=panel-characters&m=Personaggio creato con successo');
	 nuovoMsg();
	 $mysqli->close();
	 die();
}


?>