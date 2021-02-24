
<?php
	if(isset($_GET["p"]))
	{
		$page=$_GET["p"];  
	}else{
		$page="home";
	}
	echo "<div class='row flag-container'>";
    foreach($lingue as $l){
            echo '<div class="flag-content"><a href="/php/change-language.php?l='.$l.'&pa='.$page.'"><img src="/img/flags/'.$l.'.png" class="flag-home"></a></div>'; 
	}
	echo "</div>";
?>


<?php

include("php/login-page.php");
include("php/registration-page.php");
include("php/password-recover.php");


?>


 <!--<video autoplay loop>
        <source src="/media/index_video.mp4" type="video/mp4">
        <source src="/media/index_video.webm" type="video/webm">
        <source src="/media/index_video.ogv" type="video/ogg">
 </video>-->


<script>

function changeSection(sezione){
	if(sezione==0){ //switcha su login
		$("#registration-content").fadeOut(200);
		$("#login-content").delay(200).fadeIn(100);
		$("#passwordrecover-content").fadeOut(200);
	}else if(sezione==1){ //switcha su registrazione
		$("#registration-content" ).delay(200).fadeIn(500);
		$("#login-content").fadeOut(200);
		$("#passwordrecover-content").fadeOut(200);
	}else{ //switcha su recupera password
		$("#registration-content" ).fadeOut(200);
		$("#login-content").fadeOut(200);
		$("#passwordrecover-content").delay(200).fadeIn(500);
	}
}
function goToSection(sezione)
{
	if(sezione==0){ //switcha su login
		$("#registration-content" ).hide();
		$("#login-content").show();
		$("#passwordrecover-content").hide();
	}else if(sezione==1){ //switcha su registrazione
		$("#registration-content" ).show();
		$("#login-content").hide();
		$("#passwordrecover-content").hide();
	}else{ //switcha su recupera password
		$("#registration-content" ).hide();
		$("#login-content").hide();
		$("#passwordrecover-content").show();
	}	
}

</script>

<?php
if(isset($_GET["sec"])){
	echo "<script>goToSection(".$_GET['sec'].");</script>";
}
?>