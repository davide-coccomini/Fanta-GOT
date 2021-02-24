<?php
include("utility.php");
include("config.php");
if (isset($_SERVER['HTTP_COOKIE'])) {//do we have any
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);//get all cookies 
    foreach($cookies as $cookie) {//loop
        $parts = explode('=', $cookie);//get the bits we need
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);//kill it
        setcookie($name, '', time()-1000, '/');//kill it more
    }
}
setcookie("messaggi",1,time()+1000, '/');
inviaLog("logout"," ",$mysqli);
header('location: ../index/home');
nuovoMsg($generalAlert81);
$mysqli->close();
die();
?>