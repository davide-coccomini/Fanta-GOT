<?php
include("config.php");
include("utility.php");
include("lang-manager.php");

	if(isset($_GET["pa"])){
		$page=$_GET["pa"];  
	}else{
		$page="market";
	}

	if(!isset($_GET["l"])){
		header('location: /index/'.$page);
		nuovoMsg($generalAlert121);
		die();
	}

	$l = filtra($_GET["l"],$mysqli);

	if(!in_array($l, $lingue)){
		header('location: /index/'.$page);
		nuovoMsg($generalAlert122);
		die();
	}
	if(!isset($_COOKIE["login"])){
		setcookie("lingua",$l, time() + (86400 * 30), "/");
	}else{
		$query = "UPDATE users SET lingua = '".$l."' WHERE username='".$_COOKIE['username']."'";
		esegui($query,$mysqli);
	}
	header('location: /index/'.$page);
	die();
?>