<?php
if(!verificaAdmin($mysqli)){
    forzaLogout("php/");
  }
controllaRank(2);

$query="SELECT * FROM contatti ORDER BY timestamp DESC";
$result=$mysqli->query($query);
?>
<div class="container logscontainer">
<center><h3>Contatti</h3></center>
	<?php
     	 while($row=$result->fetch_assoc()){
     	 	$id=$row["id"];
              echo "<div class='row text-center announcement-block'>
                      <div class='col-md-12 col-xs-12 col-sm-12 announcement-title' style='text-align:left'>".$row['oggetto']."<div style='float:right'>".$row['timestamp']."</div></div>
                      <div class='col-md-12 col-xs-12 col-sm-12 announcement-text'>".$row['contenuto']."</div><br><br>
                      <div class='announcement-sign'>".$row['email']."</div>
                    </div>";
                if($_COOKIE["admin"]==3){
                  echo "<a href='php/contact-us-process.php?action=1&id=".$id."'><input type='button' class='btn btn-danger' value='Rimuovi'></a>";
                }
                     

            }
     ?>
</div>