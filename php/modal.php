<?php
if(((isset($_GET["m"]))||(isset($_SESSION['testoMsg']))) && isset($_SESSION["letto"]))
{	
		if($_SESSION["letto"]==0){
			if(isset($_COOKIE["messaggi"])){
				if($_COOKIE["messaggi"]==1){
					$testo = (isset($_GET['m']))?$_GET['m']:$_SESSION["testoMsg"];
					leggiMsg(); 
					if(isset($_COOKIE["primoLogin"]) && $_COOKIE["primoLogin"]==1){ 
					    	setcookie("primoLogin",0,time() + (86400 * 30), "/");
					    	$function = "fade('messagecontent','out',0.5)";
					    	echo "<div id='messagecontent' style='opacity:0;' onclick='".$function."'><div id='messagebox' class='col-md-5'>";
					    	
					    	echo "<div onclick='".$function."'>";
					    	echo"<div class='col-md-1 col-md-offset-11 col-xs-1 col-xs-offset-11 modaltop'><span id='close' aria-hidden='true'>&times;</span></div></div><img src='/img/logo.png' id='messageimg'><p>".$testo."</p><center>";
							echo '<center><a data-toggle="modal" data-target="#modal" href="#"><b>'.$howToPlay.'</b></a><br><div id="socialicons"><div class="col-md-12 col-xs-12 social-networks"><a href="https://twitter.com/Fanta_Vikings/" target="_blank" class="twitter"><i class="fa fa-twitter-square fa-3x"></i></a><a href="https://www.facebook.com/fantagotofficial/" target="_blank" class="facebook"><i class="fa fa-facebook-official fa-3x" style="margin-left:3px"></i></a></div></div></center><br>';
							echo "</div></div>";
							echo "<script>setTimeout(function(){fade('messagecontent','in',1.5)},500);</script>";
							$query="UPDATE users SET primoLogin=0 WHERE username='".$_COOKIE['username']."'";
							esegui($query,$mysqli);
					}else{
						echo '<div class="alert alert-info alert-autocloseable-info col-md-4 col-md-offset-8 col-xs-11 col-xs-offset-0">';
						echo '<button type="button" class="close">×</button>';
						echo $testo;
						
						if(isset($_COOKIE["login"])){
							  if(isset($_GET["p"]))
							  {
							    $page=$_GET["p"];  
							  }else{
							    $page="market";
							  }
							echo "<br>".$generalAlert120." <a href='/php/unablemessage.php?pa=".$page."'>".$capitalClickHere."</a></center><br>";
						}
						echo '</div>';
						echo "<script> 
							  $('.alert-autocloseable-info').hide();
							  $('.alert-autocloseable-info').fadeIn('slow');
						      $('.alert-autocloseable-info').delay(7000).fadeOut('slow');
							  </script>";
			
					}
			}
		}
	}
}
?>

<script>
	$(document).on('click', '.close', function () {
			$(this).parent().hide();
    	});

</script>