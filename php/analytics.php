

<?php
if($_COOKIE["admin"]==0)
    controllaDonatore();
    
$colors = "";
$data = "";
$labels = "";
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script type="text/javascript" src="js/chart.js"></script>

<div class="container analyticscontent">
  
<?php
  $labels = "";
  $query="SELECT SUM(punteggio) as punteggio,episodio FROM punteggi WHERE username='".$_COOKIE['username']."' GROUP BY episodio ORDER BY episodio ASC";
  $resultEpisodi=$mysqli->query($query);
  $resultPunteggi=$mysqli->query($query);
  $rowEpisodi=$resultEpisodi->fetch_assoc();
  if($rowEpisodi){
    echo '<div class="row">
            <canvas id="graficoPunteggiPersonali" class="col-md-12 col-sm-12 col-xs-12"></canvas>
          </div>';
  }
?>

<script>
var dat,labels;
var canvas = document.getElementById("graficoPunteggiPersonali");
var ctx = canvas.getContext("2d");
</script>


<?php
if($rowEpisodi){
  echo'<script>var dat = {
          labels: [';
        do{ //stampo le labels
          $labels = $labels.",'".$analyticsLabel1." ".$rowEpisodi['episodio']."'";		
        }while($rowEpisodi=$resultEpisodi->fetch_assoc());
      $labels=substr($labels,1);

      echo $labels;
    echo '],';
    echo'  
      datasets: [
              {
                  label: "'.$analyticsLabel2.'",
                  fillColor: "#d49e5e",
                  strokeColor: "#d49e5e",
                  pointColor: "#d49e5e",
                  pointStrokeColor: "#d49e5e",
                  pointHighlightFill: "#d49e5e",
                  pointHighlightStroke: "#d49e5e",
                  backgroundColor: "#d49e5e",
                  data: [';

                  while($rowPunteggi=$resultPunteggi->fetch_assoc()){ //stampo i punteggi
                    $data = $data.",".$rowPunteggi['punteggio'];	
                  }

                $data=substr($data,1);
                echo $data;

                echo'
                ]
          }
      ]
  };
  </script>';
}
?>

 
<script>
 var options= {
      responsive: false,
      maintainAspectRatio: false,
      legend: { display: false },
      title: {
      	maintainAspectRatio: false,
        display: true,
        text: '"<?php echo $analyticsTitle1; ?>"'
      }
    }
var myNewChart = new Chart(ctx , {
    type: "line",
    data: dat, 
    options:options
});

</script>
<div class="row">
<canvas id="schieramentiPersonaggi" class="col-md-6 col-sm-12 col-xs-12"></canvas>

<?php
$labels="";
$data="";
$query="SELECT COUNT(DISTINCT PA.personaggio) as numeroPersonaggi FROM schieramenti S INNER JOIN personaggiacquistati PA ON PA.id=S.acquisto";
$rowPersonaggi=estrai($query,$mysqli);

$query="SELECT COUNT(*) as numero FROM (schieramenti S INNER JOIN personaggiacquistati PA ON PA.id=S.acquisto) INNER JOIN personaggi P ON P.id=PA.personaggio GROUP BY P.nome ORDER BY P.id ASC";
$resultNumero=$mysqli->query($query);

$query="SELECT P.nome FROM (schieramenti S INNER JOIN personaggiacquistati PA ON PA.id=S.acquisto) INNER JOIN personaggi P ON P.id=PA.personaggio GROUP BY P.nome ORDER BY P.id ASC";
$resultNomi=$mysqli->query($query);
echo '<script>new Chart(document.getElementById("schieramentiPersonaggi"), {
    type: "bar",
    data: {
      labels: [';
      while($rowNomi=$resultNomi->fetch_assoc()){ //stampo le labels
				$labels = $labels.",'".$rowNomi['nome']."'";		
			}
		$labels=substr($labels,1);

		echo $labels;

      echo'],
      datasets: [
        {
          label: "'.$analyticsLabel3.'",
          backgroundColor: [';
          for($i=0;$i<$rowPersonaggi["numeroPersonaggi"];$i++){
          	$color=dechex(0xd4a05e);
          	$colors=$colors.",'#".$color."'";
          }
          $colors=substr($colors,1);
		  echo $colors;
  	 echo'],data:[';
        while($rowNumero=$resultNumero->fetch_assoc()){ //stampo i punteggi
			    $data = $data.",".$rowNumero['numero'];	
        }
		  $data=substr($data,1);
		  echo $data;
		echo'  
        ]}
      ]
    },
    options: {
      responsive: false,
      maintainAspectRatio: false,
      legend: { display: false },
      title: {
        display: true,
        text: "'.$analyticsTitle2.'"
      }
    }
});</script>';


?>
<canvas id="acquistiPersonaggi" class="col-md-6 col-sm-12 col-xs-12"></canvas>
</div>
<?php
$labels="";
$data="";
$colors="";
$query="SELECT COUNT(DISTINCT personaggio) as numeroPersonaggi FROM personaggiacquistati";
$rowPersonaggi=estrai($query,$mysqli);

$query="SELECT COUNT(*) as numero,P.nome FROM personaggiacquistati PA INNER JOIN personaggi P ON P.id=PA.personaggio GROUP BY P.nome ORDER BY P.id ASC";
$resultNumero=$mysqli->query($query);
$query="SELECT nome FROM personaggi ORDER BY id ASC";
$resultNomi=$mysqli->query($query);
echo '<script>new Chart(document.getElementById("acquistiPersonaggi"), {
    type: "bar",
    data: {
      labels: [';
      	while($rowNomi=$resultNomi->fetch_assoc()){ //stampo le labels
          $labels = $labels.",'".$rowNomi['nome']."'";		
        }
		$labels=substr($labels,1);

		echo $labels;

      echo'],
      datasets: [
        {
          label: "'.$analyticsLabel1.'",
          backgroundColor: [';
          for($i=0;$i<$rowPersonaggi["numeroPersonaggi"];$i++){
          	$color=dechex(0xd4a05e);
          	$colors=$colors.",'#".$color."'";
          }
          $colors=substr($colors,1);
		  echo $colors;
  	 echo'],data:[';
    		while($rowNumero=$resultNumero->fetch_assoc()){ //stampo i punteggi
			  $data = $data.",".$rowNumero['numero'];	

			}
		  $data=substr($data,1);
		  echo $data;
		echo']}
      ]
    },
    options: {
      responsive: false,
      maintainAspectRatio: false,
      legend: { display: false },
      title: {
      	maintainAspectRatio: false,
        display: true,
        text: "'.$analyticsTitle3.'"
      }
    }
});</script>';

?>


<div class="row">
<canvas id="punteggiPersonaggi" class="col-md-12 col-sm-12 col-xs-12">></canvas>
</div>
<?php
$labels="";
$data="";
$colors="";
$query="SELECT COUNT(DISTINCT personaggio) as numeroPersonaggi FROM punteggipersonaggi";
$rowPersonaggi=estrai($query,$mysqli);
$query="SELECT SUM(PP.punteggio) as totale,P.nome FROM punteggipersonaggi PP INNER JOIN personaggi P ON P.id=PP.personaggio GROUP BY P.nome ORDER BY P.id ASC";
$resultNumero=$mysqli->query($query);
$query="SELECT nome FROM personaggi ORDER BY nome ASC";
$resultNomi=$mysqli->query($query);
echo '<script>new Chart(document.getElementById("punteggiPersonaggi"), {
    type: "bar",
    data: {
      labels: [';
      	while($rowNomi=$resultNomi->fetch_assoc()){ //stampo le labels
				$labels = $labels.",'".$rowNomi['nome']."'";		
			}
		$labels=substr($labels,1);

		echo $labels;

      echo'],
      datasets: [
        {
          label: "'.$analyticsLabel1.'",
          backgroundColor: [';
          for($i=0;$i<$rowPersonaggi["numeroPersonaggi"];$i++){
          	$color=dechex(0xd4a05e);
          	$colors=$colors.",'#".$color."'";
          }
          $colors=substr($colors,1);
		  echo $colors;
  	 echo'],data:[';
    		while($rowNumero=$resultNumero->fetch_assoc()){ //stampo i punteggi
			  $data = $data.",".$rowNumero['totale'];	

			}
		  $data=substr($data,1);
		  echo $data;
		echo']}
      ]
    },
    options: {
      responsive: false,
      maintainAspectRatio: false,
      legend: { display: false },
      title: {
      	maintainAspectRatio: false,
        display: true,
        text: "'.$analyticsTitle4.'"
      }
    }
});</script>';

?>

<div class="row">

<canvas id="scommesse" class="col-md-4 col-sm-4 col-xs-12"></canvas>

<?php
$labels="";
$data="";
$colors="";
$query="SELECT COUNT(distinct S.id) as numeroScommesse FROM scommesse S INNER JOIN scommesseeffettuate SE ON SE.scommessa=S.id WHERE S.episodio=".$episodioCorrente;
$rowScommesse=estrai($query,$mysqli);
$query="SELECT COUNT(*) as numero,S.descrizione FROM scommesseeffettuate SE INNER JOIN scommesse S ON S.id=SE.scommessa WHERE S.episodio=".$episodioCorrente." GROUP BY S.descrizione ORDER BY S.id ASC";
$resultNumero=$mysqli->query($query);

$query="SELECT descrizione FROM scommesse WHERE episodio=".$episodioCorrente." ORDER BY id ASC";
$resultNomi=$mysqli->query($query);

echo '<script>new Chart(document.getElementById("scommesse"), {
    type: "doughnut",
    data: {
      labels: [';
      	while($rowNomi=$resultNomi->fetch_assoc()){ //stampo le labels
				$labels = $labels.',"'.$rowNomi['descrizione'].'"';		
			}
		$labels=substr($labels,1);

		echo $labels;

      echo'],
      datasets: [
        {
          label: "'.$analyticsLabel4.'",
          backgroundColor: [';
          for($i=0;$i<$rowScommesse["numeroScommesse"];$i++){
          	$color=dechex(0xd4a05e);
          	$colors=$colors.",'#".$color."'";
          }
          $colors=substr($colors,1);
		  echo $colors;
  	 echo'],data:[';
    		while($rowNumero=$resultNumero->fetch_assoc()){ //stampo i punteggi
			    $data = $data.",".$rowNumero['numero'];	
			  }
		  $data=substr($data,1);
		  echo $data;
		echo']}
      ]
    },
    options: {
      responsive: false,
      maintainAspectRatio: false,
      legend: { display: false },
      title: {
        display: true,
        text: "'.$analyticsTitle5.'"
      }
    }
});</script>';

?>

<canvas id="gruppiPerClan" class="col-md-4 col-sm-4 col-xs-12"></canvas>

<?php
$labels="";
$data="";
$colors="";
$query="SELECT COUNT(DISTINCT clan) as numeroClan FROM gruppi";
$rowNumeroClan=estrai($query,$mysqli);
$query="SELECT COUNT(*) as numeroGruppi FROM gruppi GROUP BY clan ORDER BY clan ASC";
$resultNumero=$mysqli->query($query);

$query="SELECT clan FROM gruppi GROUP BY clan ORDER BY clan ASC";
$resultNomi=$mysqli->query($query);

echo '<script>new Chart(document.getElementById("gruppiPerClan"), {
    type: "doughnut",
    data: {
      labels: [';
      	while($rowNomi=$resultNomi->fetch_assoc()){ //stampo le labels
				$labels = $labels.',"'.$rowNomi['clan'].'"';		
			}
		$labels=substr($labels,1);

		echo $labels;

      echo'],
      datasets: [
        {
          label: "'.$analyticsLabel5.'",
          backgroundColor: [';
          for($i=0;$i<$rowNumeroClan["numeroClan"];$i++){
          	$color=dechex(0xd4a05e);
          	$colors=$colors.",'#".$color."'";
          }
          $colors=substr($colors,1);
		  echo $colors;
  	 echo'],data:[';
    		while($rowNumero=$resultNumero->fetch_assoc()){ //stampo i punteggi
			  $data = $data.",".$rowNumero['numeroGruppi'];	

			}
		  $data=substr($data,1);
		  echo $data;
		echo']}
      ]
    },
    options: {
      responsive: false,
      maintainAspectRatio: false,
      legend: { display: false },
      title: {
        display: true,
        text: "'.$analyticsTitle6.'"
      }
    }
});</script>';

?>



<canvas id="membriPerClan" class="col-md-4 col-sm-4 col-xs-12"></canvas>
</div>
<?php
$labels="";
$data="";
$colors="";
$query="SELECT SUM(membri) as membriTotali FROM gruppi GROUP BY clan ORDER BY clan ASC";
$resultNumero=$mysqli->query($query);

$query="SELECT clan FROM gruppi GROUP BY clan ORDER BY clan ASC";
$resultNomi=$mysqli->query($query);

echo '<script>new Chart(document.getElementById("membriPerClan"), {
    type: "doughnut",
    data: {
      labels: [';
      	while($rowNomi=$resultNomi->fetch_assoc()){ //stampo le labels
				$labels = $labels.',"'.$rowNomi['clan'].'"';		
			}
		$labels=substr($labels,1);

		echo $labels;

      echo'],
      datasets: [
        {
          label: "'.$analyticsLabel6.'",
          backgroundColor: [';
          for($i=0;$i<$rowNumeroClan["numeroClan"];$i++){
          	$color=dechex(0xd4a05e);
          	$colors=$colors.",'#".$color."'";
          }
          $colors=substr($colors,1);
		  echo $colors;
  	 echo'],data:[';
    		while($rowNumero=$resultNumero->fetch_assoc()){ //stampo i membri
			  $data = $data.",".$rowNumero['membriTotali'];	

			}
		  $data=substr($data,1);
		  echo $data;
		echo']}
      ]
    },
    options: {
      responsive: false,
      maintainAspectRatio: false,
      legend: { display: false },
      title: {
        display: true,
        text: "Membri per clan"
      }
    }
});</script>';

?>
<br><br>
</div>