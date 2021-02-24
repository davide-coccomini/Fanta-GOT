 <div class="col-md-12 logscontainer">


<?php
if(!verificaAdmin($mysqli)){
		forzaLogout("php/");
}
if(!isset($_GET["ep"])){
	forzaLogout("php/");
}
$ep=filtra($_GET["ep"],$mysqli);
if($ep==1) $epprec=10;
else $epprec=$ep-1;

if($ep==10) $epsucc=1;
else $epsucc=$ep+1;

if(isset($_GET["pg"]))
    $pg = filtra($_GET["pg"],$mysqli);
else
    $pg = 1;

$applyScores="'applyScores'";
$resetScores="'resetScores'";
echo '<center><div class="row scorearrows"><a href="panel-index.php?p=panel-scores&ep='.$epprec.'"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>';
echo " Episodio ".$ep;
echo ' <a href="panel-index.php?p=panel-scores&ep='.$epsucc.'"><i class="fa fa-arrow-right" aria-hidden="true"></i></a></div>
<center>
<i class="fa fa-lock" onclick="unlock(this,'.$resetScores.');"  aria-hidden="true"></i><br>
<a href="php/panel-scores-process.php?action=2&ep='.$ep.'"><button class="btn btn-danger" id="resetScores" disabled>RESET</button></a></center>';

echo'<br><a href="php/panel-scores-process.php?action=7&ep='.$ep.'"><button class="btn btn-primary">Autocompletamento</button></a><br>';
if($_COOKIE["admin"]>2){
	echo'<br>
    <center><i class="fa fa-lock" onclick="unlock(this,'.$applyScores.');"  aria-hidden="true"></i><br>
    <a href="php/panel-scores-process.php?action=3&ep='.$ep.'"> <button class="btn btn-success" id="applyScores" disabled>APPLICA</button></a></center>';
}
echo'<br><a href="php/panel-scores-process.php?action=4&ep='.$ep.'"><button class="btn btn-primary" id="generateScoreDocument">Sovrascrivi documento</button></a><a href="php/panel-scores-process.php?action=6&ep='.$ep.'"><button class="btn btn-primary" id="generateScoreDocument">Concatena documento</button></a></center>';
echo "<form></form>";


$queryDaEseguire="SELECT * FROM personaggi ORDER BY prezzo DESC";
$characters=$mysqli->query($queryDaEseguire);
echo "<div class='characters-nav col-md-12 text-center'>";
    while($row=$characters->fetch_assoc())
    {
        $percorso = $row["percorso"];
        $id = $row["id"];
        $query="SELECT COUNT(*) as numero FROM punteggipersonaggi PP INNER JOIN personaggi P ON P.id=PP.personaggio WHERE episodio=".$ep." AND personaggio=".$id;
        $riga=estrai($query,$mysqli);
        if($riga["numero"]==0){
            echo "<a href='panel-index.php?p=panel-scores&ep=".$ep."&pg=".$id."'><img src='../img/personaggi/".$percorso."' class='img-responsive characters-img characters-not-filled' data-toggle='tooltip' data-placement='top' title='".$row['nome']."'></a>";
        }else{
            echo "<a href='panel-index.php?p=panel-scores&ep=".$ep."&pg=".$id."'><img src='../img/personaggi/".$percorso."' class='img-responsive characters-img' data-toggle='tooltip' data-placement='top' title='".$row['nome']."'></a>";
        }
    }
echo "</div>";
$half = round($totEpisodi/2);


$query = "SELECT COUNT(*) as numero FROM regolepersonaggi RP WHERE RP.voce = 3 AND RP.episodio <= ".$half." AND RP.personaggio = ".$pg;
$row = estrai($query,$mysqli);
$firstHalf = $row["numero"];

$query = "SELECT COUNT(*) as numero FROM regolepersonaggi RP WHERE RP.voce = 3 AND RP.episodio > ".$half." AND RP.personaggio = ".$pg;
$row = estrai($query,$mysqli);
$secondHalf = $row["numero"];


echo "<div class='row' id='character-content'>";
echo "<div class='character-rule-box col-md-9'>";
    $query="SELECT * FROM vociregolamento ORDER BY id ASC";
    $result=$mysqli->query($query);
    while($row=$result->fetch_assoc()){
     $query = "SELECT *,COUNT(*) as numero FROM regolepersonaggi WHERE voce = ".$row['id']." AND episodio = ".$ep." AND personaggio=".$pg;
     $regola = estrai($query,$mysqli);
     
     echo"<div class='row rule-box col-md-12'><div id='rule".$row["id"]."'>
     <div class='scores-voice' id='name".$row['id']."'>".$row['nome']."</div>
     <div class='scores-details col-md-10'>".$row['dettagli']."</div>
     <div class='quantity'>
     <input type='number' onchange='aggiornaPunteggio(this);' class='scores-selector col-md-1 col-md-offset-1' min='".$row['punteggioMinimo']."' max='".$row['punteggioMassimo']."' id='pt".$row['id']."' value='".$regola['punteggio']."'> 
     </div>";

     if($regola["numero"]==0){ // la regola non è stata ancora applicata
        echo "<i id='btn".$row['id']."'' class='fa fa-plus fa-2x' value='Aggiungi' onclick='inserisciRegola(".$row['id'].")'></i>";
     }else{ // la regola è stata già applicata
        echo "<i id='btn".$row['id']."'' class='fa fa-refresh fa-2x' value='Aggiungi' onclick='inserisciRegola(".$row['id'].")'></i>";
     }
     echo "</div></div>";
    }
echo "</div>";
echo "<div class='character-box col-md-3'>";

            	$query="SELECT * FROM punteggipersonaggi WHERE episodio=".$ep." AND personaggio=".$pg;
            	$riga=estrai($query,$mysqli);
            	if(!$riga) $action=0; //inserisci
            	else $action=1; //aggiorna
                $query="SELECT * FROM personaggi WHERE id=".$pg;
                $row = estrai($query,$mysqli);
                $id=$row["id"];
                $percorso=$row["percorso"];
                $nome=$row["nome"];
                if($action==0){
                    echo '<form method="POST" action="php/panel-scores-process.php?action=0"><div class="panel-scores-box col-md-12 col-sm-12 col-xs-12">';
                    echo '<img src="img/personaggi/'.$percorso.'" class="col-lg-offset-4 col-md-offset-2 col-sm-offset-2 col-xs-offset-1 img-responsive" id="img-not-buyed">';
                    echo '<div class="pgname text-center">'.$nome.'</div>';
                    echo '<div class="row text-center">
                            <div class="col-md-6">'.$firstHalf.'/'.$half.'</div>
                            <div class="col-md-6">'.$secondHalf.'/'.$half.'</div>
                          </div><br>';
                    echo '<input type="hidden" name="pg" value="'.$id.'">';
                    echo '<input type="hidden" name="ep" value="'.$ep.'">';
                    echo '<center>
                          <input type="number" class="col-md-offset-1 col-md-10 col-xs-12" min="-500" max="500" name="punteggio" id="score-pg" value="0">
                          </center><br><br>';
                    echo '<input type="hidden" name="numeroRegole" id="numeroRegole" value="0">';
                     echo '<div id="rules-pg"></div>';

                    echo '<input type="submit" class="btn btn-buy" value="INSERISCI">';
                    echo '</div></form>';
                }else{
                    $query = "SELECT * FROM regolepersonaggi RP INNER JOIN vociregolamento V ON V.id=RP.voce WHERE RP.episodio=".$ep." AND RP.personaggio=".$pg;
                    $rules=$mysqli->query($query);
    			    echo '<form method="POST" action="php/panel-scores-process.php?action=1"><div class="panel-scores-box col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                    echo '<img src="img/personaggi/'.$percorso.'" class="col-lg-offset-4 col-md-offset-3 col-sm-offset-2 col-xs-offset-1 img-responsive" id="img-market">';
                    echo '<div class="pgname text-center">'.$nome.'</div>';
                    echo '<div class="row text-center">
                             <div class="col-md-6">'.$firstHalf.'/'.$half.'</div>
                             <div class="col-md-6">'.$secondHalf.'/'.$half.'</div>
                         </div><br>';
                    echo '<input type="hidden" name="pg" value="'.$id.'">';
                    echo '<input type="hidden" name="ep" value="'.$ep.'">';
                    echo '<center><input type="number" class="col-md-offset-1 col-md-10 col-xs-12" min="-500" max="500" name="punteggio" value="'.$riga["punteggio"].'" id="score-pg" readonly></center><br><br>';
                    echo '<div id="rules-pg">';
                    for($i=1;$r=$rules->fetch_assoc();$i++){
                        echo "<div class='row' id='row".$r['voce']."'>";
                        echo "<input type='text' name='voce".$i."' id='".$r['voce']."' value='".$r['nome']."' class='col-md-10' readonly>";
                        echo "<input type='number' name='score".$i."' id='score".$r['voce']."' value='".$r['punteggio']."' class='col-md-2' readonly>";
                        echo "</div>";
                    }
                       echo '<input type="hidden" name="numeroRegole" id="numeroRegole" value="'.($i-1).'">';

                    echo "</div>";
                    echo '<input type="submit" class="btn btn-buy" value="AGGIORNA">';
                    echo '</div></form>';
                }

?>
</div>
</div>

<script>
function unlock(locker,id){
    var target=document.getElementById(id);
    target.disabled=!target.disabled;
    if(locker.className=="fa fa-lock"){
        locker.className="fa fa-unlock-alt";
    }else{
        locker.className="fa fa-lock";
    }
}


function aggiornaPunteggio(){
 var t=document.getElementsByClassName("scores-selector");

    for(var i=0; i<t.length; i++){ //evito che ci siano punteggi che sforano
        var target=t[i];
        var min=parseFloat(target.min);
        var max=parseFloat(target.max);
        var number=parseFloat(t[i].value);
        if(number<min || number>max){
            target.value="";
            number=0;
        }
    }
}
function inserisciRegola(id){
 var nomeRegola = document.getElementById("name"+id).textContent;
 var punteggioRegola = document.getElementById("pt"+id).value;
 var numeroRegole = parseFloat(document.getElementById("numeroRegole").value);
 var counter = numeroRegole;
 if(punteggioRegola=="" && !document.getElementById(id)) return;

 //verifico se la regola in questione va aggiornata o inserita
 if(document.getElementById(id)){ //aggiornamento
    var vecchioPunteggio = document.getElementById("score"+id).value;
    var scope=document.getElementById("score-pg");
     if(punteggioRegola==""){ //elimina la vecchia regola
      punteggioTotale = parseFloat(scope.value)-parseFloat(vecchioPunteggio);
      var riga = document.getElementById("row"+id);
      riga.parentNode.removeChild(riga);
      counter = counter - 1;
      //decremento il numero di regole
      numeroRegole = numeroRegole - 1;
      document.getElementById("numeroRegole").value = numeroRegole;
      document.getElementById("btn"+id).className = "fa fa-plus fa-2x";
     }else{ // aggiorna la vecchia regola
      punteggioTotale = parseFloat(scope.value)-parseFloat(vecchioPunteggio)+parseFloat(punteggioRegola);
      var punteggioRigaBox = document.getElementById("score"+id);
      punteggioRigaBox.value = punteggioRegola; 
      document.getElementById("btn"+id).className = "fa fa-refresh fa-2x";
     }
   
   scope.value=punteggioTotale;
 }else{ //inserimento
     var scope=document.getElementById("score-pg");
     punteggioTotale = parseFloat(scope.value)+parseFloat(punteggioRegola);
     scope.value = punteggioTotale;
     
     
     var scope=document.getElementById("rules-pg");

     var row=document.createElement("div");
     row.className="row";
     row.id = "row"+id;

     counter = counter + 1;
     var nomeRegolaBox = document.createElement("input");
     nomeRegolaBox.type = "text";
     nomeRegolaBox.value = nomeRegola;
     nomeRegolaBox.id = id;
     nomeRegolaBox.className = "col-md-10";
     nomeRegolaBox.name = "voce"+counter;
     nomeRegolaBox.readonly = true;
     row.appendChild(nomeRegolaBox);

     var punteggioRegolaBox = document.createElement("input");
     punteggioRegolaBox.type = "number";
     punteggioRegolaBox.value = punteggioRegola;
     punteggioRegolaBox.id = "score"+id;
     punteggioRegolaBox.className = "col-md-2";
     punteggioRegolaBox.readonly = true;
     punteggioRegolaBox.name = "score"+counter;
     row.appendChild(punteggioRegolaBox);

     scope.appendChild(row);

     //incremento il numero di regole
     numeroRegole = numeroRegole + 1;
     document.getElementById("numeroRegole").value = numeroRegole;

     document.getElementById("btn"+id).className = "fa fa-refresh fa-2x";
 }

}
 jQuery('<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>').insertAfter('.quantity input');
    jQuery('.quantity').each(function() {
      var spinner = jQuery(this),
        input = spinner.find('input[type="number"]'),
        btnUp = spinner.find('.quantity-up'),
        btnDown = spinner.find('.quantity-down'),
        min = input.attr('min'),
        max = input.attr('max');

      btnUp.click(function() {
        if(!input.val())
            var oldValue=parseFloat(min)-1;
        else
            var oldValue=parseFloat(input.val());

        if (oldValue >= max) {
          var newVal = "";
        } else {
          var newVal = oldValue + 1;
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });

      btnDown.click(function() {
        if(!input.val())
            var oldValue=parseFloat(max)+1;
        else
            var oldValue=parseFloat(input.val());
        
        if (oldValue <= min) {
          var newVal = "";
        } else {
          var newVal = oldValue - 1;
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });

    });

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});


</script>