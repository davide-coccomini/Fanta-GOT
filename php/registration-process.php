<?php

SESSION_start();
include("utility.php");
include("config.php");
controllaRegistrazioni($mysqli);
if(isset($_POST["username"]))
{
	setcookie("formUsername",$_POST['username'],time() + (1800), "/");
}
if(isset($_POST["email"]))
{
	setcookie("formEmail",$_POST['email'],time() + (1800), "/");
}
if(!isset($_POST["username"]) || strlen($_POST["username"])<4 || strlen($_POST["username"])>15){
	header('location: /index/home/1');
	nuovoMsg($generalAlert97);
	$mysqli->close();
	die();

}else if(!isset($_POST["email"]) || !checkEmail($_POST["email"]) || tmpEmail($_POST["email"])){
	header('location: /index/home/1');
	nuovoMsg($generalAlert98);
	$mysqli->close();
	die();
}else if(!isset($_POST["password"]) || strlen($_POST["password"])<5 || strlen($_POST["password"])>30){
	header('location: /index/home/1');
	nuovoMsg($generalAlert99);
	$mysqli->close();
	die();
}else if(!isset($_POST["confirm"])){
	header('location: /index/home/1');
	nuovoMsg($generalAlert100);
	$mysqli->close();
	die();
}else if($_POST["confirm"]!=$_POST["password"]){
	header('location: /index/home/1');
	nuovoMsg($generalAlert101);
	$mysqli->close();
	die();
}
// $response = ...
$responseDecoded  = json_decode($response);
if ( $responseDecoded->success == false ) {
  header('location: /index/home/1');
  nuovoMsg($generalAlert1);
  $mysqli->close();
  die();
}

$email = (isset($_COOKIE['formFB']))?$_COOKIE['formFB']:
				 (isset($_COOKIE['formTW']))?$_COOKIE['formTW']:$email;

$username=filtra($_POST["username"],$mysqli);
$email=filtra($_POST["email"],$mysqli);
$password=filtra($_POST["password"],$mysqli);
$password=sha1($password);
$hash=getHash($password);
$password=$hash["encrypted"];
$salt=$hash["salt"];

$row=estrai("SELECT COUNT(*) as numero FROM users WHERE email='".$email."'",$mysqli);

if($row["numero"]!=0){
	header('location: /index/home/1');
	nuovoMsg($generalAlert102);
	$mysqli->close();
	die();
}
$row=estrai("SELECT COUNT(*) as numero FROM users WHERE username='".$username."'",$mysqli);
if($row["numero"]!=0){
	header('location: /index/home/1');
	nuovoMsg($generalAlert103);
	$mysqli->close();
	die();
}
if(isset($_COOKIE["invitedBy"]) && !isset($_COOKIE['alreadyInvited'])){
	$invitedLabel = ",invitante,punteggioInvitante";
	$invitedValue = ",'".$_COOKIE['invitedBy']."',0";
	$invitedLog = $_COOKIE['invitedBy'];
	setcookie("invitedBy", '', time()-1000);
    setcookie("invitedBy", '', time()-1000, '/');
    setcookie("alreadyInvited",1,  time() + (86400 * 360), "/");
    $query = "UPDATE users SET invitati = invitati+1 WHERE username ='".$_COOKIE['invitedBy']."'";
    esegui($query,$mysqli);
}else{
	$invitedLabel = "";
	$invitedValue = "";
}
if($episodioCorrente>=round($totEpisodi/2)){
	$query="SELECT punteggioNetto FROM users ORDER BY punteggioNetto DESC LIMIT 1";
	$row=estrai($query,$mysqli);
	$punteggioIniziale=round($row["punteggioNetto"]/4);
	$query = "INSERT INTO users(username,email,password,salt,crediti,punteggio,punteggioNetto".$invitedLabel.") VALUES('".$username."','".$email."','".$password."','".$salt."',".$creditiIniziali.",".$punteggioIniziale.",".$punteggioIniziale.$invitedValue.")";

}else{
	$query = "INSERT INTO users(username,email,password,salt,crediti".$invitedLabel.") VALUES('".$username."','".$email."','".$password."','".$salt."',".$creditiIniziali.$invitedValue.")";
}
esegui($query,$mysqli);

// Verifica email
if (isset($_COOKIE['formFB'])) {

	$query = "INSERT INTO users_verification (email, registration_key, status) VALUES ('".$email."', '".$registration_key."', 1)";
	esegui($query, $mysqli);

	$_COOKIE["username"]=$username;
	inviaLog("registrazione",$username,$mysqli);

	include('./libs/mailchimp.php');
	$mailchimp = new Mailchimp();
	$mailchimp->addUserToList($email);

	header('location: /index/home');
	$testoMsg =  $generalAlert104;
}else if (isset($_COOKIE['formTW'])) {

	$query = "INSERT INTO users_verification (email, registration_key, status) VALUES ('".$email."', '".$registration_key."', 1)";
	esegui($query, $mysqli);

	$_COOKIE["username"]=$username;

	inviaLog("registrazione",$email,$mysqli);

	include('./libs/mailchimp.php');
	$mailchimp = new Mailchimp();
	$mailchimp->addUserToList($email);

	header('location: /index/home');
	$testoMsg = $generalAlert104;
}else {

	include('./libs/PHPMailer/PHPMailerAutoload.php');
	include_once "./mail/MailComposer.php";
	$registration_key = filtra(uniqid(), $mysqli);
	$mail_composer = new MailComposer();

	$query = "INSERT INTO users_verification (email, registration_key, status) VALUES ('".$email."', '".$registration_key."', 0)";
	esegui($query, $mysqli);
	$body = $emailContent1.$registration_key.$emailContent2.$emailFooter1.$registration.$emailFooter2;
	
	$mail_composer->sendRegistrationEmail($email, $registration_key, $emailObject, $body);

	// Fine verifica

	$_COOKIE["username"]=$username;
	if($invitedLog!=""){
	 $etichetta = $email." By: ".$invitedLog;
	 inviaLog("registrazione su invito",$etichetta,$mysqli);
	}else{
	 inviaLog("registrazione",$email,$mysqli);
	}

	include('./libs/mailchimp.php');
	$mailchimp = new Mailchimp();
	$mailchimp->addUserToList($email);

	header('location: /index/home');
	$testoMsg = $generalAlert105;

}
nuovoMsg($testoMsg);
$mysqli->close();
die();
?>
