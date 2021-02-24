<!-- Modal -->
<div class="modal fade" id="modalArena" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog" id="modal-dialog-rules" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><?php echo $arenaBattlesTitle1; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php  
        $doc = "";
      
         for($ep=1; $ep<=$punteggiDaMostrare; $ep++){




              // Arena
              if($lingua == "IT"){
                    $doc=$doc."<h2>Sfide arena episodio ".$ep."</h2><hr style='width:100%'><br>";
                    $tabella="<div class='table-responsive'><table id='tablerank' class='table sortable text-center tableranking'><thead><tr><th>Sfidante</th><th>Sfidato</th><th>Regola sfidante</th><th>Regola sfidato</th></tr></thead>";
              }else{
                    $doc=$doc."<h2>Episode ".$ep." arena battles</h2><hr style='width:100%'><br>";
                    $tabella="<div class='table-responsive'><table id='tablerank' class='table sortable text-center tableranking'><thead><tr><th>Challenger</th><th>Challenged</th><th>Challenger's rule</th><th>Challenged's rule</th></tr></thead>";
              }
              $doc=$doc.$tabella;
              $query = "SELECT * FROM sfide WHERE episodio=".$ep." AND status=4";
              $result=$mysqli->query($query);


              while($row=$result->fetch_assoc()){

                $querySfidante = "SELECT nome, punteggioMassimo FROM vociregolamento WHERE lingua='".$lingua."' AND associazione=".$row["regolaSfidante"];
                $r=estrai($querySfidante,$mysqli);
                $regolaSfidante = $r["nome"];
                $punteggioSfidante = round($r["punteggioMassimo"]/2);

                $querySfidato = "SELECT nome, punteggioMassimo FROM vociregolamento WHERE lingua='".$lingua."' AND associazione=".$row["regolaSfidato"];
                $r=estrai($querySfidato,$mysqli);
                $regolaSfidato = $r["nome"];
                $punteggioSfidato = round($r["punteggioMassimo"]/2);
                
                $query = "SELECT VR.punteggioMassimo, COUNT(*) as numero FROM regolepersonaggi RP INNER JOIN vociregolamento VR ON VR.associazione = RP.voce WHERE RP.episodio = ".$ep." AND RP.voce = ".$row['regolaSfidante'];
                $r = estrai($query,$mysqli);
                $statusSfidante = ($r["numero"] > 0)?"fa fa-check":"fa fa-times";

                $query = "SELECT VR.punteggioMassimo, COUNT(*) as numero FROM regolepersonaggi RP INNER JOIN vociregolamento VR ON VR.associazione = RP.voce WHERE RP.episodio = ".$ep." AND RP.voce = ".$row['regolaSfidato'];
                $r = estrai($query,$mysqli);
                $statusSfidato = ($r["numero"] > 0)?"fa fa-check":"fa fa-times";

                $sfida =  "<tr><td>".$row['sfidante']."</td><td>".$row['sfidato']."</td><td><i class='".$statusSfidante."'></i> ".$regolaSfidante." <b>(+".$punteggioSfidante.")</b></td><td><i class='".$statusSfidato."'></i> ".$regolaSfidato." <b>(+".$punteggioSfidato.")</b></td></tr>";
                $doc=$doc.$sfida;
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