<?php
include("config.php");
include("utility.php");
if(!isset($_GET["action"]) || !isset($_GET["pg"]))
{
	header('location: /index/market');
	nuovoMsg($generalAlert82);
	$mysqli->close();
	die();
}else{
	$action=$_GET["action"];
	$id=filtra($_GET["pg"],$mysqli);
}

if($action==0){ //acquisto		
	$query = "SELECT status FROM aperture WHERE soggetto='mercato' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
	$row=estrai($query,$mysqli);
	if($row["status"]==1){
		inviaLog("[!]Tentativo di acquisto durante chiusura","",$mysqli);
		header('location: /index/market/'.$id);
		nuovoMsg($generalAlert83);
		$mysqli->close();
		die();
	}
	
	$query = "SELECT * FROM users WHERE username='".$_COOKIE['username']."'";
	$user=estrai($query,$mysqli);
	$query = "SELECT * FROM personaggi WHERE id=".$id;
	$pg=estrai($query,$mysqli);
	$nome=$pg["nome"];

		if($user["crediti"]<$pg["prezzo"])
		{
			header('location: /index/market/'.$id);
			nuovoMsg($generalAlert84);
			$mysqli->close();
			die();
		}
		if($user["personaggiAcquistati"]>=$maxPersonaggiAcquistabili)
		{
			header('location: /index/market');
			nuovoMsg($generalAlert85);
			$mysqli->close();
			die();
		}
	 $query="SELECT COUNT(*) as numero FROM personaggiacquistati WHERE username='".$_COOKIE['username']."' AND personaggio=".$id;
	 $row=estrai($query,$mysqli);
	 if($row["numero"]>0){
	 	header('location: /index/market');
	 	nuovoMsg($generalAlert86);
		$mysqli->close();
		die();
	 }
	 $query="UPDATE users SET personaggiAcquistati=personaggiAcquistati+1,crediti=crediti-".$pg['prezzo']." WHERE username='".$_COOKIE['username']."'";
	 esegui($query,$mysqli);
	 $personaggiAcquistati=$_COOKIE["personaggiAcquistati"]+1;
	 $crediti=$_COOKIE["crediti"]-$pg['prezzo'];
	 setcookie("personaggiAcquistati", $personaggiAcquistati , time() + (86400 * 30), "/");
	 setcookie("crediti", $crediti , time() + (86400 * 30), "/");
	 

     $query="SELECT COUNT(*) as numero FROM aperture WHERE soggetto='mercato' AND ufficiale=1";
	 $numero=estrai($query,$mysqli); //controllo che sia stato già chiuso il mercato ufficialmente una volta
	   if($numero["numero"]==0){ 
		 	 $query="INSERT INTO personaggiacquistati(username,personaggio) VALUES('".$_COOKIE['username']."',".$pg['id'].")";
	 		 esegui($query,$mysqli);
		 }else{ //C'è già stata una chiusura
		 	$query="SELECT COUNT(*) as numero FROM personaggivendutischierati WHERE username='".$_COOKIE['username']."' AND personaggio=".$id;
			$schierato=estrai($query,$mysqli);
			if($schierato["numero"]==0){ //sta acquistando un personaggio che non ha mai schierato in precedenza
				$query="INSERT INTO personaggiacquistati(username,personaggio,primo) VALUES('".$_COOKIE['username']."',".$pg['id'].",0)";
	 		 	esegui($query,$mysqli);
			}else{
				$query="INSERT INTO personaggiacquistati(username,personaggio,schierato,primo) VALUES('".$_COOKIE['username']."',".$pg['id'].",1,0)";
	 		    esegui($query,$mysqli);
	 		    $query="DELETE FROM personaggivendutischierati WHERE username='".$_COOKIE['username']."' AND personaggio=".$pg['id'];
	 		    esegui($query,$mysqli);
			}
		 	
		 }


	
	 $query="SELECT personaggio,COUNT(*) as numero FROM personagginonschierati WHERE username='".$_COOKIE['username']."'";
	 $row=estrai($query,$mysqli);
	 $messaggio="";
	 if($row["numero"]>0){
	 	$query="DELETE FROM personagginonschierati WHERE personaggio=".$id." AND username='".$_COOKIE['username']."'";
	 	esegui($query,$mysqli);
	 	$messaggio=$generalAlert89;
	 }
	 setcookie("personaggio".$id,1 , time() + (86400 * 30), "/");
	 if($personaggiAcquistati<$maxPersonaggiAcquistabili){
	 	 $mancanti=$maxPersonaggiAcquistabili-$personaggiAcquistati;
	 	 if($mancanti > 1)
	 	 	$text = $mancanti.$generalAlert118;
	 	 else
	 	 	$text = $mancanti.$generalAlert119;
	 	 header('location: /index/market/'.$id);
	 	 $testoMsg = $generalAlert90_1.$text.$generalAlert90_2.$messaggio;
	 }else{
	 	 header('location: /index/market/'.$id);
	 	 $testoMsg = $generalAlert91_1.$maxPersonaggiAcquistabili.$generalAlert91_2.$messaggio;
	 }
	inviaLog("acquisto",$nome,$mysqli);
	nuovoMsg($testoMsg);
	$mysqli->close();
	die();

  }else if($action==1){ //vendita
	$query = "SELECT status FROM aperture WHERE soggetto='mercato' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
	$row=estrai($query,$mysqli);

	if($row["status"]==1){
		inviaLog("[!]Tentativo di vendita durante chiusura","",$mysqli);
		nuovoMsg();
		header('location: /index/market/'.$id);
		$mysqli->close();
		die();
	}
	$query="SELECT COUNT(*) as numero FROM personaggiacquistati WHERE username='".$_COOKIE['username']."' AND personaggio=".$id;
	 $row=estrai($query,$mysqli);
	 if($row["numero"]==0){
	 	nuovoMsg($generalAlert87);
	 	header('location: /index/market?');
		$mysqli->close();
		die();
	 }
	 $query = "SELECT * FROM personaggi WHERE id=".$id;
	 $pg=estrai($query,$mysqli);
	 $query="SELECT S.acquisto FROM personaggiacquistati PA INNER JOIN schieramenti S ON S.acquisto=PA.id WHERE S.username='".$_COOKIE['username']."'";
            $result=$mysqli->query($query);
            while($row=$result->fetch_assoc()){
            	setcookie("schierato".$row['acquisto'],1,time()-1, "/");
            }
	 $query="DELETE FROM schieramenti WHERE username='".$_COOKIE['username']."'";
	 esegui($query,$mysqli);
	 setcookie("personaggiSchierati",0, time() + (86400 * 30), "/"); 
	 $query="UPDATE users SET personaggiAcquistati=personaggiAcquistati-1,personaggischierati=0,crediti=crediti+".$pg['prezzo']." WHERE username='".$_COOKIE['username']."'";
	 esegui($query,$mysqli);
	 $query="SELECT schierato FROM personaggiacquistati WHERE username='".$_COOKIE['username']."' AND personaggio=".$id;
	 $row=estrai($query,$mysqli);
	 $messaggio="";
	 if($row["schierato"]==0){ //controllo che il personaggio sia stato schierato
	 	$query="SELECT COUNT(*) as numero FROM aperture WHERE soggetto='mercato' AND ufficiale=1";
	 	$row=estrai($query,$mysqli); //controllo che sia stato già chiuso il mercato ufficialmente una volta
	 	if($row["numero"]>0){ //se è stato chiuso almeno una volta in modo ufficiale allora vuol dire che sta vendendo un personaggio prima di averlo schierato
	 		$query="SELECT primo,schierato FROM personaggiacquistati WHERE username='".$_COOKIE['username']."' AND personaggio=".$id;
	 		$riga=estrai($query,$mysqli);
	 		if($riga["primo"]==1 && $riga["schierato"]==0)
	 		{
	 			$query="INSERT INTO personagginonschierati(username,personaggio) VALUES('".$_COOKIE['username']."',".$id.')';
		 		esegui($query,$mysqli);
		 		$messaggio=$generalAlert92;
	 		}	
	 	}
	 }else{
	 	$query="INSERT INTO personaggivendutischierati(username,personaggio) VALUES('".$_COOKIE['username']."',".$id.")";
	 	esegui($query,$mysqli);
	 }
	 $query="DELETE FROM personaggiacquistati WHERE username='".$_COOKIE['username']."' AND personaggio=".$id;
	 esegui($query,$mysqli);

	 $personaggiAcquistati=$_COOKIE["personaggiAcquistati"]-1;
	 $crediti=$_COOKIE["crediti"]+$pg['prezzo'];
	 setcookie("personaggiAcquistati", $personaggiAcquistati , time() + (86400 * 30), "/");
	 setcookie("crediti", $crediti , time() + (86400 * 30), "/");
	 setcookie("personaggio".$id,1 , time() -1, "/");
	 	 $mancanti=$maxPersonaggiAcquistabili-$personaggiAcquistati;
	 if($mancanti > 1)
	  	$text = $mancanti.$generalAlert118;
	 else
	 	$text = $mancanti.$generalAlert119;
	header('location: /index/market/'.$id);
	inviaLog("vendita",$pg["nome"],$mysqli);
	$testoMsg = $generalAlert88_1.$text.$generalAlert88_2.'<br>'.$messaggio;
	nuovoMsg($testoMsg);
	$mysqli->close();
	die();
  }


?>