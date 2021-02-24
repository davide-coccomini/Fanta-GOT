<?php
session_start();
include("utility.php");
include("config.php");

	if(!isset($_POST["email"]) || !isset($_POST["password"])){
		header('location: /index/home');
		nuovoMsg($generalAlert9);
		$mysqli->close();
		die();
	}else{
		$email=filtra($_POST["email"],$mysqli);
		$query="SELECT COUNT(*) as numero FROM users_verification WHERE email='".$email."' AND status=0";
		$row=estrai($query,$mysqli);
		if($row["numero"]>0){
			header('location: /index/home');
			nuovoMsg($generalAlert77);
			$mysqli->close();
			die();
		}
		$password=filtra($_POST["password"],$mysqli);
		$password=sha1($password);
		$query = 'SELECT *,COUNT(*) as numero FROM users WHERE email="'.$email.'"';
        $row=estrai($query,$mysqli);
        if($row["numero"]==0){
        	header('location: /index/home');
			nuovoMsg($generalAlert78);
			$mysqli->close();
			die();
        }
        $salt = $row['salt'];
        $db_encrypted_password = $row['password'];
        if(!(verifyHash($password.$salt, $db_encrypted_password))){
			header('location: /index/home');
			nuovoMsg($generalAlert79);
			$mysqli->close();
			die();
        }


			$_SESSION["letto"]=0;
			setcookie("messaggi",1, time() + (86400 * 30), "/");
			setcookie("login",1, time() + (86400), "/");
			setcookie("id",$row["id"], time() + (86400 * 30), "/");
			setcookie("username",$row["username"], time() + (86400 * 30), "/");
			setcookie("email",$row["email"], time() + (86400 * 30), "/");
			setcookie("crediti",$row["crediti"], time() + (86400 * 30), "/");
			setcookie("gruppo",$row["gruppo"], time() + (86400 * 30), "/");
			setcookie("personaggiAcquistati",$row["personaggiAcquistati"], time() + (86400 * 30), "/");
			setcookie("personaggiSchierati",$row["personaggiSchierati"], time() + (86400 * 30), "/");
			setcookie("punteggio",$row["punteggio"], time() + (86400 * 30), "/");
			setcookie("punteggioNetto",$row["punteggioNetto"], time() + (86400 * 30), "/");
			setcookie("penalita",$row["penalita"], time() + (86400 * 30), "/");
			setcookie("admin",$row["admin"], time() + (86400), "/");
			setcookie("primoLogin",$row["primoLogin"], time() + (86400), "/");
			setcookie("posizione",$row["posizione"], time() + (86400), "/");
			setcookie("posizioneSettimanale",$row["posizioneSettimanale"], time() + (86400), "/");
			setcookie("punteggioSettimanale",$row["punteggioSettimanale"], time() + (86400), "/");
			setcookie("punteggioScommesse",$row["punteggioScommesse"], time() + (86400), "/");
			setcookie("punteggioArena",$row["punteggioArena"], time() + (86400), "/");
			setcookie("scommessaEffettuata",$row["scommessaEffettuata"], time() + (86400), "/");
			setcookie("opzioneScommessaStagionale",$row["opzioneScommessaStagionale"], time() + (86400), "/");
			setcookie("donatore",$row["donatore"], time() + (86400), "/");
			setcookie("annunciDaLeggere",$row["annunciDaLeggere"], time() + (86400 * 30), "/");
			$username=$row["username"];
			$primoLogin=$row["primoLogin"];
			$query="UPDATE users SET posizioneAggiornata=0 WHERE username='".$username."'";
			esegui($query,$mysqli);
			$query="SELECT * FROM gruppi G INNER JOIN users U ON U.gruppo=G.nome WHERE U.username='".$username."'";
			$row=estrai($query,$mysqli);
			setcookie("clan",$row["clan"], time() + (86400 * 30), "/");
			/*AGGIORNO GLI UTENTI INVITATI VERIFICATI*/
				$query = "SELECT COUNT(*) as numero FROM users WHERE invitante = '".$username."' AND punteggioInvitante = 1";
				$row = estrai($query,$mysqli);
				setcookie("invitatiVerificati",$row["numero"], time() + (86400*30), "/");
				if($row["numero"]>=$utentiDaInvitare){
					if($_COOKIE["donatore"]==0){
						setcookie("donazioneDisponibile",1, time() + (86400 * 30), "/");
					}	
				}
		    /*AGGIORNO GLI ACQUISTI*/
			for($i=1;$i<$personaggiDisponibili;$i++) //resetto tutti i cookies dei personaggi
			{
			 $name="personaggio".$i;
				if(isset($_COOKIE[$name])){
					setcookie($name, '', time()-1000);
					setcookie($name, '', time()-1000, '/');
				}
			}
			$query="SELECT * FROM personaggiacquistati WHERE username='".$username."'";
			$result=$mysqli->query($query);
			while($row=$result->fetch_assoc()){
				$name="personaggio".$row['personaggio'];
			    setcookie($name,1 , time() + (86400 * 30), "/");

			}

			/*AGGIORNO SCHIERAMENTI*/
			$query="SELECT P.id FROM personaggiacquistati P WHERE P.username='".$username."' AND P.id NOT IN(SELECT S.acquisto FROM schieramenti S WHERE S.username='".$_COOKIE['username']."')";
			$result=$mysqli->query($query);

			while($row=$result->fetch_assoc()){ //resetto tutti i cookies degli schieramenti
			 $name="schierato".$row['id'];
				if(isset($_COOKIE[$name])){
					setcookie($name, '', time()-1000);
					setcookie($name, '', time()-1000, '/');
				}
			}
			$query="SELECT P.id FROM personaggiacquistati P WHERE P.username='".$username."' AND P.id IN(SELECT S.acquisto FROM schieramenti S WHERE S.username='".$username."')";
			$result=$mysqli->query($query);
			while($row=$result->fetch_assoc()){ 
				$name="schierato".$row['id'];
	 			setcookie($name,1 , time() + (86400 * 30), "/");
			}

            inviaLogNoCookie($username,"login",$email,$mysqli);
        	if(controllaManutenzioneNoRedirect($mysqli)){
        		controllaManutenzione($mysqli);
        	}
            if($primoLogin==1){
				header('location: /index/market');
				$testoMsg = $generalAlert80;
				nuovoMsg($testoMsg);
            }else{
            	header('location: /index/market');
            }
			
			$mysqli->close();
			die();
	}


?>
