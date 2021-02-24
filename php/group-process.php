<?php
include("config.php");
include("utility.php");
if(!isset($_GET["action"]))
{
	header('location: /index/market');
    nuovoMsg($generalAlert1);
	$mysqli->close();
	die();
}

if(controllaChiusuraUfficiale($mysqli)){
	if($_GET["action"]!=6){
		header('location: /index/market');
	    nuovoMsg($generalAlert41);
		$mysqli->close();
		die();
	}
}
if($_GET["action"]==0){ //CREAZIONE GRUPPO
	if(isset($_POST["nome"]))
    {
    	setcookie("formNomeGruppo",$_POST["nome"],time() + 60, "/");
    }
    if(isset($_POST["motto"]))
    {
    	setcookie("formMottoGruppo",$_POST["motto"],time() + 60, "/");
    }
    if(isset($_POST["clan"])){
    	if($_POST["clan"]=="Lannister") 
    		setcookie("formClanGruppo",0,time() + 60, "/");
    	else if($_POST["clan"]=="Stark")
    		setcookie("formClanGruppo",1,time() + 60, "/"); 
    	else
    		setcookie("formClanGruppo",2,time() + 60, "/");
    }
	
	if(!isset($_POST["clan"]) || !isset($_POST["nome"]) || !isset($_POST["password"]) || !isset($_POST["motto"])){
		if(isset($_POST["clan"])){
			if($_POST["clan"]=="White Walkers")
				header('location: /index/group-secondary-creation');
			else
				header('location: /index/group-creation');
		}else{
			header('location: /index/group-creation');
		}
		
	    nuovoMsg($generalAlert42);
		$mysqli->close();
		die();
	}
	if(isset($_COOKIE["gruppo"])){
		header('location: /index/group-creation');
	    nuovoMsg($generalAlert43);
		$mysqli->close();
		die();
	}
	$clan=filtra($_POST["clan"],$mysqli);
	$nome=filtra($_POST["nome"],$mysqli);
	$password=filtra($_POST["password"],$mysqli);
	$motto=filtra($_POST["motto"],$mysqli);

	if(!controllaAperturaClan($clan,$mysqli)){
		header('location: /index/group-creation');
	    nuovoMsg($generalAlert44);
		$mysqli->close();
		die();
	}
	if(strlen($nome)<4 || strlen($nome)>16)
	{
		if($clan == "White Walkers")
			header('location: /index/group-secondary-creation');
		else
			header('location: /index/group-creation');
	    nuovoMsg($generalAlert45);
		$mysqli->close();
		die();
	}
	if(strlen($password)<4 || strlen($password)>20)
	{
		if($clan == "White Walkers")
			header('location: /index/group-secondary-creation');
		else
			header('location: /index/group-creation');
	    nuovoMsg($generalAlert46);
		$mysqli->close();
		die();
	}
    if(strlen($motto)<6 || strlen($motto)>40)
	{
		if($clan == "White Walkers")
			header('location: /index/group-secondary-creation');
		else
			header('location: /index/group-creation');
	    nuovoMsg($generalAlert47);
		$mysqli->close();
		die();
	}
	$query="SELECT COUNT(*) as numero FROM gruppi WHERE nome='".$nome."'";
	$row=estrai($query,$mysqli);
	if($row["numero"]!=0){
		if($clan == "White Walkers")
			header('location: /index/group-secondary-creation');
		else
			header('location: /index/group-creation');
	    nuovoMsg($generalAlert48);
		$mysqli->close();
		die();
	}
	if(controllo_caratteri($nome)){
		if($clan == "White Walkers")
			header('location: /index/group-secondary-creation');
		else
			header('location: /index/group-creation');
	    nuovoMsg($generalAlert49);
		$mysqli->close();
		die();
	}
	if(controllo_caratteri($motto)){
		if($clan == "White Walkers")
			header('location: /index/group-secondary-creation');
		else
			header('location: /index/group-creation');
	    nuovoMsg($generalAlert50);
		$mysqli->close();
		die();
	}
	if(controllo_caratteri($password)){
		if($clan == "White Walkers")
			header('location: /index/group-secondary-creation');
		else
			header('location: /index/group-creation');
	    nuovoMsg($generalAlert51);
		$mysqli->close();
		die();
	}
	
	$query="SELECT COUNT(*) as numero FROM gruppi WHERE motto='".$motto."'";
	$row=estrai($query,$mysqli);
	if($row["numero"]!=0){
		if($clan == "White Walkers")
			header('location: /index/group-secondary-creation');
		else
			header('location: /index/group-creation');
	    nuovoMsg($generalAlert52);
		$mysqli->close();
		die();
	}

	$query="INSERT INTO gruppi(nome,motto,clan,capo,password,mediaPunteggi) VALUES('".$nome."','".$motto."','".$clan."','".$_COOKIE['username']."','".$password."',".$_COOKIE['punteggioNetto'].")";
	esegui($query,$mysqli);
	$query="UPDATE users SET gruppo='".$nome."' WHERE username='".$_COOKIE['username']."'";
	esegui($query,$mysqli);
	setcookie("gruppo",$nome, time() + (86400 * 30), "/");
	setcookie("clan",$clan, time() + (86400 * 30), "/");
	aggiornaGruppo($gruppo,$mysqli);
	inviaLog("creazione gruppo",$nome,$mysqli);
	header('location: /index/group-page');
    nuovoMsg($generalAlert53);
	$mysqli->close();
	die();
}else if($_GET["action"]==1){ //scioglimento gruppo
	if(!isset($_COOKIE["gruppo"])){
		header('location: /index/group-creation');
	    nuovoMsg($generalAlert54);
		$mysqli->close();
		die();
	}
	$query="SELECT COUNT(*) as numero FROM gruppi WHERE nome='".$_COOKIE['gruppo']."'";
	$row=estrai($query,$mysqli);

	if($row["numero"]==0){
		header('location: /index/group-creation');
	    nuovoMsg($generalAlert75);
		$mysqli->close();
		die();
	}
 $query = "SELECT * FROM users WHERE gruppo = '".$_COOKIE['gruppo']."'";
 $result=$mysqli->query($query);
	 while($row=$result->fetch_assoc()){
		 notifica($row["username"], 0, "/index/group-join", $_COOKIE["clan"], $mysqli);
	 }
 inviaLog("scioglimento gruppo",$_COOKIE["gruppo"],$mysqli);
 $query = "DELETE FROM messaggi WHERE gruppo='".$_COOKIE['gruppo']."'";
 esegui($query,$mysqli);
 $query = "DELETE FROM titoliassegnati WHERE assegnato='".$_COOKIE['gruppo']."' AND tipo='gruppo'";
 esegui($query,$mysqli);
 $query="DELETE FROM gruppi WHERE nome='".$_COOKIE['gruppo']."'";
 esegui($query,$mysqli);
 $query="UPDATE users SET gruppo=NULL WHERE gruppo='".$_COOKIE['gruppo']."'";
 esegui($query,$mysqli);
 setcookie("gruppo", '', time()-1000);
 setcookie("gruppo", '', time()-1000, '/');
 setcookie("clan", '', time()-1000);
 setcookie("clan", '', time()-1000, '/');
 header('location: /index/group-creation');
 nuovoMsg($generalAlert55);
 $mysqli->close();
 die();
}else if($_GET["action"]==2){ //join
	if(!isset($_POST["gruppo"]) || !isset($_POST["password"])){
		 header('location: /index/group-join');
		 nuovoMsg();
		 $mysqli->close($generalAlert15);
		 die();
	}
	if(isset($_COOKIE["gruppo"]) || isset($_COOKIE["clan"])){
		header('location: /index/market');
		 nuovoMsg($generalAlert40);
		 $mysqli->close();
		 die();
	}
	$gruppo=filtra($_POST["gruppo"],$mysqli);
	$password=filtra($_POST["password"],$mysqli);
	$query="SELECT clan,membri,COUNT(*) as numero FROM gruppi WHERE password='".$password."' AND nome='".$gruppo."'";
	$row=estrai($query,$mysqli);
	$clan=$row["clan"];
	if($row["numero"]==0){
		header('location: /index/group-join');
	    nuovoMsg($generalAlert57);
		$mysqli->close();
		die();
	}else{
		if($row["membri"]>=10 && $clan != "White Walkers"){
			header('location: /index/group-join');
		    nuovoMsg($generalAlert58);
			$mysqli->close();
			die();
		}
		$query = "SELECT * FROM users WHERE gruppo = '".$_COOKIE['gruppo']."'";
		$result=$mysqli->query($query);
			while($row=$result->fetch_assoc()){ // Notifica ai membri che qualcuno è entrato nel gruppo
				notifica($row["username"], 3, "/index/group-page", $_COOKIE["clan"], $mysqli);
			}

		$query="UPDATE users SET gruppo='".$gruppo."' WHERE username='".$_COOKIE['username']."'";
		esegui($query,$mysqli);
		$query = "SELECT COUNT(*) as numero FROM users WHERE gruppo='".$gruppo."'";
		$row = estrai($query,$mysqli);
		$membri = $row["numero"];
		$query="UPDATE gruppi SET membri=".$membri." WHERE nome='".$gruppo."'";
		esegui($query,$mysqli);
		aggiornaGruppo($gruppo,$mysqli);
		setcookie("gruppo",$gruppo, time() + (86400 * 30), "/");
		setcookie("clan",$clan, time() + (86400 * 30), "/");
		inviaLog("ingresso gruppo",$gruppo,$mysqli);
		header('location: /index/group-page');
	    nuovoMsg($generalAlert59.$gruppo."!");
		$mysqli->close();
		die();
	} 
}else if($_GET["action"]==3){ //uscita dal gruppo
	if(!isset($_COOKIE["gruppo"])){
		header('location: /index/group-join');
	    nuovoMsg($generalAlert60);
		$mysqli->close();
		die();
	}
	inviaLog("uscita gruppo",$_COOKIE['gruppo'],$mysqli);
	$query="UPDATE users SET gruppo=NULL WHERE username='".$_COOKIE['username']."'";
	esegui($query,$mysqli);
	$query = "SELECT COUNT(*) as numero FROM users WHERE gruppo='".$gruppo."'";
	$row = estrai($query,$mysqli);
	$membri = $row["numero"];
	$query="UPDATE gruppi SET membri=".$membri." WHERE nome='".$gruppo."'";
	esegui($query,$mysqli);
	aggiornaGruppo($_COOKIE["gruppo"],$mysqli);
	setcookie("gruppo", '', time()-1000);
    setcookie("gruppo", '', time()-1000, '/');
    setcookie("clan", '', time()-1000);
	setcookie("clan", '', time()-1000, '/');
	
	$query = "SELECT * FROM users WHERE gruppo = '".$_COOKIE['gruppo']."'";
	$result=$mysqli->query($query);
		while($row=$result->fetch_assoc()){ // Notifica ai membri che qualcuno è uscito dal gruppo
			notifica($row["username"], 4, "/index/group-page", $_COOKIE["clan"], $mysqli);
		}

    header('location: /index/group-join');
    nuovoMsg($generalAlert61);
	$mysqli->close();
	die();
}else if($_GET["action"]==4){ //kick
	if(!isset($_COOKIE["gruppo"])){
		header('location: /index/group-join');
	    nuovoMsg($generalAlert60);
		$mysqli->close();
		die();
	}
	if(!isset($_GET["u"])){
		header('location: /index/group-page');
	    nuovoMsg($generalAlert62);
		$mysqli->close();
		die();
	}
	$query="SELECT capo FROM gruppi WHERE nome='".$_COOKIE['gruppo']."'";
	$row=estrai($query,$mysqli);
	if($row["capo"]!=$_COOKIE["username"]){
		header('location: /index/group-page');
	    nuovoMsg($generalAlert66);
		$mysqli->close();
		die();
	}
	$username=filtra($_GET["u"],$mysqli);
	$query="SELECT COUNT(*) as numero FROM users WHERE gruppo='".$_COOKIE['gruppo']."' AND username='".$username."'";
	$row=estrai($query,$mysqli);
	if($row["numero"]==0){
		header('location: /index/group-page');
	    nuovoMsg($generalAlert64);
		$mysqli->close();
		die();
	}
	$query="UPDATE users SET gruppo=NULL WHERE username='".$username."'";
	esegui($query,$mysqli);
	$query="SELECT * FROM users WHERE username='".$username."'";
	$row=estrai($query,$mysqli);
	$query = "SELECT COUNT(*) as numero FROM users WHERE gruppo='".$_COOKIE['gruppo']."'";
	$row = estrai($query,$mysqli);
	$membri = $row["numero"];
	$query="UPDATE gruppi SET membri=".$membri." WHERE nome='".$_COOKIE['gruppo']."'";
	esegui($query,$mysqli);
	aggiornaGruppo($_COOKIE["gruppo"],$mysqli);
	$query = "SELECT * FROM users WHERE gruppo = '".$_COOKIE['gruppo']."'";
	$result=$mysqli->query($query);
		while($row=$result->fetch_assoc()){ // Notifica ai membri che qualcuno è stato cacciato dal gruppo
			notifica($row["username"], 4, "/index/group-page", $_COOKIE["clan"], $mysqli);
		}
	notifica($username, 1, "/index/group-page", $_COOKIE["clan"], $mysqli); // Notifica al membro cacciato in merito al kick

	inviaLog("kick dal gruppo",$username,$mysqli);
	header('location: /index/group-page');
	nuovoMsg($generalAlert65);
	$mysqli->close();
	die();
}else if($_GET["action"]==5){ //segnalazione inattivo
	if(!isset($_GET["u"]))
	{
		header('location: /index/group-page');
	    nuovoMsg($generalAlert62);
		$mysqli->close();
		die();
	}
	$username = filtra($_GET["u"],$mysqli);

	$query="SELECT capo FROM gruppi WHERE nome='".$_COOKIE['gruppo']."'";
	$row=estrai($query,$mysqli);
	if($row["capo"]!=$_COOKIE["username"]){
		header('location: /index/group-page');
	    nuovoMsg($generalAlert66);
		$mysqli->close();
		die();
	}
	if($username==$_COOKIE['username']){
		header('location: /index/group-page');
	    nuovoMsg($generalAlert67);
		$mysqli->close();
		die();
	}
	$query="SELECT COUNT(*) as numero FROM users WHERE username='".$username."' AND gruppo='".$_COOKIE['gruppo']."'";
	$row = estrai($query,$mysqli);
	if($row["numero"]==0){
		header('location: /index/group-page');
	    nuovoMsg($generalAlert139);
		$mysqli->close();
		die();
	}
	$query="UPDATE users SET segnalato=NOT segnalato WHERE username='".$username."'";
	esegui($query,$mysqli);
	header('location: /index/group-page');
	nuovoMsg($generalAlert69);
	$mysqli->close();
	die();
}else if($_GET["action"]==6){ // Inserimento di un messaggio in bacheca
	if(!isset($_COOKIE["gruppo"])){
		header('location: /index/group-join');
	    nuovoMsg($generalAlert60);
		$mysqli->close();
		die();
	}

	if(!isset($_POST["testo"])){
		header('location: /index/group-board');
	    nuovoMsg($generalAlert70);
		$mysqli->close();
		die();
	}
	$testo = filtra($_POST["testo"],$mysqli);
	$query = "SELECT COUNT(*) as numero FROM messaggi WHERE username='".$_COOKIE['username']."' AND gruppo ='".$_COOKIE['gruppo']."'";
	$riga = estrai($query,$mysqli);
	$numero = $riga["numero"];
	$query = "SELECT TIMESTAMPDIFF(MINUTE,timestamp,CURRENT_TIMESTAMP+INTERVAL 6 HOUR) as diff FROM messaggi WHERE gruppo='".$_COOKIE['gruppo']."' AND username='".$_COOKIE['username']."' ORDER BY timestamp DESC";
	$row = estrai($query,$mysqli);

	if($row["diff"]<15 && $numero>0){
		header('location: /index/group-board');
	    nuovoMsg($generalAlert71);
		$mysqli->close();
		die();	
	}
	$query = "INSERT INTO messaggi(username,testo,gruppo,timestamp) VALUES('".$_COOKIE['username']."','".$testo."','".$_COOKIE['gruppo']."',CURRENT_TIMESTAMP + INTERVAL 6 HOUR)";
	esegui($query,$mysqli);
	$query = "SELECT * FROM users WHERE gruppo = '".$_COOKIE['gruppo']."' AND username <> '".$_COOKIE['username']."'";
	$result=$mysqli->query($query);
		while($row=$result->fetch_assoc()){ // Notifica ai membri che qualcuno ha scritto in bacheca
			notifica($row["username"], 2, "/index/group-join", $_COOKIE["clan"], $mysqli);
		}

	header('location: /index/group-board');
    nuovoMsg($generalAlert72);
	$mysqli->close();
	die();
}else if($_GET["action"]==7){ // Rimuovi un messaggio dalla bacheca
	if(!isset($_COOKIE["gruppo"])){
		header('location: /index/group-join');
	    nuovoMsg($generalAlert60);
		$mysqli->close();
		die();
	}

	if(!isset($_GET["id"])){
		header('location: /index/group-board');
	    nuovoMsg($generalAlert76);
		$mysqli->close();
		die();
	}
	$id = filtra($_GET["id"],$mysqli);
	$query = "SELECT capo FROM gruppi WHERE nome='".$_COOKIE['gruppo']."'";
	$row = estrai($query,$mysqli);
	$capo = $row["capo"];

	$query = "SELECT * FROM messaggi WHERE gruppo = '".$_COOKIE['gruppo']."' AND id=".$id;
	$row = estrai($query,$mysqli);

	if($row["username"]!=$_COOKIE["username"] && ($row["gruppo"]!=$_COOKIE["gruppo"] || $capo!=$_COOKIE["username"])){
		header('location: /index/group-board');
	    nuovoMsg($generalAlert73);
		$mysqli->close();
		die();
	}

	$query = "DELETE FROM messaggi WHERE id=".$id;
	esegui($query,$mysqli);
	header('location: /index/group-board');
	nuovoMsg($generalAlert74);
	$mysqli->close();
	die();
}