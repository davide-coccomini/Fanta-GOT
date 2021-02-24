<?php
include("config.php");
include("utility.php");
if(!isset($_POST["oldpassword"]) || !isset($_POST["newpassword"]) || !isset($_POST["newpasswordconfirm"]) ){
	header('location: /index/change-password');
	nuovoMsg($generalAlert15);
	$mysqli->close();
	die();
}
if($_POST["newpassword"]!=$_POST["newpasswordconfirm"]){
	header('location: /index/change-password');
	nuovoMsg($generalAlert16);
	$mysqli->close();
	die();
}
if(strlen($_POST["newpassword"])<5 || strlen($_POST["newpassword"])>30){
	header('location: /index/change-password');
	nuovoMsg($generalAlert17);
	$mysqli->close();
	die();
}
$oldpsw=sha1(filtra($_POST["oldpassword"],$mysqli));
$newpsw=sha1(filtra($_POST["newpassword"],$mysqli));
$hash=getHash($newpsw);
$password=$hash["encrypted"];
$salt=$hash["salt"];

$query="SELECT password,salt FROM users WHERE username='".$_COOKIE["username"]."'";
$row=estrai($query,$mysqli);
if(!(verifyHash($oldpsw.$row["salt"], $row["password"]))){
	header('location: /index/change-password');
	nuovoMsg($generalAlert18);
	$mysqli->close();
	die();
}

$query="UPDATE users SET password='".$password."',salt='".$salt."' WHERE username='".$_COOKIE["username"]."'";
esegui($query,$mysqli);
header('location: /index/change-password');
nuovoMsg($generalAlert19);
$mysqli->close();
die();
?>