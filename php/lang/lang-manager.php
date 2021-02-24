<?php
$languages = array(1=>'Italiano', 'English');
$choose = array(1=>'Scegli la lingua', 'Choose language');
$flags = array(1=>'it', 'us'); 
$language = @$_GET['language'];
   if (!$language) $lingua = 2; // default inglese
 
 switch ($lingua)
      {
         case 1:
            include "it.php";
            break;
         case 2:
            include "en.php";
            break;
      }


?>