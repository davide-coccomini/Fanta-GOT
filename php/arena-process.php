<?php
include("config.php");
include("utility.php");

if(!isset($_GET["action"]))
{
	header('location: /index/arena');
	nuovoMsg($generalAlert1);
	$mysqli->close();
	die();
}


$query="SELECT status FROM aperture WHERE soggetto='schieramento' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
$riga=estrai($query,$mysqli);
$status=$riga["status"];

  if($status==1){
		header('location: /index/arena');
		nuovoMsg($generalAlert2);
	    $mysqli->close();
		die();
   }


$action = $_GET["action"];

if($action == 0){ // Sfida/Annulla
    $query = "SELECT id, sfidato, sfidante, COUNT(*) as numero FROM sfide WHERE (sfidante='".$_COOKIE['username']."' OR sfidato = '".$_COOKIE['username']."') AND (status <> 4 OR status IS NULL)";
    $row = estrai($query,$mysqli);

    if($row["numero"] == 0){ // Sfida
        if($_COOKIE["punteggioNetto"] < 0){
            header('location: /index/arena');
            nuovoMsg($generalAlert127);
            $mysqli->close();
            die();
        }
        // Controllo se c'è uno sfidante di un altro clan che non ha ancora trovato qualcuno da sfidare e in caso li accoppio
        $query = "SELECT id, sfidante, COUNT(*) as numero FROM sfide WHERE sfidato IS NULL AND sfidante NOT IN (SELECT U.username FROM users U INNER JOIN gruppi G ON U.gruppo = G.nome WHERE G.clan = '".$_COOKIE['clan']."')";
        $row = estrai($query,$mysqli);
        if($row["numero"]>0){ // Sfidante trovato, li accoppio
            inviaLog("Si rende disponibile per la sfida e viene accoppiato",$row["sfidante"],$mysqli);
            $query = "UPDATE sfide SET sfidato = '".$_COOKIE['username']."', status = 1 WHERE id=".$row["id"];
            esegui($query,$mysqli);
            
            notifica($row["sfidante"], 5, "/index/arena", "arena", $mysqli); // Notifico allo sfidante che qualcuno ha accettato la sua sfida
            header('location: /index/arena');
            nuovoMsg($generalAlert128);
        }else{ // Non c'è uno sfidante, lo metto in attesa
            inviaLog("Si rende disponibile per la sfida e rimane in attesa","",$mysqli);
            $query = "INSERT INTO sfide(sfidante,episodio) VALUES('".$_COOKIE['username']."',".$episodioCorrente.")";
            esegui($query,$mysqli);
            header('location: /index/arena');
            nuovoMsg($generalAlert124);
        }
    }else{ // Annulla
        $idSfida = $row["id"];
        if(isset($row["sfidato"]) && isset($row["sfidante"])){
            $query = "SELECT TIMESTAMPDIFF(SECOND,CURRENT_TIMESTAMP+INTERVAL 18 HOUR,programmato) as diff FROM aperture WHERE programmato IS NOT NULL ORDER BY programmato ASC LIMIT 1";
            $riga = estrai($query,$mysqli);
      
            if($riga["diff"]<=0 && isset($riga["diff"])){
                header('location: /index/arena');
                nuovoMsg($generalAlert126);
                $mysqli->close();
                die();
            }
            if($row["sfidante"]==$_COOKIE["username"]){ // Lo sfidante ha annullato la sfida
                notifica($row["sfidato"], 6, "/index/arena", "arena", $mysqli); // Notifico allo sfidato che il suo sfidante ha annullato la sfida
                $clan = ottieniClan($row["sfidato"], $mysqli);
                $query = "SELECT *, COUNT(*) as numero FROM sfide WHERE sfidato = NULL AND sfidante NOT IN (SELECT U.username FROM users U INNER JOIN gruppi G ON U.gruppo = G.nome WHERE G.clan = '".$clan."')";
                $r = estrai($query,$mysqli);
                if($r["numero"]>0){ // Posso già accoppiarlo con qualcun'altro, di un altro clan, che era in attesa
                    inviaLog("Annulla la sfida da sfidante ma lo sfidato trova subito un altro accoppiamento",$row["sfidato"],$mysqli);

                    $query = "UPDATE sfide SET sfidato = '".$row['sfidato']."', status = 1 WHERE id=".$r['id'];
                    esegui($query,$mysqli); 

                    notifica($row["sfidato"], 5, "/index/arena", "arena", $mysqli); // Notifico allo sfidato che qualcuno ha accettato la sua sfida
                    notifica($r["sfidante"], 5, "/index/arena", "arena", $mysqli); // Notifico allo sfidante che qualcuno ha accettato la sua sfida
           
                    $query = "DELETE FROM sfide WHERE id=".$idSfida;
                    esegui($query,$mysqli);

                }else{ // Non c'è nessun altro in attesa e quindi metto lui in attesa come sfidante
                    inviaLog("Annulla la sfida da sfidante quindi lo sfidato diventa sfidante",$row["sfidato"],$mysqli);

                    $query = "UPDATE sfide SET sfidante = sfidato, sfidato = NULL, regolaSfidato = NULL, regolaSfidante = NULL, status = NULL WHERE id=".$idSfida;
                    esegui($query,$mysqli);
                }
                
            
            }else{ // Lo sfidato ha annullato la sfida quindi lo sfidante dovrà trovare un altro da sfidare
                inviaLog("Annulla la sfida da sfidato, lo sfidante rimane in attesa",$row["sfidante"],$mysqli);
                notifica($row["sfidante"], 6, "/index/arena", "arena", $mysqli); // Notifico allo sfidante che il suo sfidato ha annullato la sfida
               
                $clan = ottieniClan($row["sfidante"], $mysqli);
                $query = "SELECT *, COUNT(*) as numero FROM sfide WHERE sfidato = NULL AND sfidante NOT IN (SELECT U.username FROM users U INNER JOIN gruppi G ON U.gruppo = G.nome WHERE G.clan = '".$clan."')";
                $r = estrai($query,$mysqli);
                if($r["numero"]>0){ // Posso già accoppiarlo con qualcun'altro, di un altro clan, che era in attesa
                    $query = "UPDATE sfide SET sfidato = '".$row['sfidante']."', status = 1 WHERE id=".$r['id'];
                    esegui($query,$mysqli); 
                    notifica($row["sfidante"], 5, "/index/arena", "arena", $mysqli); // Notifico allo sfidato che abbiamo trovato un'altra sfida per lui
                    notifica($r["sfidante"], 5, "/index/arena", "arena", $mysqli); // Notifico allo sfidante che qualcuno ha accettato la sua sfida
                }else{ // Lo sfidante si mette semplicemente in attesa
                    $query = "UPDATE sfide SET sfidato = NULL, regolaSfidato = NULL, regolaSfidante = NULL, status = NULL WHERE id=".$idSfida;
                    esegui($query,$mysqli);
                }
            }
            
        }else{ // L'utente è lo sfidante e non ha ancora trovato nessuno da sfidare           
            inviaLog("Annulla la sfida da sfidante mentre è ancora in attesa, la sfida è cancellata","",$mysqli);
            $query = "DELETE FROM sfide WHERE id=".$idSfida;
            esegui($query,$mysqli);
        }
        header('location: /index/arena');
        nuovoMsg($generalAlert125);
    }
    $mysqli->close();
    die();
}else if($action == 1){ // Seleziona la regola
    if(!isset($_POST["rule"])){
        header('location: /index/arena');
        nuovoMsg($generalAlert129);
        $mysqli->close();
        die();
    }
    $query = "SELECT * FROM sfide WHERE (sfidato = '".$_COOKIE['username']."' OR sfidante = '".$_COOKIE['username']."') AND status <> 4";
    $row = estrai($query,$mysqli);

    $regola = filtra($_POST["rule"], $mysqli);
    if($row["status"] == 1){
        $query = "UPDATE sfide SET status=2, regolaSfidato = ".$regola." WHERE sfidato = '".$_COOKIE['username']."'";
        notifica($row["sfidante"], 7, "/index/arena", "arena", $mysqli); // Notifico allo sfidato che deve scegliere la sua regola
    }else{
        $query = "UPDATE sfide SET status=3, regolaSfidante = ".$regola." WHERE sfidante = '".$_COOKIE['username']."'";
        notifica($row["sfidato"], 8, "/index/arena", "arena", $mysqli); // Notifico allo sfidante che lo sfidato ha scelto la sua regola e che deve attendere 
    }
    esegui($query,$mysqli);

    $query = "SELECT nome FROM vociregolamento WHERE associazione = ".$regola." AND lingua = 'IT'";
    $row = estrai($query,$mysqli);
    $nome = $row["nome"];
    inviaLog("Seleziona la regola per la sfida",$nomeRegola,$mysqli);

    header('location: /index/arena');
    nuovoMsg($generalAlert130);
    $mysqli->close();
    die();    
}


?>