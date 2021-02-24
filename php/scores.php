<!-- Modal -->
<div class="modal fade" id="modalScores" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog" id="modal-dialog-rules" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><?php echo $lastScoresTitle1; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php  
        $doc = "";
      



        if($lingua == "EN"){
          $etichetta1 = "Episode's bets";
          $etichetta2 = "VERIFIED";
          $etichetta3 = "UNVERIFIED";
        }else if($lingua == "IT"){
          $etichetta1 = "Scommesse episodio";
          $etichetta2 = "VERIFICATO";
          $etichetta3 = "NON VERIFICATO";
        }
         for($ep=1; $ep<=$punteggiDaMostrare; $ep++){

           // Scommesse

           $doc = $doc."<br><h2>".$etichetta1." ".$ep."</h2><hr style='width:100%'><br>";
           $query="SELECT * FROM scommesse WHERE episodio=".$ep." AND lingua='".$lingua."' AND verificato IS NOT NULL ORDER BY id";
           $resultBets=$mysqli->query($query);
           while($row=$resultBets->fetch_assoc()){	
              if($row["verificato"]==0){
                $status = $etichetta3;
              }else{
                $status = $etichetta2;
              }
                $scommessa = "- ".$row['descrizione'].": <b>".$status."</b><br>";
                $doc=$doc.$scommessa;
            }

            // Punteggi
           
            if($lingua == "IT"){
                 $doc=$doc."<h2>Punteggi episodio ".$ep."</h2><hr style='width:100%'><br>";
                 $tabella="<div class='table-responsive'><table id='tablerank' class='table sortable text-center tableranking'><thead><tr><th>Personaggio</th><th>Dettagli</th><th>Punteggio</th></tr></thead>";
            }else{
                 $doc=$doc."<h2>Episode ".$ep." scores</h2><hr style='width:100%'><br>";
                 $tabella="<div class='table-responsive'><table id='tablerank' class='table sortable text-center tableranking'><thead><tr><th>Character</th><th>Details</th><th>Score</th></tr></thead>";
            }
              $doc=$doc.$tabella;
              $query="SELECT *,PP.punteggio as pt FROM (punteggipersonaggi PP INNER JOIN dettaglipunteggi DP ON DP.idPunteggio=PP.id) INNER JOIN personaggi P ON P.id=PP.personaggio WHERE DP.lingua='".$lingua."' AND PP.episodio=".$ep." ORDER BY P.id";
          
              $result=$mysqli->query($query);
                  while($row=$result->fetch_assoc()){
                    $pg="<tr><td>".$row['nome']."</td><td>".$row['dettagli']."</td><td>".$row['pt']."</td></tr>";
                    $doc=$doc.$pg;
                  }
                  $doc=$doc."</table></div>";
         } 

         echo $doc;
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $closeText; ?></button>
      </div>
    </div>
  </div>
</div>