<?php

controllaRank(3);

	if(!isset($_GET["l"])){
		$language = $lingue[0];
	}else{
		$language = filtra($_GET["l"],$mysqli);
	}
$query = "SELECT COUNT(*) as numero FROM users";
$row = estrai($query,$mysqli);
$users = $row["numero"];

$query = "SELECT SUM(numero) as emails FROM newsletters GROUP BY DATE(timestamp)";
$row = estrai($query,$mysqli);
$inviate = ($row["emails"]!="")?$row["emails"]:0;

?>


<div class="content">
<center>
<?php

	foreach($lingue as $l){
		if($l!=$language){
			echo'<a href="panel-index.php?p=panel-email&l='.$l.'"><img src="../img/flags/'.$l.'.png" class="flag"></a>';
		}else{
			echo'<a href="panel-index.php?p=panel-email&l='.$l.'"><img src="../img/flags/'.$l.'.png" class="flag cur-flag"></a>';
		}
	}
?>
</center>
<div class="row">

	<center><h3>Estrai le email degli utenti (<?php echo $inviate."/3000"; ?>)</h3>
	<div class="col-md-8 col-md-offset-2">
	<div class="row">
	<input type="number" class="form-control text-center" id="min" placeholder="min" value="1">
	<input type="number" class="form-control text-center" id="max" placeholder="max" <?php echo "value='".$users."'"; ?>>
	<center><input type="button" class="btn btn-primary" onclick="getEmail()" value="Ottieni"></center><br>
	</div>
	<textarea class="form-control" rows="20" id="emailsResult"></textarea>

	</div>
</div>


<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<form method="POST">
			<center><h3>Invia un'email a tutti gli utenti</h3>
			<input type="text" id="oggettoBroadcast" class="form-control" name="oggetto" placeholder="Oggetto">
			<textarea class="form-control"  id="testoBroadcast"  name="testo" placeholder="Testo" rows="20"></textarea>
			<input type="hidden" class="form-control" id="linguaBroadcast"  name="lingua" <?php echo "value='".$language."'";  ?>>
			<input type="button" class="btn btn-primary" value="Invia" onclick="sendEmail(2)"></center>
		</form><br><br>
	</div>
</div>


<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<form method="POST">
			<center><h3>Invia un'email ad un utente</h3>
			<input type="text" class="form-control" id="email" name="email" placeholder="Email">
			<input type="text" class="form-control" id="oggetto" name="oggetto" placeholder="Oggetto">
			<textarea class="form-control" name="testo" id="testo" placeholder="Testo" rows="20"></textarea>
			<input type="hidden" class="form-control" name="lingua" id="lingua" <?php echo "value='".$language."'";  ?>>
			<input type="button" class="btn btn-primary" onclick="sendEmail(1)" value="Invia"></center>
		</form><br><br>
	</div>
</div>
<div id="results" style="overflow: auto; max-height:300px; text-align:center;" class="col-md-8 col-md-offset-2">
<center>
<div class="lds-ring" id="loading"><div></div><div></div><div></div><div></div><br><br><br>Sending</div><br>
</center>
</div>
<br><br><br>
<script>
function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}
function getEmail(){
	var lingua = $("#linguaBroadcast").val();
	var min = $("#min").val();
	var max = $("#max").val();

	$.post("php/ajax/emails.php", { action: 1, lingua: lingua, min: min, max: max},
				function(data) {
					emails = $.parseJSON(data);
					
					$("#emailsResult").empty();
					emails.forEach(function(email) {
						$('#emailsResult').append(email+" ");
					})
				});
}
function sendEmail(action){
	$("#loading").show();
	
	if(action == 0){ // broadcast
		var oggetto = $("#oggettoBroadcast").val();
		var testo = $("#testoBroadcast").val();
		var lingua = $("#linguaBroadcast").val();
		var inviate = 0;
		var emails;
		// SELEZIONE DELLE EMAIL 
		$.post("php/ajax/emails.php", { action: 0, lingua: lingua },
				function(data) {
					emails = $.parseJSON(data);
					delay = 1000;
					emails.forEach(function(email) {
						console.log(inviate+") Sending ... "+email)
						
						inviate = inviate + 1;
						if(inviate%20 == 0) 
							delay = delay + 15000;
						else
							delay = delay + 500;
						setTimeout(function(){
													$.post("php/panel-email-process.php?action=1", { email: email, oggetto: oggetto, testo: testo, lingua: lingua },
												function(data) {
													$('#results').append("<br>("+inviate+") Email inviata a: "+email);
												});

						},delay)
					
					});
		  $("#loading").hide();
		});
	
	}else if(action == 1){ // email singola
		var oggetto = $("#oggetto").val();
		var email = $("#email").val();
		var testo = $("#testo").val();
		var lingua = $("#lingua").val();
		var min = 0;
		var max = 0;
		$.post("php/panel-email-process.php?action=1", { email: email, oggetto: oggetto, testo: testo, lingua: lingua },
		function(data) {
			$("#loading").hide();
			$('#results').html(data);
		});
	}else if(action == 2){ // group broadcast
		var oggetto = $("#oggettoBroadcast").val();
		var testo = $("#testoBroadcast").val();
		var lingua = $("#linguaBroadcast").val();
		var inviate = 0;
		var emails;
		$.post("php/ajax/emails.php", { lingua: lingua },
				function(data) {
					emails = $.parseJSON(data);
					delay = 1000;
					step = 50;
					for(current = 0; current < emails.length; current = current + step){
						min = current;
						max = current + step;
						console.log("Invio delle email da "+ min + " a " + max);
				
						setTimeout(function(){

													$.post("php/panel-email-process.php?action=2", { oggetto: oggetto, testo: testo, lingua: lingua, min: min, max: max },
												function(data) {
													console.log("Email inviate");
												});

						},delay)
						delay = delay + 60000;
					}
		
		  $("#loading").hide();
		});
	}
}
</script>

<style>
.lds-ring {
  display: none;
  position: relative;
  width: 64px;
  height: 64px;
}
.lds-ring div {
  box-sizing: border-box;
  display: block;
  position: absolute;
  width: 51px;
  height: 51px;
  margin: 6px;
  border: 6px solid black;
  border-radius: 50%;
  animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  border-color: black transparent transparent transparent;
}
.lds-ring div:nth-child(1) {
  animation-delay: -0.45s;
}
.lds-ring div:nth-child(2) {
  animation-delay: -0.3s;
}
.lds-ring div:nth-child(3) {
  animation-delay: -0.15s;
}
@keyframes lds-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

</style>