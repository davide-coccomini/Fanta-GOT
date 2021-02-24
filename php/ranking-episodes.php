<?php

$rowsPerPage=30;

if(!isset($_GET["ep"]))
 $ep = 1;
else
 $ep = filtra($_GET["ep"],$mysqli);

if(isset($_GET["page"])){
  $page=filtra($_GET["page"],$mysqli);
}else{
  $page=1;
}
	
?>
<div id="ranking-header">
<nav aria-label="Page navigation">
  <ul class="pagination">
    <li class="page-item">
    	<h2><?php echo $navbarSubTitle8; ?></h2>
<?php
     for($i=1;$i<=$episodioCorrente;$i++){
       if($i==$ep)
        echo "<li class='page-item'><a class='page-link' id='page-active' href='/index/ranking-episodes/".$i."/".$page."'>".$i."</a></li>";
       else
      	echo "<li class='page-item'><a class='page-link' href='/index/ranking-episodes/".$i."/".$page."'>".$i."</a></li>";
     }   
?>
  	</li>
  </ul>
</nav>
</div>
<?php
$queryNumero="SELECT COUNT(*) as numero FROM users U INNER JOIN punteggi P ON P.username=U.username WHERE P.episodio = ".$ep." GROUP BY P.username ORDER BY SUM(P.punteggio)";

$row=estrai($queryNumero,$mysqli);
$pagine=ceil($row["numero"]/$rowsPerPage);
$records=($page*$rowsPerPage)-$rowsPerPage;
$query="SELECT U.username, SUM(P.punteggio) as somma, U.gruppo, U.titolo FROM users U INNER JOIN punteggi P ON P.username=U.username WHERE P.episodio = ".$ep." GROUP BY P.username ORDER BY SUM(P.punteggio)  DESC LIMIT ".$rowsPerPage." OFFSET ".$records;
$result=$mysqli->query($query);

echo '<div class="table-responsive"><table id="tablerank" class="table sortable text-center tableranking">';
     echo "<thead><tr><th>#</th><th>".$rankingsTableLabel1."</th><th>".$rankingsTableLabel2."</th><th>".$rankingsTableLabel3."</th><th>".$rankingsTableLabel9."</th><th>".$rankingsTableLabel10."</th><th>".$rankingsTableLabel8."</th></tr></thead>";
		for($i=($page*$rowsPerPage)-$rowsPerPage+1;$row=$result->fetch_assoc();$i++){
       
          if(!isset($row["somma"])) $punteggioTotale="-";
          else $punteggioTotale=$row["somma"];
          if($row['username']==$_COOKIE['username'])
            $class = "rowtableranking1";
          else
            $class = "rowtableranking2";

          echo "<tr class='".$class."'><td><b>".$i."</b></td><td>".$row['username']."</td><td>".$row['titolo']."</td><td>".$punteggioTotale."</td>";
          $query="SELECT punteggio FROM punteggi WHERE tipologia='mercato' AND username='".$row['username']."' AND episodio = ".$ep;
          $ris=$mysqli->query($query);
          $riga=$ris->fetch_assoc();
              if(!$riga)
               echo"<td>-</td>";
              else
                echo"<td>".$riga['punteggio']."</td>";
          $query="SELECT punteggio FROM punteggi WHERE tipologia='scommessa' AND username='".$row['username']."' AND episodio = ".$ep;
          $ris=$mysqli->query($query);
          $riga=$ris->fetch_assoc();
              if(!$riga)
               echo"<td>-</td>";
              else
                echo"<td>".$riga['punteggio']."</td>";
            
          echo "<td>".$row['gruppo']."</td></tr>";
      }

      echo "</table></div>";



?>


<center>
<nav aria-label="Page navigation">
  <ul class="pagination">
    <li class="page-item">
    <?php
    $pageP=$page-1;
    if($pageP>0)
      echo'<a class="page-link" href="/index/ranking-episodes/'.$pageP.'" aria-label="Previous">';
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
          echo "<li class='page-item'><a class='page-link' id='page-active' href=/index/ranking-episodes/".$ep."/".$i.">".$i."</a></li>";
        else
          echo "<li class='page-item'><a class='page-link' href=/index/ranking-episodes/".$ep."/".$i.">".$i."</a></li>";
      } 
    ?>
    <li class="page-item">
    <?php
    $pageN=$page+1;
      if($pageN<=$pagine)
        echo'<a class="page-link" href="index/ranking-episodes/'.$ep.'/'.$pageN.'" aria-label="Next">';
      else
        echo'<a class="page-link" href="#" aria-label="Next">';
    ?>
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
    <li>
      <?php
      if($page != $pagine && $pagine>0)
       echo "<a class='page-link' href='/index/ranking-bets/".$pagine."'>".$pagine."</a>";
      ?>
    </li>
  </ul>
</nav>
</center>