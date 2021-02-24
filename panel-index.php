<?php
include("php/config.php");
include("php/utility.php");
include("php/lang-manager.php");
?>

<html>
  <head>
  <meta name="robots" content="noindex">
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/img/favicon.ico" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-sortable.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/custompanel.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
 	<script src="js/bootstrap.min.js"> </script>
    <script src="js/bootstrap-sortable.js"></script>
    <title>Fanta GOT</title>
</head>
<body>
<?php


include("php/panel-navbar.php");

if(!verificaAdmin($mysqli)){

    inviaLog("[!]Tentativo di accesso al pannello amministrativo","",$mysqli);
		forzaLogout("php/");
}
aggiornaInformazioni($mysqli,0,$utentiDaInvitare);
	 if(isset($_GET["p"]))
	  {
	    $p=$_GET["p"];
	    
	  }else{
	    $p="panel-general";
	  }

	  if(!include("php/".$p.".php")){
		  forzaLogout("php/");
	  }else{
		  include_once("php/".$p.".php");
	  }
	if(!isset($_SESSION["letto"])){
	  $_SESSION["letto"]=0;
	}
	include("php/modal.php");
include("php/panel-handbook.php");

?>

<script>

function fade(id, io, tm)
{
    var el = document.getElementById(id);
  el.style.opacity = 1;

  el.onclick = function(event){ 
    if (event.target == el) {
       el.style.display = 'none';
    }
  }
  
    el.style.transition = "opacity " + tm + "s";
    el.style.WebkitTransition = "opacity " + tm + "s";
}
</script>
</body>
</html>