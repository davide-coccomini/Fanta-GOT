<?php

if(!verificaAdmin($mysqli)){
		forzaLogout("php/");
	}
controllaRank(3);

$query="SELECT * FROM bugs ORDER BY timestamp DESC";
$result=$mysqli->query($query);
?>

<div class="container logscontainer">
<form action="php/bug-report-process.php?action=3">
<input type="text" name="username" placeholder="Username">
<textarea name="testo" placeholder="Testo"></textarea>
<input type="submit" value="Invia notifica">
</form>
<div>
<center><br><br><i class="fa fa-lock" onclick="unlock(this,'buttonResetAll');"  aria-hidden="true"></i></center>
<center><a href='php/bug-report-process.php?action=1'><input type='button' class='btn btn-danger' id='buttonResetAll' value='Rimuovi tutto' disabled></a></center>

<center><h3>Segnalazioni</h3></center>
	<?php
     	 while($row=$result->fetch_assoc()){
     	 	$id=$row["id"];
              echo "<div class='row text-center announcement-block'>
                      <div class='col-md-12 col-xs-12 col-sm-12 announcement-title' style='text-align:left'>".$row['titolo']."<div style='float:right'>".$row['timestamp']."</div></div>
                      <div class='col-md-12 col-xs-12 col-sm-12 announcement-text'>".$row['descrizione']."</div><br><br>
                      <div class='announcement-sign'>".$row['username']."</div>
                      
                    </div>
                    <a href='php/bug-report-process.php?action=0&id=".$id."'><input type='button' class='btn btn-danger' value='Rimuovi'></a>";
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

</script>

