<?php

SESSION_start();
include("utility.php");
include("config.php");


if ($_GET["action"] == 0) { //Richiesta codice
  if(!isset($_POST["username"])){
    header('location: /index/home/2');
    nuovoMsg($generalAlert97);
    $mysqli->close();
    die();
  }
  if(!isset($_POST["email"]) || !checkEmail($_POST["email"])){
    header('location: /index/home/2');
    nuovoMsg($generalAlert98);
    $mysqli->close();
    die();
  }
  
  $email = filtra($_POST["email"], $mysqli);
  $username = filtra($_POST["username"], $mysqli);
  $query="SELECT COUNT(*) as numero FROM users WHERE username='".$username."' AND email='".$email."'";
  $row=estrai($query,$mysqli);
  if($row["numero"]==0){
    header('location: /index/home/2');
    nuovoMsg($generalAlert149);
    $mysqli->close();
    die();
  }
  $query="SELECT email,COUNT(*) as numero FROM passwordrecover WHERE email='".$email."' AND NOW() BETWEEN creationdate AND creationdate + INTERVAL 5 MINUTE";
    $row=estrai($query,$mysqli);
    if($row["numero"]>0){
      header('location: /index/home/2');
      nuovoMsg($generalAlert150);
      $mysqli->close();
      die();
    }
  $codice = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 6)), 0, 6);

  $query="INSERT INTO passwordrecover(email,codice) VALUES('".$email."','".$codice."')";
  esegui($query,$mysqli);

  include('./libs/PHPMailer/PHPMailerAutoload.php');
  include_once "./mail/MailComposer.php";

  $mail_composer = new MailComposer();
  $body = $recoverPasswordContent1.$codice.$recoverPasswordContent2.$emailFooter1.$codice.$emailFooter2;
  $mail_composer->sendRecoverPassword($email, $body);
  ob_clean();

   header('location: /index/home/2');
   nuovoMsg($generalAlert151);
   $mysqli->close();
   die();

  }else if($_GET["action"]==1){ //Aggiornamento password
    if(!isset($_POST["codice"])||!isset($_POST["password"])||!isset($_POST["confirm"])){
       header('location: /index/home/2');
       nuovoMsg($generalAlert42);
       $mysqli->close();
       die();
    }
    $codice=filtra($_POST["codice"],$mysqli);
    $password=filtra($_POST["password"],$mysqli);
    $confirm=filtra($_POST["confirm"],$mysqli);
    if(strlen($password)<5 || strlen($password)>30){
      header('location: /index/home/2/1');
      nuovoMsg($generalAlert99);
      $mysqli->close();
      die();
    }
     if($confirm!=$password){
        header('location:  /index/home/2/1');
        nuovoMsg($generalAlert101);
        $mysqli->close();
        die();
      }
    $query="SELECT email,COUNT(*) as numero FROM passwordrecover WHERE codice='".$codice."' AND NOW() BETWEEN creationdate AND creationdate + INTERVAL 10 MINUTE";
    $row=estrai($query,$mysqli);
    if($row["numero"]==0){
       header('location:  /index/home/2/1');
       nuovoMsg($generalAlert152);
       $mysqli->close();
       die();
    }
    $email=$row["email"];
    $password=sha1($password);
    $hash=getHash($password);
    $password=$hash["encrypted"];
    $salt=$hash["salt"];
    $query="UPDATE users SET password = '".$password."', salt = '".$salt."' WHERE email = '".$email."'";
    esegui($query,$mysqli);
    header('location: /index/home');
    nuovoMsg($generalAlert153);
    $mysqli->close();
    die();
  }

