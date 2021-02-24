<?php
include("../config.php");
include("../utility.php");
$query="UPDATE users SET annunciDaLeggere=0 WHERE username='".$_COOKIE['username']."'";
esegui($query,$mysqli);

?>