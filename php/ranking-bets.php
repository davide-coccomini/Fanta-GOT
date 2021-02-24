<?php

$rowsPerPage=20;

if(isset($_GET["page"])){
  $page=filtra($_GET["page"],$mysqli);
}else{
  $page=1;
}
	
$queryNumero="SELECT COUNT(*) as numero FROM users";
$row=estrai($queryNumero,$mysqli);
$pagine=ceil($row["numero"]/$rowsPerPage);
$records=($page*$rowsPerPage)-$rowsPerPage;
$query="SELECT username, gruppo, titolo,punteggioScommesse FROM users  ORDER BY punteggioScommesse DESC LIMIT ".$rowsPerPage." OFFSET ".$records;
$result=$mysqli->query($query);

echo '<div class="table-responsive"><table id="tablerank" class="table sortable text-center tableranking">';
     echo "<thead><tr><th>#</th><th>".$rankingsTableLabel1."</th><th>".$rankingsTableLabel2."</th><th>".$rankingsTableLabel7."</th><th>".$rankingsTableLabel8."</th></tr></thead>";
		for($i=($page*$rowsPerPage)-$rowsPerPage+1;$row=$result->fetch_assoc();$i++){
       
          if(!isset($row["punteggioScommesse"])) $punteggioTotale="-";
          else $punteggioTotale=$row["punteggioScommesse"];
        
          if($row["username"]==$_COOKIE["username"])
            $class = "rowtableranking1";
          else
            $class = "rowtableranking2";


          echo "<tr class='".$class."'><td><b>".$i."</b></td><td>".$row['username']."</td><td>".$row['titolo']."</td><td>".$punteggioTotale."</td>";
            
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
      echo'<a class="page-link" href="/index/ranking-bets/'.$pageP.'" aria-label="Previous">';
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
          echo "<li class='page-item'><a class='page-link' id='page-active' href='/index/ranking-bets/".$i."'>".$i."</a></li>";
        else
          echo "<li class='page-item'><a class='page-link' href='/index/ranking-bets/".$i."'>".$i."</a></li>";
      } 
    ?>

    <li class="page-item">
    <?php
    $pageN=$page+1;
      if($pageN<=$pagine)
        echo'<a class="page-link" href="/index/ranking-bets/'.$pageN.'" aria-label="Next">';
      else
        echo'<a class="page-link" href="#" aria-label="Next">';
    ?>
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
    <li>
      <?php
      if($page != $pagine && $pagine > 0)
       echo "<a class='page-link' href='/index/ranking-bets/".$pagine."'>".$pagine."</a>";
      ?>
    </li>
  </ul>
</nav>
<br>



</center>