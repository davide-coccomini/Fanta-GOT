<?php
 ob_start();
include_once("config.php");
include("lang-manager.php");


if (session_status() === PHP_SESSION_NONE) session_start();
	function estrai($query,$mysqli){
	    $result=$mysqli->query($query);
	    $row=$result->fetch_assoc();
	    return $row;
	}
	function esegui($query,$mysqli){
	    $result=$mysqli->query($query);
	}
	/*VARIABILI GLOBALI*/
	$query = "SELECT * FROM config";
	$row = estrai($query,$mysqli);

    $personaggiDisponibili = $row["personaggiDisponibili"];
    $episodioCorrente = $row["episodioCorrente"];
	$maxPersonaggiSchierabili = $row["maxPersonaggiSchierabili"];
	$maxPersonaggiAcquistabili = $row["maxPersonaggiAcquistabili"];
	$creditiIniziali = $row["creditiIniziali"];
	$totEpisodi = $row["totEpisodi"];
	$utentiDaInvitare = $row["utentiDaInvitare"];
	$punteggiDaMostrare = $row["punteggiDaMostrare"];


	function filtra($string,$mysqli)
	 {
	  if (get_magic_quotes_gpc())
	   $string = stripslashes($string);
	  if (!is_numeric($string))
	   $string = $mysqli->real_escape_string($string);
	  return $string;  
	 }

	 function checkEmail($email){
		  //pulisco la stringa
		  $em = filter_var($email, FILTER_SANITIZE_EMAIL);
		  //verifico e ritorno
		  return filter_var($email, FILTER_VALIDATE_EMAIL);
	 }
	 function tmpEmail($email){
	 	$verifiedDomains = array("gmail.com","hotmail.com","hotmail.it","outlook.com","outlook.it","libero.it","live.it","inwind.it","rocketmail.com","yahoo.it","yahoo.com","icloud.com","ngi.it","msn.com","tiscali.it","ymail.com","virgilio.it","alice.it","mail.com");
	 	$split = explode("@", $email);
	  	$domain = $split[1];
	 	if(!in_array($domain,$verifiedDomains)){
	 		return true;
	 	}else{
	 		return false;
	 	}
	 	
	 }
	 function nuovoMsg($testo){
	 	$_SESSION["letto"]=0;
	 	if(isset($testo))
	 		$_SESSION["testoMsg"] = $testo;	
	 }
	 function leggiMsg(){
	    $_SESSION["letto"]=1;
	 	if(isset($_SESSION["testoMsg"]))
	 		unset($_SESSION["testoMsg"]);
	 }
	function controllo_caratteri($stringa) { 
    $find =  array('&','\r\n','\n','/','\\','+',"'",'"','|');  
    for($i=0;$i<strlen($stringa);$i++)  
        if(in_array($stringa[$i],$find)) 
            return true; 
     
    return false; 
} 

	function verificaAppartenenzaGruppo($username,$mysqli){
		$query="SELECT gruppo FROM users WHERE username='".$username."'";
		$result=$mysqli->query($query);
	    $row=$result->fetch_assoc();
	    if($row["gruppo"]==null && isset($_COOKIE["gruppo"])){
	    	setcookie("gruppo", '', time()-1000);
 			setcookie("gruppo", '', time()-1000, '/');
 			setcookie("clan", '', time()-1000);
 			setcookie("clan", '', time()-1000, '/');
	    }
	}

	function inviaLog($evento,$dettagli,$mysqli){
		$ip = ottieniIp();
		$query="INSERT INTO logs(username,evento,dettagli,ip) VALUES('".$_COOKIE['username']."','".$evento."','".$dettagli."','".$ip."')";
		$result=$mysqli->query($query);
	}
	function ottieniIp() {
	    $ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	       $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}


	function inviaLogNoCookie($username,$evento,$dettagli,$mysqli){
		$ip = ottieniIp();
		$query="INSERT INTO logs(username,evento,dettagli,ip) VALUES('".$username."','".$evento."','".$dettagli."','".$ip."')";
		$result=$mysqli->query($query);
	}
	function verificaAdmin($mysqli){
		$query="SELECT admin FROM users WHERE username='".$_COOKIE['username']."'";
		$row=estrai($query,$mysqli);
		$_COOKIE["admin"]=$row["admin"];
		if($row["admin"]>0){
			return true;
		}else{
			return false;
		}
	}
	function forzaLogout($percorso){
		header('location: '.$percorso.'logout.php');
		$mysqli->close();
		die();
	}
	function resetCookies(){
			if (isset($_SERVER['HTTP_COOKIE'])) {//do we have any
				    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);//get all cookies 
				    foreach($cookies as $cookie) {//loop
				        $parts = explode('=', $cookie);//get the bits we need
				        $name = trim($parts[0]);
				        setcookie($name, '', time()-1000);//kill it
				        setcookie($name, '', time()-1000, '/');//kill it more
				    }
			}
	}
	function controllaManutenzione($mysqli){

		$query="SELECT status FROM aperture WHERE soggetto='sito' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
		$row=estrai($query,$mysqli);
		if($row["status"]==1){
			if(!verificaAdmin($mysqli)){
				resetCookies();
				setcookie("messaggi",1, time() + (86400 * 30), "/"); 
				header('location: /index/home');
				nuovoMsg($generalAlert142);
				$mysqli->close();
				die();
			}
		}
	}
	function controllaChiusuraScommesse($mysqli){

		$query="SELECT status FROM aperture WHERE soggetto='scommesse' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
		$row=estrai($query,$mysqli);
		if($row["status"]==1)
			return true;
		else
			return false;
	}
	function controllaManutenzioneNoRedirect($mysqli){
	$query="SELECT status FROM aperture WHERE soggetto='sito'  AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
	$row=estrai($query,$mysqli);
		if($row["status"]==1)
			return true;
		else
			return false;
	}
	function controllaRegistrazioni($mysqli){
		$query="SELECT status FROM aperture WHERE soggetto='registrazioni' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
		$row=estrai($query,$mysqli);
		if($row["status"]==1){
			header('location: /index/home');
			nuovoMsg($generalAlert143);
			$mysqli->close();
			die();
		}
	}
	function controllaRegistrazioniNoRedirect($mysqli){
		$query="SELECT status FROM aperture WHERE soggetto='registrazioni'  AND programmato IS NULL  ORDER BY timestamp DESC LIMIT 1";
		$row=estrai($query,$mysqli);
		if($row["status"]==1)
			return true;
		else
			return false;
	}
	function verificaReset($mysqli){
		$query =" SELECT personaggiAcquistati,personaggiSchierati FROM users WHERE username='".$_COOKIE['username']."'";
         $row=estrai($query,$mysqli);
         if($row["personaggiAcquistati"]==-1){
         	$query="UPDATE users SET personaggiAcquistati=0 WHERE username='".$_COOKIE['username']."'";
         	esegui($query,$mysqli);
         	resetCookies();
            setcookie("messaggi",1, time() + (86400 * 30), "/"); 
            nuovoMsg($generalAlert144);
         	header('location: /index/home');
			$mysqli->close();
			die();
         }else if($row["personaggiAcquistati"]==-2){
        	$query="UPDATE users SET personaggiAcquistati=0 WHERE username='".$_COOKIE['username']."'";
         	esegui($query,$mysqli);
         	resetCookies();
            setcookie("messaggi",1, time() + (86400 * 30), "/"); 
         	header('location: /index/home');
			nuovoMsg($generalAlert145);
			$mysqli->close();
			die(); 

     	 }else if($row["personaggiSchierati"]<0){
         	$query="UPDATE users SET personaggiSchierati=0 WHERE username='".$_COOKIE['username']."'";
         	esegui($query,$mysqli);
         	resetCookies();
            setcookie("messaggi",1, time() + (86400 * 30), "/"); 
         	header('location: /index/home');
			nuovoMsg($generalAlert146);
			$mysqli->close();
			die();
         }
	}
	
	function aggiornaInformazioni($mysqli,$personaggiDisponibili,$utentiDaInvitare){
		$query="SELECT * FROM users WHERE username='".$_COOKIE['username']."'";
		$row=estrai($query,$mysqli);
		setcookie("crediti",$row['crediti'],time() + (86400 * 30), "/");
		setcookie("penalita",$row['penalita'],time() + (86400 * 30), "/");
		setcookie("annunciDaLeggere",$row['annunciDaLeggere'],time() + (86400 * 30), "/");
		setcookie("scommessaEffettuata",$row['scommessaEffettuata'],time() + (86400 * 30), "/");
		setcookie("punteggioNetto",$row['punteggioNetto'],time() + (86400 * 30), "/");
		setcookie("donatore",$row['donatore'],time() + (86400 * 30), "/");
		setcookie("scadenzaDonatore",$row['scadenzaDonatore'],time() + (86400 * 30), "/");
		setcookie("gruppo",$row['gruppo'],time() + (86400 * 30), "/");
		setcookie("admin",$row['admin'],time() + (86400 * 30), "/");
		setcookie("punteggioScommesse",$row['punteggioScommesse'],time() + (86400 * 30), "/");
		setcookie("personaggiAcquistati",$row['personaggiAcquistati'],time() + (86400 * 30), "/");
		setcookie("personaggiSchierati",$row['personaggiSchierati'],time() + (86400 * 30), "/");
		setcookie("punteggio",$row['punteggio'],time() + (86400 * 30), "/");
		setcookie("punteggioSettimanale",$row['punteggioSettimanale'],time() + (86400 * 30), "/");
		setcookie("posizione",$row['posizione'],time() + (86400 * 30), "/");
		setcookie("posizioneSettimanale",$row['posizioneSettimanale'],time() + (86400 * 30), "/");
		setcookie("messaggi",$row['avvisi'],time() + (86400 * 30), "/");
		setcookie("invitedBy",$row['invitante'],time() + (86400 * 30), "/");
		setcookie("punteggioInvitante",$row['punteggioInvitante'],time() + (86400 * 30), "/");
		setcookie("invitati",$row['invitati'],time() + (86400 * 30), "/");
		setcookie("opzioneScommessaStagionale",$row['opzioneScommessaStagionale'],time() + (86400 * 30), "/");
		if($row["ban"]!=0){
			resetCookies();
         	header('location: /index/home');
			nuovoMsg($generalAlert147);
			$mysqli->close();
			die();
		}
		controllaScadenzaDonatore($mysqli);
		/*AGGIORNO GLI ACQUISTI*/
		for($i=1;$i<$personaggiDisponibili;$i++) //resetto tutti i cookies dei personaggi
		{
		 $name="personaggio".$i;
			if(isset($_COOKIE[$name])){
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/');
			}
		}
		$query="SELECT * FROM personaggiacquistati WHERE username='".$row['username']."'";
		$result=$mysqli->query($query);
		while($row=$result->fetch_assoc()){
			$name="personaggio".$row['personaggio'];
		    setcookie($name,1 , time() + (86400 * 30), "/");

		}

		/*AGGIORNO SCHIERAMENTI*/
		$query="SELECT P.id FROM personaggiacquistati P WHERE P.username='".$_COOKIE['username']."' AND P.id NOT IN(SELECT S.acquisto FROM schieramenti S WHERE S.username='".$_COOKIE['username']."')";
		$result=$mysqli->query($query);

		while($row=$result->fetch_assoc()){ //resetto tutti i cookies degli schieramenti
		 $name="schierato".$row['id'];
			if(isset($_COOKIE[$name])){
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/');
			}
		}
		$query="SELECT P.id FROM personaggiacquistati P WHERE P.username='".$_COOKIE['username']."' AND P.id IN(SELECT S.acquisto FROM schieramenti S WHERE S.username='".$_COOKIE['username']."')";
		$result=$mysqli->query($query);
		while($row=$result->fetch_assoc()){ 
			$name="schierato".$row['id'];
 			setcookie($name,1 , time() + (86400 * 30), "/");
		}
		/*CONTROLLO SE GLI UTENTI CHE HA INVITATO ABBIANO COMPLETATO LO SCHIERAMENTO*/
		if(isset($_COOKIE["invitati"])){
			if($_COOKIE["invitati"]>0){
				controllaPunteggioInvitati($mysqli,$utentiDaInvitare);
			}else{
				setcookie("invitatiVerificati",0, time() + (86400*30), "/");
			}
		}
	}
	function controllaPunteggioInvitati($mysqli,$utentiDaInvitare){
		$query = "SELECT COUNT(*) as numero FROM users WHERE invitante = '".$_COOKIE['username']."' AND punteggioInvitante = 1";
		$row = estrai($query,$mysqli);
		setcookie("invitatiVerificati",$row["numero"], time() + (86400*30), "/");
		if($row["numero"]>=$utentiDaInvitare){
			if($_COOKIE["donatore"]==0){
				setcookie("donazioneDisponibile",1, time() + (86400 * 30), "/");
			}	
		}else{
			setcookie("donazioneDisponibile",0, time() + (86400 * 30), "/");
		}
	}
	function controllaRank($rank){
		if($_COOKIE["admin"]<$rank){
			header('location: panel-index.php?p=panel-general&m=Autorizzazioni insufficienti');
			nuovoMsg();
			$mysqli->close();
			die();
		}
	}
	function aggiornaPosizione($mysqli,$episodioCorrente){
	$query="SELECT * FROM users ORDER BY punteggioNetto DESC";
	$result=$mysqli->query($query);
	for($i=1;$row=$result->fetch_assoc();$i++){
		$query="UPDATE users SET posizione=".$i." WHERE username='".$row['username']."'";
   	 	esegui($query,$mysqli);
	}
	
   	$query="SELECT username,punteggio FROM punteggi WHERE episodio=".$episodioCorrente." ORDER BY punteggio DESC";
    $result=$mysqli->query($query);
    for($i=1;$row=$result->fetch_assoc();$i++){
	    	 $query="UPDATE users SET posizioneSettimanale=".$i.", punteggioSettimanale=".$row['punteggio']." WHERE username='".$row['username']."'";
	    	 esegui($query,$mysqli);
	 }
	}
	function controllaDonatore(){
		if($_COOKIE["donatore"]==0){
			header('location: /index/market');
			nuovoMsg('Questo contenuto è accessibile soltanto ai donatori. Se vuoi maggiori informazioni su come effettuare una donazione e sui vantaggi che otterresti <a href="/index/about-donation">clicca qui</a>');
		}
	}
	function controllaScadenzaDonatore($mysqli){
		if(isset($_COOKIE["donatore"]) && isset($_COOKIE["scadenzaDonatore"])){
		 $query = "SELECT TIMESTAMPDIFF(MINUTE,CURRENT_TIMESTAMP,scadenzaDonatore) as diff FROM users WHERE username='".$_COOKIE['username']."'";
		 $row = estrai($query,$mysqli);
		}
		if(isset($row)){
			if($row["diff"]<0){
				$query = "UPDATE users SET donatore = 0, scadenzaDonatore = NULL WHERE username='".$_COOKIE['username']."'";
				esegui($query,$mysqli);
			}
		}
	}
	function controllaChiusuraUfficiale($mysqli){
		$query="SELECT COUNT(*) as numero FROM aperture WHERE soggetto='mercato' AND ufficiale=1";
	 	$row=estrai($query,$mysqli); //controllo che sia stato già chiuso il mercato ufficialmente una volta
	 	if($row["numero"]>0)
	 		return true;
	 	else
	 		return false;
	}
	
	function aggiornaGruppo($gruppo,$mysqli){ 
		$query="SELECT membri FROM gruppi WHERE nome='".$gruppo."'"; // seleziono il numero di membri per capire tra quante persone fare la media
		$row = estrai($query,$mysqli);	
		if($row["membri"]>6)
		 $query = "SELECT AVG(punteggioNetto) as media FROM users WHERE gruppo='".$gruppo."' ORDER BY punteggioNetto LIMIT 5 ";
		else if($row["membri"]>4 && $row["membri"]<7)
		 $query="SELECT AVG(punteggioNetto) as media FROM users WHERE gruppo='".$gruppo."' ORDER BY punteggioNetto LIMIT 4";
		else 
		 $query="SELECT AVG(punteggioNetto) as media FROM users WHERE gruppo='".$gruppo."' ORDER BY punteggioNetto LIMIT 3";
		$r=estrai($query,$mysqli);
		$query="UPDATE gruppi SET mediaPunteggi=".$r['media']." WHERE nome='".$gruppo."'";
		esegui($query,$mysqli);
		aggiornaPosizioniGruppi($mysqli);
	}
	function aggiornaPosizioniGruppi($mysqli){
		$query="SELECT nome FROM gruppi ORDER BY mediaPunteggi DESC";
	    $result=$mysqli->query($query);
		for($i=1;$r=$result->fetch_assoc();$i++){
			$query="UPDATE gruppi SET posizione=".$i." WHERE nome='".$r['nome']."'";
			esegui($query,$mysqli);
		}

	}
	function getLanguage($mysqli){
		$lingua = "EN";
		if(!isset($_COOKIE["login"])){
			if(isset($_COOKIE["lingua"])){
				$lingua = $_COOKIE["lingua"];
			}else{
				$lingua = "EN";
			}
		}else{
 			$query = "SELECT lingua FROM users WHERE username='".$_COOKIE['username']."'";
			$row = estrai($query,$mysqli);
			$lingua = $row["lingua"]; 
		}
 		return $lingua;
 	}
	function getTitle($assegnato,$tipo,$mysqli){
		$lingua = getLanguage($mysqli);
		$query = "SELECT COUNT(DISTINCT TA.titolo) as numero FROM titoli T INNER JOIN titoliassegnati TA ON TA.titolo=T.associazione WHERE TA.assegnato='".$assegnato."' AND TA.tipo='".$tipo."' AND T.lingua='".$lingua."'";
		$row = estrai($query,$mysqli);
		$numeroTitoli = $row["numero"];

		$titolo = "";
		$query = "SELECT T.nome FROM titoli T INNER JOIN titoliassegnati TA ON TA.titolo=T.associazione WHERE TA.assegnato='".$assegnato."' AND TA.tipo='".$tipo."' AND T.lingua='".$lingua."'";
		$result=$mysqli->query($query);
		$row=$result->fetch_assoc();
		if($numeroTitoli>0){
		 $i=0;
			do{
				if($i==$numeroTitoli-1){
					$titolo = $titolo.$row["nome"];
				}else{
					$titolo = $titolo.$row["nome"].", ";			
				}
			 $i++;
			}while($row=$result->fetch_assoc());
		}
	 return $titolo;
	}
	function getHash($password) {
     $salt = sha1(rand());
     $salt = substr($salt, 0, 10);
     $encrypted = password_hash($password.$salt, PASSWORD_DEFAULT);
     $hash = array("salt" => $salt, "encrypted" => $encrypted);
     return $hash;
    }
    function verifyHash($password, $hash) {
        return password_verify($password, $hash);
    }
    function controllaAperturaClan($clan,$mysqli){
    	$query="SELECT status FROM aperture WHERE soggetto='".$clan."' ORDER BY timestamp DESC LIMIT 1";
		$row=estrai($query,$mysqli);
		if($row["status"]==1)
			return false;
		else return true;
    }
    function controllaScadenzaProgrammazione($mysqli){
    	$query = "SELECT *,TIMESTAMPDIFF(SECOND,CURRENT_TIMESTAMP,programmato) as diff FROM aperture WHERE programmato IS NOT NULL ORDER BY programmato ASC LIMIT 1";
		$row = estrai($query,$mysqli);
		if($row["diff"]<=0){
			$query = "UPDATE aperture SET programmato=NULL WHERE id=".$row['id'];
			esegui($query,$mysqli);
		}
    }
 	function controllaRegistrazioniPerIp($mysqli,$ip){
 		$query = "SELECT COUNT(*) as numero FROM logs WHERE evento = 'Registrazione' AND ip = '".$ip."'";
 		$row = estrai($query,$mysqli);
 		if($row["numero"]>2)
 			return true;
 		else
 			return false;
 		
	 }
	 function ottieniClan($username, $mysqli){
		$query = "SELECT G.clan FROM gruppi G INNER JOIN users U ON U.gruppo = G.nome WHERE U.username = '".$username."'";
		$row = estrai($query,$mysqli);
		if(!isset($row["clan"])){
			$clan = "//";
		}else{
			$clan = $row["clan"];
		}
		return $clan;	 
	 }
	 function notifica($username, $testo, $link, $immagine, $mysqli, $parametro = NULL){
		 if($parametro != NULL){
			$query = "INSERT INTO notifiche(username, testo, link, immagine, parametro) VALUES('".$username."','".$testo."', '".$link."', '".$immagine."','".$parametro."')";
			esegui($query,$mysqli);
		 }else{
			$query = "INSERT INTO notifiche(username, testo, link, immagine) VALUES('".$username."','".$testo."', '".$link."', '".$immagine."')";
			esegui($query,$mysqli);
		 }
	 }
 	 function isMobile(){
    	$useragent=$_SERVER['HTTP_USER_AGENT'];
 		return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
 	 }
 	
?>