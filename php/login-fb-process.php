<?php
SESSION_start();
include("utility.php");
include("config.php");

if (!isset($_POST["email"])) {
  header('location: /index/home');
  nuovoMsg($generalAlert9);
  $mysqli->close();
  die();
}else{
  $email = filtra($_POST["email"],$mysqli);

  $query = 'SELECT *, COUNT(*) as numero FROM users WHERE email = "'.$email.'"';
  $row = estrai($query, $mysqli);

  if($row["numero"]==0){

    setcookie("formEmail", $email, time() + (60), "/");
    setcookie("formFB", $email, time() + (180), "/");
    $response = array(
      'p' => 'home',
      'sec' => '1'
    );

    echo json_encode($response);

  }else{

    $_SESSION["letto"]=0;
    setcookie("messaggi",1, time() + (86400 * 30), "/");
    setcookie("login",1, time() + (86400), "/");
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
        $query="SELECT personaggio FROM personaggiacquistati WHERE username='".$username."'";
          $result=$mysqli->query($query);
          while($row=$result->fetch_assoc()){
            setcookie("personaggio".$row['personaggio'],1,time() + (86400 * 30), "/");
          }

    $query="SELECT S.acquisto FROM personaggiacquistati PA INNER JOIN schieramenti S ON S.acquisto=PA.id WHERE  PA.username='".$username."'";
          $result=$mysqli->query($query);
          while($row=$result->fetch_assoc()){
            setcookie("schierato".$row['acquisto'],1,time() + (86400 * 30), "/");
          }

          inviaLogNoCookie($username,"login"," ",$mysqli);
          if($primoLogin==1){
            //header('location: ../index.php?p=market&m=Benvenuto su FANTA-VIKINGS!');

            $response = array(
              'p' => 'market',
            );

            echo json_encode($response);

          }else{
            //header('location: ../index.php?p=market');
            $response = array(
              'p' => 'market'
            );

            echo json_encode($response);
            
          }
    $mysqli->close();
    die();

  }

}

 ?>
