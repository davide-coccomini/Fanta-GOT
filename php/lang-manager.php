<?php

include_once("geolocalization/geoiploc.php");
$lingue = array('IT', 'EN');
$lingua = "EN"; 
if(isset($_COOKIE["login"])){ 
	$query = "SELECT lingua FROM users WHERE username='".$_COOKIE['username']."'";
	$result=$mysqli->query($query);
	$row=$result->fetch_assoc();
	$lingua = $row["lingua"];
}else{
      if(isset($_COOKIE["lingua"]))
            $lingua = $_COOKIE["lingua"];
      else{
            $ip = ottieniIp();
            $lingua = getCountryFromIP($ip);
            if(!$lingua || !in_array($lingua, $lingue)) $lingua = "EN";
      }
}
 switch ($lingua)
      {
         case "IT":
            include "lang/it.php";
            break;
         case "EN":
            include "lang/en.php";
            break;
      }
 
?>