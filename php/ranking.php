
<div class="bg-blue ranking-content">
 <center> <h1 class="ranking-title">Ranking</h1> </center>
<?php
$rowsPerPage=20;
if(isset($_GET["page"])){
  $page=filtra($_GET["page"],$mysqli);
}else{
  $page=1;
}



$records=($page*$rowsPerPage)-$rowsPerPage;
if(!isset($_GET["s"])){ //giocatori
  //Ricerca del numero di pagine necessarie
      $queryNumero="SELECT COUNT(*) as numero FROM users";
      $row=estrai($queryNumero,$mysqli);
      $pagine=ceil($row["numero"]/$rowsPerPage);
      $paginaUtente = (isset($_COOKIE["posizione"]))?ceil($_COOKIE["posizione"]/$rowsPerPage):ceil($_COOKIE["id"]/$rowsPerPage);
  //Query per la selezione degli N utenti da visualizzare nella pagina
      $query="SELECT * FROM users ORDER BY punteggioNetto DESC LIMIT ".$rowsPerPage." OFFSET ".$records;
      $etichetta='Gruppo';
}else if($_GET["s"]=='g'){ //gruppi
  //Ricerca del numero di pagine necessarie
    $queryNumero="SELECT COUNT(*) as numero FROM gruppi WHERE membri>=3 AND clan <> 'White Walkers' ";
    $row=estrai($queryNumero,$mysqli);
    $pagine=ceil($row["numero"]/$rowsPerPage);
  //Query per la selezione degli N gruppi da visualizzare nella pagina
     $query="SELECT * FROM gruppi G WHERE membri>=3 AND clan <> 'White Walkers' ORDER BY mediaPunteggi DESC LIMIT ".$rowsPerPage." OFFSET ".$records;
     $etichetta=$rankingsTableLabel6;
}else if($_GET["s"]=='gn'){
   //Ricerca del numero di pagine necessarie
    $queryNumero="SELECT COUNT(*) as numero FROM gruppi WHERE clan <> 'White Walkers' ";
    $row=estrai($queryNumero,$mysqli);
    $pagine=ceil($row["numero"]/$rowsPerPage);
  //Query per la selezione degli N gruppi da visualizzare nella pagina
     $query="SELECT * FROM gruppi WHERE clan <> 'White Walkers' ORDER BY mediaPunteggi DESC LIMIT ".$rowsPerPage." OFFSET ".$records;
     $etichetta=$rankingsTableLabel6;
}else if($_GET["s"]=='gs'){ // Gruppi secondari
   //Ricerca del numero di pagine necessarie
   $queryNumero="SELECT COUNT(*) as numero FROM gruppi WHERE clan = 'White Walkers'";
   $row=estrai($queryNumero,$mysqli);
   $pagine=ceil($row["numero"]/$rowsPerPage);
 //Query per la selezione degli N gruppi da visualizzare nella pagina
    $query="SELECT * FROM gruppi WHERE clan = 'White Walkers' ORDER BY mediaPunteggi DESC LIMIT ".$rowsPerPage." OFFSET ".$records;
    $etichetta=$rankingsTableLabel6;
}else if($_GET["s"]=='gsr'){ // Gruppi secondari
  //Ricerca del numero di pagine necessarie
  $queryNumero="SELECT COUNT(*) as numero FROM gruppi WHERE membri>=3 AND clan = 'White Walkers'";
  $row=estrai($queryNumero,$mysqli);
  $pagine=ceil($row["numero"]/$rowsPerPage);
//Query per la selezione degli N gruppi da visualizzare nella pagina
   $query="SELECT * FROM gruppi WHERE membri>=3 AND clan = 'White Walkers' ORDER BY mediaPunteggi DESC LIMIT ".$rowsPerPage." OFFSET ".$records;
   $etichetta=$rankingsTableLabel6;
}
$result=$mysqli->query($query);

     echo '<div class="table-responsive"><table id="tablerank" class="table sortable text-center tableranking">';
     echo "<thead><tr><th>#</th><th>".$rankingsTableLabel1."</th><th>".$rankingsTableLabel2."</th><th>".$rankingsTableLabel3."</th>";
     
     for($i=1; $i<=$totEpisodi; $i++)
      echo "<th>".$i."° ".$episodeText."</th>";     
     echo"<th>".$rankingsTableLabel4."</th><th>".$rankingsTableLabel12."</th><th>".$rankingsTableLabel5."</th><th>".$etichetta."</th></tr></thead>";

     for($i=($page*$rowsPerPage)-$rowsPerPage+1;$row=$result->fetch_assoc();$i++){
      if(!isset($_GET["s"])){ //giocatori
        if(!isset($row["punteggioNetto"])) $punteggio="-";
          else $punteggio=$row["punteggioNetto"];
        $query="SELECT * FROM punteggi WHERE tipologia='mercato' AND username='".$row['username']."'";
        $ris=$mysqli->query($query);
        if($row['username']==$_COOKIE['username'])
          $class = "rowtableranking1";
        else
          $class = "rowtableranking2";

        $titolo = getTitle($row["username"],"utente",$mysqli);

        echo "<tr class='".$class."'><td><b>".$i."</b></td><td>".$row['username']."</td><td>".$titolo."</td><td>".$punteggio."</td>";
              for($j=0;$j<$totEpisodi;$j++){
                $riga=$ris->fetch_assoc();
                nofetch:
                if(!$riga)
                  echo"<td>-</td>";
                else if($riga["episodio"]==$j+1)
                  echo"<td>".$riga['punteggio']."</td>";
                else{
                  echo"<td>-</td>";
                  $j++;
                  goto nofetch;
                }
               }
              echo "<td>".$row['punteggioScommesse']."</td><td>".$row['punteggioArena']."</td><td>".$row['penalita']."</td><td>".$row['gruppo']."</td></tr>";

      }else if($_GET["s"]=='g' || $_GET["s"]=='gn' || $_GET["s"]=="gs" || $_GET["s"]=="gsr"){ //gruppi 
          $query="SELECT SUM(P.punteggio) as totale, P.episodio FROM punteggi P INNER JOIN users U ON U.username=P.username WHERE U.gruppo='".$row['nome']."' GROUP BY P.episodio";
          $ris=$mysqli->query($query);
           if(isset($_COOKIE['gruppo'])){
              if($row['nome']==$_COOKIE['gruppo'])
                $class = "rowtableranking1";
              else
                $class = "rowtableranking2";
           }else
              $class = "rowtableranking2";

            $titolo = getTitle($row["nome"],"gruppo",$mysqli);
            echo "<tr class='".$class."'><td><b>".$i."</b></td><td>".$row['nome']."</td><td>".$titolo."</td><td>".$row['mediaPunteggi']."</td>"; 
             for($j=0;$j<$totEpisodi;$j++){
              $riga=$ris->fetch_assoc();
              $mediaEpisodio=round($riga["totale"]/$row['membri']);
              if(!$riga)
               echo"<td>-</td>";
              else
                echo"<td>".$mediaEpisodio."</td>";
             }
             $scommesseTotale = (isset($row['scommesseTotale']))?$row['scommesseTotale']:"-";
             $penalitaTotale = (isset($row['penalitaTotale']))?$row['penalitaTotale']:"-";
             echo"<td>".$scommesseTotale."</td><td>-</td><td>".$penalitaTotale."</td><td>".$row['clan']."</td></tr>";

      }	
     }

     echo '</table></div>';
     if(isset($_GET["s"]))
      $s="/".filtra($_GET["s"],$mysqli);
     else
      $s = "";
     ?>
<center>
<nav aria-label="Page navigation">
  <ul class="pagination">
    <li class="page-item">
    <?php
    $pageP=$page-1;
    if($pageP>0)
      echo'<a class="page-link" href="/index/ranking'.$s.'/'.$pageP.'" aria-label="Previous">';
    else
      echo'<a class="page-link" href="#" aria-label="Previous">';
    ?>
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>
    <?php
      $range = (isMobile())?1:6;
      for($i=$page-(($page>=$range)?$range:$page)+1; $i<=$pagine && $i <= $page+$range; $i++){

        if($i==$page)
          echo "<li class='page-item'><a class='page-link' id='page-active' href='/index/ranking".$s."/".$i."'>".$i."</a></li>";
        else
          echo "<li class='page-item'><a class='page-link' href='/index/ranking".$s."/".$i."'>".$i."</a></li>";
      } 
    ?>
    <li class="page-item">
    <?php
    $pageN=$page+1;
      if($pageN<=$pagine)
        echo'<a class="page-link" href="/index/ranking'.$s.'/'.$pageN.'" aria-label="Next">';
      else
        echo'<a class="page-link" href="#" aria-label="Next">';

    ?>
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
    <li>
      <?php
      if($page != $pagine && $pagine > 1)
       echo "<a class='page-link' href='/index/ranking".$s."/".$pagine."'>".$pagine."</a>";
      ?>
    </li>
    <li>
      <?php
      if(!isset($_GET["s"]))
        echo '<a class="page-link" id="page-link-user" href="/index/ranking'.$s.'/'.$paginaUtente.'">'.$rankingButton1.'</a>';
      ?>
    </li>
  </ul>
</nav>
<br>

</div>
</center>