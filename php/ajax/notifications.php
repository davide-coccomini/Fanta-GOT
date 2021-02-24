<?php
include("../config.php");
include("../utility.php");
$query="UPDATE notifiche SET letto = 1 WHERE username='".$_COOKIE['username']."'";
esegui($query,$mysqli);
?>