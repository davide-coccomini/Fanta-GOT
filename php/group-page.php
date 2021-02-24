<?php

	verificaAppartenenzaGruppo($_COOKIE["username"],$mysqli);
	if(!isset($_COOKIE["gruppo"])){
		header('location: /index/group-creation');
	    nuovoMsg($generalAlert39);
		$mysqli->close();
		die();
	}

?>


<div class="row">
	
		<?php
		 include("php/group-sidebar.php");
		?>
	<div class="col-md-9 col-xs-12">
		<?php
			if($membri<3){
				echo '<div class="infobox alert alert-info col-md-6 col-md-offset-3">
					  '.$groupPageAlert1.' <b><a href="https://www.facebook.com/groups/1940185119330465/" class="red-text" target="_blank">'.$capitalClickHere.'</a></b>
					    </div>';
			}
		?>
		<div class="col-md-12 col-xs-12 groupmembers">
			<?php

				 $queryDaEseguire="SELECT * FROM users U WHERE U.gruppo='".$nome."' ORDER BY U.punteggioNetto DESC";
				 $result=$mysqli->query($queryDaEseguire);
			     echo '<div class="table-responsive" ><table class="table text-center sortable tableranking">';
				 echo "<thead><tr class='headtablemembers'><th>#</th><th>".$rankingsTableLabel1."</th><th>".$rankingsTableLabel3."</th>";
				 
				 for($i=1; $i<=$totEpisodi; $i++)
    				  echo "<th>".$i."° ".$episodeText."</th>"; 
				 
				 echo "<th>".$rankingsTableLabel4."</th><th>".$rankingsTableLabel5."</th>";
				 
			     if($capo==1){
			     	echo "<th>Azioni</th>";
			     }
			     echo "</tr></thead>";
			     for($i=1;$row=$result->fetch_assoc();$i++){
			     	if(!isset($row["punteggioNetto"])) $punteggio="-";
          			else $punteggio=$row["punteggioNetto"];
				    $query="SELECT SUM(punteggio) as totale FROM punteggi WHERE username='".$row['username']."' AND tipologia<>'scommessa' GROUP BY episodio";
	        		$ris=$mysqli->query($query);
	       
               echo "<tr class='rowtableranking2'><td><b>".$i."</b></td><td>".$row['username']."</td><td>".$punteggio."</td>";
             for($j=0;$j<$totEpisodi;$j++){
              $riga=$ris->fetch_assoc();
              if(!$riga)
               echo"<td>-</td>";
              else
                echo"<td>".$riga['totale']."</td>";
             }
             echo "<td>".$row['punteggioScommesse']."</td><td>".$row['penalita']."</td>";
			     		if($capo==1 && $row["username"]!=$_COOKIE["username"]){
			     			if($episodioCorrente>1){
			     				if($row["segnalato"]==0){
			     					echo "<td><a href='../php/group-process.php?action=5&u=".$row['username']."'><button class='btn btn-warning btn-kick'>".$groupPageButton1."</button></a></td>";
			     				}else{
			     					echo "<td><a href='../php/group-process.php?action=5&u=".$row['username']."'><button class='btn btn-warning btn-kick'>".$groupPageButton2."</button></a></td>";
			     				}
			     			
			     			}else{
			     				echo "<td><a href='../php/group-process.php?action=4&u=".$row['username']."'><button class='btn btn-danger btn-kick'>".$groupPageButton3."</button></a></td>";	
			     			}
			     		
			     		}else if($capo==1  && $row["username"]==$_COOKIE["username"]){
			     			echo "<td></td>";
			     		}
			     		echo "</tr>";
			     	
			     	
			     }
			     echo '</table></div>';
			?>
		</div>
	</div>
</div>