<?php
include("config.php");
include("utility.php");

controllaPunteggioInvitati($mysqli);
if($_COOKIE["donazioneDisponibile"]==1){
	setcookie("donazioneDisponibile", '', time()-1000);
    setcookie("donazioneDisponibile", '', time()-1000, '/');
    $query = "UPDATE users SET punteggioInvitante = -1 WHERE invitante='".$_COOKIE['username']."' AND punteggioInvitante = 1 LIMIT ".$utentiDaInvitare;
    esegui($query,$mysqli);
    $query = "UPDATE users SET donatore=3, scadenzaDonatore = CURRENT_TIMESTAMP + INTERVAL 7 DAY WHERE username = '".$_COOKIE['username']."'";
    esegui($query,$mysqli);
    inviaLog("Riscuote donatore da invito","",$mysqli);
     header('location: /index/market');
	 nuovoMsg($generalAlert26);
	 $mysqli->close();
	 die();
}



?>