<?php
include("config.php");
include("utility.php");
	if($_COOKIE["messaggi"]==1){
		$query = "UPDATE users SET avvisi = 0 WHERE username='".$_COOKIE['username']."'";
		esegui($query,$mysqli);
		setcookie("messaggi",0, time() + (86400 * 30), "/"); 
	}
	else{
		$query = "UPDATE users SET avvisi = 1 WHERE username='".$_COOKIE['username']."'";
		esegui($query,$mysqli);
		setcookie("messaggi",1, time() + (86400 * 30), "/"); 
	}
		

	 if(isset($_GET["pa"]))
	 {
		$page=$_GET["pa"];  
	 }else{
		$page="market";
	}
	header('location: /index/'.$page);
	nuovoMsg($generalAlert106);
	die();
?>