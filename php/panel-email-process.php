<?php
include("config.php");
include("utility.php");
include('./libs/PHPMailer/PHPMailerAutoload.php');
include_once "./mail/MailComposer.php";
set_time_limit(0);
controllaRank(3);

if(!isset($_POST["lingua"]) || !isset($_POST["oggetto"]) || !isset($_POST["testo"])){
    echo "Form incompleto";
    $mysqli->close();
    die();
}
$lingua = filtra($_POST["lingua"],$mysqli);
$oggetto = filtra($_POST["oggetto"], $mysqli);
$testo = filtra($_POST["testo"], $mysqli);
$emailFooterIt1 = ' <footer>
                    Seguici su Facebook: <a href="https://www.facebook.com/fantagotofficial">clicca qui.</a><br>
                    Visita il sito: <a href="https://fantagot.com/">clicca qui.</a><br>   
                    Se non vuoi ricevere email dalla newsletter <a href="https://fantagot.com/php/unsubscribe.php?key=';
                    
$emailFooterIt2 = '">clicca qui.</a><br>
                    </footer>';

$emailFooterEn1 = ' <footer>
                    Follow us on Facebook: <a href="https://www.facebook.com/fantagotofficial">click here.</a><br>
                    Visit the website: <a href="https://fantagot.com/">click here.</a><br>
                    If you do not want to receive emails from the newsletter <a href="https://fantagot.com/php/unsubscribe.php?key=';
                    
$emailFooterEn2 = '">click here.</a><br>
                    </footer>';

$footer1 = ($lingua == "IT")?$emailFooterIt1:$emailFooterEn1;
$footer2 = ($lingua == "IT")?$emailFooterIt2:$emailFooterEn2;
$testo = $testo."<br><br>".$footer1.$footer2;
if($_GET["action"]==0) { // broadcast
    echo "INVIO IN BROADCAST IN CORSO";
    $query = "SELECT * FROM users U WHERE U.lingua = '".$lingua."' AND U.email NOT IN (SELECT UV.email FROM users_verification UV WHERE UV.status=2)";
    $result=$mysqli->query($query);
    $mail_composer = new MailComposer();
        while($row=$result->fetch_assoc()){
            $mail_composer->sendGenericEmail($row["email"], $oggetto, $testo);
        }
}else if($_GET["action"]== 1){ // Email singola
    echo "INVIO EMAIL SINGOLA IN CORSO";
    if(!isset($_POST["email"])){
      echo "Email invalida";
      $mysqli->close();
      die();
    }
    $email = filtra($_POST["email"],$mysqli);
    $mail_composer = new MailComposer();
    $mail_composer->sendGenericEmail($email, $oggetto, $testo);    
}else{
    $min =filtra($_POST["min"],$mysqli);;
    $max =filtra($_POST["max"],$mysqli);
    $query = "SELECT * FROM users U WHERE U.lingua = '".$lingua."' AND U.id BETWEEN ".$min." AND ".$max." AND U.email NOT IN (SELECT UV.email FROM users_verification UV WHERE UV.status=2)";
    $result=$mysqli->query($query);
    $mail_composer = new MailComposer();
        while($row=$result->fetch_assoc()){
            $mail_composer->sendGenericEmail($row["email"], $oggetto, $testo);
        }
}
echo "Email inviata con successo";
$mysqli->close();
die();
?>
